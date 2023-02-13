<?php
ob_start();
//session_start();
//session_start();
$sessao = $_SESSION["sessao"];

$aplicacoes = $sessao->getAplicacoes();
 
    if (!$aplicacoes[10])
    {
		header("Location:index.php");
    }else{
    require_once('dao/acessibilidadeDAO.php');
    require_once('classes/acessibilidade.php');
    require_once('dao/tpacessibilidadeDAO.php');
    require_once('classes/tpacessibilidade.php');
   
    
    $unidade = new Unidade();
    $unidade->setCodunidade($codunidade);
    $unidade->setNomeunidade($nomeunidade);
    $tiposea = array();
    $daotea = new TpacessibilidadeDAO();
    $daoea = new AcessibilidadeDAO();
    $cont = 0;
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
    $tamanho = count($tiposea);
    $cont1 = 0;
    $rows_ea = $daoea->buscaeaunidade($codunidade, $anobase);
    foreach ($rows_ea as $row) {
        $tipo = $row['Tipo'];
        for ($i = 1; $i <= $tamanho; $i++) {
            echo $tiposea[$i]->getCodigo();
            if ($tiposea[$i]->getCodigo() == $tipo) {
                $cont1++;
                $tiposea[$i]->criaAcessib($row["CodigoEstrutura"], $unidade, $anobase, $row["Quantidade"]);
            }
        }
    }
   
    $daoea->fechar();
    if ($cont1 > 0) {
        Utils::redirect("acessib", "consultaacess");
    }
?>

<script language='JavaScript'>
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
        var passou = false;
        if ((document.ea.qtdArquitetonica.value == "")
                || (document.ea.qtdMobiliario.value == "")
                || (document.ea.qtdRampas.value == "")
                || (document.ea.qtdBanheiros.value == "")
                || (document.ea.qtdEquipamentos.value == "")) {
            document.getElementById('msg').innerHTML = "Todos os campos são obrigat&oacute;rios.";
            passou = true;
        }
        if (passou) {
            return false;
        }
        else {
            return true;
        }
    }
    function direciona(botao) {
        switch (botao) {
            case 1:
                if (valida()) {
                    document.ea.action = "?modulo=acessib&acao=opacess";
                    document.ea.submit();
                }
                break;
            case 2:
                document.ea.action = "../saida/saida.php";
                document.ea.submit();
                break;
        }
    }
</script>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
            <li class="active">
                <a href="<?php echo Utils::createLink("acessib", "consultaacess"); ?>">Estrutura de acessibilidade</a>                
                <i class="fas fa-long-arrow-alt-right"></i>
                Incluir
            </li>
		</ul>
	</div>
</head>
<div class="ui-widget">
    <div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;">
        <p>
            <span class="ui-icon ui-icon-alert" 
                  style="float: left; margin-right: .3em;"></span>
            <strong>Importante!</strong>
        </p>
        <p>Marque a opção somente se a unidade possui o respectivo item.</p>
    </div>
</div>

<div class="card card-info">
    <div class="card-header"><h3 class="card-title">Estruturas de Acessibilidade</h3></div>
    <form class="form-horizontal" name="ea" id="ea" method="post">
        
        <div class="msg" id="msg"></div>
        <?php
        if ($anobase <= 2012) {
            ?>
            <table width="400px" id="tabela">
                <tr style="font-style:italic;">
                    <td>Itens</td>
                    <td>Quantidade</td>
                </tr>
                <tr>
                    <td>Arquitet&ocirc;nica</td>
                    <td><input class="form-control"type="text" name="qtdArquitetonica" size="5" value='' maxlength="4"
                            onchange="Soma();" onkeypress='return SomenteNumero(event)' onkeydown="TABEnter();" />
                    </td>
                </tr>
                <tr>
                    <td>Mobili&aacute;rio Adaptado</td>
                    <td><input class="form-control"type="text" name="qtdMobiliario" size="5" value='' maxlength="4"
                            onchange="Soma();" onkeypress='return SomenteNumero(event)' onkeydown="TABEnter();" /></td>
                </tr>
                <tr>
                    <td>Rampas</td>
                    <td><input class="form-control"type="text" name="qtdRampas" size="5" value='' maxlength="4"
                            onchange="Soma();" onkeypress='return SomenteNumero(event)' onkeydown="TABEnter();"/></td>
                </tr>
                <tr>
                    <td>Banheiros Adaptados</td>
                    <td><input class="form-control"type="text" name="qtdBanheiros" size="5" maxlength="4"
                            onchange="Soma();" value='' onkeydown="TABEnter();"
                            onkeypress='return SomenteNumero(event)' />
                    </td>
                </tr>
                <tr>
                    <td>Equipamentos Eletromec&acirc;nicos</td>
                    <td><input class="form-control"type="text" name="qtdEquipamentos" onchange="Soma();" maxlength="4" onkeydown="TABEnter();"
                            size="5" value='' onkeypress='return SomenteNumero(event)' />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr style="font-style:italic;">
                    <td>Total Geral</td>
                    <td><b id='totalgeral'></b>
                    </td>
                </tr>
            </table>
            <?php
        } else {
            ?>
            <table width="400px" id="tabela">
                <tr style="font-style:italic;">
                    <td>Itens</td>
                    <td>Possui</td>
                </tr>
                <tr>
                    <td>Sinaliza&ccedil;&atilde;o t&aacute;til
                    <a href="#" class="help" data-trigger="hover" data-content='Sinalização que envolva o tato como meio de assimilar a mensagem. Podendo ser: caracteres em relevo, pelo sistema Braille; piso tátil localizado em área de circulação indicando o caminho a ser percorrido, sobretudo pelo deficiente visual; rebaixamentos de calçadas de portas de elevadores, faixas de travessia e pontos de ônibus; etc.' title="Sinalização que envolva o tato como meio de assimilar a mensagem. Podendo ser: caracteres em relevo, pelo sistema Braille; piso tátil localizado em área de circulação indicando o caminho a ser percorrido, sobretudo pelo deficiente visual; rebaixamentos de calçadas de portas de elevadores, faixas de travessia e pontos de ônibus; etc." ><span class="glyphicon glyphicon-question-sign"></span></a></td>
                    <td><input type="checkbox" class="form-check-input" name="qtdArquitetonica" />
                    </td>
                </tr>
                <tr>
                    <td>Rampas de acesso com corrim&atilde;o</td>
                    <td><input type="checkbox" class="form-check-input" name="qtdRampas"/></td>
                </tr>
                <tr>
                    <td>Entrada/Sa&iacute;da com dimensionamento
                    <a href="#" class="help" data-trigger="hover" data-content='Entrada/saída dimensionadas que atendam as necessidades das pessoas com deficiências, de forma a garantir a acessibilidade aos espaços arquitetônicos, apresentando percursos livres de obstáculos. ' title="Entrada/saída dimensionadas que atendam as necessidades das pessoas com deficiências, de forma a garantir a acessibilidade aos espaços arquitetônicos, apresentando percursos livres de obstáculos." ><span class="glyphicon glyphicon-question-sign"></span></a></td>
                    <td><input type="checkbox" class="form-check-input" name="qtdEs" />
                    </td>
                </tr>
                <tr>
                    <td>Ambientes desobstru&iacute;dos - mov. de cadeirantes e pessoas com def. visual
                    <a href="#" class="help" data-trigger="hover" data-content='Ambientes que favoreçam a locomoção da pessoa com deficiência ou com mobilidade reduzida, ampliando a autonomia pessoal, total ou assistida.' title="Ambientes que favoreçam a locomoção da pessoa com deficiência ou com mobilidade reduzida, ampliando a autonomia pessoal, total ou assistida." ><span class="glyphicon glyphicon-question-sign"></span></a></td>
                    <td><input type="checkbox" class="form-check-input" name="qtdAd" />
                    </td>
                </tr>
                <tr>
                    <td>Bebedouros e lavabos adaptados
                    <a href="#" class="help" data-trigger="hover" data-content='Bebedouro e lavabos com leiautes adaptados (bica e torneira) para atender a quem utiliza cadeira de rodas, aparelhos ortopédicos, próteses e também a quem precisa de apoio.' title="Bebedouro e lavabos com leiautes adaptados (bica e torneira) para atender a quem utiliza cadeira de rodas, aparelhos ortopédicos, próteses e também a quem precisa de apoio." ><span class="glyphicon glyphicon-question-sign"></span></a></td>
                    <td><input type="checkbox" class="form-check-input" name="qtdBl" />
                    </td>
                </tr>
                <tr>
                    <td>Sinaliza&ccedil;&atilde;o sonora
                    <a href="#" class="help" data-trigger="hover" data-content='Sinalização realizada através de recursos auditivos, que a pessoa com deficiência recebe como forma de alerta.' title="Sinalização realizada através de recursos auditivos, que a pessoa com deficiência recebe como forma de alerta." ><span class="glyphicon glyphicon-question-sign"></span></a></td>
                    <td><input type="checkbox" class="form-check-input" name="qtdSs"/>
                    </td>
                </tr>
                <tr>
                    <td>Sinaliza&ccedil;&atilde;o visual
                    <a href="#" class="help" data-trigger="hover" data-content='É aquela realizada através de textos ou figuras. As informações visuais seguem as premissas de textura, dimensionamento e contraste de cor dos textos para que sejam perceptíveis por pessoas de baixa visão. Está presente em pisos, corrimões, acessos às escadas, portas de banheiros, interior dos elevadores. ' title="É aquela realizada através de textos ou figuras. As informações visuais seguem as premissas de textura, dimensionamento e contraste de cor dos textos para que sejam perceptíveis por pessoas de baixa visão. Está presente em pisos, corrimões, acessos às escadas, portas de banheiros, interior dos elevadores." ><span class="glyphicon glyphicon-question-sign"></span></a></td>
                    <td><input type="checkbox" class="form-check-input" name="qtdSv" />
                    </td>
                </tr>
                <tr>
                    <td>Equipamentos eletromecânicos
                    <a href="#" class="help" data-trigger="hover" data-content='Equipamentos eletromecânicos, tais como elevadores, esteiras rolantes e plataformas elevatórias, projetados para garantir acessibilidade às pessoas com deficiência ou mobilidade reduzida.' title="Equipamentos eletromecânicos, tais como elevadores, esteiras rolantes e plataformas elevatórias, projetados para garantir acessibilidade às pessoas com deficiência ou mobilidade reduzida." ><span class="glyphicon glyphicon-question-sign"></span></a></td>
                    <td><input type="checkbox" class="form-check-input" name="qtdEquipamentos" />
                    </td>
                </tr>          
                <tr>
                    <td>Banheiros Adaptados
                    <a href="#" class="help" data-trigger="hover" data-content='Banheiros com leiautes adaptados (lavatórios, espelhos, barras de apoio, vasos, papeleiras, mictórios e área de transferência) para atender a quem utiliza cadeira de rodas, aparelhos ortopédicos, próteses e também a quem precisa de apoio. O espaço livre no banheiro deve ser suficiente para manobrar a cadeira de rodas. Devem ser facilmente acessados, ficando próximos das circulações principais e sinalizados.' title="Banheiros com leiautes adaptados (lavatórios, espelhos, barras de apoio, vasos, papeleiras, mictórios e área de transferência) para atender a quem utiliza cadeira de rodas, aparelhos ortopédicos, próteses e também a quem precisa de apoio. O espaço livre no banheiro deve ser suficiente para manobrar a cadeira de rodas. Devem ser facilmente acessados, ficando próximos das circulações principais e sinalizados." ><span class="glyphicon glyphicon-question-sign"></span></a></td>
                    <td><input type="checkbox" class="form-check-input" name="qtdBanheiros"/>
                    </td>
                </tr>
                <tr>
                    <td>Atendimento (&aacute;rea ou balc&atilde;o) adaptados
                    <a href="#" class="help" data-trigger="hover" data-content='Espaço arquitetônico adaptado para atender usuários de cadeira de rodas, pessoas com mobilidade reduzida e/ou de baixa estatura. Os elementos de mobiliário deste local devem ser acessíveis, garantindo - se as áreas de aproximação e manobra e as faixas de alcance manual, visual e auditivo. Os pisos devem ter superfície regular, firme, estável e antiderrapante.' title="Espaço arquitetônico adaptado para atender usuários de cadeira de rodas, pessoas com mobilidade reduzida e/ou de baixa estatura. Os elementos de mobiliário deste local devem ser acessíveis, garantindo - se as áreas de aproximação e manobra e as faixas de alcance manual, visual e auditivo. Os pisos devem ter superfície regular, firme, estável e antiderrapante." ><span class="glyphicon glyphicon-question-sign"></span></a></td>
                    <td><input type="checkbox" class="form-check-input" name="qtdAt" />
                    </td>
                </tr>
                <tr>
                    <td>Mobili&aacute;rio Adaptado
                    <a href="#" class="help" data-trigger="hover" data-content='Mobiliários com leiautes adaptados (telefones, mesas ou superfícies para refeições ou trabalho, balcões, entre outros) para atender a quem utiliza cadeira de rodas, aparelhos ortopédicos, próteses e também a quem precisa de apoio. ' title="Mobiliários com leiautes adaptados (telefones, mesas ou superfícies para refeições ou trabalho, balcões, entre outros) para atender a quem utiliza cadeira de rodas, aparelhos ortopédicos, próteses e também a quem precisa de apoio." ><span class="glyphicon glyphicon-question-sign"></span></a></td>
                    <td><input type="checkbox" class="form-check-input" name="qtdMobiliario" /></td>
                </tr>               
                <tr>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <?php
        }
        ?>
        <table>
            <tr>
                <td align="center" colspan="2">
                    <br>
                    <input class="form-control"name="operacao" type="hidden" value="I" />
                    <input type="button" onclick="direciona(1);" id="gravar" value="Gravar" class="btn btn-info" />
                </td>
            </tr>
        </table>
    </form>
</div>
<?php }  ?>