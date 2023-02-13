<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[14]) {
    echo "Você não tem permissão para visualizar este formulário!";die;
}
//$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
//$responsavel = $sessao->getResponsavel();
//$anobase = $sessao->getAnobase();
$codestruturado = $sessao->getCodestruturado();

require_once('classes/local.php');
$unidade = new Unidade();
$anobase=$sessao->getAnobase();

$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$cont = 0;
$codigo = $_GET["codigo"];
if (!is_numeric($codigo) && $codigo == "") {
     echo "Informações incorreta para acessar este formulário!"; die;
}
//busca psaude
$dao = new PsaudemensalDAO();

//-------------

$daou = new UnidadeDAO();

if ($sessao->getUnidadeResponsavel()>1){
    $rows=$daou->buscaidunidade($sessao->getUnidadeResponsavel());
    foreach ($rows as $r){
        $codunidade=$r['CodUnidade'];// quandoo usuario for subunidade
        $nomeunidade=$r['NomeUnidade'];
        $codestruturado = $r['hierarquia_organizacional'];
        $resp=$r['unidade_responsavel'];
    }
    
}else{
    $rs = $daou->unidadeporcodigo($sessao->getCodUnidade());
    foreach ($rs as $r) {
        $nomeunidade=$r['NomeUnidade'];
        $codestruturado = $r['hierarquia_organizacional'];
        $resp=$r['unidade_responsavel'];
    }
}
$tipounidade = ($codunidade == 1644)? "L":"P";
    
if ($codunidade == 270) {
    $locais = array();
    $cont = 0;
    $rows = $daou->buscalocais($codestruturado);
    foreach ($rows as $row) {
        $cont++;
        $locais[$cont] = new Local();
        $locais[$cont]->setCodigo($row['CodUnidade']);
        $locais[$cont]->setNome($row['NomeUnidade']);
        $locais[$cont]->setTipo($row['TipoUnidade']);
    }
}


$seljan = "";
$selfev = "";
$selmar = "";
$selabr = "";
$selmai = "";
$seljun = "";
$seljul = "";
$selago = "";
$selset = "";
$selout = "";
$selnov = "";
$seldez = "";



//Pega o registro a ser alterado
if ($codunidade == 270){
    $rows = $dao->psaudecodigo($codigo);
}else{
	
    $rows = $dao->psaudecodigo1($codigo);
}

