<?php
require_once __DIR__ . '../../../config/database.php';
require_once __DIR__ . '../../../models/Income/Income.php';


class IncomeDAO implements IncomeDAOInterface
{
    private $conn;
    private $table = "transactions";

    public function __construct()
    {
        $dataBase = new Database();
        $this->conn = $dataBase->connect();
    }

    public function getAllIncomes()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE type = 'income'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getIncomeById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id AND type = 'income'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return new Income(
                $result["id"],
                $result["amount"],
                $result["description"],
                $result["date"],
                $result["type"],
                $result["users_id"],
                $result["category_id"]
            );
        }
        return null;
    }

    public function createIncome(Income $income)
    {
        $query = "INSERT INTO " . $this->table ." (
        amount, description, date, type, users_id, category_id
        ) VALUES (
         :amount, :description, :date, :type, :users_id, :category_id
        )";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":amount", $income->amount);
        $stmt->bindParam(":description", $income->description);
        $stmt->bindParam(":date", $income->date);
        $stmt->bindParam(":type", $income->type);
        $stmt->bindParam(":users_id", $income->userId);
        $stmt->bindParam(":category_id", $income->categoryId);

        return $stmt->execute();
    }

    public function updateIncome(Income $income) {
        $query = "UPDATE " . $this->table . " SET 
        amount = :amount, description = :description, date = :date, category_id = :category_id 
        WHERE id = :id AND type = 'income'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $income->id, PDO::PARAM_INT);
        $stmt->bindParam(":amount", $income->amount);
        $stmt->bindParam(":description", $income->description);
        $stmt->bindParam(":date", $income->date);
        $stmt->bindParam(":category_id", $income->categoryId);

        return $stmt->execute();
    }


    public function deleteIncome($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id AND type = 'income'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getIncomesByUserId($userId) {
        $query = "SELECT * FROM " . $this->table . " WHERE users_id = :users_id AND type = 'income'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":users_id", $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
