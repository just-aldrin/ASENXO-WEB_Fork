<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Map View | ASENXO</title>
  <!-- FAVICON -->
  <link rel="icon" type="image/png" href="ASENXO-WEB/favicon.png">
  <!-- Google Font & Font Awesome -->
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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
    
    /* Apply animations to elements */
    body {
        animation: bodyFade 0.9s ease-out forwards;
        background-color: var(--bg-body, #0a0a0a);
        transition: background-color 0.3s ease;
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
    
    .map-panel, .layers-panel {
        animation: cardIntro 0.7s cubic-bezier(0.2, 0.9, 0.3, 1) forwards;
        opacity: 0;
    }
    
    .layers-panel { animation-delay: 0.15s; }
    
    /* Ensure smooth theme transition */
    body, .map-panel, .layers-panel, .sidebar, .top-header, 
    button, input, select, .ent-item, .stat-mini-item {
        transition: background-color 0.3s ease, 
                    border-color 0.3s ease,
                    color 0.3s ease,
                    box-shadow 0.3s ease;
    }

    /* Map-specific styles that integrate with the existing CSS */
    .main-content {
      padding: 16px 20px;
      background: var(--bg-light);
      margin-left: 56px;
      width: calc(100% - 56px);
    }
    
    body.dark .main-content {
      background: #0f0f0f;
    }
    
    .map-layers-container {
      display: flex;
      gap: 20px;
      height: calc(100vh - 120px);
      min-height: 500px;
    }
    
    .map-panel {
      flex: 2;
      background: var(--bg-white);
      border: 1px solid var(--border-light);
      border-radius: var(--radius-lg);
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }
    
    .layers-panel {
      flex: 1;
      background: var(--bg-white);
      border: 1px solid var(--border-light);
      border-radius: var(--radius-lg);
      padding: 16px;
      display: flex;
      flex-direction: column;
      gap: 16px;
      overflow-y: auto;
      min-width: 300px;
    }
    
    .map-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 16px;
      border-bottom: 1px solid var(--border-light);
      font-size: 13px;
      font-weight: 500;
      color: var(--text-primary);
    }
    
    #map {
      flex: 1;
      width: 100%;
      background: #c8e0f0;
    }
    
    /* Stats mini cards - matching ben-stat style */
    .stats-mini {
      display: flex;
      align-items: center;
      justify-content: space-around;
      background: var(--bg-light);
      border-radius: var(--radius-lg);
      padding: 12px;
      border: 1px solid var(--border-light);
    }
    
    .stat-mini-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 4px;
    }
    
    .stat-mini-label {
      font-size: 11px;
      font-weight: 500;
      color: var(--text-muted);
      text-transform: uppercase;
    }
    
    .stat-mini-value {
      font-size: 20px;
      font-weight: 700;
      color: var(--text-primary);
    }
    
    .stat-mini-divider {
      width: 1px;
      height: 30px;
      background: var(--border-light);
    }
    
    /* Search enterprise - matches .search from ben-stat */
    .search-enterprise {
      display: flex;
      align-items: center;
      background: var(--bg-light);
      border-radius: var(--radius-full);
      padding: 0 12px;
      border: 1px solid var(--border-light);
    }
    
    .search-enterprise i {
      color: var(--text-muted);
      font-size: 13px;
    }
    
    .search-enterprise input {
      border: none;
      background: transparent;
      padding: 8px 8px;
      width: 100%;
      font-size: 12px;
      outline: none;
      color: var(--text-primary);
    }
    
    .search-enterprise input::placeholder {
      color: var(--text-muted);
    }
    
    /* Enterprise list */
    .enterprise-list {
      display: flex;
      flex-direction: column;
      gap: 4px;
      max-height: 200px;
      overflow-y: auto;
      border: 1px solid var(--border-light);
      border-radius: var(--radius-md);
      padding: 6px;
      background: var(--bg-light);
    }
    
    .ent-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 8px 12px;
      background: var(--bg-white);
      border: 1px solid var(--border-light);
      border-radius: var(--radius-md);
      cursor: pointer;
      font-size: 12px;
      font-weight: 500;
      color: var(--text-secondary);
      transition: all 0.2s ease;
    }
    
    .ent-item:hover {
      background: var(--hover);
      border-color: var(--green);
    }
    
    .ent-item.active {
      background: var(--green-light);
      border-color: var(--green);
      color: var(--green-dark);
    }
    
    .ent-item .small-note {
      font-size: 10px;
      color: var(--text-muted);
      background: var(--bg-light);
      padding: 2px 8px;
      border-radius: var(--radius-full);
    }
    
    .ent-item.active .small-note {
      background: var(--bg-white);
      color: var(--text-secondary);
    }
    
    /* Company brief - matches info card style */
    .company-brief {
      background: var(--bg-light);
      border: 1px solid var(--border-light);
      border-radius: var(--radius-lg);
      padding: 14px;
    }
    
    .brief-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 8px 0;
      border-bottom: 1px solid var(--border-light);
      font-size: 12px;
    }
    
    .brief-row:last-child {
      border-bottom: none;
    }
    
    .brief-row span:first-child {
      color: var(--text-muted);
      font-weight: 500;
    }
    
    .brief-value, .brief-row span:last-child {
      color: var(--text-primary);
      font-weight: 600;
    }
    
    .last-updated span:last-child {
      font-size: 11px;
      color: var(--green);
    }
    
    /* Buffer/Competitor info */
    .buffer-info {
      background: var(--bg-light);
      border: 1px solid var(--border-light);
      border-radius: var(--radius-lg);
      padding: 14px;
    }
    
    .buffer-title {
      font-size: 12px;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    
    .buffer-title i {
      color: var(--green);
    }
    
    .competitor-tags {
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
    }
    
    .competitor-tag {
      background: var(--bg-white);
      border: 1px solid var(--border-light);
      border-radius: var(--radius-full);
      padding: 4px 10px;
      font-size: 11px;
      font-weight: 500;
      color: var(--text-secondary);
      white-space: nowrap;
    }
    
    /* POI list */
    .poi-header {
      font-size: 12px;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    
    .poi-header i {
      color: var(--green);
    }
    
    .poi-scroll {
      display: flex;
      flex-direction: column;
      gap: 8px;
      max-height: 150px;
      overflow-y: auto;
    }
    
    .poi-item {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 6px 10px;
      background: var(--bg-light);
      border-radius: var(--radius-md);
      font-size: 11px;
      color: var(--text-secondary);
      border: 1px solid var(--border-light);
    }
    
    .poi-item i {
      color: var(--green);
      font-size: 12px;
      width: 16px;
    }
    /* Notification styles */
    #notificationContainer {
      position: fixed;
      top: 70px;
      right: 20px;
      z-index: 10000;
    }
    
    .realtime-notification {
      background: var(--bg-white);
      border-left: 3px solid var(--green);
      border-radius: var(--radius-md);
      padding: 12px 16px;
      margin-bottom: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 12px;
      color: var(--text-primary);
      animation: slideInRight 0.3s ease;
      border: 1px solid var(--border-light);
    }
    
    @keyframes slideInRight {
      from { transform: translateX(100%); opacity: 0; }
      to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOut {
      from { transform: translateX(0); opacity: 1; }
      to { transform: translateX(100%); opacity: 0; }
    }
    
    .realtime-notification i {
      color: var(--green);
    }
    
    .realtime-notification.error {
      border-left-color: #e74c3c;
    }
    
    .realtime-notification.error i {
      color: #e74c3c;
    }
    
    .realtime-notification.success {
      border-left-color: #2ecc71;
    }
    
    .realtime-notification.success i {
      color: #2ecc71;
    }
  </style>
</head>
<body> <!-- removed class="dark" -->
<div class="app">
  <!-- header - matching psto-ben-stat.php exactly -->
  <header class="top-header">
    <div class="top-header-left">
      <span class="project-name">ASENXO</span>
      <span class="badge">GEOSPATIAL ANALYSIS</span>
      <div class="search"><i class="fas fa-search"></i><input type="text" id="globalFilter" placeholder="Search..."></div>
      <div class="sort-filter-bar" style="min-width: auto;">
    </span>
      </div>
    </div>
    <div class="top-header-right">
      <button class="btn-export" id="exportBtn"><i class="fas fa-download"></i> Export</button>
      <button class="theme-toggle" id="themeToggle"><i class="fas fa-moon"></i> Dark</button> <!-- changed from Light -->
    </div>
  </header>

  <div class="content-row">
    <!-- left sidebar - matching psto-ben-stat.php exactly with hidden until hover -->
    <aside class="sidebar">
      <div class="sidebar-section">
        <div class="sidebar-header">MENU</div>
        <ul class="sidebar-menu">
          <li><a href="psto-home.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
          <li><a href="psto-ben-stat.php"><i class="fas fa-user-check"></i><span>Beneficiary Status</span></a></li>
          <li class="active"><a href="psto-map-view.php"><i class="fas fa-map-marked-alt"></i><span>Map View</span></a></li>
          <li><a href="psto-tna-tool.php"><i class="fas fa-clipboard-list"></i><span>TNA Tool</span></a></li>
          <li><a href="psto-revisions.php"><i class="fas fa-history"></i><span>Revisions</span></a></li>
          <li><a href="psto-endorsement.php"><i class="fas fa-check-circle"></i><span>Endorsement</span></a></li>
          <li><a href="psto-settings.php"><i class="fas fa-gear"></i><span>Settings</span></a></li>
        </ul>
      </div>
    </aside>

    <main class="main-content">
      <div class="map-layers-container">

        <!-- MAP PANEL -->
        <div class="map-panel">
          <div class="map-header">
            <span><i class="fas fa-map"></i> <span id="currentLayer"></span></span>
            <span style="display:flex; gap:12px;">
              <i class="fas fa-globe" id="switchLayerBtn" style="cursor:pointer;" title="Switch layer"></i>
              <span id="gpsIndicator"><i class="fas fa-satellite-dish"></i></span>
            </span>
          </div>
          <div id="map"></div>
        </div>

        <!-- RIGHT ANALYSIS PANEL -->
        <div class="layers-panel">
          <!-- QUICK STATS -->
          <div class="stats-mini">
            <div class="stat-mini-item">
              <span class="stat-mini-label">Beneficiaries</span>
              <span class="stat-mini-value" id="totalBeneficiaries">10</span>
            </div>
            <div class="stat-mini-divider"></div>
            <div class="stat-mini-item">
              <span class="stat-mini-label">Employees</span>
              <span class="stat-mini-value" id="totalEmployees">251</span>
            </div>
          </div>

          <!-- BENEFICIARY SEARCH -->
          <div class="search-enterprise">
            <i class="fas fa-search"></i>
            <input type="text" id="benefSearch" placeholder="Search beneficiary...">
          </div>

          <!-- BENEFICIARY LIST -->
          <div class="enterprise-list" id="benefList">
            <!-- dynamic -->
          </div>

          <!-- BENEFICIARY DETAILS -->
          <div class="company-brief" id="companyBrief">
            <div class="brief-row">
              <span>Enterprise</span>
              <span class="brief-value" id="briefName">Select a beneficiary</span>
            </div>
            <div class="brief-row">
              <span>Employees</span>
              <span id="briefEmp">-</span>
            </div>
            <div class="brief-row">
              <span>Municipality</span>
              <span id="briefMunicipality">-</span>
            </div>
            <div class="brief-row last-updated">
              <span>Last Updated</span>
              <span id="lastUpdatedTime">just now</span>
            </div>
          </div>

          <!-- PROXIMITY ANALYSIS -->
          <div class="buffer-info">
            <div class="buffer-title">
              <i class="fas fa-draw-polygon"></i>
              Nearby Competitors (7.5km)
            </div>
            <div class="competitor-tags" id="competitorList">
              <span class="competitor-tag">Select enterprise</span>
            </div>
          </div>

          <!-- NEAREST POI -->
          <div>
            <div class="poi-header">
              <span><i class="fas fa-map-pin"></i> Nearest Points of Interest</span>
            </div>
            <div class="poi-scroll" id="poiList">
              <!-- dynamic -->
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- Notification container -->
<div id="notificationContainer"></div>

<script>
  // JavaScript remains exactly the same as in the original working version
  // (keeping all the functionality)
  (function() {
    // ========== DATA STORES ==========
    let beneficiaries = [];
    let poiMaster = [];
    let benefMarkers = [];
    let updateInterval = null;
    let lastUpdateTime = new Date();
    
    // ========== MAP INIT ==========
    const map = L.map('map').setView([10.7, 122.55], 12);
    
    // Layer switching
    const layers = [
      { name: 'OpenStreetMap', url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', attr: '© OpenStreetMap' },
      { name: 'CartoDB Voyager', url: 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', attr: '© CartoDB' },
      { name: 'Satellite', url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', attr: '© ESRI' }
    ];
    
    let layerIndex = 0;
    let tileLayer = L.tileLayer(layers[0].url, {attribution: layers[0].attr}).addTo(map);
    document.getElementById('currentLayer').innerText = layers[0].name;
    
    document.getElementById('switchLayerBtn').addEventListener('click', ()=>{
      layerIndex = (layerIndex+1)%layers.length;
      map.removeLayer(tileLayer);
      tileLayer = L.tileLayer(layers[layerIndex].url, {attribution: layers[layerIndex].attr}).addTo(map);
      document.getElementById('currentLayer').innerText = layers[layerIndex].name;
    });

    // ========== GPS INDICATOR ==========
    const gpsSpan = document.getElementById('gpsIndicator');
    if (navigator.geolocation) {
      navigator.geolocation.watchPosition(
        () => { gpsSpan.innerHTML = '<i class="fas fa-satellite-dish"></i> live'; },
      );
    } else { 
      gpsSpan.innerHTML = '<i class="fas fa-ban"></i> no GPS'; 
    }

    // ========== LOAD DATA ==========
    async function loadData() {
      try {
        // Simulate CSV data load - in production, replace with fetch to your CSV endpoint
        beneficiaries = [
          { 
            id: 'BEN-001',
            name: 'Han Jim Marketing Corporation', 
            location: 'Villa',
            lat: 10.68763192, 
            lng: 122.5135522, 
            employees: 12,
            status: 'active',
            lastUpdated: new Date().toISOString()
          },
          { 
            id: 'BEN-002',
            name: '4JNG Food Services', 
            location: 'Sta. Cruz',
            lat: 10.68874355, 
            lng: 122.5554969, 
            employees: 8,
            status: 'active',
            lastUpdated: new Date().toISOString()
          },
          { 
            id: 'BEN-003',
            name: 'RMSS Garments Maker', 
            location: 'San Jose',
            lat: 10.68757492, 
            lng: 122.44374, 
            employees: 25,
            status: 'active',
            lastUpdated: new Date().toISOString()
          },
          { 
            id: 'BEN-004',
            name: 'SJL Corporation', 
            location: 'City proper',
            lat: 10.6920791, 
            lng: 122.5510329, 
            employees: 15,
            status: 'active',
            lastUpdated: new Date().toISOString()
          },
          { 
            id: 'BEN-005',
            name: 'Balay Sang Amo Food Products', 
            location: 'Oton',
            lat: 10.70677626, 
            lng: 122.5699115, 
            employees: 20,
            status: 'active',
            lastUpdated: new Date().toISOString()
          },
          { 
            id: 'BEN-006',
            name: 'Maravilla Enterprises Inc.', 
            location: 'Jaro',
            lat: 10.73957095, 
            lng: 122.5708666, 
            employees: 45,
            status: 'active',
            lastUpdated: new Date().toISOString()
          },
          { 
            id: 'BEN-007',
            name: 'JLP Multi Ventures, Inc.', 
            location: 'Jaro',
            lat: 10.72864646, 
            lng: 122.5563029, 
            employees: 30,
            status: 'active',
            lastUpdated: new Date().toISOString()
          },
          { 
            id: 'BEN-008',
            name: 'Belverim Foods Corporation', 
            location: 'Mandurriao',
            lat: 10.77907702, 
            lng: 122.5364158, 
            employees: 50,
            status: 'active',
            lastUpdated: new Date().toISOString()
          },
          { 
            id: 'BEN-009',
            name: 'Orchard Valley, Inc.', 
            location: 'Mandurriao',
            lat: 10.77400823, 
            lng: 122.5493236, 
            employees: 40,
            status: 'active',
            lastUpdated: new Date().toISOString()
          },
          { 
            id: 'BEN-010',
            name: 'Think About Cakes Bakery', 
            location: 'Passi',
            lat: 11.25698579, 
            lng: 123.0150381, 
            employees: 6,
            status: 'active',
            lastUpdated: new Date().toISOString()
          }
        ];

        // POI Data
        poiMaster = [
          // Villa area
          { id: 'POI001', type: 'major_road', name: 'Osmeña St. (Villa Arevalo)', lat: 10.68987523, lng: 122.5134389 },
          { id: 'POI002', type: 'public_market', name: 'Arevalo Public Market', lat: 10.68715531, lng: 122.5162441 },
          { id: 'POI003', type: 'supermarket', name: 'Arevalo Supermarket', lat: 10.68692241, lng: 122.5160331 },
          { id: 'POI004', type: 'public_plaza', name: 'Plaza Villa', lat: 10.68812231, lng: 122.5161445 },
          { id: 'POI005', type: 'school', name: 'Villa Elementary', lat: 10.68963511, lng: 122.51702278 },
          { id: 'POI006', type: 'malls', name: 'Villa Mall', lat: 10.68692241, lng: 122.5160331 },
          { id: 'POI007', type: 'hospital', name: 'Villa District Hospital', lat: 10.68884451, lng: 122.5222339 },
          
          // City Proper area
          { id: 'POI008', type: 'major_road', name: 'M.H. del Pilar St', lat: 10.68821102, lng: 122.5551339 },
          { id: 'POI009', type: 'public_market', name: 'Iloilo Central Market', lat: 10.69345521, lng: 122.5739884 },
          { id: 'POI010', type: 'supermarket', name: 'SM City Iloilo', lat: 10.69383321, lng: 122.5718442 },
          { id: 'POI011', type: 'public_plaza', name: 'Plaza Libertad', lat: 10.69251142, lng: 122.5711229 },
          { id: 'POI012', type: 'school', name: 'University of Iloilo', lat: 10.68953321, lng: 122.5562441 },
          { id: 'POI013', type: 'malls', name: 'SM City Iloilo', lat: 10.69442211, lng: 122.5721338 },
          { id: 'POI014', type: 'hospital', name: 'Iloilo Doctors', lat: 10.69553312, lng: 122.5732442 },
          
          // Jaro area
          { id: 'POI015', type: 'major_road', name: 'Jaro Road', lat: 10.69128831, lng: 122.4475221 },
          { id: 'POI016', type: 'public_market', name: 'Jaro Market', lat: 10.69354412, lng: 122.4485331 },
          { id: 'POI017', type: 'supermarket', name: 'Jaro Supermarket', lat: 10.69482211, lng: 122.4482335 },
          { id: 'POI018', type: 'hospital', name: 'Jaro District Hospital', lat: 10.68752241, lng: 122.4835441 },
          
          // Additional POI
          { id: 'POI019', type: 'public_plaza', name: 'Plaza Libertad', lat: 10.6925, lng: 122.5711 },
          { id: 'POI020', type: 'school', name: 'University of Iloilo', lat: 10.6895, lng: 122.5562 }
        ];

        // Update UI
        updateBeneficiaryList();
        renderMapMarkers();
        updateTotalStats();
        
        // Select first beneficiary by default
        if (beneficiaries.length > 0) {
          setTimeout(() => {
            const firstItem = document.querySelector('.ent-item');
            if (firstItem) {
              firstItem.classList.add('active');
              updatePanelByBeneficiary(0);
            }
          }, 100);
        }
        
        // Setup real-time updates
        setupRealtimeUpdates();
        
      } catch (error) {
        console.error('Error loading data:', error);
        showNotification('Error loading beneficiary data', 'error');
      }
    }

    // ========== SETUP REAL-TIME UPDATES ==========
    function setupRealtimeUpdates() {
      // Clear existing interval
      if (updateInterval) clearInterval(updateInterval);
      
      // Update every 30 seconds
      updateInterval = setInterval(() => {
        simulateRealtimeUpdate();
      }, 30000);
    }

    function simulateRealtimeUpdate() {
      // Randomly update a beneficiary to simulate real-time changes
      if (beneficiaries.length > 0) {
        const randomIndex = Math.floor(Math.random() * beneficiaries.length);
        
        // Only update employees
        const empChange = Math.floor(Math.random() * 3) - 1; // -1, 0, or 1
        
        if (empChange !== 0) {
          beneficiaries[randomIndex].employees = Math.max(1, beneficiaries[randomIndex].employees + empChange);
          beneficiaries[randomIndex].lastUpdated = new Date().toISOString();
          
          // Show notification
          showNotification(
            `${beneficiaries[randomIndex].name}: employees ${empChange > 0 ? '+' : ''}${empChange}`,
            'info'
          );
          
          // Update UI if this beneficiary is selected
          const activeItem = document.querySelector('.ent-item.active');
          if (activeItem) {
            const index = Array.from(document.querySelectorAll('.ent-item')).indexOf(activeItem);
            if (index === randomIndex) {
              updatePanelByBeneficiary(index);
            }
          }
          
          // Update marker popup
          if (benefMarkers[randomIndex]) {
            benefMarkers[randomIndex].setPopupContent(`
              <b>${beneficiaries[randomIndex].name}</b><br>
              Employees: ${beneficiaries[randomIndex].employees}<br>
              <small>Updated: ${new Date().toLocaleTimeString()}</small>
            `);
          }
          
          // Update total stats
          updateTotalStats();
          lastUpdateTime = new Date();
        }
      }
    }

    function showNotification(message, type = 'info') {
      const container = document.getElementById('notificationContainer');
      const notification = document.createElement('div');
      notification.className = `realtime-notification ${type}`;
      
      let icon = 'sync-alt';
      if (type === 'error') icon = 'exclamation-circle';
      if (type === 'success') icon = 'check-circle';
      
      notification.innerHTML = `
        <i class="fas fa-${icon} fa-${type === 'info' ? 'spin' : ''}"></i>
        <span>${message}</span>
      `;
      
      container.appendChild(notification);
      
      setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
      }, 3000);
    }

    // ========== UPDATE BENEFICIARY LIST ==========
    function updateBeneficiaryList() {
      const listContainer = document.getElementById('benefList');
      listContainer.innerHTML = '';
      
      beneficiaries.forEach((b, index) => {
        const item = document.createElement('div');
        item.className = 'ent-item';
        item.dataset.id = `benef${index}`;
        item.dataset.benefId = b.id;
        item.innerHTML = `
          <span>${b.name}</span>
          <span class="small-note">${b.location}</span>
        `;
        item.addEventListener('click', function() {
          document.querySelectorAll('.ent-item').forEach(i => i.classList.remove('active'));
          this.classList.add('active');
          updatePanelByBeneficiary(index);
        });
        listContainer.appendChild(item);
      });
    }

    // ========== RENDER MAP MARKERS ==========
    function renderMapMarkers() {
      // Clear existing markers
      benefMarkers.forEach(marker => map.removeLayer(marker));
      benefMarkers = [];
      
      // Add new markers
      beneficiaries.forEach((b, idx) => {
        const marker = L.circleMarker([b.lat, b.lng], {
          radius: 8,
          fillColor: '#2ecc71',
          color: '#fff',
          weight: 1.5,
          fillOpacity: 0.9
        }).addTo(map).bindPopup(`
          <b>${b.name}</b><br>
          Employees: ${b.employees}<br>
          <small>Last updated: ${new Date(b.lastUpdated).toLocaleTimeString()}</small>
        `);
        benefMarkers.push(marker);
      });
    }

    // ========== UPDATE TOTAL STATISTICS ==========
    function updateTotalStats() {
      const totalBeneficiaries = beneficiaries.length;
      const totalEmployees = beneficiaries.reduce((sum, b) => sum + b.employees, 0);
      
      document.getElementById('totalBeneficiaries').textContent = totalBeneficiaries;
      document.getElementById('totalEmployees').textContent = totalEmployees;
    }

    // ========== DISTANCE CALCULATION ==========
    function distance(lat1, lng1, lat2, lng2) {
      const R = 6371;
      const dLat = (lat2-lat1)*Math.PI/180;
      const dLng = (lng2-lng1)*Math.PI/180;
      const a = Math.sin(dLat/2)**2 + Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*Math.sin(dLng/2)**2;
      return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    }

    // ========== UPDATE PANEL BY BENEFICIARY ==========
    function updatePanelByBeneficiary(index) {
      const b = beneficiaries[index];
      
      // Update company brief
      document.getElementById('briefName').innerText = b.name;
      document.getElementById('briefEmp').innerText = b.employees;
      document.getElementById('briefMunicipality').innerText = b.location;
      document.getElementById('lastUpdatedTime').innerHTML = `<small>${new Date(b.lastUpdated).toLocaleTimeString()}</small>`;

      // Find competitors within 7.5km
      const competitors = beneficiaries
        .filter((other, i) => i !== index)
        .map(other => ({
          name: other.name.split(' ').slice(0, 2).join(' '),
          dist: distance(b.lat, b.lng, other.lat, other.lng),
          employees: other.employees
        }))
        .filter(c => c.dist <= 7.5)
        .sort((a, b) => a.dist - b.dist);

      const compContainer = document.getElementById('competitorList');
      if (competitors.length) {
        compContainer.innerHTML = competitors.map(c => 
          `<span class="competitor-tag" title="${c.employees} employees">${c.name} (${c.dist.toFixed(2)}km)</span>`
        ).join(' ');
      } else {
        compContainer.innerHTML = '<span class="competitor-tag">No competitors within 7.5km</span>';
      }

      // Find nearest POI
      const poiWithDist = poiMaster
        .map(p => ({
          ...p,
          distKm: distance(b.lat, b.lng, p.lat, p.lng)
        }))
        .sort((a, b) => a.distKm - b.distKm)
        .slice(0, 5);

      const poiListDiv = document.getElementById('poiList');
      poiListDiv.innerHTML = poiWithDist.map(p => {
        let icon = 'map-pin';
        if (p.type.includes('road')) icon = 'road';
        else if (p.type.includes('market')) icon = 'store';
        else if (p.type.includes('super')) icon = 'shopping-cart';
        else if (p.type.includes('plaza')) icon = 'tree';
        else if (p.type.includes('school')) icon = 'school';
        else if (p.type.includes('hospital')) icon = 'hospital';
        else if (p.type.includes('mall')) icon = 'building';
        
        return `<div class="poi-item">
          <i class="fas fa-${icon}"></i> 
          ${p.name} (${p.distKm.toFixed(2)} km)
        </div>`;
      }).join('');

      // Update map view and highlight marker
      map.setView([b.lat, b.lng], 14);
      
      benefMarkers.forEach((marker, i) => {
        if (i === index) {
          marker.setStyle({ radius: 10, fillColor: '#f39c12' });
        } else {
          marker.setStyle({ radius: 8, fillColor: '#2ecc71' });
        }
      });
    }

    // ========== SEARCH FILTER ==========
    document.getElementById('benefSearch').addEventListener('input', function(e) {
      const val = e.target.value.toLowerCase();
      document.querySelectorAll('.ent-item').forEach(item => {
        const name = item.innerText.toLowerCase();
        item.style.display = name.includes(val) ? 'flex' : 'none';
      });
    });

    // ========== GLOBAL FILTER ==========
    document.getElementById('globalFilter').addEventListener('input', function(e) {
      const val = e.target.value.toLowerCase();
      document.querySelectorAll('.ent-item').forEach(item => {
        const name = item.innerText.toLowerCase();
        item.style.display = name.includes(val) ? 'flex' : 'none';
      });
    });

    // ========== THEME TOGGLE ==========
    document.getElementById('themeToggle').onclick = function() {
      document.body.classList.toggle('dark');
      const buttonText = document.querySelector('#themeToggle');
      if (document.body.classList.contains('dark')) {
        buttonText.innerHTML = '<i class="fas fa-sun"></i> Light';
      } else {
        buttonText.innerHTML = '<i class="fas fa-moon"></i> Dark';
      }
    };

    // ========== EXPORT FUNCTION ==========
    document.getElementById('exportBtn').addEventListener('click', () => {
      const data = {
        beneficiaries: beneficiaries.map(b => ({
          name: b.name,
          location: b.location,
          employees: b.employees,
          lastUpdated: b.lastUpdated
        })),
        summary: {
          totalBeneficiaries: beneficiaries.length,
          totalEmployees: beneficiaries.reduce((sum, b) => sum + b.employees, 0)
        },
        exportDate: new Date().toISOString()
      };
      
      const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = `beneficiary-export-${new Date().toISOString().split('T')[0]}.json`;
      a.click();
      URL.revokeObjectURL(url);
      
      showNotification('Export completed successfully', 'success');
    });

    // ========== INITIALIZE ==========
    loadData();

    // Handle resize
    window.addEventListener('resize', () => map.invalidateSize());
  })();
</script>
</body>
</html>