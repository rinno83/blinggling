<?php 

	class admin_db_model extends CI_Model
	{
		
		function __construct() {
			parent::__construct();
		}
		
		
		function login($main_id, $main_pwd)
		{
			try 
			{
				$this->db->start_cache();
				$this->db->from('admin');
				$this->db->stop_cache();
				
				if($main_id !== NULL && $main_pwd !== NULL) 
				{
					$this->db->where('id', $main_id);
					
					
					if($this->db->count_all_results() == 0) 
					{
						$reValue = "101";
					} 
					else 
					{
						$this->db->where('id', $main_id);
						$this->db->where('password', 'PASSWORD("'.$main_pwd.'")', FALSE);
						
						if($this->db->count_all_results() == 0)
						{
							$reValue = "102";
						}
						else
						{
							$this->db->select('admin_id, id', FALSE);
							$this->db->from('admin');
							$this->db->where('id', $main_id);
							$this->db->where('password', 'PASSWORD("'.$main_pwd.'")', FALSE);
							
							$query = $this->db->get();
							
							if(!$query)
							{
								throw new Exception('Could not query:' . mysql_error());
							}
							
							if($query->num_rows() > 0)
							{
								$reValue = $query->result_array();
							}				
							
							$query->next_result(FALSE);
							$query->free_result();
							
							return $reValue;
						}
					}
				}
				else
				{
					$reValue = "9999";
				}
				
				return $reValue;

			}
			catch (Exception $e)
			{
				//var_dump($e);
				$query = NULL;
			}
		}	
		
		function get_notice_list($sid, $lang, $keyword, $offset, $limit) {
			
			$query = $this->db->query('CALL USP_ADMIN_GET_NOTICE_LIST(?, ?, ?, ?, ?);',array($sid, $lang, $keyword, $offset, $limit));
		
		
			if (!$query) {
				throw new Exception('Could not query:' . mysql_error());
			}
			if ($query->num_rows() > 0)
			{
				$rows['count'] = $query->result_array();
				
				$query->next_result();
				
				$rows['list'] = $query->result_array();
				
				$query->next_result(FALSE);
				$query->free_result();
				
				return $rows;
			}else{
			
				$query->next_result(FALSE);
				$query->free_result();
				return false;
			}
		}
		
		function set_notice($sid, $lang, $nid, $title, $content) {
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL XSP_SERVICE_NOTICE_SET(".$sid.", '".$lang."', ".$nid.", '".$title."', '".$content."')");
				
				//var_dump($resultSet2);					
				
				if(!$resultSet2)
				{
					throw new Exception('Could not query:' . mysql_error());
				}
				
				if($resultSet2->num_rows() > 0)
				{
					$reValue = $resultSet2->result_array();
				}
				
				$resultSet2->next_result(FALSE);
				$resultSet2->free_result();
				
				return $reValue;

			}
			catch (Exception $e)
			{
				var_dump($e);
				$resultSet2 = NULL;
			}
		}
		
		
		function del_notice($sid, $nid, $lang) {
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL XSP_SERVICE_NOTICE_DELETE(".$sid.", '".$lang."', ".$nid.")");
				
				//var_dump($resultSet2);					
				
				if(!$resultSet2)
				{
					throw new Exception('Could not query:' . mysql_error());
				}
				
				if($resultSet2->num_rows() > 0)
				{
					$reValue = $resultSet2->result_array();
				}
				
				$resultSet2->next_result(FALSE);
				$resultSet2->free_result();
				
				return $reValue;

			}
			catch (Exception $e)
			{
				var_dump($e);
				$resultSet2 = NULL;
			}
		}
		
		
		function get_notice($nid) {
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL USP_ADMIN_GET_NOTICE(".$nid.")");
				
				//var_dump($resultSet2);					
				
				if(!$resultSet2)
				{
					throw new Exception('Could not query:' . mysql_error());
				}
				
				if($resultSet2->num_rows() > 0)
				{
					$reValue = $resultSet2->result_array();
				}
				
				$resultSet2->next_result(FALSE);
				$resultSet2->free_result();
				
				return $reValue;

			}
			catch (Exception $e)
			{
				var_dump($e);
				$resultSet2 = NULL;
			}
		}
		
		
		function get_service_lang($sid) {
		
			$result = array();
		
			$query = $this->db->query('CALL USP_ADMIN_GET_SERVICE_LANG(?);',array($sid));
						
			if (!$query) {
				throw new Exception('Could not query:' . mysql_error());
			}
			if ($query->num_rows() > 0)
			{
				$rows = $query->result_array(); 
				
				$query->next_result(FALSE);
				$query->free_result();
				
				return $rows;
			}else{
			
				$query->next_result(FALSE);
				$query->free_result();
				return false;
			}		
		}
		
		
		function get_member_list($sid, $keyword, $offset, $limit) {
			
			$query = $this->db->query('CALL USP_ADMIN_GET_MEMBER_LIST(?, ?, ?, ?);',array($sid, $keyword, $offset, $limit));
		
		
			if (!$query) {
				throw new Exception('Could not query:' . mysql_error());
			}
			if ($query->num_rows() > 0)
			{
				$rows['count'] = $query->result_array();
				
				$query->next_result();
				
				$rows['list'] = $query->result_array();
				
				$query->next_result(FALSE);
				$query->free_result();
				
				return $rows;
			}else{
			
				$query->next_result(FALSE);
				$query->free_result();
				return false;
			}
		}
		
		function get_mobile_terms_list($serviceId, $lang)
		{
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("SELECT termsid, terms_title AS title, terms_content AS content, update_date AS date FROM x_service_terms WHERE sid = ".$serviceId." AND lang = '".$lang."' AND is_show = 'Y' ORDER BY update_date DESC");
				
				if(!$resultSet2)
				{
					throw new Exception('Could not query:' . mysql_error());
				}
				
				if($resultSet2->num_rows() > 0)
				{
					$reValue = $resultSet2->result_array();
				}
				
				$resultSet2->next_result(FALSE);
				$resultSet2->free_result();
				
				return $reValue;

			}
			catch (Exception $e)
			{
				var_dump($e);
				$resultSet2 = NULL;
			}
		}
		
		
		
		function get_terms_list($sid) {
		
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("SELECT * FROM x_service_terms WHERE sid = ".$sid." ORDER BY update_date DESC");
				
				//var_dump($resultSet2);					
				
				if(!$resultSet2)
				{
					throw new Exception('Could not query:' . mysql_error());
				}
				
				if($resultSet2->num_rows() > 0)
				{
					$reValue = $resultSet2->result_array();
				}
				
				$resultSet2->next_result(FALSE);
				$resultSet2->free_result();
				
				return $reValue;

			}
			catch (Exception $e)
			{
				var_dump($e);
				$resultSet2 = NULL;
			}		
		}
		
		
		function set_terms($sid, $terms_id, $lang, $type, $title, $content, $is_show) {
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL XSP_SERVICE_TERMS_SET(". $sid .",". $terms_id .",'". $lang ."',". $type .",'". $title ."','". $content ."', '".$is_show."')");
				
				//var_dump($resultSet2);					
				
				if(!$resultSet2)
				{
					throw new Exception('Could not query:' . mysql_error());
				}
				
				if($resultSet2->num_rows() > 0)
				{
					$reValue = $resultSet2->result_array();
				}
				
				$resultSet2->next_result(FALSE);
				$resultSet2->free_result();
				
				return $reValue;

			}
			catch (Exception $e)
			{
				var_dump($e);
				$resultSet2 = NULL;
			}
		}
		
		
		function get_terms($sid, $lang, $termsid) {
			
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("SELECT * FROM x_service_terms WHERE sid = ".$sid." AND lang = '".$lang."' AND termsid = ".$termsid);
				
				//var_dump($resultSet2);					
				
				if(!$resultSet2)
				{
					throw new Exception('Could not query:' . mysql_error());
				}
				
				if($resultSet2->num_rows() > 0)
				{
					$reValue = $resultSet2->result_array();
				}
				
				$resultSet2->next_result(FALSE);
				$resultSet2->free_result();
				
				return $reValue;

			}
			catch (Exception $e)
			{
				var_dump($e);
				$resultSet2 = NULL;
			}
		}
		
		
		function del_terms($termsid) {
			
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL XSP_SERVICE_TERMS_DELETE(".$termsid.")");
				
				//var_dump($resultSet2);					
				
				if(!$resultSet2)
				{
					throw new Exception('Could not query:' . mysql_error());
				}
				
				if($resultSet2->num_rows() > 0)
				{
					$reValue = $resultSet2->result_array();
				}
				
				$resultSet2->next_result(FALSE);
				$resultSet2->free_result();
				
				return $reValue;

			}
			catch (Exception $e)
			{
				var_dump($e);
				$resultSet2 = NULL;
			}
		}
		
		
		
		
		
		
		
			
		
		
		function get_item_list($keyword, $offset, $limit, $xid) {
			
			$query = $this->db->query('CALL xenix_anomychat_db.USP_ADMIN_GET_ITEM_LIST(?, ?, ?, ?);',array($keyword, $offset, $limit, $xid));
		
		
			if (!$query) {
				throw new Exception('Could not query:' . mysql_error());
			}
			if ($query->num_rows() > 0)
			{
				$rows['count'] = $query->result_array();
				
				$query->next_result();
				
				$rows['list'] = $query->result_array();
				
				$query->next_result(FALSE);
				$query->free_result();
				
				return $rows;
			}else{
			
				$query->next_result(FALSE);
				$query->free_result();
				return false;
			}
		}
		
		function set_item($itemid, $name, $desc, $price, $discount, $option) {
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL xenix_anomychat_db.USP_ADMIN_SET_ITEM(".$itemid.", '".$name."', '".$desc."', ".$price.", ".$discount.", '".$option."')");
				
				//var_dump($resultSet2);					
				
				if(!$resultSet2)
				{
					throw new Exception('Could not query:' . mysql_error());
				}
				
				if($resultSet2->num_rows() > 0)
				{
					$reValue = $resultSet2->result_array();
				}
				
				$resultSet2->next_result(FALSE);
				$resultSet2->free_result();
				
				return $reValue;

			}
			catch (Exception $e)
			{
				var_dump($e);
				$resultSet2 = NULL;
			}
		}
		
		
		
		
		
		function get_member_point($member_idx) {
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL xenix_anomychat_db.USP_MEMBER_POINT_GET(".$member_idx.")");
				
				//var_dump($resultSet2);					
				
				if(!$resultSet2)
				{
					throw new Exception('Could not query:' . mysql_error());
				}
				
				if($resultSet2->num_rows() > 0)
				{
					$reValue = $resultSet2->result_array();
				}
				
				$resultSet2->next_result(FALSE);
				$resultSet2->free_result();
				
				return $reValue;

			}
			catch (Exception $e)
			{
				var_dump($e);
				$resultSet2 = NULL;
			}
		}
		
		
		
		function get_message_list($offset, $limit) {
			
			$query = $this->db->query('CALL xenix_anomychat_db.USP_ADMIN_GET_MESSAGE_LIST(?, ?);',array($offset, $limit));
		
		
			if (!$query) {
				throw new Exception('Could not query:' . mysql_error());
			}
			if ($query->num_rows() > 0)
			{
				$rows['count'] = $query->result_array();
				
				$query->next_result();
				
				$rows['list'] = $query->result_array();
				
				$query->next_result(FALSE);
				$query->free_result();
				
				return $rows;
			}else{
			
				$query->next_result(FALSE);
				$query->free_result();
				return false;
			}
		}
		
		
		
		
		function getDeviceVersion($serviceId, $device) {
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL xenix_service_db.USP_ADMIN_GET_DEVICE_VERSION(".$serviceId.", '".$device."')");
				
				//var_dump($resultSet2);					
				
				if(!$resultSet2)
				{
					throw new Exception('Could not query:' . mysql_error());
				}
				
				if($resultSet2->num_rows() > 0)
				{
					$reValue = $resultSet2->result_array();
				}
				
				$resultSet2->next_result(FALSE);
				$resultSet2->free_result();
				
				return $reValue;

			}
			catch (Exception $e)
			{
				var_dump($e);
				$resultSet2 = NULL;
			}
		}
		
		
		
		
		function setDeviceVersion($serviceId, $device, $version, $versionCode, $isForce) {
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL xenix_service_db.USP_ADMIN_SET_DEVICE_VERSION(".$serviceId.", '".$device."', '".$version."', ".$versionCode.", ".$isForce.")");
				
				//var_dump($resultSet2);					
				
				if(!$resultSet2)
				{
					throw new Exception('Could not query:' . mysql_error());
				}
				
				if($resultSet2->num_rows() > 0)
				{
					$reValue = $resultSet2->result_array();
				}
				
				$resultSet2->next_result(FALSE);
				$resultSet2->free_result();
				
				return $reValue;

			}
			catch (Exception $e)
			{
				var_dump($e);
				$resultSet2 = NULL;
			}
		}
			
	}

?>