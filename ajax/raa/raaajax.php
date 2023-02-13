<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once '../../dao/PDOConnectionFactory.php';
require_once '../../modulo/raa/dao/topicoDAO.php';
require_once '../../modulo/raa/dao/subtopicoDAO.php';

$raaDAO = new RaaDAO();

$subDAO = new SubtopicoDAO();

$op = $_POST["operacao"];


$titulo = $_POST["titulo"];
$situacao = isset($_POST['situacao']) ? 1 : 0;

if($_POST["codUnidade"] == 100000){
	$tipo = "P";
}else{
	$tipo = "E";
}

$codUnidade = $_POST["codUnidade"];
$anobase = $_POST["anobase"];

	switch ($op){
		case "I":
			$rowqtd = $raaDAO->countmesmotitulo($codUnidade, $titulo,$anobase);
			foreach ($rowqtd as $row){
				$mesmoTitulo = $row['qtd'];
			}
			
			//Buscar a quantidade de tópicos padrões
			$countP=0;
			$qtdTopicoP = $raaDAO->buscartopicospadroes($anobase);
			foreach ($qtdTopicoP as $row){
				$countP = $countP+1;
			}
			
			//Busca a quantidade de topicos criados pela unidade
			$qtdTop = $raaDAO->counttopicos($codUnidade,$anobase);
			foreach ($qtdTop as $row){
				$countU = $row['qtd'];
			}		
			
			//Nova Ordem
			$ordemNovo = $countP+$countU+1;
			
			if($mesmoTitulo == 0){
				$dados = array('ordem' => $ordemNovo, 'codUnidade' => $codUnidade, 'titulo' => $titulo, 'tipo' => $tipo, 'situacao' => $situacao, 'anoinicial' => $anobase);
				$codTopico = $raaDAO->inseretopico($dados);
								
				if($_POST["codUnidade"] == 100000){					
					//Insere as unidades vinculadas ao tópico
					foreach ($_POST['topico_unidade'] as $uni){
						if($uni!=0){
							$dadosTU = array('codTopico' => $codTopico,'codUnidade' => $uni);
							$raaDAO->insereTopicoUnidade($dadosTU);
							//print $codTopico;
						}
					}
				}
				echo 1; //Execultado com sucesso
			}else{
				echo 0; //já existe um tópico com o mesmo título
			}
			break;
		case "A":
			$rowqtd = $raaDAO->countmesmotituloeditar($codUnidade, $titulo, $_POST['codTopico'],$anobase);
			foreach ($rowqtd as $row){
				$mesmoTitulo = $row['qtd'];
			}
			
			//Buscar quantidade de subtopicos válidos
			$qtdSub = 0;
			$rowSub = $subDAO->buscarsubtopicos($codUnidade, $_POST['codTopico'], $anobase);
			foreach ($rowSub as $row){
				$qtdSub=$qtdSub+1;
			}
			
			if($mesmoTitulo == 0){
				if($qtdSub!=0 && $situacao ==0){
					echo 2; //Caso tente desabilitar tópicos com subtópicos válidos
				}else{
					$dados = array('codUnidade' => $codUnidade, 'titulo' => $titulo, 'tipo' => $tipo, 'situacao' => $situacao, 'codTopico' => $_POST['codTopico'], 'anofinal' => $anobase);
					$raaDAO->alteratopico($dados);
					
					if($_POST["codUnidade"] == 100000){
						$raaDAO->excluirUnidadeTopico($_POST['codTopico']);//Exclui todas as vinculações para posteriormente vincular novamente
						//Insere as unidades vinculadas ao tópico
						foreach ($_POST['topico_unidade'] as $uni){
							if($uni!=0){								
								$dadosTU = array('codTopico' => $_POST['codTopico'],'codUnidade' => $uni);
								$raaDAO->insereTopicoUnidade($dadosTU);
								//print $codTopico;
							}
						}
					}					
					echo 1; //Execultado com sucesso
										
				}
			}else{
				echo 0; //já existe um tópico com o mesmo título
			}
			break;
	}	


?>

 
