<script type="text/javascript">
 $(document).ready(
 function() {
 $("#anual").hide();
 $("#serie").hide();
 $("#tipografico").hide();
 $("#unidade").hide();
 });
 $(function() {
 $("#pesquisa-anual").click(function() {
 $("#serie").hide();
 $("#anual").show(600);
 $("#tipografico").show(600);
 limpacampos();
 });
 $("#pesquisa-serie").click(function() {
 $("#anual").hide(600);
 $("#serie").show(600);
 $("#tipografico").hide(600);
 limpacampos();
 });
 $("select[name=agrupamento]").change(function() {
 var selecao = $("select[name=agrupamento]").val();
 $("#unidade").hide();
 if (selecao != "INSTITUTO" && selecao != "ESCOLA" && selecao != "HOSPITAL" && selecao != "CAMPUS")
 $("#unidade").show();
 limpacampos();
 });
 });
 function limpacampos() {
 $("input[name=ano]").val("");
 $("input[name=ano1]").val("");
 $("input[name=txtUnidade]").val("");
 }
</script>
<form class="form-horizontal" name="us" id="us">
 <fieldset>
 <div id="chart-erro"></div>
 <legend>Infraestrutura</legend>
 <div id="tipo_pesquisa">
 <label>Pesquisa:</label>
 <input class="form-control"type="radio" name="pesquisa" id="pesquisa-anual" value="anual"/> Anual
 <input class="form-control"type="radio" name="pesquisa" id="pesquisa-serie" value="serie"/> Série histórica
 </div>
 <div id="anual">
 <label for="anounico">Ano</label> 
 <!-- campo select -->
 <?php
 $ano0 = 2011; // ano inicial
 $anofinal = date('Y') - 1;
 ?>
 <select class="custom-select" name="anounico">
 <?php for ($i = $ano0; $i <= $anofinal; $i++): ?>
 <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
 <?php endfor; ?>
 </select>
 </div> 
 <div id="serie">
 <label for="ano">Período </label>
 <input class="form-control"type="text" size="4" maxlength="4" id="ano" name="ano" value="" class="ano" /> 
 a <input class="form-control"type="text" size="4" maxlength="4" name="ano1" value="" class="ano" />
 </div>
 <div>
 <label for="agrupamento">Campo para agrupamento</label>
 <select class="custom-select" name="agrupamento">
 <option value="0">--Selecione o tipo de agrupamento--</option>
 <option value="INSTITUTO">Institutos</option>
 <option value="CAMPUS">Campus</option>
 <option value="ESCOLA">Escolas</option>
 <option value="HOSPITAL">Hospitais</option>
 <option value="PCD">PCD</option>
 <option value="MODALIDADE">Modalidade</option>
 <!-- <option value="SITUACAO">Situação</option>-->
 <option value="TIPO">Tipo</option>
 </select>
 <label for="campovalor">Campo para valor</label>
 <select class="custom-select" name="campovalor">
 <option value="0">--Selecione o campo para valor--</option>
 <option value="1">Capacidade</option>
 <option value="2">Área</option>
 <option value="3">Número de infraestrutura</option>
 </select>
 </div>
 <div>
 <label>Situação da infraestrutura</label>
 <select class="custom-select" name="situacao">
 <option value="0">--Selecione a situação--</option>
 <option value="A">Ativa</option>
 <option value="D">Desativada</option>
 </select>
 </div>
 <div id="tipografico">
 <label for="tipografico">Tipo do gráfico</label>
 <select class="custom-select" name="tipografico">
 <option value="pizza">Pizza</option>
 <option value="bar">Barra</option>
 </select>
 </div>
 <div id="unidade">
 <label for="txtUnidade">Unidade </label> 
 <input class="form-control"type="text" size="60" id="txtUnidade" name="txtUnidade"/><a href="#" class="help" data-trigger="hover" data-content='Se necessário, informe o nome da unidade específica.' title="Ajuda" ><span class="glyphicon glyphicon-question-sign"></span></a>
 </div>
 <div id="completaform">
 </div>
 <div>
 <input type="button" id="gerarGrafico" value="Gerar gráfico" class="btn btn-info" />
 </div>
 </fieldset>
 <input class="form-control"type="hidden" name="modulo" value="infra" />
</form>
<!-- nesta div serão gerados os resultados ajax: gráficos, tabelas e planilhas para download -->
<div id="resultado">
</div>