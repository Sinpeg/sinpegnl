<?php


/*
 * Busca logins dos usu�rios de determinada unidade
 */
//require_once('../../includes/classes/sessao.php');
require_once '../../classes/sessao.php';
require_once '../../classes/controlador.php';

session_start();
if (!isset($_SESSION["sessao"])){
//	header("location:../../index.php");
	exit();
}
else {
	$sessao = $_SESSION["sessao"];
	$nomeunidade = $sessao->getNomeunidade();
	$codunidade = $sessao->getCodunidade();
	$responsavel = $sessao->getResponsavel();
	$anobase = $sessao->getAnobase();
	$aplicacoes =$sessao->getAplicacoes();
	$loginsessao = $sessao->getLogin();

        require_once '../../dao/PDOConnectionFactory.php';
        require_once '../../dao/usuarioDAO.php';
        require_once '../../classes/unidade.php';
        require_once '../../classes/usuario.php';
        require_once '../../dao/unidadeDAO.php';
 
    $sessao=$_SESSION["sessao"];
    $unidade = array();
	$cont = 1;
	$daocat = new UnidadeDAO();


	
	$parametro1 = strtoupper(addslashes($_POST["parametro"]));
    if ($parametro1==""){
		$display = "";
		echo $display;
	}elseif (is_string($parametro1)){
        $parametro= "%".$parametro1."%";
		$cont=0;
        $rows=$daocat->buscaUnidResp($parametro);
		foreach ($rows as $row){
			$cont++;
			$unidade[$cont] =  new Unidade();
			$unidade[$cont]->setNomeunidade($row["NomeUnidade"]);
            $unidade[$cont]->setCodunidade($row["CodUnidade"]);
			}
        $vetor=array();
        $c=new Controlador();
        $vetor=$c->identificaItemMenu($sessao->getCodigo());
		$display = "<table>";
		$display .="<tr><td>Unidades</td><td>Selecionar</td></tr>";
        
		foreach ($unidade as $u){
			$display.="<td>".$u->getNomeunidade()."</td>";
		    $display.="<td align='center'>";
		    $display.="<a href='?modulo=".$vetor[1]."&acao=".$vetor[2]."&codunidade=".$u->getCodunidade()."&nomeunidade=".$u->getNomeunidade()."'";
		    $display.=" target='_self'><img src='webroot/img/busca.png' alt='Selecionar'";
		    $display.="width='19' height='19' /> </a></td></tr>";
           
    
 
		    }

		$display.="</table>";
echo $display;
		}

	
	}


ob_end_flush();
?>




