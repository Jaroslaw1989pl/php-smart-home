<?php

declare(strict_types = 1);

namespace models;


class UserModel extends Database
{
   private string $uuid;
   private string $email;
   private \DateTime $emailUpdate;
   private string $pass;
   private \DateTime $passUpdate;

    public function __construct() {
        parent::__construct();
    }
    
    public function add(array $form): void
    {
        $user = [
            'id' => time().uniqid(),
            'email' => $form['userEmail'],
            'emailUpdate' => time(),
            'pass' => password_hash($form['userPass'], PASSWORD_BCRYPT, ['cost' => 12]),
            'passUpdate' => time()
        ];

        // saving user authentication data in database
        $this->insert("INSERT INTO users (user_id, email, email_update, pass, pass_update) 
                       VALUES (:id, :email, :emailUpdate, :pass, :passUpdate)", $user);
        // saving user profile data in database
        $this->insert("INSERT INTO profiles (user_id) VALUES (:id)", ['id' => $user['id']]);
    }

    public function find(string $email): ?array
    {
        return $this->select("SELECT * FROM users WHERE email = :email", ['email' => $email])[0] ?? null;
    }

    public function get(?string $userId): ?array
    {
        if ($userId === null) return null;

        $result = $this->select("SELECT * FROM users RIGHT JOIN profiles ON users.user_id = profiles.user_id
                                 WHERE users.user_id = :id", ['id' => $userId])[0];

        return [
            'id' => $result['user_id'],
            'email' => $result['email'],
            'emailUpdate' => $result['email_update'],
            'passUpdate' => $result['pass_update'],
            'firstName' => $result['first_name'],
            'lastName' => $result['last_name'],
            'phone' => $result['phone'],
            'location' => $result['location'],
            'avatar' => $result['avatar']
        ];
    }

    public function setPassword(string $key, string $value): int
    {
        $data = [
            'pass' => password_hash($value, PASSWORD_BCRYPT, ['cost' => 12]),
            'passUpdate' => time(),
            'email' => $key
        ];
        return $this->update("UPDATE users SET pass = :pass, pass_update = :passUpdate WHERE email = :email", $data);
    }

    public function setAvatar(string $id, string $avatar): int
    {
        return $this->update("UPDATE profiles SET avatar = :avatar WHERE user_id = :id", ['avatar' => $avatar, 'id' => $id]);
    }

    public function setFirstName(string $id, string $value): int
    {
        $data = ['id' => $id, 'firstName' => $value];
        return $this->update("UPDATE profiles SET first_name = :firstName WHERE user_id = :id", $data);
    }

    public function setLastName(string $id, string $value): int
    {
        $data = ['id' => $id, 'lastName' => $value];
        return $this->update("UPDATE profiles SET last_name = :lastName WHERE user_id = :id", $data);
    }

    public function setPhone(string $id, string $value): int
    {
        $data = ['id' => $id, 'phone' => $value];
        return $this->update("UPDATE profiles SET phone = :phone WHERE user_id = :id", $data);
    }

    public function setLocation(string $id, string $value): int
    {
        $data = ['id' => $id, 'location' => $value];
        return $this->update("UPDATE profiles SET location = :location WHERE user_id = :id", $data);
    }

    public function remove(string $userId, string $userEmail): void
    {
        $this->delete("DELETE FROM users WHERE user_id = :id", ['id' => $userId]);
        $this->delete("DELETE FROM profiles WHERE user_id = :id", ['id' => $userId]);
        $this->delete("DELETE FROM pwd_reset WHERE email = :email", ['email' => $userEmail]);
    }
}