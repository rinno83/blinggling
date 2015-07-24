<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mobile extends CI_Controller {

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
	 
	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//										예약 정보												   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function info_get()
	{
		$result_array = array();
		$order_code = $this->uri->segment(4);
		if($order_code)
		{
			$db_result = $this->menu_db_model->get_order_by_code($order_code);
			if($db_result)
			{
				$result_array = $db_result[0];
				
				$db_result_menu = $this->menu_db_model->get_order_menu($db_result[0]['order_id'], 0);
				$result_array['menu'] = $db_result_menu['list'];
				
				foreach($db_result_menu['list'] as $key => $row)
				{
					// get menu image
					$db_result_image = $this->menu_db_model->get_menu_image($row['menuId']);
					if($db_result_image)
					{
						foreach($db_result_image['list'] as $key3 => $row3)
						{
							 $result_array['menuImage'][$key]['menuImageUrl'] = $row3['menu_image_url'];
						}
					}
				}
			}
			
			//var_dump($result_array);
			$this->load->view('order_info_view', $result_array);
		}
		else
		{
			http_response_code(400);
			
			log_message('error', 'parameter error');
			
			echo json_encode(array('errorCode' => '00', 'errorMessage' => 'parameter error'));
		}		
	}
	
		 
	public function notice()
	{		
		$lang = 'ko';
		
		if(isset($_GET['lang']))
		{
			$lang = $_GET['lang'];
		}
	
		$view_data = array();
		$dbResult = array();
		$total_row = 0;
		
		// POST DATA
		$keyword = '';
		
		$current_page = 1; // 현재 페이지
		
		$page_block = 10; // 한 화면에 보여지는 페이지 수
				
		$limit = 0; // 한 화면에 보여지는 리스트 수
		$offset = ($current_page - 1) * $limit; // 각 페이지의 한 화면의 리스트의 첫번째 인덱스
		
		// DB에서 DATA 얻기
		$dbResult = $this->notice_db_model->get_notice_list(1, $lang, $keyword, $offset, $limit);
		
		if($dbResult)
		{
			$view_data['list'] = $dbResult['list'];
			
			// DATA 파싱
			foreach($dbResult['list'] as $key => $row)
	    	{
	    		$today = mktime();
	    		$reg_date = strtotime($row['regist_date']);
	    		
	    		$date_diff = $today - $reg_date;
	    		
	    		if($date_diff < 259200)
	    		{
		    		$view_data['list'][$key]['is_new'] = 'Y';
	    		}
	    		else
	    		{
		    		$view_data['list'][$key]['is_new'] = 'N';
	    		}
	    	}	
		}
		else
		{
			// 페이지 정보 얻기
			$view_data['list'] = array();
		}
		
		//var_dump($view_data);
		$this->load->view('m_notice_view', $view_data);
	}
	
	
	public function faq()
	{		
		$lang = 'ko';
		
		if(isset($_GET['lang']))
		{
			$lang = $_GET['lang'];
		}
	
		$view_data = array();
		$dbResult = array();
		$total_row = 0;
		
		// POST DATA
		$keyword = '';
		
		$current_page = 1; // 현재 페이지
		
		$page_block = 10; // 한 화면에 보여지는 페이지 수
				
		$limit = 0; // 한 화면에 보여지는 리스트 수
		$offset = ($current_page - 1) * $limit; // 각 페이지의 한 화면의 리스트의 첫번째 인덱스
		
		// DB에서 DATA 얻기
		$dbResult = $this->faq_db_model->get_faq_list(1, $lang, $keyword, $offset, $limit);
		
		if($dbResult)
		{
			$view_data['list'] = $dbResult['list'];
			
			// DATA 파싱
			foreach($dbResult['list'] as $key => $row)
	    	{
	    		$today = mktime();
	    		$reg_date = strtotime($row['regist_date']);
	    		
	    		$date_diff = $today - $reg_date;
	    		
	    		if($date_diff < 259200)
	    		{
		    		$view_data['list'][$key]['is_new'] = 'Y';
	    		}
	    		else
	    		{
		    		$view_data['list'][$key]['is_new'] = 'N';
	    		}
	    	}	
		}
		else
		{
			// 페이지 정보 얻기
			$view_data['list'] = array();
		}
		
		//var_dump($view_data);
		$this->load->view('m_faq_view', $view_data);
	}
	
	
	public function terms()
	{
		$lang = 'kr';
		if(isset($_GET['lang']))
		{
			$lang = $_GET['lang'];
		}
	
		$view_data = array();
		$dbResult = array();
		
		// DB에서 DATA 얻기
		$dbResult = $this->terms_db_model->get_terms_list(1, $lang, 'service', '', 0, 0);
		
		if($dbResult)
		{
			$view_data['list'] = $dbResult['list'];
			
			// DATA 파싱
			foreach($dbResult['list'] as $key => $row)
	    	{
	    		$today = mktime();
	    		$reg_date = strtotime($row['regist_date']);
	    		
	    		$date_diff = $today - $reg_date;
	    		
	    		if($date_diff < 259200)
	    		{
		    		$view_data['list'][$key]['is_new'] = 'Y';
	    		}
	    		else
	    		{
		    		$view_data['list'][$key]['is_new'] = 'N';
	    		}
	    	}	
		}
		else
		{
			// 페이지 정보 얻기
			$view_data['list'] = array();
		}
		
		//var_dump($view_data);
		$this->load->view('m_terms_view', $view_data);
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */