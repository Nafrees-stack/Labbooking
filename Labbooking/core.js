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

// ══════════════════════════════════════════
//  UI HELPERS
// ══════════════════════════════════════════
function openModal(id) {
  const el = document.getElementById(id);
  if (!el) return;
  el.classList.add('open');
  if (id === 'booking-modal') {
    const d = new Date(); d.setDate(d.getDate() + 1);
    document.getElementById('b-date').value = d.toISOString().split('T')[0];
    document.getElementById('b-date').min = new Date().toISOString().split('T')[0];
  }
  if (id === 'eq-request-modal' || id === 'waitlist-modal') {
    const d = new Date(); d.setDate(d.getDate() + 1);
    const key = id === 'eq-request-modal' ? 'eq-date' : 'wl-date';
    document.getElementById(key).value = d.toISOString().split('T')[0];
  }
}

function closeModal(id) {
  const el = document.getElementById(id);
  if (el) el.classList.remove('open');
}

// Global modal background click listener
document.addEventListener('click', e => {
  if (e.target.classList.contains('overlay')) {
    e.target.classList.remove('open');
  }
});

function sendNotif(type, title, body) {
  DB.notifications.unshift({ type, title, body, time: 'Just now' });
  if (DB.notifications.length > 10) DB.notifications.pop();
  saveData();
  if (typeof renderNotifLog === 'function') renderNotifLog();
}

function toast(type, title, body, dur = 4000) {
  const icons = { success: 'ti-check-circle', error: 'ti-alert-circle', info: 'ti-info-circle' };
  const colors = { success: 'var(--green)', error: 'var(--red)', info: 'var(--blue)' };
  const el = document.createElement('div'); el.className = `toast ${type}`;
  el.innerHTML = `<i class="ti ${icons[type]} toast-icon" style="color:${colors[type]}"></i><div class="toast-msg"><div class="toast-title">${title}</div><div class="toast-body">${body}</div></div>`;
  const wrap = document.getElementById('toast-wrap');
  if (!wrap) return;
  wrap.appendChild(el);
  setTimeout(() => { el.style.opacity = '0'; el.style.transition = 'opacity 0.3s'; setTimeout(() => el.remove(), 300) }, dur);
}

// ══════════════════════════════════════════
//  UTILS
// ══════════════════════════════════════════
function cap(s) { return s.charAt(0).toUpperCase() + s.slice(1); }
function todayStr() { return new Date().toISOString().split('T')[0]; }
