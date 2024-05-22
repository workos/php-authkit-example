<?php

require_once 'shared.php';
require_once 'session.php';

$user = null;

if (isset($_COOKIE['wos-session'])) {
  // Decrypt the session
  $session = getSessionFromCookie();

  $user = $session->user;
}

if (!isset($user)) {
  $authorizationUrl = $userManagement->getAuthorizationUrl(
    $_ENV['WORKOS_REDIRECT_URI'],
    null,
    'authkit'
  );

  header('Location: ' . $authorizationUrl);
  die();
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>AuthKit callback</title>
  <link rel="stylesheet" href="css/base.css" />
</head>

<body>
  <main>
    <div class="container">
      <div class="nav">
        <a href="/"><button>Home</button></a>
        <a href="/account"><button>Account</button></a>
      </div>
      <div class="content">
        <h1>Welcome, <?= $user->firstName ?></h1>
        <div>Below are your account details</div>
        <ul class="account-details">
          <li>First name: <?= $user->firstName ?></li>
          <li>Last name: <?= $user->lastName ?></li>
          <li>Email: <?= $user->email ?></li>
          <?php if (!empty($user->role)) echo "<li>Role: " . $user->role . "</li>"; ?>
          <li>ID: <?= $user->id ?></li>
        </ul>
      </div>
    </div>
    <div class="footer">
      <div>
        <a href="https://workos.com/docs" rel="noreferrer" target="_blank">
          <h4>Documentation</h4>
          <p>View integration guides and SDK documentation.</p>
        </a>
      </div>
      <div>
        <a href="https://workos.com/docs/reference" rel="noreferrer" target="_blank">
          <h4>API Reference</h4>
          <p>Every WorkOS API method and endpoint documented.</p>
        </a>
      </div>
      <div>
        <a href="https://workos.com" rel="noreferrer" target="_blank">
          <h4>WorkOS</h4>
          <p>Learn more about other WorkOS products.</p>
        </a>
      </div>
    </div>
  </main>
</body>

</html>