<?php
error_reporting(0);
require 'dao/UtilizacaoDAO.php';
require 'classes/Utilizacao.php';
//session_start();
$sessao = $_SESSION["sessao"];
if (!isset($sessao)) {
    exit();
}
$codUnidade = $sessao->getCodUnidade();
$ano = $sessao->getAnobase();
$daoutil = new UtilizacaoDAO();
$rows = $daoutil->listaAplicacoes();
$unidade = NULL;
foreach ($rows as $row) {
    if ($codUnidade == $row["CodUnidade"]) {
        $unidades[$row['NomeUnidade']][] = array(
            'aplicacao' => $row['Nome'],
            'codap' => $row['Codigo'],
            'unidade' => $row['NomeUnidade'],
            'codunidade' => $row['CodUnidade']
        );
    }
}
$daoutil->fechar();


////////////Verificar preenchimento do Formulário "Gestão do Plano"
$unidade2 = new Unidade();
$unidade2->setCodunidade($sessao->getCodunidade());
$unidade2->setNomeunidade($nomeunidade);
$codunidade1=$unidade2->getCodunidade();
$anogestao=$sessao->getAnobase();

$dao1=new DocumentoDAO();
$docufpa = $dao1->buscadocumentoPrazoEUnidade($anogestao, $codunidade);
foreach ($docufpa as $row){                		
  	 $coddoc=strip_tags($row["codigo"]);
}

$periodo = 2; //2- periodo final
$anobase = $sessao->getAnobase();
$c=new Controlador();
if ($c->getProfile($sessao->getGrupo())) {//se grupo for 18
    $codunidade=938;
    $class="style='visibility:hidden;'";
}else{
    $codunidade=$sessao->getCodUnidade();
    $class = "";
}
$daocal = new CalendarioDAO();
$daodoc= new DocumentoDAO();

//////////////////////////////////
$arraycal = $daocal->verificaPrazoCalendarioDoDocumento($sessao->getAnobase());
foreach($arraycal as $a){
    $varperiodo = $a['habilita'];
    $codcal=$a['codCalendario'];
}

if($varperiodo == 'Parcial'){
    $idPeriodo = 1;
}else if($varperiodo == 'Final'){
    $idPeriodo = 2;
}else{
    $idPeriodo = 0;
}

