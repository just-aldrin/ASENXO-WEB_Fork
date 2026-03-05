<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ASENXO | MSME Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="src/css/msme-home-style.css">
  <style>
    :root { --accent: #2ecc71; }
    
    /* Layout Logic */
    .content-row { display: flex; padding: 10px; gap: 12px; align-items: flex-start; }
    
    /* Sidebar Navigation Restored */
    .sidebar { width: 190px; flex-shrink: 0; }
    .sidebar-nav-item { 
      display: flex; align-items: center; gap: 10px; padding: 8px 10px; 
      font-size: 11px; color: #888; border-radius: 4px; cursor: pointer; transition: 0.2s; margin-bottom: 2px;
    }
    .sidebar-nav-item.active { background: rgba(46, 204, 113, 0.15); color: var(--accent); font-weight: 600; }
    .sidebar-nav-item i { width: 14px; text-align: center; }
    .sidebar-nav-item:hover:not(.active) { background: rgba(255,255,255,0.05); color: #fff; }

    /* Sticky Info Column */
    .info-column { 
      position: sticky; 
      top: 10px; 
      width: 230px; 
      display: flex; 
      flex-direction: column; 
      gap: 10px; 
    }

    /* Ultra-Compact Stepper UI */
    .progress-column { flex: 1; }
    .step-item { background: var(--card-bg); border: 1px solid #333; border-radius: 6px; margin-bottom: 4px; overflow: hidden; }
    .step-header { padding: 4px 10px; display: flex; align-items: center; gap: 10px; cursor: pointer; }
    .step-icon { width: 18px; height: 18px; border-radius: 50%; background: #262626; display: flex; align-items: center; justify-content: center; font-size: 8px; font-weight: 800; }
    
    .step-item.active { border-color: var(--accent); }
    .step-item.active .step-icon { background: var(--accent); color: white; }
    .step-item.completed .step-icon { background: var(--accent); color: white; }
    
    .step-title { font-weight: 700; font-size: 10.5px; line-height: 1; margin-bottom: 2px; }
    .step-desc { font-size: 8.5px; color: #666; display: block; }
    
    .step-content { display: none; padding: 8px 10px 10px 38px; border-top: 1px solid #262626; }
    .step-item.active .step-content { display: block; }

    /* Form High-Density Squeeze */
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4px; }
    .input-group label { display: block; font-size: 7.5px; color: #777; text-transform: uppercase; font-weight: 800; margin-bottom: 1px; }
    .input-group input, .input-group select { width: 100%; padding: 3px 6px; background: #000; border: 1px solid #333; color: white; border-radius: 2px; font-size: 10px; }
    .input-group input:disabled { opacity: 0.5; border-style: dashed; }

    /* Theme Toggle */
    .theme-btn { 
      background: none; border: 1px solid #444; color: #888; 
      padding: 3px 8px; border-radius: 4px; cursor: pointer; font-size: 11px; 
      display: flex; align-items: center; gap: 5px; 
    }
  </style>
</head>
<body class="dark">
<div class="app">
  <div class="top-header">
    <div class="top-header-left">
      <span class="project-name" style="font-weight: 800; font-size: 14px; letter-spacing: -0.5px;">ASENXO</span>
      <span class="badge production">ACTIVE</span>
    </div>
    <div class="top-header-right" style="display: flex; gap: 8px; align-items: center;">
      <button class="theme-btn" id="themeToggle"><i class="fas fa-moon"></i></button>
      <button onclick="logout()" style="background:#ef4444; color:white; border:none; padding:4px 10px; border-radius:4px; font-size:10px; font-weight:700; cursor:pointer;">Logout</button>
    </div>
  </div>

  <div class="content-row">
    <div class="sidebar">
      <div class="user-profile-box" style="margin-bottom: 10px;">
        <div class="user-avatar"><i class="fas fa-user"></i></div>
        <div style="overflow:hidden">
          <div class="user-name-sidebar" id="sidebarUserName">Loading...</div>
          <div style="font-size:8px; color:#666; font-weight:700;">VERIFIED OWNER</div>
        </div>
      </div>
      
      <div style="font-size: 8px; color: #555; font-weight: 800; margin-bottom: 6px; padding: 0 10px;">NAVIGATION</div>
      <div class="sidebar-nav-item active"><i class="fas fa-home"></i> Dashboard</div>
      <div class="sidebar-nav-item"><i class="fas fa-file-contract"></i> Applications</div>
      <div class="sidebar-nav-item"><i class="fas fa-wallet"></i> My Wallet</div>
      <div class="sidebar-nav-item"><i class="fas fa-chart-line"></i> Analytics</div>
      <div class="sidebar-nav-item"><i class="fas fa-cog"></i> Settings</div>
    </div>

    <div class="progress-column">
      <ul class="step-list" id="middleStepList"></ul>
    </div>

    <div class="info-column">
      <div class="card" style="padding:10px; background:var(--card-bg); border:1px solid #333; border-radius:8px;">
        <div style="font-size:8px; color:#777; font-weight:800; text-transform:uppercase; margin-bottom:6px;">Profile Progress</div>
        <div style="display:flex; justify-content:space-between; align-items:flex-end;">
          <span id="progressPercent" style="font-size:16px; font-weight:900; color:white; line-height:1;">0%</span>
          <span style="font-size:8px; color:#555;">COMPLETION</span>
        </div>
        <div class="progress-bar-bg" style="height:3px; background:#222; border-radius:2px; margin-top:8px;">
          <div class="progress-bar-fill" id="progressBar" style="width:0%; height:100%; background:var(--accent); transition:0.5s;"></div>
        </div>
      </div>

      <div class="card" style="padding:10px; background:var(--card-bg); border:1px solid #333; border-radius:8px;">
        <div style="font-size:8px; color:#777; font-weight:800; text-transform:uppercase; margin-bottom:6px;">System Info</div>
        <div style="display:flex; flex-direction:column; gap:4px;">
          <div style="display:flex; justify-content:space-between;"><span style="font-size:8px; color:#555;">ENGINE</span><span style="font-size:8px; color:var(--accent); font-weight:800;">ONLINE</span></div>
          <div style="display:flex; justify-content:space-between;"><span style="font-size:8px; color:#555;">SECURITY</span><span style="font-size:8px; color:var(--accent); font-weight:800;">SSL-SECURE</span></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
<script>
  // 1. CONFIG (Renamed variable to avoid conflicts)
  const SB_URL = 'https://hmxrblblcpbikkxcwwni.supabase.co';
  const SB_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhteHJibGJsY3BiaWtreGN3d25pIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzIyODY0MDksImV4cCI6MjA4Nzg2MjQwOX0.qC4Lm2KbToc0f1syHpMWJmQqRhQTosNfFzBrfTXSWDw'; 

  let supabaseClient;
  let user = null;
  let profile = null;
  let currentStep = 2;

  const steps = [
    { id: 1, title: "Account Initialized", desc: "MSME Type set" },
    { id: 2, title: "Identity Security", desc: "Email check-in" },
    { id: 3, title: "Owner Profile", desc: "Personal data submission" },
    { id: 4, title: "ID Verification", desc: "Government ID scan" },
    { id: 5, title: "Business Info", desc: "TIN & DTI registration" },
    { id: 6, title: "Legal Permits", desc: "Upload requirements" }
  ];

  async function init() {
    try {
      // Create client using specific variable name
      supabaseClient = supabase.createClient(SB_URL, SB_KEY, {
        global: { headers: { 'apikey': SB_KEY, 'Authorization': `Bearer ${SB_KEY}` } }
      });

      const { data: { user: u } } = await supabaseClient.auth.getUser();
      if (!u) { window.location.href = 'login.php'; return; }
      user = u;

      const { data: p } = await supabaseClient.from('user_profiles').select('*').eq('id', user.id).single();
      if (p) {
        profile = p;
        document.getElementById('sidebarUserName').innerText = p.first_name + ' ' + p.last_name;
        currentStep = p.current_step || 2;
        render();
      }
    } catch (e) { console.error("Init Error:", e); }
  }

  function render() {
    const p = Math.round((currentStep / steps.length) * 100);
    document.getElementById('progressBar').style.width = p + '%';
    document.getElementById('progressPercent').innerText = p + '%';
    
    document.getElementById('middleStepList').innerHTML = steps.map(s => {
      const active = s.id === currentStep;
      const done = s.id < currentStep;
      return `
        <li class="step-item ${active ? 'active' : ''}">
          <div class="step-header">
            <div class="step-icon">${done ? '<i class="fas fa-check"></i>' : s.id}</div>
            <div>
              <div class="step-title" style="color:${done ? 'var(--accent)' : (active ? 'white' : '#444')}">${s.title}</div>
              <span class="step-desc">${s.desc}</span>
            </div>
          </div>
          <div class="step-content">${getForm(s.id)}</div>
        </li>`;
    }).join('');
  }

  function getForm(id) {
    if (id === 2) return `<button class="primary-btn" style="width:180px" onclick="next()">Confirm Email Access</button>`;
    if (id === 3 && profile) {
      return `
      <div class="form-grid">
        <div class="input-group"><label>Owner ID</label><input value="${user.id}" disabled></div>
        <div class="input-group"><label>Full Name</label><input value="${profile.first_name} ${profile.last_name}" disabled></div>
        <div class="input-group"><label>Nickname</label><input id="o_nick"></div>
        <div class="input-group"><label>DOB</label><input type="date" id="o_dob"></div>
        <div class="input-group"><label>Birth Place</label><input id="o_pob"></div>
        <div class="input-group"><label>Nationality</label><input id="o_nat" value="Filipino"></div>
        <div class="input-group"><label>Sex</label><select id="o_sex"><option>Male</option><option>Female</option></select></div>
        <div class="input-group"><label>Status</label><input id="o_mar"></div>
        <div class="input-group"><label>Spouse</label><input id="o_spo"></div>
        <div class="input-group"><label>Contact</label><input id="o_pho"></div>
        <div class="input-group" style="grid-column: span 2;"><label>Full Address</label><input id="o_adr"></div>
        <div class="input-group"><label>Enterprise</label><input id="o_ent"></div>
        <div class="input-group"><label>Position</label><input id="o_des"></div>
      </div>
      <button class="primary-btn" id="sBtn" onclick="save()">
        <i class="fas fa-circle-notch fa-spin" style="display:none" id="spn"></i> <span>Save & Continue</span>
      </button>`;
    }
    return `<p style="font-size:8px; color:#444; margin:10px 0;">Pending next milestone...</p>`;
  }

  async function save() {
    document.getElementById('spn').style.display = 'inline-block';
    const d = {
      owner_ID: user.id,
      owner_name: profile.first_name + ' ' + profile.last_name,
      owner_nickname: document.getElementById('o_nick').value,
      owner_dob: document.getElementById('o_dob').value,
      owner_pob: document.getElementById('o_pob').value,
      owner_nationality: document.getElementById('o_nat').value,
      owner_sex: document.getElementById('o_sex').value,
      owner_marstat: document.getElementById('o_mar').value,
      owner_spouse: document.getElementById('o_spo').value,
      owner_contactnum: document.getElementById('o_pho').value,
      owner_address: document.getElementById('o_adr').value,
      owner_email: user.email,
      enterprise_name: document.getElementById('o_ent').value,
      enterprise_designation: document.getElementById('o_des').value
    };
    const { error } = await supabaseClient.from('owner_profile').upsert([d]);
    if (!error) next(); else { alert(error.message); document.getElementById('spn').style.display = 'none'; }
  }

  async function next() {
    currentStep++;
    await supabaseClient.from('user_profiles').update({ current_step: currentStep }).eq('id', user.id);
    render();
  }

  // THEME TOGGLE
  document.getElementById('themeToggle').addEventListener('click', function() {
    document.body.classList.toggle('dark');
    const icon = this.querySelector('i');
    icon.className = document.body.classList.contains('dark') ? 'fas fa-sun' : 'fas fa-moon';
  });

  function logout() { supabaseClient.auth.signOut().then(() => window.location.href = 'login.php'); }
  window.onload = init;
</script>
</body>
</html>