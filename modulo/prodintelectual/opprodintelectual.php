<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
//require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('classes/prodintelectual.php');
require_once('dao/prodintelectualDAO.php');
require_once('classes/tipoprodintelectual.php');
require_once('dao/tipoprodintelectualDAO.php');
//session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[4]) {
    header("Location:index.php");
} 
//$nomeUnidade = $sessao->getNomeunidade();
//$codUnidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();
//require_once('../../includes/classes/curso.php');
$tipoPI = $_POST['prodi'];
$codunidade = $_POST["codunidade"];
$operacao = $_POST["operacao"];
$erro = false;

foreach ($tipoPI as $i => $qtde) {
    if (!preg_match('/^[0-9]+$/', $qtde)) {
        $erro = true;
        break;
    }
}
$validade = 2014;
    	if ($anobase<=2013){
    		$validade=2014;
    	}
if ($erro) {
    $mensagem = urlencode(" ");
    $cadeia = "location:../saida/erro.php?codigo=1&mensagem=" . $mensagem;
    header($cadeia);
} else {
    $uni = new Unidade();
    $uni->setCodunidade($codunidade);
    $cont = 0;
    $i = 0;
    $daopi = new ProdintelectualDAO();
    $daotpi = new TipoprodintelectualDAO();
    if ($operacao == "I") {    		
        $rows = $daopi->tiponaoinserido($codunidade, $anobase,$validade);
        foreach ($rows as $row) {
            if (($anobase>=2018 && $row['Codigo']<>120 && $row['Codigo']<>132 && $row['Codigo']<>133 && $row['Codigo']<>134 && $validade==2014) || ($anobase<2018)) {
        	
		            $cont++;
		            $tPI[$cont] = new Tipoprodintelectual();
		            $tPI[$cont]->setCodigo($row["Codigo"]); //código do tipo - tabela tipo de prod int
		            if ($operacao == "I") {
		                $tPI[$cont]->criaProdintelectual(null, $uni , $anobase, $tipoPI[$i]);
		            }		            
		            $i++;
		            
        	}
        }
     $daopi->inseretodos($tPI);
        Flash::addFlash('Dados da Produção Intelectual incluidos com sucesso!');
    } elseif ($operacao == "A") {
        $rows = $daopi->buscapiunidade($codunidade, $anobase);
        foreach ($rows as $row) {
            if (($anobase>=2018 && $row['Tipo']<>120 && $row['Codigo']<>132 && $row['Codigo']<>133 && $row['Codigo']<>134 && $validade==2014) || ($anobase<2018)) {
        	            
			            $cont++;
			            $tPI[$cont] = new Tipoprodintelectual();
			            $tPI[$cont]->setCodigo($row["Tipo"]);
			            $consulta = $daopi->buscapi($codunidade, $anobase, $row["Tipo"]); //tabela prodintelectual
			            foreach ($consulta as $row1) {
			                $tPI[$cont]->criaProdintelectual($row1["Codigo"], $uni, $anobase, $tipoPI[$i]);
			            }
			            $i++;
        	 }
        }
        $daopi->alteratodos($tPI);
        Flash::addFlash('Dados da Produção Intelectual atualizados com sucesso!');
       
    }
}


$daopi->fechar();
Utils::redirect('prodintelectual', 'consultaprodintelectual');//aqui


//header($cadeia);
//ob_end_flush();
?>
