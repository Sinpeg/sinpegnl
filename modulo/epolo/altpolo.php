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

    $daopolo = new  poloDAO();
    $rows_ep = $daopolo->buscaep($codunidade, $anobase);
    
    $cont = 0; // contador
    
    foreach ($rows_ep as $row) {
    	$cont++;
    	if($cont>0){
    		$dados = array('anoBase' => $anobase,'codUnidade' => $codunidade, 'qtdvideo' => $row['videoConf'], 'qtdsala' => $row['coordenacao'], 'qtdmicro' => $row['micros'], 'qtdbanda' => $row['bandaLarga'], 'qtdsalatutores' => $row['salaTutor']);
    	}
    }
?>

<head>
	<div class="bs-example">
		<ul class="breadcrumb">
		    <li><a href="<?php echo Utils::createLink("epolo", "consultapolo"); ?>">Estrutura do Pólo</a></li>
			<li class="active">Alterar</li>
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
<form class="form-horizontal" name="form-alterarpolo" id="form-alterarpolo" method="post">
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
                <td>               
                <input class="form-check-input"<?php  print ($dados['qtdvideo']==1)?"checked":""; ?> type="checkbox" value="1" name="qtdvideoconferencia" />
                </td>
            </tr>
            <tr>
                <td>Sala de Coordenação do Polo
                <a href="#" class="help" data-trigger="hover" data-content="" title="Espaço mobiliado adequado para exercer as atividades de coordenação do polo." ><span class="glyphicon glyphicon-question-sign"></span></a></td>
                <td><input class="form-check-input"<?php  print ($dados['qtdsala']==1)?"checked":""; ?> type="checkbox" value="1" name="qtdsala" />
                </td>
            </tr>
            <tr>
                <td>Microcomputadores
                <a href="#" class="help" data-trigger="hover" data-content="" title="Computadores pessoais, laptops e tablets." ><span class="glyphicon glyphicon-question-sign"></span></a></td>
                <td><input class="form-check-input"<?php  print ($dados['qtdmicro']==1)?"checked":""; ?> type="checkbox" value="1" name="qtdmicro" />
                </td>
            </tr>
            <tr>
                <td>Conexão à internet banda larga
                <a href="#" class="help" data-trigger="hover" data-content="" title="Banda larga é a conexão de internet em alta velocidade." ><span class="glyphicon glyphicon-question-sign"></span></a></td>
                <td><input class="form-check-input"<?php  print ($dados['qtdbanda']==1)?"checked":""; ?> type="checkbox" value="1" name="qtdbanda" />
                </td>
            </tr>
            <tr>
                <td>Salas equipadas para o atendimento pelos tutores
                <a href="#" class="help" data-trigger="hover" data-content="" title="Espaços acadêmicos com recursos e equipamentos técnicos e didático-pedagógicos que viabilizem vídeo/webconferência." ><span class="glyphicon glyphicon-question-sign"></span></a></td>
                <td><input class="form-check-input"<?php  print ($dados['qtdsalatutores']==1)?"checked":""; ?> type="checkbox" value="1" name="qtdsalatutores"/>
                </td>
            </tr>            
        </table>
     
    <input class="form-control"name="operacao" type="hidden" value="A" />
    <input class="form-control"name="codUnidade" type="hidden" value="<?php echo $codunidade;?>" />
    <input class="form-control"name="anoBase" type="hidden" value="<?php echo $anobase;?>" />
    <input type="button" id="alterar-epolo" value="Gravar" class="btn btn-info" />
</form>
<?php }  ?>