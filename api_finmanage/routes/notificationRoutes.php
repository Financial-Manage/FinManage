<?php
require_once __DIR__ . '../../controllers/NotificationController/NotificationController.php';

header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 

// Verifica se é uma solicitação OPTIONS e retorna uma resposta vazia
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

$notificationController = new NotificationController();
$response = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Lê o conteúdo da requisição
    $input = file_get_contents("php://input");

    // Decodifica o JSON
    $data = json_decode($input, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("Erro no JSON: " . json_last_error_msg());
        $response = ["status" => "error", "message" => "Erro ao decodificar o JSON: " . json_last_error_msg()];
        echo json_encode($response);
        exit;
    }

    if ($data && isset($data["action"])) {
        switch ($data["action"]) {
            case "createNotification":
                if (empty($data["message"]) || empty($data["type"])) {
                    $response = ["status" => "error", "message" => "Todos os campos são obrigatórios."];
                } else {
                    $response = $notificationController->createNotification($data);
                }
                break;

            case "markNotificationAsRead":
                if (empty($data["notification_id"])) {
                    $response = ["status" => "error", "message" => "O ID da notificação é obrigatório."];
                } else {
                    $response = $notificationController->markNotificationAsRead($data["notification_id"]);
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
    if (isset($_GET["users_id"])) {
        $response = $notificationController->getUnreadNotifications($_GET["users_id"]);
    } elseif (isset($_GET["id"])) {
        $response = $notificationController->getAllNotificationsByUser($_GET["id"]);
    } else {
        $response = ["status" => "error", "message" => "Parâmetros inválidos para GET."];
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
