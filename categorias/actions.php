<?php
session_start();

require("../database/conexao.php");

function validarCampos()
{
    $erros = [];

    if (!isset($_POST["descricao"]) || $_POST["descricao"] == "") {
        $erros[] = "O campo descrição é obrigatório";
    }

    return $erros;
}

switch ($_POST["acao"]) {

    case "inserir":

        $erros = validarCampos();

        if (count($erros) > 0) {
            $_SESSION["mensagem"] = $erros[0];

            header("location: index.php");

            exit();
        }

        $descricao = $_POST["descricao"];

        $sql = " INSERT INTO tbl_categoria (descricao) VALUES ('$descricao') ";

        $resultado = mysqli_query($conexao, $sql) or die(mysqli_error($conexao));

        if ($resultado) {
            $_SESSION["mensagem"] = "Categoria adicionada com sucesso!";
            $tipoMensagem = "Sucesso";
        } else {
            $_SESSION["mensagem"] = "Erro ao adicionar nova categoria!";
            $tipoMensagem = "Erro";
        }

        break;

    case "deletar":

        $categoriaId = $_POST["categoriaId"];

        $sqlDeDelecao = " DELETE FROM tbl_categoria WHERE id = $categoriaId ";
        $resultado = mysqli_query($conexao, $sqlDelete);

        if ($resultado) {
            $_SESSION["mensagem"] = "Categoria excluída com sucesso!";
            $tipoMensagem = "sucesso";
        } else {
            $_SESSION["mensagem"] = "Erro ao excluir a categoria!";
            $tipoMensagem = "erro";
        }

        break;
}
header("location: index.php?");
