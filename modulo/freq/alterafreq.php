<?php
//require_once('../../includes/classes/sessao.php');
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[17]) {
    header("Location:index.php");
} else {
    $sessao = $_SESSION["sessao"];
//	$nomeunidade = $sessao->getNomeunidade();
    $login = $sessao->getLogin();
    if ($login == "admin")
        $codunidade = 270;
    else
        $codunidade = $sessao->getCodunidade();


//	$responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnobase();

//	require_once('../../includes/dao/PDOConnectionFactory.php');
    require_once('dao/freqfarmaciaDAO.php');
    require_once('classes/freqfarmacia.php');

    $codigo = $_GET['codigo'];

    if (is_numeric($codigo) && $codigo != "") {
        $tipofreq = array();
        $daofreq = new FreqfarmaciaDAO();
        $rows_freq = $daofreq->buscaporcodigo($codigo);
        $cont = 0;
        foreach ($rows_freq as $row) {
            $cont = 1;
            $f = new Freqfarmacia();
            $f->setCodigo($row['Codigo']);
            $f->setMes($row['Mes']);
            $f->setNalunos($row['NAlunos']);
            $f->setNprofessores($row['NProfessores']);
            $f->setNvisitantes($row['NVisitantes']);
            $f->setNpesquisadores($row['NPesquisadores']);
            $f->setAno($anobase);
        }
        $daofreq->fechar();

        $selecionado1 = "";
        $selecionado2 = "";
        $selecionado3 = "";
        $selecionado4 = "";
        $selecionado5 = "";
        $selecionado6 = "";
        $selecionado7 = "";
        $selecionado8 = "";
        $selecionado9 = "";
        $selecionado10 = "";
        $selecionado11 = "";
        $selecionado12 = "";
        if ($f->getMes() == 1) {
            $selecionado1 = "selected='selected'";
        }
        if ($f->getMes() == 2) {
            $selecionado2 = "selected='selected'";
        }
        if ($f->getMes() == 3) {
            $selecionado3 = "selected='selected'";
        }
        if ($f->getMes() == 4) {
            $selecionado4 = "selected='selected'";
        }
        if ($f->getMes() == 5) {
            $selecionado5 = "selected='selected'";
        }
        if ($f->getMes() == 6) {
            $selecionado6 = "selected='selected'";
        }
        if ($f->getMes() == 7) {
            $selecionado7 = "selected='selected'";
        }
        if ($f->getMes() == 8) {
            $selecionado8 = "selected='selected'";
        }
        if ($f->getMes() == 9) {
            $selecionado9 = "selected='selected'";
        }
        if ($f->getMes() == 10) {
            $selecionado10 = "selected='selected'";
        }
        if ($f->getMes() == 11) {
            $selecionado11 = "selected='selected'";
        }
        if ($f->getMes() == 12) {
            $selecionado12 = "selected='selected'";
        }
    }
//	ob_end_flush();
}
?>
<script language='JavaScript'>
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
        if (isNaN(parseInt(document.freq.di.value))) {
            document.getElementById('msg').innerHTML = "N&uacute;mero de frequentadores &eacute; campo obrigat&oacute;rio!";
            document.freq.di.focus();
        } else if (isNaN(parseInt(document.freq.doc.value))) {
            document.getElementById('msg').innerHTML = "N&uacute;mero de Docente &eacute; campo obrigat&oacute;rio! ";
            document.freq.doc.focus();
        } else if (isNaN(parseInt(document.freq.pq.value))) {
            document.getElementById('msg').innerHTML = "N&uacute;mero de Pesquisadores &eacute; campo obrigat&oacute;rio! ";
            document.freq.pq.focus();
        } else if (isNaN(parseInt(document.freq.nv.value))) {
            document.getElementById('msg').innerHTML = "N&uacute;mero de Visitantes &eacute; campo obrigat&oacute;rio!";
            document.freq.nv.focus();
        } else {
            document.freq.action = "?modulo=freq&acao=opfreq";
            document.freq.submit();
        }
    }
</script>
<form class="form-horizontal" name="freq" method="post" action="">
    <h3 class="card-title"> Frequentadores da F&aacute;rmacia </h3>
    <div class="msg" id="msg"></div>
    <table>
        <tr>
            <td width="200px">M&ecirc;s</td>
            <td><select class="custom-select" name="mes">
                    <option  <?php print $selecionado1; ?> value="1">janeiro</option>
                    <option <?php print $selecionado2; ?> value="2">fevereiro</option>
                    <option <?php print $selecionado3; ?> value="3">mar&ccedil;o</option>
                    <option <?php print $selecionado4; ?> value="4">abril</option>
                    <option <?php print $selecionado5; ?> value="5">maio</option>
                    <option <?php print $selecionado6; ?> value="6">junho</option>
                    <option <?php print $selecionado7; ?> value="7">julho</option>
                    <option <?php print $selecionado8; ?> value="8">agosto</option>
                    <option <?php print $selecionado9; ?> value="9">setembro</option>
                    <option <?php print $selecionado10; ?> value="10">outubro</option>
                    <option <?php print $selecionado11; ?> value="11">novembro</option>
                    <option <?php print $selecionado12; ?>  value="12">dezembro</option>
                </select></td>
        </tr>
        <tr><td>N&uacute;meros de Discentes </td>
            <td><input class="form-control"type="text" name="di" size="5" value='<?php print $f->getNAlunos(); ?>' onchange="Soma();"
                       onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr><td>N&uacute;meros de Docentes </td>
            <td><input class="form-control"type="text" name="doc" size="5" value='<?php print $f->getNProfessores(); ?>'
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' /> </td>
        </tr>
        <tr><td>N&uacute;meros de Pesquisadores </td>
            <td><input class="form-control"type="text" name="pq" size="5"
                       value='<?php print $f->getNPesquisadores(); ?>' onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr><td>N&uacute;meros de Visitantes</td>
            <td><input class="form-control"type="text" name="nv" size="5"
                       value='<?php print $f->getNVisitantes(); ?>' onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr><td>Total Geral</td><td><b id='totalgeral'></b></td></tr>
    </table>

    <input class="form-control"name="operacao" type="hidden" value="A" /><br/>
    <input type="button"onclick="direciona();" class="btn btn-info" value="Gravar" />&ensp;
    <input type="button"onclick="javascript:history.go(-1)" class="btn btn-info" value="Voltar" />
</form>
<script>
    window.onload = Soma();
</script>



