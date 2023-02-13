<?php
//ob_start();
//echo ini_get('display_errors');
//if (!ini_get('display_errors')) {
//    ini_set('display_errors', 1);
//    ini_set('error_reporting', E_ALL & ~E_NOTICE);
//}
?>
<?php
//require_once('../../includes/classes/sessao.php');
//session_start();
$sessao = $_SESSION['sessao'];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[4]) {
    header("Location:index.php");
}else {
    $nomeunidade = $sessao->getNomeunidade();
    $codunidade = $sessao->getCodunidade();
//    echo $codunidade;
//    $responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnobase();
    //buscar cursos
//    require('../../includes/dao/PDOConnectionFactory.php');
//    require('../../includes/dao/cursoDAO.php');
//    require('../../includes/classes/curso.php');
//    require('../../includes/classes/campus.php');
//    require('../../includes/classes/unidade.php');
    $daocurso = new CursoDAO();
    $unidade = new Unidade();
    $unidade->setCodunidade($codunidade);
    $unidade->setNomeunidade($nomeunidade);
    $rows = $daocurso->buscacursosUnidade($codunidade, $anobase);
    $cont = 0;
    foreach ($rows as $row) {
        $campus = new Campus;
        $campus->setCodigo($row["codcampus"]);
        $campus->setNome($row["nomecampus"]);
        $unidade->adicionaItemCursos($campus, $row['CodCursoSis'], $row['CodCurso'], $row['NomeCurso'], $row['DataInicio'], $row['CodEmec']);
        $cont++;
    }
    $daocurso->fechar();
//    ob_end_flush();
}
?>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
		    <li class="active">Produção Intelectual</li>
		</ul>
	</div>
</head>
<h3 class="card-title">Produção Intelectual - Cursos</h3>
<table id="tablesorter" class="table table-bordered table-hover">
                    <tfoot>
            <tr>
                <th colspan="7" class="ts-pager form-horizontal">
                    <button type="button" class="btn first"><i class="icon-step-backward glyphicon glyphicon-step-backward"></i></button>
                    <button type="button" class="btn prev"><i class="icon-arrow-left glyphicon glyphicon-backward"></i></button>
                    <span class="pagedisplay"></span> <!-- this can be any element, including an input class="form-control"-->
                    <button type="button" class="btn next"><i class="icon-arrow-right glyphicon glyphicon-forward"></i></button>
                    <button type="button" class="btn last"><i class="icon-step-forward glyphicon glyphicon-step-forward"></i></button>
                    <select class="custom-select" title="Select page size">
                        <option selected="selected" value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                    </select>
                    <select class="pagenum input-mini" title="Select page number"></select>
                </th>
            </tr>
        </tfoot>
    <thead>
        <tr>
            <th align="center">Campus</th>
            <th align="center">Código Emec</th>
            <th align="center">Nome do curso</th>
            <th align="center">Visualizar</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($unidade->getCursos() as $curso) { ?>
            <tr>
                <td><?php print $curso->getCampus()->getNome(); ?>
                </td>
                <td><?php print $curso->getCodemec(); ?>
                </td>
                <td><?php print $curso->getNomecurso(); ?>
                <td align="center"><a href="<?php echo Utils::createLink('prodintelectual', 'consultaprodintelectual', array('codcurso' => $curso->getCodcurso(), 'nomecurso' => $curso->getNomecurso())); ?>" target="_self"> <img src="webroot/img/busca.png" alt="Visualizar" width="19" height="19" />
                    </a>
                </td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>