<?php
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
$controlador=new Controlador();

// Definir ativação do menu
if(isset($_GET['modulo'])){
	$enable = $_GET['modulo'];
}else{
	$enable = "";
}

if(isset($_GET['acao'])){
	$subEnable = $_GET['acao'];
}else{
	$subEnable = "";
}

$c=new Controlador();

$background = "style='background-color:#eee'";
$texto = "style='color: #23527c'";
	
?>

 <!-- Navbar content -->
 
 <nav id ="grad" style="height: 130%;">
    <!-- Collect the nav links, forms, and other content for toggling -->
     <div  class="container" style="height: 71px;">
    	<a href="#" class="brand-logo"><img src="webroot/img/logo_proplan.png" width="190px" height="60px"></a> 		
    	<a href="#" class="brand-logo" style="position:relative;float:right;"><img src="webroot/img/sinpeg2.png" width="220px" height="65px" ></a>
    </div>
    <div id="contmenu" class="nav-wrapper container" style="height: 38px;width:1350.2px;margin-top: 40px;">
        <ul class="nav navbar-nav" <?php  if($enable=="usuario" || $enable=="uparquivo" || $enable=="simec"){echo $background;}?>>
            <li class="">
                <a <?php if($enable=="usuario" || $enable=="uparquivo" || $enable=="simec"){echo $texto;}?> href="#" class="dropdown-toggle" data-toggle="dropdown">SInPeG <b class="caret"></b></a>
                <ul class="dropdown-menu">
               
                  <!-- O link da opcao de menu é montada com o createlink, "envio de arquivo" 
                    é uma aplicação que deve ser exibido em todas as unidades  -->
                   <?php  
                   	 if ($sessao->getUnidadeResponsavel()==1){//unidade
                    ?>
                    <!--  <li><a href="<?php // echo Utils::createLink("uparquivo", "consultaarqs"); ?>"><span>Envio de arquivo</span></a>
                    </li> -->
                   
                    
<?php 
                    }
                    /* 
                     * O vetor aplicacoes contem as aplicacoes que a unidade pode utilizar 
                     */
                 
                     if ($aplicacoes[2]) { 
                     ?>
                        <!-- <li><a href="<?php //echo Utils::createLink("simec", "consultaacao"); ?>"><span></span> </a>
                        </li> -->
<?php } ?>
                    
                    <?php
                    if ($aplicacoes[23]) {
                        $caminho = Utils::createLink("usuario", "altusuario");
                        ?>
                        <li><a href="<?php echo Utils::createLink("usuario", "altusuario") ?>" >Atualizar Senha</a>
                        </li>
                    <?php } ?>
                    <?php if ($aplicacoes[3] ) { ?>
                        <li><a href="<?php echo Utils::createLink('usuario', 'incusuario'); ?>" ><span>Usu&aacute;rio
                                    de acesso</span> </a>
                        </li>
                    <?php } ?>
                    <?php if ($aplicacoes[47]) { ?>
                        <li><a href="<?php echo Utils::createLink('usuario', 'grupunidade'); ?>" ><span>Vincular usu&aacute;rio a um grupo</span> </a>
                        </li>
                    <?php } ?>
                    
                     <?php if ($aplicacoes[42]) { ?>
                                <li><a href="modulo/utilizacao/relatorio.php" ><span>Utilização</span>
                                    </a></li> 
                            <?php } ?>
                            
                            
                    
                            
                            
                            
                </ul>
            </li>
        </ul>
        
          
 <!-- 
          <ul class="nav navbar-nav">	             
	        <li class="dropdown">
	            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"> <span class="nav-label">Executar</span> <span class="caret"></span></a>
	            <ul class="dropdown-menu">
	                <li><a href="#">Service A</a></li>
	                <li><a href="#">Service B</a></li>
	                <li class="dropdown-submenu">
	                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <span class="nav-label">Service C</span></a>
	                    <ul class="dropdown-menu">
	                        <li><a href="#">Service C1</a></li>
	                        <li><a href="#">Service C2</a></li>
	                        <li><a href="#">Service C3</a></li>
	                        <li><a href="#">Service C4</a></li>
	                        <li><a href="#">Service C5</a></li>
	                    </ul>
	                </li>
	            </ul>
	        </li>
	    </ul>
          
          
       -->               
        
         <?php if ($aplicacoes[36] ||   $aplicacoes[37] || $aplicacoes[38] || $sessao->getCodUnidade() == 100000) { ?>
        
          <ul class="nav navbar-nav" <?php  if($enable=="documentopdi" ||  $enable=="mapaestrategico" || $enable=="iniciativa" ){echo $background;}?>>
                    <li class="dropdown">
                        <a <?php if( $enable=="documentopdi" || $enable=="mapaestrategico" || $enable=="iniciativa"){echo $texto;}?> href="#" class="dropdown-toggle" data-toggle="dropdown">Planejar<b class="caret"></b></a>
                        <ul class="dropdown-menu">   
                             <?php if ($aplicacoes[36]) { ?>
                                <li><a href="
                                    <?php echo Utils::createLink('documentopdi', 'editardocpdi'); ?>"><span>Definir Plano </span> </a>
                                </li>
                            <?php } ?>
                             <?php if ($sessao->getCodUnidade()==938) { ?> 
                                <li><a href="<?php echo Utils::createLink('calendarioPdi', 'listaCalendario'); ?>"><span>Definir Calendário </span> </a>
                                </li>
                            <?php } ?>
                             <?php if ($aplicacoes[37] ) { ?>
                          <li><a href="<?php echo Utils::createLink('mapaestrategico', 'listamapa');?>"><span>Elaborar Painel <?php echo $sessao->getCodunidade()==938?"Estratégico":"Tático";?></span> </a>
                                </li> 
                            <?php } ?>
                            <?php if ($c->getProfile($sessao->getGrupo()) && $sessao->getCodUnidade() != 100000) { //Exibe menu para participantes do grupo 18?>
							    <li><a href="<?php echo Utils::createLink('mapaestrategico', 'listamapapdu');?>"><span>Exportar Painel <?php echo $sessao->getCodunidade()==938?"Estratégico":"Tático";?></span> </a>
                                </li> 
<?php } ?>
                           
                            
                           
                                <?php if ($aplicacoes[38]) { ?>
                                <li><a href="<?php echo Utils::createLink('iniciativa', 'listaIniciativa'); ?>"><span>Cadastrar Iniciativa</span> </a>
                                </li>
                            <?php } ?>
                          
                          
                           <?php if ($sessao->getCodUnidade() == 100000) { ?>
							    <li><a href="relatorio/acompanhamentoPT.php?anoBase=<?php echo $sessao->getAnobase();?>")><span>Relatório de Cadastramento do PDU</span> </a>
		                        </li>
		 					 			 					     
		 					<?php } ?>
                            
                           
                            
                   </ul>
                    </li>
                </ul>             
                <?php }?>
         <?php if ($aplicacoes[29] || $aplicacoes[54] ) { ?>
              <ul class="nav navbar-nav" <?php  if( $enable=="resultadopdi" || $enable=="raa"){echo $background;}?>>
                    <li class="dropdown">
                        <a <?php if($enable=="resultadopdi" || $enable=="raa" ){echo $texto;}?> href="#" class="dropdown-toggle" data-toggle="dropdown">RAA<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                        	  <?php 
		                    if ($sessao->getCodUnidade() == 100000){//unidade
		                    ?>
		                     <li><a href="<?php echo Utils::createLink("raa", "consultarRelatorio"); ?>"><span>Consultar RAA</span></a>
		                    </li>
		                    <li><a href="<?php echo Utils::createLink("raa", "consultarTopicos"); ?>"><span>Criar Tópico do RAA</span></a>
		                    </li>
		                    <li><a href="<?php echo Utils::createLink("raa", "consultarModelos"); ?>"><span>Criar Modelo para Tópico do RAA</span></a>
		                    </li> 
		                    <li><a href="<?php echo  Utils::createLink('resultadopdi', 'consultaresult');  ?>"><span>Relatório dos Resultados</span> </a>
		 				   </li>                 
<?php 
		                    }                           
                             if ($aplicacoes[29] && $sessao->getCodUnidade() != 100000) { ?>
                                <li><a href="<?php echo  Utils::createLink('resultadopdi', 'consultaresult');  ?>"><span>Lançar Resultados</span> </a>
                                </li>
                            <?php } 
                               if ($aplicacoes[54]) { ?>
                    <li><a href="<?php echo Utils::createLink("raa", "listaTexto"); ?>"><span>Redigir Texto do RAA</span></a></li>
                    
                    
                    <?php  if ($sessao->getAnobase()>=2019){?>
                      <li><a href="<?php echo Utils::createLink("raa", "avalraapdu"); ?>"><span>Exportar Análise do RAA</span></a></li>
                      <?php } ?>
                    
                    
                    <?php }?>
                          
                         
                   </ul>
                    </li>
                </ul>                
        
        <?php } ?>
        
      
      
            <!--  
            <ul class="nav navbar-nav" >
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Biblioteca<b class="caret"></b></a>
                    <ul class="dropdown-menu">                                             
                            <li><a href="relatorio/relatorio_biblioteca.php?anoBase=<?php // echo $sessao->getAnobase();?>&codUnidade=<?php // echo $sessao->getCodUnidade();?>">Relatório</a>
                            </li>   
                                                                     
                    </ul>
                </li>
            </ul>
            -->
        <?php if ($aplicacoes[6] ||  $aplicacoes[7] || $aplicacoes[8] || $aplicacoes[9] || $aplicacoes[32] ||
                   $aplicacoes[33] || $aplicacoes[34] || $aplicacoes[10] || $aplicacoes[11] || $aplicacoes[35] ) { ?>
            <ul class="nav navbar-nav" <?php  if($enable=="micros" || ($enable=="labor" && $subEnable!="relatorioForm") || $enable=="infra" || $enable=="infraensino" || $enable=="epolo" || $enable=="acessib" || $enable=="tecnol"){echo $background;}?>>
                <li class="dropdown">
                    <a <?php if($enable=="micros" || ($enable=="labor" && $subEnable!="relatorioForm") || $enable=="infra" 
                    || $enable=="infraensino" || $enable=="epolo" || $enable=="acessib" || $enable=="tecnol"){echo $texto;}?> href="#" class="dropdown-toggle" data-toggle="dropdown">Censoo <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li class="arrow"></li>
                        <?php if ($aplicacoes[6]) { ?>
                            <li><a href="<?php echo Utils::createLink('micros', 'consultamicros'); ?>" ><span>Computadores</span>
                                </a></li>
                        <?php } ?>
                        <?php if ($aplicacoes[7]) { ?>
                            <li><a href="<?php echo Utils::createLink('labor', 'consultalab'); ?>" ><span>Laborat&oacute;rios</span>
                                </a></li> 
                        <?php } ?> 
                        <?php if ($aplicacoes[8]) { ?>
                            <li><a href="<?php echo Utils::createLink('infra', 'consultainfra'); ?>" ><span>Infraestrutura</span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if ($aplicacoes[51]) { //Para polos so vale a estrutura hierarquica .1.20.?>
                            <li><a href="<?php echo Utils::createLink('epolo', 'consultapolo'); ?>" ><span>Polos</span>
                                </a>
                            </li>
<?php } ?>
                        
                        <?php if ($aplicacoes[10]) { ?>
                            <li><a href="<?php echo Utils::createLink('acessib', 'consultaacess'); ?>"><span>Estrutura de Acessilidade</span> </a>
                            </li>
                        <?php } ?>
                        <?php if ($aplicacoes[11])  { ?>
                            <li><a href="<?php echo Utils::createLink('tecnol', 'cursounidades'); ?>"><span>Tecnologia Assistiva</span> </a></li>
                        <?php } ?>
                        <?php if ($aplicacoes[35]) { ?>
                            <li><a href="<?php echo Utils::createLink('acessib', 'buscaacess'); ?>"><span>Relatório da Estrutura de Acessibilidade</span>
                                </a></li> 
                        <?php } ?> 
                        
                        
                        <?php if ($aplicacoes[32]) { ?>
                            <li><a href="<?php echo Utils::createLink('labor', 'buscalab'); ?>"><span>Relatório dos Laboratórios</span>
                                </a></li> 
                        <?php } ?>
                        <?php if ($aplicacoes[33]) { ?>
                            <li><a href="<?php echo Utils::createLink('infra', 'consadmin'); ?>"><span>Relatório da Infraestrutura</span>
                                </a></li> 
                        <?php } ?> 
                        <?php if ($aplicacoes[34]) { ?>
                            <li><a href="<?php echo Utils::createLink('infraensino', 'buscainfraensino'); ?>" ><span>Relatório da Infraestrutura de Ensino</span>
                                </a></li>
                        <?php if ($aplicacoes[46]) { ?>
                            <li><a href="<?php echo Utils::createLink('micros', 'buscamicros'); ?>" ><span>Relatório de Micros</span>
                                </a></li>
                        <?php } ?>
                        <?php if ($aplicacoes[45]) { ?>
                            <li><a href="<?php echo Utils::createLink('biblio', 'buscabiblio'); ?>" ><span>Relatório de Bibliotecas</span>
                                </a></li>
                        <?php } ?> 
                         
                        <?php } ?> 
                    </ul>
                </li>
            </ul>
        <?php } ?>
      
        <?php if ($aplicacoes[5] ||$aplicacoes[15] || $aplicacoes[16] ||  $aplicacoes[13] || $aplicacoes[17] || $aplicacoes[14] || $aplicacoes[30] || $aplicacoes[28] || $aplicacoes[26] || $sessao->getCodUnidade() == 100000) { ?>
            <ul class="nav navbar-nav" <?php  if($enable=="prodintelectual" || $enable=="premios" || $enable=="prodintelectual" ||
             $enable=="premios" || $enable=="prodsaude4" || $enable=="prodsaude" || $enable=="produto" || $enable=="freq"){echo $background;}?>>
                       
                <li class="dropdown">
                    <a <?php if($enable=="prodsaude4" || $enable=="prodsaude" || $enable=="produto" || $enable=="freq" || $enable=="prodintelectual" || $enable=="premios"){echo $texto;}?>
                     href="#" class="dropdown-toggle" data-toggle="dropdown">Anuário<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                    
                    
                    <?php if ($aplicacoes[4] && $sessao->getUnidadeResponsavel() == 1) { ?>
                            <li><a href="<?php echo Utils::createLink('prodintelectual', 'cursosunidade'); ?>" ><span>Produ&ccedil;&atilde;o Intelectual</span> </a>
                            </li>
                        <?php } ?>
                          <li><a href="relatorio/relatorio_producao.php?anoBase=<?php echo $sessao->getAnobase();?>&codUnidade=<?php echo $sessao->getCodUnidade();?>">Relatório de Produção Intelectual</a>
                            </li>  
                            
                             <?php if ($aplicacoes[5]) { ?>
                            <li><a href="<?php echo Utils::createLink('premios', 'consultapremios'); ?>"><span>Pr&ecirc;mios</span></a>
                            </li>
                        <?php } ?>
                         <li><a href="relatorio/relatorio_premios2.php?anoBase=<?php echo $sessao->getAnobase();?>&codUnidade=<?php echo $sessao->getCodUnidade();?>">Relatório de Pr&ecirc;mios</a>
                            </li> 
                         <?php if ($aplicacoes[30]) { ?>
                                <li><a href="<?php echo Utils::createLink('incubadora', 'consultaincub'); ?>" ><span>Incubadora</span>
                                    </a></li> 
                            <?php } ?> 
                            
                           
                        
                            
                        <?php if ($aplicacoes[13]) { ?>
                            <li><a href="<?php echo Utils::createLink('praticajuridica', 'consultapjuridica'); ?>"
                                   ><span>Pr&aacute;ticas Jur&iacute;dicas</span>
                                </a></li>
                        <?php } ?>
                    
                            
                            
                  
                        <?php if ($aplicacoes[14]) { ?>
                            <li><a href="<?php echo Utils::createLink('prodsaude4', 'incluipsaude5'); ?>"><span>Produ&ccedil;&atilde;o
                                        da Área de Sa&uacute;de</span> </a>
                            </li>
                        <?php } ?>
                        <?php if ($aplicacoes[15]) { ?>
                            <li><a href="<?php echo Utils::createLink('prodsaude', 'consultapsaude'); ?>"><span>Patologia Tropical e Dermatologia</span> </a>
                            </li>
                        <?php } ?>
                        <?php if ($aplicacoes[16]) { ?>
                            <li><a href="<?php echo Utils::createLink('produto', 'consultapfarma'); ?>"><span>Produ&ccedil;&atilde;o
                                        da Farm&aacute;cia</span> </a>
                            </li>
                        <?php } ?>
                        <?php if ($aplicacoes[17]) { ?>
                            <li><a href="<?php echo Utils::createLink('freq', 'consultafreq'); ?>"><span>Frequentadores
                                        da Farm&aacute;cia</span> </a>
                            </li>
                        <?php } ?>
                        
                         <?php if ($aplicacoes[22]) { ?>
                            <li><a href="<?php echo Utils::createLink('ensinofund', 'consultaensino'); ?>">Quantitativo do Ensino Fundamental</a>
                            </li>
                        <?php } ?>
                        <?php if ($aplicacoes[21]) { ?>
                            <li><a href="<?php echo Utils::createLink('ensinomedio', 'consultaemedio'); ?>">Quantitativo do Ensino M&eacute;dio</a>
                            </li>
                        <?php } ?>
                        <?php if ($aplicacoes[20]) { ?>
                            <li><a href="<?php echo Utils::createLink('eaprojpesquisa', 'consultapesquisa'); ?>">Projetos de Pesquisa</a></li>
                        <?php } ?>
                        <?php if ($aplicacoes[25]) { ?>
                            <li><a href="<?php echo Utils::createLink('eaprojextensao', 'consultaextensao'); ?>">Projetos de Extens&atilde;o</a>
                            </li>
                        <?php } ?>
                        <?php if ($aplicacoes[28]) { ?>
                            <li><a href="<?php echo Utils::createLink('eapimetodologicas', 'consultapimetodologicas'); ?>">Pr&aacute;tica de Interven&ccedil;&otilde;es Metodol&oacute;gicas</a>
                            </li>
                        <?php } ?>
                          <?php if ($aplicacoes[18]) { ?>
                            <li><a href="<?php echo Utils::createLink('prodartistica', 'consultaprodartistica'); ?>"
                                   >Produ&ccedil;&atilde;o Art&iacute;stica</a>
                            </li>
                        <?php } ?>
                        <?php if ($aplicacoes[24]) { ?>
                            <li><a href="<?php echo Utils::createLink('edprofrh', 'consultarh'); ?>">Quadro de
                                    Pessoal</a>
                            </li>
                        <?php } ?>
                        <?php if ($aplicacoes[19]) { ?>
                            <li><a href="<?php echo Utils::createLink('atividades', 'consultaatividadeextensao'); ?>"
                                   >Atividades de Extens&atilde;o</a>
                            </li>
                        <?php } ?>
                        <?php if ($aplicacoes[26]) { ?>
                            <li><a href="<?php echo Utils::createLink('cledprofissional', 'conscleducprof'); ?>">Ed. Profissional e Cursos Livres</a>
                            </li>
                        <?php } ?>
                        
                    </ul>
                </li>
            </ul>
        <?php } ?>
        
        <!-- Menu para exportar a produção da área da saúde no formato do anuário -->
        <?php if ($sessao->getCodUnidade() == 100000) { ?>
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Saúde<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li class="arrow"></li>
                         <li><a href="relatorio/prodSaudeAnuario.php?anoBase=<?php echo $sessao->getAnobase();?>"><span>Produ&ccedil;&atilde;o da &Aacute;rea de Sa&uacute;de - Anuário</span> </a>
                         </li>                                      
                    </ul>
                </li>
            </ul>
        <?php } ?>
        
        
        
        
      
           
           
            <?php // if ($aplicacoes[43] || $aplicacoes[44]) { ?> 
                <ul class="nav navbar-nav" <?php  if( $enable=="painelmedicao" || $enable=="homologar" ){echo $background;}?>>
                    <li class="dropdown">
                        <a <?php if($enable=="painelmedicao" || $enable=="homologar"){echo $texto;}?> href="#" class="dropdown-toggle" data-toggle="dropdown">Avaliar<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li class="arrow"></li>
                            <?php if ($aplicacoes[43]) { ?>
                                <li><a href="<?php echo Utils::createLink('homologar', 'listaplicacao'); ?>" ><span>Homologar</span>
                                    </a></li> 
                            <?php } ?>
                            <?php if ($aplicacoes[44]): ?>
                                <li><a href="<?php echo Utils::createLink('homologar', 'reversao'); ?>" ><span>Reverter homologação</span>
                                    </a></li>    
                                <?php endif; ?>
                                
                                  <li>
                                <a href="<?php echo Utils::createLink('painelmedicao', 'painelmed'); ?>">Painel de Medições</a>
                            </li>
                             <li>
                                <a href="<?php echo Utils::createLink('painelmedicao', 'dashboard'); ?>">Análises do PDU/PDI</a>
                            </li>
                            <?php if ($sessao->getCodUnidade() == 100000) { ?>
							    <li><a href="relatorio/relatorioAvaliacao.php?anoBase=<?php echo $sessao->getAnobase();?>")><span>Relatório de Gestão do Plano</span> </a>
		                        </li>
		 					 <?php } ?>
                            

                            </ul>
                            
                            
                        </li>
                        
                        
                    </ul>   
                <?php //} ?>
               

          <?php
          if (array_key_exists("55",$aplicacoes)){
          		if ($aplicacoes[55] || $aplicacoes[44]) { ?> 
                <ul class="nav navbar-nav" <?php  if($enable=="indicadorpdi"){echo $background;}?>>
                    <li class="dropdown">
                        <a <?php if($enable=="indicadorpdi"){echo $texto;}?> href="#" class="dropdown-toggle" data-toggle="dropdown">Direcionar ações<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li class="arrow"></li>
                            
                            <?php //if ($aplicacoes[44]): ?>
                               <!-- <li><a href="<?php // echo Utils::createLink('homologar', 'reversao'); ?>" ><span>Reverter homologação</span>
                                    </a></li>-->    
                                <?php // endif; ?>

                            <?php if ($aplicacoes[55] ) { ?>
                          	<li><a href="<?php echo Utils::createLink('indicadorpdi', 'quadrosolicitacoes');?>"><span>Quadro de Solicitações</span> </a>
                            </li> 
                            <?php } ?>
                            
                             
                            
                            
                            
                            </ul>
                        </li>
                    </ul>   
<?php } 
				}
                ?>
                
                
                <?php if ($aplicacoes[45]) { ?> 
       <!--      <ul class="nav navbar-nav" <?php //if($enable=="biblio"){echo $background;}?>>
                    <li class="dropdown">
                        <a <?php //if($enable=="biblio"){echo $texto;}?> href="#" class="dropdown-toggle" data-toggle="dropdown">Biblioteca<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li class="arrow"></li>
                            <?php //if ($aplicacoes[45]) { ?>
                                <li><a href="<?php //echo Utils::createLink('biblio', 'altbiblicenso'); ?>" ><span>Censo <?php //echo $sessao->getAnobase(); ?></span>
                                    </a></li> 
                            <?php //} ?>
                            

                            </ul>
                        </li>
                    </ul> -->  
<?php } ?>
                    </ul>
                </li>
            </ul>
    </div>
</nav>
 
 <style>
 .dropdown-submenu {
    position:relative;
}
.dropdown-submenu>.dropdown-menu {
    top:0;
    left:100%;
    margin-top:-6px;
    margin-left:-1px;
    -webkit-border-radius:0 6px 6px 6px;
    -moz-border-radius:0 6px 6px 6px;
    border-radius:0 6px 6px 6px;
}
.dropdown-submenu:hover>.dropdown-menu {
    display:block;
}
.dropdown-submenu>a:after {
    display:block;
    content:" ";
    float:right;
    width:0;
    height:0;
    border-color:transparent;
    border-style:solid;
    border-width:5px 0 5px 5px;
    border-left-color:#cccccc;
    margin-top:5px;
    margin-right:-10px;
}
.dropdown-submenu:hover>a:after {
    border-left-color:#ffffff;
}
.dropdown-submenu.pull-left {
    float:none;
}
.dropdown-submenu.pull-left>.dropdown-menu {
    left:-100%;
    margin-left:10px;
    -webkit-border-radius:6px 0 6px 6px;
    -moz-border-radius:6px 0 6px 6px;
    border-radius:6px 0 6px 6px;
}
 </style>
 
