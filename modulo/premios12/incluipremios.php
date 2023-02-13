<?php
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[5]) {
    header("Location:index.php");
}
$nomeunidade = $sessao->getNomeUnidade();
$codunidade = $sessao->getCodUnidade();
$codestruturado = $sessao->getCodestruturado();
require_once('dao/premiosDAO.php');
require_once('classes/premios.php');
require_once('dao/tppremiosDAO.php');
require_once('classes/tppremios.php');

$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$daou = new UnidadeDAO();

if ($sessao->isUnidade()){//busca subunidades da unidade
  $rows = $daou->buscasubunidades00($codestruturado);
  foreach ($rows as $row) {
    $unidade->adicionaItemSubunidade($row['CodUnidade'], $row['NomeUnidade'], $row['hierarquia_organizacional']);
  }
}
$daotp = new TppremiosDAO();
$rows_tp = $daotp->lista();
$cont=0;
foreach ($rows_tea as $row) {
	$cont++;
	$tps[$cont] = new Tppremios();
	$tps[$cont]->setCodigo($row['Codigo']);
	$tps[$cont]->setNome($row['Nome']);
}
$tamanho=$cont;
?>
<script type="text/javascript">
    function valida() {
 	   var s = document.pre.categoria.options[document.pre.categoria.selectedIndex].text;
 	   var s1 = document.pre.reconhec.options[document.pre.reconhec.selectedIndex].text;
 	   var passou=true;
//        if (document.pre.subunidade.value == "0") {
//            document.getElementById('msg').innerHTML = "O campo Subunidade &eacute; obrigat&oacute;rio. Se n&atilde;o houver subunidade, escolha a unidade.";
//            document.pre.subunidade.focus();
//        } else
        if (document.pre.Orgao.value == "") {
            document.getElementById('msg').innerHTML = "O campo &oacute;rg&atilde;o Concessor &eacute; obrigat&oacute;rio.";
            document.pre.Orgao.focus();
        }
        else if (document.pre.Nome.value == "") {
            document.getElementById('msg').innerHTML = "O campo Denomina&ccedil;&atilde;o do Pr&ecirc;mio &eacute; obrigat&oacute;rio.";
            document.pre.Nome.focus();
        }
        else if   (s == "" || s == null) {
            document.getElementById('msg').innerHTML = "O campo categoria &eacute; obrigat&oacute;rio.";
            document.pre.categoria.focus();
           	  }
        else if   (s1 == "" || s1 == null) {
            document.getElementById('msg').innerHTML = "O campo forma de conhecimento &eacute; obrigat&oacute;rio.";
            document.pre.reconhec.focus();          
        }
        else if (document.pre.data.value == "") {
            document.getElementById('msg').innerHTML = "O campo Data de concess&atilde;o &eacute; obrigat&oacute;rio.";
            document.pre.data.focus();
        } else if (document.pre.qtde.value == "") {
            document.getElementById('msg').innerHTML = "O campo quantidade de pessoas &eacute; obrigat&oacute;rio.";
            document.pre.qtde.focus();
        } else
            passou = false;

         if (passou) {
            return false;
        }
        else {
            return true;
        }
    }
    function direciona(botao) {
        if (valida()) {
            document.forms[0].action = "?modulo=premios&acao=oppremios";
            document.forms[0].submit();
        }
    }
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
                document.getElementById('msg').innerHTML = "O campo deve conter apenas n&uacute;mero.";
                return false;
            }
        }
    }
</script>
<form class="form-horizontal" name="pre" method="post">
    <h3 class="card-title">Pr&ecirc;mios</h3>
    <div class="msg" id="msg"></div>
    <table>
        <?php if ($sessao->isUnidade()): ?>
            <tr>
                <td>Subunidade</td>
                <td><select class="custom-select" name="subunidade" >
                        <option value="0">Selecione uma subunidade...</option>
                        <?php foreach ($unidade->getSubunidades() as $sub) { ?>
                            <option value="<?php print $sub->getCodunidade(); ?>">
                                <?php print $sub->getNomeunidade(); ?></option>
                        <?php } ?>
                    </select></td>
            </tr>
        <?php endif; ?>
        <tbody>
            <tr>
                <td>&Oacute;rg&atilde;o Concessor/Unidade</td>
                <td><input class="form-control"type="text" name="Orgao" maxlength="80" size="90" value='' /></td>
            </tr>
            <tr>
                <td>Nome do pr&ecirc;mio/distin&ccedil;&atilde;o</td>
                <td><input class="form-control"type="text" name="Nome" maxlength="80" size="90" value='' /></td>
            </tr>
            <tr>
                <td>Categoria</td>
                <td><select class="custom-select" name="categoria">
                            <option value="0">Selecione categoria...</option>
                            <option value="1">Discente</option>
                            <option value="2">Docente</option>
                            <option value="3">T&eacute;cnico-administrativo</option>
                      </select></td>
            </tr>
             <tr>
                <td>Forma de Reconhecimento</td>
                <td>
                <select class="custom-select" name="reconhec">
                            <option value="0">Selecione forma de reconhecimento...</option>
                           <?php for ($i = 1; $i <= $tamanho; $i++) {   ?>
                           <option value="<?php echo $tps[$i]->getCodpremio();  ?>">
                           <?php echo $tps[$i]->getNome();  ?></option>
                           <?php } ?>      
                           
                        </select>  
                </td>
            </tr>
             <tr>
                <td>Data de concess&atilde;o</td>
                <td><input class="form-control"type="text" id="data" maxlength="8" onkeypress='return SomenteNumero(event)' name="qtde" size="8" value='' /></td>
            </tr>
            <tr>
                <td>Quantidade de pessoas premiadas</td>
                <td><input class="form-control"type="text" id="qtde" maxlength="4" onkeypress='return SomenteNumero(event)' name="qtde" size="5" value='' /></td>
            </tr>
        </tbody>
    </table>
    <input class="form-control"type="hidden" name="operacao" value="I" /> <input type="button"
                                                             onclick='direciona(1);' value="Gravar" />
</form>
