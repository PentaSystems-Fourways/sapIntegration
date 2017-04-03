<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Op extends REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('sapinterfacer');
		$this->sapinterfacer->set_object(97);
	}

	public function index_post() {
		$opportunityList = $this->post();

		$sapInfResponse = $this->sapinterfacer->_add($opportunityList);

		if ($sapInfResponse) {
			$this->response(array('message'=>'Sales Opportunity Successfully Added'), 200);
		} else {
			$this->response(array('message'=>$this->sapinterfacer->errMsg), 200);
		}
	}

	public function index_put() {
		$opportunityList = $this->put();

		$sapInfResponse = $this->sapinterfacer->_update($opportunityList,1,'OpprId');

		if ($sapInfResponse) {
			$this->response(array('message'=>'Sales Opportunity '.$opportunityList["OpprId"].' (OpprId) has been Successfully Updated'), 200);
		} else {
			$this->response(array('message'=>$this->sapinterfacer->errMsg), 200);
		}
	}

}

/* End of file op.php */
/* Location: ./application/controllers/api/op.php */