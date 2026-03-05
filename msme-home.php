<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ASENXO | MSME Dashboard</title>
  <link rel="icon" type="image/png" href="favicon.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="src/css/msme-home-style.css">
  <style>
    .app { animation: fadeInUp 0.6s ease-out; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(10px); } 100% { opacity: 1; transform: translateY(0); } }
    .form-card { background: #1a1a1a; padding: 24px; border-radius: 12px; border: 1px solid #333; margin-bottom: 20px; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 8px; color: #9ca3af; font-size: 14px; }
    .form-group input { width: 100%; padding: 12px; background: #000; border: 1px solid #333; color: white; border-radius: 6px; }
    .primary-btn { background: #3b82f6; color: white; border: none; padding: 12px; border-radius: 6px; cursor: pointer; width: 100%; font-weight: 600; margin-top: 10px; }
    .primary-btn:hover { background: #2563eb; }
    #logoutBtn { background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px; margin-right: 10px; }
    .step-item.active { border-left: 3px solid #3b82f6; background: rgba(59, 130, 246, 0.1); }
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
          <li data-step="3"><i class="fas fa-arrow-right"></i> Proceed</li>
          <li data-step="4"><i class="fas fa-image"></i> Profile Image</li>
          <li data-step="5"><i class="fas fa-user"></i> Personal Info</li>
          <li data-step="6"><i class="fas fa-building"></i> Business Info</li>
          <li data-step="7"><i class="fas fa-check-double"></i> Confirmation</li>
          <li data-step="8"><i class="fas fa-file-upload"></i> Documents</li>
        </ul>
      </div>
    </div>

    <div class="main-content">
      <div class="progress-column">
        <div id="activeStepForm" class="form-card">
          <h3>Loading your application...</h3>
        </div>
      </div>

      <div class="info-column">
        <div class="card">
          <div class="card-title">Progress Overview</div>
          <div class="info-stats">
            <div class="stat-item"><span class="stat-label">Status</span><span class="stat-value" id="statusLabel" style="color: #eab308;">In Progress</span></div>
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
  // CONFIGURATION - RENAMED TO AVOID CONFLICT
  const SUPABASE_URL = 'https://hmxrblblcpbikkxcwwni.supabase.co';
  const SUPABASE_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhteHJibGJsY3BiaWtreGN3d25pIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzIyODY0MDksImV4cCI6MjA4Nzg2MjQwOX0.qC4Lm2KbToc0f1syHpMWJmQqRhQTosNfFzBrfTXSWDw'; 
  const supabaseClient = supabase.createClient(SUPABASE_URL, SUPABASE_KEY);

  let currentUser = null;
  let currentStep = 2;

  async function init() {
  const { data: { user } } = await supabaseClient.auth.getUser();
  if (!user) { window.location.href = 'login-mock.php'; return; }
  
  currentUser = user;
  document.getElementById('userEmailDisplay').innerText = user.email;

  // Attempt to fetch profile
  let { data: profile, error: fetchError } = await supabaseClient
    .from('profiles')
    .select('*')
    .eq('id', user.id)
    .single();

  // If profile doesn't exist or RLS blocked it, try to create it
  if (!profile || fetchError) {
    console.log("No profile found, attempting to create one...");
    const { data: newProfile, error: insertError } = await supabaseClient
      .from('profiles')
      .insert([{ id: user.id, email: user.email, current_step: 2 }])
      .select()
      .single();

    if (insertError) {
      console.error("Critical: Could not create profile. Check RLS policies.", insertError);
      document.getElementById('activeStepForm').innerHTML = "<h3>Permission Error</h3><p>Please ensure RLS policies are set in Supabase.</p>";
      return; // Stop here so it doesn't crash on the next line
    }
    profile = newProfile;
  }

  // Now it's safe to read current_step
  currentStep = profile.current_step;
  updateDashboardUI();
}

  function renderCurrentForm() {
    const container = document.getElementById('activeStepForm');
    
    switch(currentStep) {
      case 2:
        container.innerHTML = `
          <h3>Step 2: Email Verification</h3>
          <p>Please check your inbox at <b>${currentUser.email}</b>. Once you click the link in your email, come back here.</p>
          <button class="primary-btn" onclick="verifyEmailStatus()">I have verified my email</button>
        `;
        break;
      case 4:
        container.innerHTML = `
          <h3>Step 4: Profile Image</h3>
          <div class="form-group"><label>Select a professional photo</label><input type="file" id="profileFile" accept="image/*"></div>
          <button class="primary-btn" onclick="handleUpload('profile_image', 'profileFile')">Upload & Continue</button>
        `;
        break;
      case 6:
        container.innerHTML = `
          <h3>Step 6: Business Information</h3>
          <div class="form-group"><label>Registered Business Name</label><input type="text" id="bizName" placeholder="Enter name"></div>
          <div class="form-group"><label>TIN Number</label><input type="text" id="bizTin" placeholder="000-000-000"></div>
          <button class="primary-btn" onclick="saveBusinessData()">Save Business Details</button>
        `;
        break;
      case 8:
        container.innerHTML = `
          <h3>Step 8: Required Documents</h3>
          <div class="form-group"><label>Business Permit (PDF/JPG)</label><input type="file" id="docFile"></div>
          <button class="primary-btn" onclick="handleUpload('business_permit', 'docFile')">Submit Application</button>
        `;
        break;
      default:
        container.innerHTML = `<h3>Step ${currentStep}</h3><p>Move to next step to continue.</p><button class="primary-btn" onclick="moveNext()">Next Step</button>`;
    }
  }

  // LOGIC FUNCTIONS
  async function verifyEmailStatus() {
    const { data: { user } } = await supabaseClient.auth.getUser();
    if (user.email_confirmed_at) { moveNext(); } 
    else { alert("Email not verified yet! Please check your spam folder."); }
  }

  async function saveBusinessData() {
    const name = document.getElementById('bizName').value;
    const tin = document.getElementById('bizTin').value;
    const { error } = await supabaseClient.from('business_info').upsert({ id: currentUser.id, business_name: name, tin_number: tin });
    if (!error) moveNext();
  }

  async function handleUpload(type, inputId) {
    const file = document.getElementById(inputId).files[0];
    if (!file) return alert("Please select a file first.");

    const path = `uploads/${currentUser.id}/${Date.now()}_${file.name}`;
    const { data, error } = await supabaseClient.storage.from('application-files').upload(path, file);

    if (!error) {
      await supabaseClient.from('application_documents').insert({ user_id: currentUser.id, file_path: path, document_type: type });
      moveNext();
    } else {
      alert("Upload failed: " + error.message);
    }
  }

  async function moveNext() {
    currentStep++;
    await supabaseClient.from('profiles').update({ current_step: currentStep }).eq('id', currentUser.id);
    updateDashboardUI();
  }

  // LOGOUT
  document.getElementById('logoutBtn').addEventListener('click', async () => {
    await supabaseClient.auth.signOut();
    window.location.href = 'login-mock.php';
  });

  init();
</script>
</body>
</html>