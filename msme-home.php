<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ASENXO | MSME Dashboard</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@200..800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="src/css/msme-home-style.css">
  
  <style>
    :root { 
      --accent: #2ecc71; 
      --bg-dark: #000000;
      --card-bg: #111111;
      --border-color: #222222;
    }

    body {
      font-family: 'Bricolage Grotesque', sans-serif;
      background-color: var(--bg-dark);
      margin: 0;
      color: white;
      overflow-x: hidden;
    }

    .app { animation: fadeInUp 0.6s ease-out; }
    @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(10px); } 100% { opacity: 1; transform: translateY(0); } }

    /* Form Styles */
    .step-form-container { margin-top: 15px; padding-top: 15px; border-top: 1px solid var(--border-color); }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .input-group { display: flex; flex-direction: column; gap: 4px; }
    .input-group label { font-size: 10px; color: #666; font-weight: 700; text-transform: uppercase; }
    .input-group input, .input-group select { 
      background: #000; border: 1px solid var(--border-color); color: white; padding: 8px 10px; 
      border-radius: 6px; font-size: 13px; font-family: 'Bricolage Grotesque', sans-serif;
    }
    .input-group input:disabled { background: #0a0a0a; color: #444; border-style: dashed; }

    .primary-btn { 
      background: var(--accent); color: #000; border: none; padding: 10px 20px; 
      border-radius: 6px; font-weight: 700; cursor: pointer; margin-top: 15px;
      display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 13px;
      font-family: 'Bricolage Grotesque', sans-serif; transition: 0.2s;
    }
    .primary-btn:hover { opacity: 0.9; transform: translateY(-1px); }
    .primary-btn:disabled { opacity: 0.5; cursor: not-allowed; }

    .spinner { animation: fa-spin 1s infinite linear; display: none; }

    /* Sidebar Avatar Styling */
    .user-avatar-container {
      width: 32px; height: 32px; border-radius: 50%; overflow: hidden;
      background: #222; display: flex; align-items: center; justify-content: center;
      border: 1px solid var(--border-color); margin-right: 10px;
    }
    .user-avatar-container img { width: 100%; height: 100%; object-fit: cover; }
    .user-avatar-container i { font-size: 16px; color: #555; }

    /* Step Item Active State */
    .step-item.active { border: 1px solid rgba(46, 204, 113, 0.2); background: rgba(255,255,255,0.02); padding: 15px; border-radius: 12px; margin: 10px 0; }
    
    /* Image Preview Circle */
    #imagePreview { 
      width: 100px; height: 100px; border-radius: 50%; border: 2px dashed #333; 
      display: flex; align-items: center; justify-content: center; overflow: hidden; 
      background: #0a0a0a; margin: 0 auto 10px auto;
    }
    #imagePreview img { width: 100%; height: 100%; object-fit: cover; }
  </style>
</head>
<body class="dark"> 
<div class="app">
  <div class="top-header">
    <div class="top-header-left">
      <span class="project-name" style="font-weight: 800;">ASENXO</span>
      <span class="badge production">PRODUCTION</span>
    </div>
    <div class="top-header-right">
      <button class="theme-toggle" id="themeToggle"><i class="fas fa-sun"></i></button>
      <button onclick="handleLogout()" style="background:#ef4444; border:none; color:white; padding:5px 12px; border-radius:4px; font-size:11px; font-weight:700; cursor:pointer; font-family:'Bricolage Grotesque';">Logout</button>
    </div>
  </div>

  <div class="content-row">
    <div class="sidebar">
      <div class="sidebar-section">
        <div class="sidebar-header">MSME DASHBOARD</div>
        <ul class="sidebar-menu">
          <li class="active"><i class="fas fa-cube"></i> Application Module</li>
          <li><i class="fas fa-chart-line"></i> Progress</li>
          <li><i class="fas fa-cloud-upload-alt"></i> Documents</li>
        </ul>
      </div>
      <div class="msme-label" style="display: flex; align-items: center; padding: 10px 15px;">
        <div class="user-avatar-container" id="sidebarAvatar">
          <i class="fas fa-user"></i>
        </div>
        <span id="sidebarName">Loading...</span>
      </div>
    </div>

    <div class="main-content">
      <div class="progress-column">
        <div class="card">
          <div class="card-title"><i class="fas fa-clipboard-check" style="margin-right: 8px; color: var(--accent);"></i> Application Flow</div>
          <ul class="step-list" id="dynamicSteps"></ul>
        </div>
      </div>

      <div class="info-column">
        <div class="card">
          <div class="card-title">Overview</div>
          <div class="info-stats">
            <div class="stat-item"><span class="stat-label">Progress</span><span class="stat-value" id="progressTxt">0%</span></div>
            <div class="progress-bar-bg"><div class="progress-bar-fill" id="progressFill" style="width:0%"></div></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
<script>
  const S_URL = 'https://hmxrblblcpbikkxcwwni.supabase.co';
  const S_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhteHJibGJsY3BiaWtreGN3d25pIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzIyODY0MDksImV4cCI6MjA4Nzg2MjQwOX0.qC4Lm2KbToc0f1syHpMWJmQqRhQTosNfFzBrfTXSWDw'; 
  const sb = supabase.createClient(S_URL, S_KEY);

  let user = null;
  let profile = null;
  let currentStep = 3;

  const stepsData = [
    { id: 1, title: "Account Selection", desc: "Entity type chosen" },
    { id: 2, title: "Identity Security", desc: "Verify mobile & email" },
    { id: 3, title: "Complete Your Information", desc: "Detailed owner profile" },
    { id: 4, title: "IMAGE HERE", desc: "Upload profile image" },
    { id: 5, title: "Business Information", desc: "Business registration details" },
    { id: 6, title: "Account Confirmation", desc: "Review and confirm" }
  ];

  async function init() {
    const { data: { user: u } } = await sb.auth.getUser();
    if (!u) return window.location.href = 'login.php';
    user = u;

    // Fetch user profile and owner_profile simultaneously
    const [profRes, ownerRes] = await Promise.all([
        sb.from('user_profiles').select('*').eq('id', user.id).single(),
        sb.from('owner_profile').select('profile_pic_url').eq('owner_ID', user.id).single()
    ]);

    if (profRes.data) {
      profile = profRes.data;
      currentStep = profile.current_step || 3;
      document.getElementById('sidebarName').innerText = `${profile.first_name} ${profile.last_name}`;
      
      // Update sidebar avatar if photo exists
      if (ownerRes.data && ownerRes.data.profile_pic_url) {
        updateSidebarAvatar(ownerRes.data.profile_pic_url);
      }
      
      renderSteps();
    }
  }

  function updateSidebarAvatar(url) {
    const avatarContainer = document.getElementById('sidebarAvatar');
    avatarContainer.innerHTML = `<img src="${url}" alt="Profile">`;
  }

  function renderSteps() {
    const perc = Math.round((currentStep / stepsData.length) * 100);
    document.getElementById('progressFill').style.width = perc + '%';
    document.getElementById('progressTxt').innerText = perc + '%';

    const list = document.getElementById('dynamicSteps');
    list.innerHTML = stepsData.map(s => {
      const isDone = s.id < currentStep;
      const isActive = s.id === currentStep;
      
      return `
        <li class="step-item ${isActive ? 'active' : ''}">
          <span class="step-icon ${isDone ? 'completed' : (isActive ? 'current' : '')}">
            ${isDone ? '<i class="fas fa-check"></i>' : (isActive ? '<i class="fas fa-spinner fa-spin"></i>' : s.id)}
          </span>
          <div class="step-content">
            <div class="step-title" style="color:${isDone ? 'var(--accent)' : 'white'}">${s.title}</div>
            <div class="step-description">${s.desc}</div>
            ${isActive && s.id === 3 ? renderOwnerForm() : ''}
            ${isActive && s.id === 4 ? renderImageForm() : ''}
            ${isActive && s.id !== 3 && s.id !== 4 ? `<button class="primary-btn" onclick="moveNext()">Continue</button>` : ''}
          </div>
        </li>
      `;
    }).join('');
  }

  function renderOwnerForm() {
    return `
      <div class="step-form-container">
        <div class="form-grid">
          <div class="input-group"><label>Owner ID</label><input value="${user.id}" disabled></div>
          <div class="input-group"><label>First Name</label><input value="${profile.first_name}" disabled></div>
          <div class="input-group"><label>Last Name</label><input value="${profile.last_name}" disabled></div>
          <div class="input-group"><label>Email</label><input value="${user.email}" disabled></div>
          <div class="input-group"><label>Nickname</label><input id="o_nick"></div>
          <div class="input-group"><label>DOB</label><input type="date" id="o_dob"></div>
          <div class="input-group"><label>POB</label><input id="o_pob"></div>
          <div class="input-group"><label>Nationality</label><input id="o_nat" value="Filipino"></div>
          <div class="input-group"><label>Sex</label><select id="o_sex"><option>Male</option><option>Female</option></select></div>
          <div class="input-group"><label>Status</label><select id="o_mar"><option>Single</option><option>Married</option></select></div>
          <div class="input-group"><label>Spouse</label><input id="o_spo"></div>
          <div class="input-group"><label>Contact</label><input id="o_pho"></div>
          <div class="input-group" style="grid-column: span 2;"><label>Address</label><input id="o_adr"></div>
          <div class="input-group"><label>Enterprise</label><input id="o_ent"></div>
          <div class="input-group"><label>Designation</label><input id="o_des"></div>
          <div class="input-group"><label>Affiliations</label><input id="o_aff"></div>
          <div class="input-group"><label>Education</label><input id="o_hea"></div>
        </div>
        <button class="primary-btn" id="saveBtn" onclick="saveOwnerInfo()">
          <i class="fas fa-circle-notch spinner" id="saveSpin"></i> <span>Save & Continue</span>
        </button>
      </div>`;
  }

  function renderImageForm() {
    return `
      <div class="step-form-container" style="text-align: center;">
        <div id="imagePreview"><i class="fas fa-user" style="font-size: 40px; color: #333;"></i></div>
        <input type="file" id="profileFile" accept="image/*" onchange="previewImg(this)" style="margin-bottom: 10px; font-size: 11px;">
        <button class="primary-btn" id="upBtn" onclick="uploadImg()" style="width: 100%;">
          <i class="fas fa-circle-notch spinner" id="upSpin"></i> <span>Upload Profile Photo</span>
        </button>
      </div>`;
  }

  function previewImg(input) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = e => document.getElementById('imagePreview').innerHTML = `<img src="${e.target.result}">`;
      reader.readAsDataURL(input.files[0]);
    }
  }

  async function uploadImg() {
    const file = document.getElementById('profileFile').files[0];
    if (!file) return alert("Select a file");
    
    const btn = document.getElementById('upBtn');
    const spin = document.getElementById('upSpin');
    btn.disabled = true; spin.style.display = 'inline-block';

    // Use a path that includes user ID for the storage policy
    const filePath = `${user.id}/${Date.now()}_${file.name}`;
    
    const { data, error } = await sb.storage.from('avatars').upload(filePath, file);
    if (error) { alert(error.message); btn.disabled = false; return; }

    const { data: { publicUrl } } = sb.storage.from('avatars').getPublicUrl(filePath);
    
    // Update DB
    await sb.from('owner_profile').update({ profile_pic_url: publicUrl }).eq('owner_ID', user.id);
    
    // Update Sidebar Immediately
    updateSidebarAvatar(publicUrl);
    
    moveNext();
  }

  async function saveOwnerInfo() {
    const btn = document.getElementById('saveBtn');
    const spin = document.getElementById('saveSpin');
    btn.disabled = true; spin.style.display = 'inline-block';

    const payload = {
      owner_ID: user.id,
      owner_name: `${profile.first_name} ${profile.last_name}`,
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

    const { error } = await sb.from('owner_profile').upsert([payload]);
    if (!error) moveNext();
    else { alert(error.message); btn.disabled = false; spin.style.display = 'none'; }
  }

  async function moveNext() {
    currentStep++;
    await sb.from('user_profiles').update({ current_step: currentStep }).eq('id', user.id);
    renderSteps();
  }

  function handleLogout() { sb.auth.signOut().then(() => window.location.href = 'login.php'); }
  window.onload = init;
</script>
</body>
</html>