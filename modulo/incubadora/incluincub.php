<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[30]) {
    header("Location:index.php");
} 
//$nomeunidade = $sessao->getNomeUnidade();
//$codunidade = $sessao->getCodUnidade();
//$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnoBase();
$codunidade = $sessao->getCodunidade();
//require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('dao/incubadoraDAO.php');
require_once('classes/incubadora.php');
//$incubadora = array();
$cont = 0;
$daope = new IncubadoraDAO();
$incubadora = new Incubadora();
$rows_pe = $daope->buscainc($anobase);
foreach ($rows_pe as $row) {
    if ($row['CodUnidade'] == $codunidade) {
        $cont++;
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
if ($cont > 0) {
    Utils::redirect("incubadora", "consultaincub");
//    echo "teste";
//    exit();
//    $cadeia = "location:consultaincub.php";
//    header($cadeia);
    //exit();
}
//ob_end_flush();
?>
<script language='JavaScript'>
    function SomenteNumero(e){
    var tecla = (window.event)?event.keyCode:e.which;
    //0 a 9 em ASCII
    if((tecla>47 && tecla<58)){
    document.getElementById('msg').innerHTML =" ";
    return true;
    }
    else{
    if (tecla==8 || tecla==0) {
    document.getElementById('msg').innerHTML =" ";
    return true;//Aceita tecla tab
    }
    else {
    document.getElementById('msg').innerHTML = "Todos os campos devem conter apenas n&uacute;meros.";
    return false;
    }
    }
    }

    function Soma1(){
    var soma = 0;
    qtde = new Array (document.pe.qtdEmpg.value,document.pe.qtdEmpgs.value,
    document.pe.qtdProja.value,document.pe.qtdEmpcap.value, document.pe.qtdCap.value, 
    document.pe.qtdCons.value, document.pe.qtdFei.value);
    for (var i = 0;i < qtde.length; i++){
    if (!isNaN(parseInt(qtde[i]))){
    soma += parseInt(qtde[i]);
    }
    }

    document.getElementById('totalgeral1').innerHTML = soma;
    }


    function direciona(botao){
    switch (botao){
    case 1:
    if (valida()){
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
    function valida(){
    var passou=false;
    if ((document.pe.qtdEmpg.value =="") || (document.pe.qtdEmpgs.value=="")
    || (document.pe.qtdProja.value=="") || (document.pe.qtdEmpcap.value=="")
    || (document.pe.qtdCap.value=="")  || (document.pe.qtdCons.value=="") || (document.pe.qtdFei.value=="")){
    document.getElementById('msg').innerHTML ="As quantidades s&atilde;o obrigat&oacute;rias.";
    passou=true;
    }
    if (passou){return false;}
    else {return true;}
    }
</script>
<form class="form-horizontal" name="pe" id="pe" method="post">
    <h3 class="card-title">Produção da Agência de Inovação Tecnológica</h3>
    <div class="msg" id="msg"></div>
    <table>
        <tr style="font-style: italic;">
            <th>Itens</th>
            <th>Quantidade</th>
        </tr>
        <tr>
            <td>Empresas Graduadas</td>
            <td><input class="form-control"type="text" onchange="Soma1();" name="qtdEmpg" size="5" maxlength="5"
                       value='' onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Empregos Gerados</td>
            <td><input class="form-control"type="text" onchange="Soma1();" name="qtdEmpgs" size="5" maxlength="5"
                       value='' onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Projetos Aprovados (SEBRAE, FINEP, Fundos Setoriais, etc.)</td>
            <td><input class="form-control"type="text" onchange="Soma1();" name="qtdProja" size="5" maxlength="5"
                       value='' onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Eventos Promovidos (Cursos, Palestras, Workshops, F&oacute;runs)</td>
            <td><input class="form-control"type="text" onchange="Soma1();" name="qtdEven" size="5" maxlength="5"
                       value='' onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>N&uacute;mero de Empreendedores Capacitados</td>
            <td><input class="form-control"type="text" onchange="Soma1();" name="qtdEmpcap" size="5" maxlength="5"
                       value='' onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Capacita&ccedil;&atilde;o de Recursos Humanos (Cursos)</td>
            <td><input class="form-control"type="text" onchange="Soma1();" name="qtdCap" size="5" maxlength="5"
                       value='' onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Consultorias Promovidas</td>
            <td><input class="form-control"type="text" onchange="Soma1();" name="qtdCons" size="5" maxlength="5"
                       value='' onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        </tr>
        <tr>
            <td>Participa&ccedil;&atilde;o das Empresas em Feiras</td>
            <td><input class="form-control"type="text" onchange="Soma1();" name="qtdFei" size="5" maxlength="5"
                       value='' onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr style="font-style: italic;">
            <td>Total</td>
            <td><b id='totalgeral1'></b>
            </td>
        </tr>

    </table>
    <input class="form-control"name="operacao" type="hidden" value="I" /> <input type="button"
                                                             onclick='direciona(1);' value="Gravar" />
</form>

