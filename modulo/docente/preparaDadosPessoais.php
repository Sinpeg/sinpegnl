<?php

if ($_POST['cpf']!=$r['cpf']){
    $p->setCpf($_POST['cpf']);
}

if ($_POST['nome']!=$r['nome']){
    $p->setNome(strtoupper($_POST['nome']));
}

//Trata data
$datanasc = isset($_REQUEST['calendario']) ? $_REQUEST['calendario'] : null;
if ($datanasc!=$r['dtnascimento']){
    if (!is_valid_date(trim($datanasc), $anobase)){
        $erro="Data inválida! O docente não pode ter menos de 15 anos nem mais de 110 anos.";
    }else {
        $p->setDtnascimento(dataformatada($dtnasc));
    }
}

if ($_POST['sexo']!=$r['sexo']){
    $p->setSexo(strtoupper($_POST['sexo']));
}


if ($_POST['cor']!=$r['cor']){
    $p->setCor(strtoupper($_POST['cor']));
}


if ($_POST['pais']!=$r['paisorigem']){
    $p->setPaisorigem($_POST['pais']);
}

if ($_POST['nacionalidade']!=$r['nacionalidade']){
    $p->setNacionalidade($_POST['nacionalidade']);
}

if ($_POST['passaporte']!=$r['passaporte']){
    $p->setPassaporte($_POST['passaporte']);
}

if ($_POST['uf']!=$r['ufnascimento']){
    $p->setUfnascimento($_POST['ufnascimento']);
}

if ($_POST['mun']!=$r['munnascimento']){
    $p->setMunnascimento($_POST['mun']);
}



//deficiência e  outras
if (!empty($_POST['temdeficiencia']) && $r['pnd']==0){
    
    $p->setPnd('1');
    $p->setCegueira((empty($_POST['cegueira']))?'0':'1');
    $p->setBaixavisao((empty($_POST['baixavisao']))?'0':'1');
    $p->setSurdez((empty($_POST['surdez']))?'0':'1');
    $p->setAuditiva((empty($_POST['auditiva']))?'0':'1');
    $p->setFisica((empty($_POST['fisica']))?'0':$r['fisica']);
    $p->setSurdocegueira((empty($_POST['surdocegueira']))?'0':'1');
    $p->setMental((empty($_POST['mental']))?'0':'1');
    $p->setAutismoinfantil((empty($_POST['autismoinfantil']))?'0':'1');
    $p->setAltashabilidades((empty($_POST['altashabilidades']))?'0':'1');
    
}else if ($_POST['temdeficiencia']==0 && $r['pnd']==1){
    
    $p->setCegueira(null);
    $p->setBaixavisao(null);
    $p->setSurdez(null);
    $p->setAuditiva(null);
    $p->setFisica(null);
    $p->setSurdocegueira(null);
    $p->setMental(null);
    $p->setAutismoinfantil(null);
    $p->setAltashabilidades(null);
    
}









function is_valid_date($datanasc, $anobase) {
    // does it look like a date?
    
    $dia = substr($datanasc,0,2);
    $mes = substr($datanasc,3,2);
    $ano = substr($datanasc,6,4);
    $idade=$anobase - $ano;
    $idademinima=15;
    $idademaxima=110;
    //  echo $dia.".".$mes.".".$ano;
    $r1=($dia>0 && $dia<=31)?true:false;
    $r2=($mes>0 && $mes<=12)?true:false;
    $r3= ($idade>=$idademinima && $idade<=$idademaxima)?true:false;
    if ($r1 && $r2 && $r3){
        return false;
    }else{
        return true;
    }
    
    
    /* if (preg_match("/^(\d{2})\\(\d{2})\\(\d{4})$/", $date, $matches)) {
     // is it a valid date?
     return checkdate($matches[1], $matches[2], $matches[3]);
     }*/
    return false;
}

function dataformatada($datanasc) {
    // does it look like a date?
    
    $dia = substr($datanasc,0,2);
    $mes = substr($datanasc,3,2);
    $ano = substr($datanasc,6,4);
    return $dia.$mes.$ano;
}