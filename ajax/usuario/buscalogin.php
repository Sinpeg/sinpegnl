<?php
/*
 * Busca logins dos usu�rios de determinada unidade
 */
//require_once('../../includes/classes/sessao.php');
require_once '../../classes/sessao.php';

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
	if (!$aplicacoes[3]){
		$mensagem = urlencode(" ");
//		$cadeia="location:../saida/erro.php?codigo=2&mensagem=".$mensagem;
		header($cadeia);
		exit();
	}
        require_once '../../dao/PDOConnectionFactory.php';
        require_once '../../dao/usuarioDAO.php';
        require_once '../../classes/unidade.php';
        require_once '../../classes/usuario.php';
        require_once '../../dao/unidadeDAO.php';
//	require_once('../../includes/dao/PDOConnectionFactory.php');
//	require_once('../../includes/dao/usuarioDAO.php');
//	require_once('../../includes/classes/usuario.php');
//	require_once('../../includes/classes/unidade.php');


    $usuario = array();
	$cont = 1;
	$dao= new UsuarioDAO();
	$daocat = new UnidadeDAO();
	$hierarquia = $daocat->buscahierarquia($codunidade);
	foreach($hierarquia as $hiera){
		$hier =  addslashes($hiera["hierarquia"]);
	}
	
	$parametro1 = strtoupper(addslashes($_POST["parametro"]));
	if ($parametro1==""){
		$display = "";
		echo $display;
	}elseif (is_string($parametro1)){
		$parametro= "%".$parametro1."%";
		$cont=0;
		$rows=$dao->buscaUnidadeLogin($parametro, $hier);

		foreach ($rows as $row){
			$cont++;
			$usuario[$cont] =  new Usuario();
			$usuario[$cont]->setCodusuario($row["CodUsuario"]);
			$usuario[$cont]->setLogin($row["Login"]);
			$usuario[$cont]->criaUnidade($row["CodUnidade"], $row["NomeUnidade"], null);

			}
		}

		$display = '<table id="tablesorter" class="table table-bordered table-hover" >
		
			<thead><tr><th>Login</th><th>Unidade</th><th>Altera</th></tr></thead>
			<tbody>';
		foreach ($usuario as $u){
			$display.="<tr><td>".$u->getLogin()."</td><td>".$u->getUnidade()->getNomeunidade()."</td>".
		    "<td align='center'><a href='?modulo=usuario&acao=altusuario&login=".$u->getLogin()."&msg='".
			"target='_self'><img src='webroot/img/editar.gif' alt='Alterar'".
		    "width='19' height='19' /> </a></td></tr>";
		    }
		$display.="</tbody></table>";

		$display.= '<script>
						$(function () {
							$(\'#tabelaInfra\').DataTable({
							"paging": true,
							"sort": true,
							"lengthChange": false,
							"searching": false,
							"ordering": true,
							"info": true,
							"autoWidth": false,
							"responsive": true,
							});
						});
					</script>';

		echo $display;
	}

ob_end_flush();
?>
