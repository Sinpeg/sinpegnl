<?php

//require_once '../../dao/PDOConnectionFactory.php';
//require_once '../../classes/sessao.php';
//require_once '../../classes/unidade.php';
//require_once '../../dao/unidadeDAO.php';
$sessao = new Sessao();
$sessao = $_SESSION['sessao'];
if (!isset($_SESSION["sessao"])) {
    echo "Sua sessão expirou, faça login novamente!";
    exit();
}


$codigo=$_POST["codigocur"];
$daocurso=new CursoDAO();
$recc=$daocurso->listacursocenso();

$dao=new UnidadeDAO();
$i=0;

$daou = new UnidadeDAO();
if ( $sessao->getCodUnidade()!=100000){
   $rowr=$daou->buscaidunidadeRel($sessao->getCodUnidade());
}else{
   $rowr=$daou->ListaResponsavel();

}


foreach ($rowr as $s){// array de unidades responsáveis
	//if ($s['CodUnidade']!=941 && $s['CodUnidade']!=942 && $s['CodUnidade']!=939 && $s['CodUnidade']!=940  && $s['CodUnidade']!=938 && $s['CodUnidade']!=1781 ){//eliminando reitoria e vice-reitoria
	      	$i++;
	      	$u=new Unidade();
	      	$u->setNomeunidade($s['NomeUnidade']);
	      	$u->setCodunidade($s['CodUnidade']);
	      	$u->setCodestruturado($s['hierarquia_organizacional']);
	      	$u->setIdunidaderesponsavel($s['id_unidade']) ;
	      	$us[$i]=$u;
	//}

}
$i=0;
$daocam=new CampusDAO();
$rowr= $daocam->Lista();
foreach ($rowr as $s){// array de unidades responsáveis
	//if ($s['CodUnidade']!=941 && $s['CodUnidade']!=942 && $s['CodUnidade']!=939 && $s['CodUnidade']!=940  && $s['CodUnidade']!=938 && $s['CodUnidade']!=1781 ){//eliminando reitoria e vice-reitoria
	      	$i++;
	      	$c=new Campus();
	      	$c->setNome($s['Campus']);
	      	$c->setCodigo($s['CodCampus']);
	      	$cs[$i]=$c;
	//}

}
$cont=$i;

$rows=$daocurso->buscaCurso($codigo);
?>
<head>

	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active"><a href="<?php echo Utils::createLink('curso', 'incluiCurso'); ?>">Inserir Curso </a> >> <a href="#" >Editar Curso</a></li>
		</ul>
	</div>
</head>
<form class="form-horizontal" name="formcurso" id="formcurso" method="post">
    <h3 class="card-title">Alterar Curso</h3>
    <div class="msg" id="msg"></div>
<table>

<?php

