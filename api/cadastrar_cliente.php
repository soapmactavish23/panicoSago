<?php
# carrega as configuraçoes iniciais
require_once "config.php";

# carrega classe de usuario
require_once "class/cliente.php";

# instancia o objeto usuario
$_cliente = new cliente();

# define o retorno
$_RESPONSE = $_cliente->salvar();

# retorno no formato json
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
print json_encode( $_RESPONSE );
?>