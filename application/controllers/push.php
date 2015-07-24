<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
class Push extends CI_Controller {

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
	 
//////////////////////////////////////////////////////////////////////////////
//																			//
//								Queue										//
//																			//
//////////////////////////////////////////////////////////////////////////////

	public function set_queue()
	{
		if(isset($_POST['xid']) && isset($_POST['type']) && isset($_POST['badgeCount']) && isset($_POST['message']) && isset($_POST['payload']))
		{
			$xid = $_POST['xid'];
			$badge_count = $_POST['badgeCount'];
			$message = $_POST['message'];
			$payload = $_POST['payload'];
			$type = $_POST['type'];
			
			if($type == 'member')
			{
				// get member device info
				$db_result = $this->member_db_model->get_member_device($xid);
			}
			else // seller
			{
				// get member device info
				$db_result = $this->seller_db_model->get_seller_device($xid);
			}			
			
			if($db_result)
			{
				foreach($db_result as $key => $row)
				{
					$messageData = array(
						'token' => $row['push_token'],
						'alert_message' => $message,
						'badge_count' => $badge_count,
						'payload' => json_decode($payload)
					);
					
					$queue_name = $this->config->item('apns_queue_name');
					if( strtoupper($row['device']) == 'ANDROID' )
					{
						$queue_name = $this->config->item('gcm_queue_name');
					}
					
					send_queue($this->config->item('mq_host'), $this->config->item('mq_port'), $this->config->item('mq_user'), $this->config->item('mq_pass'), $messageData, $queue_name);
					
/*
					$connection = new AMQPConnection($this->config->item('mq_host'), $this->config->item('mq_port'), $this->config->item('mq_user'), $this->config->item('mq_pass'));
					$channel = $connection->channel();
					
					$channel->queue_declare($queue_name, false, true, false, false);
					
					if( is_array($messageData) ) {
				    	$messageData = json_encode($messageData);
			    	}
					$msg = new AMQPMessage($messageData,
					                        array('delivery_mode' => 2) # make message persistent
					                      );
					
					$channel->basic_publish($msg, '', $queue_name);
					
					log_message('info', ' [x] Sent '. $messageData);
					
					$channel->close();
					$connection->close();
*/
				}
			}
			else
			{
				http_response_code(500);
						
				log_message('error', 'get member device db error');
			}
		}
		else
		{
			http_response_code(400);
			
			log_message('error', 'parameter error');
			
			echo json_encode(array('errorCode' => '00', 'errorMessage' => 'parameter error'));
		}
	}
	
	
	public function test_set_queue()
	{
		if(isset($_POST['token']) && isset($_POST['badgeCount']) && isset($_POST['message']))
		{
			$push_token = $_POST['token'];
			$badge_count = $_POST['badgeCount'];
			$message = $_POST['message'];
			$payload = array();
			
			
			$messageData = array(
				'token' => $push_token,
				'alert_message' => $message,
				'badge_count' => $badge_count,
				'payload' => $payload
			);
			
			$queue_name = 'GCM_PETSBE_QUEUE';
			
			
			send_queue('54.64.225.127', '5672', 'xenix', 'wpslrtm79!', $messageData, $queue_name);
		}
		else
		{
			http_response_code(400);
			
			log_message('error', 'parameter error');
			
			echo json_encode(array('errorCode' => '00', 'errorMessage' => 'parameter error'));
		}
	}
	
	
	function notification()
	{
		if(isset($_POST['xid']) && isset($_POST['type']) && isset($_POST['badgeCount']) && isset($_POST['message']) && isset($_POST['payload']))
		{
			$result = array();
			
			$xid = $_POST['xid'];
			$type = $_POST['type'];
			$badge_count = $_POST['badgeCount'];
			$message = $_POST['message'];
			$payload = (isset($_POST['payload']))?json_decode($_POST['payload'], true):array();
			
			if($type == 'member')
			{
				$result = $this->member_db_model->get_member_device($xid);
			}
			else
			{
				$result = $this->seller_db_model->get_seller_device($xid);
			}
			
			log_message('info', json_encode($result));
			
			if($result)
			{
				foreach($result as $key => $row)
				{
					if($row['push_token'])
					{
						if(strtoupper($row['device']) == 'ANDROID')
						{
							$this->sendGCMMessage($xid, $row['push_token'], $row['gcm_service_key'], $message, $badge_count, $payload);
						}
						else
						{
							$this->sendAPNSMessage($xid, $row['push_token'], $message, (int)$badge_count, $payload);
						}
					}
				}
			}
		}
		else
		{
			log_message('error', 'parameter error');
		}
	}
	
	
	
	function sendGCMMessage($xid, $push_token, $app_key, $message, $badge_count, $payload) {
		$data = array(
			'registration_ids' => array($push_token),
			'data' => array('alert_message' => $message, 'badge_count' => $badge_count, 'payload' => $payload)
		);
	    
	    $headers = array(
	        "Content-Type:application/json", 
	        "Authorization:key=".$app_key
	    );
	    			    
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, "https://android.googleapis.com/gcm/send");
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	    $result = curl_exec($ch);
	    
	    log_message('info', 'GCM Push Send!! ///////////////////////////////');
	    log_message('info', 'XID :: ' . $xid);
	    log_message('info', 'DATA :: ' . json_encode($data));
	    log_message('info', 'RESULT :: ' . $result);
	    curl_close($ch);
	}
	
	
	function sendAPNSMessage($xid, $push_token, $message, $badge_count, $payload)
	{
		$sound = 'default';
		
		// 개발용 서버 설정
		$apnsHost = 'gateway.push.apple.com';
		$apnsCert = $this->config->item('apns_key_path');
		//$apnsCert = 'cert.pem';
		//$apnsCert = 'apns-dev.pem';
		
		// 실서비스용 서버 설정
		//$apnsHost = 'gateway.push.apple.com';
		//$apnsCert = 'apns-prod.pem';
		
		$apnsPort = 2195;
		
		$msgBody = array();
		$msgBody['aps'] = array('alert' => $message);
		$msgBody['aps']['badge'] = ($badge_count)?$badge_count:1;
		$msgBody['aps']['sound'] = ($sound)?$sound:'default';
		$msgBody['aps']['payload'] = $payload;
		//$msgBody['aps']['payload'] = $payload;
		
		// APNS서버와 SSL 소켓 통신
		$streamCtxt = stream_context_create();
		stream_context_set_option($streamCtxt, 'ssl', 'local_cert', $apnsCert); 
		//stream_context_set_option($streamCtxt, 'ssl', 'passphrase', 'cf1234');
		$fp = stream_socket_client('ssl://'.$apnsHost.':'.$apnsPort, $err, $errstr, 30, STREAM_CLIENT_CONNECT | STREAM_CLIENT_ASYNC_CONNECT, $streamCtxt);
		stream_set_blocking ($fp, 0);
		
		if (!$fp) 
		{
			log_message('error', "APNS Fail Connection!!!");
			log_message('error', "[$err]$errstr \n");
			exit;
		} 
		else 
		{
			// 보낼 내용을 json 포맷으로 인코딩
			$payload = json_encode($msgBody);
			
			$apple_expiry = time() + (90 * 24 * 60 * 60);
			
			//$apnsMsg = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $push_token)) .chr(0) . chr(strlen($payload)) . $payload;
            $apnsMsg = pack("C", 1) . pack("N", $xid) . pack("N", $xid) . pack("n", 32) . pack('H*', str_replace(' ', '', $push_token)) . pack("n", strlen($payload)) . $payload; //Enhanced Notification
            

			
			$fwrite = fwrite($fp, $apnsMsg);
			
			log_message('info', 'APNS Push Send!! ///////////////////////////////');
		    log_message('info', 'XID :: ' . $xid);
		    log_message('info', 'TOKEN :: ' . $push_token);
		    log_message('info', 'DATA :: ' . $payload);
		    log_message('info', 'RESULT :: ' . $this->checkAppleErrorResponse($fp));
		    
		    
		}
		fclose($fp);
			
	}
	
	 function checkAppleErrorResponse($fp) {
	 	
       $apple_error_response = fread($fp, 6); //byte1=always 8, byte2=StatusCode, bytes3,4,5,6=identifier(rowID). Should return nothing if OK.
       //NOTE: Make sure you set stream_set_blocking($fp, 0) or else fread will pause your script and wait forever when there is no response to be sent.

       if ($apple_error_response) {

            $error_response = unpack('Ccommand/Cstatus_code/Nidentifier', $apple_error_response); //unpack the error response (first byte 'command" should always be 8)

            if ($error_response['status_code'] == '0') {
                $error_response['status_code'] = '0-No errors encountered';

            } else if ($error_response['status_code'] == '1') {
                $error_response['status_code'] = '1-Processing error';

            } else if ($error_response['status_code'] == '2') {
                $error_response['status_code'] = '2-Missing device token';

            } else if ($error_response['status_code'] == '3') {
                $error_response['status_code'] = '3-Missing topic';

            } else if ($error_response['status_code'] == '4') {
                $error_response['status_code'] = '4-Missing payload';

            } else if ($error_response['status_code'] == '5') {
                $error_response['status_code'] = '5-Invalid token size';

            } else if ($error_response['status_code'] == '6') {
                $error_response['status_code'] = '6-Invalid topic size';

            } else if ($error_response['status_code'] == '7') {
                $error_response['status_code'] = '7-Invalid payload size';

            } else if ($error_response['status_code'] == '8') {
                $error_response['status_code'] = '8-Invalid token';

            } else if ($error_response['status_code'] == '255') {
                $error_response['status_code'] = '255-None (unknown)';

            } else {
                $error_response['status_code'] = $error_response['status_code'].'-Not listed';

            }
			return $error_response['status_code'];
       }
       return 'I don`t know';
    }
	
	public function php_info()
	{
		phpinfo();
	}






