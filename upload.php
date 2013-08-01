<?php

function handle_post()
{
	$response = array();
	$file = $_POST['file'];
	$local_root_path = $_POST['local_root_path'];
	$server_root_path = $_POST['server_root_path'];
	
	$needs_deleted = false;
	
	if(strpos($file, '-') === 0)
	{
		// this file needs to be deleted
		$needs_deleted = true;
		$file = substr($file, 1);
	}
	
	$root = realpath(dirname(__FILE__) . '/../../');
	$local_path = $local_root_path . $file;
	
	if(!$needs_deleted && !file_exists($local_path))
	{
		echo json_encode(array('status'=>false, 'error'=>'File not found'));
		die();
	}
	
	
	$msg = '';
	
	$server_path = $server_root_path . $file;
	
	$result = upload($_POST['server'], $_POST['username'], $_POST['password'], $_POST['port'], $local_path, $server_path, $needs_deleted);
	
	if($result > 0)
	{
		$response['status'] = true;
		
		if($needs_deleted)
		{
			$response['deleted'] = true;
		}
		
		$response['msg'] = $msg;
	}
	else if($result == -1)
	{
		$response = array('status'=>false, 'error'=>'Server not found!', 'can_not_continue'=>true);
	}
	else if($result == -2)
	{
		$response = array('status'=>false, 'error'=>'Could not login!', 'can_not_continue'=>true);
	}
	else
	{
		$response = array('status'=>false);
	}
	
	echo json_encode($response);
}

function upload($server, $username, $password, $port, $local_path, $server_path, $needs_deleted = false)
{
	if(isset($_POST['__ftp_connect']))
	{
		$conn_id = $_POST['__ftp_connect'];
	}
	else
	{
		$conn_id = ftp_connect($server, $port);
		if(!$conn_id) return -1;
		
		// send access parameters
		if(!@ftp_login($conn_id, $username, $password))
		{
			return -2;
		}
		
		$_POST['__ftp_connect'] = $conn_id;
	}
	
	
	
	if(!$needs_deleted)
	{
		check_create_directory($conn_id, $server_path);
		
		$file_size = filesize($local_path);
		
		for($i = 0; $i < 5; $i++)
		{
			// perform file upload
			$file_put_result = ftp_put($conn_id, $server_path, $local_path, FTP_BINARY);
			
			// get the size of $file
			$server_file_size = ftp_size($conn_id, $server_path);
			
			if ($server_file_size != -1 && $server_file_size == $file_size) {
				return $file_put_result;
			}
		}
		
		return false;
	}
	else
	{
		return ftp_delete($conn_id, $server_path);
	}
	
}

function check_create_directory($ftp_connection, $file_path)
{
	$directory = dirname($file_path);
	
	$dir_queue = array();
	$dir_array = explode('/', $directory);
	
	$dir_arr_temp = $dir_array;
	
	foreach($dir_array as $val)
	{
		$path =  implode('/', $dir_arr_temp);
		if(@ftp_chdir($ftp_connection, $path))
		{
			if(count($dir_queue))
			{
				$dir_queue = array_reverse($dir_queue);
				foreach($dir_queue as $dir)
				{
					ftp_mkdir($ftp_connection, $dir);
					ftp_chdir($ftp_connection, $dir);
				}
			}
			
			break;
		}
		else
		{
			$dir_queue[] = array_pop($dir_arr_temp);
		}
	}
	
	ftp_chdir($ftp_connection, '/');
}

handle_post();