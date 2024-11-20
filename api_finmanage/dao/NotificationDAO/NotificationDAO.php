<?php 
require_once __DIR__ . '../../../config/database.php';
require_once __DIR__ . '../../../models/Notification/Notification.php';

class NotificationDAO implements NotificationDAOInterface {
    private $conn;
    private $table = "notifications";

    public function __construct() {
        $dataBase = new Database();
        $this->conn = $dataBase->connect();
    }

    public function createNotification(Notification $notification) {
        $query = "INSERT INTO " . $this->table . "(message, type, status, created, transactions_id, budgets_id, category_id)
                  VALUES (:message, :type, :status, :created, :transactionId, :budgetId, :categoryId)";
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(":message", $notification->message);
        $stmt->bindParam(":type", $notification->type);
        $stmt->bindParam(":status", $notification->status);
        $stmt->bindParam(":created", $notification->created);
        $stmt->bindParam(":transactionId", $notification->transactionId, PDO::PARAM_INT); // Mesmo que seja NULL, o PDO lida com isso
        $stmt->bindParam(":budgetId", $notification->budgetId, PDO::PARAM_INT);
        // $stmt->bindParam(":userId", $notification->userId, PDO::PARAM_INT);
        $stmt->bindParam(":categoryId", $notification->categoryId, PDO::PARAM_INT);
    
        return $stmt->execute();
    }

    public function getUnreadNotificationsByUser($userId) {
        $query = "SELECT * FROM " . $this->table . " WHERE users_id = :userId AND status = 'unread'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function markAsRead($notificationId) {
        $query = "UPDATE " . $this->table . " SET status = 'read' WHERE id = :notificationId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":notificationId", $notificationId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}


?>