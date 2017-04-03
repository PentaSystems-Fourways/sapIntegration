<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Bp extends REST_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->library('sapinterfacer');
		$this->sapinterfacer->set_object(2);
	}
	
	public function client_post() {
		$addList = $this->post();
		
		$sapInfResponse = $this->sapinterfacer->_add($addList);

		if ($sapInfResponse) {
			$this->response(array('message'=>'Business Partner Client Successfully Added'), 200);
		} else {
			$this->response(array('message'=>$this->sapinterfacer->errMsg), 200);
		}
	}

	public function client_put() {
		$updatedList = $this->put();

		$sapInfResponse = $this->sapinterfacer->_update($updatedList);

		if ($sapInfResponse) {
			$this->response(array('message'=>'Client '.implode(", ",array_keys($updatedList)).' has been Successfully Updated'), 200);
		} else {
			$this->response(array('message'=>$this->sapinterfacer->errMsg), 200);
		}
	}

} 

/* End of file bp.php */
/* Location: ./application/controllers/api/bp.php */