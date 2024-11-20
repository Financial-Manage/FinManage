<?php 
require_once __DIR__ . '/../controllers/NotificationController/NotificationController.php';

$notificationController = new NotificationController();

$data = [
    "message" => "Teste de notificação",
    "type" => "info",
    "status" => "unread",
    "budgets_id" => 1,
    "users_id" => 1, 
    "category_id" => 1 
];

$result = $notificationController->createNotification($data);
echo "Resultado da criação de notificação: ";
var_dump($result);


?>