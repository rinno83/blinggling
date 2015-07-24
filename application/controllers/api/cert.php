<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Cert extends REST_Controller {

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
	 
	 
	public function code_post()
	{
		set_req_log('/cert/code', $_SERVER['HTTP_CLIENT_TYPE'], json_encode($this->post()));
		if($this->post('phone'))
		{
			$phone = $this->post('phone');
			$part = ($this->post('part'))?$this->post('part'):'join';
			$cert_code = str_pad(mt_rand(0,999999),6,'0');
			
			if($part == 'join')
			{
				$db_result = $this->member_db_model->has_phone($phone);
				if($db_result)
				{
					http_response_code(400);
				
					set_err_log('Member Phone Number Exists');
					
					echo json_encode(array('errorCode' => '05', 'errorMessage' => 'phone number exists'));
				}
				else
				{
					$db_result = $this->member_db_model->set_cert_code($phone, $cert_code);
					if($db_result != -1)
					{
						$send_message = '[' . $cert_code . '] 미친물고기 인증번호 입니다.';
						$this->member_db_model->set_sms($phone, $send_message);
						
						set_res_log(json_encode(array('certCode' => $cert_code)));
						
						echo json_encode(array('certCode' => $cert_code));
					}
					else
					{
						http_response_code(500);
						
						set_err_log('Set Certification Code DB Error');
					}
				}
			}
			else
			{
				$db_result = $this->member_db_model->has_phone($phone);
				if($db_result)
				{
					$db_result = $this->member_db_model->set_cert_code($phone, $cert_code);
					if($db_result != -1)
					{
						$send_message = '[' . $cert_code . '] 미친물고기 인증번호 입니다.';
						$this->member_db_model->set_sms($phone, $send_message);
						
						set_res_log(json_encode(array('certCode' => $cert_code)));
						
						echo json_encode(array('certCode' => $cert_code));
					}
					else
					{
						http_response_code(500);
						
						set_err_log('Set Certification Code DB Error');
					}
				}
				else
				{
					http_response_code(400);
				
					set_err_log('Member Phone Number Not Exists');
					
					echo json_encode(array('errorCode' => '02', 'errorMessage' => 'phone number not exists'));
				}
			}
		}
		else
		{
			http_response_code(400);
			
			if(!array_key_exists('phone', $this->get()))
			{
				set_err_log('Parameter Empty - phone');
			}
			
			echo json_encode(array('errorCode' => '00', 'errorMessage' => 'parameter error'));
		}
	}
	
	public function code_validation_get()
	{
		set_req_log('/cert/code_validation', $_SERVER['HTTP_CLIENT_TYPE'], json_encode($this->get()));
		if($this->get('phone') && $this->get('certCode'))
		{
			$phone = $this->get('phone');
			$cert_code = $this->get('certCode');
			$part = $this->get('part');
			
			if($part == 'find')
			{
				$db_result_has_phone = $this->member_db_model->has_phone($phone);
				if($db_result_has_phone) // exists phone
				{
					$db_result = $this->member_db_model->has_cert_phone($phone);
					if($db_result)
					{
						if($cert_code === '0000')
						{
							http_response_code(200);
						}
						else
						{
							$db_result = $this->member_db_model->check_cert_code($phone);
							if($db_result)
							{
								$db_cert_code = $db_result[0]['code'];
								
								if($db_cert_code != $cert_code)
								{
									http_response_code(400);
									
									set_err_log('Invalid Cert Code');
									
									echo json_encode(array('errorCode' => '03', 'errorMessage' => 'invalid certification code'));
								}
								else
								{
									set_res_log(json_encode(array('email' => $db_result_has_phone[0]['email'])));
									echo json_encode(array('email' => $db_result_has_phone[0]['email']));
								}
							}
							else
							{
								http_response_code(500);
								
								set_err_log('Check Code Validation DB Error');
							}
						}
					}
					else
					{
						http_response_code(400);
					
						set_err_log('Member Phone Not Exists In Certification Table');
						
						echo json_encode(array('errorCode' => '02', 'errorMessage' => 'phone not exists'));
					}					
				}
				else
				{
					http_response_code(400);
				
					set_err_log('Member Phone Not Exists In Member Table');
					
					echo json_encode(array('errorCode' => '02', 'errorMessage' => 'phone not exists'));
				}
			}
			else // join
			{
				$db_result_has_phone = $this->member_db_model->has_phone($phone);
				if($db_result_has_phone) // exists phone
				{
					http_response_code(400);
				
					set_err_log('Member Phone Duplication In Member Table');
					
					echo json_encode(array('errorCode' => '01', 'errorMessage' => 'phone duplicaiton'));					
				}
				else
				{
					$db_result = $this->member_db_model->has_cert_phone($phone);
					if($db_result)
					{
						if($cert_code === '0000')
						{
							http_response_code(200);
						}
						else
						{
							$db_result = $this->member_db_model->check_cert_code($phone);
							if($db_result)
							{
								$db_cert_code = $db_result[0]['code'];
								
								if($db_cert_code != $cert_code)
								{
									http_response_code(400);
									
									set_err_log('Invalid Cert Code');
									
									echo json_encode(array('errorCode' => '03', 'errorMessage' => 'invalid certification code'));
								}
								else
								{
									http_response_code(200);
									
									set_res_log('OK');
								}
							}
							else
							{
								http_response_code(500);
								
								set_err_log('Check Code Validation DB Error');
							}
						}
					}
					else
					{
						http_response_code(400);
					
						set_err_log('Member Phone Exists In Certification Table');
						
						echo json_encode(array('errorCode' => '02', 'errorMessage' => 'phone not exists in certification'));
					}
				}
			}
		}
		else
		{
			http_response_code(400);
				
			if(!array_key_exists('phone', $this->get()))
			{
				set_err_log('Parameter Empty - phone');
			}
			if(!array_key_exists('certCode', $this->get()))
			{
				set_err_log('Parameter Empty - certCode');
			}
			
			echo json_encode(array('errorCode' => '00', 'errorMessage' => 'parameter error'));
		}
	}
	
	
	public function signature_get()
	{
		$secret_key = '183297df504bdd56d9b56911da8750e3
';
		//$request_uri = $_SERVER['REQUEST_URI'];
		$request_uri = $this->get('uri');
		var_dump($request_uri);
		$service_key = 'fe031560e53ac1bb158e982be5fc90c7dba53657';
		$device = $this->get('device');
		$server_datetime = '1434527778';//time();
		$access_token = $this->get('access_token');
		//$access_token = 'g6N4aWQFpmV4cGlyZc5VG6yOpGRhdGGQ';	// normal token
		//$access_token = 'g6N4aWQFpmV4cGlyZc5U+/FGpGRhdGGQ'; // token expired
		//$access_token = 'g6N4aWQFpmV4cGfwg24tg4gwgt4wggwgNjQ5pGRhdGGQ'; // wrong token
		var_dump('RequestTime :: ' . $server_datetime);
		
		if($access_token)
		{
			$xid = get_xid($access_token);
			var_dump('xid :: ' . $xid);
			
			$request_string = $request_uri . $service_key . $device . $access_token;
		}
		else
		{
			$request_string = $request_uri . $service_key . $device;
		}
		
		$string_to_sign = $request_string."\n".$server_datetime;
		var_dump($string_to_sign);
		$signature = base64_encode(hash_hmac("sha1",utf8_encode($string_to_sign), $secret_key, true));
		
		var_dump('signature :: ' . $signature);
		
		//signature_check($signature, $request_uri, $server_datetime, $device, $service_key, $access_token);
	}
	
	
	public function excel_test_get()
	{
		// PHPExcel 라이브러리 로드
		$this->load->library('excel');
		
		$file = 'test.xls'; // not viewable by public 
		$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel); 
		$objWriter->save($file); 
		
/*
		$fileType = 'Excel2007';
		$fileName = 'testFile.xls';
		
		// Read the file
		$objReader = PHPExcel_IOFactory::createReader($fileType);
		$objPHPExcel = $objReader->load($fileName);
		
		// Change the file
		$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A1', 'Hello')
		            ->setCellValue('B1', 'World!');
		
		// Write the file
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
		$objWriter->save($fileName);
*/
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */