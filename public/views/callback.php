<?php

require_once 'shared.php';
require_once 'session.php';

$response = $userManagement->authenticateWithCode(
  $_ENV['WORKOS_CLIENT_ID'],
  $_GET['code']
);

setSessionCookie([
  'refresh_token' => $response->refresh_token,
  'access_token' => $response->access_token,
  'user' => $response->user->toArray(),
]);

header('Location: ' . '/');
die();
