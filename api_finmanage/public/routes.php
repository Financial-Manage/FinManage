<?php 
require_once __DIR__ . '../../controllers/UserController.php';

// Permitir requisições de qualquer origem (não recomendado para produção, ajuste conforme necessário)
header("Access-Control-Allow-Origin: *");

// Permitir métodos HTTP específicos
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Permitir cabeçalhos específicos
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Se for uma solicitação OPTIONS (verificação de pré-requisito CORS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$userController = new UserController();
$response = [];

print_r($_POST);
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = file_get_contents('php://input'); //pega conteudo da req
    $data = json_decode($input, true); //decodifica o JSON para um array associativo

    if ($data && isset($data["action"])) {
        switch ($data["action"]) {
            case "createUser":
                $response = $userController->createUser($data);
                break;
            case "updateUser":
                $id = $data["id"];
                $response = $userController->updateUser($id, $data);
                break;
            case "deleteUser":
                $id = $data["id"];
                $response = $userController->deleteUser($id);
                break;
            default:
                $response = ["status" => "error", "message" => "Ação não reconhecida."];
                break;
        }
    } else {
        $response = ["status" => "error", "message" => "Dados de entrada inválidos."];
    }
} elseif ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_GET["id"])) {
        $response = $userController->getUser($_GET["id"]);
    } else {
        $response = $userController->getAllUsers();
    }
}

header("Content-Type: application/json");
echo json_encode($response);
?>