<?php
session_start();
$sessao = $_SESSION["sessao"];

if (!$aplicacoes[5]) {
    header("Location:index.php");
    exit;
} else {
    require_once('classes/premios.php');
    require_once ('dao/premiosDAO.php');
    require_once('classes/tppremios.php');
    require_once ('dao/tppremiosDAO.php');

    $codigo = filter_input(INPUT_GET, "codigo", FILTER_DEFAULT);
    
    $daotp = new TpprimiosDAO();
    $rows_tp = $daotp->lista();
    $cont=0;
    foreach ($rows_tea as $row) {
    	$cont++;
    	$tps[$cont] = new Tppremios();
    	$tps[$cont]->setCodigo($row['Codigo']);
    	$tps[$cont]->setNome($row['Nome']);
    }
    
    $tamanho=$cont;
    if ($sessao->isUnidade()){//busca subunidades da unidade
    	$rows = $daou->buscasubunidades00($codestruturado);
    	foreach ($rows as $row) {
    		$unidade = new Unidade();
    		$unidade->setCodunidade($sessao->getCodunidade());
    		$unidade->adicionaItemSubunidade($row['CodUnidade'], $row['NomeUnidade'], $row['hierarquia_organizacional']);
    	}
    }
    $daop = new premiosDAO(); // prêmios
    $rows = $daop->buscapremios($codigo); // busca tudo por código
    $ano = 0; // ano
    // itera dentro dos resultados 
    foreach ($rows as $row) {
    	$unidadePremio = new Unidade();
    	$unidadePremio->setCodunidade($row["CodUnidade"]);
    	$unidadePremio->criaSubunidade($row["CodSubunidade"], null, null);
        $unidadePremio->getSubunidade()->criaPremios($row["Codigo"],  $row["OrgaoConcessor"], $row["Categoria"], $row["Nome"], $row["Quantidade"], $row["Data"], $row["Ano"]);
       
    }
   
    $lock = new Lock(); // lock
    // Dados da subunidade
    if (!$sessao->isUnidade()) {
        $lock->setLocked(Utils::isApproved(5, $cpga, $codunidade, $premio->getAno()));
        if ($codunidade!=$premio->getUnidade()->getCodunidade()) {
            // tentativa da subunidade alterar dados de outras unidades
            Error::addErro("Você não pode alterar dados de outras unidades");
            Utils::redirect('premios', 'alterapremios');
        }
    } else { 
        if ($codunidade != $premio->getUnidade()->getCodunidade()) {
            $lock->setLocked(true);
        }
    }
}
?>        
<script type="text/javascript">
    function valida() {
        var passou = true;
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
        } else if (document.pre.qtde.value == "") {
            document.getElementById('msg').innerHTML = "O campo Denomina&ccedil;&atilde;o do Pr&ecirc;mio &eacute; obrigat&oacute;rio.";
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
<form class="form-horizontal" name="pre" id="pre" method="post">
    <h3 class="card-title">Pr&ecirc;mios</h3>
    <div class="msg" id="msg"></div>
    <table>
     <?php if ($sessao->isUnidade()): ?>
            <tr>
                <td>Subunidade</td>
                <td><select class="custom-select" name="subunidade" >
                        <option value="0">Selecione uma subunidade...</option>
                        <?php foreach ($unidade->getSubunidades() as $sub) { ?>
                            <option value="<?php print $sub->getCodunidade(); ?>" 
                            <?php if ($sub->getCodunidade()== $unidadePremio->getSubunidade()->getCodunidade())
                            	    echo "selected";
                            ?>>
                            <?php print $sub->getNomeunidade(); ?></option>
                        <?php } ?>
                    </select></td>
            </tr>
        <?php endif; ?>
       
    
        <tr>
            <td>Org&atilde;o Concessor</td>
            <td><input class="form-control"<?php echo $lock->getDisabled(); ?> type="text" name="Orgao" maxlength="150" size="90"
                                                           value='<?php echo $unidadePremio->getSubunidade()->getPremios->getOrgao(); ?>' /></td>
        </tr>
        <tr>
            <td>Nome do pr&ecirc;mio/distin&ccedil;&atilde;o</td>
            <td><input class="form-control"<?php echo $lock->getDisabled(); ?> type="text" name="Nome" size="90" maxlength="150"
                value='<?php echo $unidadePremio->getSubunidade()->getPremios->getNome(); ?>' /></td>
        </tr>
          <tr>
            <td>Categoria</td>
            <td>
            <select class="custom-select" name="categoria">
            <option value="0">Selecione categoria...</option>
            <option value="1" <?php if ($unidadePremio->getSubunidade()->getCategoria()==1) : echo "selected"; endif; ?>>Discente</option>
            <option value="2" <?php if ($unidadePremio->getSubunidade()->getCategoria()==2) : echo "selected"; endif; ?>>Docente</option>
            <option value="3" <?php if ($unidadePremio->getSubunidade()->getCategoria()==3) : echo "selected"; endif; ?>>T&eacute;cnico-administrativo</option>
            </select></td>
        </tr>
        <tr>
        <td>Forma de Reconhecimento</td>
        <td>
        <select class="custom-select" name="reconhec">
          <option value="0">Selecione forma de reconhecimento...</option>
              <?php for ($i = 1; $i <= $tamanho; $i++) {   ?>
              <option value="<?php echo $tps[$i]->getCodpremio();  ?>" 
              <?php if ($tps[$i]->getCodpremio()==$unidadePremio->getSubunidade()->getPremio()->getCodpremio()): echo "selected"; endif; ?>>
              <?php echo $tps[$i]->getNome();  ?></option>
              <?php } ?>      
             </select>  
        </td>
        </tr>
         <tr>
                <td>Data de concess&atilde;o</td>
                <td><input class="form-control"type="text" id="data" maxlength="8" onkeypress='return SomenteNumero(event)' name="qtde" size="8" value='<?php $unidadePremio->getSubunidade()->getPremios->getData(); ?>' /></td>
            </tr>
        <tr>
            <td>Quantidade de pessoas premiadas</td>
            <td><input class="form-control"<?php echo $lock->getDisabled(); ?> type="text" id="qtde" maxlength="4" onkeypress='return SomenteNumero(event)'
                                                           name="qtde" size="5"
                                                           value='<?php echo $unidadePremio->getSubunidade()->getPremios->getQtde(); ?>' /></td>
        </tr>
    </table>
<?php if (!$lock->getLocked()): ?>
        <input class="form-control"name="operacao" type="hidden" value="A" /> <input class="form-control"type="hidden" name="codigo" value="<?php print $codigo; ?>" /> <input type="button" onclick='direciona(1);' value="Gravar" />
    <?php endif; ?>
</form>