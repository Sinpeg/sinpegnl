<?php
class Menu {
	
		private $estado;
		private $texto;
		private $aplicacoes;
		private $quantidade;
		
		public function __construct($estado, $texto) {
			$this->setado = $estado;
			$this->texto = $texto;
			$this->aplicacoes = array();
			$this->quantidade = 0;
		}
		
		public function setEstado($estado) {
			$this->estado = $estado;
		}
			
		public function setTexto($texto) {
			$this->texto = $texto;
		}
		
		
		public function getEstado() {
			return $this->estado;
		}

		public function getTexto() {
			return $this->texto;
		}
		
		public function concatenaTexto($cadeia) {
			$this->texto .= $cadeia;
		}
		
		public function getQuantAplicacao() {
			return $this->quantidade;
		}
 		
		public function adicionaAplicacao(Aplicacao $app, $controle) {
			
			$this->aplicacoes[$this->quantidade] = $app;
			$submenu = '<tr><td id="submenu"><a href="';
			$submenu_parte = ' " target="workspace">';
			$submenu_fecha = '</a></td></tr>';
			$str = 		$str = $submenu."".$app->getPath()."".$submenu_parte."".$app->getNome()."".$submenu_fecha;

			if ($controle) {			
				$this->concatenaTexto($str);
				$this->quantidade++;
			}
			
		}	
	}

?>
