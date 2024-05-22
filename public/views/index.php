<?php

use WorkOS\UserManagement;

require_once 'shared.php';
require_once 'session.php';

$authorizationUrl = $userManagement->getAuthorizationUrl(
  $_ENV['WORKOS_REDIRECT_URI'],
  null,
  'authkit'
);

$user = null;

if (isset($_COOKIE['wos-session'])) {
  // Decrypt the session
  $session = getSessionFromCookie();

  $user = $session->user;

  // Check if the session is still valid
  $isValid = verifyAccessToken($session->access_token);

  if (!$isValid) {
    error_log('Session invalid, trying refresh token');

    try {
      $authResult = $userManagement->authenticateWithRefreshToken(
        $_ENV['WORKOS_CLIENT_ID'],
        $session->refresh_token,
      );

      // Refresh tokens are single use, so update the cookie to use the new refresh token
      setSessionCookie([
        'refresh_token' => $authResult->refresh_token,
        'access_token' => $authResult->access_token,
        'user' => $authResult->user,
      ]);

      $user = $authResult->user;
    } catch (\Exception $e) {
      echo 'Failed to refresh: ' . $e->getMessage();

      // Delete cookie
      unset($_COOKIE['wos-session']);
      setcookie('wos-session', '', time() - 3600, '/');

      // Unset user
      unset($user);
    }
  }
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Example AuthKit authentication app</title>
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
        <h1>AuthKit authentication example</h1>
        <?php if (isset($user)) : ?>
          <p>Welcome back, <?= $user->firstName ?></p>
          <div>
            <a href="/account"><button>View account</button></a>
            <form action="signout.php" method="get">
              <button type="submit">Sign out</button>
            </form>
          </div>
        <?php else : ?>
          <p>Sign in to view your account details</p>
          <a href="<?= $authorizationUrl ?>">
            <button>Sign in with AuthKit</button>
          </a>
        <?php endif ?>
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