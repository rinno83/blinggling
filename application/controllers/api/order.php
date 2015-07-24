<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';



class Order extends REST_Controller {

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
		//phpinfo();
		//var_dump(make_token(42));
		//var_dump(get_xid('g6N4aWSiNDKmZXhwaXJlqjE0MzcwMzk3OTikZGF0YYKrc2VydmljZV9rZXnZKDEwOTNhOWJkMTlmYmIwNGMyOTI1YmM4NzdhZjg0YjljNzdmOGVjNzandmVyc2lvbqEx='));
		echo shell_exec("sudo mkdir test");
		$output = 'test';
		if(!file_exists($output)){
		    if (!mkdir($output, 0777, true)) {//0777
		        die('Failed to create folders...');
		    }
		
		}
	} 
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//										바로 예약 화면											   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function view_get()
	{
		set_req_log('/order/view', $_SERVER['HTTP_CLIENT_TYPE'], json_encode($this->get()));
		if(check_header())
		{
			$menu_id = $this->uri->segment(4);
			if($menu_id)
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
						$result_array = array();
						
						// get order(delivery, store) info
						$db_result_info = $this->service_db_model->get_service_order_info($this->config->item('service_key'));
						if($db_result_info)
						{
							$result_array['info'] = array(
								'delivery' => (isset($db_result_info[0]['deliveryInfo']))?$db_result_info[0]['deliveryInfo']:'',
								'store' => (isset($db_result_info[0]['storeInfo']))?$db_result_info[0]['storeInfo']:''
							);
							
							$result_array['deliveryFee'] = (isset($db_result_info[0]['deliveryFee']))?$db_result_info[0]['deliveryFee']:'';
						}
						else
						{
							$result_array['info'] = array();
						}
						
						// get member piont
						$db_result_member_point = $this->member_db_model->get_member_point($xid);
						if($db_result_member_point)
						{
							$result_array['point'] = $db_result_member_point[0]['point'];
						}
						else
						{
							$result_array['point'] = 0;
						}
						
						// get receive type
						$db_result_receive_type = $this->menu_db_model->get_receive_type();
						if($db_result_receive_type)
						{
							$result_array['receiveType'] = $db_result_receive_type['list'];
						}
						else
						{
							$result_array['receiveType'] = array();
						}
						
						// get menu list (include topping)
						$db_result_menu_list = $this->menu_db_model->get_menu_list_for_order($menu_id);
						if($db_result_menu_list)
						{
							$result_array['menuList'] = $db_result_menu_list['list'];
						}
						else
						{
							$result_array['menuList'] = array();
						}


						// get delivery menu list (include topping)
						$db_result_delivery_menu_list = $this->menu_db_model->get_delivery_menu_list_for_order();
						if($db_result_delivery_menu_list)
						{
							$result_array['deliveryMenuList'] = $db_result_delivery_menu_list['list'];
						}
						else
						{
							$result_array['deliveryMenuList'] = array();
						}
						
						// get main menu
						$db_result_menu = $this->menu_db_model->get_menu($xid, $menu_id);
						if($db_result_menu)
						{
							$result_array['menu'] = $db_result_menu[0];
							$result_array['menu']['menuId'] = $menu_id;
							
							// get menu images
							$db_result_image = $this->menu_db_model->get_menu_image_list($menu_id);
							if($db_result_image)
							{
								$image_array = array();
								foreach($db_result_image['list'] as $key2 => $row2)
								{
									 array_push($image_array, $row2['menu_image_url']);
								}
								
								$result_array['menu']['menuImageUrl'] = $image_array;
							}
							else
							{
								$result_array['menu']['menuImageUrl'] = array();
							}
							
							// get menu service
							$db_result_service_menu = $this->menu_db_model->get_service_menu($menu_id);
							if($db_result_service_menu)
							{
								$result_array['menu']['menuService'] = $db_result_service_menu;
							}
							else
							{
								$result_array['menu']['menuService'] = array();
							}
		
/*
							// get menu component
							$db_result_menu_component = $this->menu_db_model->get_menu_component($menu_id);
							if($db_result_menu_component)
							{
								$result_array['menu']['menuComponent'] = $db_result_menu_component;
							}
							else
							{
								$result_array['menu']['menuComponent'] = array();
							}
*/
						}
						else
						{
							$result_array['menu'] = array();
						}
															
						echo json_encode($result_array);
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
	//										식당 목록												   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function store_get()
	{
		set_req_log('/order/store', $_SERVER['HTTP_CLIENT_TYPE'], json_encode($this->get()));
		if(check_header())
		{
			if($this->get('menuIds') && (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']) || isset($_SERVER['HTTP_ACCESS_TOKEN'])))
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
					$menu_array = explode(',', $this->get('menuIds'));
					$result_array = array();
					$store_array = array();
					
					foreach($menu_array as $key => $row)
					{
						// get store list
						$db_result = $this->menu_db_model->get_store_list_by_menu($row);
						$count = 0;
						foreach($db_result['list'] as $key2 => $row2)
						{
							//var_dump($row2);
							if($key == 0)
							{
								array_push($store_array, $row2);
							}
							else
							{
								if(!in_array($row2, $store_array)) // store_array에 $row2가 없으면
								{
									$count++;
								}
							}
						}
						if(count($db_result['list']) == $count)
						{
							$store_array = array();
						}	
					}
													
					echo json_encode($store_array);
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
			
			log_message('error', 'header parameter error');
			
			echo json_encode(array('errorCode' => '00', 'errorMessage' => 'header parameter error'));
		}
	}
	
	function remove_array_item( $array, $item ) {
		$index = array_search($item, $array);
		if ( $index !== false ) {
			unset( $array[$index] );
		}
	
		return $array;
	}
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//										결제 하기												   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function index_post()
	{
		set_req_log('/order', $_SERVER['HTTP_CLIENT_TYPE'], json_encode($this->post()));
		if(check_header())
		{
			if($this->post())
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
						$result_array = array();

						$order_json = json_decode($this->post('data'), true);
						
						
						$order_datetime = $order_json['orderDateTime'];
						$customer_phone = $order_json['customerPhone'];
						$customer_address = $order_json['customerAddress'];
						$customer_email = $order_json['customerEmail'];
						$receive_type = $order_json['receiveType'];
						$total_price = $order_json['totalPrice'];
						$total_point = $order_json['totalPoint'];
						$memo = $order_json['memo'];
						
						$order_menu = $order_json['menu'];
						
						$main_menu_id = 0;
						$order_seller_id = 0;
						
						
						log_message('info', json_encode($order_menu));
						
						
						// check menu price
/*
						$total_order_price = 0;
						$total_order_point = 0;
						$total_order_point_calc = 0;
						
						foreach($order_menu as $key => $row)
						{
							$db_result_menu_calc = $this->menu_db_model->calc_order_menu_price($row['menuId'], $row['menuCount']);
						}
*/
								
						
						date_default_timezone_set('Asia/Seoul');
						$current_datetime = time();
						log_message('info', 'current_datetime :: ' . $current_datetime);
						log_message('info', 'datetime diff :: ' . (string)($order_datetime - $current_datetime));
						// 당일 주문은 최소 1시간(3600) 이전, 1주일(604800) 전까지 예약 주문 가능
						if(($order_datetime - $current_datetime > 3600) && ($order_datetime - $current_datetime < 604800))
						{
							log_message('info', 'order date time OK');
							foreach($order_menu as $key => $row)
							{
								if($row['isRepresent'] == 1)
								{
									$main_menu_id = $row['menuId'];
								}
							}
							
							if($main_menu_id == 0)
							{
								http_response_code(400);
			
								log_message('error', 'parameter error => not exists main menu');
								
								echo json_encode(array('errorCode' => '00', 'errorMessage' => 'parameter error => not exists main menu'));
								
								exit(0);
							}
							
/*
							// get seller
							$db_result_seller = $this->seller_db_model->get_seller_by_seller_ratio();
							$seller_id = $db_result_seller[0]['seller_id'];
							log_message('info', 'seller_id ::'.$seller_id);
							
							// get seller_ratio by menu_seller
							$db_result_seller_ratio_by_menu = $this->seller_db_model->get_seller_ratio_by_menu($main_menu_id);
							$menu_seller_ratio = $db_result_seller_ratio_by_menu;
							log_message('info', 'menu_seller_ratio ::'.json_encode($menu_seller_ratio));
							
							$is_perfect = false;
							$perfect_seller_id = 0;
							foreach($menu_seller_ratio as $key => $row)
							{
								if($row['ratio'] == 100)
								{
									$is_perfect = true;
									$perfect_seller_id = $row['seller_id'];
								}
							}
							
							log_message('info', 'is_perfect ::'.$is_perfect);
							
							if($is_perfect) // 메뉴 판매자 비율에 100%가 있으면
							{
								log_message('info', '$row["seller_id"] ::'.$row['seller_id']);
								if($seller_id == $perfect_seller_id)
								{
									log_message('info', 'is_perfect true & $seller_id choice');
									$order_seller_id = $seller_id;
								}
								else
								{
									log_message('info', 'is_perfect true & $seller_id choice');
									$db_result_seller_swap = $this->seller_db_model->set_seller_swap($seller_id, $perfect_seller_id);
									$order_seller_id = $row['seller_id'];
								}
							}
							else // 메뉴 판매자 비율에 100%가 없으면
							{
								// 주문 별 메뉴 비율
								$db_result_menu_ratio = $this->seller_db_model->get_menu_ratio_by_order($main_menu_id);
								$menu_ratio_by_order = $db_result_menu_ratio;
								log_message('info', 'menu_ratio_by_order ::'.json_encode($menu_ratio_by_order));
								$sellable_seller = array();
								
								foreach($menu_seller_ratio as $key => $row) // 지정된 메뉴 배정 비율
								{
									foreach($menu_ratio_by_order as $key2 => $row2) // 실제 주문 메뉴 배정 비율
									{
										if($row['seller_id'] == $row2['seller_id'])
										{
											// 메뉴 비율 비교
											if($row['ratio'] > $row2['ratio'])
											{
												array_push($sellable_seller, $row['seller_id']);
											}
										}
									}
								}
								
								log_message('info', 'sellable_seller ::'.json_encode($sellable_seller));
								
								if(in_array($seller_id, $sellable_seller)) // 가능한 상점이 있고, 처음 뽑은 seller_id가 있다면
								{
									log_message('info', '가능한 상점이 있고, 처음 뽑은 seller_id가 있다면');
									$order_seller_id = $seller_id;
								}
								else if($sellable_seller && !in_array($seller_id, $sellable_seller)) // 가능한 상점이 있고, 처음 뽑은 seller_id가 없다면
								{
									log_message('info', '가능한 상점이 있고, 처음 뽑은 seller_id가 없다면 가능한 상점중에 랜덤으로 하나 선택');
									// 가능한 상점중에 랜덤으로 하나 선택
									$order_seller_id = $sellable_seller[array_rand($sellable_seller, 1)];
									
									// 처음 뽑은 seller_id 와 가능한 상점중에 랜덤으로 뽑은 상점을 스왑
									$db_result_seller_swap = $this->seller_db_model->set_seller_swap($seller_id, $order_seller_id);
								}
								else // 해당 메뉴에 가능한 판매자가 없는 경우
								{
									log_message('info', '해당 메뉴에 가능한 판매자가 없는 경우'); 
									// 큐 추가
									$db_result_queue = $this->seller_db_model->set_seller_queue();
									$order_seller_id = $seller_id;
								}
							}
							
							log_message('info', 'order_seller_id ::'.$order_seller_id);
							// set seller ratio status
							$db_result_seller_ratio_status = $this->seller_db_model->set_seller_ratio_status($order_seller_id);
							
							// get seller info
							$db_result_seller_info = $this->seller_db_model->get_seller_info($order_seller_id);
							
							$result_array['receiveStore'] = $db_result_seller_info[0]['sellerName'];
							
*/
							// set order
							$db_result_order = $this->menu_db_model->set_order($xid, $order_seller_id, $order_datetime, $customer_address, $customer_phone, $receive_type, $total_price, $total_point, $memo);
							if($db_result_order)
							{
								$order_result_count = 0;
								$order_id = $db_result_order['order_id'];
								log_message('info', 'order_id :: ' . $order_id);
								
								// set order menu
								foreach($order_menu AS $row)
								{
									log_message('info', 'order_menu row :: '.json_encode($row));
									$db_result_order_menu = $this->menu_db_model->set_order_menu($order_id, $row['menuId'], $row['menuType'], $row['menuCount'], isset($row['addPerson'])?$row['addPerson']:0, $row['usePoint'], $row['isRepresent']);
									
									if($db_result_order_menu == -1)
									{
										http_response_code(500);
							
										log_message('error', 'set order menu db error');
									}
									else
									{
										$order_result_count++;
									}
								}
								
								if($order_result_count == (count($order_menu)))
								{
									// set member point
									//$db_result_point = $this->member_db_model->set_member_point($xid, 'buy', 50);
									
									
									// set oder store
									if($receive_type == '3')
									{
										$store_id = $order_json['storeId'];
										log_message('info', '$store_id :: ' . $store_id);
										$db_result_order_store = $this->menu_db_model->set_order_store($order_id, $store_id);
									}
/*
									// send push at seller
									// get member device info
									$db_result_seller_device = $this->seller_db_model->get_seller_device($order_seller_id);
									
									$messageData = array(
										'token' => $db_result_seller_device[0]['push_token'],
										'alert_message' => '새로운 주문이 접수되었습니다.',
										'badge_count' => 1,
										'payload' => array(
											'type' => 'S01'
										)
									);
									
									$queue_name = $this->config->item('apns_queue_name');
									if( strtoupper($db_result_seller_device[0]['device']) == 'ANDROID' )
									{
										$queue_name = $this->config->item('gcm_queue_name');
									}
									
									send_queue($this->config->item('mq_host'), $this->config->item('mq_port'), $this->config->item('mq_user'), $this->config->item('mq_pass'), $messageData, $queue_name);
*/
									
									$db_result_order_get = $this->menu_db_model->get_order($order_id);

									if($db_result_order_get)
									{	
										//$result_array['orderCode'] = $db_result_order_get[0]['orderCode'];
										//$result_array['registDate'] = $db_result_order_get[0]['registDate'];
										
										// set order link
										$url = $this->config->item('domain').'/api/order/info/';
										$db_result_ordr_link = $this->menu_db_model->set_order_link($db_result_order_get[0]['orderCode'], $url);
										
										// pay link
										//$pay_url = $this->config->item('domain').'/pay/confirm/'.$db_result_order_get[0]['orderCode'];
										$pay_url = $this->config->item('domain').'/smart_xpay/payreq_crossplatform2.php?order_code='.$db_result_order_get[0]['orderCode'];
										$result_array['payLink'] = $pay_url;
										echo json_encode($result_array);
										
										//$this->load->view('pay_view', $result_array);
									}
									else
									{
										http_response_code(500);
						
										log_message('error', 'get order db error');
									}
								}
							}
							else
							{
								http_response_code(500);
						
								log_message('error', 'set order db error');
							}
						}
						else
						{
							http_response_code(400);
					
							log_message('error', 'invalid parameter error :: order_datetime invalid');
							
							echo json_encode(array('errorCode' => '03', 'errorMessage' => 'invalid parameter error'));
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
	
	
	function send($url,$shorten = true) {
		// Create cURL
		$ch = curl_init();
		// If we're shortening a URL...
		if($shorten) {
			curl_setopt($ch,CURLOPT_URL,'https://www.googleapis.com/urlshortener/v1/url?key=AIzaSyDu_ILcjvJ2k4zZnwr987tTjT1cX80xTXA');
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode(array("longUrl"=>$url)));
			curl_setopt($ch,CURLOPT_HTTPHEADER,array("Content-Type: application/json"));
		}
		else {
			curl_setopt($ch,CURLOPT_URL,$this->apiURL.'&shortUrl='.$url);
		}
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		// Execute the post
		$result = curl_exec($ch);
		// Close the connection
		curl_close($ch);
		// Return the result
		return json_decode($result,true);
	}	
	
	
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//										결제 완료												   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function complete_get()
	{
		if(check_header())
		{
			$order_id = $this->uri->segment(4);
			if($order_id && (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']) || isset($_SERVER['HTTP_ACCESS_TOKEN'])))
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
						$db_result = $this->menu_db_model->get_order($order_id);
						//var_dump($db_result);
						if($db_result)
						{
							$db_result_menu = $this->menu_db_model->get_order_menu($order_id, $xid);
								
							foreach($db_result_menu['list'] AS $key => $row)
							{
								$db_result_image = $this->menu_db_model->get_menu_image_list($row['menuId']);
								if($db_result_image)
								{
									$image_array = array();
									foreach($db_result_image['list'] as $key2 => $row2)
									{
										 array_push($image_array, $row2['menu_image_url']);
									}
									
									$db_result_menu['list'][$key]['menuImageUrl'] = $image_array;
								}
								else
								{
									$db_result_menu['list'][$key]['menuImageUrl'] = array();
								}
							}
							
							$db_result[0]['menu'] = $db_result_menu['list'];
							
							echo json_encode($db_result[0]);
						}
						else
						{
							http_response_code(500);
							
							log_message('error', 'get order error');
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



	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//										결제 취소												   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function cancel_post()
	{
		set_req_log('/order/cancel', $_SERVER['HTTP_CLIENT_TYPE'], json_encode($this->post()));
		if(check_header())
		{
			if((isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']) || isset($_SERVER['HTTP_ACCESS_TOKEN'])))
			{
				if((isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']) || isset($_SERVER['HTTP_ACCESS_TOKEN'])))
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
						$lgd_tid = $this->post('tid');
						$order_id = $this->post('orderId');
						
						if($lgd_tid) // card, bank
						{
							$db_result = $this->menu_db_model->is_order_by_tid($order_id, $xid, $lgd_tid);
							if($db_result)
							{
								$post_data = array(
									'CST_PLATFORM' => 'test',
									'CST_MID' => 'mediau',
									'LGD_TID' => $lgd_tid
								);
								
								$ch = curl_init(); 
								curl_setopt($ch, CURLOPT_URL, $this->config->item('domain').'/smart_xpay/Cancel.php'); 
								curl_setopt($ch, CURLOPT_POST, 1);
								curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); 
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
								$res = curl_exec($ch); 
								$info = curl_getinfo($ch);
								
								curl_close($ch);
								
								log_message('info', json_encode($res));
								log_message('info', json_encode($info));
								
								if($info['http_code'] != 200)
								{
									http_response_code(500);
					
									log_message('error', 'order cancel error');
								}
								else
								{
									$this->menu_db_model->set_order_status($order_id, 'cancel');
									
									// set member point
									if($db_result[0]['sell_status'] == 1)
									{
										$db_result_point = $this->member_db_model->set_member_point($xid, 'cancel', 50);
									}
									
									// refund use point
									if($db_result[0]['total_order_point'] > 0)
									{
										$db_result_point = $this->member_db_model->set_member_point($xid, 'refund', $db_result[0]['total_order_point']);
									}
									
									http_response_code(200);
								}
							}
							else
							{
								http_response_code(400);
				
								log_message('error', 'not exists order');
								
								echo json_encode(array('errorCode' => '00', 'errorMessage' => 'not exists order'));
							}
						}
						else // vbank
						{
							$this->menu_db_model->set_order_status($order_id, 'cancel');
							
							// get order
							$db_result = $this->menu_db_model->get_order($order_id);
							if($db_result)
							{
								// set member point
								if($db_result[0]['sell_status'] == 1)
								{
									$db_result_point = $this->member_db_model->set_member_point($xid, 'cancel', 50);
								}
								
								// refund use point
								if($db_result[0]['total_order_point'] > 0)
								{
									$db_result_point = $this->member_db_model->set_member_point($xid, 'refund', $db_result[0]['total_order_point']);
								}
							}
							
							http_response_code(200);
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
	
	
	
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//										예약 정보												   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function info_get()
	{
		set_req_log('/order/info', '', 'order_code - '.$this->uri->segment(4));
		$result_array = array();
		$order_code = $this->uri->segment(4);
		if($order_code)
		{
			$db_result = $this->menu_db_model->get_order_by_code($order_code);
			if($db_result)
			{
				$result_array = $db_result[0];
				
				if($result_array['location_image_url'])
				{
					$result_array['origin_location_image_url'] = $result_array['location_image_url'];
					
					$temp = explode('/', $result_array['location_image_url']);
					
					$temp[count($temp) - 1] = 'thumb_' . $temp[count($temp) - 1];
					
					$result_array['location_image_url'] = implode('/', $temp);
				}
				
				if($result_array['market_image_url'])
				{
					$result_array['origin_market_image_url'] = $result_array['market_image_url'];
					
					$temp = explode('/', $result_array['market_image_url']);
					
					$temp[count($temp) - 1] = 'thumb_' . $temp[count($temp) - 1];
					
					$result_array['market_image_url'] = implode('/', $temp);					
				}
				
				$db_result_menu = $this->menu_db_model->get_order_menu($db_result[0]['order_id'], 0);
				$result_array['menu'] = $db_result_menu['list'];
				
				foreach($db_result_menu['list'] as $key => $row)
				{
					// get menu image
					$db_result_image = $this->menu_db_model->get_menu_image_list($row['menuId']);
					if($db_result_image)
					{
						foreach($db_result_image['list'] as $key3 => $row3)
						{
							$temp = explode('/', $row3['menu_image_url']);
					
							$temp[count($temp) - 1] = 'thumb_' . $temp[count($temp) - 1];
							
							$result_array['menuImage'][$key3]['menuImageUrl'] = implode('/', $temp);
						}
					}
				}
			}
			
			$db_result_corp = $this->service_db_model->get_corp_info();
			$result_array['corp'] = $db_result_corp[0];
			
			$db_result_store = $this->menu_db_model->get_store_by_order_code($order_code);
			if($db_result_store)
			{
				$result_array['store_name'] = $db_result_store[0]['name'];
			}
			else
			{
				$result_array['store_name'] = '';
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


	
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//										구매 내역												   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function history_get()
	{
		set_req_log('/order/history', $_SERVER['HTTP_CLIENT_TYPE'], json_encode($this->get()));
		if(check_header())
		{
			if(isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']) || isset($_SERVER['HTTP_ACCESS_TOKEN']))
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

				$db_result = $this->menu_db_model->get_order_list($xid);
				if($db_result)
				{
					// set order menu
					foreach($db_result['list'] AS $key => $row)
					{
						$db_result_menu = $this->menu_db_model->get_order_menu($row['orderId'], $xid);
						if($db_result_menu)
						{
							foreach($db_result_menu['list'] AS $key2 => $row2)
							{
								$db_result_image = $this->menu_db_model->get_menu_image($row2['menuId']);
								if($db_result_image)
								{
									foreach($db_result_image['list'] as $key3 => $row3)
									{
										 $db_result_menu['list'][$key2]['menuImageUrl'] = $row3['menu_image_url'];
									}
								}
								else
								{
									$db_result_menu['list'][$key2]['menuImageUrl'] = '';
								}
							}
						}
						else
						{
							$db_result_menu['list'] = array();
						}
						
						
						$db_result['list'][$key]['menu'] = $db_result_menu['list'];
						$db_result['list'][$key]['point'] = '50';
					}
					
					$result_array = $db_result['list'];
				}
				
				echo json_encode($result_array);
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
			
			log_message('error', 'header parameter error');
			
			echo json_encode(array('errorCode' => '00', 'errorMessage' => 'header parameter error'));
		}
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */