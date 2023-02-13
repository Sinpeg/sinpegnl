<?php  
$periodo = $_POST['periodo']; // período de referência
$interpretacao = $_POST['interpretacao']; // interpretacao da meta do indicador
$codmeta = addslashes($_POST['codmeta']); // código da meta
$codmapind = addslashes($_POST['codindicador']); // código do indicador
$resultado = strip_tags(addslashes($_POST['resultado'])); // resultado
$meta_atingida=str_replace(',', '.', $resultado);
$coddoc=$_POST["coddoc"];
$codcal=$_POST["codcal"];
$metrica=$_POST["metrica"];
$cumulativo=$_POST["cumulativo"];
$erro="";
$mensagem=NULL;
require_once '../../dao/PDOConnectionFactory.php';
require_once  '../../modulo/resultadopdi/dao/ResultadoDAO.php';
require_once  '../../modulo/metapdi/dao/MetaDAO.php';
$valor_acumulado="";
$daoMeta=new MetaDAO();
                             //   echo $codmeta.'pega a meta que está sendo modificada'.$meta_atingida."-".$meta."-s ".$situacao; die;

$rows=$daoMeta->buscarmeta($codmeta);
foreach ($rows as $r){
                $meta=$r['meta'];
}
if ($meta != 0) {
    if ($resultado == "") {
    $erro = "Por favor, informe o valor do resultado em número.";
    }/* else if (!preg_match('/^(([0-9]+\,[0-9]+)|([1-9][0-9]*)|([0]{1}))$/', $resultado)) {
    $erro =$metrica=="Q"?"Métrica Quantitativo! Preencha com um valor inteiro.":"Métrica Percentual! Preencha com um valor decinmal.";
    }else if  (strpos($resultado,',') && $metrica=="Q"){
    $erro="Métrica quantitativa para o resultado não aceita número decimal!";        
    }*/ else{
    
    if ($cumulativo==1){
        //buscar no banco as anteriores para dar a situação
       
        $dao=new ResultadoDAO();
        $rows=$dao->buscaresultadometa($codmeta);
        $valor_referencia=0;
        
        foreach ($rows as $row){
            
            if ($row['periodo']!=$periodo){
                  $valor_referencia+=$row['meta_atingida'];
                
            }
        }
        $valor_referencia+=$meta_atingida;//pega a meta que está sendo modificada
        $mensagem="Valor acumulado com metas de outros períodos:".$valor_referencia;
    }else{
          $valor_referencia = ($meta_atingida == '-') ? (0) : ($meta_atingida);
    }    		
    		$situacao = ($interpretacao == 1)?$valor_referencia/$meta: $meta/$valor_referencia;
           // echo $interpretacao."---".$valor_referencia."----".$meta;
            if ($situacao >= 0.9) {
                $sinalizacao = 'green.png';
                $titulo =  "O resultado atingiu ".number_format($situacao*100,2,",",".").'% da meta definida.';
            } else if ($situacao >= 0.6 && $situacao < 0.9) {
                $sinalizacao = 'yellow.png';
                $titulo = "Atenção! O resultado atingiu ".number_format($situacao*100,2,",",".").'%  da meta definida.';                    
            } else {
                $sinalizacao = 'red.png';
                $titulo = " Abaixo do esperado! O resultado atingiu ".number_format($situacao*100,2,",",".").'% da meta definida.';
            }
    }
} else {
            $sinalizacao = 'grey.png';
            $titulo = 'Meta não registrada!';
        }

if ($erro=="") {?>

<a href="#" class="help" data-trigger="hover" data-content="<?php echo $titulo; ?>" 
title="Semáforo" ><img src="webroot/img/bullet-<?php print $sinalizacao; ?>" width="40" height="40" /></a>


<?php

print "<p>".$titulo."</p>";
print "<p>".is_null($mensagem)?$mensagem."</p>":""."</p>";
 } else {
    echo $erro;
}?>
