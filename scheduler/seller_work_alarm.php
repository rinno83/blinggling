<?php 
	//$conn = mysqli_connect('net-xenix-db.cejburuco0ed.ap-northeast-1.rds.amazonaws.com', 'xenix', 'wpslrtm79!');
	$conn = mysqli_connect('nrj-db-instance.ckjudznpjuws.ap-northeast-1.rds.amazonaws.com', 'xenix', 'wpslrtm79!', 'x_nrj_db');
	if(mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	if($conn)
	{
	    date_default_timezone_set('Asia/Seoul');
		
		$sql = "SELECT order_id, seller_id FROM `order` WHERE TIMESTAMPDIFF(SECOND, CURRENT_TIMESTAMP, order_datetime) < 3600 AND work_status = 0 AND seller_id <> 0 AND order_status <> 'temp';";
		$result = mysqli_query($conn,$sql);
		
		$row_count = $result->num_rows;
		echo "Work Count :: $row_count \n";
		
		while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			echo json_encode($row) . "\n";
			
			$target_xid = $row['seller_id'];
			$data = array(
				'xid' => $target_xid,
				'type' => 'seller',
				'badgeCount' => 1,
				'message' => '아직 작업되지 않은 상품이 있습니다.',
				'payload' => json_encode(array('type' => 'S02'))
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
		}
		
		mysqli_free_result($result);
	}
	
	mysqli_close($conn);
?>