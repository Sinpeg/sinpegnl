<?php
header("Content-Type:text/html; charset=utf8");
?>
<?php
require '../../dao/PDOConnectionFactory.php';
require_once '../../classes/sessao.php';
require_once '../../classes/unidade.php';
require_once '../../modulo/objetivopdi/classe/Mapa.php';
require_once '../../modulo/documentopdi/classe/Documento.php';
require_once '../../modulo/objetivopdi/dao/MapaDAO.php';
require_once '../../modulo/documentopdi/dao/DocumentoDAO.php';
?>
<?php
session_start();
$sessao = $_SESSION['sessao'];
$codunidade = $sessao->getCodUnidade();
$aplicacoes = $sessao->getAplicacoes();
?>
<?php
if (!$aplicacoes[41]) {
    print "Erro ao acessar esta aplicação";
    exit();
}
?>
<?php
// Recupera as informações enviadas via metodo POST
$pdu = addslashes($_POST['pdu']); // DOCUMENTO PDU
$pdi = addslashes($_POST['documento']); // DOCUMENTO PDI
$ordem = strip_tags(addslashes($_POST['ordem']));   // ORDEM DO DOCUMENTO
$objetivo_txt = strip_tags(addslashes($_POST['objetivo_txt'])); // NOME DO OBJETIVO
$descricao = strip_tags(addslashes($_POST['descricao'])); // DESCRIÇÃO
$perspectiva = strip_tags(addslashes($_POST['perspectiva'])); // PERSPECTIVA
$objetivo = addslashes($_POST['objetivo']); // CÓDIGO DO MAPA
$action = addslashes($_POST['action']);

if (isset($_POST['codmapa'])) {
    $codmapa_atualizar = $_POST['codmapa'];
}

// fim
$erro = "";
?>
<?php
/* Validação */
if ($ordem == "") {
    $erro = "Preencha o campo ordem";
} else if ($objetivo_txt == "") {
    $erro = "Preencha o campo objetivo";
} else if ($descricao == "") {
    $erro = "Preencha o campo descricao";
} else if ($perspectiva == "") {
    $erro = "Preencha o campo perspectiva";
} else {
    // realiza o procedimento
    $daodoc = new DocumentoDAO();
    $rows = $daodoc->buscadocumento($pdu);
    $objdoc = new Documento();
    foreach ($rows as $row) {
        if ($row['CodUnidade'] == $codunidade)
            $objdoc->setCodigo($row['codigo']);
    }
    if ($objdoc->getCodigo() == NULL) {
        $erro = "O documento não foi encontrado";
    } else {
        $objmapa = new Mapa();
        $objmapa->setDocumento($objdoc); // documento
        $objmapa->setObjetivo(($objetivo_txt)); // nome do objetivo
        $objmapa->setdescricaoObjetivo(($descricao)); // descrição
        $objmapa->setPerspectiva(($perspectiva)); // perspectiva
        $objmapa->setOrdem($ordem); // ordem
        $objmapa->setCodObjetivoPDI($objetivo);
        $daomapa = new MapaDAO();
        // Atualização
        if ($action == 'A') {
            $rows = $daomapa->buscamapaordemdoc($ordem, $pdu);
            $objmapa->setCodigo($codmapa_atualizar);
            // Ordem única, portanto é possível atualizar diretamente
            if ($rows->rowCount() == 0) {
                $daomapa->altera($objmapa);
                $sucesso = "Dados atualizados com sucesso!";
            }
            // Caso contrário
            else {
                foreach ($rows as $row) {
                    if ($row['Codigo'] == $codmapa_atualizar) {
                        $daomapa->altera($objmapa);
                        $sucesso = "Dados atualizados com sucesso!";
                    } 
                    else {
                        $erro = "A ordem já está cadastrada. Por favor, tente outro valor";
                    }
                }
            }
        }
        // Inserção
        else if ($action == 'I') {
            $rows = $daomapa->buscamapaordemdoc($ordem, $pdu);
            if ($rows->rowCount() == 0) { // Não há o código de meta
                $daomapa->insere($objmapa);
                $sucesso = "Dados cadastrados com sucesso!";
            } else {
                $erro = "A ordem já está cadastrada. Por favor, tente outro valor";
            }
        }
    }
}
?>
<?php if ($erro != ""): ?>
    <div class="erro">
        <img src="webroot/img/error.png" width="30" height="30"/>
    <?php print $erro; ?>
    </div>
    <?php else : ?>
    <div id="success">
        <img src="webroot/img/accepted.png" width="30" height="30"/><?php print $sucesso; ?>
    </div>
<?php endif; ?>