foreach ($rows as $r){?>
	  <tr><td>Nome do Curso</td>
           <td><input class="form-control" type="text" id="cxcurso" size="80"  name="cxcurso" placeholder="Curso" autocomplete="off" value="<?php  echo $r['NomeCurso'];?>"/>
               <input class="form-control"type="hidden" id="codigo"   name="codigo" size="100" value="<?php  echo $r['CodCurso'];?>" /></td></tr>

      <tr>
                <td>Unidade</td>
                <td>
                <select class="custom-select" name="unidade" id="unidade">
                     <option value="0">Selecione unidade...</option>
                   <?php for ($ind=1;$ind<=count($us);$ind++){?>
                      <option <?php echo $r['CodUnidade']==$us[$ind]->getCodunidade()?"selected":""; ?>   value="<?php echo $us[$ind]->getCodunidade(); ?>"><?php echo $us[$ind]->getNomeunidade(); ?>.</option>
                      <?php } ?>
                </select>
                </td>
            </tr>
            <tr>
                <td>Campus</td>
                <td>
                 <select class="custom-select" name="campus" id="campus">
                     <option value="0">Selecione campus...</option>
                   <?php for ($ind=1;$ind<=count($cs);$ind++){?>
                      <option <?php echo $r['CodCampus']==$cs[$ind]->getCodigo()?"selected":""; ?> value="<?php echo $cs[$ind]->getCodigo(); ?>"><?php echo $cs[$ind]->getNome(); ?>.</option>
                      <?php } ?>
                </select>
                </td>
            </tr>
             <tr>
                <td>Número da matriz no SIGAA (Graduação)</td>
                <td><input class="form-control"type="text" id="codsigaa" maxlength="8"  name="codsigaa" size="60" value='<?php echo $r['CodSigaa']; ?>' /></td>
            </tr>
            <tr>
                <td>Número do código EMEC (Graduação)</td>
                <td>

                 <select class="custom-select" name="codemec" id="codemec">
                     <option value="0">Selecione código EMEC...</option>
                   <?php foreach ($recc as $s){?>
                      <option <?php echo $r['CodEmec']==$s['idCursocenso']?"selected":""; ?> value="<?php echo $s['idCursocenso']; ?>"><?php echo $s['idcursoinep']." - ".$s['nome']; ?>.</option>
                      <?php } ?>
                </select>



                </td>
            </tr>
              <tr>
                <td>Nível </td>


                <td><select class="custom-select" name="nivel"  id="nivel">
                            <option  value="0">Selecione nível...</option>
                            <option <?php echo $r['CodNivel']==1?"selected":""; ?> value="1">Graduação</option>
                            <option <?php echo $r['CodNivel']==2?"selected":""; ?> value="2">Pós-graduação</option>
                            <option <?php echo $r['CodNivel']==3?"selected":""; ?>value="3">Básico</option>
                      </select>
                      </td>

            </tr>
             <tr>
                <td>Situação </td>


                <td><select class="custom-select" name="situacao"  id="situacao">
                            <option <?php echo $r['Situacao']=="A"?"selected":""; ?> value="A">Ativo</option>
                            <option <?php echo $r['Situacao']=="I"?"selected":""; ?> value="I">Inativo</option>


                      </select>
                      </td>

            </tr>

  <tr>
                <td>Formato </td>
               <td><select class="custom-select" name="formato"  id="formato">
                            <option value="0">Selecione formato...</option>
                            <option <?php echo $r['Formato']=="P"?"selected":""; ?> value="P">Presencial</option>
                            <option <?php echo $r['Formato']=="D"?"selected":""; ?>  value="D">À distância</option>
                     </select>
                      </td>

            </tr>
              <tr>
                <td>Modalidade </td>
               <td><select class="custom-select" name="modalidade"  id="modalidade">
                            <option  value="0">Selecione modalidade...</option>
                            <option  <?php echo $r['Modalidade']=="GBA"?"selected":""; ?>  value="GBA">Bacharelado</option>
                            <option  <?php echo $r['Modalidade']=="GLI"?"selected":""; ?> value="GLI">Licenciatura</option>
                             <option <?php echo $r['Modalidade']=="BAS"?"selected":""; ?> value="BAS">Área básica</option>
                             <option <?php echo $r['Modalidade']=="GTE"?"selected":""; ?> value="GTE">Tecnológico</option>
                            <option  <?php echo $r['Modalidade']=="FOR"?"selected":""; ?> value="FOR">Tecnológico</option>
                            <option <?php echo $r['Modalidade']=="DOU"?"selected":""; ?> value="DOU">Doutorado</option>
                            <option <?php echo $r['Modalidade']=="MES"?"selected":""; ?> value="MES">Mestrado</option>


                     </select>
                      </td>

            </tr>

	<?php }//foreach?>


</table>
</form>
       <input type="button" id="gravacurso" value="Gravar"  class="btn btn-info"/>

  <a href="<?php echo Utils::createLink('curso', 'incluiCurso'); ?>"> <input type="button" id="voltar" value="Voltar"  class="btn btn-info"/></a>


<script>

$("#gravacurso").click(function(){
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
	}

	else{
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



