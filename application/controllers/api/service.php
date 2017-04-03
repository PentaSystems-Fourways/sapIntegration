<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends REST_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->library('sapinterfacer');
		$this->sapinterfacer->set_object(191);
	}

	public function index_post() {
		$serviceCallList = $this->post();

		if (isset($serviceCallList['internalSN'])) {
			$this->sapinterfacer->_add_serial($serviceCallList['internalSN']);		
			unset($serviceCallList['internalSN']);
		}

		$sapInfResponse = $this->sapinterfacer->_add($serviceCallList);

		if ($sapInfResponse) {
			$this->response(array('message'=>'Service Call Successfully Added'), 200);
		} else {
			$this->response(array('message'=>$this->sapinterfacer->errMsg), 200);
		}
	}

	public function index_put() {
		$serviceCallList = $this->put();

		$sapInfResponse = $this->sapinterfacer->_update($serviceCallList,1,'DocEntry');

		if ($sapInfResponse) {
			$obj = $this->sapinterfacer->get_object();
			$this->response(array('message'=>'Service Call '.$obj->DocNum.'(DocNum) has been Successfully Updated'), 200);
		} else {
			$this->response(array('message'=>$this->sapinterfacer->errMsg), 200);
		}
	}

}

/* End of file sc.php */
/* Location: ./application/controllers/api/sc.php */