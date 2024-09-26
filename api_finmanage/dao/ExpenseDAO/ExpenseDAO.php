<?php
require_once __DIR__ . '../../../config/database.php';
require_once __DIR__ . '../../../models/Expense/Expense.php';


class ExpenseDAO implements ExpenseDAOInterface
{
    private $conn;
    private $table = "transactions";

    public function __construct()
    {
        $dataBase = new Database();
        $this->conn = $dataBase->connect();
    }

    public function getAllExpenses()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE type = 'expense'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getExpenseById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id AND type = 'expense'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return new Expense(
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

    public function createExpense(Expense $expense)
    {
        $query = "INSERT INTO " . $this->table ." (
        amount, description, date, type, users_id, category_id
        ) VALUES (
         :amount, :description, :date, :type, :users_id, :category_id
        )";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":amount", $expense->amount);
        $stmt->bindParam(":description", $expense->description);
        $stmt->bindParam(":date", $expense->date);
        $stmt->bindParam(":type", $expense->type);
        $stmt->bindParam(":users_id", $expense->userId);
        $stmt->bindParam(":category_id", $expense->categoryId);

        return $stmt->execute();
    }

    public function updateExpense(Expense $expense) {
        $query = "UPDATE " . $this->table . " SET 
        amount = :amount, description = :description, date = :date, type = :type, users_id = :users_id, category_id = :category_id 
        WHERE id = :id AND type = 'expense'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $expense->id, PDO::PARAM_INT);
        $stmt->bindParam(":amount", $expense->amount);
        $stmt->bindParam(":description", $expense->description);
        $stmt->bindParam(":date", $expense->date);
        $stmt->bindParam(":type", $expense->type);
        $stmt->bindParam(":users_id", $expense->userId);
        $stmt->bindParam(":category_id", $expense->categoryId);

        return $stmt->execute();
    }


    public function deleteExpense($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id AND type = 'expense'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getExpensesByUserId($userId) {
        $query = "SELECT * FROM " . $this->table . " WHERE users_id = :users_id AND type = 'expense'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":users_id", $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
