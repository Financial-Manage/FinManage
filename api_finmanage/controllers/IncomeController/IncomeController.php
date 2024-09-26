<?php 
require_once __DIR__ . '../../../dao/IncomeDAO/IncomeDAO.php';
require_once __DIR__ . '../../../models/Income/Income.php';

class IncomeController {
    private $incomeDao;

    public function __construct() {
        $this->incomeDao = new IncomeDAO();
    }

    public function createIncome($incomeData) {
        $income = new Income();
        $income->userId = $incomeData["users_id"];
        $income->categoryId = $incomeData["category_id"];
        $income->amount = $incomeData["amount"];
        $income->description = $incomeData["description"];
        $income->date = $incomeData["date"];

        if($this->incomeDao->createIncome($income)) {
            return ["status" => "success", "message" => "Receita cadastrada com sucesso!"];
        } else {
            return ["status" => "error", "message" => "Erro ao cadastrar receita."];
        }
    }

    public function getAllIncomes() {
        $incomes = $this->incomeDao->getAllIncomes();
        if($incomes) {
            return $incomes;
        } else {
            return ["status" => "error", "message" => "Nenhuma receita encontrada"];
        }
    }


    public function getIncomebyId($id) {
        $income = $this->incomeDao->getIncomeById($id);
        if($income) {
            return $income;
        } else {
            return ["status" => "error", "message" => "Receita não encontrada."];
        }
    }

    public function updateIncome($id, $incomeData) {
        if (!is_numeric($id)) {
            return ["status" => "error", "message" => "ID inválido"];
        }

        $income = $this->incomeDao->getIncomeById($id);

        if(!$income) {
            return ["status" => "error", "message" => "Receita não encontrada"];
        } else {
            $income->amount = $incomeData["amount"] ?? $income->amount;
            $income->description = $incomeData["description"] ?? $income->description;
            $income->date = $incomeData["date"] ?? $income->date;
            $income->categoryId = $incomeData["category_id"] ?? $income->categoryId;

            
            if($this->incomeDao->updateIncome($income)) {
                return ["status" => "success", "message" => "Receita atualizada com sucesso!"];
            } else {
                return ["status" => "error", "message" => "Erro ao atualizar receita."];
            }
        }
    }

    public function deleteIncome($id) {
        if($this->incomeDao->deleteIncome($id)) {
            return ["status" => "success", "message" => "Receita excluída com sucesso."];
        } else {
            return ["status" => "error", "message" => "Erro ao excluir receita."];
        }
    }

    public function getIncomesByUser($userId) {
        $incomes = $this->incomeDao->getIncomesByUserId($userId);

        if($incomes) {
            return $incomes;
        } else {
            return ["status" => "error", "message" => "Nenhuma receita encontrada"];
        }

    }
}



?>