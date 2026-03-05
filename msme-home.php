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
  
  <style>
    :root { 
      --accent: #2ecc71; 
      --bg-body: #000000;
      --card-bg: #111111;
      --border-color: #222222;
      --text-main: #ffffff;
      --text-muted: #666666;
      --input-bg: #000000;
    }

    body.light-theme {
      --bg-body: #f5f5f5;
      --card-bg: #ffffff;
      --border-color: #e0e0e0;
      --text-main: #1a1a1a;
      --text-muted: #888888;
      --input-bg: #f9f9f9;
    }

    body {
      font-family: 'Bricolage Grotesque', sans-serif;
      background-color: var(--bg-body);
      margin: 0;
      color: var(--text-main);
      transition: background 0.3s, color 0.3s;
    }

    /* Fixed Centering for Check Icons */
    .step-icon {
      display: flex !important;
      align-items: center;
      justify-content: center;
      width: 28px;
      height: 28px;
      border-radius: 50%;
      flex-shrink: 0;
    }

    .step-icon.completed { background: var(--accent); color: #000; }
    .step-icon.current { background: transparent; border: 2px solid var(--accent); color: var(--accent); }

    /* Form Styling */
    .input-group label { 
      font-size: 11px; 
      color: var(--text-muted); 
      font-weight: 600; 
      margin-bottom: 4px;
      display: block;
    }

    .input-group input, .input-group select {
      width: 100%;
      background: var(--input-bg);
      border: 1px solid var(--border-color);
      color: var(--text-main);
      padding: 10px;
      border-radius: 8px;
      font-family: inherit;
      box-sizing: border-box;
    }

    .card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px; padding: 20px; margin-bottom: 15px; }

    /* File Repository Card */
    .repo-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 15px; }
    .repo-item { padding: 15px; background: rgba(128,128,128,0.05); border-radius: 10px; text-align: center; border: 1px solid var(--border-color); }
    .repo-val { font-size: 20px; font-weight: 800; display: block; color: var(--accent); }
    .repo-lab { font-size: 10px; color: var(--text-muted); font-weight: 700; text-transform: uppercase; }

    .primary-btn {
      background: var(--accent);
      color: #000;
      border: none;
      padding: 12px;
      border-radius: 8px;
      font-weight: 700;
      cursor: pointer;
      font-family: inherit;
    }

    .theme-toggle { cursor: pointer; background: var(--card-bg); border: 1px solid var(--border-color); color: var(--text-main); padding: 8px 12px; border-radius: 8px; }
  </style>
</head>
<body> 

<div class="app">
  <header style="height: 60px; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; padding: 0 25px; background: var(--card-bg);">
    <div style="display: flex; align-items: center; gap: 10px;">
      <span style="font-weight: 900; letter-spacing: -0.5px; font-size: 1.2rem;">ASENXO</span>
      <span style="background: var(--accent); color: #000; font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 4px;">PRODUCTION</span>
    </div>
    <div style="display: flex; gap: 12px;">
      <button class="theme-toggle" id="themeToggle" onclick="toggleTheme()"><i class="fas fa-moon"></i></button>
      <button onclick="handleLogout()" style="background:#ef4444; border:none; color:white; padding:8px 16px; border-radius:8px; font-size:12px; font-weight:700; cursor:pointer;">Logout</button>
    </div>
  </header>

  <div style="display: flex; min-height: calc(100vh - 60px);">
    <nav style="width: 260px; border-right: 1px solid var(--border-color); padding: 25px; display: flex; flex-direction: column;">
      <div style="display: flex; align-items: center; margin-bottom: 40px; background: rgba(128,128,128,0.05); padding: 12px; border-radius: 12px; border: 1px solid var(--border-color);">
        <div id="sidebarAvatar" style="width: 40px; height: 40px; border-radius: 50%; background: #333; margin-right: 12px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
          <i class="fas fa-user" style="color: #666;"></i>
        </div>
        <div>
          <div id="sidebarName" style="font-weight: 700; font-size: 14px;">Loading...</div>
          <div style="font-size: 10px; color: var(--accent); font-weight: 700;">MSME OWNER</div>
        </div>
      </div>

      <ul style="list-style: none; padding: 0; margin: 0;">
        <li style="padding: 12px; background: rgba(46, 204, 113, 0.1); color: var(--accent); border-radius: 8px; font-weight: 700; margin-bottom: 5px;">
          <i class="fas fa-home" style="margin-right: 12px;"></i> Dashboard
        </li>
        <li style="padding: 12px; opacity: 0.5; cursor: not-allowed;"><i class="fas fa-folder" style="margin-right: 12px;"></i> Applications</li>
        <li style="padding: 12px; opacity: 0.5; cursor: not-allowed;"><i class="fas fa-wallet" style="margin-right: 12px;"></i> My Wallet</li>
        <li style="padding: 12px; opacity: 0.5; cursor: not-allowed;"><i class="fas fa-cog" style="margin-right: 12px;"></i> Settings</li>
      </ul>
    </nav>

    <main style="flex: 1; padding: 40px; display: grid; grid-template-columns: 1.8fr 1fr; gap: 30px; background-color: var(--bg-body);">
      
      <section>
        <div class="card">
          <h2 style="margin: 0 0 25px 0; font-size: 18px; font-weight: 800;"><i class="fas fa-stream" style="color: var(--accent); margin-right: 12px;"></i> Application Flow</h2>
          <ul id="dynamicSteps" style="list-style: none; padding: 0; margin: 0;"></ul>
        </div>
      </section>

      <aside>
        <div class="card">
          <h3 style="margin-top: 0; font-size: 15px;">Overview</h3>
          <div style="margin: 20px 0;">
            <div style="display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 8px;">
              <span style="color: var(--text-muted);">Registration Progress</span>
              <span id="progressTxt" style="font-weight: 800; color: var(--accent);">0%</span>
            </div>
            <div style="height: 8px; background: var(--border-color); border-radius: 10px; overflow: hidden;">
              <div id="progressFill" style="width: 0%; height: 100%; background: var(--accent); transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);"></div>
            </div>
          </div>
        </div>

        <div class="card">
          <h3 style="margin-top: 0; font-size: 15px;"><i class="fas fa-archive" style="margin-right: 10px; color: var(--accent);"></i> File Repository</h3>
          <div class="repo-grid">
            <div class="repo-item">
              <span class="repo-val" id="filesUploaded">0</span>
              <span class="repo-lab">Uploaded</span>
            </div>
            <div class="repo-item">
              <span class="repo-val" id="filesPending" style="color: #f1c40f;">0</span>
              <span class="repo-lab">Pending</span>
            </div>
          </div>
        </div>
      </aside>

    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
