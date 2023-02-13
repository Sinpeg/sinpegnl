<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');

$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[11]) {
    header("Location:index.php");
}  else {
//    $sessao = $_SESSION["sessao"];
    $nomeunidade = $sessao->getNomeunidade();
    $codunidade = $sessao->getCodunidade();
//	$responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnobase();

//	require_once('../../includes/dao/PDOConnectionFactory.php');
//	require_once('../../includes/dao/cursoDAO.php');
//	require_once('../../includes/classes/curso.php');
//	require_once('../../includes/classes/unidade.php');

    $unidade = new Unidade();
    $unidade->setCodunidade($codunidade);
    $unidade->setNomeunidade($nomeunidade);

    require_once('dao/tptecnassistDAO.php');
    require_once('classes/tipotecnologiassistiva.php');

    require_once('dao/tecnassistivaDAO.php');
    require_once('classes/tecnologiassistiva.php');

    $tiposta = array();
    $cont = 0;
    $daotipotecno = new TptecnassistDAO();
    $daota = new TecnassistivaDAO();
    $daocur = new CursoDAO();


    $rows_tta = $daotipotecno->Lista();
    foreach ($rows_tta as $row) {
        $tiposta[$cont] = new Tipotecnologiassistiva();
        $tiposta[$cont]->setCodigo($row['Codigo']);
        $tiposta[$cont]->setNome($row['Nome']);
        $cont++;
    }
    $cont1 = 0;
    $codcurso = $_GET["codcurso"];

    if (is_string($codcurso)) {
        $rows_cur = $daocur->buscacurso($codcurso);
        $conttacurso = 0;
        foreach ($rows_cur as $row) {
            $curso = $unidade->criaCurso($row['CodCampus'], $row['CodCursoSis'], $row['CodCurso'], $row['NomeCurso'], $row['DataInicio'], $row['CodEmec']);
            $rows_ta = $daota->buscatacurso($codcurso, $anobase);
            foreach ($rows_ta as $row1) {
                $tipo = $row1['Tipo'];
                foreach ($tiposta as $tipota) {
                    if ($tipota->getCodigo() == $tipo) {
                        $conttacurso++;
                        $tipota1 = $tipota;
                    }
                }

                $curso->adicionaItemTacursos($curso, $anobase, $tipota1, $row1["CodTecnologiaAssistiva"]);
            }
        }
        $daocur->fechar();
        if ($conttacurso == 0) {
            echo Utils::redirect('tecnol', 'incluitecnol', array('codcurso' => $codcurso));
        }
    }
}
//ob_end_flush();
?>
<?php echo Utils::deleteModal('Tecnologia Assistiva', 'Você tem certeza que deseja remover a tecnologia assistiva?'); ?>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
            <li class="active">
                <a href="<?php echo Utils::createLink("tecnol", "cursounidades"); ?>">Tecnologia assistiva</a>
                <i class="fas fa-long-arrow-alt-right"></i>
                <a href="<?php echo Utils::createLink("tecnol", "incluitecnol", array('codcurso'=>$curso->getCodcurso(), 'nomecurso'=>$curso->getNomecurso())); ?>">Incluir</a>
                <i class="fas fa-long-arrow-alt-right"></i>
                Tecnologias utilizadas pelo curso
            </li>
		</ul>
	</div>
</head>

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Tecnologia assistiva</h3>
    </div>
    <form class="form-horizontal" method="POST" action="index.php?modulo=tecnol&amp;acao=incluitecnol">
        <div class="card-body">
            Curso:
            <?php print $_GET["nomecurso"]; ?>
            <input class="form-control"name="codcurso" type="hidden" value="<?php print $codcurso ?>" />
            <br />
            <?php if ($conttacurso > 0) { ?>
                <br/>
                Tecnologias assistivas utilizadas pelo curso:<br/><br/>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <tr><th>C&oacute;digo</th>
                            <th>Tipo da tecnologia</th>
                            <th>Excluir</th>
                        </tr>
                        <?php foreach ($curso->getTacursos() as $ta_docurso) { ?>
                            <tr><td><?php print $ta_docurso->getTipota()->getCodigo(); ?></td>
                                <td><?php print ($ta_docurso->getTipota()->getNome()); ?></td>

                                <td align="center">
                                    <a href="<?php echo Utils::createLink('tecnol', 'deltecnol', array('codcurso' => $curso->getCodcurso(), 'codtta' => $ta_docurso->getTipota()->getCodigo(), 'codta' => $ta_docurso->getCodtecnologiaassistiva())); ?>" class="delete-link" target="_self" ><img src="webroot/img/delete.png" alt="Excluir" width="19" height="19" /> </a>
                                </td></tr>
                        <?php } ?>
                    </table>
                </div>
                <?php
            } else {
                print "Nenhuma tecnologia registrada para este curso.";
            }
            ?>
        </div>
        <table class="card-body">
            <tr>
                <td align="center">
                    <br>
                    <input class="btn btn-info" class="btn btn-info" type="submit"  value="Incluir" />
                </td>
            </tr>
        </table>
    </form>
</div>