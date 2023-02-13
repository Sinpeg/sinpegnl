<?php


if (!$aplicacoes[7]) {
    header("Location:index.php");
} else {
	
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
    // Trecho para bloqueio
    $lock = new Lock();
    // verifica a infraestrutura informada é da unidade
    $rows = $daolab->buscaLaboratorio($_GET["codlab"]); // código do laboratório
    foreach ($rows as $row) {
        if ($row["CodUnidade"] != $codunidade) {
            $lock->setLocked(true);
        }
        $ano = $row["AnoAtivacao"];
    }
    
    if (!$sessao->isUnidade()) {
        $lock->setLocked(Utils::isApproved(7, $codunidadecpga, $codunidade, $ano));
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
    $rows = $daolab->buscaLaboratorio($codlab);
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
    $daocat->fechar();
}
?>
<head>
<div class="bs-example">
		<ul class="breadcrumb">
		    <li><a href="<?php echo Utils::createLink("labor", "consultalab"); ?>">Consultar laboratórios</a></li>
			<li class="active">Alterar laboratório</li>
		</ul>
	</div>
</head>
<form class="form-horizontal" name="fgravar" id="fgravar" method="post">
    <h3 class="card-title">Laborat&oacute;rio </h3><br/>
    <input class="form-control"name="operacao" type="hidden"
                                       value="A" /> <input class="form-control"name="codlab" type="hidden"
                                       value="<?php print $codlab; ?>" />
    <div class="msg" id="msg"></div>
    <table>
        <tr>
            <td>Categoria do laborat&oacute;rio</td>
            <td><select <?php echo $lock->getDisabled(); ?> name="cat" onchange="exibediv();
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
                </select></td>
        </tr>
        <tr>
            <td>Subcategoria</td>
            <td>
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
        <tr>
            <td>Nome</td>
            <td><input class="form-control"type="text" name="nome" size="70" maxlength="100" onkeydown="TABEnter();"
                       value="<?php print $lab->getNome() ?>" class="texto"  <?php echo $lock->getDisabled(); ?> /></td>
        </tr>
        <tr>
            <td>Sigla</td>
            <td><input class="form-control"type="text" name="sigla" size="10" maxlength="8" onkeydown="TABEnter();"
                       value="<?php print $lab->getSigla() ?>" class="texto"  <?php echo $lock->getDisabled(); ?> /></td>
        </tr>
        <tr>
            <td>Situa&ccedil;&atilde;o</td>
            <td><select <?php echo $lock->getDisabled(); ?> onkeydown="TABEnter();" name="situacao">
                    <option value="A" <?php print $selecionado8; ?>>Ativado</option>
                    <option value="D" <?php print $selecionado9; ?>>Desativado</option>
                </select></td>
        </tr>
        <tr>
            <td>Capacidade</td>
            <td><input class="form-control"type="text" name="capacidade" onkeydown="TABEnter();"
                       onkeypress="return SomenteNumero(event);" maxlength="4"
                       value="<?php print $lab->getCapacidade() ?>" size="5" <?php echo $lock->getDisabled(); ?> /></td>
        </tr>
        <tr>
            <td>&Aacute;rea
            <a href="#" class="help" data-trigger="hover" data-content='O formato válido para o campo Área possui de 1 a 3 casas de números inteiros e duas casas decimais (d,dd ou dd,dd ou ddd,dd).' title="O formato válido para o campo Área possui de 1 a 3 casas de números inteiros e duas casas decimais (d,dd ou dd,dd ou ddd,dd)." ><span class="glyphicon glyphicon-question-sign"></span></a></td>
            <td>
            <?php
            $areaA="";
           	$rowAtual = $daolab->areaAno($codlab, $anobase);
            foreach ($rowAtual as $rowA){
            	$areaA = str_replace(".", ",", $rowA['area']);
            }
            
            ?>	
            <input class="form-control"type="text" name="area" onkeydown="TABEnter();"
                       value="<?php echo $areaA;?>" size="5" onchange="mascaradec(this.value);" maxlength="6" <?php echo $lock->getDisabled(); ?> />
             
             <!--Área do ano anterior-->           
            <?php   $area ="";         		
            		$ano = $anobase-1; 
            		if($ano < 2017){$ano = 2017;}            		
            		$rowArea = $daolab->areaAno($codlab, $ano);
            		foreach ($rowArea as $row){
            			$area = str_replace(".", ",", $row['area']);
            		}
            ?>
            	&ensp;&ensp;&ensp;&ensp;Ano anterior <input class="form-control"disabled type="text" name="areaAnterior"  value="<?php echo $area;?>" size="5" />           
            </td>
        </tr>
       
       <tr>
       <td>Justificativa da mudança de área</td>
       <td>
       <?php
            $jus="";
           	$jusAtual = $daolab->areaAno($codlab, $anobase);
            foreach ($jusAtual as $rowJ){
            	$jus = str_replace(".", ",", $rowJ['justificativa']);
            }            
       ?>
       <textarea name="justificativa" placeholder="Insira uma justificativa, caso haja alteração da área em relaçao ao ano anterior" class="form-control" rows="5" id="justificativa"><?php echo $jus;?></textarea></td>
       </tr>
        
        <tr>
            <th colspan="2" align="left"><input class="form-control"type="checkbox"
                                                <?php print $selecionado; ?> style="font-weight: normal;"
                                                name="aulapratica[]" id="aulapratica" value="S"
                                                onclick="exibeQuestao();" <?php echo $lock->getDisabled(); ?> />Laborat&oacute;rio de aulas pr&aacute;ticas<br />
        <div id="questao" style="font-weight: normal;" >
            Os equipamentos disponíveis neste laborat&oacute;rio s&atilde;o suficientes para
            todos os alunos?<br />
            <input class="form-control"<?php echo $lock->getDisabled(); ?> type="radio" name="resposta" <?php print $selecionado1; ?> value="1"/>Sim, em todas as aulas
            pr&aacute;ticas<br />
            <input class="form-control"<?php echo $lock->getDisabled(); ?> type="radio" name="resposta" <?php print $selecionado2; ?> value="2"/>Sim, na maior parte das
            aulas pr&aacute;ticas <br />
            <input class="form-control"<?php echo $lock->getDisabled(); ?> type="radio" name="resposta" <?php print $selecionado3; ?> value="3"/>Sim, mas apenas na metade
            das aulas pr&aacute;ticas<br />
            <input class="form-control"<?php echo $lock->getDisabled(); ?> type="radio" name="resposta" <?php print $selecionado4; ?> value="4"/>Sim,
            mas em menos da metade das aulas pr&aacute;ticas<br />
            <input class="form-control"<?php echo $lock->getDisabled(); ?> type="radio" name="resposta" <?php print $selecionado5; ?> value="5"/>N&atilde;o, em nenhuma das aulas pr&aacute;ticas<br/>
        </div> <br /></th>
        </tr>
        <tr>
            <th colspan="2" align="left">Para laborat&oacute;rios de Inform&aacute;tica</th>
        </tr>
        <tr>
            <td>N&uacute;mero de esta&ccedil;&otilde;es de trabalho</td>
            <td><input class="form-control"type="text" name="nestacoes" onkeydown="TABEnter();"
                       onkeypress="return SomenteNumero(event);"
                       value="<?php print $lab->getNestacoes() ?>" size="10" maxlength="4" <?php echo $lock->getDisabled(); ?> />
            </td>
        </tr>
        <tr>
            <td>Local</td>
            <td><input class="form-control"type="text" name="local" size="70" maxlength="80" onkeydown="TABEnter();"
                       value="<?php print $lab->getLocal() ?>" class="texto" <?php echo $lock->getDisabled(); ?> /></td>
        </tr>
        <tr>
            <td>Sistema operacional utilizado no laborat&oacute;rio</td>
            <td><select <?php echo $lock->getDisabled(); ?> name="so" onkeydown="TABEnter();">
                    <option value="0" selected="selected">Selecione o sistema
                        operacional...</option>
                    <option value="W" <?php print $selecionado6; ?>>Windows</option>
                    <option value="L" <?php print $selecionado7; ?>>Linux</option>
                </select></td>
        </tr>
        <tr>
            <th colspan="2" align="left"><input class="form-control"<?php echo $lock->getDisabled(); ?> type="checkbox" name="cabo[]" style="font-weight: normal;" id="cabo" <?php print $selecionado10; ?>/>Possui cabeamento
                estruturado
            </th>
        </tr>
    </table><br/>
    <?php if (!$lock->getLocked()): ?>
        <input type="button" class="btn btn-info" onclick="direcionaAlt();" value="Gravar" <?php echo $lock->getLocked(); ?> />&ensp;&ensp;
        <a href="<?php echo Utils::createLink("labor", "consultalab"); ?>">
        <input type="button" class="btn btn-info" onclick="javascript:history.go(-1);" value="Voltar" <?php echo $lock->getLocked(); ?> />
    	</a>
    <?php endif; ?>
    
    <!--Dados hidenn-->
    <input class="form-control"type="hidden" name="nomeUnidade" value="<?php echo $nomeunidade;?>">
    <input class="form-control"type="hidden" name="codUnidade" value="<?php echo $codunidade; ?>">
    <input class="form-control"type="hidden" name="anoBase" value="<?php echo $anobase;?>">
    
</form>
<!-- value="<?php print $lab->getCabo() ?>;" -->
<script type="text/javascript"> 
</script>
