<input class="form-control"type="hidden" id="tipoSolicitacaoB">
<!-- input class="form-control"para armazenar o tipo de solicitação na busca dos dados-->
<input
	type="hidden" id="codUsuario"
	value="<?php echo $sessao->getCodusuario();?>">
<!-- input class="form-control"para armazenar o tipo de solicitação na busca dos dados-->

<!-- Modal Loading-->
<div class="" id="modalLoading" tabindex="-1" role="dialog"
	aria-labelledby="modalLoading" aria-hidden="true">
	<div class="loader"></div>
</div>

<!-- Modal Inserir Solicitação-->
<div class="modal fade" id="cadastrarSol" tabindex="-1" role="dialog"
	aria-labelledby="cadastrarSol" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Cadastrar Solicitação</h4>
			</div>
			<div class="modal-body">
				<div id="form">
					<form class="form-horizontal" name="form-criarSol" id="form-criarSolicitacao" method="POST"
						enctype='multipart/form-data'>
						<fieldset>
							<table>
								<tr>
									<td>Documento:</td>
									<td><input class="form-control"type='text' disabled class='form-control'
										value="<?php echo $nomeDoc;?>" nome='nomeDoc'></td>
								</tr>


								<tr>
									<td>Indicador:</td>
									<td><input class="form-control"type='text' disabled class='form-control'
										nome='nomeInd' id='nomeInd'></td>
								</tr>
								<tr>
									<td>Tipo de Operação:</td>
									<td><select class="custom-select" name="tipo" onchange="selectOperacao(this.value)"
										class="form-control" id="selectOp">
											<option value="0">Selecione uma operação</option>
											<option value="1">Substituir Indicador</option>
											<option value="2">Editar Indicador</option>
											<option value="7">Excluir Indicador</option>
									</select></td>
								</tr>
								<tr>
									<td colspan="2"><div id="conteudoSolicitacao"></div></td>
								</tr>

								<!--Quando a edição é selecionada-->
								<tr id="nomeEditar" style="display: none;">
									<td>Nome:</td>
									<td><input class="form-control"type='text' class='form-control' nome='nomeIndEdit'
										id='nomeIndEdit' /></td>
								</tr>
								<tr id="formulaEditar" style="display: none;">
									<td>Fórmula:</td>
									<td><textarea class='form-control' nome='formulaIndEdit'
											id='formulaIndEdit' /></textarea></td>
								</tr>
								<tr id="interEditar" style="display: none;">
									<td>Interpretação:</td>
									<td><select nome='interpretacaoIndEdit'
										id='interpretacaoIndEdit' class='form-control' />
										<option value='1'>Quanto maior, melhor!</option>
										<option value='2'>Quanto menor, melhor!</option> </select></td>
								</tr>


								<tr>
									<td>Justificativa:</td>
									<td><textarea class="form-control" id="justificativa"
											nome="justificativa"></textarea></td>
								</tr>
								<tr>
									<td>Anexo RAT:</td>
									<td><div id="teste">
											<input class="form-control" onchange="verificaExtensao(this)"  class="custom-file-input" type="file"
												accept=".rar,.zip" name="arquivo" id="arquivo" /> <input
												type="text" id="texto" /> <input type="button" id="botao"
												value="Selecionar..." class="btn btn-info" />
										</div></td>
								</tr>
							</table>
							<input class="form-control"type="hidden" name="codestruturado" id="codestruturado"
								value="<?php echo $codestruturado;?>" /> <input class="form-control"type="hidden"
								name="codUnidade" id="codUnidade"
								value="<?php echo $codUnidade;?>" /> <input class="form-control"type="hidden"
								name="codmapa" id="codmapa" value="<?php echo $codmapa;?>" /> <input
								type="hidden" name="coddoc" id="coddoc"
								value="<?php echo $coddocumento;?>" /> <input class="form-control"type="hidden"
								name="anobase" id="anobase" value="<?php echo $anobase;?>" /> <input
								type="hidden" name="codMapaInd" id="codMapaInd" /> <input
								type="hidden" name="codInd" id="codInd" />
						</fieldset>
					</form>
				</div>
				<div id="confirmacaoSol" style="display: none;">
					<p>
						<span class="ui-icon ui-icon-circle-check"
							style="float: left; margin: 0 7px 50px 0;"></span> Cadastro
						realizado com sucesso. Sua solicitação foi enviada para análise.
					</p>
				</div>
				<div id="possuiSolicitacao" style="display: none;">
					<p>
						<span class="ui-icon ui-icon-circle-check"
							style="float: left; margin: 0 7px 50px 0;"></span> Este indicador
						já possui uma solicitação enviada.
					</p>
				</div>
				<div id="loading" style="display: none;">
					<div class="loader"></div>
				</div>
			</div>
			<div class="alert alert-danger" role="alert" id="alert"
				style="display: none;"></div>
			<div class="modal-footer">

				<button type="button" class="btn btn-secondary"
					onclick="btnFechar();" data-dismiss="modal">Fechar</button>
				<button type="button" id="btnEnviarSol" class="btn btn-info">Solicitar</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal para o cadastro de um novo indicador -->
<div class="modal fade" id="cadastrarSolInclusao" tabindex="-1"
	role="dialog" aria-labelledby="cadastrarSolInclusao" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Cadastrar Solicitação de Inclusão</h4>
			</div>
			<div class="modal-body">
				<div id="formInclusao">
					<form class="form-horizontal" name="form-criarSolInclusao"
						id="form-criarSolicitacaoInclusao" method="POST"
						enctype='multipart/form-data'>
						<fieldset>
							<table>
								<tr>
									<td>Documento:</td>
									<td><input class="form-control"type='text' disabled class='form-control'
										value="<?php echo $nomeDoc;?>" nome='nomeDoc'></td>
								</tr>
								<tr>
									<td>Objetivo:</td>
									<td><input class="form-control"type='text' disabled class='form-control'
										value="<?php echo $obje;?>"></td>
								</tr>
								<tr>
									<td colspan="2"><div id="conteudoSolicitacaoInclusao"></div></td>
								</tr>

								<tr>
									<td>Justificativa:</td>
									<td><textarea class="form-control" id="justificativaInclusao"
											nome="justificativaInclusao"></textarea></td>
								</tr>
								<tr>
									<td>Anexo RAT:</td>
									<td><div id="testeInclusao">
											<input class="form-control" onchange="verificaExtensao(this)"  class="custom-file-input" type="file"
												accept=".rar,.zip" name="arquivoInclusao"
												id="arquivoInclusao" /> <input class="form-control"type="textInclusao"
												id="textoInclusao" /> <input type="button" id="botao"
												value="Selecionar..." class="btn btn-info" />
										</div></td>
								</tr>
							</table>
							<input class="form-control"type="hidden" name="codestruturado" id="codestruturado"
								value="<?php echo $codestruturado;?>" /> <input class="form-control"type="hidden"
								name="codUnidade" id="codUnidade"
								value="<?php echo $codUnidade;?>" /> <input class="form-control"type="hidden"
								name="codmapa" id="codmapa" value="<?php echo $codmapa;?>" /> <input
								type="hidden" name="coddoc" id="coddoc"
								value="<?php echo $coddocumento;?>" /> <input class="form-control"type="hidden"
								name="anobase" id="anobase" value="<?php echo $anobase;?>" /> <input
								type="hidden" name="codobjet" id="codobjet"
								value="<?php echo $codObjetico;?>" />
						</fieldset>
					</form>
				</div>
				<div id="confirmacaoSolInclusao" style="display: none;">
					<p>
						<span class="ui-icon ui-icon-circle-check"
							style="float: left; margin: 0 7px 50px 0;"></span> Cadastro
						realizado com sucesso. Sua solicitação foi enviada para análise.
					</p>
				</div>

				<div id="loadingInclusao" style="display: none;">
					<div class="loader"></div>
				</div>
			</div>
			<div class="alert alert-danger" role="alert" id="alertInclusao"
				style="display: none;"></div>
			<div class="modal-footer">

				<button type="button" class="btn btn-secondary"
					onclick="btnFechar();" data-dismiss="modal">Fechar</button>
				<button type="button" id="btnEnviarSolInclusao"
					class="btn btn-info">Solicitar</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Buscar Dados Solicitação substituição do indicador-->

<div class="modal fade" id="dadosSolSubs" tabindex="-1" role="dialog" aria-labelledby="dadosSolSubs" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Dados da Solicitação - Substituição do Indicador</h4>
        </div>
      <div class="modal-body">
        <div id="">
        <form class="form-horizontal" name="" id="" method="POST"  >
		    <fieldset>		       
		        <table id="table_dados1">
		        <tr><td>Documento:</td><td><input class="form-control"type='text' disabled class='form-control' id='docDadosS' nome='docDadosS'></td></tr>
		        <tr><td>Objetivo:</td><td><input class="form-control"type='text' disabled class='form-control' nome='objetivoIn' id='objetivoS'></td></tr>
		        <tr><td>Indicador:</td><td><input class="form-control"type='text' disabled class='form-control' nome='nomeA' id='nomeA'></td></tr>
		        <tr><td>Indicador Substituto:</td><td><input class="form-control"type='text' disabled class='form-control' nome='nomeN' id='nomeN'></td></tr>
		        <tr id="tr_situacaoDadosS"><td>Situação:</td><td><input class="form-control"type='text' disabled class='form-control' nome='situacaoDadosS' id='situacaoDadosS'></td></tr>									      
		        <tr><td>Justificativa:</td><td><textarea disabled class="form-control" id="justDadosS" nome="justDadosS"></textarea></td></tr>
		        <tr><td>Anexo RAT:</td><td><a href="" id="arquivoDadosS"><img width="30" src="webroot/img/download.gif"/></a></td></tr>

			    <?php  if ($grupoAnalista){?>


		        <tr id="tr_deferir1"><td>Situação:</td><td><select class="custom-select" id=situacaoPSubs><option value="A">Aberta</option>
		        								  <option value="D">Deferido</option>
		        								  <option value="I">Indeferido</option></select></td></tr>		         
		       <?php }else{?>
		       <tr id="tr_cancela1" ><td>Situação:</td><td><select class="custom-select" id="situacaoPSubs" name="situacaoPSubs">
						<option value="A">Aberta</option>
						<option value="C">Cancelar</option></select></td></tr>
		       <?php }?>
		       <tr><td>Comentários:</td><td><div id="iframeCS" style="overflow: auto;height: 130px; border:solid 1px;border-color: #E6E6E6;"></div></td></tr>
		       <tr id="tr_comentario1"><td>Comentar:</td><td><textarea class="form-control" id="comentarioPSubs"> </textarea><button style="margin-top:8px" type="button" id="btn_enviarCS"  class="btn btn-info">Enviar Somente Comentário</button></td></tr>		       		       
		       </table>      
		   </fieldset>		   	   		 		   
		</form>
		<table id="table_delegar1" style="display:none;">
		<tr ><td>Delagar para:</td><td><select id="usuarioDelegadoSS" class="form-control">
		       								<option value="52">DINFI</option>
		       								<option value="159">DIAVI</option>
		       								</select></td></tr>
		</table>		       								
		</div>				
      </div>
      <div  class="alert alert-danger" role="alert" id="alert" style="display:none;"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <?php if ($grupoAnalista){?>
        <button type="button"  id="btn_delegarSS"  class="btn btn-info" >Delegar</button>      	
      	<button type="button" id="btn_gravarDelegar1" style="display: none;" class="btn btn-info">Gravar</button>      	
      	<?php }?>      	
      	<button type="button" id="btn_gravarSolDadosS" class="btn btn-info">Gravar</button>      	
      </div>
    </div>
  </div>
</div>

