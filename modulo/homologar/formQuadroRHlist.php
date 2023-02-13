<?php
//require_once('../../includes/classes/sessao.php');
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[24]) {
    header("Location:index.php");
}else {
//    $sessao = $_SESSION["sessao"];
    $nomeunidade = $sessao->getNomeunidade();
    $codunidade = $sessao->getCodunidade();
//	$responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnobase();
//	
//	require_once('../../includes/dao/PDOConnectionFactory.php');
    require_once('modulo/edprofrh/dao/rhetemufpaDAO.php');
    require_once('modulo/edprofrh/classes/rhetemufpa.php');
//	require_once('../../includes/classes/unidade.php');
    $unidade = new Unidade();
    $unidade->setCodunidade($codunidade);
    $unidade->setNomeunidade($nomeunidade);
    $rhetemufpa = array();
    $rhetemufpa = new Rhetemufpa();
    $cont = 0;
    $daorh = new rhetemufpaDAO();
    $codsub = filter_input(INPUT_GET, 'codunidade', FILTER_DEFAULT);
    $rows_rh = $daorh->buscarhunidade($codsub, $anobase);
    foreach ($rows_rh as $row) {
        $cont++;
        $unidade->adicionaItemRhetemufpa($row['Codigo'], $row['CodSubunidade'], $row['DocDoutores'], $row['DocMestres'], $row['DocEspecialistas'], $row['DocGraduados'], $row['DocNTecnicos'], $row['DocTemporarios'], $row['Tecnicos'], $row['Ano']);
    }

    $daorh->fechar();
}

//ob_end_flush();
?>

<form class="form-horizontal" name="pe" id="pe" method="post">

    <h3 class="card-title"> Quadro de pessoal</h3>
    <div class="msg" id="msg"></div>

    <table>
        <tr align="center" style="font-style:italic;">
                    
            <th>Ef.- N&iacute;vel Superior</th>
            <th>Ef.- N&iacute;vel T&eacute;cnico</th>
            <th>Tempor&aacute;rios</th>
            <th>T&eacute;cnicos</th>
        </tr>
        <?php foreach ($unidade->getRhetemufpa() as $r) { ?>
            <tr>

           <td align="center"><?php echo $r->getDoutores() + $r->getMestres() + $r->getEspecialistas() + $r->getGraduados();?></td> 
           <td> <?php echo $r->getNtecnicos(); ?></td>
           <td align="center"> <?php echo $r->getTemporarios(); ?></td>
           <td align="center"> <?php echo $r->getTecnicos(); ?></td>
                
            </tr>
        <?php } ?>
    </table>
    
</form>
</body>
</html>
