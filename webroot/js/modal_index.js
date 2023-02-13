function open_modal( id ){
	var maskHeight = $(document).height();
	var maskWidth = $(window).width();
	
	$('#mask').css({'width':maskWidth,'height':maskHeight});

	$('#mask').fadeIn(1000);	
	$('#mask').fadeTo("slow",0.8);	
	
	//Get the window height and width
	var winH = $(window).height();
	var winW = $(window).width();
              
	$(id).css('top',  winH/2-$(id).height()/2);
	$(id).css('left', winW/2-$(id).width()/2);
	$(id).fadeIn(2000); 
};
$(document).ready(function() {
	/*
	var url_atual = window.location.href;
	//alert(url_atual);
	if(url_atual.indexOf("?")==-1){
		$('a[name=modal]').click(function(e) {
			e.preventDefault();
			open_modal( $(this).attr('href') );	
		});
		
		open_modal( '#dialog' );//abrindo o div#modal ao carregar a página
		
		$('.window .close2').click(function (e) {
			e.preventDefault();
			
			$('#mask').hide();
			$('.window').hide();
		});		
		
		$('#mask').click(function () {
			$(this).hide();
			$('.window').hide();
		});
	}
	*/
});