<?php
header("Content-Type: application/json; charset=UTF-8");

require_once "CursoController.php";
require_once "Curso.php";

$controller = new CursoController();

$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {
    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data["nm_curso"], $data["ds_curso"], $data["id_usuario"])) {
            $curso = new Curso();
            $curso->setNmCurso($data["nm_curso"]);
            $curso->setDsCurso($data["ds_curso"]);
            $curso->setIdUsuario($data["id_usuario"]);

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
        break;

    case "GET":
        if (isset($_GET["id"])) {
            echo json_encode($controller->buscarCurso($_GET["id"]));
        } else {
            echo json_encode($controller->listarCursos());
        }
        break;

    case "PUT":
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data["id_curso"], $data["nm_curso"], $data["ds_curso"])) {
            $curso = new Curso();
            $curso->setIdCurso($data["id_curso"]);
            $curso->setNmCurso($data["nm_curso"]);
            $curso->setDsCurso($data["ds_curso"]);

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
        break;

    case "DELETE":
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
        break;

    default:
        http_response_code(405);
        echo json_encode(["status" => "error", "message" => "Método não permitido"]);
        break;
}