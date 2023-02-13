<?php
$html.= 'Quadro 2 - Desempenho do Orçamento de Custeio no Exercício por Plano Interno <br/>';
if ($anobase<=2020){
    $html.= '<div style="width:300px;font-size:10px" class="table-wrapper-scroll-y my-custom-scrollbar">
        
<table disabled="disabled" class="table table-bordered table-striped mb-0" style="width:100%">
	<tr bgcolor="#CCCCCC" >
		<td rowspan=2 style="text-align:center;vertical-align: middle;width=10"><b>PI</b></td>
        <td rowspan=2 style="text-align:center;vertical-align: middle"><b>Projeto</b></td>
        <td colspan=5 style="text-align:center"><b>Recurso de Custeio</b></td>
        <td colspan=3 style="text-align:center"><b>Despesa de Custeio</b></td></tr>';
    
    $html.= '<tr bgcolor="#CCCCCC">
    			<td bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Previsto</b></td><td bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Reprogramado</b></td><td bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Apoio Recebido</b></td>
    			<td bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Recebido após Portaria PROAD</b></td><td bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Disponibilizado</b></td><td bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Movimentada</b></td>
    			<td bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Empenhada</b></td><td bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Liquidada</b></td></tr>';
}else{
    $html.= '<div style="width:300px;font-size:10px"><table disabled="disabled" style="width:100%">
	<tr bgcolor="#CCCCCC" >
		<td rowspan=3 style="text-align:center;vertical-align: middle;width=10"><b>PI</b></td>
        <td rowspan=3 style="text-align:center;vertical-align: middle"><b>Projeto</b></td>
        <td colspan=5 style="text-align:center"><b>Recurso de Custeio</b></td>
        <td colspan=4 style="text-align:center"><b>Despesa de Custeio</b></td></tr>';
    
    $html.= '<tr bgcolor="#CCCCCC">
<td rowspan=2 bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Previsto</b></td>
<td rowspan=2 bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Reprogramado</b></td>
<td rowspan=2 bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Liberado</b></td>
<td rowspan=2 bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Apoio Recebido</b></td>
<td rowspan=2 bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Disponibilizado</b></td>
        
<td colspan=3 bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Executado</b></td>
<td rowspan=2 bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Liquidado</b></td>
</tr>
<tr>
        
<td bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Movimentada</b></td>
<td bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Empenhada</b></td>
<td bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Total</b></td></tr>
        
</tr>';
    
    
    
}

$rowsOrca = $mdao->buscarorcamentocusteio($codunidade, $anobase);


