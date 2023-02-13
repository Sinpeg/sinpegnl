<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

//session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();

if (!$aplicacoes[10]){
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
     
}

if ($cont == 0) {
   Utils::redirect('epolo', 'incluipolo');
}
 
?>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active">Estrutura do pólo</li>
		</ul>
	</div>
</head>

<div><a href="relatorio/relatorio_epolo.php?anoBase=<?php echo $anobase;?>"><img alt="Exportar Relatório" src="webroot/img/pdf.png"> Exportar Relatório dos Pólos</a>
</div>
<br/>
<form class="form-horizontal" name="form-direciona" id="form-incluipolo" method="post">
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
                <?php $item = ($dados['qtdvideo']==1)? "Sim" : "Não";echo $item;?>
                </td>
            </tr>
            <tr>
                <td>Sala de Coordenação do Polo
                <a href="#" class="help" data-trigger="hover" data-content="" title="Espaço mobiliado adequado para exercer as atividades de coordenação do polo." ><span class="glyphicon glyphicon-question-sign"></span></a></td>
                <td><?php $item = ($dados['qtdsala']==1)? "Sim" : "Não";echo $item;?>
                </td>
            </tr>
            <tr>
                <td>Microcomputadores
                <a href="#" class="help" data-trigger="hover" data-content="" title="Computadores pessoais, laptops e tablets." ><span class="glyphicon glyphicon-question-sign"></span></a></td>
                <td><?php $item = ($dados['qtdmicro']==1)? "Sim" : "Não";echo $item;?>
                </td>
            </tr>
            <tr>
                <td>Conexão à internet banda larga
                <a href="#" class="help" data-trigger="hover" data-content="" title="Banda larga é a conexão de internet em alta velocidade." ><span class="glyphicon glyphicon-question-sign"></span></a></td>
                <td><?php $item = ($dados['qtdbanda']==1)? "Sim" : "Não";echo $item;?>
                </td>
            </tr>
            <tr>
                <td>Salas equipadas para o atendimento pelos tutores
                <a href="#" class="help" data-trigger="hover" data-content="" title="Espaços acadêmicos com recursos e equipamentos técnicos e didático-pedagógicos que viabilizem vídeo/webconferência." ><span class="glyphicon glyphicon-question-sign"></span></a></td>
                <td><?php $item = ($dados['qtdsalatutores']==1)? "Sim" : "Não";echo $item;?>
                </td>
            </tr>            
        </table>
     <br />
    <input type="button" id="alterard-epolo" value="Alterar" class="btn btn-info"/> 
    <input class="form-control"name="operacao" type="hidden" value="A" />
    <input class="form-control"name="codUnidade" type="hidden" value="<?php echo codunidade;?>" />
    <input class="form-control"name="anoBase" type="hidden" value="<?php echo $anobase;?>" />    
</form>    


