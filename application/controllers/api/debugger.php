<?php defined('BASEPATH') OR exit('No direct script access allowed');

class debugger extends REST_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('sapinterfacer');
		$this->sapinterfacer->set_object(97);
	}

	public function index_get() {
		$this->response(array('message'=>'Sever successfully logged in'), 200);
	}	
}
