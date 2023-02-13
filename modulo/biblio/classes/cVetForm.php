<?php
class cVetForm{
	private $aspectos = array();
	private $f = array();
	
  public function inicializa($fim){
  	$this->aspectos=NULL;
  	$this->aspectos=array();
  	for ($i=1;$i<=$fim;$i++){
  		$this->aspectos[$i]=0;
  	}
  	return $this->aspectos;
  }
	
	public function obtemCheckbox($fim, $entrada){

        $this->inicializa($fim);
		if(!empty($entrada)) {
			foreach($entrada as $i=>$entrada) {
			   $this->aspectos[$i]=1;
                                
			}
		}

           
		return $this->aspectos;
	}
	
	public function  legendasForm(){
		$this->f[1] ="Usa ferramenta de busca integrada (ferramenta eletr&ocirc;nica que possibilita agregar diversas
fontes eletr&ocirc;nicas de informa&ccedil;&atilde;o a partir de um &uacute;nico campo de pesquisa)";
		$this->f[2] ="Realiza comuta&ccedil;&otilde;es bibliogr&aacute;ficas";
	    $this->f[3] = "Oferece servi&ccedil;os pela Internet";
	    $this->f[4] = "Possui rede sem fio";
	    $this->f[5] = "Participa de redes sociais";
	    $this->f[6] = "Possui Atendente ou Membro da Equipe de Atendimento Treinado na L&iacute;ngua Brasileira de Sinais - LIBRAS";
	    
	    $this->f[7] ="Possui acervo em formato especial (Braile/sonoro)";
	    $this->f[8] ="S&iacute;tios e aplica&ccedil;&otilde;es desenvolvidos para que pessoas percebam, compreendam, naveguem e utilizem servi&ccedil;os oferecidos";
        $this->f[9] ="Plano de aquisi&ccedil;&atilde;o gradual de acervo bibliogr&aacute;fico dos conte&uacute;dos b&aacute;sicos em formato especial";
	                        
	                        
	     $this->f[10] ="Disponibiliza software de leitura para pessoas com baixa vis&atilde;o";
         $this->f[11] ="Disponibiliza impressoras em Braile";
	     $this->f[12] ="Teclado virtual";
	              
	     
	     $this->f[13] ="Possui acesso ao Portal Capes de Peri&oacute;dicos";
	     $this->f[14] ="Assina outras bases de dados";
	     $this->f[15] ="Possui biblioteca digital de Servi&ccedil;o P&uacute;blico";
	     $this->f[16] ="Possui cat&aacute;logo online de Servi&ccedil;o P&uacute;blico";   
	     	     
	    return $this->f;
	    
	}
}
