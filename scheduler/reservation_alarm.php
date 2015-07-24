<?php 
	//$conn = mysqli_connect('net-xenix-db.cejburuco0ed.ap-northeast-1.rds.amazonaws.com', 'xenix', 'wpslrtm79!');
	$conn = mysqli_connect('nrj-db-instance.ckjudznpjuws.ap-northeast-1.rds.amazonaws.com', 'xenix', 'wpslrtm79!', 'x_nrj_db');
	if(mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	if($conn)
	{
		$conn->set_charset("utf8");
		
	    date_default_timezone_set('Asia/Seoul');
		
		$sql = "SELECT order_id, xid, link, DATE_FORMAT(FROM_UNIXTIME(UNIX_TIMESTAMP(order_datetime)), '%p %h시 %i분') AS order_datetime FROM `order` WHERE TIMESTAMPDIFF(SECOND, CURRENT_TIMESTAMP, order_datetime) < 3600 AND reservation_push_status = 0 AND order_status = 'finish';";
		$result = mysqli_query($conn,$sql);
		
		$row_count = $result->num_rows;
		echo "Work Count :: $row_count \n";
		
		while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			echo json_encode($row) . "\n";
			
			$message = '미친물고기 알림: 오늘 '.$row['order_datetime'].' 예약입니다.';
			
			// set push_member status 
			$sql2 = "UPDATE `order` SET reservation_push_status = 1 WHERE xid = ".$row['xid']." AND order_id = ".$row['order_id'].";";
			$result2 = mysqli_query($conn,$sql2);
			
			// set alarm
			$sql3 = "INSERT INTO alarm(xid, type, title, content) VALUES(".$row['xid'].", 'url', '".$message."', '".$row['link']."');";
			$result3 = mysqli_query($conn,$sql3);
			
			$target_xid = $row['xid'];
			$data = array(
				'xid' => $target_xid,
				'type' => 'member',
				'badgeCount' => 1,
				'message' => $message,
				'payload' => json_encode(array('type' => 'url', 'content' => $row['link']))
			);
			
			$dataJson = json_encode($data);
			
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, 'http://api.crazyfish.co.kr/push/notification'); 
			curl_setopt($ch, CURLOPT_POST, 1); 
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
			curl_setopt($ch, CURLOPT_HTTPHEADER, array());  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			$res = curl_exec($ch); 
			
			curl_close($ch);
			
			echo "success target :: $target_xid\n";
			
			mysqli_free_result($result2);
			mysqli_free_result($result3);
		}
		
		mysqli_free_result($result);
	}
	
	mysqli_close($conn);
?>