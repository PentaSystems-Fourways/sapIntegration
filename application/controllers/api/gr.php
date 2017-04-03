<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gr extends REST_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->library('sapinterfacer');
		$this->sapinterfacer->set_object(20);
	}

	public function add_receipt_post() {
		$receiptList = $this->post();
		
		$receipt = $this->sapinterfacer->get_object();

		$this->sapinterfacer->set_properties($receiptList, array('Type','Serial'));

		if ($receiptList['Type'] == 1) {
			$this->sapinterfacer->_add_serial($receiptList['Serial']);
		}

		$sapInfResponse = $receipt->add();

		if ($sapInfResponse == 0) {
			$this->response(array('message'=>'Goods Receipt Successfully Added'), 200);
		} else {
			$this->response(array('message'=>$this->sapinterfacer->get_error($sapInfResponse)), 200);
		}
	}
}

/* End of file gr.php */
/* Location: ./application/controllers/api/gr.php */