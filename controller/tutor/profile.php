<?php
class ControllerTutorProfile extends Controller {
	private $error = array();

	public function index() {
		if (!$this->user->isLogged()) {
			$this->session->data['redirect'] = HTTPS_SERVER . 'index.php?route=account/edit';
			$this->redirect(HTTPS_SERVER . 'index.php?route=account/login');
		}

		$this->language->load('tutor/profile');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('tutor/profile');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_tutor_profile->editTutor($this->session->data['user_id'], $this->request->post);
			log_activity("Profile Updated", "Tutor profile details updated.");
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect(HTTPS_SERVER . 'index.php?route=tutor/profile&token=' . $this->session->data['token']);
		}

		if (isset($this->session->data['success'])) {
    		$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

      	$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	); 

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTPS_SERVER . 'index.php?route=tutor/profile&token=' . $this->session->data['token'],
        	'text'      => $this->language->get('text_edit'),
        	'separator' => $this->language->get('text_separator')
      	);
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_your_details'] = $this->language->get('text_your_details');

    	$this->data['entry_firstname'] = $this->language->get('entry_firstname');
    	$this->data['entry_lastname'] = $this->language->get('entry_lastname');
    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_cellphone'] = $this->language->get('entry_cellphone');
    	$this->data['entry_password'] = $this->language->get('entry_password');
    	$this->data['entry_confirm'] = $this->language->get('entry_confirm');
    	$this->data['entry_user_group'] = $this->language->get('entry_user_group');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_notes'] = $this->language->get('entry_notes');
		$this->data['entry_post_secondary_education'] = $this->language->get('entry_post_secondary_education');
		$this->data['entry_subjects_studied'] = $this->language->get('entry_subjects_studied');
		
		$this->data['entry_courses_available'] = $this->language->get('entry_courses_available');
		$this->data['entry_previous_experience'] = $this->language->get('entry_previous_experience');
		$this->data['entry_cities'] = $this->language->get('entry_cities');
		$this->data['entry_references'] = $this->language->get('entry_references');
		$this->data['entry_gender'] = $this->language->get('entry_gender');
		$this->data['entry_certified_teacher'] = $this->language->get('entry_certified_teacher');
		$this->data['entry_criminal_conviction'] = $this->language->get('entry_criminal_conviction');
		$this->data['entry_background_check'] = $this->language->get('entry_background_check');
		$this->data['entry_subjects'] = $this->language->get('entry_subjects');

		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['firstname'])) {
			$this->data['error_firstname'] = $this->error['firstname'];
		} else {
			$this->data['error_firstname'] = '';
		}

 		if (isset($this->error['lastname'])) {
			$this->data['error_lastname'] = $this->error['lastname'];
		} else {
			$this->data['error_lastname'] = '';
		}
		
 		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}
		
 		if (isset($this->error['home_phone'])) {
			$this->data['error_telephone'] = $this->error['home_phone'];
		} else {
			$this->data['error_telephone'] = '';
		}
		
 		if (isset($this->error['cell_phone'])) {
			$this->data['error_cellphone'] = $this->error['cell_phone'];
		} else {
			$this->data['error_cellphone'] = '';
		}

 		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}
		
 		if (isset($this->error['confirm'])) {
			$this->data['error_confirm'] = $this->error['confirm'];
		} else {
			$this->data['error_confirm'] = '';
		}
		
 		if (isset($this->error['address'])) {
			$this->data['error_address_1'] = $this->error['address'];
		} else {
			$this->data['error_address_1'] = '';
		}
		
 		if (isset($this->error['city'])) {
			$this->data['error_city'] = $this->error['city'];
		} else {
			$this->data['error_city'] = '';
		}
		
 		if (isset($this->error['state'])) {
			$this->data['error_zone'] = $this->error['state'];
		} else {
			$this->data['error_zone'] = '';
		}
		
 		if (isset($this->error['pcode'])) {
			$this->data['error_pcode'] = $this->error['pcode'];
		} else {
			$this->data['error_pcode'] = '';
		}		
		
 		if (isset($this->error['country'])) {
			$this->data['error_country'] = $this->error['country'];
		} else {
			$this->data['error_country'] = '';
		}
		
 		if (isset($this->error['users_note'])) {
			$this->data['error_notes'] = $this->error['users_note'];
		} else {
			$this->data['error_notes'] = '';
		}	
		
 		if (isset($this->error['post_secondary_education'])) {
			$this->data['error_post_secondary_education'] = $this->error['post_secondary_education'];
		} else {
			$this->data['error_post_secondary_education'] = '';
		}
		
 		if (isset($this->error['subjects_studied'])) {
			$this->data['error_subjects_studied'] = $this->error['subjects_studied'];
		} else {
			$this->data['error_subjects_studied'] = '';
		}
		
 		if (isset($this->error['courses_available'])) {
			$this->data['error_courses_available'] = $this->error['courses_available'];
		} else {
			$this->data['error_courses_available'] = '';
		}
		
 		if (isset($this->error['previous_experience'])) {
			$this->data['error_previous_experience'] = $this->error['previous_experience'];
		} else {
			$this->data['error_previous_experience'] = '';
		}
		
 		if (isset($this->error['cities'])) {
			$this->data['error_cities'] = $this->error['cities'];
		} else {
			$this->data['error_cities'] = '';
		}
		
 		if (isset($this->error['references'])) {
			$this->data['error_references'] = $this->error['references'];
		} else {
			$this->data['error_references'] = '';
		}
		
 		if (isset($this->error['gender'])) {
			$this->data['error_gender'] = $this->error['gender'];
		} else {
			$this->data['error_gender'] = '';
		}
		
 		if (isset($this->error['certified_teacher'])) {
			$this->data['error_certified_teacher'] = $this->error['certified_teacher'];
		} else {
			$this->data['error_certified_teacher'] = '';
		}
		
 		if (isset($this->error['criminal_conviction'])) {
			$this->data['error_criminal_conviction'] = $this->error['criminal_conviction'];
		} else {
			$this->data['error_criminal_conviction'] = '';
		}
		
 		if (isset($this->error['background_check'])) {
			$this->data['error_background_check'] = $this->error['background_check'];
		} else {
			$this->data['error_background_check'] = '';
		}

		$this->data['action'] = HTTPS_SERVER . 'index.php?route=tutor/profile&token=' . $this->session->data['token'];

		if ($this->request->server['REQUEST_METHOD'] != 'POST') {
			$user_info = $this->model_tutor_profile->getTutor($this->user->getId());
		}
		
    	if (isset($this->request->post['firstname'])) {
      		$this->data['firstname'] = $this->request->post['firstname'];
		} elseif (isset($user_info)) { 
			$this->data['firstname'] = $user_info['firstname'];
		} else {
      		$this->data['firstname'] = '';
    	}

    	if (isset($this->request->post['lastname'])) {
      		$this->data['lastname'] = $this->request->post['lastname'];
    	} elseif (isset($user_info)) { 
			$this->data['lastname'] = $user_info['lastname'];
		} else {
      		$this->data['lastname'] = '';
    	}
		
    	if (isset($this->request->post['home_phone'])) {
      		$this->data['home_phone'] = $this->request->post['home_phone'];
    	} elseif (isset($user_info)) { 
			$this->data['home_phone'] = $user_info['home_phone'];
		} else {
      		$this->data['home_phone'] = '';
    	}
		
    	if (isset($this->request->post['cell_phone'])) {
      		$this->data['cell_phone'] = $this->request->post['cell_phone'];
    	} elseif (isset($user_info)) { 
			$this->data['cell_phone'] = $user_info['cell_phone'];
		} else {
      		$this->data['cell_phone'] = '';
    	}

    	if (isset($this->request->post['email'])) {
      		$this->data['email'] = $this->request->post['email'];
    	} elseif (isset($user_info)) { 
			$this->data['email'] = $user_info['email'];
		} else {
      		$this->data['email'] = '';
    	}
		
    	if (isset($this->request->post['password'])) { 
			$this->data['password'] = $this->request->post['password'];
		} else {
			$this->data['password'] = '';
		}
		
		if (isset($this->request->post['confirm'])) { 
    		$this->data['confirm'] = $this->request->post['confirm'];
		} else {
			$this->data['confirm'] = '';
		}
		
    	if (isset($this->request->post['address'])) {
      		$this->data['address'] = $this->request->post['address'];
    	} elseif (isset($user_info)) { 
			$this->data['address'] = $user_info['address'];
		} else {
      		$this->data['address'] = '';
    	}	
		
    	if (isset($this->request->post['city'])) {
      		$this->data['city'] = $this->request->post['city'];
    	} elseif (isset($user_info)) { 
			$this->data['city'] = $user_info['city'];
		} else {
      		$this->data['city'] = '';
    	}	
		
    	if (isset($this->request->post['state'])) {
      		$this->data['state'] = $this->request->post['state'];
    	} elseif (isset($user_info)) { 
			$this->data['state'] = $user_info['state'];
		} else {
      		$this->data['state'] = '';
    	}	
    	
    	$all_states = $this->zone($this->data['state']);
		$this->data['list_states'] = $all_states;
		
    	if (isset($this->request->post['pcode'])) {
      		$this->data['pcode'] = $this->request->post['pcode'];
    	} elseif (isset($user_info)) { 
			$this->data['pcode'] = $user_info['pcode'];
		} else {
      		$this->data['pcode'] = '';
    	}
		
    	if (isset($this->request->post['country'])) {
      		$this->data['country'] = $this->request->post['country'];
    	} elseif (isset($user_info)) { 
			$this->data['country'] = $user_info['country'];
		} else {
      		$this->data['country'] = '';
    	}
    	
    	$all_countries = $this->country($this->data['country']);
		$this->data['list_country'] = $all_countries;
		
    	if (isset($this->request->post['users_note'])) {
      		$this->data['users_note'] = $this->request->post['users_note'];
    	} elseif (isset($user_info)) { 
			$this->data['users_note'] = $user_info['users_note'];
		} else {
      		$this->data['users_note'] = '';
    	}
		
    	if (isset($this->request->post['post_secondary_education'])) {
      		$this->data['post_secondary_education'] = $this->request->post['post_secondary_education'];
    	} elseif (isset($user_info)) { 
			$this->data['post_secondary_education'] = $user_info['post_secondary_education'];
		} else {
      		$this->data['post_secondary_education'] = '';
    	}	
		
    	if (isset($this->request->post['subjects_studied'])) {
      		$this->data['subjects_studied'] = $this->request->post['subjects_studied'];
    	} elseif (isset($user_info)) { 
			$this->data['subjects_studied'] = $user_info['subjects_studied'];
		} else {
      		$this->data['subjects_studied'] = '';
    	}	
		
    	if (isset($this->request->post['courses_available'])) {
      		$this->data['courses_available'] = $this->request->post['courses_available'];
    	} elseif (isset($user_info)) { 
			$this->data['courses_available'] = $user_info['courses_available'];
		} else {
      		$this->data['courses_available'] = '';
    	}	
		
    	if (isset($this->request->post['previous_experience'])) {
      		$this->data['previous_experience'] = $this->request->post['previous_experience'];
    	} elseif (isset($user_info)) { 
			$this->data['previous_experience'] = $user_info['previous_experience'];
		} else {
      		$this->data['previous_experience'] = '';
    	}	
		
    	if (isset($this->request->post['cities'])) {
      		$this->data['cities'] = $this->request->post['cities'];
    	} elseif (isset($user_info)) { 
			$this->data['cities'] = $user_info['cities'];
		} else {
      		$this->data['cities'] = '';
    	}	
		
    	if (isset($this->request->post['references'])) {
      		$this->data['references'] = $this->request->post['references'];
    	} elseif (isset($user_info)) { 
			$this->data['references'] = $user_info['references'];
		} else {
      		$this->data['references'] = '';
    	}	
		
    	if (isset($this->request->post['gender'])) {
      		$this->data['gender'] = $this->request->post['gender'];
    	} elseif (isset($user_info)) { 
			$this->data['gender'] = $user_info['gender'];
		} else {
      		$this->data['gender'] = '';
    	}	
		
    	if (isset($this->request->post['certified_teacher'])) {
      		$this->data['certified_teacher'] = $this->request->post['certified_teacher'];
    	} elseif (isset($user_info)) { 
			$this->data['certified_teacher'] = $user_info['certified_teacher'];
		} else {
      		$this->data['certified_teacher'] = '';
    	}	
		
    	if (isset($this->request->post['criminal_conviction'])) {
      		$this->data['criminal_conviction'] = $this->request->post['criminal_conviction'];
    	} elseif (isset($user_info)) { 
			$this->data['criminal_conviction'] = $user_info['criminal_conviction'];
		} else {
      		$this->data['criminal_conviction'] = '';
    	}
		
    	if (isset($this->request->post['background_check'])) {
      		$this->data['background_check'] = $this->request->post['background_check'];
    	} elseif (isset($user_info)) { 
			$this->data['background_check'] = $user_info['background_check'];
		} else {
      		$this->data['background_check'] = '';
    	}
		
		// get all subjects
		$all_subjects = $this->model_tutor_profile->getAllSubjects();
		$this->data['all_subjects'] = $all_subjects;
		
		$this->data['all_subject_ids'] = array();
		if(isset($this->request->post['subjects']) || $this->request->server['REQUEST_METHOD'] == 'POST') {
			
			if(isset($this->request->post['subjects']))
			if(count($this->request->post['subjects']) > 0)
				$this->data['all_subject_ids'] = $this->request->post['subjects'];				
		} else {
			$this->data['all_subject_ids'] = $this->model_tutor_profile->getTutorSubjects($this->session->data['user_id']);
		}				

		$this->data['back'] = HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'];
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/tutor/profile.tpl')) {
			$this->template = $this->config->get('config_template') . '/tutor/profile.tpl';
		} else {
			$this->template = 'tutor/profile.tpl';
		}
		
		$this->children = array(
			'common/footer',
			'common/header'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));		
	}
	
	public function country($cid) {
		$output = '';
		
		$this->load->model('localisation/country');
		
		$results = $this->model_localisation_country->getCountries();
		
		foreach ($results as $result) {
			$output .= '<option value="' . $result['code'] . '"';

			if (isset($cid) && ($cid == $result['code'])) {
				$output .= ' selected="selected"';
			}

			$output .= '>' . $result['name'] . '</option>';
		}

		if (!$results) {
			$output .= '<option value="">' . $this->language->get('text_none') . '</option>';
		}

		return $output;
	}
	
	public function zone($zid) {
		$output = '';
		
		$this->load->model('localisation/zone');
		
		$results = $this->model_localisation_zone->getAllZones();
		
		foreach ($results as $result) {
			$output .= '<option value="' . $result['code'] . '"';

			if (isset($zid) && ($zid == $result['code'])) {
				$output .= ' selected="selected"';
			}

			$output .= '>' . $result['name'] . '</option>';
		}

		if (!$results) {
			$output .= '<option value="">' . $this->language->get('text_none') . '</option>';
		}

		return $output;
	}

	private function validate() {

    	if ((strlen(utf8_decode($this->request->post['firstname'])) < 1) || (strlen(utf8_decode($this->request->post['firstname'])) > 32)) {
      		$this->error['firstname'] = $this->language->get('error_firstname');
    	}

    	if ((strlen(utf8_decode($this->request->post['lastname'])) < 1) || (strlen(utf8_decode($this->request->post['lastname'])) > 32)) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}
    	
		if ((strlen(utf8_decode($this->request->post['email'])) > 96) || (!preg_match(EMAIL_PATTERN, $this->request->post['email']))) {
      		$this->error['email'] = $this->language->get('error_email');
    	}
		
		if($this->model_tutor_profile->validateEmail($this->request->post['email'], $this->session->data['user_id'])){
			$this->error['email'] = $this->language->get('error_emailexist');
		}

    	if ((strlen(utf8_decode($this->request->post['home_phone'])) < 3) || (strlen(utf8_decode($this->request->post['home_phone'])) > 32)) {
      		$this->error['home_phone'] = $this->language->get('error_telephone');
    	}
		
    	if ((strlen(utf8_decode($this->request->post['address'])) < 3) || (strlen(utf8_decode($this->request->post['address'])) > 128)) {
      		$this->error['address'] = $this->language->get('error_address_1');
    	}
		
    	if ((strlen(utf8_decode($this->request->post['city'])) < 3) || (strlen(utf8_decode($this->request->post['city'])) > 32)) {
      		$this->error['city'] = $this->language->get('error_city');
    	}

    	if (utf8_decode($this->request->post['state'])=="") {
      		$this->error['state'] = $this->language->get('error_zone');
    	}
		
    	if ((strlen(utf8_decode($this->request->post['pcode'])) < 4) || (strlen(utf8_decode($this->request->post['pcode'])) > 10)) {
      		$this->error['pcode'] = $this->language->get('error_pcode');
    	}	
		
    	if (utf8_decode($this->request->post['country'])=="") {
      		$this->error['country'] = $this->language->get('error_country');
    	}
		
    	if (strlen(utf8_decode($this->request->post['users_note'])) < 3) {
      		$this->error['users_note'] = $this->language->get('error_notes');
    	}
		
    	if (strlen(utf8_decode($this->request->post['post_secondary_education'])) < 3) {
      		$this->error['post_secondary_education'] = $this->language->get('error_post_secondary_education');
    	}
		
    	if (strlen(utf8_decode($this->request->post['subjects_studied'])) < 3) {
      		$this->error['subjects_studied'] = $this->language->get('error_subjects_studied');
    	}
		
    	if (strlen(utf8_decode($this->request->post['courses_available'])) < 3) {
      		$this->error['courses_available'] = $this->language->get('error_courses_available');
    	}
		
    	if (strlen(utf8_decode($this->request->post['previous_experience'])) < 3) {
      		$this->error['previous_experience'] = $this->language->get('error_previous_experience');
    	}
		
    	if (strlen(utf8_decode($this->request->post['cities'])) < 3) {
      		$this->error['cities'] = $this->language->get('error_cities');
    	}
		
    	if (strlen(utf8_decode($this->request->post['references'])) < 3) {
      		$this->error['references'] = $this->language->get('error_references');
    	}
		
    	if (utf8_decode($this->request->post['certified_teacher'])=="") {
      		$this->error['certified_teacher'] = $this->language->get('error_certified_teacher');
    	}
		
    	if (utf8_decode($this->request->post['criminal_conviction'])=="") {
      		$this->error['criminal_conviction'] = $this->language->get('error_criminal_conviction');
    	}
		
    	if (utf8_decode($this->request->post['background_check'])=="") {
      		$this->error['background_check'] = $this->language->get('error_background_check');
    	}

    	if ($this->request->post['password']!="") {
      		if ((strlen(utf8_decode($this->request->post['password'])) < 4) || (strlen(utf8_decode($this->request->post['password'])) > 20)) {
        		$this->error['password'] = $this->language->get('error_password');
      		}
	
	  		if ($this->request->post['password'] != $this->request->post['confirm']) {
	    		$this->error['confirm'] = $this->language->get('error_confirm');
	  		}
    	}
    	
    	if (count($this->error) > 0) {
    		$this->error['warning'] = $this->language->get('error_warning');
    	}
		
    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}
	}
}
?>