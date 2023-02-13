<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
//require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('classes/atividadeextensao.php');
require_once ('dao/atividadeextensaoDAO.php');
//session_start();
//session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[19]) {
    header("Location:index.php");
}
//$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeUnidade();
$codunidade = $sessao->getCodUnidade();
$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnoBase();
$lock = new Lock();
$daounid = new UnidadeDAO();
$atividadeextensao = array();
$atividadeextensao = new Atividadeextensao();
$cont = 0;
$daoae = new atividadeextensaoDAO();
$rows_ae = $daoae->buscaaeunidade($codunidade, $anobase);

foreach ($rows_ae as $row) {
    $cont++;
    $atividadeextensao->setCodigo($row['Codigo']);
    $atividadeextensao->setTipo($row['Tipo']);
    $atividadeextensao->setSubunidade($row['CodSubunidade']);
    $atividadeextensao->setQuantidade($row['Quantidade']);
    $atividadeextensao->setParticipantes($row['Participantes']);
    $atividadeextensao->setAtendidas($row['PesAtendidas']);
    $atividadeextensao->setAno($row['Ano']);
}

$rowscodsup = $daounid->RetornaCodUnidadeSuperior($cpga);
foreach ($rowscodsup as $row)
{
	$codunidadesup = $row['CodUnidade'];
}


if (!$sessao->isUnidade())
{
	$rows_verifica = $daoae->buscaaeunidade($codunidadesup, $anobase);
	foreach ($rows_verifica as $row)
	{
		if ($rows_verifica->rowCount() > 0)
		{
			$lock->setLocked(true);
		}
	}
}

$daoae->fechar();

if ($cont == 4) {
    Utils::redirect('atividades', 'consultaatividadeextensao');
}

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
		    <li class="active">Atividades de extens&atilde;o</li>
		</ul>
	</div>
</head>

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title"> Atividades de Extens&atilde;o</h3>
    </div>

    <form class="form-horizontal" name="pe" id="pe" method="post">
        <div class="msg" id="msg"></div>
        <table>
            <tr>
                <td>Subunidade</td>
                <td>
                    <select class="custom-select" name="subunidade">
                        <?php if ($sessao->isUnidade()) { ?>
                            <option value="1001">Escola de M&uacute;sica</option>
                            <option value="1004">Escola de Teatro e Dan&ccedil;a</option>
                        <?php } else {?>
                            <option value="<?php echo $codunidade ?>"><?php echo $nomeunidade ?></option>
                        <?php } ?>    
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tipo</td>
                <td>
                    <select class="custom-select" name="tipo">
                        <option value="1">Programa</option>
                        <option value="2">Projeto</option>
                    </select></td>
            </tr>
            <tr>
                <td>Quantidade</td>
                <td>
                    <input class="form-control"type="text" maxlength="5" name="qtd" size="5" value=''
                        onkeypress='return SomenteNumero(event)' />
                </td>
            </tr>
            <tr>
                <td>Participantes</td>
                <td>
                    <input class="form-control"type="text" maxlength="5" name="qtdPart" size="5"
                        value='' onkeypress='return SomenteNumero(event)' />
                </td>
            </tr>
            <tr>
                <td>Pessoas Atendidas</td>
                <td>
                    <input class="form-control"type="text" maxlength="5" name="qtdAten" size="5"
                        value='' onkeypress='return SomenteNumero(event)' />
                </td>
            </tr>
        </table>
        <?php if (!$lock->getLocked()){ ?>
            <input class="form-control"name="operacao" type="hidden" value="I" />
            <input type="button" onclick='direciona(1);' value="Gravar" />
        <?php } ?>
    </form>
</div>

