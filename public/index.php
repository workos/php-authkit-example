<?php
$request = $_SERVER['REQUEST_URI'];

// Simple router
switch ($request) {
  case '/':
  case '':
    require __DIR__ . '/views/index.php';
    break;
  case str_starts_with($request, '/callback'):
    require __DIR__ . '/views/callback.php';
    break;
  case '/account':
    require __DIR__ . '/views/account.php';
    break;
  case '/signout':
    require __DIR__ . 'signout.php';
    break;
  default:
    http_response_code(404);
    echo '404 page not found';
    break;
}
