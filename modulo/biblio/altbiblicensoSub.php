<?php
ob_start();

//session_start(); - Sessão já inicializada

$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();

if(isset($_GET['biblioteca'])){
	//Caso o uniário não seja uma Biblioteca
	$codunidade = $_GET['biblioteca'];
	$block_campos = 'disabled="disabled"';	
}else{
	//Caso o usuário seja uma Biblioteca
	$codunidade=$sessao->getCodunidade();
	$block_campos = "";
}

$anobase = $sessao->getAnobase();

if (!$aplicacoes[45]) {//acesso à aplicacao
	header("Location:index.php");
    exit();
} else {
	require_once('classes/bibliemec.php');
	require_once('classes/cVetForm.php');
	require_once('fachBEmec.php');
	require_once('classes/biblicenso.php');
	require_once('fachBCenso.php');
	require_once('dao/blofertaDAO.php');
	require_once('dao/localofertaDAO.php');
	require_once('classes/localoferta.php');
	require_once('classes/Bloferta.php');
	
	$b = array(); // tipo da estrutura de acessibilidade
	$daobo = new BlofertaDAO();
	$daolo = new LocalofertaDAO();
    $classe = new cVetForm();
    $legendas= $classe->legendasForm();
    $fachada1= new FachBEmec();

    $be = $fachada1->retornaBEmec($codunidade);
	
     $idBibliemec = $be->getIdBibliemec();  
	// tipo de biblioteca
	$tipo="";$imprime=0;
	
 	if ($be->getTipo()==1){	
		$tipo="Central";
	}else{ 
		$tipo="Setorial";
	}

	$a=array();
    for ($i =1; $i <=16; $i++) {
    	   $a[$i]=0;
    }

	$fachada2=new FachBCenso();
	$be=$fachada2->retBibliocenso($be, $anobase);

	//busca lista de locais de oferta
	$rows = $daolo->Lista1();	
	$cont=0;
	foreach ($rows as $row) {
		$cont++;
		$b[$cont] = new Localoferta();
		$b[$cont]->setIdLocal($row['idLocal']);
		$b[$cont]->setNome($row['nome']);

	}
	
	//Vê se local de oferta já foi selecionado
	$tamanho=$cont;
	if (!is_null($be->getBiblicenso())){
	  $rows = $daobo->buscaporIdbibli($be->getIdBibliemec());//alterar na tabela bloferta para idbibliemec
	  foreach ($rows as $row){
			for ($i=1;$i<=$cont;$i++){
				if ($b[$i]->getIdLocal()==$row['idloferta']){
					$b[$i]->criaBloferta($be);
				}
			}//for
		}//for
	}

	if (is_null($be->getBiblicenso())){
		$operacao='I';
    }else {
		$operacao='A';

		//$lock = new Lock(); // trecho de bloqueio

		//Aspectos apresentados pela biblioteca

		$a[1]=$be->getBiblicenso()->getFbuscaIntegrada();
		$a[2]=$be->getBiblicenso()->getComutBibliog();
		$a[3]=$be->getBiblicenso()->getServInternet();
		$a[4]=$be->getBiblicenso()->getRedeSemFio();
		$a[5]=$be->getBiblicenso()->getPartRedeSociais();
		$a[6]=$be->getBiblicenso()->getAtendTreiLibras();
		//acessibilidade de conteudo
		$a[7]=$be->getBiblicenso()->getAcervoFormEspecial();
		$a[8]=$be->getBiblicenso()->getAppFormEspecial();
		$a[9]=$be->getBiblicenso()->getPlanoFormEspecial();

		//acessibilidade tecnologica
		$a[10]=$be->getBiblicenso()->getSofLeitura();
		$a[11]=$be->getBiblicenso()->getImpBraile();
		$a[12]=$be->getBiblicenso()->getTecVirtual();

		//infos adicionais
		$a[13]=$be->getBiblicenso()->getPortalCapes();
		$a[14]=$be->getBiblicenso()->getOutrasBases();
		$a[15]=$be->getBiblicenso()->getBdOnlineSerPub();
		$a[16]=$be->getBiblicenso()->getCatOnlineSerPub();
	}

//	$lock->setLocked(Utils::isApproved(10, $cpga, $codunidade, $anobase));
}
?>

