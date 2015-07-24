<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Seller extends REST_Controller {

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
		//$token = 'g6N4aWQEpmV4cGlyZaoxNDMyNTI0MTk1pGRhdGGCq3NlcnZpY2Vfa2V52gAoMTA5M2E5YmQxOWZiYjA0YzI5MjViYzg3N2FmODRiOWM3N2Y4ZWM3Nqd2ZXJzaW9uoTE=';//make_token(2);
/*
		var_dump($token);
		
		var_dump(check_token($token));
*/
		
		//$token_content = get_xid($token);
		//var_dump($token_content);
		var_dump(msgpack_unpack(base64_decode('g6RkYXRhgqd2ZXJzaW9uAatzZXJ2aWNlX2tledoAKDEwOTNhOWJkMTlmYmIwNGMyOTI1YmM4NzdhZjg0YjljNzdmOGVjNzamZXhwaXJlzlVi6UejeGlkBQ==')));
		
	} 
		
	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//									판매자 코드 체크											   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function login_post()
	{
		if($this->post('sellerCode') && $this->post('uuid') && $this->post('pushToken'))
		{
			$seller_code = $this->post('sellerCode');
			$device = strtoupper($_SERVER['HTTP_CLIENT_TYPE']);
			$uuid = $this->post('uuid');
			$push_token = $this->post('pushToken');
			
			$db_result = get_seller_id_by_code($seller_code);
			if($db_result)
			{
				$seller_id = $db_result['seller_id'];
				$seller_name = $db_result['name'];
				
				$db_result = $this->seller_db_model->set_seller_device($seller_id, $uuid, $device, $push_token);
				
				if($db_result != -1)
				{
					$access_token = make_token($seller_id);
					
					header('Access-Token: ' . $access_token);
					
					echo json_encode(array('sellerName'=>$seller_name));
				}
				else
				{
					http_response_code(500);
					
					log_message('error', 'set device db error');
				}
			}
			else
			{
				http_response_code(400);
						
				log_message('error', 'seller code error');
				
				echo json_encode(array('errorCode' => '03', 'errorMessage' => 'invalid seller code'));
			}		
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
	//									Access-Token 체크											   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function token_check_get()
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
		$seller_id = (isset($_SERVER['HTTP_XID']))?$_SERVER['HTTP_XID']:get_seller_id($access_token);
		
		header('Access-Token: ' . $access_token);
		
		if($seller_id == 0)
		{
			http_response_code(400);

			log_message('error', 'access token error');
			
			echo json_encode(array('errorCode' => '04', 'errorMessage' => ' access token error'));
			
			exit(0);
		}
	}
	
	
	
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//									디바이스 정보 설정											   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function device_post()
	{
		if($this->post('uuid') && $this->post('pushToken') && (isset($_SERVER['HTTP_NEW_ACCESS_TOKEN']) || isset($_SERVER['HTTP_ACCESS_TOKEN'])))
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
			
			$device = strtoupper($_SERVER['HTTP_CLIENT_TYPE']);
			$uuid = $this->post('uuid');
			$push_token = $this->post('pushToken');
			
			$db_result = $this->seller_db_model->set_seller_device($seller_id, $uuid, $device, $push_token);
			if($db_result == -1)
			{
				http_response_code(500);
				
				log_message('error', 'set device db error');				
			}			
		}
		else
		{
			http_response_code(400);
				
			log_message('error', 'parameter error');
			
			echo json_encode(array('errorCode' => '00', 'errorMessage' => 'parameter error'));
		}
	}

	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */