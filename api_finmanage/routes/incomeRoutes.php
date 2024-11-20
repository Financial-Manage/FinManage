<?php 
require_once __DIR__ . '../../controllers/IncomeController/IncomeController.php';

header("Access-Control-Allow-Origin: *"); // Permite qualquer origem    
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Permite métodos HTTP
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Permite cabeçalhos específicos

// Verifica se é uma solicitação OPTIONS e retorna uma resposta vazia
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

$incomeController = new IncomeController();
$response = [];

if($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = file_get_contents("php://input"); //pega os dados bruto do corpo da req
    $data = json_decode($input, true); //converte esses dados de json para um array => associativo

    if($data && isset($data["action"])) {
        switch ($data["action"]) {

            case "createIncome":
                $response = $incomeController->createIncome($data);
                break;
            case "updateIncome":
                $id = $data["id"];
                $response = $incomeController->updateIncome($id, $data);
                break;
            case "deleteIncome":
                $id = $data["id"];
                $response = $incomeController->deleteIncome($id);
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
        $response = $incomeController->getIncomebyId($_GET["id"]);
    } elseif (isset($_GET["users_id"])) {
        $response = $incomeController->getIncomesByUser($_GET["users_id"]);
    } else {
        $response = $incomeController->getAllIncomes();
    }
}

header('Content-Type: application/json');
echo json_encode($response);

?>