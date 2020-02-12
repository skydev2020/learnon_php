<?php

# This file has changed for 1.4.9.4 based on the optimization proposal here: http://forum.opencart.com/viewtopic.php?f=24&t=19000

class ControllerSettingSetting extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('setting/setting');
		$this->load->model('cms/settings');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			if(isset($this->request->post['grade_subjects']) && !empty($this->request->post['grade_subjects'])) {
				$this->load->model('setting/grades');
				
				$grade_id = $this->request->post['grade_id'];
				$subjects = $this->request->post['grade_subjects'];
				$subjects = explode(",", $subjects);
				
				$this->model_setting_grades->updateGradeSubjects($grade_id, $subjects);						
			}
			
			if (isset($this->request->post['config_token_ignore'])) {
				$this->request->post['config_token_ignore'] = serialize($this->request->post['config_token_ignore']);
			}

			$this->model_setting_setting->editSetting('config', $this->request->post);

			if ($this->config->get('config_currency_auto')) {
				$this->load->model('localisation/currency');

				$this->model_localisation_currency->updateCurrencies();
			}
			
            $this->model_cms_settings->editSetting($this->request->post['wageid'], $this->request->post);
			log_activity("Settings Updated", "System settings updated.");
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect(HTTPS_SERVER . 'index.php?route=setting/setting&token=' . $this->session->data['token']);
		}

		# Load language file
		$this->data = array_merge($this->data, $this->load->language('setting/setting'));

		$this->data['token'] = $this->session->data['token'];

		# Error array
		$errors = array(
		   'warning',
		   'name',
		   'url',
		   'owner',
		   'address',
		   'email',
		   'email2',
		   'email3',
		   'masterpassword',
		   'telephone',
		   'title',
		   'image_thumb',
		   'image_popup',
		   'image_category',
		   'image_product',
		   'image_additional',
		   'image_related',
		   'image_cart',
		   'error_filename',
		   'catalog_limit',
		   'admin_limit'
		);

		foreach($errors as $error) {
			if (isset($this->error[$error])) {
				$this->data['error_' . $error] = $this->error[$error];
			} else {
				$this->data['error_' . $error] = '';
			}
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=setting/setting&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=setting/store/insert&token=' . $this->session->data['token'];

		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=setting/setting&token=' . $this->session->data['token'];

		$this->data['action'] = HTTPS_SERVER . 'index.php?route=setting/setting&token=' . $this->session->data['token'];

		$this->data['stores'] = array();

		$this->data['stores'][] = array(
			'name' => $this->language->get('text_default'),
			'href' => HTTPS_SERVER . 'index.php?route=setting/setting&token=' . $this->session->data['token']
		);

		$this->load->model('setting/store');

		$results = $this->model_setting_store->getStores();

		foreach ($results as $result) {
			$this->data['stores'][] = array(
				'name' => $result['name'],
				'href' => HTTPS_SERVER . 'index.php?route=setting/store/update&token=' . $this->session->data['token'] . '&store_id=' . $result['store_id']
			);
		}
		
		$this->load->model('setting/subjects');		
		$this->data['text_subject'] = 'Subject Name: ';
		
		$this->data['error_subject'] = '';
		
		if(isset($this->request->post['subject_name'])){
			$this->data['subject_name'] = $this->request->post['subject_name'];
		}else{
			$this->data['subject_name'] = "";
		}
		
		$this->data['entry_minimum_bill'] = 'Process Minimum Bill';
		$this->data['button_add_subject'] = 'Add Subject';
		$this->data['button_delete_subject'] = 'Delete Selected Subject';
		$this->data['subjects'] = $this->model_setting_subjects->getInformations();
			
		$this->data['error_wage_usa'] = '';
		$this->data['error_wage_canada'] = '';
		$this->data['error_wage_alberta'] = '';
		$this->data['error_invoice_usa'] = '';
		$this->data['error_invoice_canada'] = '';
		$this->data['error_invoice_alberta'] = '';

		$wages = $this->model_cms_settings->getSetting();
		$this->data['wage_usa'] = $wages['wage_usa'];
		$this->data['wage_canada'] = $wages['wage_canada'];
		$this->data['wage_alberta'] = $wages['wage_alberta'];
		$this->data['invoice_usa'] = $wages['invoice_usa'];
		$this->data['invoice_canada'] = $wages['invoice_canada'];
		$this->data['invoice_alberta'] = $wages['invoice_alberta'];
		$this->data['wageid'] = $wages['wageid'];
		
		$this->load->model('setting/grades');		
		$this->data['text_grade'] = 'Grade Name: ';
		$this->data['text_subjects'] = 'Subjects: ';
				
		if(isset($this->request->post['grade_name'])){
			$this->data['grade_name'] = $this->request->post['grade_name'];
		}else{
			$this->data['grade_name'] = "";
		}
		
		$this->data['error_grade'] = '';
		$this->data['button_add_grade'] = 'Add Grade';
		$this->data['button_delete_grade'] = 'Delete Grade';
		$this->data['grades'] = $this->model_setting_grades->getInformations();
		

		$this->data['templates'] = array();

		$directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);

		foreach ($directories as $directory) {
			$this->data['templates'][] = basename($directory);
		}

		$this->load->model('localisation/country');

		$this->data['countries'] = $this->model_localisation_country->getCountries();

		$this->load->model('localisation/currency');

		$this->data['currencies'] = $this->model_localisation_currency->getCurrencies();
		
		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->load->model('tool/image');

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		$this->data['languages'] = $languages;

		foreach ($languages as $language) {
			if (isset($this->request->post['config_description_' . $language['language_id']])) {
				$this->data['config_description_' . $language['language_id']] = $this->request->post['config_description_' . $language['language_id']];
			} else {
				$this->data['config_description_' . $language['language_id']] = $this->config->get('config_description_' . $language['language_id']);
			}
		}

		# List all settings in an array
		$settings = array(
			'config_name',
			'config_url',
			'config_owner',
			'config_address',
			'config_email',
			'config_email2',
			'config_email3',
			'config_masterpassword',
			'config_telephone',
			'config_fax',
			'config_minimum_bill',
			'config_title',
			'config_meta_description',
			'config_template',
			'config_country_id',
			'config_zone_id',
			'config_language',
			'config_admin_language',
			'config_currency',
			'config_currency_auto',
			'config_length_class',
			'config_weight_class',
			'config_tax',
			'config_customer_group_id',
			'config_customer_price',
			'config_customer_approval',
			'config_guest_checkout',
			'config_account_id',
			'config_checkout_id',
			'config_stock_display',
			'config_stock_warning',
			'config_stock_checkout',
			'config_order_status_id',
			'config_stock_status_id',
			'config_download',
			'config_download_status',
			'config_shipping_session',
			'config_admin_limit',
			'config_catalog_limit',
			'config_cart_weight',
			'config_pinvoice_prefix',
			'config_invoice_prefix',
			'config_pinvoice_no',
			'config_invoice_no',
			'config_review',
			'config_logo',
			'config_icon',
			'config_image_thumb_width',
			'config_image_thumb_height',
			'config_image_popup_width',
			'config_image_popup_height',
			'config_image_category_width',
			'config_image_category_height',
			'config_image_product_width',
			'config_image_product_height',
			'config_image_additional_width',
			'config_image_additional_height',
			'config_image_related_width',
			'config_image_related_height',
			'config_image_cart_width',
			'config_image_cart_height',
			'config_mail_protocol',
			'config_smtp_host',
			'config_smtp_port',
			'config_smtp_timeout',
			'config_smtp_username',
			'config_smtp_password',
			'config_account_mail',
			'config_alert_mail',
			'config_alert_emails',
			'config_mail_parameter',
			'config_ssl',
			'config_maintenance',
			'config_encryption',
			'config_seo_url',
			'config_compression',
			'config_error_display',
			'config_error_log',
			'config_error_filename',

		);

		# Loop through all settings for the post/config values
		foreach ($settings as $setting) {
			if (isset($this->request->post[$setting])) {
				$this->data[$setting] = $this->request->post[$setting];
			} else {
				$this->data[$setting] = $this->config->get($setting);
			}
		}

		# Special Case values
		if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
			$this->data['preview_logo'] = HTTPS_IMAGE . $this->config->get('config_logo');
		} else {
			$this->data['preview_logo'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->data['preview_icon'] = HTTPS_IMAGE . $this->config->get('config_icon');
		} else {
			$this->data['preview_icon'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		if (!$this->data['config_invoice_prefix']) {
			$this->data['config_invoice_prefix'] = 'INV-' . date('Y') . '-00';
		}
		
		if (!$this->data['config_pinvoice_prefix']) {
			$this->data['config_pinvoice_prefix'] = 'PKG-' . date('Y') . '-00';
		}
		
		if (!$this->data['config_invoice_no']) {
			$this->data['config_invoice_no'] = '';
		}
		
		if (!$this->data['config_pinvoice_no']) {
			$this->data['config_pinvoice_no'] = '';
		}

		if (!$this->data['config_smtp_port']) {
			$this->data['config_smtp_port'] = 25;
		}

		if (!$this->data['config_smtp_timeout']) {
			$this->data['config_smtp_timeout'] = 5;
		}

		$ignore = array(
			'common/login',
			'common/logout',
			'error/not_found',
			'error/permission'
		);

		$this->data['tokens'] = array();

		$files = glob(DIR_APPLICATION . 'controller/*/*.php');

		foreach ($files as $file) {
			$data = explode('/', dirname($file));

			$token = end($data) . '/' . basename($file, '.php');

			if (!in_array($token, $ignore)) {
				$this->data['tokens'][] = $token;
			}
		}

		if (isset($this->request->post['config_token_ignore'])) {
			$this->data['config_token_ignore'] = $this->request->post['config_token_ignore'];
		} elseif ($this->config->get('config_token_ignore')) {
			$this->data['config_token_ignore'] = unserialize($this->config->get('config_token_ignore'));
		} else {
			$this->data['config_token_ignore'] = array();
		}

		$this->template = 'setting/setting.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'setting/setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['config_name']) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!$this->request->post['config_url']) {
			$this->error['url'] = $this->language->get('error_url');
		}

		if ((strlen(utf8_decode($this->request->post['config_owner'])) < 3) || (strlen(utf8_decode($this->request->post['config_owner'])) > 64)) {
			$this->error['owner'] = $this->language->get('error_owner');
		}

		if ((strlen(utf8_decode($this->request->post['config_address'])) < 3) || (strlen(utf8_decode($this->request->post['config_address'])) > 256)) {
			$this->error['address'] = $this->language->get('error_address');
		}

		if (!$this->request->post['config_title']) {
			$this->error['title'] = $this->language->get('error_title');
		}

    	if ((strlen(utf8_decode($this->request->post['config_email'])) > 96) || (!preg_match(EMAIL_PATTERN, $this->request->post['config_email']))) {
      		$this->error['email'] = $this->language->get('error_email');
    	}
		
    	if ((strlen(utf8_decode($this->request->post['config_email2'])) > 96) || (!preg_match(EMAIL_PATTERN, $this->request->post['config_email2']))) {
      		$this->error['email2'] = $this->language->get('error_email2');
    	}
		
    	if ((strlen(utf8_decode($this->request->post['config_email3'])) > 96) || (!preg_match(EMAIL_PATTERN, $this->request->post['config_email3']))) {
      		$this->error['email3'] = $this->language->get('error_email3');
    	}
		
    	if ((strlen(utf8_decode($this->request->post['config_masterpassword'])) < 3) || (strlen(utf8_decode($this->request->post['config_telephone'])) > 20)) {
      		$this->error['masterpassword'] = $this->language->get('error_masterpassword');
    	}

    	if ((strlen(utf8_decode($this->request->post['config_telephone'])) < 3) || (strlen(utf8_decode($this->request->post['config_telephone'])) > 32)) {
      		$this->error['telephone'] = $this->language->get('error_telephone');
    	}

		if (!$this->request->post['config_image_thumb_width'] || !$this->request->post['config_image_thumb_height']) {
			$this->error['image_thumb'] = $this->language->get('error_image_thumb');
		}

		if (!$this->request->post['config_image_popup_width'] || !$this->request->post['config_image_popup_height']) {
			$this->error['image_popup'] = $this->language->get('error_image_popup');
		}

		if (!$this->request->post['config_image_category_width'] || !$this->request->post['config_image_category_height']) {
			$this->error['image_category'] = $this->language->get('error_image_category');
		}

		if (!$this->request->post['config_image_product_width'] || !$this->request->post['config_image_product_height']) {
			$this->error['image_product'] = $this->language->get('error_image_product');
		}

		if (!$this->request->post['config_image_additional_width'] || !$this->request->post['config_image_additional_height']) {
			$this->error['image_additional'] = $this->language->get('error_image_additional');
		}

		if (!$this->request->post['config_image_related_width'] || !$this->request->post['config_image_related_height']) {
			$this->error['image_related'] = $this->language->get('error_image_related');
		}

		if (!$this->request->post['config_image_cart_width'] || !$this->request->post['config_image_cart_height']) {
			$this->error['image_cart'] = $this->language->get('error_image_cart');
		}

		if (!$this->request->post['config_error_filename']) {
			$this->error['error_filename'] = $this->language->get('error_error_filename');
		}

		if (!$this->request->post['config_admin_limit']) {
			$this->error['admin_limit'] = $this->language->get('error_limit');
		}

		/*if (!$this->request->post['config_catalog_limit']) {
			$this->error['catalog_limit'] = $this->language->get('error_limit');
		}*/
		
//		print_r($this->error);

		if (!$this->error) {
			return TRUE;
		} else {
			if (!isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_required_data');
			}
			return FALSE;
		}
	}

	public function zone() {
		$output = '';

		$this->load->model('localisation/zone');

		$results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);

		foreach ($results as $result) {
			$output .= '<option value="' . $result['zone_id'] . '"';

			if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {
				$output .= ' selected="selected"';
			}

			$output .= '>' . $result['name'] . '</option>';
		}

		if (!$results) {
			$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
		}

		$this->response->setOutput($output, $this->config->get('config_compression'));
	}
	
	public function grades() {
		$this->load->model('setting/grades');		
		
		if(isset($this->request->get['grade_id'])) {
			$grade_id = $this->request->get['grade_id'];
		} else {
			$grade_id = 0;
		}
		
		switch ($this->request->get['action']) {
			case 'add':
				
				$data = array(
					'grade_name' => $this->request->get['grade_name']
				);
				
				echo $this->model_setting_grades->addInformation($data);
				
			break;
			case 'delete':
				
				echo $this->model_setting_grades->deleteInformation($this->request->get['grade_id']);
				
			break;
			case 'grade_subjects':
				$subjects_data = array();
		
				$results = $this->model_setting_grades->getSubjectsByGradeId($grade_id);
		
				foreach ($results as $result) {
					$subjects_data[] = array (
						'subjects_id' => $result['subjects_id'],
						'subjects_name' => $result['subjects_name']
					);
				}
		
				$this->load->library('json');
		
				$this->response->setOutput(Json::encode($subjects_data));			 
			break;
			case 'filter_subjects':
				$subjects_data = array();
		
				$results = $this->model_setting_grades->getFilteredSubjectsByGradeId($grade_id);
		
				foreach ($results as $result) {
					$subjects_data[] = array (
						'subjects_id' => $result['subjects_id'],
						'subjects_name' => $result['subjects_name']
					);
				}
		
				$this->load->library('json');
		
				$this->response->setOutput(Json::encode($subjects_data));
			break;
		}
		
		return '0';
	}
	
	public function subjects() {
		$this->load->model('setting/subjects');
		
		if(isset($this->request->post['subject_name'])) {
			$data = array(
				'subject_name' => $this->request->post['subject_name']
			);
			
			$results = $this->model_setting_subjects->addInformation($data);
			echo $results;
		}
		
		if(isset($this->request->post['action']) && $this->request->post['action'] == 'update') {
			$data = array();

			$results = $this->model_setting_subjects->editInformation($data);
			echo $results;
		}
		
		if(isset($this->request->post['action']) && $this->request->post['action'] == 'delete') {
			
			echo $this->model_setting_subjects->deleteInformation($this->request->post['subject_id']);
		}
	}

	public function template() {
		$template = basename($this->request->get['template']);

		if (file_exists(DIR_IMAGE . 'templates/' . $template . '.png')) {
			$image = HTTPS_IMAGE . 'templates/' . $template . '.png';
		} else {
			$image = HTTPS_IMAGE . 'no_image.jpg';
		}

		$this->response->setOutput('<img src="' . $image . '" alt="" title="" style="border: 1px solid #EEEEEE;" />');
	}
}
?>