<?php 
	//$conn = mysqli_connect('net-xenix-db.cejburuco0ed.ap-northeast-1.rds.amazonaws.com', 'xenix', 'wpslrtm79!');
	$conn = mysqli_connect('nrj-db-instance.ckjudznpjuws.ap-northeast-1.rds.amazonaws.com', 'xenix', 'wpslrtm79!', 'x_nrj_db');
	if(mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	if($conn)
	{
	    $date = new DateTime();
		$date->setTimezone(new DateTimeZone('Asia/Seoul'));
		
		//var_dump($date->format('Y-m-d H:i:s'));
	    $now = $date->format('Y-m-d H:i:s');
		
		$sql = "SELECT order_id, xid FROM `order` WHERE TIMESTAMPDIFF(SECOND, update_date, CURRENT_TIMESTAMP) > 3600 AND review_push_status = 0 AND order_status = 'finish' AND work_status = 1 AND sell_status = 1;";
		$result = mysqli_query($conn,$sql);
		
		$row_count = $result->num_rows;
		echo "Work Count :: $row_count \n";
		
		while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			echo json_encode($row) . "\n";
			
			// set push_member status 
			$sql2 = "UPDATE `order` SET review_push_status = 1 WHERE xid = ".$row['xid']." AND order_id = ".$row['order_id'].";";
			$result2 = mysqli_query($conn,$sql2);
			
			$target_xid = $row['xid'];
			$data = array(
				'xid' => $target_xid,
				'type' => 'member',
				'badgeCount' => 1,
				'message' => '리뷰를 등록하시면 결제시 현금처럼 사용하실 수 있는 포인트를 드립니다.',
				'payload' => json_encode(array('type' => 'M01'))
			);
			
			$dataJson = json_encode($data);
			
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, 'http://api.crazyfish.co.kr/push/set_queue'); 
			curl_setopt($ch, CURLOPT_POST, 1); 
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
			curl_setopt($ch, CURLOPT_HTTPHEADER, array());  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			$res = curl_exec($ch); 
			
			curl_close($ch);
			
			echo "success target :: $target_xid\n";
			
			mysqli_free_result($result2);
		}
		
		mysqli_free_result($result);
	}
	
	mysqli_close($conn);
?>