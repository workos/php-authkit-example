<?php

require '../vendor/autoload.php';

// If the .env file was not configured properly, display a helpful message.
if (!file_exists('../.env')) {
?>
  <h1>Missing <code>.env</code></h1>

  <p>Make a copy of <code>.env.example</code>, place it in the same directory as composer.json, and name it <code>.env</code>, then populate the variables.</p>
  <p>It should look something like the following, but contain your API keys which you can retrieve from the <a href='https://dashboard.workos.com/'>WorkOS dashboard</a>:</p>
  <pre>
    WORKOS_CLIENT_ID=
    WORKOS_API_KEY=
    WORKOS_REDIRECT_URI=
    WORKOS_COOKIE_PASSWORD=
  </pre>
  <hr>

  <p>You can use this command to get started:</p>
  <pre>cp .env.example .env</pre>

<?php
  exit;
}

// Load `.env` file from the server directory so that
// environment variables are available in $_ENV or via
// getenv().
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Make sure the configuration file is good.
if (!$_ENV['WORKOS_API_KEY']) {
?>

  <h1>Invalid <code>.env</code></h1>
  <p>Make a copy of <code>.env.example</code> and name it <code>.env</code>, then populate the variables.</p>
  <p>It should look something like the following, but contain your API keys which you can retrieve from the <a href='https://dashboard.workos.com/'>WorkOS dashboard</a>:</p>
  <pre>
    WORKOS_CLIENT_ID=
    WORKOS_API_KEY=
    WORKOS_REDIRECT_URI=
    WORKOS_COOKIE_PASSWORD=
  </pre>
  <hr>

  <p>You can use this command to get started:</p>
  <pre>cp .env.example .env</pre>

<?php
  exit;
}

WorkOS\WorkOS::setApiKey($_ENV['WORKOS_API_KEY']);
WorkOS\WorkOS::setClientId($_ENV['WORKOS_CLIENT_ID']);

$userManagement = new WorkOS\UserManagement();
