<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Signature extends REST_Controller {

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
	
	public function php_info_get()
	{
		phpinfo();
	} 
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//										Signature 체크										   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function index_get()
	{
		if(isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']) || isset($_SERVER['HTTP_ACCESS_TOKEN']))
		{
			$access_token = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?$_SERVER['HTTP_NEW_ACCESS_TOKEN']:$_SERVER['HTTP_ACCESS_TOKEN'];
			
			header('Access-Token: ' . $access_token);
		}
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */