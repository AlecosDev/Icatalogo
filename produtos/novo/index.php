<?php

require("../../database/conexao.php");

$sql = " SELECT * FROM tbl_produto ";

$resultado = mysqli_query($conexao, $sql);

session_start();
if (!isset($_SESSION["usuarioId"])) {
  $_SESSION["mensagem"] = "Acesso negado, você precisa logar.";
  header("location: ../index.php");
}

$sql = " SELECT * FROM tbl_categoria ";

$resultado = mysqli_query($conexao, $sql);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../styles-global.css" />
  <link rel="stylesheet" href="./novo.css" />
  <title>Administrar Produtos</title>
</head>

<body>
  <?php
  include("../../componentes/header/header.php")
  ?>
  <div class="content">
    <section class="produtos-container">
      <main>
        <form class="form-produto" method="POST" action="actions.php" enctype="multipart/form-data">
          <input type="hidden" name="acao" value="inserir" />
          <h1>Cadastro de produto</h1>
          <ul>
            <?php
            if (isset($_SESSION["erros"])) {
              foreach ($_SESSION["erros"] as $erro) {
            ?>
                <li><?= $erro ?></li>
            <?php
              }
              unset($_SESSION["erros"]);
            }
            ?>
          </ul>
          <div class="input-group span2">
            <label for="descricao">Descrição</label>
            <input type="text" name="descricao" id="descricao" placeholder="Digite a descrição do produto" required>
          </div>
          <div class="input-group">
            <label for="peso">Peso</label>
            <input type="text" name="peso" id="peso" placeholder="Digite o peso" required>
          </div>
          <div class="input-group">
            <label for="quantidade">Quantidade</label>
            <input type="text" name="quantidade" id="quantidade" placeholder="Digite a quantidade" required>
          </div>
          <div class="input-group">
            <label for="cor">Cor</label>
            <input type="text" name="cor" id="cor" placeholder="Digite a cor" required>
          </div>
          <div class="input-group">
            <label for="tamanho">Tamanho</label>
            <input type="text" name="tamanho" id="tamanho" placeholder="Digite o tamanho">
          </div>
          <div class="input-group">
            <label for="valor">Valor</label>
            <input type="text" name="valor" id="valor" placeholder="Digite o valor" required>
          </div>
          <div class="input-group">
            <label for="desconto">Desconto</label>
            <input type="text" name="desconto" id="desconto" placeholder="Digite o desconto">
          </div>
          <div class="input-group">
            <label for="categoria">Categorias</label>
            <select type="text" name="categoria" id="categoria" required>
              <option value="">SELECIONE</option>
              <?php
              while ($categoria = mysqli_fetch_array($resultado)) {
              ?>
                <option value="<?= $categoria["id"] ?>">
                  <?= $categoria["descricao"] ?>
                </option>
              <?php
              }
              ?>
            </select>
          </div>
          <div class="input-group">
            <label for="foto">Foto</label>
            <input type="file" name="foto" accept="image/*">
          </div>
          <button onclick="javascript:window.location.href = '../'">Cancelar</button>
          <button>Salvar</button>
        </form>
      </main>
    </section>
  </div>
  <footer>
    SENAI 2021 - Todos os direitos reservados
  </footer>
</body>

</html>