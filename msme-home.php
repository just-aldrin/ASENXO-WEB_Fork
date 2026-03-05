<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ASENXO | MSME Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="src/css/msme-home-style.css">
  <style>
    :root { --accent: #2ecc71; --bg: #0d0d0d; --card: #1a1a1a; --border: #333; }
    body { background: var(--bg); color: #e0e0e0; font-family: 'Inter', sans-serif; overflow-x: hidden; }

    /* Layout Squeeze */
    .app { display: flex; flex-direction: column; height: 100vh; }
    .content-row { display: flex; flex: 1; overflow: hidden; padding: 10px; gap: 10px; }
    
    /* Sidebar - High Density */
    .sidebar { width: 180px; background: var(--card); border: 1px solid var(--border); border-radius: 8px; display: flex; flex-direction: column; }
    .user-profile-box { padding: 10px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 8px; }
    .user-avatar { width: 28px; height: 28px; background: var(--accent); border-radius: 4px; display: flex; align-items: center; justify-content: center; color: white; font-size: 11px; }
    .user-name-sidebar { font-weight: 700; font-size: 10.5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    /* Main Content Area */
    .main-content { flex: 1; display: grid; grid-template-columns: 1fr 220px; gap: 10px; overflow-y: auto; }

    /* Stepper Squeeze */
    .step-list { list-style: none; padding: 0; margin: 0; }
    .step-item { background: var(--card); border: 1px solid var(--border); border-radius: 6px; margin-bottom: 4px; transition: all 0.2s; }
    .step-header { padding: 6px 12px; display: flex; align-items: center; gap: 10px; cursor: pointer; }
    .step-icon { width: 20px; height: 20px; border-radius: 50%; background: #262626; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 9px; color: #666; }
    
    .step-item.active { border-color: var(--accent); background: #1c1c1c; box-shadow: 0 4px 12px rgba(0,0,0,0.5); }
    .step-item.active .step-icon { background: var(--accent); color: white; }
    .step-item.completed .step-icon { background: var(--accent); color: white; }
    
    .step-title { font-weight: 700; font-size: 11px; line-height: 1.1; display: block; }
    .step-desc { font-size: 9px; color: #777; display: block; }

    /* Form Content Squeeze */
    .step-content { display: none; padding: 8px 12px 12px 42px; border-top: 1px solid #262626; }
    .step-item.active .step-content { display: block; }

    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 6px; }
    .input-group { margin-bottom: 2px; }
    .input-group label { display: block; font-size: 8px; color: #888; text-transform: uppercase; font-weight: 800; margin-bottom: 1px; }
    .input-group input, .input-group select { width: 100%; padding: 4px 8px; background: #000; border: 1px solid var(--border); color: white; border-radius: 3px; font-size: 10.5px; transition: border 0.2s; }
    .input-group input:focus { border-color: var(--accent); outline: none; }
    .input-group input:disabled { opacity: 0.5; cursor: not-allowed; background: #111; border-style: dashed; }
    
    /* Stats Cards */
    .info-column .card { background: var(--card); border: 1px solid var(--border); border-radius: 8px; padding: 12px; margin-bottom: 10px; }
    .card-title { font-size: 9px; color: #777; font-weight: 800; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px; }
    .stat-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
    .stat-label { font-size: 10px; color: #aaa; }
    .stat-val { font-size: 12px; font-weight: 700; color: white; }
    
    .progress-bar-bg { height: 4px; background: #262626; border-radius: 2px; overflow: hidden; margin-top: 5px; }
    .progress-bar-fill { height: 100%; background: var(--accent); transition: width 0.4s ease; }

    /* Global Actions */
    .primary-btn { background: var(--accent); color: white; border: none; padding: 7px; border-radius: 4px; cursor: pointer; width: 100%; font-weight: 700; margin-top: 8px; font-size: 11px; display: flex; align-items: center; justify-content: center; gap: 8px; transition: opacity 0.2s; }
    .primary-btn:hover { opacity: 0.9; }
    .primary-btn:disabled { opacity: 0.5; cursor: not-allowed; }
    .spinner { animation: rotate 1s linear infinite; display: none; font-size: 10px; }
    @keyframes rotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
  </style>
</head>
<body class="dark">
<div class="app">
  <div class="top-header">
    <div class="top-header-left">
      <span class="project-name" style="font-weight: 800; font-size: 13px;">ASENXO <span style="color:var(--accent)">PRO</span></span>
      <span class="badge production">ACTIVE</span>
    </div>
    <div class="top-header-right">
      <button onclick="logout()" style="background:transparent; border:1px solid #444; color:#888; padding:3px 10px; border-radius:4px; font-size:10px; cursor:pointer;">Logout</button>
    </div>
  </div>

  <div class="content-row">
    <div class="sidebar">
      <div class="user-profile-box">
        <div class="user-avatar"><i class="fas fa-user"></i></div>
        <div class="user-name-sidebar" id="sidebarUserName">Loading...</div>
      </div>
      <div style="padding: 10px;">
        <div style="font-size: 8px; color: #555; font-weight: 800; margin-bottom: 8px;">MAIN NAVIGATION</div>
        <div style="color: var(--accent); font-size: 11px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
          <i class="fas fa-th-large"></i> Dashboard
        </div>
      </div>
    </div>

    <div class="main-content">
      <div class="progress-column">
        <ul class="step-list" id="middleStepList"></ul>
      </div>

      <div class="info-column">
        <div class="card">
          <div class="card-title">Profile Completion</div>
          <div class="stat-row">
            <span class="stat-label">Progress</span>
            <span class="stat-val" id="progressPercent">0%</span>
          </div>
          <div class="progress-bar-bg"><div class="progress-bar-fill" id="progressBar" style="width:0%"></div></div>
        </div>

        <div class="card">
          <div class="card-title">System Info</div>
          <div class="stat-row"><span class="stat-label">Status</span><span class="stat-val" style="color:var(--accent); font-size:9px;">ONLINE</span></div>
          <div class="stat-row"><span class="stat-label">Last Updated</span><span class="stat-val" style="font-size:9px;">Just now</span></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
<script>
  // --- KEEP YOUR WORKING CONFIG ---
  const URL = 'https://hmxrblblcpbikkxcwwni.supabase.co';
  const KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhteHJibGJsY3BiaWtreGN3d25pIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzIyODY0MDksImV4cCI6MjA4Nzg2MjQwOX0.qC4Lm2KbToc0f1syHpMWJmQqRhQTosNfFzBrfTXSWDw'; 

  let supabaseClient;
  let user = null;
  let profile = null;
  let currentStep = 3;

  const stepDetails = [
    { id: 1, title: "Account Setup", desc: "Account type and registration" },
    { id: 2, title: "Security Verification", desc: "Confirm your email address" },
    { id: 3, title: "Owner Profile", desc: "Detailed personal information" },
    { id: 4, title: "Identity Documentation", desc: "Upload official ID photos" },
    { id: 5, title: "Business Details", desc: "Enterprise and Tax info" },
    { id: 6, title: "Submission", desc: "Final review and legal permits" }
  ];

  async function init() {
    try {
      // THE FIX: Header injection
      supabaseClient = supabase.createClient(URL, KEY, {
        global: { headers: { 'apikey': KEY, 'Authorization': `Bearer ${KEY}` } }
      });

      const { data: { user: authUser } } = await supabase.auth.getUser();
      if (!authUser) { window.location.href = 'login.php'; return; }
      user = authUser;

      const { data: p } = await supabase.from('user_profiles').select('*').eq('id', user.id).single();
      if (p) {
        profile = p;
        document.getElementById('sidebarUserName').innerText = p.first_name + ' ' + p.last_name;
        currentStep = p.current_step || 3;
        render();
      }
    } catch (e) { console.error("Initialization Failed", e); }
  }

  function render() {
    const p = Math.round((currentStep / stepDetails.length) * 100);
    document.getElementById('progressBar').style.width = p + '%';
    document.getElementById('progressPercent').innerText = p + '%';
    
    document.getElementById('middleStepList').innerHTML = stepDetails.map(s => {
      const active = s.id === currentStep;
      const done = s.id < currentStep;
      return `
        <li class="step-item ${active ? 'active' : ''} ${done ? 'completed' : ''}">
          <div class="step-header" onclick="toggleStep(${s.id})">
            <div class="step-icon">${done ? '<i class="fas fa-check"></i>' : s.id}</div>
            <div>
              <div class="step-title" style="color:${done ? 'var(--accent)' : (active ? 'white' : '#555')}">${s.title}</div>
              <span class="step-desc">${s.desc}</span>
            </div>
          </div>
          <div class="step-content">${getFormHtml(s.id)}</div>
        </li>`;
    }).join('');
  }

  function getFormHtml(id) {
    if (id === 2) return `<button class="primary-btn" onclick="nextStep()">Verify and Continue <i class="fas fa-arrow-right"></i></button>`;
    
    if (id === 3 && profile) {
      return `
      <div class="form-grid">
        <div class="input-group"><label>Owner ID</label><input value="${user.id}" disabled></div>
        <div class="input-group"><label>Full Name</label><input value="${profile.first_name} ${profile.last_name}" disabled></div>
        <div class="input-group"><label>Nickname</label><input id="o_nick" placeholder="e.g. AJ"></div>
        <div class="input-group"><label>DOB</label><input type="date" id="o_dob"></div>
        <div class="input-group"><label>Birth Place</label><input id="o_pob"></div>
        <div class="input-group"><label>Nationality</label><input id="o_nat" value="Filipino"></div>
        <div class="input-group"><label>Sex</label><select id="o_sex"><option>Male</option><option>Female</option></select></div>
        <div class="input-group"><label>Status</label><input id="o_mar"></div>
        <div class="input-group"><label>Spouse</label><input id="o_spo"></div>
        <div class="input-group"><label>Contact</label><input id="o_pho"></div>
        <div class="input-group" style="grid-column: span 2;"><label>Full Address</label><input id="o_adr"></div>
        <div class="input-group"><label>Email</label><input value="${user.email}" disabled></div>
        <div class="input-group"><label>Enterprise</label><input id="o_ent"></div>
        <div class="input-group"><label>Position</label><input id="o_des"></div>
        <div class="input-group"><label>Affiliations</label><input id="o_aff"></div>
        <div class="input-group"><label>Education</label><input id="o_edu"></div>
      </div>
      <button class="primary-btn" id="sBtn" onclick="saveProfile()">
        <i class="fas fa-circle-notch spinner" id="spn"></i> <span>Save & Continue</span>
      </button>`;
    }
    return `<div style="padding:10px; background:#111; border-radius:4px; font-size:10px; color:#444; border:1px dashed #222;">
              <i class="fas fa-lock" style="margin-right:5px;"></i> This step is currently locked.
            </div>`;
  }

  async function saveProfile() {
    const btn = document.getElementById('sBtn');
    const spn = document.getElementById('spn');
    btn.disabled = true; spn.style.display = 'inline-block';

    const payload = {
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
      owner_hea: document.getElementById('o_edu').value
    };

    const { error } = await supabase.from('owner_profile').upsert([payload]);
    if (!error) nextStep();
    else { alert(error.message); btn.disabled = false; spn.style.display = 'none'; }
  }

  async function nextStep() {
    currentStep++;
    await supabase.from('user_profiles').update({ current_step: currentStep }).eq('id', user.id);
    render();
  }

  function logout() {
    supabase.auth.signOut().then(() => window.location.href = 'login.php');
  }

  window.onload = init;
</script>
</body>
</html>