<?php
//session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[5]) {
    header("Location:index.php");
}

$nomeunidade = $sessao->getNomeUnidade();
$codunidade = $sessao->getCodUnidade();
$codestruturado = $sessao->getCodestruturado();

$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$daou = new UnidadeDAO();

if ($sessao->isUnidade())  //busca subunidades da unidade
{
  $rows = $daou->buscasubunidades00($codestruturado);
  foreach ($rows as $row) 
  {
    $unidade->adicionaItemSubunidade($row['CodUnidade'], $row['NomeUnidade'], $row['hierarquia_organizacional']);
  }	
}

$tps = array();
$cont=0;

$daotp = new TppremiosDAO();

$rows_tp = $daotp->lista();
foreach ($rows_tp as $row) {
	$cont++;
	$tps[$cont] = new Tppremios();
	$tps[$cont]->setCodpremio($row['CodPremio']);
	$tps[$cont]->setNome($row['Nome']);
}
 $tamanho = count($tps);
 
 $daop = new PremiosDAO();
 $listaPaises = $daop->listarPaisesPremios();
 
 
?>

<script type="text/javascript">
    function valida() {
 	   var erro=true;
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
            erro = false;

         if (erro) {
            return false;
        }
        else {
            return true;
        }
    }
    
    function direciona(botao) {
    	 switch (botao) {
         case 1:
        if (valida()) 
                // &&  (validarData()))
             {
              
            document.getElementById("pre").action = "?modulo=premios&acao=oppremios";
            document.getElementById("pre").submit();
            }
        break;
         case 2:
             document.forms[0].action = "?modulo=premios&acao=consultaitem";
             document.forms[0].submit();
             break;
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
			<li class="active">Pr&ecirc;mios</li>
		</ul>
	</div>
</head>

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Pr&ecirc;mios</h3>
    </div>
    <form class="form-horizontal" name="pre" id="pre" method="post">

        <table class="card-body">
            <div class="msg" id="msg"></div>
            <tbody>
                <?php if ($sessao->isUnidade()){?> 
                    <tr>
                        <td class="coluna1">Subunidade </td>
                    </tr>
                    <tr>
                        <td class="coluna2">
                            <select class="custom-select" name="subunidade" >
                                <option value="0">Selecione uma subunidade...</option>
                                <?php foreach ($unidade->getSubunidades() as $sub) { ?>
                                    <option value="<?php print $sub->getCodunidade(); ?>">
                                        <?php print $sub->getNomeunidade(); ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td class="coluna1">&Oacute;rg&atilde;o Concessor/Unidade</td>
                </tr>
                <tr>
                    <td class="coluna2"><input class="form-control" type="text" name="Orgao" maxlength="80" size="90" value='' /></td>
                </tr>
                <tr>
                    <td class="coluna1">Nome do pr&ecirc;mio/distin&ccedil;&atilde;o</td>
                </tr>
                <tr>
                    <td class="coluna2"><input class="form-control" type="text" name="Nome" maxlength="80" size="90" value='' /></td>
                </tr>
            <?php  if ($sessao->getAnobase()<2018){?> 
                <tr>
                    <td class="coluna1">Categoria </td>
                </tr>
                <tr>
                    <td class="coluna2">
                        <select class="custom-select" name="categoria">
                            <option value="0">Selecione categoria...</option>
                            <option value="1">Discente</option>
                            <option value="2">Docente</option>
                            <option value="3">T&eacute;cnico-administrativo</option>
                        </select>
                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <td class="coluna1">Forma de Reconhecimento</td>
                </tr>
                <tr>
                    <td class="coluna2">
                        <select class="custom-select" name="reconhec">
                            <option value="0">Selecione forma de reconhecimento...</option>
                            <?php for ($i = 1; $i <=$tamanho; $i++) {   ?>
                                <option value="<?php echo $tps[$i]->getCodpremio();  ?>">
                                <?php echo $tps[$i]->getNome();  ?></option>
                            <?php } ?>      
                            
                        </select>  
                    </td>
                </tr>
                <tr>
                    <td class="coluna1">País</td>
                </tr>
                <tr>
                    <td class="coluna2">
                        <select class="custom-select" name="pais">
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
                </tr>
                <tr>
                    <td class="coluna2"><input class="form-control" type="text" name="link" /><br></td>
                </tr>
            <!--    <tr>
                    <td>Data de concess&atilde;o</td>
                    <td><input class="form-control"type="text" id="data" maxlength="10"    name="data" size="10" value='' /></td>
                </tr> --> 
                
                <?php  if ($sessao->getAnobase()<2018){?> 
                    <tr>
                        <td class="coluna2">
                            Quantidade de pessoas premiadas 
                            <input class="form-control" type="text" id="qtde" maxlength="4" onkeypress='return SomenteNumero(event)' name="qtde" size="5" value='' />
                        </td>
                    </tr>
                <?php } else {?>
                    <tr>
                        <td class="coluna1">
                            Nº de Discentes
                        </td>
                    </tr>
                    <tr>
                        <td class="coluna2" align="center">
                            <input class="form-control"style="width: 20%;" class="form-control" type="number" id="qtdei" maxlength="4" onkeypress='return SomenteNumero(event)' name="qtdei" size="5" value='' />
                        </td>
                    </tr>
                    <tr>
                        <td class="coluna1">
                            Nº de Docentes
                        </td>
                    </tr>
                    <tr>
                        <td class="coluna2" align="center">
                            <input class="form-control"style="width: 20%;" class="form-control" type="number" id="qtdeo" maxlength="4" onkeypress='return SomenteNumero(event)' name="qtdeo" size="5" value='' />
                        </td>
                    </tr>
                    <tr>
                        <td class="coluna1"> 
                            Nº de Técnicos-administrativo
                        </td>
                    </tr>
                    <tr>
                        <td class="coluna2" align="center">
                            <input class="form-control"style="width: 20%;" class="form-control" type="number" id="qtdet" maxlength="4" onkeypress='return SomenteNumero(event)' name="qtdet" size="5" value='' />
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <br>
        <table class="card-body">
            <tr>
                <td align="center">
                    <input class="form-control"type="hidden" name="operacao" value="I" /> 
                    <input type="button"  onclick='direciona(1);' value="Gravar" id="gravar" class="btn btn-info"/>
                    <a href="relatorio/relatorio_premios.php?anoBase=<?php echo $anobase;?>&codUnidade=<?php echo $codunidade;?>" > 
                    <input type="button" id="botao" value="Relatorio de Prêmios" class="btn btn-info" /></a>
                </td>
            </tr>
        </table>
    </form>
</div>
