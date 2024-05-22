<?php

require_once 'shared.php';

use Firebase\JWT\JWT;
use Firebase\JWT\JWK;
use GuzzleHttp\Client;

$jwksUrl = $userManagement->getJwksUrl($_ENV['WORKOS_CLIENT_ID']);

// Create a new HTTP client
$client = new Client();

try {
  // Fetch the JWKS data
  $response = $client->request('GET', $jwksUrl);
  $jwksJSON = $response->getBody()->getContents();
  $jwks = json_decode($jwksJSON, true);
} catch (\Exception $e) {
  error_log('Error fetching JWKS: ' . $e->getMessage());
}

function encrypt($plaintext, $key, $cipher = 'aes-256-gcm')
{
  if (!in_array($cipher, openssl_get_cipher_methods())) {
    return false;
  }
  $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
  $tag = null;
  $ciphertext = openssl_encrypt(
    gzcompress($plaintext),
    $cipher,
    base64_decode($key),
    $options = 0,
    $iv,
    $tag,
  );
  return json_encode(
    array(
      "ciphertext" => base64_encode($ciphertext),
      "cipher" => $cipher,
      "iv" => base64_encode($iv),
      "tag" => base64_encode($tag),
    )
  );
}

function decrypt($cipherjson, $key)
{
  try {
    $json = json_decode($cipherjson, true, 2,  JSON_THROW_ON_ERROR);
  } catch (Exception $e) {
    return false;
  }
  return gzuncompress(
    openssl_decrypt(
      base64_decode($json['ciphertext']),
      $json['cipher'],
      base64_decode($key),
      $options = 0,
      base64_decode($json['iv']),
      base64_decode($json['tag'])
    )
  );
}

function setSessionCookie($data)
{
  // The refresh token is sensitive as it allows you to login the user again.
  // Therefore we encrypt it before storing in the cookie
  $encryptedSession = encrypt(json_encode($data), $_ENV['WORKOS_COOKIE_PASSWORD']);

  setcookie('wos-session', $encryptedSession, 0, '/', '', false, true);
}

function getSessionFromCookie()
{
  global $jwks;

  // Decrypt the session from the cookie
  $session = decrypt($_COOKIE['wos-session'], $_ENV['WORKOS_COOKIE_PASSWORD']);

  $session = json_decode($session);

  return $session;
}

function getSessionFromJWT($accessToken)
{
  global $jwks;

  $session = JWT::decode($accessToken, JWK::parseKeySet($jwks));

  return $session;
}

function verifyAccessToken($accessToken)
{
  try {
    getSessionFromJWT($accessToken);
    return true;
  } catch (\Exception $e) {
    error_log('Access token verification failed: ' . $e->getMessage());
    return false;
  }
}
