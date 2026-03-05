<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ASENXO | MSME Dashboard</title>
  <link rel="icon" type="image/png" href="favicon.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="src/css/msme-home-style.css">
  <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
  <style>
    .app { animation: fadeInUp 0.6s ease-out; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(10px); } 100% { opacity: 1; transform: translateY(0); } }
    .form-card { background: var(--card-bg); padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid var(--border-color); }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; color: var(--text-muted); font-size: 14px; }
    .form-group input, .form-group select { width: 100%; padding: 10px; background: #111; border: 1px solid #333; color: white; border-radius: 4px; }
    .primary-btn { background: #3b82f6; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; width: 100%; font-weight: bold; }
    .primary-btn:hover { background: #2563eb; }
    #logoutBtn { cursor: pointer; background: #991b1b; border: none; padding: 5px 10px; border-radius: 4px; color: white; font-size: 12px; }
  </style>
</head>
<body class="dark"> 
<div class="app">
  <div class="top-header">
    <div class="top-header-left">
      <span class="project-name">ASENXO Project</span>
      <span class="badge">main</span>
      <span class="badge production">PRODUCTION</span>
    </div>
    <div class="top-header-right">
      <button id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</button>
      <div class="search"><i class="fas fa-search"></i><input type="text" placeholder="Search..."></div>
      <span id="userEmailDisplay" class="badge">Loading...</span>
      <button class="theme-toggle" id="themeToggle"><i class="fas fa-sun"></i></button>
    </div>
  </div>

  <div class="content-row">
    <div class="sidebar">
      <div class="sidebar-section">
        <div class="sidebar-header">MSME DASHBOARD</div>
        <ul class="sidebar-menu" id="sidebarSteps">
          <li data-step="1"><i class="fas fa-check"></i> Choose Account Type</li>
          <li data-step="2"><i class="fas fa-envelope"></i> Email Verification</li>
          <li data-step="3"><i class="fas fa-forward"></i> Proceed</li>
          <li data-step="4"><i class="fas fa-image"></i> Profile Image</li>
          <li data-step="5"><i class="fas fa-user"></i> Personal Info</li>
          <li data-step="6"><i class="fas fa-building"></i> Business Info</li>
          <li data-step="7"><i class="fas fa-tasks"></i> Confirmation</li>
          <li data-step="8"><i class="fas fa-file-upload"></i> Documents</li>
        </ul>
      </div>
    </div>

    <div class="main-content">
      <div class="progress-column">
        <div id="activeStepForm" class="form-card">
          <h3>Loading your progress...</h3>
        </div>

        <div class="card">
          <div class="card-title"><i class="fas fa-clipboard-list"></i> Application Status</div>
          <p id="stepStatusText">Initializing...</p>
        </div>
      </div>

      <div class="info-column">
        <div class="card">
          <div class="card-title">Progress Overview</div>
          <div class="info-stats">
            <div class="stat-item"><span class="stat-label">Status</span><span class="stat-value" id="statusLabel" style="color: #eab308;">Pending</span></div>
            <div class="stat-item"><span class="stat-label">Completion</span><span class="stat-value" id="progressPercent">0%</span></div>
            <div class="progress-bar-bg"><div class="progress-bar-fill" id="progressBar" style="width:0%"></div></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // CONFIGURATION
  const SUPABASE_URL = 'https://hmxrblblcpbikkxcwwni.supabase.co';
  const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhteHJibGJsY3BiaWtreGN3d25pIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzIyODY0MDksImV4cCI6MjA4Nzg2MjQwOX0.qC4Lm2KbToc0f1syHpMWJmQqRhQTosNfFzBrfTXSWDw';
  const supabase = supabase.createClient(SUPABASE_URL, SUPABASE_KEY);

  let currentUser = null;
  let currentStep = 2;

  // INITIALIZE
  async function init() {
    const { data: { user } } = await supabase.auth.getUser();
    if (!user) { window.location.href = 'login-mock.php'; return; }
    currentUser = user;
    document.getElementById('userEmailDisplay').innerText = user.email;

    // Get or Create Profile
    let { data: profile } = await supabase.from('profiles').select('*').eq('id', user.id).single();
    if (!profile) {
      const { data: newProfile } = await supabase.from('profiles').insert([{ id: user.id, email: user.email, current_step: 2 }]).select().single();
      profile = newProfile;
    }

    currentStep = profile.current_step;
    updateUI();
  }

  // UPDATE UI
  function updateUI() {
    const percent = Math.round((currentStep / 12) * 100);
    document.getElementById('progressBar').style.width = percent + '%';
    document.getElementById('progressPercent').innerText = percent + '%';
    
    // Update Sidebar
    document.querySelectorAll('#sidebarSteps li').forEach(li => {
      const step = parseInt(li.dataset.step);
      li.classList.remove('active');
      if (step < currentStep) li.style.color = '#4ade80';
      if (step === currentStep) li.classList.add('active');
    });

    renderForm();
  }

  // RENDER DYNAMIC FORMS
  function renderForm() {
    const container = document.getElementById('activeStepForm');
    
    switch(currentStep) {
      case 2:
        container.innerHTML = `
          <h3>Step 2: Email Verification</h3>
          <p>We've sent a link to <b>${currentUser.email}</b>. Please click it to continue.</p>
          <button class="primary-btn" onclick="checkVerification()">I have verified my email</button>
        `;
        break;
      case 4:
        container.innerHTML = `
          <h3>Step 4: Upload Profile Image</h3>
          <div class="form-group"><input type="file" id="fileInput" accept="image/*"></div>
          <button class="primary-btn" onclick="handleFileUpload('profile_img')">Upload Image</button>
        `;
        break;
      case 6:
        container.innerHTML = `
          <h3>Step 6: Business Information</h3>
          <div class="form-group"><label>Business Name</label><input type="text" id="bName"></div>
          <div class="form-group"><label>TIN Number</label><input type="text" id="bTin"></div>
          <button class="primary-btn" onclick="saveBusinessInfo()">Submit Business Details</button>
        `;
        break;
      default:
        container.innerHTML = `<h3>Step ${currentStep}</h3><p>Form coming soon...</p><button class="primary-btn" onclick="next()">Skip for now (Debug)</button>`;
    }
  }

  // FUNCTIONAL LOGIC
  async function checkVerification() {
    const { data: { user } } = await supabase.auth.getUser();
    if (user.email_confirmed_at) { next(); } 
    else { alert("Email not verified yet!"); }
  }

  async function saveBusinessInfo() {
    const name = document.getElementById('bName').value;
    const tin = document.getElementById('bTin').value;
    const { error } = await supabase.from('business_info').upsert({ id: currentUser.id, business_name: name, tin_number: tin });
    if (!error) next();
  }

  async function handleFileUpload(type) {
    const file = document.getElementById('fileInput').files[0];
    if (!file) return;
    const path = `${currentUser.id}/${Date.now()}_${file.name}`;
    const { data, error } = await supabase.storage.from('application-files').upload(path, file);
    if (!error) {
      await supabase.from('application_documents').insert({ user_id: currentUser.id, file_path: path, document_type: type });
      next();
    }
  }

  async function next() {
    currentStep++;
    await supabase.from('profiles').update({ current_step: currentStep }).eq('id', currentUser.id);
    updateUI();
  }

  document.getElementById('logoutBtn').addEventListener('click', async () => {
    await supabase.auth.signOut();
    window.location.href = 'login-mock.php';
  });

  init();
</script>
</body>
</html>