<?php

$d31=new Docente31();

if ($_POST['escolaridade']!=$r['escolaridade']){
    $d31->setEscolaridade($_POST['escolaridade']);
}

if ($_POST['sitdocente']!=$r['situacao']){
    $d31->setSituacao($_POST['sitdocente']);
}


if ($_POST['exercicio3112']!=$r['exercicio3112']){
    $d31->setExercicio3112($_POST['exercicio3112']);
}

// A partir daqui depende da situacao

if ($d31->getSituacao()!=1){
    
}else {

        //atuação
        if ($r['atpossspresencial']!=$_POST['atpossspresencial'] && $_POST['atpossspresencial']==1 ){
            $erro= "Docente, inclua os documentos que comprovem sua atuação na pós-graduação presencial.";
            
        }else if ($r['atpesquisa']!=$_POST['atpesquisa'] && $_POST['atpesquisa']==1){
            
            $erro= "Docente, inclua os documentos que comprovem sua atuação em pesquisa.";
            
        }else if  ($r['atextensao']!=$_POST['atextensao'] && $_POST['atextensao']==1){
            $erro= "Docente, inclua os documentos que comprovem sua atuação em extensão.";
            
        }else if  ($r['atgpa']!=$_POST['atgpa'] && $_POST['atgpa']==1){
            $erro= "Docente, inclua os portarias que comprovem sua atuação em gestão, planejamento e avaliação.";
            
        }else if  ( $r['atgpresencial']!=$_POST['atgpresencial'] && $_POST['atgpresencial']==1){
            $erro= "Docente, inclua os documentos que comprovem sua atuação na graduação presencial e verifique se o curso informado é presencial.";
            
        } else if  ($r['atgEAD']!=$_POST['atgEAD'] && $_POST['atgEAD']==1){
            $erro= "Docente, inclua os documentos que comprovem sua atuação na graduação a distâcia e verifique se o curso informado é a distância.";
        }else if  ($r['atgEAD']!=$_POST['atgEAD'] && $_POST['atgEAD']==1){
            $erro= "Docente, inclua os documentos que comprovem sua atuação na graduação a distâcia e verifique se o curso informado é a distância.";
        }else if  ($r['escolaridade']!=$_POST['escolaridade'] ){
            $erro= "Docente, inclua os documentos que comprovem sua escolaridade em 31/12/".$anobase.". Lembre-se de que a escolaridade é relativa ao cargo ocupado e não, à pessoa.";
        }else if  ($r['situacao']!=$_POST['sitdocente'] ){
            $erro= "Docente, inclua os documentos que comprovem sua situação nem 31/12/".$anobase.". Lembrando que para docentes afastados, não é possível incluir atuação.";
        }else if  ($r['regime']!=$_POST['regime'] ){
            $erro= "Docente,inclua os documentos, que comprovem a situação. Lembre-se que se trata do regime em 31/12/".$anobase;
        }else if  ($r['substituto']!=$_POST['substituto'] ){
            $erro= "Docente, inclua os documentos, que comprovem a situação. Lembre-se que se trata da situação em 31/12/".$anobase;
        }
}
?>