$totalcp=0.0;$totalcr=0.0;$totalcl=0.0;$totalca=0.0;$totalcd=0.0;$totalcm=0.0;$totalce=0.0;$totalcq=0.0;//acrescentei pelas subunidades do ica
foreach ($rowsOrca As $rows){
    if ($anobase<=2020){
        $somatorio=intval($rows['custeio_previsto'])+intval($rows['custeio_reprogramado'])+intval($rows['custeio_apoio'])
        +intval($rows['custeio_recebido_proad'])+intval($rows['custeio_disponibilizado'])+intval($rows['custeio_movimentada'])+
        intval($rows['custeio_empenhada'])+intval($rows['custeio_liquidada']);
    }else{
        $somatorio=intval($rows['custeio_previsto'])+intval($rows['custeio_reprogramado'])+intval($rows['custeio_apoio'])
        +intval($rows['custeio_liberado'])+intval($rows['custeio_disponibilizado'])+intval($rows['custeio_movimentada'])+
        intval($rows['custeio_empenhada'])+intval($rows['custeio_liquidada']);
    }
    
    if ($somatorio>0){
        
        $rows['custeio_previsto'] = ($rows['custeio_previsto']== null || $rows['custeio_previsto']== "") ? "0,00" : $rows['custeio_previsto'] ;
        $rows['custeio_reprogramado'] = ($rows['custeio_reprogramado']== null || $rows['custeio_reprogramado']== "") ? "0,00" : $rows['custeio_reprogramado'] ;
        $rows['custeio_apoio'] = ($rows['custeio_apoio']== null || $rows['custeio_apoio']== "") ? "0,00" : $rows['custeio_apoio'] ;
        $rows['custeio_disponibilizado'] = ($rows['custeio_disponibilizado']== null || $rows['custeio_disponibilizado']== "") ? "0,00" : $rows['custeio_disponibilizado'] ;
        $rows['custeio_movimentada'] = ($rows['custeio_movimentada']== null || $rows['custeio_movimentada']== "") ? "0,00" : $rows['custeio_movimentada'] ;
        $rows['custeio_empenhada'] = ($rows['custeio_empenhada']== null || $rows['custeio_empenhada']== "") ? "0,00" : $rows['custeio_empenhada'] ;
        $rows['custeio_liquidada'] = ($rows['custeio_liquidada']== null || $rows['custeio_liquidada']== "") ? "0,00" : $rows['custeio_liquidada'] ;
        
        
        if ($anobase<=2020){
            $rows['custeio_recebido_proad'] = ($rows['custeio_recebido_proad']== null || $rows['custeio_recebido_proad']== "") ? "0,00" : $rows['custeio_recebido_proad'] ;
            $html.= '<tr><td>'.$rows['pi'].'</td><td>'.$rows['projeto'].'</td><td>R$'.
                $rows['custeio_previsto'].'</td><td>R$'.
                $rows['custeio_reprogramado'].'</td><td>R$'.
                $rows['custeio_apoio'].'</td><td>R$'.
                $rows['custeio_recebido_proad'].'</td><td>R$'.
                $rows['custeio_disponibilizado'].'</td><td>R$'.
                $rows['custeio_movimentada'].'</td><td>R$'.
                $rows['custeio_empenhada'].'</td><td>R$'.
                $rows['custeio_liquidada'].'</td></tr>';
                $totalca+=str_replace(",",".",preg_replace("/[^0-9,]+/i","",intval($rows['custeio_recebido_proad'])));
                
        }else{
            $rows['custeio_liberado'] = ($rows['custeio_liberado']== null || $rows['custeio_liberado']== "") ? "0,00" : $rows['custeio_liberado'] ;
            $total=0;
            $custeio_movimentado=($rows['custeio_movimentada']== null || $rows['custeio_movimentada']== "") ? "0" : $rows['custeio_movimentada'] ;
            $custeio_empenhado=($rows['custeio_empenhada']== null || $rows['custeio_empenhada']== "") ? "0" : $rows['custeio_empenhada'] ;
            $total=intval($custeio_movimentado)+intval($custeio_empenhado);
            
            $total=$total==0?'0,0':str_replace(",",".",preg_replace("/[^0-9,]+/i","",$total));
            $total=number_format(intval($total),2,",",".");
            
            $html.= '<tr><td>'.$rows['pi'].'</td><td>'.$rows['projeto'].'</td><td>R$'.
                $rows['custeio_previsto'].'</td><td>R$'.
                $rows['custeio_reprogramado'].'</td><td>R$'.
                $rows['custeio_liberado'].'</td><td>R$'.
                $rows['custeio_apoio'].'</td><td>R$'.
                $rows['custeio_disponibilizado'].'</td><td>R$'.
                $rows['custeio_movimentada'].'</td><td>R$'.
                $rows['custeio_empenhada'].'</td><td>R$'.
                
                $total.'</td><td>R$'.
                
                
                
                $rows['custeio_liquidada'].'</td></tr>';
                $totalca+=str_replace(",",".",preg_replace("/[^0-9,]+/i","",intval($rows['custeio_liberado'])));
                
        }
        
        
        
    }
    $totalcp+=str_replace(",",".",preg_replace("/[^0-9,]+/i","",intval($rows['custeio_previsto'])));
    $totalcr+=str_replace(",",".",preg_replace("/[^0-9,]+/i","",intval($rows['custeio_reprogramado'])));
    $totalcl+=str_replace(",",".",preg_replace("/[^0-9,]+/i","",intval($rows['custeio_apoio'])));
    $totalca+=str_replace(",",".",preg_replace("/[^0-9,]+/i","",intval($rows['custeio_recebido_proad'])));
    $totalcd+=str_replace(",",".",preg_replace("/[^0-9,]+/i","",intval($rows['custeio_disponibilizado'])));
    $totalcm+=str_replace(",",".",preg_replace("/[^0-9,]+/i","",intval($rows['custeio_movimentada'])));
    $totalce+=str_replace(",",".",preg_replace("/[^0-9,]+/i","",intval($rows['custeio_empenhada'])));
    $totalcq+=str_replace(",",".",preg_replace("/[^0-9,]+/i","",intval($rows['custeio_liquidada'])));
    $tem++;
}

