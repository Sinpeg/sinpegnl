<?php
$sessao = $_SESSION["sessao"];
//$nomeUnidade = $sessao->getNomeUnidade();
//$login = $sessao->getLogin();
//$responsavel = $sessao->getResponsavel();;
$anoBase = $sessao->getAnoBase();
$aplicacoes = $_SESSION["sessao"]->getAplicacoes();
if (!$aplicacoes[17]) {
    header("Location:index.php");
}
//require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('dao/freqfarmaciaDAO.php');
require_once('classes/freqfarmacia.php');
$cont = 0;
$daofreq = new FreqfarmaciaDAO();
$rows_freq = $daofreq->buscaporano($anoBase);
foreach ($rows_freq as $row) {
    $f = new Freqfarmacia();
    $cont++;
    $f->setCodigo($row['Codigo']);
    $f->setAno($anoBase);
    $f->setMes($row['Mes']);
    $f->setNAlunos($row['NAlunos']);
    $f->setNProfessores($row['NProfessores']);
    $f->setNVisitantes($row['NVisitantes']);
    $f->setNPesquisadores($row['NPesquisadores']);
}

$daofreq->fechar();
/* if ($cont>0){
  $cadeia = "location:consultafreq.php";
  header($cadeia);
  exit();
  } */
//ob_end_flush();
?>

<script language='javascript'>
    function SomenteNumero(event) {
        var tecla = (window.event) ? event.keyCode : event.which;
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
        qtde = new Array(document.freq.di.value, document.freq.doc.value,
                document.freq.pq.value, document.freq.nv.value);
        for (var i = 0; i < qtde.length; i++) {
            if (!isNaN(parseInt(qtde[i]))) {
                soma += parseInt(qtde[i]);
            }
        }
        document.getElementById('totalgeral').innerHTML = soma;
    }
    function direciona() {
        if (document.freq.di.value == "") {
            document.getElementById('msg').innerHTML = "N&uacute;mero de frequentadores &eacute; campo obrigat&oacute;rio!";
            document.freq.di.focus();
        } else if (document.freq.doc.value == "") {
            document.getElementById('msg').innerHTML = "N&uacute;mero de Docente &eacute; campo obrigat&oacute;rio! ";
            document.freq.doc.focus();
        } else if (document.freq.pq.value == "") {
            document.getElementById('msg').innerHTML = "N&uacute;mero de Pesquisadores &eacute; campo obrigat&oacute;rio! ";
            document.freq.pq.focus();
        } else if (document.freq.nv.value == "") {
            document.getElementById('msg').innerHTML = "N&uacute;mero de Visitantes &eacute; campo obrigat&oacute;rio!";
            document.freq.nv.focus();
        } else {
            document.freq.action = "?modulo=freq&acao=opfreq";
            document.freq.submit();
        }
    }
</script>
<div id="msg"></div>
<form class="form-horizontal" name="freq" method="post" >
    <h3 class="card-title"> Frequentadores da F&aacute;rmacia </h3>
    <table>
        <tr>
            <td width="200px">M&ecirc;s</td>
            <td><select class="custom-select" name="mes">
                    <option value="1">janeiro</option>
                    <option value="2">fevereiro</option>
                    <option value="3">mar&ccedil;o</option>
                    <option value="4">abril</option>
                    <option value="5">maio</option>
                    <option value="6">junho</option>
                    <option value="7">julho</option>
                    <option value="8">agosto</option>
                    <option value="9">setembro</option>
                    <option value="10">outubro</option>
                    <option value="11">novembro</option>
                    <option value="12">dezembro</option>
                </select></td>
        </tr>
        <tr><td>N&uacute;meros de Discentes </td>
            <td><input class="form-control"type="text" name="di" size="5" value='' onchange="Soma();"
                       onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr><td>N&uacute;meros de Docentes </td>
            <td><input class="form-control"type="text" name="doc" size="5" value=''
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' /> </td>
        </tr>
        <tr><td>N&uacute;meros de Pesquisadores </td>
            <td><input class="form-control"type="text" name="pq" size="5"
                       value='' onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr><td>N&uacute;meros de Visitantes</td>
            <td><input class="form-control"type="text" name="nv" size="5"
                       value='' onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr><td>Total Geral</td><td><b id='totalgeral'></b></td></tr>
    </table>

    <input class="form-control"name="operacao" type="hidden" value="I" /><br/>
    <input type="button" onclick="direciona();" class="btn btn-info" value="Gravar" />&ensp;
    <input type="button" onclick="javascript:history.go(-1);" class="btn btn-info" value="Voltar" />
</form>
