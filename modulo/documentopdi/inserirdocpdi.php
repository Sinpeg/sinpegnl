<?php
header("Content-type: text/html; charset=utf-8"); 
$sessao = $_SESSION['sessao'];
$aplicacoes = $sessao->getAplicacoes();
$anobase=$sessao->getAnobase();
$c=new Controlador();
$unidade = new Unidade();
$unidade->setCodunidade($sessao->getCodUnidade());
$unidade->setNomeunidade($sessao->getNomeunidade());

if (!$aplicacoes[36]) {
    print "O usuário não tem permissão para acessar este módulo!";
    exit();
}
?>

<script>  
    $(function(){
        $('#upload').on('change',function(){
            var numArquivos = $(this).get(0).files.length;
            $('#texto').val( $(this).val() );        
        });
    });
</script>

<style>
    #insereDoc { position: relative; }
    #upload { position:absolute; top:0;left:0; border:1px solid #ff0000; opacity:0.01; z-index:1; }
</style>

<script language="JavaScript">
    function direciona() {
        var extensoesOk = ",.rar,.zip,";
        document.getElementById('msg').innerHTML ="";
        var extensao = "," + document.adicionar.userfile.value.substr(document.adicionar.userfile.value.length - 4).toLowerCase() + ",";
        if (document.adicionar.userfile.value == "")
        { document.getElementById('msg').innerHTML = "O campo do arquivo est&aacute; vazio!!";
        }
        else if (extensoesOk.indexOf(extensao) == -1){
        
            document.getElementById('msg').innerHTML = "Envie arquivos compactados (extens&atilde;o .rar ou .zip).";
        
        }else{
            document.getElementById('adicionar').action = "?modulo=documentopdi&acao=registradoc";
            document.getElementById('adicionar').submit();
        }
    
    }
</script>

<div class="bs-example">
    <ul class="breadcrumb">
        <li class="active">Cadastrar plano de desenvolvimento</li>
    </ul>
</div>

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Cadastrar Plano de Desenvolvimento</h3>
    </div>
    <form class="form-horizontal" name="adicionar" id="adicionar" method="POST" enctype="multipart/form-data" >
        <div class="card-body">
            <div id="msg"></div>        

            <?php
            $docdao=new DocumentoDAO();  

            if (!$c->getProfile($sessao->getGrupo())){
                $rows=$docdao->buscaPeriodoGestao($anobase, 2)->fetch();
                $setanoinicial=$anobase;//$rows['anoinicial'];                    
                $setanofinal= $rows == false ? NULL: $rows['anofinal'];
                $setunidade='PDU - '.$unidade->getNomeunidade();
            } else {
                $rows=$docdao->buscaPeriodoGestao($anobase, 1)->fetch();
                $setanoinicial=$rows['anoinicial'];                       
                $setanofinal=$rows['anofinal'];
                $setunidade='PDI - '.$unidade->getNomeunidade();
            }            
            ?>    
        </div>

        <table class="card-body">  
            <?php if ($c->getProfile($sessao->getGrupo())) {//se grupo for 18 ?>  
                <tr>
                    <td class="coluna1">Unidade</td>
                </tr>
                <tr>
                    <td>
                        <div id="insereDoc">
                            <input class="form-control" type="text" id="cxunidade"  name="cxunidade" placeholder="Unidade" autocomplete="off"/>
                            <div id="suggesstion-box"></div>
                        </div>
                    <td>
                </tr>
            <?php } ?>   
            <tr>
                <td class="coluna1">Arquivo do PDU</td>
            </tr>
            <tr>
                <td>
                    <div class="input-group">
                        <div class="custom-file">
                            <input class="custom-file-input" type="file" id="upload" name="userfile" />
                            <input class="form-control" type="hidden" name="MAX_FILE_SIZE" value="10485760">
                            <input class="form-control" type="text" id="texto" />
                            <input type="button" id="botao" value="Selecionar..." class="custom-file-button"/>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="coluna1">Documento</td>  
            </tr>
            <tr>
                <td class="coluna2">
                    <input class="form-control"type="text" class='form-control' name="nomedoc" disabled size="60" value="<?php print $setunidade; ?>" />
                </td>
            </tr> 
            
            <tr>
                <td class="coluna1">Ano Inicial</td>
            </tr>
            <tr>
                <td class="coluna2">
                    <input class="form-control"type="text" class='form-control' name="anoinicial" disabled  maxlength="4" size="4" style="width:100px" value="<?php print $setanoinicial; ?>"/>
                </td>
            </tr>
            <tr>
                <td class="coluna1">Ano Final</td>
            </tr>
            <tr>
                <td class="coluna2">
                    <input class="form-control"type="text" class='form-control' name="anofinal" disabled class='form-control' maxlength="4" size="4" style="width:100px" value="<?php print $setanofinal; ?>" />
                </td>
            </tr>
            <?php
            if($anobase < 2022){
                print '<tr><td>Missão</td>
                        </tr>
                        <tr>
                            <td><textarea class="area" name="missao" rows="10" cols="60"></textarea></td>
                        </tr>
                        <tr>
                            <td>Visão</td>
                        </tr>
                        <tr>
                            <td><textarea class="area" name="visao"  rows="10" cols="60"></textarea></td>
                        </tr>';                             
            }                    
            ?>
        </table>

        <div class="card-body" align="center">
            <input type="button" id = "gravar" value="Gravar" onclick="direciona();" name="salvar" class="btn btn-info btn"/>
            <input class="form-control"type="hidden" value="I" name="op" />
        </div>
    </form>
</div>
  

