<?php
class ControllerTutorEssays extends Controller { 
	private $error = array();

	public function index() {
		$this->load->language('cms/essays');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('tutor/essays');
		$this->getList();
	}

	public function update() {
		$this->load->language('cms/essays');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('tutor/essays');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->model_tutor_essays->editInformation($this->request->get['essay_id'], $this->request->post);
			log_activity("Essay Updated", "Essay assignment details updated.");
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=tutor/essays&token=' . $this->session->data['token'] . $url);
		}

		$this->getForm();
	}

	
	
	public function upload_attachment() {
	
		set_time_limit(6000); //100 minutes
	
		$this->load->language('cms/essays');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('tutor/essays');
		$this->load->model('cms/essays');
		$this->load->model('account/student');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
		
			$essay_id = $this->request->get['essay_id'];
		
			$file_controls = array("attachment_1","attachment_2","attachment_3");
		
			$this->session->data['error'] = '';
			$this->session->data['success'] = '';
			
			foreach($file_controls as $file_control_name)
			{
				if(empty($_FILES[$file_control_name]['type'])) //file is provided
				{
					continue;
				}
				elseif ($_FILES[$file_control_name]["error"] > 0)
				{
					$this->session->data['error'] .= "<br>".$_FILES[$file_control_name]["error"];
					continue;
				}
				elseif($_FILES[$file_control_name]["size"] > 10000000 ) //10MB approx match file size
				{
					$this->session->data['error'] .= "<br>"."File size exceeds 10000000 bytes";
					continue;
				}
				else
				{

					//Check image type. Must not be PHP.
					$file_type = strtolower(end(explode(".", $_FILES[$file_control_name]["name"])));
					if(strpos($file_type,'php') !== false)
					{
						$this->session->data['error'] .= "<br>"."Image type $file_type is not permitted!";	
						continue;
					}
					else
					{ 
						define("ABSPATH_ATTCH_FILE", dirname(dirname(dirname(dirname(__FILE__))))."/");
//						echo(ABSPATH_ATTCH_FILE);
						
						$org_file_name = $_FILES[$file_control_name]['name'];
						$file_name = preg_replace('/[^a-zA-Z0-9_ \.-]/s', '', str_replace(' ','-',$_FILES[$file_control_name]['name']));
						$file_name = rand (1,9999).'-'.strtotime('now').'-'.strtolower($file_name);
						if(!move_uploaded_file($_FILES[$file_control_name]["tmp_name"],ABSPATH_ATTCH_FILE.'essay_attachments/'.$file_name ))
						{
							$this->session->data['error'] .= "<br>"."File Upload Failed!";				
						}
						else
						{
							//insert into table essay_assignment_attachments
							$this->model_tutor_essays->addAttachmentInformation($essay_id, $file_name);
							
							$essay_info = $this->model_tutor_essays->getInformation($essay_id);
							//echo '<br> stud email: '.$essay_info['student_email'];
							
							$to = $essay_info['student_email'];
							//$to = 'shabbir.tavawala@softronikx.com'; //temp
							
							$student_mail = $this->model_account_student->getMailFormat('10');
							
							$subject = $student_mail['broadcasts_subject']. " - A". $essay_info['assignment_num'];
							$message = $student_mail['broadcasts_content'];
			
							$replace_info = array(
									"asignment_link" => "http://www.learnon.ca/download.php?essay=".$essay_id."&assign=".urlencode($file_name) 
								);
							
							foreach($replace_info as $rep_key => $rep_text) {
								$message = str_replace('@'.$rep_key.'@', $rep_text, $message);
							}
			
							/*
							$message = "Hi, <br/> \n Below is the link of your completed assignment: <br/> \n http://www.learnon.ca/download.php?essay=".$essay_id."&assign=".urlencode($file_name)." <br/> \n  Thanks, <br/> \n  Team Learnon!" ;							
							$subject = 'Essay Assignment Uploaded';
							*/
							
							$mail = new PHPMailer();

$mail->From = 'info@ehomework.ca';
$mail->FromName = 'Info@Ehomework';
$mail->addAddress($to);  // Add a recipient
$mail->addBCC('postings@ehomework.ca');
  // Add a recipient

$mail->addAttachment(ABSPATH_ATTCH_FILE.'essay_attachments/'.$file_name);         // Add attachments
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = $subject;
$mail->Body    = html_entity_decode($message, ENT_QUOTES, 'UTF-8');

if(!$mail->send()) {
//   echo 'Message could not be sent.';
//   echo 'Mailer Error: ' . $mail->ErrorInfo;
//   exit;
}

//echo 'Message has been sent';
							
							
							
							/*$mail = new Mail($this);
							$mail->protocol = $this->config->get('config_mail_protocol');
							$mail->parameter = $this->config->get('config_mail_parameter');
							$mail->hostname = $this->config->get('config_smtp_host');
							$mail->username = $this->config->get('config_smtp_username');
							$mail->password = $this->config->get('config_smtp_password');
							$mail->port = $this->config->get('config_smtp_port');
							$mail->timeout = $this->config->get('config_smtp_timeout');
							$mail->setTo($to);
							$mail->setFrom('info@ehomework.ca');
							//$mail->addheader('Bcc','Bcc: postings@ehomework.ca');//nikhil.oza@softronikx.com
							$mail->addheader('Bcc','Bcc: nikhil.oza@softronikx.com');//nikhil.oza@softronikx.com
							$mail->setSender($this->config->get('config_name'));
							$mail->setSubject($subject);
							$mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
							$mail->addAttachment(ABSPATH_ATTCH_FILE.'essay_attachments/'.$file_name);
							$mail->send();*/
							
							$this->session->data['success'] .= "<br>"."$org_file_name Uploaded Succesfully!";
							
							
						}
					}
				}
			}
			
			//$this->model_tutor_essays->editInformation($this->request->get['essay_id'], $this->request->post);
			
			// assignment status as tutor completed - 4
			$this->model_cms_essays->updateAssignmentStatus($this->request->get['essay_id'],4);
			
			log_activity("Essay Assignments Uploaded", "Essay assignments uploaded.");
			
			$url = '';
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=tutor/essays&token=' . $this->session->data['token'] . $url);
		}

		$this->getAttachmentForm();
	}

	
	private function getAttachmentForm() {      		
		$this->data['heading_title'] = $this->language->get('heading_title');
	
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		//$this->data['entry_student_name'] = $this->language->get('entry_student_name');
		$this->data['entry_student_name'] = 'Assignment #';//Modified 7th May, 2013 by Softronikx Technologies as Student name is Confidential and must not appear in Tutors Login
		$this->data['entry_topic'] = $this->language->get('entry_topic');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_format'] = $this->language->get('entry_format');
		$this->data['entry_tutor_price'] = $this->language->get('entry_tutor_price');
		$this->data['entry_date_assigned'] = $this->language->get('entry_date_assigned');
		$this->data['entry_date_completed'] = $this->language->get('entry_date_completed');
		$this->data['entry_due_date'] = $this->language->get('entry_due_date');
		$this->data['entry_status'] = $this->language->get('entry_status');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['date_completed'])) {
			$this->data['error_date_completed'] = $this->error['date_completed'];
		} else {
			$this->data['error_date_completed'] = '';
		}
		
		if (isset($this->error['status'])) {
			$this->data['error_status'] = $this->error['status'];
		} else {
			$this->data['error_status'] = '';
		}
		
		$url = '';
			
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=tutor/essays&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=tutor/essays&token=' . $this->session->data['token'] . $url;

		if (isset($this->request->get['essay_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$information_info = $this->model_tutor_essays->getInformation($this->request->get['essay_id']);
		}
		
		if (isset($this->request->post['student_name'])) {
			$this->data['student_name'] = $this->request->post['student_name'];
		} elseif (isset($information_info)) {
			$this->data['student_name'] = $information_info['student_name'];
		} else {
			$this->data['student_name'] = "";
		}	
		
		$this->data['student_name'] = $information_info['assignment_num'];//Added on 7th May, 2013 by Softronikx Technologies as Student name is Confidential and must not appear in Tutors Login
		
		if (isset($this->request->post['topic'])) {
			$this->data['topic'] = $this->request->post['topic'];
		} elseif (isset($information_info)) {
			$this->data['topic'] = $information_info['topic'];
		} else {
			$this->data['topic'] = "";
		}
		
		if (isset($this->request->post['description'])) {
			$this->data['description'] = $this->request->post['description'];
		} elseif (isset($information_info)) {
			$this->data['description'] = $information_info['description'];
		} else {
			$this->data['description'] = "";
		}
		
		if (isset($this->request->post['format'])) {
			$this->data['format'] = $this->request->post['format'];
		} elseif (isset($information_info)) {
			$this->data['format'] = $information_info['format'];
		} else {
			$this->data['format'] = "";
		}
		
		if (isset($this->request->post['tutor_price'])) {
			$this->data['tutor_price'] = $this->request->post['tutor_price'];
		} elseif (isset($information_info)) {
			$this->data['tutor_price'] = $information_info['paid'];
		} else {
			$this->data['tutor_price'] = "";
		}
		
		if (isset($this->request->post['date_assigned'])) {
			$this->data['date_assigned'] = $this->request->post['date_assigned'];
		} elseif (isset($information_info)) {
			$this->data['date_assigned'] = date('Y-m-d', strtotime($information_info['date_assigned']));
		} else {
			$this->data['date_assigned'] = date('Y-m-d');
		}
		
		if (isset($this->request->post['date_completed'])) {
			$this->data['date_completed'] = $this->request->post['date_completed'];
		} elseif (isset($information_info)) {
			if($information_info['date_completed']!=""&&$information_info['date_completed']!="0000-00-00 00:00:00")
			$this->data['date_completed'] = date('Y-m-d', strtotime($information_info['date_completed']));
			else
			$this->data['date_completed'] = "";
		} else {
			$this->data['date_completed'] = "";
		}
		
		
		if (isset($this->request->post['due_date'])) {
			$this->data['due_date'] = $this->request->post['due_date'];
		} elseif (isset($information_info)) {
			$this->data['due_date'] = date('Y-m-d', strtotime($information_info['date_due']));
		} else {
			$this->data['due_date'] = "";
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($information_info)) {
			$this->data['status'] = $information_info['current_status'];
		} else {
			$this->data['status'] = 0;
		}
		
		$all_status = array();
		$results = $this->model_tutor_essays->getEssaysStatus();
		foreach ($results as $result) {
			$all_status[] = array(
				'status_id' => $result['essay_status_id'],
				'status_name' => $result['name'],
			);
		}
		
		$this->data['all_status'] = $all_status;
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=tutor/essays/upload_attachment&token=' . $this->session->data['token'] . '&essay_id=' . $this->request->get['essay_id'] . $url;
		$this->template = 'tutor/attachment_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	
	private function getList() {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'date_assinged';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		$url = '';
			
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=tutor/essays&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		$this->data['informations'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$information_total = $this->model_tutor_essays->getTotalInformations();
	
		$results = $this->model_tutor_essays->getInformations($data);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => "View/Edit",
				'href' => HTTPS_SERVER . 'index.php?route=tutor/essays/update&token=' . $this->session->data['token'] . '&essay_id=' . $result['essay_id'] . $url
			);
			
			$action[] = array(
				'text' => "Upload Assignment",
				'href' => HTTPS_SERVER . 'index.php?route=tutor/essays/upload_attachment&token=' . $this->session->data['token'] . '&essay_id=' . $result['essay_id'] . $url
			);
						
			$this->data['informations'][] = array(
				'essay_id' => $result['essay_id'],
				'topic'      => $result['topic'],
				'assignment_num' => "A".$result['assignment_num'], //softronikx technologies
				'student_name' => $result['student_name'],
				'status' => $result['curr_status'],
				'due_date' => date('Y-m-d', strtotime($result['date_due'])),
				'date_assigned' => date('Y-m-d', strtotime($result['date_assigned'])),
				'selected'   => isset($this->request->post['selected']) && in_array($result['essay_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_student_name'] = $this->language->get('column_student_name');
		$this->data['column_topic'] = $this->language->get('column_topic');
		$this->data['column_date_assigned'] = $this->language->get('column_date_assigned');
		$this->data['column_due_date'] = $this->language->get('column_due_date');
		$this->data['column_topic'] = $this->language->get('column_topic');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_action'] = $this->language->get('column_action');		
		$this->data['column_assignment_num'] = $this->language->get('column_assignment_num');		
		 
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_student_name'] = 'javascript:void(0)'; //HTTPS_SERVER . 'index.php?route=tutor/essays&token=' . $this->session->data['token'] . '&sort=id.title' . $url;
		$this->data['sort_tutor_name'] = 'javascript:void(0)';
		$this->data['sort_topic'] = 'javascript:void(0)';
		$this->data['sort_status'] = 'javascript:void(0)';
		$this->data['sort_date_assigned'] = HTTPS_SERVER . 'index.php?route=tutor/essays&token=' . $this->session->data['token'] . '&sort=date_assigned' . $url;;
		$this->data['sort_due_date'] = HTTPS_SERVER . 'index.php?route=tutor/essays&token=' . $this->session->data['token'] . '&sort=date_due' . $url;;
		$this->data['sort_assignment_num'] = HTTPS_SERVER . 'index.php?route=tutor/essays&token=' . $this->session->data['token'] . '&sort=assignment_num' . $url;;
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $information_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=tutor/essays&token=' . $this->session->data['token'] . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'tutor/essays_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function getForm() {      		
		$this->data['heading_title'] = $this->language->get('heading_title');
	
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		//$this->data['entry_student_name'] = $this->language->get('entry_student_name');
		$this->data['entry_student_name'] = 'Assignment #';//Modified 7th May, 2013 by Softronikx Technologies as Student name is Confidential and must not appear in Tutors Login
		$this->data['entry_topic'] = $this->language->get('entry_topic');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_format'] = $this->language->get('entry_format');
		$this->data['entry_tutor_price'] = $this->language->get('entry_tutor_price');
		$this->data['entry_date_assigned'] = $this->language->get('entry_date_assigned');
		$this->data['entry_date_completed'] = $this->language->get('entry_date_completed');
		$this->data['entry_due_date'] = $this->language->get('entry_due_date');
		$this->data['entry_status'] = $this->language->get('entry_status');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['date_completed'])) {
			$this->data['error_date_completed'] = $this->error['date_completed'];
		} else {
			$this->data['error_date_completed'] = '';
		}
		
		if (isset($this->error['status'])) {
			$this->data['error_status'] = $this->error['status'];
		} else {
			$this->data['error_status'] = '';
		}
		
		$url = '';
			
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=tutor/essays&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=tutor/essays&token=' . $this->session->data['token'] . $url;

		if (isset($this->request->get['essay_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$information_info = $this->model_tutor_essays->getInformation($this->request->get['essay_id']);
			
			$this->data['attachments_info'] = $this->model_tutor_essays->getAttachmentInformation($this->request->get['essay_id']);
			
			
		}
		
		if (isset($this->request->post['student_name'])) {
			$this->data['student_name'] = $this->request->post['student_name'];
		} elseif (isset($information_info)) {
			$this->data['student_name'] = $information_info['student_name'];
		} else {
			$this->data['student_name'] = "";
		}	
		
		$this->data['student_name'] = $information_info['assignment_num'];//Added on 7th May, 2013 by Softronikx Technologies as Student name is Confidential and must not appear in Tutors Login
		
		if (isset($this->request->post['topic'])) {
			$this->data['topic'] = $this->request->post['topic'];
		} elseif (isset($information_info)) {
			$this->data['topic'] = $information_info['topic'];
		} else {
			$this->data['topic'] = "";
		}
		
		if (isset($this->request->post['description'])) {
			$this->data['description'] = $this->request->post['description'];
		} elseif (isset($information_info)) {
			$this->data['description'] = $information_info['description'];
		} else {
			$this->data['description'] = "";
		}
		
		if (isset($this->request->post['format'])) {
			$this->data['format'] = $this->request->post['format'];
		} elseif (isset($information_info)) {
			$this->data['format'] = $information_info['format'];
		} else {
			$this->data['format'] = "";
		}
		
		if (isset($this->request->post['tutor_price'])) {
			$this->data['tutor_price'] = $this->request->post['tutor_price'];
		} elseif (isset($information_info)) {
			$this->data['tutor_price'] = $information_info['paid'];
		} else {
			$this->data['tutor_price'] = "";
		}
		
		if (isset($this->request->post['date_assigned'])) {
			$this->data['date_assigned'] = $this->request->post['date_assigned'];
		} elseif (isset($information_info)) {
			$this->data['date_assigned'] = date('Y-m-d', strtotime($information_info['date_assigned']));
		} else {
			$this->data['date_assigned'] = date('Y-m-d');
		}
		
		if (isset($this->request->post['date_completed'])) {
			$this->data['date_completed'] = $this->request->post['date_completed'];
		} elseif (isset($information_info)) {
			if($information_info['date_completed']!=""&&$information_info['date_completed']!="0000-00-00 00:00:00")
			$this->data['date_completed'] = date('Y-m-d', strtotime($information_info['date_completed']));
			else
			$this->data['date_completed'] = "";
		} else {
			$this->data['date_completed'] = "";
		}
		
		
		if (isset($this->request->post['due_date'])) {
			$this->data['due_date'] = $this->request->post['due_date'];
		} elseif (isset($information_info)) {
			$this->data['due_date'] = date('Y-m-d', strtotime($information_info['date_due']));
		} else {
			$this->data['due_date'] = "";
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($information_info)) {
			$this->data['status'] = $information_info['current_status'];
		} else {
			$this->data['status'] = 0;
		}
		
		$all_status = array();
		$results = $this->model_tutor_essays->getEssaysStatus();
		foreach ($results as $result) {
			$all_status[] = array(
				'status_id' => $result['essay_status_id'],
				'status_name' => $result['name'],
			);
		}
		
		$this->data['all_status'] = $all_status;
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=tutor/essays/update&token=' . $this->session->data['token'] . '&essay_id=' . $this->request->get['essay_id'] . $url;
		$this->template = 'tutor/essays_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
}
?>