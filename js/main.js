$(function(){
	$('#btn_upload').click(function(){
		
		var can_not_continue = false;
		
		var $this = $(this);
		
		var txt_server = $('#txt_server').val();
		var txt_username = $('#txt_username').val();
		var txt_password = $('#txt_password').val();
		var txt_port = $('#txt_port').val();
		
		if(txt_server == '' || txt_username == '' || txt_password == '' || txt_port == '')
		{
			alert("Please fill up all the information");
			return false;
		}
		
		var local_root_path = $("#txt_local_root_path").val();
		var server_root_path = $("#txt_server_root_path").val();
		
		var $txt_file_list = $('#txt_file_list');
		
		
		var file_list = $txt_file_list.val().trim();
		
		
		if(file_list == "")
		{
			$this.attr("status", '');
			
		}
		else
		{
			$txt_file_list.attr("readonly", 'true');
			$this.attr("status", 'uploading');
			
			var lines = $("#txt_file_list").val().split("\n");
			
			if(lines.length)
			{
				$('#stats').text("");
				
				for(n in lines)
            	{
            		if(can_not_continue) break;
            		
            		var elem = lines[n];
            		
            		var data = {};
            		
		            
		            data.server = txt_server;
		            data.username = txt_username;
		            data.password = txt_password;
		            data.port = txt_port;
		            data.local_root_path = local_root_path;
		            data.server_root_path = server_root_path;
		            data.file = elem;
		            
		            $('#stats').append("<div class='start'>Upload Started: " + elem + "</div>");
		            
		            $.ajax({
		            	url: 'upload.php',
		            	data: data,
		            	type: 'post',
		            	dataType: 'json',
		            	success: function(e){
		            		if(e.status == true)
		            		{
		            			if(typeof e.escaped != "undefined")
			            		{
			            			$('#stats').append("<div class='msg'>Escaped</div><hr />");
			            		}
		            			else if(typeof e.deleted != "undefined")
			            		{
			            			$('#stats').append("<div class='finish'>File deleted</div><hr />");
			            		}
		            			else
		            			{
		            				$('#stats').append("<div class='finish'>Upload Finished: " + elem + "</div><hr />");
		            			}
		            		}
		            		else
		            		{
		            			$('#stats').append("<div class='error'>Upload Failed: " + elem + "</div><hr />");
		            		}
		            		
		            		if(typeof e.error != "undefined")
		            		{
		            			$('#stats').append("<div class='error'>" + e.error + "</div><hr />");
		            		}
		            		
		            		if(typeof e.msg != "undefined")
		            		{
		            			$('#stats').append("<div class='msg'>" + e.msg + "</div><hr />");
		            		}
		            		
		            		if(typeof e.can_not_continue != "undefined")
		            		{
		            			$('#stats').append("<div class='msg'>Quitting...</div><hr />");
		            			can_not_continue = true;
		            		}
		            	},
		            	error: function(){
		            		$('#stats').append("<div class='error'>Upload Failed: " + elem + "</div><hr />");
		            	},
		            	async: false,
		            });
		        };
		        
		        if(!can_not_continue)
		        {
		        	$('#stats').append("<div class='msg'>Finised...</div><hr />");
		        }
			}
			

			$txt_file_list.removeAttr('readonly');
			
		}
	});
});