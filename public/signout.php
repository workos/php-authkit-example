<?php

require_once 'shared.php';
require_once 'session.php';

// Get session ID from JWT
$session = getSessionFromCookie();
$jwtData = getSessionFromJWT($session->access_token);

// Delete cookie
unset($_COOKIE['wos-session']);
setcookie('wos-session', '', time() - 3600, '/');

// Revoke session
$signOutUrl = $userManagement->getLogoutUrl($jwtData->sid);

header('Location: ' . $signOutUrl);
die();
