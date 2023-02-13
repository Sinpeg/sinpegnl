<?php
//require_once('../../includes/classes/sessao.php');

$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
$codunidade = $sessao->getCodunidade();
if (!$aplicacoes[24] ||  !(($codunidade==971) || ($codunidade==1001) || ($codunidade==1004) || ($codunidade==201)) ) {
    header("Location:index.php");
} 
else {
//    $sessao = $_SESSION["sessao"];
    $nomeunidade = $sessao->getNomeunidade();    
//	$responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnobase();	
//	require_once('../../includes/dao/PDOConnectionFactory.php');
    require_once('dao/rhetemufpaDAO.php');
    require_once('classes/rhetemufpa.php');
//	require_once('../../includes/classes/unidade.php');
    $lock = new Lock();
    $unidade = new Unidade();
    $daounid = new UnidadeDAO();
    $unidade->setCodunidade($codunidade);
    $unidade->setNomeunidade($nomeunidade);
    $rhetemufpa = array();
    $rhetemufpa = new Rhetemufpa();
    $cont = 0;
    $daorh = new rhetemufpaDAO();
    
    $rowscodsup = $daounid->RetornaCodUnidadeSuperior($cpga);
    foreach ($rowscodsup as $row)
    {
    	$codunidadesup = $row['CodUnidade'];
    }
    
    if (!$sessao->isUnidade()) {
    	// verifica se a subunidade possui homologação
    	$lock->setLocked(Utils::isApproved(24, $codunidadesup, $codunidade, $anobase));  }
    	 
    
    	for ($i = 0; $i < count($array_codunidade); $i++) 
    {
    
        $rows_rh = $daorh->buscarhunidade($array_codunidade[$i], $anobase);    
       
        foreach ($rows_rh as $row) 
       {
       	if ($sessao->isUnidade() && $array_codunidade[$i] != $codunidade && $rows_rh->rowCount() > 0) {
       		$lock->setLocked(true); }
       	
        $cont++;
        $unidade->adicionaItemRhetemufpa($row['Codigo'], $row['CodSubunidade'], $row['DocDoutores'], $row['DocMestres'], $row['DocEspecialistas'], $row['DocGraduados'], $row['DocNTecnicos'], $row['DocTemporarios'], $row['Tecnicos'], $row['Ano']);
       }
    }
  
    $daorh->fechar();
    
    if ($cont == 0) {
    	Utils::redirect('edprofrh', 'incluirh');
    }
}

//ob_end_flush();
?>
<script type="text/javascript">
    function direciona(botao) {
        if (botao == 1) {
            if (document.pe.registros.value == 2)
                document.getElementById('msg').innerHTML = "O n&uacute;mero m&aacute;ximo de registros &eacute; 2.";
            else {
                document.getElementById('pe').action = "<?php echo Utils::createLink('edprofrh', 'incluirh'); ?>";
                document.getElementById('pe').submit();
            }
        }
    }
</script>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li><a href="<?php Utils::createLink('edprofrh', 'incluirh'); ?>" >Quadro de pessoal</a></li>
		    <li class="active">Consulta</li>
		</ul>
	</div>
</head>
<form class="form-horizontal" name="pe" id="pe" method="post">

    <h3 class="card-title"> Quadro de pessoal</h3>
    <div class="msg" id="msg"></div>

    <table>
        <tr align="center" style="font-style:italic;">
            <th>Subunidade</th>
            <th>Ef.- N&iacute;vel Superior</th>
            <th>Ef.- N&iacute;vel T&eacute;cnico</th>
            <th>Tempor&aacute;rios</th>
            <th>T&eacute;cnicos</th>
            <th>Alterar</th>
            <th>Excluir</th>
        </tr>
        <?php foreach ($unidade->getRhetemufpa() as $r) { ?>
            <tr>
                <td><?php
                    if ($r->getSubunidade() == 1001) {
                        print "Escola de Música";
                    } else {
                        print "Escola de Teatro e Dança";
                    }
                    ?></td>

                <td align="center"><?php echo $r->getDoutores() + $r->getMestres() + $r->getEspecialistas() + $r->getGraduados();
                    ?></td> <td> <?php echo $r->getNtecnicos(); ?></td>
                <td align="center"> <?php echo $r->getTemporarios(); ?></td>
                <td align="center"> <?php echo $r->getTecnicos(); ?></td>
                <?php if (!$lock->getLocked()){ ?>
                <td align="center"><a href="<?php echo Utils::createLink('edprofrh','alterarh', array('codigo'=>$r->getCodigo()));?>" target=_self >
                        <img src=webroot/img/editar.gif alt=Alterar width=19 height=19 /></a></td>
                        <?php } ?>
                <?php if (!$lock->getLocked()){ ?>
                <td align="center"><a href="<?php echo Utils::createLink('edprofrh','oprh', array('codigo'=>$r->getCodigo(),'op'=>"excluir"));?>" target=_self >
                        <img src=webroot/img/delete.png alt=Excluir width=19 height=19 /></a></td>
                        <?php } ?>
            </tr>
        <?php } ?>   
    
    </table>
    <input class="form-control"type="text" name="registros"  style="border: none;background-color: transparent"  readonly="readonly"
           size="1" value="<?php echo $cont; ?>"  />registros<br />
    <?php if ($sessao->isUnidade()) { ?>
    <?php if (!$lock->getLocked()){ ?>
    <input	type="button" value="Incluir" onclick="direciona(1);" />
    <?php } ?>
    <?php } ?> 
</form>
</body>
</html>
