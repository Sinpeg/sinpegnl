<?php
if (!$aplicacoes[9]) {
    header("Location:index.php");
} else {
    require_once('dao/infraensinoDAO.php');
    require_once('classes/infraensino.php');
    require_once('dao/tipoinfraensinoDAO.php');
    require_once('classes/tipoinfraensino.php');
    $tiposie = array(); // tipo associado a Infraestrutura de Ensino
    $cont = 0;
    $daoie = new InfraensinoDAO();
    $daotie = new TipoinfraensinoDAO();
    $rows_tie = $daotie->Lista();
    
    $lock = new Lock();
    
     // primeiro caso
    // Se for subunidade 
    if (!$sessao->isUnidade()) {
     $lock->setLocked(Utils::isApproved(9, $codunidadecpga, $codunidade, $anobase));
    }
    $ie_array = array(); // array de infraestrutura de ensino
    // itera entre todas os tipos de infraestrutura de ensino
    foreach ($rows_tie as $row) {
        $cont++;
        $tiposie[$cont] = new Tipoinfraensino();
        $tiposie[$cont]->setCodigo($row['Codigo']);
        $tiposie[$cont]->setNome($row['Nome']);
    }
    // fim 
    $cont1 = 0;
    $soma = 0; // soma das quantidades das infraestrutura de ensino
    $tamanho = count($tiposie);
    for ($j = 0; $j < count($array_codunidade); $j++) {
        $rows_ie = $daoie->buscaieunidade($array_codunidade[$j], $anobase);
         
         // Se é CPGA, o código de busca é de outra subunidade
        // e esta subunidade possui dados $rowCount()>0
        // Bloqueia edição
        if ($sessao->isUnidade() && $array_codunidade[$j]!=$codunidade && $rows_ie->rowCount()>0) {    
           $lock->setLocked(true);
        }
        
        foreach ($rows_ie as $row) {
            $tipo = $row['Tipo'];
            for ($i = 1; $i <= $tamanho; $i++) {
                if ($tiposie[$i]->getCodigo() == $tipo) {
                    $cont1++;
                    $quant = (is_null($ie_array[$i]))?$row["Quantidade"]:$ie_array[$i]->getQuantidade()+$row["Quantidade"];
                    $ie = new Infraensino();
                    $ie->setQuantidade($quant);
                    $ie->setAno($anobase);
                    $ie->setTipo($tiposie[$i]);
                    $ie_array[$i] = $ie;
                    $soma += $row["Quantidade"];
                }
            }
        }
    }
}
$daoie->fechar();
if (count($tiposie) == 0) {
    Utils::redirect('infraensino', 'incluiinfraensino');
}
?>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li><a href="<?php echo Utils::createLink("infraensino", "incluiinfraensino"); ?>">Infraestrutura de ensino na unidade</a></li>
			<li><a href="<?php echo Utils::createLink("infraensino", "consultainfraensino") ?>">Consulta</a></li>
			<li class="active">Altera</li>
		</ul>
	</div>
</head>
<form class="form-horizontal" name="gravar" id="gravar" method="post">
    <h3 class="card-title"> Infraestrutura de Ensino na Unidade </h3>
    <div class="msg" id="msg"></div>
    <table>
        <tr>
            <th>Itens</th>
            <th>Quantidade</th>
        </tr>
        <tr>
            <td>Aparelho de DVD</td>
            <td><input class="form-control"<?php echo $lock->getDisabled(); ?> type="text" name="qtdDVD" size="5" onkeydown="TABEnter();" maxlength="6"
                       value='<?php print $ie_array[1]->getQuantidade(); ?>'
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Equipamento de &Aacute;udio</td>
            <td><input class="form-control"<?php echo $lock->getDisabled(); ?> type="text" name="qtdAudio" size="5" onkeydown="TABEnter();" maxlength="6"
                       value='<?php print  $ie_array[2]->getQuantidade(); ?>'
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Equipamento de Climatiza&ccedil;&atilde;o-Ar,Central de Ar,etc.</td>
            <td><input class="form-control"<?php echo $lock->getDisabled(); ?> type="text" name="qtdAr" size="5" onkeydown="TABEnter();" maxlength="6"
                       value='<?php  print $ie_array[3]->getQuantidade(); ?>'
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Equipamentos de Computa&ccedil;&atilde;o</td>
            <td><input class="form-control"<?php echo $lock->getDisabled(); ?> type="text" name="qtdPC" size="5" onkeydown="TABEnter();" maxlength="6"
                       value='<?php print $ie_array[4]->getQuantidade(); ?>'
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' />
            </td>
        </tr>
        <tr>
            <td>Equipamentos de Videoconferência/Teleconferência</td>
            <td><input class="form-control"<?php echo $lock->getDisabled(); ?> type="text" name="qtdVideoconferencia" size="5" onkeydown="TABEnter();" maxlength="6"
                       value='<?php print $ie_array[5]->getQuantidade(); ?>'
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Equipamentos Específicos-Microsc&oacute;pio, Roteador, etc.</td>
            <td><input class="form-control"<?php echo $lock->getDisabled(); ?> type="text" name="qtdEspecificos" size="5" onkeydown="TABEnter();" maxlength="6"
                       value='<?php print $ie_array[6]->getQuantidade(); ?>'
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Equipamentos Eletrônico-Inform&Aacute;ticos</td>
            <td><input class="form-control"<?php echo $lock->getDisabled(); ?> type="text" name="qtdEletronico" size="5" onkeydown="TABEnter();" maxlength="6"
                       value='<?php print $ie_array[7]->getQuantidade(); ?>'
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>M&oacute;veis Altamente Relevantes</td>
            <td><input class="form-control"<?php echo $lock->getDisabled(); ?> type="text" name="qtdMoveis" size="5" onkeydown="TABEnter();" maxlength="6"
                       value='<?php print $ie_array[8]->getQuantidade(); ?>'
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Outros Equipamentos Relevantes</td>
            <td><input class="form-control"<?php echo $lock->getDisabled(); ?> type="text" name="qtdOutrosequipamentos" size="5" maxlength="6"
                       onchange="Soma();" onkeydown="TABEnter();"
                       value='<?php print $ie_array[9]->getQuantidade(); ?>'
                       onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Projetor Multimidia-Data Show, Projetores,etc.</td>
            <td><input class="form-control"<?php echo $lock->getDisabled(); ?> type="text" name="qtdProjetores" size="5" onkeydown="TABEnter();" maxlength="6"
                       value='<?php print $ie_array[10]->getQuantidade(); ?>'
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Retroprojetor e Televis&atilde;o</td>
            <td><input class="form-control"<?php echo $lock->getDisabled(); ?> type="text" name="qtdTV" size="5" onkeydown="TABEnter();" maxlength="6"
                       value='<?php print $ie_array[11]->getQuantidade(); ?>'
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Inova&ccedil;&otilde;es Tecnol&oacute;gicas Significativas</td>
            <td><input class="form-control"<?php echo $lock->getDisabled(); ?> type="text" name="qtdInovacoes" onchange="Soma();"
                       size="5" onkeydown="TABEnter();" maxlength="6"
                       value='<?php print $ie_array[12]->getQuantidade(); ?>'
                       onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr style="font-style:italic;">
            <td>Total geral</td>
            <td><b id='totalgeral'></b></td>
        </tr>
    </table>
    <?php if(!$lock->getLocked()): ?>
    <br/> <input class="form-control"name="operacao" type="hidden" value="I"/>
    <input type="button" onclick='direciona(1);' value="Gravar" class="btn btn-info"/>
    <?php endif; ?>
</form>
<script>
    window.onload = Soma();
</script>
