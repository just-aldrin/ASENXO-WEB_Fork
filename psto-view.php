<?php
session_start();

$host = 'aws-1-ap-southeast-2.pooler.supabase.com';
$port = '5432';
$dbname = 'postgres';
$username = 'postgres.hmxrblblcpbikkxcwwni';
$password = 'GgqIRwBL1ktX5xNt';

$user_id = $_GET['user_id'] ?? '';
if (empty($user_id)) {
    die('No user specified.');
}

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare('SELECT * FROM owner_profile WHERE "owner_ID" = ?');
    $stmt->execute([$user_id]);
    $owner = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT * FROM company_profile WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $company = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT document_type, document_label, file_name, uploaded_at FROM application_documents WHERE user_id = ? ORDER BY uploaded_at DESC");
    $stmt->execute([$user_id]);
    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $docMap = [];
    foreach ($documents as $doc) {
        $docMap[$doc['document_type']] = $doc;
    }

    if (!$owner) $owner = [];
    if (!$company) $company = [];

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

$documentTypes = [
    ['key' => 'approved_technical_assistance', 'label' => '1. Approved Request for Technical Assistance'],
    ['key' => 'tna_form_01', 'label' => '2. DOST TNA Form 01 (Application for TNA)'],
    ['key' => 'tna_form_02', 'label' => '3. DOST TNA Form 02 + Technology Level Assessment'],
    ['key' => 'letter_of_intent', 'label' => '4. Letter of intent (refund & insurance commitment)'],
    ['key' => 'setup_form_001', 'label' => '5. SETUP Form 001 Project Proposal (Annex A-1)'],
    ['key' => 'mayors_permit_dti', 'label' => '6. Mayor\'s permit / DTI registration (photocopy)'],
    ['key' => 'cash_sales_invoice', 'label' => '7. Company cash/sales invoice (photocopy)'],
    ['key' => 'board_resolution', 'label' => '8. Board Resolution authorizing availment'],
    ['key' => 'inhouse_fs_sworn', 'label' => '9. In-house FS (3 yrs) + notarized sworn statement'],
    ['key' => 'sworn_affidavit', 'label' => '10. Sworn affidavit (consanguinity / bad debt)'],
    ['key' => 'equipment_specs', 'label' => '11. Equipment technical specs / drawings'],
    ['key' => 'three_quotations', 'label' => '12. Three quotations (fermenters, bottles, etc.)'],
    ['key' => 'projected_fs', 'label' => '13. Projected Financial Statements (5 years)'],
    ['key' => 'work_financial_plan', 'label' => '14. Work & Financial Plan / equity details'],
    ['key' => 'gad_checklist_2', 'label' => '15. GAD Checklist 2 (project identification)'],
    ['key' => 'data_privacy_consent', 'label' => '16. Data Privacy Consent Form']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Details | ASENXO</title>
  <link rel="icon" type="image/png" href="ASENXO-WEB/favicon.png">
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="src/css/psto-view-style.css">
  <style>
    .profile-pic-large {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      overflow: hidden;
      background: var(--input-bg, #222);
      display: flex;
      align-items: center;
      justify-content: center;
      border: 2px solid var(--border-color, #333);
      flex-shrink: 0;
    }
    .profile-pic-large img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .profile-pic-large i {
      font-size: 40px;
      color: var(--text-muted, #666);
    }

    .req-list {
      margin-top: 10px;
    }
    .req-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 12px 15px;
      border-bottom: 1px solid var(--border-color, #333);
    }
    .req-item:last-child {
      border-bottom: none;
    }
    .req-info {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .req-info i {
      width: 20px;
      text-align: center;
    }
    .req-name {
      font-size: 13px;
      color: var(--text-main, #fff);
    }
    .req-date {
      font-size: 12px;
      color: var(--text-muted, #888);
      display: flex;
      align-items: center;
      gap: 5px;
    }
    .check-badge {
      color: var(--accent, #2ecc71);
    }
    .missing-badge {
      color: var(--text-muted, #888);
    }
    .file-checklist-section {
      margin-top: 30px;
    }
    .section-header h3 {
      margin-bottom: 15px;
    }
    .divider {
      height: 1px;
      background: var(--border-color, #333);
      margin: 20px 0;
    }
    .summary-stats {
      display: flex;
      gap: 20px;
      margin-top: 20px;
      color: var(--text-muted);
      font-size: 12px;
      border-top: 1px solid var(--border-color);
      padding-top: 16px;
    }
  </style>
</head>
<body> 
<div class="app">
  <header class="top-header">
    <div style="display: flex; align-items: center; gap: 16px;">
      <span class="project-name">ASENXO</span>
      <span class="badge">PROVINCIAL</span>
    </div>
    <button class="theme-toggle" id="themeToggle"><i class="fas fa-moon"></i> Dark</button> <!-- changed from Light -->
  </header>

  <div class="content-row">
    <aside class="sidebar">
      <div class="sidebar-section"><div class="sidebar-header">MENU</div>
        <ul class="sidebar-menu">
          <li><a href="psto-home.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
          <li class="active"><a href="psto-ben-stat.php"><i class="fas fa-user-check"></i><span>Beneficiary Status</span></a></li>
          <li><a href="psto-map-view.php"><i class="fas fa-map-marked-alt"></i><span>Map View</span></a></li>
          <li><a href="psto-tna-tool.php"><i class="fas fa-clipboard-list"></i><span>TNA Tool</span></a></li>
          <li><a href="psto-revisions.php"><i class="fas fa-history"></i><span>Revisions</span></a></li>
          <li><a href="psto-endorsement.php"><i class="fas fa-check-circle"></i><span>Endorsement</span></a></li>
          <li><a href="psto-settings.php"><i class="fas fa-gear"></i><span>Settings</span></a></li>
        </ul>
      </div>
    </aside>

    <main class="main-content">
      <div class="profile-grid">
        <div class="info-card">
          <div class="client-card-with-pic" style="display: flex; gap: 20px;">
            <div class="profile-pic-large">
              <?php if (!empty($owner['profile_pic_url'])): ?>
                <img src="<?php echo htmlspecialchars($owner['profile_pic_url']); ?>" alt="Profile">
              <?php else: ?>
                <i class="fas fa-user"></i>
              <?php endif; ?>
            </div>
            <div class="info-content" style="flex:1;">
              <h3 style="margin-top: 0; border-bottom: none; padding-bottom: 0;"><i class="fas fa-user-circle"></i> iFund Client Information</h3>
              <div class="info-row"><span class="info-label">Full name</span><span class="info-value"><?php echo htmlspecialchars($owner['owner_name'] ?? 'N/A'); ?></span></div>
              <div class="info-row"><span class="info-label">Nickname</span><span class="info-value"><?php echo htmlspecialchars($owner['owner_nickname'] ?? 'N/A'); ?></span></div>
              <div class="info-row"><span class="info-label">Date / Place of birth</span><span class="info-value">
                <?php 
                $dob = !empty($owner['owner_dob']) ? date('d M Y', strtotime($owner['owner_dob'])) : 'N/A';
                $pob = htmlspecialchars($owner['owner_pob'] ?? 'N/A');
                echo $dob . ' · ' . $pob;
                ?>
              </span></div>
              <div class="info-row"><span class="info-label">Nationality</span><span class="info-value"><?php echo htmlspecialchars($owner['owner_nationality'] ?? 'N/A'); ?></span></div>
            </div>
          </div>
          <div class="gender-marital" style="margin-left: 0; margin-top: 8px;">
            <span class="tag"><i class="fas fa-venus-mars"></i> <?php echo htmlspecialchars($owner['owner_sex'] ?? 'N/A'); ?></span>
            <span class="tag"><i class="fas fa-heart"></i> <?php echo htmlspecialchars($owner['owner_marstat'] ?? 'N/A'); ?></span>
          </div>
          <div class="info-row"><span class="info-label">Spouse</span><span class="info-value"><?php echo htmlspecialchars($owner['owner_spouse'] ?? 'N/A'); ?></span></div>
          <div class="divider"></div>
          <div class="info-row"><span class="info-label">Home address</span><span class="info-value"><?php echo htmlspecialchars($owner['owner_address'] ?? 'N/A'); ?></span></div>
          <div class="info-row"><span class="info-label">Contact Nos.</span><span class="info-value"><?php echo htmlspecialchars($owner['owner_contactnum'] ?? 'N/A'); ?></span></div>
          <div class="info-row"><span class="info-label">Email address</span><span class="info-value"><?php echo htmlspecialchars($owner['owner_email'] ?? 'N/A'); ?></span></div>
          <div class="divider"></div>
          <div class="info-row"><span class="info-label">Company name</span><span class="info-value"><?php echo htmlspecialchars($owner['enterprise_name'] ?? 'N/A'); ?></span></div>
          <div class="info-row"><span class="info-label">Company address</span><span class="info-value"><?php echo htmlspecialchars($owner['enterprise_address'] ?? 'N/A'); ?></span></div>
          <div class="info-row"><span class="info-label">Designation</span><span class="info-value"><?php echo htmlspecialchars($owner['enterprise_designation'] ?? 'N/A'); ?></span></div>
          <div class="info-row"><span class="info-label">Highest education</span><span class="info-value"><?php echo htmlspecialchars($owner['owner_hea'] ?? 'N/A'); ?></span></div>
          <div class="info-row"><span class="info-label">Affiliations</span><span class="info-value"><?php echo htmlspecialchars($owner['owner_affiliations'] ?? 'N/A'); ?></span></div>
        </div>

        <div class="info-card">
          <h3><i class="fas fa-building"></i> Enterprise Profile</h3>
          <div class="info-row"><span class="info-label">Name of Firm</span><span class="info-value"><?php echo htmlspecialchars($company['enterprise_name'] ?? 'N/A'); ?></span></div>
          <div class="info-row"><span class="info-label">Address</span><span class="info-value"><?php echo htmlspecialchars($company['enterprise_address'] ?? 'N/A'); ?></span></div>
          <div class="info-row"><span class="info-label">Contact Person</span><span class="info-value"><?php echo htmlspecialchars($company['contact_person'] ?? 'N/A'); ?></span></div>
          <div class="info-row"><span class="info-label">Contact No.</span><span class="info-value"><?php echo htmlspecialchars($company['contact_number'] ?? 'N/A'); ?></span></div>
          <div class="info-row"><span class="info-label">Email</span><span class="info-value"><?php echo htmlspecialchars($company['enterprise_email'] ?? 'N/A'); ?></span></div>
          <div class="info-row"><span class="info-label">Year Established</span><span class="info-value"><?php echo htmlspecialchars($company['year_established'] ?? 'N/A'); ?></span></div>
          
          <div class="info-row geo-row">
            <span class="info-label"><i class="fas fa-map-pin"></i> Latitude</span>
            <span class="info-value"><?php echo htmlspecialchars($company['enterprise_lat'] ?? 'N/A'); ?></span>
          </div>
          <div class="info-row geo-row">
            <span class="info-label"><i class="fas fa-map-marker-alt"></i> Longitude</span>
            <span class="info-value"><?php echo htmlspecialchars($company['enterprise_long'] ?? 'N/A'); ?></span>
          </div>

          <div style="display: flex; gap: 20px; flex-wrap: wrap; margin: 12px 0;">
            <span class="tag"><?php echo htmlspecialchars($company['organization_type'] ?? 'N/A'); ?></span>
            <span class="tag"><?php echo htmlspecialchars($company['business_type'] ?? 'N/A'); ?></span>
            <span class="tag"><?php echo htmlspecialchars($company['msme_type'] ?? 'N/A'); ?></span>
          </div>

          <?php
          $direct_prod_male = $company['emp_direct_prod_male'] ?? 0;
          $direct_prod_female = $company['emp_direct_prod_female'] ?? 0;
          $direct_nonprod_male = $company['emp_direct_nonprod_male'] ?? 0;
          $direct_nonprod_female = $company['emp_direct_nonprod_female'] ?? 0;
          $indirect_prod_male = $company['emp_indirect_prod_male'] ?? 0;
          $indirect_prod_female = $company['emp_indirect_prod_female'] ?? 0;
          $indirect_nonprod_male = $company['emp_indirect_nonprod_male'] ?? 0;
          $indirect_nonprod_female = $company['emp_indirect_nonprod_female'] ?? 0;
          $total_male = $direct_prod_male + $direct_nonprod_male + $indirect_prod_male + $indirect_nonprod_male;
          $total_female = $direct_prod_female + $direct_nonprod_female + $indirect_prod_female + $indirect_nonprod_female;
          ?>
          <table class="emp-table">
            <tr><th>Employment</th><th>Male</th><th>Female</th><th>Total</th></tr>
            <tr><td>Direct Production</td><td><?php echo $direct_prod_male; ?></td><td><?php echo $direct_prod_female; ?></td><td><?php echo $direct_prod_male + $direct_prod_female; ?></td></tr>
            <tr><td>Direct Non‑Production</td><td><?php echo $direct_nonprod_male; ?></td><td><?php echo $direct_nonprod_female; ?></td><td><?php echo $direct_nonprod_male + $direct_nonprod_female; ?></td></tr>
            <tr><td>Indirect/Contract</td><td><?php echo $indirect_prod_male + $indirect_nonprod_male; ?></td><td><?php echo $indirect_prod_female + $indirect_nonprod_female; ?></td><td><?php echo ($indirect_prod_male + $indirect_prod_female + $indirect_nonprod_male + $indirect_nonprod_female); ?></td></tr>
            <tr><td><strong>Total</strong></td><td><strong><?php echo $total_male; ?></strong></td><td><strong><?php echo $total_female; ?></strong></td><td><strong><?php echo $total_male + $total_female; ?></strong></td></tr>
          </table>

          <div class="reg-grid">
            <div><span class="info-label">DTI</span> <?php echo htmlspecialchars($company['DTI_reg_num'] ?? 'N/A'); ?> (<?php echo !empty($company['dti_reg_date']) ? date('d M Y', strtotime($company['dti_reg_date'])) : 'N/A'; ?>)</div>
            <div><span class="info-label">SEC</span> <?php echo htmlspecialchars($company['SEC_reg_num'] ?? 'N/A'); ?> (<?php echo !empty($company['sec_reg_date']) ? date('d M Y', strtotime($company['sec_reg_date'])) : 'N/A'; ?>)</div>
            <div><span class="info-label">CDA</span> <?php echo htmlspecialchars($company['CDA_reg_num'] ?? 'N/A'); ?> (<?php echo !empty($company['cda_reg_date']) ? date('d M Y', strtotime($company['cda_reg_date'])) : 'N/A'; ?>)</div>
            <div><span class="info-label">Other</span> <?php echo htmlspecialchars($company['others_reg_num'] ?? 'N/A'); ?> (<?php echo !empty($company['other_reg_date']) ? date('d M Y', strtotime($company['other_reg_date'])) : 'N/A'; ?>)</div>
          </div>

          <div class="info-row"><span class="info-label">Business activity</span><span class="info-value"><?php echo htmlspecialchars($company['business_activities'] ?? 'N/A'); ?></span></div>
          <div class="info-row"><span class="info-label">Brief background</span><span class="info-value"><?php echo htmlspecialchars($company['enterprise_background'] ?? 'N/A'); ?></span></div>
        </div>
      </div>

      <div class="file-checklist-section">
        <div class="section-header">
          <h3><i class="fas fa-clipboard-check"></i> Requirements & Uploads</h3>
        </div>

        <div class="req-list">
          <?php 
          $uploadedCount = 0;
          foreach ($documentTypes as $doc): 
            $uploaded = isset($docMap[$doc['key']]);
            if ($uploaded) $uploadedCount++;
          ?>
          <div class="req-item">
            <div class="req-info">
              <?php if ($uploaded): ?>
                <i class="fas fa-check-circle check-badge"></i>
              <?php else: ?>
                <i class="far fa-circle missing-badge"></i>
              <?php endif; ?>
              <span class="req-name"><?php echo htmlspecialchars($doc['label']); ?></span>
            </div>
            <?php if ($uploaded): ?>
              <span class="req-date"><i class="far fa-calendar-alt"></i> <?php echo date('d M Y', strtotime($docMap[$doc['key']]['uploaded_at'])); ?></span>
            <?php else: ?>
              <span class="req-date">—</span>
            <?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>

        <div class="summary-stats">
          <span><i class="fas fa-check-circle" style="color: var(--accent);"></i> <?php echo $uploadedCount; ?>/16 requirements complied</span>
          <?php 
          $latest = !empty($documents) ? max(array_column($documents, 'uploaded_at')) : null;
          if ($latest):
          ?>
          <span><i class="far fa-clock"></i> latest upload: <?php echo date('d M Y', strtotime($latest)); ?></span>
          <?php endif; ?>
        </div>
      </div>

    </main>
  </div>
</div>

<script>
  document.getElementById('themeToggle').onclick = function() {
    document.body.classList.toggle('dark');
    const btn = document.querySelector('#themeToggle');
    if (document.body.classList.contains('dark')) {
      btn.innerHTML = '<i class="fas fa-sun"></i> Light';
    } else {
      btn.innerHTML = '<i class="fas fa-moon"></i> Dark';
    }
  };
</script>
</body>
</html>