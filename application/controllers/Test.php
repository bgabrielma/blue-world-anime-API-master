<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 * @* @author Bruno Martins - <brunonunes.martins@my.istec.pt> <b.gabriel.ma@gmail.com>
 * @* @version 1.0
 * @* @since 1.0
 */

class Test extends BWA_Controller {

	function __construct() {
		parent::__construct();
    }

	public function index() {
		parent::__construct();
		parent::getMartins();
	 }
}

?>
