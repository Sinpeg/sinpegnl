<?php
//require_once('../../includes/classes/sessao.php');
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[24]) {
    header("Location:index.php");
}
//require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('classes/rhetemufpa.php');
require_once ('dao/rhetemufpaDAO.php');
//$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
//$anobase = $sessao->getAnobase();
//require_once('../../includes/classes/unidade.php');
$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$codigo = $_GET["codigo"];
if (is_numeric($codigo) && $codigo != "") {
    $rhetemufpa = array();
    $cont = 0;

    $daorh = new rhetemufpaDAO();
    $rhetemufpa = new Rhetemufpa();

    $rows_rh = $daorh->buscarh($codigo);
    foreach ($rows_rh as $row) {
        $unidade->criaRhetemufpa($row['Codigo'], $row['CodSubunidade'], $row['DocDoutores'], $row['DocMestres'], $row['DocEspecialistas'], $row['DocGraduados'], $row['DocNTecnicos'], $row['DocTemporarios'], $row['Tecnicos'], $row['Ano']);
    }

    if ($unidade->getRhufpa()->getSubunidade() == 1001) {
        $selecionado1 = "selected=selected";
        $selecionado2 = "";
    } else {
        $selecionado2 = "selected=selected";
        $selecionado1 = "";
    }
    $daorh->fechar();
}
//ob_end_flush();
?>

<script language='javascript'>
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
                document.getElementById('msg').innerHTML = "Todos os campos devem conter apenas n&uacute;meros.";
                return false;
            }
        }
    }
    function Soma() {
        var soma = 0;
        qtde = new Array(document.pe.qtdDoc.value, document.pe.qtdMes.value, document.pe.qtdNt.value,
                document.pe.qtdEsp.value, document.pe.qtdGra.value, document.pe.qtdTem.value, document.pe.qtdTec.value);
        for (var i = 0; i < qtde.length; i++) {
            if (!isNaN(parseInt(qtde[i]))) {
                soma += parseInt(qtde[i]);
            }
        }

        document.getElementById('totalgeral').innerHTML = soma;
    }
    function direciona(botao) {
        switch (botao) {
            case 1:
                if (valida()) {
                    document.getElementById('pe').action = "<?php echo Utils::createLink('edprofrh', 'oprh'); ?>";
                    document.getElementById('pe').submit();
                }
                break;
            case 2:
                break;
        }
    }
    function valida() {
        if (document.pe.qtdDoc.value == "" || document.pe.qtdMes.value == ""
                || document.pe.qtdNt.value == "" || document.pe.qtdEsp.value == ""
                || document.pe.qtdGra.value == "" || document.pe.qtdTem.value == ""
                || document.pe.qtdTec.value == "") {
            document.getElementById('msg').innerHTML = "Todos os campos s&atilde;o obrigat&oacute;rios.";
            return false;
        }
        return true;
    }
</script>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li><a href="<?php echo Utils::createLink("edprofrh", "incluirh"); ?>" >Quadro de pessoal</a></li>
			<li><a href="<?php echo Utils::createLink("edprofrh", "consultarh"); ?>" >Consulta</a></li>
		    <li class="active">Alterar</li>
		</ul>
	</div>
</head>
<form class="form-horizontal" name="pe" id="pe" method="post">
    <h3 class="card-title"> Quadro de Pessoal</h3>
    <div class="msg" id="msg"></div>
    <table>
        <tr>
            <td>Subunidade</td>
       <td><select class="custom-select" name="subunidade">
          <?php if ($sessao->isUnidade()) { ?>
                    <option   <?php print $selecionado1; ?> value="1001">Escola de
                        M&uacute;sica</option>
                    <option   <?php print $selecionado2; ?> value="1004">Escola de
                        Teatro e Dan&ccedil;a</option>
                   
              <?php } else {?>
                    <option value="<?php echo $codunidade ?>"><?php echo $nomeunidade ?></option>
             <?php } ?> 
           </select></td>
        </tr>
        <tr>
            <td>Quantidade de Docentes Efetivos - Doutores</td>
            <td><input class="form-control"type="text" name="qtdDoc" size="5" onchange="Soma();"
                       maxlength="4"
                       value='<?php echo $unidade->getRhufpa()->getDoutores(); ?>'
                       onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Quantidade de Docentes Efetivos - Mestres</td>
            <td><input class="form-control"type="text" name="qtdMes" size="5" onchange="Soma();"
                       maxlength="4"
                       value='<?php echo $unidade->getRhufpa()->getMestres(); ?>'
                       onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Quantidade de Docentes Efetivos - Especialistas</td>
            <td><input class="form-control"type="text" name="qtdEsp" size="5" onchange="Soma();"
                       maxlength="4"
                       value='<?php echo $unidade->getRhufpa()->getEspecialistas(); ?>'
                       onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Quantidade de Docentes Efetivos - Graduados</td>
            <td><input class="form-control"type="text" name="qtdGra" size="5" onchange="Soma();"
                       maxlength="4"
                       value='<?php echo $unidade->getRhufpa()->getGraduados(); ?>'
                       onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Quantidade de Docentes Efetivos - N&iacute;vel T&eacute;cnico</td>
            <td><input class="form-control"type="text" name="qtdNt" size="5" onchange="Soma();"
                       maxlength="4"
                       value='<?php echo $unidade->getRhufpa()->getNtecnicos(); ?>'
                       onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Quantidade de Docentes Tempor&aacute;rios</td>
            <td><input class="form-control"type="text" name="qtdTem" size="5" onchange="Soma();"
                       maxlength="4"
                       value='<?php echo $unidade->getRhufpa()->getTemporarios(); ?>'
                       onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Quantidade de T&eacute;cnicos-administrativos</td>
            <td><input class="form-control"type="text" name="qtdTec" onchange="Soma();" size="5"
                       value='<?php echo $unidade->getRhufpa()->getTecnicos(); ?>'
                       maxlength="4" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Total Geral</td>
            <td><b id='totalgeral'></b>
            </td>
        </tr>
    </table>
    <input class="form-control"name="operacao" type="hidden" value="A" />
    <input class="form-control"type="hidden" name="codigo" value="<?php print $codigo; ?>" />
    <input type="button" onclick='direciona(1);' value="Gravar" />
</form>
<script>
  window.onload = Soma();
</script>
