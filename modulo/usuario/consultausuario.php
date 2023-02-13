<?php
//require_once('../../includes/classes/sessao.php');
//session_start();
if (!isset($_SESSION["sessao"])) {
//	header("location:../../index.php");
    exit();
}
$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeUnidade();
$codunidade = $sessao->getCodUnidade();
$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnoBase();
$aplicacoes = $sessao->getAplicacoes();
$loginsessao = $sessao->getLogin();

if (!$aplicacoes[3]) {
    $mensagem = urlencode(" ");
    //$cadeia = "location:../saida/erro.php?codigo=2&mensagem=" . $mensagem;
    header($cadeia);
    exit();
}
$cont = 0;
?>

<script language="javascript">
    function direciona(botao) {
        switch (botao) {
            case 1:
                if (valida()) {
                    document.getElementById('us').action = "<?php echo Utils::createLink('usuario', 'opusuario'); ?>";
                    document.getElementById('us').submit();
                }
                break;
            case 3:
                document.getElementById('us').action = "<?php echo Utils::createLink('usuario', 'incusuario'); ?>";
                document.getElementById('us').submit();
                break;
        }
    }
</script>

<head>
	<div class="bs-example">
		<ul class="breadcrumb">
		    <li><a href="<?php echo Utils::createLink("usuario", "incusuario"); ?>">Cadastro de usu&aacute;rios</a></li>
		    <li class="active">Consulta</li>
		</ul>
	</div>
</head>

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Usu&aacute;rios cadastrados</h3><br />
    </div>
    <form class="form-horizontal" name="us" id="us" method="post">
        
        <div class="msg" id="msg"></div>
        
        <div class="msg" id="msg1"></div>
        
        <table class="card-body">
            <tr>
                <td class="coluna1">Unidade</td>
            </tr>
            <tr>
                <td class="coluna2"><input class="form-control"name='parametro' type='text'  size="60" value="" onkeydown="ajaxlogins();"/></td>
            </tr>
        </table>
        
        <div class="msg" id="tabela"></div>

        <table class="card-body">
            <?php if ($loginsessao == "admin") { ?>
                <input type="button" onclick='direciona(3);' value="Incluir" />
            <?php } ?>
        </table>
    </form>
</div>