$ps=new Psaudemensal();
$cont=0;
$mensal=array();
foreach ($rows as $row) {
    $subunid=new Unidade();
    $subunid->setCodunidade($row['codsubunidade']);
    $subunid->setNomeunidade($row['subunidade']);
    $subunid->criaServico($row['codservico'], $row['servico']);//ja vinculado a subunidade
    $p = new Procedimento();
    $p->setCodigo($row['codproc']);
    $p->setNome($row['procedimento']);
    $subunid->getServico()->criaSp(null, $p);//cria associação entre serviço e procedimento
    
    //local
    $loc=new Unidade();
    $loc->setCodunidade($row['codlocal']);
    $loc->setNomeunidade($row['local']);
    
    
    $subunid->getServico()->getSp()->criaPsaude(null, $p, $loc, $anobase, 
        $row['Mes'],  $row['ndocentes'], $row['ndiscentes'], $row['npesquisadores'], 
        $row['npessoasatendidas'],  $row['nprocedimentos'],  $row['nexames']);
    
    
   $cont++;
   $mensal[$cont]=$subunid;
    
   
}
for ($i=1;$i<=count($mensal);$i++) {
    $psaude= $mensal[$i]->getServico()->getSp()->getPsaude();
    
    if ($psaude->getMes()== 1)
        $seljan = "selected='selected'";
    if ($psaude->getMes() == 2)
        $selfev = "selected='selected'";
    if ($psaude->getMes()== 3)
        $selmar = "selected='selected'";
    if ($psaude->getMes()== 4)
        $selabr = "selected='selected'";
    if ($psaude->getMes()== 5)
        $selmai = "selected='selected'";
    if ($psaude->getMes()== 6)
        $seljun = "selected='selected'";
    if ($psaude->getMes()== 7)
        $seljul = "selected='selected'";
    if ($psaude->getMes()== 8)
        $selago = "selected='selected'";
    if ($psaude->getMes()== 9)
        $selset = "selected='selected'";
    if ($psaude->getMes()== 10)
        $selout = "selected='selected'";
    if ($psaude->getMes()== 11)
        $selnov = "selected='selected'";
    if ($psaude->getMes()== 12)
        $seldez = "selected='selected'";   
      
        $localizacao=$psaude->getLocal()==NULL?"":$psaude->getLocal()->getCodunidade();
    ?>

    <div class="bs-example">
        <ul class="breadcrumb">
            <li class="active">
                <a href="<?php echo Utils::createLink('prodsaude4', 'incluipsaude5'); ?>">
                Consultar Produção da Área da Saúde</a>
                <i class="fas fa-long-arrow-alt-right"></i>
                <a href="<?php echo Utils::createLink('prodsaude4', 'mostraprod',
                    array('servico'=>$mensal[$i]->getServico()->getCodigo(),
                        'proced'=>$mensal[$i]->getServico()->getSp()->getProcedimento()->getCodigo(),
                        'sub'=>$mensal[$i]->getCodunidade(),
                        'local'=>$localizacao
                        
                    )); ?>" >Consultar Produção Mensal</a>  
                <i class="fas fa-long-arrow-alt-right"></i>
                <a href="#" >Alterar Produção Mensal</a>
            </li>  
        </ul>
    </div>
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Produção da Área da Saúde</h3>
        </div> 
        <form class="form-horizontal" name="fpsaude4" id="fpsaude4" method="post">


            <table class="card-body">            
            <div class="msg" id="msg"></div>
            <div id="resultado"></div>
            
            <input class="form-control"type="hidden" name="codigo" value="<?php print $row['Codigo']; ?>"	/>
            <tr>
               <td class="coluna1"> <label>M&ecirc;s</label></td>
               </tr>
                <tr>
                    <td class="coluna2">
                     <select class="custom-select" name="mes" class="sel1">
                    <option  <?php echo $seljan; ?>value="1">janeiro</option>
                    <option <?php echo $selfev; ?> value="2">fevereiro</option>
                    <option <?php echo $selmar; ?> value="3">mar&ccedil;o</option>
                    <option <?php echo $selabr; ?> value="4">abril</option>
                    <option <?php echo $selmai; ?> value="5">maio</option>
                    <option <?php echo $seljun; ?> value="6">junho</option>
                    <option <?php echo $seljul; ?> value="7">julho</option>
                    <option <?php echo $selago; ?> value="8">agosto</option>
                    <option <?php echo $selset; ?> value="9">setembro</option>
                    <option <?php echo $selout; ?> value="10">outubro</option>
                    <option <?php echo $selnov; ?> value="11">novembro</option>
                    <option <?php echo $seldez; ?> value="12">dezembro</option>
                </select>
                </td>
            </tr>
            <tr>
                <td class="coluna1"><label>Subunidade</label></td>
                </tr>
                <tr>
                    <td class="coluna2">
                <?php //echo $tipounidade.','.$codestruturado; die;?>
                <select class="custom-select" name="subunidade"  id="subunidade_busca" class="sel1">
                    <option selected="selected" value="0"> Selecione uma subunidade...</option>
                    <?php
                      $rowsubs = $daou->buscasubunidades($tipounidade, $codestruturado);
                    
               foreach ($rowsubs as $r1) {
                  //  if ($r1['CodUnidade'] == $row['codsubunidade']) {
                  if ($r1['CodUnidade'] == $mensal[$i]->getCodunidade()) {
                      
                           ?>
                            <option selected="selected" value="<?php print $r1['CodUnidade']; ?>">
                                <?php print ( $r1['NomeUnidade']); ?></option>
                        <?php } else { ?>
                            <option value="<?php print $r1['CodUnidade']; ?>">
                                <?php print($r1['NomeUnidade']);  ?></option>
                            <?php
                        }
               }
                    ?>
                </select>
                </td>
            </tr>
            <?php if ($codunidade == 270) {//nmt  ?>

                <?php 
                //$local = $row['codlocal'];
                 $local = $psaude->getLocal()->getCodunidade();
                ?>
                <tr>
                  <td class="coluna1">  <label>Local</label></td>
                  </tr>
                <tr>
                    <td class="coluna2"> <select id="local" name="local" class="sel1">
                        <option value="0">Selecione local...</option>
                        <?php
                        $tamanho = count($locais);
                        for ($j = 1; $j <= $tamanho; $j++) {
                            if ($locais[$j]->getCodigo() == $local) {
                                ?>
                                <option selected="selected"  value="<?php print $locais[$j]->getCodigo(); ?>">
                                    <?php print ($locais[$j]->getNome()); ?></option>
                            <?php } else { ?>
                                <option  value="<?php print $locais[$j]->getCodigo(); ?>">
                                    <?php print ($locais[$j]->getNome()); ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                    </td>
            <?php } ?>
            </tr>
            <tr>
                <td class="coluna1"><label>Servi&ccedil;o</label></td>
                </tr>
                <tr>
                    <td class="coluna2"><span  id="txtHint1">
                    <?php
                    $daos = new ServicoDAO();
                    $codsubunidade = $row['codsubunidade'];
                    
                    
                    
                    $unidade->criaSubunidade($codsubunidade, null, null);
                    $rows1 = $daos->buscaservicos($codunidade, $codsubunidade);
                    $passou = false;
                    foreach ($rows1 as $row1) {
                        $passou = true;
                        $unidade->getSubunidade()->adicionaItemServico($row1['Codigo'], $row1['Nome']);
                    }
                    $daos->fechar();
                    ?>
                    <select  id="servico"  name="servico" class="sel1">
                        <option value="0">Selecione um servi&ccedil;o...</option>
                        <?php
                        
                       foreach ($unidade->getSubunidade()->getServicos() as $s) {
                           // if ($s->getCodigo() == $row['codservico']) {
                           if ($s->getCodigo() == $mensal[$i]->getServico()->getCodigo()) {
                           ?>
                                <option  selected="selected" value="<?php echo $s->getCodigo(); ?>"><?php echo ($s->getNome()); ?></option>
                            <?php } else { ?>
                                <option  value="<?php echo $s->getCodigo(); ?>"><?php print ($s->getNome()); ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </span>
                </td>
            </tr>
            <tr>
                <td class="coluna1"><label>Procedimento</label></td>
                
                </tr>
                <tr>
                    <td class="coluna2"><span id="txtHint2">
                    <?php $daos = new ServprocDAO(); ?>
                    <select id="procedimento" name="procedimento" class="sel1">
                        <option value="0">Selecione um procedimento...</option>
                        <?php
                        if ($codunidade == 270)
                            //    $rowsp = $daos->buscaservproced($row['codservico'], $row['codsubunidade']);
                            $rowsp = $daos->buscaservproced($mensal[$i]->getServico()->getCodigo(), $mensal[$i]->getCodunidade());
                            
                        else
                           // $rowsp = $daos->buscaservproced1($row['codsubunidade']);
                            $rowsp = $daos->buscaservproced1($mensal[$i]->getCodunidade());
                           
                        foreach ($rowsp as $rowp) {
                            // if ($rowp['CodProced'] == $row['codproc']) {
                            if ($rowp['CodProced'] == $mensal[$i]->getServico()->getSp()->getProcedimento()->getCodigo()) {
                            ?>
                                <option  selected="selected" value="<?php echo $rowp['CodProced']; ?>"><?php echo ($rowp['Nome']); ?></option>
                            <?php } else { ?>
                                <option  value="<?php echo $rowp['CodProced']; ?>"><?php echo ($rowp['Nome']); ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </span>
                </td>
            </tr>
            <tr>
                <?php if ($codunidade == 270) { ?>

                  <td class="coluna1">  <label>Discentes</label></td>
                  </tr>
                <tr>
                    <td class="coluna2"> <input class="form-control"type="text" name="ndisc" value="<?php
                   //print $row['ndiscentes'];
                   print $mensal[$i]->getServico()->getSp()->getPsaude()->getNdiscentes();
                   
                            ?>" size="5" maxlength="5"
                           onkeypress='return SomenteNumero(event)' class="short"/></td>
                </tr>
                <tr>
                    <td class="coluna1"><label>Docentes</label></td>
                    </tr>
                <tr>
                    <td class="coluna2"><input class="form-control"type="text" name="ndoc" value="<?php 
                    //print $row['ndocentes'];
                    print $mensal[$i]->getServico()->getSp()->getPsaude()->getNdocentes();
                    
                    ?>" size="5" maxlength="5"
                           onkeypress='return SomenteNumero(event)' class="short" /></td>
                </tr>
                <tr>
                    <td class="coluna1"><label> Pesquisadores </label></td>
                    </tr>
                <tr>
                    <td class="coluna2"><input class="form-control"type="text" name="npesq" value="<?php 
                  //  print $row['npesquisadores']; 
                    print $mensal[$i]->getServico()->getSp()->getPsaude()->getNpesquisadores();
                    
                    ?>" size="5" maxlength="5"
                           onkeypress='return SomenteNumero(event)' class="short" /></td>
                </tr>
                <tr>
                   <td class="coluna1"> <label>Pessoas atendidas</label></td>
                   </tr>
                <tr>
                    <td class="coluna2"> <input class="form-control"type="text" name="npaten" value="<?php
                   //print $row['npessoasatendidas']; 
                   print $mensal[$i]->getServico()->getSp()->getPsaude()->getNpessoasatendidas();
                   
                   ?>" size="6" maxlength="5"
                           onkeypress='return SomenteNumero(event)' class="short" /></td>
                </tr>
                <tr>
			     <td class="coluna1"><label>Procedimentos</label></td>
                 </tr>
                <tr>
                    <td class="coluna2"><input class="form-control"type="text" name="nproc" value="<?php
				            //print $row['nprocedimentos']; 
				            print $mensal[$i]->getServico()->getSp()->getPsaude()->getNprocedimentos();
				            
				            ?>" size="6" maxlength="5"
				                           onkeypress='return SomenteNumero(event)' class="short" /></td>
                </tr>
                <tr>
                <?php } else if ($codunidade == 202) { ?>
                    <td class="coluna1"><label>Pessoas atendidas</label></td>
                </tr>
                <tr>
                    <td class="coluna2"><input class="form-control"type="text" name="npaten" value="<?php 
                   // print $row['npessoasatendidas'];
                    print $mensal[$i]->getServico()->getSp()->getPsaude()->getNpessoasatendidas();
                    
                    ?>" size="6" maxlength="5"
                           onkeypress='return SomenteNumero(event)' class="short" /></td>
                </tr>
                <tr>
                <?php } else { ?>
                    <td class="coluna1"><label>Exames realizados</label></td>
                </tr>
                <tr>
                    <td class="coluna2"><input class="form-control"type="text" name="nexames" value="<?php 
                  //  print $row['nexames']; 
                    print $mensal[$i]->getServico()->getSp()->getPsaude()->getNexames();
                    
                    ?>" size="6" maxlength="5"
                           onkeypress='return SomenteNumero(event)' /></td>
                </tr>
            <?php } ?>
            
            </table>
            <div class = "card-body">
                <input class="form-control"name="operacao" type="hidden" value="A" />
                <input type="button" value="Gravar" name="Gravar" class="btn btn-info btn"/>&ensp;
            </div>
        </form>
    </div>
<?php
}//for row
$dao->fechar();
//    ob_end_flush();
?>
<script>
$(function() {
    $('input[name=Gravar]').click(function() {
            
    	
		$('div#resultado').empty();
        $.ajax({url: "ajax/prodsaude4/incprodsaude4.php", type: 'POST', data:$('form[name=fpsaude4]').serialize(), success: function(data) {
                $('div#resultado').html(data);
            }});
    	
    	});   
});
</script>