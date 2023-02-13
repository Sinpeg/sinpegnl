<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

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
	

if(isset($_POST["mudarUnidade"])){
    
    //Receber variáveis
    $unidade = $_POST['cxunidade'];
    $user = $_POST['userID'];
    
    $dao=new UnidadeDAO();
    $rows=$dao->buscarUnidadeByNome($unidade);
    
    foreach ($rows as $r){
        $idUnidade = $r["CodUnidade"];
    }
    
    //Definir Variáveis
    $naplicacoes = 57; //número de formulários ou de aplicações
    $aplicacoes = array();
    $usuario = new Usuario();
    
    $unidadeUsuario = $idUnidade;
    
    for ($i = 1; $i <= $naplicacoes; $i++) {
        $aplicacoes[$i] = false;
    }
    // Verifica se a unidade do usu�rio � unidade ou subunidade
    $rows = $dao->unidadeporcodigo($unidadeUsuario);
    $urespons = 0;
    foreach ($rows as $row) {
        $idunidade = $row["id_unidade"];
        $urespons = $row["unidade_responsavel"]; // unidade responsável
        $codunidadesup = $row["id_unid_resp_org"]; //id_unid_resp_org apresenta a unidade do topo sem n�veis adicionais
    }
    //busca permiss�es do usu�rio
    $row1 = $dao->buscaUnidade($unidadeUsuario);
	var_dump($row1->fetch());
    while ($a = $row1->fetch()) {
        $i = $a['Codaplicacao'];
        $aplicacoes[$i] = true;
        $codestruturado = $a["hierarquia_organizacional"];
        $nomeunidade = $a["NomeUnidade"];
        $grupo[] = $a['Codigo'];
    }
    $usuario->criaUnidade($unidadeUsuario, $unidade, $codestruturado);
    $sessao->setCodUnidade($unidadeUsuario);
    $sessao->setNomeUnidade($unidade);
    $sessao->setCodestruturado($codestruturado);
    $sessao->setAplicacoes($aplicacoes);
	$sessao->setGrupo($grupo); 
    $sessao->setUnidadeResponsavel($urespons); // unidade_responsavel
    $sessao->setCodUnidadeSup($codunidadesup); // id_unid_resp_org
    $sessao->setIdunidade($idunidade); // id da unidade do usuario
}
?>
 <!-- Navbar content -->      
            <li class="nav-item ">
                <a href="#" class="nav-link">
				  <i class="fas fa-circle nav-icon"></i>
				  <p>
					SINPEG
					<i class="right fas fa-angle-left"></i>
				  </p>
				</a>
               <ul class="nav nav-treeview">
                  <!-- O link da opcao de menu é montada com o createlink, "envio de arquivo" 
                    é uma aplicação que deve ser exibido em todas as unidades  -->
                     <?php  
                   if ($sessao->getCodUnidade() == 100000){//unidade
                    ?>  		
						<li class="nav-item">
							<a href="<?php echo Utils::createLink("labor", "relatoriocenso"); ?>" class="nav-link active">
							  <i class="far fa-circle nav-icon"></i>
							  <p>Relatório de laboratórios para o censo</p>
							</a>
						  </li>
                    
                    <?php 
                    }?>
                    
                    <?php if (  $sessao->getCodUnidade() == 100000 ||  $aplicacoes[56]) {    ?>
                        
						<li class="nav-item">
							<a href="<?php echo Utils::createLink("unidade", "incluiUnidade"); ?>" class="nav-link">
							  <i class="far fa-circle nav-icon"></i>
							  <p>Cadastrar unidade</p>
							</a>
						  </li>
						
						
                       <?php } ?>   
                        
                     <?php if (  $sessao->getCodUnidade() == 100000 || $aplicacoes[57]) {    ?>
                        
                        <li class="nav-item">
							<a href="<?php echo Utils::createLink("curso", "incluiCurso"); ?>" class="nav-link">
							  <i class="far fa-circle nav-icon"></i>
							  <p>Cadastrar curso</p>
							</a>
						  </li>
                       
                    <?php } ?>
                    
                    
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
                 
                   //  if ($aplicacoes[2]) { 
                     ?>
                        <!-- <li><a href="<?php //echo Utils::createLink("simec", "consultaacao"); ?>"><span></span> </a>
                        </li> -->
                    <?php //} ?>
                    
                    <?php
                    if ($aplicacoes[23]) {
                        $caminho = Utils::createLink("usuario", "altusuario");
                        ?>                        
						<li class="nav-item">
							<a href="<?php echo Utils::createLink("usuario", "altusuario") ?>" class="nav-link">
							  <i class="far fa-circle nav-icon"></i>
							  <p>Atualizar Dados</p>
							</a>
						  </li>
                    <?php } ?>
                    <?php if ($aplicacoes[3] ) { ?>
                        
						<li class="nav-item">
							<a href="<?php echo Utils::createLink('usuario', 'incusuario'); ?>" class="nav-link">
							  <i class="far fa-circle nav-icon"></i>
							  <p>Cadastrar Usu&aacute;rio</p>
							</a>
						  </li>
                    <?php } ?>
                    <?php if ($aplicacoes[47]) { ?>
                        
						<li class="nav-item">
							<a href="<?php echo Utils::createLink('usuario', 'grupunidade'); ?>" class="nav-link">
							  <i class="far fa-circle nav-icon"></i>
							  <p>Vincular Usu&aacute;rio</p>
							</a>
						  </li>
                    <?php } ?>
                    
                  
                            
                            
                     <?php if ($sessao->getCodusuario()==52) { //colocar para um usuario específico?>                                
								<li class="nav-item">
									<a href="<?php echo Utils::createLink('calendarioPdi', 'listaCalendario'); ?>" class="nav-link">
									  <i class="far fa-circle nav-icon"></i>
									  <p>Definir Calendário</p>
									</a>
								  </li>
                            <?php } ?>
                     
                      <?php if ($sessao->getCategoria()==0) { ?> 
                                
								<li class="nav-item">
									<a href="<?php echo Utils::createLink('usuario', 'selecionarUnidade'); ?>" class="nav-link">
									  <i class="far fa-circle nav-icon"></i>
									  <p>Selecionar Unidade</p>
									</a>
								  </li>
                            <?php } ?>    
                            
                            
                 </ul>           
               
            </li> 
            
        
         <?php if ($aplicacoes[36] ||   $aplicacoes[37] || $aplicacoes[38] || $sessao->getCodUnidade() == 100000) { ?>
        
         
                    <li class="nav-item menu">
						<a href="#" class="nav-link">
						  <i class="nav-icon fas fa-copy"></i>
						  <p>
							PLANEJAR
							<i class="right fas fa-angle-left"></i>
						  </p>
						</a>
					   <ul class="nav nav-treeview">
                             <?php if ($aplicacoes[36]) { ?>                                
								<li class="nav-item">
									<a href="<?php echo Utils::createLink('documentopdi', 'editardocpdi'); ?>" class="nav-link">
									  <i class="far fa-circle nav-icon"></i>
									  <p>Definir Plano</p>
									</a>
								  </li>
                            <?php } ?>
                             <?php if ($sessao->getCodUnidade()==938) { ?> 
                                
								<li class="nav-item">
									<a href="<?php echo Utils::createLink('calendarioPdi', 'listaCalendario'); ?>" class="nav-link">
									  <i class="far fa-circle nav-icon"></i>
									  <p>Definir Calendário</p>
									</a>
								  </li>
                            <?php } ?>
                             <?php if ($aplicacoes[37] ) { ?>
                           
								<li class="nav-item">
									<a href="<?php echo Utils::createLink('mapaestrategico', 'listamapa');?>" class="nav-link">
									  <i class="far fa-circle nav-icon"></i>
									  <p>Elaborar Painel</p>
									</a>
								  </li>
                            <?php } ?>
                            <?php if ($c->getProfile($sessao->getGrupo()) && $sessao->getCodUnidade() != 100000) { //Exibe menu para participantes do grupo 18?>
							   
								<li class="nav-item">
									<a href="<?php echo Utils::createLink('mapaestrategico', 'listamapapdu');?>" class="nav-link">
									  <i class="far fa-circle nav-icon"></i>
									  <p>Exportar Painel <?php echo $sessao->getCodunidade()==938?"Estratégico":"Tático";?></p>
									</a>
								  </li>								
		 					 <?php } ?>
                           
                            
                           
                                <?php if ($aplicacoes[38]) { ?>
                                
								<li class="nav-item">
									<a href="<?php echo Utils::createLink('iniciativa', 'listaIniciativa'); ?>" class="nav-link">
									  <i class="far fa-circle nav-icon"></i>
									  <p>Cadastrar Iniciativa</p>
									</a>
								  </li>
                            <?php } ?>
                          
                          
                           <?php if ($sessao->getCodUnidade() == 100000) { ?>
							    
								<li class="nav-item">
									<a href="relatorio/acompanhamentoPT.php?anoBase=<?php echo $sessao->getAnobase();?>" class="nav-link">
									  <i class="far fa-circle nav-icon"></i>
									  <p>Relatório de Cadastramento do PDU</p>
									</a>
								  </li>
		 					 			 					     
		 					<?php } ?>
		 					
		 					<?php if ($sessao->getCodUnidade() == 100000) { ?>
							    
								<li class="nav-item">
									<a href="<?php echo Utils::createLink('mapaestrategico', 'listaPlanoAcao');?>" class="nav-link">
									  <i class="far fa-circle nav-icon"></i>
									  <p>Plano de Ação</p>
									</a>
								  </li>
		 					 			 					     
		 					<?php } ?>                           
                           
                            
						</ul>
                    </li>
                         
                <?php }?>
         <?php if ($aplicacoes[29] || $aplicacoes[54] ) { ?>
              
                    <li class="nav-item menu">
						<a href="#" class="nav-link">
						  <i class="nav-icon fas fa-tachometer-alt"></i>
						  <p>
							RAA
							<i class="right fas fa-angle-left"></i>
						  </p>
						</a>
					   <ul class="nav nav-treeview">
                        	  <?php 
		                    if ($sessao->getCodUnidade() == 100000){//unidade
		                    ?>		                     
							<li class="nav-item">
									<a href="<?php echo Utils::createLink("raa", "consultarRelatorio"); ?>" class="nav-link">
									  <i class="far fa-circle nav-icon"></i>
									  <p>Consultar RAA</p>
									</a>
								  </li>		                    
							<li class="nav-item">
									<a href="<?php echo Utils::createLink("raa", "consultarTopicos"); ?>" class="nav-link">
									  <i class="far fa-circle nav-icon"></i>
									  <p>Criar Tópico do RAA</p>
									</a>
								  </li>
		                    
							<li class="nav-item">
									<a href="<?php echo Utils::createLink("raa", "consultarModelos"); ?>" class="nav-link">
									  <i class="far fa-circle nav-icon"></i>
									  <p>Criar Modelo para Tópico do RAA</p>
									</a>
								  </li>
		                   
						   <li class="nav-item">
									<a href="relatorio/acompanhamentoRAA.php?anoBase=<?php echo $sessao->getAnobase();?>" class="nav-link">
									  <i class="far fa-circle nav-icon"></i>
									  <p>Relatório de Preenchimento RAA</p>
									</a>
								  </li>
		                    
								<li class="nav-item">
									<a href="<?php echo  Utils::createLink('resultadopdi', 'consultaresult');  ?>" class="nav-link">
									  <i class="far fa-circle nav-icon"></i>
									  <p>Relatório dos Resultados</p>
									</a>
								  </li>						   
		                     <?php 
		                    }                           
                             if ($aplicacoes[29] && $sessao->getCodUnidade() != 100000) { ?>
                                
								<li class="nav-item">
									<a href="<?php echo  Utils::createLink('resultadopdi', 'consultaresult');  ?>" class="nav-link">
									  <i class="far fa-circle nav-icon"></i>
									  <p>Lançar Resultados</p>
									</a>
								  </li>	
                            <?php } 
                               if ($aplicacoes[54]) { ?>
							
							<li class="nav-item">
									<a href="<?php echo Utils::createLink("raa", "listaTexto"); ?>" class="nav-link">
									  <i class="far fa-circle nav-icon"></i>
									  <p>Redigir Texto do RAA</p>
									</a>
								  </li>	
							
							<?php  if ($sessao->getAnobase()>=2019){?>							  
							  <li class="nav-item">
									<a href="<?php echo Utils::createLink("raa", "avalraapdu"); ?>" class="nav-link">
									  <i class="far fa-circle nav-icon"></i>
									  <p>Exportar Análise do RAA</p>
									</a>
								  </li>	
							  <?php } ?>
							
							
							<?php }?>
                          
                         
						</ul>
                    </li>
					<li class="nav-item menu">
						<a href="#" class="nav-link">
						  <i class="nav-icon fas fa-th"></i>
						  <p>
							PROJETO <span class="left badge badge-danger">New</span>
							<i class="right fas fa-angle-left"></i>
						  </p>
						</a>
					   <ul class="nav nav-treeview">
                        	
		                                               
                             
                                
								<li class="nav-item">
									<a href="<?php echo  Utils::createLink('projetos', 'dashboard');  ?>" class="nav-link">
									  <i class="far fa-circle nav-icon"></i>
									  <p>Dashboard</p>
									</a>
								  </li>	
                           
                               
							
							<li class="nav-item">
									<a href="<?php echo Utils::createLink("projetos", "projetos"); ?>" class="nav-link">
									  <i class="far fa-circle nav-icon"></i>
									  <p>Projetos</p>
									</a>
								  </li>	
							
													  
							  <li class="nav-item">
									<a href="<?php echo Utils::createLink("projetos", "kanban"); ?>" class="nav-link">
									  <i class="far fa-circle nav-icon"></i>
									  <p>Kanban</p>
									</a>
								  </li>	
								  
							  <li class="nav-item">
									<a href="<?php echo Utils::createLink("projetos", "calendario"); ?>" class="nav-link">
									  <i class="far fa-circle nav-icon"></i>
									  <p>Calendário</p>
									</a>
								  </li>	
							
							
						
                          
                         
						</ul>
                    </li>
                              
        
        <?php } ?>

        <?php if ($aplicacoes[6] ||  $aplicacoes[7] || $aplicacoes[8] || $aplicacoes[9] || $aplicacoes[32] ||
                   $aplicacoes[33] || $aplicacoes[34] || $aplicacoes[10] || $aplicacoes[11] || $aplicacoes[35] ) { ?>
            
						<li class="nav-item menu">
						<a href="#" class="nav-link">
						  <i class="nav-icon fas fa-edit"></i>
						  <p>
							CENSO
							<i class="right fas fa-angle-left"></i>
						  </p>
						</a>
					   <ul class="nav nav-treeview">								
								<?php if ($aplicacoes[6]) { ?>									
									<li class="nav-item">
										<a href="<?php echo Utils::createLink('micros', 'consultamicros'); ?>" class="nav-link">
										  <i class="far fa-circle nav-icon"></i>
										  <p>Computadores</p>
										</a>
									</li>	
								<?php } ?>
								<?php if ($aplicacoes[7]) { ?>
									
									<li class="nav-item">
										<a href="<?php echo Utils::createLink('laborv3', 'consultalab'); ?>" class="nav-link">
										  <i class="far fa-circle nav-icon"></i>
										  <p>Laborat&oacute;rios</p>
										</a>
									</li>			
								<?php } ?> 
								<?php if ($aplicacoes[8]) { ?>
																			
									<li class="nav-item">
										<a href="<?php echo Utils::createLink('infra', 'consultainfra'); ?>" class="nav-link">
										  <i class="far fa-circle nav-icon"></i>
										  <p>Infraestrutura</p>
										</a>
									</li>	
								<?php } ?>
								
								<?php if ($aplicacoes[51])  { ?>									
									<li class="nav-item">
										<a href="<?php echo Utils::createLink('epolo', 'consultapolo'); ?>" class="nav-link">
										  <i class="far fa-circle nav-icon"></i>
										  <p>Tecnologias do Polo</p>
										</a>
									</li>	
								<?php } ?>
								
								<?php if ($aplicacoes[10]) { ?>									
									<li class="nav-item">
										<a href="<?php echo Utils::createLink('acessib', 'consultaacess'); ?>" class="nav-link">
										  <i class="far fa-circle nav-icon"></i>
										  <p>Estrutura de Acessilidade</p>
										</a>
									</li>
								<?php } ?>
								<?php if ($aplicacoes[11])  { ?>
									
									<li class="nav-item">
										<a href="<?php echo Utils::createLink('tecnol', 'cursounidades'); ?>" class="nav-link">
										  <i class="far fa-circle nav-icon"></i>
										  <p>Tecnologia Assistiva</p>
										</a>
									</li>
								<?php } ?>
								<?php if ($aplicacoes[35]) { ?>									
									<li class="nav-item">
										<a href="<?php echo Utils::createLink('acessib', 'buscaacess'); ?>" class="nav-link">
										  <i class="far fa-circle nav-icon"></i>
										  <p>Relatório da Estrutura de Acessibilidade</p>
										</a>
									</li>	
								<?php } ?> 
								
								
								<?php if ($aplicacoes[32]) { ?>
									
									<li class="nav-item">
										<a href="<?php echo Utils::createLink('labor', 'buscalab'); ?>" class="nav-link">
										  <i class="far fa-circle nav-icon"></i>
										  <p>Relatório dos Laboratórios</p>
										</a>
									</li>										
								<?php } ?>
								<?php if ($aplicacoes[33]) { ?>
									
									<li class="nav-item">
										<a href="<?php echo Utils::createLink('infra', 'consadmin'); ?>" class="nav-link">
										  <i class="far fa-circle nav-icon"></i>
										  <p>Relatório da Infraestrutura</p>
										</a>
									</li>										
								<?php } ?> 
								<?php if ($aplicacoes[34]) { ?>									
										
										<li class="nav-item">
											<a href="<?php echo Utils::createLink('infraensino', 'buscainfraensino'); ?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Relatório da Infraestrutura de Ensino</p>
											</a>
										</li>
										<?php } ?>
								<?php if ($sessao->getCodUnidade() == 100000) { ?>									
										<li class="nav-item">
											<a href="relatorio/micros/exportmicros.php?anoBase=<?php echo $sessao->getAnobase();?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Relatório de Micros</p>
											</a>
										</li>
								<?php } ?>
								 <?php if ($sessao->getCodUnidade() == 100000) { ?>
									
									<li class="nav-item">
											<a href="relatorio/relatorio_epolo.php?anoBase=<?php echo $sessao->getAnobase();?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Relatório Tecnologias do Polo</p>
											</a>
									</li>	
								<?php } ?>
								<?php //if ($aplicacoes[45]) { 
							if ($sessao->getCodUnidade() == 100000) {
							?>
									
										<li class="nav-item">
											<a href="<?php echo Utils::createLink('biblio', 'buscabiblio'); ?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Relatório de Bibliotecas</p>
											</a>
									</li>
								<?php } ?> 
								  <?php if ($sessao->getCodUnidade() == 100000) { ?>
																				
										<li class="nav-item">
											<a href="relatorio/relatorioUtilizacao.php?anoBase=<?php echo $sessao->getAnobase();?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Relatório de Preenchimento</p>
											</a>
									</li>
											
									<?php } ?>                      
                 </ul>
                </li>
            
        <?php } ?>
      
        <?php if ($aplicacoes[5] ||$aplicacoes[15] || $aplicacoes[16] ||  $aplicacoes[13] || $aplicacoes[17] || $aplicacoes[14] || $aplicacoes[30] || $aplicacoes[28] || $aplicacoes[26] || $sessao->getCodUnidade() == 100000) { ?>
            
                       
                <li class="nav-item menu">
						<a href="#" class="nav-link">
						  <i class="nav-icon fas fa-table"></i>
						  <p>
							ANUÁRIO
							<i class="right fas fa-angle-left"></i>
						  </p>
						</a>
					   <ul class="nav nav-treeview">
               
                    
                    
                    <?php if ($aplicacoes[4] && $sessao->getUnidadeResponsavel() == 1) { ?>
                            
							<li class="nav-item">
											<a href="<?php echo Utils::createLink('prodintelectual', 'consultaprodintelectual'); ?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Produ&ccedil;&atilde;o Intelectual</p>
											</a>
									</li>
                        <?php } ?>                          
							<li class="nav-item">
											<a href="relatorio/relatorio_producao.php?anoBase=<?php echo $sessao->getAnobase();?>&codUnidade=<?php echo $sessao->getCodUnidade();?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Relatório de Produção Intelectual</p>
											</a>
									</li>		
                            
                             <?php if ($aplicacoes[5]) { ?>
                            
							<li class="nav-item">
											<a href="<?php echo Utils::createLink('premios', 'consultapremios'); ?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Pr&ecirc;mios</p>
											</a>
									</li>
                        <?php } ?>
                          
							<li class="nav-item">
											<a href="relatorio/relatorio_premios2.php?anoBase=<?php echo $sessao->getAnobase();?>&codUnidade=<?php echo $sessao->getCodUnidade();?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Relatório de Pr&ecirc;mios</p>
											</a>
									</li>
                         <?php if ($aplicacoes[30]) { ?>
                                <!--  <li><a href="<?php     // echo Utils::createLink('incubadora', 'consultaincub'); ?>" ><span>Incubadora</span>
                                    </a></li>--> 
                            <?php } ?> 
                            
                           
                        
                            
                        <?php if ($aplicacoes[13]) { ?>
                            
								<li class="nav-item">
											<a href="<?php echo Utils::createLink('praticajuridica', 'consultapjuridica'); ?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Pr&aacute;ticas Jur&iacute;dicas</p>
											</a>
									</li>
                        <?php } ?>
                    
                            
                            
                  
                        <?php if ($aplicacoes[14]) { ?>
                            
							<li class="nav-item">
											<a href="<?php echo Utils::createLink('prodsaude4', 'incluipsaude5'); ?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Produ&ccedil;&atilde;o da Área de Sa&uacute;de</p>
											</a>
									</li>
                        <?php } ?>
                        <?php if ($aplicacoes[15]) { ?>
                           
							<li class="nav-item">
											<a href="<?php echo Utils::createLink('prodsaude', 'consultapsaude'); ?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Patologia Tropical e Dermatologia</p>
											</a>
									</li>
                        <?php } ?>
                        <?php if ($aplicacoes[16]) { ?>
                           
							<li class="nav-item">
											<a href="<?php echo Utils::createLink('produto', 'consultapfarma'); ?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Produ&ccedil;&atilde;o da Farm&aacute;cia</p>
											</a>
									</li>
                        <?php } ?>
                        <?php if ($aplicacoes[17]) { ?>
                            
							<li class="nav-item">
											<a href="<?php echo Utils::createLink('freq', 'consultafreq'); ?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Frequentadores da Farm&aacute;cia</p>
											</a>
									</li>
                        <?php } ?>
                        
                         <?php if ($aplicacoes[22]) { ?>
                           
							<li class="nav-item">
											<a href="<?php echo Utils::createLink('ensinofund', 'consultaensino'); ?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Quantitativo do Ensino Fundamental</p>
											</a>
									</li>
                        <?php } ?>
                        <?php if ($aplicacoes[21]) { ?>
                            
							<li class="nav-item">
											<a href="<?php echo Utils::createLink('ensinomedio', 'consultaemedio'); ?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Quantitativo do Ensino M&eacute;dio</p>
											</a>
									</li>
                        <?php } ?>
                        <?php if ($aplicacoes[20]) { ?>
                            
							<li class="nav-item">
											<a href="<?php echo Utils::createLink('eaprojpesquisa', 'consultapesquisa'); ?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Projetos de Pesquisa</p>
											</a>
									</li>
                        <?php } ?>
                        <?php if ($aplicacoes[25]) { ?>
                            
							<li class="nav-item">
											<a href="<?php echo Utils::createLink('eaprojextensao', 'consultaextensao'); ?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Projetos de Extens&atilde;o</p>
											</a>
									</li>
                        <?php } ?>
                        <?php if ($aplicacoes[28]) { ?>
                            
							<li class="nav-item">
											<a href="<?php echo Utils::createLink('eapimetodologicas', 'consultapimetodologicas'); ?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Pr&aacute;tica de Interven&ccedil;&otilde;es Metodol&oacute;gicas</p>
											</a>
									</li>
                        <?php } ?>
                          <?php if ($aplicacoes[18]) { ?>
                           
							<li class="nav-item">
											<a href="<?php echo Utils::createLink('prodartistica', 'consultaprodartistica'); ?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Produ&ccedil;&atilde;o Art&iacute;stica</p>
											</a>
									</li>
                        <?php } ?>
                        <?php if ($aplicacoes[24]) { ?>
                            
							<li class="nav-item">
											<a href="<?php echo Utils::createLink('edprofrh', 'consultarh'); ?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Quadro de Pessoal</p>
											</a>
									</li>
                        <?php } ?>
                        <?php if ($aplicacoes[19]) { ?>
                            
							<li class="nav-item">
											<a href="<?php echo Utils::createLink('atividades', 'consultaatividadeextensao'); ?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Atividades de Extens&atilde;o</p>
											</a>
									</li>
                        <?php } ?>
                        <?php if ($aplicacoes[26]) { ?>
                            
							<li class="nav-item">
											<a href="<?php echo Utils::createLink('cledprofissional', 'conscleducprof'); ?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Ed. Profissional e Cursos Livres</p>
											</a>
									</li>
                        <?php } ?>
                          <?php  if ($sessao->getCodUnidade() == 100000){//unidade  ?>
                             
							 <li class="nav-item">
											<a href="relatorio/relatorioAnuarioCursosLivres.php?anoBase=<?php echo $sessao->getAnobase();?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Relatório de Educação Profissional e Cursos Livres</p>
											</a>
									</li>
                             
                           
							<li class="nav-item">
											<a href="relatorio/relatorioPraticaJuridica.php?anoBase=<?php echo $sessao->getAnobase();?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Relatório de Prática Jurídica</p>
											</a>
									</li>
                             
                             							 
                         <?php }?>                         
                        
					</ul>
                </li>
            
        <?php } ?>
        
        <!-- Menu para exportar a produção da área da saúde no formato do anuário -->
        <?php if ($sessao->getCodUnidade() == 100000) { ?>
           
                <li class="nav-item menu">
						<a href="#" class="nav-link">
						  <i class="nav-icon fas fa-tachometer-alt"></i>
						  <p>
							SAÚDE
							<i class="right fas fa-angle-left"></i>
						  </p>
						</a>
					   <ul class="nav nav-treeview">
                   
                      
						<li class="nav-item">
											<a href="relatorio/prodSaudeAnuario.php?anoBase=<?php echo $sessao->getAnobase();?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Produ&ccedil;&atilde;o da &Aacute;rea de Sa&uacute;de - Anuário</p>
											</a>
									</li>
                  </ul>
                </li>
         
        <?php } ?>  
           
           
            <?php // if ($aplicacoes[43] || $aplicacoes[44]) { ?> 
               
                    <li class="nav-item menu">
						<a href="#" class="nav-link">
						  <i class="nav-icon fas fa-chart-pie"></i>
						  <p>
							AVALIAR
							<i class="right fas fa-angle-left"></i>
						  </p>
						</a>
					   <ul class="nav nav-treeview">
                         
                            <?php if ($aplicacoes[43]) { ?>
                               
								<li class="nav-item">
											<a href="<?php echo Utils::createLink('homologar', 'listaplicacao'); ?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Homologar</p>
											</a>
									</li>	
                            <?php } ?>
                            <?php if ($aplicacoes[44]): ?>
                                
									<li class="nav-item">
											<a href="<?php echo Utils::createLink('homologar', 'reversao'); ?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Reverter homologação</p>
											</a>
									</li>									
                                <?php endif; ?>
                                
                                 
							<li class="nav-item">
											<a href="<?php echo Utils::createLink('painelmedicao', 'painelmed'); ?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Painel de Medições</p>
											</a>
									</li>
                            
							<li class="nav-item">
											<a href="<?php echo Utils::createLink('painelmedicao', 'dashboard'); ?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Análises do PDU/PDI</p>
											</a>
									</li>
                            <?php if ($sessao->getCodUnidade() == 100000) { ?>
							 
								<li class="nav-item">
											<a href="relatorio/relatorioAvaliacao.php?anoBase=<?php echo $sessao->getAnobase();?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Relatório de Gestão do Plano</p>
											</a>
									</li>
		                        
								<li class="nav-item">
											<a href="relatorio/relatorioAvaliacaoEsforco.php?anoBase=<?php echo $sessao->getAnobase();?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Relatório de Avaliação de Indicadores de Esforço (PDU)</p>
											</a>
									</li>
		 					 <?php } ?>
                            

                           
                            
                         </ul>   
                        </li>
                        
                        
                 
                <?php //} ?>
               

          <?php
          if (array_key_exists("55",$aplicacoes)){
          		if ($aplicacoes[55] || $aplicacoes[44]) { ?> 
               
                    <li class="nav-item menu">
						<a href="#" class="nav-link">
						  <i class="nav-icon far fa-plus-square"></i>
						  <p>
							DIRECIONAR AÇÕES
							<i class="right fas fa-angle-left"></i>
						  </p>
						</a>
					   <ul class="nav nav-treeview">
                            
                            <?php //if ($aplicacoes[44]): ?>
                               <!-- <li><a href="<?php // echo Utils::createLink('homologar', 'reversao'); ?>" ><span>Reverter homologação</span>
                                    </a></li>-->    
                                <?php // endif; ?>

                            <?php if ($aplicacoes[55] ) { ?>
                          	
									<li class="nav-item">
											<a href="<?php echo Utils::createLink('indicadorpdi', 'quadrosolicitacoes');?>" class="nav-link">
											  <i class="far fa-circle nav-icon"></i>
											  <p>Quadro de Solicitações</p>
											</a>
									</li>							
                            <?php } ?>
                            
                             
                            
                            
                            
                        </ul>  
                        </li>
                     
                <?php } 
				}
                ?>              
                <li class="nav-item">
					<a href="logout.php" class="nav-link">
					  <i class="nav-icon far fa-circle text-danger"></i>
					  <p class="text">Sair</p>
					</a>
				  </li>
                
                  
              