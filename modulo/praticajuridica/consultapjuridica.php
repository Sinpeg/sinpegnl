<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
//session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[13]) {
    header("Location:index.php");
}
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
$anobase = $sessao->getAnobase();
require_once('dao/praticajuridicaDAO.php');
require_once('classes/praticajuridica.php');
require_once('dao/tipopraticajuridicaDAO.php');
require_once('classes/tipopraticajuridica.php');
$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);

$tipospj = array();
$cont = 0;
$daotpj = new TipopraticajuridicaDAO();
$daopj = new PraticajuridicaDAO();
$rows_tpj = $daotpj->Lista();
foreach ($rows_tpj as $row) {
    $cont++;
    $tipospj[$cont] = new Tipopraticajuridica();
    $tipospj[$cont]->setCodigo($row['Codigo']);
    $tipospj[$cont]->setNome($row['Nome']);
}
$tamanho = count($tipospj);
$cont1 = 0;
$soma = 0;
$rows_pj = $daopj->buscapjunidade($codunidade, $anobase);
foreach ($rows_pj as $row) {
    $tipo = $row['Tipo'];
    for ($i = 1; $i <= $tamanho; $i++) {
        if ($tipospj[$i]->getCodigo() == $tipo) {
            $tipospj[$i]->criaPraticajuridica($row["Codigo"], $unidade, $anobase, $row["Quantidade"]);
            $soma += $row["Quantidade"];
            $cont1++;
        }
    }
}
$daopj->fechar();
if ($cont1 == 0) {
    Utils::redirect('praticajuridica', 'incluipjuridica');
}
?>
<script type="text/javascript">
    function direciona(botao) {
        if (botao == 1) {
            document.getElementById('pj').action = "?modulo=praticajuridica&acao=altpjuridica";
            document.getElementById('pj').submit();
        }

        else {
            document.getElementById('pj').action = "../saida/saida.php";
            document.getElementById('pj').submit();
        }
    }
</script>	<form class="form-horizontal" name="pj" id="pj" method="post">
    <h3 class="card-title">Pr&aacute;tica Jur&iacute;dica</h3>
    <table>
        <tr align="center" style="font-style:italic;">
            <td>Itens</td>
            <td>Quantidade</td>
        </tr>
        <tbody>
        <?php for ($i = 1; $i <= $tamanho; $i++) { ?>
            <tr><td>
                    <?php print ($tipospj[$i]->getNome()); ?></td><td align="center">
                    <?php
                    print $tipospj[$i]->getPraticajuridica()->getQuantidade();
                }
                ?></td> </tr>
        <tr align="center" style="font-style:italic;"><td>Total</td><td><?php echo $soma; ?></td></tr>
        </tbody>
    </table>
    <input type="button" onclick='direciona(1);' value="Alterar" class="btn btn-info" />
</form>
