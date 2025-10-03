<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../controller/EtapaController.php';
require_once __DIR__ . '/../model/Etapa.php';

$controller = new EtapaController();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        echo json_encode($controller->listar());
        break;

    case 'POST':
        $dados = json_decode(file_get_contents("php://input"));
        if (isset($dados->id_curso) && isset($dados->id_tipo_etapa)) {
            
            $etapa = new Etapa(
                //null,                      // id gerado pelo banco
                $dados->id_curso,
                $dados->id_tipo_etapa,
              
            );
            $sucesso = $controller->criar($etapa);
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
        $dados = json_decode(file_get_contents("php://input"));
        if (isset($dados->id_etapa)) {
            $sucesso = $controller->deletar($dados->id_etapa);
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