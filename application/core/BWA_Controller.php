<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 * @* @author Bruno Martins - <brunonunes.martins@my.istec.pt> <b.gabriel.ma@gmail.com>
 * @* @version 1.0
 * @* @since 1.0
 */

class BWA_Controller extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * @* @param Array <code, message> - response data
	 * 
	 * @* @return json - response 400 http with his respective response
	 */
	public function throwInvalidAccess() {
		$this->output
				->set_content_type('application/json', 'utf-8')
				->set_status_header(400)
				->set_output(json_encode(array('code' => 400, 'message' => 'Invalid request from client'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
		exit;
	}

	/**
	 * @* @param Array <code, message> - response data
	 * 
	 * @* @return json - response 200 or 400 http with his respective response
	 */
	public function output($response) {
		$this->output
				->set_content_type('application/json', 'utf-8')
				->set_status_header( ($response['code'] == 200 ? 200 : 500) )
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
		exit;
	}
}


?>
