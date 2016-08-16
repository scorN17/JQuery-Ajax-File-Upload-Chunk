(function($){
	var upload_chunk_size= 1024*1024*8;
	$(document).ready(function(){
		$('#form').submit(function(){
			var flag= true;
			var reader= new FileReader();
			var file= $('#file',this).get(0).files.item(0);
			reader.readAsDataURL(file);
			$('.info').css({ color:'#0044c5' }).text('Загрузка ... Ожидайте!');
			reader.addEventListener("load",function(){
				var data= reader.result;
				$.ajaxSetup({ async: false });
				var pos= 0;
				while(pos<data.length && flag)
				{
					if(true || pos+upload_chunk_size>=data.length)
					{
						$.post('<?= $module_url ?>&act=ajaxfileupload',{
							chunk: data.substring(pos, pos+upload_chunk_size),
							offset: pos,
							all: data.length,
							last: (pos+upload_chunk_size>=data.length?'y':'n')
						});
					}
					pos += upload_chunk_size;
					//flag= false;
				}
				$('.info').css({ color:'#43ae01' }).text('Загрузка завершена!');
			},false);
			return false;
		});
	});
})(jQuery);
