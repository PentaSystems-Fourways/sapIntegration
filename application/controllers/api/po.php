<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Po extends REST_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->library('sapinterfacer');
		$this->sapinterfacer->set_object(22);
	}

	public function add_order_post() {
		$orderList = $this->post();
		
		$sapInfResponse = $this->sapinterfacer->_add($orderList);

		if ($sapInfResponse) {
			$this->response(array('message'=>'Purchase Order Successfully Added'), 200);
		} else {
			$this->response(array('message'=>$this->sapinterfacer->errMsg), 200);
		}
	}

	public function update_order_put() {
		$orderUpdatedList = $this->put();

		$sapInfResponse = $this->sapinterfacer->_update($orderUpdatedList,1,'DocEntry');

		if ($sapInfResponse) {
			$order =  $this->sapinterfacer->get_object();
			$this->response(array('message'=>'Purchase Order '.$order->DocNum.'(DocNum) has been Successfully Updated'), 200);
		} else {
			$this->response(array('message'=>$this->sapinterfacer->errMsg), 200);
		}
	}

}

/* End of file po.php */
/* Location: ./application/controllers/api/po.php */