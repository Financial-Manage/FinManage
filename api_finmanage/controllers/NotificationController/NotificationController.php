<?php
require_once __DIR__ . '/../../dao/NotificationDAO/NotificationDAO.php';
require_once __DIR__ . '/../../models/Notification/Notification.php';

class NotificationController {
    private $notificationDAO;

    public function __construct() {
        $this->notificationDAO = new NotificationDAO();
    }

    // Criar uma nova notificação
    public function createNotification($data) {
        // Garantir que os parâmetros essenciais existem
        if (empty($data["message"]) || empty($data["type"])) {
            return ["status" => "error", "message" => "Campos obrigatórios não preenchidos."];
        }
    
        // Se não houver transactions_id, passá-lo como NULL
        $transactions_id = isset($data["transactions_id"]) ? $data["transactions_id"] : NULL;
        $budgets_id = isset($data["budgets_id"]) ? $data["budgets_id"] : NULL;
        $users_id = isset($data["users_id"]) ? $data["users_id"] : NULL;
        $category_id = isset($data["category_id"]) ? $data["category_id"] : NULL;
    
        $notification = new Notification(
            null,
            $data["message"],
            $data["type"],
            $data["status"] ?? "unread", 
            $transactions_id,
            $budgets_id, // Agora com NULL como valor padrão
            $users_id,
            $category_id
        );
    
        $createResult = $this->notificationDAO->createNotification($notification);
        if ($createResult) {
            return ["status" => "success", "message" => "Notificação criada com sucesso."];
        } else {
            return ["status" => "error", "message" => "Erro ao criar notificação."];
        }
    }
    
    

    // Buscar notificações não lidas de um usuário
    public function getUnreadNotifications($userId) {
        $notifications = $this->notificationDAO->getUnreadNotificationsByUser($userId);

        if ($notifications) {
            return $notifications;
        }

        return ["status" => "error", "message" => "Nenhuma notificação não lida encontrada."];
    }

    // Marcar uma notificação como lida
    public function markNotificationAsRead($notificationId) {
        if ($this->notificationDAO->markAsRead($notificationId)) {
            return ["status" => "success", "message" => "Notificação marcada como lida."];
        }

        return ["status" => "error", "message" => "Erro ao marcar a notificação como lida."];
    }

    // Buscar todas as notificações de um usuário (lidas e não lidas)
    public function getAllNotificationsByUser($userId) {
        $notifications = $this->notificationDAO->getUnreadNotificationsByUser($userId); // Você pode criar um método dedicado para todas as notificações no DAO

        if ($notifications) {
            return $notifications;
        }

        return ["status" => "error", "message" => "Nenhuma notificação encontrada."];
    }
}
?>
