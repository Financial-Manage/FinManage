<?php

require_once __DIR__ . '../../config/database.php';
require_once __DIR__ . '../../models/User.php';

class UserDAO implements UserDAOInterface {
    private $conn;
    private $table = "users";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAllUsers() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ); //PDO::FETCH_OBJ faz com que o método fetch() retorne uma linha dos resultados como um objeto anônimo.
    }

    public function getUserById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result) {
            return new User(
                $result["id"],
                $result["name"],
                $result["lastname"],
                $result["username"],
                $result["email"],
                $result["password_hash"]
            );
        }
         return null;
    }

    public function createUser(User $user) {
        $query = "INSERT INTO " . $this->table . " (username, email, name, lastname, password_hash) VALUES (
        :username, :email, :name, :lastname, :password)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $user->username);
        $stmt->bindParam(":email", $user->email);
        $stmt->bindParam(":name", $user->name);
        $stmt->bindParam(":lastname", $user->lastname);
        $stmt->bindParam(":password", $user->password);

        return $stmt->execute();
    }

    public function updateUser(User $user) {
        $query = "UPDATE " . $this->table . " 
                  SET username = :username, email = :email, name = :name, lastname = 
                  :lastname, password_hash = :password 
                  WHERE id = :id";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $user->id, PDO::PARAM_INT);
        $stmt->bindParam(":username", $user->username);
        $stmt->bindParam(":email", $user->email);
        $stmt->bindParam(":name", $user->name);
        $stmt->bindParam(":lastname", $user->lastname);
        $stmt->bindParam(":password", $user->password);
        return $stmt->execute();
    }

    public function deleteUser($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    //verifica se email ja existe
    public function isEmailRegistered($email) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);//indica que o parametro é tipo string
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ) !== false;//PDO::FETCH_OBJ faz com que o método fetch() retorne uma linha dos resultados como um objeto anônimo.
    }

}
?>