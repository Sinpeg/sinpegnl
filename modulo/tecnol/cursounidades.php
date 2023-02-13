<?php
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[11]) {
    header("Location:index.php");
}  else {
    $sessao = $_SESSION["sessao"];
    $nomeunidade = $sessao->getNomeunidade();
    $codunidade = $sessao->getCodunidade();
//  $responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnobase();
    $urespons = $sessao->getUnidadeResponsavel();    
    
//    buscar cursos
//    require('../../includes/dao/PDOConnectionFactory.php');
//    require('../../includes/dao/cursoDAO.php');
//    require('../../includes/classes/curso.php');
//    require('../../includes/classes/campus.php');
//    require('../../includes/classes/unidade.php');
    
    $daounidade =  new UnidadeDAO();
    $daocurso = new CursoDAO();
    $unidade = new Unidade();   
    
    $unidade->setCodunidade($codunidade);
    $unidade->setNomeunidade($nomeunidade);     
   
    if($urespons==1){
        $rows = $daocurso->buscacursosUnidadeTec($codunidade, $anobase);
    
    }else // Para subunidade utiliza o código da unidades responsável 
    {  	    	
    	$codunid = $daounidade->buscaidunidade($urespons);
    	foreach ($codunid as $cod) 
    	{
    	   $codunidaderesp=$cod['CodUnidade'];
    	}   	
    
    	$rows = $daocurso->buscacursosUnidadeTec($codunidaderesp, $anobase);
    }
    	
    //$echo $rows
    $cont = 0;
    foreach ($rows as $row) {
        $campus = new Campus;
        $campus->setCodigo($row["codcampus"]);
        $campus->setNome($row["nomecampus"]);

        $unidade->adicionaItemCursos($campus, $row['CodCursoSis'], $row['CodCurso'], $row['NomeCurso'], $row['DataInicio'], $row['CodEmec']);
        $cont++;
    }
    $daocurso->fechar();
}
//ob_end_flush();
?>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active">Tecnologia assistiva</li>
		</ul>
	</div>
</head>

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Tecnologia Assistiva - Cursos  </h3>
    </div>    
    <form class="form-horizontal" name="cunidades">
        <div class="card-body"> 
            <table class="table table-bordered table-hover" id="tabelaCursos">
                <thead>
                    <tr>
                        <th>Campus</th>
                        <th>C&oacute;digo Emec</th>
                        <th>Nome do curso</th>
                        <th>Visualizar</th>                
                    </tr>
                </thead>
                <tbody>            
                    <?php foreach ($unidade->getCursos() as $curso) { ?>
                        <tr>
                            <td><?php print $curso->getCampus()->getNome(); ?>
                            </td>
                            <td align="center"><?php print $curso->getCodemec(); ?>
                            </td>
                            <td><?php print $curso->getNomecurso(); ?>
                            </td>
                            <td align="center"><a
                                    href="<?php echo Utils::createLink('tecnol', 'consultatecnol', array('codcurso'=>$curso->getCodcurso(), 'nomecurso'=>$curso->getNomecurso()))?>"
                                    target="_self"> <img src="webroot/img/busca.png"
                                                    alt="Visualizar" width="19" height="19" />
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </form>
</div>

<script>
 $(function () {
    $('#tabelaCursos').DataTable({
      "paging": true,
      "sort": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "responsive": true,
    });
});
</script>