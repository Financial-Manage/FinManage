<?php 
require_once __DIR__ . '/../../dao/BudgetDAO/BudgetDAO.php';
require_once __DIR__ . '/../../models/Budget/Budget.php';
require_once __DIR__ . '/../NotificationController/NotificationController.php';


class BudgetController {
    private $budgetDAO;

    public function __construct() {
        $this->budgetDAO = new BudgetDAO();
    }

    public function createBudget($data)
    {
        $budget = new Budget(
            null, 
            $data["amount"], 
            $data["alertTriggered"] ?? false, 
            null,  // Sem userId por enquanto
            $data["category_id"],
            $data["description"]
        );
        return $this->budgetDAO->createBudget($budget);
    }

    public function updateBudget($id, $data)
    {
        $budget = $this->budgetDAO->getBudgetById($id);
        if ($budget) {
            $budget->amount = $data["amount"];
            $budget->alertTriggered = $data["alertTriggered"] ?? false;
            $budget->categoryId = $data["category_id"];
            $budget->description = $data["description"];
            return $this->budgetDAO->updateBudget($budget);
        }
        return ["status" => "error", "message" => "Orçamento não encontrado."];
    }

    public function getAllBudgets() {
        $budgets = $this->budgetDAO->getAllBudgets();
        if($budgets) {
            return $budgets;
        } else {
            return ["status" => "error", "message" => "Nenhuma orçamento encontrado"];
        }
    }

    public function getBudgetById($id) {
        $budget = $this->budgetDAO->getBudgetById($id);
        if($budget) {
            return $budget;
        } else {
            return ["status" => "error", "message" => "Orçamento não encontrado."];
        }
    }

    public function getBudgetsByUserId($userId) {
        $budgets = $this->budgetDAO->getBudgetsByUserId($userId);
        if($budgets) {
            return $budgets;
        } else {
            return ["status" => "error", "message" => "Orçamentos não encontrados."];
        }
    }


    public function deleteBudget($id) {
        if($this->budgetDAO->deleteBudget($id)) {
            return ["status" => "success", "message" => "Orçamento excluído com sucesso."];
        } else {
            return ["status" => "error", "message" => "Erro ao excluir orçamento."];
        }
    }

    public function checkBudgetAlert($budgetId) {
        $budget = $this->budgetDAO->getBudgetById($budgetId);

        if(!$budget) {
            return ["status" => "error", "message" => "Orçamento não encontrado."];
        }

        $totalExpenses = $this->budgetDAO->checkBudgetAlert($budgetId);

        $limit = 0.9 * $budget->amount;
        
        $notificationController = new NotificationController();

        if ($totalExpenses >= $limit) {
            $alertStatus = true;
            $this->budgetDAO->updateAlertStatus($budgetId, $alertStatus);
    
            $message = "";
            $type = "";
    
            if ($totalExpenses > $budget->amount) {
                $message = "Orçamento excedido para a categoria!";
                $type = "alert";
            } else {
                $message = "Atingiu 90% do orçamento!";
                $type = "warning";
            }
    
            // Cria a notificação
            $notificationController->createNotification([
                "message" => $message,
                "type" => $type,
                "status" => "unread",
                "budgets_id" => $budgetId,
                "users_id" => $budget->userId,
                "category_id" => $budget->categoryId
            ]);
    
            return [
                "status" => $type,
                "message" => $message,
                "total_expenses" => $totalExpenses
            ];
        }
    
        return ["status" => "ok", "message" => "Dentro do orçamento.", "total_expenses" => $totalExpenses];
    }
}

?>