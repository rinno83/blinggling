<?php 

	class push_db_model extends CI_Model
	{
		
		function __construct() {
			parent::__construct();
		}

//////////////////////////////////////////////////////////////////////////////
//																			//
//								List										//
//																			//
//////////////////////////////////////////////////////////////////////////////
		
		
		
		function get_push_history_list($keyword, $offset, $limit) {
			
			try 
			{
				// list
				$this->db->select('p.push_id, p.device, p.title, p.status, p.send_date, s.name', FALSE);
				
				$this->db->from('push AS p');
				
				$this->db->join('service AS s', 'p.service_id = s.service_id', 'inner');
				
				$this->db->like('title', $keyword); 
				
				$this->db->limit($limit, $offset);
				
				$this->db->order_by("push_id", "desc");
				
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
				$this->db->from('push AS p');
				
				$this->db->join('service AS s', 'p.service_id = s.service_id', 'inner');
				
				$this->db->like('title', $keyword);
				
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
		
		
		
		
		function set_push($push_id, $service_id, $device, $title, $payload, $status, $fail_reason) {
			
			try 
			{
				if($push_id == 0)
				{
					$this->db->set('service_id', $service_id);
					$this->db->set('device', $device);
					$this->db->set('title', $title);
					$this->db->set('payload', $payload);
					
					$this->db->insert('push');
					
					$result_value = "1";
				}
				else
				{
					$data = array(
		               'stauts' => $status,
		               'fail_reason' => $fail_reason
		            );
		            
					$this->db->where('push_id', $push_id);
					$this->db->update('push', $data);
					
					$result_value = "1";
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



		function get_push($push_id) {
			
			try 
			{
				$return_value = array();
				
				$this->db->select('p.push_id, p.device, p.title, p.payload, p.status, p.fail_reason, p.send_date, s.name', FALSE);
				
				$this->db->from('push AS p');
					
				$this->db->join('service AS s', 'p.service_id = s.service_id', 'inner');
				
				$this->db->where('p.push_id', $push_id);
				
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
				//var_dump($e);
				$query = NULL;
			}

		}

		
	}		
?>