if ($anobase<=2020){
    
    $html.= '<tr><td colspan=2>Totais</td><td>R$'.
        number_format($totalcp,2,",",".").'</td><td>R$'.
        number_format($totalcr,2,",",".").' </td><td>R$ '.
        number_format($totalca,2,",",".").'</td><td>R$'.
        number_format($totalcl,2,",",".").'</td><td>R$'.
        number_format($totalcd,2,",",".").'</td><td>R$'.
        number_format($totalcm,2,",",".").'</td><td>R$'.
        number_format($totalce,2,",",".").'</td><td>R$'.
        
        number_format($totalcq,2,",",".").'</td></tr>';
}else{
    $html.= '<tr><td colspan=2>Totais</td><td>R$'.
        number_format($totalcp,2,",",".").'</td><td>R$'.
        number_format($totalcr,2,",",".").' </td><td>R$ '.
        number_format($totalca,2,",",".").'</td><td>R$'.
        number_format($totalcl,2,",",".").'</td><td>R$'.
        number_format($totalcd,2,",",".").'</td><td>R$'.
        number_format($totalcm,2,",",".").'</td><td>R$'.
        number_format($totalce,2,",",".").'</td><td>'.
        
        '</td><td>R$'.
        
        number_format($totalcq,2,",",".").'</td></tr>';
}

$html.='</table>Fonte:PGO-'.$anobase.' e SIAFI 30/12/'.$anobase.'<br></div><br/>';

//calculo de percentuais
$percentRecebidoDoRecCusteio=$totalcp!=0.0?$totalcd*100.0/$totalcp:"0.0";
$percentCusteioUtilizado=$totalcd==0.0?0.0:(($totalcm+$totalce)/$totalcd)*100;
$percentDespesaCusteioLiquidada=$totalce==0.0?0.0:($totalcq/$totalce)*100.0;
$html.='<br/><br/>';

$html.= 'Quadro 3 - Desempenho do Orçamento de Capital no Exercício por Plano Interno';
$rowsOrca = $mdao->buscarorcamentocapital($codunidade, $anobase);
$html.= '
<div style="width:300px;font-size:10px"><table disabled="disabled" style="width:100%">';


if ($anobase<=2020){
    $html.= '<tr bgcolor="#CCCCCC" >
        <td rowspan=2 style="text-align:center;vertical-align: middle"><b>PI</b></td>
        <td rowspan=2 style="text-align:center;vertical-align: middle"><b>Projeto</b></td>
        <td colspan=5 style="text-align:center"><b>Recurso de Capital</b></td>
        <td colspan=3 style="text-align:center"><b>Despesa de Capital</b></td></tr>';
    
    $html.= '<tr bgcolor="#CCCCCC">
			<td bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Previsto</b></td><td bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Reprogramado</b></td><td bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Apoio recebido</b></td>
			<td bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Recebido após Portaria PROAD</b></td><td bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Disponibilizado</b></td><td bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Movimentada</b></td>
			<td bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Empenhada</b></td><td bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Liquidada</b></td></tr>';
}else{
    
    $html.= '<tr bgcolor="#CCCCCC" >
        <td rowspan=3 style="text-align:center;vertical-align: middle"><b>PI</b></td>
        <td rowspan=3 style="text-align:center;vertical-align: middle"><b>Projeto</b></td>
        <td colspan=5 style="text-align:center"><b>Recurso de Capital</b></td>
        <td colspan=4 style="text-align:center"><b>Despesa de Capital</b></td></tr>';
    
    
    
    $html.= '<tr bgcolor="#CCCCCC">
        <td rowspan=2 bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Previsto</b></td>
        <td rowspan=2 bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Reprogramado</b></td>
        <td rowspan=2 bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Liberado</b></td>
        <td rowspan=2 bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Apoio Recebido</b></td>
        <td rowspan=2 bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Disponibilizado</b></td>
        
        <td colspan=3 bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Executado</b></td>
        <td rowspan=2 bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Liquidado</b></td>
        </tr>
        <tr>
        <td bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Movimentada</b></td>
        <td bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Empenhada</b></td>
        <td bgcolor="#CCCCCC" style="text-align:center;vertical-align: middle"><b>Total</b></td></tr>
       </tr>';
    
}

