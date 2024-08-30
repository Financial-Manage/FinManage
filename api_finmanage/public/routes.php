<?php 
require_once __DIR__ . '../../controllers/UserController.php';

$userController = new UserController();

if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"])) {
    switch($_POST["action"]) {
        case "createUser":
            $response = $userController->createUser($_POST);
            break;
        case "updateUser":
            $id = $_POST["id"];
            $response = $userController->updateUser($id, $_POST);
            break;
        case "deleteUser":
            $id = $_POST["id"];
            $response = $userController->deleteUser($id);
            break;
    }       
} elseif($_SERVER["REQUEST_METHOD"] === "GET") {
    if(isset($_GET["id"])) {
        $response = $userController->getUser($_GET["id"]);
    } else {
        $response = $userController->getAllUsers();
    }
}

header("Content-Type: application/json");
echo json_encode($response);
?>