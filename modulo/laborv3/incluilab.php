<?php
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[7]) {
    exit();
} else {
    
    require_once('dao/tplaboratorioDAO.php');
    require_once('classes/tplaboratorio.php');
    
    $tipos = array();
    $cont = 0;
    $daocat = new TplaboratorioDAO();
    $rows = $daocat->Lista($sessao->getAnobase());
    foreach ($rows as $row) {
        $cont++;
        $cat[$cont] = new Tplaboratorio();
        $cat[$cont]->setCodigo($row['Codigo']);
        $cat[$cont]->setNome($row['Nome']);
    }    
    
}
?>
<head>
<div class="bs-example">
		<ul class="breadcrumb">
            <li class="active">
                <a href="<?php echo Utils::createLink("laborv3", "consultalab"); ?>">Consultar laboratórios</a>
                <i class="fas fa-long-arrow-alt-right"></i>
			    Incluir novo labotatório
            </li>
		</ul>
	</div>
</head>


<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Laboratório </h3>
    </div>
    <form class="form-horizontal" name="fgravar">

        <table class="card-body" width="700px">        
            <input class="form-control"name="operacao" type="hidden" value="I" />
            <div class="msg" id="msg"></div>
            <tr>
                <td class="coluna1">Categoria</td>
            </tr>
            <tr>
                <td class="coluna2">
                    <select class="custom-select" name="cat">
                        <option value="0">-- Selecione a categoria --</option>
                        <?php foreach ($cat as $c) { ?>
                            <option value="<?php print $c->getCodigo(); ?>">
                                <?php print $c->getNome(); ?></option>
                        <?php } ?>
                    </select><br />
                </td>
            </tr>   
            <tr>
                <td class="coluna1">Nome</td>            
            </tr>
            <tr>
                <td class="coluna2">
                    <input class="form-control"type="text" name="nome" size="70" maxlength="100" class="texto" /><br />
                </td>
            </tr>
            <tr>
                <td class="coluna1">Sigla</td>
            </tr>
            <tr>
                <td class="coluna2">
                    <input class="form-control"type="text" style="width:200px" name="sigla" size="10" maxlength="8" class="texto" /><br />
                </td>
            </tr>
            <tr>
                <td class="coluna2">        
                    <div class="form-group row">
                        <label for="capacidade" class="col-sm-2 col-form-label">Capacidade</label>
                        <div class="col-sm-2">
                            <input class="form-control"type="text" id="capacidade" name="capacidade" maxlength="4" onkeypress="return SomenteNumero(event);" size="5" />
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="coluna2">        
                    <div class="form-group row">
                        <label for="area" class="col-sm-1 col-form-label">Área</label>
                        <div class="col-sm-1">
                            <input class="form-control"type="text" id="area" style="width:50px;" data-mask="000000,00" data-mask-reverse="true" name="area" size="13" maxlength="13" onchange="mascaradec(this.value);" />
                        </div>
                        <strong>m<sup>2</sup> </strong>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="coluna1"> </br>Informações adicionais   
                    <a href="#" class="help" data-trigger="hover" 
                        data-content='' title="Este campo é livre para sugerir informações
                        sobre o laboratório, que você considera importantes de serem coletadas
                        pelo Censo (por exemplo, se o laborário é físico ou virtual, se é multidisciplinar,
                        próprio ou não, a que se destina, etc.). As sugestões feitas não serão incorporadas
                        à sua declaração e nem farão parte das estatísticas oficias do Censo 2020.
                        Elas serão analisadas apenas para fins de estudo exploratório sobre a possibilidade
                        de melhoria da coleta referente aos laboratórios." >
                        <span class="glyphicon glyphicon-question-sign"></span>
                    </a>
                </td>            
            </tr>
            <tr>
                <td class="coluna2">  
                    <textarea id="i1" name="infoad"      class="form-control" rows="5"></textarea>
                </td>
            </tr>
    
            <tr>
                <td class="coluna1">Sugestão de classificação  
                        <a href="#" class="help" data-trigger="hover" data-content='' 
                            title="Se o tipo de laboratório escolhido não representa bem o laboratório, sugira neste campo um tipo mais adequado." >
                            <span class="glyphicon glyphicon-question-sign"></span>
                        </a>
                </td>            
            </tr>
            <tr>
                <td class="coluna2">
                    <textarea id="i2" name="sugestao"     class="form-control" rows="5"></textarea>
                </td>
            </tr>
            <tr>
                <th><h4 align="center"></br>Para laborat&oacute;rios de Inform&aacute;tica</br></h4></th>
            </tr>
            <tr>
                <td class="coluna2">
                    <div class="form-group row">
                        <label for="estTrabalho" class="col-sm-2 col-form-label">Número de estações de trabalho</label>
                        <div class="col-sm-10">
                        <input class="form-control"type="text" style="width:50px" id="estTrabalho" name="nestacoes" onkeypress="return SomenteNumero(event);" maxlength="3" value="0" size="10" />
                    </div>
                </td>
            </tr>
            <tr>
                <td class="coluna1">Local</td>
            </tr>
            <tr>
                <td class="coluna2">
                    <input class="form-control"type="text" name="local" maxlength="80" size="70" class="texto" />
                </td>
            </tr>
            <tr>
                <td class="coluna1">Sistema operacional</td>
            </tr>
            <tr>
                <td class="coluna2">
                    <select class="custom-select" name="so">
                        <option value="0" selected="selected">Selecione o sistema
                            operacional...</option>
                        <option value="W">Windows</option>
                        <option value="L">Linux</option>
                    </select></td>
            </tr>
            <tr>
                <th colspan="2" align="left">
                    <input class="form-check-input" type="checkbox" name="cabo[]" style="font-weight: normal;" id="cabo" value="N"/> Possui cabeamento estruturado
                </th>
            </tr>
        </table>

        <div class="card-body">
            <input type="button" class="btn btn-info" id="gravar" value="Gravar" />
            <a href="<?php echo Utils::createLink("laborv3", "consultalab"); ?>">
                <input type="button" class="btn btn-info" onclick="javascript:history.go(-1);" value="Voltar"  />
            </a>
        </div>
        
        <!--Dados hidenn-->
        <input class="form-control"type="hidden" name="nomeUnidade" value="<?php echo $nomeunidade;?>">
        <input class="form-control"type="hidden" name="codUnidade" value="<?php echo $codunidade; ?>">
        <input class="form-control"type="hidden" name="anoBase" value="<?php echo $anobase;?>">
        <input class="form-control"type="hidden" name="justificativa" value="">
    </form>
</div>


<!-- atualização para revelar ao clicar no check box -->
<script type="text/javascript">
    window.onload = exibeQuestao();

    $('#gravar').click(function(){
        $('div#msg').empty();
        $.ajax({url: 'ajax/laborv3/alteraLab.php', type: 'POST', data:$('form[name=fgravar]').serialize() , success: function(data) {
            $('div#msg').html(data);
        }});
    });
</script>
