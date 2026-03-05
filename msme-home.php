<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ASENXO | MSME Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="src/css/msme-home-style.css">
  <style>
    .app { animation: fadeInUp 0.6s ease-out; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(10px); } 100% { opacity: 1; transform: translateY(0); } }
    .form-card { background: #1a1a1a; padding: 24px; border-radius: 12px; border: 1px solid #333; margin-bottom: 20px; }
    .primary-btn { background: #3b82f6; color: white; border: none; padding: 12px; border-radius: 6px; cursor: pointer; width: 100%; font-weight: 600; margin-top: 10px; }
    #logoutBtn { background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; margin-right: 10px; }
    .step-item.active { border-left: 3px solid #3b82f6; background: rgba(59, 130, 246, 0.1); }
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
      <button id="logoutBtn">Logout</button>
      <span id="userEmailDisplay" class="badge">Loading...</span>
    </div>
  </div>

  <div class="content-row">
    <div class="sidebar">
      <div class="sidebar-section">
        <div class="sidebar-header">MSME DASHBOARD</div>
        <ul class="sidebar-menu" id="sidebarSteps">
          <li data-step="1">Choose Account Type</li>
          <li data-step="2">Email Verification</li>
          <li data-step="4">Profile Image</li>
          <li data-step="6">Business Info</li>
          <li data-step="8">Documents</li>
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

  // 1. CONFIGURATION (Replace with your actual key)
  const SUPABASE_URL = 'https://hmxrblblcpbikkxcwwni.supabase.co';
  const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhteHJibGJsY3BiaWtreGN3d25pIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzIyODY0MDksImV4cCI6MjA4Nzg2MjQwOX0.qC4Lm2KbToc0f1syHpMWJmQqRhQTosNfFzBrfTXSWDw';
  const supabaseClient = window.supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY, {
            global: {
                headers: {
                'apikey': SUPABASE_ANON_KEY,
                'Authorization': `Bearer ${SUPABASE_ANON_KEY}`
                }
            }
            });;

  let currentUser = null;
  let currentStep = 2;

  // 2. UI FUNCTIONS
  function updateDashboardUI() {
    const totalSteps = 12;
    const percent = Math.round((currentStep / totalSteps) * 100);
    document.getElementById('progressBar').style.width = percent + '%';
    document.getElementById('progressPercent').innerText = percent + '%';

    document.querySelectorAll('#sidebarSteps li').forEach(li => {
      const step = parseInt(li.dataset.step);
      li.classList.remove('active');
      if (step < currentStep) li.style.color = '#4ade80'; 
      if (step === currentStep) li.classList.add('active');
    });

    renderCurrentForm();
  }

  function renderCurrentForm() {
    const container = document.getElementById('activeStepForm');
    if (currentStep === 2) {
      container.innerHTML = `
        <h3>Step 2: Email Verification</h3>
        <p>We sent a link to <b>${currentUser.email}</b>. Please verify to continue.</p>
        <button class="primary-btn" onclick="checkVerification()">I've Verified My Email</button>`;
    } else {
      container.innerHTML = `<h3>Step ${currentStep}</h3><p>Current progress recorded.</p><button class="primary-btn" onclick="moveNext()">Continue</button>`;
    }
  }

  // 3. LOGIC FUNCTIONS
  async function checkVerification() {
    const { data: { user } } = await supabaseClient.auth.getUser();
    if (user.email_confirmed_at) { moveNext(); } 
    else { alert("Email not verified yet! Check your inbox."); }
  }

  async function moveNext() {
    currentStep++;
    await supabaseClient.from('user_profiles').update({ current_step: currentStep }).eq('id', currentUser.id);
    updateDashboardUI();
  }

  // 4. INITIALIZATION (Wait for DOM to load)
  async function init() {
    const { data: { user } } = await supabaseClient.auth.getUser();
    if (!user) { window.location.href = 'login-mock.php'; return; }
    
    currentUser = user;

    // Fetching first_name and last_name from your specific table
    let { data: profile, error } = await supabaseClient
      .from('user_profiles')
      .select('first_name, last_name, current_step')
      .eq('id', user.id)
      .single();

    if (profile) {
      document.getElementById('userEmailDisplay').innerText = `${profile.first_name} ${profile.last_name}`;
      currentStep = profile.current_step || 2;
      updateDashboardUI();
    } else {
      document.getElementById('userEmailDisplay').innerText = user.email;
      console.error("Profile not found in user_profiles table.");
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    init();
    
    document.getElementById('logoutBtn').addEventListener('click', async () => {
      await supabaseClient.auth.signOut();
      window.location.href = 'login-mock.php';
    });
  });
</script>
</body>
</html>