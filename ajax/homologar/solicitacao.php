<?php

require_once '../../classes/sessao.php'; // sessão
require_once '../../banco/include_dao.php'; // classes geradas para os módulos adicionados
require_once '../../vendors/phpmailer/class.phpmailer.php';
?>
<?php
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
?>
<?php

if (!$aplicacoes[43]) {
    exit;
}
?>
<?php
session_start();
$codunidade = $sessao->getCodunidade(); // código da unidade
$codap = filter_input(INPUT_POST, "codap", FILTER_DEFAULT); // código da aplicação
$codsub = filter_input(INPUT_POST, "codsub", FILTER_DEFAULT); // código da subunidade
$anobase = filter_input(INPUT_POST, "anobase", FILTER_DEFAULT); // ano base
?>
<?php

// realiza a consulta para os dados passados por POST
// start new transaction;
$transaction = new Transaction();
$transaction->getConnection();
$arr = DAOFactory::getHomologacaoDAO()->queryByCodAplicacao($codap);
for ($i = 0; $i < count($arr); $i++) {
    $row = $arr[$i];
    if ($row->codSub == $codsub && $row->codUnidade == $codunidade && $row->ano == $anobase && $row->situacao == "H") {
        $row->situacao = "S";
        DAOFactory::getHomologacaoDAO()->update($row);
    }
}
$transaction->commit();
?>
<?php

// Envia o e-mail para o SISRAA
$mail = new PHPMailer(); // php mail
$mail->isSMTP();
$mail->Host = "smtp.ufpa.br";
$mail->SMTPAuth = true;
$mail->Username = "sinpeg@ufpa.br";
$mail->Password = "sinpegproplan";
$mail->From = "sinpeg@ufpa.br";
$mail->FromName = "Sistema Integrado de Planejamento e Gestão (SInPeG)";
$mail->addAddress("sinpeg@ufpa.br", "SInPeG");
$mail->Subject = utf8_decode("Solicitação de nova homologação");
$message = "Solicitação de nova homologação realizada no dia " . date("Y/m/d") . " às " . date("H:i:s") . ".";
$mail->Body = utf8_decode($message);
$enviado = $mail->Send();

if ($enviado) 
{
    echo "E-mail enviado com sucesso!";
} 
else 
{
    echo "Erro ao enviar a mensagem";
}