<head>
	<style>
		a.help {
			text-decoration: none;
			color: #000;
			font-size: 0.2in
		}

		a.help:hover {
			color: #555;
		}

		div.crow {
			clear: both;
			padding-top: 5px;
			font: 15px arial, sans-serif;

		}

		div.crow span.clabel {
			float: left;
			width: 150px;
			text-align: left;
			}

		div.crow span.cinput class="form-control"{
			float: left;
			width: 465px;
			text-align: left;
		}

		tabs-nohdr {
			padding: 0px;
			background: none;
			border-width: 0px;
		}

		tabs-nohdr .ui-tabs-nav {
			padding-left: 0px;
			background: transparent;
			border-width: 0px 0px 1px 0px;
			-moz-border-radius: 0px;
			-webkit-border-radius: 0px;
			border-radius: 0px;
		}

		tabs-nohdr .ui-tabs-panel {
			border-width: 0px 1px 1px 1px;

		}
	</style>

	<!--<link rel="stylesheet"
		href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>  // comentado em 16/01/2023 para evitar redundância--> 

	<script type="text/javascript" src="webroot/js/jquery-ui-1.10.3/js/jquery-1.9.1.js"></script>
	<script type="text/javascript" src="webroot/js/jquery-ui-1.10.3/js/jquery-ui-1.10.3.custom.js"></script>

	<!-- Bootstrap 4 -->
	<script src="novo_layout/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="novo_layout/dist/js/adminlte.min.js"></script>
	<!-- AdminLTE for demo purposes <script src="novo_layout/dist/js/demo.js"></script>-->	<script type="text/javascript" src="webroot/js/jquery-ui-1.10.3/js/jquery-1.9.1.js"></script>
	<script type="text/javascript" src="webroot/js/jquery-ui-1.10.3/js/jquery-ui-1.10.3.custom.js"></script>

	<script>
		function valida() {
			if ( (document.bib.nass.value == "") ||
			(document.bib.empd.value == "") ||
			(document.bib.empb.value == "") ||
			(document.bib.freq.value == "") ||
			(document.bib.pcap.value == "") ||
			(document.bib.consp.value == "") ||
			(document.bib.iai.value == "") ||
			(document.bib.iaeletr.value == "")||
			(document.bib.consl.value == ""))
			{
				document.getElementById('msg').innerHTML = "Os campos da guia Quantitativo s&atilde;o obrigat&oacute;rios.";
				return false;
			}else {
				if (document.bib.consp.value =="0"){
					document.getElementById('msg').innerHTML = "Consulta presencial (guia Quantitativo) deve ser maior ou igual a 1.";
					return false;
				}
				if (document.bib.empd.value =="0"){
					document.getElementById('msg').innerHTML = "Empr&eacute;stimos domiciliares (guia Quantitativo) deve ser maior ou igual a 1.";
					return false;
				}
				if (document.bib.freq.value =="0"){
					document.getElementById('msg').innerHTML = "Frequ&ecirc;ncia (guia Quantitativo) deve ser maior ou igual a 1.";
					return false;
				}
				if (parseInt(document.bib.empd.value) >parseInt(document.bib.consp.value) ){//falta
					document.getElementById('msg').innerHTML = "N&uacute;mero de empr&eacute;stimos domiciliares n&atilde;o deve ser maior que consultas presenciais (guia Quantitativo).";
					return false;
				}
				if (parseInt(document.bib.empd.value) >parseInt(document.bib.freq.value) ){//falta
							document.getElementById('msg').innerHTML = "N&uacute;mero de empr&eacute;stimos domiciliares n&atilde;o deve ser maior que frequ&ecirc;ncia (guia Quantitativo).";
							return false;
				}
			}
			return true;
		}

		function direciona(botao) {
			if (botao == 1) {
				if (valida()) {

					document.bib.action = "?modulo=biblio&acao=opBibcenso";
					document.bib.submit();
				}
			}
			else {
				document.bib.action = "../saida/saida.php";
				document.bib.submit();
			}
		}

		function er_replace( pattern, replacement, subject ){
			return subject.replace( pattern, replacement );
		}

		$(document).ready(function(){
				$('input:text:first').focus();

				$(":text").keyup(function() {
					var $this = $( this );
					$this.val( er_replace( /[^0-9]+/g,'', $this.val() ) );
				});

				$(function() {
						$( "#tabs" ).tabs();
				});
		});
	</script>
