<?php
$host = 'aws-1-ap-southeast-2.pooler.supabase.com';
$port = '5432';
$dbname = 'postgres';
$username = 'postgres.hmxrblblcpbikkxcwwni';
$password = 'GgqIRwBL1ktX5xNt';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $checkTable = $pdo->query("SELECT EXISTS (SELECT 1 FROM information_schema.tables WHERE table_name = 'company_profile')");
    $tableExists = $checkTable->fetchColumn();
    
    if (!$tableExists) {
        $beneficiaries = [];
        $tableError = "Table 'company_profile' does not exist.";
    } else {
        $stmt = $pdo->query("SELECT user_id, enterprise_name, created_at, status FROM company_profile ORDER BY created_at DESC");
        $beneficiaries = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    $error = $e->getMessage();
    $beneficiaries = [];
    
    error_log("Database error in psto-ben-stat.php: " . $error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Beneficiary Status | ASENXO</title>
<link rel="icon" type="image/png" href="ASENXO-WEB/favicon.png">
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>
<link rel="stylesheet" href="src/css/psto-ben-stat-style.css">

<style>
    @keyframes cardIntro {
        0% {
            opacity: 0;
            transform: translateY(30px) scale(0.98);
        }
        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    @keyframes bodyFade {
        0% { background-color: #000; }
        100% { background-color: var(--bg-body, #0a0a0a); }
    }
    
    @keyframes slideIn {
        0% {
            opacity: 0;
            transform: translateX(-20px);
        }
        100% {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes fadeInUp {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    body {
        animation: bodyFade 0.9s ease-out forwards;
        background-color: var(--bg-body, #0a0a0a);
        transition: background-color 0.3s ease;
    }
    
    .stat-card {
        animation: cardIntro 0.7s cubic-bezier(0.2, 0.9, 0.3, 1) forwards;
        transform-origin: center;
        will-change: transform, opacity;
        opacity: 0; 
    }
    
    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; }
    
    .table-container {
        animation: cardIntro 0.7s cubic-bezier(0.2, 0.9, 0.3, 1) 0.2s forwards;
        transform-origin: center;
        will-change: transform, opacity;
        opacity: 0;
    }
    
    .sidebar-menu li {
        animation: slideIn 0.5s cubic-bezier(0.2, 0.9, 0.3, 1) forwards;
        opacity: 0;
        transform-origin: left;
    }
    
    .sidebar-menu li:nth-child(1) { animation-delay: 0.1s; }
    .sidebar-menu li:nth-child(2) { animation-delay: 0.15s; }
    .sidebar-menu li:nth-child(3) { animation-delay: 0.2s; }
    .sidebar-menu li:nth-child(4) { animation-delay: 0.25s; }
    .sidebar-menu li:nth-child(5) { animation-delay: 0.3s; }
    .sidebar-menu li:nth-child(6) { animation-delay: 0.35s; }
    .sidebar-menu li:nth-child(7) { animation-delay: 0.4s; }
    
    .top-header {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
    }
    
    body, .stat-card, .table-container, .sidebar, .top-header, 
    .modal, .calendar-grid, button, input, select {
        transition: background-color 0.3s ease, 
                    border-color 0.3s ease,
                    color 0.3s ease,
                    box-shadow 0.3s ease;
    }
    
    .register-card {
        background: var(--bg-card, #0e0e0e);
    }
</style>

</head>
<body> 
<div class="app">
<header class="top-header">
<div class="top-header-left">
<span class="project-name">ASENXO</span>
<span class="badge">MSME STATUS</span>
<div class="search"><i class="fas fa-search"></i><input type="text" id="searchInput" placeholder="Search..."></div>
<div class="sort-filter-bar">
  <select id="sortSelect"><option value="date-desc">Date ▼ new</option><option value="date-asc">Date ▲ old</option><option value="name-asc">A-Z ▲</option><option value="name-desc">Z-A ▼</option></select>
  <select id="statusFilterSelect"><option value="all">All status</option><option value="pending">Pending</option><option value="approved">Approved</option></select>
  <span id="activeFilterBadge" class="filter-badge" style="display: none;"><span id="filterText"></span> <i class="fas fa-times-circle" id="clearFilterBtn"></i></span>
</div>
</div>
<div class="top-header-right">
  <button class="btn-export" id="exportBtn"><i class="fas fa-download"></i> Export</button>
  <button class="theme-toggle" id="themeToggle"><i class="fas fa-moon"></i> Dark</button> <!-- changed from Light to Dark -->
</div>
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

<div class="modal-overlay" id="confirmModal"><div class="modal"><h3 style="margin:0 0 6px;font-size:16px;">Confirm status change</h3><p style="margin:0 0 16px;color:var(--text-muted);">Approve <strong id="modalBusiness" style="color:var(--text-primary);"></strong> to schedule TNA visit.</p><div class="modal-actions"><button class="modal-btn" id="modalCancel">Cancel</button><button class="modal-btn confirm" id="modalConfirm">Confirm</button></div></div></div>

<div class="modal-overlay calendar-modal" id="calendarModal">
  <div class="modal">
    <h3 style="display:flex; align-items:center; gap:6px; margin:0 0 6px; font-size:16px;"><i class="fas fa-calendar-alt" style="color:var(--green);"></i> Schedule visit</h3>
    <p style="margin:0 0 12px; color:var(--text-muted);" id="calendarBusiness"></p>
    <div class="calendar-header">
      <button class="calendar-nav-btn" id="prevMonthBtn"><i class="fas fa-chevron-left"></i> Prev</button>
      <h4 id="monthYearDisplay">March 2026</h4>
      <button class="calendar-nav-btn" id="nextMonthBtn">Next <i class="fas fa-chevron-right"></i></button>
    </div>
    <div class="calendar-grid" id="calendarGrid"></div>
    <div class="datetime-row">
      <label>Date</label><input type="text" id="selectedDate" readonly>
      <label>Time</label>
      <select id="timeSelect" class="time-select">
        <option value="08:00 AM">8:00 AM</option><option value="09:00 AM">9:00 AM</option><option value="10:00 AM">10:00 AM</option>
        <option value="11:00 AM">11:00 AM</option><option value="12:00 PM">12:00 PM</option><option value="01:00 PM" selected>1:00 PM</option>
        <option value="02:00 PM">2:00 PM</option><option value="03:00 PM">3:00 PM</option><option value="04:00 PM">4:00 PM</option><option value="05:00 PM">5:00 PM</option>
      </select>
    </div>
    <div class="modal-actions">
      <button class="modal-btn" id="calendarCancel">Cancel</button>
      <button class="modal-btn confirm" id="calendarConfirm">Schedule</button>
    </div>
  </div>
</div>

<main class="main-content">
<div class="stats-grid">
  <div class="stat-card"><div class="stat-title">Total</div><div class="stat-value" id="totalBeneficiaries">0</div><div class="stat-change" id="totalPercent"></div></div>
  <div class="stat-card"><div class="stat-title">Pending</div><div class="stat-value" id="pendingCount">0</div><div class="stat-change" id="pendingPercent"></div></div>
  <div class="stat-card"><div class="stat-title">For Assessment</div><div class="stat-value" id="approvedCount">0</div><div class="stat-change" id="approvedPercent"></div></div>
</div>
<div class="table-container"><table><thead><tr><th>ID No.</th><th>Enterprise</th><th>Date Received</th><th>Status</th><th>Details</th><th>Action</th><th>Scheduled Visit</th></tr></thead>
<tbody id="beneficiaryTableBody">
    <?php if (empty($beneficiaries)): ?>
        <tr>
            <td colspan="7" style="text-align: center; color: var(--text-muted);">No beneficiaries found.</td>
        </tr>
    <?php else: ?>
        <?php foreach ($beneficiaries as $index => $row): ?>
        <tr>
            <td><?php echo $index + 1; ?></td>
            <td class="business-name"><?php echo htmlspecialchars($row['enterprise_name']); ?></td>
            <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
            <td>
                <span class="status-pill <?php echo ($row['status'] === 'approved') ? 'approved' : 'pending'; ?>">
                    <?php echo ($row['status'] === 'approved') ? 'FOR ASSESSMENT' : 'PENDING'; ?>
                </span>
            </td>
            <td>
                <button class="action-btn view-btn" data-user-id="<?php echo htmlspecialchars($row['user_id']); ?>" title="View Details">
                    <i class="fas fa-eye"></i>
                </button>
            </td>
            <td>
                <button class="action-btn approve" data-index="<?php echo $index; ?>" <?php echo ($row['status'] === 'approved') ? 'disabled' : ''; ?>>
                    For Assessment
                </button>
            </td>
            <td>—</td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</tbody>
</table></div>
</main>
<?php if (isset($error)): ?>
<script>
console.error('Database Error: <?php echo addslashes($error); ?>');
document.addEventListener('DOMContentLoaded', function() {
    const tbody = document.getElementById('beneficiaryTableBody');
    if (tbody) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: #e74c3c;">Database connection error. Please check credentials.</td></tr>';
    }
});
</script>
<?php endif; ?>
</div></div>

<script>
const beneficiaries = <?php echo json_encode($beneficiaries); ?>.map(b => ({
    user_id: b.user_id,
    name: b.enterprise_name,
    date: new Date(b.created_at).toLocaleDateString(),
    status: b.status
}));

let pendingApprovalIndex = null;
const tbody = document.getElementById('beneficiaryTableBody');
const searchInput = document.getElementById('searchInput');

function getTodayDate() { const d=new Date(); d.setHours(0,0,0,0); return d; }
function isValidSelectedDate(y,m,d) { 
  const sel = new Date(y,m,d); 
  sel.setHours(0,0,0,0); 
  return sel >= getTodayDate(); 
}

function updateStats(){
  const total = beneficiaries.length;
  const pending = beneficiaries.filter(b=>b.status==='pending').length;
  const approved = beneficiaries.filter(b=>b.status==='approved').length;
  document.getElementById('totalBeneficiaries').textContent = total;
  document.getElementById('pendingCount').textContent = pending;
  document.getElementById('approvedCount').textContent = approved;
  document.getElementById('totalPercent').innerHTML = '<i class="fas fa-users"></i> Active';
  document.getElementById('pendingPercent').innerHTML = `<i class="fas fa-clock"></i> ${((pending/total)*100).toFixed(1)}%`;
  document.getElementById('approvedPercent').innerHTML = `<i class="fas fa-check-circle"></i> ${((approved/total)*100).toFixed(1)}%`;
}

let currentSort='date-desc', currentStatusFilter='all';
function getFilteredAndSorted(){
  let filtered = beneficiaries.filter(b => currentStatusFilter==='all' || b.status===currentStatusFilter);
  const st = searchInput.value.toLowerCase();
  if(st) filtered = filtered.filter(b => b.name.toLowerCase().includes(st));
  const sorted = [...filtered];
  switch(currentSort){
    case 'date-desc': sorted.sort((a,b)=>a.date<b.date?1:-1); break;
    case 'date-asc': sorted.sort((a,b)=>a.date>b.date?1:-1); break;
    case 'name-asc': sorted.sort((a,b)=>a.name.localeCompare(b.name)); break;
    case 'name-desc': sorted.sort((a,b)=>b.name.localeCompare(a.name)); break;
  }
  return sorted;
}

function renderTable(){
  const data = getFilteredAndSorted();
  tbody.innerHTML = '';
  data.forEach(b => {
    const originalIdx = beneficiaries.findIndex(ben => ben.name===b.name && ben.date===b.date);
    const statusClass = b.status==='approved'?'status-approved':'status-pending';
    const statusText = b.status==='approved'?'FOR ASSESSMENT':'PENDING';
    const row = document.createElement('tr');
    row.innerHTML = `<td>${String(originalIdx+1).padStart(4,'0')}</td><td>${b.name}</td><td>${b.date}</td>
      <td><span class="status-badge ${statusClass}">${statusText}</span></td>
      <td><button class="action-btn view-btn" data-user-id="${b.user_id}">View</button></td>
      <td><button class="action-btn approve" data-index="${originalIdx}" ${b.status==='approved'?'disabled':''}>For Assessment</button></td>
      <td>${b.scheduled||'—'}</td>`;
    tbody.appendChild(row);
  });
  updateStats();
  document.querySelectorAll('.view-btn').forEach(button => {
    button.addEventListener('click', function() {
        const userId = this.getAttribute('data-user-id');
        window.location.href = `psto-view.php?user_id=${encodeURIComponent(userId)}`;
    });
  });
  document.querySelectorAll('.approve').forEach(btn => btn.addEventListener('click',(e)=>{
    if(!e.target.disabled){ 
      pendingApprovalIndex = e.target.dataset.index;
      document.getElementById('modalBusiness').textContent = beneficiaries[pendingApprovalIndex].name;
      document.getElementById('confirmModal').classList.add('active'); 
    }
  }));
  const badge=document.getElementById('activeFilterBadge');
  if(currentStatusFilter!=='all'){ badge.style.display='inline-flex'; document.getElementById('filterText').innerText=currentStatusFilter==='pending'?'Pending':'Approved'; }
  else badge.style.display='none';
}

let currentYear, currentMonth, selectedDay;

function initCalendarToToday() {
  const today = new Date();
  currentYear = today.getFullYear();
  currentMonth = today.getMonth();      
  selectedDay = today.getDate();
}

function updateCalendar() {
  const monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];
  document.getElementById('monthYearDisplay').textContent = monthNames[currentMonth] + ' ' + currentYear;
  
  const firstDayOfMonth = new Date(currentYear, currentMonth, 1).getDay(); 
  let startOffset = (firstDayOfMonth === 0) ? 6 : firstDayOfMonth - 1; 
  
  const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
  
  const grid = document.getElementById('calendarGrid');
  grid.innerHTML = `
    <div class="calendar-day-header">MO</div><div class="calendar-day-header">TU</div><div class="calendar-day-header">WE</div>
    <div class="calendar-day-header">TH</div><div class="calendar-day-header">FR</div><div class="calendar-day-header">SA</div><div class="calendar-day-header">SU</div>
  `;
  
  for (let i = 0; i < startOffset; i++) {
    const empty = document.createElement('div');
    empty.className = 'calendar-cell empty';
    grid.appendChild(empty);
  }
  
  for (let d = 1; d <= daysInMonth; d++) {
    const cell = document.createElement('div');
    cell.className = 'calendar-cell';
    cell.innerText = d;
    cell.dataset.day = d;
    
    if (!isValidSelectedDate(currentYear, currentMonth, d)) {
      cell.classList.add('past-date');
    } else if (d === selectedDay) {
      cell.classList.add('selected');
    }
    
    cell.addEventListener('click', function() {
      if (this.classList.contains('past-date')) return;
      document.querySelectorAll('#calendarGrid .calendar-cell').forEach(c => c.classList.remove('selected'));
      this.classList.add('selected');
      selectedDay = parseInt(this.dataset.day);
      const monthStr = String(currentMonth + 1).padStart(2, '0');
      const dayStr = String(selectedDay).padStart(2, '0');
      document.getElementById('selectedDate').value = `${currentYear}-${monthStr}-${dayStr}`;
    });
    
    grid.appendChild(cell);
  }
  
  if (selectedDay && selectedDay <= daysInMonth && isValidSelectedDate(currentYear, currentMonth, selectedDay)) {
    const monthStr = String(currentMonth + 1).padStart(2, '0');
    const dayStr = String(selectedDay).padStart(2, '0');
    document.getElementById('selectedDate').value = `${currentYear}-${monthStr}-${dayStr}`;
  } else {
    for (let d = 1; d <= daysInMonth; d++) {
      if (isValidSelectedDate(currentYear, currentMonth, d)) {
        selectedDay = d;
        break;
      }
    }
    const monthStr = String(currentMonth + 1).padStart(2, '0');
    const dayStr = String(selectedDay).padStart(2, '0');
    document.getElementById('selectedDate').value = `${currentYear}-${monthStr}-${dayStr}`;
    document.querySelectorAll('#calendarGrid .calendar-cell').forEach(cell => {
      if (parseInt(cell.dataset.day) === selectedDay) cell.classList.add('selected');
    });
  }
}

document.getElementById('prevMonthBtn').addEventListener('click', () => {
  currentMonth--;
  if (currentMonth < 0) { currentMonth = 11; currentYear--; }
  const daysInNewMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
  if (selectedDay > daysInNewMonth) selectedDay = daysInNewMonth;
  if (!isValidSelectedDate(currentYear, currentMonth, selectedDay)) {
    for (let d = 1; d <= daysInNewMonth; d++) {
      if (isValidSelectedDate(currentYear, currentMonth, d)) { selectedDay = d; break; }
    }
  }
  updateCalendar();
});

document.getElementById('nextMonthBtn').addEventListener('click', () => {
  currentMonth++;
  if (currentMonth > 11) { currentMonth = 0; currentYear++; }
  const daysInNewMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
  if (selectedDay > daysInNewMonth) selectedDay = daysInNewMonth;
  if (!isValidSelectedDate(currentYear, currentMonth, selectedDay)) {
    for (let d = 1; d <= daysInNewMonth; d++) {
      if (isValidSelectedDate(currentYear, currentMonth, d)) { selectedDay = d; break; }
    }
  }
  updateCalendar();
});

document.getElementById('modalConfirm').onclick = () => {
  if (pendingApprovalIndex !== null) {
    const b = beneficiaries[pendingApprovalIndex];
    document.getElementById('calendarBusiness').innerHTML = `<strong>${b.name}</strong> — select date & time`;
    const today = new Date();
    currentYear = today.getFullYear();
    currentMonth = today.getMonth();
    selectedDay = today.getDate();
    updateCalendar();
    document.getElementById('confirmModal').classList.remove('active');
    document.getElementById('calendarModal').classList.add('active');
  } else {
    document.getElementById('confirmModal').classList.remove('active');
  }
};

document.getElementById('modalCancel').onclick = () => {
  document.getElementById('confirmModal').classList.remove('active');
  pendingApprovalIndex = null;
};

document.getElementById('calendarCancel').onclick = () => {
  document.getElementById('calendarModal').classList.remove('active');
  pendingApprovalIndex = null;
};

document.getElementById('calendarConfirm').onclick = () => {
  if (pendingApprovalIndex !== null) {
    const dateStr = document.getElementById('selectedDate').value;
    const timeStr = document.getElementById('timeSelect').value;
    const [y,m,d] = dateStr.split('-').map(Number);
    if (!isValidSelectedDate(y, m-1, d)) { alert('Cannot schedule past date'); return; }
    beneficiaries[pendingApprovalIndex].status = 'approved';
    beneficiaries[pendingApprovalIndex].scheduled = `${dateStr} ${timeStr}`;
    renderTable();
  }
  document.getElementById('calendarModal').classList.remove('active');
  pendingApprovalIndex = null;
};


document.getElementById('themeToggle').onclick = function() {
  document.body.classList.toggle('dark');
  const buttonText = document.querySelector('#themeToggle');
  if (document.body.classList.contains('dark')) {
    buttonText.innerHTML = '<i class="fas fa-sun"></i> Light';
  } else {
    buttonText.innerHTML = '<i class="fas fa-moon"></i> Dark';
  }
};

document.getElementById('exportBtn').onclick = () => {
  const exportData = beneficiaries.map((b,i) => ({ ID: i+1, 'Business Name': b.name, 'Date Received': b.date, Status: b.status==='approved'?'FOR ASSESSMENT':'PENDING', 'Scheduled Visit': b.scheduled||'' }));
  const ws = XLSX.utils.json_to_sheet(exportData);
  const wb = XLSX.utils.book_new(); XLSX.utils.book_append_sheet(wb, ws, 'Beneficiaries'); XLSX.writeFile(wb, 'beneficiaries.xlsx');
};

searchInput.addEventListener('input', renderTable);
document.getElementById('sortSelect').addEventListener('change', (e) => { currentSort = e.target.value; renderTable(); });
document.getElementById('statusFilterSelect').addEventListener('change', (e) => { currentStatusFilter = e.target.value; renderTable(); });
document.getElementById('clearFilterBtn')?.addEventListener('click', () => { currentStatusFilter = 'all'; document.getElementById('statusFilterSelect').value = 'all'; renderTable(); });

initCalendarToToday();
renderTable();
</script>
</body>
</html>