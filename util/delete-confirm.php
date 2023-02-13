<!-- Modal Dialog -->
<div class="modal fade" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">SinPEG - Confirmação de Exclusão</h4>
      </div>
      <div class="modal-body">
        <p>Você quer realmente excluir o item selecionado?</p>
                      <input type="hidden" name="codigo" id="codigo" value=""/>
        
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="confirm">Excluir</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
function opdelete(codigo){
  $(".modal-body #codigo").val(codigo);
}

         
    
    <!-- Form confirm (yes/ok) handler, submits form -->
    $('#confirmDelete').find('.modal-footer #confirm').on('click', function(){
   
      var codigo=  $(".modal-body #codigo").val();
      
   //  alert(codigo);
     $.ajax({
      url: "ajax/prodsaude4/delpsaude4.php",     
       type: 'POST',
       data: {codigo:codigo}, 
       success: function(data) {
               //$('div#msg').html(data);
              location.reload(); 
                
            }});
    });
        </script>