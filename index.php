<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>FTP File Upload</title>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	
	<link type="text/css" rel="stylesheet" media="screen" href="style.css">
</head>
<body>

<div id="wrapper">
	<div id="credentials">
		<form>
		Server: <input type="text" name="server" id="txt_server"> 
		Username: <input type="text" name="username" id="txt_username"> 
		Password: <input type="password" name="password" id="txt_password">
		Port: <input type="text" name="port" id="txt_port" value="21">
		</form> 
	</div>
	
	<div id="sites">
		Local root path: <br />
		<input id="txt_local_root_path" type="text" value="<?php echo dirname(__FILE__); ?>" /> <br /><br />
	
		Server root path: <br />
		<input id="txt_server_root_path" type="text" value="/" /> 
		<button id="btn_upload">Start Uploading</button>
	</div>
	
	<div id="paths">
	
	</div>
	
	<div id="file_list">
		Files to be updated:
		<textarea id="txt_file_list"></textarea>
	</div>
	
	<div id="stats">
	
	</div>
</div>
</body>
</html>