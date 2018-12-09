<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 * @* @author Bruno Martins - <brunonunes.martins@my.istec.pt> <b.gabriel.ma@gmail.com>
 * @* @version 1.0
 * @* @since 1.0
 */

class User extends CI_Controller {

	/**
	 * @* @return json in output function - an invalid Access - error 400
	 * @* @see User::throwInvalidAccess
	 */
	public function index() { $this->throwInvalidAccess(); }

	public function throwInvalidAccess() {
		$this->output
				->set_content_type('application/json', 'utf-8')
				->set_status_header(400)
				->set_output(json_encode(array('code' => 400, 'message' => 'Invalid request from client'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
		exit;
	}
	
	/**
	 * @* @return String $uuid - a new uuid generated by server.
	 */
	public function generateUUID() {
		$uuid = "";
		$finish = false;

		do {
			$data = random_bytes(95);
			$data[6] = chr(ord($data[6]) & 0x0f | 0x40); 
			$data[8] = chr(ord($data[8]) & 0x3f | 0x80); 
			$data[4] = chr(ord($data[4]) & 0x7f | 0x10); 
			$data[1] = chr(ord($data[1]) & 0x3f | 0x80); 
			
			
			$uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 5));
			$row = $this->db->select('*')
				->from('senpai')
				->where('uuid_senpai', $uuid)
				->count_all_results();

			if($row == 0) 
				$finish = true;

		} while(!$finish);

		return $uuid;
	}

	/**
	 * @* @return json in output function- response 200 or 400 http with his respective response
	 */
	public function getUser($uuid = NULL) {
		$this->load->database();
		$data = $this->db->get_where('senpai', array('uuid_senpai' => $uuid))->result_array();
		
		if(empty($data))
			$this->throwInvalidAccess();
		else
			$this->output($status = array('code' => 200, 'message' => $data));
	}

	/**
	 * @* @return json in output function- response 200 or 400 http with his respective response
	 */
	public function getUsersByType($type = NULL) {
		$this->load->database();

		$this->db->select('type_account.type_account as type_senpai_account, senpai.*');
		$this->db->from('senpai');
		$this->db->join('type_account', 'type_account.id_type_account = senpai.type_account');
		$this->db->where('senpai.type_account', $type);
		$query = $this->db->get()->result();

		if(empty($query))
			$this->throwInvalidAccess();
		else
			$this->output($status = array('code' => 200, 'message' => $query));
	}

	/**
	 * @* @param String nickname - nickname's senpai
	 * @* @param String firstname - firstname's senpai
	 * @* @param String surname - surname's senpai
	 * @* @param Integer type_account - type account's senpai
	 * @* @param Integer email - senpai's email
	 * @* @param Object create_date - @see in query => NOW() SQL function. @return DATETIME
	 * @* @param Object update_date - @see in query => NOW() SQL function. @return DATETIME
	 * 
	 * @* @return json in output function- response 200 or 400 http with his respective response
	 */ 	

	public function insertUser($nickname, $firstname, $surname, $type_account, $email) {
		$this->load->database();
		date_default_timezone_set("Europe/Lisbon"); 

		$data = array(
			'uuid_senpai' => $this->generateUUID(),
			'nickname' => $nickname,
			'firstname' => $firstname,
			'surname' => $surname,
			'type_account' => $type_account,
			'email' => $email,
			'create_date' => date('Y-m-d H:i:s'),
			'update_date' => date('Y-m-d H:i:s')
		);
	
		if( ! $this->db->insert('senpai', $data)) {
			$this->output($status = array('code' => 500, 'message' => $this->db->error()));
		} else {
			$this->output($status = array('code' => 200, 'message' => 'ok'));
		}
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

	/**
	 * @* @param String nickname - nickname's senpai
	 * @* @param String firstname - firstname's senpai
	 * @* @param String surname - surname's senpai
	 * @* @param Integer type_account - type account's senpai
	 * @* @param Integer email - senpai's email
	 * @* @param Object create_date - @see in query => NOW() SQL function. @return DATETIME
	 * @* @param Object update_date - @see in query => NOW() SQL function. @return DATETIME
	 * 
	 * @*  @return json - response 200 or 400 http with his respective response
	 */
	public function updateUser($nickname, $firstname, $surname, $type_account, $email, $uuid) {
		$this->load->database();
		date_default_timezone_set("Europe/Lisbon"); 

		$data = array(
			'nickname' => $nickname,
			'firstname' => $firstname,
			'surname' => $surname,
			'type_account' => $type_account,
			'email' => $email,
			'update_date' => date('Y-m-d H:i:s')
		);

		if( ! $this->db->update('senpai', $data, array('uuid_senpai' => $uuid))) {

			$this->output($status = array('code' => 500, 'message' => $this->db->error()));
		} else {
			$this->output($status = array('code' => 200, 'message' => 'ok'));
		}
	}

	/**
	 * @* @param String uuid  - User's uuid in order to delete.
	 * 
	 * @*  @return json - response 200 or 400 http with his respective response
	 */

	 public function deleteUser($uuid) {
		$this->load->database();

		if( ! $this->db->delete('senpai', array('uuid_senpai' => $uuid))) {

			$this->output($status = array('code' => 500, 'message' => $this->db->error()));
		} else {
			$this->output($status = array('code' => 200, 'message' => 'ok'));
		}
	 }

	/**
	 * @* @param String uuid  - User's uuid in order to delete.
	 * 
	 * @*  @return json - response 200 or 400 http with his respective response
	 */
	 public function disableUser($uuid) {
		$this->load->database();
		date_default_timezone_set("Europe/Lisbon"); 

		$data = array(
			'is_Active' => '0',
			'update_date' => date('Y-m-d H:i:s')
		);

		if( ! $this->db->update('senpai', $data, array('uuid_senpai' => $uuid))) {

			$this->output($status = array('code' => 500, 'message' => $this->db->error()));
		} else {
			$this->output($status = array('code' => 200, 'message' => 'ok'));
		}
	 }

	 /**
	 * @* @param String uuid  - User's uuid in order to delete.
	 * 
	 * @*  @return json - response 200 or 400 http with his respective response
	 */
	 public function enableUser($uuid) {
		$this->load->database();
		date_default_timezone_set("Europe/Lisbon"); 

		$data = array(
			'is_Active' => '1',
			'update_date' => date('Y-m-d H:i:s')
		);

		if( ! $this->db->update('senpai', $data, array('uuid_senpai' => $uuid))) {

			$this->output($status = array('code' => 500, 'message' => $this->db->error()));
		} else {
			$this->output($status = array('code' => 200, 'message' => 'ok'));
		}
	 }
}