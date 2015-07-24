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
		
		// get order where seller_id = 0
		$sql = "SELECT o.order_id, om.menu_id, link, o.xid FROM `order` AS o INNER JOIN order_menu AS om	ON om.order_id = o.order_id	AND om.is_represent = 1 WHERE seller_id = 0 AND TIMEDIFF(NOW(), regist_date + INTERVAL 10 MINUTE) > '00:00:00' AND o.order_status = 'standby' AND IF(o.pay_type = 'vbank', o.vbank_update_date IS NOT NULL, TRUE);";
		$result = mysqli_query($conn,$sql);
		
		$row_count = $result->num_rows;
		echo "Work Count :: $row_count \n";
		
		while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			echo json_encode($row) . "\n";
			
			$seller_id = 0;
			$is_perfect = false;
			$perfect_seller_id = 0;
			$order_seller_id = 0;
			$main_menu_id = ($row['menu_id'])?$row['menu_id']:0;
			$order_id = $row['order_id'];
			$xid = $row['xid'];
			$link = $row['link'];
			
			// get seller
			$sql = "SELECT seller_id FROM seller_ratio WHERE status = 0 ORDER BY ratio_id ASC LIMIT 1;";
			$result_get_seller = mysqli_query($conn,$sql);
			while($row_get_seller = mysqli_fetch_array($result_get_seller,MYSQLI_ASSOC))
			{
				$seller_id = $row_get_seller['seller_id'];
				//echo 'seller_id ::'.$seller_id;
			}
			mysqli_free_result($result_get_seller);
			
			// get seller_ratio by menu_seller
			$sql = "SELECT seller_id, ratio FROM menu_seller WHERE menu_id = " . $main_menu_id;
			$result_get_seller_ratio_by_menu_seller = mysqli_query($conn,$sql);
			while($row_get_seller_ratio_by_menu_seller = mysqli_fetch_array($result_get_seller_ratio_by_menu_seller,MYSQLI_ASSOC))
			{
				//echo 'menu_seller_ratio ::'.json_encode($row_get_seller_ratio_by_menu_seller);
				if($row_get_seller_ratio_by_menu_seller['ratio'] = 100)
				{
					$is_perfect = true;
					$perfect_seller_id = $row_get_seller_ratio_by_menu_seller['seller_id'];
				}
			}
			mysqli_free_result($result_get_seller_ratio_by_menu_seller);
			
			if($is_perfect) // 메뉴 판매자 비율에 100%가 있으면
			{
				if($seller_id == $perfect_seller_id)
				{
					//echo 'is_perfect true & $seller_id choice';
					$order_seller_id = $seller_id;
				}
				else
				{
					//echo 'is_perfect true & $seller_id choice';
					
					// set seller swap
					$sql = "CALL USP_SET_SELLER_SWAP(".$seller_id.", ".$perfect_seller_id.");";
					$result_set_seller_swap = mysqli_query($conn,$sql);
					mysqli_free_result($result_set_seller_swap);
					
					$order_seller_id = $seller_id;
				}
			}
			else // 메뉴 판매자 비율에 100%가 없으면
			{
				$sellable_seller = array();
				
				// get seller_ratio by menu_seller
				$sql = "SELECT seller_id, ratio FROM menu_seller WHERE menu_id = " . $main_menu_id;
				$result_get_seller_ratio_by_menu_seller = mysqli_query($conn,$sql);
				while($row_get_seller_ratio_by_menu_seller = mysqli_fetch_array($result_get_seller_ratio_by_menu_seller,MYSQLI_ASSOC))
				{
					// 주문 별 메뉴 비율
					$sql = "CALL USP_GET_MENU_RATIO_BY_ORDER(".$main_menu_id.");";
					$result_get_menu_ratio_by_order = mysqli_query($conn,$sql);
					while($row_get_menu_ratio_by_order = mysqli_fetch_array($result_get_menu_ratio_by_order,MYSQLI_ASSOC))
					{
						//echo 'menu_ratio_by_order ::'.json_encode($row_get_seller_ratio_by_menu_seller);
						
						if($row['seller_id'] == $row_get_menu_ratio_by_order['seller_id'])
						{
							// 메뉴 비율 비교
							if($row_get_seller_ratio_by_menu_seller['ratio'] > $row_get_menu_ratio_by_order['ratio'])
							{
								array_push($sellable_seller, $row_get_seller_ratio_by_menu_seller['seller_id']);
							}
						}
					}
					mysqli_free_result($result_get_menu_ratio_by_order);
				}
				mysqli_free_result($result_get_seller_ratio_by_menu_seller);				
				
				//echo 'sellable_seller ::'.json_encode($sellable_seller);
				
				if(in_array($seller_id, $sellable_seller)) // 가능한 상점이 있고, 처음 뽑은 seller_id가 있다면
				{
					//echo '가능한 상점이 있고, 처음 뽑은 seller_id가 있다면';
					$order_seller_id = $seller_id;
				}
				else if($sellable_seller && !in_array($seller_id, $sellable_seller)) // 가능한 상점이 있고, 처음 뽑은 seller_id가 없다면
				{
					//echo '가능한 상점이 있고, 처음 뽑은 seller_id가 없다면 가능한 상점중에 랜덤으로 하나 선택';
					// 가능한 상점중에 랜덤으로 하나 선택
					$order_seller_id = $sellable_seller[array_rand($sellable_seller, 1)];
					
					// 처음 뽑은 seller_id 와 가능한 상점중에 랜덤으로 뽑은 상점을 스왑
					// set seller swap
					$sql = "CALL USP_SET_SELLER_SWAP(".$seller_id.", ".$order_seller_id.");";
					$result_set_seller_swap = mysqli_query($conn,$sql);
					mysqli_free_result($result_set_seller_swap);
					
					// TODO
				}
				else // 해당 메뉴에 가능한 판매자가 없는 경우
				{
					//echo '해당 메뉴에 가능한 판매자가 없는 경우'; 
					// 큐 추가
					$sql = "CALL USP_SET_SELLER_QUEUE();";
					$result_add_queue = mysqli_query($conn,$sql);
					mysqli_free_result($result_add_queue);
					
					$order_seller_id = $seller_id;
				}
			}
			
			echo 'order_seller_id ::'.$order_seller_id . "\n";
			
			if($order_seller_id)
			{
				// set seller ratio status
				$sql = "CALL USP_SET_SELLER_RATIO_STATUS(".$order_seller_id.");";
				$result_seller_ratio_status = mysqli_query($conn,$sql);
				mysqli_free_result($result_seller_ratio_status);
				
				// set seller for order
				$sql = "UPDATE `order` SET seller_id = ".$order_seller_id.", order_status='finish' WHERE order_id = ".$order_id.";";
				$result_set_seller_order = mysqli_query($conn,$sql);
				mysqli_free_result($result_set_seller_order);
				
				// send push to seller
				$target_xid = $order_seller_id;
				$data = array(
					'xid' => $target_xid,
					'type' => 'seller',
					'badgeCount' => 1,
					'message' => '새로운 주문이 접수 됐습니다.',
					'payload' => json_encode(array('type' => 'S01'))
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
				
				echo "success send push target :: $target_xid\n";
				
				// get seller info
				$sql = "SELECT phone FROM seller WHERE seller_id = " . $order_seller_id;
				$result_get_seller_info = mysqli_query($conn,$sql);
				while($row_get_seller_info = mysqli_fetch_array($result_get_seller_info,MYSQLI_ASSOC))
				{
					$seller_phone = $row_get_seller_info['phone'];
					
					// send sms to seller
					$sql = "CALL USP_SET_SMS_MESSAGE('".$seller_phone."', '새로운 주문이 접수 됐습니다.');";
					$result_seller_ratio_status = mysqli_query($conn,$sql);
					mysqli_free_result($result_seller_ratio_status);
				}
				mysqli_free_result($result_get_seller_info);
				
				
				// send push to customer
				$data = array(
					'xid' => $xid,
					'type' => 'member',
					'badgeCount' => 1,
					'message' => '예약이 완료되었습니다.',
					'payload' => array('type' => 'url', 'content' => $link)
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
				
				echo "success send push customer :: $xid\n";
				
				// get member info
				$sql = "SELECT phone FROM member WHERE xid = " . $xid;
				$result_get_member_info = mysqli_query($conn,$sql);
				while($row_get_member_info = mysqli_fetch_array($result_get_member_info,MYSQLI_ASSOC))
				{
					$member_phone = $row_get_member_info['phone'];
					
					// send sms to seller
					$sql = "CALL USP_SET_SMS_MESSAGE('".$member_phone."', '예약이 완료되었습니다.\n ".$link."' );";
					$result_send_member_sms = mysqli_query($conn,$sql);
					mysqli_free_result($result_send_member_sms);
				}
				mysqli_free_result($result_get_seller_info);
				
				
				// set customer alarm
				$sql = "INSERT INTO alarm(xid, `type`, title, content) VALUES(".$xid.", 'url', '예약이 완료되었습니다', '".$link."')";
				$result_set_alarm = mysqli_query($conn,$sql);
			}


			
			
/*
			// get corp info
			$sql = "SELECT mobile_phone1, mobile_phone2, mobile_phone3 FROM service_corp WHERE corp_id = 1";
			$result_get_corp_info = mysqli_query($conn,$sql);
			while($row_get_corp_info = mysqli_fetch_array($result_get_corp_info,MYSQLI_ASSOC))
			{
				$corp_phone1 = $row_get_corp_info['mobile_phone1'];
				$corp_phone2 = $row_get_corp_info['mobile_phone2'];
				$corp_phone3 = $row_get_corp_info['mobile_phone3'];
				
				if($corp_phone1)
				{
					// send sms to corp
					$sql = "CALL USP_SET_SMS_MESSAGE('".$corp_phone1."', '새로운 주문이 접수 됐습니다.');";
					$result_sms1 = mysqli_query($conn,$sql);
					mysqli_free_result($result_sms1);
				}
				if($corp_phone2)
				{
					// send sms to corp
					$sql = "CALL USP_SET_SMS_MESSAGE('".$corp_phone2."', '새로운 주문이 접수 됐습니다.');";
					$result_sms2 = mysqli_query($conn,$sql);
					mysqli_free_result($result_sms3);
				}
				if($corp_phone3)
				{
					// send sms to corp
					$sql = "CALL USP_SET_SMS_MESSAGE('".$corp_phone3."', '새로운 주문이 접수 됐습니다.');";
					$result_sms3 = mysqli_query($conn,$sql);
					mysqli_free_result($result_sms3);
				}
			}
			mysqli_free_result($result_get_corp_info);
*/
			
			
		}
		mysqli_free_result($result);
	}
	
	mysqli_close($conn);
?>