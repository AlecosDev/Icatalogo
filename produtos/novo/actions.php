<?php

session_start();
function validarCampos()
{
    $erros = [];

    if (!isset($_POST["descricao"]) && $_POST["descricao"] == "") {
        $erros[] = "O campo descrição é obrigatório";
    }

    if (!isset($_POST["peso"]) && $_POST["peso"] == "") {
        $erros[] = "O campo peso é obrigatório";
    } elseif (!is_numeric(str_replace(",", ".", $_POST["peso"]))) {
        $erros[] = "O campo peso deve ser um número";
    }

    if (!isset($_POST["quantidade"]) && $_POST["quantidade"] == "") {
        $erros[] = "O campo quantidade é obrigatório";
    } elseif (!is_numeric(str_replace(",", ".", $_POST["quantidade"]))) {
        $erros[] = "O campo quantidade deve ser um número";
    }

    if (!isset($_POST["cor"]) && $_POST["cor"] == "") {
        $erros[] = "O campo cor é obrigatório";
    }

    if (!isset($_POST["valor"]) && $_POST["valor"] == "") {
        $erros[] = "O campo valor é obrigatório";
    } elseif (!is_numeric(str_replace(",", ".", $_POST["valor"]))) {
        $erros[] = "O campo valor deve ser um número";
    }

    if (isset($_POST["desconto"]) && $_POST["desconto"] != "" && !is_numeric((str_replace(",", ".", $_POST["desconto"])))) {
        $erros[] = "O campo desconto deve ser um número";
    }

    if ($_FILES["foto"]["error"] == UPLOAD_ERR_NO_FILE) {
        $erros[] = "O campo foto é obrigátorio";
    } elseif ($_FILES["foto"]["error"] != UPLOAD_ERR_OK) {
        $erros[] = "Ops, houve um erro com o upload, verifique o arquivo e tente novamente.";
    } else {
        $imagemInfos = getimagesize($_FILES["foto"]["tmp_name"]);

        if (!$imagemInfos) {
            $erros[] = "O arquivo precisa ser uma imagem";
        } elseif ($_FILES["foto"]["size"] > 1024 * 1024 * 2) {
            $erros[] = "A foto não pode ser maior que 2MB";
        }

        $width = $imagemInfos[0];
        $height = $imagemInfos[1];
        if ($width != $height) {
            $erros[] = "A imagem precisa ser quadrada";
        }
    }
    if (!isset($_POST["categoria"]) && $_POST["categoria"] == "") {
        $erros[] = "O campo categoria é obrigatório";
    }

    return $erros;
}

function validarCamposEditar()
{
    $erros = [];

    if (!isset($_POST["descricao"]) && $_POST["descricao"] == "") {
        $erros[] = "O campo descrição é obrigatório";
    }
    if (!isset($_POST["peso"]) && $_POST["peso"] == "") {
        $erros[] = "O campo peso é obrigatório";
    } elseif (!is_numeric(str_replace(",", ".", $_POST["peso"]))) {
        $erros[] = "O campo peso deve ser um número";
    }
    if (!isset($_POST["quantidade"]) && $_POST["quantidade"] == "") {
        $erros[] = "O campo quantidade é obrigatório";
    } elseif (!is_numeric(str_replace(",", ".", $_POST["quantidade"]))) {
        $erros[] = "O campo quantidade deve ser um número";
    }
    if (!isset($_POST["cor"]) && $_POST["cor"] == "") {
        $erros[] = "O campo cor é obrigatório";
    }
    if (!isset($_POST["valor"]) && $_POST["valor"] == "") {
        $erros[] = "O campo valor é obrigatório";
    } elseif (!is_numeric(str_replace(",", ".", $_POST["valor"]))) {
        $erros[] = "O campo valor deve ser um número";
    }
    if (isset($_POST["desconto"]) && $_POST["desconto"] != "" && !is_numeric(str_replace(",", ".", $_POST["desconto"]))) {
        $erros[] = "O campo desconto deve ser um número";
    }
    if ($_FILES["foto"]["error"] != UPLOAD_ERR_NO_FILE) {
        $imagemInfos = getimagesize($_FILES["foto"]["tmp_name"]);
        if (!$imagemInfos) {
            $erros[] = "O arquivo precisa ser uma imagem";
        } elseif ($_FILES["foto"]["size"] > 1024 * 1024 * 2) {
            $erros[] = "A foto não pode ser maior que 2MB";
        }
        $width = $imagemInfos[0];
        $height = $imagemInfos[1];
        if ($width != $height) {
            $erros[] = "A imagem precisa ser quadrada";
        }
    }
    if (!isset($_POST["categoria"]) || $_POST["categoria"] == "") {
        $erros[] = "O campo categoria é obrigatório";
    }
    return $erros;
}

