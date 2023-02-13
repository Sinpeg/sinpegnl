function ajaxFunction() {
    var ajaxRequest;  // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
        return ajaxRequest;
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
            return ajaxRequest;
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                return ajaxRequest;
            } catch (e) {
                // Something went wrong
                alert("Seu browser não tem suporte para o Ajax!");
                return false;
            }
        }
    }
}


function usajax() {
    ajaxRequest = ajaxFunction();
    if (ajaxRequest) {
        ajaxRequest.open("POST", "ajax/usuario/buscaunidade.php", true);
        ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");//ADD
        cadeia = "parametro=" + document.us.parametro.value;
        ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
            	//alert(ajaxRequest.responseText);
                //if (ajaxRequest.status==200){
                //document.fgravar.time.value = ajaxRequest.responseText;
                document.getElementById("combo1").innerHTML = ajaxRequest.responseText;
            }
        }
        ajaxRequest.send(cadeia);
    }

}

function loginajax() {
    ajaxRequest = ajaxFunction();
    if (ajaxRequest) {
        ajaxRequest.open("POST", "ajax/usuario/buscalogin1.php", true);
        ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        cadeia = "parametro=" + document.us.login.value;
        ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                //if (ajaxRequest.status==200){
                //document.fgravar.time.value = ajaxRequest.responseText;
                document.getElementById("msg1").innerHTML = ajaxRequest.responseText;
            }
        }
        ajaxRequest.send(cadeia);
//        alert(ajaxRequest.responseText);
    }
}


function ajaxunidades() {
    ajaxRequest = ajaxFunction();
    if (ajaxRequest) {
        ajaxRequest.open("POST", "ajax/usuario/buscauni.php", true);
        ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");//ADD
        cadeia = "parametro=" + document.us.parametro.value;
        ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                //if (ajaxRequest.status==200){
                //document.fgravar.time.value = ajaxRequest.responseText;
                document.getElementById("tabela").innerHTML = ajaxRequest.responseText;
            }
        }
        ajaxRequest.send(cadeia);
    }
}

function ajaxlogins() {
    ajaxRequest = ajaxFunction();
    if (ajaxRequest) {
        ajaxRequest.open("POST", "ajax/usuario/buscalogin.php", true);
        ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");//ADD
        cadeia = "parametro=" + document.us.parametro.value;
        ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                //if (ajaxRequest.status==200){
                //document.fgravar.time.value = ajaxRequest.responseText;
                document.getElementById("tabela").innerHTML = ajaxRequest.responseText;
            }
        }
        ajaxRequest.send(cadeia);
    }
}
