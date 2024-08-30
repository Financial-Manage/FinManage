<?php
// Defina os dados do usuário
$userData = [
    'username' => 'johndoe',
    'email' => 'johndoe@example.com',
    'name' => 'John',
    'lastname' => 'Doe',
    'password' => 'password123',
    'action' => 'createUser'
];

// Inicialize o cURL
$ch = curl_init();

// Defina a URL da API
$url = 'http://localhost/api_finmanage/public/routes.php';

// Defina as opções do cURL
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($userData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute a solicitação
$response = curl_exec($ch);

// Verifique se houve erro
if (curl_errno($ch)) {
    echo 'Erro: ' . curl_error($ch);
} else {
    // Exiba a resposta
    echo 'Resposta da API: ' . $response;
}

// Feche a conexão cURL
curl_close($ch);
?>