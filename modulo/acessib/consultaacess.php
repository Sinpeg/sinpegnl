<?php
//session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();

if (!$aplicacoes[10]){
		header("Location:index.php");
}else{
	
    require_once('dao/acessibilidadeDAO.php');
    require_once('classes/acessibilidade.php');
    require_once('dao/tpacessibilidadeDAO.php');
    require_once('classes/tpacessibilidade.php');
    
    
    $tiposea = array(); // tipo da estrutura de acessibilidade
    $ea = array(); // estrututura de acessibilidade
    $daotea = new TpacessibilidadeDAO();
    $daoea = new AcessibilidadeDAO();
    $cont = 0; // contador
    $quant = 0; // quantidade
    $soma = 0; // total
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
    for ($j = 0; $j < count($array_codunidade); $j++) {
        $quant = 0;
        // busca todas as estruturas de acessibilidade por código da unidade e ano
        $rows_ea = $daoea->buscaeaunidade($array_codunidade[$j], $anobase);
        
        // no conjunto resultado de cada unidade verifica para cada tipo    
        foreach ($rows_ea as $row) {
            for ($i = 1; $i <= count($tiposea); $i++) {
                // para um determinado tipo
                if ($tiposea[$i]->getCodigo() == $row["Tipo"]) {
                    if ($row["Ano"] >= 2013) {
                    	//echo $ea[$i];
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
    }
}

if (count($ea)==0) {
   Utils::redirect('acessib', 'incluiacess');
}
 
?>

<script type="text/javascript">
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
    function direciona(botao) {
        if (botao == 1) {
            document.ea.action = "?modulo=acessib&acao=altacess";
            document.ea.submit();
        }
        else {
            document.ea.action = "../saida/saida.php";
            document.ea.submit();
        }
    }
</script>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active">Estrutura de acessibilidade</li>
		</ul>
	</div>
</head>
<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Estruturas de Acessibilidade</h3>
    </div>
    <form class="form-horizontal" name="ea" id="ea" method="post">
        <div class="card-body">
            <table class="table table-bordered table-hover" id="tabelaAcess">
                <thead>
                    <tr align="left" style="font-style:italic; font-weight:bold;">
                        <td>Itens</td>
                        <?php if ($parametro <= 2012): ?>
                            <td>Quantidade</td>
                        <?php else: ?>
                            <td>Possui item</td>
                        <?php endif ?>
                    </tr>
                </thead>
                <?php for ($i = 1; $i <= count($tiposea); $i++) { ?>
                    <tr>
                        <td> <?php print ($tiposea[$i]->getNome()." "); 
                        switch($tiposea[$i]->getNome()){
                        case "Sinalização Tátil":
                            echo "<a href='#' class='help' data-trigger='hover' data-content='Sinalização que envolva o tato como meio de assimilar a mensagem. Podendo ser: caracteres em relevo, pelo sistema Braille; piso tátil localizado em área de circulação indicando o caminho a ser percorrido, sobretudo pelo deficiente visual; rebaixamentos de calçadas de portas de elevadores, faixas de travessia e pontos de ônibus; etc.' title='Sinalização que envolva o tato como meio de assimilar a mensagem. Podendo ser: caracteres em relevo, pelo sistema Braille; piso tátil localizado em área de circulação indicando o caminho a ser percorrido, sobretudo pelo deficiente visual; rebaixamentos de calçadas de portas de elevadores, faixas de travessia e pontos de ônibus; etc.' ><span class='glyphicon glyphicon-question-sign'></span></a>";
                            break;
                        
                        case "Entrada/saída  com dimensionamento":
                            echo "<a href='#' class='help' data-trigger='hover' data-content='Entrada/saída dimensionadas que atendam as necessidades das pessoas com deficiências, de forma a garantir a acessibilidade aos espaços arquitetônicos, apresentando percursos livres de obstáculos. ' title='Entrada/saída dimensionadas que atendam as necessidades das pessoas com deficiências, de forma a garantir a acessibilidade aos espaços arquitetônicos, apresentando percursos livres de obstáculos.' ><span class='glyphicon glyphicon-question-sign'></span></a>";
                            break;
                        
                        case "Ambientes desobstruídos-mov. cadeirante e def. visua":
                            echo "<a href='#' class='help' data-trigger='hover' data-content='Ambientes que favoreçam a locomoção da pessoa com deficiência ou com mobilidade reduzida, ampliando a autonomia pessoal, total ou assistida.' title='Ambientes que favoreçam a locomoção da pessoa com deficiência ou com mobilidade reduzida, ampliando a autonomia pessoal, total ou assistida.' ><span class='glyphicon glyphicon-question-sign'></span></a>";
                            break;
                        
                        case "Bebedouros e lavabos adaptados":
                            echo "<a href='#' class='help' data-trigger='hover' data-content='Bebedouro e lavabos com leiautes adaptados (bica e torneira) para atender a quem utiliza cadeira de rodas, aparelhos ortopédicos, próteses e também a quem precisa de apoio.' title='Bebedouro e lavabos com leiautes adaptados (bica e torneira) para atender a quem utiliza cadeira de rodas, aparelhos ortopédicos, próteses e também a quem precisa de apoio.' ><span class='glyphicon glyphicon-question-sign'></span></a>";
                            break;
                        
                        case "Sinalização Sonora":
                            echo "<a href='#' class='help' data-trigger='hover' data-content='Sinalização realizada através de recursos auditivos, que a pessoa com deficiência recebe como forma de alerta.' title='Sinalização realizada através de recursos auditivos, que a pessoa com deficiência recebe como forma de alerta.' ><span class='glyphicon glyphicon-question-sign'></span></a>";
                            break;
                        
                        case "Sinalização Visual":
                            echo "<a href='#' class='help' data-trigger='hover' data-content='É aquela realizada através de textos ou figuras. As informações visuais seguem as premissas de textura, dimensionamento e contraste de cor dos textos para que sejam perceptíveis por pessoas de baixa visão. Está presente em pisos, corrimões, acessos às escadas, portas de banheiros, interior dos elevadores. ' title='É aquela realizada através de textos ou figuras. As informações visuais seguem as premissas de textura, dimensionamento e contraste de cor dos textos para que sejam perceptíveis por pessoas de baixa visão. Está presente em pisos, corrimões, acessos às escadas, portas de banheiros, interior dos elevadores.' ><span class='glyphicon glyphicon-question-sign'></span></a>";
                            break;
                        
                        case "Banheiros adaptados":
                            echo "<a href='#' class='help' data-trigger='hover' data-content='Banheiros com leiautes adaptados (lavatórios, espelhos, barras de apoio, vasos, papeleiras, mictórios e área de transferência) para atender a quem utiliza cadeira de rodas, aparelhos ortopédicos, próteses e também a quem precisa de apoio. O espaço livre no banheiro deve ser suficiente para manobrar a cadeira de rodas. Devem ser facilmente acessados, ficando próximos das circulações principais e sinalizados.' title='Banheiros com leiautes adaptados (lavatórios, espelhos, barras de apoio, vasos, papeleiras, mictórios e área de transferência) para atender a quem utiliza cadeira de rodas, aparelhos ortopédicos, próteses e também a quem precisa de apoio. O espaço livre no banheiro deve ser suficiente para manobrar a cadeira de rodas. Devem ser facilmente acessados, ficando próximos das circulações principais e sinalizados.' ><span class='glyphicon glyphicon-question-sign'></span></a>";
                            break;

                        case "Atendimento (área ou balcão) adaptados":
                            echo "<a href='#' class='help' data-trigger='hover' data-content='Espaço arquitetônico adaptado para atender usuários de cadeira de rodas, pessoas com mobilidade reduzida e/ou de baixa estatura. Os elementos de mobiliário deste local devem ser acessíveis, garantindo - se as áreas de aproximação e manobra e as faixas de alcance manual, visual e auditivo. Os pisos devem ter superfície regular, firme, estável e antiderrapante.' title='Espaço arquitetônico adaptado para atender usuários de cadeira de rodas, pessoas com mobilidade reduzida e/ou de baixa estatura. Os elementos de mobiliário deste local devem ser acessíveis, garantindo - se as áreas de aproximação e manobra e as faixas de alcance manual, visual e auditivo. Os pisos devem ter superfície regular, firme, estável e antiderrapante.' ><span class='glyphicon glyphicon-question-sign'></span></a>";
                            break;
                        
                        case "Mobiliário adaptado":
                            echo "<a href='#' class='help' data-trigger='hover' data-content='Mobiliários com leiautes adaptados (telefones, mesas ou superfícies para refeições ou trabalho, balcões, entre outros) para atender a quem utiliza cadeira de rodas, aparelhos ortopédicos, próteses e também a quem precisa de apoio. ' title='Mobiliários com leiautes adaptados (telefones, mesas ou superfícies para refeições ou trabalho, balcões, entre outros) para atender a quem utiliza cadeira de rodas, aparelhos ortopédicos, próteses e também a quem precisa de apoio.' ><span class='glyphicon glyphicon-question-sign'></span></a>";
                            break;

                        case "Equipamentos eletromecânicos":
                            echo "<a href='#' class='help' data-trigger='hover' data-content='Equipamentos eletromecânicos, tais como elevadores, esteiras rolantes e plataformas elevatórias, projetados para garantir acessibilidade às pessoas com deficiência ou mobilidade reduzida.' title='Equipamentos eletromecânicos, tais como elevadores, esteiras rolantes e plataformas elevatórias, projetados para garantir acessibilidade às pessoas com deficiência ou mobilidade reduzida.' ><span class='glyphicon glyphicon-question-sign'></span></a>";
                            break;
                        }
                        ?></td>
                        <td align="left"> 
                            <?php
                            if ($parametro <= 2012) {
                                print $ea[$i]->getQuantidade();
                            } else {
                                print $ea[$i]->getQuantidade() == "1" ? "Sim" : "Não";
                            }
                        }
                        
                        ?></td>
                </tr>
                <?php
                if ($anobase <= 2012) {
                    ?>
                    <tr style="font-style:italic;"><td align="center" >Total geral</td><td align="center"><?php print $soma; ?></td></tr>
                <?php }
                ?>
            </table>
            <table class="card-body">
                <tr>
                    <td align="center" colspan="2">
                        <br /> <input type="button" onclick='direciona(1);' id="gravar" value="Alterar" class="btn btn-info"/>
                    </div>
                </tr>
            </table>
        </div>
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