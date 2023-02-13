<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
$sessao = $_SESSION["sessao"];

$nomeunidade = $sessao->getNomeUnidade();
	
$codunidade = $sessao->getCodUnidade();
$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnoBase();
$loginsessao = $sessao->getLogin();
$aplicacoes = $sessao->getAplicacoes();
/*
if (!$aplicacoes[3]) {
    exit();
}
*/
$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
?>

<script type="text/javascript">
    function direciona(botao) {
        switch (botao) {
            case 1:
                document.getElementById('us').action = "<?php echo Utils::createLink('usuario', 'opusuario'); ?>";
                document.getElementById('us').submit();
                break;
            case 2:
                document.getElementById('us').action = "<?php echo Utils::createLink('usuario', 'consultausuario'); ?>";
                document.getElementById('us').submit();
                break;
            case 3:
                document.getElementById('us').action = "<?php echo Utils::createLink('usuario', 'incusuario'); ?>";
                document.getElementById('us').submit();
                break;
        }
    }
</script>

<div class="bs-example">
    <ul class="breadcrumb">
        <li class="active">Cadastro de usuários</li>
    </ul>
</div>

<div id="resposta-ajax"></div>

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Cadastro de usuários - Inclusão</h3>
    </div>
    <form class="form-horizontal" name="us" id="us" method="post" action="">

        <table class="card-body">
            <div class="msg" id="msg"></div>
            <tr>
                <td class="coluna1">Login</td>
            </tr>
            <tr>
                <td class="coluna2"><input class="form-control"type="text" size="15" maxlength="8" name="login" id="login" maxlength="10" value='' onblur="loginajax();"/>
                </td>
            </tr>
            <tr>
                <td class="coluna1">Senha</td>
            </tr>
            <tr>
                <td class="coluna2"><input class="form-control"type="password" size="15" maxlength="8" name="senha" size="10" value='' /></td>
            </tr>
            <tr>
                <td class="coluna1"> Confirma senha</td>
            </tr>
            <tr>
                <td class="coluna2"><input class="form-control"type="password" size="15" maxlength="8" name="confirma" onblur="confirmasenha();" size="10" value='' /></td>
            </tr>
            <tr>
                <td class="coluna1">Nome</td>
            </tr>
            <tr>
                <td class="coluna2"><input class="form-control"type="text" name="nome" size="80" value='' />
                </td>
            </tr>
            <tr>
                <td class="coluna1">E-mail</td>
            </tr>
            <tr>
                <td class="coluna2"><input class="form-control"type="text" name="email" size="80" value='' />
                </td>
            </tr>
            <tr>
                <td class="coluna1">
                    Unidade
                    <div class="input-group">
                        <input class="form-control form-control-lg" type="text" style="width:50%" id="parametro" name="parametro" value=''/>
                        <div class="input-group-append">
                            <input type="button" id="buscar" class="btn btn-info" onclick="usajax();" value="Buscar" />
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div class="msg" id="combo1"></div>
                </td>
            </tr>
        </table>
        <table class="card-body">
            <tr>
                <td align="center" colspan=3>
                    <br>
                    <input class="form-control"name="operacao" type="hidden" readonly="readonly" value="I" />
                    <input type="button" id="gravar" name="gravar" value="Incluir novo usuário" class="btn btn-info" />
                    <?php
                    if ($aplicacoes[3]) { ?>
                        <input type="button" onclick='direciona(2);' id="consultar" value="Consultar" class="btn btn-info" />
                    <?php } ?>
                </td>
            </tr>
        </table>
        <div class="msg" id="msg1"></div>
    </form>
</div> 

<script>
    $(function() {
        $('input[name=gravar]').click(function() {
            $('div#msg').empty();
            $.ajax({
                url: "ajax/usuario/opusuario.php", 
                type: 'POST', 
                data:$('form[name=us]').serialize(), 
                success: function(data) {
                    $('div#msg').html(data);
                }
            });
        });   
    });

    $('#us').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) { 
            e.preventDefault();
            return false;
        }
    });
</script>
