<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
	public function index()
	{
		//phpinfo();
		//$this->load->view('welcome_message');
		date_default_timezone_set('Asia/Seoul');
		$t = microtime(true) * 100;
		
		$random = substr( md5(rand()), 0, 7);
		$random1 = mt_rand(0,999999);
		var_dump($t.'_'.$random1);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */