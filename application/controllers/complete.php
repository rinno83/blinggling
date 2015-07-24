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
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */