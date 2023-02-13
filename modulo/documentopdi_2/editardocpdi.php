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
    $passou=true;
   // }
}
if (!$passou){
    Utils::redirect('documentopdi','inserirdocpdi');
}
?>
<script>  $(function(){
            $('#upload').on('change',function(){
                var numArquivos = $(this).get(0).files.length;
               
        	        $('#texto').val( $(this).val() );
              
            });
        });</script>
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
                
                }else if (document.adicionar.nomedoc.value == ""){
                    document.getElementById('msg').innerHTML = "Informe o nome do documento!";
                    erro=true ;
                }else if (document.adicionar.anoinicial.value == "" || document.adicionar.anoinicial.value==0 ){
                    document.getElementById('msg').innerHTML = "Informe o ano inicial!";
                    erro=true ;
                }else if (document.adicionar.anofinal.value == "" || document.adicionar.anofinal.value=="0"  ){
                    document.getElementById('msg').innerHTML = "O ano inicial deve ser número!";
                    erro=true ;
                }else if (parseInt(document.adicionar.anofinal.value, 10)<=parseInt(document.adicionar.anoinicial.value, 10)){
                     document.getElementById('msg').innerHTML = "O ano inicial deve ser menor que ano final deve ser número!";
                    erro=true ;
                }else if (document.adicionar.missao.value == ""){
                    document.getElementById('msg').innerHTML = "Informe a missão da sua unidade!";
                    erro=true ;
                }else if (document.adicionar.visao.value == ""){
                    document.getElementById('msg').innerHTML = "Informe a visão da sua unidade!";
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
<form class="form-horizontal" enctype="multipart/form-data" name="adicionar" id="adicionar" method="POST"  >
    <fieldset>
        <legend>Atualizar documento</legend>
        <div id="msg"></div>
        <table>
        <?php if (!is_null($sessao->getCodunidsel())){?>  
                <tr>
                    <td><label>Unidade selecionada</label></td>
                    <td><?php print $unidade->getNomeunidade(); ?></td>
                </tr>
               <?php } ?> 
        
       
             <tr>                     
          <td><label>Novo arquivo PDU</label></td>
          <td>
            
            
<div id="teste">
    <input class="form-control" class="custom-file-input" type="file" id="upload" name="userfile" />
    <input class="form-control"type="hidden" name="MAX_FILE_SIZE" value="10485760">
    
    <input class="form-control"type="text" id="texto" />
    <input type="button" id="botao" value="Selecionar..." class="btn btn-info"/>
</div>
</td>
            </tr>
                <tr>                     
          <td><label>Exportar arquivo</label></td>
          <td><?php print substr($objdoc->getNomearq(),27,strlen($objdoc->getNomearq())); ?>
          <?php // echo Utils::createLink('documentopdi', 'download', array('codigo' =>$objdoc->getCodigo())); ?>
              <a href="<?php print $objdoc->getNomearq(); ?>"
                                 target="_self"><img  src="webroot/img/download.jpg"  alt="Download" width="19" height="19" /> </a>

</td>
            </tr>
             <tr>
           <td><label>Nome</label></td>
            <td><input class="form-control"type="text" name="nomedoc"   value="<?php print $objdoc->getNome(); ?>" size="60"/></td>
        </tr>
        <tr>
            <td> <label>Ano Inicial</label></td>
            <td><input class="form-control"type="text" size="4" name="anoinicial"  value="<?php print $objdoc->getAnoInicial(); ?>" /></td>
        </tr>
        <tr>
            <td><label>Ano Final</label></td>
            <td><input class="form-control"type="text" size="4" name="anofinal"   value="<?php print $objdoc->getAnoFinal(); ?>" /></td>
        </tr>
            <tr>
            <td><label>Missão</label></td>
            <td><textarea class="area" name="missao" rows="10" cols="50"><?php print (
            
             $objdoc->getMissao()
            
           ); ?></textarea></td>
            </tr>
             
       
            <tr><td> <label>Visão</label></td>
            <td><textarea class="area" name="visao" rows="10" cols="50"><?php print ($objdoc->getVisao()); ?></textarea></td></tr>
        </table>
        <div>
            <input class="form-control"type="hidden" value="<?php print $objdoc->getCodigo(); ?>" name="coddoc" />
            <input class="form-control"type="hidden" value="E" name="op" />
            <input type="button" value="Atualizar" onclick="direciona(1);" name="salvar" class="btn btn-info btn"/>
            <input type="button" value="Elaborar Painel Tático" onclick="direciona(2);" name="tatico" class="btn btn-info btn"/>

        </div>
        

    </fieldset>
</form>