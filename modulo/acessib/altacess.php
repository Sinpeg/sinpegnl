<?php
//session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();

if (!$aplicacoes[10]){
	header("Location:index.php");
}else {
    require_once('dao/acessibilidadeDAO.php');
    require_once('classes/acessibilidade.php');
    require_once('dao/tpacessibilidadeDAO.php');
    require_once('classes/tpacessibilidade.php');
    $tiposea = array(); // tipo da estrutura de acessibilidade
    $ea = array(); // estrututura de acessibilidade
    $daotea = new TpacessibilidadeDAO();
    $daoea = new AcessibilidadeDAO();
    $lock = new Lock(); // trecho de bloqueio
    $cont = 0; // contador
    $quant = 0; // quantidade
    $soma = 0; // total
    $contsub = 0; // total de subunidades
    
    // parâmetro padrão é o ano de 2012
    $parametro = 2012;
    if ($anobase > 2012) {
        $parametro = 2013;
    }
    
    $rows_tea = $daotea->Lista($parametro);
    
    foreach ($rows_tea as $row) {
        $cont++;
        $tiposea[$cont] = new Tpacessibilidade();
        $tiposea[$cont]->setCodigo($row['Codigo']);
        $tiposea[$cont]->setNome($row['Nome']);
    }

    // primeiro caso
    // Se for subunidade 
    $codunidadecpga=NULL;
    $lock->setLocked(Utils::isApproved(10, $codunidadecpga, $codunidade, $anobase));//tirei //Diogo

    for ($j = 0; $j < count($array_codunidade); $j++) {
        $quant = 0; 
        // busca todas as estruturas de acessibilidade por código da unidade e ano
        $rows_ea = $daoea->buscaeaunidade($array_codunidade[$j], $anobase);
        // no conjunto resultado de cada unidade verifica para cada tipo  
        
        // Se é CPGA, o código de busca é de outra subunidade
        // e esta subunidade possui dados $rowCount()>0
        
        // Bloqueia edição
        if ($sessao->isUnidade() && $array_codunidade[$j]!=$codunidade && $rows_ea->rowCount()>0) {    
            $lock->setLocked(true); //tirei //Diogo
        }
        
        foreach ($rows_ea as $row) {
            for ($i = 1; $i <= count($tiposea); $i++) {
                // para um determinado tipo
                if ($tiposea[$i]->getCodigo() == $row["Tipo"]) {
                    if ($row["Ano"] >= 2013) {
                        // consolida o valor através do operador lógico OR
                        //$quant = (is_null($ea[$i])) ? ($row["Quantidade"] or 0) : (($ea[$i]->getQuantidade() or 0) or $row["Quantidade"]);
                    	$quant = (empty($ea[$i])) ? ($row["Quantidade"] or 0) : (($ea[$i]->getQuantidade() or 0) or $row["Quantidade"]);//Diogo
                    } else {
                        // consolida pelo valor
                        $quant = (is_null($ea[$i])) ? ($row["Quantidade"]) : ($ea[$i]->getQuantidade() + $row["Quantidade"]);
                    }
                    $acess = new Acessibilidade(); // Acessibilidade
                    $acess->setAno($row["Ano"]); // Ano
                    $acess->setTipo($tiposea[$i]->getCodigo()); // Tipo
                    $acess->setQuantidade($quant); // Quantidade
                    $soma += $acess->getQuantidade(); // total
                    $ea[$i] = $acess;
                }
            }
        }
        // fim
    }
}
///////////////////////////////////////////////////////////////
?>



<script type="text/javascript">
    function TABEnter(oEvent) {
        var oEvent = (oEvent) ? oEvent : event;
        var oTarget = (oEvent.target) ? oEvent.target : oEvent.srcElement;
        if (oEvent.keyCode == 13)
            oEvent.keyCode = 9;
        if (oTarget.type == "text" && oEvent.keyCode == 13)
            //return false;
            oEvent.keyCode = 9;
        if (oTarget.type == "radio" && oEvent.keyCode == 13)
            oEvent.keyCode = 9;
    }
    function Soma() {
        var soma = 0;
        qtde = new Array(document.ea.qtdArquitetonica.value, document.ea.qtdMobiliario.value,
                document.ea.qtdRampas.value, document.ea.qtdBanheiros.value, document.ea.qtdEquipamentos.value);
        for (var i = 0; i < qtde.length; i++) {
            if (!isNaN(parseInt(qtde[i]))) {
                soma += parseInt(qtde[i]);
            }
        }
        document.getElementById('totalgeral').innerHTML = soma;
    }
    function valida() {
        if (document.ea.qtdArquitetonica.value == "" ||
                document.ea.qtdMobiliario.value == "" ||
                document.ea.qtdRampas.value == "" ||
                document.ea.qtdBanheiros.value == "" ||
                document.ea.qtdEquipamentos.value == "") {
            document.getElementById('msg').innerHTML = "Todos os campos s&atilde;o obrigat&oacute;rios.";
            return false;
        }
        return true;
    }
    function SomenteNumero(e) {
        var tecla = (window.event) ? event.keyCode : e.which;
        //0 a 9 em ASCII
        if ((tecla > 47 && tecla < 58)) {
            document.getElementById('msg').innerHTML = " ";
            return true;
        }
        else {
            if (tecla == 8 || tecla == 0) {
                document.getElementById('msg').innerHTML = " ";
                return true;//Aceita tecla tab
            }
            else {
                document.getElementById('msg').innerHTML = "Todos os campos devem conter apenas n&uacute;meros.";
                return false;
            }
        }
    }
    function direciona(botao) {
        if (botao == 1) 
        {             
                document.ea.action = "?modulo=acessib&acao=opacess";
                document.ea.submit();   
        }
        else {
            document.iacess.action = "../saida/saida.php";
            document.iacess.submit();
        }
    }
</script>

<head>
	<div class="bs-example">
		<ul class="breadcrumb">
		    <li class="active">
                <a href="<?php echo Utils::createLink("acessib", "consultaacess"); ?>">Estrutura de acessibilidade</a>
                <i class="fas fa-long-arrow-alt-right"></i>
			    Alterar
            </li>
		</ul>
	</div>
</head>

<div class="card card-info">        
    <div class="card-header">
        <h3 class="card-title">Alterar estruturas de acessibilidade</h3>
    </div>
    <form class="form-horizontal" name="ea" id="ea" method="post">

        <div class="card-body">
            <?php if ($anobase <= 2012) { ?>
                <table id="tabelaAcess" class="table table-bordered table-hover">
                    <tr style="font-style:italic;">
                        <td>Itens</td>
                        <td>Quantidade</td>
                    </tr>
                    <tr>
                        <td>Arquitet&ocirc;nica</td>
                        <td>
                            <input <?php  echo $lock->getDisabled(); ?> type="text" name="qtdArquitetonica" maxlength="4"
                                size="5" onkeydown="TABEnter();"
                                value='<?php print $tiposea[1]->getAcessib()->getQuantidade(); ?>'
                                onchange="Soma();" onkeypress='return SomenteNumero(event)' />
                        </td>
                    </tr>
                    <tr>
                        <td>Banheiros Adaptados</td>
                        <td>
                            <input type="text" name="qtdBanheiros" size="5" maxlength="4" onkeydown="TABEnter();"
                                value='<?php print $tiposea[2]->getAcessib()->getQuantidade(); ?>'
                                onchange="Soma();" onkeypress='return SomenteNumero(event)' />
                        </td>
                    </tr>
                    <tr>
                        <td>Equipamentos Eletromec&acirc;nicos</td>
                        <td>
                            <input <?php echo $inputlock; ?> type="text" name="qtdEquipamentos" size="5" maxlength="4" onkeydown="TABEnter();" 
                            value='<?php print $tiposea[3]->getAcessib()->getQuantidade(); ?>' onchange="Soma();" onkeypress='return SomenteNumero(event)' />
                        </td>
                    </tr>
                    <tr>
                        <td>Mobili&aacute;rio Adaptado</td>
                        <td><input type="text" name="qtdMobiliario" size="5" maxlength="4" onkeydown="TABEnter();"
                                value='<?php print $tiposea[4]->getAcessib()->getQuantidade(); ?>'
                                onchange="Soma();" onkeypress='return SomenteNumero(event)' /><br />
                        </td>
                    </tr>
                    <tr>
                        <td>Rampas</td>
                        <td><input type="text" name="qtdRampas" size="5" maxlength="4" onkeydown="TABEnter();"
                                value='<?php print $tiposea[5]->getAcessib()->getQuantidade(); ?>'
                                onkeypress='return SomenteNumero(event)' onchange="Soma();" /><br />
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="font-style:italic;">Total Geral</td>
                        <td>
                            <b id='totalgeral'></b>
                        </td>
                    </tr>
                </table>
            <?php  }else {   ?>
                <table id="tabelaAcess" class="table table-bordered table-hover">
                    <thead>
                        <tr style="font-style:italic;">
                            <td>Itens</td>
                            <td>Possui</td>
                        </tr>
                    </thead>
                    
                    <tr>
                        <td>
                            Sinaliza&ccedil;&atilde;o t&aacute;til
                            <a href="#" class="help" data-trigger="hover" data-content='' title="Ajuda" ><span class="glyphicon glyphicon-question-sign"></span></a>
                        </td>
                        <td align="center">
                            <input <?php echo $lock->getDisabled(); ?> type="checkbox" class="form-check-input" name="qtdArquitetonica" <?php print ($ea[1]->getQuantidade()==1)?"checked":""; ?> />
                        </td>
                    </tr>
                    <tr>
                        <td>Rampas de acesso com corrim&atilde;o</td>
                        <td align="center">
                            <input <?php echo $lock->getDisabled(); ?> type="checkbox" class="form-check-input" name="qtdRampas" <?php print ($ea[2]->getQuantidade()==1)?"checked":""; ?> />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Entrada/Sa&iacute;da com dimensionamento
                            <a href="#" class="help" data-trigger="hover" data-content='' title='Entrada/saída dimensionadas que atendam as necessidades das pessoas com deficiências, de forma a garantir a acessibilidade aos espaços arquitetônicos, apresentando percursos livres de obstáculos. ' title="Sinalização que envolva o tato como meio de assimilar a mensagem. Podendo ser: caracteres em relevo, pelo sistema Braille; piso tátil localizado em área de circulação indicando o caminho a ser percorrido, sobretudo pelo deficiente visual; rebaixamentos de calçadas de portas de elevadores, faixas de travessia e pontos de ônibus; etc.'" ><span class="glyphicon glyphicon-question-sign"></span></a>
                        </td>
                        <td align="center">
                            <input <?php echo $lock->getDisabled(); ?> type="checkbox" class="form-check-input" name="qtdEs" <?php print ($ea[3]->getQuantidade()==1)?"checked":""; ?> />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Ambientes desobstru&iacute;dos - mov. de cadeirantes e pessoas com def. visual
                            <a href="#" class="help" data-trigger="hover" data-content='' title="Ambientes que favoreçam a locomoção da pessoa com deficiência ou com mobilidade reduzida, ampliando a autonomia pessoal, total ou assistida." ><span class="glyphicon glyphicon-question-sign"></span></a>
                        </td>
                        <td align="center">
                            <input <?php echo $lock->getDisabled(); ?> type="checkbox" class="form-check-input" name="qtdAd" <?php print ($ea[4]->getQuantidade()==1)?"checked":""; ?> />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Bebedouros e lavabos adaptados
                            <a href="#" class="help" data-trigger="hover" data-content='' title="Bebedouro e lavabos com leiautes adaptados (bica e torneira) para atender a quem utiliza cadeira de rodas, aparelhos ortopédicos, próteses e também a quem precisa de apoio." ><span class="glyphicon glyphicon-question-sign"></span></a>
                        </td>
                        <td align="center">
                            <input <?php echo $lock->getDisabled(); ?> type="checkbox" class="form-check-input" name="qtdBl" <?php print ($ea[5]->getQuantidade()==1)?"checked":""; ?> />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Sinaliza&ccedil;&atilde;o sonora
                            <a href="#" class="help" data-trigger="hover" data-content='' title="Sinalização realizada através de recursos auditivos, que a pessoa com deficiência recebe como forma de alerta." ><span class="glyphicon glyphicon-question-sign"></span></a>
                        </td>
                        <td align="center">
                            <input <?php echo $lock->getDisabled(); ?> type="checkbox" class="form-check-input" name="qtdSs" <?php print ($ea[6]->getQuantidade()==1)?"checked":""; ?> />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Sinaliza&ccedil;&atilde;o visual
                            <a href="#" class="help" data-trigger="hover" data-content='É aquela realizada através de textos ou figuras. As informações visuais seguem as premissas de textura, dimensionamento e contraste de cor dos textos para que sejam perceptíveis por pessoas de baixa visão. Está presente em pisos, corrimões, acessos às escadas, portas de banheiros, interior dos elevadores. ' title="Ajuda" ><span class="glyphicon glyphicon-question-sign"></span></a>
                        </td>
                        <td align="center">
                            <input <?php echo $lock->getDisabled(); ?> type="checkbox" class="form-check-input" name="qtdSv"  <?php print ($ea[7]->getQuantidade()==1)?"checked":""; ?> />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Equipamentos Eletromec&acirc;nicos
                            <a href="#" class="help" data-trigger="hover" data-content='Equipamentos eletromecânicos, tais como elevadores, esteiras rolantes e plataformas elevatórias, projetados para garantir acessibilidade às pessoas com deficiência ou mobilidade reduzida.' title="Ajuda" ><span class="glyphicon glyphicon-question-sign"></span></a>
                        </td>
                        <td align="center">
                            <input <?php echo $lock->getDisabled(); ?> type="checkbox" class="form-check-input" name="qtdEquipamentos" <?php print ($ea[8]->getQuantidade()==1)?"checked":""; ?> />
                        </td>
                    </tr>            
                    <tr>
                        <td>
                            Banheiros Adaptados
                            <a href="#" class="help" data-trigger="hover" data-content='Banheiros com leiautes adaptados (lavatórios, espelhos, barras de apoio, vasos, papeleiras, mictórios e área de transferência) para atender a quem utiliza cadeira de rodas, aparelhos ortopédicos, próteses e também a quem precisa de apoio. O espaço livre no banheiro deve ser suficiente para manobrar a cadeira de rodas. Devem ser facilmente acessados, ficando próximos das circulações principais e sinalizados.' title="Ajuda" ><span class="glyphicon glyphicon-question-sign"></span></a>
                        </td>
                        <td align="center">
                            <input <?php echo $lock->getDisabled(); ?> type="checkbox" class="form-check-input" name="qtdBanheiros" <?php print ($ea[9]->getQuantidade()==1)?"checked":""; ?> />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Atendimento (&aacute;rea ou balc&atilde;o) adaptados
                            <a href="#" class="help" data-trigger="hover" data-content='Espaço arquitetônico adaptado para atender usuários de cadeira de rodas, pessoas com mobilidade reduzida e/ou de baixa estatura. Os elementos de mobiliário deste local devem ser acessíveis, garantindo - se as áreas de aproximação e manobra e as faixas de alcance manual, visual e auditivo. Os pisos devem ter superfície regular, firme, estável e antiderrapante.' title="Ajuda" ><span class="glyphicon glyphicon-question-sign"></span></a>
                        </td>
                        <td align="center">
                            <input <?php echo $lock->getDisabled(); ?> type="checkbox" class="form-check-input" name="qtdAt" <?php print ($ea[10]->getQuantidade()==1)?"checked":""; ?> />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Mobili&aacute;rio Adaptado
                            <a href="#" class="help" data-trigger="hover" data-content='Mobiliários com leiautes adaptados (telefones, mesas ou superfícies para refeições ou trabalho, balcões, entre outros) para atender a quem utiliza cadeira de rodas, aparelhos ortopédicos, próteses e também a quem precisa de apoio. ' title="Ajuda" ><span class="glyphicon glyphicon-question-sign"></span></a>
                        </td>
                        <td align="center">
                            <input <?php echo $lock->getDisabled(); ?> type="checkbox" class="form-check-input" name="qtdMobiliario"  <?php print ($ea[11]->getQuantidade()==1)?"checked":""; ?> />
                        </td>
                    </tr>
                </table>
            <?php } ///////////////////////////////////////////////////////////////// ?> 
        </div>
        <?php if (!$lock->getDisabled()) { ?>
            <table class="card-body">
                <tr>
                    <td align="center" colspan="2">
                        <br>
                        <input name="operacao" type="hidden" value="A" />
                        <input type="button" onclick="direciona(1);" id="gravar" value="Gravar" class="btn btn-primary"/>
                    </td>
                </tr>
            </table>
        <?php  } ?>
    </form>
</div>

<script>
 $(function () {
    $('#tabelaAcess').DataTable({
      "paging": true,
      "sort": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "responsive": true,
    });
});
</script>