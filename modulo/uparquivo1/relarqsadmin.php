<?php
// ob_start();

// echo ini_get('display_errors');

// if (!ini_get('display_errors')) {
//     ini_set('display_errors', 1);
//     ini_set('error_reporting', E_ALL & ~E_NOTICE);
// }
?>
<?php
// require_once(dirname(__FILE__) . '/../../includes/classes/sessao.php');
// session_start();
// if (!isset($_SESSION["sessao"])) {
//     header("location:../../index.php");
//     exit();
// }
// $sessao = $_SESSION["sessao"];
// if ($sessao->getUnidadeResponsavel()!=1){
// 	header("location:index.php");
// }
$nomeunidade = $sessao->getNomeUnidade();
$codunidade = $sessao->getCodUnidade();
$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnoBase();
$aplicacoes = $sessao->getAplicacoes();
$aplicacoes = $_SESSION["sessao"]->getAplicacoes();

// require_once(dirname(__FILE__) . '/../../includes/dao/PDOConnectionFactory.php');
// require_once(dirname(__FILE__) . '/../../includes/dao/usuarioDAO.php');
// require_once(dirname(__FILE__) . '/../../includes/classes/usuario.php');
// require_once(dirname(__FILE__) . '/../../includes/classes/unidade.php');

$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
ob_end_flush();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.or/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
        <title>Sistema de Registro de Atividades Anuais</title>
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
                        document.getElementById('msg').innerHTML = "Todos os campos devem conter apenas números.";
                        return false;
                    }
                }
            }
            function valida(){
                if (document.us.ano.value=="") {
                    document.getElementById('msg').innerHTML ="Preencha o campo ano!";
                }else if (document.us.unidade.value=="") {
                    document.getElementById('msg').innerHTML ="Preencha o campo unidade!";
                }else {
                    return true;
                }
                return false;
            }


            function direciona(botao){
                switch (botao){
                    case 1:
                        if (valida()){
                                         document.getElementById('us').action = "?modulo=uparquivo&acao=consultadmin";
                                         document.getElementById('us').submit();
                                     }
                                     break;
                                 case 2:

                                     break;
                                 }
                             }
        </script>
        <link rel="stylesheet" href="../../estilo/msgs.css" />
    </head>
    <body style="font-family:arial,helvetica,sans-serif;font-size:14px;">
        <form class="form-horizontal" name="us" id="us" method="post">
            <h3 class="card-title">Consulta - Arquivos enviados</h3> <br />
            <div class="msg" id="msg"></div>
            <div class="msg" id="msg1"></div>
            <table>
                <tr>
                    <td>Assunto</td>
                    <td><select class="custom-select" name="assunto">
                            <option value="1">Apresenta&ccedil;&atilde;o do Relat&oacute;rio de Atividades</option>
                            <option value="2">Outros</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Ano</td>
                    <td><input class="form-control"type="text" size="5" onkeypress='return SomenteNumero(event)' maxlength="4" name="ano"
                               value='' />
                    </td>
                </tr>
                <tr>
                    <td>Unidade</td>
                    <td><input class="form-control"type="text" size="70" id="unidade" name="unidade"/></td>
                </tr>


            </table>
            <input class="form-control"name="operacao" type="hidden" readonly="readonly" value="I" />
            <input type="button" onclick='direciona(1);' value="Consultar" />

        </form>
    </body>
</html>
