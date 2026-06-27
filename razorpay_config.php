<?php
// SVARA — Razorpay Test Mode credentials (no real money charged)
// Switch to rzp_live_... keys only when deploying live.

// Keys are loaded from .env file — NEVER hardcode keys here
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), ';') === 0) continue; // skip comments
        if (strpos($line, '=') !== false) {
            [$key, $value] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

$razorpayKeyId     = $_ENV['RAZORPAY_KEY_ID']     ?? '';
$razorpayKeySecret = $_ENV['RAZORPAY_KEY_SECRET']  ?? '';

function razorpayKeysConfigured($keyId, $keySecret) {
    $placeholders = [
        'rzp_test_your_key_id_here',
        'your_key_secret_here'
    ];
    return !in_array(trim($keyId), $placeholders, true)
        && !in_array(trim($keySecret), $placeholders, true)
        && trim($keyId) !== ''
        && trim($keySecret) !== '';
}
?>