<!-- Modal Buscar Dados Solicitação Edição do indicador-->
<div class="modal fade" id="dadosSolEdit" tabindex="-1" role="dialog" aria-labelledby="dadosSolEdit" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Dados da Solicitação - Edição do Indicador</h4>
        </div>
      <div class="modal-body">
        <div id="">
        <form class="form-horizontal" name="" id="" method="POST"  >
		    <fieldset>		       
		        <table id="table_dados2">		        
		        <tr><td>Documento:</td><td><input class="form-control"type='text' disabled class='form-control' value="" nome='docDadosE' id='docDadosE'></td></tr>
		        <tr><td>Objetivo:</td><td><input class="form-control"type='text' disabled class='form-control' nome='objetivoE' id='objetivoE'></td></tr>
		        <tr><td>Indicador:</td><td><input class="form-control"type='text' disabled class='form-control' nome='' id='nomeDadosE'></td></tr>
		        <tr><td>Novo Nome:</td><td><input class="form-control"type='text' disabled class='form-control' nome='' id='nomeNDadosE'></td></tr>
		        <tr><td>Nova Fórmula:</td><td><input class="form-control"type='text' disabled class='form-control' nome='' id='formulaNDadosE'></td></tr>
		        <tr><td>Nova Interpretação:</td><td><input class="form-control"type='text' disabled class='form-control' nome='' id='interpretacaoNDadosE'></td></tr>
			    <tr><td>Justificativa:</td><td><textarea disabled class="form-control" id="justDadosE" nome=""></textarea></td></tr>
		        <tr id="tr_situacaoDadosE"><td>Situação:</td><td><input class="form-control"type='text' disabled class='form-control' nome='situacaoDadosE' id='situacaoDadosE'></td></tr>
		        <tr><td>Anexo RAT:</td><td><a href="" id="arquivoDadosE"><img width="30" src="webroot/img/download.gif"/></a></td></tr>

		        <?php  if ($grupoAnalista){ ?>
		        <tr id="tr_deferir2"><td>Situação:</td><td><select class="custom-select" id="situacaoPEdit"><option value="A">Aberta</option><option value="D">Deferido</option>
		        								  <option value="I">Indeferido</option></select></td></tr>
			   
		       <?php }else{?>
		       <tr id="tr_cancela2" ><td>Situação:</td><td><select class="custom-select" id="situacaoPEdit" name="situacaoPEdit">
						<option value="A">Aberta</option>
						<option value="C">Cancelar</option></select></td></tr>
		       <?php }?>
		       <tr><td>Comentários:</td><td><div id="iframeCE" style="overflow: auto;height: 130px; border:solid 1px;border-color: #E6E6E6;"></div></td></tr>			        								  
		       <tr><td>Comentar:</td><td><textarea class="form-control" id="comentarioPEdit"> </textarea><button style="margin-top:8px" type="button" id="btn_enviarCE"  class="btn btn-info">Enviar Somente Comentário</button></td></tr>		       
		       </table>      
		   </fieldset>	   		 		   
		</form>
		<table id="table_delegar2" style="display:none;">
		<tr><td>Delagar para:</td><td><select id="usuarioDelegadoSE" class="form-control">
		       								<option value="52">DINFI</option>
		       								<option value="159">DIAVI</option>
		       								</select></td></tr>
		</table>
		</div>				
      </div>
      <div  class="alert alert-danger" role="alert" id="alert" style="display:none;"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <?php  if ($grupoAnalista){?>
        <button type="button"  id="btn_delegarSE"  class="btn btn-info" >Delegar</button>        
        <button type="button" id="btn_gravarDelegar2" style="display: none;" class="btn btn-info">Gravar</button>
        <?php }?>
        <button type="button" id="btn_gravarSolDadosE"  class="btn btn-info">Gravar</button>
     </div>
    </div>
  </div>
</div>

<!-- Modal Buscar Dados Solicitação Excluir indicador-->
<div class="modal fade" id="dadosSolExcluir" tabindex="-1" role="dialog" aria-labelledby="dadosSolExcluir" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Dados da Solicitação - Excluir Indicador</h4>
        </div>
      <div class="modal-body">
        <div id="">
        <form class="form-horizontal" name="" id="" method="POST"  >
		    <fieldset>		       
		        <table id="table_dados7">
		        <tr><td>Documento:</td><td><input class="form-control"type='text' disabled class='form-control' id='docDadosEx' nome='docDadosEx'></td></tr>
		        <tr><td>Objetivo:</td><td><input class="form-control"type='text' disabled class='form-control' nome='objetivoEx' id='objetivoEx'></td></tr>
		        <tr><td>Indicador:</td><td><input class="form-control"type='text' disabled class='form-control' nome='nomeindicadorEx' id='nomeindicadorEx'></td></tr>
		        <tr id="tr_situacaoDadosEx"><td>Situação:</td><td><input class="form-control"type='text' disabled class='form-control' nome='situacaoDadosEx' id='situacaoDadosEx'></td></tr>									      
		        <tr><td>Justificativa:</td><td><textarea disabled class="form-control" id="justDadosEx" nome="justDadosEx"></textarea></td></tr>
		        <tr><td>Anexo RAT:</td><td><a href="" id="arquivoDadosEx"><img width="30" src="webroot/img/download.gif"/></a></td></tr>

				
				<?php  if ($grupoAnalista){?>
		        <tr id="tr_deferir7"><td>Situação:</td><td><select class="custom-select" id="situacaoPEx"><option value="A">Aberta</option><option value="D">Deferido</option>
		        								  <option value="I">Indeferido</option></select></td></tr>		         
		       <?php }else{?>
		       <tr id="tr_cancela7" ><td>Situação:</td><td><select class="custom-select" id="situacaoPEx" name="situacaoPEx">
						<option value="A">Aberta</option>
						<option value="C">Cancelar</option></select></td></tr>
		       <?php }?>
		       <tr><td>Comentários:</td><td><div id="iframeCEx" style="overflow: auto;height: 130px; border:solid 1px;border-color: #E6E6E6;"></div></td></tr>
		       <tr id="tr_comentario7"><td>Comentar:</td><td><textarea class="form-control" id="comentarioPEx"> </textarea> <button style="margin-top:8px" type="button" id="btn_enviarCEx"  class="btn btn-info">Enviar Somente Comentário</button></td></tr>		       		       
		       </table>      
		   </fieldset>		   	   		 		   
		</form>
		<table id="table_delegar7" style="display:none;">
		<tr ><td>Delagar para:</td><td><select id="usuarioDelegadoSEx" class="form-control">
		       								<option value="52">DINFI</option>
		       								<option value="159">DIAVI</option>
		       								</select></td></tr>
		</table>		       								
		</div>				
      </div>
      <div  class="alert alert-danger" role="alert" id="alert" style="display:none;"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        

        <?php if ($grupoAnalista){?>
        <button type="button"  id="btn_delegarSEx"  class="btn btn-info" >Delegar</button>
      	<button type="button" id="btn_gravarDelegar7" style="display: none;" class="btn btn-info">Gravar</button>      	
      	<?php }?>
      	<button type="button" id="btn_gravarSolDadosEx" class="btn btn-info">Gravar</button>         	      	
      </div>
    </div>
  </div>

</div>



