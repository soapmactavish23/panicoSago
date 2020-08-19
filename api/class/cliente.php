<?php

class cliente extends database {
	
	public function autenticar ($login, $password) {
		$login = addslashes($login);
		$password = addslashes($password);
		$sql = "SELECT idcliente, nome, foto FROM cliente
		WHERE binary email='$login' and binary senha='".md5($password)."' 
		LIMIT 1";
		if ( $rs = parent::fetch_all($sql) ) {
			$row = array_shift($rs);
			$this->saveLog('Entrou', $row['idcliente']);
			$rows['token_cliente'] = createJWT ($row);
			return $rows;
		}
	}
	
	public function obterTodos() {
		$sql = "SELECT * FROM cliente";

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

	public function obterClienteLogado(){
		global $_user;
		$sql = "SELECT * FROM cliente WHERE idcliente = ".$_user->idcliente." LIMIT 1";
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

	public function obterHistoricoCortes(){
		global $_user;
		$sql = "SELECT idagenda, nome, preco, data, status  FROM agenda a
		INNER JOIN corte c
		ON c.idcorte = a.idcorte
		WHERE idcliente = ".$_user->idcliente;
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

	public function obterHistoricoBarbeiros(){
		global $_user;
		$sql = "SELECT foto, nome, contato, COUNT(*) as total FROM agenda a
		INNER JOIN usuario u
		ON a.idusuario = u.idusuario
		WHERE a.idcliente = ".$_user->idcliente."
		group by nome";
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
		$this->idcliente = @ $_REQUEST['idcliente'];
		$this->nome = $_REQUEST['nome'];
        $this->email = $_REQUEST['email'];
        $this->contato = $_REQUEST['contato'];
		$this->cpf = $_REQUEST['cpf'];
		
		if ( $this->idcliente ) {
			$this->dt_update = date('Y-m-d H:i:s');
			$this->update();
			return array ( 'idcliente' => $this->idcliente, 'msg' => "Usuário Atualizado com Sucesso");
		} else {
			$this->senha = md5($_REQUEST['password']);
			$this->idcliente = $this->insert();
			return array ( 'idcliente' => $this->idcliente, 'msg' => "Usuário Cadastrado com Sucesso");
		}
	}
	
	public function salvar_foto(){
		global $_user;
		$this->idcliente = $_user->idcliente;
		$this->foto = @ $_REQUEST['foto_1'];
		$this->dt_update = date('Y-m-d H:i:s');
		if($this->update()){
			return array ( 'success' => "Foto Atualizada com Sucesso");
		}else{
			return array ( 'error' => "Não Foi Possível Alterar a Foto");
		}
	}

	public function excluir() {
		if ( @ $_REQUEST['idcliente'] ) {
			$this->idcliente = $_REQUEST['idcliente'];
			$this->delete();
			global $_user;
			$this->saveLog('excluiu usuario ID '.$_REQUEST['idcliente'], $_user->idcliente);
			return array ( 'idcliente' => $this->idcliente );
		}
	}

	public function renovarSenha() {
		if ( @ $_REQUEST['idcliente'] && @ $_REQUEST['email'] ) {
			$this->idcliente = $_REQUEST['idcliente'];
			$this->senha = md5($_REQUEST['email']);
			$this->update();
			global $_user;
			$this->saveLog('renovou senha do usuario ID '.$_REQUEST['idcliente'], $_user->idcliente);
			return array ('idcliente' => $this->idcliente );
		}
	}

	public function mudarSenha() {
		global $_user;
		$sql = "SELECT idcliente FROM cliente
		WHERE binary idcliente='".$_user->idcliente."' and binary senha='".md5($_REQUEST['senha'])."' 
		LIMIT 1";
		if ( $rs = parent::fetch_all($sql) ) {
			$vet = array_shift($rs);
			$this->idcliente = $vet['idcliente'];
			$this->senha = md5($_REQUEST['novasenha']);
			$this->update();
			$this->saveLog('mudou senha', $_user->idcliente);
			return array ('success' => 'Sua senha foi alterada com sucesso' );
		} else {
			return array ('error' => 'Senha atual inválida');
		}
	}
}
?>