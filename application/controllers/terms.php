<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Terms extends CI_Controller {

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
		$view_data = array();
		$db_result = array();
		$total_row = 0;
		
		$timezone = $this->session->userdata('time');
		$view_data['timezone'] = $timezone;
		
		// POST DATA
		$keyword = '';
		
		$current_page = (isset($_GET['current_page']))?$_GET['current_page']:1; // 현재 페이지
		
		$page_block = 10; // 한 화면에 보여지는 페이지 수
				
		$limit = 10; // 한 화면에 보여지는 리스트 수
		$offset = ($current_page - 1) * $limit; // 각 페이지의 한 화면의 리스트의 첫번째 인덱스
		
		// DB에서 DATA 얻기
		$db_result = $this->terms_db_model->get_terms_list(0, '', '', $keyword, $offset, $limit);
		
		// 전체 데이터 갯수 얻기
		$total_row = $db_result['count'];
		
		if($total_row > 0)
		{
			$view_data['list'] = $db_result['list'];		
			
			foreach($db_result['list'] as $key => $row)
	    	{
	    		$view_data['list'][$key]['is_show'] = ($row['is_show'])?'Y':'N';
	    		
	    		if($row['type'] == 'service')
	    		{
		    		$view_data['list'][$key]['type'] = '서비스 이용 약관';
	    		}	    		
	    		else if($row['type'] == 'private')
	    		{
		    		$view_data['list'][$key]['type'] = '개인정보 보호 정책';
	    		}
	    		else
	    		{
		    		$view_data['list'][$key]['type'] = '이용 안내';
	    		}
	    		
	    		$local = gmmktime(gmdate("H", strtotime($row['regist_date'])),gmdate("i", strtotime($row['regist_date']))-$timezone); // adjust GMT by client's offset 	
			    
			    $view_data['list'][$key]['regist_date'] = gmdate("Y-m-d H:i:s",$local);
	    	}				
		}
		else
		{
			// 페이지 정보 얻기
			$view_data['list'] = array();
		}
		
		$view_data['total_row'] = $total_row;
		$view_data['paging'] = ajax_pagingHTML_pageBlock($current_page, $total_row, $page_block);
		$view_data['page_info'] = get_page_info_pageBlock($current_page, $total_row, $page_block);
		$view_data['page_info']['current_page'] = $current_page;
		
		//var_dump($view_data);
		$this->load->view('terms_list_view', $view_data);
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
		
		$current_page = isset($_POST['current_page'])?$_POST['current_page']:1; // 현재 페이지
		
		$page_block = 10; // 한 화면에 보여지는 페이지 수
		$limit = 10; // 한 화면에 보여지는 리스트 수
		$offset = ($current_page - 1) * $limit; // 각 페이지의 한 화면의 리스트의 첫번째 인덱스
		
		// DB에서 DATA 얻기
		$db_result = $this->terms_db_model->get_terms_list(0, '', '', $keyword, $offset, $limit);
		
		// 페이지 정보 얻기
		$total_row = $db_result['count'];
			
		if($total_row > 0)
		{
			$view_data['list'] = $db_result['list'];
			$view_data['result'] = "1";
			
			foreach($db_result['list'] as $key => $row)
	    	{
	    		$view_data['list'][$key]['is_show'] = ($row['is_show'])?'Y':'N';
	    		
	    		if($row['type'] == 'service')
	    		{
		    		$view_data['list'][$key]['type'] = '서비스 이용 약관';
	    		}	    		
	    		else if($row['type'] == 'private')
	    		{
		    		$view_data['list'][$key]['type'] = '개인정보 보호 정책';
	    		}
	    		else
	    		{
		    		$view_data['list'][$key]['type'] = '이용 안내';
	    		}
	    		
	    		$local = gmmktime(gmdate("H", strtotime($row['regist_date'])),gmdate("i", strtotime($row['regist_date']))-$timezone); // adjust GMT by client's offset 	
			    
			    $view_data['list'][$key]['regist_date'] = gmdate("Y-m-d H:i:s",$local);
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
		
		$db_result = $this->service_db_model->get_language_list('', 0, 99999);
		if($db_result)
		{
			$view_data['language'] = $db_result['list'];
		}
		else
		{
			$view_data['language'] = array();
		}		
		
		$db_result = $this->service_db_model->get_service_list('', 0, 999999);
		if($db_result)
		{
			$view_data['service'] = $db_result['list'];
		}
		else
		{
			$view_data['service'] = array();
		}		
		
		$this->load->view('terms_write_view', $view_data);
	}
	
	public function write()
	{
		$service_id = $_POST['service_id'];
		$title = $_POST['title'];
		$content = $_POST['content'];
		$lang_code = $_POST['lang_code'];
		$type = $_POST['type'];
		$is_show = ($_POST['is_show'] == 'true')?1:0;
		
		$db_result = $this->terms_db_model->set_terms(0, $service_id, $lang_code, $type, $title, $content, $is_show);
		
		$result_data = array('result' => 1);
    	
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
		$terms_id = $_GET['terms_id'];
		$view_data['current_page'] = $_GET['current_page'];
		
		$db_result = $this->service_db_model->get_language_list('', 0, 99999);
		if($db_result)
		{
			$view_data['language'] = $db_result['list'];
		}
		else
		{
			$view_data['language'] = array();
		}		
		
		$db_result = $this->service_db_model->get_service_list('', 0, 999999);
		if($db_result)
		{
			$view_data['service'] = $db_result['list'];
		}
		else
		{
			$view_data['service'] = array();
		}	
		
		$db_result = $this->terms_db_model->get_terms($terms_id);		
		if($db_result)
		{
			$db_result[0]['content'] = str_replace(array("\r\n", "\n"),"",$db_result[0]['content']);
			$view_data['terms'] = $db_result[0];
		}
		else
		{
			$view_data['terms'] = array();
		}
		
		$this->load->view('terms_modify_view', $view_data);
	}
	
	
	
	public function modify()
	{
		$terms_id = $_POST['terms_id'];
		$service_id = $_POST['service_id'];
		$title = $_POST['title'];
		$content = $_POST['content'];
		$lang_code = $_POST['lang_code'];
		$type = $_POST['type'];
		$is_show = ($_POST['is_show'] == 'true')?1:0;

		$db_result = $this->terms_db_model->set_terms($terms_id, $service_id, $lang_code, $type, $title, $content, $is_show);
		
		$result_data = array('result' => 1);
    	
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
		
		foreach($item_ids as $row)
		{
			$db_result = $this->terms_db_model->del_terms($row);
		}		
			
		$result_data = array(
    		'result' => 1
    	);
    	
    	//var_dump($result_data);
    	echo json_encode($result_data);
				
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */