<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ASENXO | MSME Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    :root { --accent: #2ecc71; --bg: #0d0d0d; --card: #1a1a1a; }
    body { background: var(--bg); color: white; font-family: 'Inter', sans-serif; margin: 0; padding: 20px; font-size: 12px; }
    
    /* Ultra-Compact Stepper */
    .step-list { list-style: none; padding: 0; max-width: 600px; margin: 0 auto; }
    .step-item { background: var(--card); border: 1px solid #333; border-radius: 4px; margin-bottom: 3px; overflow: hidden; }
    .step-header { padding: 4px 10px; display: flex; align-items: center; gap: 8px; cursor: pointer; }
    .step-icon { width: 16px; height: 16px; border-radius: 50%; background: #262626; display: flex; align-items: center; justify-content: center; font-size: 8px; color: #555; }
    .step-item.active { border-color: var(--accent); }
    .step-item.active .step-icon { background: var(--accent); color: white; }
    
    .step-title { font-weight: 700; font-size: 10px; }
    .step-desc { font-size: 8px; color: #666; display: block; }
    
    .step-content { display: none; padding: 8px 10px 10px 34px; border-top: 1px solid #262626; }
    .step-item.active .step-content { display: block; }

    /* High Density Form */
    .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4px; }
    label { font-size: 7px; color: #888; text-transform: uppercase; font-weight: 800; display: block; }
    input { width: 100%; padding: 3px 6px; background: #000; border: 1px solid #333; color: white; border-radius: 2px; font-size: 10px; box-sizing: border-box; }
    input:disabled { opacity: 0.4; }
    
    .btn { background: var(--accent); color: white; border: none; padding: 5px; border-radius: 3px; width: 100%; font-weight: 700; font-size: 10px; cursor: pointer; margin-top: 5px; display: flex; align-items: center; justify-content: center; gap: 5px; }
    .fa-spin { display: none; }
  </style>
</head>
<body>

<div class="step-list" id="stepper">
  <div style="text-align: center; color: #444;">Initializing Supabase Connection...</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
<script>
  // 1. HARDCODED CONFIG (Double check these in Supabase Settings > API)
  const SB_URL = 'https://hmxrblblcpbikkxcwwni.supabase.co';
  const SB_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhteHJibGJsY3BiaWtreGN3d25pIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzIyODY0MDksImV4cCI6MjA4Nzg2MjQwOX0.qC4Lm2KbToc0f1syHpMWJmQqRhQTosNfFzBrfTXSWDw'; // <--- PASTE AGAIN, CAREFULLY

  let supabase;
  let user = null;
  let profile = null;
  let step = 3; // Starting at step 3 for testing

  async function start() {
    try {
      // FORCE HEADERS: This is the "Nuclear" part to fix the apikey error
      supabase = supabase.createClient(SB_URL, SB_KEY, {
        global: {
          headers: {
            'apikey': SB_KEY,
            'Authorization': `Bearer ${SB_KEY}`
          }
        }
      });

      // Test Connection
      const { data, error } = await supabase.auth.getUser();
      if (error) throw error;
      user = data.user;

      // Get Profile
      const { data: p } = await supabase.from('user_profiles').select('*').eq('id', user.id).single();
      profile = p;
      step = p.current_step || 3;

      render();
    } catch (err) {
      document.getElementById('stepper').innerHTML = `
        <div style="background:#441111; padding:10px; border-radius:4px; border:1px solid red;">
          <strong style="color:red">CONNECTION ERROR:</strong><br>
          <code style="font-size:10px">${err.message}</code><br><br>
          <small>Tip: Check CORS settings and ensure the API key has no spaces.</small>
        </div>`;
    }
  }

  function render() {
    const steps = [
      { id: 1, t: "Account", d: "Entity choice" },
      { id: 2, t: "Verification", d: "Email checked" },
      { id: 3, t: "Owner Profile", d: "Personal details" }
    ];

    document.getElementById('stepper').innerHTML = steps.map(s => `
      <div class="step-item ${s.id === step ? 'active' : ''}">
        <div class="step-header">
          <div class="step-icon">${s.id < step ? '✓' : s.id}</div>
          <div>
            <div class="step-title">${s.t}</div>
            <span class="step-desc">${s.d}</span>
          </div>
        </div>
        <div class="step-content">${getForm(s.id)}</div>
      </div>
    `).join('');
  }

  function getForm(id) {
    if (id === 3 && profile) {
      return `
        <div class="grid">
          <div><label>Name</label><input id="f_name" value="${profile.first_name} ${profile.last_name}" disabled></div>
          <div><label>Nickname</label><input id="f_nick"></div>
          <div><label>Phone</label><input id="f_phone"></div>
          <div><label>Address</label><input id="f_addr"></div>
        </div>
        <button class="btn" id="saveBtn" onclick="saveData()">
          <i class="fas fa-circle-notch fa-spin" id="loader"></i>
          <span>Save Profile</span>
        </button>
      `;
    }
    return `<p style="color:#444">Step completed or locked.</p>`;
  }

  async function saveData() {
    const b = document.getElementById('saveBtn');
    const l = document.getElementById('loader');
    b.disabled = true; l.style.display = 'inline-block';

    const payload = {
      owner_ID: user.id,
      owner_name: document.getElementById('f_name').value,
      owner_nickname: document.getElementById('f_nick').value,
      owner_contactnum: document.getElementById('f_phone').value,
      owner_address: document.getElementById('f_addr').value
    };

    const { error } = await supabase.from('owner_profile').upsert(payload);

    if (!error) {
      step++;
      render();
    } else {
      alert("Error saving: " + error.message);
      b.disabled = false; l.style.display = 'none';
    }
  }

  window.onload = start;
</script>

</body>
</html>