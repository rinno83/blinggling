<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inquiry extends CI_Controller {

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
		$db_result = $this->inquiry_db_model->get_inquiry_list(0, '', $keyword, $offset, $limit);
		
		// 전체 데이터 갯수 얻기
		$total_row = $db_result['count'];
		
		if($total_row > 0)
		{
			$view_data['list'] = $db_result['list'];		
			
			foreach($db_result['list'] as $key => $row)
	    	{
		    	$view_data['list'][$key]['content'] = nl2br($row['content']);
		    	if($row['status'] == 0)
		    	{
			    	$view_data['list'][$key]['is_answer'] = 'N';
		    	}
		    	else
		    	{
			    	$view_data['list'][$key]['is_answer'] = 'Y';
		    	}
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
		$this->load->view('inquiry_list_view', $view_data);
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
		$db_result = $this->inquiry_db_model->get_inquiry_list(0, '', $keyword, $offset, $limit);
		
		// 페이지 정보 얻기
		$total_row = $db_result['count'];

		if($total_row > 0)
		{
						
			$view_data['list'] = $db_result['list'];
			$view_data['result'] = "1";
			
			foreach($db_result['list'] as $key => $row)
	    	{
	    		if($row['status'] == 0)
		    	{
			    	$view_data['list'][$key]['is_answer'] = 'N';
		    	}
		    	else
		    	{
			    	$view_data['list'][$key]['is_answer'] = 'Y';
		    	}
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
//								Modify										//
//																			//
//////////////////////////////////////////////////////////////////////////////

	public function modify_form()
	{
		$view_data = array();
		$inquiry_id = $_GET['inquiry_id'];
		$view_data['current_page'] = $_GET['current_page'];
		
		$db_result = $this->inquiry_db_model->get_inquiry($inquiry_id);		
		if($db_result)
		{
			//$db_result[0]['content'] = str_replace(array("\r\n", "\n"),"",$db_result[0]['content']);
			//$db_result[0]['content'] = nl2br($db_result[0]['content']);
			//$db_result[0]['content'] = str_replace("\n","<br>", $db_result[0]['content']);
			$view_data['inquiry'] = $db_result[0];
		}
		else
		{
			$view_data['inquiry'] = array();
		}
		
		$this->load->view('inquiry_modify_view', $view_data);
	}
	
	
	
	public function modify()
	{
		$inquiry_id = $_POST['inquiry_id'];
		$phone = $_POST['phone'];
		$email = $_POST['email'];
		$content = $_POST['content'];
		$answer = $_POST['answer'];

		$db_result = $this->inquiry_db_model->set_inquiry($inquiry_id, $phone, $email, $content, $answer);
		
		$result_data = array('result' => 1);
    	
    	//var_dump($result_data);
    	echo json_encode($result_data);
		
	}

}	
	
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */