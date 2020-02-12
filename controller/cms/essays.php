<?php
class ControllerCmsEssays extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('cms/essays');

		$this->document->title = $this->language->get('heading_title');
			
		$this->load->model('cms/essays');

		$this->getList();
	}

	public function upload_csv()
	{
		$this->load->language('cms/essays');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('cms/essays');
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateFormUpload()) {
			/* Code to upload CSV file data */

			//upload income file data
			$csv = array();
			// check there are no errors
			if($_FILES['upload_csv_file']['error'] == 0){
				$name = $_FILES['upload_csv_file']['name'];
				$ext = strtolower(end(explode('.', $_FILES['upload_csv_file']['name'])));
				$type = $_FILES['upload_csv_file']['type'];
				$tmpName = $_FILES['upload_csv_file']['tmp_name'];

				// check the file is a csv
				if($ext === 'csv'){
					if(($handle = fopen($tmpName, 'r')) !== FALSE) {
						// necessary if a large csv file
						set_time_limit(0);

						$row = 0;

						while(($line = fgetcsv($handle)) !== FALSE) {
							$row++;

							// number of fields in the csv
							$num = count($line);

							if($num > 5)
							{
								// get the values from the csv
								$data['assignment_num'] = substr($line[2], 1);
								$data['topic'] = $line[4];
								$data['description']=$line[4];
								$data['format'] = '';
								$data['student_name'] = $line[0];
								$data['student_email'] = $line[3];
								$data['student_id'] = $this->getUserId($line[3]);
								$data['tutor_id'] = $this->getUserId($line[10]);
								if(strtotime ($line[5]))
								{
									$data['date_assigned'] = date('Y-m-d',strtotime ($line[5]));
								}
								else
								{
									$data['date_assigned'] = '';
								}
								
								if(strtotime ($line[6]))
								{
									$data['date_completed'] = date('Y-m-d',strtotime ($line[6])); //modified softronikx 17th Dec
									$data['due_date'] = date('Y-m-d',strtotime ($line[6]));
								}
								else
								{
									$data['date_completed'] = ''; //modified softronikx 17th Dec
									$data['due_date'] = '';
								}
								$data['tutor_price'] = str_replace("$", "", $line[11]);
								$data['total_price'] = str_replace("$", "", $line[7]);
								$data['current_status'] = 1;
								$data['status'] = 1;

								if($data['tutor_id']==FALSE)
								{
									$data['current_status'] = 7;
									$data['status'] = 7;
								}
								if($data['status']==1)
								{
									$this->load->model('cms/notifications');
									$subject = "Essay assigned";
									$message = "You have been assigned an essay to complete.";
									$notification = array(
									'notification_from'=>$this->session->data['user_id'],
									'notification_to'=>$data['tutor_id'],
									'subject'=>$subject,
									'message'=>$message
									);
									$this->model_cms_notifications->addInformation($notification);
								}

								$this->model_cms_essays->addInformation_csv($data);
								unset($data);
							}
						}
						fclose($handle);
					}
				}
			}


			$this->session->data['success'] = $this->language->get('text_success_file_upload');

			log_activity("Essay Upload CSV", "Essay uploaded");

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
			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/essays&token=' . $this->session->data['token'] . $url);
		}
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_select_file'] = $this->language->get('text_income_file_name');

		$this->data['button_upload'] = $this->language->get('button_upload');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->template = 'cms/essays_form_upload.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
			);
			
			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	/* Softronikx Technologies */
	private function getUserId($email) {
		$this->load->model('cms/essays');
		return $this->model_cms_essays->getUserId($email);
	}

	/* Softronikx Technologies */
	private function validateFormUpload() {
		if ($_FILES['upload_csv_file']['name'] == "") {
			$this->error['upload_csv_file'] = $this->language->get('error_file');
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function insert() {
		$this->load->language('cms/essays');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('cms/essays');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			if($this->request->post['status']=="1"){
				$this->load->model('cms/notifications');
				$subject = "Essay assigned";
				$message = "You have been assigned an essay to complete.";
				$notification = array(
					'notification_from'=>$this->session->data['user_id'],
					'notification_to'=>$this->request->post['tutor_id'],
					'subject'=>$subject,
					'message'=>$message
				);
				$this->model_cms_notifications->addInformation($notification);
			}

			$this->request->post['assignment_num'] = substr($this->request->post['assignment_num'], 1);

			$this->model_cms_essays->addInformation($this->request->post);
			log_activity("Essay Assigned", "A new essay assigned to tutor.");
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

			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/essays&token=' . $this->session->data['token'] . $url);
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('cms/essays');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('cms/essays');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->request->post['assignment_num'] = substr($this->request->post['assignment_num'], 1);

			$this->model_cms_essays->editInformation($this->request->get['essay_id'], $this->request->post);

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

			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/essays&token=' . $this->session->data['token'] . $url);
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('cms/essays');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('cms/essays');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $essay_id) {
				$this->model_cms_essays->deleteInformation($essay_id);
			}
			log_activity("Essay(s) Deleted", "Essay assignment(s) deleted.");
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

			$this->redirect(HTTPS_SERVER . 'index.php?route=cms/essays&token=' . $this->session->data['token'] . $url);
		}

		$this->getList();
	}

	private function getList() {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		//set filter variables
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

		/* Softronikx Technologies */

		if (isset($this->request->get['filter_assignment_num'])) {
			$filter_assignment_num = $this->request->get['filter_assignment_num'];
		} else {
			$filter_assignment_num = NULL;
		}

		if (isset($this->request->get['filter_price_paid'])) {
			$filter_price_paid = $this->request->get['filter_price_paid'];
		} else {
			$filter_price_paid = NULL;
		}

		if (isset($this->request->get['filter_paid_to_tutor'])) {
			$filter_paid_to_tutor = $this->request->get['filter_paid_to_tutor'];
		} else {
			$filter_paid_to_tutor = NULL;
		}
			

		if (isset($this->request->get['filter_student_name'])) {
			$filter_student_name = $this->request->get['filter_student_name'];
		} else {
			$filter_student_name = NULL;
		}

		if (isset($this->request->get['filter_tutor_name'])) {
			$filter_tutor_name = $this->request->get['filter_tutor_name'];
		} else {
			$filter_tutor_name = NULL;
		}

		if (isset($this->request->get['filter_all'])) {
			$filter_all = $this->request->get['filter_all'];
		} else {
			$filter_all = NULL;
		}

		if (isset($this->request->get['filter_topic'])) {
			$filter_topic = $this->request->get['filter_topic'];
		} else {
			$filter_topic = NULL;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = NULL;
		}

		if (isset($this->request->get['filter_date_assigned'])) {
			$filter_date_assigned = $this->request->get['filter_date_assigned'];
		} else {
			$filter_date_assigned = NULL;
		}

		if (isset($this->request->get['filter_date_completed'])) {
			$filter_date_completed = $this->request->get['filter_date_completed'];
		} else {
			$filter_date_completed = NULL;
		}

		if (isset($this->request->get['filter_date_to_assigned'])) {
			$filter_date_to_assigned = $this->request->get['filter_date_to_assigned'];
		} else {
			$filter_date_to_assigned = NULL;
		}

		if (isset($this->request->get['filter_date_to_completed'])) {
			$filter_date_to_completed = $this->request->get['filter_date_to_completed'];
		} else {
			$filter_date_to_completed = NULL;
		}

		/* End of Code */

		// set default show only approved
		$filter_approved = '1';

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = NULL;
		}
		//end of set filter variables


		//set url variables
		$url = '';

		if (isset($this->request->get['filter_assignment_num'])) {
			$url .= '&filter_assignment_num=' . $this->request->get['filter_assignment_num'];
		}

		if (isset($this->request->get['filter_price_paid'])) {
			$url .= '&filter_price_paid=' . $this->request->get['filter_price_paid'];
		}

		if (isset($this->request->get['filter_paid_to_tutor'])) {
			$url .= '&filter_paid_to_tutor=' . $this->request->get['filter_paid_to_tutor'];
		}

		if (isset($this->request->get['filter_student_name'])) {
			$url .= '&filter_student_name=' . $this->request->get['filter_student_name'];
		}

		if (isset($this->request->get['filter_tutor_name'])) {
			$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
		}

		if (isset($this->request->get['filter_all'])) {
			$url .= '&filter_all=' . $this->request->get['filter_all'];
		}

		if (isset($this->request->get['filter_tutor_name'])) {
			$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
		}

		if (isset($this->request->get['filter_topic'])) {
			$url .= '&filter_topic=' . $this->request->get['filter_topic'];
		}

		if (isset($this->request->get['filter_subjects'])) {
			$url .= '&filter_subjects=' . $this->request->get['filter_subjects'];
		}
			
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
		}
			
		if (isset($this->request->get['filter_date_assigned'])) {
			$url .= '&filter_date_assigned=' . $this->request->get['filter_date_assigned'];
		}

		if (isset($this->request->get['filter_date_completed'])) {
			$url .= '&filter_date_completed=' . $this->request->get['filter_date_completed'];
		}
			
		if (isset($this->request->get['filter_date_to_assigned'])) {
			$url .= '&filter_date_to_assigned=' . $this->request->get['filter_date_to_assigned'];
		}

		if (isset($this->request->get['filter_date_to_completed'])) {
			$url .= '&filter_date_to_completed=' . $this->request->get['filter_date_to_completed'];
		}
			
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		//end of set url variables


		$this->document->breadcrumbs = array();

		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
		);

		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=cms/essays&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
      		);
      		 
      		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=cms/essays/insert&token=' . $this->session->data['token'] . $url;
      		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=cms/essays/delete&token=' . $this->session->data['token'] . $url;
      		$this->data['upload'] = HTTPS_SERVER . 'index.php?route=cms/essays/upload_csv&token=' . $this->session->data['token'] . $url;

      		$this->data['informations'] = array();

      		$data = array(
			'filter_assignment_num'    => $filter_assignment_num, 
			'filter_price_paid'        => $filter_price_paid, 
			'filter_paid_to_tutor'     => $filter_paid_to_tutor, 
			'filter_student_name'      => $filter_student_name, 
			'filter_tutor_name'        => $filter_tutor_name, 
			'filter_all'               => $filter_all, 
			'filter_topic'             => $filter_topic,  
			'filter_status'            => $filter_status,
			'filter_date_assigned'     => $filter_date_assigned,
			'filter_date_completed'    => $filter_date_completed,
			'filter_date_to_assigned'  => $filter_date_to_assigned,			
			'filter_date_to_completed' => $filter_date_to_completed,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
      		);

      		/*Softronikx Technologies */
      		$assignment_status = $this->model_cms_essays->getAssignmentStatus();
      		/*End of Code */

      		$information_total = $this->model_cms_essays->getTotalInformations($data);

      		$results = $this->model_cms_essays->getInformations($data);

      		foreach ($results as $result) {
      			$action = array();

      			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=cms/essays/update&token=' . $this->session->data['token'] . '&essay_id=' . $result['essay_id'] . $url
      			);

      			if($result['is_locked'])
      			$action[] = array(
				'text' => 'Locked',
				'href' => 'javascript:void(0)'
				);
					
				$due_date = strtotime($result['date_completed']);
				if(!empty($due_date))
				$due_date = date('Y-m-d', strtotime($result['date_completed']));
				else
				$due_date = date('Y-m-d', strtotime($result['date_due']))." *";
				//

				$this->data['informations'][] = array(
				'essay_id' => $result['essay_id'],
				'topic'      => $result['topic'],
				'assignment_num' => "A".$result['assignment_num'], //softronikx technologies
				'student_name' => $result['student_name'],
				'tutor_name' => $result['tutor_name'],
				'status' => $result['curr_status'],
				'owed' => $result['owed'],
				'paid' => $result['paid'],
				'due_date' => $due_date,
				'date_assigned' => date('Y-m-d', strtotime($result['date_assigned'])),
				'selected'   => isset($this->request->post['selected']) && in_array($result['essay_id'], $this->request->post['selected']),
				'action'     => $action
				);
      		}

      		$this->data['heading_title'] = $this->language->get('heading_title');

      		$this->data['text_enabled'] = $this->language->get('text_enabled');
      		$this->data['text_disabled'] = $this->language->get('text_disabled');
      		$this->data['text_yes'] = $this->language->get('text_yes');
      		$this->data['text_no'] = $this->language->get('text_no');
      		$this->data['text_no_results'] = $this->language->get('text_no_results');

      		$this->data['column_assignment_num'] = $this->language->get('column_assignment_num');
      		$this->data['column_student_name'] = $this->language->get('column_student_name');
      		$this->data['column_tutor_name'] = $this->language->get('column_tutor_name');
      		$this->data['column_topic'] = $this->language->get('column_topic');
      		$this->data['column_date_assigned'] = $this->language->get('column_date_assigned');
      		$this->data['column_due_date'] = $this->language->get('column_due_date');
      		$this->data['column_topic'] = $this->language->get('column_topic');
      		$this->data['column_status'] = $this->language->get('column_status');
      		$this->data['column_action'] = $this->language->get('column_action');

      		$this->data['button_approve'] = $this->language->get('button_approve');
      		$this->data['button_insert'] = $this->language->get('button_insert');
      		$this->data['button_upload'] = $this->language->get('button_upload_batch');
      		$this->data['button_delete'] = $this->language->get('button_delete');
      		$this->data['button_filter'] = $this->language->get('button_filter');

      		$this->data['token'] = $this->session->data['token'];

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

      		if (isset($this->request->get['filter_assignment_num'])) {
      			$url .= '&filter_assignment_num=' . $this->request->get['filter_assignment_num'];
      		}

      		if (isset($this->request->get['filter_price_paid'])) {
      			$url .= '&filter_price_paid=' . $this->request->get['filter_price_paid'];
      		}

      		if (isset($this->request->get['filter_paid_to_tutor'])) {
      			$url .= '&filter_paid_to_tutor=' . $this->request->get['filter_paid_to_tutor'];
      		}

      		if (isset($this->request->get['filter_student_name'])) {
      			$url .= '&filter_student_name=' . $this->request->get['filter_student_name'];
      		}

      		if (isset($this->request->get['filter_tutor_name'])) {
      			$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
      		}

      		if (isset($this->request->get['filter_all'])) {
      			$url .= '&filter_all=' . $this->request->get['filter_all'];
      		}

      		if (isset($this->request->get['filter_topic'])) {
      			$url .= '&filter_topic=' . $this->request->get['filter_topic'];
      		}

      		if (isset($this->request->get['filter_status'])) {
      			$url .= '&filter_status=' . $this->request->get['filter_status'];
      		}

      		if (isset($this->request->get['filter_date_assigned'])) {
      			$url .= '&filter_date_assigned=' . $this->request->get['filter_date_assigned'];
      		}

      		if (isset($this->request->get['filter_date_completed'])) {
      			$url .= '&filter_date_completed=' . $this->request->get['filter_date_completed'];
      		}

      		if (isset($this->request->get['filter_date_to_assigned'])) {
      			$url .= '&filter_date_to_assigned=' . $this->request->get['filter_date_to_assigned'];
      		}

      		if (isset($this->request->get['filter_date_to_completed'])) {
      			$url .= '&filter_date_to_completed=' . $this->request->get['filter_date_to_completed'];
      		}

      		if ($order == 'ASC') {
      			$url .= '&order=DESC';
      		} else {
      			$url .= '&order=ASC';
      		}

      		if (isset($this->request->get['page'])) {
      			$url .= '&page=' . $this->request->get['page'];
      		}

      		$this->data['sort_assignment_num'] = HTTPS_SERVER . 'index.php?route=cms/essays&token=' . $this->session->data['token'] . '&sort=ea.assignment_num' . $url;
      		$this->data['sort_student_name'] = HTTPS_SERVER . 'index.php?route=cms/essays&token=' . $this->session->data['token'] . '&sort=ea.student_name' . $url;
      		$this->data['sort_tutor_name'] = HTTPS_SERVER . 'index.php?route=cms/essays&token=' . $this->session->data['token'] . '&sort=ea.tutor_id' . $url;
      		$this->data['sort_topic'] = HTTPS_SERVER . 'index.php?route=cms/essays&token=' . $this->session->data['token'] . '&sort=ea.topic' . $url;
      		$this->data['sort_status'] = HTTPS_SERVER . 'index.php?route=cms/essays&token=' . $this->session->data['token'] . '&sort=ea.status' . $url;
      		$this->data['sort_date_assigned'] = HTTPS_SERVER . 'index.php?route=cms/essays&token=' . $this->session->data['token'] . '&sort=ea.date_assigned' . $url;;
      		$this->data['sort_due_date'] = HTTPS_SERVER . 'index.php?route=cms/essays&token=' . $this->session->data['token'] . '&sort=ea.date_due' . $url;;
      		$this->data['sort_price_paid'] = HTTPS_SERVER . 'index.php?route=cms/essays&token=' . $this->session->data['token'] . '&sort=ea.owed' . $url;;
      		$this->data['paid_to_tutor'] = HTTPS_SERVER . 'index.php?route=cms/essays&token=' . $this->session->data['token'] . '&sort=ea.paid' . $url;;

      		$url = '';

      		$url = '';

      		if (isset($this->request->get['filter_assignment_num'])) {
      			$url .= '&filter_assignment_num=' . $this->request->get['filter_assignment_num'];
      		}

      		if (isset($this->request->get['filter_price_paid'])) {
      			$url .= '&filter_price_paid=' . $this->request->get['filter_price_paid'];
      		}

      		if (isset($this->request->get['filter_paid_to_tutor'])) {
      			$url .= '&filter_paid_to_tutor=' . $this->request->get['filter_paid_to_tutor'];
      		}


      		if (isset($this->request->get['filter_student_name'])) {
      			$url .= '&filter_student_name=' . $this->request->get['filter_student_name'];
      		}

      		if (isset($this->request->get['filter_tutor_name'])) {
      			$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
      		}

      		if (isset($this->request->get['filter_all'])) {
      			$url .= '&filter_all=' . $this->request->get['filter_all'];
      		}

      		if (isset($this->request->get['filter_topic'])) {
      			$url .= '&filter_topic=' . $this->request->get['filter_topic'];
      		}

      		if (isset($this->request->get['filter_status'])) {
      			$url .= '&filter_status=' . $this->request->get['filter_status'];
      		}

      		if (isset($this->request->get['filter_date_assigned'])) {
      			$url .= '&filter_date_assigned=' . $this->request->get['filter_date_assigned'];
      		}

      		if (isset($this->request->get['filter_date_completed'])) {
      			$url .= '&filter_date_completed=' . $this->request->get['filter_date_completed'];
      		}

      		if (isset($this->request->get['filter_date_to_assigned'])) {
      			$url .= '&filter_date_to_assigned=' . $this->request->get['filter_date_to_assigned'];
      		}

      		if (isset($this->request->get['filter_date_to_completed'])) {
      			$url .= '&filter_date_to_assigned=' . $this->request->get['filter_date_to_assigned'];
      		}

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
      		$pagination->url = HTTPS_SERVER . 'index.php?route=cms/essays&token=' . $this->session->data['token'] . $url . '&page={page}';
      		 
      		$this->data['pagination'] = $pagination->render();

      		$this->data['filter_student_name'] = $filter_student_name;
      		$this->data['filter_tutor_name'] = $filter_tutor_name;
      		$this->data['filter_all'] = $filter_all;
      		$this->data['filter_topic'] = $filter_topic;
      		$this->data['filter_status'] = $filter_status;
      		$this->data['filter_date_assigned'] = $filter_date_assigned;
      		$this->data['filter_date_completed'] = $filter_date_completed;
      		$this->data['filter_date_to_assigned'] = $filter_date_to_assigned;
      		$this->data['filter_date_to_completed'] = $filter_date_to_completed;
      		$this->data['assignment_status'] = $assignment_status;
      		$this->data['filter_assignment_num'] = $filter_assignment_num;
      		$this->data['filter_price_paid'] = $filter_price_paid;
      		$this->data['filter_paid_to_tutor'] = $filter_paid_to_tutor;

      		$this->data['sort'] = $sort;
      		$this->data['order'] = $order;



      		$this->template = 'cms/essays_list.tpl';
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

		$this->data['entry_assignment_num'] = $this->language->get('entry_assignment_num');
		$this->data['entry_tutor_name'] = $this->language->get('entry_tutor_name');
		$this->data['entry_student_name'] = $this->language->get('entry_student_name');
		$this->data['entry_student_email'] = $this->language->get('entry_student_email');
		$this->data['entry_topic'] = $this->language->get('entry_topic');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_format'] = $this->language->get('entry_format');
		$this->data['entry_total_price'] = $this->language->get('entry_total_price');
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

		if (isset($this->error['assignment_num'])) {
			$this->data['error_assignment_num'] = $this->error['assignment_num'];
		} else {
			$this->data['error_assignment_num'] = '';
		}

		if (isset($this->error['tutor_id'])) {
			$this->data['error_tutor_id'] = $this->error['tutor_id'];
		} else {
			$this->data['error_tutor_id'] = '';
		}

		if (isset($this->error['student_name'])) {
			$this->data['error_student_name'] = $this->error['student_name'];
		} else {
			$this->data['error_student_name'] = '';
		}

		if (isset($this->error['student_email'])) {
			$this->data['error_student_email'] = $this->error['student_email'];
		} else {
			$this->data['error_student_email'] = '';
		}

		if (isset($this->error['topic'])) {
			$this->data['error_topic'] = $this->error['topic'];
		} else {
			$this->data['error_topic'] = '';
		}

		if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = '';
		}

		if (isset($this->error['format'])) {
			$this->data['error_format'] = $this->error['format'];
		} else {
			$this->data['error_format'] = '';
		}

		if (isset($this->error['total_price'])) {
			$this->data['error_total_price'] = $this->error['total_price'];
		} else {
			$this->data['error_total_price'] = '';
		}

		if (isset($this->error['tutor_price'])) {
			$this->data['error_tutor_price'] = $this->error['tutor_price'];
		} else {
			$this->data['error_tutor_price'] = '';
		}

		if (isset($this->error['date_assigned'])) {
			$this->data['error_date_assigned'] = $this->error['date_assigned'];
		} else {
			$this->data['error_date_assigned'] = '';
		}

		if (isset($this->error['date_completed'])) {
			$this->data['error_date_completed'] = $this->error['date_completed'];
		} else {
			$this->data['error_date_completed'] = '';
		}

		if (isset($this->error['due_date'])) {
			$this->data['error_due_date'] = $this->error['due_date'];
		} else {
			$this->data['error_due_date'] = '';
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
       		'href'      => HTTPS_SERVER . 'index.php?route=cms/essays&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
      		);
      		 
      		if (!isset($this->request->get['essay_id'])) {
      			$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/essays/insert&token=' . $this->session->data['token'] . $url;
      		} else {
      			$this->data['action'] = HTTPS_SERVER . 'index.php?route=cms/essays/update&token=' . $this->session->data['token'] . '&essay_id=' . $this->request->get['essay_id'] . $url;
      		}

      		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=cms/essays&token=' . $this->session->data['token'] . $url;

      		if (isset($this->request->get['essay_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {

      			$information_info = $this->model_cms_essays->getInformation($this->request->get['essay_id']);

      			/*// To setting Data
      			 $this->export->addData(array($information_info));
      			  
      			 // To setting Header
      			 $this->export->addRow(array('Essay ID','Invoice ID','Topic'), 0);

      			 // To setting File Name
      			 $this->export->download("websites.xls");
      			 exit;//*/
      		}

      		if (isset($this->request->post['assignment_num'])) {
      			$this->data['assignment_num'] = $this->request->post['assignment_num'];
      		} elseif (isset($information_info)) {
      			$this->data['assignment_num'] = "A".$information_info['assignment_num'];
      		} else {
      			$get_next_assignment_num = $this->model_cms_essays->getNextAssignmentNumber();
      			$this->data['assignment_num'] = "A".$get_next_assignment_num;
      		}

      		if (isset($this->request->post['tutor_id'])) {
      			$this->data['tutor_id'] = $this->request->post['tutor_id'];
      		} elseif (isset($information_info)) {
      			$this->data['tutor_id'] = $information_info['tutor_id'];
      		} else {
      			$this->data['tutor_id'] = "";
      		}

      		if (isset($this->request->post['student_name'])) {
      			$this->data['student_name'] = $this->request->post['student_name'];
      		} elseif (isset($information_info)) {
      			$this->data['student_name'] = $information_info['student_name'];
      		} else {
      			$this->data['student_name'] = "";
      		}

      		if (isset($this->request->post['student_email'])) {
      			$this->data['student_email'] = $this->request->post['student_email'];
      		} elseif (isset($information_info)) {
      			$this->data['student_email'] = $information_info['student_email'];
      		} else {
      			$this->data['student_email'] = "";
      		}

      		if (isset($this->request->post['student_id'])) {
      			$this->data['student_id'] = $this->request->post['student_id'];
      		} elseif (isset($information_info)) {
      			$this->data['student_id'] = $information_info['student_id'];
      		} else {
      			$this->data['student_id'] = "";
      		}

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

      		if (isset($this->request->post['total_price'])) {
      			$this->data['total_price'] = $this->request->post['total_price'];
      		} elseif (isset($information_info)) {
      			$this->data['total_price'] = $information_info['owed'];
      		} else {
      			$this->data['total_price'] = "";
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
      			$this->data['date_completed'] = date('Y-m-d', strtotime($information_info['date_completed']));
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

      		$all_tutors = array();
      		$this->load->model('user/tutors');
      		$results = $this->model_user_tutors->getAllTutors(array('sort'=>'name', 'filter_approved' => 1, 'filter_status' => 1));
      		foreach ($results as $result) {
      			$all_tutors[] = array(
				'tutor_id' => $result['user_id'],
				'tutor_name' => $result['name'],
      			);
      		}
      		$this->data['tutors'] = $all_tutors;

      		$all_students = array();
      		$this->load->model('user/students');
      		$results = $this->model_user_students->getAllStudents(array('sort'=>'name', 'filter_approved' => 1));
      		foreach ($results as $result) {
      			$all_students[] = array(
				'student_id' => $result['user_id'],
				'student_name' => $result['name'],
      			);
      		}
      		$this->data['students'] = $all_students;


      		$all_status = array();
      		$results = $this->model_cms_essays->getEssaysStatus();
      		foreach ($results as $result) {
      			$all_status[] = array(
				'status_id' => $result['essay_status_id'],
				'status_name' => $result['name'],
      			);
      		}

      		$this->data['all_status'] = $all_status;

      		$this->template = 'cms/essays_form.tpl';
      		$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'cms/essays')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$assignment_num = (int) substr($this->request->post['assignment_num'], 1);

		if (empty($assignment_num)) {
			$this->error['assignment_num'] = $this->language->get('error_assignment_num');
		}

		if(!isset($this->request->get['essay_id'])) {
			$check_query = $this->db->query("select essay_id from ".DB_PREFIX."essay_assignment WHERE assignment_num='" . $assignment_num . "'");
			if($check_query->num_rows)
			$this->error['assignment_num'] = $this->language->get('error_assignment_num_exist');
		}

		/*if ((strlen(utf8_decode($this->request->post['topic'])) < 3) || (strlen(utf8_decode($this->request->post['topic'])) > 32)) {
			$this->error['topic'] = $this->language->get('error_topic');
		}*/

		if (strlen(utf8_decode($this->request->post['description'])) < 3) {
			$this->error['description'] = $this->language->get('error_description');
		}


		if (!$this->error) {
			return TRUE;
		} else {
			if (!isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_required_data');
			}
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'cms/essays')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/store');

		foreach ($this->request->post['selected'] as $essay_id) {
			if ($this->config->get('config_account_id') == $essay_id) {
				$this->error['warning'] = $this->language->get('error_account');
			}

			if ($this->config->get('config_checkout_id') == $essay_id) {
				$this->error['warning'] = $this->language->get('error_checkout');
			}

			$store_total = $this->model_setting_store->getTotalStoresByInformationId($essay_id);

			if ($store_total) {
				$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
			}
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>