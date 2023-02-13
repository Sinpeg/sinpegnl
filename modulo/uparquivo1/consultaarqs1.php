<?php
ob_start();
//set_include_path(';../../includes');
require_once('../../includes/classes/sessao.php');


session_start();
if (!isset($_SESSION["sessao"])){
	header("location:index.php");
}

$sessao = $_SESSION["sessao"];

$nomeunidade = $sessao->getNomeUnidade();
$codunidade = $sessao->getCodUnidade();
$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnoBase();
$codusuario = $sessao->getCodusuario();
$data = $sessao->getData();

require_once('../../includes/dao/PDOConnectionFactory.php');
require_once('classes/arquivo.php');
require_once('dao/arquivoDAO.php');
require_once('../../includes/classes/usuario.php');

$usuarios=array();
$dao = new ArquivoDAO();
$i=0;
$rows = $dao->buscaUnidade($anobase,$codunidade) ;
foreach ($rows as $row){
	$i++;
	$usuarios[$i] = new Usuario();
	$usuarios[$i]->setCodusuario($row['CodUsuario']);
	$usuarios[$i]->setResponsavel($row['Responsavel']);
	$usuarios[$i]->adicionaItemArquivo($row['Codigo'],$row['Assunto'], null, $row['Nome'],
	null, null, $row['Comentario'], null, $row['DataInclusao'],$anobase);
}
//$i � o tamanho do vetor usuarios
$dao->fechar();


if ($i==0){
	$cadeia = "location:entarquivo.php";
	header($cadeia);

}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.or/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
<title>Sistema de Registro de Atividades Anuais</title>
<script language="JavaScript">
	function direciona(botao){
		if (botao==1){
		  if (document.farquivo.registros.value==2)
				document.getElementById('msg').innerHTML = "O n�mero m�ximo de arquivos � 2.";
		  else{
			document.getElementById('farquivo').action = "entarquivo.php";
			document.getElementById('farquivo').submit();
		  }
		}
		else if (botao==2) {
			document.getElementById('farquivo').action = "../saida/saida.php";
			document.getElementById('farquivo').submit();
		}
		}
</script>
<link rel="stylesheet" href="../../estilo/msgs.css" />

</head>
<body style="font-family:arial,helvetica,sans-serif;font-size:14px;">
	<form class="form-horizontal" name="farquivo" id="farquivo" method="post">

		<h3 class="card-title">Arquivos enviados</h3> 
		<div class="msg" id="msg"></div>

		<table width="700px" border="1">
			<tr align="center">
				<td>Nome do Arquivo</td>
				<td>Tipo</td>
				<td>Data</td>
				<td>Editar</td>
				<td>Excluir</td>
				<td>Baixar</td>
			</tr>
			
			
			
			

		<?php
        for ($j=1; $j <= $i; $j++){?>

        <?php 	foreach ($usuarios[$j]->getArquivos() as $u){
        ?>
			    <tr>

				<td><?php print $u->getNome(); ?></td>
				<td><?php if ($u->getAssunto() == 1){
					   print "Apresenta��o do Relat�rio de Atividades";
				}
					   else print "Outros";
					 ?></td>
				<td>
				<?php $data1=explode('-',$u->getDatainclusao());
					$data = $data1[2].'/'.$data1[1].'/'.$data1[0];
				
				print $data; ?>
				</td>
				<td align="center">
				<?php  if ( $usuarios[$j]->getResponsavel()==$responsavel){?>
				<a	href="alteraarquivo.php?codigo=<?php print $u->getCodigo();?>"
					target="_self"><img
						src="../../includes/images/editar.gif"
						alt="Alterar" width="19" height="19" /> </a>
						<?php } ?>
				</td>
				<td align="center">
				<?php  if ( $usuarios[$j]->getResponsavel()==$responsavel){?>
				<a	href="delarquivo.php?codigo=<?php print  $u->getCodigo();?>"
					target="_self"><img
						src="../../includes/images/delete.gif"
						alt="Excluir" width="19" height="19" /> </a>
				<?php } ?>

				</td>
					<td align="center">
				<?php  if ( $usuarios[$j]->getResponsavel()==$responsavel){?>
				<a	href="downarquivo.php?codigo=<?php print  $u->getCodigo();?>"
					target="_self"><img
						src="../../includes/images/download.jpg"
						alt="Excluir" width="19" height="19" /> </a>
				<?php } ?>

				</td>
				</tr>

<?php }?>
        <?php }?>
</table>
		<input class="form-control"type="text" name="registros" readonly="readonly" size="1"
			value="<?php echo $i;?>" style="border: none;background-color: transparent" />registro(s)<br /> <input
			type="button" onclick="direciona(1);" value="Incluir arquivo" />

	</form>
</body>
</html>
