<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ASENXO | Admin Review Dashboard</title>
  
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

    .card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px; padding: 25px; margin-bottom: 20px; }
    
    .input-group label { font-size: 11px; color: var(--text-muted); font-weight: 600; margin-bottom: 5px; display: block; text-transform: uppercase;}
    .input-group input, .input-group select {
      width: 100%; background: var(--input-bg); border: 1px solid var(--border-color);
      color: var(--text-main); padding: 10px; border-radius: 8px; font-family: inherit; box-sizing: border-box;
    }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;}

    .primary-btn {
      background: var(--accent); color: #000; border: none; padding: 10px 15px;
      border-radius: 8px; font-weight: 800; cursor: pointer; font-family: inherit;
    }
    
    .repo-item { padding: 15px; background: rgba(128,128,128,0.05); border-radius: 10px; border: 1px solid var(--border-color); margin-bottom:10px; }
  </style>
</head>
<body>

<div class="app">
  <header style="height: 60px; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; padding: 0 25px; background: var(--card-bg);">
    <div style="font-weight: 900; font-size: 1.2rem; display: flex; align-items: center; gap: 10px;">
        ASENXO <span style="background:var(--accent); color:#000; font-size:9px; padding:2px 5px; border-radius:4px; font-weight: 800;">ADMIN</span>
    </div>
    <div style="display: flex; gap: 10px;">
      <button onclick="exportToCSV(event)" style="background:transparent; border:1px solid var(--accent); color:var(--accent); padding:8px 15px; border-radius:8px; font-weight:700; cursor:pointer;"><i class="fas fa-file-export"></i> Bulk Export CSV</button>
      
      <button onclick="document.body.classList.toggle('light-theme')" style="background:none; border:1px solid var(--border-color); color:var(--text-main); padding:8px; border-radius:8px; cursor:pointer;"><i class="fas fa-adjust"></i></button>
      <button onclick="handleLogout()" style="background:#ef4444; color:white; border:none; padding:8px 15px; border-radius:8px; font-weight:700; cursor:pointer;">Logout</button>
    </div>
  </header>

  <main style="padding: 40px; max-width: 1100px; margin: 0 auto; overflow-y: auto;">
    
    <div id="admin_list_view">
      <div class="card">
        <h2 style="font-size: 18px; margin-top: 0; margin-bottom: 20px;">Pending MSME Applications</h2>
        <div id="msme_user_list" style="display: flex; flex-direction: column; gap: 10px;">
          <p style="color: var(--text-muted);"><i class="fas fa-spinner fa-spin"></i> Loading applications...</p>
        </div>
      </div>
    </div>

    <div id="admin_review_panel" style="display: none;">
      </div>
  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
<script>
  const S_URL = 'https://hmxrblblcpbikkxcwwni.supabase.co';
  const S_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhteHJibGJsY3BiaWtreGN3d25pIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzIyODY0MDksImV4cCI6MjA4Nzg2MjQwOX0.qC4Lm2KbToc0f1syHpMWJmQqRhQTosNfFzBrfTXSWDw'; 
  const sb = supabase.createClient(S_URL, S_KEY);

  window.onload = () => {
    fetchMSMEUsers();
  };

  async function fetchMSMEUsers() {
    const listContainer = document.getElementById('msme_user_list');
    
    const { data: companies, error } = await sb.from('company_profile').select('user_id, enterprise_name, contact_numb');

    if (error) {
      listContainer.innerHTML = `<p style="color:red">Error loading users: ${error.message}</p>`;
      return;
    }

    if (!companies || companies.length === 0) {
      listContainer.innerHTML = `<p style="color: var(--text-muted); padding: 20px;">No applications found.</p>`;
      return;
    }

    listContainer.innerHTML = companies.map(biz => `
      <div class="repo-item" style="display: flex; justify-content: space-between; align-items: center; text-align: left;">
        <div>
          <strong style="color: var(--accent); font-size:16px;">${biz.enterprise_name || 'Unnamed Enterprise'}</strong>
          <div style="font-size: 11px; color: var(--text-muted); margin-top:4px;">UID: ${biz.user_id}</div>
        </div>
        <button class="primary-btn" onclick="openReview('${biz.user_id}')">Review Data</button>
      </div>
    `).join('');
  }

  function renderAdminReviewView(userId) {
    return `
      <div class="card" style="border-left: 5px solid var(--accent);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px;">
          <div>
            <h2 style="margin: 0; font-size: 20px;">Reviewing: <span id="adm_user_title" style="color:var(--accent);">Loading...</span></h2>
            <p style="color: var(--text-muted); font-size: 12px; margin-top:5px;">User ID: ${userId}</p>
          </div>
          <div>
            <button class="primary-btn" onclick="saveAdminEdits('${userId}')"><i class="fas fa-save"></i> Save All Changes</button>
            <button style="background: transparent; border: 1px solid var(--border-color); color: var(--text-main); padding: 10px 15px; border-radius: 8px; cursor: pointer; margin-left:10px;" onclick="closeAdminView()">Back to List</button>
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
          <div>
            <h4 style="color: var(--accent); font-size: 12px; border-bottom:1px solid var(--border-color); padding-bottom:5px;">Owner Info</h4>
            <div class="form-grid">
              <div class="input-group"><label>Nickname</label><input id="adm_o_nick"></div>
              <div class="input-group"><label>Sex</label><input id="adm_o_sex"></div>
            </div>

            <h4 style="color: var(--accent); font-size: 12px; border-bottom:1px solid var(--border-color); padding-bottom:5px; margin-top:20px;">Enterprise Info</h4>
            <div class="form-grid">
              <div class="input-group" style="grid-column: span 2;"><label>Enterprise Name</label><input id="adm_c_name"></div>
              <div class="input-group"><label>Contact</label><input id="adm_c_phone"></div>
              <div class="input-group"><label>Email</label><input id="adm_c_email"></div>
              <div class="input-group"><label>MSME Type</label><input id="adm_c_type"></div>
              <div class="input-group"><label>Sector</label><input id="adm_c_sector"></div>
            </div>

            <div class="input-group" style="margin-bottom:15px;">
            <label>Business Activities</label>
            <textarea id="adm_c_activities" class="adm-textarea" oninput="this.style.height = '';this.style.height = this.scrollHeight + 'px'"></textarea>
          </div>
          <div class="input-group" style="margin-bottom:15px;">
            <label>Products / Services</label>
            <textarea id="adm_c_products" class="adm-textarea" oninput="this.style.height = '';this.style.height = this.scrollHeight + 'px'"></textarea>
          </div>
          <div class="input-group" style="margin-bottom:15px;">
            <label>Enterprise Background</label>
            <textarea id="adm_c_background" class="adm-textarea" oninput="this.style.height = '';this.style.height = this.scrollHeight + 'px'"></textarea>
          </div>
        </div>
            
            <h4 style="color: var(--accent); font-size: 12px; border-bottom:1px solid var(--border-color); padding-bottom:5px; margin-top:20px;">Registrations</h4>
            <div class="form-grid">
              <div class="input-group"><label>DTI #</label><input id="adm_c_dti_n"></div>
              <div class="input-group"><label>DTI Date</label><input type="date" id="adm_c_dti_d"></div>
              <div class="input-group"><label>SEC #</label><input id="adm_c_sec_n"></div>
              <div class="input-group"><label>SEC Date</label><input type="date" id="adm_c_sec_d"></div>
              <div class="input-group"><label>CDA #</label><input id="adm_c_cda_n"></div>
              <div class="input-group"><label>CDA Date</label><input type="date" id="adm_c_cda_d"></div>
              <div class="input-group"><label>Other #</label><input id="adm_c_oth_n"></div>
              <div class="input-group"><label>Other Date</label><input type="date" id="adm_c_oth_d"></div>
            </div>
          </div>

          <div>
            <h4 style="color: var(--accent); font-size: 12px; border-bottom:1px solid var(--border-color); padding-bottom:5px;">Worker Profile</h4>
            <div class="form-grid">
              <div class="input-group"><label>M Dir Prod</label><input type="number" id="adm_m_d_p"></div>
              <div class="input-group"><label>F Dir Prod</label><input type="number" id="adm_f_d_p"></div>
              <div class="input-group"><label>M Dir Non-Prod</label><input type="number" id="adm_m_d_np"></div>
              <div class="input-group"><label>F Dir Non-Prod</label><input type="number" id="adm_f_d_np"></div>
              
              <div class="input-group"><label>M Ind Prod</label><input type="number" id="adm_m_i_p"></div>
              <div class="input-group"><label>F Ind Prod</label><input type="number" id="adm_f_i_p"></div>
              <div class="input-group"><label>M Ind Non-Prod</label><input type="number" id="adm_m_i_np"></div>
              <div class="input-group"><label>F Ind Non-Prod</label><input type="number" id="adm_f_i_np"></div>
            </div>
          </div>
        </div>
      </div>`;
  }

  async function openReview(userId) {
    document.getElementById('admin_list_view').style.display = 'none';
    const panel = document.getElementById('admin_review_panel');
    panel.style.display = 'block';
    panel.innerHTML = renderAdminReviewView(userId);
    await loadAdminData(userId);
  }

  function closeAdminView() {
    document.getElementById('admin_list_view').style.display = 'block';
    document.getElementById('admin_review_panel').style.display = 'none';
  }

  async function loadAdminData(userId) {
    const { data: owner } = await sb.from('owner_profile').select('*').eq('owner_ID', userId).maybeSingle();
    const { data: company } = await sb.from('company_profile').select('*').eq('user_id', userId).maybeSingle();

    if (owner) {
      document.getElementById('adm_user_title').innerText = owner.owner_name || "Unknown Owner";
      document.getElementById('adm_o_nick').value = owner.owner_nickname || '';
      document.getElementById('adm_o_sex').value = owner.owner_sex || '';
    }

    if (company) {
      document.getElementById('adm_c_name').value = company.enterprise_name || '';
      document.getElementById('adm_c_phone').value = company.contact_number || '';
      document.getElementById('adm_c_email').value = company.enterprise_email || '';
      document.getElementById('adm_c_type').value = company.msme_type || '';
      document.getElementById('adm_c_sector').value = company.industry_sector || '';

      document.getElementById('adm_c_activities').value = company.business_activities || '';
      document.getElementById('adm_c_products').value = company.products_services || '';
      document.getElementById('adm_c_background').value = company.enterprise_background || '';

      document.querySelectorAll('.adm-textarea').forEach(tx => {
        tx.style.height = tx.scrollHeight + 'px';
      });
      
      document.getElementById('adm_c_dti_n').value = company.dti_reg_num || '';
      document.getElementById('adm_c_dti_d').value = company.dti_reg_date || '';
      document.getElementById('adm_c_sec_n').value = company.sec_reg_num || '';
      document.getElementById('adm_c_sec_d').value = company.sec_reg_date || '';
      document.getElementById('adm_c_cda_n').value = company.cda_reg_num || '';
      document.getElementById('adm_c_cda_d').value = company.cda_reg_date || '';
      document.getElementById('adm_c_oth_n').value = company.others_reg_num || '';
      document.getElementById('adm_c_oth_d').value = company.others_reg_date || '';

      document.getElementById('adm_m_d_p').value = company.male_dir_prod || 0;
      document.getElementById('adm_f_d_p').value = company.female_dir_prod || 0;
      document.getElementById('adm_m_d_np').value = company.male_dir_nonprod || 0;
      document.getElementById('adm_f_d_np').value = company.female_dir_nonprod || 0;
      document.getElementById('adm_m_i_p').value = company.male_ind_prod || 0;
      document.getElementById('adm_f_i_p').value = company.female_ind_prod || 0;
      document.getElementById('adm_m_i_np').value = company.male_ind_nonprod || 0;
      document.getElementById('adm_f_i_np').value = company.female_ind_nonprod || 0;
    }
  }

  // CORRECTED: Strictly async function handling the updates
  async function saveAdminEdits(userId) {
    const btn = event.currentTarget;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    btn.disabled = true;

    try {
      const ownerUpdates = {
        owner_nickname: document.getElementById('adm_o_nick').value,
        owner_sex: document.getElementById('adm_o_sex').value,
      };

      const companyUpdates = {
        enterprise_name: document.getElementById('adm_c_name').value,
        contact_number: document.getElementById('adm_c_phone').value,
        enterprise_email: document.getElementById('adm_c_email').value,
        msme_type: document.getElementById('adm_c_type').value,
        industry_sector: document.getElementById('adm_c_sector').value,

        business_activities: document.getElementById('adm_c_activities').value,
        products_services: document.getElementById('adm_c_products').value,
        enterprise_background: document.getElementById('adm_c_background').value,
        
        dti_reg_num: document.getElementById('adm_c_dti_n').value || null,
        dti_reg_date: document.getElementById('adm_c_dti_d').value || null,
        sec_reg_num: document.getElementById('adm_c_sec_n').value || null,
        sec_reg_date: document.getElementById('adm_c_sec_d').value || null,
        cda_reg_num: document.getElementById('adm_c_cda_n').value || null,
        cda_reg_date: document.getElementById('adm_c_cda_d').value || null,
        others_reg_num: document.getElementById('adm_c_oth_n').value || null,
        others_reg_date: document.getElementById('adm_c_oth_d').value || null,

        male_dir_prod: parseInt(document.getElementById('adm_m_d_p').value) || 0,
        female_dir_prod: parseInt(document.getElementById('adm_f_d_p').value) || 0,
        male_dir_nonprod: parseInt(document.getElementById('adm_m_d_np').value) || 0,
        female_dir_nonprod: parseInt(document.getElementById('adm_f_d_np').value) || 0,
        male_ind_prod: parseInt(document.getElementById('adm_m_i_p').value) || 0,
        female_ind_prod: parseInt(document.getElementById('adm_f_i_p').value) || 0,
        male_ind_nonprod: parseInt(document.getElementById('adm_m_i_np').value) || 0,
        female_ind_nonprod: parseInt(document.getElementById('adm_f_i_np').value) || 0
      };

      const { error: err1 } = await sb.from('owner_profile').update(ownerUpdates).eq('owner_ID', userId);
      const { error: err2 } = await sb.from('company_profile').update(companyUpdates).eq('user_id', userId);

      if (err1 || err2) throw (err1 || err2);
      
      alert("All records updated successfully!");
      closeAdminView();
      fetchMSMEUsers(); // Refresh the list view

    } catch (e) {
      console.error(e);
      alert("Save failed! Please check console.");
    } finally {
      btn.innerHTML = '<i class="fas fa-save"></i> Save All Changes';
      btn.disabled = false;
    }
  }

  // BULK EXPORT LOGIC
  async function exportToCSV(event) {
    const btn = event.currentTarget;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exporting...';
    btn.disabled = true;

    try {
        const { data: companies, error: cErr } = await sb.from('company_profile').select('*');
        const { data: owners, error: oErr } = await sb.from('owner_profile').select('*');

        if (cErr || oErr) throw (cErr || oErr);
        if (!companies || companies.length === 0) return alert("No data found.");

        const headers = [
            "User ID", "Enterprise Name", "Contact Number", "Email", "MSME Type", "Sector",
            "DTI Number", "DTI Date", "SEC Number", "SEC Date", "CDA Number", "CDA Date",
            "M_Dir_Prod", "F_Dir_Prod", "Owner Name", "Nickname", "Sex"
        ];
        
        const csvRows = [headers.join(",")];

        companies.forEach(company => {
            const owner = owners.find(o => o.owner_ID === company.user_id) || {};
            const row = [
                `"${company.user_id}"`,
                `"${company.enterprise_name || ''}"`,
                `"${(company.business_activities || '').replace(/"/g, '""')}"`, 
                `"${(company.products_services || '').replace(/"/g, '""')}"`,
                `"${(company.enterprise_background || '').replace(/"/g, '""')}"`,
                `"${company.contact_number || ''}"`,
                `"${company.enterprise_email || ''}"`,
                `"${company.msme_type || ''}"`,
                `"${company.industry_sector || ''}"`,
                `"${company.dti_reg_num || ''}"`,
                `"${company.dti_reg_date || ''}"`,
                `"${company.sec_reg_num || ''}"`,
                `"${company.sec_reg_date || ''}"`,
                `"${company.cda_reg_num || ''}"`,
                `"${company.cda_reg_date || ''}"`,
                `"${company.male_dir_prod || 0}"`,
                `"${company.female_dir_prod || 0}"`,
                `"${owner.owner_name || ''}"`,
                `"${owner.owner_nickname || ''}"`,
                `"${owner.owner_sex || ''}"`
            ];
            csvRows.push(row.join(","));
        });

        const blob = new Blob([csvRows.join("\n")], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement("a");
        link.href = url;
        link.download = `ASENXO_Export_${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

    } catch (err) {
        console.error(err);
        alert("Export failed: " + err.message);
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
  }

  function handleLogout() {
    sb.auth.signOut().then(() => window.location.href = 'login.php');
  }
</script>
</body>
</html>