</head>

<div class="card card-info">
	<form class="form-horizontal" name="bib" id="bib" method="POST">
		<div class="crow">
			<span>
				<b>Unidade:</b> <?php echo $codunidade;?>
			</span>
		</div>
		<fieldset>
			<legend></legend>
			<div class="crow">
				<span class="clabel"><b>C&oacute;digo Emec</b></span> <span class="cinput">
				    <?php echo $be->getCodEmec(); ?>
					<input class="form-control"type="hidden" name="idbiblicenso"
					value="<?php  print ($operacao=='A')? $be->getBiblicenso()->getIdBiblicenso():""  ?>" />
					<input class="form-control"type="hidden" name="idBibliemec"
					value="<?php  print  $be->getIdBibliemec();  ?>" />
				</span>
			</div>
			<div class="crow">
				<span class="clabel"><b>Tipo da Biblioteca</b></span> <span class="cinput">
					<?php print  trim($tipo) ;  ?>
				</span>
			</div>
			<div class="crow">
				<span class="clabel"><b>Nome</b></span> <span class="cinput">
				<?php  print $be->getNome(); ?> </span>
			</div>
			</br>
			</br>
			<div id="msg" class="msg"></div>
			<div id="tabs">
				<ul>
					<li><a href="#tabs-1">Quantitativo</a></li>
					<li><a href="#tabs-2">Servi&ccedil;os</a></li>
					<li><a href="#tabs-3">Acessibilidade</a></li>
					<li><a href="#tabs-4">Outras</a></li>
					<li><a href="#tabs-5">Locais de oferta</a></li>
				</ul>

				<div id="tabs-1">
					<table>
						<!-- <div class="crow"> -->
						<tr>
							<td>
								<span class="clabel">Assentos <a href="#" class="help" data-trigger="hover" data-content="XXX" title="Espaços disponibilizados aos usuários para leitura e estudo, com ou sem mesas ou equipamentos. Incluem assentos em cabines individuais, salas de estudo em grupo, de multimídia e de setores infantis. Não devem ser contados os assentos de auditórios e espaços informais sem mobiliário"><span class="glyphicon glyphicon-question-sign"> </span></a></span>
							</td>
							<td>				
								<span class="cinput"><input
									<?php echo $block_campos;?> type="text" size=6
									name="nass" maxlength="6"
									value="<?php print ($operacao=='A')? $be->getBiblicenso()->getNassentos() : "" ?>" /></span>
							</td>
						</tr>		
						<!-- </div> -->

						<!-- <div class="crow"> -->
						<tr>
							<td>	
								<span class="clabel">Empr&eacute;stimos domiciliares <a href="#" class="help" data-trigger="hover" data-content="XXX" title="Cessão dos itens do acervo da biblioteca para serem utilizados fora da instituição pelo usuário."><span class="glyphicon glyphicon-question-sign"> </span></a></span> 
							</td>
							<td>
								<span
									class="cinput"><input class="form-control"<?php echo $block_campos;?> size=6
									type="text" name="empd" maxlength="6"
									value="<?php print ($operacao=="A")? $be->getBiblicenso()->getNEmpdomicilio() : "" ?>" /></span>
							</td>
						</tr>
						<!-- </div> -->
						<!-- <div class="crow"> -->
						<tr>
							<td>	
								<span class="clabel">Empr&eacute;stimos entre Bibliotecas <a href="#" class="help" data-trigger="hover" data-content="XXX" title="Modalidade de cessão de itens do acervo efetuada entre bibliotecas, baseada em acordos mútuos. Considerar os empréstimos emitidos e recebidos pela biblioteca até a data de referência do Censo."><span class="glyphicon glyphicon-question-sign"> </span></a></span> 
							</td>
							<td> 
								<input
								<?php echo $block_campos;?> type="text" name="empb"
								size=6 maxlength="6" onkeypress="return SomenteNumero(event);"
								value="<?php print ($operacao=="A")? $be->getBiblicenso()->getNEmpbiblio() : "" ?>" />
							</td>
						</tr>
						<!-- </div> -->
						<!-- <div class="crow"> -->
						<tr>
							<td>
								<span class="clabel">Frequ&ecirc;ncia <a href="#" class="help" data-trigger="hover" data-content="XXX" title="N&uacute;mero de visitas feitas por pessoas &agrave;s instala&ccedil;&otilde;es da biblioteca por ano."><span class="glyphicon glyphicon-question-sign"> </span></a></span><span class="cinput">
							</td>
						
							<td>
								<input
								<?php echo $block_campos;?> size=6 type="text"
								name="freq" maxlength="6"
								value="<?php print ($operacao=="A")? $be->getBiblicenso()->getFrequencia() : "" ?> " /></span>
							</td>
						</tr>
						<!-- </div> -->
						<!-- <div class="crow"> -->
						<tr>
							<td>
								<span class="clabel">Consulta presencial<a href="#" class="help" data-trigger="hover" data-content="XXX" title="Itens do acervo retirados das estantes e consultados livremente."><span class="glyphicon glyphicon-question-sign"> </span></a></span> 
							</td>
							<td>
								<span
									class="cinput"><input class="form-control"<?php echo $block_campos;?> size=6
									type="text" name="consp" maxlength="6"
									value="<?php print ($operacao=="A")? $be->getBiblicenso()->getNconsPresencial(): "" ?>" />
								</span>
							</td>
						</tr>
						<!-- </div> -->
						<!-- <div class="crow"> -->
						<tr>
							<td>	
								<span class="clabel">Consulta online <a href="#" class="help" data-trigger="hover" data-content="XXX" title="Acesso ao catálogo bibliográfico online para consulta via Internet."><span class="glyphicon glyphicon-question-sign"> </span></a></span> </td><td><span class="cinput"><input
								<?php echo $block_campos;?> size=6 type="text"
								name="consl" maxlength="6"
								value="<?php print ($operacao=="A")? $be->getBiblicenso()->getNconsOnline() : "" ?>" /></span>
							</td>
						</tr>
						<!-- </div> -->
						<!-- <div class="crow"> -->
						<tr>
							<td>
								<span class="clabel">Usu&aacute;rios treinados em programas de
								capacita&ccedil;&atilde;o <a href="#" class="help" data-trigger="hover" data-content="XXX" title="Usuários submetidos a treinamento orientado para o uso das tecnologias disponíveis na biblioteca através de treinamentos especializados. "><span class="glyphicon glyphicon-question-sign"> </span></a></span></td><td> <span class="cinput"><input
								<?php echo $block_campos;?> size=6 type="text"
								name="pcap" maxlength="6"
								value="<?php print ($operacao=="A")? $be->getBiblicenso()->getNusuariosTpc() : "" ?>" /></span>
							</td>
						</tr>
						<!-- </div> -->
						<!-- <div class="crow"> -->
						<tr>
							<td>
								<span class="clabel">Itens do acervo impresso <a href="#" class="help" data-trigger="hover" data-content="XXX" title="Volumes de materiais impressos, tais como livros, periódicos (fascículos), seriados, teses, dissertações, mapas, plantas arquitetônicas, partituras musicais, manuscritos, dentre outros"><span class="glyphicon glyphicon-question-sign"> </span></a></span></td><td> <span
								class="cinput"><input class="form-control"<?php echo $block_campos;?> size=6
								type="text" name="iai" maxlength="6"
								value="<?php print ($operacao=="A")?$be->getBiblicenso()->getNitensAcervoImp() : "" ?>" /></span>
							</td>
						</tr>
						<!-- </div> -->
						<!-- <div class="crow"> -->
						<tr>
							<td>
								<span class="clabel">Itens do acervo eletr&ocirc;nico <a href="#" class="help" data-trigger="hover" data-content="XXX" title="Todo e qualquer material cujo conteúdo é disponibilizado em formato eletrônico, tais como: livros e periódicos eletrônicos, e bases de dados e periódicos do portal Capes."><span class="glyphicon glyphicon-question-sign"> </span></a></span></td><td><span
								class="cinput"><input class="form-control"<?php echo $block_campos;?> size=6
								type="text" name="iaeletr" maxlength="6"
								value="<?php print ($operacao=="A")?$be->getBiblicenso()->getNitensAcervoElet() : "" ?>" /></span>
							</td>
						</tr>
						<!-- </div> -->				
					</table>	
				</div>
				</br>
				<div id="tabs-2">
					<div class="crow">
						<fieldset>
							<legend>Servi&ccedil;os oferecidos pela biblioteca</legend>
							<table>
								<?php  $cont=0;
								for ($i = 1; $i <= 6; $i++) {
									$cont++; ?>
									<!-- <div class="crow"> -->
									<tr>
										<td>
											<input class="form-check-input"<?php echo $block_campos;?> type="checkbox"
												<?php print ($a[$i]==1) ? "checked" : "" ?>
												name="aab[<?php echo $cont ?>]" />  <?php echo $legendas[$i];?>
										</td>
									</tr>
									<!-- </div> -->
								<?php } ?>
							</table>
						</fieldset>
					</div>
				</div>
				<br>
				<div id="tabs-3">
					<div class="crow">
						<fieldset>
							<legend>Conte&uacute;do</legend>
							<table>
								<?php
								$cont=0;
								for ($i = 7; $i <= 9; $i++) {
									$cont++; ?>
									<!-- <div class="crow"> -->
									<tr>
										<td>
											<input type="checkbox" class="form-check-input" <?php echo $block_campos;?>
											<?php print ($a[$i]==1) ? "checked" : "" ?>
												name="laccont[<?php  echo $cont; ?>]" /> <?php echo $legendas[$i];?>
										</td>
									</tr>
									<!-- </div> -->
								<?php } ?>
							</table>
						</fieldset>
					</div>
					<br>
					<div class="crow">
						<fieldset>
							<legend>Tecnol&oacute;gica</legend>
							<table>
								<?php
								$cont=0;
								for ($i =10; $i <=12; $i++) {
									$cont++; ?>
										<!-- <div class="crow"> -->
									<tr><td>
										<span>
											<input type="checkbox" class="form-check-input" <?php echo $block_campos;?>
												<?php print ($a[$i]==1) ? "checked" : "" ?>
												name="lactecn[<?php  echo $cont; ?>]" /> <?php echo $legendas[$i];?></span>
										</tr></td>
								<!-- </div> -->
								<?php } ?>
							</table>
						</fieldset>
					</div>
				</div>
				<div id="tabs-4">
					<div class="crow">
						<fieldset>
							<legend>Informa&ccedil;&otilde;es adicionais</legend>
							<table>
								<?php
								$cont=0;
								for ($i =13; $i <=16; $i++) {
									$cont++;
								?>
								<!-- <div class="crow"> -->
								<tr><td>
										<input type="checkbox" class="form-check-input" <?php echo $block_campos;?>
											<?php print ($a[$i]==1) ? "checked" : "" ?>
											name="adic[<?php echo $cont; ?>]" /> <?php echo $legendas[$i];?>
								</tr></td>
								<!-- </div> -->
								<?php } ?>
							</table>
						</fieldset>
					</div>
				</div>
				<div id="tabs-5">
					<div class="crow">
						<fieldset>
							<legend>Selecione locais de oferta atendidos</legend>
							<table>
								<tr align="center" style="font-style: italic;">
									<td></td>
									<td>C&oacute;digo Emec</td>
									<td>Local de oferta</td>
								</tr>
								<?php  for ($i = 1; $i <= $tamanho; $i++) {  ?>
									<tr>
										<td align="center"><input type="checkbox" class="form-check-input" <?php echo $block_campos;?>
													name="loferta[<?php echo $b[$i]->getIdLocal();?>]"
													<?php
											if (!is_null($b[$i]->getBloferta())){
													echo "checked";
											}
											?>>
										</td>
										<td> <?php echo ($b[$i]->getIdLocal()); ?></td>
										<td> <?php echo ($b[$i]->getNome()); ?></td>
									</tr>
								<?php }//for
								?>
							</table>
						</fieldset>
					</div>
				</div>
			</div>
		</fieldset>
		</br>
		<div class="crow">
			<?php if ($block_campos == ""){ ?>
				<input type="button" onclick='direciona(1);' value="Gravar" id="botao"	class="btn btn-info" <?php //echo $inputlock; ?> />
<?php }else{?>
				<a href="<?php echo Utils::createLink('biblio', 'altbiblicenso');?>"><input type="button" onClick="javascript:history.go(-1)" value="Voltar" class="btn btn-info"></a> 
				<input class="form-control"name="operacao" type="hidden" value="<?php echo $operacao;?>" />
			<?php }?>
		</div>
	</form> 
</div>