$totalcp=0.0;$totalcr=0.0;$totalcl=0.0;$totalca=0.0;$totalcd=0.0;$totalcm=0.0;$totalce=0.0;$totalcq=0.0;


foreach ($rowsOrca As $rows){
    $totalcp+=str_replace(",",".",preg_replace("/[^0-9,]+/i","",intval($rows['capital_previsto'])));
    $totalcr+=str_replace(",",".",preg_replace("/[^0-9,]+/i","",intval($rows['capital_reprogramado'])));
    $totalcl+=str_replace(",",".",preg_replace("/[^0-9,]+/i","",intval($rows['capital_apoio'])));
    if ($anobase<=2020){
        $totalca+=str_replace(",",".",preg_replace("/[^0-9,]+/i","",intval($rows['capital_recebido_proad'])));
    }else{
        $totalca+=str_replace(",",".",preg_replace("/[^0-9,]+/i","",intval($rows['capital_liberado'])));
    }
    $totalcd+=str_replace(",",".",preg_replace("/[^0-9,]+/i","",intval($rows['capital_disponibilizado'])));
    $totalcm+=str_replace(",",".",preg_replace("/[^0-9,]+/i","",intval($rows['capital_movimentada'])));
    $totalce+=str_replace(",",".",preg_replace("/[^0-9,]+/i","",intval($rows['capital_empenhada'])));
    $totalcq+=str_replace(",",".",preg_replace("/[^0-9,]+/i","",intval($rows['capital_liquidada'])));
    
    
    
    if ($anobase<=2020){
        $somatorio=intval($rows['capital_previsto'])+intval($rows['capital_reprogramado'])+intval($rows['capital_apoio'])+intval($rows['capital_recebido_proad'])
        +intval($rows['capital_disponibilizado'])+intval($rows['capital_movimentada'])+intval($rows['capital_empenhada'])+intval($rows['capital_liquidada']);
        
    }else{
        
        $somatorio=intval($rows['capital_previsto'])+intval($rows['capital_reprogramado'])+intval($rows['capital_apoio'])+intval($rows['capital_liberado'])
        +intval($rows['capital_disponibilizado'])+intval($rows['capital_movimentada'])+intval($rows['capital_empenhada'])+intval($rows['capital_liquidada']);
        
    }
    
    
    if ($somatorio>0){
        
        $rows['capital_previsto'] = ($rows['capital_previsto']== null || $rows['capital_previsto']== "") ? "0,00" : $rows['capital_previsto'] ;
        $rows['capital_reprogramado'] = ($rows['capital_reprogramado']== null || $rows['capital_reprogramado']== "") ? "0,00" : $rows['capital_reprogramado'] ;
        $rows['capital_apoio'] = ($rows['capital_apoio']== null || $rows['capital_apoio']== "") ? "0,00" : $rows['capital_apoio'] ;
        if ($anobase<=2020){
            $rows['capital_recebido_proad'] = ($rows['capital_recebido_proad']== null || $rows['capital_recebido_proad']== "") ? "0,00" : $rows['capital_recebido_proad'] ;
            
            $html.= '<tr><td>'.$rows['pi'].'</td><td>'.$rows['projeto'].'</td><td>R$'.
                $rows['capital_previsto'].'</td><td>R$'.
                $rows['capital_reprogramado'].'</td><td>R$'.
                $rows['capital_apoio'].'</td><td>R$'.
                $rows['capital_recebido_proad'].'</td><td>R$'.
                $rows['capital_disponibilizado'].'</td><td>R$'.
                $rows['capital_movimentada'].'</td><td>R$'.
                $rows['capital_empenhada'].'</td><td>R$'.
                $rows['capital_liquidada'].'</td></tr>';
                
                
        }else{
            $rows['capital_liberado'] = ($rows['capital_liberado']== null || $rows['capital_liberado']== "") ? "0,00" : $rows['capital_liberado'] ;
            
            $total=0;
            $capital_movimentado=($rows['capital_movimentada']== null || $rows['capital_movimentada']== "") ? "0" : $rows['capital_movimentada'] ;
            $capital_empenhado=($rows['capital_empenhada']== null || $rows['capital_empenhada']== "") ? "0" : $rows['capital_empenhada'] ;
            $total=intval($capital_movimentado)+intval($capital_empenhado);
            
            
            $total=$total==0?'0,0':str_replace(",",".",preg_replace("/[^0-9,]+/i","",$total));
            
            $total=number_format(intval($total),2,",",".");
            
            
            
            $html.= '<tr><td>'.$rows['pi'].'</td><td>'.$rows['projeto'].'</td><td>R$'.
                $rows['capital_previsto'].'</td><td>R$'.
                $rows['capital_reprogramado'].'</td><td>R$'.
                $rows['capital_liberado'].'</td><td>R$'.
                
                $rows['capital_apoio'].'</td><td>R$'.
                $rows['capital_disponibilizado'].'</td><td>R$'.
                $rows['capital_movimentada'].'</td><td>R$'.
                $rows['capital_empenhada'].'</td><td>R$'.
                
                $total.'</td><td>R$'.
                
                
                $rows['capital_liquidada'].'</td></tr>';
                
        }
        $rows['capital_disponibilizado'] = ($rows['capital_disponibilizado']== null || $rows['capital_disponibilizado']== "") ? "0,00" : $rows['capital_disponibilizado'] ;
        $rows['capital_movimentada'] = ($rows['capital_movimentada']== null || $rows['capital_movimentada']== "") ? "0,00" : $rows['capital_movimentada'] ;
        $rows['capital_empenhada'] = ($rows['capital_empenhada']== null || $rows['capital_empenhada']== "") ? "0,00" : $rows['capital_empenhada'] ;
        $rows['capital_liquidada'] = ($rows['capital_liquidada']== null || $rows['capital_liquidada']== "") ? "0,00" : $rows['capital_liquidada'] ;
        
        
    }
}

