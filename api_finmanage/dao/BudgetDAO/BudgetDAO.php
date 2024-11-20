<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Budget/Budget.php';

class BudgetDAO implements BudgetDAOInterface
{
    private $conn;
    private $table = "budgets";

    public function __construct()
    {
        $dataBase = new Database();
        $this->conn = $dataBase->connect();
    }

    public function getAllBudgets()
    {
        $query = "SELECT id, description, category_id, amount, alert_triggered FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getBudgetById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return new Budget(
                $result["id"],
                $result["amount"],
                $result["alertTriggered"] ?? false,
                // $result["userId"],
                $result["category_id"]
            );
        }
        return null;
    }

    public function createBudget(Budget $budget)
    {
        try {
            $query = "INSERT INTO " . $this->table . " (amount, alert_triggered, category_id, description) VALUES (:amount, :alertTriggered, :categoryId, :description)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":amount", $budget->amount);
            $stmt->bindParam(":alertTriggered", $budget->alertTriggered);
            $stmt->bindParam(":description", $budget->description);
            // $stmt->bindParam(":userId", $budget->userId, PDO::PARAM_INT);
            $stmt->bindParam(":categoryId", $budget->categoryId, PDO::PARAM_INT);

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

    public function updateBudget(Budget $budget)
    {
        $query = "UPDATE " . $this->table . " SET amount = :amount, alert_triggered = :alertTriggered, category_id = :categoryId, description = :description WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":amount", $budget->amount);
        $stmt->bindParam(":alertTriggered", $budget->alertTriggered);
        // $stmt->bindParam(":userId", $budget->userId, PDO::PARAM_INT);
        $stmt->bindParam(":categoryId", $budget->categoryId, PDO::PARAM_INT);
        $stmt->bindParam(":description", $budget->description);
        $stmt->bindParam(":id", $budget->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updateAlertStatus($id, $alertTriggered)
{
    $query = "UPDATE " . $this->table . " SET alert_triggered = :alertTriggered WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":alertTriggered", $alertTriggered, PDO::PARAM_BOOL);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    return $stmt->execute();
}

    public function deleteBudget($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getBudgetsByUserId($userId)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :userId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function checkBudgetAlert($budgetId)
    {
        $query = "SELECT SUM(t.amount) AS total_expenses 
            FROM transactions  t 
            JOIN budgets b ON t.category_id = b.category_id 
            WHERE b.id = :budgetId 
            AND t.type = 'expense'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":budgetId", $budgetId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_expenses'] ?? 0;
    }
}
