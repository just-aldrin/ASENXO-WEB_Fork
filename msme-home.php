<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ASENXO | MSME Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="src/css/msme-home-style.css">
  <style>
    :root { --accent-color: #2ecc71; }
    
    .user-profile-box { padding: 16px; border-bottom: 1px solid #333; margin-bottom: 15px; display: flex; align-items: center; gap: 12px; }
    .user-avatar { width: 40px; height: 40px; background: var(--accent-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0; }
    .user-name-sidebar { font-weight: 600; font-size: 13px; color: white; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    
    /* Stepper Styling */
    .step-list { list-style: none; padding: 0; }
    .step-item { background: #1a1a1a; border: 1px solid #333; border-radius: 12px; margin-bottom: 10px; overflow: hidden; transition: all 0.3s ease; }
    .step-header { padding: 12px 20px; display: flex; align-items: center; gap: 15px; cursor: pointer; }
    .step-icon { width: 26px; height: 26px; border-radius: 50%; background: #262626; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 11px; color: #666; }
    
    .step-item.active { border-color: var(--accent-color); background: #1f1f1f; }
    .step-item.active .step-icon { background: var(--accent-color); color: white; }
    .step-item.completed .step-icon { background: var(--accent-color); color: white; }
    
    .step-content { display: none; padding: 0 20px 20px 61px; border-top: 1px solid #262626; padding-top: 15px; }
    .step-item.active .step-content { display: block; }

    /* Owner Profile Form Grid */
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .input-group { margin-bottom: 8px; }
    .input-group label { display: block; font-size: 10px; color: #888; margin-bottom: 3px; text-transform: uppercase; font-weight: 600; }
    .input-group input, .input-group select { width: 100%; padding: 8px 10px; background: #000; border: 1px solid #333; color: white; border-radius: 6px; font-size: 12px; }
    .input-group input:disabled { opacity: 0.5; cursor: not-allowed; background: #111; }
    
    /* Button & Spinner */
    .primary-btn { background: var(--accent-color); color: white; border: none; padding: 10px; border-radius: 6px; cursor: pointer; width: 100%; font-weight: 600; margin-top: 10px; font-size: 13px; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .primary-btn:disabled { opacity: 0.7; cursor: not-allowed; }
    .spinner { animation: rotate 1s linear infinite; display: none; }
    @keyframes rotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

    #logoutBtn { background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 11px; }
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
      <button class="theme-toggle" id="themeToggle" style="margin-right: 15px; background: none; border: none; color: inherit; cursor: pointer;">
        <i class="fas fa-sun"></i>
      </button>
      <button id="logoutBtn">Logout</button>
    </div>
  </div>

  <div class="content-row">
    <div class="sidebar">
      <div class="user-profile-box">
        <div class="user-avatar"><i class="fas fa-user"></i></div>
        <div class="user-info-text">
          <span id="sidebarUserName" class="user-name-sidebar">Loading...</span>
          <small style="color: #666; font-size: 10px;">MSME Account</small>
        </div>
      </div>
      <div class="sidebar-section">
        <div class="sidebar-header">MENU</div>
        <ul class="sidebar-menu">
          <li class="active"><a><i class="fas fa-home"></i> Dashboard</a></li>
          <li><a><i class="fas fa-file-invoice"></i> Applications</a></li>
          <li><a><i class="fas fa-cog"></i> Settings</a></li>
        </ul>
      </div>
    </div>

    <div class="main-content">
      <div class="progress-column">
        <div class="section-title" style="margin-bottom: 15px; font-weight: 700; font-size: 16px;">Application Progress</div>
        <ul class="step-list" id="middleStepList"></ul>
      </div>

      <div class="info-column">
        <div class="card">
          <div class="card-title">Overall Progress</div>
          <div class="info-stats">
            <div class="stat-item"><span class="stat-label">Completion</span><span class="stat-value" id="progressPercent">0%</span></div>
            <div class="progress-bar-bg"><div class="progress-bar-fill" id="progressBar" style="width:0%; background: var(--accent-color);"></div></div>
          </div>
        </div>
        <div class="card">
          <div class="card-title">File Repository</div>
          <div class="info-stats">
            <div class="stat-item"><span class="stat-label">FILES UPLOADED</span><span class="stat-value" id="fileCounter">0</span></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
<script>
  const SUPABASE_URL = 'https://hmxrblblcpbikkxcwwni.supabase.co';
  const SUPABASE_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhteHJibGJsY3BiaWtreGN3d25pIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzIyODY0MDksImV4cCI6MjA4Nzg2MjQwOX0.qC4Lm2KbToc0f1syHpMWJmQqRhQTosNfFzBrfTXSWDw'; 
  const supabaseClient = supabase.createClient(SUPABASE_URL, SUPABASE_KEY);

  let currentUser = null;
  let cachedProfile = null;
  let currentStep = 2;

  const stepDetails = [
    { id: 1, title: "Account Selection", desc: "Type chosen" },
    { id: 2, title: "Email Verification", desc: "Identity check" },
    { id: 3, title: "Owner Information", desc: "Detailed profile data" },
    { id: 4, title: "Profile Image", desc: "Identification photo" },
    { id: 5, title: "Business Information", desc: "Registration details" },
    { id: 6, title: "Document Upload", desc: "Required files" }
  ];

  async function init() {
    const { data: { user } } = await supabaseClient.auth.getUser();
    if (!user) { window.location.href = 'login-mock.php'; return; }
    currentUser = user;

    const { data: profile } = await supabaseClient.from('user_profiles').select('*').eq('id', user.id).single();
    if (profile) {
      cachedProfile = profile;
      document.getElementById('sidebarUserName').innerText = `${profile.first_name} ${profile.last_name}`;
      currentStep = profile.current_step || 2;
      refreshUI();
    }
  }

  function refreshUI() {
    const percent = Math.round((currentStep / stepDetails.length) * 100);
    document.getElementById('progressBar').style.width = percent + '%';
    document.getElementById('progressPercent').innerText = percent + '%';
    
    const list = document.getElementById('middleStepList');
    list.innerHTML = stepDetails.map(step => {
      const isActive = step.id === currentStep;
      const isDone = step.id < currentStep;
      return `
        <li class="step-item ${isActive ? 'active' : ''} ${isDone ? 'completed' : ''}">
          <div class="step-header">
            <div class="step-icon">${isDone ? '<i class="fas fa-check"></i>' : step.id}</div>
            <div>
              <div style="font-weight:600; font-size:13px; color:${isDone ? 'var(--accent-color)' : 'white'}">${step.title}</div>
              <div style="font-size:10px; color:#666;">${isActive ? step.desc : ''}</div>
            </div>
          </div>
          <div class="step-content">${getFormHtml(step.id)}</div>
        </li>`;
    }).join('');
  }

  function getFormHtml(id) {
    if (id === 2) return `<p style="font-size:12px; color:#aaa; margin-bottom:10px;">Please confirm verification.</p><button class="primary-btn" onclick="moveNext()">Continue</button>`;
    
    if (id === 3) {
      const fullName = `${cachedProfile.first_name} ${cachedProfile.last_name}`;
      return `
      <div class="form-grid">
        <div class="input-group"><label>Owner ID</label><input type="text" value="${currentUser.id}" disabled></div>
        <div class="input-group"><label>Full Name</label><input type="text" value="${fullName}" disabled></div>
        <div class="input-group"><label>Nickname</label><input type="text" id="owner_nick"></div>
        <div class="input-group"><label>Date of Birth</label><input type="date" id="owner_dob"></div>
        <div class="input-group"><label>Place of Birth</label><input type="text" id="owner_pob"></div>
        <div class="input-group"><label>Nationality</label><input type="text" id="owner_nat"></div>
        <div class="input-group"><label>Sex</label><select id="owner_sex"><option>Male</option><option>Female</option></select></div>
        <div class="input-group"><label>Marital Status</label><input type="text" id="owner_marstat"></div>
        <div class="input-group"><label>Spouse Name</label><input type="text" id="owner_spouse"></div>
        <div class="input-group"><label>Contact Number</label><input type="text" id="owner_phone"></div>
        <div class="input-group" style="grid-column: span 2;"><label>Full Address</label><input type="text" id="owner_addr"></div>
        <div class="input-group"><label>Email Address</label><input type="text" value="${currentUser.email}" disabled></div>
        <div class="input-group"><label>Enterprise Name</label><input type="text" id="ent_name"></div>
        <div class="input-group"><label>Enterprise Designation</label><input type="text" id="ent_desig"></div>
        <div class="input-group"><label>Owner Affiliations</label><input type="text" id="owner_aff"></div>
        <div class="input-group"><label>Educational Attainment</label><input type="text" id="owner_hea"></div>
      </div>
      <button class="primary-btn" id="saveOwnerBtn" onclick="saveOwnerInfo()">
        <i class="fas fa-spinner spinner" id="saveSpinner"></i>
        <span>Save Owner Profile</span>
      </button>`;
    }
    return `<p style="font-size:11px; color:#555;">Locked.</p>`;
  }

  async function saveOwnerInfo() {
    const btn = document.getElementById('saveOwnerBtn');
    const spinner = document.getElementById('saveSpinner');
    
    // Start Loading
    btn.disabled = true;
    spinner.style.display = 'inline-block';

    const data = {
      owner_ID: currentUser.id,
      owner_name: `${cachedProfile.first_name} ${cachedProfile.last_name}`,
      owner_nickname: document.getElementById('owner_nick').value,
      owner_dob: document.getElementById('owner_dob').value,
      owner_pob: document.getElementById('owner_pob').value,
      owner_nationality: document.getElementById('owner_nat').value,
      owner_sex: document.getElementById('owner_sex').value,
      owner_marstat: document.getElementById('owner_marstat').value,
      owner_spouse: document.getElementById('owner_spouse').value,
      owner_contactnum: document.getElementById('owner_phone').value,
      owner_address: document.getElementById('owner_addr').value,
      owner_email: currentUser.email,
      enterprise_name: document.getElementById('ent_name').value,
      enterprise_designation: document.getElementById('ent_desig').value,
      owner_affiliations: document.getElementById('owner_aff').value,
      owner_hea: document.getElementById('owner_hea').value
    };

    const { error } = await supabaseClient.from('owner_profile').upsert([data]);
    
    if (!error) {
      moveNext();
    } else {
      alert("Error: " + error.message);
      btn.disabled = false;
      spinner.style.display = 'none';
    }
  }

  async function moveNext() {
    currentStep++;
    await supabaseClient.from('user_profiles').update({ current_step: currentStep }).eq('id', currentUser.id);
    refreshUI();
  }

  document.addEventListener('DOMContentLoaded', () => {
    init();
    document.getElementById('themeToggle').addEventListener('click', () => {
      document.body.classList.toggle('dark'); document.body.classList.toggle('light');
    });
    document.getElementById('logoutBtn').addEventListener('click', async () => {
      await supabaseClient.auth.signOut(); window.location.href = 'login-mock.php';
    });
  });
</script>
</body>
</html>