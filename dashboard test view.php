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

    <main style="flex: 1; padding: 40px; overflow-y: auto;">
  <div id="admin_list_view">
    <div class="card">
      <h2 style="font-size: 18px; margin-bottom: 20px;">Pending MSME Applications</h2>
      <div id="msme_user_list" style="display: flex; flex-direction: column; gap: 10px;">
        <p style="color: var(--text-muted);">Fetching users...</p>
      </div>
    </div>
  </div>

  <div id="admin_review_panel" style="display: none;">
    </div>
</main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
<script>
  const S_URL = 'https://hmxrblblcpbikkxcwwni.supabase.co';
  const S_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhteHJibGJsY3BiaWtreGN3d25pIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzIyODY0MDksImV4cCI6MjA4Nzg2MjQwOX0.qC4Lm2KbToc0f1syHpMWJmQqRhQTosNfFzBrfTXSWDw'; 
  const sb = supabase.createClient(S_URL, S_KEY);

  // Initialize Admin View
window.onload = () => {
  fetchMSMEUsers();
};

async function fetchMSMEUsers() {
  const listContainer = document.getElementById('msme_user_list');
  
  // Log attempt
  console.log("Fetching MSME users...");

  const { data: companies, error } = await sb
    .from('company_profile')
    .select('user_id, enterprise_name, contact_number');

  if (error) {
    console.error("Supabase Error:", error);
    listContainer.innerHTML = `<p style="color:red">Error: ${error.message}</p>`;
    return;
  }

  // LOG THE DATA: If this is [], your RLS is blocking you.
  console.log("Retrieved Data:", companies);

  if (!companies || companies.length === 0) {
    listContainer.innerHTML = `<p style="color: var(--text-muted); padding: 20px;">No pending applications found or Access Denied (RLS).</p>`;
    return;
  }

  listContainer.innerHTML = companies.map(biz => `
    <div class="repo-item" style="display: flex; justify-content: space-between; align-items: center; text-align: left; margin-bottom: 10px;">
      <div>
        <strong style="color: var(--accent);">${biz.enterprise_name || 'Unnamed Business'}</strong>
        <div style="font-size: 11px; color: var(--text-muted);">UID: ${biz.user_id}</div>
      </div>
      <button class="primary-btn" style="width: auto; padding: 5px 15px; font-size: 12px;" 
        onclick="openReview('${biz.user_id}')">REVIEW DATA</button>
    </div>
  `).join('');
}

async function openReview(userId) {
  // 1. Show the panel and hide the list
  document.getElementById('admin_list_view').style.display = 'none';
  const panel = document.getElementById('admin_review_panel');
  panel.style.display = 'block';
  
  // 2. Inject the HTML structure
  panel.innerHTML = renderAdminReviewView(userId);
  
  // 3. Load the specific data
  await loadAdminData(userId);
}

function closeAdminView() {
  document.getElementById('admin_list_view').style.display = 'block';
  document.getElementById('admin_review_panel').style.display = 'none';
}

async function loadAdminData(userId) {
  const { data: owner } = await sb.from('owner_profile').select('*').eq('owner_ID', userId).single();
  const { data: company } = await sb.from('company_profile').select('*').eq('user_id', userId).single();

  if (owner) {
    document.getElementById('adm_user_title').innerText = owner.owner_name || "Unknown";
    document.getElementById('adm_o_nick').value = owner.owner_nickname || "";
    document.getElementById('adm_o_sex').value = owner.owner_sex || "";
    document.getElementById('adm_o_pob').value = owner.owner_pob || "";
    document.getElementById('adm_view_photo').href = owner.profile_pic_url || "#";
  }

  if (company) {
    document.getElementById('adm_c_name').value = company.enterprise_name || "";
    document.getElementById('adm_c_phone').value = company.contact_number || ""; // Use exact schema name
    document.getElementById('adm_c_email').value = company.enterprise_email || "";        // Use exact schema name
}
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
  
async function saveAdminEdits(userId) {
    const btn = document.querySelector('button[onclick*="saveAdminEdits"]');
    btn.innerText = "Saving...";

    const ownerUpdates = {
        owner_nickname: document.getElementById('adm_o_nick').value,
        owner_sex: document.getElementById('adm_o_sex').value,
        owner_pob: document.getElementById('adm_o_pob').value
    };

    const companyUpdates = {
        enterprise_name: document.getElementById('adm_c_name').value,
        // CHECK YOUR TABLE: Is it 'contact_numb' or 'contact_number'? 
        // Based on your earlier error, it is likely 'contact_numb'
        contact_number: document.getElementById('adm_c_phone').value, 
        // CHECK YOUR TABLE: Is it 'email' or 'enterprise_email'?
        enterprise_email: document.getElementById('adm_c_email').value 
    };

    // Execute updates
    const res1 = await sb.from('owner_profile').update(ownerUpdates).eq('owner_ID', userId);
    const res2 = await sb.from('company_profile').update(companyUpdates).eq('user_id', userId);

    if (res2.error) {
        console.error("Company Table Error Detail:", res2.error);
        alert(`Company Update Failed: ${res2.error.message}`);
    } else if (res1.error) {
        alert(`Owner Update Failed: ${res1.error.message}`);
    } else {
        alert("All changes saved successfully!");
        closeAdminView();
        fetchMSMEUsers();
    }
    btn.innerText = "Save All Changes";
}

  // Perform updates
  const res1 = await sb.from('owner_profile').update(updates.owner).eq('owner_ID', userId);
  const res2 = await sb.from('company_profile').update(updates.company).eq('user_id', userId);

  if (res1.error || res2.error) {
    console.error("Owner Update Error:", res1.error);
    console.error("Company Update Error:", res2.error);
    alert("Update failed! check console for RLS or Schema errors.");
  } else {
    alert("Changes saved successfully!");
  }
  btn.innerText = "Save All Changes";
}

</script>
</body>
</html>