<?php
require_once __DIR__ . '../../controllers/UserController/UserController.php';

header("Access-Control-Allow-Origin: *"); // Permite qualquer origem    
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Permite métodos HTTP
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Permite cabeçalhos específicos

if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    exit;
}

$userController = new UserController();
$response = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = file_get_contents("php://input"); // Obtém os dados brutos do corpo da requisição
    $data = json_decode($input, true); // Converte os dados de JSON para um array associativo

    if ($data && isset($data["action"])) {
        switch ($data["action"]) {
            case "register":
                if (
                    empty($data["username"]) || 
                    empty($data["email"]) || 
                    empty($data["name"]) || 
                    empty($data["lastname"]) || 
                    empty($data["password"])
                ) {
                    $response = ["status" => "error", "message" => "Todos os campos são obrigatórios: username, email, name, lastname, password."];
                } else {
                    $response = $userController->createUser($data);
                }
                break;

            case "login":
                if (empty($data["email"]) || empty($data["password"])) {
                    $response = ["status" => "error", "message" => "Email e senha são obrigatórios."];
                } else {
                    $response = $userController->loginUser($data); // Login será implementado no controlador
                }
                break;

            case "updateUser":
                if (empty($data["id"])) {
                    $response = ["status" => "error", "message" => "ID do usuário é obrigatório para atualização."];
                } else {
                    $response = $userController->updateUser($data["id"], $data);
                }
                break;

            case "deleteUser":
                if (empty($data["id"])) {
                    $response = ["status" => "error", "message" => "ID do usuário é obrigatório para exclusão."];
                } else {
                    $response = $userController->deleteUser($data["id"]);
                }
                break;

            case "checkSession":
                if (empty($data["token"])) {
                    $response = ["status" => "error", "message" => "Token de sessão é obrigatório."];
                } else {
                    $response = $userController->checkSession($data["token"]);
                }
                break;

            case "logout":
                if (empty($data["token"])) {
                    $response = ["status" => "error", "message" => "Token de sessão é obrigatório para logout."];
                } else {
                    $response = $userController->logoutUser($data["token"]);
                }
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
    } elseif (isset($_GET["all"])) {
        $response = $userController->getAllUsers();
    } else {
        $response = ["status" => "error", "message" => "Requisição inválida. Nenhum parâmetro fornecido."];
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
