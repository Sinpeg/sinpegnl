<?php 
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once '../../dao/PDOConnectionFactory.php';
require_once '../../dao/usuarioDAO.php';
require_once '../../classes/Controlador.php';
require_once '../../classes/sessao.php';

require_once '../../modulo/indicadorpdi/dao/IndicadorDAO.php';
require_once '../../modulo/metapdi/dao/MetaDAO.php';
require_once  '../../modulo/indicadorpdi/dao/MapaIndicadorDAO.php';
require_once '../../modulo/indicadorpdi/dao/SolicitacaoVersaoIndicadorDAO.php';
require_once '../../modulo/documentopdi/dao/DocumentoDAO.php';
require_once '../../modulo/mapaestrategico/dao/ObjetivoDAO.php';
require_once '../../modulo/mapaestrategico/dao/ComentarioSolicitpduDAO.php';

require_once '../../modulo/mapaestrategico/dao/SolicitItensIndicadoresDeObjetivoDAO.php';

session_start();
$sessao = $_SESSION['sessao'];

if (!isset($sessao)) {
 echo "Sessão expirou...";
 exit(0);
} else {
 $aplicacoes = $sessao->getAplicacoes();
 if (!$aplicacoes[55]) { 
 echo "Você não tem permissão para acessar esta operação!";
 exit(0);
 }
}

$c=new Controlador();
$tipousuario=$c->identificarUsuarios($sessao->getCodusuario());


$tipo = $_POST['tipo'];
$codSolicitacao = $_POST['codSolicitacao'];
$daoSol = new SolicitacaoVersaoIndicadorDAO();
$rowSol = $daoSol->buscaSolicitacao($codSolicitacao);

//print_r($rowSol);
$daoIndicador = new IndicadorDAO();
$daoObj=new ObjetivoDAO();
$daoSolItensInd=new SolicitItensIndicadoresDeObjetivoDAO();

	//echo $tipo." ".$codSolicitacao;die;
