<?php 

	class service_db_model extends CI_Model
	{
		
		function __construct() {
			parent::__construct();
		}
		
		
		function get_slr_test() {
			
			try 
			{
				$return_value = array();
				
				// list
				$this->db->select('id, section', FALSE);
				
				$this->db->from('front_page');
				
				$this->db->where('section', 'hot_article');
				
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
				//var_dump($e);
				$query = NULL;
			}
			
		}

		function set_slr_test($section, $id, $old_section, $old_id) {
			
			try 
			{
				$this->db->set('id', $id);
				$this->db->set('section', $section);
				$this->db->where('id', $old_id);
				$this->db->where('section', $old_section);
				
				$this->db->update('front_page');
			}
			catch (Exception $e)
			{
				var_dump($e);
			}
			
		}

//////////////////////////////////////////////////////////////////////////////
//																			//
//								List										//
//																			//
//////////////////////////////////////////////////////////////////////////////
		
		
		function get_service_list($keyword, $offset, $limit) {
			
			try 
			{
				$return_value = array();
				
				// list
				$this->db->select('s.service_id, `key`, `secret`,  `name`, `desc`, l.lang_name, s.regist_date', FALSE);
				
				$this->db->from('service AS s');
				
				$this->db->join('service_language_map AS sl', 'sl.service_id = s.service_id', 'inner');
				
				$this->db->join('language AS l', 'l.lang_code = sl.lang_code', 'inner');
				
				$this->db->like('name', $keyword); 
				
				$this->db->limit($limit, $offset);
				
				$this->db->order_by("s.service_id", "desc");
				
				$query = $this->db->get();
				
				if(!$query)
				{
					throw new Exception('Could not query:' . mysql_error());
				}
				
				if($query->num_rows() > 0)
				{
					$return_value['list'] = $query->result_array();
				}
				
				// count
				$this->db->from('service AS s');
				
				$this->db->join('service_language_map AS sl', 'sl.service_id = s.service_id', 'inner');
				
				$this->db->join('language AS l', 'l.lang_code = sl.lang_code', 'inner');
				
				$this->db->like('name', $keyword); 
				
				$return_value['count'] = $this->db->count_all_results();
				
				$query->next_result(FALSE);
				$query->free_result();
				
				return $return_value;

			}
			catch (Exception $e)
			{
				//var_dump($e);
				$query = NULL;
			}
			
		}



		function get_language_list($keyword, $offset, $limit) {
			
			try 
			{
				$return_value = array();
				
				// list
				$this->db->select('lang_code, lang_name, regist_date', FALSE);
				
				$this->db->from('language');
				
				$this->db->like('lang_name', $keyword, FALSE); 
				
				$this->db->limit($limit, $offset);
				
				$this->db->order_by("lang_name", "ASC");
				
				$query = $this->db->get();
				
				if(!$query)
				{
					throw new Exception('Could not query:' . mysql_error());
				}
				
				if($query->num_rows() > 0)
				{
					$return_value['list'] = $query->result_array();
				}
				
				// count
				$this->db->from('language');
				
				$this->db->like('lang_name', $keyword); 
				
				$return_value['count'] = $this->db->count_all_results();
				
				$query->next_result(FALSE);
				$query->free_result();
				
				return $return_value;

			}
			catch (Exception $e)
			{
				//var_dump($e);
				$query = NULL;
			}
		}
		
		
		
//////////////////////////////////////////////////////////////////////////////
//																			//
//								Insert										//
//																			//
//////////////////////////////////////////////////////////////////////////////
		function set_service($service_id, $lang_code, $name, $desc) {
			
			try 
			{
				if($service_id == 0)
				{
					$this->db->start_cache();
					$this->db->from('service');
					$this->db->stop_cache();
					$this->db->where('name', $name);
					
					if($this->db->count_all_results() == 0) 
					{
						$this->db->set('key', 'CAST(SHA1(UUID()) AS CHAR(40) CHARACTER SET utf8)', FALSE);
						$this->db->set('secret', 'CAST(MD5(UUID()) AS CHAR(32) CHARACTER SET utf8)', FALSE);
						$this->db->set('name', $name);
						$this->db->set('desc', $desc);
						
						$this->db->insert('service');
						
						$service_id = $this->db->insert_id();
						
						$this->db->set('service_id', $service_id);
						$this->db->set('lang_code', $lang_code);
						
						$this->db->insert('service_language_map');
						
						$result_value = "1";
					}
					else
					{
						$result_value = "103";
					}
				}
				else
				{
					// service table update
					$data = array(
		               'name' => $name,
		               'desc' => $desc
		            );
		            
					$this->db->where('service_id', $service_id);
					$this->db->update('service', $data);
					
					// language table update
					$data2 = array(
		               'lang_code' => $lang_code
		            );
		            
					$this->db->where('service_id', $service_id);
					$this->db->update('service_language_map', $data2);
					
					$result_value = "1";
				}
				
				return $result_value;
			}
			catch (Exception $e)
			{
				var_dump($e);
			}
		}


		function set_language($lang_code, $lang_name) {
			
			try 
			{
				$this->db->start_cache();
				$this->db->from('language');
				$this->db->stop_cache();
				$this->db->where('lang_code', $lang_code);
				
				if($this->db->count_all_results() == 0) 
				{
					$this->db->set('lang_code', $lang_code);
					$this->db->set('lang_name', $lang_name);
					
					$this->db->insert('language');
					
					$result_value = "1";
				}
				else
				{
					$result_value = "103";
				}
				
				return $result_value;
			}
			catch (Exception $e)
			{
				var_dump($e);
			}
		}




//////////////////////////////////////////////////////////////////////////////
//																			//
//								Detail										//
//																			//
//////////////////////////////////////////////////////////////////////////////



		function get_service_id($service_key) {
			
			try 
			{
				$reValue = array();
				
				$this->db->select('service_id', FALSE);
				
				$this->db->from('service');
				
				$this->db->where('key', $service_key);
				
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
				//var_dump($e);
				$query = NULL;
			}
		}



		function get_service($service_id) {
			
			try 
			{
				$reValue = array();
				
				$this->db->select('s.service_id, s.key, s.secret, s.name, s.desc, l.lang_code, l.lang_name, s.regist_date', FALSE);
				
				$this->db->from('service AS s');
				
				$this->db->join('service_language_map AS sl', 'sl.service_id = s.service_id', 'inner');
				
				$this->db->join('language AS l', 'l.lang_code = sl.lang_code', 'inner');
				
				$this->db->where('s.service_id', $service_id);
				
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
				//var_dump($e);
				$query = NULL;
			}
			
		}


		function get_service_delivery_fee($service_key) {
			
			try 
			{
				$reValue = array();
				
				$this->db->select('delivery_fee', FALSE);
				
				$this->db->from('service AS s');
				
				$this->db->where('s.key', $service_key);
				
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
				//var_dump($e);
				$query = NULL;
			}
			
		}
		


//////////////////////////////////////////////////////////////////////////////
//																			//
//								Delete										//
//																			//
//////////////////////////////////////////////////////////////////////////////

		function del_service($service_id) {
			
			try 
			{
				$return_value = array();
				
				$this->db->where('service_id', $service_id);
				$this->db->delete('service_language_map');
				
				$this->db->where('service_id', $service_id);
				$this->db->delete('service');
				
				$return_value = $this->db->affected_rows();
				
				return $return_value;

			}
			catch (Exception $e)
			{
				//var_dump($e);
				$query = NULL;
			}
		}


		function del_language($lang_code) {
			
			try 
			{
				$return_value = array();
				
				$this->db->where('lang_code', $lang_code);
				$this->db->delete('language');
				
				$return_value = $this->db->affected_rows();
				
				return $return_value;

			}
			catch (Exception $e)
			{
				//var_dump($e);
				$query = NULL;
			}
		}
		
		
		

//////////////////////////////////////////////////////////////////////////////
//																			//
//								API											//
//																			//
//////////////////////////////////////////////////////////////////////////////

		function get_service_order_info($service_key) {
			
			try 
			{
				$reValue = array();
				
				$this->db->select('delivery_info AS deliveryInfo, store_info AS storeInfo, delivery_fee AS deliveryFee', FALSE);
				
				$this->db->from('service');
				
				$this->db->where('key', $service_key);
				
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
				//var_dump($e);
				$query = NULL;
			}
		}


		function get_corp_info() {
			
			try 
			{
				$reValue = array();
				
				$this->db->select('name, ceo, regist_number, communication_business_report, address, phone, mobile_phone1, mobile_phone2, mobile_phone3, email, bank_account, bank_name, bank_holder', FALSE);
				
				$this->db->from('service_corp');
				
				$this->db->where('corp_id', 1);
				
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
				//var_dump($e);
				$query = NULL;
			}
		}


		function get_service_version($service_key, $device) {
			
			try 
			{
				$reValue = array();
				
				$this->db->select('version_name AS versionName, version_code AS versionCode', FALSE);
				
				$this->db->from('service AS s');
				
				$this->db->join('service_device AS sd', 'sd.service_id = s.service_id', 'inner');
				
				$this->db->where('key', $service_key);
				$this->db->where('sd.device', $device);
				
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
				//var_dump($e);
				$query = NULL;
			}
		}

		function get_corp_info_api() {
			
			try 
			{
				$reValue = array();
				
				$this->db->select('`name`, ceo, regist_number AS registNumber, communication_business_report AS communicationBusinessReport, address, phone, mobile_phone1 AS mobilePhone1, mobile_phone2 AS mobilePhone2, mobile_phone3 AS mobilePhone3, email, bank_account AS bankAccount, bank_name AS bankName', FALSE);
				
				$this->db->from('service_corp');
				
				$this->db->where('corp_id', 1);
				
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
				//var_dump($e);
				$query = NULL;
			}
		}
	}

?>