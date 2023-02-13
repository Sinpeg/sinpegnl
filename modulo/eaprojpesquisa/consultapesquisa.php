<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
//session_start();
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[20]) {
    header("Location:index.php");
}
//$sessao = $_SESSION["sessao"];
//$nomeunidade = $sessao->getNomeunidade();
//$codunidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();
//equire_once('../../includes/dao/PDOConnectionFactory.php');
require_once('dao/eaprojpesquisaDAO.php');
require_once('classes/eaprojpesquisa.php');
$projpesquisa = array();
$cont = 0;
$daopp = new EAprojpesquisaDAO();
$projpesquisa = new EAprojpesquisa();

$rows_pp = $daopp->buscapp($anobase);
foreach ($rows_pp as $row) {
    $cont++;
    $projpesquisa->setExecucao($row['EmExecucao']);
    $projpesquisa->setTramitacao($row['Emtramitacao']);
    $projpesquisa->setCancelado($row['Cancelado']);
    $projpesquisa->setSuspenso($row['Suspenso']);
    $projpesquisa->setConcluido($row['Concluido']);
    $projpesquisa->setDocentes($row['Qdocentes']);
    $projpesquisa->setTecnicos($row['Qtecnicos']);
    $projpesquisa->setDiscentes($row['Qdiscentes']);
    $projpesquisa->setOutras($row['QoutrasInstituicoes']);
    $projpesquisa->setAno($row['Ano']);
}
$daopp->fechar();
if ($cont == 0) {
    Utils::redirect('eaprojpesquisa', 'incluipesquisa');
//    $cadeia = "location:incluipesquisa.php";
//    header($cadeia);
//    exit();
}
//ob_end_flush();
?>
<script type="text/javascript">
    function SomenteNumero(e){
    var tecla = (window.event)?event.keyCode:e.which;
    //0 a 9 em ASCII
    if((tecla>47 && tecla<58)){
    document.getElementById('msg').innerHTML =" ";
    return true;
    }
    else{
    if (tecla==8 || tecla==0) {
    document.getElementById('msg').innerHTML =" ";
    return true;//Aceita tecla tab
    }
    else {
    document.getElementById('msg').innerHTML = "Todos os campos devem conter apenas n&uacute;meros.";
    return false;
    }
    }
    }

    function Soma1(){
    var soma = 0;
    qtde = new Array (document.pe.qtdExe.value,document.pe.qtdTra.value,
    document.pe.qtdCan.value,document.pe.qtdSusp.value, document.pe.qtdConc.value);
    for (var i = 0;i < qtde.length; i++){
    if (!isNaN(parseInt(qtde[i]))){
    soma += parseInt(qtde[i]);
    }
    }

    document.getElementById('totalgeral1').innerHTML = soma;
    }

    function Soma2(){
    var soma = 0;
    qtde = new Array (document.pe.qtdDoc.value,document.pe.qtdTec.value,
    document.pe.qtdDisc.value,document.pe.qtdOutras.value);
    for (var i = 0;i < qtde.length; i++){
    if (!isNaN(parseInt(qtde[i]))){
    soma += parseInt(qtde[i]);
    }
    }

    document.getElementById('totalgeral2').innerHTML = soma;
    }

    function direciona(botao){
    switch (botao){
    case 1:
    document.getElementById('pe').action = "?modulo=eaprojpesquisa&acao=alterapesquisa";
    document.getElementById('pe').submit();
    break;
    case 2:
    document.getElementById('pe').action = "../saida/saida.php";
    document.getElementById('pe').submit();
    break;
    }

    }

</script>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li><a href="<?php echo Utils::createLink("eaprojpesquisa", "incluipesquisa"); ?>">Projetos de pesquisa</a></li>
			<li class="active">Consulta</li>
		</ul>
	</div>
</head>
<form class="form-horizontal" name="pe" id="pe" method="post">
    <h3 class="card-title"> Projetos de Pesquisa da Escola de Aplica&ccedil;&atilde;o</h3>
    <table>
        <tr style="font-style: italic;" align="center">
            <th>Itens</th>
            <th>Quantidade</th>
        </tr>
        <tr>
            <td>Em Execu&ccedil;&atilde;o</td>
            <td> <?php echo $projpesquisa->getExecucao(); ?></td>
        </tr>
        <tr>
            <td>Em Tramita&ccedil;&atilde;o</td>
            <td> <?php echo $projpesquisa->getTramitacao(); ?></td>
        </tr>
        <tr>
            <td>Cancelados</td>
            <td> <?php echo $projpesquisa->getCancelado(); ?></td>
        </tr>
        <tr>
            <td>Suspensos</td>
            <td> <?php echo $projpesquisa->getSuspenso(); ?></td>
        </tr>
        <tr>
            <td>Conclu&iacute;dos</td>
            <td> <?php echo $projpesquisa->getConcluido(); ?></td>
        </tr>
        <tr>
            <th colspan="2" align="left">N&uacute;mero de Participantes</th>
        </tr>
        <tr>
            <td>Docentes</td>
            <td> <?php echo $projpesquisa->getDocentes(); ?></td>
        </tr>
        <tr>
            <td>T&eacute;cnicos</td>
            <td> <?php echo $projpesquisa->getTecnicos(); ?></td>
        </tr>
        <tr>
            <td>Discentes</td>
            <td> <?php echo $projpesquisa->getDiscentes(); ?></td>
        </tr>
        <tr>
            <td>Pessoas de Outras Institui&ccedil;&otilde;es</td>
            <td> <?php echo $projpesquisa->getOutras() ?></td>
        </tr>
    </table>
    <br /> <input type="button" value="Alterar" onclick="direciona(1);" />

</form>