<?php 

	class terms_db_model extends CI_Model
	{
		
		function __construct() {
			parent::__construct();
		}

//////////////////////////////////////////////////////////////////////////////
//																			//
//								List										//
//																			//
//////////////////////////////////////////////////////////////////////////////
		
		
		
		function get_terms_list($service_id, $lang_code, $type, $keyword, $offset, $limit) {
			
			try 
			{
				$return_value = array();
				
				// CMS에서 전체 리스트
				if($service_id == 0)
				{
					// list
					$this->db->select('terms_id, type, t.lang_code, title, content, is_show, t.regist_date, l.lang_name, s.name', FALSE);
					
					$this->db->from('terms AS t');
					
					$this->db->join('service AS s', 't.service_id = s.service_id', 'inner');
					
					$this->db->join('language AS l', 'l.lang_code = t.lang_code', 'inner');
					
					$this->db->like('title', $keyword); 
					
					$this->db->limit($limit, $offset);
					
					$this->db->order_by("terms_id", "desc");
					
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
					$this->db->from('terms AS t');
					
					$this->db->join('service AS s', 't.service_id = s.service_id', 'inner');
					
					$this->db->join('language AS l', 'l.lang_code = t.lang_code', 'inner');
					
					$this->db->like('title', $keyword); 
					
					$return_value['count'] = $this->db->count_all_results();
					
					$query->next_result(FALSE);
					$query->free_result();
					
					return $return_value;
				}
				// Mobile Web이나 API에서 사용
				else
				{
					$this->db->select('a.type, a.content', FALSE);
					
					$this->db->from('(SELECT * FROM terms WHERE service_id = '.$service_id.' AND is_show = 1 AND lang_code = "'.$lang_code.'" ORDER BY regist_date DESC) AS a');
					
					$this->db->group_by('a.type');
										
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
		
		
		
		
		function set_terms($terms_id, $service_id, $lang_code, $type, $title, $content, $is_show) {
			
			try 
			{
				if($terms_id == 0)
				{
					$this->db->set('service_id', $service_id);
					$this->db->set('lang_code', $lang_code);
					$this->db->set('type', $type);
					$this->db->set('title', $title);
					$this->db->set('content', $content);
					$this->db->set('is_show', $is_show);
					
					$this->db->insert('terms');
					
					$result_value = "1";
				}
				else
				{
					$data = array(
		               'service_id' => $service_id,
		               'lang_code' => $lang_code,
		               'type' => $type,
		               'title' => $title,
		               'content' => $content,
		               'is_show' => $is_show
		            );
		            
					$this->db->where('terms_id', $terms_id);
					$this->db->update('terms', $data);
					
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



		function get_terms($terms_id) {
			
			try 
			{
				$return_value = array();
				
				$this->db->select('terms_id, n.service_id, n.lang_code, type, title, content, is_show, n.regist_date, l.lang_name, s.name', FALSE);
				
				$this->db->from('terms AS n');
					
				$this->db->join('service AS s', 'n.service_id = s.service_id', 'inner');
				
				$this->db->join('language AS l', 'l.lang_code = n.lang_code', 'inner');
				
				$this->db->where('n.terms_id', $terms_id);
				
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



		function del_terms($terms_id) {
			
			try 
			{
				$return_value = array();
				
				$this->db->where('terms_id', $terms_id);
				$this->db->delete('terms');
				
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