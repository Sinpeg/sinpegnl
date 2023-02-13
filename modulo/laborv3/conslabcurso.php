<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');

//session_start(); - Sessão já iniciada

$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[7]) {
    exit();
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
    $rows=$daolab->buscaLaboratorio($codlab,$anobase);
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
    
  //Inclusão para impedir a edição após vinculado

    // Trecho para bloqueio
    $lock = new Lock();
    // verifica a infraestrutura informada é da unidade
  
    if (!$dono) {
        $lock->setLocked(true);
        $botao=false;
    }

    if (!$sessao->isUnidade()) {
        $lock->setLocked(Utils::isApproved(7, $codunidadecpga, $codunidade, $ano!=0?$ano:$sessao->getAnobase()));
    }

    /*-------------------------------*/  
    
    if (is_numeric($codlab) && $codlab != "") 
    {
        $daolabcurso = new LabcursoDAO();        
        $rows = $daolab->buscaLaboratorio($codlab,$anobase);
        foreach ($rows as $row) {
            //cria tipo do lab para criar lab
            $tipolab = new Tplaboratorio();
            $tipolab->setCodigo($row["Tipo"]);
            $lab = $unidade->criaLabv21($codlab,  $row["nomelab"]);
    }
        $cont = 0;
        //busca cursos do lab
        $rows = $daolabcurso->buscaCursosLaboratorio2($lab,$anobase);
        foreach ($rows as $row) {
            
            $curso = new Curso();
            $curso->setUnidade($unidade);
            $curso->setCodcursosis($row["CodCursoSis"]);
            $curso->setCodcurso( $row["CodCurso"]);
            $curso->setNomecurso( $row["NomeCurso"]);
            $curso->setCodemec($row["idcursoinep"]);
            $lab->adicionaItemLabcursos($row["CodLabCurso"], $curso,$ano,$cont);
            $cont++;
        }
    }
}

//ob_end_flush();
?>
<head>
    <div class="bs-example">
		<ul class="breadcrumb">
		    <li class="active"><a href="<?php echo Utils::createLink("laborv3", "consultalab"); ?>">Consultar laboratórios</a>
                <i class="fas fa-long-arrow-alt-right"></i>
                Laboratório por Curso
            </li>
		</ul>
	</div>
</head>

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Cursos - Laborat&oacute;rio</h3><br/>
    </div>
    <form class="form-horizontal" name="fconsultar" method="post" action="index.php?modulo=laborv3&acao=inccursolab&codlab=<?php print  $lab->getCodlaboratorio();?>">
        
        <div class="card-body">
            <b>Nome do laboratório:</b>

            <?php echo ($lab->getNome()); ?>
            
            <br /> 
            
            <input class="form-control"type="hidden" name="codlab"
                        value="<?php print $lab->getCodlaboratorio(); ?>" /> <input
                        type="hidden" name="nomelab" value="<?php print $lab->getNome(); ?>" />
            <br />
            
            <b>Cursos:</b><br />
        </div>
        <?php if ($cont > 0) { ?>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <tr align="center">
                        <th>C&oacute;digo Emec</th>
                        <th>Curso</th>
                        <th>Excluir</th>
                    </tr>
                    <?php foreach ($lab->getLabcursos() as $lab1) { ?>
                        <tr>
                            <td><?php print $lab1->getCurso()->getCodemec(); ?></td>
                            <td><?php print ($lab1->getCurso()->getNomecurso()); ?></td>
                            
                            <?php 
                            if ($dono && !$lock->getLocked()) {?> 
                                <td align="center">
                                    <a href="<?php echo Utils::createLink('laborv3', 'dellabcurso', array('codlabcurso' => $lab1->getCodlabcurso(), 'codlab' => $lab1->getLaboratorio()->getCodlaboratorio())); ?>" target="_self" ><img src="webroot/img/delete.png" alt="Excluir" width="19" height="19" /> </a>
                                </td>
                                <?php }else{ ?>
                                <td>
                                    <button "disabled" title='Não é possível excluir, pois o laboratório pertence a outra subunidade!' data-trigger='hover'> <img src='webroot/img/delete.no.png' alt='Ajuda' data-trigger='hover' width="19" height="19" ></button>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </table>
            </div>
            <?php
        } else {
            print "<div class='card-body>'Nenhum curso vinculado ao laborat&oacute;rio.</div>";
        }
        ?> 

        <div class="card-body" align="center">
            <input class="form-control"value="Vincular curso" class="btn btn-info" class="btn btn-info" type="submit"  id="botao"; />
            <!--  <a href="<?php // echo Utils::createLink("laborv3", "consultalab"); ?>"> -->
        </div>
                    

    </form>
</div>

<script>
    $('#botao').click(function(){
        $.ajax({url: 'inccursolab.php',
            type: 'POST',
            data:$('form[name=fconsultar]').serialize() ,
            success: function(data) {
        }});
    });

    $.ajax({
        type: 'POST',
        url: 'inccursolab.php',
        data:$('form[name=fconsultar]').serialize() ,
        dataType: 'html',

    
        success: function (retorno) {
            window.location = 'inccursolab.php';
    }
    
    });
</script>


