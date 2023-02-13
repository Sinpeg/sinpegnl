<?php
//ob_start();
//echo ini_get('display_errors');
//
//if (!ini_get('display_errors')) {
//    ini_set('display_errors', 1);
//    ini_set('error_reporting', E_ALL & ~E_NOTICE);
//}
?>
<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[19]) {
    header("Location:index.php");
} else {
//	require_once('../../includes/dao/PDOConnectionFactory.php');
    require_once('classes/atividadeextensao.php');
    require_once ('dao/atividadeextensaoDAO.php');
    $sessao = $_SESSION["sessao"];
    $nomeunidade = $sessao->getNomeunidade();
    $codunidade = $sessao->getCodunidade();
    $responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnobase();
//	require_once('../../includes/classes/unidade.php');
    $unidade = new Unidade();
    $unidade->setCodunidade($codunidade);
    $unidade->setNomeunidade($nomeunidade);
    $codigo = $_GET["codigo"];
    if (is_numeric($codigo)) {
        $cont = 0;
        $daoae = new atividadeextensaoDAO();
        $atividadeextensao = new Atividadeextensao();
        $rows_ae = $daoae->buscaae($codigo);
        foreach ($rows_ae as $row) {
            $unidade->criaAtividadeextensao($row['Codigo'], $row['CodSubunidade'], $row['Tipo'], $row['Quantidade'], $row['Participantes'], $row['PesAtendidas'], $row['Ano']);
        }
        if ($unidade->getAtividadeextensao()->getSubunidade() == 1001) {
            $selecionado1 = "selected=selected";
            $selecionado2 = "";
        } else {
            $selecionado2 = "selected=selected";
            $selecionado1 = "";
        }
        $daoae->fechar();
    }
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
                document.getElementById('msg').innerHTML = "Todos os campos devem conter apenas números.";
                return false;
            }
        }
    }
    function direciona(botao) {
        switch (botao) {
            case 1:
                if (valida()) {
                    document.getElementById('pe').action = "<?php echo Utils::createLink('atividades', 'opatividadeextensao'); ?>";
                    document.getElementById('pe').submit();
                }
                break;
            case 2:
                document.getElementById('pe').action = "../saida/saida.php";
                document.getElementById('pe').submit();
                break;
        }
    }
    function valida() {
        var passou = false;
        if ((document.pe.qtd.value == "") || (document.pe.qtdPart.value == "") || (document.pe.qtdAten.value == "")) {
            document.getElementById('msg').innerHTML = "Todos os campos s&atilde;o obrigat&oacute;rios.";
            passou = true;
        }

        if (passou) {
            return false;
        }
        else {
            return true;
        }
    }
</script>

<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li><a href="<?php echo Utils::createLink("atividades", "incuiatividadeextensao"); ?>" >Atividades de extens&atilde;o</a></li>
			<li><a href="<?php echo Utils::createLink("atividades", "consultaatividadeextensao"); ?>" >Consulta</a>
		    <li class="active">Alterar</li>
		</ul>
	</div>
</head>

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title"> Atividades de Extens&atilde;o</h3>
    </div>
    <form class="form-horizontal" name="pe" id="pe" method="post">
        <div class="msg" id="msg"></div>
        <table width="500px">
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
                <td>Tipo</td>
                <td><select class="custom-select" name="tipo">
                        <option value="1">Programas</option>
                        <option value="2">Projetos</option>
                    </select></td>
            </tr>
            <tr>
                <td>Quantidade</td>
                <td><input class="form-control"type="text" maxlength="5" name="qtd" size="5"
                        value='<?php echo $unidade->getAtividadeextensao()->getQuantidade() ?>'
                        onkeypress='return SomenteNumero(event)' /></td>
            </tr>
            <tr>
                <td>Participantes</td>
                <td><input class="form-control"type="text" maxlength="5" name="qtdPart" size="5"
                        value='<?php echo $unidade->getAtividadeextensao()->getParticipantes(); ?>'
                        onkeypress='return SomenteNumero(event)' /></td>
            </tr>
            <tr>
                <td>Pessoas Atendidas</td>
                <td><input class="form-control"type="text" maxlength="5" name="qtdAten" size="5"
                        value='<?php echo $unidade->getAtividadeextensao()->getAtendidas(); ?>'
                        onkeypress='return SomenteNumero(event)' /></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input class="form-control"type="hidden" name="codigo" value="<?php print $codigo; ?>" />
                    <input	type="button" id="gravar" onclick='direciona(1);' value="Gravar" />
                </td>
            </tr>
        </table>

    </form>
</div>