<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wt extends REST_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->library('sapinterfacer');
		$this->sapinterfacer->set_object(67);
	}

	public function transfer_items_post() {
		$transferList = $this->post();

		$sapInfResponse = $this->sapinterfacer->_add($transferList);

		if ($sapInfResponse) {
			$this->response(array('message'=>'Warehouse Transfer Successfully Added'), 200);
		} else {
			$this->response(array('message'=>$this->sapinterfacer->errMsg), 200);
		}
	}
}

/* End of file wt.php */
/* Location: ./application/controllers/api/wt.php */