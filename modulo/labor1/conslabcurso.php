<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');

//session_start(); - Sessão já iniciada

$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[7]) {
    header("Location:index.php");
} else {
    //$sessao = $_SESSION["sessao"];	
	$nomeunidade = $sessao->getNomeunidade();
    $codunidade = $sessao->getCodunidade();
    // $responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnobase();
    // require_once('../../includes/dao/PDOConnectionFactory.php');
    // var_dump($usuario);
    require_once('dao/labcursoDAO.php');
    require_once('classes/labcurso.php');
    // require_once('../../includes/classes/campus.php');
    // require_once('../../includes/classes/curso.php');
    // require_once('../../includes/classes/unidade.php');
    require_once('classes/laboratorio.php');
    require_once('classes/tplaboratorio.php');
    require_once('dao/laboratorioDAO.php');
    //busca unidade
    $unidade = new Unidade();
    $unidade->setCodunidade($codunidade);
    $unidade->setNomeunidade($nomeunidade);
    $codlab = $_GET["codlab"];
    $daolab=new LaboratorioDAO();
    $rows=$daolab->buscaLaboratorio($codlab);
    $dono=true; // dono da informação
    $ano = 0;
    foreach ($rows as $row) 
    {
    	if ($row["CodUnidade"]!=$codunidade) 
    	{
    		$dono = false;
    	}
    	$ano = $row["AnoAtivacao"];
    }
    
    if (!$dono) 
    {
    	sleep(2);
    	Flash::addFlash("Você não pode vincular cursos para laboratórios de outra unidade");
    	Utils::redirect('labor', 'consultalab');
     }     
    
    
    if (is_numeric($codlab) && $codlab != "") 
    {
        $daolabcurso = new LabcursoDAO();        
        $rows = $daolab->buscaLaboratorio($codlab);
        foreach ($rows as $row) {
            //cria tipo do lab para criar lab
            $tipolab = new Tplaboratorio();
            $tipolab->setCodigo($row["Tipo"]);
            $lab = $unidade->criaLab($codlab, $tipolab, $row["Nome"], null, null, null, null, null, null, null, null, null, null, null, null);
    }
        $cont = 0;
        //busca cursos do lab
        $rows = $daolabcurso->buscaCursosLaboratorio2($lab,$anobase);
        foreach ($rows as $row) {
            $campus = new Campus();
            $campus->setCodigo($row['CodCampus']);
            $campus->setNome($row['Campus']);
            $curso = $campus->criaCurso($row["CodCursoSis"], $unidade, $row["CodCurso"], $row["NomeCurso"], $row["DataInicio"], $row["CodEmec"]);
            $lab->adicionaItemLabcursos($row["CodLabCurso"], $curso);
            $cont++;
        }
        //var_dump($lab->getLabcursos());
        $daolab->fechar();
    }
}
//ob_end_flush();
?>
<head>
<div class="bs-example">
		<ul class="breadcrumb">
		    <li><a href="<?php echo Utils::createLink("labor", "consultalab"); ?>">Consultar laboratórios</a></li>
			<li class="active">Laboratório por Curso</li>
		</ul>
	</div>
</head>
<form class="form-horizontal" name="fconsultar" method="post">
    <h3 class="card-title">Cursos - Laborat&oacute;rio</h3><br/>
    <b>Nome do laboratório:</b>
    <?php echo ($lab->getNome()); ?>
    <br /> <input class="form-control"type="hidden" name="codlab"
                  value="<?php print $lab->getCodlaboratorio(); ?>" /> <input
                  type="hidden" name="nomelab" value="<?php print $lab->getNome(); ?>" />
    <br />
    <b>Cursos:</b><br />
    <?php if ($cont > 0) { ?>
        <table id="tablesorter" class="table table-bordered table-hover">
            <tr align="center">
                <th>Campus</th>
                <th>C&oacute;digo Emec</th>
                <th>Curso</th>
                <th>Excluir</th>
            </tr>
            <?php foreach ($lab->getLabcursos() as $lab1) { ?>
                <tr>
                    <td><?php print $lab1->getCurso()->getCampus()->getNome(); ?></td>
                    <td><?php print $lab1->getCurso()->getCodemec(); ?></td>
                    <td><?php print ($lab1->getCurso()->getNomecurso()); ?></td>
                    <td align="center">
                        <a href="<?php echo Utils::createLink('labor', 'dellabcurso', array('codlabcurso' => $lab1->getCodlabcurso(), 'codlab' => $lab1->getLaboratorio()->getCodlaboratorio())); ?>" target="_self" ><img src="webroot/img/delete.png" alt="Excluir" width="19" height="19" /> </a>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <?php
    } else {
        print "Nenhum curso vinculado ao laborat&oacute;rio.";
    }
    ?> <br/><br/><br/>
    <input class="form-control"value="Vincular curso" class="btn btn-info" type="button" onclick="direcionacurso(1,<?php echo $codlab;?>);" />&ensp;&ensp;
    <a href="<?php echo Utils::createLink("labor", "consultalab"); ?>">
        <input type="button" class="btn btn-info" onclick="javascript:history.go(-1);" value="Voltar"  />
    </a>
</form>