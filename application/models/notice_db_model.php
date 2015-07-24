<?php 

	class notice_db_model extends CI_Model
	{
		
		function __construct() {
			parent::__construct();
		}

//////////////////////////////////////////////////////////////////////////////
//																			//
//								List										//
//																			//
//////////////////////////////////////////////////////////////////////////////
		
		
		
		function get_notice_list($service_id, $lang_code, $keyword, $offset, $limit) {
			
			try 
			{
				// CMS에서 전체 리스트
				if($service_id == 0)
				{
					// list
					$this->db->select('notice_id, n.lang_code, title, content, is_show, n.regist_date, l.lang_name, s.key, s.name', FALSE);
					
					$this->db->from('notice AS n');
					
					$this->db->join('service AS s', 'n.service_id = s.service_id', 'inner');
					
					$this->db->join('language AS l', 'l.lang_code = n.lang_code', 'inner');
					
					$this->db->like('title', $keyword); 
					
					$this->db->limit($limit, $offset);
					
					$this->db->order_by("notice_id", "desc");
					
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
					$this->db->from('notice AS n');
					
					$this->db->join('service AS s', 'n.service_id = s.service_id', 'inner');
					
					$this->db->join('language AS l', 'l.lang_code = n.lang_code', 'inner');
					
					$this->db->like('title', $keyword);
					
					$return_value['count'] = $this->db->count_all_results();
					
					$query->next_result(FALSE);
					$query->free_result();
					
					return $return_value;
				}
				// Mobile Web에서 사용
				else if($limit == 0)
				{
					$this->db->select('notice_id, title, content, n.regist_date', FALSE);
					
					$this->db->from('notice AS n');
					
					$this->db->where('service_id', $service_id);
					$this->db->where('is_show', 1);
					$this->db->where('lang_code', $lang_code);
					
					$this->db->order_by("notice_id", "desc");
					
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
					$this->db->select('notice_id, title, content, n.regist_date', FALSE);
					
					$this->db->from('notice AS n');
					
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
		
		
		
		
		function set_notice($notice_id, $service_id, $lang_code, $title, $content, $is_show) {
			
			try 
			{
				if($notice_id == 0)
				{
					$this->db->set('service_id', $service_id);
					$this->db->set('lang_code', $lang_code);
					$this->db->set('title', $title);
					$this->db->set('content', $content);
					$this->db->set('is_show', $is_show);
					
					$this->db->insert('notice');
					
					$result_value = "1";
				}
				else
				{
					$data = array(
		               'service_id' => $service_id,
		               'lang_code' => $lang_code,
		               'title' => $title,
		               'content' => $content,
		               'is_show' => $is_show
		            );
		            
					$this->db->where('notice_id', $notice_id);
					$this->db->update('notice', $data);
					
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



		function get_notice($notice_id) {
			
			try 
			{
				$return_value = array();
				
				$this->db->select('notice_id, n.service_id, n.lang_code, title, content, is_show, n.regist_date, l.lang_name, s.name', FALSE);
				
				$this->db->from('notice AS n');
					
				$this->db->join('service AS s', 'n.service_id = s.service_id', 'inner');
				
				$this->db->join('language AS l', 'l.lang_code = n.lang_code', 'inner');
				
				$this->db->where('n.notice_id', $notice_id);
				
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



		function del_notice($notice_id) {
			
			try 
			{
				$return_value = array();
				
				$this->db->where('notice_id', $notice_id);
				$this->db->delete('notice');
				
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