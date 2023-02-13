<?php
if (!$aplicacoes[7]) {
    header("Location:index.php");
} else {
    require_once('dao/categoriaDAO.php');
    require_once('classes/categoria.php');
    $tipos = array();
    $cont = 0;
    $daocat = new CategoriaDAO();
    $rows = $daocat->Lista();
    foreach ($rows as $row) {
        $cont++;
        $cat[$cont] = new Categoria();
        $cat[$cont]->setCodigo($row['Codigo']);
        $cat[$cont]->setNome($row['Nome']);
    }    
    $daocat->fechar();
}
?>
<head>
<div class="bs-example">
		<ul class="breadcrumb">
		    <li><a href="<?php echo Utils::createLink("labor", "consultalab"); ?>">Consultar laboratórios</a></li>
			<li class="active">Incluir novo labotatório</li>
		</ul>
	</div>
</head>
<form class="form-horizontal" name="fgravar">
    <input class="form-control"name="operacao" type="hidden" value="I" />
    <h3 class="card-title">Laboratórios</h3><br/>
    <div class="msg" id="msg"></div>
    <table width="700px">
        <tr>
            <td>Categoria</td>
            <td><select class="custom-select" name="cat" onchange="ajaxBuscatipo();">
                    <option value="0">-- Selecione a categoria --</option>
                    <?php foreach ($cat as $c) { ?>
                        <option value="<?php print $c->getCodigo(); ?>">
                            <?php print $c->getNome(); ?></option>
                    <?php } ?></select><br /></td>
        </tr>
        <tr>
            <td>Subcategoria</td>
            <td>
                <div id="txtHint"></div></td>
        </tr>
        <tr>
            <td>Nome</td>
            <td><input class="form-control"type="text" name="nome" size="70" maxlength="100" class="texto" /><br />
            </td>
        </tr>
        <tr>
            <td>Sigla</td>
            <td><input class="form-control"type="text" name="sigla" size="10" maxlength="8" class="texto" /><br />
            </td>
        </tr>
        <tr>
            <td>
                <div class="form-group row">
                    <label for="capacidade" class="col-sm-2 col-form-label">Capacidade</label>
                    <div class="col-sm-10">
                        <input class="form-control"type="text" id="capacidade" name="capacidade" maxlength="4" onkeypress="return SomenteNumero(event);" size="5" />
                    </div>
                  </div>
            </td>
        </tr>
        <tr>
            <td>Área</td>
            <td><input class="form-control"type="text" data-mask="000000,00" data-mask-reverse="true" name="area" size="13" maxlength="13" onchange="mascaradec(this.value);" />m<sup>2</sup></td>
        </tr>
        <tr>
            <th colspan="2" align="left"><input class="form-check-input" type="checkbox" style="font-weight: normal;"
                                                name="aulapratica[]" id="aulapratica" value="N"
                                                onclick="exibeQuestao();" />Laborat&oacute;rio de aulas pr&aacute;ticas<br />
        <div id="questao" style="font-weight: normal;" >
            Os equipamentos dispon&iacute;veis neste laborat&oacute;rio s&atilde;o suficientes para
            todos os alunos?<br /> <input class="form-control"type="radio" name="resposta"
                                          value="1" />Sim, em todas as aulas pr&aacute;ticas<br /> <input
                                          type="radio" name="resposta" value="2" />Sim, na maior parte das
            aulas pr&aacute;ticas <br /> <input class="form-control"type="radio" name="resposta"value="3" />Sim, mas apenas na metade das aulas pr&aacute;ticas<br /> <input
                type="radio" name="resposta" value="4" />Sim, mas em menos da
            metade das aulas pr&aacute;ticas<br /> <input class="form-control"type="radio"
                                                          name="resposta" value="5" />N&atilde;o, em nenhuma das aulas pr&aacute;ticas
        </div></th>
        </tr>
        <tr>
            <th colspan="2" align="left">Para laborat&oacute;rios de Inform&aacute;tica</th>
        </tr>
        <tr>
            <td>Número de estações de trabalho</td>
            <td><input class="form-control"type="text" name="nestacoes" onkeypress="return SomenteNumero(event);" maxlength="3" value="0" size="10" />
            </td>
        </tr>
        <tr>
            <td>Local</td>
            <td><input class="form-control"type="text" name="local" maxlength="80" size="70" class="texto" /></td>
        </tr>
        <tr>
            <td>Sistema operacional</td>
            <td><select class="custom-select" name="so">
                    <option value="0" selected="selected">Selecione o sistema
                        operacional...</option>
                    <option value="W">Windows</option>
                    <option value="L">Linux</option>
                </select></td>
        </tr>
        <tr>
            <th colspan="2" align="left"><input class="form-check-input" type="checkbox" name="cabo[]" style="font-weight: normal;"
                                                id="cabo" value="N"/>Possui cabeamento estruturado
            </th>
        </tr>
    </table><br/>
    <input type="button" class="btn btn-info"  id="gravar"   value="Gravar" />&ensp;&ensp;
    <a href="<?php echo Utils::createLink("labor", "consultalab"); ?>">
        <input type="button" class="btn btn-info" onclick="javascript:history.go(-1);" value="Voltar"  />
    </a>
    
     <!--Dados hidenn-->
    <input class="form-control"type="hidden" name="nomeUnidade" value="<?php echo $nomeunidade;?>">
    <input class="form-control"type="hidden" name="codUnidade" value="<?php echo $codunidade; ?>">
    <input class="form-control"type="hidden" name="anoBase" value="<?php echo $anobase;?>">
    <input class="form-control"type="hidden" name="justificativa" value="">
</form>
<!-- atualização para revelar ao clicar no check box -->
<script type="text/javascript">
    window.onload = exibeQuestao();
</script>
<script>

$('#gravar').click(function(){


	$('div#msg').empty();
    

    $.ajax({url: 'ajax/labor/alteraLab.php', type: 'POST', data:$('form[name=fgravar]').serialize() , success: function(data) {
        $('div#msg').html(data);
    }});
});


</script>
