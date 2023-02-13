<?php
define(BASE_DIR, dirname(__FILE__) . DIRECTORY_SEPARATOR);
?>
<?php
session_start();
$sessao = $_SESSION['sessao'];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[2]){
    header("Location:index.php");
}
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
$anobase = $sessao->getAnobase();

require_once(BASE_DIR . 'dao/acaoDAO.php');
require_once(BASE_DIR . 'classes/programa.php');
require_once(BASE_DIR . 'classes/acao.php');
//require_once('../../../includes/classes/unidade.php');
$cont = 0;
$codigo = $_GET["codigo"];
if (($codigo != "a-z, A-Z") && ($codigo != "")) {
    $unidade = new Unidade();
    $unidade->setCodunidade($codunidade);
    $unidade->setNomeunidade($nomeunidade);

    $dao = new AcaoDAO();
    $p = new Programa();
    $rows = $dao->buscacodigo($codigo);
    foreach ($rows as $row) {
        $cont++;
        $p->setCodigo($row["pcodigo"]);
        $p->setNome($row["pnome"]);
        $p->setCodprograma($row["CodigoPrograma"]);
        $p->criaAcao($codigo, $unidade, $row["CodigoAcao"], $row["Nome"], $row["Finalidade"], $row["Descricao"], $row["AnaliseCritica"], $anobase);
    }

    $dao->fechar();
}
?>
<script language="JavaScript">
    function valida() {
        var passou = false;
        if (document.facao.analise.value == "") {
            document.getElementById('msg').innerHTML = "Preencha o campo An&aacute;lise Cr&iacute;tica";
            passou = true;
        }

        if (passou) {
            return false;
        }
        else {
            return true;
        }
    }

    function conta() {
        numCaracteres = document.facao.analise.value.length;
        document.getElementById('conta').innerHTML = numCaracteres;

    }

    function direciona(botao) {
        switch (botao) {
            case 1:
                if (valida()) {
                    document.getElementById('facao').action = "<?php echo Utils::createLink('simec', 'opacao'); ?>";
                    document.getElementById('facao').submit();
                }
                break;
            case 2:
                document.getElementById('facao').action = "../../saida/saida.php";
                document.getElementById('facao').submit();
                break;
        }

    }
</script>
<form class="form-horizontal" name="facao" id="facao" method="post">
    <h3 class="card-title">Ação da Unidade </h3>
    <div class="msg" id="msg"></div>

    <table>
        <tr>
            <td>Programa</td><td><input class="form-control"readonly="readonly" size=100 name="prog" value="<?php print ($p->getNome()); ?>" /></td>
        </tr>
        <tr>
            <td>Código da a&ccedil;&atilde;o</td><td><input class="form-control"name="acao" readonly="readonly" size=50 value="<?php print ($p->getAcao()->getCodacao()); ?>" /></td>
        </tr>
        <tr>
            <td>Nome da a&ccedil;&atilde;o</td><td><input class="form-control"name="nomeacao" readonly="readonly" size=100 value="<?php print ($p->getAcao()->getNome()); ?>" />
            </td>
        </tr>
        <tr>
            <td align="left">Finalidade</td><td><textarea name="finalidade" rows=4 cols="80" readonly="readonly"><?php print ($p->getAcao()->getFinalidade()); ?></textarea></td>
        </tr>
        <tr>
            <td align="left">Descri&ccedil;&atilde;o</td><td><textarea name="descricao" rows=4 cols="80" readonly="readonly"><?php print ($p->getAcao()->getDescricao()); ?></textarea></td>
        </tr>
    </table>
    <table width="700px" align="left">
        <tr><td width="100px"></td><td style="font-weight: bold;">An&aacute;lise cr&iacute;tica - Orientações</td>	</tr>
        <tr><td width="100px"></td><td>1. Principais resultados: informar os avan&ccedil;os  conquistados considerando as metas e os produtos da a&ccedil;&atilde;o e dos recursos humanos utilizados na sua execu&ccedil;&atilde;o.</td></tr>
        <tr><td width="100px"></td><td>Meta F&iacute;sica: qual a meta prevista e a meta alcançada, caso n&atilde;o tenha atingido a meta prevista expor motivos</td></tr>
        <tr><td width="100px"></td><td>Meta Financeira: qual a meta prevista e a meta alcan&ccedil;ada, caso n&atilde;o tenha atingido a meta prevista expor motivos </td>	</tr>
        <tr><td width="100px"></td><td>2. Principais problemas: insucessos e provid&ecirc;ncias adotadas e a adotar para corrigi-los e os respons&aacute;veis pelas respectivas medidas corretivas.</td></tr>
        <tr><td width="100px"></td><td>3. Contrata&ccedil;&otilde;es e parcerias: realizadas no exerc&iacute;cio e sua import&acirc;ncia para viabilizar a a&ccedil;&atilde;o e o alcance dos resultados.</td></tr>
        <tr><td width="100px"></td><td>4. Transfer&ecirc;ncia: relatar a import&acirc;ncia de recursos recebidos vinculados &agrave; a&ccedil;&atilde;o, que demonstrem ampliar a abrang&ecirc;ncia da a&ccedil;&atilde;o governamental (vantagens de desvantagens da eventual descentraliza&ccedil;&atilde;o de recursos, crit&eacute;rios utilizados para a an&acute;lise e aprova&ccedil;&atilde;o do repasse de recursos, problemas relativos &agrave; inadimpl&ecirc;ncia quanto a recursos transferidos, complementados por informa&ccedil;&otilde;es sobre as provid&ecirc;ncias tomadas para evitar perdas e/ou reaver valores.</td></tr>
    </table>
    <table>
        <tr>
            <td>An&aacute;lise Cr&iacute;tica</td>
            <td align="left"><textarea name="analise" onkeydown="conta();" id="analise" rows="25" cols="80"><?php print $p->getAcao()->getAnalisecritica(); ?></textarea>
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td>N&uacute;mero de caracteres:</td>
            <td><div id="conta"></div></td>
        </tr>
    </table>
    <input class="form-control"name="operacao" type="hidden" value="A" /> <input class="form-control"type="hidden" name="codigoacao" value="<?php echo $p->getAcao()->getCodigo(); ?>" />
    <input class="form-control"type="hidden" name="codigoprog"
           value="<?php echo $p->getCodigo(); ?>" />
    <input type="button" onclick="direciona(1);" value="Gravar" />

</form>