if ($anobase<=2020){
    $html.= '<tr><td colspan=2>Totais</td><td>R$'.
        number_format($totalcp,2,",",".").'</td><td>R$'.
        number_format($totalcr,2,",",".").'</td><td>R$'.
        number_format($totalcl,2,",",".").'</td><td>R$'.
        number_format($totalca,2,",",".").'</td><td>R$'.
        number_format($totalcd,2,",",".").'</td><td>R$'.
        number_format($totalcm,2,",",".").'</td><td>R$'.
        number_format($totalce,2,",",".").'</td><td>'.
        number_format($totalcq,2,",",".").'</td></tr>';
}else{
    $html.= '<tr><td colspan=2>Totais</td><td>R$'.
        number_format($totalcp,2,",",".").'</td><td>R$'.
        number_format($totalcr,2,",",".").'</td><td>R$'.
        number_format($totalcl,2,",",".").'</td><td>R$'.
        number_format($totalca,2,",",".").'</td><td>R$'.
        number_format($totalcd,2,",",".").'</td><td>R$'.
        number_format($totalcm,2,",",".").'</td><td>R$'.
        number_format($totalce,2,",",".").'</td><td>'.
        '</td><td>R$'.
        number_format($totalcq,2,",",".").'</td></tr>';
}

$html.='</table>Fonte:PGO-'.$anobase.' e SIAFI 30/12/'.$anobase.'<br/></div><br/>';

/*
 //calculo de percentuais
 $percentRecebidoDoRecCapital=$totalcp!=0.0?$totalcd*100.0/$totalcp:"0.0";
 $percentCapitalUtilizado=$totalcd==0.0?0.0:(($totalcm+$totalce)/$totalcd)*100.0;
 $percentDespesaCapitalLiquidada=$totalce==0.0?0.0:($totalcq/$totalce)*100.0;
 
 //impressao de percentuais
 $html.= 'Percentual recebido do recurso de custeio ('.$percentRecebidoDoRecCusteio.'%) x
 Percentual recebido do recurso de capital ('.$percentRecebidoDoRecCapital.'%)<br>';
 $html.= 'Percentual de custeio utilizado '. $percentCusteioUtilizado. "% x " ."Percentual de capital utilizado ". $percentCapitalUtilizado."%<br>";
 $html.= 'Percentual da despesa de custeio liquidada '.$percentDespesaCusteioLiquidada. "% x " ."Percentual da despesa de capital liquidada ". $percentDespesaCapitalLiquidada."%<br>";
 */


?>