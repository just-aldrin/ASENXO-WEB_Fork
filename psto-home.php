<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PSTO Dashboard | ASENXO</title>
<!-- FAVICON -->
<link rel="icon" type="image/png" href="ASENXO-WEB/favicon.png">
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>
<link rel="stylesheet" href="src/css/psto-ben-stat-style.css">

<style>
    /* Intro Animations */
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
    
    /* Apply animations to elements */
    body {
        animation: bodyFade 0.9s ease-out forwards;
        background-color: var(--bg-body, #0a0a0a);
        transition: background-color 0.3s ease;
    }
    
    .stat-card {
        animation: cardIntro 0.7s cubic-bezier(0.2, 0.9, 0.3, 1) forwards;
        transform-origin: center;
        will-change: transform, opacity;
        opacity: 0; /* Start invisible */
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
    
    /* Ensure smooth theme transition */
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
<span class="badge">OVERVIEW</span>
<div class="search"><i class="fas fa-search"></i><input type="text" id="searchInput" placeholder="Search..."></div>
<div class="sort-filter-bar">
  <select id="sortSelect"><option value="date-desc">Date ▼ new</option><option value="date-asc">Date ▲ old</option><option value="name-asc">A-Z ▲</option><option value="name-desc">Z-A ▼</option></select>
  <select id="statusFilterSelect"><option value="all">All status</option><option value="pending">Pending</option><option value="approved">Approved</option></select>
  <span id="activeFilterBadge" class="filter-badge" style="display: none;"><span id="filterText"></span> <i class="fas fa-times-circle" id="clearFilterBtn"></i></span>
</div>
</div>
<div class="top-header-right">
  <button class="btn-export" id="exportBtn"><i class="fas fa-download"></i> Export</button>
  <button class="theme-toggle" id="themeToggle"><i class="fas fa-moon"></i> Dark</button> <!-- changed from Light -->
</div>
</header>

<div class="content-row">
<aside class="sidebar">
  <div class="sidebar-section"><div class="sidebar-header">MENU</div>
    <ul class="sidebar-menu">
    <li class="active"><a href="psto-home.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
      <li><a href="psto-ben-stat.php"><i class="fas fa-user-check"></i><span>Beneficiary Status</span></a></li>
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
<div class="table-container"><table><thead><tr><th>ID No.</th><th>Enterprise</th><th>Date Received</th><th>Status</th><th>Details</th><th>Action</th><th>Scheduled Visit</th></tr></thead><tbody id="tableBody"></tbody></table></div>
</main>
</div></div>

<script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
<script>
const S_URL = 'https://hmxrblblcpbikkxcwwni.supabase.co';
const S_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhteHJibGJsY3BiaWtreGN3d25pIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzIyODY0MDksImV4cCI6MjA4Nzg2MjQwOX0.qC4Lm2KbToc0f1syHpMWJmQqRhQTosNfFzBrfTXSWDw'; 
const sb = supabase.createClient(S_URL, S_KEY);

async function verifyPstoAccess() {
    const { data: { session } } = await sb.auth.getSession();
    if (!session) return window.location.href = 'login-mock.php';

    const userId = session.user.id;
    const { data: p, error } = await sb.from('user_profiles').select('role').eq('id', userId).maybeSingle();

    const userRole = p?.role ? p.role.toLowerCase() : '';

    if (error || !p || userRole !== 'psto') {
        alert("Unauthorized access. Your role is: " + p?.role);
        return window.location.href = (userRole === 'msme') ? 'msme-home.php' : 'login-mock.php';
    }
    
    console.log("Access Granted to PSTO Dashboard");
} 

verifyPstoAccess();

// ---------- beneficiary data ----------
const beneficiaries = [
  {name:'Han Jim Marketing Corporation',date:'2026-02-12', status:'pending', scheduled: ''},
  {name:'4JNG Food Services', date:'2026-02-12', status:'pending', scheduled: ''},
  {name:'RMSS Garments Maker', date:'2026-02-14', status:'pending', scheduled: ''},
  {name:'SJL Corporation', date:'2026-02-16', status:'pending', scheduled: ''},
  {name:'Balay Sang Amo Food Products', date:'2026-02-18', status:'pending', scheduled: ''},
  {name:'Maravilla Enterprises Inc.', date:'2026-02-19', status:'pending', scheduled: ''},
  {name:'JLP Multi Ventures, Inc.', date:'2026-02-21', status:'pending', scheduled: ''},
  {name:'Belverim Foods Corporation', date:'2026-02-22', status:'pending', scheduled: ''},
  {name:'Orchard Valley, Inc.', date:'2026-02-23', status:'pending', scheduled: ''},
  {name:'Think About Cakes Bakery', date:'2026-02-25', status:'pending', scheduled: ''}
];

let pendingApprovalIndex = null;
const tbody = document.getElementById('tableBody');
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
      <td><button class="action-btn view-btn" data-index="${originalIdx}">View</button></td>
      <td><button class="action-btn approve" data-index="${originalIdx}" ${b.status==='approved'?'disabled':''}>For Assessment</button></td>
      <td>${b.scheduled||'—'}</td>`;
    tbody.appendChild(row);
  });
  updateStats();
  document.querySelectorAll('.view-btn').forEach(button => {
    button.addEventListener('click', function() {
        const index = this.getAttribute('data-index');
        window.location.href = `psto-view.php?index=${index}`;
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

// ---------- FIXED CALENDAR ----------
let currentYear, currentMonth, selectedDay;

function initCalendarToToday() {
  const today = new Date();
  currentYear = today.getFullYear();
  currentMonth = today.getMonth();      
  selectedDay = today.getDate();
}

  function updateCal(){
  const monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];
  document.getElementById('monthYearDisplay').textContent = monthNames[currentMonth] + ' ' + currentYear;
  
  const firstDayOfMonth = new Date(currentYear, currentMonth, 1).getDay(); // 0 = Sunday
  // adjust: Monday first (0 = Monday? we want Monday first: if Sunday (0) -> offset 6, else shift by 1)
  let startOffset = (firstDayOfMonth === 0) ? 6 : firstDayOfMonth - 1; // now Monday=0 offset
  
  const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
  
  const grid = document.getElementById('calendarGrid');
  grid.innerHTML = `
    <div class="calendar-day-header">MO</div><div class="calendar-day-header">TU</div><div class="calendar-day-header">WE</div>
    <div class="calendar-day-header">TH</div><div class="calendar-day-header">FR</div><div class="calendar-day-header">SA</div><div class="calendar-day-header">SU</div>
  `;
  
  // empty cells before first day
  for (let i = 0; i < startOffset; i++) {
    const empty = document.createElement('div');
    empty.className = 'calendar-cell empty';
    grid.appendChild(empty);
  }
  
  // actual days
  for (let d = 1; d <= daysInMonth; d++) {
    const cell = document.createElement('div');
    cell.className = 'calendar-cell';
    cell.innerText = d;
    cell.dataset.day = d;
    
    // disable past dates
    if (!isValidSelectedDate(currentYear, currentMonth, d)) {
      cell.classList.add('past-date');
    } else if (d === selectedDay) {
      cell.classList.add('selected');
    }
    
    cell.addEventListener('click', function() {
      if (this.classList.contains('past-date')) return;
      // remove selected from others
      document.querySelectorAll('#calendarGrid .calendar-cell').forEach(c => c.classList.remove('selected'));
      this.classList.add('selected');
      selectedDay = parseInt(this.dataset.day);
      // update input
      const monthStr = String(currentMonth + 1).padStart(2, '0');
      const dayStr = String(selectedDay).padStart(2, '0');
      document.getElementById('selectedDate').value = `${currentYear}-${monthStr}-${dayStr}`;
    });
    
    grid.appendChild(cell);
  }
  
  // ensure selectedDate input reflects selectedDay (if valid)
  if (selectedDay && selectedDay <= daysInMonth && isValidSelectedDate(currentYear, currentMonth, selectedDay)) {
    const monthStr = String(currentMonth + 1).padStart(2, '0');
    const dayStr = String(selectedDay).padStart(2, '0');
    document.getElementById('selectedDate').value = `${currentYear}-${monthStr}-${dayStr}`;
  } else {
    // if selectedDay invalid, set to first valid date
    for (let d = 1; d <= daysInMonth; d++) {
      if (isValidSelectedDate(currentYear, currentMonth, d)) {
        selectedDay = d;
        break;
      }
    }
    const monthStr = String(currentMonth + 1).padStart(2, '0');
    const dayStr = String(selectedDay).padStart(2, '0');
    document.getElementById('selectedDate').value = `${currentYear}-${monthStr}-${dayStr}`;
    // also update selected class
    document.querySelectorAll('#calendarGrid .calendar-cell').forEach(cell => {
      if (parseInt(cell.dataset.day) === selectedDay) cell.classList.add('selected');
    });
  }
}

// month navigation
document.getElementById('prevMonthBtn').addEventListener('click', () => {
  currentMonth--;
  if (currentMonth < 0) { currentMonth = 11; currentYear--; }
  // adjust selectedDay if out of range or invalid
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

// modal 1 -> confirm opens calendar
document.getElementById('modalConfirm').onclick = () => {
  if (pendingApprovalIndex !== null) {
    const b = beneficiaries[pendingApprovalIndex];
    document.getElementById('calendarBusiness').innerHTML = `<strong>${b.name}</strong> — select date & time`;
    // reset calendar to today
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