//Buscar dados do documento
$daoDoc = new DocumentoDAO();
//echo $rowSol->rowCount();
$htmlC=NULL;
if($rowSol->rowCount()==1){
	foreach ($rowSol as $row){	
		if($row['situacao']=="A"){
			$situacao = "Aberta";
		}elseif($row['situacao']=="D"){
			$situacao = "Deferida";
		}elseif($row['situacao']=="I"){
			$situacao = "Indeferida";
		}elseif ($row['situacao'] == "G"){
			$situacao = "Delegado";
		}elseif($row['situacao']=="C"){
			$situacao = "Cancelada";
		}
		
		//Buscar Comentários para a solicitação
		$classT="";
		$count=1;
		$htmlC = "<table>";
		$daoComentario = new ComentarioSolicitpduDAO();
		$rowComentario = $daoComentario->buscaComentarios($codSolicitacao);
		foreach ($rowComentario as $rowC){
			if($count%2 !=0){
				$classT="style='background-color:#F2F2F2';";
			}else{
				$classT="style='background-color:#E6E6E6';";
			}
			$daoUser = new UsuarioDAO();
			$rowUser = $daoUser->buscarUsuario($rowC['autor']);
			foreach ($rowUser as $rowU){
				$responsavel = $rowU['Responsavel'];
			}
			
			$htmlC.="<tr $classT  ><td><span style='font-size: 12px;'><b>".$responsavel."</b></span></td><td style='text-align: right;'><span style='font-size: 12px;'><b>".date("d/m/Y H:i",strtotime($rowC['dataComentario']))."</b></span></td></tr><tr  $classT><td colspan='2'>".$rowC['texto']."</td></tr>";
			$count++;
		}
		$htmlC.="</table>";
		
		if ($tipo==1) {	
				 	
		 	$rowInd = $daoIndicador->buscaindicador($row['codindicador']); 
		 	foreach ($rowInd as  $rowind){
		 		$nomeN = $rowind['nome'];
		 	}
		 	
		 	$rowDoc = $daoDoc->buscadocumentoporunidadePeriodo($row['codunidade'], $row['anogestao']);
		 	foreach ($rowDoc as $row2){
		 		$nomeDoc = $row2['nome'];
		 	}
			
		 	$rows = $daoObj->buscaObjetivoPorSolicitacao($codSolicitacao);
		 	foreach ($rows as $r){
		 		$nomeobjet=$r['nomeObjetivo'];
		 	}
		 	
			echo json_encode(array('comentarios' => $htmlC,'nomeObjetivo'  => $nomeobjet,'nomeA'  => $row['nomeindicadorantigo'],'nomeN' => $nomeN,'situacao' => $situacao,'justificativa' => $row['justificativa'],'arquivo' => $row['anexo'],'documento' => $nomeDoc));
		}elseif($tipo==2){
			$rowInd = $daoIndicador->buscaindicador($row['codindicador']);
			foreach ($rowInd as  $rowind){
				$nomeN = $rowind['nome'];
			}
			
			$rows = $daoObj->buscaObjetivoPorSolicitacao($codSolicitacao);
			foreach ($rows as $r){
				$nomeobjet=$r['nomeObjetivo'];
			}
			
			$rowDoc = $daoDoc->buscadocumentoporunidadePeriodo($row['codunidade'], $row['anogestao']);
			foreach ($rowDoc as $row2){//busca nome do documento
				$nomeDoc = $row2['nome'];
			}
			echo json_encode(array('comentarios' => $htmlC,'nomeObjetivo'  => $nomeobjet,'nome'  => $row['nomeindicadorantigo'],'nomeN' => $row['nome'],'formula' => $row['calculo'],'interpretacao' => $row['interpretacao'],'situacao' => $situacao,'justificativa' => $row['justificativa'],'arquivo' => $row['anexo'],'documento' => $nomeDoc));
		}elseif ($tipo==3){//inclui objetivo
			
                $rows = $daoObj->buscaobjetivo($row['codobjetivo']);
                foreach ($rows as $r){
                    $nomeobj=$r['Objetivo'];
                }
		        $rowDoc = $daoDoc->buscadocumentoporunidadePeriodo($row['codunidade'], $row['anogestao']);
			    foreach ($rowDoc as $row2){//busca nome do documento
			       $coddoc= $row2['codigo'];
			       $nomeDoc = $row2['nome'];
				   $anoinicial= $row2['anoinicial'];
				   $anofinal = $row2['anofinal'];
				   
			    }
                $rows=$daoSolItensInd->buscaIndicadoresObjetivoIgSituacao($codSolicitacao);
                $nindicadores='';
                $cont=1;
                
                include "formIncluirObjfinal.php";
              
     }else if ($tipo==5){
                $rowDoc = $daoDoc->buscadocumentoporunidadePeriodo($row['codunidade'], $row['anogestao']);
			    foreach ($rowDoc as $row2){//busca nome do documento
			    	$nomeDoc = $row2['nome'];
			    }
                $rows = $daoObj->buscaobjetivo($row['codobjetivo']);
                foreach ($rows as $r){
                    $nomeobj=$r['Objetivo'];
                }
                $daomapaInd = new MapaIndicadorDAO();
                $rows=$daomapaInd->buscaporIndicadorPorMapa($row['codmapa']);//indicadores do obj
                $nindicadores='';
                foreach ($rows as $r){
                     $nindicadores.=$r['nomeindicador'].",";
                 }
                
                $sit11=$situacao=="Delegado"?"G":substr($situacao, 0,1);
             	echo json_encode(array('comentarios' => $htmlC,'nomeObjE'  => $nomeobj,'indicadoresobj' => $nindicadores,
             	'situacaoE' => $sit11,'sitfinalE' => $situacao,'justificativa' => $row['justificativa'],'arquivo' => $row['anexo'],
             	'documentoE' => $nomeDoc,'tipousuario' => $tipousuario));
            
        }elseif ($tipo==4){//repactuação
        	    $daometa=new MetaDAO();
                $rowsmeta=$daometa->buscarmeta($row['codmeta']);
                foreach ($rowsmeta as $m){	
                       $ano=$m['ano'];
                       $mapaind=$m['CodMapaInd'];
                       $meta=$m['meta'];	 
                }
                $daomi=new MapaIndicadorDAO();
                
                $rowsmapaind=$daomi->buscaMapaIndicador($mapaind);
                foreach ($rowsmapaind as $i){
                    $nomeind=$i['nome'];
                }
                $rowDoc = $daoDoc->buscadocumentoporunidadePeriodo($row['codunidade'], $row['anogestao']);
		 	    foreach ($rowDoc as $row2){
		 		   $nomeDoc = $row2['nome'];
		 	    }
                $sit11=$situacao=="Delegado"?"G":substr($situacao, 0,1);
		 	    
               echo json_encode(array('comentarios' => $htmlC,'nomeindicadorrm' => $nomeind,'anorm' => $ano,'situacaoRM' => $situacao,
                	'metarm'  => str_replace('.',',',$meta),'novameta' =>str_replace('.',',',$row['novameta']),'situacao' => $sit11,'tipousuario' => $tipousuario,
                	'justificativarm' => $row['justificativa'],'arquivorm' => $row['anexo'],'documentorm' => $nomeDoc,
                	'tipoSolicitacaoB'=>$tipo));
           	 
        }elseif ($tipo==6){ //Incluir indicador
        	$rowInd = $daoIndicador->buscaindicador($row['codindicador']);
        	foreach ($rowInd as  $rowind){
        		$nomeN = $rowind['nome'];
        	}
        	$rowDoc = $daoDoc->buscadocumentoporunidadePeriodo($row['codunidade'], $row['anogestao']);
        	foreach ($rowDoc as $row2){//busca nome do documento
        		$nomeDoc = $row2['nome'];
        	}
        	 
        	$rows = $daoObj->buscaobjetivo($row['codobjetivo']);
            foreach ($rows as $r){
                $nomeobjet=$r['Objetivo'];
            }
        	echo json_encode(array('comentarios' => $htmlC,'nomeObjetivo'  => $nomeobjet,'nomeN' => $nomeN,'situacao' => $situacao,'justificativa' => $row['justificativa'],'arquivo' => $row['anexo'],'documento' => $nomeDoc));
        	
        }elseif ($tipo==7){//Excluir indicador
        	
       		 $rowInd = $daoIndicador->buscaindicador($row['codindicador']); 
			 	foreach ($rowInd as  $rowind){
			 		$nomeN = $rowind['nome'];
			 }
        	$rowDoc = $daoDoc->buscadocumentoporunidadePeriodo($row['codunidade'], $row['anogestao']);
        	foreach ($rowDoc as $row2){//busca nome do documento
        		$nomeDoc = $row2['nome'];
        	}
        	
        	$rows = $daoObj->buscaObjetivoPorSolicitacao($codSolicitacao);
                foreach ($rows as $r){
                    $nomeobjet=$r['nomeObjetivo'];
            }
        	echo json_encode(array('comentarios' => $htmlC,'nomeObjetivo'  => $nomeobjet,'nomeN' => $nomeN,'situacao' => $situacao,'justificativa' => $row['justificativa'],'arquivo' => $row['anexo'],'documento' => $nomeDoc));
        }
		
	}//for
}



?>