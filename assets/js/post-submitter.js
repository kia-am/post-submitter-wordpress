jQuery(document).ready(function($){
	$('input[name="send_tlg"]').click(function(){
		if($(this).is(":checked")){
			$('input[name="send_image_tlg"]').parent().css("display","block");
		}else{
			$('input[name="send_image_tlg"]').parent().css("display","none");
		}
	});
});