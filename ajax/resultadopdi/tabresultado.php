<?php 

require '../../dao/PDOConnectionFactory.php';
require '../../classes/sessao.php';
require '../../classes/Controlador.php';
require '../../classes/unidade.php';
require '../../dao/unidadeDAO.php';
require '../../modulo/avaliacao/dao/AvaliacaofinalDAO.php';
require '../../modulo/calendarioPdi/dao/CalendarioDAO.php'; 
require '../../modulo/indicadorpdi/dao/SolicitacaoVersaoIndicadorDAO.php'; 

require_once '../../util/Utils.php'; // classe para auxiliar na criação de gráficos
define('MODULO_DIR', (dirname(__FILE__)) . '/../../modulo/');

require_once MODULO_DIR . 'resultadopdi/dao/ResultadoDAO.php';


require_once MODULO_DIR . 'documentopdi/dao/DocumentoDAO.php';

session_start();
$sessao = $_SESSION['sessao'];
$aplicacoes = $sessao->getAplicacoes();
if (!isset($sessao)) {
    echo "Sessão encerrada! Faça login novamente!";die;
}      


if (!$aplicacoes[29]) {
    print ("O usuário não possui permissão para acessar este aplicativo."); die;
}

$unidades_estrategicas = array('948','949','950','951','952','953','954','957','1781','2504','3690','3698','7136');


$coddoc=strip_tags($_POST['doc']);//numero 
$periodo=strip_tags($_POST['speriodo']);//numero  

$anobase = $sessao->getAnobase();
$c=new Controlador();
$unidade=new Unidade();
if ($c->getProfile($sessao->getGrupo())) {//se grupo for 18  
    $codunidade=938;
    $class="style='visibility:hidden;'";
}else{
	$codunidade=$sessao->getCodUnidade();
	$class = "";
}
$daocal = new CalendarioDAO();
$daodoc= new DocumentoDAO();


$table ="";


$arraycal = $daocal->verificaPrazoCalendarioDoDocumento($sessao->getAnobase());
foreach($arraycal as $a){
  $varperiodo = $a['habilita'];
  $codcal=$a['codCalendario'];
  //echo "cccc".$codcal;
}
if($varperiodo == 'Parcial'){
	$idPeriodo = 1;
}else if($varperiodo == 'Final'){
	$idPeriodo = 2;
}else{
	$idPeriodo = 0;
}


// $data = date("Y-m-d");

// 	if($data > $arraycal['dataIniAnalise'] and $data < $arraycal['dataFimAnalise']){
// 		$peratual = 'Parcial';$peratualnum = "1";
// 	}elseif ($data > $arraycal['datainiAnaliseFinal'] and $data < $arraycal['datafimAnaliseFinal']){  
// 		$peratual = 'Final';$peratualnum = "2";
// 	} else{
// 		$peratual = 'Não está em nenhum periodo';
// 	} 



$string=array();
$rows11 =  $daodoc->pedenciasDocumento($coddoc,$sessao->getAnobase(),$sessao->getCodunidade());
$cont1=0;
foreach ($rows11 as $r){
	//echo $r['codmi']."-".$r['codmeta']."-".$r['Objetivo'].'<br>';
	
	if (empty($r['codmi'])){
		$cont1++;
		$string[$cont1]='<td>Vincular indicador (es) ao objetivo</td><td>'.$r['Objetivo']."</td><td>".$r['nome'].'</td>';
	}
	if ($sessao->getAnobase()>2021 && empty($r['codmeta'])){
		$cont1++;		
			$string[$cont1]='<td>Vincular metas aos indicadores do objetivo</td><td>'.$r['Objetivo']."</td><td>".$r['nome'].'</td>';
	}
	if ($sessao->getAnobase()<=2021 && empty($r['metrica'])){
	    $cont1++;
	    $string[$cont1]='<td>Vincular metas aos indicadores do objetivo</td><td>'.$r['Objetivo']."</td><td>".$r['nome'].'</td>';
	}
	if (empty($r['codmapaind']) && ($sessao->getCodunidade()!=938)){//Pode ser visualizado indicador sem iniciativa, mas on lançamento ela é obrigatória 
			$cont1++;
			$string[$cont1]='<td>Vincular iniciativa aos indicadores do objetivo</td><td>'.$r['Objetivo']."</td><td>".$r['nome'].'</td>';
	}
}
 
$bt = 1; //flag para ativar botão. Quando todos os resultados finais forem submetidos.

$daosol=new SolicitacaoVersaoIndicadorDAO();
$sol=$daosol->buscaSolicitacaoNoAnoParaUnidade($codunidade, $anobase);
if ($sol->rowCount()>0){
	echo '<br><br><p><b>Existem solicitações pendentes para este PDU, portanto os resultados nao podem ser lançados.</b></p>';
}else {

if (count($string)==0 &&  (!in_array($codunidade, $unidades_estrategicas)) ){
//echo $anobase.','.$coddoc.','.$sessao->getCodunidade().'<br>';
	if ($codunidade!=938) {
	$rows = $daodoc->listaIndporDocCal1($anobase,$coddoc,$codunidade);//lista indicadores da unidade por doc e calendario
	}else{
	$rows = $daodoc->listaIndporDocCal2($anobase,$coddoc,$codunidade);//lista indicadores da unidade por doc e calendario
		
	}

	if($rows->rowCount()==0){
            $bt = 2; //Não possui indicador cadastrado 
    }

	$dados=array();
	$cont=0;
	
	foreach ($rows as $r){
		//echo $cont;
	        $subarray=array();
	        $subarray[1]=$r['periodo'];
	        $subarray[2]=$r['codobj'];
	        $subarray[3]=$r['Objetivo'];
	        $subarray[4]=$r['codmi'];
	        $subarray[5]=$r['nome'];
	        $subarray[6]=($sessao->getCodUnidade()==938)?$r['PropIndicador']:$r['metrica'];
	        $subarray[7]=$r['codmeta'];
	        $subarray[8]=$r['codmapa'];
	        $subarray[9]=$r['coddoc'];
	        $subarray[10]=$r['meta'];
	        $dados[$cont]=$subarray;
	        $cont++;
	        
	       
	 }
	 
	 $rowUnidade = $daodoc->buscaunidadedocumento($coddoc);
	 
	 
	 if ($coddoc==-1) {
	     $unidade_ex=0; // Caso em que escolhe todas as unidades
	 }else{
	     foreach ($rowUnidade as $row){
	         $unidade_ex = $row['CodUnidade'];
	     }
	 }
	 
	 
	 $p = array(
	                'A' => array('Ano de ' . $anobase),
	                'M' => array('janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho',
	                    'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'),
	                'T' => array('1º trimestre', '2º trimestre', '3º trimestre', '4º trimestre'),
	                'S' => array('1º semestre', '2º semestre'),
	                'P'=> array('Parcial', 'Final')
	            );      
	
	
	$indicador=0;?>
	
	<br/><br/>Exportar Resultado do Painel <?php echo $sessao->getCodunidade()==938?"Estratégico:":"Tático:"; 
	echo  $unidade_ex!=0?"<a href='relatorio/resultadosPainelTatico2.php?unidade=".$unidade_ex."&anoBase=".$sessao->getAnobase()."'><img src='webroot/img/pdf.png'></a>&nbsp;&nbsp;&nbsp;":"&nbsp;&nbsp;&nbsp";
	echo '<a href="relatorio/resultadosPainelTaticoExcel2.php?unidade='.$unidade_ex.'&anoBase='.$anobase.'"><img width="20px" height="20px" src="webroot/img/excel.png"></a>';
	?>
	<br>
	<br>
	<?php if($unidade_ex!=0){ 	
		
	// Inicio da tabela -->
	
	 $table .='<div class="card-body"><table '.$class.' id="tablesorter" class="table table-bordered table-hover">	       
	         <thead>
	            <tr>
	                <th>Objetivo </th>
	                <th>Indicador</th>
	                <th>';
	                $table .= ($codunidade==938)?"Unidade Responsável":"Métrica";
	                $table .= '</th>	                
	                <th>Resultado</th>
	            </tr>
	        </thead>
	     ';
	     

	     
for ($j=0;$j<count($dados);$j++){
    
    if ($indicador!=$dados[$j][4]){
	         $indicador=$dados[$j][4];

	         
$table.=' 
<tbody>
	 <tr>
	     <td>'. $dados[$j][3].'</td>
	     <td>'. $dados[$j][5].'</td>
	     <td>'; 	     
	     if ($sessao->getCodUnidade()==938){
	    	//"Unidade Responsável"
	    	$uniddao=new UnidadeDAO();
	    	$rows=$uniddao->buscaidunidadeRel($dados[$j][6]);
	    	foreach ($rows as $rs){
	    		$table.= $rs['NomeUnidade'];
	    	} 
	     }else{
	    	    $table.= $dados[$j][6];
	    }
	    $table.='
	    </td>	     	               
	     <td>';
	$daores = new ResultadoDAO();
	$rows=NULL;
	//echo "meta".$dados[$j][7].','.$periodo;
	$detalhes="";
	//echo $dados[$j][7].",". $periodo;
	$rows = $daores->buscaresultaperiodometa($dados[$j][7], $periodo);//codmeta e periodo
	                            if ($dados[$j][10]==0.0){
	                            	$img = 'webroot/img/warning.png';
	                            	$url="#";
	                            	$detalhes='title="Indicador não possui meta definida!" data-trigger="hover"';
	                            }else  if ($rows->rowCount() == 0) { // não encontrou nenhum resultado
	                                $valor = '-';
	                                $img = 'webroot/img/add.png';
	                                $metrica = '-';
	                                $url = Utils::createLink('resultadopdi', 'adicionares', array('periodo' => $periodo,
	                                            'mperiodo'=>$periodo, //P
	                                            'periodo'=>  $dados[$j][1],                                             
	                                            'meta' => $dados[$j][7],//codigo meta
	                                            'objetivo' =>  $dados[$j][8],//codigo mapa
	                                            'indicador' => $dados[$j][4],
	                                            'documento' => $dados[$j][9])
	                                );
	                                $bt = 0; 
	                            }//if
	                            else { // caso contrário, deve-se alterar o resultado
	                                foreach ($rows as $row) {
	                                    $valor = $row['meta_atingida'];
	                                    $img = "webroot/img/editar.gif";
	                                    $metrica = $dados[$j][6] ;
	                                    $url = Utils::createLink('resultadopdi', 'altresultado', array('periodo' => $periodo,
	                                            'mperiodo'=>$periodo,
	                                            'periodo'=>  $dados[$j][1], 
	                                            'meta' => $dados[$j][7],//codigo meta
	                                            'objetivo' =>  $dados[$j][8],//codigo mapa
	                                            'indicador' => $dados[$j][4],
	                                            'documento' => $dados[$j][9]
	                                                    )
	                                    );
	                                   
	                                }
	                                
	                                
	                                
	                            }//else                                       
	                                       
	                                   
	                                       
	                       
	       $table.='<a href="'. $url.'"><img src="'.$img.' "'.$detalhes.'/></a>   
	     </td>
	  </tr>
    </tbody>';
	      }   //if 
	      
   
} //for 

$table.=' </table></div>';
	} //fim condicional para verificar se foi selecionado "Todas as Unidades"
	     $auxAvaliacao = 0; // Variável para verificar se já existe uma avaliação para o plano
	     $avaldao = new AvaliacaofinalDAO();
	     $avall = $avaldao->buscaAvalDP( $coddoc,$codcal, $idPeriodo);
	     foreach ($avall as $r){
	     	$codaval=$r['codigo'];
			 $auxAvaliacao=1;
			 
	     }  
	    
	     
	    // echo $bt."-". $periodo."==". $idPeriodo."?";	 
    
    foreach ($rows as $r){
        $cal=new Calendario();
        $cal->setCodigo($r['codCalendario']);
        $doc=new Documento();
        $doc->setCodigo($r['codDocumento']);
        $aval=new Avaliacaofinal();
        $aval->setCodigo($r['codigo']);
        $aval->setPeriodo($r['periodo']);
        $aval->setRat($r['RAT']);
        $aval->setAvaliacao($r['avaliacao']);
        
    }

	 if($bt == 1 and $periodo == $idPeriodo){ 
	 			
	 			if ($auxAvaliacao == 0) {?>
					<div class="alert alert-danger" role="alert">Você já lançou todos os resultados, agora só falta preencher o formulário de Gestão do Plano. <a href = "<?php echo Utils::createLink("avaliacao", "registraEditaAvaliacao", array('coddoc'=>$coddoc,'codcal'=>$codcal,'periodo'=>$periodo) ); ?>">Clique aqui</a> para realizar o preenchimento.</div>	
				<?php }else{?>
					<div class="alert alert-danger" role="alert"><a href = "<?php echo Utils::createLink("avaliacao", "registraEditaAvaliacao", array('coddoc'=>$coddoc,'codcal'=>$codcal,'periodo'=>$periodo) ); ?>">Clique aqui</a> para consultar a Gestão do Plano realizada.</div>
				<?php } ?>

	 			
	 				 			   
	     <?php }
	     echo $table;
	     
	    // echo $bt."-". $periodo."==". $idPeriodo."?";
	 if($bt == 1 and $periodo == $idPeriodo){ 
		  
			if ($auxAvaliacao == 0) {?>
					<a href = "<?php echo Utils::createLink("avaliacao", "registraEditaAvaliacao", array('coddoc'=>$coddoc,'codcal'=>$codcal,'periodo'=>$periodo) ); ?>" class = "btn btn-primary" ><?php  if($varperiodo == 'Parcial'){ echo "Realizar Gestão do Plano Parcial";}else{echo "Realizar Gestão do Plano Final";} ?></a>   
				<?php }else{?>
					<a href = "<?php echo Utils::createLink("avaliacao", "registraEditaAvaliacao", array('coddoc'=>$coddoc,'codcal'=>$codcal,'periodo'=>$periodo) ); ?>" class = "btn btn-primary" ><?php  if($varperiodo == 'Parcial'){ echo "Consultar Gestão do Plano Parcial realizada";}else{echo "Consultar a Gestão do Plano Final realizada";} ?></a>   
			<?php } ?>
				
	            
		 <?php }           

} else{
	if (in_array($codunidade, $unidades_estrategicas)){//temporario?>
        <div class="card-body">
			<div class="erro">
				<img src="webroot/img/error.png" width="30" height="30"/>
				Lançamento de Resultados Indisponível para esta Unidade!<br>
			</div>
		</div>
<?php } else  if (count($string)>0){ ?>

	   <div class="erro">
			<img src="webroot/img/error.png" width="30" height="30"/>
			<?php print 'Não é possível realizar o lançamento, devido as seguintes pendências:</br>'; ?>
		</div>
		<div class="card-body">
			<table class="table table-bordered table-hover">
				<tr>
					<th>Pendência</th>
					<th>Objetivo</th>
					<th>Indicador</th>
				</tr>
				<?php for ($i=1;$i<=count($string);$i++){
					echo '<tr>'.$string[$i].'</tr>';
				 }?>
			</table>
		</div>
	<?php
	
	}
   
}//pendências

}//solicitações

?>


