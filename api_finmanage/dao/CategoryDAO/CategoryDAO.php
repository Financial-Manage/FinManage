<?php 
require_once __DIR__ . '../../../config/database.php';
require_once __DIR__ . '../../../models/Category/Category.php';

class CategoryDAO implements CategoryDAOInterface {
    private $conn;
    private $table = "categories";

    public function __construct(){
        $dataBase = new Database();
        $this->conn = $dataBase->connect();
    }

    public function getAllCategories()
    {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCategoryById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result) {
            return new Category(
                $result["id"],
                $result["name"],
                $result["description"],
                $result["type"]
            );
        }
        return null;
    }

    public function createCategory(Category $category) {
        try {
            $query = "INSERT INTO " . $this->table . " (name, description, type, users_id) VALUES (:name, :description, :type, :users_id)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":name", $category->name);
            $stmt->bindParam(":description", $category->description);
            $stmt->bindParam(":type", $category->type);
            $stmt->bindParam(":users_id", $category->userId, PDO::PARAM_INT);
    
            if ($stmt->execute()) {
                return true;
            } else {
                $errorInfo = $stmt->errorInfo();
                echo "SQL Error: " . $errorInfo[2]; // Exibe o erro na tela
                return false;
            }
        } catch (Exception $e) {
            echo "Exception: " . $e->getMessage(); // Exibe a exceção na tela
            return false;
        }
    }

    public function updateCategory(Category $category) {
        $query = "UPDATE " . $this->table . " SET name = :name, description = :description, type = :type WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $category->id, PDO::PARAM_INT);
        $stmt->bindParam(":name", $category->name);
        $stmt->bindParam(":description", $category->description);
        $stmt->bindParam(":type", $category->type);

        return $stmt->execute();
    }

    public function deleteCategory($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getCategoryByUserId($userId) {
        $query = "SELECT * FROM " . $this->table . " WHERE users_id = :users_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam("users_id", $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCategoryByType($type) {
        $query = "SELECT * FROM " . $this->table . " WHERE type = :type";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":type", $type);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}

?>