<!-- Modal Buscar Dados Solicitação Incluir indicador-->
<div class="modal fade" id="dadosSolIncluir" tabindex="-1" role="dialog"
	aria-labelledby="dadosSolIncluir" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Dados da Solicitação - Incluir Indicador</h4>
			</div>
			<div class="modal-body">
				<div id="">
					<form class="form-horizontal" name="" id="" method="POST">
						<fieldset>
							<table id="table_dados6">
								<tr>
									<td>Documento:</td>
									<td><input class="form-control"type='text' disabled class='form-control'
										id='docDadosIn' nome='docDadosIn'></td>
								</tr>
								<tr>
									<td>Objetivo:</td>
									<td><input class="form-control"type='text' disabled class='form-control'
										nome='objetivoIn' id='objetivoIn'></td>
								</tr>
								<tr>
									<td>Indicador:</td>
									<td><input class="form-control"type='text' disabled class='form-control'
										nome='nomeindicadorIn' id='nomeindicadorIn'></td>
								</tr>
								<tr id="tr_situacaoDadosIn">
								<td>Situação:</td><td><input class="form-control"type='text' disabled class='form-control' nome='situacaoDadosIn' id='situacaoDadosIn'></td>
								</tr>
								<tr>
									<td>Justificativa:</td>
									<td><textarea disabled class="form-control" id="justDadosIn"
											nome="justDadosIn"></textarea></td>
								</tr>
								<tr>
									<td>Anexo RAT:</td>
									<td><a href="" id="arquivoDadosIn"><img width="30"
											src="webroot/img/download.gif" /> </a></td>
								</tr>

				<?php if ($grupoAnalista){?>

		        <tr id="tr_deferir6"><td>Situação:</td><td><select class="custom-select" id="situacaoPIn"><option value="A">Aberta</option><option value="D">Deferido</option>
		        								  <option value="I">Indeferido</option></select></td></tr>		         
		       <?php }else{?>
		       <tr id="tr_cancela6" ><td>Situação:</td><td><select class="custom-select" id="situacaoPIn" name="situacaoPIn">
						<option value="A">Aberta</option>
						<option value="C">Cancelar</option></select></td></tr>
		       <?php }?>
		       <tr><td>Comentários:</td><td><div id="iframeCIn" style="overflow: auto;height: 130px; border:solid 1px;border-color: #E6E6E6;"></div></td></tr>
		       <tr id="tr_comentario6"><td>Comentar:</td><td><textarea class="form-control" id="comentarioPIn"> </textarea><button style="margin-top:8px" type="button" id="btn_enviarCIn"  class="btn btn-info">Enviar Somente Comentário</button></td></tr>		       		       
		       </table>      
		   </fieldset>		   	   		 		   
		</form>
		<table id="table_delegar6" style="display:none;">
		<tr ><td>Delagar para:</td><td><select id="usuarioDelegadoSIn" class="form-control">
		       								<option value="52">DINFI</option>
		       								<option value="159">DIAVI</option>
		       								</select></td></tr>
		</table>		       								
		</div>				
      </div>
      <div  class="alert alert-danger" role="alert" id="alert" style="display:none;"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        

        <?php if ($grupoAnalista){?>
        <button type="button"  id="btn_delegarSIn"  class="btn btn-info" >Delegar</button>      	
      	<button type="button" id="btn_gravarDelegar6" style="display: none;" class="btn btn-info">Gravar</button>      	
      	<?php }?>
      	<button type="button" id="btn_gravarSolDadosIn" class="btn btn-info">Gravar</button>         	      	
      </div>
    </div>
  </div>

</div>

<style>
.modal {
	text-align: center;
	padding: 0 !important;
}

.modal:before {
	content: '';
	display: inline-block;
	height: 100%;
	vertical-align: middle;
	margin-right: -4px;
}

.modal-dialog {
	display: inline-block;
	text-align: left;
	vertical-align: middle;
}

.modal-content {
	width: 700px;
}

/* The Modal (background) */
#modalLoading {
	display: none; /* Hidden by default */
	position: fixed; /* Stay in place */
	/*z-index: 4; /* Sit on top */
	padding-top: 100px; /* Location of the box */
	left: 0;
	top: 0;
	width: 100%; /* Full width */
	height: 100%; /* Full height */
	overflow: auto; /* Enable scroll if needed */
	background-color: rgb(0, 0, 0); /* Fallback color */
	background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
}

.loader {
	left: 50%;
	position: absolute;
	top: 40%;
	left: 45%;
	border: 16px solid #f3f3f3;
	border-radius: 50%;
	border-top: 16px solid #3498db;
	width: 100px;
	height: 100px;
	-webkit-animation: spin 2s linear infinite; /* Safari */
	animation: spin 2s linear infinite;
}
/* Safari */
@
-webkit-keyframes spin { 0% {
	-webkit-transform: rotate(0deg);
}

100%
{
-webkit-transform
:
 
rotate
(360deg);
 
}
}
@
keyframes spin { 0% {
	transform: rotate(0deg);
}

100%
{
transform
:
 
rotate
(360deg);
 
}
}
#teste {
	position: relative;
}

#arquivo {
	position: absolute;
	top: 0;
	left: 0;
	border: 1px solid #ff0000;
	opacity: 0.01;
	z-index: 1;
}

#testeInclusao {
	position: relative;
}

#arquivoInclusao {
	position: absolute;
	top: 0;
	left: 0;
	border: 1px solid #ff0000;
	opacity: 0.01;
	z-index: 1;
}
</style>
<script>
$('#arquivo').on('change',function(){
        var numArquivos = $(this).get(0).files.length;
       
	        $('#texto').val( $(this).val() );
      
    });

$('#arquivoInclusao').on('change',function(){
    var numArquivos = $(this).get(0).files.length;
   
        $('#textoInclusao').val( $(this).val() );
  
});
</script>
