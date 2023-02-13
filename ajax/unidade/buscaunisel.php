<?php
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../classes/sessao.php';
require_once '../../classes/unidade.php';
require_once '../../dao/unidadeDAO.php';
session_start();
$sessao = new Sessao();
$sessao = $_SESSION['sessao'];
if (!isset($_SESSION["sessao"])) {
    echo "Sua sessão expirou, faça login novamente!";
    exit();
}

$nomeUnidade=$_POST["cadunidade"];
$dao=new UnidadeDAO();
$rows=$dao->buscarUnidadeByNome($nomeUnidade);

$us=array();
$i=0;

$rowr=$dao->ListaResponsavel();

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

$cont=$i;

$string=	'<div class="card">
				<form id = "formunidade" name="altera" method="post" action=""><table class="card-body">';

foreach ($rows as $r){
	$string.=   '<tr>
					<td class="coluna1">Novo nome da unidade</td>
				</tr>
				<tr>
					<td class="coluna2">
						<input type="hidden" name="codigouni" value="'.$r['CodUnidade'].'"/>'.
						'<input type="text" name="cadunidadealt" size=80 value="'.$r['NomeUnidade'].'"/>
					</td>
				</tr>';

	$tipo=$r['TipoUnidade'];
	$tipo1=$tipo=="N"?"selected":"";
	$tipo2=$tipo=="T"?"selected":"";
	$tipo3=$tipo=="L"?"selected":"";
	$tipo4=(($tipo=="P") && ($r['unidade_responsavel']==302))?"selected":"";
	$tipo4=(($tipo=="P") && ($r['unidade_responsavel']==380))?"selected":"";
	$tipo6=$tipo=="E"?"selected":"";
	$tipo7=$tipo=="I"?"selected":"";
	$perfil1=$r['perfil']==1?"selected":"";
	$perfil2=$r['perfil']==2?"selected":"";
	
	$ehsubunidade=($r['unidade_responsavel']>1)?"checked":"";
	
	$string.=	'<tr>
					<td class="coluna1">Sigla</td>
				</tr>
				<tr>
					<td class="coluna2"><input type="text" id="sigla1" style="width:200px;"  name="sigla" size="20" value="'.$r["sigla"].'" /></td>
				</tr>
				<tr>
					<td class="coluna1">SIAFI</td>
				</tr>
				<tr>
					<td class="coluna2"><input type="text" id="siafi1"  style="width:200px;" name="siafi" size="80" value="'.$r["siafi"].'" /></td>
				</tr>
				<tr>
					<td class="coluna1">Categoria </td>
				</tr>
				<tr>
					<td class="coluna2">
						<select name="perfil" id="perfil1">
							<option value="0">Selecione categoria...</option>
							<option  value="1"'.$perfil1.'>Administrativo</option>
							<option value="2"'.$perfil2.'>Acadêmico</option>
						</select>
					</td>
				</tr>';
		
	$string.='<tr>
				<td class="coluna1">Tipo </td>
			</tr>
			<tr>
				<td class="coluna2">
					<select name="tipo" id="tipo1">
						<option  value="N" '.$tipo1.'>Unidade </option>
						<option  value="T"  '.$tipo2.'>Unidade desativada</option>';

	if ($sessao->getCodUnidade()==1644 || $sessao->getCodUnidade()==100000) {            
		$string.= '<option  value="L"  '.$tipo3.'>Subunidade de prestação de serviço de saúde do NMT</option> ';
	}
	
	if ($sessao->getCodUnidade()==270 || $sessao->getCodUnidade()==100000) { 
		$string.=	'<option  value="P"  '.$tipo4.'>Subunidade de prestação de serviço de saúde do ICS/IFCH</option>
					<option  value="E"  '.$tipo6.'>Local externo de prestação de serviço de saúde do ICS</option>
					<option  value="I"  '.$tipo7.'>Local interno de prestação de serviço de saúde do ICS</option>';
	}
	
	if ($sessao->getCodUnidade()==270 || $sessao->getCodUnidade()==100000) { 
			$string.= '<option  value="P"  '.$tipo4.'>Subunidade de prestação de serviço de saúde do ICS/IFCH</option>';
	}
	
	$string.= '</select></td></tr> ';

	if ($sessao->getCodUnidade()==100000){
		$string.=	'<tr>
						<td class="coluna1">
							É subunidade?
						</td>
					</tr>
					<tr>
						<td class="coluna2">
							<input type="checkbox"'.$ehsubunidade.' value="1" id="ehsubunidadealt" name="ehsubunidadealt">
						</td>
		
						<td>
							<div id="unidaderesp2">';
		$urespon="";

		if ($ehsubunidade=="checked"){
			$string.= 	'<select name="uresp" id="uresp1">';
			for ($i=1;$i<=$cont;$i++){ 
				if ($us[$i]->getIdunidaderesponsavel()==$r['unidade_responsavel']){
				//$urespon=substr($r['hierarquia_organizacional'],0,strlen($us[$i]->getCodestruturado()))==$us[$i]->getCodestruturado()?"selected":"";
				$urespon="selected";
				
				}else{
				$urespon="";
				}
			
				$string.='<option  value="'.$us[$i]->getIdunidaderesponsavel().'" '.$urespon.'>'.$us[$i]->getNomeunidade().'</option>';		
			}         
			$string.= '</select>';
		}
	}else{
			
	}//if
		
	$string.='</div></td></tr></div>';
}//foreach
												
$string.='</table><div class="card-body"><input type="button" id="botaltera" class="btn btn-primary" name="button" value="Alterar"/></div></form> ';

echo $string;   

?>
<script>
	$("#ehsubunidadealt").change(function(){

	if ($(this).is(":checked")){
		$.ajax({
				type: "POST",
				url: "ajax/unidade/buscarunidaderesp.php",
				data: $("form[name=altera]").serialize(), 
				
				
				success: function(response){
					$("div#unidaderesp2").html(response);
				}
		});	
	}else{
		$("div#unidaderesp2").empty();
	}
	});

	$("#botaltera").click(function(){
		$("div#msg").empty();	
		if($("#cadunidadealt").val() == ""){
			$("div#msg").html("O campo unidade é obrigatório!");
			return ;
		}else if ($("#tipo1").val() == "0"){
			$("div#msg").html("O campo tipo de unidade é obrigatóriogg!");
			return ;
		}else if ($("#perfil1").val() == "0"){
			$("div#msg").html("O campo categoria da unidade é obrigatório!");
			return ;
		}

		<?php if ($sessao->getCodUnidade()==100000){ ?> 

		
		else if (($("#ehsubunidadealt").is(':checked')) && ($("#uresp1").val()=="0")){
				$("div#msg").html("Informe a unidade responsável da subunidade!");
				return;
		}else if ((!$("#ehsubunidadealt").is(':checked')) && ($("#sigla1").val() == "")){
				$("div#msg").html("Informe a sigla para unidade responsável!");
				return;
		}else if ((!$("#ehsubunidadealt").is(':checked')) && ($("#siafi1").val() == "")){
				$("div#msg").html("Informe o siafi para unidade responsável!");
				return;
		}
		<?php } ?>
		else{	
			$("div#msg").empty();
			
			$.ajax({
					type: "POST",
					url: "ajax/unidade/gravaunidade.php",
					data: $("form[name=altera]").serialize(), 
					
					
					success: function(response){
						$("div#msg").html(response);
					}
			});	
		}
	});
</script>
