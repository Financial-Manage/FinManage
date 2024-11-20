<?php 
require_once __DIR__ . '../../../dao/ExpenseDAO/ExpenseDAO.php';
require_once __DIR__ . '../../../models/Expense/Expense.php';

class ExpenseController {
    private $expenseDao;

    public function __construct() {
        $this->expenseDao = new ExpenseDAO();
    }

    public function createExpense($ExpenseData) {
        $Expense = new Expense();
        $Expense->userId = $ExpenseData["users_id"];
        $Expense->categoryId = $ExpenseData["category_id"];
        $Expense->amount = $ExpenseData["amount"];
        $Expense->description = $ExpenseData["description"];
        $Expense->date = $ExpenseData["date"];

        if($this->expenseDao->createExpense($Expense)) {
            return ["status" => "success", "message" => "Receita cadastrada com sucesso!"];
        } else {
            return ["status" => "error", "message" => "Erro ao cadastrar receita."];
        }
    }

    public function getAllExpenses() {
        $expenses = $this->expenseDao->getAllExpenses();
        if($expenses) {
            return $expenses;
        } else {
            return ["status" => "error", "message" => "Nenhuma receita encontrada"];
        }
    }


    public function getExpensebyId($id) {
        $Expense = $this->expenseDao->getExpenseById($id);
        if($Expense) {
            return $Expense;
        } else {
            return ["status" => "error", "message" => "Receita não encontrada."];
        }
    }

    public function updateExpense($id, $expenseData) {
        $expense = $this->expenseDao->getExpenseById($id);
        if(!$expense) {
            return ["status" => "error", "message" => "Receita não encontrada"];
        } else {
            $expense->amount = $expenseData["amount"] ?? $expense->amount;
            $expense->description = $expenseData["description"] ?? $expense->description;
            $expense->date = $expenseData["date"] ?? $expense->date;
            $expense->categoryId = $expenseData["category_id"] ?? $expense->categoryId;

            if($this->expenseDao->updateExpense($expense)) {
                return ["status" => "success", "message" => "Receita atualizada com sucesso!"];
            } else {
                return ["status" => "error", "message" => "Erro ao atualizar receita."];
            }
        }
    }

    public function deleteExpense($id) {
        if($this->expenseDao->deleteExpense($id)) {
            return ["status" => "success", "message" => "Receita excluída com sucesso."];
        } else {
            return ["status" => "error", "message" => "Erro ao excluir receita."];
        }
    }

    public function getExpensesByUser($userId) {
        $Expenses = $this->expenseDao->getExpensesByUserId($userId);

        if($Expenses) {
            return $Expenses;
        } else {
            return ["status" => "error", "message" => "Nenhuma receita encontrada"];
        }

    }
}



?>