<?php 
class ControllerSaleNotify extends Controller {
	private $error = array();
	 
	public function index() {
		$this->load->language('sale/notify');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('sale/user');
		$this->load->model('sale/user_group');
		
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/store');
		
			$store_info = $this->model_setting_store->getStore($this->request->post['store_id']);			
			
			if ($store_info) {
				$store_name = $store_info['name'];
			} else {
				$store_name = $this->config->get('config_name');
			}
			
			$emails = array();
			
			switch ($this->request->post['to']) {
				case 'user_all':
					$results = $this->model_sale_user->getUsers();
					foreach ($results as $result) {
						$emails[$result['user_id']] = $result['email'];
					}						
					break;
				case 'user_group':
					$post_active = 0;
					if(isset($this->request->post['active_tutors']))					
						$post_active = 1;
						
					$filter_data =  array(
						'filter_user_group_id' => $this->request->post['user_group_id'],
						'filter_active_tutors' => $post_active
					);
						
					$results = $this->model_sale_user->getUsers($filter_data);
					foreach ($results as $result) {
						$emails[$result['user_id']] = $result['email'];
					}						
					break;
				case 'user':
					if (isset($this->request->post['user'])) {					
						foreach ($this->request->post['user'] as $user_id) {
							$user_info = $this->model_sale_user->getUser($user_id);
							
							if ($user_info) {
								$emails[$user_info['user_id']] = $user_info['email'];
							}
						}
					}
					break;																						
			}
			
			/*print_r($emails);
			die;*/
			
			$emails = array_unique($emails);
			
			if ($emails) {
				$message  = '<html dir="ltr" lang="en">' . "\n";
				$message .= '<head>' . "\n";
				$message .= '<title>' . $this->request->post['subject'] . '</title>' . "\n";
				$message .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
				$message .= '</head>' . "\n";
				$message .= '<body>' . html_entity_decode($this->request->post['message'], ENT_QUOTES, 'UTF-8') . '</body>' . "\n";
				$message .= '</html>' . "\n";

				$attachments = array();

				if (preg_match_all('#(src="([^"]*)")#mis', $message, $matches)) {
					foreach ($matches[2] as $key => $value) {
						$filename = md5(basename($value)) . strrchr($value, '.');
						$path = rtrim($this->request->server['DOCUMENT_ROOT'], '/') . parse_url($value, PHP_URL_PATH);
						
						$attachments[] = array(
							'filename' => $filename,
							'path'     => $path
						);
						
						$message = str_replace($value, 'cid:' . $filename, $message);
					}
				}	
				
				foreach ($emails as $key=>$email) {
					$mail = new Mail();	
					$mail->protocol = $this->config->get('config_mail_protocol');
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->hostname = $this->config->get('config_smtp_host');
					$mail->username = $this->config->get('config_smtp_username');
					$mail->password = $this->config->get('config_smtp_password');
					$mail->port = $this->config->get('config_smtp_port');
					$mail->timeout = $this->config->get('config_smtp_timeout');				
					$mail->setTo($email);
					$mail->setFrom($this->config->get('config_email'));
					$mail->setSender($store_name);
					$mail->setSubject($this->request->post['subject']);					
					
					foreach ($attachments as $attachment) {
						$mail->addAttachment($attachment['path'], $attachment['filename']);
					}
					
					$mail->setHtml($message);
					if(isset($this->request->post['send_email'])){
						$mail->send();
					}
					$this->load->model('cms/notifications');
					$data = array(
					"notification_from"=>$this->session->data['user_id'],
					"notification_to"=>$key,
					"subject"=>$this->request->post['subject'],
					"message"=>$this->request->post['message']
					);
					$this->model_cms_notifications->addInformation($data);
				}
			}
			
			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_user_all'] = $this->language->get('text_user_all');	
		$this->data['text_user'] = $this->language->get('text_user');	
		$this->data['text_user_group'] = $this->language->get('text_user_group');

		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_to'] = $this->language->get('entry_to');
		$this->data['entry_user_group'] = $this->language->get('entry_user_group');
		$this->data['entry_user'] = $this->language->get('entry_user');
		$this->data['entry_subject'] = $this->language->get('entry_subject');
		$this->data['entry_message'] = $this->language->get('entry_message');
		
		$this->data['button_send'] = $this->language->get('button_send');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->data['tab_general'] = $this->language->get('tab_general');
		
		$this->data['token'] = $this->session->data['token'];
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
 		if (isset($this->error['subject'])) {
			$this->data['error_subject'] = $this->error['subject'];
		} else {
			$this->data['error_subject'] = '';
		}
	 	
		if (isset($this->error['message'])) {
			$this->data['error_message'] = $this->error['message'];
		} else {
			$this->data['error_message'] = '';
		}	

  		$this->document->breadcrumbs = array();
   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);
   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=sale/notify&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
				
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
				
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=sale/notify&token=' . $this->session->data['token'];
    	$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=sale/notify&token=' . $this->session->data['token'];

		if (isset($this->request->post['store_id'])) {
			$this->data['store_id'] = $this->request->post['store_id'];
		} else {
			$this->data['store_id'] = '';
		}
		
		$this->load->model('setting/store');
		$this->data['stores'] = $this->model_setting_store->getStores();
		
		if (isset($this->request->post['to'])) {
			$this->data['to'] = $this->request->post['to'];
		} else {
			$this->data['to'] = '';
		}
				
		if (isset($this->request->post['user_group_id'])) {
			$this->data['user_group_id'] = $this->request->post['user_group_id'];
		} else {
			$this->data['user_group_id'] = '';
		}
				
		$this->data['user_groups'] = $this->model_sale_user_group->getUserGroups(0);
		$this->data['users'] = array();
		
		if (isset($this->request->post['user'])) {					
			foreach ($this->request->post['user'] as $user_id) {
				$user_info = $this->model_sale_user->getUser($user_id);
					
				if ($user_info) {
					$this->data['users'][] = array(
						'user_id' => $user_info['user_id'],
						'name'        => $user_info['firstname'] . ' ' . $user_info['lastname']
					);
				}
			}
		}
				
		if (isset($this->request->post['subject'])) {
			$this->data['subject'] = $this->request->post['subject'];
		} else {
			$this->data['subject'] = '';
		}
		
		if (isset($this->request->post['active_tutors'])) {
			$this->data['active_tutors'] = 'Y';
		} else {
			$this->data['active_tutors'] = '';
		}
		
		if (!isset($this->request->post['send_email'])) {
			$this->data['send_email'] = 'no';
		} else {
			$this->data['send_email'] = 'yes';
		}
		
		if (isset($this->request->post['message'])) {
			$this->data['message'] = $this->request->post['message'];
		} else {
			$this->data['message'] = '';
		}

		$this->template = 'sale/notify.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'sale/notify')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
				
		if (!$this->request->post['subject']) {
			$this->error['subject'] = $this->language->get('error_subject');
		}

		if (!$this->request->post['message']) {
			$this->error['message'] = $this->language->get('error_message');
		}
						
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>