<?php
$sessao = $_SESSION ['sessao'];

require 'dao/docentedao.php';

$anobase=$sessao->getAnobase();
$login=$sessao->getLogin();//login é o cpf
$login=61378291204;
$anobase=2020;
$daodoc=new docentedao();
//echo "aqui";die;

// tempo
$rows=$daodoc->buscaTempo($anobase);
foreach ($rows as $r){
    $codtempo=$r['CodTempo'];
}


$codtempo=15;
//Cursos do SIGAA associados aos do emec
$rows=$daodoc->buscaCursos($codtempo);
foreach ($rows as $a) {
    $arr[$a['idcursocenso']]=$a['nomeCurso']." >> ".$a['modalidade']." >> ".$a['codInepMatriz'];
}
//Pais de nascimentos
$rows=$daodoc->buscaPais();
$paises=[];
foreach ($rows as $a) {
    $paises[$a['idPais']]=$a['nome'];
}

//UF de nascimento
$rows = $daodoc->buscaUf();
$ufs = array();
foreach ($rows as $row) {
    $ufs[$row['idUf']]=$row['nome'];
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
<script src="https://cdn.es.gov.br/scripts/jquery/1.11.2/jquery-1.11.2.min.js"></script>
<script src="https://cdn.es.gov.br/scripts/jquery/jquery-maskedinput/1.4.1/jquery.maskedinput-1.4.1.min.js"></script>
<style>
#teste { position:relative; }
#upload { position:absolute; top:0;left:0; border:1px solid #ff0000; opacity:0.01; z-index:1; }
.titulo{font-weight: bold;color: #fff;background-color: #337ab7;border-color: #337ab7;}
.tableBody{width:98%;margin-left:10px}
</style>
<script language="JavaScript">

            
        </script>
<style>
#unid-list{float:left;list-style:none;margin-top:-3px;padding:0;width:520px;position: absolute;height: 50px;}
#unid-list li{padding: 5px;salvar background: #f0f0f0; border-bottom: #bbb9b9 1px solid;}
#unid-list li:hover{background:#ece3d2;cursor: pointer;}
#cxunidade{padding: 5px;border: #a8d4b1 1px solid;border-radius:4px;width: 520px;height: 25px;}
</style>



<form class="form-horizontal" name="fdocente" id="fdocente" method="POST" enctype="multipart/form-data" >

        <fieldset>
            <legend>Alterar dados do docente para o censo da educação superior <?php print $anobase;?></legend>
             
        <div  id="msg"></div>
  

     


<?php 

$rowspes=$daodoc->buscacPessoa($login);
foreach ($rowspes as $r){ ?>

<?php
include 'dadosPessoais.php';
$idpessoa=$r['idPessoa'];
$codtempo=16;
$rowsd31=$daodoc->buscaDocente31($idpessoa,$codtempo);
}//$rows ?>



<?php 
if (empty($rowsd31) || $rowsd31==null){
   echo "Dados ainda não disponíveis"; 
}else{
    
    foreach ($rowsd31 as $r){
        
                 include 'dadosDocIes.php';?>
         <?php
                $rowsd32=$daodoc->buscaDocente32($r['idRegistro31']);

         }//buscadocente31
}//if		?>


  <tr><td> Selecione o curso a incluir</td>
  <td><select  name="curso"  class='form-control' style="width:94%;" id="curso" > 
            <?php  foreach ($arr as $key => $value) { ?>
               <option  value="<?php print  $key;?>"><?php print $value;?></option>
               <?php     }      ?>
              </select>
              <a href="#"  onclick="AddTableRow()" style="float:right;margin-top: -6%;" ><img title="Adicionar" src="webroot/img/add.png"/></a>
  </td></tr>
             

		

	<table id="tabaddindicador">
				
					<tr style="background: #c8c8c8;">
						<td ><b>Código Inep do Curso</b></td>						
						<td ><b>Nome do Curso no Inep</b></td>
						<td ><b>Modalidade</b></td>
						<td ><b>Ação</b></td>
                        
					</tr>

				
				
				<?php foreach ($rowsd32 as $a){?>
					<tr>
						<td><?php print  $a['idcursoinep']; ?><input class="form-control"type="hidden" name="idcursocenso[]" value="<?php print $a['idcursocenso'];?>"/></td>
						<td><?php print  $a['nome']; ?></td>
						<td><?php print  $a['modalidade']; ?></td>
						<td><a href="#" onclick="RemoveTableRow(this)"><img title="Excluir" width="30" src="webroot/img/error.png"/></a></td>
					</tr>
					<?php }?>
			</table>
     
    <table>  
                <td>Documentos comprobatórios devem ser importados, quando for incluída alguma atuação</td>
                <td>
                
   <div id="teste"><input class="form-control" class="custom-file-input" type="file" id="upload" name="userfile" />   
    <input class="form-control"type="text" id="texto" />
    <input type="button" id="botao" value="Selecionar..." class="btn btn-info"/>
    </div>
 </td>
              
                          </tr>
                          
     <tr><td></td><td></td></tr>                     
                          
              
     </table>           


                
                    
<!-- <button type="button" class="btn btn-info" data-toggle="modal" data-target="#alertaescolaridade">Large modal</button> -->


<div class="alert alert-warning" role="alert">Só serão consideradas como alterados, os dados de docentes, que apresentarem documento comprobatório! </div>
</div>
			
<input type="button" id="botdoc" value="Homologar" name="salvar" class="btn btn-info btn"/>

<input class="form-control"type="hidden" value="I" name="op" />


                     </fieldset>

            </form>
  

<script>



$('#botdoc').click(function(event) {
	$("#msg").css("display","none");
	$("#msg").empty();
    //$("#msg").html("teste");
    
 //$('#msg').html('Docente, ao alterar a escolaridade, certifique-se de que a nova escolaridade seja compatível ao cargo!');
  
   
if  ($('#nacionalidade').val()!=3 &&  $('#passaporte').val().length != 0){
       $("#msg").css("display","");
       addAlert("Documento estrangeiro só deve ser informado, se a nacionalidade do docente for estrangeira!");
       return false;
     }
     
     else if ($('#pais').val()!="BRA" && $('#nacionalidade').val()==1) {
             //campo 9 - pais de origem
       $("#msg").css("display","");
       addAlert("Quando o docente for brasileiro nato, o país de origem informado deve ser o Brasil!");
       return false;
     }
    
     else if (($('#pais').val()=="BRA" && $('#nacionalidade').val()!=1) ||
        ($('#pais').val()=="BRA" && $('#nacionalidade').val()!=1)){
             //campo 9 - pais de origem
              $("#msg").css("display","");
              addAlert("Quando o país for Brasil, o docente deve ter nacionalidade igual a brasileiro nato!");
              return false;
     }
     
     else  if ($('#pais').val()=="BRA" &&  $('#uf').val()==0){
       $("#msg").css("display","");
       addAlert("Estado  de origem são obrigatórios para docente brasileiro nato!");
       return false;
      
      } //municipio de nascimento
       else  if ($('#pais').val()=="BRA" &&  ( $('#mun').val()==0)){
           $("#msg").css("display","");
           addAlert("Município de origem é obrigatório, se país de origem for Brasil!");
           return false;
          //campo 5
     }   
 
          else if ($("#temdeficiencia").val()==1 &&
     !$("#cegueira").is(':checked')  &&
     !$("#baixavisao").is(':checked')  &&
     !$("#surdez").is(':checked')  &&
     !$("#auditiva").is(':checked')  &&
     !$("#fisica").is(':checked')  &&
     !$("#surdocegueira").is(':checked')  &&
     !$("#mental").is(':checked')  &&
     !$("#autismoinfantil").is(':checked')  &&
     !$("#altashabilidades").is(':checked') 
     ){
     $("#msg").css("display","");
     addAlert("Especifique pelo menos uma deficiência, quando preencher o campo Docente com deficiência, TGD/TEA ou altas habilidades/superdotação com 'Sim'.");
     return false;
     }  
     
    else if ($("#temdeficiencia").val()!=1 &&
    ( $("#cegueira").is(':checked') ||
     $("#baixavisao").is(':checked')  ||
     $("#surdez").is(':checked')  ||
     $("#auditiva").is(':checked')  ||
     $("#fisica").is(':checked')  ||
     $("#surdocegueira").is(':checked')  ||
     $("#mental").is(':checked')  ||
     $("#autismoinfantil").is(':checked')  ||
     $("#altashabilidades").is(':checked') )
     ){
     $("#msg").css("display","");
     addAlert("É obrigatório preencher o campo Docente com deficiência, TGD/TEA ou altas habilidades/superdotação com 'Sim', quando uma deficiência/TGD-TEA ou altas habilidades/superdotação for selecionado.");
     return false;
     }  
  
 else if (($("#cegueira").is(':checked')  &&
          $("#baixavisao").is(':checked')  &&
          $("#surdocegueira").is(':checked')) ||
          ($("#cegueira").is(':checked')  &&
          $("#baixavisao").is(':checked')  ) ||
          ($("#cegueira").is(':checked')  &&
          $("#surdocegueira").is(':checked')) ||
          ( $("#baixavisao").is(':checked')  &&
          $("#surdocegueira").is(':checked') )
         ){
       $("#msg").css("display","");
       addAlert("Não é possível informar 'Cegueira' ou 'Baixa visão' ou 'Surdocegueira' simultaneamente!");
       return false;
 }  else if (($("#cegueira").is(':checked')  &&
          $("#surdez").is(':checked')  &&
          $("#surdocegueira").is(':checked')) ||
          ($("#cegueira").is(':checked')  &&
          $("#surdez").is(':checked')  ) ||
          ($("#cegueira").is(':checked')  &&
          $("#surdocegueira").is(':checked')) ||
          ( $("#surdez").is(':checked')  &&
          $("#surdocegueira").is(':checked') )
         ){
       $("#msg").css("display","");
       addAlert("Não é possível informar 'Cegueira' ou 'Surdez' ou 'Surdocegueira' simultaneamente!");
       return false;
 }   
 else if (( $("#surdez").is(':checked')  &&
     $("#auditiva").is(':checked')  &&
     $("#surdocegueira").is(':checked') ) ||
     ($("#surdez").is(':checked')  &&
     $("#auditiva").is(':checked') ) ||
     ($("#surdez").is(':checked')  &&
     $("#surdocegueira").is(':checked')) ||
       ($("#auditiva").is(':checked')  &&
     $("#surdocegueira").is(':checked') ) )
       {
       addAlert( "Não é possível informar 'Surdez' ou 'Deficiência auditiva' ou 'Surdocegueira' simultaneamente!");
       return false;
     }  
     
 
  else  if (($("#sitdocente").val()!=1) && ($("#visitante").is(':checked'))){
		     $("#msg").css("display","");
             addAlert("Docente Visitante é campo obrigatório, SOMENTE quando informado 'Em exercício' no campo Situação do Docente!");
             return false;
   } 
      
  
   else  if ($("#visitante").is(':checked') &&  $("#vinculovisitante").val()==0  ){
		     $("#msg").css("display","");
             addAlert("Vínculo de Visitante só deve ser informado, quando Docente for Visitante!");
             return false;
   } 
 
 
  
  
     else  if (($("#sitdocente").val()!=1) && ($("#substituto").is(':checked'))){
		     $("#msg").css("display","");
             addAlert("Docente Substituto só deve ser selecionado, quando informado 'Em exercício' no campo Situação do Docente!");
             return false;
   }  
  
    
     else  if (($("#sitdocente").val()==1) && ($("#regime").val()==0)){
		     $("#msg").css("display","");
             addAlert("Regime é campo obrigatório, quando informado 'Em exercício' no campo Situação do Docente!");
             return false;
   } 
     else  if (($("#sitdocente").val()!=1) && ($("#regime").val()!=0)){
		     $("#msg").css("display","");
             addAlert("Regime só deve ser informado, quando informado 'Em exercício' no campo Situação do Docente!");
             return false;
   }  
    
     else  if ($("#visitante").is(':checked') && $("#substituto").is(':checked')){
		     $("#msg").css("display","");
             addAlert("Docente Substituto não pode ser Substituto e Visitante ao mesmo tempo!");
             return false;
   }   
    
   
  
  
 
  

 else if (
    ($("#atpesquisa").is(':checked') || $("#atpossspresencial").is(':checked')
    || $("#atgpa").is(':checked')  || $("#atextensao").is(':checked')  
    || $("#atposssEAD").is(':checked')  || $("#atgpresencial").is(':checked')
      )
    
   &&   ($("#sitdocente").val()!=1)){
		     $("#msg").css("display","");
             addAlert("Somente é possível informar o campo Atuação do docente, se o campo Situação do docente na IES informado for 'Em exercício'!");
             return false;
   }  


else if ((!$("#atpesquisa").is(':checked')  
   && !$("#atpossspresencial").is(':checked')  
   && !$("#atgpa").is(':checked')  
   && !$("#atextensao").is(':checked')  
   && !$("#atposssEAD").is(':checked')  
   && !$("#atgpresencial").is(':checked')  
   && !$("#atgpa").is(':checked') ) 
  &&   ($("#sitdocente").val()==1)){
		     $("#msg").css("display","");
             addAlert("Alguma atuação deve ser selecionada, quando informada a opção 'Em exercício' no campo Situação do docente na IES");
             return false;
   }  
     
else   if ((!$("#atpesquisa").is(':checked')) &&   
          ($("#bolsapesquisa").is(':checked'))){
		    $("#msg").css("display","");
            addAlert("Somente é possível informar o campo Bolsa de pesquisa, f se o campo Atuação do docente - Pesquisa for selecionado");
            return false;
	}    
 
  
  
       
else{
     
     
    var file_data = $('#upload').prop('files')[0];  //arquivo 
    var formData = new FormData($("#teste")[0]);//cria objeto form
    formData.append('file', file_data);//adiciona o arquivo ao objeto form
     //$("form[name=fdocente]").serialize(),
             alert("oi"+$("#vinculovisitante").val());
     
             $.ajax({
                url: "ajax/docente/gravarAlteracao.php",
                type: "POST",
                data: formData, 
                processData: false, 
                contentType: false, 
                success: function(data) {
                alert(data);	
                	$("#msg").html(data);

          	
                },
            });
	
    }
	
});
     
     
      
     
$('#curso').change(function() {
		$("#msg").empty();
		
		
    $.ajax({
		type: "POST",
		url: "ajax/docente/buscamodalidadecurso.php",
		data: $("form").serialize(),
		success: function(data){
			$("#modalidadecurso").html(data);
		}    });
		
});

function addAlert(message) {
    $('#msg').append('<div class="alert alert-danger" role="alert">' +
             message + '</div>');
}


$(function() {
    $( "#calendario" ).datepicker({
        
          dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
        dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
        dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
        monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']
        
    });
    
   // $('#cpf').mask("999.999.999/99"); 
    
});





 $('#pais').change(function() {
		$("#ufselect").empty();
		$("#msg").empty();
		var  valor= $('#curso').children("option:selected").val();
		
		if (valor!='BRA'){
		  $("#uf").val( $('option:contains("Selecione a UF, se país de nascimento, Brasil")').val() );//sel um option em select
		  $("#mun").val( $('option:contains("Selecione a município, se país de nascimento, Brasil")').val() );//sel um option em select
		  $("#nacionalidade").val( $('option:contains("Selecione nacionalidade")').val() );
		}		
				
  
		
});
 
$('#uf').change(function() {
		$("#munselect").empty();
		$("#msg").empty();
		
		
    $.ajax({
		type: "POST",
		url: "ajax/docente/buscauf.php",
		data: $("form").serialize(),
		success: function(data){
			$("#munselect").html(data);
		}    });
		
});


$(function(){
	  AddTableRow = function() {
	  //Incluir os cursos emec na tabela 
		var  valor= $('#curso').children("option:selected").val();
		var  texto= $('#curso').children("option:selected").text();
		alert(valor+"--"+texto);
		//Separa o código do curso do nome do curso
		var posicoes=texto.indexOf(">");
		var tamanho=texto.length;
		var nomecurso=texto.substr(0,posicoes-1);//nome do curso
				alert("nomecurso"+nomecurso);		
		var texto2=texto.substr(posicoes+2,texto.length);//texto sem nome do curso
		modalidadecurso=texto2.substr(0,texto2.indexOf(">")-1);
		       alert("modalidadecurso"+modalidadecurso);		
		var codigoemec=texto2.substr(texto2.indexOf(">")+2,texto2.length);
		       alert(codigoemec);
		 var atuacaocompativel=verificaSeEadPresencial(modalidadecurso);
	     //Verifica se está se tentando incluir um código emec que já existe na tabela
		 var desigualdade=getEqualsCourse(codigoemec);
		 if ($("#sitdocente").val()!=1){
		     $("#msg").css("display","");
		     $("#msg").empty();
             addAlert("Somente é possível informar cursos se informada a opção 'Esteve em exercício' no campo 'Situação do docente na IES'!");
             return false;
		}
		
	    if (valor!=0 && desigualdade==1 && atuacaocompativel==1){
	         
	     	var newRow = $("<tr>");
	 	    var cols = "";

	 	    cols += '<td>'+codigoemec+'<input class="form-control"type="hidden" name="idcursocenso[]"  value="'+valor+'"></td>';

            
	 	    cols += '<td>'+nomecurso+'</td>'; 
	 	    
	 	    cols += '<td>'+modalidadecurso+'</td>';
	 	   
	 	
	 	    cols += '<td><button class="btn btn-secondary" onclick="RemoveTableRow(this)" type="button">Remover</button>';
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
	
function verificaSeEadPresencial(emodalidade){
    var teste= "Presencial";
    var resultado=1;
    var modalidade=(emodalidade.trim()==teste)? modalidade=1 : modalidade=0;
    
  
   	if ((modalidade==1) &&   (!$("#atgpresencial").is(':checked'))){
		     $("#msg").css("display","");
             addAlert("Somente é possível incluir curso presencial, se o campo 'Atuação do docente - Ensino em curso de graduação presencial' estiver selecionado!");
             resultado= 0;
	}
		
   if ((modalidade==0) &&   (!$("#atgEAD").is(':checked'))){
		     $("#msg").css("display","");
             addAlert("Somente é possível incluir  curso a distância, se o campo 'Atuação do docente - Ensino em curso de graduação a distância' estiver selecionado!");
             resultado= 0;
	}
		
  
  return resultado;
  
}


function getEqualsCourse(texto){
     resultado=1;
   
    var mydata = Array();
  for(i=0;i<$('#tabaddindicador').find("tr").length;i++){
    mydata[i] = Array();
    var currentRow = $('#tabaddindicador').find("tr").eq(i);
    for(j=0; j< currentRow.find('td').length ;j++){
       
        mydata[i][j] = currentRow.find('td').eq(j).html();
		codigoemec=mydata[i][j].substr(0,mydata[i][j].indexOf("<"));
		
        if ((j==0) && (codigoemec==texto)){
          resultado=0;
        }
    }
  }
  return resultado;
  
}




	


</script>

<?php function prepararDataForm($data){
     $novadata=empty($data)?"":substr($data,0,2)."/".substr($data,2,2)."/".substr($data,4,4);
     return $novadata;
 
}?>




























