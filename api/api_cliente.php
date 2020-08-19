<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

# carrega as configuraçoes iniciais
require_once "config.php";

if ( $classe = @ $_REQUEST['classe'] ) {
	# carrega classe
	require_once "class/$classe.php";

	# instancia objeto
	$_object = new $classe();
    if ( $metodo = @ $_REQUEST['metodo'] ) {
        if ( $rs = @ $_object->$metodo() ) {
            $_RESPONSE = $rs;
        } else {
            $_RESPONSE['error'] = 'Nenhum resultado encontrado';
        }
    } else {
        $_RESPONSE['error'] = 'Nenhum METODO requerido';
    }
} else {
	$_RESPONSE['error'] = 'Nenhuma CLASSE requerida';
}

# retorno no formato json
header("Content-Type: application/json; charset=utf-8", true);
print json_encode( @ $_RESPONSE );
?>