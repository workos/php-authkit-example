<?php

require_once 'shared.php';
require_once 'session.php';

$response = $userManagement->authenticateWithCode(
  $_ENV['WORKOS_CLIENT_ID'],
  $_GET['code']
);

$accessToken = $response->access_token;
$refreshToken = $response->refresh_token;

$user = $response->user;

setSessionCookie([
  'refresh_token' => $refreshToken,
  'access_token' => $accessToken,
  'user' => $user->toArray(),
]);

header('Location: ' . '/');
die();
