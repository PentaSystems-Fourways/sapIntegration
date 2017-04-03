<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SapInterfacer {

	protected $businessObj;

	protected $sapCom;

	protected $retCode;

	private $itemCode;

	private $errorMessages = array('{itemType} "{userInput}" does not exist', 
								   'No data is specified',
								   'CardCode "{$userInput}" Exceeds the limit of 15 characters specified on documentation',
								   '{itemType} (Mandatory Field) is not specified. Please make sure this field is specified.');

	private $user_defined = array('U_ClubName','U_SCOpenDt', 'U_SCOpenTm', 'U_SCRespDt', 'U_SCRespTm', 'U_SCClosDt', 'U_SCClosTm', 'U_SCBrkType', 'U_SCVATechPreCheck', 
		'U_SCPSTechFTypAcc', 'U_SCPSTechFDescAcc', 'U_SCVATechPrtReqAcc', 'U_SCPSTechPrtDelAcc', 'U_SCVATechSelfFix', 'U_SCVATechPresent', 'U_SCVAPMState', 'U_SCVAPMHist', 
		'U_SCVATechSerRptAcc', 'U_SCVATechNPS', 'U_SCPSPartAvail', 'U_ClubName', 'U_Contact', 'U_U_SCRequest', 'U_Tel', 'U_SCVATechPreCheckN', 'U_SCVATechPrtReqAccN', 
		'U_SCVATechPrtReqAccD', 'U_SCPSTechPrtDelAccN', 'U_SCPSTechPrtDelAccD', 'U_SCVATechSelfFixM', 'U_SCVATechSelfFixN', 'U_SCVATechPresentP', 'U_SCVATechPresentN', 
		'U_SCVAPMStateA', 'U_SCVAPMStateN', 'U_SCVAPMHistN', 'U_SCVATechSerRptAccN', 'U_SCPSPartAvailN', 'U_SCAccessNum', 'U_SCFunc', 'U_WEBLOG');
	
	public $errCode = 0;
	public $errMsg = '';
 
	public function __construct() {

		try {
			$this->sapCom = new COM("SAPbobsCOM.company") or die ("No connection");

			$this->sapCom->server = "SAPSERVER";
			$this->sapCom->CompanyDB = "PENTA_LIVE";
			$this->sapCom->username = "dir002";
			$this->sapCom->password = "nivek";
			$this->sapCom->DbServerType = "6";
			
			$lRetCode = $this->sapCom->Connect;

			if ($lRetCode != 0) {
				echo $this->get_error();
				return;
			}

		} catch (com_exception $e) {
			echo $e->getMessage();
		}

	}

	public function set_object($obj) {	
		$this->businessObj = $this->sapCom->GetBusinessObject($obj);
	}

	public function &get_object() {
		return $this->businessObj;
	}

	public function get_service_object() {
		return $this->sapCom->GetCompanyService();
	}

	protected function set_error($errCode) {

		$this->sapCom->GetLastError($errCode, $this->errMsg);

		if ($this->errMsg == '') {
			$this->errMsg = $this->sapCom->GetLastErrorDescription();
		}
	}

	public function get_error($errCode = 0) {
		
		if ($this->errMsg == '') {
			$this->set_error($errCode);
		}

		return $this->errMsg;
	}

	public function _add($addList) {

		if (count($addList) == 0) {
			$this->errMsg = $this->custom_errro(1);
			return 0;
		}
		
		try {	
			$this->set_properties($addList);

			$RetCode = $this->businessObj->add();

			if ($RetCode == 0) {
				return 1;
			} else {
				$this->set_error($RetCode); 
				return 0;
			}

		} catch (com_exception $e) {
			$this->errMsg = $e->getMessage();
			return 0;
		}

	}

	public function _update($updatedList, $itemTypeCode = 0, $itemType = 'CardCode') {

		if (count($updatedList) == 0) {
			$this->errMsg = $this->custom_errro(1);
			return 0;
		}

		if (!isset($updatedList[$itemType])) {
			$this->errMsg = $this->custom_errro(3,'',$itemType);
			return 0;
		}

		$this->itemCode = $updatedList[$itemType];
		unset($updatedList[$itemType]);

		if ($itemTypeCode = 0) {
			if (strlen($this->itemCode) > 15) {
				$this->errMsg = $this->custom_errro(2,$this->itemCode);
				return 0;
			}
		} 
		
		try {
			
			$vItem = $this->businessObj->GetByKey($this->itemCode);

			if (!$vItem) {
				$this->errMsg = $this->custom_errro(0, $this->itemCode, $itemType);
				return 0;
			}
			
			$this->set_properties($updatedList);

			$RetCode = $this->businessObj->update();

			if ($RetCode == 0) {
				return 1;
			} else {
				$this->set_error($RetCode); 
				return 0;
			}

		} catch (com_exception $e) {
			$this->errMsg = $e->getMessage();
			return 0;
		}

	}

	public function set_properties ($itemList, $exclusion = array()) {
		foreach ($itemList as $key => $value) {

			if (in_array($key, $exclusion)) {
				continue;
			}

			
			if (in_array($key, $this->user_defined)) {
				$this->businessObj->UserFields->Fields->Item($key)->Value = $value;
				continue;
			}

			$key = explode('_', $key);

			if (count($key) <= 1) {	
				$this->businessObj->$key[0] = $value;
			} else {
				
				if ($key[1] == 'SetCurrentLine') {	
					$this->businessObj->$key[0]->$key[1]($value);
					continue;
				} 

				if ($key[1] == 'Add') {
					$this->businessObj->$key[0]->add;
					continue;
				}

				$this->businessObj->$key[0]->$key[1] = $value;
			}
		}
	}

	public function _add_serial($serial) {
		$this->businessObj->Lines->SerialNumbers->ManufacturerSerialNumber = $serial;
		$this->businessObj->Lines->SerialNumbers->InternalSerialNumber = $serial;
		$this->businessObj->Lines->SerialNumbers->add();
	}

	protected function custom_errro($errCode, $userInput = '', $itemType = '') {
		return str_replace(array('{itemType}','{userInput}'), array($itemType,$userInput), $this->errorMessages[$errCode]);
	}

}

/* End of file SapInterfacer.php */
/* Location: ./application/libraries/SapInterfacer.php */
