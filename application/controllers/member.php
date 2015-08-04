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
		//phpinfo();
		var_dump(config_get());
		var_dump(md5('blinggling'));
	} 
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//									Access-Token 체크											   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function token_check_get()
	{
		set_req_log('/member/token_check', $_SERVER['HTTP_CLIENT_TYPE'], json_encode($this->get()));
		if(isset($_SERVER['HTTP_ACCESS_TOKEN']))
		{
			$access_token_check = check_token($_SERVER['HTTP_ACCESS_TOKEN']);
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
		else
		{
			http_response_code(400);
			
			set_err_log('Parameter Empty - Access-Token');
			
			echo json_encode(array('errorCode' => '00', 'errorMessage' => 'parameter error'));
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
		if($this->post('memberKey'))
		{
			$member_key = $this->post('memberKey');
			$password = ($this->post('password'))?$this->post('password'):$this->post('memberKey');
			
			$db_result = $this->member_db_model->task_member_login($member_key, $password);
			if($db_result)
			{
				$xid = $db_result[0]['xid'];
				set_xid_log($xid);
				
				$access_token = make_token($xid);
				set_res_log($access_token);
				
				header('Access-Token: ' . $access_token);
				
				set_res_log(json_encode($db_result[0]));
				unset($db_result[0]['xid']);
				
				echo json_encode($db_result[0]);
			}
			else
			{
				http_response_code(400);
			
				set_err_log('Member Not Exists');
				
				echo json_encode(array('errorCode' => '02', 'errorMessage' => 'not exists error'));
			}
			
		}
		else
		{
			http_response_code(400);
			
			if(!array_key_exists('memberKey', $this->post()))
			{
				set_err_log('Parameter Empty - memberKey');
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
		if($this->post('memberKey'))
		{
			$member_key = $this->post('memberKey');
			$password = ($this->post('password'))?$this->post('password'):$this->post('memberKey');
			$name = ($this->post('name'))?$this->post('name'):'';			
			$birthday = ($this->post('birthday'))?$this->post('birthday'):'';
			$gender = ($this->post('gender'))?$this->post('gender'):'';
			$profile_image_url = ($this->post('profileImageUrl'))?$this->post('profileImageUrl'):'';
			
			$db_result = $this->member_db_model->set_member_account($member_key, $password, $name, $birthday, $gender, $profile_image_url);
			if($db_result)
			{
				if($db_result[0]['result'] == '01')
				{
					set_err_log('Duplication - memberKey');
					echo json_encode(array('errorCode' => '01', 'errorMessage' => 'duplicate error'));
				}
				else // ok
				{
					$xid = $db_result[0]['xid'];
					set_xid_log($xid);
					
					$access_token = make_token($xid);
					set_res_log($access_token);
					
					header('Access-Token: ' . $access_token);
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
			
			if(!array_key_exists('memberKey', $this->post()))
			{
				set_err_log('Parameter Empty - memberKey');
			}
			
			echo json_encode(array('errorCode' => '00', 'errorMessage' => 'parameter error'));
		}
	}
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//										디바이스 설정											   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function device_post()
	{
		set_req_log('/member/device', $_SERVER['HTTP_CLIENT_TYPE'], json_encode($this->post()));
		if(isset($_SERVER['HTTP_ACCESS_TOKEN']))
		{
			$access_token_check = check_token($_SERVER['HTTP_ACCESS_TOKEN']);
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
			
			if($this->post('uuid') && $this->post('pushToken'))
			{
				$uuid = $this->post('uuid');
				$push_token = $this->post('pushToken');
				$device = $_SERVER['HTTP_CLIENT_TYPE'];
				
				$this->member_db_model->set_member_device($xid, $device, $uuid, $push_token);
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
		else
		{
			http_response_code(400);
			
			set_err_log('Parameter Empty - Access-Token');
			
			echo json_encode(array('errorCode' => '00', 'errorMessage' => 'parameter error'));
		}
	}
		
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */