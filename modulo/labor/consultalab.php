<?php
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[7]) {
    	exit();
    
} else {
    require_once('dao/laboratorioDAO.php');
    require_once('classes/laboratorio.php');
    require_once('dao/tplaboratorioDAO.php');
    require_once('classes/tplaboratorio.php');
    
    $codunidade=$sessao->getCodUnidade();//codigo da unidade do usuário
    
    $daolab = new LaboratorioDAO();
    $daotipolab = new TplaboratorioDAO();
    $cont = 0;
    $rows_tlab = $daotipolab->Lista();
    foreach ($rows_tlab as $row) {
        $tiposlab[$cont] = new Tplaboratorio();
        $tiposlab[$cont]->setCodigo($row['Codigo']);
        $tiposlab[$cont]->setNome($row['Nome']);
        $cont++;
    }
    $contreg = 0;
    $cont1 = 0;
    $locks = array(); // array de lock
    $labs = array(); // array de laboratorios
    $button = true; // botão
    $daoun = new UnidadeDAO();
    
    if ($sessao->getCodUnidadeSup()!= 1){
		    $unidade = $daoun->queryByUnidadeResponsavel($sessao->getCodUnidadeSup());
		    foreach ($unidade as $uni) 
		    {
		      if ($sessao->getUnidadeResponsavel()==20){
		       if ($uni["CodUnidade"]==$sessao->getCodUnidade()){
		    	$array_codunidade[] = $uni["CodUnidade"];//Quando for polo, só exibe os laboratórios do polo, array_codunidade tem só uma posicao
		       }
		      }
		      else{
		      	$array_codunidade[] = $uni["CodUnidade"];
		      
		      }
		    }
		    array_shift($array_codunidade);
		    $codigo= $daoun->buscaidunidade($sessao->getCodUnidadeSup());
		    foreach ($codigo as $cod)
		    {
		    	$codunidaderesp = $cod["CodUnidade"];
		    }
		    
		    $array_codunidade[] = $codunidaderesp;
   }
   
   $rowscodsup = $daoun->RetornaCodUnidadeSuperior($cpga);
   foreach ($rowscodsup as $row)
   {
   	$codunidadesup = $row['CodUnidade'];
   }
    for ($j = 0; $j < count($array_codunidade); $j++) 
    {
        // codigo das unidades registradas no array         
    	//$rows = $daolab->buscaLaboratoriosUnidade($array_codunidade[$j]);

    	$rows = $daolab->buscaLaboratoriosUnidadeAnoD($array_codunidade[$j],$anobase);
       
        foreach ($rows as $row) 
        {
            $tipo = $row['Tipo'];
            foreach ($tiposlab as $tipolab) 
            {
                if ($tipolab->getCodigo() == $tipo) 
                {
                    $cont1++;
                    $tplab = $tipolab;
                    $lab = new Laboratorio();
                    $lab->setCodlaboratorio($row["CodLaboratorio"]); // configura o código do laboratório                    
                    $lab->setNome($row["Nome"]); // configura o nome
                    $lab->setCapacidade($row["Capacidade"]); // configura a capacidade
                    $lab->setSigla($row["Sigla"]); // sigla do laboratório
                    $lab->setLabensino($row["LabEnsino"]); // laboratório de ensino
                    $lab->setArea(str_replace(".", ",", $row['Area'])); // área do laboratório
                    $lab->setSituacao($row["Situacao"]); // situação
                    $lab->setAnoativacao($row["AnoAtivacao"]); // ano de ativação
                    $lab->setAnodesativacao($row["AnoDesativacao"]); // ano de desativacao
                    $lab->setTipo($tipolab); // tipo do laboratório
                    //unidade do laboratorio  
                    $uni=new Unidade();
                    $uni->setCodunidade($row["CodUnidade"]);
                    $uni->setNomeunidade($row["NomeUnidade"]);
                    $lab->setUnidade($uni);
                    
                    $l = new Lock();
                    $l->setData($lab);
                    // Se o laboratório não pertencer a unidade ou subunidade
                    if ($row["CodUnidade"] != $codunidade) {
                        $l->setLocked(true);
                    }
                    
                    // fim
                    // Se é subunidade
                    // Bloqueio dos dados
                    if (!$sessao->isUnidade()) {
                        // Teste se a subunidade possui dados homologados
                        $l->setLocked(Utils::isApproved(7, $codunidadesup, $array_codunidade[$j], $lab->getAnoativacao()));
                    }
                    // Teste se já existe dados cadastrados naquele anobase 
                    // Se há dados de subunidades e o ano base é igual ao ano de ativação
                    // bloqueia o botão de inserção
                    if ($sessao->isUnidade() && $lab->getAnoativacao() == $anobase && $codunidade != $array_codunidade[$j]) {
                        $button = false;
                    }
                    // Subunidade com dados homologados no ano base
                    if (!$sessao->isUnidade() && Utils::isApproved(7, $codunidadesup, $codunidade, $lab->getAnoativacao())
                       && $anobase == $lab->getAnoativacao()) {
                        $button = false;
                    }
                    // configura os bloqueios
                    $locks[] = $l;
                    $cont1++; // contador  
                }
            }
        }
    }
    
    
    
    $daolab->fechar();
    if ($cont1 == 0) {
        Utils::redirect('labor', 'incluilab');
    }
}
?>
<?php require_once 'notificacaolabor.php'; ?>
<?php echo Utils::deleteModal('Remover Laboratório', 'Você tem certeza que deseja remover o laboratório selecionado?');?>
<head>
<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active">Consultar laboratórios</li>
		</ul>
	</div>
	<div class="ui-widget">
    <div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;">
        <p>
            <span class="ui-icon ui-icon-alert" 
                  style="float: left; margin-right: .3em;"></span>
            <strong>Importante:</strong>
        </p>
        <p>Em 2018, atualizamos os cursos, então verifique se os vínculos dos laboratórios aos cursos estão corretos, caso contrário, corrija, por favor.</p>
        <p>Verifique se os cursos listados realmente pertencem a sua unidade, caso contrário, ligue para 8504.</p>
        <p>Agora você só precisa vincular os laboratórios apenas aos cursos de GRADUAÇÃO. Esta informação é IMPRESCINDÍVEL para o Censo da Educação Superior.</p>
        <p>Cadastre <strong>TODOS</strong> os novos laboratórios da sua unidade, porque a área registrada é utilizada no cálculo da Matriz Orçamentária.</p>
        <p>Quando for exibido o ícone <img src="webroot/img/add.png"  alt="Adicionar"/>, significa que não há cursos vinculados ao laboratório. Caso o laboratório seja utilizado apenas por curso de Pós-graduação, o vínculo não é mais necessário. </p>
        
    </div>
</div>
</head>
<h3 class="card-title">Laboratórios </h3><br/>
<?php if ($cont > 0) { ?>
    <script>
        $(function () {
            $("#exportar_pdf").click(function () {
                $("#pdf").submit();
            });
        });
    </script>
    <form class="form-horizontal" name="pdf" id="pdf" method="POST" action="relatorio/labor/exportarpdf.php"></form>
    <div class="download" style="margin-left: 3%;">
        <ul class="pdf">
            <li><a href="#" id="exportar_pdf">Exportar em PDF</a></li>
        </ul>
    </div>
    <table id="tablesorter" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Unidade</th>
                <th>Nome </th>
                <th>Tipo</th>
                <th>Capacidade</th>
                <th>Alterar</th>
                <th>Excluir</th>
                <th>Vincular cursos</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th colspan="7" class="ts-pager form-horizontal">
                    <button type="button" class="btn first"><i class="icon-step-backward glyphicon glyphicon-step-backward"></i></button>
                    <button type="button" class="btn prev"><i class="icon-arrow-left glyphicon glyphicon-backward"></i></button>
                    <span class="pagedisplay"></span> <!-- this can be any element, including an input class="form-control"-->
                    <button type="button" class="btn next"><i class="icon-arrow-right glyphicon glyphicon-forward"></i></button>
                    <button type="button" class="btn last"><i class="icon-step-forward glyphicon glyphicon-step-forward"></i></button>
                    <select class="custom-select" title="Select page size">
                        <option selected="selected" value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                    </select>
                    <select class="pagenum input-mini" title="Select page number"></select>
                </th>
            </tr>
        </tfoot>
        <tbody>
            
    <?php 
    for ($i = 0; $i < count($locks); $i++) {
        $lab1 = $locks[$i]->getData();
        
        
        
        	
        	?>
        <tr>
                    <td><?php print ($lab1->getUnidade()->getNomeunidade()); ?></td>
                    <td><?php print ($lab1->getNome()); ?></td>
                    <td><?php print ($lab1->getTipo()->getNome()); ?></td>
                    <td><?php print ($lab1->getCapacidade()); ?></td>
                    
             
                    <td>                                                                     
                         <a
                         href="<?php echo Utils::createLink('labor', 'altlab', array('codlab' => $lab1->getCodlaboratorio(), 'operacao' => 'A')) ?>"                    
                         target="_self"><img src="webroot/img/editar.gif" alt="Alterar" width="19" height="19" /> </a>
                    
                    </td>
            
        <?php 
        //O locks só verifica se foi homologado ou não, se foi homologado não permite mais alterar        
        
        if (!$locks[$i]->getLocked() && $codunidade==$lab1->getUnidade()->getCodUnidade()): ?>
                        <td align="center">
                            <a href="<?php echo Utils::createLink('labor', 'dellab', array('codlab' => $lab1->getCodlaboratorio())); ?>" class="delete-link" target="_self"><img src="webroot/img/delete.png" alt="Excluir" width="19" height="19" /> </a>
                        </td>
        <?php else: ?>
                        <td> 
                        

         <button "disabled"  title='Não é possível excluir, pois o laboratório pertence a outra subunidade!' data-trigger='hover'> <img src='webroot/img/delete.no.png' alt='Ajuda' data-trigger='hover' width="17" height="17" ></button>

                                    
                      </td>
        <?php endif; ?>
       <?php //if (!$locks[$i]->getLocked() ): ?>
                    <?php
                     $num = $daolab->buscaVinculoLabCurso($lab1->getCodlaboratorio(),$sessao->getAnobase());?>
                    <td align="center">
                        <?php foreach ($num as $row){
                        	if($row['qtdCursos'] >= 1){?> 
                        	<a href="<?php echo Utils::createLink('labor', 'conslabcurso', array('codlab' => $lab1->getCodlaboratorio())); ?>" 
                        	target="_self"><img src="webroot/img/busca.png"  alt="Visualizar"/> </a>
                        	<?php }else{?>
                        	    
                        		<a href="<?php echo Utils::createLink('labor', 'conslabcurso', array('codlab' => $lab1->getCodlaboratorio())); ?>" target="_self"> 
                        		<img src="webroot/img/add.png"  alt="Adicionar"/> </a>
                        		
                        		
                        		
                        	<?php }
                        	//data-toggle="popover" title="Popover Header" data-content="Some content inside the popover"                        		
                        	
                        }?>
                            
                        </td>
        <?php //else: ?>
                       <!--  <td></td> -->
                    <?php // endif; ?>
                </tr>
<?php
                }//for
                
                
                
  //  }//if
                ?>
        </tbody>
    </table><br/>
            <?php if ($button): ?>
        <form class="form-horizontal" name="fconsultar" method="post" action="<?php echo Utils::createLink('labor', 'incluilab'); ?>">
            <input class="form-control"value="Incluir novo laboratório" class="btn btn-info" type="submit"  class="btn btn-info"/>
        </form>
    <?php endif; ?>
<?php
} else {
    print "Nenhum laboratório registrado.";
}
