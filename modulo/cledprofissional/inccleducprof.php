<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
//session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[26]) {
    header("Location:index.php");
} else {
    $sessao = $_SESSION["sessao"];
    $nomeunidade = $sessao->getNomeUnidade();
    $codunidade = $sessao->getCodUnidade();
//	$responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnoBase();
//    $aplicacoes = $_SESSION["sessao"]->getAplicacoes();
//    if (!$aplicacoes[26]) {
//        $mensagem = urlencode(" ");
//        $cadeia = "location:../saida/erro.php?codigo=2&mensagem=" . $mensagem;
//        header($cadeia);
//        exit();
//    }
//	require_once('../../includes/dao/PDOConnectionFactory.php');
    require_once('dao/edprofissionallivreDAO.php');
    require_once('classes/edprofissionallivre.php');
    require_once('classes/tdmedprofissionallivre.php');
    require_once('dao/tdmedprofissionallivreDAO.php');
//	require_once('../../includes/classes/unidade.php');
    $unidade = new Unidade();
    $unidade->setCodunidade($codunidade);
    $unidade->setNomeunidade($nomeunidade);

    $tipos = array();
    $daot = new TdmedprofissionallivreDAO();
    $dao = new EdprofissionallivreDAO();
    $cont = 0;
    $rowst = $daot->buscaporunidade($codunidade);

    foreach ($rowst as $row) {
        $cont++;
        $tipos[$cont] = new Tdmedprofissionallivre();
        $tipos[$cont]->setCodigo($row['Codigo']);
        $tipos[$cont]->setCategoria($row['Categoria']);
    }

    $tamanho = count($tipos);
    $cont1 = 0;
    $rows = $dao->buscaeduc($anobase);
    foreach ($rows as $row) {
        $tipo = $row['Categoria'];
        for ($i = 1; $i <= $tamanho; $i++) {
            if ($tipos[$i]->getCodigo() == $tipo) {
                $cont1++;
                $tipos[$i]->adicionaItemEdprofl($row['Codigo'], $row['NomeCurso']
                        , $row['Ingressantes1'], $row['Ingressantes2'], $row['Matriculados1'], $row['Matriculados2'], $row['Aprovados1'], $row['Aprovados2'], $row['Concluintes1'], $row['Concluintes2'], $row['Ano']);
            }
        }
    }
}
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

    function valida() {
        var passou = false;
        if ((document.ea.cat.value == "")
                || (document.ea.nome.value == "")
                || (document.ea.qtding1.value == "")
                || (document.ea.qtding2.value == "")
                || (document.ea.qtdmatr1.value == "")
                || (document.ea.qtdmatr2.value == "")
                || (document.ea.qtdapr1.value == "")
                || (document.ea.qtdapr2.value == "")
                || (document.ea.qtdconc1.value == "")
                || (document.ea.qtdconc2.value == "")) {
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
    function direciona(botao) {
        if (valida()) {
            document.ea.action = "<?php echo Utils::createLink('cledprofissional','opcleducprof'); ?>";
            document.ea.submit();
        }

    }
</script>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active">Ed. Profissional e cursos livres</li>
		</ul>
	</div>
</head>
<div class="alert alert-danger" role="alert">
  Informar o total para cada especificação de língua (Alemão, Espanhol, Francês e Italiano).
</div>
<form class="form-horizontal" name="ea" id="ea" method="post">

    <h3 class="card-title">Educa&ccedil;&atilde;o Profissional e Cursos Livres</h3>
    <div class="msg" id="msg"></div>

    <table>
        <tr>
            <td>Tipo do curso</td>
            <td>
                <select class="custom-select" name="cat" >
                    <?php for ($i = 1; $i <= $tamanho; $i++) { ?>
                        <option value="<?php print $tipos[$i]->getCodigo(); ?>">
                            <?php print ($tipos[$i]->getCategoria()); ?></option>
                    <?php } ?></select>
            </td>
        </tr>
        <tr>
            <td>Nome do curso</td>
            <td><input class="form-control"type="text" name="nome" size="60" value='' maxlength="60" class="texto" /></td>
        </tr>
        <tr>
            <td>N&uacute;mero de Ingressantes no 1o. semestre</td>
            <td><input class="form-control"type="text" name="qtding1" size="5" maxlength="4"
                       value=''	onkeypress='return SomenteNumero(event)' />
            </td>
        </tr>
        <tr>
            <td>N&uacute;mero de Ingressantes no 2o. semestre</td>
            <td><input class="form-control"type="text" name="qtding2" size="5" value='' maxlength="4"
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>N&uacute;mero de Matriculados no 1o. semestre</td>
            <td><input class="form-control"type="text" name="qtdmatr1" size="5" maxlength="4"
                       value=''	onkeypress='return SomenteNumero(event)' />
            </td>
        </tr>
        <tr>
            <td>N&uacute;mero de Matriculados no 2o. semestre</td>
            <td><input class="form-control"type="text" name="qtdmatr2" size="5" maxlength="4"
                       value=''	onkeypress='return SomenteNumero(event)' />
            </td>
        </tr>
        <tr>
            <td>N&uacute;mero de Aprovados no 1o. semestre</td>
            <td><input class="form-control"type="text" name="qtdapr1" size="5" maxlength="4"
                       value=''	onkeypress='return SomenteNumero(event)' />
            </td>
        </tr>
        <tr>
            <td>N&uacute;mero de Aprovados no 2o. semestre</td>
            <td><input class="form-control"type="text" name="qtdapr2" size="5" maxlength="4"
                       value=''	onkeypress='return SomenteNumero(event)' />
            </td>
        </tr>
        <tr>
            <td>N&uacute;mero de Concluintes no 1o. semestre</td>
            <td><input class="form-control"type="text" name="qtdconc1" size="5" maxlength="4"
                       value=''	onkeypress='return SomenteNumero(event)' />
            </td>
        </tr>
        <tr>
            <td>N&uacute;mero de Concluintes no 2o. semestre</td>
            <td><input class="form-control"type="text" name="qtdconc2" size="5" maxlength="4"
                       value=''	onkeypress='return SomenteNumero(event)' />
            </td>
        </tr>
    </table>
    <input class="form-control"name="operacao" type="hidden" value="I" />
    <input type="button" class="btn btn-info" onclick="direciona(1);" value="Gravar"/>
</form>