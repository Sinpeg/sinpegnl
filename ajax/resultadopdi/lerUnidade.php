<?php

sleep(1);
session_start();
$sessao = $_SESSION["sessao"];
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../dao/unidadeDAO.php';
$parametro=$_POST["keyword"];
$daouni=new UnidadeDAO();

if(!empty($parametro)) {
  $result = $daouni->unidadePorStr($parametro, 15);
  if(!empty($result)) { //compoe a lista para selecao da unidade?>
    <ul class="itens" id="unid-list1">
      <?php foreach($result as $uni) { ?>
      <li>
        <span class="subitem"><?php echo $uni["NomeUnidade"]; ?></span>
      </li> 
      <?php  } ?>
    </ul>
<?php }
} ?>

<script>
  $(document).ready(function() {
    $('ul.itens li').click(function(e) { 
      var item=$(this).find("span.subitem").text();
      $("#cxunidade").val(item);
      $("#cxunidade").text(item);
      $("#cxsugestao").hide();
        // alert("dfkkd "+item.replace(/ /g, "*"));
      $("#plano").load('ajax/documentopdi/buscadocs.php?parametro='+ item.replace(/ /g, "*") );
    });

      /*$('#pdi-resultado .inputs').bind('input', function(){
        alert($("cxunidade").val());
      console.log('this actually works');
      });*/
        
    /*   $("#cxunidade").change(function(){
          $("#plano").load('ajax/documentopdi/buscadocs.php?parametro='+ $(this).text() );
    }); */
    
  });
</script>
