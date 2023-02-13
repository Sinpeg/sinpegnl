<?php
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
/*if (!$aplicacoes[50]) {
    header("Location:index.php");
} else {*/
// colocar o usuario
    $sessao = $_SESSION["sessao"];
   // $nomeunidade = $sessao->getNomeunidade();
    $codunidade =$_GET["coduni"]; 
    $responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnobase();
    $codigo = $_GET["codcalend"];
    $daouni=new UnidadeDAO();
    $rows=$daouni->unidadeporcodigo($codunidade);
    foreach ($rows as $row ){
        $nomeunidade=$row['NomeUnidade'];
    }
/*    require_once('classes/Calendario.php');
    require_once ('dao/CalendarioDAO.php');
    require_once ('modulo/metapdi/dao/MetaDAO.php');*/

    $unidade = new Unidade();
    $unidade->setCodunidade($codunidade);
    $unidade->setNomeunidade($nomeunidade);
    $daocal=new CalendarioDAO();
    if ($codigo>0 ) {
            $cal=new Calendario();
            $cal->setCodigo($codigo);
            $daometa = new MetaDAO();
            $rows=$daometa->buscarmetaParaCalendario($codigo);
            $passou=0;
            foreach ($rows as $row ){
                $passou=1;
            }
            if ($passou==0){
               $daocal->deleta($cal);
               Flash::addFlash("Operação realizada com sucesso!");
            }else{
               Flash::addFlash("Existe meta relacionada a este calendário, não será possível eliminá-lo!!Elimine a meta e depois o calendário!");
            }
        Utils::redirect('calendarioPdi', 'listaCalendario', array( 'codunidade' =>  $unidade->getCodunidade(),'nomeunidade'=> $unidade->getNomeunidade()));    

    }
//}
//ob_end_flush();
?>
