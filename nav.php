<?php
//$sessao = new Sessao();
$sessao = $_SESSION["sessao"];

if(isset($_POST["inputAnobase"])){
	$sessao->setAnobase($_POST["inputAnobase"]);	
}
$anobase = $sessao->getAnobase();
$nomeUnidade = $sessao->getNomeUnidade();
$codunidade = $sessao->getCodUnidade();
$usuario = $sessao->getResponsavel();
?>

<!-- <div class="row"> -->
<!--   <div class="col-md-4">.col-md-4</div> -->
<!--   <div class="col-md-4">.col-md-4</div> -->
<!--   <div class="col-md-4">.col-md-4</div> -->
<!-- </div> -->

<!-- <div class="row" style="background-color:#eceff1; position:absolute;padding-top: 150px;width:100%;height:181px;">-->
<div class="row" style="background-color:#eceff1; padding-top: 7px;height:32px;margin-top: -0.1%;">
    <div class="col-md-2 text-center">
        Usuário: <?php echo $usuario ?>
    </div>
    <div class="col-md-5 text-center">
        Unidade: <?php echo $nomeUnidade; ?>
    </div>

    <form id="formAnobase" action="#" method="post">
    <div class="col-md-2 text-center">
        Ano Base: 
       <input name="inputAnobase" class=' datepicker' style="font-size: 8pt; width:40px;" onclick="ocultar()" maxlength="4" size="4" value= " <?php echo $anobase;?>" id="datepickerYear"/>
    </div>
    </form>	
    
    <div class="col-md-2 text-center">
       Código da Unidade: <?php echo $codunidade; ?>
    </div>

    <div class="col-md-1 text-center">
        <strong><a href="logout.php" id="sair">Sair</a></strong>
    </div>
</div>

<script type='text/javascript'>

$(function(){
    $('#datepickerYear').datepicker({
    	changeMonth: false,
        changeYear: true,        
        showButtonPanel: true,
        yearRange: '2018:2030',
        dateFormat: 'yy',
        onClose: function(dateText, inst) { 		        
   		}
    });
});


function atualizaAno(){
	$('#datepickerYear').val($('.ui-datepicker-year').val());
	$("#formAnobase").submit();	
}

  $('#datepickerYear').on({
	  click: function () {
		  $(".ui-datepicker-calendar").hide();
	      $(".ui-datepicker-month").hide();
	      $(".ui-datepicker-current").hide();
	      $(".ui-icon-circle-triangle-w").hide();
	      $(".ui-icon-circle-triangle-e").hide();
	      $('.ui-datepicker-year').attr('onChange', 'selectAno();');
	      $('.ui-datepicker-close').attr('onClick', 'atualizaAno();');
	      //$('.ui-datepicker-year').val($('#datepickerYear').val());
	      $( '.ui-datepicker-title' ).css( 'color','black' );	      	     
      }
  
  			 
  });
  
  function selectAno(){
	  $(".ui-datepicker-calendar").hide();
      $(".ui-datepicker-month").hide();
      $(".ui-datepicker-current").hide();
      $(".ui-icon-circle-triangle-w").hide();
      $(".ui-icon-circle-triangle-e").hide();
      $('.ui-datepicker-year').attr('onChange', 'selectAno();');
      $('.ui-datepicker-close').attr('onClick', 'atualizaAno();');
      $( '.ui-datepicker-title' ).css( 'color','black' );	
	}

	      
</script>