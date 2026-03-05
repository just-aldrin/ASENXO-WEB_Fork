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
    .app { animation: fadeInUp 0.6s ease-out; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(10px); } 100% { opacity: 1; transform: translateY(0); } }

    /* Profile Box */
    .user-profile-box { padding: 20px; border-bottom: 1px solid #333; margin-bottom: 10px; text-align: center; }
    .user-avatar { width: 50px; height: 50px; background: var(--accent-color); border-radius: 50%; margin: 0 auto 10px; display: flex; align-items: center; justify-content: center; color: white; }
    .user-name-sidebar { font-weight: 600; font-size: 14px; color: white; }

    /* Sidebar Steps Layout (Reverting to Old UI style) */
    .sidebar-menu { list-style: none; padding: 0; margin: 0; }
    .sidebar-menu li { padding: 0; margin-bottom: 10px; border-radius: 8px; transition: 0.3s; border: 1px solid transparent; }
    .step-header { display: flex; align-items: center; padding: 12px; cursor: pointer; gap: 12px; }
    .step-icon { width: 24px; height: 24px; border-radius: 50%; background: #333; display: flex; align-items: center; justify-content: center; font-size: 12px; flex-shrink: 0; }
    
    /* Active & Completed States */
    li.active { background: rgba(46, 204, 113, 0.05); border: 1px solid rgba(46, 204, 113, 0.2); }
    li.active .step-icon { background: var(--accent-color); color: white; }
    li.completed .step-icon { background: var(--accent-color); color: white; }
    li.completed .step-title { color: var(--accent-color); }

    /* Form Expansion Area */
    .step-content-area { display: none; padding: 0 15px 15px 50px; animation: fadeIn 0.3s ease; }
    li.active .step-content-area { display: block; }

    /* Form Elements */
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 10px; }
    .input-group { margin-bottom: 10px; }
    .input-group label { display: block; font-size: 11px; color: #888; margin-bottom: 4px; text-transform: uppercase; }
    .input-group input, .input-group select { width: 100%; padding: 8px; background: #000; border: 1px solid #333; color: white; border-radius: 4px; font-size: 13px; }
    .primary-btn { background: var(--accent-color); color: white; border: none; padding: 10px; border-radius: 4px; cursor: pointer; width: 100%; font-weight: 600; margin-top: 10px; }
    
    #logoutBtn { background: #ef4444; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
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
        <span id="sidebarUserName" class="user-name-sidebar">Loading...</span>
        <small id="sidebarUserEmail" style="color: #666; font-size: 11px; display: block;"></small>
      </div>

      <div class="sidebar-section">
        <div class="sidebar-header">MSME ONBOARDING</div>
        <ul class="sidebar-menu" id="stepContainer">
          </ul>
      </div>
    </div>

    <div class="main-content">
      <div class="progress-column">
        <div class="card">
          <div class="card-title">Welcome to ASENXO</div>
          <p id="welcome-msg">Please complete the steps on the left to finalize your MSME application.</p>
        </div>
      </div>

      <div class="info-column">
        <div class="card">
          <div class="card-title">Overall Progress</div>
          <div class="info-stats">
            <div class="stat-item"><span class="stat-label">Completion</span><span class="stat-value" id="progressPercent">0%</span></div>
            <div class="progress-bar-bg"><div class="progress-bar-fill" id="progressBar" style="width:0%"></div></div>
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
  let currentStep = 2;

  const steps = [
    { id: 1, title: "Account Type", desc: "Selected" },
    { id: 2, title: "Email Verification", desc: "Confirm your account" },
    { id: 3, title: "Owner Information", desc: "Detailed profile data" },
    { id: 4, title: "Profile Image", desc: "Upload identification photo" },
    { id: 5, title: "Business Information", desc: "Enterprise registration" },
    { id: 6, title: "Document Upload", desc: "Final requirements" }
  ];

  async function init() {
    const { data: { user } } = await supabaseClient.auth.getUser();
    if (!user) { window.location.href = 'login-mock.php'; return; }
    currentUser = user;

    let { data: profile } = await supabaseClient.from('user_profiles').select('*').eq('id', user.id).single();
    if (profile) {
      document.getElementById('sidebarUserName').innerText = `${profile.first_name} ${profile.last_name}`;
      document.getElementById('sidebarUserEmail').innerText = user.email;
      currentStep = profile.current_step || 2;
      renderSteps();
    }
  }

  function renderSteps() {
    const container = document.getElementById('stepContainer');
    container.innerHTML = steps.map(step => {
      const isActive = step.id === currentStep;
      const isCompleted = step.id < currentStep;
      
      return `
        <li class="${isActive ? 'active' : ''} ${isCompleted ? 'completed' : ''}">
          <div class="step-header">
            <span class="step-icon">${isCompleted ? '<i class="fas fa-check"></i>' : step.id}</span>
            <div class="step-info">
              <div class="step-title" style="font-size:13px; font-weight:600;">${step.title}</div>
              <div style="font-size:11px; color:#666;">${step.desc}</div>
            </div>
          </div>
          <div class="step-content-area">
            ${getFormHtml(step.id)}
          </div>
        </li>
      `;
    }).join('');

    const percent = Math.round((currentStep / steps.length) * 100);
    document.getElementById('progressBar').style.width = percent + '%';
    document.getElementById('progressPercent').innerText = percent + '%';
  }

  function getFormHtml(stepId) {
    if (stepId === 2) {
      return `
        <p style="font-size:12px; color:#aaa;">Click below once you've clicked the link in your email.</p>
        <button class="primary-btn" onclick="moveNext()">I have verified my email</button>`;
    }
    if (stepId === 3) {
      return `
        <div class="form-grid">
          <div class="input-group"><label>Full Name</label><input type="text" id="owner_name"></div>
          <div class="input-group"><label>Nickname</label><input type="text" id="owner_nickname"></div>
          <div class="input-group"><label>DOB</label><input type="date" id="owner_dob"></div>
          <div class="input-group"><label>Nationality</label><input type="text" id="owner_nat"></div>
          <div class="input-group"><label>Sex</label><select id="owner_sex"><option>Male</option><option>Female</option></select></div>
          <div class="input-group"><label>Contact</label><input type="text" id="owner_contact"></div>
          <div class="input-group" style="grid-column: span 2;"><label>Address</label><input type="text" id="owner_addr"></div>
          <div class="input-group" style="grid-column: span 2;"><label>Enterprise Name</label><input type="text" id="ent_name"></div>
        </div>
        <button class="primary-btn" onclick="saveOwnerInfo()">Save & Next Step</button>`;
    }
    return `<p style="font-size:12px; color:#aaa;">Complete previous steps to unlock.</p>
            <button class="primary-btn" onclick="moveNext()">Continue</button>`;
  }

  async function saveOwnerInfo() {
    const data = {
      owner_ID: currentUser.id,
      owner_name: document.getElementById('owner_name').value,
      owner_nickname: document.getElementById('owner_nickname').value,
      owner_dob: document.getElementById('owner_dob').value,
      owner_nationality: document.getElementById('owner_nat').value,
      owner_sex: document.getElementById('owner_sex').value,
      owner_contactnum: document.getElementById('owner_contact').value,
      owner_address: document.getElementById('owner_addr').value,
      enterprise_name: document.getElementById('ent_name').value,
      owner_email: currentUser.email
    };

    const { error } = await supabaseClient.from('owner_profile').upsert([data]);
    if (!error) moveNext();
    else alert(error.message);
  }

  async function moveNext() {
    currentStep++;
    await supabaseClient.from('user_profiles').update({ current_step: currentStep }).eq('id', currentUser.id);
    renderSteps();
  }

  document.addEventListener('DOMContentLoaded', () => {
    init();
    document.getElementById('themeToggle').addEventListener('click', () => {
      document.body.classList.toggle('dark');
      document.body.classList.toggle('light');
    });
    document.getElementById('logoutBtn').addEventListener('click', async () => {
      await supabaseClient.auth.signOut();
      window.location.href = 'login-mock.php';
    });
  });
</script>
</body>
</html>