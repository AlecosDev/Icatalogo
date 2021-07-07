<?php
session_start();
require("../../database/conexao.php");

function validarCampos()
{
    $erros = [];

    if (!isset($_POST["usuario"])  || $_POST["usuario"] == "") {
        $erros[] = "O campo usuário é obrigatório";
    }

    if (!isset($_POST["senha"])  || $_POST["senha"] == "") {
        $erros[] = "O campo senha é obrigatório";
    }

    return $erros;
}

switch ($_REQUEST["acao"]) {
    case "login":

        $erros = validarCampos();

        if (count($erros) > 0) {
            $_SESSION["erros"] = $erros;

            header("location: ../../produtos/index.php");
        }

        $usuario = $_POST["usuario"];
        $senha = $_POST["senha"];
        $sqlSelect = "SELECT * FROM tbl_administrador WHERE usuario = '$usuario'";

        $resultado = mysqli_query($conexao, $sqlSelect) or die(mysqli_error($conexao));
        $usuario = mysqli_fetch_array($resultado);

        if (!$usuario || !password_verify($senha, $usuario["senha"])) {
            $mensagem = "Usuários e/ou senha inválidos";
        } else {
            $_SESSION["usuarioId"] = $usuario["id"];
            $_SESSION["usuarioNome"] = $usuario["nome"];
        }
        $mensagem = "Bem vindo," . $usuario["nome"];
        header("location: ../../produtos/index.php");
        break;

    case "logout":

        session_destroy();
        header("location: ../../produtos/index.php");
        break;
}
