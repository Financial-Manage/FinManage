<?php 
require_once __DIR__ . '../../controllers/ExpenseController/ExpenseController.php';

header("Access-Control-Allow-Origin: *"); // Permite qualquer origem    
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Permite métodos HTTP
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Permite cabeçalhos específicos

// Verifica se é uma solicitação OPTIONS e retorna uma resposta vazia
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

$expenseController = new ExpenseController();
$response = [];

if($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = file_get_contents("php://input"); //pega os dados bruto do corpo da req
    $data = json_decode($input, true); //converte esses dados de json para um array => associativo

    if($data && isset($data["action"])) {
        switch ($data["action"]) {

            case "createExpense":
                if (empty($data["description"]) || empty($data["amount"]) || empty($data["category_id"])) {
                    $response = ["status" => "error", "message" => "Todos os campos são obrigatórios: nome, tipo, usuário."];
                } else {
                    $response = $expenseController->createExpense($data);
                }
                break;
            case "updateExpense":
                $id = $data["id"];
                $response = $expenseController->updateExpense($id, $data);
                break;
            case "deleteExpense":
                $id = $data["id"];
                $response = $expenseController->deleteExpense($id);
                break;
            default:
            $response =  ["status" => "error", "message" => "Ação não reconhecida."];
            break;
        }
    } else {
        $response = ["status" => "error", "message" => "Dados de entrada inválidos."];
    }
} elseif ($_SERVER["REQUEST_METHOD"] === "GET") {
    if(isset($_GET["id"])) {
        $response = $expenseController->getExpensebyId($_GET["id"]);
    } elseif (isset($_GET["users_id"])) { //verificar
        $response = $expenseController->getExpensesByUser($_GET["users_id"]);
    } else {
        $response = $expenseController->getAllExpenses();
    }
}

header('Content-Type: application/json');
echo json_encode($response);

?>