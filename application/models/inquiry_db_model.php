<?php 

	class inquiry_db_model extends CI_Model
	{
		
		function __construct() {
			parent::__construct();
		}

//////////////////////////////////////////////////////////////////////////////
//																			//
//								List										//
//																			//
//////////////////////////////////////////////////////////////////////////////
		
		
		
		function get_inquiry_list($service_id, $lang_code, $keyword, $offset, $limit) {
			
			try 
			{
				// CMS에서 전체 리스트
				if($service_id == 0)
				{
					// list
					$this->db->select('inquiry_id, email, phone, content, status, i.regist_date', FALSE);
					
					$this->db->from('inquiry AS i');
					
					$this->db->like('content', $keyword); 
					
					$this->db->limit($limit, $offset);
					
					$this->db->order_by("inquiry_id", "desc");
					
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
					$this->db->from('inquiry AS n');
					
					$this->db->like('content', $keyword);
					
					$return_value['count'] = $this->db->count_all_results();
					
					$query->next_result(FALSE);
					$query->free_result();
					
					return $return_value;
				}
				// Mobile Web에서 사용
				else if($service_id == 0 && $limit == 0)
				{
					$this->db->select('inquiry_id, title, content, n.regist_date', FALSE);
					
					$this->db->from('inquiry AS n');
					
					$this->db->where('service_id', $service_id);
					$this->db->where('is_show', 1);
					$this->db->where('lang_code', $lang_code);
					
					$query = $this->db->get();
					
					if(!$query)
					{
						throw new Exception('Could not query:' . mysql_error());
					}
					
					$return_value = array();
					if($query->num_rows() > 0)
					{
						$return_value['list'] = $query->result_array();
					}
					
					$query->next_result(FALSE);
					$query->free_result();
					
					return $return_value;
				}
				// API에서 사용
				else
				{
					$this->db->select('inquiry_id, title, content, n.regist_date', FALSE);
					
					$this->db->from('inquiry AS n');
					
					$this->db->where('service_id', $service_id);
					$this->db->where('is_show', 1);
					$this->db->where('lang_code', $lang_code);
					
					$this->db->like('title', $keyword); 
					
					$this->db->limit($limit, $offset);
					
					$query = $this->db->get();
					
					if(!$query)
					{
						throw new Exception('Could not query:' . mysql_error());
					}
					
					$return_value = array();
					if($query->num_rows() > 0)
					{
						$return_value['list'] = $query->result_array();
					}
					
					$query->next_result(FALSE);
					$query->free_result();
					
					return $return_value;
				}
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
		
		
		
		
		function set_inquiry($inquiry_id, $phone, $email, $content, $answer) {
			
			try 
			{
				$data = array(
	               'inquiry_id' => $inquiry_id,
	               'email' => $email,
	               'phone' => $phone,
	               'content' => $content,
	               'answer' => $answer,
	               'status' => 1
	            );
	            
				$this->db->where('inquiry_id', $inquiry_id);
				$this->db->update('inquiry', $data);
				
				$result_value = "1";
				
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



		function get_inquiry($inquiry_id) {
			
			try 
			{
				$return_value = array();
				
				$this->db->select('inquiry_id, email, phone, content, answer, status, regist_date', FALSE);
				
				$this->db->from('inquiry');
				
				$this->db->where('inquiry_id', $inquiry_id);
				
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

		
		
		
		
		
//////////////////////////////////////////////////////////////////////////////
//																			//
//								Delete										//
//																			//
//////////////////////////////////////////////////////////////////////////////



		function del_inquiry($inquiry_id) {
			
			try 
			{
				$return_value = array();
				
				$this->db->where('inquiry_id', $inquiry_id);
				$this->db->delete('inquiry');
				
				$return_value = $this->db->affected_rows();
				
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