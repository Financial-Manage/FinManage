<?php 
require_once __DIR__ . '../../../dao/UserDAO/UserDAO.php';
require_once __DIR__ . '../../../models/User/User.php';

class UserController {
    private $userDao;

    public function __construct() {
        $this->userDao = new UserDAO();
    }

    public function createUser($userData) {
        $user = new User();
        $user->username = $userData["username"];
        $user->email = $userData["email"];
        $user->name = $userData["name"];
        $user->lastname = $userData["lastname"];
        $user->password = $user->generatePassword($userData["password"]);

        if ($this->userDao->isEmailRegistered($user->email)) {
            return ["status" => "error", "message" => "Email já registrado."];
        }

        $this->userDao->createUser($user);
        return ["status" => "success", "message" => "Usuário criado com sucesso!"];
    }

    public function loginUser($userData) {
        $user = $this->userDao->getUserByEmail($userData["email"]);
        if (!$user || !password_verify($userData["password"], $user->password)) {
            return ["status" => "error", "message" => "Credenciais inválidas."];
        }

        // Gera token de sessão
        $user->sessionToken = bin2hex(random_bytes(16));
        $user->tokenExpiresAt = date("Y-m-d H:i:s", strtotime("+1 hour"));
        $this->userDao->updateUserSession($user);

        return [
            "status" => "success",
            "message" => "Login bem-sucedido.",
            "sessionToken" => $user->sessionToken
        ];
    }

    public function checkSession($token) {
        $user = $this->userDao->getUserByToken($token);
        if (!$user || !$user->isSessionTokenValid()) {
            return ["status" => "error", "message" => "Sessão inválida ou expirada."];
        }
        return ["status" => "success", "message" => "Sessão válida."];
    }

    public function logoutUser($token) {
        $user = $this->userDao->getUserByToken($token);
        if ($user) {
            $user->sessionToken = null;
            $user->tokenExpiresAt = null;
            $this->userDao->updateUserSession($user);
        }
        return ["status" => "success", "message" => "Logout realizado com sucesso."];
    }

    public function getUser($id) {
        $user = $this->userDao->getUserById($id);
        if ($user) {
            return $user;
        } else {
            return ["status" => "error", "message" => "Usuário não encontrado."];
        }
    }

    public function updateUser($id, $userData) {
        $user = $this->userDao->getUserById($id);
        if (!$user) {
            return ["status" => "error", "message" => "Usuário não encontrado."];
        }

        $user->username = $userData["username"] ?? $user->username;
        $user->email = $userData["email"] ?? $user->email;
        $user->name = $userData["name"] ?? $user->name;
        $user->lastname = $userData["lastname"] ?? $user->lastname;
        if (!empty($userData["password"])) {
            $user->password = $user->generatePassword($userData["password"]);
        }

        $this->userDao->updateUser($user);
        return ["status" => "success", "message" => "Usuário atualizado com sucesso!"];
    }

    public function deleteUser($id) {
        if ($this->userDao->deleteUser($id)) {
            return ["status" => "success", "message" => "Usuário excluído com sucesso."];
        } else {
            return ["status" => "error", "message" => "Erro ao excluir usuário."];
        }
    }

    public function getAllUsers() {
        $users = $this->userDao->getAllUsers();
        return $users ?: ['status' => 'error', 'message' => 'Nenhum usuário encontrado'];
    }
}
?>
