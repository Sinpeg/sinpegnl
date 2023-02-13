<?php
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../classes/sessao.php';
require_once '../../classes/curso.php';
require_once '../../classes/unidade.php';
require_once '../../classes/campus.php';
require '../../util/Utils.php';
require_once '../../dao/campusDAO.php';

require_once '../../dao/cursoDAO.php';
require_once '../../dao/unidadeDAO.php';

session_start();
$sessao = new Sessao();
$sessao = $_SESSION['sessao'];
if (!isset($_SESSION["sessao"])) {
    echo "Sua sessão expirou, faça login novamente!";
    exit();
}
$anobase=$sessao->getAnobase();


$nomecurso=$_POST["cxcurso"];
$dao=new CursoDAO();
$rows=$dao->cursoPorNomeExato($nomecurso, $anobase);
 foreach ($rows as $r){
 	$codcursosel=$r['CodCurso'];
 }
$us=array();
$i=0;
/* ANTES
$string='
<form name="altera" method="post" action="index.php?modulo=curso&acao=alteraCurso">
<input type="hidden" name="codigocur" value="'.$codcursosel.'"/>
<input type="submit" class="btn btn-primary" name="button" value="Alterar"/>
</form>';


  echo $string;   */
   
$daocurso=new CursoDAO();
$recc=$daocurso->listacursocenso();

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

$rows=$daocurso->buscaCurso($codcursosel);

