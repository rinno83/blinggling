<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pay extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('pay_view');
	}
	
	public function confirm()
	{
		$result_array = array();
		
		$string = '{
					"menu":[
					{
					"menuId":"1",
					"menuType":"menu",
					"menuName":"싱싱커플 2~3인용",
					"menuCount":"1",
					"usePoint":"0",
					"isRepresent":"1"
					}
					],
					"memo":"해산물 1개, 전복 1개 주세요.",
					"orderDateTime":"1434775991",
					"customerPhone":"01027521038",
					"customerAddress":"",
					"receiveType":"1",
					"totalPrice":"65000",
					"totalPoint":"1500",
					"customerEmail":"rinno83@naver.com",
					"receiveTypeName":"포장",
					"orderCode":"1434525069",
					"registDate":"1434517912",
					"payLink":"http://localhost:8888/pay/confirm"
					}';
		
		$order_code = $this->uri->segment(3);
		$db_result_order = $this->menu_db_model->get_order_by_order_code($order_code);
		$db_result_menu = $this->menu_db_model->get_menu_by_order($db_result_order[0]['orderId']);
		
		$db_result_corp = $this->service_db_model->get_corp_info();
		
		$db_result_store = $this->menu_db_model->get_store_by_order_code($order_code);
			
		$menu_string = '';
		
		foreach($db_result_menu as $key => $row)
		{
			if($row['isRepresent'] == 1)
			{
				$menu_string .= $row['menuName'] . ' ' . $row['menuCount'] . '세트';
			}
		}
		
		$unexpect_count = count($db_result_menu) - 1;
		
		if($unexpect_count)
		{
			$menu_string .= ' 외 ' . ($unexpect_count) . '종';	
		}
		
		
		$db_result_order[0]['menuString'] = $menu_string;
		$db_result_order[0]['menu'] = $db_result_menu;
		
		
		$db_result_order[0]['name'] = $db_result_order[0]['customerName'];
		$db_result_order[0]['phone_string'] = substr_replace($db_result_order[0]['customerPhone'], '**', -2);
		
		$db_result_order[0]['corp'] = $db_result_corp[0];
		
		if($db_result_store)
		{
			$db_result_order[0]['store'] = $db_result_store[0];
		}
		else
		{
			$db_result_order[0]['store'] = array();
		}
		
		
		//var_dump($result_array);
		
		$this->load->view('pay_view', $db_result_order[0]);
		//echo json_encode($result_array);
	}
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	//																							   //
	//										무통장 입금 예약										   //
	//																							   //
	///////////////////////////////////////////////////////////////////////////////////////////////// 
	public function vbank()
	{
		set_req_log('/pay/vbank', '', 'order_code - '.$this->uri->segment(3));
		$result_array = array();
		$order_code = $this->uri->segment(3);
		if($order_code)
		{
			$db_result = $this->menu_db_model->get_order_vbank_by_code($order_code);
			if($db_result)
			{
				$result_array = $db_result[0];
				
				$db_result_menu = $this->menu_db_model->get_order_menu($db_result[0]['order_id'], 0);
				$result_array['menu'] = $db_result_menu['list'];
				
				foreach($db_result_menu['list'] as $key => $row)
				{
					// get menu image
					$db_result_image = $this->menu_db_model->get_menu_image_list($row['menuId']);
					if($db_result_image)
					{
						foreach($db_result_image['list'] as $key3 => $row3)
						{
							$temp = explode('/', $row3['menu_image_url']);
					
							$temp[count($temp) - 1] = 'thumb_' . $temp[count($temp) - 1];
							
							$result_array['menuImage'][$key3]['menuImageUrl'] = implode('/', $temp);
						}
					}
				}
			}
			
			$db_result_corp = $this->service_db_model->get_corp_info();
			$result_array['corp'] = $db_result_corp[0];
			
			// send sms corp
			$corp_phone1 = $db_result_corp[0]['mobile_phone1'];
			$corp_phone2 = $db_result_corp[0]['mobile_phone2'];
			$corp_phone3 = $db_result_corp[0]['mobile_phone3'];
			
			if($corp_phone1)
			{
				// send sms to corp
				$this->member_db_model->set_sms($corp_phone1, '새로운 주문이 접수 됐습니다.');
			}
			if($corp_phone2)
			{
				// send sms to corp
				$this->member_db_model->set_sms($corp_phone2, '새로운 주문이 접수 됐습니다.');
			}
			if($corp_phone3)
			{
				// send sms to corp
				$this->member_db_model->set_sms($corp_phone3, '새로운 주문이 접수 됐습니다.');
			}
			
			
			// set order status
			$this->menu_db_model->set_order_status($db_result[0]['order_id'], 'standby');
			
			// set order pay_type
			$this->menu_db_model->set_order_pay_type($db_result[0]['order_id'], 'vbank');
			
			// set order pg
			$this->menu_db_model->set_order_pg($db_result[0]['order_id'], 'vbank');
			
			//var_dump($result_array);
			$this->load->view('order_vbank_info_view', $result_array);
		}
		else
		{
			http_response_code(400);
			
			log_message('error', 'parameter error');
			
			echo json_encode(array('errorCode' => '00', 'errorMessage' => 'parameter error'));
		}
	}
	
	
	public function complete()
	{
		
	}
	
	public function form()
	{
		$result_array = array();
		
		$db_result = $this->menu_db_model->get_menu_list(0, 0, 0, 10);
		$result_array['menu'] = $db_result['list'];

		$this->load->view('pay_form_view', $result_array);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */