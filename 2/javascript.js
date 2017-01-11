
		$('input[type="file"]').on('change',function(){
			var files= $(this).get(0).files;
			if(files.length)
			{
				var chunksize= 1024*1024*5;
				var file,chunkscc;
				for(var ii=1; ii<=files.length; ii++)
				{
					file= files.item(ii-1);
					if( ! file.size) continue;
					$('.sbpdt_files_progress').append('<div class="sbpdt_files_progress_'+ii+'"><div>&nbsp;</div></div>');
					chunkscc= Math.ceil(file.size/chunksize);
					for(var kk=0; kk<chunkscc; kk++)
					{
						fileChunkUpload(file, ii, chunkscc, kk);
					}
				}
				function fileChunkUpload(file, fileii, chunkscc, chunkkk)
				{
					var reader= new FileReader();
					var chunkblob= file.slice(chunkkk*chunksize, chunkkk*chunksize+chunksize);
					reader.readAsDataURL(chunkblob);
					reader.onload= function(e){
						var chunkblob= e.target.result;
						var formdata= new FormData;
						formdata.append('chunkblob', chunkblob);
						$.ajax({
							url: '/ajax/?ajax&a=fileChunkUpload&fn='+file.name+'&fs='+file.size+'&ii='+fileii+'&cc='+chunkscc+'&kk='+chunkkk,
							type: 'POST',
							data: formdata,
							processData: false,
							contentType: false
						}).done(function(data){
							if(data=='lastchunk')
							{
								$('.sbpdt_files_progress .sbpdt_files_progress_'+fileii+' div').width('100%');
								$('.sbpdt_files_progress .sbpdt_files_progress_'+fileii).animate({ opacity:0 },5000,function(){
									$(this).remove();
								});
								$.ajax({ url: '/ajax/?ajax&a=shopBasketFiles' })
								.done(function(data){
									$('.sbp_data .sbpdt_files_ajax').html(data);
								});
							}else{
								var proc= parseInt(data)*100/file.size;
								$('.sbpdt_files_progress .sbpdt_files_progress_'+fileii+' div').width(proc+'%');
							}
						});
					};
				}
			}
		});
