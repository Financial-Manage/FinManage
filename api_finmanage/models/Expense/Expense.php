<?php 

class Expense {
    public $id;
    public $userId;
    public $categoryId;
    public $amount;
    public $description;
    public $date;
    public $type = "expense";

    public function __construct($id = null, $userId = null, $categoryId = null, $amount = null, $description = null, $date = null){
        
        $this->id = $id;
        $this->userId = $userId;
        $this->categoryId = $categoryId;
        $this->amount = $amount;
        $this->description = $description;
        $this->date = $date;
    }

    public function formatAmount() {
        return number_format($this->amount, 2, ",", ".");
    }
}

interface ExpenseDAOInterface {
    public function getAllExpenses();
    public function getExpenseById($id);
    public function createExpense(Expense $expense);
    public function updateExpense(Expense $expense);
    public function deleteExpense($id);
    public function getExpensesByUserId($userId);
}

?>