<?php
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();


if (!$aplicacoes[7]) {
    exit();
} else {
    $lab=NULL;

    require_once('dao/categoriaDAO.php');
    require_once('classes/categoria.php');
    require_once('dao/laboratorioDAO.php');
    require_once('classes/laboratorio.php');
    require_once('dao/tplaboratorioDAO.php');
    require_once('classes/tplaboratorio.php');

    $tipos = array();
    $cont = 0;
    $ano = 0;
    $daocat = new CategoriaDAO();
    $daotlab = new TplaboratorioDAO();
    $daolab = new LaboratorioDAO();
    $unidade = new Unidade();
    $codunidade=$sessao->getCodUnidade();
    
    
    // Trecho para bloqueio
    $lock1 = new Lock();
    // verifica a infraestrutura informada Ã© da unidade
    $rows = $daolab->buscaLaboratorio($_GET["codlab"],$sessao->getAnobase()); // cÃ³digo do laboratÃ³rio
    $botao=1;
    $lock="";
    
    foreach ($rows as $row) {
        if ($row["CodUnidade"] != $codunidade) {
            //$lock->setLocked(true);
            $lock="disabled";
            $botao=0;
        }
        $ano = $row["AnoAtivacao"];
    }
    
    if (!$sessao->isUnidade()) {
        $lock1->setLocked(Utils::isApproved(7, $codunidadecpga, $codunidade, $ano));
    }
    
    // Fim
    //busca categoria
    $rows = $daocat->Lista();
    foreach ($rows as $row) {
        $cont++;
        $cat[$cont] = new Categoria();
        $cat[$cont]->setCodigo($row['Codigo']);
        $cat[$cont]->setNome($row['Nome']);
    }
    
    
    //busca laboratorio a ser alterado
    $codlab = $_GET["codlab"];
    $indice = 0;
    $rows = $daolab->buscaLaboratorio($codlab,$sessao->getAnobase());

    if ($sessao->getAnobase()<2021){
        foreach ($rows as $row) {
            $contt = 0;
            //busca categoria pelo tipo do lab
            $rowst = $daotlab->buscatipo($row["Tipo"]);
            
            foreach ($rowst as $rowt) {
                $categorialab = $rowt["CodCategoria"];
                //busca os tipos da categoria do laborat&otilde;rio
                $rowst = $daotlab->buscacattipo($categorialab);
                foreach ($rowst as $rowt) {
                    $contt++;
                    $tlab[$contt] = new Tplaboratorio();
                    $tlab[$contt]->setCodigo($rowt["Codigo"]);
                    $tlab[$contt]->setNome($rowt["Nome"]);
                    $tlab[$contt]->setCodcategoria($rowt["CodCategoria"]);
                    $codtipolab = $tlab[$contt]->getCodigo();
                    if ($row["Tipo"] == $codtipolab) {
                        $tipolab = $tlab[$contt];
                        $indice = $contt;
                    }
                }
                if ($indice != 0) {
                    $lab = $tipolab->criaLab($row["CodLaboratorio"], $unidade, $row["Nome"], $row["Capacidade"], $row["Sigla"], $row["LabEnsino"], str_replace(".", ",", $row["Area"]), $row["Resposta"], $row["CabEstruturado"], $row["Local"], $row["SisOperacional"], $row["Nestacoes"], $row["Situacao"], $row["AnoAtivacao"], $row["AnoDesativacao"]);
                    $indice = 0;
                }
            }
        }
        
        if ($lab!=NULL){
            
            $selecionado1 = "";
            $selecionado2 = "";
            $selecionado3 = "";
            $selecionado4 = "";
            $selecionado5 = "";
            $selecionado6 = "";
            $selecionado7 = "";
            $selecionado8 = "";
            $selecionado9 = "";
            $selecionado10 = "";
            $selecionado11 = "";
            if ($lab->getLabensino() == "S") {
                $selecionado = "checked";
                switch ($lab->getResposta()) {
                    case 1:$selecionado1 = "checked";
                    break;
                    case 2:$selecionado2 = "checked";
                    break;
                    case 3:$selecionado3 = "checked";
                    break;
                    case 4:$selecionado4 = "checked";
                    break;
                    case 5:$selecionado5 = "checked";
                    break;
                }
            } else
                $selecionado = "";
                if ($lab->getSo() == "W") {
                    $selecionado6 = "selected";
                } else if ($lab->getSo() == "L") {
                    $selecionado7 = "selected";
                }
                if ($lab->getSituacao() == "A") {
                    $selecionado8 = "selected";
                } else {
                    $selecionado9 = "selected";
                }
                if ($lab->getCabo() == "S") {
                    $selecionado10 = "checked='checked'";
                }
        }
    }
    
}//fim

?>
<head>
    <div class="bs-example">
        <ul class="breadcrumb">
                <li class="active">
                    <a href="<?php echo Utils::createLink("laborv3", "consultalab"); ?>">Consultar laboratórios</a>
                    <i class="fas fa-long-arrow-alt-right"></i>
                    Alterar laboratório
                </li>
        </ul>
    </div>
</head>

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Laborat&oacute;rio </h3><br/>
    </div>
    <form class="form-horizontal" name="fgravar">
        <input class="form-control"name="operacao" type="hidden"  value="A" /> 
        <input class="form-control"name="codlab" type="hidden"  value="<?php print $codlab; ?>" />
        <div class="msg" id="msg"></div>

        <table class="card-body">
            <?php if ($sessao->getAnobase()<2021){?>
                <tr>
                    <td class="coluna1">Categoria do laborat&oacute;rio</td>
                    <td class="coluna2">
                        <select <?php echo $lock->getDisabled(); ?> name="cat" onchange="exibediv();
                            ajaxBuscatipo();" >
                            <option value="0">Selecione uma categoria...</option>
                            <?php
                            foreach ($cat as $c) {
                                if ($c->getCodigo() == $lab->getTipo()->getCodcategoria()) {
                                    ?>
                                    <option selected="selected" value="<?php print $c->getCodigo(); ?>"><?php print ($c->getNome()); ?></option>
                                <?php } else {
                                    ?>
                                    <option value="<?php print $c->getCodigo(); ?>"><?php print $c->getNome(); ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="coluna1">Subcategoria</td>
                    <td class="coluna2">
                        <div id="exibe">
                            <select  <?php echo $lock->getDisabled(); ?> name="tlab" onkeydown="TABEnter();">
                                <option value="0">Selecione um tipo...</option>
                                <?php
                                for ($i = 1; $i <= $contt; $i++) {
                                    if ($tlab[$i]->getCodigo() == $lab->getTipo()->getCodigo()) {
                                        ?>
                                        <option selected="selected" value="<?php print $tlab[$i]->getCodigo(); ?>"><?php print $tlab[$i]->getNome(); ?></option>
                                    <?php } else {
                                        ?>
                                        <option value="<?php print $tlab[$i]->getCodigo(); ?>"><?php print $tlab[$i]->getNome(); ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div id="txtHint"></div>
                    </td>
                </tr>
            <?php } else {
                
                $rowc=$daolab->listaClasseCenso($codlab,$sessao->getAnobase());
                $tiporetornado=0;
                
                foreach ($rowc as $f){
                    $tiporetornado=$f['codigo'];
                }
                
                $rowu=$daolab->buscaLaboratorio($codlab, $sessao->getAnobase());
                
                foreach ($rowu as $row){
                    $lab = new Laboratorio();
                    
                    $lab->setCodlaboratorio($row['CodLaboratorio']);
                    $u=new Unidade();
                    $u->setCodunidade($row['CodUnidade']);
                    $lab->setUnidade($u);
                    
                    $lab->setNome( $row["nomelab"] );
                    $lab->setCapacidade($row["Capacidade"]);
                    $lab->setSigla($row["Sigla"]);
                    $lab->setCabo($row["CabEstruturado"]);
                    
                    $lab->setLocal( $row["Local"]) ; 
                    $lab->setSo( $row["SisOperacional"]) ;
                    $lab->setAnoativacao($row["AnoAtivacao"]);
                    $lab->setSituacao( $row["Situacao"]);
                    $lab->setNestacoes($row["Nestacoes"]);
                    $lab->setAnodesativacao( $row["AnoDesativacao"]);
                    $lab->setInfoadd($row["inforAdicional"]);
                    $lab->setSugestao($row["sugestaoDeTipo"]);
                    $codlab=  $row["CodLaboratorio"];
                    $indice = 0;
                    
                    $selecionado6 = "";
                    $selecionado7 = "";
                    $selecionado8 = "";
                    $selecionado9 = "";
                    $selecionado10 = "";
                    if ($lab->getCabo() == "S") {
                        $selecionado10 = "checked='checked'";
                    }
                    $selecionado = "";
                    if ($lab->getSo() == "W") {
                        $selecionado6 = "selected";
                    } else if ($lab->getSo() == "L") {
                        $selecionado7 = "selected";
                    }
                    if ($lab->getSituacao() == "A") {
                        $selecionado8 = "selected";
                    } else if ($lab->getSituacao() == "D") {
                        $selecionado9 = "selected";
                    }/*if ($lab->getSituacao() == "C") {
                        $selecionado100 = "selected";
                    }if ($lab->getSituacao() == "V") {
                        $selecionado101 = "selected";
                    }*/
                } ?>
                <tr>
                    <td class="coluna1">
                        <?php if ($tiporetornado==0) {?>
                            Selecione Tipo
                        <?php } else {?>
                            Tipo 
                        <?php }
                        $rowl=$daolab->listaNova(2021); ?>
                    </td>
                </tr>
                <tr>
                    <td class="coluna2">
                        <div id="exibe">
                            <select <?php echo $lock ?> name="cat" onkeydown="TABEnter();">
                                <option value="0">Selecione o novo tipo...</option>
                                <?php foreach ($rowl as $r) {
                                    if ($tiporetornado!=0 && $r['Codigo'] == $tiporetornado) {                  ?>
                                        <option selected="selected" value="<?php print $tiporetornado; ?>">
                                            <?php print $r['Nome']; ?>
                                        </option> 
                                    <?php } else {   ?>
                                        <option value="<?php print $r['Codigo']; ?>">
                                            <?php print $r['Nome']; ?>
                                        </option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>      
                    </td>
                </tr>
            <?php }//validade=2020 ?>
            <tr>
                <td class="coluna1">Nome</td>
            </tr>
            <tr>
                <td class="coluna2"><input class="form-control"type="text" name="nome" size="70" maxlength="100" onkeydown="TABEnter();"
                        value="<?php print $lab->getNome() ?>" class="texto"  <?php echo $lock; ?> /></td>
            </tr>
            <tr>
                <td class="coluna1">Sigla</td>
            </tr>
            <tr>
                <td class="coluna2"><input class="form-control"type="text" style="width:200px" name="sigla" size="10" maxlength="8" onkeydown="TABEnter();"
                        value="<?php print $lab->getSigla() ?>" class="texto"  <?php echo $lock; ?> /></td>
            </tr>
            <tr>
                <td class="coluna1">
                    <br>
                    <input class="form-check-input" type="checkbox" <?php echo $lock; ?> value="D"
                    name="situacao2" <?php  $sit=$selecionado9=="selected"?"checked":""; echo $sit;?>/>
                    <input class="form-control"type="hidden" name="situacao" value="<?php print $lab->getSituacao();?>"/>
                    Desativar laboratório
                    <br>
                    <br>
                </td>
            </tr>
            <tr>
                <td class="coluna2" align="center">Capacidade:
                    <input class="form-control"type="text" name="capacidade" onkeydown="TABEnter();"
                        onkeypress="return SomenteNumero(event);" maxlength="4"
                        value="<?php print $lab->getCapacidade() ?>" style="width:50px" size="5" <?php echo $lock; ?> /></td>
            </tr>
            <tr>
                <td class="coluna2" align="center">&Aacute;rea:
                    <a href="#" class="help" data-trigger="hover" data-content='O formato válido para o campo área possui de 1 a 3 casas de números inteiros e duas casas decimais (d,dd ou dd,dd ou ddd,dd).' title="O formato vÃ¡lido para o campo área possui de 1 a 3 casas de números inteiros e duas casas decimais (d,dd ou dd,dd ou ddd,dd)." ><span class="glyphicon glyphicon-question-sign"></span></a>
                
                    <?php
                    $areaA="";
                    $rowAtual = $daolab->areaAno($codlab,  $sessao->getAnobase());
                    
                    foreach ($rowAtual as $rowA){           	                            		
                        $areaA = str_replace(".", ",", $rowA['area']);
                    }
                    ?>	

                    <input class="form-control"type="text" style="width:50px" name="area" data-mask="000000,00" data-mask-reverse="true" 
                            value="<?php echo $areaA;?>" size="6"  maxlength="9" <?php echo $lock; ?> />

                    <!--Ã�rea do ano anterior-->           
                    <?php $area ="";      
                                                                    
                    $rowArea = $daolab->areaAno($codlab,  $sessao->getAnobase());

                    foreach ($rowArea as $rowA){
                        $area = str_replace(".", ",", $rowA['area']);
                    }         	           		
                    
                    $rowAreaAnterior = $daolab->areaAnoAnterior($codlab,  $sessao->getAnobase());
                    
                    $AreaAnterior="";

                    foreach ($rowAreaAnterior as $rowA2){            				
                            $AreaAnterior = str_replace(".", ",", $rowA2['area']);								
                    }         	            		
                            
                    ?>
                    &nbsp;
                    Área atual: 
                    <input class="form-control"disabled type="text" name="areaAnteriorView" style="width:50px" data-mask="000000,00" data-mask-reverse="true"   value="<?php print $AreaAnterior;?>" size="5" />
                    <input class="form-control" type="hidden" name="areaAnterior" data-mask="000000,00" data-mask-reverse="true"   value="<?php print $AreaAnterior;?>" size="5" />                      
                </td>
            </tr>
            <tr>
                <td class="coluna1"><br>Justificativa da mudança de área</td>
            </tr>
            <tr>
                <td class="coluna2">
                    <?php
                    $jus="";
                    $jusAtual = $daolab->areaAno($codlab, $sessao->getAnobase());
                    foreach ($jusAtual as $rowJ){
                        $jus = str_replace(".", ",", $rowJ['justificativa']);
                    }            
                    ?>
                    <textarea name="justificativa" placeholder="Insira uma justificativa, caso haja alteração da Área em relação ao ano anterior" class="form-control" rows="5" id="justificativa"><?php echo $jus;?></textarea>
                </td>
            </tr>
            <?php if ($sessao->getAnobase()<2020) {?> 
                <tr>
                    <th colspan="2" align="left">
                        <input class="form-check-input" type="checkbox" <?php print $selecionado; ?> style="font-weight: normal;" name="aulapratica[]" id="aulapratica" value="S"
                                                    onclick="exibeQuestao();" <?php echo $lock; ?> />Laborat&oacute;rio de aulas pr&aacute;ticas<br />
                        <div id="questao" style="font-weight: normal;" >
                            Os equipamentos disponÃ­veis neste laborat&oacute;rio s&atilde;o suficientes para
                            todos os alunos?<br />
                            <input class="form-control"<?php echo $lock; ?> type="radio" name="resposta" <?php print $selecionado1; ?> value="1"/>Sim, em todas as aulas
                            pr&aacute;ticas<br />
                            <input class="form-control"<?php echo $lock; ?> type="radio" name="resposta" <?php print $selecionado2; ?> value="2"/>Sim, na maior parte das
                            aulas pr&aacute;ticas <br />
                            <input class="form-control"<?php echo $lock; ?> type="radio" name="resposta" <?php print $selecionado3; ?> value="3"/>Sim, mas apenas na metade
                            das aulas pr&aacute;ticas<br />
                            <input class="form-control"<?php echo $lock; ?> type="radio" name="resposta" <?php print $selecionado4; ?> value="4"/>Sim,
                            mas em menos da metade das aulas pr&aacute;ticas<br />
                            <input class="form-control"<?php echo $lock; ?> type="radio" name="resposta" <?php print $selecionado5; ?> value="5"/>N&atilde;o, em nenhuma das aulas pr&aacute;ticas<br/>
                        </div> <br />
                    </th>
                </tr>
            <?php }else { ?>
                <tr>
                    <td class="coluna1">Informações adicionais
                        <a href="#" class="help" data-trigger="hover" data-content='' <?php echo $lock; ?>
                            title="Este campo é livre para sugerir informações sobre o laboratório, que você considera importantes de
                            serem coletadas pelo Censo (por exemplo, se o laborório é físico ou virtual, se é multidisciplinar,
                            pr�prio ou n�o, a que se destina, etc.). As sugest�es feitas n�o ser�o incorporadas � sua declara��o
                                e nem far�o parte das estatÃ­sticas oficias do Censo 2020. Elas ser�o analisadas apenas para fins de estudo
                                explorat�rio sobre a possibilidade de melhoria da coleta referente aos laboratórios." >
                            <span class="glyphicon glyphicon-question-sign"></span>
                        </a>
                    </td>
            </tr>
            <tr>
                    <td>  
                        <textarea id="i1" name="infoad"  class="form-control" rows="5"><?php print $lab->getInfoadd();?></textarea>
                    </td>
                </tr>
                <tr>
                    <td class="coluna1">Sugestão de classificação  
                        <a href="#" class="help" data-trigger="hover" data-content='' <?php echo $lock; ?> 
                            title="Se o tipo de laboratÃ³rio escolhido nÃ£o representa bem o laboratÃ³rio, sugira neste campo um tipo mais adequado." >
                            <span class="glyphicon glyphicon-question-sign"></span>
                        </a>
                    </td>
            </tr>
            <tr>
                    <td>  
                        <textarea id="i2" name="sugestao"    class="form-control" rows="5"><?php print $lab->getSugestao();?></textarea>
                    </td>
                </tr>
            <?php }?>        
            <tr>
                <th ><br><h4 align="center">Para laborat&oacute;rios de Inform&aacute;tica<br></h4></th>
            </tr>
            <tr>
                <td class="coluna2" align="center">N&uacute;mero de esta&ccedil;&otilde;es de trabalho:
                    <input class="form-control"type="text" style="width:50px" name="nestacoes" onkeydown="TABEnter();"
                        onkeypress="return SomenteNumero(event);"
                        value="<?php print $lab->getNestacoes() ?>" size="10" maxlength="4" <?php echo $lock; ?> />
                </td>
            </tr>
            <tr>
                <td class="coluna1">Local</td>
            </tr>
            <tr>
                <td class="coluna2"><input class="form-control"type="text" name="local" size="70" maxlength="80" onkeydown="TABEnter();"
                        value="<?php print $lab->getLocal() ?>" class="texto" <?php echo $lock; ?> /></td>
            </tr>
            <tr>
                <td class="coluna1">Sistema operacional utilizado no laborat&oacute;rio</td>
            </tr>
            <tr>
                <td class="coluna2"><select <?php echo $lock; ?> name="so" onkeydown="TABEnter();">
                        <option value="0" selected="selected">Selecione o sistema operacional...</option>
                        <option value="W" <?php print $selecionado6; ?>>Windows</option>
                        <option value="L" <?php print $selecionado7; ?>>Linux</option>
                    </select>
                </td>
            </tr>
            <tr align="center">
                <th>
                    <br>
                    <input class="form-control"<?php echo $lock; ?> type="checkbox" name="cabo[]" style="font-weight: normal;" id="cabo" <?php print $selecionado10; ?>/>Possui cabeamento
                    estruturado
                </th>
            </tr>
        </table><br/>

        <table class="card-body">
            <tr>
                <td align="center">
                    <?php if (!$lock) {
                        if ($botao == true) { ?>
                            <!--  <input type="button" class="btn btn-info" onclick="direcionaAlt();" value="Gravar" <?php //echo $lock->getLocked(); ?> /> -->
                            <input type="button" class="btn btn-info" id="gravar" value="Gravar" <?php echo $lock; ?>  /> 
                            <?php if ($lab->getSituacao() == "V" || $lab->getSituacao() == "A") { ?>
                                <a href="<?php echo Utils::createLink("laborv3", "conslabcurso", array("codlab" => $codlab)); ?>">
                                    <input type="button" id="botao" class="btn btn-info" value="Vincular curso"  />
                                </a>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </td>
            </tr>
        </table>

        <!--Dados hidenn-->
        <input class="form-control"type="hidden" name="nomeUnidade" value="<?php echo $nomeunidade;?>">
        <input class="form-control"type="hidden" name="codUnidade" value="<?php echo $codunidade; ?>">
        <input class="form-control"type="hidden" name="anoBase" value="<?php echo  $sessao->getAnobase();?>">
    </form>
</div>
 
 
<script>
    $('#gravar').click(function(){
        $('div#msg').empty();
        $.ajax({url: 'ajax/laborv3/alteraLab.php', type: 'POST', data:$('form[name=fgravar]').serialize() ,
            success: function(data) {
            $('div#msg').html(data);
            $("html, body").animate({ scrollTop: 0 }, "slow");
        }});
    });
</script>
