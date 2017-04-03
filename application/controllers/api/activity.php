<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Activity extends REST_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->library('sapinterfacer');
		$this->sapinterfacer->set_object(33);
	}

	public function index_post() {
        $activity = $this->post();

		$sapInfResponse = $this->sapinterfacer->_add($activity);

		if ($sapInfResponse) {
			$this->response(array('message'=>'Activity Successfully Added'), 200);
		} else {
			$this->response(array('message'=>$this->sapinterfacer->errMsg), 200);
		}
	}

	public function index_put() {	
        $activity = $this->put();

		$sapInfResponse = $this->sapinterfacer->_update($activity,1,'ContactCode');

		if ($sapInfResponse) {
			$this->response(array('message'=>'Activity '.$activity['ContactCode'].'(ContactCode) has been Successfully Updated'), 200);
		} else {
			$this->response(array('message'=>$this->sapinterfacer->errMsg), 200);
		}
	}
}

/* End of file am.php */
/* Location: ./application/controllers/api/am.php */