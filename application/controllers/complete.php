<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Complete extends REST_Controller {

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
	public function index_get()
	{
		phpinfo();
	}
	
	public function index_post()
	{
/*
		try
		{
			$file_name = $this->post('name');
			$ext = substr(strrchr($file_name, '.'), 1);
			$path = $this->post('path');
			
			$file_url = $this->config->item('host').$this->config->item('file_path').basename($path).'.'.$ext;
			
			rename($path, $path.".".$ext);
			$path = $path.".".$ext;
			
			//resize
			$thumb = new Imagick();
			$thumb->readImage($path);
			
			$thumb->resizeImage(300, 300, Imagick::FILTER_POINT, 1, TRUE);
			$thumb->writeImage($path . '.thumb');
			$thumb->clear();
			$thumb->destroy();
			
			echo json_encode(array('url' => $file_url));
		}
		catch(Exception $e)
		{
			log_message('error', 'file upload complete error message :: ' . $e->getMessage());
		}
*/
		$config = config_get();
		$name = $this->post('name');
		$tmpPath = $this->post('path');
		
		$path_parts = pathinfo($tmpPath);
		
		$ext = pathinfo($name)['extension'];
				
		$newPath = $path_parts['dirname'] . "/" . uniqid(). "." .pathinfo($name)['extension'];
		
		rename($tmpPath, $newPath);
		
		if($ext == 'png' || $ext == 'jpg') {
			$config['image_library'] = 'gd2';
			$config['source_image']	= $newPath;
			$config['create_thumb'] = TRUE;
			$config['maintain_ratio'] = TRUE;
			$config['width']	= 200;
			$config['height']	= 200;
							
			$this->load->library('image_lib', $config);
			
			$this->image_lib->resize();	
			$this->image_lib->clear();
		}
				
		$path_parts = pathinfo($newPath);

		echo 'http://' . $config['host'] . $config['file_path'] . $path_parts['basename'];
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */