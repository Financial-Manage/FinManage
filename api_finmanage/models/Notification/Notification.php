<?php

class Notification {
    public $id;
    public $message;
    public $type; # 'warning', 'alert', 'info', etc.
    public $status; # 'unread', 'read'
    public $created;
    public $transactionId;
    public $budgetId;
    public $userId;
    public $categoryId;

    public function __construct(
        $id = null,
        $message = null,
        $type = null,
        $status = 'unread',
        $created = null,
        $transactionId = null,
        $budgetId = null,
        $userId = null,
        $categoryId = null
    ) {
        $this->id = $id;
        $this->message = $message;
        $this->type = $type;
        $this->status = $status;
        $this->created = $created ?? date('Y-m-d H:i:s');
        $this->transactionId = $transactionId;
        $this->budgetId = $budgetId;
        $this->userId = $userId;
        $this->categoryId = $categoryId;
    }
}

interface NotificationDAOInterface {
    public function createNotification(Notification $notification);
    public function getUnreadNotificationsByUser($userId);
    public function markAsRead($notificationId);
}