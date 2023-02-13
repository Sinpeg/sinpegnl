<?php
session_start();
if (!isset($_SESSION["sessao"])) {
 header("location:index.php");
} else {
 $sessao = $_SESSION["sessao"];
 $nomeunidade = $sessao->getNomeunidade();
 $codunidade = $sessao->getCodunidade();
 require_once('dao/infraDAO.php');
 require_once('classes/infraestrutura.php');
 require_once('dao/tipoinfraDAO.php');
 require_once('classes/tipoinfraestrutura.php');
 $codin = $_GET["codin"];
 if (is_numeric($codin)) {
 $daoin = new InfraDAO();
 $daotipoinfra = new TipoinfraDAO();
 $tipoti1 = new Tipoinfraestrutura();
 $tipoti1->setCodigo($codin);
 $unidade = new Unidade();
 $unidade->setCodunidade($codunidade);
 $unidade->setNomeunidade($nomeunidade);
 $tipoti1->criaInfraestrutura($codin, $unidade, null, null, null, null, null, null, null, null, null, null, null);
 $daoin->deleta($tipoti1);
 $daoin->fechar();
 }
 Flash::addFlash('Dados removidos com sucesso');
 Utils::redirect('infra', 'consultainfra');
}
?>