//////////////////////////////////////////////////////////////////////////////
//																			//
//								List										//
//																			//
//////////////////////////////////////////////////////////////////////////////
	 
	public function history()
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
		$db_result = $this->push_db_model->get_push_history_list($keyword, $offset, $limit);
		
		// 전체 데이터 갯수 얻기
		$total_row = $db_result['count'];
		
		if($total_row > 0)
		{
			$view_data['list'] = $db_result['list'];		
			
			foreach($db_result['list'] as $key => $row)
	    	{
	    		switch($row['status'])
	    		{
		    		case 1: $view_data['list'][$key]['status'] = '발송 완료';
		    				break;
		    		case 2: $view_data['list'][$key]['status'] = '발송 실패';
		    				break;
		    		case 3: $view_data['list'][$key]['status'] = '대기';
		    				break;
		    		default: $view_data['list'][$key]['status'] = '에러';
		    				break;
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
		$this->load->view('push_history_list_view', $view_data);
	}
	
	public function history_paging()
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
		$db_result = $this->push_db_model->get_push_history_list($keyword, $offset, $limit);
		
		// 페이지 정보 얻기
		$total_row = $db_result['count'];
		
		if($total_row > 0)
		{
			$view_data['list'] = $db_result['list'];
			$view_data['result'] = "1";
			
			foreach($db_result['list'] as $key => $row)
	    	{
	    		switch($row['status'])
	    		{
		    		case 1: $view_data['list'][$key]['status'] = '발송 완료';
		    				break;
		    		case 2: $view_data['list'][$key]['status'] = '발송 실패';
		    				break;
		    		case 3: $view_data['list'][$key]['status'] = '대기';
		    				break;
		    		default: $view_data['list'][$key]['status'] = '에러';
		    				break;
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
//								Write										//
//																			//
//////////////////////////////////////////////////////////////////////////////
	
	
	
	public function index()
	{
		$view_data = array();
		
		$db_result = $this->service_db_model->getServiceList('', 0, 999999);
		if($db_result)
		{
			$view_data['service'] = $db_result['list'];
			
			$db_result = $this->device_db_model->getServiceDeviceList($db_result['list'][0]['serviceId'], '', 0, 999999);
			if($db_result)
			{
				$view_data['serviceDevice'] = $db_result['list'];
			}
			else
			{
				$view_data['serviceDevice'] = array();
			}
		}
		else
		{
			$view_data['service'] = array();
		}		
		
		$this->load->view('pushSendView', $view_data);
	}
	
	public function send_form()
	{
		$view_data = array();
		
		$db_result = $this->service_db_model->get_service_list('', 0, 999999);
		if($db_result)
		{
			$view_data['service'] = $db_result['list'];
			
			$db_result = $this->device_db_model->get_service_device_list($db_result['list'][0]['service_id'], '', 0, 999999);
			$device_count = $db_result['count'][0]['count'];
			if($device_count > 0)
			{
				$view_data['service_device'] = $db_result['list'];
			}
			else
			{
				$view_data['service_device'] = array();
			}
		}
		else
		{
			$view_data['service'] = array();
		}		
		
		$this->load->view('push_send_view', $view_data);
	}
	
	public function send()
	{
		$service_id = $_POST['service_id'];
		$title = $_POST['title'];
		$content = $_POST['content'];
		$device = $_POST['device'];
		$image_url = (isset($_POST['image_url']))?$_POST['image_url']:'';
		
		$payload = array(
			'content' => $content,
			'image_url' => $image_url,
			'push_type' => 'all'
		);
		
		$db_result = $this->push_db_model->set_push(0, $service_id, $device, $title, json_encode($payload), 3, NULL);
		
		$result_data = array('result' => 1);
    	
    	//var_dump($result_data);
    	echo json_encode($result_data);
		
	}



//////////////////////////////////////////////////////////////////////////////
//																			//
//								Detail										//
//																			//
//////////////////////////////////////////////////////////////////////////////

	public function detail()
	{
		$view_data = array();
		$push_id = $_GET['push_id'];
		$current_page = (isset($_GET['current_page']))?$_GET['current_page']:1;
		$view_data['current_page'] = $current_page;
		
		$db_result = $this->push_db_model->get_push($push_id);
		if($db_result)
		{
			switch($db_result[0]['status'])
    		{
	    		case 1: $db_result[0]['status'] = '발송 완료';
	    				break;
	    		case 2: $db_result[0]['status'] = '발송 실패';
	    				break;
	    		case 3: $db_result[0]['status'] = '대기';
	    				break;
	    		default: $db_result[0]['status'] = '에러';
	    				break;
    		}
    		
    		//$db_result[0]['payload'] = addslashes(addslashes($db_result[0]['payload']));
    		
    		$view_data['push'] = $db_result[0];
		}
		else
		{
			$view_data['push'] = array();
		}

		$this->load->view('push_detail_view', $view_data);
	}

	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */