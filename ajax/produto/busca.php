<?php

//set_include_path(';../../includes');
require_once('../../classes/sessao.php');
session_start();
if (!isset($_SESSION["sessao"])) {
    header("location:index.php");
} else {
    $sessao = $_SESSION["sessao"];
//    $nomeunidade = $sessao->getNomeunidade();
//    $codunidade = $sessao->getCodunidade();
//    $responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnobase();
    require_once('../../dao/PDOConnectionFactory.php');
    require_once('../../modulo/produto/dao/prodfarmaciaDAO.php');
    require_once('../../modulo/produto/classes/prodfarmacia.php');
    require_once('../../modulo/produto/classes/produtos.php');


    $tproduto = $_POST["produto"];

    if (is_numeric($tproduto) && $tproduto != "") {
        $pro = new Produtos();
        $pro->setCodigo($tproduto);
        $dao = new ProdfarmaciaDAO();
        $rows = $dao->buscatipoproduto($anobase, $tproduto);
        $passou = false;
        foreach ($rows as $row) {
            $passou = true;
            $pro->adicionaItemProdfarmacia($row['Codigo'], $row['Quantidade'], $anobase, $row['Preco'], $row['Mes']);
        }
        $dao->fechar();


        if (!$passou) {
            $display = "<br/><br/>N&atilde;o existem registros para o produto. ";
        } else {

            $display = "<br/><table class='tablesorter-dropbox'><thead>";
            $display.="<tr align=center><th>M&ecirc;s</th><th>Quantidade</th><th>Pre&ccedil;o</th><th>Alterar</th><th>Excluir</th></tr></thead><tbody>";
            foreach ($pro->getProdfarmacias() as $p) {
                $display.="<tr><td>";
                if ($p->getMes() == 1)
                    $display.= "janeiro";
                if ($p->getMes() == 2)
                    $display.= "fevereiro";
                if ($p->getMes() == 3)
                    $display.= "mar&ccedil;o";
                if ($p->getMes() == 4)
                    $display.= "abril";
                if ($p->getMes() == 5)
                    $display.= "maio";
                if ($p->getMes() == 6)
                    $display.= "junho";
                if ($p->getMes() == 7)
                    $display.= "julho";
                if ($p->getMes() == 8)
                    $display.= "agosto";
                if ($p->getMes() == 9)
                    $display.= "setembro";
                if ($p->getMes() == 10)
                    $display.= "outubro";
                if ($p->getMes() == 11)
                    $display.= "novembro";
                if ($p->getMes() == 12)
                    $display.= "dezembro";

                $display.="</td>";
                $display.="<td align=center>" . $p->getQuantidade() . "</td>";
                $display.="<td>R$" . str_replace(".", ",", $p->getPreco());
                $display.="</td>";
                $display.="<td align=center>";
                $display.="<a href=?modulo=produto&acao=alprodfarma&codigo=" . $p->getCodigo() . " target=_self>";
                $display.="<img src=webroot/img/editar.gif alt=Alterar width=19 height=19 /> </a>";
                $display.="</td>";
                $display.="<td align=center>";
                $display.="<a href=?modulo=produto&acao=delprodfarma&codigo=" . $p->getCodigo() . "&tproduto=" . $tproduto;
                $display.="><img src=webroot/img/delete.png alt=Excluir width=19 height=19 /></a>";
                $display.="</td></tr>";
            }
            $display.="</tbody></table>";
        }
        echo $display;
    }
//	ob_end_flush();
}
?>