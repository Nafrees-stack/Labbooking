<?php
// login.php
// Student login  -> studentss.academic_email + academic_password_hash / academic_plain_password
// Admin login    -> users.username OR users.email + users.password_hash

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Change this path only if your db.php is in another location.
// Example: if login.php is inside /pages, use: require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/db.php';

$mysqli = $mysqli ?? ($conn ?? null);
if (!$mysqli instanceof mysqli) {
    die('Database connection not found. Please check includes/db.php');
}

$mysqli->set_charset('utf8mb4');

$selectedRole = $_POST['role'] ?? '';
$username     = trim((string)($_POST['username'] ?? ''));
$password     = (string)($_POST['password'] ?? '');
$error        = '';

function verify_login_password(string $password, ?string $hash, ?string $plain = null): bool
{
    $hash = trim((string)$hash);

    if ($hash !== '' && password_verify($password, $hash)) {
        return true;
    }

    // Fallback for old/plain saved academic passwords only.
    $plain = (string)$plain;
    if ($plain !== '' && hash_equals($plain, $password)) {
        return true;
    }

    return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!in_array($selectedRole, ['student', 'admin'], true)) {
        $error = 'Please select Student or Administrator.';
    } elseif ($username === '' || $password === '') {
        $error = 'Please enter both your username and password.';
    } elseif ($selectedRole === 'student') {
        // Student login: academic_email + password from studentss table
        $sql = "SELECT id, full_name, academic_email, academic_password_hash, academic_plain_password, status, lms_status, center_reg_no, reference_no
                FROM studentss
                WHERE LOWER(academic_email) = LOWER(?)
                LIMIT 1";
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            $error = 'Student login query failed.';
        } else {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $student = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if (!$student || !verify_login_password($password, $student['academic_password_hash'] ?? '', $student['academic_plain_password'] ?? '')) {
                $error = 'Invalid academic email or password. Please try again.';
            } elseif (strtolower((string)$student['status']) !== 'active' || strtolower((string)$student['lms_status']) !== 'active') {
                $error = 'Your student account is inactive or suspended. Please contact administration.';
            } else {
                session_regenerate_id(true);

                $_SESSION['student_id']     = (int)$student['id'];
                $_SESSION['student_name']   = (string)$student['full_name'];
                $_SESSION['student_email']  = (string)$student['academic_email'];
                $_SESSION['student_reg_no'] = (string)($student['center_reg_no'] ?? '');
                $_SESSION['student_ref_no'] = (string)($student['reference_no'] ?? '');
                $_SESSION['login_role']     = 'student';
                $_SESSION['last_activity_at'] = time();

                header('Location: student.php');
                exit;
            }
        }
    } elseif ($selectedRole === 'admin') {
        // Admin login: users table. Allow username OR email.
        $sql = "SELECT id, name, username, email, role_slug, branch_id, branch_name, password_hash, active
                FROM users
                WHERE LOWER(email) = LOWER(?) OR LOWER(username) = LOWER(?)
                LIMIT 1";
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            $error = 'Admin login query failed.';
        } else {
            $stmt->bind_param('ss', $username, $username);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if (!$user || !password_verify($password, (string)$user['password_hash'])) {
                $error = 'Invalid username/email or password. Please try again.';
            } elseif ((int)$user['active'] !== 1) {
                $error = 'Your admin account is inactive. Please contact Super Admin.';
            } else {
                session_regenerate_id(true);

                $_SESSION['user'] = [
                    'id'          => (int)$user['id'],
                    'name'        => (string)$user['name'],
                    'username'    => (string)($user['username'] ?? ''),
                    'email'       => (string)$user['email'],
                    'role_slug'   => (string)$user['role_slug'],
                    'branch_id'   => $user['branch_id'] !== null ? (int)$user['branch_id'] : null,
                    'branch_name' => (string)($user['branch_name'] ?? ''),
                ];

                // Extra compatibility session keys for existing pages.
                $_SESSION['user_id']      = (int)$user['id'];
                $_SESSION['user_name']    = (string)$user['name'];
                $_SESSION['user_email']   = (string)$user['email'];
                $_SESSION['role_slug']    = (string)$user['role_slug'];
                $_SESSION['branch_id']    = $user['branch_id'] !== null ? (int)$user['branch_id'] : null;
                $_SESSION['branch_name']  = (string)($user['branch_name'] ?? '');
                $_SESSION['login_role']   = 'admin';
                $_SESSION['last_activity_at'] = time();

                header('Location: admin.php');
                exit;
            }
        }
    }
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CompuLab — Login</title>
  <meta name="description" content="Sign in to the CompuLab Computer Lab Booking Portal as a student or administrator." />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css" />
  <link rel="stylesheet" href="styles.css" />
  <style>
    body {
      background: radial-gradient(ellipse 80% 60% at 70% 20%, #d6e9f8, transparent),
                  radial-gradient(ellipse 60% 50% at 10% 80%, #e8f4e8, transparent),
                  var(--bg-tertiary, #f4f7fb);
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      margin: 0;
      padding: 20px;
      font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .login-card {
      background: var(--bg-primary, #fff);
      border-radius: 16px;
      box-shadow: 0 24px 64px rgba(0, 0, 0, 0.10), 0 2px 8px rgba(0,0,0,0.06);
      width: 420px;
      max-width: 100%;
      padding: 44px 40px 36px;
      border: 1px solid var(--border, #dce3ea);
      position: relative;
      overflow: hidden;
    }

    .login-card::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--blue, #185fa5), #60aaec, var(--blue-mid, #2b78bf));
    }

    .login-logo { text-align: center; margin-bottom: 32px; }

    .logo-icon-lg {
      width: 56px;
      height: 56px;
      background: var(--blue, #185fa5);
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-size: 28px;
      margin: 0 auto 14px;
      box-shadow: 0 8px 20px rgba(24, 95, 165, 0.30);
    }

    .login-title { font-size: 22px; font-weight: 700; color: var(--text-primary, #17202a); margin-bottom: 4px; }
    .login-sub { font-size: 13px; color: var(--text-secondary, #667085); }

    .step { display: none; animation: fadeSlideIn 0.28s ease; }
    .step.active { display: block; }
    @keyframes fadeSlideIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    .step-label {
      font-size: 11px;
      font-weight: 600;
      color: var(--text-secondary, #667085);
      text-transform: uppercase;
      letter-spacing: 0.07em;
      margin-bottom: 12px;
    }

    .role-grid { display: flex; flex-direction: column; gap: 10px; }

    .role-btn {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 15px 18px;
      border-radius: 10px;
      border: 1.5px solid var(--border, #dce3ea);
      background: var(--bg-secondary, #f8fafc);
      cursor: pointer;
      transition: border-color 0.18s, background 0.18s, transform 0.15s, box-shadow 0.18s;
      text-align: left;
      width: 100%;
      font-family: inherit;
      color: var(--text-primary, #17202a);
    }

    .role-btn:hover { border-color: var(--blue, #185fa5); background: var(--blue-light, #eaf4ff); transform: translateY(-2px); box-shadow: 0 6px 16px rgba(24, 95, 165, 0.12); }
    .role-btn.selected { border-color: var(--blue, #185fa5); background: var(--blue-light, #eaf4ff); box-shadow: 0 0 0 3px rgba(24, 95, 165, 0.12); }

    .role-icon {
      width: 42px;
      height: 42px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 21px;
      flex-shrink: 0;
    }

    .icon-student { background: var(--blue-light, #eaf4ff); color: var(--blue, #185fa5); }
    .icon-admin { background: var(--amber-light, #fff3dc); color: var(--amber, #c27a00); }

    .role-info .rtitle { font-weight: 600; font-size: 14px; margin-bottom: 2px; }
    .role-info .rdesc { font-size: 12px; color: var(--text-secondary, #667085); }

    .role-check {
      margin-left: auto;
      width: 20px;
      height: 20px;
      border-radius: 50%;
      border: 1.5px solid var(--border, #dce3ea);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      flex-shrink: 0;
      color: transparent;
      transition: all 0.15s;
    }

    .role-btn.selected .role-check { background: var(--blue, #185fa5); border-color: var(--blue, #185fa5); color: #fff; }

    .btn-next, .btn-login {
      width: 100%;
      padding: 11px;
      font-size: 14px;
      font-weight: 600;
      border-radius: 10px;
      background: var(--blue, #185fa5);
      color: #fff;
      border: none;
      cursor: pointer;
      font-family: inherit;
      transition: background 0.15s, transform 0.12s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
    }

    .btn-next { margin-top: 20px; }
    .btn-login { margin-top: 6px; }
    .btn-next:hover, .btn-login:hover { background: #0C447C; transform: translateY(-1px); }
    .btn-next:disabled { background: var(--border, #dce3ea); color: var(--text-secondary, #667085); cursor: not-allowed; transform: none; }

    .back-btn {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: 12px;
      color: var(--text-secondary, #667085);
      cursor: pointer;
      background: none;
      border: none;
      font-family: inherit;
      padding: 0;
      margin-bottom: 20px;
      transition: color 0.14s;
    }
    .back-btn:hover { color: var(--text-primary, #17202a); }
    .back-btn i { font-size: 14px; }

    .role-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      margin-bottom: 22px;
    }

    .role-badge-student { background: var(--blue-light, #eaf4ff); color: var(--blue, #185fa5); }
    .role-badge-admin { background: var(--amber-light, #fff3dc); color: var(--amber, #c27a00); }

    .fg { margin-bottom: 16px; }
    .fg label { display: block; font-size: 12px; font-weight: 600; color: var(--text-secondary, #667085); margin-bottom: 6px; letter-spacing: 0.02em; }
    .fg input {
      width: 100%;
      box-sizing: border-box;
      padding: 10px 12px;
      border-radius: 8px;
      border: 1.5px solid var(--border, #dce3ea);
      background: var(--bg-secondary, #f8fafc);
      color: var(--text-primary, #17202a);
      font-size: 14px;
      font-family: inherit;
      transition: border-color 0.15s, box-shadow 0.15s;
      outline: none;
    }

    .fg input:focus { border-color: var(--blue, #185fa5); box-shadow: 0 0 0 3px rgba(24, 95, 165, 0.12); }
    .fg .input-wrap { position: relative; }
    .fg .input-wrap input { padding-right: 40px; }

    .toggle-pw {
      position: absolute;
      right: 11px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      cursor: pointer;
      color: var(--text-secondary, #667085);
      font-size: 16px;
      display: flex;
      align-items: center;
      transition: color 0.14s;
    }

    .toggle-pw:hover { color: var(--text-primary, #17202a); }

    .error-msg {
      background: var(--red-light, #fff0f0);
      color: var(--red, #a32d2d);
      border: 1px solid rgba(163, 45, 45, 0.2);
      border-radius: 8px;
      padding: 9px 12px;
      font-size: 12px;
      font-weight: 500;
      display: none;
      margin-bottom: 14px;
      align-items: center;
      gap: 7px;
    }

    .error-msg.visible { display: flex; }

    .login-hint { margin-top: 20px; text-align: center; font-size: 11.5px; color: var(--text-secondary, #667085); line-height: 1.7; }
    .login-hint code { background: var(--bg-secondary, #f8fafc); border: 1px solid var(--border, #dce3ea); border-radius: 4px; padding: 1px 5px; font-size: 11px; font-family: 'Courier New', monospace; color: var(--text-primary, #17202a); }
    .footer-note { text-align: center; font-size: 11px; color: var(--text-secondary, #667085); margin-top: 28px; }
  </style>
</head>

<body>

  <div class="login-card">
    <div class="login-logo">
      <div class="logo-icon-lg"><i class="ti ti-device-desktop"></i></div>
      <div class="login-title">CompuLab</div>
      <div class="login-sub">Computer Lab Booking Portal</div>
    </div>

    <form method="post" action="" autocomplete="off">
      <input type="hidden" name="role" id="role-input" value="<?= e($selectedRole) ?>">

      <div class="step <?= $selectedRole === '' ? 'active' : '' ?>" id="step-role">
        <div class="step-label">Select your role to continue</div>
        <div class="role-grid">
          <button class="role-btn" id="btn-student" onclick="selectRole('student')" type="button">
            <div class="role-icon icon-student"><i class="ti ti-school"></i></div>
            <div class="role-info">
              <div class="rtitle">Student</div>
              <div class="rdesc">Use Academic Email and Password</div>
            </div>
            <div class="role-check"><i class="ti ti-check"></i></div>
          </button>

          <button class="role-btn" id="btn-admin" onclick="selectRole('admin')" type="button">
            <div class="role-icon icon-admin"><i class="ti ti-shield-lock"></i></div>
            <div class="role-info">
              <div class="rtitle">Administrator</div>
              <div class="rdesc">Use users table username/email and password</div>
            </div>
            <div class="role-check"><i class="ti ti-check"></i></div>
          </button>
        </div>

        <button class="btn-next" id="btn-continue" onclick="goToLogin()" disabled type="button">
          Continue <i class="ti ti-arrow-right"></i>
        </button>
      </div>

      <div class="step <?= $selectedRole !== '' ? 'active' : '' ?>" id="step-login">
        <button class="back-btn" onclick="goBack()" type="button">
          <i class="ti ti-arrow-left"></i> Back
        </button>

        <div id="role-badge-display"></div>

        <div class="fg">
          <label for="username" id="username-label">Username</label>
          <input type="text" id="username" name="username" value="<?= e($username) ?>" placeholder="Enter your username" autocomplete="username" />
        </div>

        <div class="fg">
          <label for="password">Password</label>
          <div class="input-wrap">
            <input type="password" id="password" name="password" placeholder="Enter your password" autocomplete="current-password" />
            <button class="toggle-pw" onclick="togglePw()" type="button" tabindex="-1" title="Show/hide password">
              <i class="ti ti-eye" id="pw-eye"></i>
            </button>
          </div>
        </div>

        <div class="error-msg <?= $error !== '' ? 'visible' : '' ?>" id="error-msg">
          <i class="ti ti-alert-circle"></i>
          <span id="error-text"><?= e($error !== '' ? $error : 'Invalid username or password.') ?></span>
        </div>

        <button class="btn-login" id="btn-login" type="submit">
          <span class="btn-label">Sign in</span>
        </button>
      </div>
    </form>

    <div class="login-hint">
      <strong>Login Details</strong><br>
      Student — Academic Email from <code>studentss.academic_email</code><br>
      Admin — Username/Email from <code>users</code> table
    </div>

    <div class="footer-note">Eden Campus, Negombo</div>
  </div>

  <script>
    let selectedRole = <?= json_encode($selectedRole ?: null) ?>;

    function selectRole(role) {
      selectedRole = role;
      document.getElementById('role-input').value = role;
      document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('selected'));
      document.getElementById('btn-' + role).classList.add('selected');
      document.getElementById('btn-continue').disabled = false;
    }

    function updateBadgeAndLabels() {
      if (!selectedRole) return;
      const isAdmin = selectedRole === 'admin';
      document.getElementById('role-badge-display').innerHTML = `
        <div class="role-badge role-badge-${selectedRole}">
          <i class="ti ti-${isAdmin ? 'shield-lock' : 'school'}"></i>
          Logging in as ${isAdmin ? 'Administrator' : 'Student'}
        </div>`;
      document.getElementById('username-label').textContent = isAdmin ? 'Username / Email' : 'Academic Email';
      document.getElementById('username').placeholder = isAdmin ? 'Enter admin username or email' : 'Enter academic email';
    }

    function goToLogin() {
      if (!selectedRole) return;
      document.getElementById('step-role').classList.remove('active');
      document.getElementById('step-login').classList.add('active');
      updateBadgeAndLabels();
      hideError();
      setTimeout(() => document.getElementById('username').focus(), 100);
    }

    function goBack() {
      selectedRole = null;
      document.getElementById('role-input').value = '';
      document.getElementById('step-login').classList.remove('active');
      document.getElementById('step-role').classList.add('active');
      document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('selected'));
      document.getElementById('btn-continue').disabled = true;
      hideError();
    }

    function togglePw() {
      const input = document.getElementById('password');
      const icon  = document.getElementById('pw-eye');
      if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'ti ti-eye-off';
      } else {
        input.type = 'password';
        icon.className = 'ti ti-eye';
      }
    }

    function hideError() {
      document.getElementById('error-msg').classList.remove('visible');
    }

    if (selectedRole) {
      selectRole(selectedRole);
      updateBadgeAndLabels();
    }
  </script>

</body>
</html>
