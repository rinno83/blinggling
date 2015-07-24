<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class File extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	 
	public function __construct()
	{
	    parent::__construct();
	    // Your own constructor code	
	    
	    
	} 	
	
	public function complete()
	{
		$file_name = $_FILES['files']['name'];
		$ext = substr(strrchr($file_name, '.'), 1);
		$path = $_FILES['files']['tmp_name'];
		
		rename($path, $path.".".$ext);
		
		$file_upload = $this->config->item('file_full_path').$path;
		$file_url = $this->config->item('host_url').$this->config->item('file_path').basename($path).'.'.$ext;
		
		if(move_uploaded_file($_FILES['files']['tmp_name'], $file_upload))
		{
			//resize
			$thumb = new Imagick();
			$thumb->readImage($file_upload);
			
			$thumb->resizeImage(200, 200, Imagick::FILTER_POINT, 1, TRUE);
			$thumb->writeImage($file_upload . '.thumb');
			$thumb->clear();
			$thumb->destroy();
			
			$rData = array(
	    		'result' => "1",
	    		'resultMessage' => "성공",
	    		'data' => array('url' => $file_url)
	    	);		    	
		}
		else
		{
			$rData = array(
	    		'result' => "998",
	    		'resultMessage' => "HTTP 에러"
	    	);	
		}
    	
    	echo json_encode($rData);
	}
	
	public function upload()
	{
		//var_dump($_FILES);
		$file_name = $_FILES['attachedFile']['name'];
		$file_array = explode('.', $file_name);
		
		if($file_array)
		{
			$ext = $file_array[1];
		}
		else
		{
			$ext = 'jpg';
		}
		
		$t = microtime(true);
		$micro = sprintf("%06d",($t - floor($t)) * 1000000);
		$d = new DateTime( date('Y-m-d H:i:s.'.$micro,$t) );
		$newFileName = $d->format("YmdHisu").'.'.$ext;
		
		$file_upload = $this->config->item('fullPath').$newFileName;
		
		$file_url = $this->config->item('imageHost').$this->config->item('path').$newFileName;
		
		if(move_uploaded_file($_FILES['attachedFile']['tmp_name'], $file_upload))
		{
			//resize
			$thumb = new Imagick();
			$thumb->readImage($file_upload);
			
			$thumb->resizeImage(200, 200, Imagick::FILTER_POINT, 1, TRUE);
			$thumb->writeImage($file_upload . '.thumb');
			$thumb->clear();
			$thumb->destroy();
			
			$rData = array(
	    		'result' => "1",
	    		'url' => $file_url
	    	);		    	
		}
		else
		{
			$rData = array(
	    		'result' => "2"
	    	);	
		}
    	
    	echo json_encode($rData);
	}
	
	public function make_url()
	{
		$urls = $_POST['urls'];
		
		$url = json_decode($urls);
		$file_arr = array();
		$file_dir = $this->config->item('file_full_path');
		
		if($_SERVER['CI_ENV'] == 'development')
		{
			$file_url = $this->config->item('host'). ':8888' . $this->config->item('file_path');
		}
		else
		{
			$file_url = $this->config->item('host').$this->config->item('file_path');
		}
				
		foreach($url as $row)
		{
			$t = microtime(true);
			$micro = sprintf("%06d",($t - floor($t)) * 1000000);
			$temp_name = date('ymdHis'.$micro,$t);
			
			
			switch(substr($row, 5, strpos($row, ';')-5))
			{
				case "image/jpeg": $img = str_replace('data:image/jpeg;base64,', '', $row);
								$file_name = $temp_name . '.jpg';
								break;
				case "image/png": $img = str_replace('data:image/png;base64,', '', $row);
								$file_name = $temp_name . '.png';
								break;
				case "image/gif": $img = str_replace('data:image/gif;base64,', '', $row);
								$file_name = $temp_name . '.gif';
								break;
				default: $img = str_replace('data:image/jpeg;base64,', '', $row);
						$file_name = $temp_name . '.jpg';
						break;
			}
			
			$data = base64_decode($img);
			$success = file_put_contents($file_dir.$file_name, $data);
			
			// Resize
			$file_resize = image_resize($file_dir.$file_name, $file_dir.$file_name.'.thumb', 200, 200, false);
			
			array_push($file_arr, $file_url.$file_name);			
		}
		
		if($file_arr)
		{
			$result_data = array(
	    		'result' => "1",
	    		'data' => $file_arr
	    	);
		}
		else
		{
			$result_data = array(
	    		'result' => "2"
	    	);
		}
		
		echo json_encode($result_data);
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */