<?php

require_once '../../classes/sessao.php';

sleep(1);
session_start();
$sessao = $_SESSION["sessao"];
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../dao/unidadeDAO.php';
$parametro=$_POST["keyword"];

$daouni=new UnidadeDAO();

if(!empty($parametro)) {
  if ($sessao->getCodUnidade()==100000){
    $result = $daouni->unidadePorStr($parametro, 15);
  }else{
    $hier=$sessao->getCodestruturado();
    //echo $sessao->getCodUnidade()." kkk ".$parametro."  ".$sessao->getCodestruturado();die;

    $result = $daouni->subunidadePorStr($parametro, $hier, 15);
  }

  if(!empty($result)) { //compoe a lista para selecao da unidade?>
    <ul class="itens" id="unid-list1">
      <?php foreach($result as $uni) { ?>
        <li>
          <span class="subitem"><?php echo $uni["NomeUnidade"]; ?></span>
        </li>
      <?php } ?>
    </ul>
<?php 
  }
} ?>

  <script>
  $(document).ready(function(){
    $('ul.itens li').click(function(e){
      var item=$(this).find("span.subitem").text();
      $("#cadunidade").val(item);
      $("#cadunidade").text(item);
      $("#cxsugestao").hide();
      $("#cadunidade").focus();
      $("#insere").hide();
      $("#altera").show();
      // $("#altera").load('ajax/unidade/buscaunisel.php?parametro='+ $("#cxunidade").text(item) );
      $.ajax({
        type: "POST",
        url: "ajax/unidade/buscaunisel.php",
        data: $("form[name=formunidade]").serialize(),

        success: function(response){
          $("div#altera").html(response);
        }
      });

      // alert("dfkkd "+item.replace(/ /g, "*"));
      //  $("#plano").load('ajax/documentopdi/buscadocs.php?parametro='+ item.replace(/ /g, "*") );
    });

    /*$('
    #pdi-resultado .inputs').bind('input', function(){
    alert($("cxunidade").val());
    console.log('this actually works');
    });*/
  });
</script>
