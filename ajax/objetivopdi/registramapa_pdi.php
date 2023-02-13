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
if (!$aplicacoes[37]) {
    print "Erro ao acessar esta aplicação";
    exit();
}
?>
<?php
// Recupera as informações enviadas via metodo POST
$coddocumento = addslashes($_POST['documento']); // código do documento
$ordem = strip_tags(addslashes($_POST['ordem']));   // ordem do documento
$objetivo_txt = strip_tags(addslashes($_POST['objetivo_txt'])); // nome do objetivo
$descricao = strip_tags(addslashes($_POST['descricao'])); // descrição
$perspectiva = strip_tags(addslashes($_POST['perspectiva'])); // perspectiva
$codmapa = addslashes($_POST['codmapa']); // código do mapa
$codobjetivopdi = NULL;
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
    $rows = $daodoc->buscadocumento($coddocumento);
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
        $objmapa->setCodObjetivoPDI($codobjetivopdi); // código do objetivo do PDI

        $daomapa = new MapaDAO();
        /** Atualização * */
        /** tentativa de atualização * */
        if ($codmapa != NULL) {
            $rows = $daomapa->buscamapa($codmapa);
            if ($rows->rowCount() == 0) {
                $erro = "Não foi encontrado o código";
            } else {
                foreach ($rows as $row) {
                    $rows1 = $daomapa->buscamapaordem($ordem);
                    if ($rows1->rowCount() == 0) {
                        $objmapa->setCodigo($codmapa);
                        $daomapa->altera($objmapa);
                        $sucesso = "Dados atualizados com sucesso!";
                    }
                    else
                        foreach ($rows1 as $row1) {
                            if ($row1['Codigo'] != $row['Codigo'] && ($row1['CodigoDocumento'] == $coddocumento)) {
                                $erro = "A ordem já está cadastrada. Por favor, tente outro valor.";
                            } else {
                                $objmapa->setCodigo($codmapa);
                                $daomapa->altera($objmapa);
                                $sucesso = "Dados atualizados com sucesso!";
                            }
                        }
                }
            }
        }
        /** Inserir * */ else {
            $rows1 = $daomapa->buscamapaordemdoc($ordem, $objdoc->getCodigo());
            if ($rows1->rowCount() > 0) {
                $erro = "A ordem já está cadastrada. Por favor, tente outro valor.";
            } else {
                $daomapa->insere($objmapa); // insere o mapa
                $sucesso = "Dados cadastrados com sucesso!";
            }
        }
    }
    $daomapa->fechar();
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