require("../../database/conexao.php");

switch ($_POST["acao"]) {

    case "inserir":
        $erros = validarCampos();
        if (count($erros) > 0) {

            $_SESSION["erros"] = $erros;

            header("location: index.php?erros=$erros");

            exit();
        }

        $nomeArquivo = $_FILES["foto"]["name"];

        $extensao = pathinfo($nomeArquivo, PATHINFO_EXTENSION);

        $novoNomeArquivo = md5(microtime()) . ".$extensao";

        move_uploaded_file($_FILES["foto"]["tmp_name"], "../imgs/$novoNomeArquivo");

        $descricao = $_POST["descricao"];
        $peso = str_replace(",", ".", $_POST["peso"]);
        $quantidade = $_POST["quantidade"];
        $cor = $_POST["cor"];
        $tamanho = $_POST["tamanho"];
        $valor = str_replace(",", ".", $_POST["valor"]);
        $desconto = $_POST["desconto"] != "" ? $_POST["desconto"] : 0;
        $categoriaId = $_POST["categoria"];

        var_dump($_POST);

        $sqlInsert = "INSERT INTO tbl_produto (descricao, peso, quantidade, cor, tamanho, valor, desconto, imagem, categoria_id) 
                          VALUES ('$descricao', $peso, $quantidade, '$cor', '$tamanho', '$valor', '$desconto', '$novoNomeArquivo', $categoriaId)";

        echo $sqlInsert;
        $resultado = mysqli_query($conexao, $sqlInsert) or die(mysqli_error($conexao));

        if ($resultado) {
            $mensagem = "Produto inserido com sucesso";
        } else {
            $mensagem = "Erro ao inserir o produto!";
        }

        $_SESSION["mensagem"] = $mensagem;
        header("location: ../index.php");

        break;

    case "deletar";

        $produtoId = $_POST["produtoId"];

        $sqlImagem = " SELECT imagem FROM tbl_produto WHERE id = $produtoId";
        $resultado = mysqli_query($conexao, $sqlImagem);
        $produto = mysqli_fetch_array($resultado);
        unlink("../imgs/" . $produto["imagem"]);

        $sql = "DELETE FROM tbl_produto WHERE id = $produtoId";
        $resultado = mysqli_query($conexao, $sql);

        if ($resultado) {
            $mensagem = "Produto excluido com sucesso";
        } else {
            $mensagem = "Erro ao excluir o produto!";
        }

        $_SESSION["mensagem"] = $mensagem;

        header("location: ../index.php");

        break;

    case "editar";

        $erros = validarCamposEditar();

        if (count($erros) > 0) {

            $_SESSION["erros"] = $erros;

            header("location: ../editar/index.php");

            exit();
        }

        $descricao = $_POST["descricao"];
        $peso = str_replace(",", ".", $_POST["peso"]);
        $quantidade = $_POST["quantidade"];
        $cor = $_POST["cor"];
        $tamanho = $_POST["tamanho"];
        $valor = str_replace(",", ".", $_POST["valor"]);
        $desconto = $_POST["desconto"] != "" ? $_POST["desconto"] : 0;
        $categoriaId = $_POST["categoria"];

        $sql = "UPDATE tbl_produto SET descricao = '$descricao', peso = $peso,
                quantidade = $quantidade, cor = '$cor', tamanho = '$tamanho',
                valor = $valor, desconto = $desconto, categoria_id = $categoriaId 
                WHERE id = $produtoId ";

        $resultado = mysqli_query($conexao, $sql) or die(mysqli_error($conexao));

        if ($resultado) {
            $mensagem = "Produto editado com sucesso.";
        } else {
            $mensagem = "Ops, problemas ao editar o produto.";
        }

        $_SESSION["mensagem"] = $mensagem;

        header("location: index.php");

        break;
}
