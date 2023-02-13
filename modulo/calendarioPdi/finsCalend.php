<?php
$sessao = $_SESSION["sessao"];
  $anobase = $sessao->getAnobase();
 //unidade do usuario
  $unidade = new Unidade();
  $unidade->setCodunidade($sessao->getCodunidade());
  $unidade->setNomeunidade($sessao->getNomeunidade());
 // $aplicacoes = $sessao->getAplicacoes();
 // $codunidadesel=0;
///require_once('classes/Calendario.php');
// require_once('dao/CalendarioDAO.php');
 $daocal = new CalendarioDAO();
/* if (!$aplicacoes[38]) {
    header("Location:index.php");
}  else {*/
   $codcalend=0;
   
    if (!is_null($sessao->getCodunidsel())){
       $codunidadesel=$sessao->getCodunidsel();
       $unidade = new Unidade();
       $unidade->setCodunidade($sessao->getCodunidsel());
       $unidade->setNomeunidade($sessao->getNomeunidsel());
       if (!empty($_GET["codcalend"])){
          $codcalend=$_GET["codcalend"];
       }
       $rowcals=$daocal->buscaCalendarioporCod($codcalend);
    }

   
    $cont = 0;
    $lista = array();
    $daodoc = new DocumentoDAO();
	$rowsdoc = $daodoc->buscadocumentoporunidadePeriodo(938,$anobase);
    // busca documentos da unidade selecionada ou da unidade do usuário
    foreach ($rowsdoc as $row) {
        $cont++;
        $lista[$cont] = new Documento();
        $lista[$cont]->setCodigo($row['codigo']);
        $lista[$cont]->setNome($row['nome']);
        $lista[$cont]->setAnoinicial($row['anoinicial']);
        $lista[$cont]->setAnofinal($row['anofinal']);
    }//for
    
                 $cont1=0;$codcalend=0;    
                 if (isset($_GET["codcalend"])){
                    $codcalend=$_GET["codcalend"];
                    $rowcals=$daocal->buscaCalendarioporCod($codcalend);
                 }

                 $contindice=0;
                   if ($codcalend!=0){
                     foreach ($lista as $l) {
                       $cont1++;
                       foreach ($rowcals as $row) {         
                          if ($l->getCodigo()==$row['codDocumento']){
                            $l->criaCalendario($row['codCalendario'],
                                $unidade,
                                null,null,
                                null,null,
                                null,null,
                                null,null,
                                null,null,
                                null,null,
                                $row['anoGestao'],$row['codusuario']);
                            $c=$l->getCalendario();
                            $c->setDatainianalise(prepararDataForm($row['dataIniAnaliseParcial']));
                            $c->setDatafimanalise(prepararDataForm($row['dataFimAnaliseParcial']));
                            $c->setDatainianalisefinal(prepararDataForm($row['datainiAnaliseFinal']));
                            $c->setDatafimanalisefinal(prepararDataForm($row['datafimAnaliseFinal']));
                            $c->setDatainiRAA(prepararDataForm($row['dataIniAnaliseRAA']));
                            $c->setDatafimRAA(prepararDataForm($row['dataFimAnaliseRAA']));
                            $c->setDatainielabpdu(prepararDataForm($row['dataInicioElabPDU']));
                            $c->setDatafimelabpdu(prepararDataForm($row['dataFimElabPDU']));
                            $c->setDatainielabpt(prepararDataForm($row['dataInicioElabPT']));
                            $c->setDatafimelabpt(prepararDataForm($row['dataFimElabPT']));
                            $c->setDatainialterapdu(prepararDataForm($row['dataIniAlteraPDU']));
                            $c->setDatafimalterapdu(prepararDataForm($row['dataFimAlteraPDU']));
                        }
                       }
                     }
                  }
 //   }
    
 if   ($cont==0) {    
      Flash::addFlash("É necessário registrar o documento antes de inserir um calendário!!");
}

//   ob_end_flush();

?>
<head>
    <script>
 
    function validarData() {

    	DAY = 1000 * 60 * 60  * 24

		//Parcial/////////////////////////////////////////////////////////
    	data1 =  $("#calendario1").prop("value");
    	data2 =  $("#calendario2").prop("value");
			
    	var nova1 = data1.toString().split('/');
    	Nova1 = nova1[1]+"/"+nova1[0]+"/"+nova1[2];
    	var nova2 = data2.toString().split('/');
    	Nova2 = nova2[1]+"/"+nova2[0]+"/"+nova2[2];

    	d1 = new Date(Nova1);
    	d2 = new Date(Nova2);

    	days_passed = Math.round((d2.getTime() - d1.getTime()) / DAY);

		//Final//////////////////////////////////////////////////////////
		data3 =  $("#calendario3").prop("value");
    	data4 =  $("#calendario4").prop("value");
			
    	var nova3 = data3.toString().split('/');
    	Nova3 = nova3[1]+"/"+nova3[0]+"/"+nova3[2];
    	var nova4 = data4.toString().split('/');
    	Nova4 = nova4[1]+"/"+nova4[0]+"/"+nova4[2];

    	d3 = new Date(Nova3);
    	d4 = new Date(Nova4);

    	days_passed2 = Math.round((d4.getTime() - d3.getTime()) / DAY);
	
	
	   //RAA/////////////////////////////////////////////////////////
    	data1 =  $("#calendario5").prop("value");
    	data2 =  $("#calendario6").prop("value");
			
    	var nova5 = data1.toString().split('/');
    	Nova5 = nova5[1]+"/"+nova5[0]+"/"+nova5[2];
    	var nova6 = data2.toString().split('/');
    	Nova6 = nova6[1]+"/"+nova6[0]+"/"+nova6[2];

    	d1 = new Date(Nova5);
    	d2 = new Date(Nova6);

    	days_passed3 = Math.round((d2.getTime() - d1.getTime()) / DAY);

		//PDU//////////////////////////////////////////////////////////
		data3 =  $("#calendario7").prop("value");
    	data4 =  $("#calendario8").prop("value");
			
    	var nova7 = data3.toString().split('/');
    	Nova7 = nova7[1]+"/"+nova7[0]+"/"+nova7[2];
    	var nova8 = data4.toString().split('/');
    	Nova8 = nova8[1]+"/"+nova8[0]+"/"+nova8[2];

    	d3 = new Date(Nova7);
    	d4 = new Date(Nova8);

    	days_passed4 = Math.round((d4.getTime() - d3.getTime()) / DAY);
    	
    	//PDU//////////////////////////////////////////////////////////
		data3 =  $("#calendario9").prop("value");
    	data4 =  $("#calendario10").prop("value");
			
    	var nova9 = data3.toString().split('/');
    	Nova9 = nova9[1]+"/"+nova9[0]+"/"+nova9[2];
    	var nova10 = data4.toString().split('/');
    	Nova10 = nova10[1]+"/"+nova10[0]+"/"+nova10[2];

    	d3 = new Date(Nova9);
    	d4 = new Date(Nova10);

    	days_passed5 = Math.round((d4.getTime() - d3.getTime()) / DAY);
    	
    	//Altera PDU//////////////////////////////////////////////////////////
		data5 =  $("#calendario11").prop("value");
    	data6 =  $("#calendario12").prop("value");
			
    	var nova11 = data5.toString().split('/');
    	Nova11 = nova11[1]+"/"+nova11[0]+"/"+nova11[2];
    	var nova12 = data4.toString().split('/');
    	Nova12 = nova12[1]+"/"+nova12[0]+"/"+nova12[2];

    	d5 = new Date(Nova11);
    	d6 = new Date(Nova12);

    	days_passed6 = Math.round((d6.getTime() - d5.getTime()) / DAY);
    	
    	// Período parcial inferior ao final 
    	if (days_passed<0 || days_passed2<0 || days_passed3<0 || days_passed4<0 || days_passed5<0 | days_passed6<0){
    		$("#alerta").html("");       	  
      	    $("#alerta").append("<div class='alert alert-warning'><strong>Data final</strong>  deve ser posterior à data inicial!</div>");
           // document.getElementById('msg').innerHTML = "Data final deve ser posterior à data inicial!";
            return false;
    	}		
		
    	//if (Nova2 >= Nova3){
    	//	$("#alerta").html("");       	  
      	//    $("#alerta").append("<div class='alert alert-warning'><strong>O período para lançar resultados parciais deve ser inferior ao período dos resultados finais!</strong></div>");
           // document.getElementById('msg').innerHTML = "Data final deve ser posterior à data inicial!";
         //   return false;
    	//}else{
            document.getElementById('msg').innerHTML = " ";
            return true;
    	//}

    	
    	}
	
    $(function() {
        $( "#calendario1" ).datepicker({
        	    dateFormat: 'dd/mm/yy',
        	    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
        	    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
        	    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
        	    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        	    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
        	    nextText: 'Próximo',
        	    prevText: 'Anterior',
        	    minDate: new Date()  	    
        });
        $( "#calendario2" ).datepicker({
    	    dateFormat: 'dd/mm/yy',
    	    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
    	    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
    	    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
    	    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
    	    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
    	    nextText: 'Próximo',
    	    prevText: 'Anterior',
    	    minDate: new Date()
   		 }); 
        $( "#calendario3" ).datepicker({
    	    dateFormat: 'dd/mm/yy',
    	    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
    	    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
    	    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
    	    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
    	    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
    	    nextText: 'Próximo',
    	    prevText: 'Anterior',
    	    minDate: new Date()  	    
    	});
        $( "#calendario4" ).datepicker({
    	    dateFormat: 'dd/mm/yy',
    	    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
    	    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
    	    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
    	    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
    	    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
    	    nextText: 'Próximo',
    	    prevText: 'Anterior',
    	    minDate: new Date()  	    
  		  }); 
        $( "#calendario5" ).datepicker({
    	    dateFormat: 'dd/mm/yy',
    	    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
    	    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
    	    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
    	    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
    	    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
    	    nextText: 'Próximo',
    	    prevText: 'Anterior',
    	    minDate: new Date()  	    
  		  }); 
  		  //PDU
        $( "#calendario6" ).datepicker({
    	    dateFormat: 'dd/mm/yy',
    	    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
    	    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
    	    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
    	    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
    	    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
    	    nextText: 'Próximo',
    	    prevText: 'Anterior',
    	    minDate: new Date()  	    
  		  }); 
  		     $( "#calendario7" ).datepicker({
    	    dateFormat: 'dd/mm/yy',
    	    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
    	    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
    	    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
    	    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
    	    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
    	    nextText: 'Próximo',
    	    prevText: 'Anterior',
    	    minDate: new Date()  	    
  		  }); 

   $( "#calendario8" ).datepicker({
    	    dateFormat: 'dd/mm/yy',
    	    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
    	    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
    	    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
    	    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
    	    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
    	    nextText: 'Próximo',
    	    prevText: 'Anterior',
    	    minDate: new Date()  	    
  		  }); 
  		     $( "#calendario9" ).datepicker({
    	    dateFormat: 'dd/mm/yy',
    	    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
    	    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
    	    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
    	    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
    	    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
    	    nextText: 'Próximo',
    	    prevText: 'Anterior',
    	    minDate: new Date()  	    
  		  }); 
   $( "#calendario10" ).datepicker({
    	    dateFormat: 'dd/mm/yy',
    	    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
    	    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
    	    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
    	    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
    	    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
    	    nextText: 'Próximo',
    	    prevText: 'Anterior',
    	    minDate: new Date()  	    
  		  }); 
  		 
	     $( "#calendario11" ).datepicker({
    	    dateFormat: 'dd/mm/yy',
    	    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
    	    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
    	    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
    	    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
    	    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
    	    nextText: 'Próximo',
    	    prevText: 'Anterior',
    	    minDate: new Date()  	    
  		  }); 
   $( "#calendario12" ).datepicker({
    	    dateFormat: 'dd/mm/yy',
    	    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
    	    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
    	    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
    	    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
    	    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
    	    nextText: 'Próximo',
    	    prevText: 'Anterior',
    	    minDate: new Date()  	    
  		  }); 
  		 
        $("#gravarcal").click(function() {
      	  // var ano = $("#anogestao").val();•••••
            
            
          if ($("#documento-painel").val()=="0")  { 
        	  $("#alerta").html("");       	  
        	  $("#alerta").append("<div class='alert alert-warning'><strong>Plano de Desenvolvimento</strong> é obrigatório!</div>");
      		  //$("#msg").text("Plano de Desenvolvimento é obrigatório!"); 
      	      return false;
      	  }    
            
      	  if ($("#anogestao").val()=="")  {
      		  $("#alerta").html("");
      		  $("#alerta").append("<div class='alert alert-warning'><strong>Ano de gestão</strong>  é obrigatório!</div>");
      		  //$("#msg").text("Ano de gestão é obrigatório!"); 
      	      return false;
      	  }
      	  if  ($("#calendario1").val()==""){
      		$("#alerta").html("");
      		  	$("#alerta").append("<div class='alert alert-warning'><strong>Data inicial</strong>  é obrigatório!</div>");
      		 // $("#msg").text("Data inicial é obrigatória!"); 
      	      return false;
      	  }
          if ($("#calendario2").val()=="") {
        	   $("#alerta").html("");
        	   $("#alerta").append("<div class='alert alert-warning'><strong>Data final</strong>  é obrigatório!</div>");
		       //$("#msg").text("Data final é obrigatória!"); 
	           return false;
	      }


          if ($("#calendario3").val()=="") {
       	   $("#alerta").html("");
       	   $("#alerta").append("<div class='alert alert-warning'><strong>Data inicial</strong>  é obrigatório!</div>");
		       //$("#msg").text("Data final é obrigatória!"); 
	           return false;
	      }

          if ($("#calendario4").val()=="") {
          	   $("#alerta").html("");
          	   $("#alerta").append("<div class='alert alert-warning'><strong>Data final</strong>  é obrigatório!</div>");
   		       //$("#msg").text("Data final é obrigatória!"); 
   	           return false;
   	      }
          var ano =$("#anogestao").val();
	         
	      if (isNaN(parseInt(ano))){
	    	  	$("#alerta").html("");
       	   		$("#alerta").append("<div class='alert alert-warning'><strong>Ano de gestão</strong>  deve ser um número com 4 dígitos!</div>");
	        	//$("#msg").text("Ano de gestão deve ser um número com 4 dígitos!"); 
	        	 return false;
	      }

	      if (!validarData()){
		      return false;
	      }
	          
	  
	        $.ajax({
	            type:'POST',
	            url:"ajax/calendariopdi/opCalend.php",
	            data: $("#ea").serialize(),
	            
	            success: function(data) {
	              /*  var token;
	                var target;
	                var resp;
	                token = response.charAt(0); // analise do token na resposta
	                target = (token=="%")?("#error"):("#msg"); // o alvo para a resposta
	                resp = (target=="#error")?(response.substr(1,(response.length-1))):(response);	*/		
	               $("#alerta").html("");
	         	   $("#alerta").append("<div class='alert alert-success'><strong>"+data+"</strong></div>");
	            // fim do parser
	            }
	        });
		



	          
	 
      	});
  });
 
 
 
 
 </script>

 


</head>

<form class="form-horizontal" name="inscalend" id="ea" method="post" action="">
	<div class="bs-example">
		<ul class="breadcrumb">
        <?php if (!is_null($sessao->getCodunidsel())){  ?> 
	<!--	    <li><a href="<?php //echo Utils::createLink("usuario", "consultaunidade",array('codigo'=>50)); ?>">Consulta </a></li> -->
<?php } ?>
            <li><a href="<?php echo Utils::createLink("calendarioPdi", "listaCalendario"); ?>">Lista calendários </a></li>
			<li class="active">Cadastrar calendário</li>
		</ul>
	</div>
	
	<!-- ALERTAS-->
	<div id="alerta"> </div>
	
    <h3 class="card-title">Cadastrar Calendário</h3><br/>
    <div class="msg" id="msg"> </div>
    <div>
   	        
      <?php if (!is_null($sessao->getCodunidsel())) {?>
      <label>Unidade selecionada: </label><?php echo $unidade->getNomeunidade();?>
      <?php } ?>
    </div>
    
    <table>
    <tr>    
      <td><label>Plano de Desenvolvimento: </label></td>
      <td> <select class="custom-select" name="codDocumento" id="documento-painel" class="sel1">
                <option value="0">Selecione o documento...</option>
                <?php foreach ($lista as $d) : 
                      if ($codcalend!=0 && $c->getDocumento()->getCodigo()==$d->getCodigo()) {?>
                  	  	<option selected value="<?php print $d->getCodigo(); ?>"><?php print $d->getNome().'-'. $d->getAnoinicial(). ' a ' . 
                        $d->getAnofinal() ; ?>
                        </option>
                       <?php } else { ?>
                        <option  value="<?php print $d->getCodigo(); ?>"><?php print $d->getNome().'-'. $d->getAnoinicial(). ' a ' . 
                        $d->getAnofinal() ; ?></option>
                        <?php }  ?>
                <?php endforeach; ?>
            </select>
       </td>  
     </tr>       
     <tr>
       <td><label>Ano da gestão: </label></td>
       <td><input class="form-control"placeholder="Ano" name="anogestao" id="anogestao" type="text" maxlength="4" size="4" value="<?php print $codcalend!=0?$c->getAnogestao():"";?>"/></td>
     </tr>
     <tr>
       <td>
          <label>Período para lançar resultados parciais:</label>
                    
       </td>      
	    <td><input class="form-control"placeholder="Data Inicial" type="text" id="calendario1" name="calendario1" 
                                          class="short"  maxlength="10" size="10"
                                         value="<?php print $codcalend!=0?$c->getDatainianalise():"";?>" /> 	
	    
	       a <input class="form-control"placeholder="Data Final" type="text" id="calendario2" name="calendario2"   class="short" 
	        maxlength="10" size="10" onchange="validarData()"  value="<?php print $codcalend!=0?$c->getDatafimanalise():"";?>" /></td>
	</tr>
	<tr>
	<td> <label>Período para lançar resultados finais:</label></td>
	<td>
		<input class="form-control"placeholder="Data Inicial" type="text" id="calendario3" name="calendario3"
													 class="short"  maxlength="10" size="10" 
													 value="<?php print $codcalend!=0?$c->getDatainianalisefinal():"";?>"/>
		a <input class="form-control"placeholder="Data Final" type="text" id="calendario4" name="calendario4"
													 class="short"  maxlength="10" size="10" 
													 value="<?php print $codcalend!=0?$c->getDatafimanalisefinal():"";?>"/>
	</td>
	</tr>
	
	
	<tr>
	<td> <label>Período para redigir o RAA:</label></td>
	<td>
		<input class="form-control"placeholder="Data Inicial para RAA" type="text" id="calendario5" name="calendario5"
													 class="short"  maxlength="10" size="10" 
													 value="<?php print $codcalend!=0?$c->getDatainiRAA():"";?>"/>
		a <input class="form-control"placeholder="Data Final para RAA" type="text" id="calendario6" name="calendario6"
													 class="short"  maxlength="10" size="10" 
													 value="<?php print $codcalend!=0?$c->getDatafimRAA():"";?>"/>
	</td>
	</tr>
	
	
	<tr>
	<td> <label>Período para elaborar PDU:</label></td>
	<td>
		<input class="form-control"placeholder="Data Inicial para elaborar PDU" type="text" id="calendario7" name="calendario7"
													 class="short"  maxlength="10" size="10" 
													 value="<?php print $codcalend!=0?$c->getDatainielabpdu():"";?>"/>
		a <input class="form-control"placeholder="Data Final para elaborar PDU" type="text" id="calendario8" name="calendario8"
													 class="short"  maxlength="10" size="10" 
													 value="<?php print $codcalend!=0?$c->getDatafimelabpdu():"";?>"/>
	</td>
	</tr>
	
		<tr>
	<td> <label>Período para elaborar painel tático:</label></td>
	<td>
		<input class="form-control"placeholder="Data Inicial para elaborar painel tático" type="text" id="calendario9" name="calendario9"
													 class="short"  maxlength="10" size="10" 
													 value="<?php print $codcalend!=0?$c->getDatainielabpt():"";?>"/>
		a <input class="form-control"placeholder="Data Final para elaborar painel tático" type="text" id="calendario10" name="calendario10"
													 class="short"  maxlength="10" size="10" 
													 value="<?php print $codcalend!=0?$c->getDatafimelabpt():"";?>"/>
	</td>
	</tr>
	<tr>
	<td> <label>Período para alterar PDU:</label></td>
	<td>
		<input class="form-control"placeholder="Data Inicial para elaborar painel tático" type="text" id="calendario11" name="calendario11"
													 class="short"  maxlength="10" size="10" 
													 value="<?php print $codcalend!=0?$c->getDatainialterapdu():"";?>"/>
		a <input class="form-control"placeholder="Data Final para elaborar painel tático" type="text" id="calendario12" name="calendario12"
													 class="short"  maxlength="10" size="10" 
													 value="<?php print $codcalend!=0?$c->getDatafimalterapdu():"";?>"/>
	</td>
	</tr>
	</table> <br/>
<?php 
if   (count($lista)>0) {
    
 ?>
  <input type="button"  value="Gravar" class="btn btn-info" id="gravarcal"  />
  </br></br>
 <?php  } ?>
   
</form>


<?php function prepararDataForm($data){
     $novadata=empty($data)?"":substr($data,8,2)."/".substr($data,5,2)."/".substr($data,0,4);
     return $novadata;
 
}?>