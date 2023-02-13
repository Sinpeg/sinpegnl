<?php
//session_start();
///$BASE_DIR = dirname(__FILE__) . '/';
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();

if ($sessao->getCodUnidade() <> 100000 && !$aplicacoes[57]) { //alterar o numero da app
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

if ( $sessao->getCodUnidade()!=100000){
  $res=$daou->buscaidunidadeRel($sessao->getCodUnidade());
}else{
  $res=$daou->ListaResponsavel();
}

$daocur=new CursoDAO();
$recc=$daocur->listacursocenso();

$daoc=new CampusDAO();
$rec=$daoc->Lista();
$sigla="0";


?>

<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active">Cadastrar curso</li>
		</ul>
	</div>

  <script type="text/javascript" src="webroot/js/jquery-ui-1.10.3/js/jquery-1.9.1.js"></script>
  <script type="text/javascript" src="webroot/js/jquery-ui-1.10.3/js/jquery-ui-1.10.3.custom.js"></script>

  <!-- Bootstrap 4 -->
  <script src="novo_layout/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="novo_layout/dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes <script src="novo_layout/dist/js/demo.js"></script>-->	<script type="text/javascript" src="webroot/js/jquery-ui-1.10.3/js/jquery-1.9.1.js"></script>
  <script type="text/javascript" src="webroot/js/jquery-ui-1.10.3/js/jquery-ui-1.10.3.custom.js"></script>
</head>

<div class="card card-info">
  <form class="form-horizontal" name="formcurso" id="formcurso" method="post">
    <h3 class="card-title">Cadastrar Curso</h3>
    <div class="msg" id="msg"></div>
    <table>
      <tbody>
        <tr>
          <td class="coluna1">Nome do Curso</td>
          <td class="coluna2">
            <input class="form-control" type="text" id="cxcurso"  name="cxcurso" placeholder="Curso" autocomplete="off"/>
            <div id="suggesstion-box2"></div> 
          </td>
        </tr>
        <tr><td></td><td></td></tr>
      </tbody>
    </table>

    <div id="insere">
      <table>
        <tbody>
          <tr>
            <td class="coluna1">Unidade</td>
            <td class="coluna2">
              <select class="custom-select" name="unidade" id="unidade">
                <option value="0">Selecione unidade...</option>
                <?php foreach ($res as $r){?>
                  <option value="<?php echo $r['CodUnidade']; ?>"><?php echo $r['NomeUnidade']; ?>.</option>
                <?php } ?>
              </select>
            </td>
          </tr>
          <tr>
            <td class="coluna1">Campus</td>
            <td class="coluna2">
              <select class="custom-select" name="campus" id="campus">
                <option value="0">Selecione campus...</option>
                <?php foreach ($rec as $r){?>
                  <option value="<?php echo $r['CodCampus']; ?>"><?php echo $r['Campus']; ?>.</option>
                <?php } ?>
              </select>
            </td>
          </tr>
          <tr>
            <td class="coluna1">Número da matriz no SIGAA (Graduação)</td>
            <td class="coluna2"><input class="form-control"type="text" id="codsigaa" maxlength="8"  name="codsigaa" size="60" value='' /></td>
          </tr>
          <tr>
            <td class="coluna1">Código EMEC (Graduação)</td>
            <td class="coluna2">
              <select class="custom-select" name="codemec" id="codemec">
                <option value="0">Selecione código EMEC para curso de graduação...</option>
                <?php foreach ($recc as $r){?>
                  <option value="<?php echo $r['idCursocenso']; ?>"><?php echo $r['idcursoinep']." - ".$r['nome']; ?>.</option>
                <?php } ?>
              </select>
            </td>
          </tr>
          <tr>
            <td class="coluna1">Nível </td>
            <td class="coluna2">
              <select class="custom-select" name="nivel"  id="nivel">
                <option value="0">Selecione nível...</option>
                <option value="1">Graduação</option>
                <option value="2">Pós-graduação</option>
                <option value="3">Básico</option>
              </select>
            </td>
          </tr>
          <tr>
            <td class="coluna1">Situação </td>
            <td class="coluna2">
              <select class="custom-select" name="situacao"  id="situacao">
                <option selected value="A">Ativo</option>
                <option value="I">Inativo</option>
              </select>
            </td>
          </tr>
          <tr>
            <td class="coluna1">Formato </td>
            <td class="coluna2">
              <select class="custom-select" name="formato"  id="formato">
                <option value="0">Selecione formato...</option>
                <option value="P">Presencial</option>
                <option value="D">À distância</option>
              </select>
            </td>
          </tr>
          <tr>
            <td class="coluna1">Modalidade </td>
            <td class="coluna2">
              <select class="custom-select" name="modalidade"  id="modalidade">
                <option value="0">Selecione modalidade...</option>
                <option value="GBA">Bacharelado</option>
                <option value="GLI">Licenciatura</option>
                <option value="BAS">Área básica</option>
                <option value="GTE">Tecnológico</option>
                <option value="FOR">Básico</option>
                <option value="DOU">Doutorado</option>
                <option value="MES">Mestrado</option>
              </select>
            </td>
          </tr>
        </tbody>
      </table>
      <br>
      <input type="button" id="gravar" value="Gravar"  class="btn btn-info"/>
    </div>
  </form>
</div>

<div id="altera"></div>

<script>
  $("#gravar").click(function(){
    $("div#msg").empty();
    if($("#cxcurso").val() == ""){
        $("div#msg").html("O campo curso é obrigatório!");
        return ;
    }else if ($("#unidade").val() == "0"){
        $("div#msg").html("O campo unidade é obrigatório!");
        return ;
    }if($("#campus").val() == "0"){
        $("div#msg").html("O campo campus é obrigatório!");
        return ;
    }else  if ($("#nivel").val() == "0"){
        $("div#msg").html("O campo nivel do curso é obrigatório!");
        return ;
    }if ($("#situacao").val() == "0"){
        $("div#msg").html("O campo tipo de unidade é obrigatório!");
        return ;
    }if ($("#formato").val() == "0"){
        $("div#msg").html("O campo formato é obrigatório!");
        return ;
    }if ($("#modalidade").val() == "0"){
        $("div#msg").html("O campo modalidade é obrigatório!");
        return ;
    } else{
      $("div#msg").empty();

      $.ajax({
        type: "POST",
        url: "ajax/curso/gravacurso.php",
        data: $("form[name=formcurso]").serialize(),
        success: function(response){
          $("div#msg").html(response);
        }
      });
    }
  });
</script>

