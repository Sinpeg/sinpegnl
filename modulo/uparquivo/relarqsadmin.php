<?php

$nomeunidade = $sessao->getNomeUnidade();
$codunidade = $sessao->getCodUnidade();
$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnoBase();
$aplicacoes = $sessao->getAplicacoes();
$aplicacoes = $_SESSION["sessao"]->getAplicacoes();



$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
ob_end_flush();
?>
  <head>
  <div class="bs-example">
		<ul class="breadcrumb">
		<li><a href="<?php echo Utils::createLink("raa", "consultarRelatorio"); ?>">Consultar RAA</a></li>
			<li class="active">Consultar Anexos</li>
		</ul>
	</div>
        <link rel="stylesheet" href="../../estilo/msgs.css" />
    </head>
    <body>
        <form class="form-horizontal" name="us" id="us" method="post" action="<?php echo AJAX_DIR.'uparquivo/consultadmin.php'; ?>">
            <h3 class="card-title">Consulta - Arquivos enviados</h3> <br />
            <div class="msg" id="msg"></div>
            <div class="msg" id="msg1"></div>
            <table>
                <tr>
                    <td>Assunto</td>
                    <td><select class="custom-select" name="assunto">
                            <option value="1">Apresenta&ccedil;&atilde;o do Relat&oacute;rio de Atividades</option>
                            <option value="2">Outros</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Ano</td>
                    <td><input class="form-control"type="text" size="5" data-mask="0,0000" data-mask-reverse="true"  maxlength="6" name="ano"
                               value='' />
                    </td>
                </tr>
            <!--     <tr>
                    <td>Unidade</td>
                    <td><input class="form-control"type="text" size="70" id="unidade" name="unidade"/></td>
                </tr>-->


            </table>
            <input class="form-control"name="operacao" type="hidden" readonly="readonly" value="I" />
       <!--      <input type="button" onclick='direciona(1);' value="Consultar" />-->
                        <input type="button" value="Buscar" id="resposta" class="btn btn-info btn" />
                        
                        
                 <div id="resultado" ></div>       

        </form>
    </body>
<script>
    
        
    $('#resposta').click(function(event) {
                        event.preventDefault();
                        $("#resultado").html("");
                        $("#resultado").addClass("ajax-loader");
                        //alert($("#us").attr("action"));
                        $.ajax({
                            url: $("#us").attr("action"),
                            type: "POST",
                            data: $("#us").serialize(),
                            success: function(data) {
                            	//alert(data);
                                $("#resultado").html(data);
                                $("#resultado").removeClass("ajax-loader");
                            }
                        });
                    });
        

</script>