<?php 

class Income {
    public $id;
    public $userId;
    public $categoryId;
    public $amount;
    public $description;
    public $date;
    public $type = "income";

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

interface IncomeDAOInterface {
    public function getAllIncomes();
    public function getIncomeById($id);
    public function createIncome(Income $income);
    public function updateIncome(Income $income);
    public function deleteIncome($id);
    public function getIncomesByUserId($userId);
}

?>