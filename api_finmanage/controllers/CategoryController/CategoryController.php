<?php 
require_once __DIR__ . '../../../dao/CategoryDAO/CategoryDAO.php';
require_once __DIR__ . '../../../models/Category/Category.php';

class CategoryController {
    private $categoryDao;

    public function __construct() {
        $this->categoryDao = new CategoryDAO();
    }

    public function getAllCategories() {
        $categories = $this->categoryDao->getAllCategories();
        if($categories) {
            return $categories;
        } else {
            return ["status" => "error", "message" => "Nenhuma categoria encontrada"];
        }
    }

    public function createCategory($categoryData) {
        $category = new Category();
        $category->name = $categoryData["name"];
        $category->description = $categoryData["description"];
        $category->type = $categoryData["type"];
        $category->userId = isset($categoryData["users_id"]) ? (int)$categoryData["users_id"] : null;

        if($this->categoryDao->createCategory($category)) {
            return ["status" => "success", "message" => "Categoria cadastrada com sucesso!"];
        } else {
            return ["status" => "error", "message" => "Erro ao cadastrar categoria."];
        }
    }

    public function getCategoryById($id) {
        $category = $this->categoryDao->getCategoryById($id);
        if($category) {
            return $category;
        } else {
            return ["status" => "error", "message" => "Categoria não encontrada."];
        }
    }

    public function updateCategory($id, $categoryData) {
        if (!is_numeric($id)) {
            return ["status" => "error", "message" => "ID inválido"];
        }

        $category = $this->categoryDao->getCategoryById($id);

        if(!$category) {
            return ["status" => "error", "message" => "Categoria não encontrada"];
        } else {
            $category->name = $categoryData["name"] ?? $category->name;
            $category->description = $categoryData["description"] ?? $category->description;
            $category->type = $categoryData["type"] ?? $category->type;

            
            if($this->categoryDao->updateCategory($category)) {
                return ["status" => "success", "message" => "Categoria atualizada com sucesso!"];
            } else {
                return ["status" => "error", "message" => "Erro ao atualizar categoria."];
            }
        }
    }

    public function deleteCategory($id) {
        if($this->categoryDao->deleteCategory($id)) {
            return ["status" => "success", "message" => "Categoria excluída com sucesso."];
        } else {
            return ["status" => "error", "message" => "Erro ao excluir categoria."];
        }
    }

    public function getCategoriesByUser($userId) {
        $categories = $this->categoryDao->getCategoryByUserId($userId);

        if($categories) {
            return $categories;
        } else {
            return ["status" => "error", "message" => "Nenhuma categoria encontrada"];
        }
    }

    public function getCategoryByType($type) {
        $categories = $this->categoryDao->getCategoryByType($type);

        if($categories) {
            return $categories;
        } else {
            return ["status" => "error", "message" => "Nenhuma categoria encontrada"];
        }
    }
}

?>