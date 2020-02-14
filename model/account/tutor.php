<?php
class ModelAccountTutor extends Model {
	private $user_group_id = "2";
	private $status = "1";
	private $approved = "1";

	public function addTutor($data) {
		$agreement = str_replace('<input name="name1" type="text" value="" />', '<b>'.$data['name1'].'</b>', html_entity_decode($data['agreement_text']));
		$agreement = str_replace('<input name="name2" type="text" value="" />', '<b>'.$data['name2'].'</b>', $agreement);
		$agreement = str_replace('<input name="name3" type="text" value="" />', '<b>'.$data['name3'].'</b>', $agreement);
			
		if($data['criminal_conviction']!="2"){
			$this->status = "0";
			$this->approved = "0";
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['email']) . "', password = '" . $this->db->escape(md5($data['password'])) . "', firstname = '" . ucfirst(strtolower($this->db->escape($data['firstname']))) . "', lastname = '" . ucfirst(strtolower($this->db->escape($data['lastname']))) . "', email = '" . $this->db->escape($data['email']) . "', user_group_id = '" . (int)$this->user_group_id . "', status = '" .  (int)$this->status . "', approved = '" .  (int)$this->approved . "', date_added = NOW(), ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");
		 
		$user_id = $this->db->getLastId();
		 
		if ($user_id!="") {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "user_info` SET country = '" . $this->db->escape($data['country']) . "', pcode = '" . $this->db->escape(strtoupper($data['pcode'])) . "', address = '" . $this->convert_address($this->db->escape($data['address'])) . "', city = '" . ucfirst(strtolower($this->db->escape($data['city']))) . "', state = '" . $this->db->escape($data['state']) . "', home_phone = '" . $this->db->escape($data['home_phone']) . "', cell_phone = '" . $this->db->escape($data['cell_phone']) . "', users_note = '" . $this->db->escape($data['users_note']) . "', post_secondary_education = '" . $this->db->escape($data['post_secondary_education']) . "', subjects_studied = '" . $this->db->escape($data['subjects_studied']) . "', courses_available = '" . $this->db->escape($data['courses_available']) . "', previous_experience = '" . $this->db->escape($data['previous_experience']) . "', cities = '" . $this->db->escape($data['cities']) . "', `references` = '" . $this->db->escape($data['references']) . "', gender = '" . $this->db->escape($data['gender']) . "', certified_teacher = '" . $this->db->escape($data['certified_teacher']) . "', criminal_conviction = '" . $this->db->escape($data['criminal_conviction']) . "', background_check = '" . $this->db->escape($data['background_check']) . "', agreement = '" . $this->db->escape($agreement) . "' ");
		}
		return $user_id;
	}

	public function editTutor($user_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['email']) . "', firstname = '" . ucfirst(strtolower($this->db->escape($data['firstname']))) . "', lastname = '" . ucfirst(strtolower($this->db->escape($data['lastname']))) . "', email = '" . $this->db->escape($data['email']) . "', user_group_id = '" . (int)$this->user_group_id . "', status = '" . (int)$data['status'] . "' WHERE user_id = '" . (int)$user_id . "'");

		if ($data['password']) {
			$this->db->query("UPDATE `" . DB_PREFIX . "user` SET password = '" . $this->db->escape(md5($data['password'])) . "' WHERE user_id = '" . (int)$user_id . "'");
		}
			
		$this->db->query("UPDATE `" . DB_PREFIX . "user_info` SET country = '" . $this->db->escape($data['country']) . "', pcode = '" . $this->db->escape(strtoupper($data['pcode'])) . "', address = '" . $this->convert_address($this->db->escape($data['address'])) . "', city = '" . ucfirst(strtolower($this->db->escape($data['city']))) . "', state = '" . $this->db->escape($data['state']) . "', home_phone = '" . $this->db->escape($data['home_phone']) . "', cell_phone = '" . $this->db->escape($data['cell_phone']) . "', users_note = '" . $this->db->escape($data['users_note']) . "', post_secondary_education = '" . $this->db->escape($data['post_secondary_education']) . "', subjects_studied = '" . $this->db->escape($data['subjects_studied']) . "', courses_available = '" . $this->db->escape($data['courses_available']) . "', previous_experience = '" . $this->db->escape($data['previous_experience']) . "', cities = '" . $this->db->escape($data['cities']) . "', `references` = '" . $this->db->escape($data['references']) . "', gender = '" . $this->db->escape($data['gender']) . "', certified_teacher = '" . $this->db->escape($data['certified_teacher']) . "', criminal_conviction = '" . $this->db->escape($data['criminal_conviction']) . "', background_check = '" . $this->db->escape($data['background_check']) . "' WHERE user_id = '" . $user_id . "' ");
	}


	public function getTutorAgreement() {
		$information_id = 5;

		$query = $this->db->query("SELECT DISTINCT * FROM ".DB_PREFIX."information WHERE information_id = '".(int)$information_id."'  AND status = '1'");

		return $query->row;
	}

	public function getMailFormat($format_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "broadcasts WHERE broadcasts_id = '" . (int)$format_id . "'");

		return $query->row;
	}

	public function validateEmail($email) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user WHERE email = '" . $email . "'");
		return $query->row['total'];
	}

	public function validateUsername($username) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user WHERE username = '" . $username . "'");
		return $query->row['total'];
	}

	public function convert_address($address)
	{
		$street_array  = array('Street',
'Avenue',
'Boulevard',
'Place',
'Court',
'Crescent',
'Circle',
'Parkway',
'Drive',
'Road',
		);

		$street_rep_array  = array('St.',
'Ave.',
'Blvd.',
'Plc.',
'Ct.',
'Cres.',
'Crl.',
'Pkwy.',
'Dr.',
'Rd.',
		);

		return str_replace($street_array,$street_rep_array,ucwords($address));

	}
}
?>