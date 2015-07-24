<?php 

	class device_db_model extends CI_Model
	{
		
		function __construct() {
			parent::__construct();
		}

//////////////////////////////////////////////////////////////////////////////
//																			//
//								List										//
//																			//
//////////////////////////////////////////////////////////////////////////////
		
		
		
		function get_service_device_list($service_id, $keyword, $offset, $limit) {
			
			try 
			{
				$return_value = array();
				
				// list
				$this->db->select('sd.service_device_id, sd.service_id, sd.device, sd.regist_date, s.name', FALSE);
				
				$this->db->from('service AS s');
				
				$this->db->join('service_device AS sd', 'sd.service_id = s.service_id', 'left');
				
				$this->db->where('sd.service_id', $service_id); 
				
				$this->db->limit($limit, $offset);
				
				$this->db->order_by("sd.service_device_id", "desc");
				
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
				$this->db->select('COUNT(sd.service_device_id) AS count, s.name', FALSE);
				
				$this->db->from('service AS s');
				
				$this->db->join('service_device AS sd', 'sd.service_id = s.service_id', 'inner');
				
				$this->db->where('sd.service_id', $service_id);
				
				$query = $this->db->get();
				
				$return_value['count'] = $query->result_array();
				
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
		
		
		
		
		function set_service_android($service_device_id, $service_id, $device, $version_name, $version_code, $gcm_service_key, $gcm_package_name, $gcm_queue_name, $gcm_worker_count, $gcm_feedback_api1, $gcm_feedback_api2) {
			
			try 
			{
				$this->db->start_cache();
				$this->db->from('service_device');
				$this->db->stop_cache();
				$this->db->where('service_id', $service_id);
				$this->db->where('device', $device);
				
				// 새로 입력인데 디바이스가 중복 됐을 때
				if($service_device_id == 0 && $this->db->count_all_results() > 0)
				{
					$result_value = "103";
				}
				else
				{
					$data = array(
					     'service_id'    => $service_id,
					     'gcm_service_key' => $gcm_service_key,
					     'gcm_package_name'  => $gcm_package_name,
					     'worker_count'  => $gcm_worker_count,
					     'queue_name'  => $gcm_queue_name,
					     'feedback_api1'  => $gcm_feedback_api1,
					     'feedback_api2'  => $gcm_feedback_api2
					);
					
					$sql = $this->db->insert_string('service_gcm', $data) . ' ON DUPLICATE KEY UPDATE gcm_service_key="'.$gcm_service_key.'", gcm_package_name="'.$gcm_package_name.'", worker_count='.$gcm_worker_count.', queue_name="'.$gcm_queue_name.'", feedback_api1="'.$gcm_feedback_api1.'", feedback_api2="'.$gcm_feedback_api2.'";';
					$this->db->query($sql);
				
					// 서비스 디바이스 등록
					if($service_device_id == 0)
					{
						$data = array(
						     'service_id'    => $service_id,
						     'device' => $device,
						     'version_name'  => $version_name,
						     'version_code'  => $version_code
						);
						
						$this->db->insert('service_device', $data);
					}
					// 서비스 디바이 수정
					else
					{
						$this->db->set('version_name', $version_name);
						$this->db->set('version_code', $version_code);
						$this->db->set('regist_date', 'CURRENT_TIMESTAMP', FALSE);
						
						$this->db->update('service_device');
					}
					
					$result_value = "1";
				}
				
				return $result_value;
			}
			catch (Exception $e)
			{
				var_dump($e);
			}
			
		}



		function set_service_iphone($service_device_id, $service_id, $device, $version_name, $version_code, $cert, $key, $is_production, $apns_queue_name, $apns_worker_count, $apns_feedback_api1, $apns_feedback_api2) {
			
			try 
			{
				$this->db->start_cache();
				$this->db->from('service_device');
				$this->db->stop_cache();
				$this->db->where('service_id', $service_id);
				$this->db->where('device', $device);
				
				// 새로 입력인데 디바이스가 중복 됐을 때
				if($service_device_id == 0 && $this->db->count_all_results() > 0)
				{
					$result_value = "103";
				}
				else
				{
					$data = array(
					     'service_id'    => $service_id,
					     'cert' => $cert,
					     'key'  => $key,
					     'production'  => $is_production,
					     'queue_name'  => $apns_queue_name,
					     'worker_count'  => $apns_worker_count,
					     'feedback_api1'  => $apns_feedback_api1,
					     'feedback_api2'  => $apns_feedback_api2
					);
					
					$sql = $this->db->insert_string('service_apns', $data) . ' ON DUPLICATE KEY UPDATE cert="'.$cert.'", `key`="'.$key.'", production='.$is_production.', queue_name="'.$apns_queue_name.'", worker_count='.$apns_worker_count.', feedback_api1="'.$apns_feedback_api1.'", feedback_api2="'.$apns_feedback_api2.'";';
					$this->db->query($sql);
				
					// 서비스 디바이스 등록
					if($service_device_id == 0)
					{
						$data = array(
						     'service_id'    => $service_id,
						     'device' => $device,
						     'version_name'  => $version_name,
						     'version_code'  => $version_code
						);
						
						$this->db->insert('service_device', $data);
					}
					// 서비스 디바이 수정
					else
					{
						$this->db->set('version_name', $version_name);
						$this->db->set('version_code', $version_code);
						$this->db->set('regist_date', 'CURRENT_TIMESTAMP', FALSE);
						
						$this->db->update('service_device');
					}
					
					$result_value = "1";
				}
				
				return $result_value;
			}
			catch (Exception $e)
			{
				var_dump($e);
				$resultSet2 = NULL;
			}
		}
		
		
		
		
		
//////////////////////////////////////////////////////////////////////////////
//																			//
//								Detail										//
//																			//
//////////////////////////////////////////////////////////////////////////////



		function get_service_device($service_device_id) {
			
			try 
			{
				$reValue = array();
				
				$this->db->select('sd.service_device_id, sd.service_id, sd.device, sd.version_name, sd.version_code, sd.regist_date, s.name, g.gcm_service_key AS gcm_service_key, g.gcm_package_name AS gcm_package_name, g.worker_count AS gcm_worker_count, g.queue_name AS gcm_queue_name, g.feedback_api1 AS gcm_feedback_api1, g.feedback_api2 AS gcm_feedback_api2, a.cert, a.key, a.production AS is_production, a.queue_name AS apns_queue_name, a.worker_count AS apns_worker_count, a.feedback_api1 AS apns_feedback_api1, a.feedback_api2 AS apns_feedback_api2', FALSE);
				
				$this->db->from('service_device AS sd');
				
				$this->db->join('service AS s', 's.service_id = sd.service_id', 'inner');
				
				$this->db->join('service_gcm AS g', 'g.service_id = sd.service_id', 'left');
				
				$this->db->join('service_apns AS a', 'a.service_id = sd.service_id', 'left');
				
				$this->db->where('sd.service_device_id', $service_device_id);
				
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



		function del_service_device($service_device_id, $device, $service_id) {
			
			try 
			{
				$return_value = array();
				
				$this->db->where('service_device_id', $service_device_id);
				$this->db->delete('service_device');
				
				if($device == 'ANDROID')
				{
					$this->db->where('service_id', $service_id);
					$this->db->delete('service_gcm');
				}
				else
				{
					$this->db->where('service_id', $service_id);
					$this->db->delete('service_apns');
				}
				
				
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