$string='<form name="altera" id="altera" method="post"><table>';
foreach ($rows as $r){
	  $string.='<tr><td>Novo Nome do Curso</td>
           <td><input class="inputs" type="text" id="cxcursoalt" size="80"  name="cxcursoalt" placeholder="Curso" autocomplete="off" value="'.$r['NomeCurso'].'"/>
               <input type="hidden" id="codigo"   name="codigo" size="100" value="'.$r['CodCurso'].'" /></td></tr>
      <tr>
                <td>Unidade</td>
                <td>
                <select name="unidade" id="unidadealt">';
                   for ($ind=1;$ind<=count($us);$ind++){
                       $string.='<option '.(($r['CodUnidade']==$us[$ind]->getCodunidade())?"selected":"").'   value="'.$us[$ind]->getCodunidade().'">'.$us[$ind]->getNomeunidade().'</option>';
                  } 
                      $string.='</select>';
                      $string.='</td></tr>';
                      $string.='<tr><td>Campus</td>';
                      $string.='<td><select name="campus" id="campusalt">';
                      $string.='<option value="0">Selecione campus...</option>';
                   for ($ind=1;$ind<=count($cs);$ind++){
                       $teste=$r['CodCampus']==$cs[$ind]->getCodigo()?"selected":"";
                       $string.='<option '.$teste.' value="'.$cs[$ind]->getCodigo().'">'.$cs[$ind]->getNome().'</option>';
                      } 
                      $string.='</select></td></tr>';
                      $string.='<tr><td>Número da matriz no SIGAA (Graduação)</td>'.
 '<td><input type="text" id="codsigaaalt" maxlength="8"  name="codsigaa" size="60" value="'.$r['CodSigaa'].'" /></td></tr>'.
                   '<tr><td>Número do código EMEC (Graduação)</td><td>'.
                 '<select name="codemec" id="codemecalt">
                  <option value="0">Selecione código EMEC para curso de Graduação...</option>';
                      foreach ($recc as $s){
                          $teste=($r['CodEmec']==$s['idCursocenso'])?"selected":"";
                          $string.='<option '.$teste.' value="'.$s['idCursocenso'].'">'.$s['idcursoinep']." - ".$s['nome'].'</option>';
                      } 
                      $string.=' </select></td></tr>'.
                      '<tr><td>Nível</td>'.
                      "<td><select name='nivel'  id='nivelalt'>";
                      $teste=($r['CodNivel']==1)?"selected":"";
                      $string.="<option ".$teste." value='1'>Graduação</option>";
                      $teste=($r['CodNivel']==2)?"selected":"";
                      $string.="<option ".$teste." value='2'>Pós-graduação</option>";
                      $teste=($r['CodNivel']==3)?"selected":"";
                      $string.="<option ".$teste." value='3'>Básico</option>".
                      '</select></td></tr>'.
             '<tr><td>Situação </td><td><select name="situacao"  id="situacaoalt">';
              $teste=($r['Situacao']=="A")?"selected":"";
              $string.='<option '.$teste.' value="A">Ativo</option>';
              $teste=($r['Situacao']=="I")?"selected":"";
              $string.='<option '.$teste.' value="I">Inativo</option>'.
             '</select></td></tr>'.
             '<tr><td>Formato </td><td><select name="formato"  id="formatoalt">'.
             '<option value="0">Selecione formato...</option>';
              $teste=($r['Formato']=="P")?"selected":"";
              $string.='<option '.$teste .' value="P">Presencial</option>';
              $teste=($r['Formato']=="D")?"selected":"";
              $string.='<option '.$teste.' value="D">À distância</option></select>'.
             '</td></tr>'.
             '<tr><td>Modalidade</td>'.
             '<td><select name="modalidade"  id="modalidadealt">'.
             '<option  value="0">Selecione modalidade...</option>';
              $teste=($r['Modalidade']=="GBA")?"selected":"";
              $string.='<option '.$teste.' value="GBA">Bacharelado</option>';
              $teste=($r['Modalidade']=="GLI")?"selected":"";
              $string.='<option '.$teste.' value="GLI">Licenciatura</option>';
              $teste=$r['Modalidade']=="BAS"?"selected":"";
              $string.='<option '.$teste.' value="BAS">Área básica</option>';
              $teste=$r['Modalidade']=="GTE"?"selected":"";
              $string.='<option '.$teste.' value="GTE">Tecnológico</option>';
              $teste=$r['Modalidade']=="FOR"?"selected":"";
              $string.='<option '.$teste.' value="FOR">Tecnológico</option>';
              $teste=$r['Modalidade']=="DOU"?"selected":"";
              $string.='<option '.$teste.' value="DOU">Doutorado</option>';
              $teste=$r['Modalidade']=="MES"?"selected":"";
              $string.='<option '.$teste.' value="MES">Mestrado</option>'.
             '</select></td></tr>';
}//foreach
$string.='</table>'.
'<input type="button" id="alteracurso" value="Gravar"  class="btn btn-primary"/>&nbsp;&nbsp;'.
'<a href="'.Utils::createLink('curso', 'incluiCurso').'">'. 
'<input type="button" id="voltar" value="Voltar"  class="btn btn-primary"/></a></form>';
echo $string;
//
?>
<script>

$("#alteracurso").click(function(){
$("div#msg").empty();
	if($("#cxcursoalt").val() == ""){
	    $("div#msg").html("O campo novo nome do curso é obrigatório!");
	    return ;
	}else if ($("#unidadealt").val() == "0"){
	    $("div#msg").html("O campo unidade é obrigatório!");
	    return ;
	}if($("#campusalt").val() == "0"){
	    $("div#msg").html("O campo campus é obrigatório!");
	    return ;
	}else  if ($("#nivelalt").val() == "0"){
	    $("div#msg").html("O campo nivel do curso é obrigatório!");
	    return ;
	}if ($("#situacaoalt").val() == "0"){
	    $("div#msg").html("O campo tipo de unidade é obrigatório!");
	    return ;
	}if ($("#formatoalt").val() == "0"){
	    $("div#msg").html("O campo formato é obrigatório!");
	    return ;
	}if ($("#modalidadealt").val() == "0"){
	    $("div#msg").html("O campo modalidade é obrigatório!");
	    return ;
	}

	else{
      $("div#msg").empty();

		 $.ajax({
			    type: "POST",
			    url: "ajax/curso/gravacurso.php",
		        data: $("form[name=altera]").serialize(),


			    success: function(response){
				    $("div#msg").html(response);
			       }
	      });
	}






});
</script>



