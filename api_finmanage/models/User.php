<?php 

class User {
    public $id;
    public $name;
    public $lastname;
    public $username;
    public $email;
    public $password;
    public $sessionToken;
    public $tokenExpiresAt;

    public function __construct($id = null, $name = null, $lastname = null, $username = null, $email = null, $password = null, $sessionToken = null, $tokenExpiresAt = null) {
        $this->id = $id;
        $this->name = $name;
        $this->lastname = $lastname;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->sessionToken = $sessionToken;
        $this->tokenExpiresAt = $tokenExpiresAt;
    }

    public function getFullName() {
        return $this->name . ' ' . $this->lastname;
    }

    public function generatePassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function isSessionTokenValid() {
        return strtotime($this->tokenExpiresAt) > time();
    }
}

interface UserDAOInterface {
    public function getAllUsers();
    public function getUserById($id);
    public function createUser(User $user);
    public function updateUser(User $user);
    public function deleteUser($id);
    public function isEmailRegistered($email);
}
?>

