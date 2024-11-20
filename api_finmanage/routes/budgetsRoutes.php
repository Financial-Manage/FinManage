<?php
require_once 'C:/xampp/htdocs/fin_manage/api_finmanage/controllers/BudgetController/BudgetController.php';

header("Access-Control-Allow-Origin: *"); // Permite qualquer origem    
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Permite métodos HTTP
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Permite cabeçalhos específicos

// Verifica se é uma solicitação OPTIONS e retorna uma resposta vazia
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

$budgetController = new BudgetController();
$response = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
   // Lê o conteúdo da requisição
   $input = file_get_contents("php://input");

   // Decodifica o JSON
   $data = json_decode($input, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("Erro no JSON: " . json_last_error_msg()); // Mostra o erro de parsing do JSON
        $response = ["status" => "error", "message" => "Erro ao decodificar o JSON: " . json_last_error_msg()];
        echo json_encode($response);
        exit;
    }

    
    if ($data && isset($data["action"])) {
        switch ($data["action"]) {
            case "checkBudgetAlert":
                if (empty($data["budget_id"])) {
                    $response = ["status" => "error", "message" => "O ID do orçamento é obrigatório."];
                } else {
                    $response = $budgetController->checkBudgetAlert($data["budget_id"]);
                }
                break;

            case "createBudget":
                if (empty($data["amount"]) || empty($data["category_id"]) || empty($data["description"])) {
                    $response = ["status" => "error", "message" => "Todos os campos são obrigatórios."];
                } else {
                    $response = $budgetController->createBudget($data);
                }
                break;

            case "updateBudget":
                $id = $data["id"];
                if (empty($data["category_id"])) {
                    $response = ["status" => "error", "message" => "Todos os campos são obrigatórios."];
                } else {
                    $response = $budgetController->updateBudget($id, $data);
                }
                break;

            case "deleteBudget":
                $id = $data["id"];
                $response = $budgetController->deleteBudget($id);
                break;
                // Outras ações de create, update, delete...
            default:
                $response =  ["status" => "error", "message" => "Ação não reconhecida."];
                break;
        }
    }  else {
        $response = ["status" => "error", "message" => "Dados de entrada inválidos."];
    }
}  elseif ($_SERVER["REQUEST_METHOD"] === "GET") {
    if(isset($_GET["id"])) {
        $response = $budgetController->getBudgetById($_GET["id"]);
    } elseif (isset($_GET["users_id"])) {
        $response = $budgetController->getBudgetsByUserId($_GET["users_id"]);
    } else {
        $response = $budgetController->getAllBudgets();
    }
}

header('Content-Type: application/json');
echo json_encode($response);
