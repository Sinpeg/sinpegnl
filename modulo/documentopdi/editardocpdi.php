<?php
include_once ('dao/DocumentoDAO.php');
include_once ('classe/Documento.php');
//require '../../classes/Controlador.php';

$sessao = $_SESSION['sessao'];
$codunidade = $sessao->getCodUnidade();
$aplicacoes = $sessao->getAplicacoes();

if (!is_null($sessao->getCodunidsel())){
    $codunidadesel=$sessao->getCodunidsel();
    $unidade = new Unidade();
    $unidade->setCodunidade($sessao->getCodunidsel());
    $unidade->setNomeunidade($sessao->getNomeunidsel());
}

$c=new Controlador();

if (!$aplicacoes[36]) {
    print "O usuário não tem acesso este módulo!";
    exit();
}
if (!empty($_GET['msg'])){
	print $_GET['msg'];
}
    $daodoc = new DocumentoDAO();

if ($c->getProfile($sessao->getGrupo())) {//se grupo for 18  
    $coddoc = $_POST['doc'];
    $rows = $daodoc->buscadocumento($coddoc);
}else{
    $rows=$daodoc->buscaporRedundancia($codunidade, $sessao->getAnobase());
}
$objdoc = new Documento();
$passou=false;
foreach ($rows as $row) {
  //  if ($row['CodUnidade'] == $codunidade) {
        $objdoc->setCodigo($row['codigo']);
        $objdoc->setAnoFinal($row['anofinal']);
        $objdoc->setAnoInicial($row['anoinicial']);
        $objdoc->setNome($row['nome']);
        $objdoc->setNomearq($row['nomearq']);
        $objdoc->setMissao($row['missao']);
        $objdoc->setVisao($row['visao']);
        $objdoc->setSituacao($row['situacao']);
        $unidade=new Unidade;
        $unidade->setCodunidade($row['CodUnidade']);
        $objdoc->setUnidade($unidade);
    $passou=true;
   // }
}
if (!$passou){
    Utils::redirect('documentopdi','inserirdocpdi');
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

<div class="bs-example">
    <ul class="breadcrumb">
        <li class="active">Atualizar documento</li>
    </ul>
</div>

<script language="JavaScript">
    function direciona(botao) {
        var erro=false;
            document.getElementById('msg').innerHTML ="";         
        var extensoesOk = ",.rar,.zip,";
        var extensao = "," + document.adicionar.userfile.value.substr(document.adicionar.userfile.value.length - 4).toLowerCase() + ",";
        
        
    if ((botao==2) && (!erro)){
            document.getElementById('adicionar').action = "?modulo=mapaestrategico&acao=listamapapdu";
            document.getElementById('adicionar').submit();
    }else{    
        
        if (document.adicionar.userfile.value == "")
        {
            document.getElementById('msg').innerHTML = "O campo do arquivo est&aacute; vazio!!";
            erro= true;
        }else if (extensoesOk.indexOf(extensao)==-1)
        {
            document.getElementById('msg').innerHTML = "Envie arquivos compactados (extens&atilde;o .rar ou .zip).";
            erro=true ;
        
        }
                            
        if ((botao==1) && (!erro)){
            document.getElementById('adicionar').action = "?modulo=documentopdi&acao=registradoc";
            document.getElementById('adicionar').submit();
        }
    }
    }
</script>

<style>
    #teste { position:relative; }
    #upload { position:absolute; top:0;left:0; border:1px solid #ff0000; opacity:0.01; z-index:1; }
</style>

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Atualizar documento</h3>
    </div>
    <form class="form-horizontal" enctype="multipart/form-data" name="adicionar" id="adicionar" method="POST">
        <table class="card-body">
            <div id="msg"></div>
            <?php if (!is_null($sessao->getCodunidsel())){?>  
                <tr>
                    <td class="coluna1">Unidade selecionada</td>
                </tr>
                <tr>
                    <td class="coluna2"><?php print $unidade->getNomeunidade(); ?></td>
                </tr>
            <?php } ?> 

            <tr>                     
                <td class="coluna1">Novo arquivo PDU</td>
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
                <td class="coluna1">Exportar arquivo PDU
                    <?php // echo Utils::createLink('documentopdi', 'download', array('codigo' =>$objdoc->getCodigo())); ?>
                    <a href="<?php print $objdoc->getNomearq(); ?>"
                                        target="_self"><img  src="webroot/img/download-2.png"  alt="Download" width="25" height="25" /> </a>
                </td>

            </tr>
            <tr>
                <td class="coluna1">Nome</td>
            </tr>
            <tr>
                <td class="coluna2"><input class="form-control"type="text" name="nomedoc" class='form-control' disabled  value="<?php print $objdoc->getNome(); ?>" size="60"/></td>
            </tr>
            <tr>
                <td class="coluna1">Ano Inicial</td>
            </tr>
            <tr>
                <td class="coluna2"><input class="form-control"type="text" size="4" style="width:200px" name="anoinicial" class='form-control' disabled value="<?php print $objdoc->getAnoInicial(); ?>" /></td>
            </tr>
            <tr>
                <td class="coluna1" >Ano Final</td>
            </tr>
            <tr>
                <td class="coluna2"><input class="form-control"type="text" style="width:200px" size="4" name="anofinal" class='form-control' disabled value="<?php print $objdoc->getAnoFinal(); ?>" /></td>
            </tr>

            <?php
            if($anobase < 2022){
                echo '<tr>
                            <td>
                                <label>Missão</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <textarea class="area form-control" name="missao" rows="10" cols="50">'.$objdoc->getMissao().'</textarea>
                            </td>
                        </tr>   
                        <tr>
                            <td> 
                                <label>Visão</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <textarea class="area form-control" name="visao" rows="10" cols="50">'.$objdoc->getVisao().'</textarea>
                            </td>
                        </tr>';
            }

            ?>
        </table>

        <table class="card-body">
            <tfoot>
                <td colspan="2" align="center">
                    <br>
                    <input class="form-control"type="hidden" value="<?php print $objdoc->getCodigo(); ?>" name="coddoc" />
                    <input class="form-control"type="hidden" value="E" name="op" />
                    <input type="button" id="atualizar" value="Atualizar" onclick="direciona(1);" name="salvar" class="btn btn-info btn"/>
                    <input type="button" id="selecionar" value="Elaborar Painel Tático" onclick="direciona(2);" name="tatico" class="btn btn-info btn"/>
                </td>
            </tfoot>
        </table>   
    </form>
</div>


<!-- Modal para extender PDU -->
<div class="modal fade" id="extenderPDU" tabindex="-1" role="dialog" aria-labelledby="extenderPDU" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Prorrogação do PDU </h4>
        </div>
       <div class="modal-body">
            <div id="msg-alertExtencao" style="display:none;"></div>      	
				<table>		    	
		    	<tr>
		    	<td><div id="" class="alert alert-info" role="alert">O Plano de Desenvolvimento da Unidade será porrogado até o ano de <?php echo $objdoc->getAnoFinal()+1;?>, e isso inclui seus <b>indicadores, metas e iniciativas</b>. Deseja realmente prorrogar?</div></td>
		    	</tr>
                </table>
		    	<input class="form-control"type="hidden" id="codDocumento" value="<?php echo $objdoc->getCodigo(); ?>"/>
		    	<input class="form-control"type="hidden" id="codunidade" value="<?php echo $codunidade;?>"/>
		    	<input class="form-control"type="hidden" id="anobase" value="<?php echo $anobase;?>"/>
                <input class="form-control"type="hidden" id="anoLimiteExtencao" value="<?php echo $objdoc->getAnoFinal()+1;?>">
				      
     	        <div id="" class="alert alert-danger" role="alert" style="display:none;"></div>
      </div>
       
      <div id="divBtn" class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
        <button type="button" id="btn_extenderPDU" onclick="extenderPDU()"  class="btn btn-info">Sim</button>
      </div>
       <div id="divBtn2" style="display:none;" class="modal-footer">
        <button type="button" class="btn btn-info" onclick="window.history.go(0);" data-dismiss="modal">OK</button>        
      </div>
    </div>
  </div>
</div>
</div>

<style>
    .modal {
    text-align: center;
    padding: 0!important; 
    }

    .modal:before {
    content: '';
    display: inline-block;
    height: 100%;
    vertical-align: middle;
    margin-right: -4px;
    }

    .modal-dialog {
    display: inline-block;
    text-align: left;
    vertical-align: middle;
    }
    .modal-content{
        width:700px;
    }

    /* The Modal (background) */
    #modalLoading {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        /*z-index: 4; /* Sit on top */
        padding-top: 100px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }

    .loader {
    left:50%;
    position:absolute;
    top:40%;
    left:45%;	
    border: 16px solid #f3f3f3;
    border-radius: 50%;
    border-top: 16px solid #3498db;
    width: 100px;
    height: 100px;
    -webkit-animation: spin 2s linear infinite; /* Safari */
    animation: spin 2s linear infinite;
    }
    /* Safari */
    @-webkit-keyframes spin {
    0% { -webkit-transform: rotate(0deg); }
    100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
    }

    #teste { position:relative; }
    #upload { position:absolute; top:0;left:0; border:1px solid #ff0000; opacity:0.01; z-index:1; }
</style>

<script type="text/javascript">


    function extenderPDU(){
        $("#msg-alertExtencao").css("display","none");
        $("#msg-alertExtencao").html('');
        if($("#anoLimiteExtencao").val() == ''){
            $("#msg-alertExtencao").css("display","");
            $("#msg-alertExtencao").append('<div class="alert alert-danger" role="alert">É necessário inserir o <b>Ano</b> limite!</div>');	    
            return;
        }

        var codDocumento = $("#codDocumento").val();
        var anoLimite = $("#anoLimiteExtencao").val();
        $.ajax({
            url: "ajax/documentopdi/registraExtencaoPDU.php",
            type: 'POST',
            data: {ano:anoLimite,codDoc:codDocumento},
            success: function(data) {
                //alert(data);
                if(data ==1){
                    history.go(0);
                }else{
                    $("#msg-alert").css("display","");
                    $("#msg-alert").html('<div class="alert alert-danger" role="alert">Não foi possível gravar os dados, por favor acione o suporte.</div>');
                    return;
                }      
            }
        });
    }

</script>