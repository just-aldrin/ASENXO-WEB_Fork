<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ASENXO | Dashboard Test Viewport</title>
  
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
      --input-bg: #0a0a0a;
    }

    body.light-theme {
      --bg-body: #f5f5f5;
      --card-bg: #ffffff;
      --border-color: #e0e0e0;
      --text-main: #1a1a1a;
      --text-muted: #888888;
      --input-bg: #fdfdfd;
    }

    body {
      font-family: 'Bricolage Grotesque', sans-serif;
      background-color: var(--bg-body);
      margin: 0; color: var(--text-main);
      transition: background 0.3s, color 0.3s;
      overflow-x: hidden;
    }

    .step-icon {
      display: flex !important;
      align-items: center;
      justify-content: center;
      width: 28px; height: 28px;
      border-radius: 50%;
      flex-shrink: 0;
    }
    .step-icon.completed { background: var(--accent); color: #000; }
    .step-icon.current { background: transparent; border: 2px solid var(--accent); color: var(--accent); }

    .card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px; padding: 25px; margin-bottom: 20px; }
    
    .sidebar-menu { list-style: none; padding: 0; margin: 0; flex-grow: 1; }
    .sidebar-menu li { 
        padding: 12px 15px; 
        border-radius: 8px; 
        margin-bottom: 5px; 
        cursor: pointer; 
        font-size: 14px; 
        display: flex; 
        align-items: center; 
        gap: 12px;
        transition: 0.2s;
        color: var(--text-muted);
    }
    .sidebar-menu li.active { background: rgba(46, 204, 113, 0.1); color: var(--accent); font-weight: 700; }
    .sidebar-menu li:hover:not(.active) { background: rgba(255,255,255,0.03); color: var(--text-main); }

    .input-group label { font-size: 11px; color: var(--text-muted); font-weight: 600; margin-bottom: 5px; display: block; }
    .input-group input, .input-group select {
      width: 100%; background: var(--input-bg); border: 1px solid var(--border-color);
      color: var(--text-main); padding: 10px; border-radius: 8px; font-family: inherit; box-sizing: border-box;
    }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }

    .primary-btn {
      background: var(--accent); color: #000; border: none; padding: 12px;
      border-radius: 8px; font-weight: 800; cursor: pointer; font-family: inherit; width: 100%;
    }

    #imagePreview {
      width: 100px; height: 100px; border-radius: 50%; border: 2px dashed var(--border-color);
      margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; overflow: hidden;
    }
    #imagePreview img { width: 100%; height: 100%; object-fit: cover; }

    .repo-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 15px; }
    .repo-item { padding: 15px; background: rgba(128,128,128,0.05); border-radius: 10px; text-align: center; border: 1px solid var(--border-color); }

    .matrix-input {
  width: 90%;
  background: var(--input-bg) !important;
  border: 1px solid var(--border-color);
  color: var(--text-main);
  padding: 8px;
  border-radius: 6px;
  text-align: center;
  font-family: inherit;
  font-weight: 600;
  transition: border-color 0.2s;
}

.matrix-input:focus {
  border-color: var(--accent);
  outline: none;
}

/* Removes arrows from number inputs for a cleaner table look */
.matrix-input::-webkit-outer-spin-button,
.matrix-input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
  </style>
</head>
<body>

