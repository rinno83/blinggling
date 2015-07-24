<?php 
	$conn = mysqli_connect('nrj-db-instance.ckjudznpjuws.ap-northeast-1.rds.amazonaws.com', 'xenix', 'wpslrtm79!', 'x_nrj_db');
	if(mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	if($conn)
	{
		$conn->set_charset("utf8");
		
	    $sql = "SELECT o.order_id, o.seller_id, o.order_code, s.phone FROM `order` AS o INNER JOIN seller AS s ON s.seller_id = o.seller_id WHERE o.order_datetime < NOW() AND o.sell_status = 0 AND o.order_status = 'finish';";
		$result = mysqli_query($conn,$sql);
		
		$row_count = $result->num_rows;
		echo date("Y-m-d H:i:s",time()) . "\n";
		echo "Work Count :: $row_count \n";
		
		while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			echo json_encode($row) . "\n";
			
			// set work, sell status 
			$sql2 = "UPDATE `order` SET work_status = 1, sell_status = 1 WHERE order_id = ".$row['order_id'].";";
			$result2 = mysqli_query($conn,$sql2);
			
			// set account
			$sql3 = "CALL USP_SET_ACCOUNT(".$row['order_id'].");";
			$result3 = mysqli_query($conn,$sql3);
			
			
			// send sms to seller
			$sql4 = "CALL USP_SET_SMS_MESSAGE('".$row['phone']."', '주문번호 : ".$row['order_code']." 건에 대해 판매완료 처리되었습니다.');";
			$result_sms1 = mysqli_query($conn,$sql4);
			
			$seller_id = $row['seller_id'];
			echo "success target :: $seller_id\n";
		}
		
		mysqli_free_result($result);
		
		mysqli_close($conn);
	}
?>