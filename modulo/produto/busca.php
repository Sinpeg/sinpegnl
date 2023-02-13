<?php
ob_start();

echo ini_get('display_errors');

if (!ini_get('display_errors')) {
    ini_set('display_errors', 1);
    ini_set('error_reporting', E_ALL & ~E_NOTICE);
}
?>
<?php
//set_include_path(';../../includes');
require_once('../../includes/classes/sessao.php');

session_start();
if (!isset($_SESSION["sessao"])){
	header("location:index.php");
}
else {
	$sessao = $_SESSION["sessao"];
	$nomeunidade = $sessao->getNomeunidade();
	$codunidade = $sessao->getCodunidade();
	$responsavel = $sessao->getResponsavel();
	$anobase = $sessao->getAnobase();
	require_once('../../includes/dao/PDOConnectionFactory.php');
	require_once('dao/prodfarmaciaDAO.php');
	require_once('classes/prodfarmacia.php');
	require_once('classes/produtos.php');


	$tproduto=$_POST["produto"];

	if (is_numeric($tproduto) && $tproduto!=""){
		$pro = new Produtos();
		$pro->setCodigo($tproduto);
		$dao=new ProdfarmaciaDAO();
		$rows = $dao->buscatipoproduto($anobase, $tproduto);
		$passou=false;
		foreach ($rows as $row){
			$passou = true;
			$pro->adicionaItemProdfarmacia($row['Codigo'], $row['Quantidade'], $anobase, $row['Preco'], $row['Mes']);
		}
		$dao->fechar();


		if (!$passou) {
			$display = "<br/><br/>N&atilde;o existem registros para o produto. ";
		}
		else {

			$display="<table border=1 width=700px>";
			$display.="<tr align=center><td>M&ecirc;s</td><td>Quantidade</td><td>Pre&ccedil;o</td><td>Alterar</td><td>Excluir</td></tr>";
			foreach ($pro->getProdfarmacias() as $p){
				$display.="<tr><td>";
				if ($p->getMes()==1)
				$display.= "janeiro";
				if ($p->getMes()==2)
				$display.= "fevereiro";
				if ($p->getMes()==3)
				$display.= "mar&ccedil;o";
				if ($p->getMes()==4)
				$display.= "abril";
				if ($p->getMes()==5)
				$display.= "maio";
				if ($p->getMes()==6)
				$display.= "junho";
				if ($p->getMes()==7)
				$display.= "julho";
				if ($p->getMes()==8)
				$display.= "agosto";
				if ($p->getMes()==9)
				$display.= "setembro";
				if ($p->getMes()==10)
				$display.= "outubro";
				if ($p->getMes()==11)
				$display.= "novembro";
				if ($p->getMes()==12)
				$display.= "dezembro";

				$display.="</td>";
				$display.="<td align=center>".$p->getQuantidade()."</td>";
				$display.="<td>R$".str_replace(".", ",",$p->getPreco());
				$display.="</td>";
				$display.="<td align=center>";
				$display.="<a href=alprodfarma.php?codigo=".$p->getCodigo()." target=_self>";
				$display.="<img src=../../includes/images/editar.gif alt=Alterar width=19 height=19 /> </a>";
				$display.="</td>";
				$display.="<td align=center>";
				$display.="<a href=delprodfarma.php?codigo=".$p->getCodigo()."&tproduto=".$tproduto;
				$display.="target=_self><img src=../../includes/images/delete.gif alt=Excluir width=19 height=19 /></a>";
				$display.="</td></tr>";
			}
			$display.="</table>";

		}
		echo $display;
	}
	ob_end_flush();
}
?>