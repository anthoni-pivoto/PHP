<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../controller/UsuarioController.php';
require_once __DIR__ . '/../model/Usuario.php';

$controller = new UsuarioController();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        echo json_encode($controller->listar());
        break;

    case 'POST':
        $dados = json_decode(file_get_contents("php://input"));
        if (isset($dados->nm_usuario) && isset($dados->email_usuario) && isset($dados->pwd_usuario)) {
            // ordem correta: id, nome, email, senha, email_contato, numero_telefone
            $usuario = new Usuario(
                null,                       // id gerado pelo banco
                $dados->nm_usuario,
                $dados->email_usuario,
                $dados->pwd_usuario,
                $dados->email_contato ?? null,
                $dados->numero_telefone ?? null
            );
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

    case 'PUT':
        $dados = json_decode(file_get_contents("php://input"));
        if (isset($dados->id_usuario) && isset($dados->nm_usuario) && isset($dados->email_usuario)) {
            $usuario = new Usuario(
                $dados->id_usuario,
                $dados->nm_usuario,
                $dados->email_usuario,
                $dados->pwd_usuario ?? null,
                $dados->email_contato ?? null,
                $dados->numero_telefone ?? null
            );
            $sucesso = $controller->atualizar($usuario);
            if ($sucesso) {
                echo json_encode(["sucesso" => true]);
            } else {
                http_response_code(404); // se não encontrou registro
                echo json_encode(["erro" => "Nenhum registro atualizado"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["erro" => "Dados incompletos para atualizar"]);
        }
        break;

    case 'DELETE':
        $dados = json_decode(file_get_contents("php://input"));
        if (isset($dados->id_usuario)) {
            $sucesso = $controller->deletar($dados->id_usuario);
            if ($sucesso) {
                echo json_encode(["sucesso" => true]);
            } else {
                http_response_code(500);
            }
        } else {
            echo json_encode(["erro" => "Informe o ID"]);
        }
        break;

    default:
        echo json_encode(["erro" => "Método não permitido"]);
}
