<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sq extends REST_Controller {

	protected $xmlDir = "C:\/xampp\htdocs\service\xml\quation.xml";

	public function __construct() {
		parent::__construct();

		$this->load->library('sapinterfacer');
		$this->sapinterfacer->set_object(23);
	}

	public function add_quotation_post() {
		$quotationList = $this->post();
		
		$sapInfResponse = $this->sapinterfacer->_add($quotationList);

		if ($sapInfResponse) {
			$this->response(array('message'=>'Sales Quote Successfully Added'), 200);
		} else {
			$this->response(array('message'=>$this->sapinterfacer->errMsg), 200);
		}
	}

	public function update_quotation_put() {
		$quotationUpdatedList = $this->put();

		$sapInfResponse = $this->sapinterfacer->_update($quotationUpdatedList,1,'DocEntry');

		if ($sapInfResponse) {
			$quotation =  $this->sapinterfacer->get_object();
			$this->response(array('message'=>'Sales Quote '.$quotation->DocNum.'(DocNum) has been Successfully Updated'), 200);
		} else {
			$this->response(array('message'=>$this->sapinterfacer->errMsg), 200);
		}
	}
}

/* End of file sales_quotation.php */
/* Location: ./application/controllers/api/sales_quotation.php */