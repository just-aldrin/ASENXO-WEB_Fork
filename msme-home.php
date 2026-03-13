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
      --sidebar-width: 70px;
      --sidebar-expanded-width: 240px;
      --error-color: #e74c3c;
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
      margin: 0; 
      color: var(--text-main);
      transition: background 0.3s, color 0.3s;
      overflow-x: hidden;
    }

    /* Validation styles */
    .input-group input:invalid,
    .input-group select:invalid,
    .input-group textarea:invalid {
      border-color: var(--error-color);
    }
    .input-group .error-message {
      color: var(--error-color);
      font-size: 11px;
      margin-top: 4px;
      display: none;
    }
    .input-group.error input,
    .input-group.error select,
    .input-group.error textarea {
      border-color: var(--error-color);
    }
    .input-group.error .error-message {
      display: block;
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

    .card { 
      background: var(--card-bg); 
      border: 1px solid var(--border-color); 
      border-radius: 12px; 
      padding: 25px; 
      margin-bottom: 20px; 
    }
    
    .sidebar {
      position: fixed;
      top: 60px;
      left: 0;
      width: var(--sidebar-width);
      height: calc(100vh - 60px);
      background: var(--bg-body);
      border-right: 1px solid var(--border-color);
      padding: 20px 0;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      transition: width 0.3s ease;
      overflow: hidden;
      white-space: nowrap;
      z-index: 1000;
    }
    
    .sidebar:hover {
      width: var(--sidebar-expanded-width);
      box-shadow: 5px 0 15px rgba(0, 0, 0, 0.5);
    }
    
    .badge {
      background: var(--accent);
      color: #000;
      font-size: 9px;
      padding: 2px 5px;
      border-radius: 4px;
      font-weight: 800;
      text-transform: uppercase;
    }
    
    .sidebar-menu {
      list-style: none;
      padding: 0;
      margin: 0;
      flex-grow: 1;
      margin-top: 20px; 
    }
    
    .sidebar-menu li {
      padding: 12px 15px;
      margin: 4px 8px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 12px;
      transition: 0.2s;
      color: var(--text-muted);
      white-space: nowrap;
    }
    
    .sidebar-menu li i {
      min-width: 20px;
      font-size: 16px;
      text-align: center;
    }
    
    .sidebar-menu li span {
      opacity: 0;
      transition: opacity 0.2s ease 0.1s;
    }
    
    .sidebar:hover .sidebar-menu li span {
      opacity: 1;
    }
    
    .sidebar-menu li.active {
      background: rgba(46, 204, 113, 0.1);
      color: var(--accent);
      font-weight: 700;
    }
    
    .sidebar-menu li:hover:not(.active) {
      background: rgba(255,255,255,0.03);
      color: var(--text-main);
    }
    
    .sidebar-user {
      display: flex;
      align-items: center;
      padding: 8px; 
      margin: 0 6px 30px 6px;
      background: rgba(128,128,128,0.05);
      border-radius: 12px;
      border: 1px solid var(--border-color);
      white-space: nowrap;
      box-sizing: border-box;
      width: auto; 
      max-width: calc(100% - 12px); 
      overflow: hidden; 
    }

    .sidebar-user .user-avatar {
      width: 38px;
      height: 38px;
      min-width: 38px;
      border-radius: 50%;
      background: #222;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 1px solid var(--border-color);
      flex-shrink: 0;
      transition: width 0.3s ease, height 0.3s ease;
    }
    
    .sidebar-user .user-avatar i {
      font-size: 16px;
      color: var(--text-muted);
    }
    
    .sidebar-user .user-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .sidebar:not(:hover) .sidebar-user .user-avatar {
      width: 34px;
      height: 34px;
      min-width: 34px;
    }

    .sidebar:not(:hover) .sidebar-user {
      padding: 6px;
    }
    
    .sidebar-user .user-info {
      opacity: 0;
      transition: opacity 0.2s ease 0.1s;
      overflow: hidden;
      margin-left: 10px;
      flex: 1;
      min-width: 0; 
    }
    
    .sidebar:hover .sidebar-user .user-info {
      opacity: 1;
    }
    
    .sidebar-user .user-name {
      font-weight: 700;
      font-size: 13px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      color: var(--text-main);
    }
    
    .sidebar-user .user-role {
      font-size: 10px;
      color: var(--accent);
      font-weight: 800;
      text-transform: uppercase;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .main-wrapper {
      margin-left: var(--sidebar-width);
      transition: margin-left 0.3s ease;
      min-height: calc(100vh - 60px);
      margin-top: 60px;
    }
    
    .sidebar:hover + .main-wrapper {
      margin-left: var(--sidebar-width); 
    }
    
    .sticky-section {
      position: sticky;
      align-self: start;
    }
    
    .sticky-card {
      position: sticky;
    }
    
    .input-group label { font-size: 11px; color: var(--text-muted); font-weight: 600; margin-bottom: 5px; display: block; }
    .input-group input, .input-group select, .input-group textarea {
      width: 100%; 
      background: var(--input-bg); 
      border: 1px solid var(--border-color);
      color: var(--text-main); 
      padding: 10px; 
      border-radius: 8px; 
      font-family: inherit; 
      box-sizing: border-box;
    }
    .input-group textarea {
      resize: vertical;
      min-height: 60px;
    }
    .input-group .error-message {
      font-size: 11px;
      color: var(--error-color);
      margin-top: 4px;
      display: none;
    }
    .input-group.error input,
    .input-group.error select,
    .input-group.error textarea {
      border-color: var(--error-color);
    }
    .input-group.error .error-message {
      display: block;
    }
    
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }

    .primary-btn {
      background: var(--accent); 
      color: #000; 
      border: none; 
      padding: 12px;
      border-radius: 8px; 
      font-weight: 800; 
      cursor: pointer; 
      font-family: inherit; 
      width: 100%;
      transition: opacity 0.2s;
    }
    .primary-btn:hover { opacity: 0.9; }
    .primary-btn:disabled { opacity: 0.5; cursor: not-allowed; }

    .upload-container {
      text-align: center;
      background: rgba(128,128,128,0.03);
      border: 1px solid var(--border-color);
      border-radius: 12px;
      padding: 25px;
      margin-top:15px;
    }
    
    #imagePreview {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      border: 2px dashed var(--border-color);
      margin: 0 auto 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      background: var(--input-bg);
    }
    #imagePreview i {
      font-size: 40px;
      color: var(--text-muted);
    }
    #imagePreview img { 
      width: 100%; 
      height: 100%; 
      object-fit: cover; 
    }
    
    .file-input-wrapper {
      position: relative;
      margin-bottom: 20px;
    }
    .file-input-wrapper input[type="file"] {
      position: absolute;
      left: -9999px;
      opacity: 0;
    }
    .file-input-label {
      display: inline-block;
      background: var(--input-bg);
      border: 1px solid var(--border-color);
      color: var(--text-main);
      padding: 10px 20px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 14px;
      transition: 0.2s;
    }
    .file-input-label:hover {
      border-color: var(--accent);
    }
    .file-name {
      margin-top: 10px;
      font-size: 12px;
      color: var(--text-muted);
    }
    .file-error {
      color: var(--error-color);
      font-size: 11px;
      margin-top: 5px;
    }

    .repo-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 15px; }
    .repo-item { 
      padding: 15px; 
      background: rgba(128,128,128,0.05); 
      border-radius: 10px; 
      text-align: center; 
      border: 1px solid var(--border-color); 
    }

    .matrix-input {
      width: 100%;
      background: var(--input-bg) !important;
      border: 1px solid var(--border-color);
      color: var(--text-main);
      padding: 8px;
      border-radius: 6px;
      text-align: center;
      font-family: inherit;
      font-weight: 600;
    }
    .matrix-input:focus { border-color: var(--accent); outline: none; }
    
    .main-content {
      padding: 40px;
      display: grid;
      grid-template-columns: 1.8fr 1fr;
      gap: 30px;
      overflow-y: auto;
    }
    
    @media (max-width: 1000px) {
      .main-content {
        grid-template-columns: 1fr;
      }
    }

    /* Modal Styles */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0,0,0,0.8);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 10000;
    }
    .modal {
      background: var(--card-bg);
      border: 1px solid var(--border-color);
      border-radius: 12px;
      padding: 30px;
      max-width: 400px;
      width: 90%;
    }
    .modal h3 {
      margin: 0 0 10px 0;
      font-size: 16px;
      color: var(--accent);
    }
    .modal p {
      margin: 0 0 20px 0;
      color: var(--text-muted);
      font-size: 14px;
    }
    .modal-actions {
      display: flex;
      justify-content: flex-end;
    }
    .modal-btn {
      background: var(--accent);
      color: #000;
      border: none;
      padding: 8px 20px;
      border-radius: 6px;
      font-weight: 600;
      cursor: pointer;
    }
  </style>
</head>
<body class="light-theme">

<div class="app">
  <header style="height: 60px; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; padding: 0 25px; background: var(--card-bg); position: fixed; top: 0; left: 0; right: 0; z-index: 1001;">
    <div style="font-weight: 900; font-size: 1.2rem; display: flex; align-items: center; gap: 10px; margin-left: 0;">
        ASENXO <span style="background:var(--accent); color:#000; font-size:9px; padding:2px 5px; border-radius:4px; font-weight: 800;">MSME</span>
    </div>
    <div style="display: flex; gap: 10px;">
      <button onclick="toggleTheme()" style="background:none; border:1px solid var(--border-color); color:var(--text-main); padding:8px; border-radius:8px; cursor:pointer;"><i class="fas fa-adjust"></i></button>
      <button onclick="handleLogout()" style="background:#ef4444; color:white; border:none; padding:8px 15px; border-radius:8px; font-weight:700; cursor:pointer;">Logout</button>
    </div>
  </header>

  <nav class="sidebar">
    <ul class="sidebar-menu">
      <li class="active"><i class="fas fa-cube"></i><span>Application Module</span></li>
      <li><i class="fas fa-chart-line"></i><span>Progress Monitoring</span></li>
      <li><i class="fas fa-cloud-upload-alt"></i><span>Document Upload History</span></li>
      <li><i class="fas fa-history"></i><span>Revisions</span></li>
      <li><i class="fas fa-file-alt"></i><span>Forms for Requirements</span></li>
      <li><i class="fas fa-cog"></i><span>Settings</span></li>
    </ul>

    <div class="sidebar-user" id="sidebarUser">
      <div class="user-avatar" id="sidebarAvatar">
        <i class="fas fa-user"></i>
      </div>
      <div class="user-info">
        <div class="user-name" id="sidebarName">Loading...</div>
        <div class="user-role">VERIFIED OWNER</div>
      </div>
    </div>
  </nav>

  <div class="main-wrapper">
    <main class="main-content">
      <section>
        <div class="card">
          <h2 style="font-size: 18px; margin-bottom: 25px;"><i class="fas fa-tasks" style="color: var(--accent); margin-right: 12px;"></i> Application Flow</h2>
          <ul id="dynamicSteps" style="list-style: none; padding: 0;"></ul>
        </div>
      </section>

      <aside class="sticky-section">
        <div class="card sticky-card">
          <h3 style="margin-top: 0; font-size: 14px; font-weight: 800;">Overview</h3>
          <div style="margin: 15px 0;">
            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 8px;">
              <span style="color: var(--text-muted);">Application Progress</span><span id="progressTxt" style="font-weight: 800; color: var(--accent);">0%</span>
            </div>
            <div style="height: 6px; background: var(--border-color); border-radius: 10px; overflow: hidden;">
              <div id="progressFill" style="width: 0%; height: 100%; background: var(--accent); transition: width 0.8s ease;"></div>
            </div>
          </div>
        </div>

        <div class="card sticky-card" style="top: 180px;">
          <h3 style="margin-top: 0; font-size: 14px; font-weight: 800;">Documents & Status</h3>
          <div class="repo-grid">
            <div class="repo-item">
              <span id="filesUploaded" style="font-size: 20px; font-weight: 800; display: block; color: var(--accent);">0</span>
              <span style="font-size: 9px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Uploaded</span>
            </div>
            <div class="repo-item">
              <span id="applicationStatusPill" class="status-pill status-pending">Pending</span>
              <span style="font-size: 9px; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-top: 5px; display: block;">Status</span>
            </div>
          </div>
        </div>
      </aside>
    </main>
  </div>
</div>

<!-- Error Modal -->
<div class="modal-overlay" id="errorModal" style="display: none;">
  <div class="modal">
    <h3>Validation Error</h3>
    <p id="errorMessage"></p>
    <div class="modal-actions">
      <button class="modal-btn" id="errorOkBtn">OK</button>
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
    { id: 3, title: "Owner Information", desc: "Detailed personal data" },
    { id: 4, title: "Profile Image", desc: "Upload profile image" },
    { id: 5, title: "Business Information", desc: "Complete enterprise details, registrations & employment" },
    { id: 6, title: "Account Confirmation", desc: "Review and confirm" },
    { id: 7, title: "Submit Required Documents", desc: "Upload all required application files" },
    { id: 8, title: "Application Status", desc: "Pending review" },
    { id: 9, title: "Technology Needs Assessment", desc: "Based on survey" },
    { id: 10, title: "Endorsement Status", desc: "Waiting for approval" }
  ];

  const documentTypes = [
    { key: 'approved_technical_assistance', label: '1. Approved Request for Technical Assistance' },
    { key: 'tna_form_01', label: '2. DOST TNA Form 01 (Application for TNA)' },
    { key: 'tna_form_02', label: '3. DOST TNA Form 02 + Technology Level Assessment' },
    { key: 'letter_of_intent', label: '4. Letter of intent (refund & insurance commitment)' },
    { key: 'setup_form_001', label: '5. SETUP Form 001 Project Proposal (Annex A-1)' },
    { key: 'mayors_permit_dti', label: '6. Mayor\'s permit / DTI registration (photocopy)' },
    { key: 'cash_sales_invoice', label: '7. Company cash/sales invoice (photocopy)' },
    { key: 'board_resolution', label: '8. Board Resolution authorizing availment' },
    { key: 'inhouse_fs_sworn', label: '9. In-house FS (3 yrs) + notarized sworn statement' },
    { key: 'sworn_affidavit', label: '10. Sworn affidavit (consanguinity / bad debt)' },
    { key: 'equipment_specs', label: '11. Equipment technical specs / drawings' },
    { key: 'three_quotations', label: '12. Three quotations (fermenters, bottles, etc.)' },
    { key: 'projected_fs', label: '13. Projected Financial Statements (5 years)' },
    { key: 'work_financial_plan', label: '14. Work & Financial Plan / equity details' },
    { key: 'gad_checklist_2', label: '15. GAD Checklist 2 (project identification)' },
    { key: 'data_privacy_consent', label: '16. Data Privacy Consent Form' }
  ];

  // Already uploaded documents
  let uploadedDocs = [];

  async function fetchUploadedDocuments() {
    const { data, error } = await sb
      .from('application_documents')
      .select('document_type, document_label, file_name, uploaded_at')
      .eq('user_id', user.id);
    if (!error && data) {
      uploadedDocs = data;
    }
  }

  async function init() {
    const { data: { session } } = await sb.auth.getSession();
    if (!session) return window.location.href = 'login-mock.php';
    user = session.user;

    const { data: p, error } = await sb.from('user_profiles').select('*').eq('id', user.id).maybeSingle();
    
    if (error || !p) {
        return window.location.href = 'login-mock.php';
    }

    const userRole = p.role ? p.role.toLowerCase() : '';

    if (userRole !== 'msme') {
        alert("Unauthorized access. Redirecting...");
        return window.location.href = (userRole === 'psto') ? 'psto-home.php' : 'login-mock.php';
    }

    if (p.role !== 'msme') {
        alert("Unauthorized access. Redirecting to your dashboard.");
        return window.location.href = p.role === 'psto' ? 'psto-home.php' : 'login-mock.php';
    }

    profile = p;
    currentStep = p.current_step || 3;
    document.getElementById('sidebarName').innerText = `${p.first_name} ${p.last_name}`;
    
    const { data: op } = await sb.from('owner_profile').select('profile_pic_url').eq('owner_ID', user.id).single();
    if (op?.profile_pic_url) {
      document.getElementById('sidebarAvatar').innerHTML = `<img src="${op.profile_pic_url}">`;
    }

    updateApplicationStatus(p.application_status || 'pending');
    await fetchUploadedDocuments();
    await fetchDocumentCount(); 

    renderSteps();
  }

  // ========== ERROR MODAL ==========
  function showErrorModal(message) {
    document.getElementById('errorMessage').innerText = message;
    document.getElementById('errorModal').style.display = 'flex';
  }

  document.getElementById('errorOkBtn').addEventListener('click', function() {
    document.getElementById('errorModal').style.display = 'none';
  });

  function renderSteps() {
    const perc = Math.round((currentStep / stepsData.length) * 100);
    document.getElementById('progressFill').style.width = perc + '%';
    document.getElementById('progressTxt').innerText = perc + '%';

    const list = document.getElementById('dynamicSteps');
    list.innerHTML = stepsData.map(s => {
      const isDone = s.id < currentStep;
      const isActive = s.id === currentStep;
      
      let stepContent = '';
      if (isActive) {
        if (s.id === 3) stepContent = renderStep3Owner();
        else if (s.id === 4) stepContent = renderStep4Image();
        else if (s.id === 5) stepContent = renderStep5Business();
        else if (s.id === 6) stepContent = renderStep6Dummy("Confirm Application Details", "Review your data above and click confirm.");
        else if (s.id === 7) stepContent = renderStep7Documents();
        else if (s.id === 8) stepContent = renderStep8Dummy();
        else if (s.id === 9) stepContent = renderStep9Dummy();
        else if (s.id === 10) stepContent = renderStep10Dummy();
      }

      return `
        <li style="display: flex; gap: 20px; margin-bottom: 30px;">
          <div class="step-icon ${isDone ? 'completed' : (isActive ? 'current' : '')}">
            ${isDone ? '<i class="fas fa-check" style="font-size: 11px;"></i>' : '<i class="fas fa-circle" style="font-size: 6px;"></i>'}
          </div>
          <div style="flex: 1;">
            <div style="font-size: 15px; font-weight: 700; color: ${isActive ? 'var(--accent)' : 'inherit'}">${s.title}</div>
            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 10px;">${s.desc}</div>
            ${stepContent}
          </div>
        </li>
      `;
    }).join('');
  }

  // ========== VALIDATION HELPER ==========
  function validateRequiredFields(fieldIds) {
    let isValid = true;
    fieldIds.forEach(id => {
      const el = document.getElementById(id);
      const group = el.closest('.input-group');
      if (!el.value || el.value.trim() === '') {
        group.classList.add('error');
        isValid = false;
      } else {
        group.classList.remove('error');
      }
    });
    return isValid;
  }

  // ========== STEP 3: OWNER INFORMATION ==========
  function renderStep3Owner() {
    return `
      <div style="background: rgba(128,128,128,0.03); border: 1px solid var(--border-color); border-radius: 12px; padding: 25px; margin-top:15px;">
        
        <h4 style="margin: 0 0 20px 0; font-size: 14px; color: var(--accent); font-weight: 800;">Personal Information</h4>
        <div class="form-grid">
          <div class="input-group" style="grid-column: span 2;"><label>Full Name *</label><input id="o_name" readonly value="${profile?.first_name || ''} ${profile?.last_name || ''}" style="background: rgba(128,128,128,0.1);"></div>
          <div class="input-group"><label>Nickname *</label><input id="o_nick" required placeholder="e.g. Juan, Jun" value="${profile?.first_name || ''}"><div class="error-message">This field is required</div></div>
          <div class="input-group"><label>Date of Birth *</label><input type="date" id="o_dob" required max="${new Date().toISOString().split('T')[0]}"><div class="error-message">This field is required</div></div>
          <div class="input-group"><label>Place of Birth *</label><input id="o_pob" required placeholder="City/Municipality, Province"><div class="error-message">This field is required</div></div>
          <div class="input-group"><label>Nationality *</label><input id="o_nat" required value="Filipino"><div class="error-message">This field is required</div></div>
          <div class="input-group"><label>Sex *</label>
            <select id="o_sex" required>
              <option value="">Select</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
              <option value="Prefer not to say">Prefer not to say</option>
            </select><div class="error-message">This field is required</div>
          </div>
          <div class="input-group"><label>Marital Status *</label>
            <select id="o_mar" required>
              <option value="">Select</option>
              <option value="Single">Single</option>
              <option value="Married">Married</option>
              <option value="Divorced">Divorced</option>
              <option value="Widowed">Widowed</option>
              <option value="Separated">Separated</option>
            </select><div class="error-message">This field is required</div>
          </div>
          <div class="input-group"><label>Spouse Name</label><input id="o_spouse" placeholder="e.g. Maria R. Dela Cruz"></div>
        </div>

        <h4 style="margin: 30px 0 20px 0; font-size: 14px; color: var(--accent); font-weight: 800; border-top: 1px solid var(--border-color); padding-top:20px;">Contact Information</h4>
        <div class="form-grid">
          <div class="input-group" style="grid-column: span 2;"><label>Residential Address *</label><input id="o_address" required placeholder="House/Blk/Lot, Street, Barangay, City/Municipality"><div class="error-message">This field is required</div></div>
          <div class="input-group"><label>Contact Number *</label><input id="o_contactnum" required type="tel" placeholder="e.g. 09171234567"><div class="error-message">This field is required</div></div>
          <div class="input-group"><label>Email Address *</label><input id="o_email" type="email" value="${user?.email || ''}" readonly style="background: rgba(128,128,128,0.1);"></div>
        </div>

        <h4 style="margin: 30px 0 20px 0; font-size: 14px; color: var(--accent); font-weight: 800; border-top: 1px solid var(--border-color); padding-top:20px;">Enterprise Association</h4>
        <div class="form-grid">
          <div class="input-group"><label>Enterprise Name *</label><input id="o_ent_name" required placeholder="Registered business name"><div class="error-message">This field is required</div></div>
          <div class="input-group"><label>Enterprise Address *</label><input id="o_ent_address" required placeholder="Business address"><div class="error-message">This field is required</div></div>
          <div class="input-group"><label>Designation / Position *</label><input id="o_designation" required placeholder="e.g. Owner, President, CEO"><div class="error-message">This field is required</div></div>
        </div>

        <h4 style="margin: 30px 0 20px 0; font-size: 14px; color: var(--accent); font-weight: 800; border-top: 1px solid var(--border-color); padding-top:20px;">Educational Background & Affiliations</h4>
        <div class="form-grid">
          <div class="input-group" style="grid-column: span 2;">
            <label>Highest Educational Attainment *</label>
            <select id="o_hea" required>
              <option value="">Select</option>
              <option value="Elementary Level">Elementary Level</option>
              <option value="Elementary Graduate">Elementary Graduate</option>
              <option value="High School Level">High School Level</option>
              <option value="High School Graduate">High School Graduate</option>
              <option value="College Level">College Level</option>
              <option value="College Graduate">College Graduate</option>
              <option value="Post Graduate">Post Graduate</option>
              <option value="Vocational">Vocational</option>
            </select><div class="error-message">This field is required</div>
          </div>
          <div class="input-group" style="grid-column: span 2;">
            <label>Professional Affiliations / Organizations *</label>
            <textarea id="o_affiliations" required placeholder="List any professional organizations, business clubs, or associations you belong to..." rows="3"></textarea><div class="error-message">This field is required</div>
          </div>
        </div>

        <button class="primary-btn" id="saveOwnerBtn" onclick="saveOwnerInfo()">Save Owner Information & Continue</button>
      </div>`;
  }

  async function saveOwnerInfo() {
    const requiredFields = [
      'o_nick', 'o_dob', 'o_pob', 'o_nat', 'o_sex', 'o_mar',
      'o_address', 'o_contactnum', 'o_ent_name', 'o_ent_address',
      'o_designation', 'o_hea', 'o_affiliations'
    ];
    if (!validateRequiredFields(requiredFields)) {
      showErrorModal('Please fill in all required fields.');
      return;
    }

    const btn = document.getElementById('saveOwnerBtn');
    btn.disabled = true; 
    btn.innerText = "Saving owner information...";

    const getVal = (id) => document.getElementById(id)?.value?.trim() || null;
    
    const fullName = getVal('o_name') || `${profile?.first_name || ''} ${profile?.last_name || ''}`.trim();

    const payload = {
      owner_ID: user.id,
      owner_name: fullName,
      owner_nickname: getVal('o_nick'),
      owner_dob: getVal('o_dob'),
      owner_pob: getVal('o_pob'),
      owner_nationality: getVal('o_nat'),
      owner_sex: getVal('o_sex'),
      owner_marstat: getVal('o_mar'),
      owner_spouse: getVal('o_spouse'),
      owner_address: getVal('o_address'),
      owner_contactnum: getVal('o_contactnum'),
      owner_email: getVal('o_email') || user?.email,
      enterprise_name: getVal('o_ent_name'),
      enterprise_address: getVal('o_ent_address'),
      enterprise_designation: getVal('o_designation'),
      owner_hea: getVal('o_hea'),
      owner_affiliations: getVal('o_affiliations')
    };

    Object.keys(payload).forEach(key => {
      if (payload[key] === null) delete payload[key];
    });

    const { error } = await sb.from('owner_profile').upsert([payload], { 
      onConflict: 'owner_ID' 
    });
    
    if (!error) {
      moveNext();
    } else {
      alert("Error saving owner information: " + error.message);
      btn.disabled = false;
      btn.innerText = "Save Owner Information & Continue";
    }
  }

  function renderStep4Image() {
    return `
      <div class="upload-container">
        <div id="imagePreview">
          <i class="fas fa-user"></i>
        </div>
        
        <div class="file-input-wrapper">
          <label for="profileFile" class="file-input-label">
            <i class="fas fa-cloud-upload-alt" style="margin-right: 8px;"></i> Choose Image
          </label>
          <input type="file" id="profileFile" accept="image/*" onchange="previewImg(this)">
          <div id="fileName" class="file-name">No file chosen</div>
        </div>
        
        <button class="primary-btn" id="upBtn" onclick="uploadImg()">
          <i class="fas fa-upload" style="margin-right: 8px;"></i> Upload & Continue
        </button>
        <p style="font-size: 11px; color: var(--text-muted); margin-top: 15px;">
          You can skip this step and upload later.
        </p>
      </div>`;
  }

  function previewImg(input) {
    const fileName = document.getElementById('fileName');
    if (input.files?.[0]) {
      fileName.innerText = input.files[0].name;
      const reader = new FileReader();
      reader.onload = e => document.getElementById('imagePreview').innerHTML = `<img src="${e.target.result}">`;
      reader.readAsDataURL(input.files[0]);
    } else {
      fileName.innerText = 'No file chosen';
      document.getElementById('imagePreview').innerHTML = '<i class="fas fa-user"></i>';
    }
  }

  async function uploadImg() {
    const file = document.getElementById('profileFile').files[0];
    if (!file) { 
      if (confirm('No image selected. Skip this step?')) {
        moveNext(); 
      }
      return; 
    }
    const btn = document.getElementById('upBtn');
    btn.disabled = true; 
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
    const filePath = `${user.id}/${Date.now()}_${file.name}`;
    const { data, error } = await sb.storage.from('avatars').upload(filePath, file);
    if (!error) {
      const { data: { publicUrl } } = sb.storage.from('avatars').getPublicUrl(filePath);
      await sb.from('owner_profile').update({ profile_pic_url: publicUrl }).eq('owner_ID', user.id);
      document.getElementById('sidebarAvatar').innerHTML = `<img src="${publicUrl}">`;
      moveNext();
    } else { 
      alert("Upload failed: " + error.message); 
      btn.disabled = false; 
      btn.innerHTML = '<i class="fas fa-upload"></i> Upload & Continue'; 
    }
  }

  // ========== STEP 5: BUSINESS INFORMATION ==========
  function renderStep5Business() {
    return `
      <div style="background: rgba(128,128,128,0.03); border: 1px solid var(--border-color); border-radius: 12px; padding: 25px; margin-top: 15px;">
        
        <h4 style="margin: 0 0 20px 0; font-size: 14px; color: var(--accent); font-weight: 800;">Enterprise Information</h4>
        <div class="form-grid">
          <div class="input-group" style="grid-column: span 2;"><label>Enterprise Name *</label><input id="c_name" required placeholder="Registered business name"><div class="error-message">This field is required</div></div>
          <div class="input-group" style="grid-column: span 2;"><label>Enterprise Address *</label><input id="c_address" required placeholder="Complete business address"><div class="error-message">This field is required</div></div>
          <div class="input-group"><label>Contact Person *</label><input id="c_contact_person" required placeholder="Primary contact"><div class="error-message">This field is required</div></div>
          <div class="input-group"><label>Contact Number *</label><input type="tel" id="c_phone" required placeholder="e.g. 09171234567"><div class="error-message">This field is required</div></div>
          <div class="input-group"><label>Enterprise Email *</label><input type="email" id="c_email" required placeholder="business@email.com"><div class="error-message">This field is required</div></div>
          <div class="input-group"><label>Year Established *</label><input type="number" id="c_year" required placeholder="YYYY" min="1900" max="2026"><div class="error-message">This field is required</div></div>
        </div>

        <h4 style="margin: 30px 0 20px 0; font-size: 14px; color: var(--accent); font-weight: 800; border-top: 1px solid var(--border-color); padding-top:20px;">Business Classification</h4>
        <div class="form-grid">
          <div class="input-group"><label>Organization Type *</label>
            <select id="c_org_type" required>
              <option value="">Select type</option>
              <option value="Sole Proprietorship">Sole Proprietorship</option>
              <option value="Partnership">Partnership</option>
              <option value="Corporation">Corporation</option>
              <option value="Cooperative">Cooperative</option>
            </select><div class="error-message">This field is required</div>
          </div>
          <div class="input-group"><label>Business Type *</label>
            <select id="c_biz_type" required>
              <option value="">Select type</option>
              <option value="Manufacturing">Manufacturing</option>
              <option value="Trading">Trading</option>
              <option value="Services">Services</option>
              <option value="Agribusiness">Agribusiness</option>
              <option value="Construction">Construction</option>
            </select><div class="error-message">This field is required</div>
          </div>
          <div class="input-group"><label>MSME Type *</label>
            <select id="c_mtype" required>
              <option value="">Select type</option>
              <option value="Micro">Micro</option>
              <option value="Small">Small</option>
              <option value="Medium">Medium</option>
            </select><div class="error-message">This field is required</div>
          </div>
          <div class="input-group"><label>Industry Sector *</label><input id="c_sector" required placeholder="e.g. Food Processing, IT Services"><div class="error-message">This field is required</div></div>
          <div class="input-group"><label>Current Capitalization (₱) *</label><input type="number" id="c_capital" required placeholder="0.00" step="0.01"><div class="error-message">This field is required</div></div>
        </div>

        <h4 style="margin: 30px 0 20px 0; font-size: 14px; color: var(--accent); font-weight: 800; border-top: 1px solid var(--border-color); padding-top:20px;">Business Description</h4>
        <div class="form-grid">
          <div class="input-group" style="grid-column: span 2;"><label>Business Activities *</label><textarea id="c_activities" required placeholder="Describe your primary business operations..." rows="3"></textarea><div class="error-message">This field is required</div></div>
          <div class="input-group" style="grid-column: span 2;"><label>Products / Services *</label><textarea id="c_products" required placeholder="List your main products or service offerings..." rows="3"></textarea><div class="error-message">This field is required</div></div>
          <div class="input-group" style="grid-column: span 2;"><label>Enterprise Background *</label><textarea id="c_background" required placeholder="Tell us about the history and mission of your business..." rows="3"></textarea><div class="error-message">This field is required</div></div>
        </div>

        <h4 style="margin: 30px 0 20px 0; font-size: 14px; color: var(--accent); font-weight: 800; border-top: 1px solid var(--border-color); padding-top:20px;">Regulatory Registrations</h4>
        <div class="form-grid">
          <div class="input-group"><label>DTI Registration No. *</label><input id="c_dti_n" required placeholder="e.g. 12345678"><div class="error-message">This field is required</div></div>
          <div class="input-group"><label>Registration Date *</label><input type="date" id="c_dti_d" required><div class="error-message">This field is required</div></div>
          <div class="input-group"><label>SEC Registration No. *</label><input id="c_sec_n" required placeholder="e.g. CS2024-123456"><div class="error-message">This field is required</div></div>
          <div class="input-group"><label>Registration Date *</label><input type="date" id="c_sec_d" required><div class="error-message">This field is required</div></div>
          <div class="input-group"><label>CDA Registration No. *</label><input id="c_cda_n" required placeholder="e.g. CDA-2024-001"><div class="error-message">This field is required</div></div>
          <div class="input-group"><label>Registration Date *</label><input type="date" id="c_cda_d" required><div class="error-message">This field is required</div></div>
          <div class="input-group"><label>Other Registration</label><input id="c_oth_n" placeholder="e.g. Mayor's Permit No."></div>
          <div class="input-group"><label>Registration Date</label><input type="date" id="c_oth_d"></div>
        </div>

        <h4 style="margin: 30px 0 20px 0; font-size: 14px; color: var(--accent); font-weight: 800; border-top: 1px solid var(--border-color); padding-top:20px;">Employment Information</h4>
        <div style="overflow-x: auto;">
          <table style="width: 100%; border-collapse: collapse; min-width: 500px;">
            <thead>
              <tr style="text-align: left;">
                <th style="padding: 10px 5px; font-size: 11px; color: var(--text-muted); text-transform: uppercase;">Type of Employment</th>
                <th style="padding: 10px; font-size: 11px; color: var(--text-muted); text-transform: uppercase; text-align: center;">Male</th>
                <th style="padding: 10px; font-size: 11px; color: var(--text-muted); text-transform: uppercase; text-align: center;">Female</th>
              </tr>
            </thead>
            <tbody>
              <tr><td colspan="3" style="padding: 15px 5px 10px; font-size: 13px; font-weight: 700;">Direct Workers</td></tr>
              <tr>
                <td style="padding: 5px; font-size: 12px; color: var(--text-muted);">Production</td>
                <td style="padding: 5px;"><input type="number" id="m_d_p" class="matrix-input" value="0" min="0"></td>
                <td style="padding: 5px;"><input type="number" id="f_d_p" class="matrix-input" value="0" min="0"></td>
              </tr>
              <tr style="border-bottom: 1px solid var(--border-color);">
                <td style="padding: 5px 5px 15px; font-size: 12px; color: var(--text-muted);">Non-Production</td>
                <td style="padding: 5px 5px 15px;"><input type="number" id="m_d_np" class="matrix-input" value="0" min="0"></td>
                <td style="padding: 5px 5px 15px;"><input type="number" id="f_d_np" class="matrix-input" value="0" min="0"></td>
              </tr>
              
              <tr><td colspan="3" style="padding: 25px 5px 10px; font-size: 13px; font-weight: 700;">Indirect / Contract Workers</td></tr>
              <tr>
                <td style="padding: 5px; font-size: 12px; color: var(--text-muted);">Production</td>
                <td style="padding: 5px;"><input type="number" id="m_i_p" class="matrix-input" value="0" min="0"></td>
                <td style="padding: 5px;"><input type="number" id="f_i_p" class="matrix-input" value="0" min="0"></td>
              </tr>
              <tr>
                <td style="padding: 5px; font-size: 12px; color: var(--text-muted);">Non-Production</td>
                <td style="padding: 5px;"><input type="number" id="m_i_np" class="matrix-input" value="0" min="0"></td>
                <td style="padding: 5px;"><input type="number" id="f_i_np" class="matrix-input" value="0" min="0"></td>
              </tr>
            </tbody>
          </table>
        </div>

        <h4 style="margin: 30px 0 20px 0; font-size: 14px; color: var(--accent); font-weight: 800; border-top: 1px solid var(--border-color); padding-top:20px;">Location Coordinates (Optional)</h4>
        <div class="form-grid">
          <div class="input-group"><label>Latitude</label><input type="number" id="c_lat" placeholder="e.g. 14.5995" step="any"></div>
          <div class="input-group"><label>Longitude</label><input type="number" id="c_lng" placeholder="e.g. 120.9842" step="any"></div>
        </div>

        <button class="primary-btn" id="saveStep5Btn" onclick="saveStep5()">Save All Business Information & Continue</button>
      </div>`;
  }

  async function saveStep5() {
    const requiredFields = [
      'c_name', 'c_address', 'c_contact_person', 'c_phone', 'c_email', 'c_year',
      'c_org_type', 'c_biz_type', 'c_mtype', 'c_sector', 'c_capital',
      'c_activities', 'c_products', 'c_background',
      'c_dti_n', 'c_dti_d', 'c_sec_n', 'c_sec_d', 'c_cda_n', 'c_cda_d'
    ];
    if (!validateRequiredFields(requiredFields)) {
      showErrorModal('Please fill in all required fields.');
      return;
    }

    const btn = document.getElementById('saveStep5Btn');
    btn.disabled = true; 
    btn.innerText = "Saving all business information...";

    const getVal = (id) => document.getElementById(id)?.value || null;
    const getInt = (id) => {
      const val = parseInt(document.getElementById(id)?.value);
      return isNaN(val) ? 0 : val;
    };
    const getFloat = (id) => {
      const val = parseFloat(document.getElementById(id)?.value);
      return isNaN(val) ? null : val;
    };

    const payload = {
      user_id: user.id,
      
      enterprise_name: getVal('c_name'),
      enterprise_address: getVal('c_address'),
      contact_person: getVal('c_contact_person'),
      contact_number: getVal('c_phone'),
      enterprise_email: getVal('c_email'),
      year_established: getInt('c_year'),
      
      organization_type: getVal('c_org_type'),
      business_type: getVal('c_biz_type'),
      msme_type: getVal('c_mtype'),
      industry_sector: getVal('c_sector'),
      current_capitalization: getFloat('c_capital'),
      
      business_activities: getVal('c_activities'),
      products_services: getVal('c_products'),
      enterprise_background: getVal('c_background'),
      
      DTI_reg_num: getVal('c_dti_n'),
      dti_reg_date: getVal('c_dti_d'),
      SEC_reg_num: getVal('c_sec_n'),
      sec_reg_date: getVal('c_sec_d'),
      CDA_reg_num: getVal('c_cda_n'),
      cda_reg_date: getVal('c_cda_d'),
      others_reg_num: getVal('c_oth_n'),
      other_reg_date: getVal('c_oth_d'),
      
      emp_direct_prod_male: getInt('m_d_p'),
      emp_direct_prod_female: getInt('f_d_p'),
      emp_direct_nonprod_male: getInt('m_d_np'),
      emp_direct_nonprod_female: getInt('f_d_np'),
      emp_indirect_prod_male: getInt('m_i_p'),
      emp_indirect_prod_female: getInt('f_i_p'),
      emp_indirect_nonprod_male: getInt('m_i_np'),
      emp_indirect_nonprod_female: getInt('f_i_np'),
      
      enterprise_lat: getFloat('c_lat'),
      enterprise_long: getFloat('c_lng'),
      
      created_at: new Date().toISOString()
    };

    Object.keys(payload).forEach(key => {
      if (payload[key] === null) delete payload[key];
    });

    const { error } = await sb.from('company_profile').upsert(payload, { 
      onConflict: 'user_id' 
    });
    
    if (!error) {
      moveNext();
    } else {
      alert("Error saving business information: " + error.message);
      btn.disabled = false;
      btn.innerText = "Save All Business Information & Continue";
    }
  }

  function renderStep6Dummy(title, desc) {
    return `
      <div style="background: rgba(128,128,128,0.03); border: 1px solid var(--border-color); border-radius: 12px; padding: 20px; margin-top:15px;">
        <p style="font-size: 13px; color: var(--text-muted);">${desc}</p>
        <button class="primary-btn" onclick="moveNext()">Confirm & Proceed</button>
      </div>`;
  }

  function renderStep7Documents() {
    const docsList = documentTypes.map((doc, index) => {
      const existing = uploadedDocs.find(d => d.document_type === doc.key);
      const uploaded = !!existing;
      const statusText = uploaded ? 'Uploaded' : 'Not uploaded';
      const statusColor = uploaded ? 'var(--accent)' : 'var(--text-muted)';
      const btnDisabled = uploaded ? 'disabled' : '';
      const btnStyle = uploaded ? 'background: #2ecc71; opacity: 1;' : 'background: var(--accent); opacity: 0.5;';
      const btnIcon = uploaded ? '<i class="fas fa-check"></i> Uploaded' : '<i class="fas fa-cloud-upload-alt"></i> Upload';
      return `
        <div class="doc-upload-item" style="margin-bottom: 20px; padding: 15px; background: rgba(128,128,128,0.03); border-radius: 8px; border: 1px solid var(--border-color);">
          <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
            <div style="flex: 1; min-width: 200px;">
              <strong style="font-size: 14px; color: var(--text-main);">${doc.label}</strong>
              <div style="font-size: 11px; color: ${statusColor}; margin-top: 4px;" id="status-${doc.key}">${statusText}</div>
            </div>
            <div style="display: flex; gap: 10px; align-items: center;">
              <div class="file-input-wrapper" style="position: relative;">
                <label for="file-${doc.key}" class="file-input-label" style="padding: 6px 12px; font-size: 12px; margin-top: 20px;">
                  <i class="fas fa-cloud-upload-alt"></i> Choose File
                </label>
                <input type="file" id="file-${doc.key}" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" onchange="handleFileSelect('${doc.key}')" style="position: absolute; left: -9999px;" ${uploaded ? 'disabled' : ''}>
              </div>
              <span id="filename-${doc.key}" style="font-size: 12px; color: var(--text-muted); max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${existing ? existing.file_name : 'No file chosen'}</span>
              <button class="upload-single-btn" id="upload-${doc.key}" onclick="uploadSingleDocument('${doc.key}')" ${btnDisabled} style="${btnStyle} color: #000; border: none; padding: 6px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; cursor: pointer;">${btnIcon}</button>
            </div>
          </div>
          <div id="file-error-${doc.key}" class="file-error" style="display: none;"></div>
        </div>
      `;
    }).join('');

    return `
      <div style="background: rgba(128,128,128,0.03); border: 1px solid var(--border-color); border-radius: 12px; padding: 25px; margin-top:15px;">
        <h4 style="margin: 0 0 20px 0; font-size: 14px; color: var(--accent); font-weight: 800;">Required Documents</h4>
        <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 25px;">Upload each required document. File names must match the document key (e.g., approved_technical_assistance.pdf).</p>
        
        <div id="documents-list">
          ${docsList}
        </div>
        
        <div style="display: flex; gap: 15px; justify-content: flex-end; margin-top: 30px; border-top: 1px solid var(--border-color); padding-top: 20px;">
          <button class="primary-btn" id="uploadAllBtn" onclick="uploadAllDocuments()" style="width: auto; padding: 12px 30px;">
            <i class="fas fa-cloud-upload-alt" style="margin-right: 8px;"></i> Upload All
          </button>
          <button class="primary-btn" id="continueAfterDocsBtn" onclick="checkDocumentsAndContinue()" style="width: auto; padding: 12px 30px; background: var(--accent);">
            Continue
          </button>
        </div>
        <div id="uploadAllStatus" style="margin-top: 15px; font-size: 13px; color: var(--text-muted);"></div>
      </div>
    `;
  }

  let selectedFiles = {};

  function handleFileSelect(docKey) {
    const input = document.getElementById(`file-${docKey}`);
    const file = input.files[0];
    const filenameSpan = document.getElementById(`filename-${docKey}`);
    const uploadBtn = document.getElementById(`upload-${docKey}`);
    const errorDiv = document.getElementById(`file-error-${docKey}`);
    
    if (file) {
      const baseName = file.name.split('.').slice(0, -1).join('.'); 
      if (baseName !== docKey) {
        errorDiv.innerText = `File name must be exactly "${docKey}" (without extension).`;
        errorDiv.style.display = 'block';
        uploadBtn.disabled = true;
        uploadBtn.style.opacity = '0.5';
        filenameSpan.innerText = file.name;
        delete selectedFiles[docKey];
        return;
      } else {
        errorDiv.style.display = 'none';
      }
      selectedFiles[docKey] = file;
      filenameSpan.innerText = file.name;
      uploadBtn.disabled = false;
      uploadBtn.style.opacity = '1';
    } else {
      delete selectedFiles[docKey];
      filenameSpan.innerText = 'No file chosen';
      uploadBtn.disabled = true;
      uploadBtn.style.opacity = '0.5';
      errorDiv.style.display = 'none';
    }
  }

  async function uploadSingleDocument(docKey) {
    const file = selectedFiles[docKey];
    if (!file) {
      alert('Please select a file first.');
      return;
    }

    const uploadBtn = document.getElementById(`upload-${docKey}`);
    const originalText = uploadBtn.innerHTML;
    uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    uploadBtn.disabled = true;

    try {
      const result = await uploadDocumentToStorage(docKey, file);
      if (result.success) {
        const newDoc = {
          document_type: docKey,
          document_label: documentTypes.find(d => d.key === docKey).label,
          file_name: result.fileName,
          uploaded_at: new Date().toISOString()
        };
        uploadedDocs = uploadedDocs.filter(d => d.document_type !== docKey);
        uploadedDocs.push(newDoc);

        document.getElementById(`status-${docKey}`).innerHTML = '<span style="color: var(--accent);"><i class="fas fa-check-circle"></i> Uploaded</span>';
        uploadBtn.innerHTML = '<i class="fas fa-check"></i> Uploaded';
        uploadBtn.style.background = '#2ecc71';
        uploadBtn.disabled = true;
        document.getElementById(`filename-${docKey}`).innerText = result.fileName;
        await fetchDocumentCount(); 
      } else {
        alert('Upload failed: ' + result.error);
        uploadBtn.innerHTML = originalText;
        uploadBtn.disabled = false;
      }
    } catch (err) {
      alert('Upload error: ' + err.message);
      uploadBtn.innerHTML = originalText;
      uploadBtn.disabled = false;
    }
  }

  async function uploadDocumentToStorage(docKey, file) {
    const docType = documentTypes.find(d => d.key === docKey);
    const fileExt = file.name.split('.').pop();
    const fileName = `${docKey}_${Date.now()}.${fileExt}`; // keep original base name for reference
    const filePath = `${user.id}/documents/${fileName}`; 

    const { error: uploadError } = await sb.storage
      .from('application-documents')
      .upload(filePath, file);

    if (uploadError) {
      return { success: false, error: uploadError.message };
    }

    const { data: urlData } = sb.storage
      .from('application-documents')
      .getPublicUrl(filePath);

    const publicUrl = urlData.publicUrl;

    const { error: dbError } = await sb
      .from('application_documents')
      .insert({
        user_id: user.id,
        document_type: docKey,
        document_label: docType.label,
        file_name: fileName,
        file_path: filePath,
        public_url: publicUrl,
        status: 'pending'
      });

    if (dbError) {
      await sb.storage.from('application-documents').remove([filePath]);
      return { success: false, error: dbError.message };
    }

    return { success: true, fileName };
  }

  async function uploadAllDocuments() {
    const statusDiv = document.getElementById('uploadAllStatus');
    statusDiv.innerHTML = 'Uploading all documents...';

    const docsToUpload = documentTypes.filter(doc => selectedFiles[doc.key]);
    if (docsToUpload.length === 0) {
      statusDiv.innerHTML = 'No files selected. Please choose files first.';
      return;
    }

    let successCount = 0;
    let failCount = 0;

    for (const doc of docsToUpload) {
      const file = selectedFiles[doc.key];
      const result = await uploadDocumentToStorage(doc.key, file);
      if (result.success) {
        successCount++;
        const newDoc = {
          document_type: doc.key,
          document_label: documentTypes.find(d => d.key === doc.key).label,
          file_name: result.fileName,
          uploaded_at: new Date().toISOString()
        };
        uploadedDocs = uploadedDocs.filter(d => d.document_type !== doc.key);
        uploadedDocs.push(newDoc);

        document.getElementById(`status-${doc.key}`).innerHTML = '<span style="color: var(--accent);"><i class="fas fa-check-circle"></i> Uploaded</span>';
        document.getElementById(`upload-${doc.key}`).innerHTML = '<i class="fas fa-check"></i> Uploaded';
        document.getElementById(`upload-${doc.key}`).disabled = true;
        document.getElementById(`filename-${doc.key}`).innerText = result.fileName;
      } else {
        failCount++;
      }
    }

    statusDiv.innerHTML = `Upload complete: ${successCount} succeeded, ${failCount} failed.`;
    await fetchDocumentCount();
  }

  function checkDocumentsAndContinue() {
    moveNext();
  }

  function renderStep8Dummy() {
    return `
      <div style="background: rgba(128,128,128,0.03); border: 1px solid var(--border-color); border-radius: 12px; padding: 20px; margin-top:15px;">
        <p style="font-size: 13px; color: var(--accent);"><i class="fas fa-clock"></i> Your application is currently under review by an administrator.</p>
        <button class="primary-btn" onclick="moveNext()">Proceed to Next Step</button>
      </div>`;
  }

  function renderStep9Dummy() {
    return `
      <div style="background: rgba(128,128,128,0.03); border: 1px solid var(--border-color); border-radius: 12px; padding: 20px; margin-top:15px;">
        <div class="input-group" style="margin-bottom: 20px;">
          <label>Primary Technological Need</label>
          <select>
            <option>Inventory Management Software</option>
            <option>Point of Sale (POS) System</option>
            <option>E-Commerce Website</option>
          </select>
        </div>
        <button class="primary-btn" onclick="moveNext()">Submit Assessment</button>
      </div>`;
  }

  function renderStep10Dummy() {
    return `
      <div style="background: rgba(46, 204, 113, 0.1); border: 1px solid var(--accent); border-radius: 12px; padding: 20px; margin-top:15px; text-align: center;">
        <h3 style="color: var(--accent); margin-top: 0;"><i class="fas fa-check-circle" style="font-size: 30px; margin-bottom: 10px; display:block;"></i> Application Complete</h3>
        <p style="font-size: 13px; color: var(--text-main);">Your application flow is complete. Waiting for final endorsement approval.</p>
        <button class="primary-btn" style="margin-top:10px; width:auto;" onclick="alert('Application Flow Finished!')">Return Home</button>
      </div>`;
  }

  async function moveNext() {
    currentStep++;
    if(currentStep <= stepsData.length) {
      await sb.from('user_profiles').update({ current_step: currentStep }).eq('id', user.id);
      renderSteps();
    }
  }

  async function fetchDocumentCount() {
    const { count, error } = await sb
      .from('application_documents')
      .select('*', { count: 'exact', head: true })
      .eq('user_id', user.id);
    if (!error) {
      document.getElementById('filesUploaded').innerText = count || 0;
    }
  }

  function updateApplicationStatus(status) {
    const pill = document.getElementById('applicationStatusPill');
    const displayStatus = status ? status.charAt(0).toUpperCase() + status.slice(1) : 'Pending';
    pill.innerText = displayStatus;
    pill.className = 'status-pill status-' + (status || 'pending');
  }

  function toggleTheme() { document.body.classList.toggle('light-theme'); }
  function handleLogout() { sb.auth.signOut().then(() => window.location.href = 'login-mock.php'); }
  window.onload = init;
</script>
</body>
</html>