$string=array();
$rows11 =  $daodoc->pedenciasDocumento($coddoc,$sessao->getAnobase(),$sessao->getCodunidade());
$cont1=0;
foreach ($rows11 as $r){   
    if (empty($r['codmi'])){
        $cont1++;
        $string[$cont1]='<td>Vincular indicador (es) ao objetivo</td><td>'.$r['Objetivo']."</td><td>".$r['nome'].'</td>';
    }
    if (empty($r['metrica'])){
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
}else {    
    $unidade_ex=0;
    if (count($string)==0  ){
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
            $unidade_ex=0;
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
        
        
        $indicador=0;
	 
	   

	     
for ($j=0;$j<count($dados);$j++){
    
    if ($indicador!=$dados[$j][4]){
	         $indicador=$dados[$j][4];

	         
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
	       
	      }   //if 	      
   
} //for 


    if($bt == 1 and $periodo == $idPeriodo){ ?>
    	<?php 
    	//Verificar se existe gravação do gestão do plano
    	$daoaval = new AvaliacaofinalDAO();
    	$rowsAva=$daoaval->buscaAvalDP($coddoc, $codcal, $periodo);
    	if($rowsAva->rowCount()==0){
    	    
    	?>
	 	<div class="alert alert-danger" role="alert">A unidade já lançou todos os resultados, agora só falta preencher o formulário de Gestão do Plano. <a href = "<?php echo Utils::createLink("avaliacao", "registraEditaAvaliacao", array('coddoc'=>$coddoc,'codcal'=>$codcal,'periodo'=>$periodo) ); ?>">Clique aqui</a> para realizar o preenchimento.</div>
 			   
	<?php 
    	}
    }else{ 
          $rowPrazoResultadoFinal = $daocal->verificaPrazoCalendarioDoResultado($anobase);   
          foreach ($rowPrazoResultadoFinal as $rowPrazoResult){
              $habilita = $rowPrazoResult['habilita'];
          }
          if($habilita == "H" AND $bt !=2){
     ?>
    		
    		<div class="alert alert-danger" role="alert">A unidade ainda não lançou todos os resultados dos indicadores. <a href = "<?php echo Utils::createLink("resultadopdi", "consultaresult" );?>">Clique aqui</a> para realizar o lançamento.</div>		
	<?php  }
        }
    }//pendências

}//solicitações


//Notificação para alertar sobre o prazo do RAA
$daoRAA = new TextoDAO();
$rowRAA = $daoRAA->buscaFinalizacaoRel($codUnidade, $anobase);
if ($rowRAA->rowCount()==0) {
    $rowCal = $daocal->buscaCalendarSomenteioPorAnoBase($anobase);
    foreach ($rowCal as $row2){
        $dataFimRAA = strtotime($row2['dataFimAnaliseRAA']);
    }
    $dataHoje = date("Y-m-d");
    $dias = $dataFimRAA-$dataHoje;
    $dataParaAlerta = date('Y-m-d', strtotime('-7 days', $dataFimRAA));
    
    
    if (strtotime($dataHoje)>=strtotime($dataParaAlerta) && strtotime($dataHoje)< $dataFimRAA && $codUnidade != 100000 && $anobase >= 2021) {        
        ?>
        <!-- <div class="alert alert-danger" role="alert">O prazo está acabando e sua unidade ainda não finalizou o Relatório Anual de Atividades para o ano base.<br/><b>Prazo final: <?php // print date("d/m/Y",$dataFimRAA); ?></b></div>  -->
<?php 
    }
}

?>

<table>
    <tr>
        <th>Módulo</th>
        <th>Situação</th>
        <th>Ano</th>
    </tr>
    <?php
    if (!$sessao->isUnidade() && $sessao->getCodUnidade()!=100000)
{
    foreach ($unidades as $unidade => $uni) {
        $objut = new Utilizacao();
        // Ações do SIMEC
        $objut->condfigure(2, 'acao', array('`Ano` = ' => $ano, '`AnaliseCritica`' => '!=" "', '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Produção Intelectual
        $objut->condfigure(4, 'prodintelectual', null, "SELECT * FROM `prodintelectual` pi, `curso` c WHERE pi.`CodCurso` = c.`CodCurso` AND c.`CodUnidade` = " . $uni[0]['codunidade'] . " AND pi.`Ano` = $ano");
        // Prêmios
        $objut->condfigure(5, 'premios', array('`Ano` = ' => $ano, '`CodSubunidade` = ' => $uni[0]['codunidade']));
        // Computadores
        $objut->condfigure(6, 'micros', array('`Ano` = ' => $ano, '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Laboratorio
        $objut->condfigure(7, 'laboratorio', array('(`AnoAtivacao` <= ' => $ano . " OR `AnoDesativacao` <= $ano)", '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Infraestrutura de ensino
        $objut->condfigure(9, 'infraensino', array('`Ano` = ' => $ano, '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Estrutura de Acessibilidade
        $objut->condfigure(10, 'estrutura_acessibilidade', array('`Ano` = ' => $ano, '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Tecnologia Assistiva
        $objut->condfigure(11, 'tecnologia_assistiva', null, "SELECT * FROM `tecnologia_assistiva` ta, `curso` c WHERE ta.`CodCurso` = c.`CodCurso` AND c.`CodUnidade` = " . $uni[0]['codunidade'] . " AND ta.`Ano` = $ano");
        // Libras
        $objut->condfigure(12, 'librascurriculo', null, "SELECT * FROM `librascurriculo` lc, `curso` c WHERE lc.`CodCurso` = c.`CodCurso` AND c.`CodUnidade` = " . $uni[0]['codunidade'] . " AND lc.`Ano` = $ano");
        // Práticas Jurídicas
        $objut->condfigure(13, 'praticajuridica', array('`Ano` = ' => $ano, '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Produção das Clínicas
        $objut->condfigure(14, 'qprodsaude', array('`Ano` = ' => $ano, '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Patologia Tropical e Dermatologia
        $objut->condfigure(15, 'qprodsaude', array('`Ano` = ' => $ano, '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Produção Artística
        $objut->condfigure(18, 'prodartistica', array('`Ano` = ' => $ano, '`CodUnidade` = ' => $uni[0]['codunidade']));
        // Atividades de Extensão
        $objut->condfigure(19, 'atividadeextensao', array('`Ano` = ' => $ano, '`CodUnidade` = ' => $uni[0]['codunidade']));
        //Quadro RH ICA
        $objut->condfigure(24, 'rhetemufpa', array('`Ano` = ' => $ano, '`CodUnidade` = ' => $uni[0]['codunidade']));
        //Produção da Farmácia
        $objut->condfigure(16, 'prodfarmacia', array('`Ano` = ' => $ano));
        // Frequentadores da Farmácia
        $objut->condfigure(17, 'freqfarmacia', array('`Ano` = ' => $ano));
        // Projetos de Extensão
        $objut->condfigure(25, 'ea_projextensao', array('`Ano` = ' => $ano));
        // Projetos de Pesquisa
        $objut->condfigure(20, 'ea_projpesquisa', array('`Ano` = ' => $ano));
        // Educação Profissional e Livre
        $objut->condfigure(26, 'edprofissionallivre', array('`Ano` = ' => $ano));
        // Portadores de necessidades especiais
        $objut->condfigure(27, 'pnd', array('`Ano` = ' => $ano));
        // Práticas de Intervenções Metodológicas
        $objut->condfigure(28, 'pi_metodologicas', array('`Ano` = ' => $ano));
        // Incubadora
        $objut->condfigure(30, 'prodincubadora', array('`Ano` = ' => $ano));
        // Ensino Fundamental
        $objut->condfigure(21, 'ensino_ea', array('`Codtdmensinoea` >= ' => '9', '`Codtdmensinoea` <= ' => '25', '`Ano` = ' => $ano));
        // Ensino Médio
        $objut->condfigure(22, 'ensino_ea', array('`Codtdmensinoea` >= ' => '1', '`Codtdmensinoea` <= ' => '8', '`Ano` = ' => $ano));
        // Infraestrutura
        $objut->condfigure(8, 'infraestrutura', array('`CodUnidade` = ' => $uni[0]['codunidade'], '(`AnoAtivacao` = ' => $ano . " OR `AnoDesativacao` = $ano)"));
       // PND
        $objut->condfigure(27, 'pnd', null, "SELECT * FROM `pnd` p, `curso` c WHERE p.`CodCurso` = c.`CodCurso` AND c.`CodUnidade` = " . $uni[0]['codunidade'] . " AND p.`Ano` = $ano");
        $utilReport = $objut->getConfig();
        $daout = new UtilizacaoDAO();
        for ($i = 0; $i < count($uni); $i++) {
        	//echo $utilReport[$uni[$i]['codap']]['query'];
            //if ($utilReport[$uni[$i]['codap']]['query'] != null) {
        	  if(!empty($utilReport[$uni[$i]['codap']]['query'])){
                $rows = $daout->consulta($utilReport[$uni[$i]['codap']]['query']);
                if ($rows->rowCount() >= 1) {
                    $status = '<i class="glyphicon glyphicon-ok-sign" style="color: green"></i>';
                } else if ($rows->rowCount() == 0) {
                    $status = '<i class="glyphicon glyphicon-exclamation-sign" style="color:red"></i>';
                }
            } else {
                $status = "Não apurado";
            }
            echo "<tr>\n";
            echo "<td>" . $uni[$i]['aplicacao']. "</td>\n";
            echo "<td>" . $status . "</td>\n";
            echo "<td>" . $ano . "</td>\n";
            echo "</tr>\n";
        }
        echo "</tr>\n";
        unset($objut);
        $daout->fechar();
    }
}
    ?>
</table>

<script type="text/javascript" src="webroot/js/jquery-ui-1.10.3/js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="webroot/js/jquery-ui-1.10.3/js/jquery-ui-1.10.3.custom.js"></script>
 

<!-- Bootstrap 4 -->
<script src="novo_layout/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="novo_layout/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes <script src="novo_layout/dist/js/demo.js"></script>-->