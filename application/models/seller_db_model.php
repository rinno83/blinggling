<?php 

	class seller_db_model extends CI_Model
	{
		
		function __construct() {
			parent::__construct();
		}
		
		function get_seller_device($seller_id) {
			
			try 
			{
				$reValue = array();
				
				$this->db->select('device, push_token, gcm_service_key');
				$this->db->from('seller_device');
				$this->db->from('(SELECT gcm_service_key FROM service_gcm WHERE service_id = 1 AND type = "seller") AS gcm');
				$this->db->where('seller_id', $seller_id);
				
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
				log_message('error', 'get_seller_device db exception :: ' . $e);
				$query = NULL;
			}
			
		}
		
		
		function set_seller_device($seller_id, $uuid, $device, $push_token) {
			
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL USP_SET_SELLER_DEVICE(".$seller_id.", '".$uuid."', '".$device."', '".$push_token."')");
				
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
		
		
		function set_account($order_id) {
			
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL USP_SET_ACCOUNT(".$order_id.")");
				
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
		
		
		function get_seller_info($seller_id) {
			
			try 
			{
				$return_value = array();
				
				// list
				$this->db->select('s.seller_id AS sellerId, s.name AS sellerName, s.seller_code As sellerCode, s.commission', FALSE);
				
				$this->db->from('seller AS s');
				
				$this->db->where('s.seller_id', $seller_id);
				
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
				log_message('error', 'get_seller_by_seller_ratio db exception :: ' . $e);
				$query = NULL;
			}
		}



		function set_seller_swap($down_seller_id, $up_seller_id) {
			
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL USP_SET_SELLER_SWAP(".$down_seller_id.", ".$up_seller_id.")");
				
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


		function set_seller_ratio_status($seller_id) {
			
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL USP_SET_SELLER_RATIO_STATUS(".$seller_id.")");
				
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
		
		
		function get_seller_by_seller_ratio() {
			
			try 
			{
				$return_value = array();
				
				// list
				$this->db->select('seller_id', FALSE);
				
				$this->db->from('seller_ratio');
				
				$this->db->where('status', 0);
				
				$this->db->order_by('ratio_id', 'ASC');
				
				$this->db->limit(1);
				
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
				log_message('error', 'get_seller_by_seller_ratio db exception :: ' . $e);
				$query = NULL;
			}
		}



		function get_menu_ratio_by_order($main_menu_id) {
			
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL USP_GET_MENU_RATIO_BY_ORDER(".$main_menu_id.")");
				
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


		function set_seller_queue() {
			
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL USP_SET_SELLER_QUEUE()");
				
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



		function get_seller_ratio_by_menu($main_menu_id) {
			
			try 
			{
				$return_value = array();
				
				// list
				$this->db->select('seller_id, ratio', FALSE);
				
				$this->db->from('menu_seller');
				
				$this->db->where('menu_id', $main_menu_id);
				
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
				log_message('error', 'get_seller_by_seller_ratio db exception :: ' . $e);
				$query = NULL;
			}
		}

		
		function is_exists_seller($seller_id) {
			
			try 
			{
				$return_value = array();
				
				// list
				$this->db->select('seller_id', FALSE);
				
				$this->db->from('seller');
				
				$this->db->where('seller_id', $seller_id);
				
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
				log_message('error', 'get seller id db exception :: ' . $e);
				$query = NULL;
			}
		}


		
		function get_order_list($seller_id, $sell_status, $offset, $limit) {
			try 
			{
				$return_value = array();
				
				// list
				$this->db->select('o.order_id AS orderId, order_code AS orderCode, UNIX_TIMESTAMP(STR_TO_DATE(order_datetime, "%Y-%m-%d %H:%i:%s")) AS orderDateTime, m.name AS customerPhone, memo, work_status AS workStatus, sell_status AS sellStatus, rt.name AS receiveTypeName, IFNULL(s.name, "") AS storeName, order_status AS orderStatus', FALSE);
				
				$this->db->from('order AS o');
				$this->db->join('receive_type AS rt', 'rt.receive_id = o.receive_id', 'inner');
				$this->db->join('member AS m', 'm.xid = o.xid', 'inner');
				$this->db->join('order_store AS os', 'os.order_id = o.order_id', 'left');
				$this->db->join('store AS s', 's.store_id = os.store_id', 'left');
				
				$this->db->where('seller_id', $seller_id); 
				$this->db->where('sell_status', $sell_status); 
				$this->db->where('order_status', 'finish'); 
				
				//$this->db->limit($limit, $offset);
				
				$this->db->order_by('order_datetime ASC, work_status ASC');
				
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
				log_message('error', 'get order list db exception :: ' . $e);
				$query = NULL;
			}
		}



		function get_menu_sell_current_month_by_menu($seller_id) {
			
			try 
			{
				$return_value = array();
				
				// list
				$this->db->select('SUM(menu_count) AS sellCount, m.name AS menuName', FALSE);
				
				$this->db->from('order_menu AS om');
				$this->db->join('order AS o', 'o.order_id = om.order_id', 'inner');
				$this->db->join('menu AS m', 'm.menu_id = om.menu_id', 'inner');
				
				$this->db->where('o.regist_date >= DATE_FORMAT(FROM_UNIXTIME(UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH)), "%Y-%m-%d %H:%i:%s")'); 
				$this->db->where('o.regist_date <  DATE_FORMAT(FROM_UNIXTIME(UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY)), "%Y-%m-%d %H:%i:%s")'); 
				$this->db->where('o.order_status', 'finish'); 
				$this->db->where('o.seller_id', $seller_id); 
				
				$this->db->group_by('om.menu_id'); 
				
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
				log_message('error', 'get_menu_sell_current_month_by_menu db exception :: ' . $e);
				$query = NULL;
			}
		}
		
		
		function get_menu_sell_current_month($seller_id) {
			
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL USP_GET_SELL_CURRENT_MONTH(".$seller_id.")");
				
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



		function get_sell_day($seller_id, $offset, $limit) {
			
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL USP_GET_SELL_DAY(".$seller_id.", ".$offset.", ".$limit.")");
				
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



		function get_sell_month($seller_id, $offset, $limit) {
			
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL USP_GET_SELL_MONTH(".$seller_id.", ".$offset.", ".$limit.")");
				
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



		function get_sell_all($seller_id) {
			
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL USP_GET_SELL_ALL(".$seller_id.")");
				
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


		function get_sell_account($seller_id, $offset, $limit) {
			
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL USP_GET_ACCOUNT(".$seller_id.")");
				
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


		function get_sell_account_order_count($account_dt, $seller_id) {
			
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL USP_GET_ACCOUNT_ORDER_COUNT(".$seller_id.", '".$account_dt."')");
				
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
		
		
		
		
		function get_order_menu($order_id) {
			
			try 
			{
				$return_value = array();
				// list
				$this->db->select('om.menu_id AS menuId, m.name AS menuName, om.is_represent AS isRepresent, menu_count AS menuCount, add_person AS addPerson, IFNULL(mp.sale, 0) AS sale, IFNULL(mp.sale_price, 0) AS salePrice, IFNULL(mp.is_bonus, 0) AS isBonus, IFNULL(mp.is_new, 0) AS isNew', FALSE);
				
				$this->db->from('order_menu AS om');
				
				$this->db->join('menu AS m', 'm.menu_id = om.menu_id', 'inner');
				$this->db->join('menu_pick AS mp', 'mp.menu_id = om.menu_id', 'left');
				
				$this->db->where('om.order_id', $order_id);
				
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
		
		
		function get_order_topping($order_id) {
			
			try 
			{
				$return_value = array();
				// list
				$this->db->select('ot.topping_id AS toppingId, buy_count AS toppingCount, name AS toppingName', FALSE);
				
				$this->db->from('order_topping AS ot');
				
				$this->db->join('topping AS t', 't.topping_id = ot.topping_id', 'inner');
				
				$this->db->where('ot.order_id', $order_id);
				
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



		function get_seller_id($seller_code) {
			
			try 
			{
				$return_value = array();
				
				// list
				$this->db->select('seller_id, name', FALSE);
				
				$this->db->from('seller');
				
				$this->db->where('seller_code', $seller_code); 
				
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
				log_message('error', 'get seller id db exception :: ' . $e);
				$query = NULL;
			}
		}



		function set_work_status($seller_id, $order_id, $type, $status) {
			
			try 
			{
				$this->db->set($type.'_status', $status);
				$this->db->set('update_date', 'CURRENT_TIMESTAMP', FALSE);
				$this->db->where('order_id', $order_id);
				
				$this->db->update('order');
				
				if($this->db->affected_rows() == -1)
				{
					log_message('error', 'set work status db error :: ' . $this->db->_error_message());
				}					
				
				return $this->db->affected_rows();
			}
			catch (Exception $e)
			{
				log_message('error', 'set work status db exception :: ' . $e);
				$query = NULL;
			}
		}
					
	}

?>