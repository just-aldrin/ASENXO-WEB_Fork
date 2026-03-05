<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ASENXO | MSME Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    :root { --accent: #2ecc71; --bg: #0d0d0d; --card: #1a1a1a; --border: #222; }
    
    /* Global: No double scrollbars */
    html, body { margin: 0; padding: 0; height: 100%; background: var(--bg); color: #fff; font-family: 'Inter', sans-serif; overflow-x: hidden; }
    
    .app { display: flex; flex-direction: column; min-height: 100vh; }

    /* Header: Flush to top */
    .top-header { 
      height: 60px; background: #000; border-bottom: 1px solid var(--border);
      display: flex; align-items: center; justify-content: space-between; padding: 0 20px;
      position: sticky; top: 0; z-index: 100;
    }

    .content-row { display: flex; flex: 1; align-items: stretch; }

    /* Sidebar: Flush to left and top (under header) */
    .sidebar { 
      width: 240px; background: #000; border-right: 1px solid var(--border);
      position: sticky; top: 60px; height: calc(100vh - 60px); padding-top: 20px;
    }

    /* Main: Normal flow, no internal scrollbar */
    .main-content { flex: 1; padding: 30px; }

    /* Right Column: Sticky stats */
    .info-column { 
      width: 280px; padding: 30px 20px; position: sticky; top: 60px; 
      height: fit-content; display: flex; flex-direction: column; gap: 15px;
    }

    /* UI Styling */
    .nav-item { padding: 12px 25px; color: #666; font-size: 13px; display: flex; align-items: center; gap: 12px; cursor: pointer; }
    .nav-item.active { color: var(--accent); background: rgba(46, 204, 113, 0.05); border-left: 3px solid var(--accent); font-weight: 700; }
    
    .step-card { background: var(--card); border: 1px solid var(--border); border-radius: 10px; margin-bottom: 15px; overflow: hidden; }
    .step-header { padding: 15px 20px; display: flex; align-items: center; gap: 15px; }
    .step-content { padding: 20px; background: #141414; border-top: 1px solid var(--border); }
    
    .stat-card { background: var(--card); border: 1px solid var(--border); border-radius: 10px; padding: 20px; }
    .progress-bar { height: 6px; background: #222; border-radius: 3px; margin-top: 12px; }
    .progress-fill { height: 100%; background: var(--accent); width: 0%; transition: 0.5s; }

    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .field label { display: block; font-size: 9px; color: #555; text-transform: uppercase; font-weight: 800; margin-bottom: 5px; }
    .field input, .field select { width: 100%; padding: 10px; background: #000; border: 1px solid var(--border); color: #fff; border-radius: 5px; font-size: 12px; box-sizing: border-box; }
    
    .btn-save { width: 100%; background: var(--accent); color: #000; border: none; padding: 12px; border-radius: 5px; font-weight: 800; cursor: pointer; margin-top: 15px; }
  </style>
</head>
<body>
  <div class="app">
    <header class="top-header">
      <div style="display:flex; align-items:center; gap:10px;">
        <span style="font-weight:900; letter-spacing:-0.5px; font-size:20px;">ASENXO</span>
        <span style="font-size:9px; background:#1a3324; color:var(--accent); padding:2px 8px; border-radius:4px; font-weight:800;">PRODUCTION</span>
      </div>
      <button onclick="logout()" style="background:#e74c3c; color:#fff; border:none; padding:6px 15px; border-radius:4px; font-weight:700; cursor:pointer;">Logout</button>
    </header>

    <div class="content-row">
      <aside class="sidebar">
        <div style="padding: 0 25px 20px; display:flex; align-items:center; gap:12px;">
          <div style="width:35px; height:35px; background:var(--accent); border-radius:50%;"></div>
          <div id="sideName" style="font-size:13px; font-weight:700;">Loading...</div>
        </div>
        <div class="nav-item active"><i class="fas fa-th-large"></i> Dashboard</div>
        <div class="nav-item"><i class="fas fa-file-alt"></i> Applications</div>
        <div class="nav-item"><i class="fas fa-cog"></i> Settings</div>
      </aside>

      <main class="main-content">
        <h2 style="margin-top:0; font-weight:800;">Application Progress</h2>
        <div id="steps-container"></div>
      </main>

      <aside class="info-column">
        <div class="stat-card">
          <div style="font-size:10px; font-weight:800; color:#555;">COMPLETION STATUS</div>
          <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-top:10px;">
            <span id="percText" style="font-size:28px; font-weight:900;">0%</span>
          </div>
          <div class="progress-bar"><div id="fillBar" class="progress-fill"></div></div>
        </div>
        <div class="stat-card">
          <div style="font-size:10px; font-weight:800; color:#555;">FILE REPOSITORY</div>
          <div style="margin-top:10px; font-size:12px; display:flex; justify-content:space-between;">
            <span>Files Uploaded:</span><span style="font-weight:700;">0</span>
          </div>
        </div>
      </aside>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
  <script>
    // Constants
    const SUPA_URL = 'https://hmxrblblcpbikkxcwwni.supabase.co';
    const SUPA_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhteHJibGJsY3BiaWtreGN3d25pIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzIyODY0MDksImV4cCI6MjA4Nzg2MjQwOX0.qC4Lm2KbToc0f1syHpMWJmQqRhQTosNfFzBrfTXSWDw'; 

    let client;
    let user;
    let currentStep = 3;

    async function init() {
      // FIX: Renamed variable to 'client' to avoid library conflict
      client = supabase.createClient(SUPA_URL, SUPA_KEY);
      
      const { data: { user: u } } = await client.auth.getUser();
      if(!u) { window.location.href='login.php'; return; }
      user = u;

      const { data: profile } = await client.from('user_profiles').select('*').eq('id', user.id).single();
      if(profile) {
        document.getElementById('sideName').innerText = profile.first_name + ' ' + profile.last_name;
        currentStep = profile.current_step || 3;
        render();
      }
    }

    function render() {
      const p = Math.round((currentStep / 6) * 100);
      document.getElementById('fillBar').style.width = p + '%';
      document.getElementById('percText').innerText = p + '%';

      const steps = [
        { id: 1, name: "Account Initialized" },
        { id: 2, name: "Security Check" },
        { id: 3, name: "Owner Information" },
        { id: 4, name: "ID Verification" }
      ];

      document.getElementById('steps-container').innerHTML = steps.map(s => `
        <div class="step-card">
          <div class="step-header">
            <div style="width:24px; height:24px; border-radius:50%; background:${s.id <= currentStep ? 'var(--accent)' : '#222'}; color:#000; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:900;">
              ${s.id < currentStep ? '✓' : s.id}
            </div>
            <div style="font-size:14px; font-weight:700; color:${s.id <= currentStep ? '#fff' : '#444'}">${s.name}</div>
          </div>
          ${s.id === currentStep ? `<div class="step-content">${getForm()}</div>` : ''}
        </div>
      `).join('');
    }

    function getForm() {
      return `
        <div class="form-grid">
          <div class="field"><label>Nickname</label><input id="f_nick"></div>
          <div class="field"><label>Birth Date</label><input type="date" id="f_dob"></div>
          <div class="field"><label>Sex</label><select id="f_sex"><option>Male</option><option>Female</option></select></div>
          <div class="field"><label>Enterprise</label><input id="f_ent"></div>
        </div>
        <button class="btn-save" onclick="save()">Save & Continue</button>
      `;
    }

    async function save() {
      const payload = {
        owner_ID: user.id, // Must match the "owner_ID" column in SQL
        owner_nickname: document.getElementById('f_nick').value,
        owner_dob: document.getElementById('f_dob').value,
        owner_sex: document.getElementById('f_sex').value,
        enterprise_name: document.getElementById('f_ent').value
      };

      const { error } = await client.from('owner_profile').upsert([payload]);
      
      if(error) {
        alert("Policy Error: Ensure you ran the SQL fix for 'owner_ID'");
        console.error(error);
      } else {
        currentStep++;
        await client.from('user_profiles').update({ current_step: currentStep }).eq('id', user.id);
        render();
      }
    }

    function logout() { client.auth.signOut().then(() => window.location.href='login.php'); }
    window.onload = init;
  </script>
</body>
</html>