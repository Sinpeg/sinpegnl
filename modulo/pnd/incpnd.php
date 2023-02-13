<?php
//require_once('../../includes/classes/sessao.php');
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[27]) {
    header("Location:index.php");
}
//$nomeunidade = $sessao->getNomeUnidade();
//$codunidade = $sessao->getCodUnidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnoBase();
//require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('dao/pndDAO.php');
require_once('classes/pnd.php');
//require_once('../../includes/classes/curso.php');
$codcurso = addslashes($_GET["codcurso"]);
$nomecurso = addslashes($_GET["nomecurso"]);
if ($codcurso != "" && is_numeric($codcurso) && is_string($nomecurso) && $nomecurso != "") {
    $curso = new Curso();
    $curso->setCodcurso($codcurso);
    $curso->setNomecurso($nomecurso);
    $dao = new PndDAO();
    $rows = $dao->buscacursoano($codcurso, $anobase);
    $passou = false;
    foreach ($rows as $row) {
        $passou = true;
        $curso->criaPnd($row['Codigo'], $row['Nopnd'], $row['Noatendidos'], $row['Ano']);
    }
    $dao->fechar();
    if ($passou) {
        Utils::redirect('pnd', 'consultapnd', array('codcurso' => $codcurso, 'nomecurso' => $nomecurso));
    }
}
?>
<form class="form-horizontal" name="pi" id="pi" method="post" action="<?php echo Utils::createLink('pnd', 'oppnd'); ?>">
    <h3 class="card-title">Portadores de Necessidades Especiais</h3>
    <div class="msg" id="msg"></div>

    <table>
        <tr>
            <td width="300px">Curso</td>
            <td><?php echo $nomecurso; ?></td>
        </tr>
        <tr>
            <td width="300px">N&uacute;mero de discentes portadores de
                necessidades especiais</td>
            <td><input class="form-control"type="text" name="nopnd" maxlength="4" size="4" />
            </td>
        </tr>
        <tr>
            <td width="300px">N&uacute;mero de pessoas atendias por tecnologias
                educacionais e sociais</td>
            <td><input class="form-control"type="text" name="noatendidos" maxlength="4" value=""
                       size="4" /></td>
        </tr>
    </table>
    <input class="form-control"name="operacao" type="hidden" value="I" />
    <input class="btn btn-info" type="submit"  value="Gravar" />
    <input class="form-control"type="hidden" name="codcurso" value="<?php print $codcurso ?>" />
    <input class="form-control"type="hidden" name="nomecurso" value="<?php print $nomecurso ?>" />
</form>