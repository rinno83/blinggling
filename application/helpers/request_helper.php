<?php 
	
require_once APPPATH.'/third_party/PhpAmqpLib/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
	
	 
	function signature_check($signature, $request_uri, $datetime, $device, $service_key, $access_token)
	{
		if($access_token)
		{
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, get_instance()->config->item('host').':8888'.$request_uri); 
			curl_setopt($ch, CURLOPT_POST, 1); 
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Signature: ' . $signature,
				'RequestTime: ' . $datetime,
				'Client-Type: ' . $device,
				'Service-Key: ' . $service_key,
				'Access-Token: ' . $access_token,
			    'Content-Type: application/x-www-form-urlencoded',
			    'Content-Length: 0')
			);  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			$res = curl_exec($ch); 
			var_dump($res);
			
			curl_close($ch);
		}
		else
		{
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, get_instance()->config->item('host').':8888'.$request_uri); 
			curl_setopt($ch, CURLOPT_POST, 1); 
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Signature: ' . $signature,
				'RequestTime: ' . $datetime,
				'Client-Type: ' . $device,
				'Service-Key: ' . $service_key,
			    'Content-Type: application/x-www-form-urlencoded',
			    'Content-Length: 0')
			);  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			$res = curl_exec($ch); 
			var_dump($res);
			
			curl_close($ch);
		}
	}
	
	
	function send_queue($mq_host, $mq_port, $mq_user, $mq_password, $messageData, $queue_name)
	{
		$connection = new AMQPConnection($mq_host, $mq_port, $mq_user, $mq_password);
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
	}
	
	
	
	
	
	function notification($xid, $type, $badge_count, $message, $payload=array())
	{
		$result = array();

		if($type == 'member')
		{
			$result = get_instance()->member_db_model->get_member_device($xid);
		}
		else
		{
			$result = get_instance()->seller_db_model->get_seller_device($xid);
		}
		
		if($result)
		{
			foreach($result as $key => $row)
			{
				if($row['push_token'])
				{
					if(strtoupper($row['device']) == 'ANDROID')
					{
						sendGCMMessage($xid, $row['push_token'], $row['gcm_service_key'], $message, $badge_count, $payload);
					}
					else
					{
						sendAPNSMessage($xid, $row['push_token'], $message, (int)$badge_count, $payload);
					}
				}
			}
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
		$apnsCert = get_instance()->config->item('apns_key_path');
		//$apnsCert = '/Users/doogoon/Documents/Work/DOOGOON/nrj/apns_key/cf.pem';
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
		    log_message('info', 'RESULT :: ' . checkAppleErrorResponse($fp));
		    
		    
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
	
?>