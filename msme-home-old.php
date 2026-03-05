<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ASENXO | MSME Dashboard</title>
  <link rel="icon" type="image/png" href="favicon.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="src/css/msme-home-style.css">
  <style>
   
    .app {
      animation: fadeInUp 0.6s ease-out;
    }
    @keyframes fadeInUp {
      0% {
        opacity: 0;
        transform: translateY(10px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }
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
      <span>Connect</span>
      <span>Feedback</span>
      <div class="search">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search...">
      </div>
      <span class="project-id">ASENXOProject</span>
      <span class="badge">NANO</span>
      <!-- removed stray '<' here -->
      <button class="theme-toggle" id="themeToggle" aria-label="Toggle light/dark mode">
        <i class="fas fa-sun"></i> 
      </button>
    </div>
  </div>

  <div class="content-row">
    <!-- SIDEBAR (MSME modules + supabase sections) -->
    <div class="sidebar">
      <div class="sidebar-section">
        <div class="sidebar-header">MSME DASHBOARD</div>
        <ul class="sidebar-menu">
          <li class="active"><i class="fas fa-cube"></i> Application Module</li>
          <li><i class="fas fa-chart-line"></i> Progress Monitoring</li>
          <li><i class="fas fa-cloud-upload-alt"></i> Document Upload History</li>
          <li><i class="fas fa-history"></i> Revisions</li>
          <li><i class="fas fa-file-alt"></i> Forms for Requirements</li>
          <li><i class="fas fa-cog"></i> Settings</li>
        </ul>
      </div>

      <div class="sidebar-section">
        <div class="sidebar-header">PROJECT</div>
        <ul class="sidebar-menu">
          <li><i class="fas fa-database"></i> Database</li>
          <li><i class="fas fa-lock"></i> Authentication</li>
          <li><i class="fas fa-cloud"></i> Storage</li>
          <li><i class="fas fa-chart-bar"></i> Observability</li>
          <li><i class="fas fa-sliders-h"></i> Project Settings</li>
        </ul>
      </div>

      <div class="msme-label">
        <i class="fas fa-user-tie"></i> MSME Portal · v1.0
      </div>
    </div>

    <!-- MAIN CONTENT: progress view + info board -->
    <div class="main-content">
      <!-- LEFT COLUMN: progress stepper -->
      <div class="progress-column">
        <div class="card">
          <div class="card-title"><i class="fas fa-clipboard-list" style="margin-right: 6px;"></i> Application Progress</div>
          <ul class="step-list">
            <li class="step-item">
              <span class="step-icon completed"><i class="fas fa-check"></i></span>
              <div class="step-content">
                <div class="step-title">Choose Account Type</div>
                <div class="step-description">Personal or Business</div>
              </div>
              <span class="step-status">Completed</span>
            </li>
            <li class="step-item">
              <span class="step-icon current"><i class="fas fa-spinner"></i></span>
              <div class="step-content">
                <div class="step-title">Verify Mobile Number & Email Address</div>
                <div class="step-description">OTP sent to +63 *** *** 0123</div>
              </div>
              <span class="step-status">In progress</span>
            </li>
            <li class="step-item">
              <span class="step-icon">3</span>
              <div class="step-content">
                <div class="step-title">Proceed</div>
                <div class="step-description">Next step after verification</div>
              </div>
            </li>
            <li class="step-item">
              <span class="step-icon"><i class="fas fa-image"></i></span>
              <div class="step-content">
                <div class="step-title">IMAGE HERE</div>
                <div class="step-description">Upload profile image</div>
              </div>
            </li>
            <li class="step-item">
              <span class="step-icon">5</span>
              <div class="step-content">
                <div class="step-title">Complete Your Information</div>
                <div class="step-description">Personal details</div>
              </div>
            </li>
            <li class="step-item">
              <span class="step-icon">6</span>
              <div class="step-content">
                <div class="step-title">Complete Business Information</div>
                <div class="step-description">Business details</div>
              </div>
            </li>
            <li class="step-item">
              <span class="step-icon">7</span>
              <div class="step-content">
                <div class="step-title">Account Confirmation</div>
                <div class="step-description">Review and confirm</div>
              </div>
            </li>
            <li class="step-item">
              <span class="step-icon">8</span>
              <div class="step-content">
                <div class="step-title">Submit Required Documents</div>
                <div class="step-description">PDF, images</div>
              </div>
            </li>
            <li class="step-item">
              <span class="step-icon">9</span>
              <div class="step-content">
                <div class="step-title">Application Status</div>
                <div class="step-description">Pending review</div>
              </div>
            </li>
            <li class="step-item">
              <span class="step-icon">10</span>
              <div class="step-content">
                <div class="step-title">Technology Needs Assessment Results</div>
                <div class="step-description">Based on survey</div>
              </div>
            </li>
            <li class="step-item">
              <span class="step-icon">11</span>
              <div class="step-content">
                <div class="step-title">Endorsement Status</div>
                <div class="step-description">Waiting for approval</div>
              </div>
            </li>
            <li class="step-item">
              <span class="step-icon"><i class="fas fa-flag-checkered"></i></span>
              <div class="step-content">
                <div class="step-title">ALL ABOUT SETUP</div>
                <div class="step-description">Final configuration</div>
              </div>
            </li>
          </ul>
        </div>

        <div class="card">
          <div class="card-title">Getting started</div>
          <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 12px;">No-code <> Code</p>
          <div style="display: flex; gap: 8px;">
            <span class="badge">No-code</span>
            <span class="badge">Code</span>
          </div>
          <button>Dismiss</button>
        </div>
      </div>

      <!-- RIGHT COLUMN: info board -->
      <div class="info-column">
        <div class="card">
          <div class="card-title"><i class="fas fa-thumbtack" style="margin-right: 6px;"></i> Application overview</div>
          <div class="info-stats">
            <div class="stat-item">
              <span class="stat-label">Status</span>
              <span class="stat-value" style="color: #eab308;">In Review</span>
            </div>
            <div class="stat-item">
              <span class="stat-label">Completion</span>
              <span class="stat-value">65%</span>
            </div>
            <div class="progress-bar-bg"><div class="progress-bar-fill" style="width:65%"></div></div>
            <div class="stat-item">
              <span class="stat-label">Last updated</span>
              <span class="stat-value">Mar 1, 2026</span>
            </div>
            <div class="stat-item">
              <span class="stat-label">Documents</span>
              <span class="stat-value">4/7 uploaded</span>
            </div>
          </div>

          <div class="recent-docs">
            <div class="card-title" style="margin-top: 16px;"><i class="fas fa-paperclip" style="margin-right: 6px;"></i> Recent uploads</div>
            <div class="doc-item"><i class="fas fa-file-pdf"></i> business_permit.pdf</div>
            <div class="doc-item"><i class="fas fa-file-image"></i> id_photo.jpg</div>
            <div class="doc-item"><i class="fas fa-file-alt"></i> application_form.pdf</div>
          </div>
        </div>

        <div class="card">
          <div class="card-title"><i class="fas fa-database" style="margin-right: 6px;"></i> Primary Database</div>
          <div class="info-stats">
            <div class="stat-item"><span class="stat-label">STATUS</span><span class="badge" style="background-color: #14532d; color: #86efac;">Healthy</span></div>
            <div class="stat-item"><span class="stat-label">LAST MIGRATION</span><span class="stat-value">No migrations</span></div>
            <div class="stat-item"><span class="stat-label">Region</span><span class="stat-value">Oceania (Sydney)</span></div>
            <div class="stat-item"><span class="stat-label">Instance</span><span class="stat-value">ap-southeast-2 · t4g.nano</span></div>
            <div class="stat-item"><span class="stat-label">LAST BACKUP</span><span class="stat-value">No backups</span></div>
            <div class="stat-item"><span class="stat-label">RECENT BRANCH</span><span class="stat-value">No branches</span></div>
          </div>
        </div>

        <div class="card">
          <div class="card-title"><i class="fas fa-cog" style="margin-right: 6px;"></i> Choose a preferred workflow</div>
          <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 16px;">With Supabase, you have the flexibility to adopt a workflow that works for you.</p>
          <div style="display: flex; gap: 12px;">
            <span class="badge">No-code</span>
            <span class="badge">Code</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  (function() {
    const toggle = document.getElementById('themeToggle');
    const body = document.body;
    const icon = toggle.querySelector('i');

    function updateIcon() {
      if (body.classList.contains('dark')) {
        icon.className = 'fas fa-sun'; 
      } else {
        icon.className = 'fas fa-moon'; 
      }
    }

    toggle.addEventListener('click', () => {
      if (body.classList.contains('dark')) {
        body.classList.remove('dark');
        body.classList.add('light');
      } else {
        body.classList.remove('light');
        body.classList.add('dark');
      }
      updateIcon();
    });

    updateIcon();
  })();
</script>
</body>
</html>