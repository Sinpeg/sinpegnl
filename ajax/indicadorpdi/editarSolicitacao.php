<?php 
//Exibir Erros


ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once '../../dao/PDOConnectionFactory.php';
require_once '../../classes/sessao.php';
require_once '../../classes/unidade.php';
require_once '../../classes/usuario.php';


require_once '../../modulo/mapaestrategico/classes/Solicitacao.php';
require_once '../../modulo/mapaestrategico/classes/SolicitItensIndicadoresDeObjetivo.php';
require_once '../../modulo/mapaestrategico/dao/SolicitItensIndicadoresDeObjetivoDAO.php';
require_once '../../modulo/indicadorpdi/classe/SolicitacaoVersaoIndicador.php';

require_once '../../modulo/mapaestrategico/dao/MapaDAO.php';
require_once '../../modulo/mapaestrategico/classes/Mapa.php';
require_once '../../modulo/metapdi/classe/Meta.php';
require_once '../../modulo/metapdi/dao/MetaDAO.php';
require_once '../../modulo/metapdi/dao/SolicitacaoRepactuacaoDAO.php';


require_once '../../modulo/indicadorpdi/dao/IndicadorDAO.php';
require_once '../../modulo/indicadorpdi/classe/Indicador.php';
require_once '../../modulo/calendarioPdi/classes/Calendario.php';

require_once '../../modulo/indicadorpdi/dao/MapaIndicadorDAO.php';
require_once '../../modulo/indicadorpdi/classe/Mapaindicador.php';
require '../../modulo/metapdi/classe/SolicitacaoRepactuacao.php';

require_once '../../modulo/calendarioPdi/dao/CalendarioDAO.php';
require_once '../../modulo/documentopdi/dao/DocumentoDAO.php';
require_once '../../modulo/documentopdi/classe/Documento.php';
require_once '../../modulo/documentopdi/classe/Perspectiva.php';
require_once '../../modulo/mapaestrategico/classes/Objetivo.php';

require_once '../../modulo/indicadorpdi/classe/Indicador.php';
require_once '../../modulo/indicadorpdi/classe/Mapaindicador.php';

require_once '../../modulo/metapdi/dao/MetaDAO.php';

require_once '../../modulo/indicadorpdi/dao/SolicitacaoVersaoIndicadorDAO.php';
require_once '../../modulo/mapaestrategico/classes/ComentarioSolicitPDU.php';
require_once '../../modulo/mapaestrategico/classes/Mapa.php';
require_once '../../modulo/mapaestrategico/dao/ComentarioSolicitpduDAO.php';

require_once '../../modulo/iniciativa/dao/IndicIniciativaDAO.php';
require_once '../../modulo/iniciativa/classe/IndicIniciativa.php';
require_once '../../modulo/iniciativa/classe/Iniciativa.php';

//require_once '../../vendors/phpmailer/class.phpmailer.php';
require '../../classes/EnviarMail.php';
require '../../classes/Controlador.php';

//print_r(  var_dump($_POST)). "Parou";die;
$c=new Controlador();
$tipousuario=$c->identificarUsuarios($_POST['user']);
session_start();
$sessao = $_SESSION["sessao"];
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
$tipo = $_POST['tipo']; // Recebe o tipo de solicitação
$anobase=$sessao->getAnobase();
$sol = new  SolicitacaoVersaoIndicador();
$sol->setCodigo($_POST['codSolicitacao']);
$sol->setSituacao($_POST['situacao']);
$daoSol = new  SolicitacaoVersaoIndicadorDAO();

$comentario = new ComentarioSolicitPDU();
$comentario->setSolcitacao($sol);
$comentario->setTexto($_POST['texto']);
$comentario->setAutor($_POST['user']);

$daoComentario = new ComentarioSolicitpduDAO();

$mapadao=new MapaDAO();
$doc=new Documento();
$docdao=new DocumentoDAO();
$mapainddao= new MapaIndicadorDAO();
$metadao=new MetaDAO();
$pers=new Perspectiva();
$caldao=new CalendarioDAO();

//Buscar dados da solicitação
$s=$daoSol->buscaQQSolicitacao($sol->getCodigo())->fetch();
$propdoc=new Unidade();
$propdoc->setCodunidade($s['codunidade']);

if ($sol->getSituacao()=="D"){//Se mudou solicitacao para deferido
	
		
	
	//buscar documento
	$pdu=$rowsdoc=$docdao->buscaDocumentoUnidadePrincipal($anobase, $s['codunidade'])->fetch();//busca documento a ser alterado	

	$pdu=$docdao->buscaDocumentoUnidadePrincipal($anobase, $s['codunidade'])->fetch();//busca documento a ser alterado
	$propdoc->criaDocumento($pdu['codigo'], $pdu['nome'], $pdu['anoinicial'], $pdu['anofinal'], NULL, NULL, NULL, NULL, NULL);
	$doc=$propdoc->getDocumento();

	//$propdoc->criaDocumento($pdu['codigo'], $nome, $pdu['anoinicial'], $pdu['anofinal'], NULL, NULL, NULL, NULL, NULL);
		
	switch ($tipo){
		case 1://Substituir Indicador		
					
				   //Busca dados do mapaindicador	
				   $dadosMi = $mapainddao->buscarDadosMapaindicador($s['codmapaind'])->fetch();
				   
				   //Atuaizar mapa-indicador data-final do indicador antigo(exclusão) 
				   $mapainddao->inserirDatafinalMapaind($s['codmapaind']);	
				   
				   $ind = new Indicador();
				   $ind->setCodigo($s['codindicador']);	
				   
				   $mp =  new Mapa();
				   $mp->setCodigo($dadosMi['codMapa']);
				   				   			   
				   //efetivar a inserção do novo indicador
				   $mi = new Mapaindicador();
				   $mi->setIndicador($ind);
				   $mi->setMapa($mp);
				   $mi->setPropindicador($propdoc);	
				   $mi->setTipoassociado($dadosMi['tipoAssociado']);
				   $mi->setAnoinicio(date("Y"));
				   $mi->setPeriodoinicial($dadosMi['periodoinicial']);
				   $mi->setPeriodofinal($dadosMi['periodofinal']);
				   
				   $mapainddao->insereMiSol($mi);	

				   //Desvincular iniciativa do indicador substituido
				   $iniDAO = new IndicIniciativaDAO();
				   $iniDAO->delvincIndicIniciativas($s['codmapaind'], date("Y"));
				   
				   break;
		case 2://Editar dados do indicador
						
				//buscar dados do indicador antigo
				$indDAO = new IndicadorDAO();
				$dadosInd = $indDAO->buscaindicador($s['codindicador'])->fetch();	
			
				//Inserir indicador com novos dados 			
				$ind = new Indicador();
				$ind->setNome($s['nome']);
				$ind->setCalculo($s['calculo']);
				$ind->setInterpretacao($s['interpretacao']);
				$ind->setValidade($dadosInd['validade']);
				$ind->setCesta($dadosInd['cesta']);
				$ind->setUnidade($propdoc);
				$ind->setCodversao($dadosInd['Codigo']);
				$ind->setAnoinicio(date("Y"));
				$ind->setDataalteracao(date("Y-m-d H:i:s"));
				$id_ind = $indDAO->insere($ind);				
				
				
				$iniDAO = new IndicIniciativaDAO();
				
				//define id do indicador que acabou de ser inserido
				$ind->setCodigo($id_ind);
				
				//Busca dados do mapaindicador anterior
				$dadosMi = $mapainddao->buscarDadosMapaindicador($s['codmapaind'])->fetch();
				
				//Atuaizar mapa-indicador data-final do indicador antigo
				$mapainddao->inserirDatafinalMapaind($s['codmapaind'],$anobase);	

				 
				
				$mp =  new Mapa();
				$mp->setCodigo($dadosMi['codMapa']);
				$un=new Unidade();$un->setCodunidade($dadosMi['PropIndicador']);
				$mi = new Mapaindicador();
				$mi->setIndicador($ind);
				$mi->setMapa($mp);
				$mi->setPropindicador($un);
				$mi->setTipoassociado($dadosMi['tipoAssociado']);
				$mi->setAnoinicio($s['anogestao']);
				$mi->setPeriodoinicial($dadosMi['periodoinicial']);
				$mi->setPeriodofinal($dadosMi['periodofinal']);
				
				$id_mi = $mapainddao->insereMiSol($mi);
				
				$newMi = new Mapaindicador();
				$newMi->setCodigo($id_mi);	

				//vincula as metas dos anos maiores que o ano de gestao - alteracao de 6 de janeiro
				$metadao=new MetaDAO();
				
				$row=$metadao->buscarmetamapaindicador1($s['codmapaind']);//busca meta do mapaindicador anterior
				//$s['codmapaind']//mapaindicador antigo
				
				//Buscar metas cadastradas para a solicitação
				$rowsMetasSolicitacao = $daoSol->buscaMetasSolicitacaoComAno($_POST['codSolicitacao']); //Tabela meta_solicitacao
				$contM=0;
				foreach($rowsMetasSolicitacao as $rowM) {
					$arrayValorMetasSolicitacao[$contM]= $rowM['novo_valor'];
					$arrayAnoMetasSolicitacao[$contM]= $rowM['ano'];
					$contM++;
				}
				
				$i=0;
				foreach ($row as $r){
					$objmeta=new Meta();
					$i++;
				    $mi->setCodigo($id_mi);//outra cópia alem de newmi
				    $objmeta->setMapaindicador($mi);
				    $objmeta->setPeriodo('P');
				    $c1=new Calendario();
				    $c1->setCodigo($r['codCalendario']);	
				    $objmeta->setCalendario($c1);
				    $objmeta->setAno($r['ano']);
					//Verificar pelo ano se existe meta da solicitação
				    $cont_aux=0;														    
				    for ($j=0; $j < $contM; $j++) {							 
						if ($r['ano']==$arrayAnoMetasSolicitacao[$j]) {
							$objmeta->setMeta($arrayValorMetasSolicitacao[$j]); //Seta o valor da meta do solicitação
							$cont_aux++;							 							
						}				
					} 
					
					if ($cont_aux==0) {
							$objmeta->setMeta($r['meta']); //Pega mesma meta definida no mapaIndicador anterior
					}				   				    
				    
				    $objmeta->setMetrica($r['metrica']);
				    $objmeta->setCumulativo($r['cumulativo']);
				    $objmeta->setAnoinicial($anobase);
				    $objmeta->setPeriodoinicial($r['periodoinicial']);
				    $arraymeta[$i] = $objmeta;
				   
				    
				}
				
				$metadao->insereAlll($arraymeta);
				
				//busca iniciativas vinculadas ao mapa indicador antigo
				$rowsIni = $iniDAO->Ind_iniciativaPorMapIndicador1($s['codmapaind'],$anobase);
				
				//Vincula iniciativas ao novo mapa-indicador na tabela "ind_iniciativa"
				if ($rowsIni->rowCount()>0) {									
					foreach ($rowsIni as $rows){						
						$iniClass = new Iniciativa();
						$iniClass->setCodIniciativa($rows['codIniciativa']);
						
						$indIni = new IndicIniciativa();
						$indIni->setMapaindicador($newMi);
						$indIni->setIniciativa($iniClass);
						$indIni->setAnoinicial($anobase);
						$indIni->setPeriodoinicial($rows['periodoinicial']);
						$indIni->setAnofinal($rows['anofinal']);
						$indIni->setPeriodofinal($rows['periodofinal']);					
						
						$iniDAO->insertEditeIndi($indIni);	
					}				
				}
				
				//Desvincular iniciativa do indicador anterior colocando ano-final				
				$iniDAO->delvincIndicIniciativas($s['codmapaind'], $anobase);				
				break;
		
		case 3://inserir objetivo
				   //buscar no pdi a perspectiva do objetivo		  
				   $objetivoPDI=new Objetivo();
				   $objetivoPDI->setCodigo($s['codobjetivo']);
				   $rows=$docdao->buscaDocumentoUnidadePrincipal($anobase,938);//busca dados do objetivo pdi
				   foreach ($rows as $r){
				   	if ($r['codObjetivoPDI']==$s['codobjetivo']){
    			   		$pers=new Perspectiva();
				        $pers->setCodigo($r['codPerspectiva']);
				   		break;
				   	}
				   }
				  
				   $doc->criaMapaSol(NULL, $pers, $objetivoPDI, $propdoc, $sessao->getAnobase(), 1, NULL, NULL);
				   
				   
				   $idmapa=$mapadao->insereMapaSol($doc->getMapa());
				   $doc->getMapa()->setCodigo($idmapa);
				   //Gravar novos mapaindicador
				   $daoitens=new SolicitItensIndicadoresDeObjetivoDAO();
				   $rowsItens=$daoitens->buscaIndicadoresObjetivoIgSituacao($sol->getCodigo());
				   foreach ($rowsItens as $i){
				   	  $indicador=new Indicador();
				   	  $indicador->setCodigo($i['codIndicador']);
				      $doc->getMapa()->criaMapaIndicadorSol(NULL, $indicador, $propdoc, $sessao->getAnobase(), 1, NULL, NULL, NULL);
				      $idmi=$mapainddao->insereMiSol($doc->getMapa()->getMapaindicador());
				      $mi=$doc->getMapa()->getMapaindicador();
				      $mi->setCodigo($idmi);
				      $meta1=($i['meta1']!=NULL)?str_replace(",",".", $i['meta1']):'0.0';
				   	    $calendario=$caldao->buscaCalendarioporAnoBaseOnly($doc->getAnoinicial())->fetch();
				   	    $cal=new Calendario();
				   	    $cal->setCodigo($calendario['codCalendario']);
				   	    $mi->criaMetaSol(null, "P", $cal, $meta1, $calendario['anoGestao'], substr($i['metrica'], 0,1), NULL,$anobase,1);
				   	    $metadao->insere($mi->getMeta());
				   	   	
				     $meta2=($i['meta2']!=NULL)?str_replace(",",".",$i['meta2']):'0.0';
				   	    
				   	    $calendario=$caldao->buscaCalendarioporAnoBaseOnly($doc->getAnoinicial()+1)->fetch();
				   	    $cal=new Calendario();
				   	    $cal->setCodigo($calendario['codCalendario']);
				   	    $mi->criaMetaSol(null, "P", $cal, $meta2, $calendario['anoGestao'], substr($i['metrica'], 0,1), NULL,$anobase,1);
				   	    $metadao->insere($mi->getMeta());
				   	  
				     $meta3=($i['meta3']!=NULL)?str_replace(",",".",$i['meta3']):'0.0';
				   	    
				   	    $calendario=$caldao->buscaCalendarioporAnoBaseOnly($doc->getAnoinicial()+2)->fetch();
				   	    $cal=new Calendario();
				   	    $cal->setCodigo($calendario['codCalendario']);
				   	    $mi->criaMetaSol(null, "P", $cal, $meta3, $calendario['anoGestao'], substr($i['metrica'], 0,1), NULL,$anobase,1);
				   	    $metadao->insere($mi->getMeta());
				   	  
				     $meta4=($i['meta4']!=NULL)?str_replace(",",".",$i['meta4']):'0.0';
				   	    
				   	    $calendario=$caldao->buscaCalendarioporAnoBaseOnly($doc->getAnoinicial()+3)->fetch();
				   	    $cal=new Calendario();
				   	    $cal->setCodigo($calendario['codCalendario']);
				   	    $mi->criaMetaSol(null,"P", $cal, $meta4, $calendario['anoGestao'], substr($i['metrica'], 0,1), NULL,$anobase,1);
				   	    $metadao->insere($mi->getMeta());
				   	 		          
				   }
				   
				   break;
		    case 4://repactuacao - gravar nova meta e desativar a anterior
		           $metantiga=$metadao->buscarmeta($s['codmeta'])->fetch();    	
		    	   $mi=new Mapaindicador();
		    	   
				   $mi->setCodigo($metantiga['CodMapaInd']);
				   $cal=new Calendario();
				   $cal->setCodigo($metantiga['codCalendario']); 
				   //altera meta anterior		   
				   $mi->criaMetaSol($metantiga['Codigo'], $metantiga['periodo'], $cal, $metantiga['meta'], $metantiga['ano'], $metantiga['metrica'], 0,
				   $metantiga['anoinicial'],$metantiga['periodoinicial'],$anobase,2);
				   $metadao->alteraSol($mi->getMeta());//metaantigaalterada
				   //inclui meta nova
				   $mi->criaMetaSol(null, "P", $cal, $s['novameta'], $metantiga['ano'], $metantiga['metrica'], 0,$anobase,1,NULL,NULL);
				   $metadao->insere($mi->getMeta());
				   break;
			case 5:
	               $mapadao->alteraMapaporCodigo($s['codmapa'],$anobase);
				   $daoindini=new IndicIniciativaDAO();
				   $rowsmi=$mapainddao->buscaporIndicadorPorMapa( $s['codmapa']);
				   foreach ($rowsmi as $mi){
				   	 $mapainddao->alteraSol($mi['codigo'], $anobase);
				     $rowsmeta=$metadao->buscarmetamapaindicadorperiodo($mi['codigo']);
				   	 foreach ($rowsmeta as $m){
				   	 	$metadao->alteraSol($m['Codigo'], $anobase, 2);
				   	 }
				   	 //invalidar vínculo com iniciativa
				   	 $daoindini->delvincIndicIniciativas($mi['codigo'], $anobase);
				   }//for
	              break;   
			case 6://Incluir indicador
				
				$ind = new Indicador();
				$ind->setCodigo($s['codindicador']);
					
				$mp =  new Mapa();
				$mp->setCodigo($s['codmapa']);
				
				//efetivar a inserção do novo indicador
				$mi = new Mapaindicador();
				$mi->setIndicador($ind);
				$mi->setMapa($mp);
				$mi->setPropindicador($propdoc);
				//$mi->setTipoassociado($dadosMi['tipoAssociado']);
				$mi->setAnoinicio($anobase);
				$mi->setPeriodoinicial(2);
				//$mi->setPeriodofinal($dadosMi['periodofinal']);
					
				$mapainddao->insereMiSol($mi);
				
				
				break;			
			case 7: //Excluir indicador
												
				//Busca dados do mapaindicador
				$dadosMi = $mapainddao->buscarDadosMapaindicador($s['codmapaind'])->fetch();
				
				//Atuaizar mapa-indicador data-final do indicador (exclusão)
				$mapainddao->inserirDatafinalMapaind($s['codmapaind'],$anobase); 
				
				//Desvincular iniciativa
				$iniDAO = new IndicIniciativaDAO(); 
				$iniDAO->delvincIndicIniciativas($s['codmapaind'], $anobase);
								
				break;
		}
}//if

if ($tipousuario=='C' && $sol->getSituacao()=="L" && $s['novameta']!=$_POST['novameta']){
	switch ($tipo){
		case 4:
	         $usuario=new Usuario();
	         $usuario->setCodusuario($s['codusuarioanalise']);
	         $meta= new Meta();$meta->setCodigo($s['codmeta']);
	         $daosol=new SolicitacaoRepactuacaoDAO();
	         $meta->criaSolicitacaoRepactuacaoEd( $propdoc, $_POST['justificativa'], str_replace(',','.', $_POST['novameta']) , $s['anexo'], 
	         "R", $s['datasolicitacao'], NULL , $usuario , $s['anogestao'], $s['codigo']);
	         
	         $codnovasol=$daosol->insere($meta->getSolicitacaorepactuacao());
	         
	         break;
	}
}else{
        //Atualiza situação da solicitação
        $daoSol->deferir($sol);
}
if(trim($comentario->getTexto())!=""){
	$daoComentario->insere($comentario);	
}

?>