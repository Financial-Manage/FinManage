<?php 

class Category {
    public $id;
    public $name;
    public $description;
    public $type;
    public $userId; 

    public function __construct($id = null, $name = null, $description = null, $type = null, $userId = null) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->type = $type;
        $this->userId = $userId;
    }
}

interface CategoryDAOInterface {
    public function getAllCategories();
    public function getCategoryById($id);
    public function createCategory(Category $category);
    public function updateCategory(Category $category);
    public function deleteCategory($id);
    public function getCategoryByUserId($userId);
    public function getCategoryByType($type);
}


?>