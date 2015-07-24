<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service extends CI_Controller {

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
//								Image										//
//																			//
//////////////////////////////////////////////////////////////////////////////

	public function image()
	{
		$result_data = array();
		
		if(isset($_GET['image_url']))
		{
			$result_data['image_url'] = $_GET['image_url'];
		}
		$this->load->view('image_view', $result_data);
	}





//////////////////////////////////////////////////////////////////////////////
//																			//
//								List										//
//																			//
//////////////////////////////////////////////////////////////////////////////


	 
	/*
		
	*/
	public function index()
	{
		$view_data = array();
		$db_result = array();
		$total_row = 0;
		
		$timezone = $this->session->userdata('time');
		$view_data['timezone'] = $timezone;
		
		// POST DATA
		$keyword = '';
		
		$current_page = (isset($_GET['current_page']))?$_GET['current_page']:1; // 현재 페이지
		$view_data['current_page'] = $current_page;
		
		$page_block = 10; // 한 화면에 보여지는 페이지 수
		$limit = 10; // 한 화면에 보여지는 리스트 수
		$offset = ($current_page - 1) * $limit; // 각 페이지의 한 화면의 리스트의 첫번째 인덱스
		
		// DB에서 DATA 얻기
		$db_result = $this->service_db_model->get_service_list($keyword, $offset, $limit);
		//var_dump($db_result);
		
		$total_row = $db_result['count'];
		
		if($total_row > 0)
		{
			$view_data['list'] = $db_result['list'];
			
			// DATA 파싱
			foreach($db_result['list'] as $key => $row)
	    	{
	    		$local = gmmktime(gmdate("H", strtotime($row['regist_date'])),gmdate("i", strtotime($row['regist_date']))-$timezone); // adjust GMT by client's offset 	
			    
			    $view_data['list'][$key]['regist_date'] = gmdate("Y-m-d H:i:s",$local);
	    	}	
		}
		else
		{
			// 페이지 정보 얻기
			$view_data['paging'] = ajax_pagingHTML_pageBlock($current_page, $total_row, $page_block);
			
			$view_data['list'] = array();
		}
		
		$view_data['total_row'] = $total_row;
		$view_data['paging'] = ajax_pagingHTML_pageBlock($current_page, $total_row, $page_block);
		$view_data['page_info'] = get_page_info_pageBlock($current_page, $total_row, $page_block);
		$view_data['page_info']['current_page'] = $current_page;
		
		//var_dump($view_data);
		$this->load->view('service_list_view', $view_data);
	}
	
	public function paging()
	{
		$view_data = array();
		$db_result = array();
		
		$timezone = $this->session->userdata('time');
		$view_data['timezone'] = $timezone;
		
		// POST DATA
		$keyword = isset($_POST['keyword'])?$_POST['keyword']:'';
		
		$current_page = isset($_POST['current_page'])?$_POST['current_page']:1; // 현재 페이지
		$page_data['current_page'] = $current_page;
		
		$page_block = 10; // 한 화면에 보여지는 페이지 수
		$limit = 10; // 한 화면에 보여지는 리스트 수
		$offset = ($current_page - 1) * $limit; // 각 페이지의 한 화면의 리스트의 첫번째 인덱스
		
		// DB에서 DATA 얻기
		$db_result = $this->service_db_model->get_service_list($keyword, $offset, $limit);

		// 전체 데이터 갯수 얻기
		$total_row = $db_result['count'];
		
		if($total_row > 0 && isset($db_result['list']))
		{
			$view_data['list'] = $db_result['list'];
			$view_data['result'] = "1";
			
			// DATA 파싱
			foreach($db_result['list'] as $key => $row)
	    	{
	    		$local = gmmktime(gmdate("H", strtotime($row['regist_date'])),gmdate("i", strtotime($row['regist_date']))-$timezone); // adjust GMT by client's offset 	
			    
			    $view_data['list'][$key]['registDate'] = gmdate("Y-m-d H:i:s",$local);
	    	}	
		}
		else
		{
			// 페이지 정보 얻기
			$total_row = 0;
			
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
    	
    	//var_dump($result_data);
    	echo json_encode($result_data);
	}
	
	
	
	
	
	public function language()
	{
		$view_data = array();
		$db_result = array();
		$total_row = 0;
		
		$timezone = $this->session->userdata('time');
		$view_data['timezone'] = $timezone;
		
		// POST DATA
		$keyword = '';
		
		$current_page = (isset($_GET['current_page']))?$_GET['current_page']:1; // 현재 페이지
		$view_data['current_page'] = $current_page;
		
		$page_block = 10; // 한 화면에 보여지는 페이지 수
		$limit = 10; // 한 화면에 보여지는 리스트 수
		$offset = ($current_page - 1) * $limit; // 각 페이지의 한 화면의 리스트의 첫번째 인덱스
		
		// DB에서 DATA 얻기
		$db_result = $this->service_db_model->get_language_list($keyword, $offset, $limit);
		$total_row = $db_result['count'];
		
		if($total_row > 0)
		{
			$view_data['list'] = $db_result['list'];
			
			
			// DATA 파싱
			foreach($db_result['list'] as $key => $row)
	    	{
	    		$local = gmmktime(gmdate("H", strtotime($row['regist_date'])),gmdate("i", strtotime($row['regist_date']))-$timezone); // adjust GMT by client's offset 	
			    
			    $view_data['list'][$key]['regist_date'] = gmdate("Y-m-d H:i:s",$local);
	    	}	
		}
		else
		{
			$view_data['list'] = array();
		}
		
		$view_data['total_row'] = $total_row;
		$view_data['paging'] = ajax_pagingHTML_pageBlock($current_page, $total_row, $page_block);
		$view_data['page_info'] = get_page_info_pageBlock($current_page, $total_row, $page_block);
		$view_data['page_info']['current_page'] = $current_page;
		
		//var_dump($viewData);
		$this->load->view('language_list_view', $view_data);
	}
	
	public function language_paging()
	{
		$view_data = array();
		$db_result = array();
		
		$timezone = $this->session->userdata('time');
		$view_data['timezone'] = $timezone;
		
		// POST DATA
		$keyword = isset($_POST['keyword'])?$_POST['keyword']:'';
		
		$current_page = isset($_POST['current_page'])?$_POST['current_page']:1; // 현재 페이지
		$page_data['current_page'] = $current_page;
		
		$page_block = 10; // 한 화면에 보여지는 페이지 수
		$limit = 10; // 한 화면에 보여지는 리스트 수
		$offset = ($current_page - 1) * $limit; // 각 페이지의 한 화면의 리스트의 첫번째 인덱스
		
		// DB에서 DATA 얻기		
		$db_result = $this->service_db_model->get_language_list($keyword, $offset, $limit);		

		$total_row = $db_result['count'];
		
		if($total_row > 0)
		{
			$view_data['list'] = $db_result['list'];
			$view_data['result'] = "1";
			
			// DATA 파싱
			foreach($db_result['list'] as $key => $row)
	    	{
	    		$local = gmmktime(gmdate("H", strtotime($row['regist_date'])),gmdate("i", strtotime($row['regist_date']))-$timezone); // adjust GMT by client's offset 	
			    
			    $view_data['list'][$key]['regist_date'] = gmdate("Y-m-d H:i:s",$local);
	    	}	
		}
		else
		{
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
    	
    	//var_dump($result_data);
    	echo json_encode($result_data);
	}
	
	
	public function quick()
	{
		$db_result = $this->service_db_model->get_service_delivery_fee($this->config->item('service_key'));
		$this->load->view('service_quick_view', $db_result[0]);
	}
	
	
	
	
	
//////////////////////////////////////////////////////////////////////////////
//																			//
//								Write										//
//																			//
//////////////////////////////////////////////////////////////////////////////
	
	
	
	public function write_form()
	{
		$view_data = array();
		
		$db_result = $this->service_db_model->get_language_list('', 0, 999999);

		if($db_result['count'] > 0)
		{
			$view_data['language'] = $db_result['list'];
		}
		else
		{
			$view_data['language'] = array();
		}		
		
		$this->load->view('service_write_view', $view_data);
	}
	
	public function write()
	{
		$name = $_POST['name'];
		$desc = $_POST['desc'];
		$lang_code = $_POST['lang_code'];
		
		$db_result = $this->service_db_model->set_service(0, $lang_code, $name, $desc);
		//var_dump($db_result);
		
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
			$result_data = array('result' => 9999);
		}
    	
    	//var_dump($result_data);
    	echo json_encode($result_data);
		
	}


	public function language_write_from()
	{
		$this->load->view('language_write_view');
	}
	
	public function language_write()
	{
		$lang_code = strtolower($_POST['lang_code']);
		$lang_name = $_POST['lang_name'];
		
		$db_result = $this->service_db_model->set_language($lang_code, $lang_name);
		
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
			$result_data = array('result' => 9999);
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
		$service_id = $_GET['service_id'];
		
		// 서비스 언어 가져오기
		$db_result = $this->service_db_model->get_language_list('', 0, 99999);
		if($db_result)
		{
			$view_data['language'] = $db_result['list'];
		}
		else
		{
			$view_data['language'] = array();
		}
		
		// 서비스 가져오기
		$db_result = $this->service_db_model->get_service($service_id);

		if($db_result)
		{
			$view_data['service'] = $db_result[0];
		}
		else
		{
			$view_data['service'] = array(
				'serviceId' => '',
				'key' => '',
				'name' => '',
				'desc' => '',
				'registDate' => ''
			);
		}
		
		$this->load->view('service_modify_view', $view_data);
	}
	
	public function modify()
	{
		$service_id = $_POST['service_id'];
		$lang_code = $_POST['lang_code'];
		$name = $_POST['name'];
		$desc = $_POST['desc'];
		
		$db_result = $this->service_db_model->set_service($service_id, $lang_code, $name, $desc);
		
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
			$result_data = array('result' => 9999);
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
		
		$item_ids = explode(',', $_POST['item_ids']);
		
		$success_count = 0;
		$fail_count = 0;
		
		foreach($item_ids as $row)
		{
			$db_result = $this->service_db_model->del_service($row);
			
			if($db_result == 1)
			{
				$success_count++;
			}
			else
			{
				$fail_count++;
			}
		}		
			
		$result_data = array(
    		'result' => 1,
    		'success' => $success_count,
    		'fail' => $fail_count
    	);
    	
    	//var_dump($result_data);
    	echo json_encode($result_data);
				
	}


	public function language_delete()
	{
		$view_data = array();
		$db_result = array();
		
		$item_ids = explode(',', $_POST['item_ids']);
		
		$success_count = 0;
		$fail_count = 0;
		
		foreach($item_ids as $row)
		{
			$db_result = $this->service_db_model->del_language($row);
			
			if($db_result == 1)
			{
				$success_count++;
			}
			else
			{
				$fail_count++;
			}
		}		
			
		$result_data = array(
    		'result' => 1,
    		'success' => $success_count,
    		'fail' => $fail_count
    	);
    	
    	//var_dump($result_data);
    	echo json_encode($result_data);
				
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */