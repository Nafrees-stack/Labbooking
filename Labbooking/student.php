<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Dashboard — CompuLab</title>
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
        <div class="nav-item" onclick="nav('mybookings',this)"><i class="ti ti-clipboard-list"></i>My Bookings</div>
        <div class="nav-item" onclick="nav('waitlist',this)"><i class="ti ti-list-numbers"></i>Waitlist</div>
        <div class="nav-section">Support</div>
        <div class="nav-item" onclick="nav('ai',this)"><i class="ti ti-robot"></i>AI Assistant</div>
      </nav>
      <div class="sidebar-user">
        <div class="user-row">
          <div class="avatar av-blue" id="sb-avatar">AK</div>
          <div style="min-width:0;flex:1">
            <div class="u-name" id="sb-name">Ashan K.</div>
            <div class="u-role"><span class="role-tag role-student" id="sb-role-tag">Student</span></div>
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
        <!-- Pages -->
        <div class="page active" id="page-dashboard">
          <div class="stats-grid" id="dash-stats"></div>
          <div class="flex-between mb-14">
            <div class="sec" style="margin:0">Upcoming bookings</div>
            <button class="btn btn-sm" onclick="nav('mybookings',document.querySelector('[onclick*=mybookings]'))">View
              all <i class="ti ti-arrow-right"></i></button>
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
              <div class="text-sm text-muted mb-14">Peak hours</div>
              <div id="peak-bars"></div>
            </div>
          </div>
        </div>

        <div class="page" id="page-calendar">
          <div class="cal-layout">
            <div class="card">
              <div class="cal-header">
                <button class="btn btn-sm btn-icon" onclick="changeMonth(-1)"><i
                    class="ti ti-chevron-left"></i></button>
                <div class="font-600" id="cal-title"></div>
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

        <div class="page" id="page-equipment">
          <div class="flex-between mb-14">
            <div class="sec" style="margin:0">Equipment inventory</div>
            <button class="btn btn-sm" onclick="openModal('eq-request-modal')"><i class="ti ti-plus"></i>Request
              equipment</button>
          </div>
          <div class="eq-grid" id="eq-grid"></div>
        </div>

        <div class="page" id="page-mybookings">
          <div class="flex-gap mb-14">
            <button class="btn btn-sm" onclick="filterMyBookings('all',this)"
              style="background:var(--bg-secondary)">All</button>
            <button class="btn btn-sm" onclick="filterMyBookings('pending',this)">Pending</button>
            <button class="btn btn-sm" onclick="filterMyBookings('approved',this)">Approved</button>
            <button class="btn btn-sm" onclick="filterMyBookings('rejected',this)">Rejected</button>
          </div>
          <div class="card">
            <table class="tbl" id="my-bookings-tbl">
              <thead>
                <tr>
                  <th>Session</th>
                  <th>Date</th>
                  <th>Time</th>
                  <th>Workstation</th>
                  <th>Equipment</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody id="my-bookings-body"></tbody>
            </table>
          </div>
        </div>

        <div class="page" id="page-waitlist">
          <div class="card mb-20">
            <div class="card-header">
              <div class="card-title">Current waitlist</div>
            </div>
            <div id="waitlist-body"></div>
          </div>
          <div class="card card-pad">
            <div class="sec">How it works</div>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;">
              <div style="font-size:13px">
                <div style="font-size:22px;margin-bottom:6px">1️⃣</div>
                <div class="font-600 mb-14">Join the queue</div>
                <div class="text-muted">Add yourself to the waitlist for a time slot that's fully booked.</div>
              </div>
              <div style="font-size:13px">
                <div style="font-size:22px;margin-bottom:6px">2️⃣</div>
                <div class="font-600 mb-14">Get notified</div>
                <div class="text-muted">When a slot opens up, you'll receive an email notification automatically.</div>
              </div>
              <div style="font-size:13px">
                <div style="font-size:22px;margin-bottom:6px">3️⃣</div>
                <div class="font-600 mb-14">Confirm booking</div>
                <div class="text-muted">You have 30 minutes to confirm before the slot moves to the next person.</div>
              </div>
            </div>
          </div>
        </div>

        <div class="page" id="page-ai">
          <div class="ai-layout">
            <div class="chat-wrap">
              <div class="chat-body" id="chat-body"></div>
              <div class="chat-foot">
                <input class="chat-in" id="chat-in" placeholder="Ask about availability, policies..."
                  onkeydown="if(event.key==='Enter')sendChat()" />
                <button class="btn btn-primary btn-sm" onclick="sendChat()"><i class="ti ti-send"></i></button>
              </div>
            </div>
            <div style="display:flex;flex-direction:column;gap:12px;overflow-y:auto">
              <div class="card card-pad">
                <div class="sec">Lab status now</div>
                <div id="ai-stats-content"></div>
              </div>
              <div class="card card-pad">
                <div class="sec">Recent Notifications</div>
                <div id="notif-log" style="font-size:12px;display:flex;flex-direction:column;gap:6px;"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modals & Toasts -->
  <div class="overlay" id="booking-modal">
    <div class="modal">
      <div class="modal-title"><i class="ti ti-calendar-plus"></i>New Booking Request</div>
      <div class="fg"><label>Session title</label><input id="b-title" placeholder="e.g. Project research" /></div>
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
        <div class="fg"><label>Start</label><select id="b-start">
            <option>08:00</option>
            <option>10:00</option>
            <option>12:00</option>
            <option>14:00</option>
          </select></div>
        <div class="fg"><label>End</label><select id="b-end">
            <option>10:00</option>
            <option>12:00</option>
            <option>14:00</option>
            <option>16:00</option>
          </select></div>
      </div>
      <div class="fg"><label>Equipment</label>
        <select id="b-equip">
          <option value="None">None</option>
          <option value="External monitor">External monitor</option>
          <option value="Webcam">Webcam</option>
          <option value="Headphones">Headphones</option>
          <option value="Drawing tablet">Drawing tablet</option>
          <option value="USB hub">USB hub</option>
        </select>
      </div>
      <div class="fg"><label>Notes</label><textarea id="b-notes"></textarea></div>
      <div class="modal-actions">
        <button class="btn" onclick="closeModal('booking-modal')">Cancel</button>
        <button class="btn btn-primary" onclick="submitBooking()">Submit</button>
      </div>
    </div>
  </div>

  <div class="overlay" id="eq-request-modal">
    <div class="modal">
      <div class="modal-title">Reserve Equipment</div>
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
        <button class="btn btn-primary" onclick="reserveEquipment()">Reserve</button>
      </div>
    </div>
  </div>

  <div class="overlay" id="waitlist-modal">
    <div class="modal">
      <div class="modal-title">Join Waitlist</div>
      <div class="fg"><label>Date</label><input type="date" id="wl-date" /></div>
      <div class="modal-actions">
        <button class="btn" onclick="closeModal('waitlist-modal')">Cancel</button>
        <button class="btn btn-primary" onclick="joinWaitlist()">Join</button>
      </div>
    </div>
  </div>

  <div class="toast-wrap" id="toast-wrap"></div>

  <script src="core.js"></script>
  <script>
    let calYear = new Date().getFullYear();
    let calMonth = new Date().getMonth();
    let selectedDate = null;

    function nav(page, el) {
      document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
      document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
      document.getElementById('page-' + page).classList.add('active');
      if (el) el.classList.add('active');
      const titles = { dashboard: 'Dashboard', calendar: 'Book a Slot', equipment: 'Equipment', mybookings: 'My Bookings', waitlist: 'Waitlist', ai: 'AI Assistant' };
      document.getElementById('page-title').textContent = titles[page] || page;
      const renders = { dashboard: renderDashboard, calendar: renderCalendar, equipment: renderEquipment, mybookings: () => renderMyBookings('all'), waitlist: renderWaitlist, ai: renderAI };
      if (renders[page]) renders[page]();
    }

    function renderDashboard() {
      const myB = DB.bookings.filter(b => b.user === 'Ashan K.');
      const upcoming = myB.filter(b => b.status === 'approved' && b.date >= todayStr()).slice(0, 3);
      const pending = myB.filter(b => b.status === 'pending').length;

      document.getElementById('dash-stats').innerHTML = `
    <div class="stat-card"><div class="stat-icon" style="color:var(--blue)"><i class="ti ti-calendar-check"></i></div><div class="stat-label">My bookings</div><div class="stat-val">${myB.length}</div></div>
    <div class="stat-card"><div class="stat-icon" style="color:var(--green)"><i class="ti ti-clock"></i></div><div class="stat-label">Upcoming</div><div class="stat-val">${upcoming.length}</div></div>
    <div class="stat-card"><div class="stat-icon" style="color:var(--amber)"><i class="ti ti-hourglass"></i></div><div class="stat-label">Pending</div><div class="stat-val">${pending}</div></div>
    <div class="stat-card"><div class="stat-icon" style="color:#853498"><i class="ti ti-device-desktop"></i></div><div class="stat-label">Lab occupancy</div><div class="stat-val">68%</div></div>
  `;

      document.getElementById('dash-upcoming').innerHTML = upcoming.length
        ? '<thead><tr><th>Session</th><th>Date</th><th>Time</th><th>Status</th></tr></thead><tbody>' +
        upcoming.map(b => `<tr><td><strong>${b.title}</strong></td><td>${b.date}</td><td>${b.start} – ${b.end}</td><td><span class="badge badge-${b.status}">${cap(b.status)}</span></td></tr>`).join('') + '</tbody>'
        : '<tr><td><div class="empty">No upcoming bookings</div></td></tr>';

      document.getElementById('ws-bars').innerHTML = `<div class="chart-bar-row"><div class="chart-bar-label">Available</div><div class="chart-bar-track"><div class="chart-bar-fill" style="width:32%;background:var(--green)"></div></div><div class="chart-bar-val">32%</div></div><div class="chart-bar-row"><div class="chart-bar-label">In use</div><div class="chart-bar-track"><div class="chart-bar-fill" style="width:68%"></div></div><div class="chart-bar-val">68%</div></div>`;
      document.getElementById('peak-bars').innerHTML = `<div class="chart-bar-row"><div class="chart-bar-label">10–12</div><div class="chart-bar-track"><div class="chart-bar-fill" style="width:90%"></div></div><div class="chart-bar-val">9</div></div><div class="chart-bar-row"><div class="chart-bar-label">14–16</div><div class="chart-bar-track"><div class="chart-bar-fill" style="width:80%"></div></div><div class="chart-bar-val">8</div></div>`;
    }

    function renderCalendar() {
      const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
      document.getElementById('cal-title').textContent = months[calMonth] + ' ' + calYear;
      const grid = document.getElementById('cal-days'); grid.innerHTML = '';
      const first = new Date(calYear, calMonth, 1).getDay();
      const dim = new Date(calYear, calMonth + 1, 0).getDate();
      const td = new Date();
      for (let i = 0; i < first; i++) { const d = document.createElement('div'); d.className = 'day other-month'; grid.appendChild(d); }
      for (let i = 1; i <= dim; i++) {
        const ds = `${calYear}-${String(calMonth + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
        const d = document.createElement('div'); d.className = 'day' + (ds === todayStr() ? ' today' : '') + (selectedDate === ds ? ' selected' : '');
        d.textContent = i; d.onclick = () => selectDate(ds, d); grid.appendChild(d);
      }
    }

    function changeMonth(dir) { calMonth += dir; if (calMonth < 0) { calMonth = 11; calYear--; } if (calMonth > 11) { calMonth = 0; calYear++; } renderCalendar(); }
    function selectDate(ds, el) {
      document.querySelectorAll('.day.selected').forEach(d => d.classList.remove('selected'));
      el.classList.add('selected'); selectedDate = ds;
      document.getElementById('slot-date-hdr').textContent = ds;
      renderSlots(ds);
    }

    function renderSlots(ds) {
      const slots = [['08:00', '10:00'], ['10:00', '12:00'], ['12:00', '14:00']];
      document.getElementById('slot-list').innerHTML = slots.map(([s, e]) => `<div class="slot available"><div><div class="slot-time">${s} – ${e}</div></div><button class="btn btn-sm btn-primary" onclick="openBookingFor('${ds}','${s}','${e}')">Book</button></div>`).join('');
    }

    function openBookingFor(ds, s, e) { document.getElementById('b-date').value = ds; document.getElementById('b-start').value = s; document.getElementById('b-end').value = e; openModal('booking-modal'); }

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
          user: 'Ashan K.',
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
      if (eq.inUse < eq.total) {
        eq.inUse++;
        saveData();
        toast('success', 'Reserved!', `${eq.name.slice(0, -1)} reserved.`);
        renderEquipment();
      } else {
        toast('error', 'Unavailable', 'All units are currently in use.');
      }
    }

    function renderMyBookings(filter) {
      const rows = DB.bookings.filter(b => b.user === 'Ashan K.' && (filter === 'all' || b.status === filter));
      document.getElementById('my-bookings-body').innerHTML = rows.map(b => `<tr><td>${b.title}</td><td>${b.date}</td><td>${b.start} – ${b.end}</td><td>${b.ws}</td><td>${b.equip}</td><td><span class="badge badge-${b.status}">${cap(b.status)}</span></td><td>${b.status === 'pending' ? `<button class="btn btn-sm btn-danger" onclick="cancelBooking(${b.id})">Cancel</button>` : ''}</td></tr>`).join('');
    }

    function filterMyBookings(f, btn) { document.querySelectorAll('#page-mybookings .btn-sm').forEach(b => b.style.background = ''); btn.style.background = 'var(--bg-secondary)'; renderMyBookings(f); }

    function cancelBooking(id) {
      const b = DB.bookings.find(x => x.id === id);
      if (!b) return;
      if (b.status !== 'pending') {
        alert('You can only cancel pending requests. Please contact an administrator to cancel approved bookings.');
        return;
      }
      if (confirm('Are you sure you want to cancel this booking request?')) {
        b.status = 'cancelled';
        saveData();
        renderMyBookings('all');
        toast('info', 'Cancelled', 'Booking request has been cancelled.');
      }
    }

    function renderWaitlist() {
      const waitlistEntries = DB.waitlist.map(w => ({ ...w, type: 'Waitlist' }));
      const pendingBookings = DB.bookings.filter(b => b.status === 'pending').map(b => ({
        id: b.id,
        user: b.user,
        date: b.date,
        start: b.start,
        pos: '—',
        type: 'Pending'
      }));

      const all = [...waitlistEntries, ...pendingBookings];

      document.getElementById('waitlist-body').innerHTML = all.length
        ? all.map(w => `<div class="waitlist-item">
        <div class="pos-badge">${w.pos}</div>
        <div style="flex:1">
          <div class="font-600">${w.user} <span class="badge badge-${w.type.toLowerCase() === 'pending' ? 'pending' : 'info'}" style="font-size:10px; margin-left:5px">${w.type}</span></div>
          <div class="text-sm text-muted">${w.date} · ${w.start}</div>
        </div>
        ${w.user === 'Ashan K.' ? '<span class="badge badge-info">You</span>' : '<span class="badge badge-approved">Waiting</span>'}
      </div>`).join('')
        : `<div class="empty"><i class="ti ti-list"></i>Waitlist is empty</div>`;
    }

    function renderAI() {
      document.getElementById('ai-stats-content').innerHTML = `<div class="flex-between text-sm mb-14"><span>Occupancy</span><span>68%</span></div><div class="progress-bar"><div class="progress-fill" style="width:68%"></div></div>`;
      renderNotifLog();
    }

    function renderNotifLog() {
      document.getElementById('notif-log').innerHTML = DB.notifications.slice(0, 4).map(n => `<div style="padding:7px 0;border-bottom:0.5px solid var(--border)"><strong>${n.title}</strong><div class="text-muted">${n.time}</div></div>`).join('');
    }

    function submitBooking() {
      const b = { id: DB.nextId++, user: 'Ashan K.', title: document.getElementById('b-title').value, date: document.getElementById('b-date').value, start: document.getElementById('b-start').value, end: document.getElementById('b-end').value, ws: document.getElementById('b-ws').value, equip: document.getElementById('b-equip').value, status: 'pending' };
      DB.bookings.push(b); saveData(); toast('success', 'Submitted', 'Pending approval.'); closeModal('booking-modal'); renderDashboard();
    }

    renderDashboard();
  </script>
</body>

</html>