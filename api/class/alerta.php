<?php

class alerta extends database {

	public function salvar() {
        // variaveis da denuncia
		$this->nome = addslashes(@ $_REQUEST['nome']);
		$this->latitude = (@ $_REQUEST['latitude']);
        $this->longitude = (@ $_REQUEST['longitude']);
        
		// grava alerta
        $this->idalerta = $this->insert();

        return array ( 'idalerta' => $this->idalerta );
		
	}

}
?>
