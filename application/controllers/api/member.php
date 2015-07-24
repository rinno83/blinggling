<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Member extends REST_Controller {

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
	//									Access-Token 체크											   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function token_check_get()
	{
		set_req_log('/member/token_check', $_SERVER['HTTP_CLIENT_TYPE'], json_encode($this->get()));
		$access_token_check = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?check_token($_SERVER['HTTP_NEW_ACCESS_TOKEN']):check_token($_SERVER['HTTP_ACCESS_TOKEN']);
		if($access_token_check['result'] == 0)
		{
			http_response_code(400);
			
			set_err_log('Access Token Error');
			
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

			set_err_log('Access Token Error');
			
			echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
			
			exit(0);
		}
	}



	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//										로그인												   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function login_post()
	{
		set_req_log('/member/login', $_SERVER['HTTP_CLIENT_TYPE'], json_encode($this->post()));
		// parameter check
		if($this->post('email') && $this->post('password'))
		{
			$email = $this->post('email');
			$password = $this->post('password');
			
			// insert member db
			$db_result = $this->member_db_model->login($email, $password);
		
			if($db_result)
			{
				if($db_result[0]['result'] == 3) // wrong password
				{
					http_response_code(400);
				
					set_err_log('Invalid Password');
					
					echo json_encode(array('errorCode' => '03', 'errorMessage' => 'invalid password'));
				}
				else if($db_result[0]['result'] == 2) // not exists email
				{
					http_response_code(400);
			
					set_err_log('Not Exists Email');
					
					echo json_encode(array('errorCode' => '02', 'errorMessage' => 'not exists email'));
				}
				else
				{
					$xid = $db_result[0]['xid'];
					set_xid_log($xid);
					
					$access_token = make_token($xid);
					
					log_message('info', 'access token :: ' . $access_token);
					log_message('info', 'access token data :: ' . json_encode(msgpack_unpack(base64_decode($access_token))));
					
					// insert member db
					$uuid = ($this->post('uuid'))?$this->post('uuid'):'';
					$push_token = ($this->post('pushToken'))?$this->post('pushToken'):'';
					$device = $_SERVER['HTTP_CLIENT_TYPE'];
					$db_result = $this->member_db_model->set_member_device($xid, $uuid, $device, $push_token);
				
					if($db_result != -1)
					{
						set_res_log($access_token);
						header('Access-Token: ' . $access_token);
					}
					else
					{
						http_response_code(500);
						
						set_err_log('Set Member Device DB Error - Modify');
					}
				}
			}
			else
			{
				http_response_code(500);
				
				set_err_log('Login DB Error');
			}
		}
		else
		{
			http_response_code(400);
			
			if(!array_key_exists('email', $this->post()))
			{
				set_err_log('Parameter Empty - email');
			}
			
			if(!array_key_exists('password', $this->post()))
			{
				set_err_log('Parameter Empty - password');
			}
			
			echo json_encode(array('errorCode' => '00', 'errorMessage' => 'parameter error'));
		}
	}
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//										계정 정보 등록											   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function account_post()
	{
		set_req_log('/member/account', $_SERVER['HTTP_CLIENT_TYPE'], json_encode($this->post()));
		// parameter check
		if($this->post('email') && $this->post('password') && $this->post('phone') && $this->post('gender') && $this->post('birthday'))
		{
			$email = $this->post('email');
			$password = $this->post('password');
			$phone = $this->post('phone');
			$gender = $this->post('gender');
			$birthday = $this->post('birthday');
			$name = ($this->post('name'))?$this->post('name'):'';
			
			if(!preg_match('/^[0-9A-Za-z]{6,20}$/', $password))
			{
				http_response_code(400);
			
				set_err_log('Invalid Password');
				
				echo json_encode(array('errorCode' => '00', 'errorMessage' => 'password invalid'));
				
				exit(0);
			}
			
			// insert member db
			$db_result = $this->member_db_model->set_member_account($email, $password, $name, $phone, $gender, $birthday);
		
			if($db_result)
			{
				if($db_result[0]['result'] == '01')
				{
					http_response_code(400);
		
					set_err_log('Email Duplication');
					
					echo json_encode(array('errorCode' => '01', 'errorMessage' => 'email duplication'));
				}
				else if($db_result[0]['result'] == '05')
				{
					http_response_code(400);
	
					set_err_log('Phone Number Exists');
					
					echo json_encode(array('errorCode' => '05', 'errorMessage' => 'phone number exists'));
				}
				else
				{
					$xid = $db_result[0]['xid'];
					set_xid_log($xid);
					
					$access_token = make_token($xid);
					
					log_message('info', 'access token :: ' . $access_token);
					log_message('info', 'access token data :: ' . json_encode(msgpack_unpack(base64_decode($access_token))));
					
					// set member point
					$db_result = $this->member_db_model->set_member_point($xid, 'join', 100);
					
					// insert member db
					$uuid = ($this->post('uuid'))?$this->post('uuid'):'';
					$push_token = ($this->post('pushToken'))?$this->post('pushToken'):'';
					$device = $_SERVER['HTTP_CLIENT_TYPE'];
					$db_result = $this->member_db_model->set_member_device($xid, $uuid, $device, $push_token);
				
					if($db_result != -1)
					{
						// send push
						notification($xid, 'member', 1, '회원가입에 감사드립니다.', array('type'=>'html','content'=>'회원가입 감사 포인트 100P 적립되었습니다.'));
						
						// set alarm
						$this->member_db_model->set_member_alarm($xid, 'html', '회원가입에 감사드립니다.', '<p>회원가입 감사 포인트 100P 적립되었습니다.</p>');
						
						set_res_log($access_token);
						header('Access-Token: ' . $access_token);
					}
					else
					{
						http_response_code(500);
						
						set_err_log('Set Member Device DB Error - Modify');
					}
				}
			}
			else
			{
				http_response_code(500);
				
				set_err_log('Set Account DB Error');
			}
		}
		else
		{
			http_response_code(400);
			
			if(!array_key_exists('email', $this->post()))
			{
				set_err_log('Parameter Empty - email');
			}
			
			if(!array_key_exists('password', $this->post()))
			{
				set_err_log('Parameter Empty - password');
			}
			
			if(!array_key_exists('phone', $this->post()))
			{
				set_err_log('Parameter Empty - phone');
			}
			
			if(!array_key_exists('gender', $this->post()))
			{
				set_err_log('Parameter Empty - gender');
			}
			
			if(!array_key_exists('birthday', $this->post()))
			{
				set_err_log('Parameter Empty - birthday');
			}
			
			echo json_encode(array('errorCode' => '00', 'errorMessage' => 'parameter error'));
		}
	}
	
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//										비밀번호 재설정											   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function password_post()
	{
		set_req_log('/member/password', $_SERVER['HTTP_CLIENT_TYPE'], json_encode($this->post()));
		// parameter check			
		if($this->post('newPassword') && $this->post('newPasswordConfirm'))
		{
			$newPassword = $this->post('newPassword');
			$newPasswordConfirm = $this->post('newPasswordConfirm');
			
			if($newPassword == $newPasswordConfirm)
			{
				if($this->post('email')) // 로그인 전 -> 비밀번호 찾기로 진입 시
				{
					$email = $this->post('email');
					// update member password
					$db_result = $this->member_db_model->set_member_password(0, $email, $newPassword);
				
					if($db_result == -1)
					{						
						http_response_code(500);
						
						set_err_log('Set Member Password DB Error - Find Password');
					}
					else
					{
						set_res_log('OK');
					}
				}
				else // 설정 -> 비밀번호 변경으로 진입 시
				{
					if(isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']) || isset($_SERVER['HTTP_ACCESS_TOKEN']))
					{
						$access_token_check = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?check_token($_SERVER['HTTP_NEW_ACCESS_TOKEN']):check_token($_SERVER['HTTP_ACCESS_TOKEN']);
						if($access_token_check['result'] == 0)
						{
							http_response_code(400);
							
							set_err_log('Access Token Error');
							
							echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
							
							exit(0);
						}
						
						$access_token = $access_token_check['access_token'];
						
						if($access_token)
						{
							// check access token
							$xid = (isset($_SERVER['HTTP_XID']))?$_SERVER['HTTP_XID']:get_xid($access_token);
							set_xid_log($xid);
							if($xid)
							{							
								// update member password
								$db_result = $this->member_db_model->set_member_password($xid, '', $newPassword);
							
								if($db_result != -1)
								{		
									set_res_log($access_token);
													
									header('Access-Token: ' . $access_token);
								}
								else
								{
									http_response_code(500);
									
									set_err_log('Set Member Password DB Error - Change Password');
								}
							}
							else
							{
								http_response_code(400);
							
								set_err_log('Access Token Error');
								
								echo json_encode(array('errorCode' => '04', 'errorMessage' => 'invalid accessToken'));
							}
						}
						else 
						{
							http_response_code(400);
							
							set_err_log('Access Token Error');
							
							echo json_encode(array('errorCode' => '04', 'errorMessage' => 'invalid accessToken'));
						}
					}
					else
					{
						http_response_code(400);
						
						set_err_log('Access Token Error');
						
						echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
					}
				}
			}
			else
			{
				http_response_code(400);
				
				set_err_log('Different Password');
				
				echo json_encode(array('errorCode' => '00', 'errorMessage' => 'parameter error :: different password'));
			}
		}
		else
		{
			http_response_code(400);
			
			if(!array_key_exists('newPassword', $this->post()))
			{
				set_err_log('Parameter Empty - newPassword');
			}

			if(!array_key_exists('newPasswordConfirm', $this->post()))
			{
				set_err_log('Parameter Empty - newPasswordConfirm');
			}
			
			echo json_encode(array('errorCode' => '00', 'errorMessage' => 'parameter error'));
		}
	}
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//									디바이스 정보 설정											   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function device_post()
	{
		set_req_log('/member/device', $_SERVER['HTTP_CLIENT_TYPE'], json_encode($this->post()));
		if($this->post('uuid') && $this->post('pushToken'))
		{
			// 디바이스 수정
			if(isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']) || isset($_SERVER['HTTP_ACCESS_TOKEN']))
			{
				//$access_token = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?$_SERVER['HTTP_NEW_ACCESS_TOKEN']:$_SERVER['HTTP_ACCESS_TOKEN'];
				$access_token_check = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?check_token($_SERVER['HTTP_NEW_ACCESS_TOKEN']):check_token($_SERVER['HTTP_ACCESS_TOKEN']);
				if($access_token_check['result'] == 0)
				{
					http_response_code(400);
					
					set_err_log('Access Token Error');
					
					echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
					
					exit(0);
				}
				
				$access_token = $access_token_check['access_token'];
				
				$device = strtoupper($_SERVER['HTTP_CLIENT_TYPE']);
				
				$uuid = $this->post('uuid');
				$push_token = $this->post('pushToken');
				
				$xid = (isset($_SERVER['HTTP_XID']))?$_SERVER['HTTP_XID']:get_xid($access_token);
				set_xid_log($xid);
				if($xid)
				{
					// insert member db
					$db_result = $this->member_db_model->set_member_device($xid, $uuid, $device, $push_token);
				
					if($db_result != -1)
					{
						set_res_log($access_token);
						header('Access-Token: ' . $access_token);
					}
					else
					{
						http_response_code(500);
						
						set_err_log('Set Member Device DB Error - Modify');
					}
				}
				else
				{
					http_response_code(400);
				
					set_err_log('Access Token Error');
					
					echo json_encode(array('errorCode' => '04', 'errorMessage' => 'invalid accessToken'));
				}
			}
			// 새로가입
			else
			{
				$device = strtoupper($_SERVER['HTTP_CLIENT_TYPE']);
				
				$uuid = $this->post('uuid');
				$push_token = $this->post('pushToken');
				
				// insert member db
				$db_result = $this->member_db_model->set_member_device(0, $uuid, $device, $push_token);
			
				if($db_result == -1)
				{
					http_response_code(500);
					
					set_err_log('Set Member Device DB Error - Insert');
				}
				else
				{
					set_res_log('OK');
				}
			}
		}
		else
		{
			http_response_code(400);
				
			if(!array_key_exists('uuid', $this->post()))
			{
				set_err_log('Parameter Empty - uuid');
			}

			if(!array_key_exists('pushToken', $this->post()))
			{
				set_err_log('Parameter Empty - pushToken');
			}
			
			echo json_encode(array('errorCode' => '00', 'errorMessage' => 'parameter error'));
		}
	}

	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//									사용자 정보 가져오기											   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function info_get()
	{
		set_req_log('/member/info', $_SERVER['HTTP_CLIENT_TYPE'], 'currentPage - '.$this->uri->segment(4));
		$current_page = $this->uri->segment(4);
		if($current_page && isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']) || isset($_SERVER['HTTP_ACCESS_TOKEN']))
		{
			//$access_token = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?$_SERVER['HTTP_NEW_ACCESS_TOKEN']:$_SERVER['HTTP_ACCESS_TOKEN'];
			$access_token_check = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?check_token($_SERVER['HTTP_NEW_ACCESS_TOKEN']):check_token($_SERVER['HTTP_ACCESS_TOKEN']);
			if($access_token_check['result'] == 0)
			{
				http_response_code(400);
				
				set_err_log('Access Token Error');
				
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
	
				set_err_log('Access Token Error');
				
				echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
				
				exit(0);
			}
			
			$db_result = $this->member_db_model->get_member_info($xid);
			//var_dump($db_result);
			if($db_result)
			{
				set_res_log(json_encode($db_result[0]));
				
				echo json_encode($db_result[0]);
			}
			else
			{
				http_response_code(500);
		
				set_err_log('Get Member Info DB Error');
			}
			
			
		}
		else
		{
			http_response_code(400);
		
			set_err_log('Parameter Empty - current_paage');
						
			echo json_encode(array('errorCode' => '00', 'errorMessage' => ' parameter error'));
		}
	}
	
	
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//										찜 목록												   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function wish_get()
	{
		set_req_log('/member/wish', $_SERVER['HTTP_CLIENT_TYPE'], 'currentPage - '.$this->uri->segment(4));
		if(check_header())
		{
			$current_page = $this->uri->segment(4);
			if($current_page && isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']) || isset($_SERVER['HTTP_ACCESS_TOKEN']))
			{
				//$access_token = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?$_SERVER['HTTP_NEW_ACCESS_TOKEN']:$_SERVER['HTTP_ACCESS_TOKEN'];
				$access_token_check = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?check_token($_SERVER['HTTP_NEW_ACCESS_TOKEN']):check_token($_SERVER['HTTP_ACCESS_TOKEN']);
				if($access_token_check['result'] == 0)
				{
					http_response_code(400);
					
					set_err_log('Access Token Error');
					
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
		
					set_err_log('Access Token Error');
					
					echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
					
					exit(0);
				}
				
				$result_array = array();
				$limit = 10; // 한 화면에 보여지는 리스트 수
				$offset = ($current_page - 1) * $limit; // 각 페이지의 한 화면의 리스트의 첫번째 인덱스
				
				$db_result = $this->member_db_model->get_wish_list($xid, $offset, $limit);
				if($db_result)
				{
					foreach($db_result['list'] as $key => $row)
					{
						$db_result_image = $this->menu_db_model->get_menu_image($row['menuId']);
						if($db_result_image)
						{
							foreach($db_result_image['list'] as $key2 => $row2)
							{
								 $db_result['list'][$key]['menuImageUrl'] = $row2['menu_image_url'];
							}
						}
						else
						{
							$db_result['list'][$key]['menuImageUrl'] = array();
						}
					}
					
					$result_array = $db_result['list'];
				}
				set_res_log(json_encode($result_array));
				
				echo json_encode($result_array);
			}
			else
			{
				http_response_code(400);
			
				set_err_log('Parameter Empty - current_paage');
				
				echo json_encode(array('errorCode' => '00', 'errorMessage' => ' parameter error'));
			}
		}
		else
		{
			http_response_code(400);
			
			set_err_log('Parameter Empty - Header');
			
			echo json_encode(array('errorCode' => '00', 'errorMessage' => 'header parameter error'));
		}
	}
	
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//										찜 하기												   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function wish_post()
	{
		set_req_log('/member/wish', $_SERVER['HTTP_CLIENT_TYPE'], 'menuId - '.$this->uri->segment(4));
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
						
						set_err_log('Access Token Error');
						
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
			
						set_err_log('Access Token Error');
						
						echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
						
						exit(0);
					}
					
					$result_array = array();
					
					$db_result = $this->member_db_model->set_member_wish($xid, $menu_id);
					if($db_result) // SELECT '01' AS result;
					{
						if($db_result[0]['result'] == 'insert')
						{
							// set member point
							$db_result = $this->member_db_model->set_member_point($xid, 'wish', 5);
							
							set_res_log(json_encode(array('status' => 'insert')));
							
							echo json_encode(array('status' => 'insert'));
						}
						else // delete
						{
							set_res_log(json_encode(array('status' => 'delete')));
							
							echo json_encode(array('status' => 'delete'));
						}
					}
					else
					{
						http_response_code(500);
				
						set_err_log('Set Member Wish DB Error');
					}
				}
				else
				{
					http_response_code(400);
			
					set_err_log('Access Token Error');
					
					echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
				}
			}
			else
			{
				http_response_code(400);
			
				set_err_log('Parameter Empty - menuId');
				
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
	//										내 알람 가져오기										   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function alarm_get()
	{
		set_req_log('/member/alarm', $_SERVER['HTTP_CLIENT_TYPE'], json_encode($this->get()));
		if(check_header())
		{
			if((isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']) || isset($_SERVER['HTTP_ACCESS_TOKEN'])))
			{
				//$access_token = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?$_SERVER['HTTP_NEW_ACCESS_TOKEN']:$_SERVER['HTTP_ACCESS_TOKEN'];
				$access_token_check = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?check_token($_SERVER['HTTP_NEW_ACCESS_TOKEN']):check_token($_SERVER['HTTP_ACCESS_TOKEN']);
				if($access_token_check['result'] == 0)
				{
					http_response_code(400);
					
					set_err_log('Access Token Error');
					
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
		
					set_err_log('Access Token Error');
					
					echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
					
					exit(0);
				}
				
				$db_result = $this->member_db_model->get_member_alarm($xid);
				
				if($db_result)
				{
					set_res_log(json_encode($db_result['list']));
					
					echo json_encode($db_result['list']);
				}
				else
				{
					set_res_log(json_encode(array()));
					
					echo json_encode(array());
				}
			}
			else
			{
				http_response_code(400);
		
				set_err_log('Access Token Error');
				
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



	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//										내 리뷰 가져오기										   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function comment_get()
	{
		set_req_log('/member/comment', $_SERVER['HTTP_CLIENT_TYPE'], json_encode($this->get()));
		if(check_header())
		{
			if($this->get('menuId'))
			{
				if((isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']) || isset($_SERVER['HTTP_ACCESS_TOKEN'])))
				{
					//$access_token = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?$_SERVER['HTTP_NEW_ACCESS_TOKEN']:$_SERVER['HTTP_ACCESS_TOKEN'];
					$access_token_check = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?check_token($_SERVER['HTTP_NEW_ACCESS_TOKEN']):check_token($_SERVER['HTTP_ACCESS_TOKEN']);
					if($access_token_check['result'] == 0)
					{
						http_response_code(400);
						
						set_err_log('Access Token Error');
						
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
			
						set_err_log('Access Token Error');
						
						echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
						
						exit(0);
					}
					
					$menu_id = $this->get('menuId');
					
					$db_result = $this->member_db_model->get_member_comment($xid, $menu_id);
					
					if($db_result)
					{
						set_res_log(json_encode($db_result[0]));
						
						echo json_encode($db_result[0]);
					}
				}
				else
				{
					http_response_code(400);
			
					set_err_log('Access Token Error');
					
					echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
				}
			}
			else
			{
				http_response_code(400);
			
				if(!array_key_exists('menuId', $this->get()))
				{
					set_err_log('Parameter Empty - menuId');
				}
				
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
	//										내 리뷰 삭제											   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function comment_delete()
	{
		set_req_log('/member/comment', $_SERVER['HTTP_CLIENT_TYPE'], json_encode($this->delete()));
		if(check_header())
		{
			if($this->delete('commentId') && $this->delete('menuId'))
			{
				if((isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']) || isset($_SERVER['HTTP_ACCESS_TOKEN'])))
				{
					//$access_token = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?$_SERVER['HTTP_NEW_ACCESS_TOKEN']:$_SERVER['HTTP_ACCESS_TOKEN'];
					$access_token_check = (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']))?check_token($_SERVER['HTTP_NEW_ACCESS_TOKEN']):check_token($_SERVER['HTTP_ACCESS_TOKEN']);
					if($access_token_check['result'] == 0)
					{
						http_response_code(400);
						
						set_err_log('Access Token Error');
						
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
			
						set_err_log('Access Token Error');
						
						echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
						
						exit(0);
					}
					
					$comment_id = $this->delete('commentId');
					$menu_id = $this->delete('menuId');
					
					$db_result = $this->member_db_model->delete_member_comment($comment_id, $xid, $menu_id);
					
					if($db_result < 1)
					{
						http_response_code(500);
					
						set_err_log('Delete Member Comment DB Error');
					}
					else
					{
						set_res_log('OK');
					}
				}
				else
				{
					http_response_code(400);
			
					set_err_log('Access Token Error');
					
					echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
				}
			}
			else
			{
				http_response_code(400);
			
				if(!array_key_exists('commentId', $this->delete()))
				{
					set_err_log('Parameter Empty - commentId');
				}

				if(!array_key_exists('menuId', $this->delete()))
				{
					set_err_log('Parameter Empty - menuId');
				}
				
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
	
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */