<?php
//require_once('../../includes/classes/sessao.php');
//session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[4]) {
    header("Location:index.php");
} 
//$nomeunidade = $sessao->getNomeUnidade();
//$codunidade = $sessao->getCodUnidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnoBase();
//require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('dao/prodintelectualDAO.php');
require_once('classes/prodintelectual.php');
require_once('dao/tipoprodintelectualDAO.php');
require_once('classes/tipoprodintelectual.php');
require_once('./classes/curso.php');

$daopi = new prodintelectualDAO();
$tiposie = array();
    $cont = 0;
    $daotpi = new TipoprodintelectualDAO();
    $validade=2014;
    if ($anobase<=2013)
    	$validade=2013;
    $rows_pi = $daopi->tiponaoinserido($codunidade, $anobase,$validade);
    if (($rows_pi->rowCount() == 0) || ($validade>2013)){
        $rows_pi = $daotpi->Lista($validade);
    }
    
    foreach ($rows_pi as $row) {
    	if (($anobase>=2018 && $row['Codigo']<>120 && $validade==2014) || ($anobase<2018)) {
	        $cont++;
	        $tipospi[$cont] = new Tipoprodintelectual();
	        $tipospi[$cont]->setCodigo($row['Codigo']);
	        $tipospi[$cont]->setNome($row['Nome']);
    	}
    }
    $tamanho = count($tipospi);
   
$daopi->fechar();
//if (($validade >2014) && ()) {
//   Utils::redirect('prodintelectual', 'consultaprodintelectual', array('codcurso' => $codcurso, 'nomecurso' => $nomecurso));

//}

//ob_end_flush();
?>       

<head>
	<div class="bs-example">
		<ul class="breadcrumb">
		    <li class="active">
                <a href="<?php echo Utils::createLink("prodintelectual", "consultaprodintelectual"); ?>">Produção Intelectual</a>
			    <i class="fas fa-long-arrow-alt-right"></i>
                Incluir
            </li>
		</ul>
	</div>
</head>

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Produção Intelectual</h3>    
    </div>
    <form class="form-horizontal" name="pi" id="pi" method="post">
        <table class="card-body">
            <div class="msg" id="msg"></div>
            <tbody>
                <tr align="left" style="font-style: italic;">
                    <td>Itens</td>
                    <td>Quantidade</td>
                </tr>
                <?php
                $cont = 0;
                foreach ($tipospi as $t) {
                    ?>
                    <tr>
                        <td><?php print ($t->getNome()); ?></td>
                        <td>
                            <input class="form-control"type="text" name="prodi[]" size="4"  maxlength="4"
                                onkeypress='return SomenteNumero(event)' value="0" onchange="Soma();"  /></td>
                    </tr>
                <?php }//for	 ?>
                <tr style="font-style:italic;">
                    <td>
                        <input class="form-control"type="hidden" name="prodi[]" size="7"  maxlength="4"	 value='0'  onchange="Soma();"  />Total Geral</td>
                        <td><b id='totalgeral'></b>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="card-body">
            <td colspan="2" align="center">
                <input class="form-control"name="operacao" type="hidden" value="I" />
                <br>
                <input type="button" onclick="direciona(1);" id="gravar" value="Gravar" class="btn btn-info" />
                <!--    <input type="button" onclick="direciona(2);" value="Consultar" class="btn btn-info" /> -->
                <!--     <input type="button" onclick="direciona(3);" value="Incluir novo item" class="btn btn-info" />-->
                <input	type="hidden" name="codunidade" value="<?php print $codunidade; ?>" />
            </td>
        </table>

    </form>
</div>

<script language="javascript">
    function Soma() {
    	
        var multi_prodi = document.pi.elements["prodi[]"];
        var soma = 0;
        for (var i = 0; i < multi_prodi.length; i++)
        {
            if (!isNaN(parseInt(multi_prodi[i].value, 10))) {
                soma += parseInt(multi_prodi[i].value, 10);
            }
        }
        document.getElementById('totalgeral').innerHTML = soma;
    }
    function SomenteNumero(e) {
    
        var tecla = (window.event) ? event.keyCode : e.which;
        //0 a 9 em ASCII
        if ((tecla > 47 && tecla < 58)) {
            document.getElementById('msg').innerHTML = " ";
            return true;
        }
        else {
            if (tecla == 8 || tecla == 0) {
                document.getElementById('msg').innerHTML = " ";
                return true;//Aceita tecla tab
            }
            else {
                document.getElementById('msg').innerHTML = "Todos os campos devem conter apenas números.";
                return false;
            }
        }
    }
    function direciona(botao) {
        switch (botao) {
            case 1:
                if (valida()) {
                    document.getElementById('pi').action = "?modulo=prodintelectual&acao=opprodintelectual";
                    document.getElementById('pi').submit();
                }
                break;
            case 2:
                document.getElementById('pi').action = "<?php echo Utils::createLink('prodintelectual', 'consultapi', array('codunidade' => $codunidade)); ?>";
                document.getElementById('pi').submit();
                break;
            case 3:
                document.getElementById('pi').action = "?modulo=prodintelectual&acao=consultaitempi";
                document.getElementById('pi').submit();
                break;
        }

    }
    function valida() {
        var multi_prodi = document.pi.elements["prodi[]"];
        var soma = 0;
        var passou = false;
        for (var i = 0; i < multi_prodi.length; i++) {
            if (isNaN(parseInt(multi_prodi[i].value, 10))) {
                document.getElementById('msg').innerHTML = '<div class="alert alert-danger" role="alert">Todos os campos são obrigatórios. Não deixe campos em branco, digite 0.</div>';
                passou = true;
                window.scrollTo(0, 0);
            }
        }
        if (passou) {
            return false;
        }
        else {
            return true;
        }
    }
</script>