<script>
  const S_URL = 'https://your-project.supabase.co';
  const S_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhteHJibGJsY3BiaWtreGN3d25pIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzIyODY0MDksImV4cCI6MjA4Nzg2MjQwOX0.qC4Lm2KbToc0f1syHpMWJmQqRhQTosNfFzBrfTXSWDw'; 
  const sb = supabase.createClient(S_URL, S_KEY);

  let user = null;
  let currentStep = 3;

  const stepsData = [
    { id: 1, title: "Account Selection", desc: "Entity type chosen" },
    { id: 2, title: "Identity Security", desc: "Verify mobile & email" },
    { id: 3, title: "Complete Your Information", desc: "Detailed owner profile" },
    { id: 4, title: "Profile Image", desc: "Upload your photo" }
  ];

  async function init() {
    const { data: { user: u } } = await sb.auth.getUser();
    if (!u) return window.location.href = 'login-mock.php';
    user = u;

    const { data: p } = await sb.from('user_profiles').select('*').eq('id', user.id).single();
    if (p) {
      currentStep = p.current_step || 3;
      document.getElementById('sidebarName').innerText = `${p.first_name} ${p.last_name}`;
      renderSteps();
    }
  }

  function toggleTheme() {
    document.body.classList.toggle('light-theme');
    const icon = document.querySelector('#themeToggle i');
    icon.className = document.body.classList.contains('light-theme') ? 'fas fa-sun' : 'fas fa-moon';
  }

  function renderSteps() {
    const perc = Math.round((currentStep / 6) * 100); // Assuming 6 total steps
    document.getElementById('progressFill').style.width = perc + '%';
    document.getElementById('progressTxt').innerText = perc + '%';

    const list = document.getElementById('dynamicSteps');
    list.innerHTML = stepsData.map(s => {
      const isDone = s.id < currentStep;
      const isActive = s.id === currentStep;
      
      return `
        <li style="display: flex; gap: 20px; margin-bottom: 30px; position: relative;">
          <div class="step-icon ${isDone ? 'completed' : (isActive ? 'current' : '')}">
            ${isDone ? '<i class="fas fa-check" style="font-size: 12px;"></i>' : '<i class="fas fa-circle" style="font-size: 6px;"></i>'}
          </div>
          <div style="flex: 1;">
            <div style="font-size: 15px; font-weight: 700; color: ${isActive ? 'var(--accent)' : 'var(--text-main)'}">${s.title}</div>
            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: ${isActive ? '15px' : '0'}">${s.desc}</div>
            ${isActive && s.id === 3 ? renderOwnerForm() : ''}
          </div>
        </li>
      `;
    }).join('');
  }

  function renderOwnerForm() {
    return `
      <div style="background: rgba(128,128,128,0.05); border: 1px solid var(--border-color); border-radius: 12px; padding: 25px; margin-top: 10px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
          <div class="input-group"><label>Nickname</label><input id="o_nick" placeholder="e.g. AJ"></div>
          <div class="input-group"><label>Date of birth</label><input type="date" id="o_dob"></div>
          <div class="input-group"><label>Place of birth</label><input id="o_pob"></div>
          <div class="input-group"><label>Nationality</label><input id="o_nat" value="Filipino"></div>
          <div class="input-group"><label>Sex</label><select id="o_sex"><option>Male</option><option>Female</option></select></div>
          <div class="input-group"><label>Contact number</label><input id="o_pho" placeholder="09xxxxxxxxx"></div>
        </div>
        <button class="primary-btn" style="width: 100%; margin-top: 25px;" onclick="moveNext()">Save & Continue</button>
      </div>`;
  }

  async function moveNext() {
    currentStep++;
    // Update Supabase logic here
    renderSteps();
  }

  function handleLogout() { window.location.href = 'login-mock.php'; }
  window.onload = init;
</script>
</body>
</html>