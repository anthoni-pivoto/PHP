<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/../controller/UsuarioController.php';

$controller = new UsuarioController();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        echo json_encode($controller->listar());
        break;

    case 'POST':
        $dados = json_decode(file_get_contents("php://input"));
        if (isset($dados->nm_usuario) && isset($dados->email_usuario) && isset($dados->pwd_usuario)) {
            $usuario = new Usuario(null, $dados->nm_usuario, $dados->email_usuario, $dados->pwd_usuario);
            $sucesso = $controller->criar($usuario);
            if ($sucesso) {
                http_response_code(201); // Created
                echo json_encode(["sucesso" => true]);
            } else {
                http_response_code(500);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(["erro" => "Dados incompletos"]);
        }
        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            echo json_encode(["sucesso" => $controller->deletar($_GET['id'])]);
        } else {
            echo json_encode(["erro" => "Informe o ID"]);
        }
        break;

    default:
        echo json_encode(["erro" => "Método não permitido"]);
}