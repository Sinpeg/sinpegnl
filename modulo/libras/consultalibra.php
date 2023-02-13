<?php
//require_once('../../includes/classes/sessao.php');
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[12]) {
    header("Location:index.php");
} 
//$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnobase();
//buscar cursos
//require('../../includes/dao/PDOConnectionFactory.php');
require('dao/librasDAO.php');
//require('../../includes/classes/campus.php');
require('classes/libras.php');
//require('../../includes/classes/curso.php');
//require('../../includes/classes/unidade.php');;

$daolibras = new LibrasDAO();
$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$rows = $daolibras->buscaCursosLibras($codunidade, $anobase);
$cont = 0;
foreach ($rows as $row) {
    $campus = new Campus;
    $campus->setCodigo($row["codcampus"]);
    $campus->setNome($row["nomecampus"]);

    $unidade->adicionaItemCursosLibras($campus, $row['CodCursoSis'], $row['CodCurso'], $row['NomeCurso'], $row['DataInicio'], $row['CodEmec'], $row['Codigo'], $anobase);
    $cont++;
}
$daolibras->fechar();

if ($cont == 0) {
    Utils::redirect('libras', 'incluilibra');
//    $cadeia = "location:incluilibra.php";
//    header($cadeia);
    //exit();
}
//ob_end_flush();
?>
<?php echo Utils::deleteModal('Libras', 'Você deseja remover o curso selecionado?'); ?>
<form class="form-horizontal" name="cunidades" id="cunidades" method="POST" action="<?php echo Utils::createLink('libras', 'incluilibra'); ?>">
    <h3 class="card-title">Libras no curr&iacute;culo</h3>
    Cursos da unidade que apresentam libras no curr&iacute;culo:<br /> <br />
    <table>
        <?php if ($cont > 0) { ?>
            <tr align="center" style="font-style: italic;">
                <th>Campus</th>
                <th>C&oacute;digo Emec</th>
                <th>Nome do curso</th>
                <th>Excluir</th>
            </tr>
        <?php } ?>
        <?php foreach ($unidade->getCursos() as $curso) { ?>
            <tr>
                <td> <?php print $curso->getCampus()->getNome(); ?> </td>
                <td> <?php print $curso->getCodemec(); ?>  </td>
                <td><?php print $curso->getNomecurso(); ?> </td>
                <td align="center">
                    <a href="<?php echo Utils::createLink('libras', 'dellibra', array('codcurso'=>$curso->getCodcurso(),'codlibra'=>$curso->getLibra()->getCodigo()));?>"  target="_self"  class="delete-link">
                        <img src="webroot/img/delete.png" alt="Excluir" width="19" height="19" />
                    </a>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <br /> <input class="btn btn-info" type="submit"  value="Identificar cursos" />
</form>