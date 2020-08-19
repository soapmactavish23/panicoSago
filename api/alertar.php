<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: POST');

# carrega as configuraçoes iniciais
require_once "config.php";

# inclui a classe
require_once "class/alerta.php";

# instancia o objeto
$_alerta = new alerta();

# define o retorno
$_RESPONSE = $_alerta->salvar();

# retorno no formato json
header("Content-Type: application/json; charset=UTF-8");
print json_encode( $_RESPONSE );
?>
