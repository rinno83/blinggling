<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Service extends REST_Controller {

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
	 
	 
	public function terms_get()
	{
		$result_array = array();
		
		$lang_code = 'ko';
		
		if(isset($_GET['lang']))
		{
			$lang_code = $_GET['lang'];
		}
		$keyword = '';
		$offset = 0;
		$limit = 10;
		
		$db_result = $this->terms_db_model->get_terms_list(1, $lang_code, '', $keyword, $offset, $limit);
		if($db_result)
		{
			$result_array = $db_result['list'];
		}
		
		echo json_encode($result_array);
	}
	
	
	public function version_get()
	{
		$result_array = array();
		
		$device = $_SERVER['HTTP_CLIENT_TYPE'];
		
		$db_result = $this->service_db_model->get_service_version($this->config->item('service_key'), $device);
		if($db_result)
		{
			$result_array = $db_result[0];
		}
		
		echo json_encode($result_array);
	}


	public function corp_info_get()
	{
		$result_array = array();
		
		$db_result = $this->service_db_model->get_corp_info_api();
		if($db_result)
		{
			$result_array = $db_result[0];
		}
		
		echo json_encode($result_array);
	}
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */