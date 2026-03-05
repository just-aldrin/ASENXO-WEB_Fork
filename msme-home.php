<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ASENXO | MSME Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="src/css/msme-home-style.css">
  <style>
    :root { --accent: #2ecc71; --card: #1a1a1a; --bg: #0d0d0d; }
    
    /* Reset Global Scroll - Only the main body should scroll */
    html, body { height: 100%; margin: 0; padding: 0; overflow-x: hidden; background: var(--bg); }
    
    /* Fix Sidebar and Header Spacing */
    .app { display: flex; flex-direction: column; min-height: 100vh; }
    
    .top-header { 
      height: 50px; display: flex; align-items: center; justify-content: space-between; 
      padding: 0 15px; background: #000; border-bottom: 1px solid #222; position: sticky; top: 0; z-index: 100;
    }

    .content-row { display: flex; align-items: flex-start; padding: 0; gap: 0; flex: 1; }

    /* Left Sidebar - Flush to left/top */
    .sidebar { 
      width: 220px; min-height: calc(100vh - 50px); background: #000; 
      border-right: 1px solid #222; padding: 15px 10px; position: sticky; top: 50px;
    }

    /* Middle Content - No internal scrollbar */
    .main-content { flex: 1; padding: 20px; display: flex; flex-direction: column; gap: 10px; }

    /* Right Column - Sticky with no scroll */
    .info-column { 
      width: 260px; padding: 20px 15px; position: sticky; top: 50px; 
      display: flex; flex-direction: column; gap: 12px;
    }

    /* UI Elements */
    .step-card { background: var(--card); border: 1px solid #333; border-radius: 8px; margin-bottom: 10px; }
    .step-header { padding: 12px 15px; display: flex; align-items: center; gap: 12px; cursor: pointer; }
    .step-content { display: none; padding: 15px; border-top: 1px solid #262626; }
    .active .step-content { display: block; }

    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .field label { display: block; font-size: 8px; color: #777; text-transform: uppercase; font-weight: 800; margin-bottom: 3px; }
    .field input, .field select { width: 100%; padding: 6px; background: #000; border: 1px solid #333; color: white; border-radius: 4px; font-size: 11px; }

    .stat-card { background: var(--card); border: 1px solid #333; border-radius: 8px; padding: 15px; }
    .btn-save { background: var(--accent); color: #000; border: none; padding: 8px; border-radius: 4px; font-weight: 800; width: 100%; cursor: pointer; margin-top: 10px; }

    /* Navigation */
    .nav-item { display: flex; align-items: center; gap: 10px; padding: 8px 12px; color: #888; border-radius: 6px; font-size: 12px; cursor: pointer; }
    .nav-item.active { background: rgba(46, 204, 113, 0.1); color: var(--accent); font-weight: 700; }
  </style>
</head>
<body>
  <div class="app">
    <header class="top-header">
      <div style="display:flex; align-items:center; gap:10px;">
        <span style="font-weight:900; letter-spacing:-0.5px; color:#fff;">ASENXO</span>
        <span style="font-size:8px; background:#1a3324; color:var(--accent); padding:2px 6px; border-radius:4px; font-weight:800;">PRODUCTION</span>
      </div>
      <button onclick="logout()" style="background:#e74c3c; color:#fff; border:none; padding:4px 12px; border-radius:4px; font-size:10px; font-weight:700; cursor:pointer;">Logout</button>
    </header>

    <div class="content-row">
      <aside class="sidebar">
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px; padding:0 5px;">
          <div style="width:32px; height:32px; background:var(--accent); border-radius:50%; display:flex; align-items:center; justify-content:center; color:#000;"><i class="fas fa-user"></i></div>
          <div style="overflow:hidden;"><div id="sideName" style="font-size:12px; font-weight:700; color:#fff;">Loading...</div></div>
        </div>
        <div style="font-size:8px; color:#444; font-weight:800; margin-bottom:10px; padding-left:12px;">WORKSPACE</div>
        <nav>
          <div class="nav-item active"><i class="fas fa-th-large"></i> Dashboard</div>
          <div class="nav-item"><i class="fas fa-file-invoice"></i> Applications</div>
          <div class="nav-item"><i class="fas fa-wallet"></i> My Wallet</div>
          <div class="nav-item"><i class="fas fa-cog"></i> Settings</div>
        </nav>
      </aside>

      <main class="main-content" id="stepWrapper"></main>

      <aside class="info-column">
        <div class="stat-card">
          <div style="font-size:8px; font-weight:800; color:#555; text-transform:uppercase;">Overall Progress</div>
          <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-top:5px;">
            <span id="progText" style="font-size:22px; font-weight:900; color:#fff;">0%</span>
            <span style="font-size:9px; color:var(--accent); font-weight:800;">ACTIVE</span>
          </div>
          <div style="height:4px; background:#222; border-radius:2px; margin-top:8px;">
            <div id="progBar" style="width:0%; height:100%; background:var(--accent); transition:0.4s;"></div>
          </div>
        </div>

        <div class="stat-card">
          <div style="font-size:8px; font-weight:800; color:#555; text-transform:uppercase;">File Repository</div>
          <div style="margin-top:10px; display:flex; flex-direction:column; gap:6px;">
            <div style="display:flex; justify-content:space-between; font-size:10px;"><span style="color:#666;">FILES UPLOADED</span><span style="color:#fff; font-weight:700;">0</span></div>
            <div style="display:flex; justify-content:space-between; font-size:10px;"><span style="color:#666;">PENDING REVIEW</span><span style="color:#fff; font-weight:700;">0</span></div>
          </div>
        </div>
      </aside>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
  <script>
    // FIX: Renamed variable to avoid 'already declared' error
    const SB_URL = 'https://hmxrblblcpbikkxcwwni.supabase.co';
    const SB_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhteHJibGJsY3BiaWtreGN3d25pIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzIyODY0MDksImV4cCI6MjA4Nzg2MjQwOX0.qC4Lm2KbToc0f1syHpMWJmQqRhQTosNfFzBrfTXSWDw';

    let supabaseClient; 
    let currentUser = null;
    let stepCount = 3;

    const steps = [
      { id: 1, title: "Account Selection", desc: "Type chosen" },
      { id: 2, title: "Email Verification", desc: "Identity check" },
      { id: 3, title: "Owner Information", desc: "Detailed personal data" },
      { id: 4, title: "ID Verification", desc: "Official Documentation" }
    ];

    async function startApp() {
      supabaseClient = supabase.createClient(SB_URL, SB_KEY);
      const { data: { user } } = await supabaseClient.auth.getUser();
      if (!user) { window.location.href = 'login.php'; return; }
      currentUser = user;

      const { data: p } = await supabaseClient.from('user_profiles').select('*').eq('id', user.id).single();
      if (p) {
        document.getElementById('sideName').innerText = p.first_name + ' ' + p.last_name;
        stepCount = p.current_step || 3;
        drawUI();
      }
    }

    function drawUI() {
      const p = Math.round((stepCount / steps.length) * 100);
      document.getElementById('progBar').style.width = p + '%';
      document.getElementById('progText').innerText = p + '%';

      document.getElementById('stepWrapper').innerHTML = steps.map(s => `
        <div class="step-card ${s.id === stepCount ? 'active' : ''}">
          <div class="step-header">
            <div style="width:20px; height:20px; border-radius:50%; background:${s.id <= stepCount ? 'var(--accent)' : '#262626'}; color:#000; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:900;">
              ${s.id < stepCount ? '<i class="fas fa-check"></i>' : s.id}
            </div>
            <div>
              <div style="font-size:12px; font-weight:700; color:#fff;">${s.title}</div>
              <div style="font-size:9px; color:#555;">${s.desc}</div>
            </div>
          </div>
          <div class="step-content">${s.id === 3 ? getProfileForm() : '<p style="color:#444; font-size:10px;">Step locked.</p>'}</div>
        </div>
      `).join('');
    }

    function getProfileForm() {
      return `
      <div class="form-grid">
        <div class="field"><label>Nickname</label><input id="o_nick"></div>
        <div class="field"><label>Birth Date</label><input type="date" id="o_dob"></div>
        <div class="field"><label>Place of Birth</label><input id="o_pob"></div>
        <div class="field"><label>Nationality</label><input id="o_nat" value="Filipino"></div>
        <div class="field"><label>Sex</label><select id="o_sex"><option>Male</option><option>Female</option></select></div>
        <div class="field"><label>Marital Status</label><input id="o_mar"></div>
        <div class="field" style="grid-column: span 2;"><label>Full Address</label><input id="o_adr"></div>
        <div class="field"><label>Enterprise</label><input id="o_ent"></div>
        <div class="field"><label>Position</label><input id="o_des"></div>
      </div>
      <button class="btn-save" onclick="saveData()">Save & Continue</button>`;
    }

    async function saveData() {
      const d = {
        owner_ID: currentUser.id,
        owner_nickname: document.getElementById('o_nick').value,
        owner_dob: document.getElementById('o_dob').value,
        owner_pob: document.getElementById('o_pob').value,
        owner_nationality: document.getElementById('o_nat').value,
        owner_sex: document.getElementById('o_sex').value,
        owner_marstat: document.getElementById('o_mar').value,
        owner_address: document.getElementById('o_adr').value,
        enterprise_name: document.getElementById('o_ent').value,
        enterprise_designation: document.getElementById('o_des').value
      };

      const { error } = await supabaseClient.from('owner_profile').upsert([d]);
      if (error) {
        alert("Policy Error: Enable RLS 'INSERT' and 'UPDATE' for Authenticated users on table 'owner_profile'.");
      } else {
        stepCount++;
        await supabaseClient.from('user_profiles').update({ current_step: stepCount }).eq('id', currentUser.id);
        drawUI();
      }
    }

    function logout() { supabaseClient.auth.signOut().then(() => window.location.href = 'login.php'); }
    window.onload = startApp;
  </script>
</body>
</html>