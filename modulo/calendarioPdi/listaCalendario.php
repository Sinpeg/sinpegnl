<?php
//Exibir Erros
/*
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
*/

//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
//session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();


/*if (!$aplicacoes[38]) {
    header("Location:index.php");
}  else {*/

    $nomeunidade = $sessao->getNomeunidade();
    $codunidade = $sessao->getCodunidade();
//	$responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnobase();
    
    
  /*  if ($_GET['codunidade']>0 ) {
        $unidade = new Unidade();
        $unidade->setCodunidade($_GET['codunidade']);
        $unidade->setNomeunidade($_GET['nomeunidade']);
        $sessao ->setCodunidsel($_GET['codunidade']);
        $sessao ->setNomeunidsel($_GET['nomeunidade']);
        $codunidade1=$sessao ->getCodunidsel();
        $_SESSION["sessao"]=$sessao ;
      
    }else {*/
        $unidade = new Unidade();
        $unidade->setCodunidade(938);
     //   $unidade->setNomeunidade($nomeunidade);
        $codunidade1=$unidade->getCodunidade();
   // }
  //  require_once('dao/CalendarioDAO.php');
 //   require_once('classes/Calendario.php');

    $lista = array();
    $cont = 0;
    $dao = new CalendarioDAO();   

    $rowsl = $dao->listaCalendario1($unidade,$anobase);
   //$rowsl = $dao->lis
  
  // require_once('modulo/documentopdi/classe/Documento.php');
   	
  
    foreach ($rowsl as $row) {
        $cont++;
        
        $d=new Documento();
        $d->setCodigo($row['dcodigo']);
        $d->setNome($row['ndocumento']);
        $d->setAnoinicial($row['anoinicial']);
        $d->setAnofinal($row['anofinal']);
        
        $parametro=$unidade->getCodunidade();
        $d->setUnidade($unidade);
        
        $d->criaCalendario($row['codCalendario'],null,
            null,null,
            null,null,
            null,null,
            null,null,
            null,null,
            null,null,
        
            $row['anoGestao'],null);
        
        
        $lista[$cont]=$d->getCalendario();
        
    }       
   
//}
//ob_end_flush();
?>
<?php echo Utils::deleteModal('Calend??rio', 'Voc?? tem certeza que deseja remover um ano de gest??o?'); ?>

<head>
<?php if ($codunidade==100000){?>
	<div class="bs-example">
		<ul class="breadcrumb">
		    <li><a href="<?php echo Utils::createLink("usuario", "consultaunidade",array('codigo'=>50)); ?>">Consulta</a></li>
			<li class="active">Listar Calend??rios</li>  
		</ul>
	</div>
	<?php }else{ ?>
		<div class="bs-example">
		<ul class="breadcrumb">
		  
			<li class="active">Listar Calend??rios</li>  
		</ul>
	</div>
	<?php }?>
</head>
<form class="form-horizontal" method="POST" action="index.php?modulo=calendarioPdi&acao=finsCalend">
    <h3 class="card-title">Calend??rio</h3>
    <?php if ($sessao ->getCodunidsel()>0) { ?>
          Unidade selecionada:<?php echo $sessao->getNomeunidsel();
  }  ?>
    <input class="form-control"name="codunidadesel" type="hidden" value="<?php print $codunidade1; ?>" />
    <input class="form-control"name="codunidade" type="hidden" value="<?php print $codunidade; ?>" />
    <br />
    <?php if ($cont > 0) { ?>
        <br/>
        <table>
                <tr><th>Plano</th>
                <th>Ano de Gest??o</th>
                <th>Alterar</th>
                <th>Excluir</th>
            </tr>
            <?php for ($i=1;$i<=count($lista);$i++) { ?>
                <tr><td><?php print $lista[$i]->getDocumento()->getNome()." - ".
                     $lista[$i]->getDocumento()->getAnoinicial()." a ".
                     $lista[$i]->getDocumento()->getAnofinal(); ?></td>
                     <td><?php print  $lista[$i]->getAnogestao(); ?></td>
                      <td align="center">
                        <a href="<?php echo Utils::createLink('calendarioPdi', 'finsCalend', array('codcalend' =>  $lista[$i]->getCodigo(),'codunidadesel'=> $codunidade1)); ?>"  target="_self" ><img src="webroot/img/editar.gif" alt="Editar" width="19" height="19" /> </a>
                    </td>	  
                   <td align="center">
                        <a href="<?php echo Utils::createLink('calendarioPdi', 'delCalend', array('codcalend' =>  $lista[$i]->getCodigo(),'coduni'=>$unidade->getCodunidade(),'nome='=>$unidade->getNomeunidade())); ?>" class="delete-link" target="_self" ><img src="webroot/img/delete.png" alt="Excluir" width="19" height="19" /> </a>
                    </td></tr>
            <?php } ?>
        </table>
        <?php
    } else {
        print "<p style='color:red'>Nenhum calend??rio registrado para a unidade.</p></br>";
    }
    ?>
    <input class="btn btn-info" type="submit"   class="btn btn-info" value="Incluir Calend??rio" />
</form>

