<?php

class alerta extends database {

	public function obterTodos() {
		$sql = "SELECT * FROM alerta";
	
		if ( $rs = parent::fetch_all($sql) ) {
			foreach ( $rs as $row ) {
				$col = array();
				foreach ( $row as $k=>$v ) {
					$col[$k] = stripslashes($v);
				}
				$rows[] = $col;
			}
			return array( 'data' => $rows );
		}
	}
	
	public function obterTodosDoCliente() {
		global $_user;
		$sql = "SELECT * FROM alerta WHERE idcliente = ".$_user->idcliente;
	
		if ( $rs = parent::fetch_all($sql) ) {
			foreach ( $rs as $row ) {
				$col = array();
				foreach ( $row as $k=>$v ) {
					$col[$k] = stripslashes($v);
				}
				$rows[] = $col;
			}
			return array( 'data' => $rows );
		}
	}

	public function obterTodosDoUsuario() {
		global $_user;
		$sql = "SELECT * FROM alerta WHERE idusuario = ".$_user->idusuario;
	
		if ( $rs = parent::fetch_all($sql) ) {
			foreach ( $rs as $row ) {
				$col = array();
				foreach ( $row as $k=>$v ) {
					$col[$k] = stripslashes($v);
				}
				$rows[] = $col;
			}
			return array( 'data' => $rows );
		}
	}

	public function salvar() {
		global $_user;
		// variaveis da denuncia
		$this->idcliente = $_user->idcliente;
		$this->nome = $_user->nome;
		$this->latitude = (@ $_REQUEST['latitude']);
		$this->longitude = (@ $_REQUEST['longitude']);
		$this->dt_registro = (@ $_REQUEST['dt_registro']);
		// grava alerta
        $this->idalerta = $this->insert();
		return array ( 'idalerta' => $this->idalerta );
		
	}

}
?>
