<?php
 header("Content-type: text/html; charset=utf-8"); 
$sessao = $_SESSION['sessao'];
$aplicacoes = $sessao->getAplicacoes();
$anobase=$sessao->getAnobase();
$c=new Controlador();
$unidade = new Unidade();
$unidade->setCodunidade($sessao->getCodUnidade());
$unidade->setNomeunidade($sessao->getNomeunidade());

?>
<?php
if (!$aplicacoes[36]) {
    print "O usuário não tem permissão para acessar este módulo!";
    exit();
}
?>
<script>  $(function(){
            $('#upload').on('change',function(){
                var numArquivos = $(this).get(0).files.length;
               
        	        $('#texto').val( $(this).val() );
              
            });
        });
</script>
<style>
#teste { position:relative; }
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
                
                }
                else if (document.adicionar.nomedoc.value == ""){
                    document.getElementById('msg').innerHTML = "Informe o nome do documento!";
                }else if (document.adicionar.anoinicial.value == "" || document.adicionar.anoinicial.value==0 ){
                    document.getElementById('msg').innerHTML = "Informe o ano inicial!";
                }else if (document.adicionar.anofinal.value == "" || document.adicionar.anofinal.value=="0"  ){
                    document.getElementById('msg').innerHTML = "O ano inicial deve ser número!";
                }else if (parseInt(document.adicionar.anofinal.value, 10)<=parseInt(document.adicionar.anoinicial.value, 10)){
                     document.getElementById('msg').innerHTML = "O ano inicial deve ser menor que ano final deve ser número!";
                }else if (document.adicionar.missao.value == ""){
                    document.getElementById('msg').innerHTML = "Informe a missão da sua unidade!";
                }else if (document.adicionar.visao.value == ""){
                    document.getElementById('msg').innerHTML = "Informe a visão da sua unidade!";
                }else{
                  document.getElementById('adicionar').action = "?modulo=documentopdi&acao=registradoc";
                  document.getElementById('adicionar').submit();
                }
            
            }
        </script>
<style>
#unid-list{float:left;list-style:none;margin-top:-3px;padding:0;width:520px;position: absolute;height: 50px;}
#unid-list li{padding: 5px;salvar background: #f0f0f0; border-bottom: #bbb9b9 1px solid;}
#unid-list li:hover{background:#ece3d2;cursor: pointer;}
#cxunidade{padding: 5px;border: #a8d4b1 1px solid;border-radius:4px;width: 520px;height: 25px;}
</style>



   <form class="form-horizontal" enctype="multipart/form-data" name="adicionar" id="adicionar" method="POST"  >

        <fieldset>
            <legend>Cadastrar Plano de Desenvolvimento</legend>
             
        <div id="msg"></div>         
                

    <?php 
                    
        if ($anobase>=2017 && $anobase<=2020 && !$c->getProfile($sessao->getGrupo())){
                       $setanoinicial=2017;
                       $setanofinal=2020;
                       $setunidade='PDU - '.$unidade->getNomeunidade();
        } else {
                       $setanoinicial="";
                       $setanofinal="";
                       $setunidade="";
        }            
                ?>    
                   <table> 
        <?php if ($c->getProfile($sessao->getGrupo())) {//se grupo for 18 ?>  
                    <tr><td><label>Unidade</label> </td>
                    <td>   <input class="form-control" type="text" id="cxunidade"  name="cxunidade" placeholder="Unidade" autocomplete="off"/>
       	<div id="suggesstion-box"></div></td>
               
<?php } ?> 
                        
                </tr>
            <tr>
                <td><label>Arquivo do PDU</label></td>
                <td>
              <div id="teste">
    <input class="form-control" class="custom-file-input" type="file" id="upload" name="userfile" />
    
    <input class="form-control"type="text" id="texto" />
    <input type="button" id="botao" value="Selecionar..." class="btn btn-info"/>
</div>
              
              
              
              
              </td>
            </tr>
                <tr><td><label>Documento</label></td>   
                    <td><input class="form-control"type="text" class="txt" name="nomedoc" size="60" value="<?php print $setunidade; ?>" /></td>
                </tr> 
              
                <tr><td><label>Ano Inicial</label></td>
                
                    <td><input class="form-control"type="text" class="short" name="anoinicial" maxlength="4" size="4" value="<?php print $setanoinicial; ?>"/></td>
                </tr>
                <tr>
                    <td><label>Ano Final</label></td>
                    <td><input class="form-control"type="text" class="short" name="anofinal" maxlength="4" size="4" value="<?php print $setanofinal; ?>" /></td>
                </tr>
                <tr><td><label>Missão</label></td>
                    <td><textarea class="area" name="missao" rows="10" cols="60"></textarea></td>
               </tr>
                <tr>
                    <td><label>Visão</label></td>
                    <td><textarea class="area" name="visao"  rows="10" cols="60"></textarea></td>
                </tr>
                    </table>
                <div>

                    <input type="button" value="Gravar" onclick="direciona();" name="salvar" class="btn btn-info btn"/>
                      
                    <input class="form-control"type="hidden" value="I" name="op" />

                </div>
                     </fieldset>

            </form>
  

