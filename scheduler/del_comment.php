<?php 
	$conn = mysqli_connect('nrj-db-instance.ckjudznpjuws.ap-northeast-1.rds.amazonaws.com', 'xenix', 'wpslrtm79!', 'x_nrj_db');
	if(mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	if($conn)
	{
		$conn->set_charset("utf8");
		
		$sql = "SELECT comment_id, menu_id, xid, star_point, comment, sorting, status, regist_date, DATEDIFF(now(), regist_date) FROM menu_comment WHERE DATEDIFF(NOW(), regist_date) > 30;";
		$result = mysqli_query($conn,$sql);
		
		$row_count = $result->num_rows;
		echo date("Y-m-d H:i:s",time()) . "\n";
		echo "Work Count :: $row_count \n";
		
		while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			echo json_encode($row) . "\n";
			
			// set work, sell status 
			$sql2 = "DELETE FROM menu_comment WHERE comment_id = ".$row['comment_id'].";";
			$result2 = mysqli_query($conn,$sql2);
			
			echo $row['comment_id'] . ' Comment Delete Result :: ';
			echo $conn->affected_rows;			
			echo "\n";
		}
		
		mysqli_free_result($result);
		
		mysqli_close($conn);
	}
?>