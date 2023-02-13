<?php

session_start();
if (!isset($_SESSION["sessao"])) {
    header("location:index.php");
} else {
    $sessao = $_SESSION["sessao"];

//    $nomeunidade = $sessao->getNomeUnidade();
//    $codunidade = $sessao->getCodUnidade();
    $responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnoBase();
    $codusuario = $sessao->getCodusuario();
    $data = $sessao->getData();

//	require_once('../../includes/dao/PDOConnectionFactory.php');
//	require_once('classes/arquivo.php');
//	require_once('dao/arquivoDAO.php');
//	require_once('../../includes/classes/usuario.php');
    $operacao = $_POST['operacao'];
//    exit();
    $codigo = 0;
    if (($_FILES['userfile']['size'] > 0 && ($_FILES['userfile']['size'] <= 10488576) && !is_executable($_FILES['userfile']['tmp_name']))) {

        $arquivo = $_FILES['userfile']['tmp_name'];
        $tamanho = $_FILES['userfile']['size'];
        $tipo = $_FILES['userfile']['type'];
        $nome = $_FILES['userfile']['name'];
        $erro = $_FILES['userfile']['error'];
        $tmpName = $_FILES['userfile']['tmp_name'];
        $assunto = $_POST["assunto"];
        $comentario = $_POST["comentario"];
        $dao = new ArquivoDAO();
        if ($operacao == "I") {
            $rows = $dao->buscaAssunto($codusuario, $anobase, $assunto);
            foreach ($rows as $row) {
                $operacao = "A";
                $codigo = $row['Codigo'];
            }
        }
        $comentario = $_POST['comentario'];

        if (is_string($comentario) && $arquivo != "none"
        ) {
            $fp = fopen($arquivo, "rb");

            $conteudo = $fp;

            $u = new Usuario();
            $u->setCodusuario($codusuario);
            $u->setResponsavel($responsavel);
            if ($operacao == "I") {
                $u->criaArquivo(null, $assunto, $tipo, $nome, $tamanho, $conteudo, $comentario, null, $data, $anobase);
                $dao->insere($u);
            } elseif ($operacao == "A") {
                if ($codigo == 0) {
                    $codigo = $_POST['codigo'];
                }
                if ($codigo != "" && is_numeric($codigo)) {
                    $u->criaArquivo($codigo, $assunto, $tipo, $nome, $tamanho, $conteudo, $comentario, $data, null, $anobase);
                    $dao->altera($u);
                }
            }
        } else {
            Error::addErro("Erro na entrada de dados. Não foi possível carregar o arquivo para o servidor.");
            Utils::redirect('uparquivo', 'consultaarqs');
        }
    } else {
        Error::addErro("Arquivo inv&aacute;lido! Pode estar vazio ou ter tamanho maior que 2Mbytes. Não foi possível carregar o arquivo para o servidor.");
        Utils::redirect('uparquivo', 'consultaarqs');
    }
    $dao->fechar();
}
Utils::redirect('uparquivo', 'consultaarqs');
Flash::addFlash("Arquivo enviado com sucesso!");
?>