<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once '../../dao/PDOConnectionFactory.php';
require_once '../../modulo/raa/dao/modeloDAO.php';

$modeloDAO = new ModeloDAO();

$op = $_POST["operacao"]; //Pode ser inclusão ou edição

$topico = $_POST["topico"]; //Recebe o codigo do tópico

$legenda = $_POST["legenda"]; //Recebe a legenda
$modelo = $_POST["modelo"]; //Recebe o modelo
$situacao = isset($_POST['situacao']) ? 1 : 0;

$codUnidade = $_POST["codUnidade"];

$codUnidadeSessao = $_POST["codUnidadeSessao"];

$anobase = $_POST["anobase"];

//echo $op;
	
	switch ($op){
		case "I":			
			//Busca a quantidade de modelos existentes
			$qtdMod = $modeloDAO->countmodelos($topico,$anobase);
			foreach ($qtdMod as $row){
				$ordemNovo = $row['qtd']+1;
			}
			$dados = array('codUnidadeSessao' => $codUnidadeSessao,'modelo' => $modelo,'ordem' => $ordemNovo,'topico' => $topico, 'codUnidade' => $codUnidade, 'legenda' => $legenda, 'situacao' => $situacao, 'anoInicio' => $anobase);
			$modeloDAO->inseremodelo($dados);
			echo 1; //Execultado com sucesso
			
			break;
		case "A":
			if($codUnidade==0){
				$codUnidade = NULL;
			}
			 
			$dados = array('codUnidadeSessao' => $codUnidadeSessao,'codModelo' => $_POST['codModelo'], 'modelo' => $modelo,'topico' => $topico, 'codUnidade' => $codUnidade, 'legenda' => $legenda, 'situacao' => $situacao, 'anofinal' => date("Y"));
			$modeloDAO->alteramodelo($dados);	
			echo 1; //Execultado com sucesso			
			break;
	}	
?>

 
