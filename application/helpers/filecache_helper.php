<?php 
	function write_file_cache($type, $service_key, $lang_code)
	{
		if($type == 'notice')
		{
			$current_page = 1;
			$keyword = '';
			$page_block = 10; // 한 화면에 보여지는 페이지 수
			$limit = 20; // 한 화면에 보여지는 리스트 수
			$offset = ($current_page - 1) * $limit; // 각 페이지의 한 화면의 리스트의 첫번째 인덱스
			
			// DB에서 DATA 얻기
			$db_result = get_instance()->notice_db_model->get_notice_list(0, $lang_code, $keyword, $offset, $limit);
			$notice_list = array();
			
			if(isset($db_result['list']))
			{
				$notice_list = $db_result['list'];
				foreach($notice_list as $key => $row)
				{
					$date = new DateTime($row['regist_date'], new DateTimeZone('Asia/Seoul'));
					$notice_list[$key]['regist_date'] = (string)$date->getTimeStamp();
				}
			}
			
			// file write
			$string_data = json_encode($notice_list);
			file_put_contents(get_instance()->config->item('file_full_path').$service_key.'_'.$lang_code.'.'.$type, $string_data);
		}
		else if($type == 'faq')
		{
			$current_page = 1;
			$keyword = '';
			$page_block = 10; // 한 화면에 보여지는 페이지 수
			$limit = 20; // 한 화면에 보여지는 리스트 수
			$offset = ($current_page - 1) * $limit; // 각 페이지의 한 화면의 리스트의 첫번째 인덱스
			
			// DB에서 DATA 얻기
			$db_result = get_instance()->faq_db_model->get_faq_list(0, $lang_code, $keyword, $offset, $limit);
			$faq_list = array();
			
			if(isset($db_result['list']))
			{
				$faq_list = $db_result['list'];
				foreach($faq_list as $key => $row)
				{
					$date = new DateTime($row['regist_date'], new DateTimeZone('Asia/Seoul'));
					$faq_list[$key]['regist_date'] = (string)$date->getTimeStamp();
				}
			}
			
			// file write
			$string_data = json_encode($faq_list);
			file_put_contents(get_instance()->config->item('file_full_path').$service_key.'_'.$lang_code.'.'.$type, $string_data);
		}
		else
		{
			
		}
	}
?>