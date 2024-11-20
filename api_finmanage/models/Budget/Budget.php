<?php 

class Budget {
    public $id;
    public $amount;
    public $alertTriggered;
    public $userId;
    public $categoryId;
    public $description;

    public function __construct($id = null, $amount = null, $alertTriggered = null, $userId = null, $categoryId = null, $description = null) {
        $this->id = $id;
        $this->amount = $amount;
        $this->alertTriggered = $alertTriggered;
        $this->userId = $userId;    
        $this->categoryId = $categoryId;
        $this->description = $description ;
    }

    public function formatAmount() {
        return number_format($this->amount, 2, ",", ".");
    }
}

interface BudgetDAOInterface {
    public function getAllBudgets();        
    public function getBudgetById($id);             
    public function createBudget(Budget $budget);   
    public function updateBudget(Budget $budget);  
    public function deleteBudget($id);              
    public function getBudgetsByUserId($userId);   
    public function checkBudgetAlert($budgetId);    
}


?>