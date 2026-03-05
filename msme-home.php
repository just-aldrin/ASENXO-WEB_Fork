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

  function renderOwnerForm() {
    return `
      <div style="background: rgba(128,128,128,0.03); border: 1px solid var(--border-color); border-radius: 12px; padding: 20px;">
        <div class="form-grid">
          <div class="input-group"><label>Nickname</label><input id="o_nick" placeholder="e.g. aldrin"></div>
          <div class="input-group"><label>Date of birth</label><input type="date" id="o_dob"></div>
          <div class="input-group"><label>Place of birth</label><input id="o_pob" placeholder="City/Town"></div>
          <div class="input-group"><label>Nationality</label><input id="o_nat" value="Filipino"></div>
          <div class="input-group"><label>Sex</label><select id="o_sex"><option>Male</option><option>Female</option></select></div>
          <div class="input-group"><label>Marital status</label><select id="o_mar"><option>single</option><option>married</option></select></div>
        </div>
        <button class="primary-btn" id="saveBtn" style="margin-top: 20px;" onclick="saveOwnerInfo()">Save & Continue</button>
      </div>`;
  }

  function renderImageForm() {
    return `
      <div style="text-align: center; background: rgba(128,128,128,0.03); border: 1px solid var(--border-color); border-radius: 12px; padding: 25px;">
        <div id="imagePreview"><i class="fas fa-user" style="font-size: 30px; color: #333;"></i></div>
        <input type="file" id="profileFile" accept="image/*" onchange="previewImg(this)" style="margin-bottom: 15px; font-size: 11px;">
        <button class="primary-btn" id="upBtn" onclick="uploadImg()">Upload Photo</button>
      </div>`;
  }

  function renderBusinessForm() {
    return `
      <div style="background: rgba(128,128,128,0.03); border: 1px solid var(--border-color); border-radius: 12px; padding: 25px; margin-top: 15px;">
        <h4 style="margin: 0 0 20px 0; font-size: 14px; color: var(--accent);">Enterprise Details</h4>
        <div class="form-grid">
          <div class="input-group"><label>Enterprise ID</label><input id="c_eid" placeholder="Auto-generated"></div>
          <div class="input-group"><label>Enterprise Name</label><input id="c_name"></div>
          <div class="input-group" style="grid-column: span 2;"><label>Enterprise Address</label><input id="c_addr"></div>
          
          <div class="input-group"><label>Latitude</label><input type="number" step="any" id="c_lat" placeholder="e.g. 14.5995"></div>
          <div class="input-group"><label>Longitude</label><input type="number" step="any" id="c_long" placeholder="e.g. 120.9842"></div>
          
          <div class="input-group"><label>Contact Person</label><input id="c_cp"></div>
          <div class="input-group"><label>Enterprise Email</label><input type="email" id="c_email"></div>
          <div class="input-group"><label>Year Established</label><input type="number" id="c_year" value="2024"></div>
          <div class="input-group"><label>Current Capitalization</label><input type="number" step="0.01" id="c_cap"></div>
          <div class="input-group"><label>Organization Type</label>
            <select id="c_org">
              <option>Sole Proprietorship</option>
              <option>Partnership</option>
              <option>Cooperative</option>
              <option>Corporation</option>
            </select>
          </div>
          <div class="input-group"><label>Business Type</label>
              <select id="c_btype">
                <option>Profit</option>
                <option>Non-Profit</option>
              </select>
          </div>
          <div class="input-group"><label>MSME Type</label>
            <select id="c_mtype">
              <option>Micro</option>
              <option>Small</option>
              <option>Medium</option>
              <option>Enterprise</option>
            </select>
          </div>
          <div class="input-group"><label>Industry Sector</label><input id="c_sector"></div>
          <h4 style="margin: 25px 0 15px 0; font-size: 13px; color: var(--text-muted); text-transform: uppercase;">Regulatory Registrations</h4>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
        <div class="input-group"><label>DTI Registration No.</label><input id="c_dti"></div>
        <div class="input-group"><label>Date of Registration</label><input type="date" id="c_dti_date"></div>
        
        <div class="input-group"><label>SEC Registration No.</label><input id="c_sec"></div>
        <div class="input-group"><label>Date of Registration</label><input type="date" id="c_sec_date"></div>
        
        <div class="input-group"><label>CDA Registration No.</label><input id="c_cda"></div>
        <div class="input-group"><label>Date of Registration</label><input type="date" id="c_cda_date"></div>
        
        <div class="input-group"><label>Other</label><input id="c_other" placeholder="e.g. Mayor's Permit No."></div>
        <div class="input-group"><label>Registration Date</label><input type="date" id="c_other_date"></div>
      </div>
        </div>

        <h4 style="margin: 30px 0 15px 0; font-size: 14px; color: var(--accent); border-top: 1px solid var(--border-color); padding-top: 20px;">Employment Information</h4>
      
      <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 10px; align-items: center; margin-bottom: 10px; border-bottom: 1px solid var(--border-color); padding-bottom: 5px;">
        <div style="font-size: 11px; color: var(--text-muted); font-weight: 800;">TYPE OF EMPLOYMENT</div>
        <div style="font-size: 11px; color: var(--text-muted); font-weight: 800; text-align: center;">MALE</div>
        <div style="font-size: 11px; color: var(--text-muted); font-weight: 800; text-align: center;">FEMALE</div>
      </div>

      <div style="font-size: 12px; font-weight: 700; margin: 10px 0;">Direct Workers</div>
      <div class="form-grid" style="grid-template-columns: 2fr 1fr 1fr; margin-bottom: 10px;">
        <div style="font-size: 11px; display: flex; align-items: center;">Production</div>
        <input type="number" id="e_dp_m" value="0">
        <input type="number" id="e_dp_f" value="0">
        
        <div style="font-size: 11px; display: flex; align-items: center;">Non-Production</div>
        <input type="number" id="e_dnp_m" value="0">
        <input type="number" id="e_dnp_f" value="0">
      </div>

      <div style="font-size: 12px; font-weight: 700; margin: 20px 0 10px 0;">Indirect / Contract Workers</div>
      <div class="form-grid" style="grid-template-columns: 2fr 1fr 1fr;">
        <div style="font-size: 11px; display: flex; align-items: center;">Production</div>
        <input type="number" id="e_ip_m" value="0">
        <input type="number" id="e_ip_f" value="0">
        
        <div style="font-size: 11px; display: flex; align-items: center;">Non-Production</div>
        <input type="number" id="e_inp_m" value="0">
        <input type="number" id="e_inp_f" value="0">
      </div>

      <button class="primary-btn" id="saveBizBtn" style="margin-top: 30px;" onclick="saveBusinessInfo()">Save & Continue</button>
      </div>`;
  }

  async function saveBusinessInfo() {
  const btn = document.getElementById('saveBizBtn');
  btn.disabled = true; btn.innerText = "Saving...";

  const payload = {
    user_id: user.id, 
    enterprise_name: document.getElementById('c_name').value,
    enterprise_address: document.getElementById('c_addr').value,
    enterprise_lat: parseFloat(document.getElementById('c_lat').value) || 0,
    enterprise_long: parseFloat(document.getElementById('c_long').value) || 0,
    contact_person: document.getElementById('c_cp').value,
    enterprise_email: document.getElementById('c_email').value,
    year_established: parseInt(document.getElementById('c_year').value) || 0, 
    current_capitalization: parseFloat(document.getElementById('c_cap').value) || 0,
    organization_type: document.getElementById('c_org').value,
    business_type: document.getElementById('c_btype').value,
    msme_type: document.getElementById('c_mtype').value,
    industry_sector: document.getElementById('c_sector').value,
    DTI_reg_num: document.getElementById('c_dti').value,
    dti_reg_date: document.getElementById('c_dti_date').value || null,
    SEC_reg_num: document.getElementById('c_sec').value,
    sec_reg_date: document.getElementById('c_sec_date').value || null,
    CDA_reg_num: document.getElementById('c_cda').value,
    cda_reg_date: document.getElementById('c_cda_date').value || null,
    others: document.getElementById('c_other').value,
    other_reg_date: document.getElementById('c_other_date').value || null,
    emp_direct_prod_male: parseInt(document.getElementById('e_dp_m').value) || 0,
    emp_direct_prod_female: parseInt(document.getElementById('e_dp_f').value) || 0,
    emp_direct_nonprod_male: parseInt(document.getElementById('e_dnp_m').value) || 0,
    emp_direct_nonprod_female: parseInt(document.getElementById('e_dnp_f').value) || 0,
    emp_indirect_prod_male: parseInt(document.getElementById('e_ip_m').value) || 0,
    emp_indirect_prod_female: parseInt(document.getElementById('e_ip_f').value) || 0,
    emp_indirect_nonprod_male: parseInt(document.getElementById('e_inp_m').value) || 0,
    emp_indirect_nonprod_female: parseInt(document.getElementById('e_inp_f').value) || 0 
  };

  const { error } = await sb
    .from('company_profile')
    .upsert(payload, { onConflict: 'user_id' });

  if (!error) {
    moveNext();
  } else {
    console.error("Detailed Error:", error);
    alert("Error: " + error.message);
    btn.disabled = false;
    btn.innerText = "Save & Continue";
  }
}

  async function moveNext() {
    currentStep++;
    await sb.from('user_profiles').update({ current_step: currentStep }).eq('id', user.id);
    renderSteps();
  }

  async function saveOwnerInfo() {
    const btn = document.getElementById('saveBtn');
    btn.disabled = true; 
    const payload = {
      owner_ID: user.id,
      owner_name: `${profile.first_name} ${profile.last_name}`,
      owner_nickname: document.getElementById('o_nick').value,
      owner_dob: document.getElementById('o_dob').value,
      owner_pob: document.getElementById('o_pob').value,
      owner_nationality: document.getElementById('o_nat').value,
      owner_sex: document.getElementById('o_sex').value,
      owner_marstat: document.getElementById('o_mar').value,
      owner_email: user.email
    };
    const { error } = await sb.from('owner_profile').upsert([payload]);
    if (!error) moveNext();
    else { alert(error.message); btn.disabled = false; }
  }

  function previewImg(input) {
    if (input.files?.[0]) {
      const reader = new FileReader();
      reader.onload = e => document.getElementById('imagePreview').innerHTML = `<img src="${e.target.result}">`;
      reader.readAsDataURL(input.files[0]);
    }
  }

  async function uploadImg() {
    const file = document.getElementById('profileFile').files[0];
    if (!file) return alert("Select a file first");
    const filePath = `${user.id}/${Date.now()}_${file.name}`;
    const { data, error } = await sb.storage.from('avatars').upload(filePath, file);
    if (!error) {
      const { data: { publicUrl } } = sb.storage.from('avatars').getPublicUrl(filePath);
      await sb.from('owner_profile').update({ profile_pic_url: publicUrl }).eq('owner_ID', user.id);
      moveNext();
    }
  }

  function toggleTheme() { document.body.classList.toggle('light-theme'); }
  function handleLogout() { sb.auth.signOut().then(() => window.location.href = 'login.php'); }
  window.onload = init;
</script>
</body>
</html>