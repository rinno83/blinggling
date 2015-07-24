<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notice extends CI_Controller {

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
		$db_result = $this->notice_db_model->get_notice_list(0, '', $keyword, $offset, $limit);
		
		// 전체 데이터 갯수 얻기
		$total_row = $db_result['count'];
		
		if($total_row > 0)
		{
			$view_data['list'] = $db_result['list'];		
			
			foreach($db_result['list'] as $key => $row)
	    	{
	    		$view_data['list'][$key]['is_show'] = ($row['is_show'])?'Y':'N';
	    		
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
		$this->load->view('notice_list_view', $view_data);
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
		$db_result = $this->notice_db_model->get_notice_list(0, '', $keyword, $offset, $limit);
		
		// 페이지 정보 얻기
		$total_row = $db_result['count'];

		if($total_row > 0)
		{
						
			$view_data['list'] = $db_result['list'];
			$view_data['result'] = "1";
			
			foreach($db_result['list'] as $key => $row)
	    	{
	    		$view_data['list'][$key]['is_show'] = ($row['is_show'])?'Y':'N';
	    		
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
		
		$this->load->view('notice_write_view', $view_data);
	}
	
	public function write()
	{
		$service_id = $_POST['service_id'];
		$service_key = $_POST['service_key'];
		$title = $_POST['title'];
		$content = $_POST['content'];
		$lang_code = $_POST['lang_code'];
		$is_show = ($_POST['is_show'] == 'true')?1:0;
		
		$db_result = $this->notice_db_model->set_notice(0, $service_id, $lang_code, $title, $content, $is_show);
		
		// write file cache
		write_file_cache('notice', $service_key, $lang_code);
		
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
		$notice_id = $_GET['notice_id'];
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
		
		$db_result = $this->notice_db_model->get_notice($notice_id);		
		if($db_result)
		{
			$db_result[0]['content'] = str_replace(array("\r\n", "\n"),"",$db_result[0]['content']);
			$view_data['notice'] = $db_result[0];
		}
		else
		{
			$view_data['notice'] = array();
		}
		
		$this->load->view('notice_modify_view', $view_data);
	}
	
	
	
	public function modify()
	{
		$notice_id = $_POST['notice_id'];
		$service_id = $_POST['service_id'];
		$service_key = $_POST['service_key'];
		$title = $_POST['title'];
		$content = $_POST['content'];
		$lang_code = $_POST['lang_code'];
		$is_show = ($_POST['is_show'] == 'true')?1:0;

		$db_result = $this->notice_db_model->set_notice($notice_id, $service_id, $lang_code, $title, $content, $is_show);
		
		// write file cache
		write_file_cache('notice', $service_key, $lang_code);
		
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
		
		$items = $_POST['items'];
		$item_array = json_decode($items);
		
		foreach($item_array as $row)
		{
			$db_result = $this->notice_db_model->del_notice($row->notice_id);
			
			// write file cache
			write_file_cache('notice', $row->service_key, $row->lang_code);
		}
			
		$result_data = array(
    		'result' => 1
    	);
    	
    	//var_dump($result_data);
    	echo json_encode($result_data);
				
	}


//////////////////////////////////////////////////////////////////////////////
//																			//
//								API											//
//																			//
//////////////////////////////////////////////////////////////////////////////
	
	
	public function api()
	{
		$default = array('current_page');
		$current_page = $this->uri->segment(3);	
		$result_data = array();
		
		if(isset($_SERVER['HTTP_SERVICE_KEY']) && isset($_SERVER['HTTP_LANGUAGE_CODE']) && $current_page)
		{
			// parameter
			$service_key = $_SERVER['HTTP_SERVICE_KEY'];
			$service_id_obj = get_service_id($service_key);
			$service_id_array = json_decode($service_id_obj);
			$lang_code = $_SERVER['HTTP_LANGUAGE_CODE'];
			
			// page info
			$keyword = '';
			$page_block = 10; // 한 화면에 보여지는 페이지 수
			$limit = 20; // 한 화면에 보여지는 리스트 수
			$offset = ($current_page - 1) * $limit; // 각 페이지의 한 화면의 리스트의 첫번째 인덱스
			
			if($service_id_array->result != "403")
			{
				$service_id = $service_id_array->data;
				$notice_list = array();
				$this->load->helper('file');
				
				// service_key.notice File이 존재할 경우
				if($current_page == 1 && read_file($this->config->item('file_full_path').$service_key.'_'.$lang_code.'.notice'))
				{
					$string_data = read_file($this->config->item('file_full_path').$service_key.'_'.$lang_code.'.notice');
					$file_result_array = json_decode($string_data);
					
					foreach($file_result_array as $key => $row)
					{
						$notice_list[$key]['notice_id'] = $row->notice_id;
						$notice_list[$key]['title'] = $row->title;
						$notice_list[$key]['content'] = $row->content;
						$notice_list[$key]['regist_date'] = $row->regist_date;
					}
					
					$result_data = array(
						'result' => "1",
						'data' => $notice_list
					);
				}
				else
				{
					// DB에서 DATA 얻기
					$db_result = $this->notice_db_model->get_notice_list($service_id, $lang_code, $keyword, $offset, $limit);
					
					if(isset($db_result['list']))
					{
						$notice_list = $db_result['list'];
						foreach($notice_list as $key => $row)
						{
							$date = new DateTime($row['regist_date'], new DateTimeZone('Asia/Seoul'));
							$notice_list[$key]['regist_date'] = (string)$date->getTimeStamp();
						}
					}
	
					$result_data = array(
						'result' => "1",
						'data' => $notice_list
					);
				}
			}
			else
			{
				$result_data = array(
					'result' => "403"
				);
			}
		}
		else
		{
			$result_data = array(
				'result' => "400"
			);
		}
		
		echo json_encode($result_data);				
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */