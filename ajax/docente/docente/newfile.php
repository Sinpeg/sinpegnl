<?php
$sessao = $_SESSION ['sessao'];

require 'dao/docentedao.php';
$anobase=$sessao->getAnobase();
$login=$sessao->getLogin();//login é o cpf
$login=61378291204;
$anobase=2020;
$daodoc=new docentedao();

$rows=$daodoc->buscaTempo($anobase);
foreach ($rows as $r){
    $codtempo=$r['CodTempo'];
}


$codtempo=15;
$rows=$daodoc->buscaCursos($codtempo);
foreach ($rows as $a) {
    $arr[$a['idcursocenso']]=$a['nomeCurso']."-".$a['codInepMatriz'];
}



/*if (!$aplicacoes[36]) {
 print "O usuário não tem permissão para acessar este módulo!";
 exit();
 }*/

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
                else if (document.adicionar.missao.value == ""){
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



   <form name="fdocente" id="fdocente" method="POST" enctype="multipart/form-data" >

        <fieldset>
            <legend>Alterar dados do docente para o censo da educação superior <?php print $anobase;?></legend>
             
        <div id="msg"></div>         
                
<p>Dados Pessoais</p>
<table>
<?php 

$rows=$daodoc->buscacPessoa($login);
foreach ($rows as $r){
 ?>
<tr><td><label>CPF</label></td><td><input type="text" class='form-control' name="cpf" disabled size="60" value="<?php print $login; ?>" /></td></tr>
<tr><td><label>Data de Nascimento</label></td><td><input type="text" class='form-control' name="nascimento" disabled size="60" value="<?php print $r['dtnascimento']; ?>" /></td></tr>
<tr><td><label>Nome</label></td><td><input type="text" class='form-control' name="nome" disabled size="60" value="<?php print $r['nome']; ?>" /></td></tr>
<tr><td><label>Sexo</label></td><td>
<select class='form-control'  name="sexo">
  <option <?php echo $r['sexo']==1?'selected':"";?> value="0">Masculino</option>
  <option <?php echo $r['sexo']==0?'selected':"";?> value="1">Feminino</option>
</select> 
</td></tr>
<tr><td><label>Cor/raça</label></td><td>
<select class='form-control'  name="cor">
  <option <?php echo $r['cor']=="0"?'selected':"";?> value="0">Docente não quis declarar cor/raça</option>
  <option <?php echo $r['cor']=="1"?'selected':"";?> value="1">Branca</option>
  <option <?php echo $r['cor']=="2"?'selected':"";?> value="2">Preta</option>
  <option <?php echo $r['cor']=="3"?'selected':"";?> value="3">Parda</option>
  <option <?php echo $r['cor']=="4"?'selected':"";?> value="4">Amarela</option>
  <option <?php echo $r['cor']=="5"?'selected':"";?> value="5">Indígena</option>
</select></td></tr>
 <tr><td><label>Nacionalidade</label></td><td>
<select class='form-control'  name="nacionalidade">
  <option <?php echo $r['nacionalidade']=="1"?'selected':"";?> value="1">Brasileira nata</option>
  <option <?php echo $r['nacionalidade']=="2"?'selected':"";?> value="2">Brasileira por naturalização</option>
  <option <?php echo $r['nacionalidade']=="3"?'selected':"";?> value="3">Estrangeira</option>
</select>
</td><tr>
<tr><td><label>País de Origem</label></td><td><input type="text" class='form-control' name="nascimento" disabled size="60" value="<?php print "banco"; ?>" /></td></tr>
<tr><td><label>UF de origem</label></td><td></td></tr>
<tr><td><label>Município</label></td><td></td></tr>
<tr><td><label>Docente com deficiência, TGD/TEA ou altas habilidades/superdotação</label></td>
<td>
<select class='form-control'  name="temdeficiencia">
  <option   value="A">Selecione uma opção...</option>
  <option <?php echo $r['pnd']=="0"?'selected':"";?>  value="0">Não</option>
  <option <?php echo $r['pnd']=="1"?'selected':"";?>  value="1">Sim</option>
  <option <?php echo $r['pnd']=="2"?'selected':"";?>  value="2">Não dispõe da informação</option>
</select>
</td></tr>
<tr><td><label>Deficiência, TGD/TEA ou altas habilidades/superdotação</label></td><td><input type = "checkbox"  name = "temdef" value = "1"></td></tr>
<tr><td><label>Tipo</label></td><td></td></tr>
 <tr> <td><input type = "checkbox" <?php echo $r['cegueira']=="1"?'checked':"";?> name = "cegueira" value = "1"></td><td>Cegueira</td></tr>
  <tr><td><input type = "checkbox" <?php echo $r['baixavisao']=="1"?'checked':"";?> name = "tipodef" value = "2"></td><td>Baixa visão</td></tr>
 <tr> <td><input type = "checkbox" <?php echo $r['surdez']=="1"?'checked':"";?> name = "tipodef" value = "3"></td><td>Surdez</td></tr>
  <tr><td><input type = "checkbox" <?php echo $r['auditiva']=="1"?'checked':"";?> name = "tipodef" value = "4"></td><td>Auditiva</td></tr>
 <tr> <td><input type = "checkbox" <?php echo $r['fisica']=="1"?'checked':"";?> name = "tipodef" value = "5"></td><td>Física</td></tr>
  <tr><td><input type = "checkbox" <?php echo $r['surdocegueira']=="1"?'checked':"";?> name = "tipodef" value = "6"></td><td>Surdocegueira</td></tr>
 <tr> <td><input type = "checkbox" <?php echo $r['mental']=="1"?'checked':"";?> name = "tipodef" value = "7"></td><td>Intelectual</td></tr>
 <tr><td> <input type = "checkbox" <?php echo $r['autismoinfantil']=="1"?'checked':"";?> name = "tipodef" value = "8"></td><td>Transtorno global do desenvolvimento (TGD)/Transtorno do Espectro Autista (TEA)</td></tr>
 <tr><td> <input type = "checkbox" <?php echo $r['altashabilidades']=="1"?'checked':"";?> name = "tipodef" value = "9"></td><td>Altas habilidades/ superdotação</td></tr>
<?php 
$idpessoa=$r['idPessoa'];

} ?>
</table>
<p>Dados do Docente na IES</p>

<?php 
$codtempo=16;
$rows=$daodoc->buscaDocente31($idpessoa,$codtempo);

if (empty($rows) || $rows==null){
   echo "Dados ainda não disponíveis"; 
}else{
    
foreach ($rows as $r){?>

            <table> 
              <tr><td><label>Escolaridade</label></td><td>             
               <select class='form-control' name="escolaridade">
                  <option <?php echo $r['escolaridade']=="1"?'selected':"";?> value="1">Sem formação de nível superior</option>
                  <option <?php echo $r['escolaridade']=="3"?'selected':"";?> value="3">Nível superior sem pós graduação</option>
                   <option <?php echo $r['escolaridade']=="4"?'selected':"";?> value="4">Especialização</option>
                  <option <?php echo $r['escolaridade']=="5"?'selected':"";?> value="5">Mestrado</option>
                  <option <?php echo $r['escolaridade']=="6"?'selected':"";?> value="6">Doutorado</option>
               </select></td> </tr>
            <tr> <td><label>Situação do Docente na IES</label></td><td>             
               <select class='form-control' name="sitdocente">
                  <option <?php echo $r['situacao']==1?'selected':"";?> value="1">Esteve em exercício</option>
                  <option <?php echo $r['situacao']==2?'selected':"";?> value="2">Afastado para qualificação</option>
                  <option <?php echo $r['situacao']==3?'selected':"";?> value="3">Afastado para exercício em outros órgãos/entidades</option>
                   <option <?php echo $r['situacao']==4?'selected':"";?> value="4">Afastado por outros motivos</option>
                  <option  <?php echo $r['situacao']==5?'selected':"";?> value="5">Afastado para tratamento de saúde</option>
               </select></td> </tr> 
                          
             <tr> <td><label>Docente esteve em exercício em 31/12?</label></td><td>   
              <input type = "checkbox"  <?php echo $r['exercicio3112']=="1"?'checked':"";?> name = "exercicio3112" value = "0">
             
             
                           
                  <tr> <td><label>Regime de trabalho</label></td><td>             
               <select class='form-control' name="regime">
              <option <?php echo $r['regime']=="1"?'selected':"";?> value="1">Tempo integral com DE</option>
              <option <?php echo $r['regime']=="2"?'selected':"";?> value="2">Tempo Integral sem DE</option>
              <option <?php echo $r['regime']=="3"?'selected':"";?> value="3">Tempo Parcial</option>
              <option <?php echo $r['regime']=="4"?'selected':"";?> value="4">Horista</option>
               </select></td> </tr>            
                           
                     <tr> <td><label>Docente Substituto</label></td><td>             
                 <input type = "checkbox"   <?php echo $r['substituto']=="1"?'checked':"";?>  name = "substituto" value = "0">
               
                
               </td> </tr>       
                          <tr> <td><label>  Docente Visitante   </label></td><td>             
                 <input type = "checkbox"  <?php echo $r['visitante']=="1"?'checked':"";?> name = "visitante" value = "0">
               
               </td> </tr>   
               
                <tr> <td><label>Tipo de vínculo de docente visitante à IES </label></td><td>             
                 <select class='form-control' name="regime">
              <option  <?php echo $r['vinculovisitante']=="1"?'selected':"";?> value="1">Em folha</option>
              <option <?php echo $r['vinculovisitante']=="2"?'selected':"";?>  value="2">Bolsista</option>
               </select>
            
               </td> </tr>   
               
               <tr><td><label>Atuação</label></td><td></td></tr>
                  <tr><td>Ensino de pós-graduação stricto sensu presencial</td><td><input type = "checkbox" <?php echo $r['atpossspresencial']=="1"?'checked':"";?> name = "atpossspresencial" value = "0"></td></tr>
                  <tr><td>Ensino de pós-graduação stricto sensu a distância</td><td><input type = "checkbox" <?php echo $r['atposssEAD']=="1"?'checked':"";?> name = "atposssEAD" value = "0"></td></tr>
                  <tr><td>Pesquisa</td><td><input type = "checkbox"  <?php echo $r['atpesquisa']=="1"?'checked':"";?> name = "atpesquisa" value = "0"></td></tr>
                  <tr><td>Recebeu Bolsa de Pesquisa?</td><td><input <?php echo $r['bolsapesquisa']=="1"?'checked':"";?> type = "checkbox"  name = "bolsapesquisa" value = "0"></td></tr>
                  <tr><td>Extensão</td><td><input type = "checkbox"  <?php echo $r['atextensao']=="1"?'checked':"";?> name = "atextensao" value = "0"></td></tr>
                  <tr><td>Gestão, planejamento e avaliação</td><td><input type = "checkbox" <?php echo $r['atgpa']=="1"?'checked':"";?> name = "atgpa" value = "0"></td></tr>
               
               
               
               
               
               </tr>
               
               
               
               <table>
  

   
      <tr><td>Cursos de Graduação em que atuou</td><td></td></tr>
         <tr><td>Ensino em curso de graduação presencial</td><td><input type = "checkbox" <?php echo $r['atgpresencial']=="1"?'checked':"";?> name = "atgpresencial" value = "0"></td></tr>
         <tr><td>Ensino em curso de graduação a distância</td><td><input type = "checkbox" <?php echo $r['atgEAD']=="1"?'checked':"";?> name = "atgEAD" value = "0"></td></tr>
      
    <tr><td> Selecione o curso a incluir
     <select  name="curso">
   <?php  foreach ($arr as $key => $value) { ?>
    
       <option  value="<?php print  $key;?>"><?php print $value;?></option>
      
  
      <?php     }
    ?>
      </select>
      
    

  
  </td>
   <td><button style="float:left;" class="btn btn-primary">Incluir</button></td></tr> 

</table>

<?php $rows1=$daodoc->buscaDocente32($r['idRegistro31']);
if (!empty($rows1)){
?>

	<table id="tabaddindicador">
				<tbody>
					<tr>
						<th style="text-align: center">Código Inep do Curso</th>
						
						<th style="text-align: center">Nome do Curso no Inep</th>
                        <th></th>
					</tr>

				</tbody>
				<tfoot>
				<?php foreach ($rows1 as $a){?>
					<tr>
						<td><?php print  $a['idcursoinep']; ?><input type="hidden" name="idcursocenso" value="<?php print $a['idcursocenso'];?>"/></td>
						<td><?php print  $a['nome']; ?></td>
					</tr>
					<?php }?>
				</tfoot>
			</table>
     
    <table>  
    <?php } ?>
        <table>
                <td><label>Documentos comprobatórios, quando realizada alteração no formulário</label></td>
                <td>
                
              <div id="teste">
    <input type="file" id="upload" name="userfile" />
    
    <input type="text" id="texto" />
    <input type="button" id="botao" value="Selecionar..." class="btn btn-primary"/>
</div>
              
              
              
              
              </td>
            </tr>
                
     </table>           
  <?php 
}
} ?>                 
                    
                <div>

                    <input type="button" id="botdoc" value="Gravar" name="salvar" class="btn btn-primary btn"/>
                      
                    <input type="hidden" value="I" name="op" />

                </div>
                     </fieldset>

            </form>
  

<script>

$(function(){
	  AddTableRow = function() {
		var  valor= $('#indicadortotal').children("option:selected").val();
		var  texto= $('#indicadortotal').children("option:selected").text();
		
		//alert($("#indicadortotal option:selected").text());
		
	    if (valor!=0){
	    	 var newRow = $("<tr>");
	 	    var cols = "";

	 	    cols += '<td>'+texto+'<input type="hidden" name="indsel[]"  value="'+valor+'"></td>';

            <?php for ($ano=$anobase;$ano<=$docobj->getAnofinal();$ano++){?>
	 	    cols += '<td><input type="text" maxlength=8 class="form_control" size=7 onKeyPress="tecla()" autocomplete="off" value=""  name="meta'+<?php echo $ano;?>+'[]"></td>';
	 	    <?php } ?>
	 	   
	 	    cols += '<td><select class="form-control" name="metrica[]" id="metrica"  style = "width:170px" >'+           
			'<option value="" >Selecione a métrica...</option>'+
            '<option value="P" >Percentual</option>'+
            '<option value="Q" >Absoluto</option>'+
            '</select></td>';
	 	    
	 	    cols += '<td>';
	 	    cols += '<button class="btn btn-secondary" onclick="RemoveTableRow(this)" type="button">Remover</button>';
	 	    cols += '</td>';

	 	    newRow.append(cols);
	         $( '.classemeta' ).unbind();
	 	    
	 	    $("#tabaddindicador").append(newRow);
           
	 	    return false;

	    }
	  };
	});

RemoveTableRow = function(handler) {
	var tr = $(handler).closest('tr');

	tr.fadeOut(400, function(){
	tr.remove();
	});

	return false;
	};
	







$('#botdoc').click(function(event) {
	//$("#msg").css("display","none");
	$("#msg").empty();
     //alert("teste");	
	
    $.ajax({
        url: "ajax/docente/gravarAlteracao.php",
        type: "POST",
        data: $("form[name=fdocente]").serialize(),
        success: function(data) {
        //alert(data);	
        	$("#msg").html(data);
        	
        },
    });
		
	
});


</script>
