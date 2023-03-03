<?php

declare(strict_types = 1);

namespace models;


class TokenModel extends Database
{
    /**
     * @throws \Exception
     */
    public static function create(string $key): string
    {
        return bin2hex($key).'.'.bin2hex(random_bytes(32));
    }

    public static function save(string $token): void
    {
        $email = hex2bin(explode('.', $token)[0]);

        $dbModel = new Database();
        // 1. delete existing token
        $dbModel->delete("DELETE FROM pwd_reset WHERE email = :email", ['email' => $email]);
        // 2. save a new token
        $dbModel->insert("INSERT INTO pwd_reset (email, token, expire) VALUES (:email, :token, :expire)", [
            'email' => $email,
            'token' => $token,
            'expire' => time() + 60 * 60
        ]);
    }

    public static function verify(string $token): bool
    {
        $email = hex2bin(explode('.', $token)[0]);

        $dbModel = new Database();
        // 1. find token in database
        $result = $dbModel->select("SELECT * FROM pwd_reset WHERE email = :email AND token = :token", [
            'email' => $email,
            'token' => $token
        ])[0];

        if (!$result) return false;
        else if ($result['expire'] < strtotime('now'))
        {
            $dbModel->delete("DELETE FROM pwd_reset WHERE email = :email", ['email' => $email]);
            return false;
        }
        
        return true;
    }

    public static function remove(string $token)
    {
        $dbModel = new Database();
        // 1. delete existing token
        $dbModel->delete("DELETE FROM pwd_reset WHERE email = :email", [
            'email' => hex2bin(explode('.', $token)[0])
        ]);
    }
}