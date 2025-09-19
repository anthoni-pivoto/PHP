<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../controller/CursoController.php';
require_once __DIR__ . '/../model/Curso.php';

$controller = new CursoController();

$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {

    case "POST":
        try {
            $data = json_decode(file_get_contents("php://input")); // sem ,true → objeto
            if (!empty($data->nm_curso) && !empty($data->ds_curso) && !empty($data->id_usuario)) {
                $curso = new Curso($data->id_usuario, $data->nm_curso, $data->ds_curso);

                if ($controller->criarCurso($curso)) {
                    echo json_encode(["status" => "success", "message" => "Curso criado com sucesso"]);
                } else {
                    http_response_code(500);
                    echo json_encode(["status" => "error", "message" => "Erro ao criar curso"]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["status" => "error", "message" => "Dados incompletos"]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Erro ao criar curso: " . $e->getMessage()]);
        }
        break;

    case "GET":
        try {
            if (isset($_GET["id"])) {
                $curso = $controller->buscarCurso($_GET["id"]);
                if ($curso) {
                    echo json_encode($curso);
                } else {
                    http_response_code(404);
                    echo json_encode(["status" => "error", "message" => "Curso não encontrado"]);
                }
            } else {
                echo json_encode($controller->listarCursos());
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Erro ao buscar cursos: " . $e->getMessage()]);
        }
        break;

    case "PUT":
        try {
            $data = json_decode(file_get_contents("php://input")); // sem ,true → objeto
            if (!empty($data->id_curso) && !empty($data->nm_curso) && !empty($data->ds_curso)) {
                $curso = new Curso($data->nm_curso, $data->ds_curso, null, $data->id_curso);

                if ($controller->atualizarCurso($curso)) {
                    echo json_encode(["status" => "success", "message" => "Curso atualizado com sucesso"]);
                } else {
                    http_response_code(500);
                    echo json_encode(["status" => "error", "message" => "Erro ao atualizar curso"]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["status" => "error", "message" => "Dados incompletos"]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Erro ao atualizar curso: " . $e->getMessage()]);
        }
        break;

    case "DELETE":
        try {
            if (isset($_GET["id"])) {
                if ($controller->deletarCurso($_GET["id"])) {
                    echo json_encode(["status" => "success", "message" => "Curso excluído com sucesso"]);
                } else {
                    http_response_code(500);
                    echo json_encode(["status" => "error", "message" => "Erro ao excluir curso"]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["status" => "error", "message" => "ID não informado"]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Erro ao excluir curso: " . $e->getMessage()]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["status" => "error", "message" => "Método não permitido"]);
        break;
}