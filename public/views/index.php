<?php

require_once 'shared.php';

$authorizationUrl = $userManagement->getAuthorizationUrl(
  $_ENV['WORKOS_REDIRECT_URI'],
  null,
  'authkit'
);
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
      <h1>AuthKit authentication example</h1>
      <p>Sign in to view your account details</p>
      <a href="<?= $authorizationUrl ?>">
        <button>Sign in with AuthKit</button>
      </a>
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