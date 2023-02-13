<?php


if (!$aplicacoes[5]) {
    header("Location:index.php");
    exit;
} else {
	
    
    $daou = new UnidadeDAO();
    $codigo = filter_input(INPUT_GET, "codigo", FILTER_DEFAULT);
    $codestruturado = $sessao->getCodestruturado();
    
    $daotp = new TppremiosDAO();
    $rows_tp = $daotp->lista();
    $cont=0;
    $tps=array();
    foreach ($rows_tp as $row) {
    	$cont++;
    	$tps[$cont] = new Tppremios();
    	$tps[$cont]->setCodpremio($row['CodPremio']);
    	$tps[$cont]->setNome($row['Nome']);
    }
    
    $unidade = new Unidade();
    $unidade->setCodunidade($sessao->getCodunidade());
    $tamanho=count($tps);
    if ($sessao->isUnidade()){//busca subunidades da unidade
    	
    	$rows = $daou->buscasubunidades00($codestruturado);
    	foreach ($rows as $row) {
    		$unidade->adicionaItemSubunidade($row['CodUnidade'], $row['NomeUnidade'], $row['hierarquia_organizacional']);
    	}
    }
    
    $daop = new PremiosDAO(); // prêmios
    $rows = $daop->buscapremios($codigo); // busca tudo por código
     $listaPaises = $daop->listarPaisesPremios();    
    $ano = 0; // ano
    // itera dentro dos resultados 
    foreach ($rows as $row) {
    	$unidadePremio = new Unidade();
    	$unidadePremio->setCodunidade($row["CodUnidade"]);
    	$subunidade =$row["CodSubunidade"];
    	$tp=new Tppremios();
    	$tp->setCodpremio($codigo);
    	$categoria=$row["Categoria"];
    	$reconhecimento=$row["Reconhecimento"];
    	$unidadePremio->criaSubunidade($row["CodSubunidade"], null, null);
        $unidadePremio->getSubunidade()->criaPremios($row["Codigo"],  $row["OrgaoConcessor"], $row["Nome"], $row["Quantidade"],
        $row["Qtde_discente"], $row["Qtde_docente"], $row["Qtde_tecnico"],$row["Ano"],$tp,$row["Categoria"],$row["pais"],$row["link"]);
        $dadosPais = $daop->buscarPaisPremio($row["pais"]);
    }
    
    $lock = new Lock(); // lock
    // Dados da subunidade 
   
    
    if ($sessao->isUnidade()) 
    {
                                 
    	
        if ($codunidade != $unidadePremio->getSubunidade()->getCodunidade()) 
        {
        	
            // tentativa da cpga de alterar dados de outras unidades
           // Error::addErro("Você não pode alterar dados de outras unidades");
          // Utils::redirect('premios', 'consultapremios');
        	 $lock->setLocked(
        	 Utils::isApproved(5, $codunidade, $unidadePremio->getSubunidade()->getCodunidade(), $anobase)
        	 );//aqui
         
        }
    } 
    else 
    { 
    	
        if ($codunidade == $unidadePremio->getSubunidade()->getCodunidade()) 
        {
              // $lock->setLocked(true); //aqui
        	  $lock->setLocked(Utils::isApproved(5, $codunidadecpga, $codunidade, $anobase));//aqui
        }
        else 
        {
        	// tentativa da subunidade de alterar dados de outras unidades
        	Error::addErro("Você não pode alterar dados de outras unidades");
        	Utils::redirect('premios', 'consultapremios');
        }
    }
    
    $seleciona1="";$seleciona2="";$seleciona3="";
    if ($categoria==1) : $seleciona1="selected"; endif;
    if ($categoria==2) : $seleciona2="selected"; endif;
    if ($categoria==3) : $seleciona3="selected"; endif;

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
        }
        else if   (<?php echo $sessao->getAnobase();?><2018 && document.pre.categoria.value == 0) {
            document.getElementById('msg').innerHTML = "O campo categoria &eacute; obrigat&oacute;rio.";
            document.pre.categoria.focus();
           	  }
        else if   (document.pre.reconhec.value == 0) {
            document.getElementById('msg').innerHTML = "O campo forma de conhecimento &eacute; obrigat&oacute;rio.";
            document.pre.reconhec.focus();          
        }
        else if   (document.pre.pais.value == 0) {
            document.getElementById('msg').innerHTML = "O campo País &eacute; obrigat&oacute;rio.";
            document.pre.reconhec.focus();          
        }
        else if (document.pre.link.value == "") {
            document.getElementById('msg').innerHTML = "O campo Link de Evidência &eacute; obrigat&oacute;rio.";
            document.pre.Nome.focus();
        }
       else if (<?php echo $sessao->getAnobase();?>>=2018 && document.pre.qtdei.value == "") {
            document.getElementById('msg').innerHTML = "O campo quantidade de discentes &eacute; obrigat&oacute;rio.";
            document.pre.qtdei.focus();
        } else if (<?php echo $sessao->getAnobase();?>>=2018 && document.pre.qtdeo.value == "") {
            document.getElementById('msg').innerHTML = "O campo quantidade de docentes &eacute; obrigat&oacute;rio.";
            document.pre.qtdeo.focus();
        }  else if (<?php echo $sessao->getAnobase();?>>=2018 && document.pre.qtdet.value == "") {
            document.getElementById('msg').innerHTML = "O campo quantidade de técnicos &eacute; obrigat&oacute;rio.";
            document.pre.qtdet.focus();
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
            document.getElementById("pre").action = "?modulo=premios&acao=oppremios";
            document.getElementById("pre").submit();
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

<head>
    <div class="bs-example">
        <ul class="breadcrumb">
                <li class="active">
                    <a href="<?php echo Utils::createLink("premios", "consultapremios"); ?>">Consultar prêmios</a>
                    <i class="fas fa-long-arrow-alt-right"></i>
                    Editar prêmio
                </li>
        </ul>
    </div>
</head>

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Editar Pr&ecirc;mio</h3><br/>
    </div>
    <form class="form-horizontal" name="pre" id="pre" method="post">
        <div class="msg" id="msg"></div>
        <table class="card-body">
        <?php if ($sessao->isUnidade()){ ?>
            <tr>
                <td class="coluna1">Subunidade</td>
            </tr><tr>
                <td class="coluna2">
                    <select class="custom-select" name="subunidade" class="form-control">
                        <option value="0">Selecione uma subunidade...</option>
                        <?php
                        foreach ($unidade->getSubunidades() as $sub) {
                            if ($sub->getCodunidade() == $subunidade) {
                                ?>
                                <option selected="selected" value="<?php print $sub->getCodunidade(); ?>">
                                    <?php print $sub->getNomeunidade(); ?></option>
                            <?php } else { ?>
                                <option  value="<?php print $sub->getCodunidade(); ?>">
                                    <?php print $sub->getNomeunidade(); ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <?php }              
            ?>
        
            <tr>
                <td class="coluna1">Org&atilde;o Concessor</td>
                </tr><tr>
                <td class="coluna2"><input class="form-control" <?php echo $lock->getDisabled(); ?> type="text" name="Orgao" maxlength="150" size="90"
                                                            value='<?php echo $unidadePremio->getSubunidade()->getPremios()->getOrgao(); ?>' /></td>                                                         
            </tr>
            <tr>
                <td class="coluna1">Nome do pr&ecirc;mio/distin&ccedil;&atilde;o</td>
                </tr><tr>
                <td class="coluna2"><input class="form-control" <?php echo $lock->getDisabled(); ?> type="text" name="Nome" size="90" maxlength="150"
                    value='<?php echo $unidadePremio->getSubunidade()->getPremios()->getNome(); ?>' /></td>
            </tr>
            <?php  if ($sessao->getAnobase()<2018){?> 
            <tr>
                <td class="coluna1">Categoria</td>
                </tr><tr>
                <td class="coluna2">
                <select class="custom-select" name="categoria">
                    <option value="0">Selecione categoria...</option>
                    <option value="1" <?php echo $seleciona1; ?>>Discente</option>
                    <option value="2" <?php echo $seleciona2; ?>>Docente</option>
                    <option value="3" <?php echo $seleciona3; ?>>T&eacute;cnico-administrativo</option>
                </select>  </td>
            </tr>
            <?php } ?>
            <tr>
            <td class="coluna1">Forma de Reconhecimento</td>
            </tr><tr>
            <td class="coluna2">
            <select class="custom-select" name="reconhec">
            <option value="0">Selecione forma de reconhecimento...</option>
                <?php for ($i = 1; $i <= $tamanho; $i++) {   ?>
                <?php    if ($tps[$i]->getCodpremio()==$reconhecimento){ ?>
                    <option  selected value="<?php echo $tps[$i]->getCodpremio();  ?>" >
                <?php echo $tps[$i]->getNome();  ?></option>
                
                <?php } else {?>    
                    <option value="<?php echo $tps[$i]->getCodpremio();  ?>">
                    <?php echo $tps[$i]->getNome();  ?></option>
                <?php }?>
                <?php } ?>
                </select>  
            </td>
            </tr>
        
            <?php  if ($sessao->getAnobase()<2018){?> 
                <tr>
                <td class="coluna1">Quantidade de pessoas premiadas</td>
                </tr>
                <tr>
                    <td class="coluna2">
                        <input class="form-control"<?php  echo $lock->getDisabled(); ?> type="text" id="qtde" 
                        maxlength="4" onkeypress='return SomenteNumero(event)'  name="qtde" size="5"
                        value='<?php echo $unidadePremio->getSubunidade()->getPremios()->getQtde(); ?>' />
                    </td>
                </tr>
            <?php } else {?>
                <tr>
                    <td class="coluna1">País</td>
                    </tr><tr>
                    <td class="coluna2">
                    <select class="custom-select" name="pais">
                                <?php foreach ($dadosPais as $rowpp) {    ?>
                            <option value="<?php echo $rowpp['codPais'];  ?>">
                            <?php echo $rowpp['paisNome'];  ?></option>
                            <?php } ?>
                                <option value="0">Selecione o País da premiação...</option>
                            <?php foreach ($listaPaises as $rowp) {    ?>
                            <option value="<?php echo $rowp['codPais'];  ?>">
                            <?php echo $rowp['paisNome'];  ?></option>
                            <?php } ?>      
                            
                            </select>  
                    </td>
                </tr>
                <tr>
                    <td class="coluna1">Link de Evidência </td>
                    </tr><tr>
                    <td class="coluna2"><input class="form-control"<?php  echo $lock->getDisabled(); ?> class="form-control" type="text" name="link" value='<?php echo $unidadePremio->getSubunidade()->getPremios()->getLink(); ?>'/></td>
                </tr>

                <tr>
                    <td class="coluna2" align="center">
                        Discente:
                        <input class="form-control" style="width: 100px;" <?php  echo $lock->getDisabled(); ?> type="text" id="qtdei" maxlength="4" onkeypress='return SomenteNumero(event)' name="qtdei" size="5" value='<?php echo $unidadePremio->getSubunidade()->getPremios()->getQtdei(); ?>' />
                    </td>
                </tr>
                <tr>
                    <td class="coluna2" align="center">
                        Docente: 
                        <input class="form-control"style="width: 100px;" 
                                class="form-control" <?php  echo $lock->getDisabled(); ?> 
                                type="text" 
                                id="qtdeo"
                                maxlength="4" 
                                onkeypress='return SomenteNumero(event)' 
                                name="qtdeo" 
                                size="5" 
                                value='<?php echo $unidadePremio->getSubunidade()->getPremios()->getQtdeo(); ?>' 
                        />
                    </td>
                </tr>
                <tr>
                    <td class="coluna2" align="center">
                        Técnico-administrativo: 
                        <input class="form-control" style="width: 100px;" class="form-control" <?php  echo $lock->getDisabled(); ?> type="text" id="qtdet" maxlength="4" onkeypress='return SomenteNumero(event)' name="qtdet" size="5" value='<?php echo $unidadePremio->getSubunidade()->getPremios()->getQtdet(); ?>' />
                    </td>
                </tr>
                
            <?php } ?>
            
        </table>
        <div class="card-body">
            <?php if (!$lock->getLocked()){ ?>
                <input class="form-control"name="operacao" type="hidden" value="A" />
                <input class="form-control"type="hidden" name="codigo" value="<?php print $codigo; ?>" />
                <input type="button" onclick='direciona(1);' value="Gravar" class="btn btn-info" />
            <?php } ?>
        </div>
        
    </form>
</div>