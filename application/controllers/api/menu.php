<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';



class Menu extends REST_Controller {

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
	//										미친 메뉴 목록											   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function page_get()
	{
		set_req_log('/menu/page', $_SERVER['HTTP_CLIENT_TYPE'], $this->uri->segment(4));
		if(check_header())
		{
			$current_page = ($this->uri->segment(4))?$this->uri->segment(4):1;
			$prefer = ($this->uri->segment(5))?$this->uri->segment(5):'';
			if($current_page)
			{
				// 회원
				if(isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']) || isset($_SERVER['HTTP_ACCESS_TOKEN']))
				{
					//$access_token = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?$_SERVER['HTTP_NEW_ACCESS_TOKEN']:$_SERVER['HTTP_ACCESS_TOKEN'];
					$access_token_check = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?check_token($_SERVER['HTTP_NEW_ACCESS_TOKEN']):check_token($_SERVER['HTTP_ACCESS_TOKEN']);
					if($access_token_check['result'] == 0)
					{
						http_response_code(400);
						
						log_message('error', 'access token error');
						
						echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
						
						exit(0);
					}
					
					$access_token = $access_token_check['access_token'];
					$xid = (isset($_SERVER['HTTP_XID']))?$_SERVER['HTTP_XID']:get_xid($access_token);
					
					header('Access-Token: ' . $access_token);
					
					if($xid == 0)
					{
						http_response_code(400);
			
						log_message('error', 'access token error');
						
						echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
						
						exit(0);
					}
				}
				// 비회원
				else
				{
					$xid = 0;
				}
				
				set_xid_log($xid);
				
				$result_array = array();
				$limit = 10; // 한 화면에 보여지는 리스트 수
				$offset = ($current_page - 1) * $limit; // 각 페이지의 한 화면의 리스트의 첫번째 인덱스
				$type = 'menu';
				
				$db_result = $this->menu_db_model->get_menu_list($xid, $type, $prefer, $offset, $limit);
				if($db_result)
				{
					$result_array = $db_result['list'];
				}
				
				echo json_encode($result_array);
			}
			else
			{
				http_response_code(400);
			
				log_message('error', ' parameter error');
				
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
	//									미친 메뉴 선호회 목록											   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function prefer_get()
	{
		set_req_log('/menu/prefer', $_SERVER['HTTP_CLIENT_TYPE'], '');
		if(check_header())
		{
			// 회원
			if(isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']) || isset($_SERVER['HTTP_ACCESS_TOKEN']))
			{
				//$access_token = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?$_SERVER['HTTP_NEW_ACCESS_TOKEN']:$_SERVER['HTTP_ACCESS_TOKEN'];
				$access_token_check = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?check_token($_SERVER['HTTP_NEW_ACCESS_TOKEN']):check_token($_SERVER['HTTP_ACCESS_TOKEN']);
				if($access_token_check['result'] == 0)
				{
					http_response_code(400);
					
					log_message('error', 'access token error');
					
					echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
					
					exit(0);
				}
				
				$access_token = $access_token_check['access_token'];
				$xid = (isset($_SERVER['HTTP_XID']))?$_SERVER['HTTP_XID']:get_xid($access_token);
				
				header('Access-Token: ' . $access_token);
				
				if($xid == 0)
				{
					http_response_code(400);
		
					log_message('error', 'access token error');
					
					echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
					
					exit(0);
				}
			}
			// 비회원
			else
			{
				$xid = 0;
			}
			
			set_xid_log($xid);
			
			$result_array = array();
			$current_page = 1;
			$limit = 100; // 한 화면에 보여지는 리스트 수
			$offset = ($current_page - 1) * $limit; // 각 페이지의 한 화면의 리스트의 첫번째 인덱스
			
			$db_result = $this->menu_db_model->get_menu_prefer_list($xid, $offset, $limit);
			if($db_result)
			{
				$result_array = $db_result['list'];
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
	//									오늘의 Pick 목록											   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function pick_get()
	{
		set_req_log('/menu/pick', $_SERVER['HTTP_CLIENT_TYPE'], $this->uri->segment(4));
		if(check_header())
		{
			$current_page = $this->uri->segment(4);
			if($current_page)
			{
				// 회원
				if(isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']) || isset($_SERVER['HTTP_ACCESS_TOKEN']))
				{
					//$access_token = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?$_SERVER['HTTP_NEW_ACCESS_TOKEN']:$_SERVER['HTTP_ACCESS_TOKEN'];
					$access_token_check = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?check_token($_SERVER['HTTP_NEW_ACCESS_TOKEN']):check_token($_SERVER['HTTP_ACCESS_TOKEN']);
					if($access_token_check['result'] == 0)
					{
						http_response_code(400);
						
						log_message('error', 'access token error');
						
						echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
						
						exit(0);
					}
					
					$access_token = $access_token_check['access_token'];
					$xid = (isset($_SERVER['HTTP_XID']))?$_SERVER['HTTP_XID']:get_xid($access_token);
					
					header('Access-Token: ' . $access_token);
					
					if($xid == 0)
					{
						http_response_code(400);
			
						log_message('error', 'access token error');
						
						echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
						
						exit(0);
					}
				}
				// 비회원
				else
				{
					$xid = 0;
				}
				
				set_xid_log($xid);
				
				$result_array = array();
				$limit = 10; // 한 화면에 보여지는 리스트 수
				$offset = ($current_page - 1) * $limit; // 각 페이지의 한 화면의 리스트의 첫번째 인덱스
				$type = 'pick';
				
				$db_result = $this->menu_db_model->get_pick_list($xid, $type, $offset, $limit);
				if($db_result)
				{	
					$result_array = $db_result['list'];
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
		else
		{
			http_response_code(400);
			
			log_message('error', 'header parameter error');
			
			echo json_encode(array('errorCode' => '00', 'errorMessage' => 'header parameter error'));
		}
	}
	
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//											메뉴 상세											   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function detail_get()
	{
		set_req_log('/menu/detail', $_SERVER['HTTP_CLIENT_TYPE'], $this->uri->segment(4) . ', ' . $this->uri->segment(5));
		if(check_header())
		{
			$menu_id = $this->uri->segment(4);
			$add_view = ($this->uri->segment(5))?$this->uri->segment(5):0;
			if($menu_id)
			{
				// 회원
				if(isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']) || isset($_SERVER['HTTP_ACCESS_TOKEN']))
				{
					//$access_token = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?$_SERVER['HTTP_NEW_ACCESS_TOKEN']:$_SERVER['HTTP_ACCESS_TOKEN'];
					$access_token_check = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?check_token($_SERVER['HTTP_NEW_ACCESS_TOKEN']):check_token($_SERVER['HTTP_ACCESS_TOKEN']);
					if($access_token_check['result'] == 0)
					{
						http_response_code(400);
						
						log_message('error', 'access token error');
						
						echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
						
						exit(0);
					}
					
					$access_token = $access_token_check['access_token'];
					$xid = (isset($_SERVER['HTTP_XID']))?$_SERVER['HTTP_XID']:get_xid($access_token);
					
					header('Access-Token: ' . $access_token);
					
					if($xid == 0)
					{
						http_response_code(400);
			
						log_message('error', 'access token error');
						
						echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
						
						exit(0);
					}
				}
				// 비회원
				else
				{
					$xid = 0;
				}
				
				set_xid_log($xid);
				
				$result_array = array();
				
				$db_result = $this->menu_db_model->get_menu($xid, $menu_id);
				
				if($db_result)
				{
					if($add_view)
					{
						$this->menu_db_model->set_menu_view_count($menu_id);
					}					
					
					// get menu images
					$db_result_image = $this->menu_db_model->get_menu_image_list($menu_id);
					if($db_result_image)
					{
						$image_array = array();
						foreach($db_result_image['list'] as $key2 => $row2)
						{
							 array_push($image_array, $row2['menu_image_url']);
						}
						
						$db_result[0]['menuImageUrl'] = $image_array;
					}
					else
					{
						$db_result[0]['menuImageUrl'] = array();
					}
					
					// get menu service
					$db_result_service_menu = $this->menu_db_model->get_service_menu($menu_id);
					if($db_result_service_menu)
					{
						$db_result[0]['menuService'] = $db_result_service_menu;
					}
					else
					{
						$db_result[0]['menuService'] = array();
					}

					// get menu component
					$db_result_menu_component = $this->menu_db_model->get_menu_component($menu_id);
					if($db_result_menu_component)
					{
						$db_result[0]['menuComponent'] = $db_result_menu_component;
					}
					else
					{
						$db_result[0]['menuComponent'] = array();
					}
					
					$result_array = $db_result[0];
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
		else
		{
			http_response_code(400);
			
			log_message('error', 'header parameter error');
			
			echo json_encode(array('errorCode' => '00', 'errorMessage' => 'header parameter error'));
		}
	}
	
	
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//											메뉴 리뷰 목록										   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function comment_get()
	{
		set_req_log('/menu/comment', $_SERVER['HTTP_CLIENT_TYPE'], json_encode($this->get()));
		if(check_header())
		{
			if($this->get('menuId') && $this->get('currentPage'))
			{
				// 회원
				if(isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']) || isset($_SERVER['HTTP_ACCESS_TOKEN']))
				{
					//$access_token = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?$_SERVER['HTTP_NEW_ACCESS_TOKEN']:$_SERVER['HTTP_ACCESS_TOKEN'];
					$access_token_check = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?check_token($_SERVER['HTTP_NEW_ACCESS_TOKEN']):check_token($_SERVER['HTTP_ACCESS_TOKEN']);
					if($access_token_check['result'] == 0)
					{
						http_response_code(400);
						
						log_message('error', 'access token error');
						
						echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
						
						exit(0);
					}
					
					$access_token = $access_token_check['access_token'];
					$xid = (isset($_SERVER['HTTP_XID']))?$_SERVER['HTTP_XID']:get_xid($access_token);
					
					header('Access-Token: ' . $access_token);
					
					if($xid == 0)
					{
						http_response_code(400);
			
						log_message('error', 'access token error');
						
						echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
						
						exit(0);
					}
				}
				// 비회원
				else
				{
					$xid = 0;
				}
				
				set_xid_log($xid);
				
				$menu_id = $this->get('menuId');
				$current_page = $this->get('currentPage');
				$result_array = array();
				
				$limit = 20; // 한 화면에 보여지는 리스트 수
				$offset = ($current_page - 1) * $limit; // 각 페이지의 한 화면의 리스트의 첫번째 인덱스
				
				$db_result_is_order = $this->menu_db_model->is_order_by_menu_id($xid, $menu_id);
				if($db_result_is_order)
				{
					$result_array['isOrder'] = "1";
				}
				else
				{
					$result_array['isOrder'] = "0";
				}
				
				$db_result = $this->menu_db_model->get_menu_comment($menu_id, $offset, $limit);
				if($db_result)
				{
					foreach($db_result['list'] as $key => $row)
					{
						// 삭제 여부 판단
						if($row['xid'] == $xid)
						{
							$db_result['list'][$key]['isMine'] = "1";
						}
						else
						{
							$db_result['list'][$key]['isMine'] = "0";
						}
						
						$db_result['list'][$key]['phone'] = substr($row['phone'], -4, 2) . '**';
					}
					
					$result_array['comments'] = $db_result['list'];
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
	//											메뉴 검색											   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function search_get()
	{
		set_req_log('/menu/search', $_SERVER['HTTP_CLIENT_TYPE'], json_encode($this->get()));
		if(check_header())
		{
			if($this->get('person') && $this->get('price'))
			{
				// 회원
				if(isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']) || isset($_SERVER['HTTP_ACCESS_TOKEN']))
				{
					//$access_token = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?$_SERVER['HTTP_NEW_ACCESS_TOKEN']:$_SERVER['HTTP_ACCESS_TOKEN'];
					$access_token_check = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?check_token($_SERVER['HTTP_NEW_ACCESS_TOKEN']):check_token($_SERVER['HTTP_ACCESS_TOKEN']);
					if($access_token_check['result'] == 0)
					{
						http_response_code(400);
						
						log_message('error', 'access token error');
						
						echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
						
						exit(0);
					}
					
					$access_token = $access_token_check['access_token'];
					$xid = (isset($_SERVER['HTTP_XID']))?$_SERVER['HTTP_XID']:get_xid($access_token);
					
					header('Access-Token: ' . $access_token);
					
					if($xid == 0)
					{
						http_response_code(400);
			
						log_message('error', 'access token error');
						
						echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
						
						exit(0);
					}
				}
				// 비회원
				else
				{
					$xid = 0;
				}
				
				set_xid_log($xid);
				
				$current_page = ($this->get('currentPage'))?$this->get('currentPage'):1;
				$search_person = $this->get('person');
				$search_price = $this->get('price');
				$search_prefer_array = explode(',', $this->get('prefer'));
								
				$white = ($search_prefer_array[0])?'white':'';
				$red = ($search_prefer_array[1])?'red':'';
				$season = ($search_prefer_array[2])?'season':'';
				$wild = ($search_prefer_array[3])?'wild':'';
				$crab = ($search_prefer_array[4])?'crab':'';
				
				log_message('info', $white);
				log_message('info', $red);
				log_message('info', $season);
				log_message('info', $wild);
				log_message('info', $crab);
								
				$result_array = array();
				$limit = 10; // 한 화면에 보여지는 리스트 수
				$offset = ($current_page - 1) * $limit; // 각 페이지의 한 화면의 리스트의 첫번째 인덱스
				
				$db_result = $this->menu_db_model->get_menu_search($xid, $search_person, $search_price, $white, $red, $season, $wild, $crab, $offset, $limit);
				if($db_result)
				{					
					$result_array = $db_result['list'];
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
	//											메뉴 공유											   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function share_post()
	{
		set_req_log('/menu/share', $_SERVER['HTTP_CLIENT_TYPE'], json_encode($this->post()));
		if(check_header())
		{
			if($this->post('menuId') || $this->post('orderId'))
			{
				// 회원
				if(isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']) || isset($_SERVER['HTTP_ACCESS_TOKEN']))
				{
					//$access_token = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?$_SERVER['HTTP_NEW_ACCESS_TOKEN']:$_SERVER['HTTP_ACCESS_TOKEN'];
					$access_token_check = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?check_token($_SERVER['HTTP_NEW_ACCESS_TOKEN']):check_token($_SERVER['HTTP_ACCESS_TOKEN']);
					if($access_token_check['result'] == 0)
					{
						http_response_code(400);
						
						log_message('error', 'access token error');
						
						echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
						
						exit(0);
					}
					
					$access_token = $access_token_check['access_token'];
					$xid = (isset($_SERVER['HTTP_XID']))?$_SERVER['HTTP_XID']:get_xid($access_token);
					
					header('Access-Token: ' . $access_token);
					
					if($xid == 0)
					{
						http_response_code(400);
			
						log_message('error', 'access token error');
						
						echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
						
						exit(0);
					}
				}
				// 비회원
				else
				{
					$xid = 0;
				}
				
				set_xid_log($xid);
				
				$menu_id = ($this->post('menuId'))?$this->post('menuId'):0;
				$order_id = ($this->post('orderId'))?$this->post('orderId'):0;
				
				$db_result = $this->menu_db_model->set_menu_share($xid, $menu_id, $order_id);
				if($db_result)
				{
					if($db_result[0]['result'] == 'ok')
					{
						// set member point
						$db_result = $this->member_db_model->set_member_point($xid, 'share', 10);
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
	//										평가하기												   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function comment_post()
	{
		set_req_log('/menu/comment', $_SERVER['HTTP_CLIENT_TYPE'], json_encode($this->post()));
		if(check_header())
		{
			if($this->post('menuId') && $this->post('star'))
			{
				if((isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']) || isset($_SERVER['HTTP_ACCESS_TOKEN'])))
				{
					//$access_token = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?$_SERVER['HTTP_NEW_ACCESS_TOKEN']:$_SERVER['HTTP_ACCESS_TOKEN'];
					$access_token_check = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?check_token($_SERVER['HTTP_NEW_ACCESS_TOKEN']):check_token($_SERVER['HTTP_ACCESS_TOKEN']);
					if($access_token_check['result'] == 0)
					{
						http_response_code(400);
						
						log_message('error', 'access token error');
						
						echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
						
						exit(0);
					}
					
					$access_token = $access_token_check['access_token'];
					$xid = (isset($_SERVER['HTTP_XID']))?$_SERVER['HTTP_XID']:get_xid($access_token);
					
					set_xid_log($xid);
					
					header('Access-Token: ' . $access_token);
					
					if($xid == 0)
					{
						http_response_code(400);
			
						log_message('error', 'access token error');
						
						echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
						
						exit(0);
					}
					else
					{
						$comment_id = ($this->post('commentId'))?$this->post('commentId'):0;
						$menu_id = $this->post('menuId');
						$star = $this->post('star');
						$comment = ($this->post('comment'))?$this->post('comment'):'';
						
						if(mb_strlen($comment) > 140)
						{
							http_response_code(400);
			
							log_message('error', 'parameter error :: comment length too large');
							
							echo json_encode(array('errorCode' => '00', 'errorMessage' => 'parameter error :: comment length too large'));
							
							exit(0);
						}
						
						$db_result = $this->menu_db_model->set_menu_comment($comment_id, $menu_id, $xid, $star, $comment);
						if($db_result)
						{
							if($db_result[0]['result'] == '01')
							{
								http_response_code(400);
						
								log_message('error', 'duplication error');
								
								echo json_encode(array('errorCode' => '01', 'errorMessage' => ' duplication error'));
							}
							else
							{
								http_response_code(400);
					
								log_message('error', 'not exists error :: order');
								
								echo json_encode(array('errorCode' => '02', 'errorMessage' => 'not exists error'));
							}					
						}
						else
						{
							if($comment_id == 0)
							{
								// set member point
								$db_result = $this->member_db_model->set_member_point($xid, 'review', 25);
							}					
						}
					}
				}
				else
				{
					http_response_code(400);
			
					log_message('error', 'access token error');
					
					echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
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
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */