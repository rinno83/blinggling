<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

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
		$this->load->view('admin_login_view');
	}
	
	public function ajax_login()
	{
		$db_result = array();
		
		if(isset($_POST['main_id']) && isset($_POST['main_pw']))
		{
			// Confirm DB
			$db_result = $this->admin_db_model->login($_POST['main_id'], $_POST['main_pw']);
			
			// SET SESSION
			if(is_array($db_result))
			{
				$new_data = array(
					'admin_id' => $db_result[0]['admin_id'],
					'id' => $db_result[0]['id']
		        );

		        $this->session->set_userdata($new_data);
		        
		        $result_data = array(
		    		'result' => "1"
		    	);
			}
			else
			{
				$result_data = array(
		    		'result' => $db_result
		    	);
			}
	    	
	    	echo json_encode($result_data);
		}
		else
		{
			$result_data = array(
	    		'result' => "9999"
	    	);
	    	
	    	echo json_encode($result_data);
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */