<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Nazim\Support\Hash;

echo "=== Hash Class Examples ===\n\n";

// Example 1: Hashing a password
echo "Example 1: Hashing a password\n";
echo "----------------------------\n";
$password = 'my-secure-password';
$hashed = Hash::make($password);
echo "Original: {$password}\n";
echo "Hashed: {$hashed}\n\n";

// Example 2: Verifying a password
echo "Example 2: Verifying a password\n";
echo "------------------------------\n";
$isValid = Hash::check('my-secure-password', $hashed);
$isInvalid = Hash::check('wrong-password', $hashed);
echo "Correct password check: " . ($isValid ? 'Valid' : 'Invalid') . "\n";
echo "Incorrect password check: " . ($isInvalid ? 'Valid' : 'Invalid') . "\n\n";

// Example 3: Checking if a hash needs rehashing
echo "Example 3: Checking if a hash needs rehashing\n";
echo "-----------------------------------------\n";
$needsRehash = Hash::needsRehash($hashed);
echo "Needs rehash: " . ($needsRehash ? 'Yes' : 'No') . "\n\n";

// Example 4: Using a different cost factor
echo "Example 4: Using a different cost factor\n";
echo "----------------------------------\n";
Hash::setRounds(12); // Higher cost factor
$strongerHash = Hash::make($password);
echo "Stronger hash: {$strongerHash}\n\n";

// Example 5: Generating random strings
echo "Example 5: Generating random strings\n";
echo "--------------------------------\n";
$random = Hash::random(16);
echo "Random string (16 chars): {$random}\n";
$token = Hash::token(32);
echo "Secure token (32 chars): {$token}\n\n";

// Example 6: Creating a HMAC
echo "Example 6: Creating a HMAC\n";
echo "-----------------------\n";
$value = 'message-to-authenticate';
$key = 'secret-key';
$hmac = Hash::hmac($value, $key);
echo "Value: {$value}\n";
echo "Key: {$key}\n";
echo "HMAC: {$hmac}\n\n";

// Example 7: Using the helper function
echo "Example 7: Using the helper function\n";
echo "--------------------------------\n";
$hashedWithHelper = hash_value('another-password');
echo "Hashed with helper: {$hashedWithHelper}\n\n";

// Example 8: Hashing with different algorithms
echo "Example 8: Hashing with different algorithms\n";
echo "---------------------------------------\n";
$md5 = Hash::digest('test-message', 'md5');
$sha1 = Hash::digest('test-message', 'sha1');
$sha256 = Hash::digest('test-message', 'sha256');
echo "Message: test-message\n";
echo "MD5: {$md5}\n";
echo "SHA1: {$sha1}\n";
echo "SHA256: {$sha256}\n\n";

// Example 9: Getting hash info
echo "Example 9: Getting hash info\n";
echo "-------------------------\n";
$hashInfo = Hash::info($hashed);
echo "Hash info: " . print_r($hashInfo, true) . "\n\n";

// Example 10: Generating UUIDs
echo "Example 10: Generating UUIDs\n";
echo "-------------------------\n";
$uuid1 = Hash::uuid();
$uuid2 = Hash::uuid();
echo "UUID 1: {$uuid1}\n";
echo "UUID 2: {$uuid2}\n\n";

echo "=== Hash Examples Complete ===\n"; 