<div class="app">
  <header style="height: 60px; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; padding: 0 25px; background: var(--card-bg);">
    <div style="font-weight: 900; font-size: 1.2rem; display: flex; align-items: center; gap: 10px;">
        ASENXO <span style="background:var(--accent); color:#000; font-size:9px; padding:2px 5px; border-radius:4px; font-weight: 800;">PRODUCTION</span>
    </div>
    <div style="display: flex; gap: 10px;">
      <button onclick="toggleTheme()" style="background:none; border:1px solid var(--border-color); color:var(--text-main); padding:8px; border-radius:8px; cursor:pointer;"><i class="fas fa-adjust"></i></button>
      <button onclick="handleLogout()" style="background:#ef4444; color:white; border:none; padding:8px 15px; border-radius:8px; font-weight:700; cursor:pointer;">Logout</button>
    </div>
  </header>

  <div style="display: flex; height: calc(100vh - 60px);">
    <nav style="width: 260px; border-right: 1px solid var(--border-color); padding: 20px; display: flex; flex-direction: column; justify-content: space-between; background: var(--bg-body);">
      <div>
        <div style="font-size: 11px; font-weight: 800; color: var(--text-muted); margin-bottom: 15px; padding-left: 10px; letter-spacing: 0.5px;">MSME DASHBOARD</div>
        <ul class="sidebar-menu">
            <li class="active"><i class="fas fa-cube"></i> Application Module</li>
            <li><i class="fas fa-chart-line"></i> Progress Monitoring</li>
            <li><i class="fas fa-cloud-upload-alt"></i> Document Upload History</li>
            <li><i class="fas fa-history"></i> Revisions</li>
            <li><i class="fas fa-file-alt"></i> Forms for Requirements</li>
            <li><i class="fas fa-cog"></i> Settings</li>
        </ul>
      </div>

      <div style="display: flex; align-items: center; gap: 12px; padding: 15px; background: rgba(128,128,128,0.05); border-radius: 12px; border: 1px solid var(--border-color);">
        <div id="sidebarAvatar" style="width: 38px; height: 38px; border-radius: 50%; background: #222; overflow: hidden; display: flex; align-items: center; justify-content: center; border: 1px solid var(--border-color); flex-shrink: 0;">
          <i class="fas fa-user"></i>
        </div>
        <div style="overflow: hidden;">
          <div id="sidebarName" style="font-weight: 700; font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Loading...</div>
          <div style="font-size: 10px; color: var(--accent); font-weight: 800;">VERIFIED OWNER</div>
        </div>
      </div>
    </nav>

    <main style="flex: 1; padding: 40px; display: grid; grid-template-columns: 1.8fr 1fr; gap: 30px; overflow-y: auto;">
      <section>
        <div class="card">
          <h2 style="font-size: 18px; margin-bottom: 25px;"><i class="fas fa-tasks" style="color: var(--accent); margin-right: 12px;"></i> Application Flow</h2>
          <ul id="dynamicSteps" style="list-style: none; padding: 0;"></ul>
        </div>
      </section>

      <aside>
        <div class="card">
          <h3 style="margin-top: 0; font-size: 14px; font-weight: 800;">Overview</h3>
          <div style="margin: 15px 0;">
            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 8px;">
              <span style="color: var(--text-muted);">Registration Progress</span><span id="progressTxt" style="font-weight: 800; color: var(--accent);">0%</span>
            </div>
            <div style="height: 6px; background: var(--border-color); border-radius: 10px; overflow: hidden;">
              <div id="progressFill" style="width: 0%; height: 100%; background: var(--accent); transition: width 0.8s ease;"></div>
            </div>
          </div>
        </div>

          <div class="card">
            <h3 style="margin-top: 0; font-size: 14px; font-weight: 800;">File Repository</h3>
            <div class="repo-grid">
              <div class="repo-item">
                <span id="filesUploaded" style="font-size: 20px; font-weight: 800; display: block; color: var(--accent);">0</span>
                <span style="font-size: 9px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Uploaded</span>
              </div>
              <div class="repo-item">
                <span id="filesPending" style="font-size: 20px; font-weight: 800; display: block; color:#f1c40f">0</span>
                <span style="font-size: 9px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Pending Review</span>
              </div>
            </div>
          </div>
       </aside>

    </main>
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
    { id: 3, title: "Owner Information", desc: "Detailed personal data" },
    { id: 4, title: "Profile Image", desc: "Upload profile image" },
    { id: 5, title: "Business Information", desc: "Enterprise details" },
    { id: 6, title: "Complete Business Information", desc: "Business details" },
    { id: 7, title: "Account Confirmation", desc: "Review and confirm" },
    { id: 8, title: "Submit Required Documents", desc: "PDF, images" },
    { id: 9, title: "Application Status", desc: "Pending review" },
    { id: 10, title: "Technology Needs Assessment", desc: "Based on survey" },
    { id: 11, title: "Endorsement Status", desc: "Waiting for approval" }
  ];

  async function init() {
    const { data: { session } } = await sb.auth.getSession();
    if (!session) return window.location.href = 'login.php';
    user = session.user;

    const { data: p } = await sb.from('user_profiles').select('*').eq('id', user.id).single();
    if (p) {
      profile = p;
      currentStep = p.current_step || 3;
      document.getElementById('sidebarName').innerText = `${p.first_name} ${p.last_name}`;
      
      const { data: op } = await sb.from('owner_profile').select('profile_pic_url').eq('owner_ID', user.id).single();
      if (op?.profile_pic_url) {
        document.getElementById('sidebarAvatar').innerHTML = `<img src="${op.profile_pic_url}" style="width:100%;height:100%;object-fit:cover;">`;
      }
      renderSteps();
    }
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
        <li style="display: flex; gap: 20px; margin-bottom: 30px;">
          <div class="step-icon ${isDone ? 'completed' : (isActive ? 'current' : '')}">
            ${isDone ? '<i class="fas fa-check" style="font-size: 11px;"></i>' : '<i class="fas fa-circle" style="font-size: 6px;"></i>'}
          </div>
          <div style="flex: 1;">
            <div style="font-size: 15px; font-weight: 700; color: ${isActive ? 'var(--accent)' : 'inherit'}">${s.title}</div>
            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 10px;">${s.desc}</div>
            ${isActive && s.id === 3 ? renderOwnerForm() : ''}
            ${isActive && s.id === 4 ? renderImageForm() : ''}
            ${isActive && s.id === 5 ? renderBusinessForm() : ''}
          </div>
        </li>
      `;
    }).join('');
  }

  function renderAdminReviewView(userId) {
  return `
    <div class="card" style="border-left: 5px solid var(--accent);">
      <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px;">
        <div>
          <h2 style="margin: 0; font-size: 20px;">Reviewing Application: <span id="adm_user_title">Loading...</span></h2>
          <p style="color: var(--text-muted); font-size: 12px;">User ID: ${userId}</p>
        </div>
        <div style="text-align: right;">
          <select id="adm_app_status" class="matrix-input" style="width: 150px; margin-bottom: 10px;">
            <option value="pending">Pending Review</option>
            <option value="approved">Approved</option>
            <option value="revision">Needs Revision</option>
          </select>
          <button class="primary-btn" onclick="updateApplicationStatus('${userId}')" style="padding: 8px 15px;">Update Status</button>
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
        <div>
          <h4 style="color: var(--accent); font-size: 13px; text-transform: uppercase;">Owner Information (Editable)</h4>
          <div class="form-grid" style="margin-bottom: 25px;">
            <div class="input-group"><label>Nickname</label><input id="adm_o_nick"></div>
            <div class="input-group"><label>Sex</label><input id="adm_o_sex"></div>
            <div class="input-group" style="grid-column: span 2;"><label>Place of Birth</label><input id="adm_o_pob"></div>
          </div>

          <h4 style="color: var(--accent); font-size: 13px; text-transform: uppercase;">Enterprise Details</h4>
          <div class="form-grid">
            <div class="input-group" style="grid-column: span 2;"><label>Enterprise Name</label><input id="adm_c_name"></div>
            <div class="input-group"><label>Contact Number</label><input id="adm_c_phone"></div>
            <div class="input-group"><label>Email</label><input id="adm_c_email"></div>
          </div>
        </div>

        <div>
          <h4 style="color: var(--accent); font-size: 13px; text-transform: uppercase;">Document Repository</h4>
          <div id="adm_file_list" style="display: flex; flex-direction: column; gap: 10px;">
            <div class="repo-item" style="text-align: left; display: flex; align-items: center; justify-content: space-between;">
              <span style="font-size: 12px;"><i class="fas fa-image" style="margin-right: 10px;"></i> Profile Photo</span>
              <a href="#" id="adm_view_photo" target="_blank" style="color: var(--accent); font-size: 11px; font-weight: 800;">VIEW FILE</a>
            </div>
          </div>
          
          <div style="margin-top: 20px; padding: 15px; background: rgba(255,165,0,0.1); border: 1px solid orange; border-radius: 8px;">
            <label style="font-size: 11px; color: orange; font-weight: 800;">ADMIN NOTES / FEEDBACK</label>
            <textarea id="adm_feedback" style="width:100%; background:transparent; border:none; color:var(--text-main); font-family:inherit; outline:none;" rows="3" placeholder="Explain what needs to be revised..."></textarea>
          </div>
        </div>
      </div>

      <div style="margin-top: 30px; border-top: 1px solid var(--border-color); padding-top: 20px; display: flex; gap: 10px;">
        <button class="primary-btn" onclick="saveAdminEdits('${userId}')">Save All Changes</button>
        <button style="background: var(--input-bg); color: var(--text-main); border: 1px solid var(--border-color); padding: 12px; border-radius: 8px; flex: 1; cursor: pointer;" onclick="closeAdminView()">Cancel</button>
      </div>
    </div>`;
}

async function loadAdminData(userId) {
  // 1. Fetch Owner Profile
  const { data: owner } = await sb.from('owner_profile').select('*').eq('owner_ID', userId).single();
  // 2. Fetch Company Profile
  const { data: company } = await sb.from('company_profile').select('*').eq('user_id', userId).single();

  if (owner) {
    document.getElementById('adm_user_title').innerText = owner.owner_name;
    document.getElementById('adm_o_nick').value = owner.owner_nickname;
    document.getElementById('adm_o_sex').value = owner.owner_sex;
    document.getElementById('adm_o_pob').value = owner.owner_pob;
    document.getElementById('adm_view_photo').href = owner.profile_pic_url;
  }

  if (company) {
    document.getElementById('adm_c_name').value = company.enterprise_name;
    document.getElementById('adm_c_phone').value = company.contact_number;
    document.getElementById('adm_c_email').value = company.enterprise_email;
  }
}

async function saveAdminEdits(userId) {
  const updates = {
    owner: {
      owner_nickname: document.getElementById('adm_o_nick').value,
      owner_sex: document.getElementById('adm_o_sex').value,
      owner_pob: document.getElementById('adm_o_pob').value
    },
    company: {
      enterprise_name: document.getElementById('adm_c_name').value,
      contact_number: document.getElementById('adm_c_phone').value,
      enterprise_email: document.getElementById('adm_c_email').value
    }
  };

  const { error: err1 } = await sb.from('owner_profile').update(updates.owner).eq('owner_ID', userId);
  const { error: err2 } = await sb.from('company_profile').update(updates.company).eq('user_id', userId);

  if (!err1 && !err2) alert("Record updated successfully!");
}

</script>
</body>
</html>