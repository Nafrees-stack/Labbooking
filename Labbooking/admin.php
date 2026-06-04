<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Panel — CompuLab</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css" />
  <link rel="stylesheet" href="styles.css" />
</head>

<body>

  <div class="app">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="sidebar-logo">
        <div class="logo-mark">
          <div class="logo-icon"><i class="ti ti-device-desktop"></i></div>
          <div>
            <div class="logo-text">CompuLab</div>
            <div class="logo-sub">Booking System</div>
          </div>
        </div>
      </div>
      <nav class="nav">
        <div class="nav-section">Main</div>
        <div class="nav-item active" onclick="nav('dashboard',this)"><i class="ti ti-layout-dashboard"></i>Dashboard
        </div>
        <div class="nav-item" onclick="nav('calendar',this)"><i class="ti ti-calendar"></i>Book a Slot</div>
        <div class="nav-item" onclick="nav('equipment',this)"><i class="ti ti-device-laptop"></i>Equipment</div>
        <div class="nav-item" onclick="nav('waitlist',this)"><i class="ti ti-list-numbers"></i>Waitlist</div>
        <div class="nav-section">Admin</div>
        <div class="nav-item" onclick="nav('admin',this)"><i class="ti ti-shield-check"></i>Approvals <span
            class="nav-badge" id="pending-badge">0</span></div>
        <div class="nav-item" onclick="nav('reports',this)"><i class="ti ti-chart-bar"></i>Reports</div>
        <div class="nav-section">Support</div>
        <div class="nav-item" onclick="nav('ai',this)"><i class="ti ti-robot"></i>AI Assistant</div>
      </nav>
      <div class="sidebar-user">
        <div class="user-row">
          <div class="avatar av-amber" id="sb-avatar">AD</div>
          <div style="min-width:0;flex:1">
            <div class="u-name" id="sb-name">Admin User</div>
            <div class="u-role"><span class="role-tag role-student" id="sb-role-tag">Administrator</span></div>
          </div>
          <a href="index.php" class="btn btn-sm btn-icon" title="Logout"><i class="ti ti-logout"></i></a>
        </div>
      </div>
    </div>

    <!-- Main -->
    <div class="main">
      <div class="topbar">
        <div class="topbar-left">
          <div class="topbar-title" id="page-title">Dashboard</div>
        </div>
        <div class="topbar-right">
          <button class="btn" onclick="openModal('booking-modal')"><i class="ti ti-plus"></i>New Booking</button>
        </div>
      </div>

      <div class="content">

        <!-- Dashboard -->
        <div class="page active" id="page-dashboard">
          <div class="stats-grid" id="dash-stats"></div>
          <div class="flex-between mb-14">
            <div class="sec" style="margin:0">Upcoming bookings</div>
            <button class="btn btn-sm" onclick="openModal('all-approved-modal')">View all <i class="ti ti-arrow-right"></i></button>
          </div>
          <div class="card mb-20">
            <table class="tbl" id="dash-upcoming"></table>
          </div>
          <div class="flex-between mb-14">
            <div class="sec" style="margin:0">Lab activity today</div>
          </div>
          <div class="card card-pad" style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
            <div>
              <div class="text-sm text-muted mb-14">Workstation occupancy</div>
              <div id="ws-bars"></div>
            </div>
            <div>
              <div class="text-sm text-muted mb-14">Peak hours (bookings per slot)</div>
              <div id="peak-bars"></div>
            </div>
          </div>
        </div>

        <!-- Calendar -->
        <div class="page" id="page-calendar">
          <div class="cal-layout">
            <div class="card">
              <div class="cal-header">
                <button class="btn btn-sm btn-icon" onclick="changeMonth(-1)"><i
                    class="ti ti-chevron-left"></i></button>
                <div class="font-600" id="cal-title">May 2026</div>
                <button class="btn btn-sm btn-icon" onclick="changeMonth(1)"><i
                    class="ti ti-chevron-right"></i></button>
              </div>
              <div class="weekdays">
                <div class="wday">Sun</div>
                <div class="wday">Mon</div>
                <div class="wday">Tue</div>
                <div class="wday">Wed</div>
                <div class="wday">Thu</div>
                <div class="wday">Fri</div>
                <div class="wday">Sat</div>
              </div>
              <div class="days-grid" id="cal-days"></div>
            </div>
            <div class="card slots-panel">
              <div class="slots-date-hdr" id="slot-date-hdr">Select a date</div>
              <div class="slot-list" id="slot-list">
                <div class="empty"><i class="ti ti-calendar"></i>Click a date to see time slots</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Equipment -->
        <div class="page" id="page-equipment">
          <div class="flex-between mb-14">
            <div class="sec" style="margin:0">Equipment inventory</div>
            <button class="btn btn-sm" onclick="openModal('eq-request-modal')"><i class="ti ti-plus"></i>Request
              equipment</button>
          </div>
          <div class="eq-grid" id="eq-grid"></div>
        </div>


        <!-- Waitlist -->
        <div class="page" id="page-waitlist">
          <div class="card mb-20">
            <div class="card-header">
              <div class="card-title">Current waitlist</div>
            </div>
            <div id="waitlist-body"></div>
          </div>
        </div>

        <!-- Admin -->
        <div class="page" id="page-admin">
          <div class="tabs">
            <div class="tab active" onclick="switchTab('approvals',this)">Pending Approvals <span
                class="badge badge-pending" id="admin-badge">0</span></div>
            <div class="tab" onclick="switchTab('users',this)">Users</div>
            <div class="tab" onclick="switchTab('settings',this)">Lab Settings</div>
          </div>
          <div class="tab-content active" id="tab-approvals">
            <div id="approval-list"></div>
          </div>
          <div class="tab-content" id="tab-users">
            <div class="card">
              <table class="tbl">
                <thead>
                  <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Total Bookings</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody id="users-body"></tbody>
              </table>
            </div>
          </div>
          <div class="tab-content" id="tab-settings">
            <div class="card card-pad" style="max-width:500px">
              <div class="sec">Lab configuration</div>
              <div class="fg"><label>Lab name</label><input id="cfg-name" value="Computer Science Lab A" /></div>
              <div class="fg"><label>Total workstations</label><input id="cfg-ws" type="number" value="25" /></div>
              <div class="fg"><label>Opening hours</label><input id="cfg-hours" value="08:00 – 20:00" /></div>
              <div class="fg"><label>Max booking duration (hours)</label><input id="cfg-maxh" type="number" value="4" />
              </div>
              <div class="fg"><label>Approval required for</label>
                <select id="cfg-approval">
                  <option>All bookings</option>
                  <option>Bookings over 2 hours</option>
                  <option>Group bookings only</option>
                  <option>No approval required</option>
                </select>
              </div>
              <div class="fg"><label>Allow equipment reservations</label>
                <select>
                  <option>Yes</option>
                  <option>No</option>
                </select>
              </div>
              <button class="btn btn-primary" onclick="saveSettings()"><i class="ti ti-device-floppy"></i>Save
                settings</button>
            </div>
          </div>
        </div>

        <!-- Reports -->
        <div class="page" id="page-reports">
          <div class="stats-grid" id="report-stats"></div>
          <div class="report-grid">
            <div class="card card-pad">
              <div class="card-title mb-14">Bookings by day of week</div>
              <div id="day-chart"></div>
            </div>
            <div class="card card-pad">
              <div class="card-title mb-14">Bookings by time slot</div>
              <div id="slot-chart"></div>
            </div>
            <div class="card card-pad">
              <div class="card-title mb-14">Equipment utilisation</div>
              <div id="eq-chart"></div>
            </div>
            <div class="card card-pad">
              <div class="card-title mb-14">Status breakdown</div>
              <div id="status-chart"></div>
            </div>
          </div>
          <div class="flex-gap" style="justify-content:flex-end">
            <button class="btn" onclick="exportCSV()"><i class="ti ti-download"></i>Export CSV</button>
            <button class="btn btn-primary" onclick="printReport()"><i class="ti ti-printer"></i>Print report</button>
          </div>
        </div>

        <!-- AI -->
        <div class="page" id="page-ai">
          <div class="ai-layout">
            <div class="chat-wrap">
              <div class="chat-body" id="chat-body">
                <div class="msg ai">
                  <div class="msg-sender"><i class="ti ti-robot"></i>Lab AI Assistant</div>
                  <div class="bubble">Hi! I'm your CompuLab booking assistant. I can help you check slot availability,
                    understand booking policies, manage equipment reservations, or answer any questions about the lab.
                    What would you like to do?</div>
                </div>
              </div>
              <div class="chat-foot">
                <input class="chat-in" id="chat-in" placeholder="Ask about availability, policies, equipment..."
                  onkeydown="if(event.key==='Enter')sendChat()" />
                <button class="btn btn-primary btn-sm" onclick="sendChat()"><i class="ti ti-send"></i></button>
              </div>
            </div>
            <div style="display:flex;flex-direction:column;gap:12px;overflow-y:auto">
              <div class="card card-pad">
                <div class="sec">Quick questions</div>
                <button class="quick-q" onclick="askQ('What time slots are available this week?')">What slots are
                  available this week?</button>
                <button class="quick-q" onclick="askQ('How do I cancel my booking?')">How do I cancel a
                  booking?</button>
                <button class="quick-q" onclick="askQ('What equipment can I reserve with my booking?')">What equipment
                  can I reserve?</button>
                <button class="quick-q" onclick="askQ('What is the maximum booking duration?')">What is the max booking
                  duration?</button>
                <button class="quick-q" onclick="askQ('How does the waitlist work?')">How does the waitlist
                  work?</button>
                <button class="quick-q" onclick="askQ('Which workstations have the highest specs?')">Which workstations
                  have best specs?</button>
              </div>
              <div class="card card-pad">
                <div class="sec">Lab status now</div>
                <div class="flex-between text-sm mb-14"><span class="text-muted">Occupancy</span><span class="font-600"
                    id="ai-occ">—</span></div>
                <div class="progress-bar mb-14">
                  <div class="progress-fill" id="ai-occ-bar" style="width:0%"></div>
                </div>
                <div class="text-sm text-muted" id="ai-free-ws">— workstations available</div>
              </div>
              <div class="card card-pad">
                <div class="sec">Email notifications</div>
                <div class="text-sm text-muted" style="margin-bottom:10px">Notifications are sent when your booking
                  status changes.</div>
                <div id="notif-log" style="font-size:12px;display:flex;flex-direction:column;gap:6px;"></div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Booking modal -->
  <div class="overlay" id="booking-modal">
    <div class="modal">
      <div class="modal-title"><i class="ti ti-calendar-plus"></i>New Booking Request</div>
      <div class="fg"><label>Session title</label><input id="b-title" placeholder="e.g. Final year project research" />
      </div>
      <div class="form-row">
        <div class="fg"><label>Date</label><input id="b-date" type="date" /></div>
        <div class="fg"><label>Workstation</label>
          <select id="b-ws">
            <option value="Any">Any available</option>
            <option value="WS-01 (High-spec, 32GB RAM)">WS-01 (High-spec, 32GB)</option>
            <option value="WS-02 (High-spec, 32GB RAM)">WS-02 (High-spec, 32GB)</option>
            <option value="WS-03">WS-03</option>
            <option value="WS-04">WS-04</option>
            <option value="WS-05 (Near printer)">WS-05 (Near printer)</option>
            <option value="WS-08">WS-08</option>
            <option value="WS-12">WS-12</option>
          </select>
        </div>
      </div>
      <div class="form-row">
        <div class="fg"><label>Start time</label>
          <select id="b-start">
            <option>08:00</option>
            <option>09:00</option>
            <option>10:00</option>
            <option>11:00</option>
            <option>12:00</option>
            <option>13:00</option>
            <option>14:00</option>
            <option>15:00</option>
            <option>16:00</option>
            <option>17:00</option>
            <option>18:00</option>
          </select>
        </div>
        <div class="fg"><label>End time</label>
          <select id="b-end">
            <option>10:00</option>
            <option>11:00</option>
            <option>12:00</option>
            <option>13:00</option>
            <option>14:00</option>
            <option>15:00</option>
            <option>16:00</option>
            <option>17:00</option>
            <option>18:00</option>
            <option>19:00</option>
            <option>20:00</option>
          </select>
        </div>
      </div>
      <div class="fg"><label>Additional equipment</label>
        <select id="b-equip">
          <option value="None">None</option>
          <option value="External monitor">External monitor</option>
          <option value="Webcam">Webcam</option>
          <option value="Headphones">Headphones</option>
          <option value="Drawing tablet">Drawing tablet</option>
          <option value="USB hub">USB hub</option>
        </select>
      </div>
      <div class="fg"><label>Purpose / notes</label><textarea id="b-notes"
          placeholder="Briefly describe what you'll be working on..."></textarea></div>
      <div class="modal-actions">
        <button class="btn" onclick="closeModal('booking-modal')">Cancel</button>
        <button class="btn btn-primary" onclick="submitBooking()"><i class="ti ti-send"></i>Submit request</button>
      </div>
    </div>
  </div>

  <!-- Equipment request modal -->
  <div class="overlay" id="eq-request-modal">
    <div class="modal">
      <div class="modal-title"><i class="ti ti-device-laptop"></i>Reserve Equipment</div>
      <div class="fg"><label>Equipment type</label>
        <select id="eq-type">
          <option>External monitor</option>
          <option>Webcam</option>
          <option>Headphones</option>
          <option>Drawing tablet</option>
          <option>USB hub</option>
          <option>Keyboard</option>
        </select>
      </div>
      <div class="form-row">
        <div class="fg"><label>Units</label><input type="number" id="eq-qty" value="1" min="1" max="5" /></div>
        <div class="fg"><label>Date Needed</label><input type="date" id="eq-date" /></div>
      </div>
      <div class="fg"><label>Return Date</label><input type="date" id="eq-return" /></div>
      <div class="modal-actions">
        <button class="btn" onclick="closeModal('eq-request-modal')">Cancel</button>
        <button class="btn btn-primary" onclick="reserveEquipment()"><i class="ti ti-check"></i>Reserve</button>
      </div>
    </div>
  </div>

  <!-- Waitlist modal -->
  <div class="overlay" id="waitlist-modal">
    <div class="modal">
      <div class="modal-title"><i class="ti ti-list-numbers"></i>Join Waitlist</div>
      <div class="fg"><label>Date</label><input type="date" id="wl-date" /></div>
      <div class="form-row">
        <div class="fg"><label>Preferred start time</label>
          <select id="wl-start">
            <option>08:00</option>
            <option>10:00</option>
            <option>12:00</option>
            <option>14:00</option>
            <option>16:00</option>
            <option>18:00</option>
          </select>
        </div>
        <div class="fg"><label>Duration needed</label>
          <select id="wl-dur">
            <option>1 hour</option>
            <option>2 hours</option>
            <option>3 hours</option>
            <option>4 hours</option>
          </select>
        </div>
      </div>
      <div class="fg"><label>Reason</label><input id="wl-reason" placeholder="Brief reason for booking..." /></div>
      <div class="modal-actions">
        <button class="btn" onclick="closeModal('waitlist-modal')">Cancel</button>
        <button class="btn btn-primary" onclick="joinWaitlist()"><i class="ti ti-list-check"></i>Join queue</button>
      </div>
    </div>
  </div>

  <!-- All Approved Bookings Modal -->
  <div class="overlay" id="all-approved-modal">
    <div class="modal" style="width: 800px; max-width: 95vw;">
      <div class="modal-title"><i class="ti ti-calendar-check"></i>All Approved Bookings</div>
      <div class="card" style="max-height: 400px; overflow-y: auto;">
        <table class="tbl">
          <thead>
            <tr>
              <th>User</th>
              <th>Session</th>
              <th>Date</th>
              <th>Time</th>
              <th>Workstation</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="all-approved-body"></tbody>
        </table>
      </div>
      <div class="modal-actions">
        <button class="btn btn-primary" onclick="closeModal('all-approved-modal')">Close</button>
      </div>
    </div>
  </div>

  <!-- Toast container -->
  <div class="toast-wrap" id="toast-wrap"></div>

  <script>
    // ══════════════════════════════════════════
    //  DATA LAYER — localStorage persistence
    // ══════════════════════════════════════════
    const STORE_KEY = 'compulab_data';

    function defaultData() {
      const today = new Date();
      const fmt = d => d.toISOString().split('T')[0];
      const t = fmt(today);
      const t1 = fmt(new Date(today.getTime() + 86400000));
      const t2 = fmt(new Date(today.getTime() + 3 * 86400000));
      const t3 = fmt(new Date(today.getTime() - 2 * 86400000));
      return {
        bookings: [
          { id: 1, user: 'Ashan K.', title: 'Final Year Project', date: t1, start: '10:00', end: '12:00', ws: 'WS-08', equip: 'None', notes: 'Working on capstone project', status: 'approved' },
          { id: 2, user: 'Ashan K.', title: 'Group Study', date: t2, start: '14:00', end: '16:00', ws: 'WS-12', equip: 'External monitor', notes: 'Studying for exams', status: 'pending' },
          { id: 3, user: 'Ashan K.', title: 'Research', date: t3, start: '09:00', end: '11:00', ws: 'WS-03', equip: 'None', notes: '', status: 'approved' },
          { id: 4, user: 'Nimali P.', title: 'ML Lab Session', date: t1, start: '13:00', end: '17:00', ws: 'WS-02 (High-spec, 32GB RAM)', equip: 'Drawing tablet', notes: 'Training deep learning models for final project', status: 'pending' },
          { id: 5, user: 'Roshan S.', title: 'Faculty Research', date: t2, start: '09:00', end: '13:00', ws: 'WS-01 (High-spec, 32GB RAM)', equip: 'External monitor', notes: 'Requires sustained internet connection', status: 'pending' },
          { id: 6, user: 'Kavya F.', title: 'Group Assignment', date: t2, start: '14:00', end: '16:00', ws: 'Any', equip: 'None', notes: '3 students, prefer adjacent workstations', status: 'pending' },
        ],
        waitlist: [
          { id: 1, user: 'Ashan K.', date: t1, start: '08:00', duration: '2 hours', reason: 'Early morning study session', pos: 1 },
          { id: 2, user: 'Priya N.', date: t1, start: '08:00', duration: '2 hours', reason: 'Assignment submission prep', pos: 2 },
        ],
        notifications: [
          { type: 'success', title: 'Booking approved', body: 'Your "Final Year Project" booking on ' + t1 + ' was approved.', time: '2 hours ago' },
          { type: 'info', title: 'Reminder', body: 'Your booking tomorrow at 10:00 starts in 18 hours.', time: '4 hours ago' },
        ],
        equipment: [
          { name: 'External Monitors', icon: 'ti-device-tv', total: 8, inUse: 3 },
          { name: 'Webcams', icon: 'ti-camera', total: 12, inUse: 4 },
          { name: 'Headphones', icon: 'ti-headphones', total: 10, inUse: 3 },
          { name: 'Drawing Tablets', icon: 'ti-stylus', total: 4, inUse: 2 },
          { name: 'USB Hubs', icon: 'ti-usb', total: 15, inUse: 4 },
          { name: 'Keyboards', icon: 'ti-keyboard', total: 6, inUse: 1 },
        ],
        nextId: 7,
        eqReservations: [],
      };
    }

    function loadData() {
      try { return JSON.parse(localStorage.getItem(STORE_KEY)) || defaultData(); }
      catch { return defaultData(); }
    }
    function saveData() { localStorage.setItem(STORE_KEY, JSON.stringify(DB)); }

    let DB = loadData();
    let isAdmin = true;
    let calYear = new Date().getFullYear();
    let calMonth = new Date().getMonth();
    let selectedDate = null;

    // ══════════════════════════════════════════
    //  NAVIGATION
    // ══════════════════════════════════════════
    function nav(page, el) {
      document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
      document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
      document.getElementById('page-' + page).classList.add('active');
      if (el) el.classList.add('active');
      const titles = { dashboard: 'Dashboard', calendar: 'Book a Slot', equipment: 'Equipment', waitlist: 'Waitlist', admin: 'Admin Panel', reports: 'Reports', ai: 'AI Assistant' };
      document.getElementById('page-title').textContent = titles[page] || page;
      const renders = { dashboard: renderDashboard, calendar: renderCalendar, equipment: renderEquipment, waitlist: renderWaitlist, admin: renderAdmin, reports: renderReports, ai: renderAI };
      if (renders[page]) renders[page]();
    }

    // ══════════════════════════════════════════
    //  DASHBOARD
    // ══════════════════════════════════════════
    function renderDashboard() {
      const allB = DB.bookings;
      const displayB = isAdmin ? allB : allB.filter(b => b.user === 'Ashan K.');
      const allUpcoming = displayB.filter(b => b.status === 'approved' && b.date >= todayStr()).sort((a, b) => a.date.localeCompare(b.date));
      const upcoming = allUpcoming.slice(0, 5);
      const totalUpcomingCount = allUpcoming.length;
      const pendingCount = displayB.filter(b => b.status === 'pending').length;

      const totalAdminPending = allB.filter(b => b.status === 'pending').length;
      document.getElementById('pending-badge').textContent = totalAdminPending;
      document.getElementById('admin-badge').textContent = totalAdminPending;

      const statsLabel = isAdmin ? 'Total bookings' : 'My bookings';
      const pendingLabel = isAdmin ? 'Pending total' : 'Pending';

      document.getElementById('dash-stats').innerHTML = `
    <div class="stat-card"><div class="stat-icon" style="color:var(--blue)"><i class="ti ti-calendar-check"></i></div><div class="stat-label">${statsLabel}</div><div class="stat-val">${displayB.length}</div><div class="stat-sub">All time</div></div>
    <div class="stat-card"><div class="stat-icon" style="color:var(--green)"><i class="ti ti-clock"></i></div><div class="stat-label">Upcoming</div><div class="stat-val">${totalUpcomingCount}</div><div class="stat-sub">Approved sessions</div></div>
    <div class="stat-card"><div class="stat-icon" style="color:var(--amber)"><i class="ti ti-hourglass"></i></div><div class="stat-label">${pendingLabel}</div><div class="stat-val">${pendingCount}</div><div class="stat-sub">Awaiting approval</div></div>
    <div class="stat-card"><div class="stat-icon" style="color:#853498"><i class="ti ti-device-desktop"></i></div><div class="stat-label">Lab occupancy</div><div class="stat-val">68%</div><div class="stat-sub">17 / 25 workstations</div></div>
  `;

      const tbody = document.getElementById('dash-upcoming');
      const tableHead = isAdmin
        ? '<thead><tr><th>User</th><th>Session</th><th>Date</th><th>Time</th><th>Workstation</th><th>Status</th><th>Actions</th></tr></thead>'
        : '<thead><tr><th>Session</th><th>Date</th><th>Time</th><th>Workstation</th><th>Status</th></tr></thead>';

      tbody.innerHTML = upcoming.length
        ? tableHead + '<tbody>' +
        upcoming.map(b => `<tr>${isAdmin ? `<td><strong>${b.user}</strong></td>` : ''}<td><strong>${b.title}</strong></td><td>${b.date}</td><td>${b.start} – ${b.end}</td><td>${b.ws}</td><td><span class="badge badge-${b.status}">${cap(b.status)}</span></td>${isAdmin ? `<td><button class="btn btn-sm btn-danger" onclick="cancelB(${b.id})"><i class="ti ti-calendar-cancel"></i> Cancel</button></td>` : ''}</tr>`).join('') + '</tbody>'
        : `<tr><td colspan="${isAdmin ? 7 : 5}"><div class="empty"><i class="ti ti-calendar-off"></i>No upcoming bookings</div></td></tr>`;

      document.getElementById('ws-bars').innerHTML = ['Available (8)', 'In use (17)', 'Maintenance (0)'].map((l, i) => {
        const vals = [32, 68, 0]; const cols = ['var(--green)', 'var(--blue)', 'var(--red)'];
        return `<div class="chart-bar-row"><div class="chart-bar-label">${l}</div><div class="chart-bar-track"><div class="chart-bar-fill" style="width:${vals[i]}%;background:${cols[i]}"></div></div><div class="chart-bar-val">${vals[i]}%</div></div>`;
      }).join('');

      const peaks = [['08–10', 4], ['10–12', 9], ['12–14', 6], ['14–16', 8], ['16–18', 5], ['18–20', 3]];
      const max = Math.max(...peaks.map(p => p[1]));
      document.getElementById('peak-bars').innerHTML = peaks.map(([l, v]) =>
        `<div class="chart-bar-row"><div class="chart-bar-label">${l}</div><div class="chart-bar-track"><div class="chart-bar-fill" style="width:${Math.round(v / max * 100)}%"></div></div><div class="chart-bar-val">${v}</div></div>`
      ).join('');
    }

    // ══════════════════════════════════════════
    //  CALENDAR
    // ══════════════════════════════════════════
    function renderCalendar() {
      const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
      document.getElementById('cal-title').textContent = months[calMonth] + ' ' + calYear;
      const grid = document.getElementById('cal-days');
      grid.innerHTML = '';
      const first = new Date(calYear, calMonth, 1).getDay();
      const dim = new Date(calYear, calMonth + 1, 0).getDate();
      const prev = new Date(calYear, calMonth, 0).getDate();
      const td = new Date(); const isToday = (y, m, d) => d === td.getDate() && m === td.getMonth() && y === td.getFullYear();
      const hasBooking = day => DB.bookings.some(b => b.date === `${calYear}-${String(calMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`);

      for (let i = first - 1; i >= 0; i--) {
        const d = document.createElement('div'); d.className = 'day other-month'; d.textContent = prev - i; grid.appendChild(d);
      }
      for (let i = 1; i <= dim; i++) {
        const ds = `${calYear}-${String(calMonth + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
        const d = document.createElement('div');
        d.className = 'day' + (isToday(calYear, calMonth, i) ? ' today' : '') + (selectedDate === ds ? ' selected' : '');
        d.textContent = i;
        if (hasBooking(i)) { const dot = document.createElement('div'); dot.className = 'dot'; d.appendChild(dot); }
        d.onclick = () => selectDate(ds, d);
        grid.appendChild(d);
      }
    }

    function changeMonth(dir) {
      calMonth += dir;
      if (calMonth < 0) { calMonth = 11; calYear--; } if (calMonth > 11) { calMonth = 0; calYear++; }
      renderCalendar();
    }

    function selectDate(ds, el) {
      document.querySelectorAll('.day.selected').forEach(d => d.classList.remove('selected'));
      el.classList.add('selected');
      selectedDate = ds;
      const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
      const [y, m, d] = ds.split('-');
      document.getElementById('slot-date-hdr').textContent = months[parseInt(m) - 1] + ' ' + parseInt(d) + ', ' + y;
      renderSlots(ds);
    }

    function renderSlots(ds) {
      const slots = [['08:00', '10:00'], ['10:00', '12:00'], ['12:00', '14:00'], ['14:00', '16:00'], ['16:00', '18:00'], ['18:00', '20:00']];
      const myB = DB.bookings.filter(b => b.date === ds && b.user === 'Ashan K.' && b.status !== 'cancelled');
      const allB = DB.bookings.filter(b => b.date === ds && b.status === 'approved');
      document.getElementById('slot-list').innerHTML = slots.map(([s, e]) => {
        const mine = myB.find(b => b.start === s);
        const taken = !mine && allB.filter(b => !(b.end <= s || b.start >= e)).length >= 25;
        const cl = mine ? 'mine' : taken ? 'booked' : 'available';
        const tag = mine ? '<span class="slot-tag tag-mine">My booking</span>' : taken ? '<span class="slot-tag tag-booked">Full</span>' : '<span class="slot-tag tag-avail">Available</span>';
        const btn = mine ? `<button class="btn btn-sm btn-danger" onclick="cancelFromSlot(${mine.id})">Cancel</button>` :
          taken ? `<button class="btn btn-sm" onclick="openWaitlistFor('${ds}','${s}')">Join waitlist</button>` :
            `<button class="btn btn-sm btn-primary" onclick="openBookingFor('${ds}','${s}','${e}')">Book</button>`;
        return `<div class="slot ${cl}"><div><div class="slot-time">${s} – ${e}</div>${mine ? `<div class="text-sm text-muted" style="margin-top:2px">${mine.title}</div>` : ''}</div><div class="flex-gap">${tag}${btn}</div></div>`;
      }).join('');
    }

    function openBookingFor(ds, s, e) {
      document.getElementById('b-date').value = ds;
      document.getElementById('b-start').value = s;
      document.getElementById('b-end').value = e;
      openModal('booking-modal');
    }

    function openWaitlistFor(ds, s) {
      document.getElementById('wl-date').value = ds;
      document.getElementById('wl-start').value = s;
      openModal('waitlist-modal');
    }

    function cancelFromSlot(id) {
      if (!confirm('Cancel this booking?')) return;
      const b = DB.bookings.find(x => x.id === id);
      if (b) { b.status = 'cancelled'; saveData(); sendNotif('info', 'Booking cancelled', `"${b.title}" has been cancelled.`); }
      renderSlots(selectedDate);
    }

    // ══════════════════════════════════════════
    //  EQUIPMENT
    // ══════════════════════════════════════════
    function renderEquipment() {
      document.getElementById('eq-grid').innerHTML = DB.equipment.map((e, i) => {
        const pct = Math.round(e.inUse / e.total * 100);
        const cls = pct > 80 ? 'danger' : pct > 50 ? 'warning' : '';
        return `<div class="eq-card">
      <div class="eq-icon"><i class="ti ${e.icon}"></i></div>
      <div class="eq-name">${e.name}</div>
      <div class="eq-sub">${e.total - e.inUse} of ${e.total} available</div>
      <div class="progress-bar"><div class="progress-fill ${cls}" style="width:${pct}%"></div></div>
      <div class="text-sm text-muted">${pct}% utilised</div>
      <button class="btn btn-sm btn-primary" style="margin-top:6px" onclick="openEqModal(${i})"><i class="ti ti-plus"></i>Reserve</button>
    </div>`;
      }).join('');

      // Admin view: show reservation list
      const resTable = `
        <div class="sec" style="margin-top:20px">Current Equipment Reservations</div>
        <div class="card mt-14" style="margin-top:14px">
          <table class="tbl">
            <thead>
              <tr>
                <th>User</th>
                <th>Equipment</th>
                <th>Units</th>
                <th>From</th>
                <th>To</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="eq-res-body">
              ${(DB.eqReservations || []).length ? DB.eqReservations.map(r => `
                <tr>
                  <td><strong>${r.user}</strong></td>
                  <td>${r.type}</td>
                  <td>${r.qty}</td>
                  <td>${r.start}</td>
                  <td>${r.end}</td>
                  <td><button class="btn btn-sm btn-danger" onclick="cancelEqRes(${r.id})"><i class="ti ti-trash"></i></button></td>
                </tr>
              `).join('') : '<tr><td colspan="6"><div class="empty">No active reservations</div></td></tr>'}
            </tbody>
          </table>
        </div>`;
      
      const container = document.getElementById('page-equipment');
      const existingTable = container.querySelector('.mt-14');
      if (existingTable) existingTable.parentElement.removeChild(existingTable.previousElementSibling);
      if (existingTable) existingTable.remove();
      container.insertAdjacentHTML('beforeend', resTable);
    }

    function cancelEqRes(id) {
      const idx = DB.eqReservations.findIndex(r => r.id === id);
      if (idx !== -1) {
        const r = DB.eqReservations[idx];
        const eq = DB.equipment.find(e => e.name.toLowerCase().includes(r.type.split(' ')[0].toLowerCase()));
        if (eq) eq.inUse -= r.qty;
        DB.eqReservations.splice(idx, 1);
        saveData();
        toast('info', 'Reservation Removed', 'Equipment has been returned to inventory.');
        renderEquipment();
      }
    }

    function reserveEquipment() {
      const type = document.getElementById('eq-type').value;
      const qty = parseInt(document.getElementById('eq-qty').value) || 1;
      const start = document.getElementById('eq-date').value;
      const end = document.getElementById('eq-return').value;

      if (!start || !end) { toast('error', 'Missing dates', 'Please select both needed and return dates.'); return; }
      if (new Date(end) < new Date(start)) { toast('error', 'Invalid dates', 'Return date cannot be before start date.'); return; }

      const eq = DB.equipment.find(e => e.name.toLowerCase().includes(type.split(' ')[0].toLowerCase()));
      if (eq && (eq.total - eq.inUse) >= qty) {
        eq.inUse += qty;
        if (!DB.eqReservations) DB.eqReservations = [];
        DB.eqReservations.push({
          id: Date.now(),
          user: isAdmin ? 'Admin User' : 'Ashan K.',
          type: type,
          qty: qty,
          start: start,
          end: end
        });
        saveData();
        toast('success', 'Reserved!', `${qty} unit(s) of ${type} reserved from ${start} to ${end}.`);
        renderEquipment();
      } else {
        toast('error', 'Unavailable', `Not enough ${type} units available for those dates.`);
      }
      closeModal('eq-request-modal');
    }

    function openEqModal(i) {
      const eq = DB.equipment[i];
      const select = document.getElementById('eq-type');
      if (select) {
        for (let opt of select.options) {
          if (eq.name.toLowerCase().includes(opt.value.toLowerCase().split(' ')[0])) {
            select.value = opt.value;
            break;
          }
        }
      }
      document.getElementById('eq-qty').value = 1;
      document.getElementById('eq-qty').max = eq.total - eq.inUse;
      openModal('eq-request-modal');
    }

    function quickReserveEq(i) {
      const eq = DB.equipment[i];
      if (eq.inUse < eq.total) { eq.inUse++; saveData(); toast('success', 'Reserved!', `${eq.name.slice(0, -1)} reserved.`); renderEquipment(); }
      else toast('error', 'Unavailable', 'All units are currently in use.');
    }


    // ══════════════════════════════════════════
    //  WAITLIST
    // ══════════════════════════════════════════
    function renderWaitlist() {
      const waitlistEntries = DB.waitlist.map(w => ({ ...w, type: 'Waitlist' }));
      const pendingBookings = DB.bookings.filter(b => b.status === 'pending').map(b => ({
        id: b.id,
        user: b.user,
        date: b.date,
        start: b.start,
        duration: b.end ? b.start + ' – ' + b.end : 'Session',
        pos: '—',
        type: 'Pending'
      }));

      const all = [...waitlistEntries, ...pendingBookings];

      document.getElementById('waitlist-body').innerHTML = all.length
        ? all.map(w => `<div class="waitlist-item">
        <div class="flex-gap"><div class="pos-badge">${w.pos}</div>
        <div><div class="font-600" style="font-size:13px">${w.user} <span class="badge badge-${w.type.toLowerCase() === 'pending' ? 'pending' : 'info'} ml-6" style="margin-left:6px; font-size:10px">${w.type}</span></div>
        <div class="text-sm text-muted">${w.date} · ${w.start} ${w.type === 'Waitlist' ? '· ' + w.duration : ''}</div></div></div>
        <div class="flex-gap">${w.user === 'Ashan K.' ? `<span class="badge badge-info">You</span>${w.type === 'Waitlist' ? `<button class="btn btn-sm btn-danger" onclick="leaveWaitlist(${w.id})">Leave</button>` : ''}` : '<span class="badge badge-approved">In Queue</span>'}</div>
      </div>`).join('')
        : `<div class="empty"><i class="ti ti-list"></i>Waitlist is empty</div>`;
    }

    function joinWaitlist() {
      const entry = { id: DB.nextId++, user: 'Ashan K.', date: document.getElementById('wl-date').value, start: document.getElementById('wl-start').value, duration: document.getElementById('wl-dur').value, reason: document.getElementById('wl-reason').value || '—', pos: DB.waitlist.length + 1 };
      DB.waitlist.push(entry); saveData();
      toast('info', 'Added to waitlist', `You're #${entry.pos} in queue for ${entry.date} at ${entry.start}.`);
      closeModal('waitlist-modal'); renderWaitlist();
    }

    function leaveWaitlist(id) {
      DB.waitlist = DB.waitlist.filter(w => w.id !== id);
      DB.waitlist.forEach((w, i) => w.pos = i + 1);
      saveData(); toast('info', 'Removed', 'You left the waitlist.'); renderWaitlist();
    }

    // ══════════════════════════════════════════
    //  ADMIN
    // ══════════════════════════════════════════
    function renderAdmin() {
      const pending = DB.bookings.filter(b => b.status === 'pending');
      
      // Sort by date then by time
      pending.sort((a, b) => {
        const dateDiff = a.date.localeCompare(b.date);
        return dateDiff !== 0 ? dateDiff : a.start.localeCompare(b.start);
      });

      document.getElementById('pending-badge').textContent = pending.length;
      document.getElementById('admin-badge').textContent = pending.length;

      document.getElementById('approval-list').innerHTML = pending.length
        ? pending.map(b => `<div class="pcard" id="pcard-${b.id}">
        <div class="pcard-top">
          <div><div class="font-600">${b.user}</div><div class="text-sm text-muted">${b.title}</div></div>
          <span class="badge badge-pending">Pending</span>
        </div>
        <div class="pcard-meta">
          <span><i class="ti ti-calendar"></i>${b.date}</span>
          <span><i class="ti ti-clock"></i>${b.start} – ${b.end}</span>
          <span><i class="ti ti-device-desktop"></i>${b.ws}</span>
        </div>
        ${b.notes ? `<div class="note-box">"${b.notes}"</div>` : ''}
        <div class="pcard-actions">
          <button class="btn btn-sm btn-success" onclick="approveB(${b.id})"><i class="ti ti-check"></i>Approve</button>
          <button class="btn btn-sm btn-danger" onclick="rejectB(${b.id})"><i class="ti ti-x"></i>Reject</button>
          <button class="btn btn-sm" onclick="toast('info','Message sent',\`Message sent to ${b.user}.\`)"><i class="ti ti-message"></i>Message</button>
        </div>
      </div>`).join('')
        : `<div class="empty"><i class="ti ti-check"></i>No pending approvals</div>`;

      const users = [...new Set(DB.bookings.map(b => b.user))];
      document.getElementById('users-body').innerHTML = users.map(u => {
        const ub = DB.bookings.filter(b => b.user === u);
        const isMe = u === 'Ashan K.';
        return `<tr><td><strong>${u}</strong><div class="text-sm text-muted">${u.toLowerCase().replace(' ', '.')}@uni.edu</div></td>
      <td><span class="badge ${isMe ? 'badge-info' : 'badge-approved'}">${isMe ? 'You' : 'Student'}</span></td>
      <td>${ub.length}</td>
      <td><span class="badge badge-active">Active</span></td>
      <td><button class="btn btn-sm">Manage</button></td></tr>`;
      }).join('');
    }

    function approveB(id) {
      const b = DB.bookings.find(x => x.id === id); if (!b) return;
      b.status = 'approved'; saveData();
      document.getElementById('pcard-' + id)?.remove();
      sendNotif('success', 'Booking approved!', `"${b.title}" on ${b.date} at ${b.start} was approved.`);
      toast('success', 'Approved', `${b.user}'s booking approved.`);
      renderAdmin();
    }

    function rejectB(id) {
      const b = DB.bookings.find(x => x.id === id); if (!b) return;
      b.status = 'rejected'; saveData();
      document.getElementById('pcard-' + id)?.remove();
      sendNotif('error', 'Booking rejected', `"${b.title}" on ${b.date} was not approved.`);
      toast('error', 'Rejected', `${b.user}'s booking rejected.`);
      renderAdmin();
    }

    function cancelB(id) {
      if (!confirm('Are you sure you want to cancel this booking?')) return;
      const b = DB.bookings.find(x => x.id === id); if (!b) return;
      b.status = 'cancelled'; saveData();
      sendNotif('info', 'Booking cancelled by Admin', `"${b.title}" for ${b.user} on ${b.date} at ${b.start} was cancelled by administrator.`);
      toast('info', 'Cancelled', `${b.user}'s booking cancelled.`);
      renderDashboard();
      if (document.getElementById('all-approved-modal').classList.contains('open')) renderAllApprovedBookings();
    }

    function renderAllApprovedBookings() {
      const approved = DB.bookings.filter(b => b.status === 'approved').sort((a,b) => a.date.localeCompare(b.date) || a.start.localeCompare(b.start));
      const tbody = document.getElementById('all-approved-body');
      tbody.innerHTML = approved.length 
        ? approved.map(b => `<tr>
            <td><strong>${b.user}</strong></td>
            <td>${b.title}</td>
            <td>${b.date}</td>
            <td>${b.start} – ${b.end}</td>
            <td>${b.ws}</td>
            <td><button class="btn btn-sm btn-danger" onclick="cancelB(${b.id})"><i class="ti ti-trash"></i></button></td>
          </tr>`).join('')
        : '<tr><td colspan="6"><div class="empty">No approved bookings found</div></td></tr>';
    }

    function switchTab(tab, el) {
      document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
      document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
      el.classList.add('active');
      document.getElementById('tab-' + tab).classList.add('active');
    }

    function saveSettings() { toast('success', 'Settings saved', 'Lab configuration updated.'); }

    // ══════════════════════════════════════════
    //  REPORTS
    // ══════════════════════════════════════════
    function renderReports() {
      const all = DB.bookings;
      const approved = all.filter(b => b.status === 'approved').length;
      const pending = all.filter(b => b.status === 'pending').length;
      const rejected = all.filter(b => b.status === 'rejected').length;
      document.getElementById('report-stats').innerHTML = `
    <div class="stat-card"><div class="stat-label">Total bookings</div><div class="stat-val">${all.length}</div></div>
    <div class="stat-card"><div class="stat-label">Approved</div><div class="stat-val" style="color:var(--green)">${approved}</div></div>
    <div class="stat-card"><div class="stat-label">Pending</div><div class="stat-val" style="color:var(--amber)">${pending}</div></div>
    <div class="stat-card"><div class="stat-label">Avg utilisation</div><div class="stat-val">68%</div></div>
  `;

      const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
      const dayVals = [5, 9, 7, 8, 6, 3, 1];
      const maxD = Math.max(...dayVals);
      document.getElementById('day-chart').innerHTML = days.map((d, i) =>
        `<div class="chart-bar-row"><div class="chart-bar-label">${d}</div><div class="chart-bar-track"><div class="chart-bar-fill" style="width:${Math.round(dayVals[i] / maxD * 100)}%"></div></div><div class="chart-bar-val">${dayVals[i]}</div></div>`
      ).join('');

      const slots2 = [['08–10', 4], ['10–12', 9], ['12–14', 6], ['14–16', 8], ['16–18', 5], ['18–20', 3]];
      const maxS = Math.max(...slots2.map(s => s[1]));
      document.getElementById('slot-chart').innerHTML = slots2.map(([l, v]) =>
        `<div class="chart-bar-row"><div class="chart-bar-label">${l}</div><div class="chart-bar-track"><div class="chart-bar-fill" style="width:${Math.round(v / maxS * 100)}%"></div></div><div class="chart-bar-val">${v}</div></div>`
      ).join('');

      const eqUsage = DB.equipment.map(e => [e.name.split(' ')[0], e.inUse]);
      const maxE = Math.max(...eqUsage.map(e => e[1]));
      document.getElementById('eq-chart').innerHTML = eqUsage.map(([n, v]) =>
        `<div class="chart-bar-row"><div class="chart-bar-label">${n}</div><div class="chart-bar-track"><div class="chart-bar-fill" style="width:${Math.round(v / maxE * 100)}%"></div></div><div class="chart-bar-val">${v}</div></div>`
      ).join('');

      const statuses = [['Approved', approved, 'var(--green)'], ['Pending', pending, 'var(--amber)'], ['Rejected', rejected, 'var(--red)'], ['Cancelled', all.filter(b => b.status === 'cancelled').length, 'var(--text-secondary)']];
      const maxSt = Math.max(...statuses.map(s => s[1]), 1);
      document.getElementById('status-chart').innerHTML = statuses.map(([l, v, c]) =>
        `<div class="chart-bar-row"><div class="chart-bar-label">${l}</div><div class="chart-bar-track"><div class="chart-bar-fill" style="width:${Math.round(v / maxSt * 100)}%;background:${c}"></div></div><div class="chart-bar-val">${v}</div></div>`
      ).join('');
    }

    function exportCSV() {
      const header = 'User,Title,Date,Start,End,Workstation,Equipment,Status,Notes\n';
      const rows = DB.bookings.map(b => `"${b.user}","${b.title}","${b.date}","${b.start}","${b.end}","${b.ws}","${b.equip}","${b.status}","${b.notes}"`).join('\n');
      const blob = new Blob([header + rows], { type: 'text/csv' });
      const a = document.createElement('a'); a.href = URL.createObjectURL(blob); a.download = 'compulab-bookings.csv'; a.click();
      toast('success', 'Exported', 'Bookings exported to CSV.');
    }

    function printReport() { window.print(); }

    // ══════════════════════════════════════════
    //  AI ASSISTANT
    // ══════════════════════════════════════════
    function renderAI() {
      const occ = 68;
      document.getElementById('ai-occ').textContent = occ + '%';
      document.getElementById('ai-occ-bar').style.width = occ + '%';
      document.getElementById('ai-free-ws').textContent = '8 workstations available right now';
      renderNotifLog();
    }

    function renderNotifLog() {
      const el = document.getElementById('notif-log');
      if (!el) return;
      el.innerHTML = DB.notifications.slice(0, 4).map(n => `
    <div style="display:flex;gap:8px;align-items:flex-start;padding:7px 0;border-bottom:0.5px solid var(--border)">
      <i class="ti ti-${n.type === 'success' ? 'check' : 'info-circle'}" style="font-size:14px;color:${n.type === 'success' ? 'var(--green)' : n.type === 'error' ? 'var(--red)' : 'var(--blue)'}"></i>
      <div><div class="font-600" style="font-size:12px">${n.title}</div><div class="text-muted" style="font-size:11px">${n.time}</div></div>
    </div>`).join('');
    }

    const AI_SYSTEM = `You are a helpful assistant for CompuLab, a university computer science lab booking system.
Lab details:
- 25 workstations, open 08:00–20:00 daily
- Max booking: 4 hours per session
- All bookings require admin approval (24-48 hours)
- Cancel up to 2 hours before session
- Equipment available: external monitors (8), webcams (12), headphones (10), drawing tablets (4), USB hubs (15), keyboards (6)
- WS-01 and WS-02 are high-spec (32GB RAM, RTX 3080) — ideal for ML/graphics work
- Waitlist system available for fully booked slots
- No food/drinks in the lab; no gaming during peak hours (10am–4pm)
- Current occupancy: 68% (17/25 workstations)
Be concise, friendly, and helpful. If someone asks to book, tell them to use the "Book a Slot" section.`;

    async function sendChat() {
      const inp = document.getElementById('chat-in');
      const msg = inp.value.trim(); if (!msg) return;
      inp.value = '';
      appendMsg('user', msg);
      const typing = appendTyping();
      try {
        const res = await fetch('https://api.anthropic.com/v1/messages', {
          method: 'POST', headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ model: 'claude-sonnet-4-20250514', max_tokens: 500, system: AI_SYSTEM, messages: [{ role: 'user', content: msg }] })
        });
        const data = await res.json();
        typing.remove();
        appendMsg('ai', data.content?.find(c => c.type === 'text')?.text || "Sorry, I couldn't process that.");
      } catch { typing.remove(); appendMsg('ai', 'Connection issue. Please try again.'); }
    }

    function appendMsg(role, text) {
      const el = document.createElement('div'); el.className = `msg ${role}`;
      el.innerHTML = (role === 'ai' ? `<div class="msg-sender"><i class="ti ti-robot"></i>Lab AI</div>` : '') + `<div class="bubble">${text.replace(/\n/g, '<br>')}</div>`;
      document.getElementById('chat-body').appendChild(el);
      el.scrollIntoView({ behavior: 'smooth' }); return el;
    }

    function appendTyping() {
      const el = document.createElement('div'); el.className = 'msg ai';
      el.innerHTML = `<div class="msg-sender"><i class="ti ti-robot"></i>Lab AI</div><div class="bubble"><div class="typing-dots"><div class="td"></div><div class="td"></div><div class="td"></div></div></div>`;
      document.getElementById('chat-body').appendChild(el); el.scrollIntoView({ behavior: 'smooth' }); return el;
    }

    function askQ(q) { document.getElementById('chat-in').value = q; sendChat(); }

    // ══════════════════════════════════════════
    //  BOOKING SUBMISSION
    // ══════════════════════════════════════════
    function submitBooking() {
      const title = document.getElementById('b-title').value.trim();
      const date = document.getElementById('b-date').value;
      if (!title || !date) { toast('error', 'Missing fields', 'Please fill in title and date.'); return; }
      const b = {
        id: DB.nextId++, user: 'Ashan K.', title, date,
        start: document.getElementById('b-start').value,
        end: document.getElementById('b-end').value,
        ws: document.getElementById('b-ws').value,
        equip: document.getElementById('b-equip').value,
        notes: document.getElementById('b-notes').value,
        status: 'pending'
      };
      DB.bookings.push(b); saveData();
      sendNotif('info', 'Booking submitted', `"${title}" on ${date} at ${b.start} is awaiting admin approval.`);
      toast('success', 'Submitted!', 'Your booking is pending admin approval.');
      closeModal('booking-modal');
      document.getElementById('b-title').value = ''; document.getElementById('b-notes').value = '';
      if (document.getElementById('page-dashboard').classList.contains('active')) renderDashboard();
      renderAdmin();
    }

    // ══════════════════════════════════════════
    //  ROLE SWITCH
    // ══════════════════════════════════════════
    function switchRole() {
      isAdmin = !isAdmin;
      document.getElementById('sb-avatar').textContent = isAdmin ? 'AD' : 'AK';
      document.getElementById('sb-avatar').className = 'avatar ' + (isAdmin ? 'av-amber' : 'av-blue');
      document.getElementById('sb-name').textContent = isAdmin ? 'Admin User' : 'Ashan K.';
      document.getElementById('sb-role-tag').textContent = isAdmin ? 'Admin' : 'Student';
      document.getElementById('sb-role-tag').className = 'role-tag ' + (isAdmin ? 'role-admin' : 'role-student');
      toast('info', 'Role switched', isAdmin ? 'Viewing as Admin — you can now approve bookings.' : 'Viewing as Student.');
    }

    // ══════════════════════════════════════════
    //  MODALS
    // ══════════════════════════════════════════
    function openModal(id) {
      document.getElementById(id).classList.add('open');
      if (id === 'all-approved-modal') renderAllApprovedBookings();
      if (id === 'booking-modal') { const d = new Date(); d.setDate(d.getDate() + 1); document.getElementById('b-date').value = d.toISOString().split('T')[0]; document.getElementById('b-date').min = new Date().toISOString().split('T')[0]; }
      if (id === 'eq-request-modal' || id === 'waitlist-modal') { const d = new Date(); d.setDate(d.getDate() + 1); const key = id === 'eq-request-modal' ? 'eq-date' : 'wl-date'; document.getElementById(key).value = d.toISOString().split('T')[0]; }
    }
    function closeModal(id) { document.getElementById(id).classList.remove('open'); }
    document.querySelectorAll('.overlay').forEach(o => o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); }));

    // ══════════════════════════════════════════
    //  NOTIFICATIONS
    // ══════════════════════════════════════════
    function sendNotif(type, title, body) {
      DB.notifications.unshift({ type, title, body, time: 'Just now' });
      if (DB.notifications.length > 10) DB.notifications.pop();
      saveData();
      renderNotifLog();
    }

    function toast(type, title, body, dur = 4000) {
      const icons = { success: 'ti-check-circle', error: 'ti-alert-circle', info: 'ti-info-circle' };
      const colors = { success: 'var(--green)', error: 'var(--red)', info: 'var(--blue)' };
      const el = document.createElement('div'); el.className = `toast ${type}`;
      el.innerHTML = `<i class="ti ${icons[type]} toast-icon" style="color:${colors[type]}"></i><div class="toast-msg"><div class="toast-title">${title}</div><div class="toast-body">${body}</div></div>`;
      const wrap = document.getElementById('toast-wrap'); wrap.appendChild(el);
      setTimeout(() => { el.style.opacity = '0'; el.style.transition = 'opacity 0.3s'; setTimeout(() => el.remove(), 300) }, dur);
    }

    // ══════════════════════════════════════════
    //  UTILS
    // ══════════════════════════════════════════
    function cap(s) { return s.charAt(0).toUpperCase() + s.slice(1); }
    function todayStr() { return new Date().toISOString().split('T')[0]; }

    // ══════════════════════════════════════════
    //  INIT
    // ══════════════════════════════════════════
    renderDashboard();
    document.getElementById('pending-badge').textContent = DB.bookings.filter(b => b.status === 'pending').length;
  </script>
</body>

</html>