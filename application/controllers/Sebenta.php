<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	public function index()
	{
		$this->load->database();
		$response = $this->db->query('SHOW DATABASES;')->result_array();


		$this->output
				->set_content_type('application/json', 'utf-8')
				->set_status_header(200)
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
		exit;
	}
}
