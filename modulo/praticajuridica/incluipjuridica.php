<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
//session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[13]) {
    header("Location:index.php");
}
//$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeUnidade();
$codunidade = $sessao->getCodUnidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnoBase();

//require_once('../../includes/dao/PDOConnectionFactory.php');

require_once('dao/praticajuridicaDAO.php');
require_once('classes/praticajuridica.php');

require_once('dao/tipopraticajuridicaDAO.php');
require_once('classes/tipopraticajuridica.php');

//require_once('../../includes/classes/unidade.php');
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
$rows_pj = $daopj->buscapjunidade($codunidade, $anobase);
foreach ($rows_pj as $row) {
    $tipo = $row['Tipo'];
    for ($i = 1; $i <= $tamanho; $i++) {
        if ($tipospj[$i]->getCodigo() == $tipo) {
            $tipospj[$i]->criaPraticajuridica($row["Codigo"], $unidade, $anobase, $row["Quantidade"]);
            $cont1++;
        }
    }
}
$daopj->fechar();
if ($cont1 != 0) {
    $cadeia = "location:consultapjuridica.php";
    header($cadeia);
    //exit();
}
//ob_end_flush();
?>
        <script type="text/javascript">
            function SomenteNumero(e) {
                var tecla = (window.event) ? event.keyCode : e.which;
                //0 a 9 em ASCII
                if ((tecla > 47 && tecla < 58)) {
                    document.getElementById('msg').innerHTML = " ";
                    return true;
                }
                else {
                    if (tecla == 8 || tecla == 0 || tecla == 44) {
                        document.getElementById('msg').innerHTML = " ";
                        return true;//Aceita tecla tab
                    }
                    else {
                        document.getElementById('msg').innerHTML = "Todos os campos devem conter apenas n&uacute;meros.";
                        return false;
                    }
                }
            }

            function valida() {
                var passou = false, teste;
                if (document.pj.qtdAcoes.value == "") {
                    passou = true;
                    document.getElementById('msg').innerHTML = "O campo A&ccedil;&otilde;es &eacute; obrigat&oacute;rio. O sistema aceita 0.";
                } else if (document.pj.qtdAudiencias.value == "") {
                    passou = true;
                    document.getElementById('msg').innerHTML = "O campo Audi&ecirc;ncias &eacute; obrigat&oacute;rio. O sistema aceita 0.";
                } else if (document.pj.qtdAtendimentos.value == "") {
                    passou = true;
                    document.getElementById('msg').innerHTML = "O campo Atendimentos &eacute; obrigat&oacute;rio. O sistema aceita 0.";
                } else if (document.pj.qtdOutros.value == "") {
                    passou = true;
                    document.getElementById('msg').innerHTML = "O campo Outros &eacute; obrigat&oacute;rio. O sistema aceita 0.";
                }

                if (passou) {
                    return false;
                }
                else {
                    return true;
                }
            }

            function Soma() {
                var soma = 0;
                qtde = new Array(document.pj.qtdAcoes.value, document.pj.qtdAudiencias.value,
                        document.pj.qtdAtendimentos.value, document.pj.qtdOutros.value);
                for (var i = 0; i < qtde.length; i++) {
                    if (!isNaN(parseInt(qtde[i]))) {
                        soma += parseInt(qtde[i]);
                    }
                }

                document.getElementById('totalgeral').innerHTML = soma;
            }

            function direciona(botao) {
                switch (botao) {
                    case 1:
                    	$('#pj').attr('action', '?modulo=praticajuridica&acao=oppraticajuridica');
                        //document.forms[0].action = "?modulo=praticajuridica&acao=oppraticajuridica";
                        //document.forms[0].submit();
                        $('#pj').submit();
                        break;
                    case 2:
                        document.forms[0].action = "../saida/saida.php";
                        document.forms[0].submit();
                        break;
                }

            }
        </script>
        <form class="form-horizontal" name="pj" id="pj" method="post">
            <h3 class="card-title">Pr&aacute;tica Jur&iacute;dica</h3>
            <div class="msg" id="msg"></div>

            <table>
                <thead>
                <tr style="font-style:italic;">
                    <th>Itens</th>
                    <th>Quantidade</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>A&ccedil;&otilde;es Ajuizadas</td>
                    <td><input class="form-control"type="text" name="qtdAcoes" onchange="Soma();" size="5" maxlength="4" onkeydown="TABEnter();"
                               value='' onkeypress='return SomenteNumero(event)' /></td>
                </tr>
                <tr>
                    <td>Audi&ecirc;ncias</td>
                    <td><input class="form-control"type="text" name="qtdAudiencias" onchange="Soma();" maxlength="4" onkeydown="TABEnter();"
                               size="5" value='' onkeypress='return SomenteNumero(event)' />
                    </td>
                </tr>
                <tr>
                    <td>Atendimento &agrave; Comunidade Carente</td>
                    <td><input class="form-control"type="text" onchange="Soma();" name="qtdAtendimentos" maxlength="4" onkeydown="TABEnter();"
                               size="5" value='' onkeypress='return SomenteNumero(event)' /><br />
                    </td>
                </tr>
                <tr>
                    <td>Outros Atendimentos</td>
                    <td><input class="form-control"type="text" name="qtdOutros" onchange="Soma();" size="5" maxlength="4" onkeydown="TABEnter();"
                               value='' onkeypress='return SomenteNumero(event)' /></td>
                </tr>
                <tr style="font-style:italic;">
                    <td>Total Geral</td>
                    <td><b id='totalgeral'></b></td>
                </tr>
                </tbody>
            </table>
            <input type="button" class="btn btn-info" onclick='direciona(1);' value="Gravar" />
        </form>
        <script>
                    window.onload = Soma();
        </script>