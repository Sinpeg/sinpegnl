<?php
//session_start();
///$BASE_DIR = dirname(__FILE__) . '/';
//require_once $BASE_DIR . '../../classes/unidade.php';
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();

 if ($sessao->getCodUnidade() <> 100000 && !$aplicacoes[56]) { //alterar o numero da app
    echo "Formulário indisponível para o seu perfil!";
    die;
}

$nomeunidade = $sessao->getNomeUnidade();
$codunidade = $sessao->getCodUnidade();
$codestruturado = $sessao->getCodestruturado();

$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$daou = new UnidadeDAO();
$res=$daou->buscaidunidadeRel($codunidade);
$sigla="0";

foreach ($res as $r){
	$sigla=$r['sigla'];
}
?>

<head>   
  <div class="bs-example">
    <ul class="breadcrumb">
      <li class="active">Cadastrar Unidade</li>
    </ul>
  </div>
</head>

<div class="card card-info">
  <div class="card-header">
    <h3 class="card-title">Cadastrar Unidade</h3>
  </div>
  <form class="form-horizontal" name="formunidade" id="formunidade" method="post">
    <table class="card-body">
      <div class="msg" id="msg"></div>
      <tr>
        <td class="coluna1">
          <label for="cadunidade" class="col-sm-6 col-form-label">
            Nome da unidade
          </label>
        </td>
      </tr>
      <tr>
        <td class="coluna2">
          <div class="col-sm-10">
              <input class="form-control" type="text" id="cadunidade"  name="cadunidade" placeholder="Unidade" size="60" autocomplete="off"/>
              <div id="cxsugestao"></div> 
          </div>
        </td>
      </tr>
    </table>
    <div id="insere">
      <table class="card-body">
          <tr>
            <td class="coluna1">
              <label for="sigla" class="col-sm-2 col-form-label">
                Sigla
              </label>
            </td>
          </tr>
          <tr>
            <td class="coluna2">
              <div class="col-sm-10">
                <input class="form-control"type="text" id="sigla" maxlength="50"  name="sigla" size="20" value='' />
              </div>
            </td>
          </tr>
          <tr>
            <td class="coluna1">
              <label for="siafi" class="col-sm-2 col-form-label">SIAFI</label>
            </td>
          </tr>
          <tr>
            <td class="coluna2">
              <div class="col-sm-10"><input class="form-control"type="text" class="form-control" id="siafi" maxlength="8"  name="siafi" size="60" value='' /></div>
            </td>
          </tr>
          <tr>
            <td class="coluna1">
              <label for="perfil" class="col-sm-2 col-form-label">Categoria </label>
            </td>
          </tr>
          <tr>
            <td class="coluna2">
              <div class="col-sm-10">
                <select class="custom-select" name="perfil" id="perfil">
                  <option value="0">Selecione categoria...</option>
                  <option  value="1">Administrativo</option>
                  <option value="2">Acadêmico</option>
                </select>
              </div>
            </td>
          </tr>
          <?php if ($sessao->getCodUnidade()!=100000){ ?>
            <tr>
              <td class="coluna1">
                <label for="tipo" class="col-sm-2 col-form-label">Tipo </label>
              </td>
          </tr>
          <tr>
              <td class="coluna2">
                <div class="col-sm-10">
                  <select class="custom-select" name="tipo" id="tipo">
                      <option value="0">Selecione tipo...</option>
                      <option value="N">Unidade</option>
                      <option value="T">Unidade desativada</option>
                      <?php if ($sigla!="0" && $sigla=="NMT"){?>
                      <option value="L">Subunidade de prestação de serviço de saúde do NMT</option>
                      <?php } ?>
                      <?php if ($sigla!="0" && $sigla=="IFCH"){?>
                          <option value="P">Subunidade de prestação de serviço de saúde do IFCH</option>
                      <?php } ?>
                      <?php if ($sigla!="0" && $sigla=="ICS"){?>
                              <option value="P">Subunidade de prestação de serviço de saúde do ICS</option>
                              <option value="E">Local externo de prestação de serviço de saúde do ICS</option>
                              <option value="I">Local interno de prestação de serviço de saúde do ICS</option>
                      <?php } ?>
                  </select>
                </div>
              </td>
            </tr>
          <?php }else{ ?>
            <tr>
              <td class="coluna1">
                <label for="tipo" class="col-sm-2 col-form-label">Tipo </label>
              </td>
          </tr>
          <tr>
              <td class="coluna2">
                <div class="col-sm-10">
                  <select class="custom-select" name="tipo" id="tipo">
                    <option value="0">Selecione tipo...</option>
                    <option value="N">Unidade </option>
                    <option value="T">Unidade desativada</option>
                    <option value="L">Subunidade de prestação de serviço de saúde do NMT</option>
                    <option value="P">Subunidade de prestação de serviço de saúde do IFCH</option>
                    <option value="P">Subunidade de prestação de serviço de saúde do ICS</option>
                    <option value="E">Local externo de prestação de serviço de saúde do ICS</option>
                    <option value="I">Local interno de prestação de serviço de saúde do ICS</option>
                  </select>
                </div>
              </td>
            </tr>
            <tr>
              <td class="coluna1">
                <label for="unidaderesp" class="col-sm-2 col-form-label">
                  <input class="form-check-input" type="checkbox" value="1" id="ehsubunidade" name="ehsubunidade">É subunidade?
                </label>
              </td>
          </tr>
          <tr>
              <td class="coluna2">
                <div class="col-sm-10">
                <div id="unidaderesp"></div>
              </td>
            </tr>
          <?php } ?>
        </table>
      <div class="card-body" align="center">
        <input type="button" id="gravar" value="Gravar"  class="btn btn-info"/>
      </div>      
    </div>         
  </form>
</div>

<div id="altera" style="margin-top:15px;"></div>

<script>
$("#gravar").click(function(){

	$("div#msg").empty();
	if($("#cadunidade").val() == ""){
	    $("div#msg").html("O campo unidade é obrigatório!");
	    return ;
	}else if ($("#tipo").val() == "0"){
	    $("div#msg").html("O campo tipo de unidade é obrigatório!");
	    return ;
	}else if ($("#perfil").val() == "0"){
	    $("div#msg").html("O campo categoria da unidade é obrigatório!");
	    return ;
	
  
  <?php if ($sessao->getCodUnidade()==100000){ ?>

      } else if (($("#ehsubunidade").is(':checked')) && ($("#uresp").val()=="0")){
        $("div#msg").html("Informe a unidade responsável da subunidade!");
        return;
      }else if ((!$("#ehsubunidade").is(':checked')) && ($("#sigla").val() == "")){
        $("div#msg").html("Informe a sigla para unidade responsável!");
        return;
      }else if ((!$("#ehsubunidade").is(':checked')) && ($("#siafi").val() == "")){
        $("div#msg").html("Informe a siafi para unidade responsável!");
        return;
	<?php } ?>
	} else{
    $("div#msg").empty();

    $.ajax({
      type: "POST",
      url: "ajax/unidade/gravaunidade.php",
        data: $("form[name=formunidade]").serialize(),


      success: function(response){
        $("div#msg").html(response);
          }
    });
	}
});

$("#ehsubunidade").change(function(){
  if ($(this).is(":checked")){
    $.ajax({
      type: "POST",
      url: "ajax/unidade/buscarunidaderesp.php",
        data: $("form[name=formunidade]").serialize(),
      success: function(response){
        $("div#unidaderesp").html(response);
      }
    });
  }else{
    $("div#unidaderesp").empty();
  }
});

$(function () {
  bsCustomFileInput.init();
});
</script>