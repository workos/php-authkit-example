<?php

require_once 'shared.php';

$response = $userManagement->authenticateWithCode(
  $_ENV['WORKOS_CLIENT_ID'],
  $_GET['code']
);

$user = $response->user;

// $user = (object) [
//   'firstName' => 'Paul',
//   'lastName' => 'Asjes',
//   'email' => 'foo@example.com',
//   'role' => 'admin',
//   'id' => 'user_123',
// ];
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