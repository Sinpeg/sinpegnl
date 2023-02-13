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

function ajaxBuscatipo() {
// Create a function that will receive data sent from the server
    ajaxRequest = ajaxFunction();
    if (ajaxRequest) {
        ajaxRequest.open("POST", "ajax/labor/buscatplab1.php",
                true);
        ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");//ADD
        cadeia = "categoria=" + document.fgravar.cat.value;
        ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                //if (ajaxRequest.status==200){
                //document.fgravar.time.value = ajaxRequest.responseText;
                document.getElementById("txtHint").innerHTML = ajaxRequest.responseText;
            }
        }
        ajaxRequest.send(cadeia);
    }
}

function ajaxBuscacurso() {
// Create a function that will receive data sent from the server
    ajaxRequest = ajaxFunction();
    if (ajaxRequest) {
        ajaxRequest.open("POST","ajax/labor/buscacurso.php",
                true);
        ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        cadeia = "nivel=" + document.fgravar.nivel.value;
        ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                //if (ajaxRequest.status==200){
                //document.fgravar.time.value = ajaxRequest.responseText;
                document.getElementById("txtHint").innerHTML = ajaxRequest.responseText;
            }
        }
        ajaxRequest.send(cadeia);
    }
}