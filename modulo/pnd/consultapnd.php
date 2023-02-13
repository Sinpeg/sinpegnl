<?php
//require_once('../../includes/classes/sessao.php');
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[27]) {
    header("Location:index.php");
}
//$sessao = $_SESSION["sessao"];;
//$nomeunidade = $sessao->getNomeUnidade();
//$codunidade = $sessao->getCodUnidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnoBase();
//require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('dao/pndDAO.php');
require_once('classes/pnd.php');
//require_once('../../includes/classes/curso.php');
$codcurso = $_GET["codcurso"];
$nomecurso = $_GET["nomecurso"];
if ($codcurso != "" && is_numeric($codcurso) && is_string($nomecurso) && $nomecurso != "") {
    $curso = new Curso();
    $curso->setCodcurso($codcurso);
    $curso->setNomecurso($nomecurso);
    $dao = new PndDAO();
    $cont = 0;
    $rows = $dao->buscacursoano($codcurso, $anobase);
    foreach ($rows as $row) {
        $cont++;
        $curso->criaPnd($row['Codigo'], $row['Nopnd'], $row['Noatendidos'], $row['Ano']);
    }
    $dao->fechar();
    if ($cont == 0) {
        Utils::redirect('pnd', 'incpnd', array('codcurso' => $codcurso, 'nomecurso' => $nomecurso));
    }
}
?>
<form class="form-horizontal" name="pi" id="pi" method="POST" action="<?php echo Utils::createLink('pnd', 'altpnd'); ?>">
    <h3 class="card-title"> Portadores de Necessidades Especiais </h3>
    <div class="msg" id="msg"></div>
    <table>
        <tr><th>Curso</th><th><?php echo $nomecurso; ?></th></tr>
        <tr><td>N&uacute;mero de discentes portadores de necessidades especiais</td>
            <td><?php print $curso->getPnd()->getNopnd(); ?></td></tr>
        <tr><td>N&uacute;mero de pessoas atensdias por tecnologias educacionais e sociais</td>
            <td><?php print $curso->getPnd()->getNoatendidos(); ?></td></tr>
    </table>
    <input class="form-control"name="operacao" type="hidden" value="A" />
    <input class="btn btn-info" type="submit"  value="Alterar" />
    <input class="form-control"type="hidden" name="codigo" value="<?php print $curso->getPnd()->getCodigo(); ?>" />
    <input class="form-control"type="hidden" name="codcurso" value="<?php print $curso->getCodcurso(); ?>" />
    <input class="form-control"type="hidden" name="nomecurso" value="<?php print $curso->getNomecurso(); ?>" />
</form>

