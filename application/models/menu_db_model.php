<?php 

	class menu_db_model extends CI_Model
	{
		
		function __construct() {
			parent::__construct();
		}
		
		
		function get_menu_search($xid, $search_person, $search_price, $white, $red, $season, $wild, $crab, $offset, $limit) {

			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL USP_GET_MENU_SEARCH(".$xid.", ".$search_person.", ".$search_price.", '".$white."', '".$red."', '".$season."', '".$wild."', '".$crab."', ".$offset.", ".$limit.")");
				
				if(!$resultSet2)
				{
					throw new Exception('Could not query:' . mysql_error());
				}
				
				if($resultSet2->num_rows() > 0)
				{
					$reValue['list'] = $resultSet2->result_array();
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
		
		
		
		function get_menu_list_for_order($menu_id) {
			
			try 
			{
				$return_value = array();
				
				// list
				$this->db->select('m.menu_id As menuId, m.menu_type As menuType, m.name AS menuName, m.base_price AS basePrice, m.link, IFNULL(mt.buy_point, 0) AS menuPoint, IFNULL(mp.sale, 0) AS sale, IFNULL(mp.sale_price, 0) AS salePrice, IFNULL(mp.is_new, 0) AS isNew, IFNULL(mp.is_bonus, 0) AS isBonus', FALSE);
				
				$this->db->from('menu AS m');
				$this->db->join('menu_pick AS mp', 'mp.menu_id = m.menu_id', 'left');
				$this->db->join('menu_topping AS mt', 'mt.menu_id = m.menu_id', 'left');
				$this->db->join('(SELECT menu_id, menu_image_url AS menuImageUrl FROM menu_images WHERE is_represent = 1 GROUP BY menu_id) AS mi', 'mi.menu_id = m.menu_id', 'inner');
				
				$this->db->where('is_show', 1);
				$this->db->where('m.menu_type <> "delivery"');
				$this->db->where('m.menu_id NOT IN(SELECT menu_id FROM menu_pick WHERE NOT UNIX_TIMESTAMP(STR_TO_DATE(CURRENT_TIMESTAMP, "%Y-%m-%d %H:%i:%s")) BETWEEN UNIX_TIMESTAMP(STR_TO_DATE(start_date, "%Y-%m-%d %H:%i:%s")) AND UNIX_TIMESTAMP(STR_TO_DATE(end_date, "%Y-%m-%d %H:%i:%s")))');
/*
				$this->db->where('menu_type <> "pick"');
				$this->db->where('menu_type <> "delivery"');
				$this->db->where('is_show', 1);
*/
				$this->db->where('m.menu_id <> ' . $menu_id);
				
				$this->db->order_by('m.name', 'ASC');
				
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
				log_message('error', 'get_menu_list_for_order db exception :: ' . $e);
				$query = NULL;
			}
		}


		function get_delivery_menu_list_for_order() {
			
			try 
			{
				$return_value = array();
				
				// list
				$this->db->select('m.menu_id As menuId, m.menu_type As menuType, m.name AS menuName, m.base_price AS basePrice, price_type AS priceType, price_per_person AS pricePerPerson', FALSE);
				
				$this->db->from('menu AS m');
				
				$this->db->where('is_show', 1);
				$this->db->where('m.menu_type = "delivery"');
				
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
				log_message('error', 'get_delivery_menu_list_for_order db exception :: ' . $e);
				$query = NULL;
			}
		}



		function get_store_list_by_menu($menu_id) {
			
			try 
			{
				$return_value = array();
				
				// list
				$this->db->select('s.store_id AS storeId, s.name, s.is_show AS isShow', FALSE);
				
				$this->db->from('menu_store AS ms');
				$this->db->join('store AS s', 's.store_id = ms.store_id', 'inner');
				
				//$this->db->where('is_show', 1);
				$this->db->where('ms.menu_id', $menu_id);
				
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
				log_message('error', 'get_store_list_by_menu db exception :: ' . $e);
				$query = NULL;
			}
		}
		
		
		function get_menu_service($menu_id, $is_required) {
			
			try 
			{
				$return_value = array();
				
				// list
				$this->db->select('name AS menuServiceName', FALSE);
				
				$this->db->from('menu_service');
				
				$this->db->where('is_required', $is_required); 
				$this->db->where('menu_id', $menu_id); 
				
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



		function get_menu_topping() {
			
			try 
			{
				$return_value = array();
				
				// list
				$this->db->select('topping_id AS toppingId, name AS toppingName, price AS toppingPrice', FALSE);
				
				$this->db->from('topping');
				
				$this->db->where('is_show', 1); 
				
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
		
		
		function get_menu_list($xid, $menu_type, $prefer, $offset, $limit) {
			
			try 
			{
				$return_value = array();
				
				// list
				$this->db->select('m.menu_id AS menuId, m.name AS menuName, m.menu_summary AS menuSummary, m.desc AS description, m.base_price AS price, GET_COMMENT_COUNT(m.menu_id) AS commentCount, GET_LIKE_COUNT(m.menu_id) AS likeCount, IS_WISH_MENU(m.menu_id, '.$xid.') AS isWish, mi.menuImageUrl', FALSE);
				
				$this->db->from('menu AS m');
				
				$this->db->join('(SELECT menu_id, menu_image_url AS menuImageUrl FROM menu_images WHERE is_represent = 1 GROUP BY menu_id) AS mi', 'mi.menu_id = m.menu_id', 'inner');
				
				$this->db->where('m.menu_type <> "pick"');
				$this->db->where('m.menu_type <> "delivery"');
				$this->db->where('m.is_show', 1);
				
				if($prefer)
				{
					$this->db->where('m.menu_id IN (SELECT mp.menu_id FROM menu_prefer AS mp INNER JOIN prefer AS p ON p.prefer_id = mp.prefer_id WHERE p.type = "'.$prefer.'")');
				} 
				
				//$this->db->limit($limit, $offset);
				
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
				log_message('error', 'get_menu_list db exception :: ' . $e);
				$query = NULL;
			}
		}

		
		
		function get_menu_prefer_list($xid, $offset, $limit) {
			
			try 
			{
				$return_value = array();
				
				// list
				$this->db->select('prefer_id AS preferId, `type` AS preferType, `name` AS preferName', FALSE);
				
				$this->db->from('prefer');
				
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
				log_message('error', 'get_menu_list db exception :: ' . $e);
				$query = NULL;
			}
		}
		
		
		
		
		
		function get_pick_list($xid, $menu_type, $offset, $limit) {
			
			try 
			{
				$return_value = array();
				
				// list
				$this->db->select('m.menu_id AS menuId, m.name AS menuName, m.menu_summary AS menuSummary, m.desc AS description, m.base_price AS price, GET_COMMENT_COUNT(m.menu_id) AS commentCount, GET_LIKE_COUNT(m.menu_id) AS likeCount, IS_WISH_MENU(m.menu_id, '.$xid.') AS isWish, mi.menuImageUrl, mp.sale, mp.sale_price AS salePrice, mp.is_new AS isNew, mp.is_bonus AS isBonus', FALSE);
				
				$this->db->from('menu AS m');
				
				$this->db->join('(SELECT menu_id, menu_image_url AS menuImageUrl FROM menu_images WHERE is_represent = 1 GROUP BY menu_id) AS mi', 'mi.menu_id = m.menu_id', 'inner');
				$this->db->join('menu_pick AS mp', 'mp.menu_id = m.menu_id', 'inner');
				
				$this->db->where('m.menu_type', $menu_type); 
				$this->db->where('m.is_show', 1); 
				$this->db->where("UNIX_TIMESTAMP(STR_TO_DATE(CURRENT_TIMESTAMP, '%Y-%m-%d %H:%i:%s')) BETWEEN UNIX_TIMESTAMP(STR_TO_DATE(mp.start_date, '%Y-%m-%d %H:%i:%s')) AND UNIX_TIMESTAMP(STR_TO_DATE(mp.end_date, '%Y-%m-%d %H:%i:%s'))", NULL, FALSE); 
				
				//$this->db->limit($limit, $offset);
				
				$this->db->order_by("m.sorting", "asc");
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
		
		
		
		function get_menu($xid, $menu_id) {
			
			try 
			{
				$return_value = null;
				
				// list
				$this->db->select('m.menu_id AS menuId, m.name AS menuName, m.menu_type AS menuType, m.menu_summary AS menuSummary, m.base_price AS basePrice, m.price_type AS priceType, m.price_per_person AS pricePerPerson, m.desc AS description, m.link, GET_LIKE_COUNT(m.menu_id) AS likeCount, IS_WISH_MENU(m.menu_id, '.$xid.') AS isWish, GET_TOTAL_STAR(m.menu_id) AS totalStar, GET_COMMENT_COUNT(m.menu_id) AS commentCount, IFNULL(mp.sale, 0) AS sale, IFNULL(mp.sale_price, 0) AS salePrice, IFNULL(mp.is_new, 0) AS isNew, IFNULL(mp.is_bonus, 0) AS isBonus, IFNULL(mt.buy_point, 0) AS buyPoint', FALSE);
				
				$this->db->from('menu AS m');
				$this->db->join('menu_pick AS mp', 'mp.menu_id = m.menu_id', 'left');
				$this->db->join('menu_topping AS mt', 'mt.menu_id = m.menu_id', 'left');
				
				$this->db->where('m.menu_id', $menu_id); 
				
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
		
		
		function get_service_menu($menu_id) {
			
			try 
			{
				$return_value = null;
				
				// list
				$this->db->select('ms.name, ms.is_required AS isRequired', FALSE);
				
				$this->db->from('menu_service AS ms');
				
				$this->db->where('ms.menu_id', $menu_id); 
				
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



		function get_menu_component($menu_id) {
			
			try 
			{
				$return_value = null;
				
				// list
				$this->db->select('mc.name, mc.image_url AS imageUrl', FALSE);
				
				$this->db->from('menu_component_map AS mcm');
				
				$this->db->join('menu_component AS mc', 'mcm.component_id = mc.component_id', 'inner');
				
				$this->db->where('mcm.menu_id', $menu_id); 
				
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



		function get_menu_image_list($menu_id) {
			
			try 
			{
				$return_value = array();
				// list
				$this->db->select('menu_image_url', FALSE);
				
				$this->db->from('menu_images');
				
				$this->db->where('menu_id', $menu_id);
				
				$this->db->order_by('sorting', 'DESC'); 
				
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
		
		
		
		function get_menu_image($menu_id) {
			
			try 
			{
				$return_value = array();
				// list
				$this->db->select('menu_image_url', FALSE);
				
				$this->db->from('menu_images');
				
				$this->db->where('menu_id', $menu_id);
				
				$this->db->limit(1);
				
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



		function get_menu_comment($menu_id, $offset, $limit) {
			
			try 
			{
				$return_value = array();
				// list
				$this->db->select('mc.xid, mc.comment_id As commentId, m.phone, mc.star_point As star, mc.comment AS comment, mc.sorting, mc.status, mc.regist_date AS registDate', FALSE);
				
				$this->db->from('menu_comment AS mc');
				
				$this->db->join('member AS m', 'm.xid = mc.xid', 'inner');
				
				$this->db->where('mc.menu_id', $menu_id);
				
				//$this->db->limit($limit, $offset);
				
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
		
		
		function set_menu_view_count($menu_id) {
			
			try 
			{				
				$this->db->set('view_count', 'view_count + 1', FALSE);
					
				$this->db->where('menu_id', $menu_id);
				$this->db->update('menu');
				
				if($this->db->affected_rows() == -1)
				{
					log_message('error', 'set_menu_view_count db error :: ' . $this->db->_error_message());
				}					
				
				return $this->db->affected_rows();
			}
			catch (Exception $e)
			{
				log_message('error', 'set_menu_view_count db exception :: ' . $e);
				$query = NULL;
			}
		}


		function set_order_pay_type($order_id, $pay_type) {
			
			try 
			{				
				$this->db->set('pay_type', $pay_type);
					
				$this->db->where('order_id', $order_id);
				$this->db->update('order');
				
				if($this->db->affected_rows() == -1)
				{
					log_message('error', 'set_menu_view_count db error :: ' . $this->db->_error_message());
				}					
				
				return $this->db->affected_rows();
			}
			catch (Exception $e)
			{
				log_message('error', 'set_order_pay_type db exception :: ' . $e);
				$query = NULL;
			}
		}


		function set_order_pg($order_id, $pay_type) {
			
			try 
			{	
				$this->db->set('o.pg_unit', 'sp.unit', FALSE);
				$this->db->set('o.pg_fee', 'sp.fee', FALSE);
				
				$this->db->where('o.order_id', $order_id);
				$this->db->where('o.pay_type', $pay_type);
				
				$this->db->update('order AS o INNER JOIN service_pg AS sp ON sp.type = o.pay_type');
				
				
				if($this->db->affected_rows() == -1)
				{
					log_message('error', 'set_order_pg db error :: ' . $this->db->_error_message());
				}					
				
				return $this->db->affected_rows();
			}
			catch (Exception $e)
			{
				log_message('error', 'set_order_pg db exception :: ' . $e);
				$query = NULL;
			}
		}


		function set_menu_share($xid, $menu_id, $order_id) {
			
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL USP_SET_MENU_SHARE(".$xid.", ".$menu_id.", ".$order_id.")");
				
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



		function set_menu_comment($comment_id, $menu_id, $xid, $star, $comment) {
			
			try 
			{
				$reValue = array();
				$resultSet2 = $this->db->query("CALL USP_SET_MENU_COMMENT(".$xid.", ".$menu_id.", ".$comment_id.", ".$star.", '".$comment."')");
				
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



		function set_order($xid, $seller_id, $order_datetime, $customer_address, $customer_phone, $receive_type, $total_price, $total_point, $memo) {
			
			try 
			{
				$this->db->set('xid', $xid);
				$this->db->set('seller_id', $seller_id);
				//$this->db->set('order_code', 'UPPER(LEFT(UUID(), 8))', FALSE);
				$this->db->set('order_code', 'ROUND(UNIX_TIMESTAMP() + (RAND() * 10000))', FALSE);
				$this->db->set('order_datetime', 'DATE_FORMAT(FROM_UNIXTIME('.$order_datetime.'), "%Y-%m-%d %H:%i:%s")', FALSE);
				$this->db->set('customer_address', $customer_address);
				$this->db->set('customer_phone', $customer_phone);
				$this->db->set('receive_id', $receive_type);
				$this->db->set('total_order_price', $total_price);
				$this->db->set('total_order_point', $total_point);
				$this->db->set('memo', $memo);
				
				$this->db->insert('order');
				
				$order_id = $this->db->insert_id();
				
				if($this->db->affected_rows() == -1)
				{
					log_message('error', 'set order db error :: ' . $this->db->_error_message());
				}					
				
				return array('result_code' => $this->db->affected_rows(), 'order_id' => $order_id);
			}
			catch (Exception $e)
			{
				log_message('error', 'set order db exception :: ' . $e);
				$query = NULL;
			}
		}


		function set_order_link($order_code, $url) {
			
			try 
			{
				$this->db->set('link', $url . $order_code);
				
				$this->db->where('order_code', $order_code);
				
				$this->db->update('order');
				
				if($this->db->affected_rows() == -1)
				{
					log_message('error', 'set_order_link db error :: ' . $this->db->_error_message());
				}					
				
				return $this->db->affected_rows();
			}
			catch (Exception $e)
			{
				log_message('error', 'set_order_link db exception :: ' . $e);
				$query = NULL;
			}
		}


		function set_order_menu($order_id, $menu_id, $menu_type, $menu_count, $add_person, $use_point, $is_represent) {
			
			try 
			{				
				$data = array(
					'order_id' => $order_id,
					'menu_id' => $menu_id,
					'menu_count' => $menu_count,
					'add_person' => $add_person,
					'use_point' => $use_point,
					'is_represent' => $is_represent
				);
				
				$sql = $this->db->insert_string('order_menu', $data);

				$this->db->query($sql);
				
				if($this->db->affected_rows() == -1)
				{
					log_message('error', 'set order menu db error :: ' . $this->db->_error_message());
				}					
				
				return $this->db->affected_rows();	
			}
			catch (Exception $e)
			{
				log_message('error', 'set order menu db exception :: ' . $e);
				$query = NULL;
			}
		}
		
		
		function set_order_topping($order_id, $topping_id, $topping_count, $topping_price, $topping_point) {
			
			try 
			{				
				$data = array(
					'order_id' => $order_id,
					'topping_id' => $topping_id,
					'topping_count' => $topping_count,
					'price' => $topping_price,
					'use_point' => $topping_point
				);
				
				$sql = $this->db->insert_string('order_topping', $data);

				$this->db->query($sql);
				
				if($this->db->affected_rows() == -1)
				{
					log_message('error', 'set order menu db error :: ' . $this->db->_error_message());
				}					
				
				return $this->db->affected_rows();	
			}
			catch (Exception $e)
			{
				log_message('error', 'set order menu db exception :: ' . $e);
				$query = NULL;
			}
		}


		
		function set_order_summary($order_id, $nrj_charge, $seller_charge,  $use_point, $use_cash) {
			
			try 
			{				
				$data = array(
					'order_id' => $order_id,
					'nrj_charge' => $nrj_charge,
					'seller_charge' => $seller_charge,
					'use_point' => $use_point,
					'use_cash' => $use_cash
				);
				
				$sql = $this->db->insert_string('summary', $data);

				$this->db->query($sql);
				
				if($this->db->affected_rows() == -1)
				{
					log_message('error', 'set order menu db error :: ' . $this->db->_error_message());
				}					
				
				return $this->db->affected_rows();	
			}
			catch (Exception $e)
			{
				log_message('error', 'set order menu db exception :: ' . $e);
				$query = NULL;
			}
		}
		
		
		function set_order_store($order_id, $store_id) {
			
			try 
			{				
				$data = array(
					'order_id' => $order_id,
					'store_id' => $store_id
				);
				
				$sql = $this->db->insert_string('order_store', $data);

				$this->db->query($sql);
				
				if($this->db->affected_rows() == -1)
				{
					log_message('error', 'set order store db error :: ' . $this->db->_error_message());
				}					
				
				return $this->db->affected_rows();	
			}
			catch (Exception $e)
			{
				log_message('error', 'set order store db exception :: ' . $e);
				$query = NULL;
			}
		}
		
		
		function set_order_status($order_id, $status) {
			
			try 
			{				
				$this->db->set('order_status', $status);
					
				$this->db->where('order_id', $order_id);
				$this->db->update('order');
				
				if($this->db->affected_rows() == -1)
				{
					log_message('error', 'set_order_status db error :: ' . $this->db->_error_message());
				}					
				
				return $this->db->affected_rows();
			}
			catch (Exception $e)
			{
				log_message('error', 'set_order_status db exception :: ' . $e);
				$query = NULL;
			}
		}

	
	
		function get_receive_type() {
			
			try 
			{
				$return_value = array();
				// list
				$this->db->select('receive_id As receiveId, name, is_show AS isOpen', FALSE);
				
				$this->db->from('receive_type');
				
				$this->db->where('is_show', 1);
				
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
				log_message('error', 'get_receive_type db exception :: ' . $e);
				$query = NULL;
			}
		}	



		function get_receive_type_name($receive_type) {
			
			try 
			{
				$return_value = array();
				// list
				$this->db->select('name', FALSE);
				
				$this->db->from('receive_type');
				
				$this->db->where('receive_id', $receive_type);
				
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
				log_message('error', 'get_receive_type_name db exception :: ' . $e);
				$query = NULL;
			}
		}	



		function get_order_list($xid) {
			
			try 
			{
				$return_value = array();
				// list
				$this->db->select('o.order_id AS orderId, order_code AS orderCode, o.seller_id AS sellerId, IFNULL(s.name, "") AS sellerName, UNIX_TIMESTAMP(STR_TO_DATE(order_datetime, "%Y-%m-%d %H:%i:%s")) AS orderDateTime, o.pay_type AS payType, IFNULL(UNIX_TIMESTAMP(o.vbank_update_date), "") AS vbankUpdateDate, o.work_status AS workStatus, o.sell_status AS sellStatus, rt.name AS receiveTypeName, IFNULL(store.name, "") AS storeName, total_order_price AS orderPrice, total_order_point AS orderPoint, order_status AS orderStatus, IF(o.seller_id = 0, "", o.link) AS link, IF(TIMEDIFF(o.order_datetime, NOW()) < "02:00:00", "", IFNULL(tid, ""))  AS tid, UNIX_TIMESTAMP(STR_TO_DATE(o.regist_date, "%Y-%m-%d %H:%i:%s")) AS registDate', FALSE);
				
				$this->db->from('order AS o');
				$this->db->join('seller AS s', 's.seller_id = o.seller_id', 'left');
				$this->db->join('receive_type AS rt', 'rt.receive_id = o.receive_id', 'inner');
				$this->db->join('order_store AS os', 'os.order_id = o.order_id', 'left');
				$this->db->join('store AS store', 'store.store_id = os.store_id', 'left');
				
				$this->db->where('xid', $xid);
				$this->db->where('o.order_status <> "temp"');
				
				$this->db->order_by('o.order_datetime', 'DESC');
				$this->db->order_by('o.regist_date', 'DESC');
				
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
				log_message('error', 'get_order_list db exception :: ' . $e);
				$query = NULL;
			}
		}	



		function calc_order_menu_price($menu_id, $menu_count) {
			
			try 
			{
				$return_value = array();
				// list
				$this->db->select('o.order_id AS orderId, order_code AS orderCode, o.seller_id AS sellerId, IFNULL(s.name, "") AS sellerName, UNIX_TIMESTAMP(STR_TO_DATE(order_datetime, "%Y-%m-%d %H:%i:%s")) AS orderDateTime, o.work_status AS workStatus, o.sell_status AS sellStatus, rt.name AS receiveTypeName, IFNULL(store.name, "") AS storeName, total_order_price AS orderPrice, total_order_point AS orderPoint, order_status AS orderStatus, IF(o.seller_id = 0, "", o.link) AS link, IF(TIMEDIFF(o.order_datetime, NOW()) < "02:00:00", "", IFNULL(tid, ""))  AS tid, UNIX_TIMESTAMP(STR_TO_DATE(o.regist_date, "%Y-%m-%d %H:%i:%s")) AS registDate', FALSE);
				
				$this->db->from('order AS o');
				$this->db->join('seller AS s', 's.seller_id = o.seller_id', 'left');
				$this->db->join('receive_type AS rt', 'rt.receive_id = o.receive_id', 'inner');
				$this->db->join('order_store AS os', 'os.order_id = o.order_id', 'left');
				$this->db->join('store AS store', 'store.store_id = os.store_id', 'left');
				
				$this->db->where('xid', $xid);
				$this->db->where('o.order_status <> "temp"');
				
				$this->db->order_by('sell_status', 'ASC');
				$this->db->order_by('o.regist_date', 'DESC');
				
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
				log_message('error', 'calc_order_menu_price db exception :: ' . $e);
				$query = NULL;
			}
		}	
		
		
		
		function get_order_menu($order_id, $xid) {
			
			try 
			{
				$return_value = array();
				// list
				$this->db->select('om.menu_id AS menuId, m.menu_type AS menuType, m.name AS menuName, om.menu_count AS menuCount, om.add_person AS addPerson, om.use_point AS usePoint, m.link, om.is_represent AS isRepresent, IS_WISH_MENU(om.menu_id, '.$xid.') AS isWish, IS_COMMENT_MENU('.$xid.', om.menu_id) AS isComment', FALSE);
				
				$this->db->from('order_menu AS om');
				
				$this->db->join('menu AS m', 'm.menu_id = om.menu_id', 'inner');
				
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
				log_message('error', 'get_order db exception :: ' . $e);
				$query = NULL;
			}
		}		


		
		function get_order($order_id) {
			
			try 
			{
				$return_value = array();
				// list
				$this->db->select('order_id AS orderId, order_code AS orderCode, o.seller_id AS sellerId, order_datetime AS orderDateTime, total_order_price AS totalPrice, total_order_point AS totalPoint, order_status AS orderStatus, o.work_status, o.sell_status, o.customer_address AS customerAddress, o.customer_phone AS customerPhone, o.receive_id AS receiveId, o.memo, UNIX_TIMESTAMP(STR_TO_DATE(o.regist_date, "%Y-%m-%d %H:%i:%s")) AS registDate', FALSE);
				
				$this->db->from('order AS o');
				//$this->db->join('seller AS s', 's.seller_id = o.seller_id', 'inner');
				
				$this->db->where('o.order_id', $order_id);
				
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
				log_message('error', 'get_order db exception :: ' . $e);
				$query = NULL;
			}
		}		



		function get_order_by_order_code($order_code) {
			
			try 
			{
				$return_value = array();
				// list
				$this->db->select('order_id AS orderId, order_code AS orderCode, m.email AS customerEmail, m.name AS customerName, o.seller_id AS sellerId, UNIX_TIMESTAMP(STR_TO_DATE(o.order_datetime, "%Y-%m-%d %H:%i:%s")) AS orderDateTime, total_order_price AS totalPrice, total_order_point AS totalPoint, order_status AS orderStatus, o.customer_address AS customerAddress, o.customer_phone AS customerPhone, o.receive_id AS receiveId, rt.name AS receiveTypeName, o.memo, UNIX_TIMESTAMP(STR_TO_DATE(o.regist_date, "%Y-%m-%d %H:%i:%s")) AS registDate', FALSE);
				
				$this->db->from('order AS o');
				$this->db->join('member AS m', 'm.xid = o.xid', 'inner');
				$this->db->join('receive_type AS rt', 'rt.receive_id = o.receive_id', 'inner');
				
				$this->db->where('o.order_code', $order_code);
				
				
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
				log_message('error', 'get_order_by_order_code db exception :: ' . $e);
				$query = NULL;
			}
		}		



		function get_store_by_order_code($order_code) {
			
			try 
			{
				$return_value = array();
				// list
				$this->db->select('s.name, s.contact, s.address', FALSE);
				
				$this->db->from('order AS o');
				$this->db->join('order_store AS os', 'os.order_id = o.order_id', 'inner');
				$this->db->join('store AS s', 's.store_id = os.store_id', 'inner');
				
				$this->db->where('o.order_code', $order_code);
				
				
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
				log_message('error', 'get_store_by_order_code db exception :: ' . $e);
				$query = NULL;
			}
		}		



		function get_menu_by_order($order_id) {
			
			try 
			{
				$return_value = array();
				// list
				$this->db->select('m.menu_id AS menuId, m.menu_type AS menuType, m.name AS menuName, om.menu_count AS menuCount, om.add_person AS addPerson, om.use_point AS usePoint, om.is_represent AS isRepresent', FALSE);
				
				$this->db->from('order_menu AS om');
				$this->db->join('menu AS m', 'm.menu_id = om.menu_id', 'inner');
				
				$this->db->where('om.order_id', $order_id);
				
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
				log_message('error', 'get_menu_by_order db exception :: ' . $e);
				$query = NULL;
			}
		}		




		function get_order_by_code($order_code) {
			
			try 
			{
				$return_value = array();
				// list
				$this->db->select('o.order_id, DATE_FORMAT(FROM_UNIXTIME(UNIX_TIMESTAMP(STR_TO_DATE(o.order_datetime, "%Y-%m-%d %H:%i:%s"))), "%Y.%m.%d %H:%i:%s") AS order_datetime, o.customer_phone, s.name AS seller_name, s.location_image_url, s.market_image_url, o.memo, rt.name AS receive_type_name', FALSE);
				
				$this->db->from('order AS o');
				$this->db->join('seller AS s', 's.seller_id = o.seller_id', 'inner');
				$this->db->join('receive_type AS rt', 'rt.receive_id = o.receive_id', 'inner');
				
				$this->db->where('o.order_code', $order_code);
				
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


		function get_order_vbank_by_code($order_code) {
			
			try 
			{
				$return_value = array();
				// list
				$this->db->select('o.order_id, DATE_FORMAT(FROM_UNIXTIME(UNIX_TIMESTAMP(STR_TO_DATE(o.order_datetime, "%Y-%m-%d %H:%i:%s"))), "%Y.%m.%d %H:%i:%s") AS order_datetime, o.customer_phone, o.memo, o.total_order_price, m.name AS member_nick', FALSE);
				
				$this->db->from('order AS o');
				$this->db->join('member AS m', 'm.xid = o.xid', 'inner');
				
				$this->db->where('o.order_code', $order_code);
				
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



		function is_order_by_menu_id($xid, $menu_id) {
			
			try 
			{
				$return_value = array();
				// list
				$this->db->select('o.xid', FALSE);
				
				$this->db->from('order AS o');
				//$this->db->join('seller AS s', 's.seller_id = o.seller_id', 'inner');
				$this->db->join('order_menu AS om', 'om.order_id = o.order_id', 'inner');
				
				$this->db->where('o.xid', $xid);
				$this->db->where('om.menu_id', $menu_id);
				
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
				log_message('error', 'is_order_by_menu_id db exception :: ' . $e);
				$query = NULL;
			}
		}		


		function is_order_by_tid($order_id, $xid, $lgd_tid) {
			
			try 
			{
				$return_value = array();
				// list
				$this->db->select('o.order_id, total_order_point, order_status, sell_status', FALSE);
				
				$this->db->from('order AS o');
				
				$this->db->where('o.xid', $xid);
				$this->db->where('o.order_id', $order_id);
				$this->db->where('o.tid', $lgd_tid);
				
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
				log_message('error', 'is_order_by_tid db exception :: ' . $e);
				$query = NULL;
			}
		}		
	}

?>