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
    
    /* Sticky Info Column */
    .info-column { 
      position: sticky; 
      top: 10px; 
      width: 240px; 
      display: flex; 
      flex-direction: column; 
      gap: 10px; 
    }

    /* Sidebar Refinements */
    .sidebar { width: 200px; flex-shrink: 0; }
    .sidebar-nav-item { 
      display: flex; align-items: center; gap: 10px; padding: 6px 10px; 
      font-size: 11px; color: #888; border-radius: 4px; cursor: pointer; transition: 0.2s;
    }
    .sidebar-nav-item.active { background: rgba(46, 204, 113, 0.1); color: var(--accent); font-weight: 600; }
    .sidebar-nav-item:hover:not(.active) { background: rgba(255,255,255,0.05); }

    /* Ultra-Compact Stepper */
    .progress-column { flex: 1; }
    .step-item { background: var(--card-bg); border: 1px solid #333; border-radius: 6px; margin-bottom: 5px; overflow: hidden; }
    .step-header { padding: 5px 12px; display: flex; align-items: center; gap: 10px; cursor: pointer; }
    .step-icon { width: 20px; height: 20px; border-radius: 50%; background: #262626; display: flex; align-items: center; justify-content: center; font-size: 9px; font-weight: 800; }
    
    .step-item.active { border-color: var(--accent); }
    .step-item.active .step-icon { background: var(--accent); color: white; }
    .step-item.completed .step-icon { background: var(--accent); color: white; }
    
    .step-title { font-weight: 700; font-size: 11px; line-height: 1; }
    .step-desc { font-size: 9px; color: #666; margin-top: 1px; display: block; }
    
    .step-content { display: none; padding: 8px 12px 12px 42px; border-top: 1px solid #262626; }
    .step-item.active .step-content { display: block; }

    /* Form Squeeze */
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
    .input-group label { display: block; font-size: 8px; color: #777; text-transform: uppercase; font-weight: 800; margin-bottom: 2px; }
    .input-group input, .input-group select { width: 100%; padding: 5px 8px; background: #000; border: 1px solid #333; color: white; border-radius: 3px; font-size: 11px; }
    .input-group input:disabled { opacity: 0.5; border-style: dashed; }

    /* Theme Toggle Style */
    .theme-btn { 
      background: none; border: 1px solid #444; color: #888; 
      padding: 4px 8px; border-radius: 4px; cursor: pointer; font-size: 11px; 
      display: flex; align-items: center; gap: 5px; transition: 0.2s;
    }
  </style>
</head>
<body class="dark">
<div class="app">
  <div class="top-header">
    <div class="top-header-left">
      <span class="project-name" style="font-weight: 800; font-size: 14px; letter-spacing: -0.5px;">ASENXO</span>
      <span class="badge production">PRODUCTION</span>
    </div>
    <div class="top-header-right" style="display: flex; gap: 8px; align-items: center;">
      <button class="theme-btn" id="themeToggle"><i class="fas fa-moon"></i></button>
      <button onclick="logout()" style="background:#ef4444; color:white; border:none; padding:4px 10px; border-radius:4px; font-size:10px; font-weight:700; cursor:pointer;">Logout</button>
    </div>
  </div>

  <div class="content-row">
    <div class="sidebar">
      <div class="user-profile-box">
        <div class="user-avatar"><i class="fas fa-user"></i></div>
        <div style="overflow:hidden">
          <div class="user-name-sidebar" id="sidebarUserName">Loading...</div>
          <div style="font-size:8px; color:#666;">MSME OWNER</div>
        </div>
      </div>
      
      <div style="padding: 10px; display:flex; flex-direction:column; gap:4px;">
        <div style="font-size: 8px; color: #555; font-weight: 800; margin-bottom: 4px; letter-spacing: 0.5px;">WORKSPACE</div>
        <div class="sidebar-nav-item active"><i class="fas fa-home"></i> Dashboard</div>
        <div class="sidebar-nav-item"><i class="fas fa-file-invoice"></i> Applications</div>
        <div class="sidebar-nav-item"><i class="fas fa-wallet"></i> Wallet</div>
        <div class="sidebar-nav-item"><i class="fas fa-cog"></i> Settings</div>
      </div>
    </div>

    <div class="progress-column">
      <ul class="step-list" id="middleStepList"></ul>
    </div>

    <div class="info-column">
      <div class="card" style="padding:12px; background:var(--card-bg); border:1px solid #333; border-radius:8px;">
        <div style="font-size:9px; color:#777; font-weight:800; text-transform:uppercase; margin-bottom:8px;">Profile Completion</div>
        <div style="display:flex; justify-content:space-between; align-items:center;">
          <span style="font-size:10px; color:#aaa;">Overall Status</span>
          <span id="progressPercent" style="font-size:14px; font-weight:800; color:white;">0%</span>
        </div>
        <div class="progress-bar-bg" style="height:4px; background:#222; border-radius:2px; margin-top:6px;">
          <div class="progress-bar-fill" id="progressBar" style="width:0%; height:100%; background:var(--accent); transition:0.4s;"></div>
        </div>
      </div>

      <div class="card" style="padding:12px; background:var(--card-bg); border:1px solid #333; border-radius:8px;">
        <div style="font-size:9px; color:#777; font-weight:800; text-transform:uppercase; margin-bottom:8px;">System Status</div>
        <div style="display:flex; flex-direction:column; gap:6px;">
          <div style="display:flex; justify-content:space-between;"><span style="font-size:9px; color:#666;">API ENGINE</span><span style="font-size:9px; color:var(--accent); font-weight:700;">STABLE</span></div>
          <div style="display:flex; justify-content:space-between;"><span style="font-size:9px; color:#666;">DATABASE</span><span style="font-size:9px; color:var(--accent); font-weight:700;">CONNECTED</span></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
<script>
  const S_URL = 'https://hmxrblblcpbikkxcwwni.supabase.co';
  const S_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhteHJibGJsY3BiaWtreGN3d25pIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzIyODY0MDksImV4cCI6MjA4Nzg2MjQwOX0.qC4Lm2KbToc0f1syHpMWJmQqRhQTosNfFzBrfTXSWDw'; 

  let supabaseClient;
  let user = null;
  let profile = null;
  let currentStep = 2;

  const steps = [
    { id: 1, title: "Account Initialized", desc: "Entity type selected" },
    { id: 2, title: "Identity Security", desc: "Confirm email access" },
    { id: 3, title: "Owner Profile", desc: "Personal information & history" },
    { id: 4, title: "ID Verification", desc: "Upload government photo ID" },
    { id: 5, title: "Business Information", desc: "TIN, DTI, & Tax details" },
    { id: 6, title: "Final Documentation", desc: "Permits & legal uploads" }
  ];

  async function init() {
    try {
      supabaseClient = supabase.createClient(S_URL, S_KEY);
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
    } catch (e) { console.error(e); }
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
              <div class="step-title" style="color:${done ? 'var(--accent)' : (active ? 'white' : '#555')}">${s.title}</div>
              <span class="step-desc">${s.desc}</span>
            </div>
          </div>
          <div class="step-content">${getForm(s.id)}</div>
        </li>`;
    }).join('');
  }

  function getForm(id) {
    if (id === 2) return `<button class="primary-btn" style="width:200px" onclick="next()">Confirm Verification</button>`;
    if (id === 3 && profile) {
      return `
      <div class="form-grid">
        <div class="input-group"><label>Owner ID</label><input value="${user.id}" disabled></div>
        <div class="input-group"><label>First Name</label><input value="${profile.first_name}" disabled></div>
        <div class="input-group"><label>Last Name</label><input value="${profile.last_name}" disabled></div>
        <div class="input-group"><label>Email</label><input value="${user.email}" disabled></div>
        
        <div class="input-group"><label>Nickname</label><input id="o_nick" placeholder="e.g. AJ"></div>
        <div class="input-group"><label>Date of Birth</label><input type="date" id="o_dob"></div>
        <div class="input-group"><label>Place of Birth</label><input id="o_pob"></div>
        <div class="input-group"><label>Nationality</label><input id="o_nat" value="Filipino"></div>
        
        <div class="input-group"><label>Sex</label>
          <select id="o_sex"><option>Male</option><option>Female</option></select>
        </div>
        <div class="input-group"><label>Marital Status</label>
          <select id="o_mar"><option>Single</option><option>Married</option><option>Widowed</option><option>Separated</option></select>
        </div>
        
        <div class="input-group"><label>Spouse Name</label><input id="o_spo" placeholder="N/A if single"></div>
        <div class="input-group"><label>Contact Number</label><input id="o_pho" placeholder="09xxxxxxxxx"></div>
        <div class="input-group" style="grid-column: span 2;"><label>Home Address</label><input id="o_adr"></div>
        
        <div class="input-group"><label>Enterprise Name</label><input id="o_ent"></div>
        <div class="input-group"><label>Designation</label><input id="o_des" placeholder="e.g. CEO / Owner"></div>
        
        <div class="input-group"><label>Educational Attainment</label>
          <select id="o_hea">
            <option>Elementary</option><option>High School</option><option>Vocational</option><option>College Graduate</option><option>Post-Graduate</option>
          </select>
        </div>
        <div class="input-group"><label>Affiliations</label><input id="o_aff" placeholder="Organizations/Clubs"></div>
      </div>
      
      <button class="primary-btn" id="sBtn" style="margin-top:10px" onclick="save()">
        <i class="fas fa-circle-notch fa-spin" style="display:none" id="spn"></i> <span>Save & Continue</span>
      </button>`;
    }
    return `<p style="font-size:9px; color:#444;">Step locked or pending...</p>`;
  }

  async function save() {
    const spn = document.getElementById('spn');
    spn.style.display = 'inline-block';
    
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
      enterprise_designation: document.getElementById('o_des').value,
      owner_affiliations: document.getElementById('o_aff').value,
      owner_hea: document.getElementById('o_hea').value
    };

    const { error } = await supabaseClient.from('owner_profile').upsert([d]);
    
    if (!error) {
      next();
    } else {
      alert("Error saving: " + error.message);
      spn.style.display = 'none';
    }
  }

  async function next() {
    currentStep++;
    await supabaseClient.from('user_profiles').update({ current_step: currentStep }).eq('id', user.id);
    render();
  }

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