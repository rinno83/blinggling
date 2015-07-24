<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service_device extends CI_Controller {

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


//////////////////////////////////////////////////////////////////////////////
//																			//
//								List										//
//																			//
//////////////////////////////////////////////////////////////////////////////
	 
	public function index()
	{
		$view_data = array();
		$db_result = array();
		$total_row = 0;
		
		$timezone = $this->session->userdata('time');
		$view_data['timezone'] = $timezone;
		
		// GET DATA
		$service_id = $_GET['service_id'];
		$keyword = '';
		
		$current_page = (isset($_GET['current_page']))?$_GET['current_page']:1; // 현재 페이지
		
		$page_block = 10; // 한 화면에 보여지는 페이지 수
				
		$limit = 10; // 한 화면에 보여지는 리스트 수
		$offset = ($current_page - 1) * $limit; // 각 페이지의 한 화면의 리스트의 첫번째 인덱스
		
		// DB에서 DATA 얻기
		$db_result = $this->device_db_model->get_service_device_list($service_id, $keyword, $offset, $limit);
		//var_dump($db_result);

		// 전체 데이터 갯수 얻기
		$total_row = $db_result['count'][0]['count'];

		if($total_row > 0)
		{	
			$view_data['list'] = $db_result['list'];
			
			foreach($db_result['list'] as $key => $row)
	    	{
	    		
	    	}				
		}
		else
		{	
			$view_data['list'] = array();
		}
		
		$view_data['service_name'] = $db_result['count'][0]['name'];
		$view_data['service_id'] = $service_id;
		$view_data['total_row'] = $total_row;
		$view_data['paging'] = ajax_pagingHTML_pageBlock($current_page, $total_row, $page_block);
		$view_data['page_info'] = get_page_info_pageBlock($current_page, $total_row, $page_block);
		$view_data['page_info']['current_page'] = $current_page;
		
		//var_dump($view_data);
		$this->load->view('service_device_list_view', $view_data);
	}
	
	public function paging()
	{
		$view_data = array();
		$db_result = array();
		$total_row = 0;
		
		$timezone = $this->session->userdata('time');
		$view_data['timezone'] = $timezone;
		
		// POST DATA
		$keyword = $_POST['keyword'];
		$service_id = $_POST['service_id'];
		
		$current_page = isset($_POST['current_page'])?$_POST['current_page']:1; // 현재 페이지
		
		$page_block = 10; // 한 화면에 보여지는 페이지 수
		$limit = 10; // 한 화면에 보여지는 리스트 수
		$offset = ($current_page - 1) * $limit; // 각 페이지의 한 화면의 리스트의 첫번째 인덱스
		
		// DB에서 DATA 얻기
		$db_result = $this->device_db_model->get_service_device_list($service_id, $keyword, $offset, $limit);
		
		// 페이지 정보 얻기
		$totalRow = $db_result['count'][0]['count'];
		
		if($totalRow > 0)
		{	
			$view_data['list'] = $db_result['list'];
			$view_data['result'] = "1";
			
			foreach($db_result['list'] as $key => $row)
	    	{
	    		
	    	}
		}
		else
		{
			// 페이지 정보 얻기
			$view_data['list'] = array();
			$view_data['result'] = "1";			
		}
		
		$view_data['total_row'] = $total_row;
		$page_data['paging'] = ajax_pagingHTML_pageBlock($current_page, $total_row, $page_block);
		$page_data['page_info'] = get_page_info_pageBlock($current_page, $total_row, $page_block);
		$page_data['page_info']['current_page'] = $current_page;
		
		$result_data = array(
    		'result' => $view_data['result'],
    		'db_data' => $view_data['list'],
    		'page_data' => $page_data
    	);
    	
    	echo json_encode($result_data);
	}
	
//////////////////////////////////////////////////////////////////////////////
//																			//
//								Write										//
//																			//
//////////////////////////////////////////////////////////////////////////////
	
	
	
	public function write_from()
	{
		$view_data = array();
		$view_data['service_id'] = $_GET['service_id'];
		$view_data['service_name'] = $_GET['service_name'];
		
		$this->load->view('service_device_write_view', $view_data);
	}
	
	public function write()
	{
		$service_id = $_POST['service_id'];
		$device = $_POST['device'];
		$version_name = $_POST['version_name'];
		$version_code = $_POST['version_code'];
		
		if($device == 'ANDROID')
		{
			$gcm_service_key = $_POST['gcm_service_key'];
			$gcm_package_name = $_POST['gcm_package_name'];
			$gcm_queue_name = $_POST['gcm_queue_name'];
			$gcm_worker_count = $_POST['gcm_worker_count'];
			$gcm_feedback_api1 = $_POST['gcm_feedback_api1'];
			$gcm_feedback_api2 = $_POST['gcm_feedback_api2'];
			
			$db_result = $this->device_db_model->set_service_android(0, $service_id, $device, $version_name, $version_code, $gcm_service_key, $gcm_package_name, $gcm_queue_name, $gcm_worker_count, $gcm_feedback_api1, $gcm_feedback_api2);
			
			if($db_result == 1)
			{
				$result_data = array('result' => 1);
			}
			else if($db_result == 103)
			{
				$result_data = array('result' => 103);
			}
			else
			{
				$result_data = array('result' => 999);
			}
		}
		else
		{
			$cert = $_POST['cert'];
			$key = $_POST['key'];
			$is_production = $_POST['is_production'];
			$apns_queue_name = $_POST['apns_queue_name'];
			$apns_worker_count = $_POST['apns_worker_count'];
			$apns_feedback_api1 = $_POST['apns_feedback_api1'];
			$apns_feedback_api2 = $_POST['apns_feedback_api2'];
			
			$db_result = $this->device_db_model->set_service_iphone(0, $service_id, $device, $version_name, $version_code, $cert, $key, $is_production, $apns_queue_name, $apns_worker_count, $apns_feedback_api1, $apns_feedback_api2);
			
			if($db_result == 1)
			{
				$result_data = array('result' => 1);
			}
			else if($db_result == 103)
			{
				$result_data = array('result' => 103);
			}
			else
			{
				$result_data = array('result' => 999);
			}
		}
		
		//var_dump($result_data);
    	echo json_encode($result_data);
		
	}



//////////////////////////////////////////////////////////////////////////////
//																			//
//								Modify										//
//																			//
//////////////////////////////////////////////////////////////////////////////

	public function modify_form()
	{
		$view_data = array();
		$service_device_id = $_GET['service_device_id'];
		$view_data['current_page'] = $_GET['current_page'];
		
		$db_result = $this->device_db_model->get_service_device($service_device_id);		
		if($db_result)
		{
			$view_data['service_device'] = $db_result[0];
		}
		else
		{
			$view_data['service_device'] = array();
		}
		
		$this->load->view('service_device_modify_view', $view_data);
	}
	
	
	
	public function modify()
	{
		$service_device_id = $_POST['service_device_id'];
		$service_id = $_POST['service_id'];
		$device = $_POST['device'];
		$version_name = $_POST['version_name'];
		$version_code = $_POST['version_code'];
		
		if($device == 'ANDROID')
		{
			$gcm_service_key = $_POST['gcm_service_key'];
			$gcm_package_name = $_POST['gcm_package_name'];
			$gcm_queue_name = $_POST['gcm_queue_name'];
			$gcm_worker_count = $_POST['gcm_worker_count'];
			$gcm_feedback_api1 = $_POST['gcm_feedback_api1'];
			$gcm_feedback_api2 = $_POST['gcm_feedback_api2'];
			
			$db_result = $this->device_db_model->set_service_android($service_device_id, $service_id, $device, $version_name, $version_code, $gcm_service_key, $gcm_package_name, $gcm_queue_name, $gcm_worker_count, $gcm_feedback_api1, $gcm_feedback_api2);
			
			if($db_result == 1)
			{
				$result_data = array('result' => 1);
			}
			else
			{
				$result_data = array('result' => 999);
			}
		}
		else
		{
			$cert = $_POST['cert'];
			$key = $_POST['key'];
			$is_production = $_POST['is_production'];
			$apns_queue_name = $_POST['apns_queue_name'];
			$apns_worker_count = $_POST['apns_worker_count'];
			$apns_feedback_api1 = $_POST['apns_feedback_api1'];
			$apns_feedback_api2 = $_POST['apns_feedback_api2'];
			
			$db_result = $this->device_db_model->set_service_iphone($service_device_id, $service_id, $device, $version_name, $version_code, $cert, $key, $is_production, $apns_queue_name, $apns_worker_count, $apns_feedback_api1, $apns_feedback_api2);
			
			if($db_result == 1)
			{
				$result_data = array('result' => 1);
			}
			else
			{
				$result_data = array('result' => 999);
			}
		}
		
		//var_dump($result_data);
    	echo json_encode($result_data);
		
	}

	
	
	
//////////////////////////////////////////////////////////////////////////////
//																			//
//								Delete										//
//																			//
//////////////////////////////////////////////////////////////////////////////
	
	
	public function delete()
	{
		$view_data = array();
		$db_result = array();
		
		$items_obj = json_decode($_POST['items']);
		$service_id = $_POST['service_id'];
		
		foreach($items_obj as $row)
		{
			$db_result = $this->device_db_model->del_service_device($row->service_device_id, $row->device, $service_id);
		}		
			
		$result_data = array(
    		'result' => 1
    	);
    	
    	//var_dump($rData);
    	echo json_encode($result_data);
				
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */