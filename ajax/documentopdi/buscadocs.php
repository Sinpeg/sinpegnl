<?php
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../dao/unidadeDAO.php';

require_once '../../classes/sessao.php';

require_once '../../modulo/documentopdi/dao/DocumentoDAO.php';
session_start();

$sessao = $_SESSION["sessao"];
$parametro=addslashes($_GET["parametro"]);

$dao1=new DocumentoDAO();
$dao2=new UnidadeDAO();

$parametro = str_replace("*", " ", $parametro);

if(!empty($parametro)) {
    $result = $dao2->unidadePorStr($parametro, 1) ;
    foreach ($result as $row){
         $sessao->setCodunidsel($row["CodUnidade"]);
         $sessao->setNomeunidsel($row["NomeUnidade"]);
         $_SESSION["sessao"]=$sessao;
    }
    $result = $dao1->listaDocporIndCal($sessao->getAnobase(),$sessao->getCodunidsel());
    if(!empty($result)) { ?>
                 
                <label for="doc">Plano de Desenvolvimento</label>
                <select name="doc" id="pdi-result-bind">
                <option value="0">--Selecione o Plano de Desenvolvimento--</option>
                    
                <?php foreach ($result as $row) { 
                    //Carrega sessao com unidade selecionada
                     
                    
                    if (!isset($row['CodDocumento'])) { ?>
                        <option value="<?php echo $row["codigo"] ?>"><?php echo $row["nome"]." - " .$row['anoinicial'].' a '.$row['anofinal']; ?></option>
                    
                    <?php } //if ?>
<?php } //for ?>
                </select>


                 
<?php }//if result
} //if empty?>

<script>

$(document).ready(function()  {//exibe indicadores do plano e da unidade gerencial
    $("#pdi-result-bind").change(function() {
        $("#resposta-ajax").html("");
        $("#resposta-ajax-result").html("");
        $("#resposta-ajax").addClass("ajax-loader");
        $.ajax({
            type: "POST",
            data: $("form").serialize(),
            url: "ajax/resultadopdi/listaindicadores.php",
            success: function(data) {
                $("#resposta-ajax").html(data);
                $("#resposta-ajax").removeClass("ajax-loader");
                $(function() {
                    $("#pdi-result-br").click(function(event) {
                        event.preventDefault();
                        $("#resposta-ajax-result").html("");
                        $("#resposta-ajax-result").addClass("ajax-loader");
                        $.ajax({
                            url: $("form").attr("action"),
                            type: "POST",
                            data: $("form").serialize(),
                            success: function(data) {
                                $("#resposta-ajax-result").html(data);
                                $("#resposta-ajax-result").removeClass("ajax-loader");
                            }
                        });
                    });
                });
            },
            error: function() {
                $("#resposta-ajax").removeClass("ajax-loader");
            }

        });
    });
});

</script>