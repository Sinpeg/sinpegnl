<?php
session_start();
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[30]) {
    header("Location:index.php");
}  else {
    $sessao = $_SESSION["sessao"];
    $codunidade = $sessao->getCodunidade();
    $anobase = $sessao->getAnobase();
    require_once('dao/incubadoraDAO.php');
    require_once('classes/incubadora.php');
    $projextensao = array();
    $cont = 0;
    $daope = new IncubadoraDAO();
    $incubadora = new Incubadora();
    $rows_pe = $daope->buscainc($anobase);
    foreach ($rows_pe as $row) {
        if ($row['CodUnidade'] == $codunidade) {
            $incubadora->setCodigo($row['Codigo']);
            $incubadora->setEmpresasgrad($row['empresasgrad']);
            $incubadora->setEmpgerados($row['empgerados']);
            $incubadora->setProjaprovados($row['projaprovados']);
            $incubadora->setEventos($row['eventos']);
            $incubadora->setCapacitrh($row['capacitrh']);
            $incubadora->setNempreendedores($row['nempreendedores']);
            $incubadora->setConsultorias($row['consultorias']);
            $incubadora->setPartempfeiras($row['partempfeiras']);
            $incubadora->setAno($row['Ano']);
        }
    }
    $daope->fechar();
}
//ob_end_flush();
?>

<script language='javascript'>
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
    function Soma1() {
        var soma = 0;
        qtde = new Array(document.pe.qtdEmpg.value, document.pe.qtdEmpgs.value,
                document.pe.qtdProja.value, document.pe.qtdEmpcap.value, document.pe.qtdCap.value,
                document.pe.qtdCons.value, document.pe.qtdFei.value);
        for (var i = 0; i < qtde.length; i++) {
            if (!isNaN(parseInt(qtde[i]))) {
                soma += parseInt(qtde[i]);
            }
        }

        document.getElementById('totalgeral1').innerHTML = soma;
    }
    function direciona(botao) {
        switch (botao) {
            case 1:
                if (valida()) {
                    document.getElementById('pe').action = "?modulo=incubadora&acao=opincub";
                    document.getElementById('pe').submit();
                }
                break;
            case 2:
                document.getElementById('pe').action = "../saida/saida.php";
                document.getElementById('pe').submit();
                break;
        }

    }
    function valida() {
        var passou = false;
        if ((document.pe.qtdEmpg.value == "") || (document.pe.qtdEmpgs.value == "")
                || (document.pe.qtdProja.value == "") || (document.pe.qtdEmpcap.value == "")
                || (document.pe.qtdCap.value == "") || (document.pe.qtdCons.value == "") || (document.pe.qtdFei.value == "")) {
            document.getElementById('msg').innerHTML = "As quantidades s&atilde;o obrigat&oacute;rias.";
            passou = true;
        }
        if (passou) {
            return false;
        }
        else {
            return true;
        }
    }
</script>
<form class="form-horizontal" name="pe" id="pe" method="post">


    <h3 class="card-title">Produ&ccedil;&atilde;o da Incubadora de Empresas</h3>
    <div class="msg" id="msg"></div>

    <table>
        <tr>
            <td>Produ&ccedil;&atilde;o</td>
            <td>Quantidade</td>
        </tr>
        <tr>
            <td>Empresas Graduadas</td>
            <td><input class="form-control"type="text" name="qtdEmpg" onchange="Soma1();" size="5"
                       value='<?php echo $incubadora->getEmpresasgrad(); ?>'
                       onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Empregos Gerados</td>
            <td><input class="form-control"type="text" name="qtdEmpgs" onchange="Soma1();" size="5"
                       value='<?php echo $incubadora->getEmpgerados(); ?>'
                       onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Projetos Aprovados (SEBRAE, FINEP, Fundos Setoriais, etc.)</td>
            <td><input class="form-control"type="text" name="qtdProja" onchange="Soma1();" size="5"
                       value='<?php echo $incubadora->getProjaprovados(); ?>'
                       onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Eventos Promovidos (Cursos, Palestras, Workshops, F&oacute;runs)</td>
            <td><input class="form-control"type="text" name="qtdEven" onchange="Soma1();" size="5"
                       value='<?php echo $incubadora->getEventos(); ?>'
                       onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td> N&uacute;mero de Empreendedores Capacitados</td>
            <td><input class="form-control"type="text" name="qtdEmpcap" onchange="Soma1();" size="5"
                       value='<?php echo $incubadora->getCapacitrh(); ?>'
                       onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td> Capacita&ccedil;&atilde;o de Recursos Humanos (Cursos)</td>
            <td><input class="form-control"type="text" name="qtdCap" onchange="Soma1();" size="5"
                       value='<?php echo $incubadora->getNempreendedores(); ?>'
                       onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Consultorias Promovidas</td>
            <td><input class="form-control"type="text" name="qtdCons" onchange="Soma1();" size="5"
                       value='<?php echo $incubadora->getConsultorias(); ?>'
                       onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td> Participa&ccedil;&atilde;o das Empresas em Feiras</td>
            <td><input class="form-control"type="text" name="qtdFei" onchange="Soma1();" size="5"
                       value='<?php echo $incubadora->getPartempfeiras(); ?>'
                       onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Total</td>
            <td><b id='totalgeral1'></b>
            </td>
        </tr>
    </table>
    <input class="form-control"name="operacao" type="hidden" value="A" /> <input class="form-control"type="hidden"
                                                             name="codigo" value="<?php print $incubadora->getCodigo(); ?>" /> <input
                                                             type="button" onclick='direciona(1);' value="Gravar" />

</form>
<script>
    window.onload = Soma1();
    Soma2();
</script>