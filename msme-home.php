<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ASENXO | MSME Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="src/css/msme-home-style.css">
  <style>
    :root { --accent-color: #2ecc71; --bg-dark: #0d0d0d; --card-bg: #1a1a1a; }
    
    /* LEFT SIDEBAR: Ultra-Compact */
    .user-profile-box { padding: 8px; border-bottom: 1px solid #333; margin-bottom: 5px; display: flex; align-items: center; gap: 8px; }
    .user-avatar { width: 28px; height: 28px; background: var(--accent-color); border-radius: 4px; display: flex; align-items: center; justify-content: center; color: white; font-size: 11px; flex-shrink: 0; }
    .user-name-sidebar { font-weight: 600; font-size: 10px; color: white; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    /* STEP ITEMS: Maximum Vertical Squeeze */
    .step-list { list-style: none; padding: 0; margin: 0; }
    .step-item { background: var(--card-bg); border: 1px solid #333; border-radius: 6px; margin-bottom: 4px; overflow: hidden; transition: 0.2s; }
    .step-header { padding: 4px 12px; display: flex; align-items: center; gap: 10px; cursor: pointer; }
    .step-icon { width: 18px; height: 18px; border-radius: 50%; background: #262626; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 8px; color: #666; }
    
    .step-item.active { border-color: var(--accent-color); }
    .step-item.active .step-icon { background: var(--accent-color); color: white; }
    .step-item.completed .step-icon { background: var(--accent-color); color: white; }
    
    .step-title { font-weight: 600; font-size: 10.5px; line-height: 1; margin: 0; }
    .step-desc { font-size: 8.5px; color: #555; display: block; line-height: 1; margin-top: 1px; }

    /* STEP CONTENT: Dense Grid */
    .step-content { display: none; padding: 6px 10px 10px 38px; border-top: 1px solid #262626; }
    .step-item.active .step-content { display: block; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 5px; }
    .input-group { margin-bottom: 2px; }
    .input-group label { display: block; font-size: 7.5px; color: #777; margin-bottom: 0px; text-transform: uppercase; font-weight: 800; }
    .input-group input, .input-group select { width: 100%; padding: 4px 8px; background: #000; border: 1px solid #333; color: white; border-radius: 3px; font-size: 10px; }
    .input-group input:disabled { opacity: 0.4; border-style: dashed; }
    
    /* BUTTONS & LOADER */
    .primary-btn { background: var(--accent-color); color: white; border: none; padding: 6px; border-radius: 3px; cursor: pointer; width: 100%; font-weight: 700; margin-top: 4px; font-size: 10px; display: flex; align-items: center; justify-content: center; gap: 6px; }
    .primary-btn:disabled { opacity: 0.6; cursor: not-allowed; }
    .spinner { animation: rotate 1s linear infinite; display: none; font-size: 9px; }
    @keyframes rotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

    /* RIGHT SIDEBAR: Stats */
    .card { padding: 10px; background: var(--card-bg); border-radius: 8px; border: 1px solid #333; margin-bottom: 8px; }
    .card-title { font-size: 9px; color: #777; text-transform: uppercase; font-weight: 800; margin-bottom: 6px; }
    .stat-item { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; }
    .stat-label { font-size: 8.5px; color: #888; }
    .stat-value { font-size: 11px; font-weight: 700; color: white; }
  </style>
</head>
<body class="dark">
<div class="app">
  <div class="top-header">
    <div class="top-header-left">
      <span class="project-name">ASENXO Project</span>
      <span class="badge production">PRODUCTION</span>
    </div>
    <div class="top-header-right">
      <button id="logoutBtn" style="background:#ef4444; color:white; border:none; padding:3px 8px; border-radius:4px; font-size:9px; cursor:pointer;">Logout</button>
    </div>
  </div>

  <div class="content-row">
    <div class="sidebar">
      <div class="user-profile-box">
        <div class="user-avatar"><i class="fas fa-user"></i></div>
        <div class="user-info-text"><span id="sidebarUserName" class="user-name-sidebar">...</span></div>
      </div>
      <ul class="sidebar-menu"><li class="active"><a><i class="fas fa-home"></i> Dashboard</a></li></ul>
    </div>

    <div class="main-content">
      <div class="progress-column">
        <ul class="step-list" id="middleStepList"></ul>
      </div>

      <div class="info-column">
        <div class="card">
          <div class="card-title">Progress</div>
          <div class="info-stats">
            <div class="stat-item"><span class="stat-label">COMPLETION</span><span class="stat-value" id="progressPercent">0%</span></div>
            <div class="progress-bar-bg" style="height:4px;"><div class="progress-bar-fill" id="progressBar" style="width:0%"></div></div>
          </div>
        </div>
        <div class="card">
          <div class="card-title">Storage</div>
          <div class="stat-item"><span class="stat-label">FILES UPLOADED</span><span class="stat-value" id="fileCounter">0</span></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
<script>
  // 1. SETTINGS
  const SUPABASE_URL = 'https://hmxrblblcpbikkxcwwni.supabase.co';
  const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhteHJibGJsY3BiaWtreGN3d25pIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzIyODY0MDksImV4cCI6MjA4Nzg2MjQwOX0.qC4Lm2KbToc0f1syHpMWJmQqRhQTosNfFzBrfTXSWDw';
  const supabaseClient = window.supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY);

  let currentUser = null;
  let cachedProfile = null;
  let currentStep = 2;

  const steps = [
    { id: 1, title: "Account Selection", desc: "Type chosen" },
    { id: 2, title: "Verification", desc: "Email security check" },
    { id: 3, title: "Owner Profile", desc: "Personal information" },
    { id: 4, title: "Profile Image", desc: "Identity photo" },
    { id: 5, title: "Business Information", desc: "TIN & Registration" },
    { id: 6, title: "Documents", desc: "Upload permits" }
  ];

  // 2. BOOTSTRAP FUNCTION
  async function boot() {
    try {
      // Explicitly pass API key in headers to bypass the common "No API Key" error
      supabaseClient = supabase.createClient(SB_URL, SB_KEY, {
        global: { headers: { 'apikey': SB_KEY, 'Authorization': `Bearer ${SB_KEY}` } }
      });

      const { data: { user } } = await supabaseClient.auth.getUser();
      if (!user) { window.location.href = 'login.php'; return; }
      currentUser = user;

      const { data: profile } = await supabaseClient.from('user_profiles').select('*').eq('id', user.id).single();
      if (profile) {
        cachedProfile = profile;
        document.getElementById('sidebarUserName').innerText = `${profile.first_name} ${profile.last_name}`;
        currentStep = profile.current_step || 2;
        refreshUI();
      }
    } catch (err) { console.error("Boot Error:", err); }
  }

  function refreshUI() {
    const p = Math.round((currentStep / steps.length) * 100);
    document.getElementById('progressBar').style.width = p + '%';
    document.getElementById('progressPercent').innerText = p + '%';
    
    document.getElementById('middleStepList').innerHTML = steps.map(s => {
      const active = s.id === currentStep;
      const done = s.id < currentStep;
      return `
        <li class="step-item ${active ? 'active' : ''} ${done ? 'completed' : ''}">
          <div class="step-header">
            <div class="step-icon">${done ? '<i class="fas fa-check"></i>' : s.id}</div>
            <div>
              <div class="step-title" style="color:${done?'var(--accent-color)':'white'}">${s.title}</div>
              <span class="step-desc">${s.desc}</span>
            </div>
          </div>
          <div class="step-content">${getForm(s.id)}</div>
        </li>`;
    }).join('');
  }

  function getForm(id) {
    if (id === 2) return `<button class="primary-btn" onclick="moveNext()">I've Verified My Email</button>`;
    if (id === 3 && cachedProfile) {
      return `
      <div class="form-grid">
        <div class="input-group"><label>Owner ID</label><input type="text" value="${currentUser.id}" disabled></div>
        <div class="input-group"><label>Full Name</label><input type="text" value="${cachedProfile.first_name} ${cachedProfile.last_name}" disabled></div>
        <div class="input-group"><label>Nickname</label><input type="text" id="o_nick"></div>
        <div class="input-group"><label>DOB</label><input type="date" id="o_dob"></div>
        <div class="input-group"><label>Place of Birth</label><input type="text" id="o_pob"></div>
        <div class="input-group"><label>Nationality</label><input type="text" id="o_nat"></div>
        <div class="input-group"><label>Sex</label><select id="o_sex"><option>Male</option><option>Female</option></select></div>
        <div class="input-group"><label>Marital Status</label><input type="text" id="o_marstat"></div>
        <div class="input-group"><label>Spouse Name</label><input type="text" id="o_spouse"></div>
        <div class="input-group"><label>Contact</label><input type="text" id="o_phone"></div>
        <div class="input-group" style="grid-column: span 2;"><label>Full Address</label><input type="text" id="o_addr"></div>
        <div class="input-group"><label>Email</label><input type="text" value="${currentUser.email}" disabled></div>
        <div class="input-group"><label>Enterprise</label><input type="text" id="e_name"></div>
        <div class="input-group"><label>Designation</label><input type="text" id="e_desig"></div>
        <div class="input-group"><label>Affiliations</label><input type="text" id="o_aff"></div>
        <div class="input-group"><label>Education</label><input type="text" id="o_hea"></div>
      </div>
      <button class="primary-btn" id="saveBtn" onclick="save()">
        <i class="fas fa-circle-notch spinner" id="spin"></i> <span>Save & Continue</span>
      </button>`;
    }
    return `<p style="font-size:8px; color:#444;">Pending previous step.</p>`;
  }

  async function save() {
    const btn = document.getElementById('saveBtn');
    const spin = document.getElementById('spin');
    btn.disabled = true; spin.style.display = 'inline-block';

    const d = {
      owner_ID: currentUser.id,
      owner_name: cachedProfile.first_name + ' ' + cachedProfile.last_name,
      owner_nickname: document.getElementById('o_nick').value,
      owner_dob: document.getElementById('o_dob').value,
      owner_pob: document.getElementById('o_pob').value,
      owner_nationality: document.getElementById('o_nat').value,
      owner_sex: document.getElementById('o_sex').value,
      owner_marstat: document.getElementById('o_marstat').value,
      owner_spouse: document.getElementById('o_spouse').value,
      owner_contactnum: document.getElementById('o_phone').value,
      owner_address: document.getElementById('o_addr').value,
      owner_email: currentUser.email,
      enterprise_name: document.getElementById('e_name').value,
      enterprise_designation: document.getElementById('e_desig').value,
      owner_affiliations: document.getElementById('o_aff').value,
      owner_hea: document.getElementById('o_hea').value
    };

    const { error } = await supabaseClient.from('owner_profile').upsert([d]);
    if (!error) moveNext();
    else { alert(error.message); btn.disabled = false; spin.style.display = 'none'; }
  }

  async function moveNext() {
    currentStep++;
    await supabaseClient.from('user_profiles').update({ current_step: currentStep }).eq('id', currentUser.id);
    refreshUI();
  }

  document.addEventListener('DOMContentLoaded', boot);
</script>
</body>
</html>