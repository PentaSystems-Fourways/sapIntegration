<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pq extends REST_Controller {


	public function __construct() {
		parent::__construct();

		$this->load->library('sapinterfacer');
		$this->sapinterfacer->set_object(540000006);
	}

	public function add_quotation_post() {
		$quotationList = $this->post();
		
		$sapInfResponse = $this->sapinterfacer->_add($quotationList);

		if ($sapInfResponse) {
			$this->response(array('message'=>'Purchase Quotation Successfully Added'), 200);
		} else {
			$this->response(array('message'=>$this->sapinterfacer->errMsg), 200);
		}
	}

	public function update_quotation_put() {
		$quotationUpdatedList = $this->put();

		$sapInfResponse = $this->sapinterfacer->_update($quotationUpdatedList,1,'DocEntry');

		if ($sapInfResponse) {
			$quotation = $this->sapinterfacer->get_object();
			$this->response(array('message'=>'Purchase Quotation '.$quotation->DocNum.'(DocNum) has been Successfully Updated'), 200);
		} else {
			$this->response(array('message'=>$this->sapinterfacer->errMsg), 200);
		}
	}

}

/* End of file pq.php */
/* Location: ./application/controllers/api/pq.php */