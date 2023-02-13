<?php
//session_start ();
$sessao = $_SESSION ['sessao'];
if (! isset ( $sessao )) {
	exit ();
}
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
$anobase = $sessao->getAnobase();

$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$codunidade1=$unidade->getCodunidade();


$mapadao = new MapaDAO ();
$daodoc = new DocumentoDAO();
$c=new Controlador();
if (!$c->getProfile($sessao->getGrupo())) {
   $rowsdoc = $daodoc->buscadocumentoporunidade($unidade->getCodunidade());
}else{
   $rowsdoc = $daodoc->lista($anobase);
}
// $propmapa = $sessao->getCodUnidade ();able>
// $mapa = $mapadao->buscaMapaByUnidadeDocumento ( 1, $propmapa );

$querrydoc = $daodoc->buscadocumentoporunidade($codunidade);
foreach ($querrydoc as $docu){
	$coddocumento = $docu['codigo'];
}

$daocal = new CalendarioDAO();
$arraycal = $daocal->buscaCalendarioporAnoBase($sessao->getAnobase(), $coddocumento)->fetch();
$data = date("Y-m-d");
	
if($data > $arraycal['dataIniAnalise'] and $data < $arraycal['dataFimAnalise']){
	$periodo = 'inicial'; 
}elseif ($data > $arraycal['datainiAnaliseFinal'] and $data < $arraycal['datafimAnaliseFinal']){
	$periodo = 'final';
} else{
	$periodo = 'indefinido';
} 

?>



<div id="message"></div>

<div class="card card-info">
	<div class="card-header"><h3 class="card-title">Lista de avaliações</h3></div>
	<form class="form-horizontal" name="chamaTabela">
				
		<h4>Documento </h4>
			
		<select class="custom-select" name="codDocumento" id="selectDocument" class="sel1">
			<?php foreach ($rowsdoc as $row) { ?>
				<?php $ano = $row['anoinicial']; ?>
				<?php if (!$c->getProfile($sessao->getGrupo())) {
					if ($row['CodUnidade'] != 938) { ?>
						<option selected value=<?php print $row["codigo"]; ?>><?php print $row['nome'] ?><?php print ' (' . $row['anoinicial'] . '-' . $row['anofinal'] . ')';
							  ?></option>
						<?php $codDocumento = $row["codigo"];
					}
				} else if ($c->getProfile($sessao->getGrupo())) { ?>
				<option value=<?php print $row["codigo"]; ?>><?php print $row['nome'] ?><?php print ' (' . $row['anoinicial'] . '-' . $row['anofinal'] . ')'; ?></option>
				<?php } ?>
			<?php } ?>
		</select>
		
		<br>

		<?php
		$avaldao = new AvaliacaofinalDAO ();

		$codunidade = ($sessao->getCodunidsel())?$sessao->getCodunidsel():$sessao->getCodUnidade();

		//realiza filtro de mapa por documento ou por calendario.
		$mapa = $avaldao->buscaaval( $codDocumento, $anobase);
		if ($mapa==NULL){
			Utils::redirect('avaliacao', 'registraEditaAvaliacao');
		}
		?>

		<!--abriga mensagem de resposta quando o usuário tentar deletar pespectiva/objetivo-->
		<div id="m"></div>

		<!-- tabela gerada apartir do o bjeto mapa -->
	</form>
</div>
<br>

<table>
	<tr>
		<th>Período</th>
		<th>Ano de Gestão</th>
		<th>Editar</th>
	</tr>
	
	<?php
	$cont = 1;
	foreach ($mapa as $row){?>
		<tr>
			<td><?php echo $row['periodo']==1?"Parcial":"Final"; ?></td>
			<td><?php echo $row['anoGestao'] ?></td>
			<td align="center">
				<a href="<?php print Utils::createLink("avaliacao", "registraEditaAvaliacao", array('codaval'=> $row['codigo'])) ?>"><img  src="webroot/img/editar.gif"></a>
			</td>
		</tr>
		<?php
		$cont++;
	}?>
</table>

<a href="<?php echo Utils::createLink('avaliacao', 'registraEditaAvaliacao'); ?>" >
<button id="aval" type="button" class="btn btn-info btn">Nova Avaliação</button></a>  
    
   
    


  
    
    
    
     
    
    
    
    
<script>
    

</script>
    
    
    
  
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
