<?php
require_once MODULO_DIR . 'documentopdi/classe/Documento.php';
require_once MODULO_DIR . 'documentopdi/dao/DocumentoDAO.php';
require_once MODULO_DIR . 'calendarioPdi/classes/Calendario.php';
require_once MODULO_DIR . 'calendarioPdi/dao/CalendarioDAO.php';
require_once MODULO_DIR . 'avaliacao/classe/Avaliacaofinal.php';
require_once MODULO_DIR . 'avaliacao/dao/AvaliacaofinalDAO.php';

$sessao = $_SESSION['sessao'];
$codunidade = $sessao->getCodUnidade();
$aplicacoes = $sessao->getAplicacoes();

if (!$aplicacoes[36]) {
    print "Erro ao acessar esta aplicação";
    exit();
}

$daodoc = new DocumentoDAO();
$daoaval = new AvaliacaofinalDAO();
$daocal = new CalendarioDAO();

$c=new Controlador();
$codunidade = ($c->getProfile($sessao->getGrupo()))?$sessao->getCodunidsel():$sessao->getCodUnidade();
//echo $sessao->getAnobase().','.$codunidade."bbbbb";
$rowsdoc = $daodoc->listaDocporIndCal1($sessao->getAnobase(),$codunidade);
$aval=NULL;

if (isset($_GET['coddoc'])){
	$coddocumento=$_GET['coddoc'];
	$periodo=$_GET['periodo'];
	$codcal=$_GET['codcal'];
	
    $rows=$daoaval->buscaAvalDP($coddocumento, $codcal, $periodo);
    
    foreach ($rows as $r){
        $cal=new Calendario();
        $cal->setCodigo($r['codCalendario']);
        $doc=new Documento();
        $doc->setCodigo($r['codDocumento']);
        $aval=new Avaliacaofinal();
        $aval->setCodigo($r['codigo']);
        $aval->setPeriodo($r['periodo']);
        $aval->setRat($r['RAT']);
        $aval->setAvaliacao($r['avaliacao']);
        
    }
}

$querrydoc = $daodoc->buscaporRedundancia($codunidade,$sessao->getAnobase());

foreach ($querrydoc as $docu){
	$coddocumento = $docu['codigo'];
	$ano = $docu['anoinicial'];
	$nome = $docu['nome'];
	$anoinicial = $docu['anoinicial'];
	$anofinal = $docu['anofinal'];
}

$arraycal = $daocal->buscaCalendarioporAnoBase($sessao->getAnobase(), $coddocumento)->fetch();
$data = date("Y-m-d");
?>

<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active"><a href="<?php echo Utils::createLink('resultadopdi', 'consultaresult'); ?>">Lançar Resultados</a> / <a href="#" >Gestão do Plano</a></li>  
		</ul>
	</div>
</head>
    
<div id="sdfadf"></div>

<div class="card card-info">
	<form class="form-horizontal" name="adicionar"  method="POST" >
		<h3 class="card-title">Realizar Gestão do Plano</h3>
			
		<table>
			<tr>
				<td>
					Documento
				</td>
				<td>
					<select class="custom-select" name="codDocumento" id="documentoAval" class="sel1">	                	
						<option  value=<?php print $coddocumento; ?>  ><?php print $nome; ?><?php print ' (' . $anoinicial . '-' . $anofinal . ')'; ?></option>
					</select><br>
				</td>
			</tr>
			<tr>
				<td>Período da Avaliação</td>
				<td>
					<?php if($_GET['periodo']==1){ ?>
							<strong>Parcial</strong>
							<input class="form-control"type="hidden" value="1" name="aperiodo">
					<?php }elseif ($_GET['periodo']==2){  ?>
							<strong>Final</strong>
							<input class="form-control"type="hidden" value="2" name="aperiodo">               		
					<?php } else{ ?>
							<a>Cadastro desabilitado!</a>
					<?php } ?>
				</td>
			</tr>   
			<tr>                     
				<td>Houve Reunião de Avalição Tática</td>
				<td><input type="checkbox" class="form-check-input" <?php echo ($aval!=NULL && $aval->getRat()==1)?"checked":"";?> name="rat" value="1" />                      
					</td>
			</tr>      
			<tr>
				<td>Relato sobre pontos discutidos na RAT</td>
				<td><textarea class="area" name="avaliacaofinal" rows="10" cols="50"><?php echo $aval==NULL?"":$aval->getAvaliacao();?></textarea></td>
			</tr>
			<tr>
				<td align="center" colspan="2">
					<input type="button" value="Gravar" id="gravar" name="salvar" class="btn btn-info"/>&nbsp
					<a  href="modulo/avaliacao/resultadosPainelTaticoPercentual.php?unidade=<?php echo $codunidade;?>&anoBase=<?php echo $anobase;?>"><input type="button" value="Relatório Percentual de Desempenho Geral" class="btn btn-info"/></a><br/><br/>
				</td>
			</tr>
		</table>
	</form><br/>
</div>

<script>
	$("#gravar").click(function() {
		$.ajax({
			url:"ajax/avaliacaofinal/ajaxAvaliacao.php",
			type: 'POST',
			data: $("form[name=adicionar]").serialize(),
			success: function(data) {
					$("div#sdfadf").html(data);
			}
			
		});
	});

	$("#verifica").click(function() {
		$.ajax({
			url:"ajax/avaliacaofinal/verificarAval.php",
			type: 'POST',
			data: $("form[name=adicionar]").serialize(),
			success: function(data) {
				$("div#sdfadf").html(data);
			}
			
		});
	});
</script>