<?php

//declaramos os informações de conexão
const HOST = "localhost";
const USER = "root";
const PASSWORD = "SenhaForte123$";
const DATABASE = "icatalogo";

//fazemos a conexão com o mysql
$conexao = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
