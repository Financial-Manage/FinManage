<?php
require_once __DIR__ . '../../controllers/CategoryController/CategoryController.php';

header("Access-Control-Allow-Origin: *"); // Permite qualquer origem    
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Permite métodos HTTP
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Permite cabeçalhos específicos

if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    exit;
}

$categoryController = new CategoryController();
$response = [];


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = file_get_contents("php://input"); //pega os dados bruto do corpo da req
    $data = json_decode($input, true); //converte esses dados de json para um array => associativo


    if ($data && isset($data["action"])) {
        switch ($data["action"]) {
            case "createCategory":
                if (empty($data["name"]) || empty($data["type"]) ||  empty($data["users_id"]) || empty($data["description"])) {
                    $response = ["status" => "error", "message" => "Todos os campos são obrigatórios: nome, tipo, usuário."];
                } else {

                    $response = $categoryController->createCategory($data);
                }
                break;
            case "updateCategory":
                $id = $data["id"];
                $response = $categoryController->updateCategory($id, $data);
                break;
            case "deleteCategory":
                $id = $data["id"];
                $response = $categoryController->deleteCategory($id);
                break;
            default:
                $response =  ["status" => "error", "message" => "Ação não reconhecida."];
                break;
        }
    } else {
        $response = ["status" => "error", "message" => "Dados de entrada inválidos."];
    }
} elseif ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_GET["id"])) {
        $response = $categoryController->getCategorybyId($_GET["id"]);
    } elseif (isset($_GET["users_id"])) {
        $response = $categoryController->getCategoriesByUser($_GET["users_id"]);
    } elseif (isset($_GET["type"])) {
        $response = $categoryController->getCategoryByType($_GET["type"]);
    } else {
        $response = $categoryController->getAllCategories();
    }
}

header('Content-Type: application/json');
echo json_encode($response);
