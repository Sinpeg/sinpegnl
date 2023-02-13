<?php
class Controlador {
   
   
   	private  $c_modulo = null ;
    private  $c_acao = null ;
    private  $c_grupo = null ;
      
    
    
             
    	//public function getProfile($grupos,$itemenu ) {
    public function getProfile($grupos) {
            //$sessao = $_SESSION["sessao"];

            foreach ($grupos as $g){
               if ( $g==18 ) {
                   $this->c_grupo=$g;
               }
            }
              if ($this->c_grupo==18){  
                // return Utils::createLink('usuario', 'consultaunidade',array('codigo'=>$itemenu));  
                  return true;
         		}else{   
                  return false;
                //return  Utils::createLink( $modulo, $acao );
                }
    
    }
        	public  function lastRedirect () {

                   $this->sessao=$_SESSION["sessao"];
                   return Utils::createLink($this->sessao->getModulo(), $this->sessao->getAcao());

            }
            
            
            public function identificarAnalistas($usuario){
            	$usuarios=array(228 =>'Fagner',159=>'Lúcia',52=>'Jaciane');
            	foreach ($usuarios as $key => $value){
            		if ($key==$usuario){
            			return true;
            		}
            	}
            	return false;;
            	
            }
            
            
           public function identificarUsuarios($usuario){
            	//$usuarios=array(228 =>'Fagner',159=>'Lúcia',52=>'Jaciane');
            	
            		switch ($usuario)  {
            			case 228: $res="A";break;
            			case 159: $res= "D";break;
            			case 52: $res= "D";break;
            		    default:$res="C";
            	}
                return $res;
            	
            }
            
    
           public function identificaItemMenu($codigo){
               $vetor=array();
               switch ($codigo) {
                case 50://codigo da aplicacao
                    $vetor[1]='calendarioPdi';$vetor[2]= 'listaCalendario';
                    break;
                case 36:
                    $vetor[1]='documentopdi';$vetor[2]= 'listadocpdi';
                    break;
                case 37:
                     $vetor[1]='mapaestrategico';$vetor[2]= 'listaMapa';  
                    break;
                case 38:
                     $vetor[1]='indicadorpdi';$vetor[2]= 'consultaindicador';  
                    break;
                 case 29:
                     $vetor[1]='resultadopdi';$vetor[2]= 'consultaresult';  
                    break;       
                      
                  case 51:
                     $vetor[1]='aaa';$vetor[2]= 'aaaa';  
                    break;       
                        }
               return $vetor;
           }
            
            public  function getC_modulo () {
                   return  $this->c_modulo;

            }
             
            public  function getC_acao () {
                   return  $this->c_acao;

            }
            
             public  function getC_grupo() {
                   return  $this->c_grupo;

            }
        }
    ?>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

   
