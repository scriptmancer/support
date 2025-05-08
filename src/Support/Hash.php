<?php

namespace Nazim\Support;

class Hash
{
    /**
     * Default cost factor for bcrypt.
     */
    protected static int $rounds = 10;

    /**
     * Default cipher for OpenSSL encryption.
     */
    protected static string $cipher = 'AES-256-CBC';

    /**
     * Hash the given value using bcrypt.
     *
     * @param string $value
     * @param array $options
     * @return string
     */
    public static function make(string $value, array $options = []): string
    {
        $cost = $options['rounds'] ?? static::$rounds;

        return password_hash($value, PASSWORD_BCRYPT, [
            'cost' => $cost,
        ]);
    }

    /**
     * Check if the given plain value matches a hash.
     *
     * @param string $value
     * @param string $hashedValue
     * @return bool
     */
    public static function check(string $value, string $hashedValue): bool
    {
        return password_verify($value, $hashedValue);
    }

    /**
     * Check if the given hash needs to be rehashed.
     *
     * @param string $hashedValue
     * @param array $options
     * @return bool
     */
    public static function needsRehash(string $hashedValue, array $options = []): bool
    {
        $cost = $options['rounds'] ?? static::$rounds;

        return password_needs_rehash($hashedValue, PASSWORD_BCRYPT, [
            'cost' => $cost,
        ]);
    }

    /**
     * Set the default cost factor.
     *
     * @param int $rounds
     */
    public static function setRounds(int $rounds): void
    {
        static::$rounds = $rounds;
    }

    /**
     * Generate a random string of the specified length.
     *
     * @param int $length
     * @return string
     */
    public static function random(int $length = 16): string
    {
        return bin2hex(random_bytes($length / 2));
    }

    /**
     * Create a keyed-hash message authentication code (HMAC).
     *
     * @param string $value
     * @param string $key
     * @param string $algorithm
     * @return string
     */
    public static function hmac(string $value, string $key, string $algorithm = 'sha256'): string
    {
        return hash_hmac($algorithm, $value, $key);
    }

    /**
     * Generate a secure one-time token.
     *
     * @param int $length
     * @return string
     */
    public static function token(int $length = 40): string
    {
        return static::random($length);
    }

    /**
     * Hash the given value using the specified algorithm.
     *
     * @param string $value
     * @param string $algorithm
     * @param bool $binary
     * @return string
     */
    public static function digest(string $value, string $algorithm = 'sha256', bool $binary = false): string
    {
        return hash($algorithm, $value, $binary);
    }

    /**
     * Get information about a hashed value.
     *
     * @param string $hashedValue
     * @return array
     */
    public static function info(string $hashedValue): array
    {
        return password_get_info($hashedValue);
    }

    /**
     * Create a UUID (version 4).
     *
     * @return string
     */
    public static function uuid(): string
    {
        $data = random_bytes(16);
        
        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * Encrypt a value using OpenSSL.
     *
     * @param string $value
     * @param string $key
     * @param string|null $cipher
     * @return string
     * @throws \Exception
     */
    public static function encrypt(string $value, string $key, ?string $cipher = null): string
    {
        $cipher = $cipher ?? static::$cipher;
        
        // Generate an initialization vector
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        
        // Encrypt the data
        $encrypted = openssl_encrypt($value, $cipher, $key, 0, $iv);
        
        if ($encrypted === false) {
            throw new \RuntimeException('Could not encrypt the data.');
        }
        
        // Return the IV with the encrypted data, encoded in base64
        return base64_encode($iv . $encrypted);
    }
    
    /**
     * Decrypt a value using OpenSSL.
     *
     * @param string $payload
     * @param string $key
     * @param string|null $cipher
     * @return string
     * @throws \Exception
     */
    public static function decrypt(string $payload, string $key, ?string $cipher = null): string
    {
        $cipher = $cipher ?? static::$cipher;
        
        // Decode the payload
        $decoded = base64_decode($payload);
        
        // Extract the initialization vector
        $ivlen = openssl_cipher_iv_length($cipher);
        if (strlen($decoded) <= $ivlen) {
            throw new \RuntimeException('Invalid payload.');
        }
        
        $iv = substr($decoded, 0, $ivlen);
        $encrypted = substr($decoded, $ivlen);
        
        // Decrypt the data
        $decrypted = openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
        
        if ($decrypted === false) {
            throw new \RuntimeException('Could not decrypt the data.');
        }
        
        return $decrypted;
    }
    
    /**
     * Set the default cipher for encryption.
     *
     * @param string $cipher
     */
    public static function setCipher(string $cipher): void
    {
        static::$cipher = $cipher;
    }
    
    /**
     * Get available OpenSSL ciphers.
     *
     * @return array
     */
    public static function getAvailableCiphers(): array
    {
        return openssl_get_cipher_methods();
    }
    
    /**
     * Generate a secure key for encryption.
     *
     * @param int $length
     * @return string
     */
    public static function generateKey(int $length = 32): string
    {
        return static::random($length);
    }
    
    /**
     * Create a secure hash of a file.
     *
     * @param string $filePath
     * @param string $algorithm
     * @return string|false
     */
    public static function file(string $filePath, string $algorithm = 'sha256'): string|false
    {
        return hash_file($algorithm, $filePath);
    }
} 