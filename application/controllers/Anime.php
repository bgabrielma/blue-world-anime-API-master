<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 * @* @author Bruno Martins - <brunonunes.martins@my.istec.pt> <b.gabriel.ma@gmail.com>
 * @* @version 1.0
 * @* @since 1.0
 */

class Anime extends BWA_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function getAllAnimes() {

		$final_array = new ArrayObject();

		$this->load->database();
		/*
			SELECT anime.name_anime, anime.created_date, anime.snipose, anime.number_of_episodes, 
					anime_studios.designation, anime_type.designation

			FROM 

			WHERE anime.id_type = anime_type.id_type AND anime_studios.id_studios = anime.id_studio
		*/
		$select_part = 'anime.name_anime, anime.created_date, anime.snipose, anime.number_of_episodes, '.
			'anime_studios.designation, anime_type.designation, anime.id_anime';

		$this->db->select($select_part);
		$this->db->from('anime');

		$this->db->join('anime_type', 'anime_type.id_type = anime.id_type');
		$this->db->join('anime_studios', 'anime_studios.id_studios = anime.id_studio');

		$query_for_anime = $this->db->get()->result();
		
		foreach($query_for_anime as $_iteratorArray) {
			$this->db->select('anime_gender.designation');
			$this->db->from('anime_has_genders');
	
			$this->db->join('anime', 'anime.id_anime = anime_has_genders.id_anime');
			$this->db->join('anime_gender', 'anime_gender.id_gender = anime_has_genders.id_gender');
			$this->db->where('anime_has_genders.id_anime', $_iteratorArray->id_anime);


			$enumDesignations = array();

				foreach($this->db->get()->result() as $designation) {
					array_push($enumDesignations, $designation->designation);
				}
			
			$final_array->append(array("informations" => $_iteratorArray, "designations" => $enumDesignations));
		}

		parent::output($status = array('code' => 200, 'message' => $final_array));
	}

	public function getAnimeByPostDesc() { 
		$this->load->database();
	}
	public function getAnimesByType() {
		$this->load->database();
	}
	public function getAnimesByStudio() { 
		$this->load->database();
	}
	public function getAnimesByName() {
		$this->load->database();
	 }
	public function getAnimesByGender() { 
		$this->load->database();
	}
}
