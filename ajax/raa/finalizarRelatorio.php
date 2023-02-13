<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require '../../dao/PDOConnectionFactory.php';
require_once '../../dao/usuarioDAO.php';
require_once '../../dao/unidadeDAO.php';
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../modulo/raa/dao/textoDAO.php';
require_once '../../vendors/phpmailer/PHPMailer.php';
require_once '../../vendors/phpmailer/Exception.php';
require_once '../../vendors/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$erro="";

$textoDAO = new TextoDAO();

$codUnidade = $_POST['codUnidade'];
$ano = $_POST['ano'];

$topicosNomes = $_POST['topicosNome'];
$topicosId = $_POST['topicosId'];

//Buscar tópicos não preenchidos
for($i=0;$i<count($topicosNomes);$i++){
   $rowPendencias = $textoDAO->buscarPendencias($codUnidade, $ano, $topicosId[$i]);
   if($rowPendencias->rowCount()==0){
     $erro .= '<div class="alert alert-danger" role="alert">O tópico <b>'.$topicosNomes[$i].'</b> não foi salvo! Clique no botão "Gravar".<br/></div>';
   }
}

if($erro==""){//Caso em que todos os itens foram salvos
	$uniDAO = new UnidadeDAO();
	$rowsDAO = $uniDAO->buscaidunidadeRel($codUnidade);
	foreach ($rowsDAO as $row){
		if($row['unidade_responsavel'] == 1){
			// Envia o e-mail para o SISRAA
			$mail = new PHPMailer(); // php mail
			$mail->isSMTP();
			$mail->IsHTML(true);
			$mail->CharSet = "UTF-8";
			$mail->Encoding = 'base64';
			$mail->Host = "smtp.gmail.com";
			$mail->SMTPAuth = true;
			$mail->Username = "sinpeg@ufpa.br";
			$mail->Password = "prtqytbjayjmldji";
			$mail->SMTPSecure = "tls";
			$mail->Port = 587;  
			$mail->From = "sinpeg@ufpa.br";
			$mail->FromName = "Sistema Integrado de PLanejamento e Gestão (SInPeG)";
			$mail->addAddress("sandy.faro@ufpa.br", "SInPeG");
			$mail->Subject = "Finalização do Relatório - SInPeG";
			$message = "A unidade: <b>".$row['NomeUnidade']."</b>, finalizou seu Relatório Anual de Atividades.";
			$mail->IsHTML(true);
			$mail->Body = $message;
			$enviado = $mail->Send();			
		}
	}
	$textoDAO->finalizaTexto($codUnidade, $ano);
	$erro = 1;
}

echo $erro;

?>