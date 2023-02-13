<?php
ob_start();
$sessao = $_SESSION["sessao"];

$aplicacoes = $sessao->getAplicacoes();
 
    if (!$aplicacoes[10])
    {
		header("Location:index.php");
    }else{
    require_once('dao/poloDAO.php');
    require_once('classes/polo.php');    
    
    $unidade = new Unidade();
    $unidade->setCodunidade($codunidade);
    $unidade->setNomeunidade($nomeunidade);   
?>
<script language='JavaScript'>
    function TABEnter(oEvent) {
        var oEvent = (oEvent) ? oEvent : event;
        var oTarget = (oEvent.target) ? oEvent.target : oEvent.srcElement;
        if (oEvent.keyCode == 13)
            oEvent.keyCode = 9;
        if (oTarget.type == "text" && oEvent.keyCode == 13)
            //return false;
            oEvent.keyCode = 9;
        if (oTarget.type == "radio" && oEvent.keyCode == 13)
            oEvent.keyCode = 9;
    }     

    //Função que envia o formulário
    /*
    function direciona(botao) {
        switch (botao) {
            case 1:               
                    document.ea.action = "?modulo=epolob&acao=opacess";
                    document.ea.submit();                		
               		break;            
        }
    }*/
</script>

<head>
	<div class="bs-example">
		<ul class="breadcrumb">
		    <li><a href="<?php echo Utils::createLink("epolo", "consultapolo"); ?>">Estrutura do Pólo</a></li>
			<li class="active">Incluir</li>
		</ul>
	</div>
</head>
<div class="ui-widget">
    <div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;">
        <p>
            <span class="ui-icon ui-icon-alert" 
                  style="float: left; margin-right: .3em;"></span>
            <strong>Importante!</strong>
        </p>
        <p>Marque a opção somente se a unidade possui o respectivo item.</p>
    </div>
</div>

<!--Início do formulário de envio dos dados-->
<form class="form-horizontal" name="form-incluipolo" id="form-incluipolo" method="post">
    <h3 class="card-title">Tecnologias e equipamentos do pólo</h3>
    <div class="msg" id="msg"></div>
    
        <table width="400px">
            <tr style="font-style:italic;">
                <td>Itens</td>
                <td>Possui</td>
            </tr>
            <tr>
                <td>Equipamento para Videoconferência
                <a href="#" class="help" data-trigger="hover" data-content="" title="Compreende microfone, monitor/televisão/telão, equipamento com software que conecte diversos pontos na mesma videoconferência, no mesmo encontro."><span class="glyphicon glyphicon-question-sign"></span></a></td>
                <td><input class="form-check-input" type="checkbox" value="1" name="qtdvideoconferencia" />
                </td>
            </tr>
            <tr>
                <td>Sala de Coordenação do Polo
                <a href="#" class="help" data-trigger="hover" data-content="" title="Espaço mobiliado adequado para exercer as atividades de coordenação do polo." ><span class="glyphicon glyphicon-question-sign"></span></a></td>
                <td><input class="form-check-input" type="checkbox" value="1" name="qtdsala" />
                </td>
            </tr>
            <tr>
                <td>Microcomputadores
                <a href="#" class="help" data-trigger="hover" data-content="" title="Computadores pessoais, laptops e tablets." ><span class="glyphicon glyphicon-question-sign"></span></a></td>
                <td><input class="form-check-input" type="checkbox" value="1" name="qtdmicro" />
                </td>
            </tr>
            <tr>
                <td>Conexão à internet banda larga
                <a href="#" class="help" data-trigger="hover" data-content="" title="Banda larga é a conexão de internet em alta velocidade." ><span class="glyphicon glyphicon-question-sign"></span></a></td>
                <td><input class="form-check-input" type="checkbox" value="1" name="qtdbanda" />
                </td>
            </tr>
            <tr>
                <td>Salas equipadas para o atendimento pelos tutores
                <a href="#" class="help" data-trigger="hover" data-content="" title="Espaços acadêmicos com recursos e equipamentos técnicos e didático-pedagógicos que viabilizem vídeo/webconferência." ><span class="glyphicon glyphicon-question-sign"></span></a></td>
                <td><input class="form-check-input" type="checkbox" value="1" name="qtdsalatutores"/>
                </td>
            </tr>            
        </table>
     
    <input class="form-control"name="operacao" type="hidden" value="I" />
    <input class="form-control"name="codUnidade" type="hidden" value="<?php echo $unidade->getCodunidade();?>" />
    <input class="form-control"name="anoBase" type="hidden" value="<?php echo $anobase;?>" />
    <input type="button" id="gravar-epolo" value="Gravar" class="btn btn-info" />
</form>
<?php }  ?>