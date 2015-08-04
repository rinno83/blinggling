<?php 

	class member_db_model extends CI_Model
	{
		
		function __construct() {
			parent::__construct();
		}
		
		/////////////////////////////////////////////////////////////////////////////////////////////////
		//																							   //
		//										계정 정보 등록											   //
		//																							   //
		///////////////////////////////////////////////////////////////////////////////////////////////// 

		function set_member_account($member_key, $password, $name, $birthday, $gender, $profile_image_url) {
			
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL USP_SET_MEMBER_ACCOUNT('".$member_key."', '".$password."', '".$name."', '".$birthday."', '".$gender."', '".$profile_image_url."')");
				
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


		/////////////////////////////////////////////////////////////////////////////////////////////////
		//																							   //
		//											로그인											   //
		//																							   //
		///////////////////////////////////////////////////////////////////////////////////////////////// 

		function task_member_login($member_key, $password) {
			
			try 
			{
				$reValue = array();
				
				$this->db->select('xid, `name`, birthday, gender, profile_image_url AS profileImageUrl');
				$this->db->from('member');
				$this->db->where('`key`', $member_key);
				$this->db->where('`password` = PASSWORD("'.$password.'")');
				
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
			catch (Exception $e)
			{
				log_message('error', 'task_member_login db exception :: ' . $e);
				$query = NULL;
			}

		}


		/////////////////////////////////////////////////////////////////////////////////////////////////
		//																							   //
		//										디바이스 설정											   //
		//																							   //
		///////////////////////////////////////////////////////////////////////////////////////////////// 

		function set_member_device($xid, $device, $uuid, $push_token) {
			
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL USP_SET_MEMBER_DEVICE(".$xid.", '".$device."', '".$uuid."', '".$push_token."')");
				
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


		
		
		
		function get_member_device($xid) {
			
			try 
			{
				$reValue = array();
				
				$this->db->select('md.device, md.push_token, gcm.gcm_service_key');
				$this->db->from('member_device AS md');
				$this->db->from('(SELECT gcm_service_key FROM service_gcm WHERE service_id = 1 AND type = "member") AS gcm');
				$this->db->where('md.xid', $xid);
				
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
			catch (Exception $e)
			{
				log_message('error', 'get_member_device db exception :: ' . $e);
				$query = NULL;
			}
			
		}



		function get_member_point($xid) {
			
			try 
			{
				$reValue = array();
				
				$this->db->select('point');
				$this->db->from('member');
				$this->db->where('xid', $xid);
				
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
			catch (Exception $e)
			{
				log_message('error', 'get_member_point db exception :: ' . $e);
				$query = NULL;
			}
			
		}




		function get_member_alarm($xid) {
			
			try 
			{
				$reValue = array();
				
				$this->db->select('alarm_id AS alarmId, xid, `type`, title, IFNULL(content, "") AS content, UNIX_TIMESTAMP(regist_date) AS registDate', FALSE);
				$this->db->from('alarm');
				$this->db->where('xid', $xid);
				
				$this->db->order_by('regist_date DESC');
				
				$query = $this->db->get();
			
				if(!$query)
				{
					throw new Exception('Could not query:' . mysql_error());
				}
				
				if($query->num_rows() > 0)
				{
					$reValue['list'] = $query->result_array();
				}
				
				$query->next_result(FALSE);
				$query->free_result();
				
				return $reValue;				
			}
			catch (Exception $e)
			{
				log_message('error', 'get_member_alarm db exception :: ' . $e);
				$query = NULL;
			}
			
		}
		
		
		function set_member_alarm($xid, $type, $title, $content) {
			
			try 
			{
				$this->db->set('xid', $xid);
				$this->db->set('type', $type);
				$this->db->set('title', $title);
				$this->db->set('content', $content);
				
				$this->db->insert('alarm');
				
				if($this->db->affected_rows() == -1)
				{
					log_message('error', 'set member alarm db error :: ' . $this->db->_error_message());
				}					
				
				return $this->db->affected_rows();
			}
			catch (Exception $e)
			{
				log_message('error', 'set_member_alarm db exception :: ' . $e);
				$query = NULL;
			}
		}
		
		
		
		
		
		function has_phone($phone) {
			
			try 
			{
				$reValue = array();
				
				$this->db->select('email, phone');
				$this->db->from('member');
				$this->db->where('phone', $phone);
				
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
			catch (Exception $e)
			{
				log_message('error', 'has_phone db exception :: ' . $e);
				$query = NULL;
			}
		}


		function has_cert_phone($phone) {
			
			try 
			{
				$reValue = array();
				
				$this->db->select('phone');
				$this->db->from('certification');
				$this->db->where('phone', $phone);
				
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
			catch (Exception $e)
			{
				log_message('error', 'has_phone db exception :: ' . $e);
				$query = NULL;
			}
		}
		
		
		function set_member() {
			
			try 
			{				
				$data = array();
				
				$sql = $this->db->insert_string('member', $data);

				$this->db->query($sql);
				
				$xid = $this->db->insert_id();
				
				if($this->db->affected_rows() == -1)
				{
					log_message('error', 'set member db error :: ' . $this->db->_error_message());
				}					
				
				return array('result_code' => $this->db->affected_rows(), 'xid' => $xid);
			}
			catch (Exception $e)
			{
				log_message('error', 'set member inquiry db exception :: ' . $e);
				$query = NULL;
			}
		}


		


		function set_member_point($xid, $type, $point) {
			
			try 
			{
				$this->db->set('xid', $xid);
				$this->db->set('type', $type);
				
				if($type == 'use' || $type == 'cancel')
				{
					$this->db->set('point', -$point);
				}
				else
				{
					$this->db->set('point', $point);
				}				
				
				$this->db->insert('point_history');
				
				if($this->db->affected_rows() == -1)
				{
					log_message('error', 'set member point db error :: ' . $this->db->_error_message());
				}					
				
				return $this->db->affected_rows();
			}
			catch (Exception $e)
			{
				log_message('error', 'set_member_point db exception :: ' . $e);
				$query = NULL;
			}
		}


		function login($email, $password) {
			
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL USP_MEMBER_LOGIN('".$email."', '".$password."')");
				
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
		
		
		function is_exists_member($xid) {
			
			try 
			{
				$reValue = array();
				
				$this->db->select('xid');
				$this->db->from('member');
				$this->db->where('xid', $xid);
				
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
			catch (Exception $e)
			{
				log_message('error', 'is_exists_member db exception :: ' . $e);
				$query = NULL;
			}
		}		


		function check_member_email($email) {
			
			try 
			{
				$reValue = array();
				
				$this->db->select('email');
				$this->db->from('member');
				$this->db->where('email', $email);
				
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
			catch (Exception $e)
			{
				log_message('error', 'check_member_email db exception :: ' . $e);
				$query = NULL;
			}
		}		


		function get_member_info($xid) {
			
			try 
			{
				$reValue = array();
				
				$this->db->select('email, name, phone, point, gender, birthday, status');
				$this->db->from('member');
				$this->db->where('xid', $xid);
				
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
			catch (Exception $e)
			{
				log_message('error', 'get_member_info db exception :: ' . $e);
				$query = NULL;
			}
		}		
		
		
		function set_member_password($xid, $email, $newPassword) {
			
			try 
			{
				if($xid == 0) // 로그인 전 -> 비밀번호 찾기로 진입 시
				{
					log_message('info', 'email base2');
					$this->db->set('password', 'PASSWORD("'.$newPassword.'")', FALSE);
					
					$this->db->where('email', $email);
					$this->db->update('member');
				}
				else // 설정 -> 비밀번호 변경으로 진입 시
				{
					$this->db->set('password', 'PASSWORD("'.$newPassword.'")', FALSE);
					
					$this->db->where('xid', $xid);
					$this->db->update('member');
				}
				
				if($this->db->affected_rows() == -1)
				{
					log_message('error', 'set_member_password db error :: ' . $this->db->_error_message());
				}					
				
			}
			catch (Exception $e)
			{
				log_message('error', 'set_member_password db exception :: ' . $e);
				$query = NULL;
			}
		}



		function set_cert_code($phone, $cert_code) {
			
			try 
			{
				$this->db->set('phone', $phone);
				$this->db->set('code', $cert_code);
				$this->db->replace('certification');
				
				if($this->db->affected_rows() == -1)
				{
					log_message('error', 'set_cert_code db error :: ' . $this->db->_error_message());
				}					
				
				return $this->db->affected_rows();				
			}
			catch (Exception $e)
			{
				log_message('error', 'set_cert_code db exception :: ' . $e);
				$query = NULL;
			}
		}



		function set_sms($phone, $send_message) {
			
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL USP_SET_SMS_MESSAGE('".$phone."', '".$send_message."')");
				
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


		function check_cert_code($phone) {
			
			try 
			{
				$reValue = array();
				
				$this->db->select('c.code');
				$this->db->from('certification AS c');
				$this->db->where('c.phone', $phone);
				
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
			catch (Exception $e)
			{
				log_message('error', 'set_cert_code db exception :: ' . $e);
				$query = NULL;
			}
		}
		
		
		function set_member_wish($xid, $menu_id) {
			
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL USP_SET_MEMBER_WISH(".$xid.", ".$menu_id.")");
				
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
		
		
		
		function get_wish_list($xid, $offset, $limit) {
			
			try 
			{
				$return_value = array();
				
				// list
				$this->db->select('m.menu_id AS menuId, m.menu_type AS menuType, name AS menuName, GET_LIKE_COUNT(m.menu_id) AS likeCount, GET_TOTAL_STAR(m.menu_id) AS totalStar, GET_TOTAL_STAR_COUNT(m.menu_id) AS totalStarCount, IS_WISH_MENU(m.menu_id, '.$xid.') AS isWish, IS_EXPIRED(m.menu_id) AS isExpired', FALSE);
				
				$this->db->from('menu AS m');
				
				$this->db->join('member_wish AS mw', 'mw.menu_id = m.menu_id', 'inner');
				
				$this->db->where('mw.xid', $xid); 
				
				$this->db->limit($limit, $offset);
				
				$this->db->order_by("m.sorting", "desc");
				$this->db->order_by("m.regist_date", "desc");
				
				$query = $this->db->get();
				
				if(!$query)
				{
					throw new Exception('Could not query:' . mysql_error());
				}
				
				if($query->num_rows() > 0)
				{
					$return_value['list'] = $query->result_array();
				}
				
				$query->next_result(FALSE);
				$query->free_result();
				
				return $return_value;
			}
			catch (Exception $e)
			{
				log_message('error', 'set member inquiry db exception :: ' . $e);
				$query = NULL;
			}
		}
		
		
		function get_member_comment($xid, $menu_id) {
			try 
			{
				$return_value = array();
				
				// list
				$this->db->select('comment_id AS commentId, star_point AS star, comment AS comment', FALSE);
				
				$this->db->from('menu_comment');
				
				$this->db->where('menu_id', $menu_id); 
				$this->db->where('xid', $xid); 
				$this->db->where('status', 'normal'); 
				
				$query = $this->db->get();
				
				if(!$query)
				{
					throw new Exception('Could not query:' . mysql_error());
				}
				
				if($query->num_rows() > 0)
				{
					$return_value = $query->result_array();
				}
				
				$query->next_result(FALSE);
				$query->free_result();
				
				return $return_value;
			}
			catch (Exception $e)
			{
				log_message('error', 'set member inquiry db exception :: ' . $e);
				$query = NULL;
			}
		}



		function delete_member_comment($comment_id, $xid, $menu_id) {
			try 
			{
				$this->db->where('comment_id', $comment_id);
				$this->db->where('xid', $xid);
				$this->db->where('menu_id', $menu_id);
				$this->db->delete('menu_comment');
				
				if($this->db->affected_rows() == -1)
				{
					log_message('error', 'set member db error :: ' . $this->db->_error_message());
				}					
				
				return $this->db->affected_rows();
			}
			catch (Exception $e)
			{
				log_message('error', 'set member inquiry db exception :: ' . $e);
				$query = NULL;
			}
		}
		
			
	}

?>