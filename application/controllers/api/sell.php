<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Sell extends REST_Controller {

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
	//										작업 상태 변경											   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function work_post()
	{
		if(check_header())
		{
			if($this->post('orderId') && $this->post('type'))
			{
				$access_token_check = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?check_token($_SERVER['HTTP_NEW_ACCESS_TOKEN']):check_token($_SERVER['HTTP_ACCESS_TOKEN']);
				if($access_token_check['result'] == 0)
				{
					http_response_code(400);
					
					log_message('error', 'access token error');
					
					echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
					
					exit(0);
				}
				
				$access_token = $access_token_check['access_token'];
				header('Access-Token: ' . $access_token);
				
				$seller_id = (isset($_SERVER['HTTP_XID']))?$_SERVER['HTTP_XID']:get_seller_id($access_token);
				
				$order_id = $this->post('orderId');
				$type = $this->post('type');
				$status = ($this->post('status'))?1:0;
				
				if($type == 'sell' && $status == 0)
				{
					http_response_code(400);
					
					log_message('error', 'invalid status value');
					
					echo json_encode(array('errorCode' => '03', 'errorMessage' => 'invalid status value'));
					
					exit(0);
				}
				
				$db_result = $this->seller_db_model->set_work_status($seller_id, $order_id, $type, $status);
				if($db_result == -1)
				{
					http_response_code(500);
				
					log_message('error', 'set work status db error');
				}
				else
				{
					// 정산 설정
					if($type == 'sell')
					{
						$this->seller_db_model->set_account($order_id);
					}
				}
			}
			else
			{
				http_response_code(400);
			
				log_message('error', 'parameter error');
				
				echo json_encode(array('errorCode' => '00', 'errorMessage' => 'parameter error'));
			}
		}
		else
		{
			http_response_code(400);
			
			log_message('error', 'header parameter error');
			
			echo json_encode(array('errorCode' => '00', 'errorMessage' => 'header parameter error'));
		}
	}
	
	
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//										주문 목록												   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function order_get()
	{
		if(check_header())
		{
			if((isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']) || isset($_SERVER['HTTP_ACCESS_TOKEN'])))
			{
				$result_array = array();
				$access_token_check = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?check_token($_SERVER['HTTP_NEW_ACCESS_TOKEN']):check_token($_SERVER['HTTP_ACCESS_TOKEN']);
				if($access_token_check['result'] == 0)
				{
					http_response_code(400);
					
					log_message('error', 'access token error');
					
					echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
					
					exit(0);
				}
				
				$access_token = $access_token_check['access_token'];
				header('Access-Token: ' . $access_token);
				
				$seller_id = (isset($_SERVER['HTTP_XID']))?$_SERVER['HTTP_XID']:get_seller_id($access_token);
				
				header('Access-Token: ' . $access_token);
				
				$sell_status = ($this->uri->segment(4))?$this->uri->segment(4):0;
				$current_page = ($this->uri->segment(5))?$this->uri->segment(5):1;
				
				$limit = 10; // 한 화면에 보여지는 리스트 수
				$offset = ($current_page - 1) * $limit; // 각 페이지의 한 화면의 리스트의 첫번째 인덱스
				
				// 주문 목록
				$db_result = $this->seller_db_model->get_order_list($seller_id, $sell_status, $offset, $limit);
				if($db_result)
				{
					foreach($db_result['list'] as $key => $row)
					{
						$result_array[$key] = $db_result['list'][$key];
						$order_id = $row['orderId'];
						
						// 주문 메뉴 목록
						$db_result_menu = $this->seller_db_model->get_order_menu($order_id);
						if($db_result_menu)
						{
							$result_array[$key]['menu'] = $db_result_menu['list'];
							$menu_service_string = '';
							$menu_service_array = array();
							
							foreach($db_result_menu['list'] as $key2 => $row2)
							{
								// 메뉴 서비스 가져오기
								$db_result_service = $this->menu_db_model->get_menu_service($row2['menuId'], 1);
								if($db_result_service)
								{
									foreach($db_result_service['list'] as $key3 => $row3)
									{
										if(strpos($menu_service_string, $row3['menuServiceName']) !== false) {  
											$menu_service_array[$row3['menuServiceName']] = $menu_service_array[$row3['menuServiceName']] + 1;
										}
										else
										{
											if($menu_service_string == '')
											{
												$menu_service_string .=  $row3['menuServiceName'];
											}
											else
											{
												$menu_service_string .=  ','.$row3['menuServiceName'];
											}
											$menu_service_array[$row3['menuServiceName']] = 1;
										}
									}
								}
							}													
						}
						else
						{
							$result_array[$key]['menu'] = array();
						}
					
						$menu_service_result = array();
						$menu_service_count = 0;
						foreach($menu_service_array as $key4 => $row4)
						{
							$temp = array('menuServiceName' => $key4,'menuServiceCount' => $row4);
							$menu_service_result[$menu_service_count] = $temp;
							$menu_service_count++;
						}
						$result_array[$key]['menuService'] = $menu_service_result;
					}
				}
				
				echo json_encode($result_array);
			}
			else
			{
				http_response_code(400);
			
				log_message('error', 'parameter error');
				
				echo json_encode(array('errorCode' => '00', 'errorMessage' => ' parameter error'));
			}
		}
		else
		{
			http_response_code(400);
			
			log_message('error', 'header parameter error');
			
			echo json_encode(array('errorCode' => '00', 'errorMessage' => 'header parameter error'));
		}
	}

	
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//									매출 현황 - 이번달											   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function current_month_get()
	{
		if(check_header())
		{
			$result_array = array();
			$access_token_check = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?check_token($_SERVER['HTTP_NEW_ACCESS_TOKEN']):check_token($_SERVER['HTTP_ACCESS_TOKEN']);
			if($access_token_check['result'] == 0)
			{
				http_response_code(400);
				
				log_message('error', 'access token error');
				
				echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
				
				exit(0);
			}
			
			$access_token = $access_token_check['access_token'];
			header('Access-Token: ' . $access_token);
			
			$seller_id = (isset($_SERVER['HTTP_XID']))?$_SERVER['HTTP_XID']:get_seller_id($access_token);
			
			$menuCount = 0;
			
			$db_result = $this->seller_db_model->get_menu_sell_current_month($seller_id);
			if($db_result)
			{
				// TODO : PG사 수수료율 계산
				//$db_result['list'][0]['totalSellPrice'] = floor($db_result['list'][0]['totalSellPrice'] - (($db_result['list'][0]['commission'] / 100) * $db_result['list'][0]['totalSellPrice']) / 10 ) * 10;
				$result_array = $db_result[0];
			}
			
			$db_result = $this->seller_db_model->get_menu_sell_current_month_by_menu($seller_id);
			if($db_result)
			{
				$result_array['menu'] = $db_result['list'];
			}
			else
			{
				$result_array['menu'] = array();
			}
			
			echo json_encode($result_array);
		}
		else
		{
			http_response_code(400);
			
			log_message('error', 'header parameter error');
			
			echo json_encode(array('errorCode' => '00', 'errorMessage' => 'header parameter error'));
		}
	}




	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//									누적 매출 - 일별											   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function day_get()
	{
		if(check_header())
		{
			$current_page = ($this->uri->segment(4))?$this->uri->segment(4):1;
			if($current_page)
			{
				$result_array = array();
				$access_token_check = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?check_token($_SERVER['HTTP_NEW_ACCESS_TOKEN']):check_token($_SERVER['HTTP_ACCESS_TOKEN']);
				if($access_token_check['result'] == 0)
				{
					http_response_code(400);
					
					log_message('error', 'access token error');
					
					echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
					
					exit(0);
				}
				
				$access_token = $access_token_check['access_token'];
				header('Access-Token: ' . $access_token);
				
				$seller_id = (isset($_SERVER['HTTP_XID']))?$_SERVER['HTTP_XID']:get_seller_id($access_token);
				
				$total_price = 0;
				$limit = 16; // 한 화면에 보여지는 리스트 수
				$offset = ($current_page - 1) * $limit; // 각 페이지의 한 화면의 리스트의 첫번째 인덱스
				
				$db_result = $this->seller_db_model->get_sell_day($seller_id, $offset, $limit);
				if($db_result)
				{
					$result_array = $db_result;
					foreach($db_result as $key => $row)
					{
						
					}
				}
				
				echo json_encode($result_array);
			}
			else
			{
				http_response_code(400);
			
				log_message('error', 'parameter error');
				
				echo json_encode(array('errorCode' => '00', 'errorMessage' => 'parameter error'));
			}
		}
		else
		{
			http_response_code(400);
			
			log_message('error', 'header parameter error');
			
			echo json_encode(array('errorCode' => '00', 'errorMessage' => 'header parameter error'));
		}
	}
	
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//									누적 매출 - 월별											   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function month_get()
	{
		if(check_header())
		{
			$current_page = ($this->uri->segment(4))?$this->uri->segment(4):1;
			if($current_page)
			{
				$result_array = array();
				$access_token_check = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?check_token($_SERVER['HTTP_NEW_ACCESS_TOKEN']):check_token($_SERVER['HTTP_ACCESS_TOKEN']);
				if($access_token_check['result'] == 0)
				{
					http_response_code(400);
					
					log_message('error', 'access token error');
					
					echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
					
					exit(0);
				}
				
				$access_token = $access_token_check['access_token'];
				header('Access-Token: ' . $access_token);
				
				$seller_id = (isset($_SERVER['HTTP_XID']))?$_SERVER['HTTP_XID']:get_seller_id($access_token);
				
				$total_price = 0;
				$limit = 10; // 한 화면에 보여지는 리스트 수
				$offset = ($current_page - 1) * $limit; // 각 페이지의 한 화면의 리스트의 첫번째 인덱스
				
				$db_result = $this->seller_db_model->get_sell_month($seller_id, $offset, $limit);
				if($db_result)
				{
					$result_array = $db_result;
					foreach($db_result as $key => $row)
					{
						
					}
				}
				
				echo json_encode($result_array);
			}
			else
			{
				http_response_code(400);
			
				log_message('error', 'parameter error');
				
				echo json_encode(array('errorCode' => '00', 'errorMessage' => 'parameter error'));
			}
		}
		else
		{
			http_response_code(400);
			
			log_message('error', 'header parameter error');
			
			echo json_encode(array('errorCode' => '00', 'errorMessage' => 'header parameter error'));
		}
	}



	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//									누적 매출 - 전체											   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function all_get()
	{
		if(check_header())
		{
			$result_array = array();
			$access_token_check = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?check_token($_SERVER['HTTP_NEW_ACCESS_TOKEN']):check_token($_SERVER['HTTP_ACCESS_TOKEN']);
			if($access_token_check['result'] == 0)
			{
				http_response_code(400);
				
				log_message('error', 'access token error');
				
				echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
				
				exit(0);
			}
			
			$access_token = $access_token_check['access_token'];
			header('Access-Token: ' . $access_token);
			
			$seller_id = (isset($_SERVER['HTTP_XID']))?$_SERVER['HTTP_XID']:get_seller_id($access_token);
			
			$db_result = $this->seller_db_model->get_sell_all($seller_id);
			if($db_result)
			{
				$result_array = $db_result[0];
				foreach($db_result as $key => $row)
				{
					
				}
			}
			
			echo json_encode($result_array);
		}
		else
		{
			http_response_code(400);
			
			log_message('error', 'header parameter error');
			
			echo json_encode(array('errorCode' => '00', 'errorMessage' => 'header parameter error'));
		}
	}




	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//											정산내역											   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function account_get()
	{
		if(check_header())
		{
			$current_page = ($this->uri->segment(4))?$this->uri->segment(4):1;
			if($current_page)
			{
				$result_array = array();
				$access_token_check = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?check_token($_SERVER['HTTP_NEW_ACCESS_TOKEN']):check_token($_SERVER['HTTP_ACCESS_TOKEN']);
				if($access_token_check['result'] == 0)
				{
					http_response_code(400);
					
					log_message('error', 'access token error');
					
					echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
					
					exit(0);
				}
				
				$access_token = $access_token_check['access_token'];
				header('Access-Token: ' . $access_token);
				
				$seller_id = (isset($_SERVER['HTTP_XID']))?$_SERVER['HTTP_XID']:get_seller_id($access_token);
				
				$total_price = 0;
				$limit = 10; // 한 화면에 보여지는 리스트 수
				$offset = ($current_page - 1) * $limit; // 각 페이지의 한 화면의 리스트의 첫번째 인덱스
				
				$db_result = $this->seller_db_model->get_sell_account($seller_id, $offset, $limit);
				if($db_result)
				{
					$result_array = $db_result;
					foreach($db_result as $key => $row)
					{
					}
				}
				
				echo json_encode($result_array);
			}
			else
			{
				http_response_code(400);
			
				log_message('error', 'parameter error');
				
				echo json_encode(array('errorCode' => '00', 'errorMessage' => 'parameter error'));
			}
		}
		else
		{
			http_response_code(400);
			
			log_message('error', 'header parameter error');
			
			echo json_encode(array('errorCode' => '00', 'errorMessage' => 'header parameter error'));
		}
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */