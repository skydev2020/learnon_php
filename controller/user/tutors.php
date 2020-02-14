<?php
class ControllerUserTutors extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('user/tutors');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('user/tutors');
		$this->getList();
	}

	public function rejected() {
		$this->load->language('user/tutors');
		$this->document->title = $this->language->get('heading_title_rejected');
		$this->load->model('user/tutors');
		$this->getListRejected();
	}

	public function export() {
		$this->load->model('user/tutors');
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'c.date_added';
		}
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = NULL;
		}
		if (isset($this->request->get['filter_all'])) {
			$filter_all = $this->request->get['filter_all'];
		} else {
			$filter_all = NULL;
		}
		if (isset($this->request->get['filter_email'])) {
			$filter_email = $this->request->get['filter_email'];
		} else {
			$filter_email = NULL;
		}
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = NULL;
		}
		if (isset($this->request->get['filter_approved'])) {
			$filter_approved = $this->request->get['filter_approved'];
		} else {
			$filter_approved = NULL;
		}
		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = NULL;
		}

		$this->data['filter_name'] = $filter_name;
		$this->data['filter_all'] = $filter_all;
		$this->data['filter_email'] = $filter_email;
		$this->data['filter_status'] = $filter_status;
		$this->data['filter_approved'] = $filter_approved;
		$this->data['filter_date_added'] = $filter_date_added;
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$select = "CONCAT(c.firstname, ' ', c.lastname) AS name";
		if(isset($this->request->get['tutor_list'])){

		}
		if(isset($this->request->get['tutor_emails'])){
			$select .= ", c.email";
		}
		if(isset($this->request->get['contract'])){
			$select .= ", ui.agreement";
		}

		$this->model_user_tutors->user_group_id = "2";
		$results = $this->model_user_tutors->getTutors($this->data, $select);
		$arrresult = array();
		if(strstr($select,'agreement')){
			foreach($results as $result){
				$result['agreement'] = strip_tags(html_entity_decode($result['agreement'],ENT_QUOTES, 'UTF-8'));
				$arrresult[] = $result;
			}
		}else{
			$arrresult = $results;
		}
		// To setting Data
		$this->export->addData($arrresult);

		// To setting File Name
		$this->export->download("tutors.xls");
		exit;
	}

	public function insert() {
		$this->load->language('user/tutors');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('user/tutors');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_user_tutors->addTutor($this->request->post);
			log_activity("Tutor Added", "A new tutor added.");
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
				
			if (isset($this->request->get['filter_all'])) {
				$url .= '&filter_all=' . $this->request->get['filter_all'];
			}
				
			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . $this->request->get['filter_email'];
			}
				
			if (isset($this->request->get['filter_user_group_id'])) {
				$url .= '&filter_user_group_id=' . $this->request->get['filter_user_group_id'];
			}
				
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
				
			if (isset($this->request->get['filter_approved'])) {
				$url .= '&filter_approved=' . $this->request->get['filter_approved'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
				
			$this->redirect(HTTPS_SERVER . 'index.php?route=user/tutors&token=' . $this->session->data['token'] . $url);
		}
		 
		$this->getForm("heading_title_insert");
	}
	 
	public function view() {
		$this->load->language('user/tutors');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('user/tutors');

		$this->getForm_view("heading_title_update");
	}
	 
	public function update() {
		$this->load->language('user/tutors');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('user/tutors');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_user_tutors->editTutor($this->request->get['user_id'], $this->request->post);
			log_activity("Profile Updated", "Tutor profile details updated.");
			$this->session->data['success'] = $this->language->get('text_success');
			 
			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
				
			if (isset($this->request->get['filter_all'])) {
				$url .= '&filter_all=' . $this->request->get['filter_all'];
			}
				
			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . $this->request->get['filter_email'];
			}
				
			if (isset($this->request->get['filter_user_group_id'])) {
				$url .= '&filter_user_group_id=' . $this->request->get['filter_user_group_id'];
			}
				
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
				
			if (isset($this->request->get['filter_approved'])) {
				$url .= '&filter_approved=' . $this->request->get['filter_approved'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
				
			$this->redirect(HTTPS_SERVER . 'index.php?route=user/tutors&token=' . $this->session->data['token'] . $url);
		}

		$this->getForm("heading_title_update");
	}

	//function written by softronikx technologies
	public function work() {
		$this->load->language('user/tutors');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('user/tutors');
		$this->load->model('cms/essays');

		if(isset($this->request->get['user_id']) and !empty($this->request->get['user_id']))
		{
			$get_work_details = true;
		}
		else
		{
			$get_work_details = false;
		}

		// -------------------------------- Homework Assignment Code --------------------------------
		if($get_work_details){

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
       		'href'      => HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
      		);
      			
      		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=user/tutors/work/insert&token=' . $this->session->data['token'] . $url;
      		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=user/tutors/work/delete&token=' . $this->session->data['token'] . $url;

      		$this->data['informations'] = array();

      		$data = array(
			'filter_assignment_num'    => $filter_assignment_num, 
			'filter_price_paid'        => $filter_price_paid, 
			'filter_paid_to_tutor'     => $filter_paid_to_tutor, 
			'filter_student_name'      => $filter_student_name, 
			'filter_tutor_name'        => $filter_tutor_name, 
      		//'filter_all'               => $filter_all,
			'filter_topic'             => $filter_topic,  
			'filter_status'            => $filter_status,
			'filter_date_assigned'     => $filter_date_assigned,
			'filter_date_completed'    => $filter_date_completed,
			'filter_date_to_assigned'  => $filter_date_to_assigned,			
			'filter_date_to_completed' => $filter_date_to_completed,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit'),
			'filter_tutor_id'		   => $this->request->get['user_id']
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
				'href' => HTTPS_SERVER . 'index.php?route=user/tutors/work/update&token=' . $this->session->data['token'] . '&essay_id=' . $result['essay_id'] . $url
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
				'assignment_num' => "A".$result['assignment_num'],
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

      		$this->data['heading_title'] = $this->language->get('heading_title_tutor_work_details');

      		$this->data['text_enabled'] = $this->language->get('text_enabled');
      		$this->data['text_disabled'] = $this->language->get('text_disabled');
      		$this->data['text_yes'] = $this->language->get('text_yes');
      		$this->data['text_no'] = $this->language->get('text_no');
      		$this->data['text_no_results'] = $this->language->get('text_no_results');

      		$this->data['column_assignment_num'] = $this->language->get('column_assignment_num');
      		$this->data['column_student_name'] = $this->language->get('column_student_name');
      		$this->data['column_topic'] = $this->language->get('column_topic');
      		$this->data['column_date_assigned'] = $this->language->get('column_date_assigned');
      		$this->data['column_due_date'] = $this->language->get('column_due_date');
      		$this->data['column_status'] = $this->language->get('column_status');
      		$this->data['column_action'] = $this->language->get('column_action');
      		$this->data['column_homework_assignment'] = $this->language->get('column_homework_assignment');

      		$this->data['button_approve'] = $this->language->get('button_approve');
      		$this->data['button_insert'] = $this->language->get('button_insert');
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

      		//url for sorting
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

      		if (isset($this->request->get['user_id'])) {
      			$url .= '&user_id=' . $this->request->get['user_id'];
      		}

      		if ($order == 'ASC') {
      			$url .= '&order=DESC';
      		} else {
      			$url .= '&order=ASC';
      		}

      		if (isset($this->request->get['page'])) {
      			$url .= '&page=' . $this->request->get['page'];
      		}

      		$this->data['sort_assignment_num'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&sort=ea.assignment_num' . $url;
      		$this->data['sort_student_name'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&sort=ea.student_name' . $url;
      		$this->data['sort_tutor_name'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&sort=ea.tutor_id' . $url;
      		$this->data['sort_topic'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&sort=ea.topic' . $url;
      		$this->data['sort_status'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&sort=ea.status' . $url;
      		$this->data['sort_date_assigned'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&sort=ea.date_assigned' . $url;;
      		$this->data['sort_due_date'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&sort=ea.date_due' . $url;;
      		$this->data['sort_price_paid'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&sort=ea.owed' . $url;;
      		$this->data['paid_to_tutor'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&sort=ea.paid' . $url;;

      		//url for pagination
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

      		if (isset($this->request->get['user_id'])) {
      			$url .= '&user_id=' . $this->request->get['user_id'];
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
      		$pagination->url = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . $url . '&page={page}';
      			
      		$this->data['pagination'] = $pagination->render();

      		//get tutors name
      		$tutor_details = $this->model_user_tutors->getTutor($this->request->get['user_id']);

      		$this->data['tutor_name'] = $tutor_details['firstname'].' '.$tutor_details['lastname'];

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
      		$this->data['user_id'] = $this->request->get['user_id'];
      		$this->data['sort'] = $sort;
      		$this->data['order'] = $order;


      		// -------------------------------- End of Homework Assignment Code --------------------------------

		}

		// -------------------------------- Session Details Code ----------------------------------------
		if($get_work_details) {

			$this->load->model('user/sessions');
			$this->load->model('cms/payment');

			if (isset($this->request->get['page_s'])) {
				$page_s = $this->request->get['page_s'];
			} else {
				$page_s = 1;
			}

			if (isset($this->request->get['sort_s'])) {
				$sort_s = $this->request->get['sort_s'];
			} else {
				$sort_s = 'name';
			}

			if (isset($this->request->get['order_s'])) {
				$order_s = $this->request->get['order_s'];
			} else {
				$order_s = 'ASC';
			}

			if (isset($this->request->get['filter_session_date_s'])) {
				$filter_session_date_s = $this->request->get['filter_session_date_s'];
			} else {
				$filter_session_date_s = NULL;
			}

			if (isset($this->request->get['filter_tutor_name_s'])) {
				$filter_tutor_name_s = $this->request->get['filter_tutor_name_s'];
			} else {
				$filter_tutor_name_s = NULL;
			}

			if (isset($this->request->get['filter_student_name_s'])) {
				$filter_student_name_s = $this->request->get['filter_student_name_s'];
			} else {
				$filter_student_name_s = NULL;
			}

			if (isset($this->request->get['filter_session_duration_s'])) {
				$filter_session_duration_s = $this->request->get['filter_session_duration_s'];
			} else {
				$filter_session_duration_s = NULL;
			}

			if (isset($this->request->get['filter_session_notes_s'])) {
				$filter_session_notes_s = $this->request->get['filter_session_notes_s'];
			} else {
				$filter_session_notes_s = NULL;
			}

			$url = '';

			if (isset($this->request->get['filter_session_date_s'])) {
				$url .= '&filter_session_date_s=' . $this->request->get['filter_session_date_s'];
			}

			if (isset($this->request->get['filter_tutor_name_s'])) {
				$url .= '&filter_tutor_name_s=' . $this->request->get['filter_tutor_name_s'];
			}

			if (isset($this->request->get['filter_student_name_s'])) {
				$url .= '&filter_student_name_s=' . $this->request->get['filter_student_name_s'];
			}
				
			if (isset($this->request->get['filter_session_duration_s'])) {
				$url .= '&filter_session_duration_s=' . $this->request->get['filter_session_duration_s'];
			}

			if (isset($this->request->get['filter_session_notes_s'])) {
				$url .= '&filter_session_notes_s=' . $this->request->get['filter_session_notes_s'];
			}

			if (isset($this->request->get['page_s'])) {
				$url .= '&page_s=' . $this->request->get['page_s'];
			}

			if (isset($this->request->get['sort_s'])) {
				$url .= '&sort_s=' . $this->request->get['sort_s'];
			}

			if (isset($this->request->get['order_s'])) {
				$url .= '&order_s=' . $this->request->get['order_s'];
			}

			if (isset($this->request->get['user_id'])) {
				$url .= '&user_id=' . $this->request->get['user_id'];
			}

			$this->document->breadcrumbs = array();

			$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
			);

			$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
      		);

      		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=user/tutors/work/insert&token=' . $this->session->data['token'] . $url;
      		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=user/tutors/work/delete&token=' . $this->session->data['token'] . $url;
      		$this->data['lock_sessions'] = HTTPS_SERVER . 'index.php?route=user/tutors/work/lock&token=' . $this->session->data['token'] . $url;
      		$this->data['unlock_sessions'] = HTTPS_SERVER . 'index.php?route=user/tutors/work/unlock&token=' . $this->session->data['token'] . $url;


      		if($this->user->getUserGroupId() > 3)
      		$this->data['sessions_controll'] = 1;
      		else
      		$this->data['sessions_controll'] = 0;

      		$this->data['sessions'] = array();

      		$data = array(
			'filter_session_date'              => $filter_session_date_s, 
			'filter_tutor_name'             => $filter_tutor_name_s, 
			'filter_student_name'             => $filter_student_name_s, 
			'filter_session_duration'        => $filter_session_duration_s,
			'filter_session_notes'        => $filter_session_notes_s,
			'sort'                     => $sort_s,
			'order'                    => $order_s,
			'start'                    => ($page_s - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit'),
			'filter_tutor_id'		=> $this->request->get['user_id'] //filter the data by tutor id here
      		);

      		$session_total = $this->model_user_sessions->getTotalSessions($data);
      		$results = $this->model_user_sessions->getSessions($data);
      		$duration_array = $this->model_user_sessions->getAllDurations();
      		foreach ($results as $result) {
      			$action = array();

      			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=user/tutors/work/update&token=' . $this->session->data['token'] . '&session_id=' . $result['session_id'] . $url
      			);
      				
      			if($result['p_locked'])
      			$action[] = array(
				'text' => 'P Locked',
				'href' => 'javascript:void(0)'
				);
					
				if($result['i_locked'])
				$action[] = array(
				'text' => 'I Locked',
				'href' => 'javascript:void(0)'
				);

				
				$session_tutor_wage = $this->model_cms_payment->getTutorSessionRate($this->request->get['user_id'],$result['tutors_to_students_id'],$result['session_id']);
				
				if(empty($session_tutor_wage)) $session_tutor_wage = $result['base_wage'];
				
				$this->data['sessions'][] = array(
			    'session_id'    => $result['session_id'],
				'tutors_to_students_id'    => $result['tutors_to_students_id'],
				//'tutor_wage'    => $result['base_wage'],
				'tutor_wage' => $session_tutor_wage,
				'base_invoice'    => $result['base_invoice'],				
				'tutor_name'    => $result['tutor_name'],
				'student_name'    => $result['student_name'],
				'session_date'           => date($this->language->get('date_format_short'), strtotime($result['session_date'])),
				'session_duration'          => $duration_array[$result['session_duration']],
				'date'     => date($this->language->get('date_format_short'), strtotime($result['date_submission'])),
				'selected'       => isset($this->request->post['selected']) && in_array($result['session_duration'], $this->request->post['selected']),
				'action'         => $action
				);
      		}
      		$this->data['duration_array'] = 	$duration_array;
      		$this->data['heading_title_s'] = $this->language->get('heading_title_session');
      		$this->data['text_no_results'] = $this->language->get('text_no_results');

      		$this->data['column_session_duration'] = $this->language->get('column_session_duration');
      		$this->data['column_student_name'] = $this->language->get('column_student_name');
      		$this->data['column_tutor_name'] = $this->language->get('column_tutor_name');
      		$this->data['column_session_notes'] = $this->language->get('column_session_notes');
      		$this->data['column_session_date'] = $this->language->get('column_session_date');
      		$this->data['column_date'] = $this->language->get('column_date');
      		$this->data['column_action'] = $this->language->get('column_action');
      		$this->data['heading_title_session'] = $this->language->get('heading_title_session');
      		$this->data['column_tutor_wage'] = $this->language->get('column_tutor_wage');
      		$this->data['column_base_invoice'] = $this->language->get('column_base_invoice');


      		$this->data['button_unassing'] = $this->language->get('button_unassing');
      		$this->data['button_insert'] = $this->language->get('button_insert');
      		$this->data['button_delete'] = $this->language->get('button_delete');
      		$this->data['button_lock'] = $this->language->get('button_lock');
      		$this->data['button_unlock'] = $this->language->get('button_unlock');
      		$this->data['button_filter'] = $this->language->get('button_filter');

      		$this->data['token'] = $this->session->data['token'];

      		if (isset($this->session->data['error'])) {
      			$this->data['error_warning'] = $this->session->data['error'];
      				
      			unset($this->session->data['error']);
      		} elseif (isset($this->error['warning'])) {
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

      		if (isset($this->request->get['filter_session_date_s'])) {
      			$url .= '&filter_session_date_s=' . $this->request->get['filter_session_date_s'];
      		}

      		if (isset($this->request->get['filter_tutor_name'])) {
      			$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
      		}

      		if (isset($this->request->get['filter_student_name_s'])) {
      			$url .= '&filter_student_name_s=' . $this->request->get['filter_student_name_s'];
      		}

      		if (isset($this->request->get['filter_session_duration_s'])) {
      			$url .= '&filter_session_duration_s=' . $this->request->get['filter_session_duration_s'];
      		}

      		if (isset($this->request->get['filter_session_notes_s'])) {
      			$url .= '&filter_session_notes_s=' . $this->request->get['filter_session_notes_s'];
      		}

      		if (isset($this->request->get['user_id'])) {
      			$url .= '&user_id=' . $this->request->get['user_id'];
      		}

      		if ($order_s == 'ASC') {
      			$url .= '&order_s=DESC';
      		} else {
      			$url .= '&order_s=ASC';
      		}

      		if (isset($this->request->get['page'])) {
      			$url .= '&page_s=' . $this->request->get['page'];
      		}

        $this->data['sort_tutor_name'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&sort_s=tutor_name' . $url;
        $this->data['sort_student_name'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&sort_s=student_name' . $url;
        $this->data['sort_session_date'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&sort_s=session_date' . $url;
        $this->data['sort_session_duration'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&sort_s=session_duration' . $url;
        $this->data['sort_session_notes'] = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&sort_s=session_notes' . $url;


        $pagination_s = new Pagination();
        $pagination_s->total = $session_total;
        $pagination_s->page = $page;
        $pagination_s->limit = $this->config->get('config_admin_limit');
        $pagination_s->text = $this->language->get('text_pagination');
        $pagination_s->url = HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . $url . '&page_s={page}';
        	
        $this->data['pagination_s'] = $pagination_s->render();
        $this->data['filter_tutor_name_s'] = $filter_tutor_name_s;
        $this->data['filter_student_name_s'] = $filter_student_name_s;
        $this->data['filter_session_date_s'] = $filter_session_date_s;
        $this->data['filter_session_duration_s'] = $filter_session_duration_s;
        $this->data['filter_session_notes_s'] = $filter_session_notes_s;


        $this->data['sort_s'] = $sort_s;
        $this->data['order_s'] = $order_s;

		}

		// -------------------------------- End of Session Details Code -------------------------------



		if($get_work_details){
			$this->template = 'user/tutors_work_list.tpl';
			$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		}
		else
		{
			echo "Invalid Tutor!";
		}
		//$this->getForm("heading_title_update");
		//code to load the work template and display the work
	}


	public function pdf() {
		$this->load->language('user/tutors');

		$this->load->model('user/tutors');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['entry_username'] = $this->language->get('entry_username');
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

		$this->data['tab_general'] = $this->language->get('tab_general');


		$user_info = $this->model_user_tutors->getTutor($this->request->get['user_id']);
		 
		if (isset($user_info)) {
			$this->data['username'] = $user_info['username'];
		} else {
			$this->data['username'] = '';
		}

		if (isset($user_info)) {
			$this->data['ip'] = $user_info['ip'];
		} else {
			$this->data['ip'] = '';
		}

		if (isset($user_info)) {
			$this->data['date_added'] = $user_info['date_added'];
		} else {
			$this->data['date_added'] = '';
		}

		if (isset($user_info)) {
			$this->data['firstname'] = $user_info['firstname'];
		} else {
			$this->data['firstname'] = '';
		}

		if (isset($user_info)) {
			$this->data['lastname'] = $user_info['lastname'];
		} else {
			$this->data['lastname'] = '';
		}

		if (isset($user_info)) {
			$this->data['home_phone'] = $user_info['home_phone'];
		} else {
			$this->data['home_phone'] = '';
		}

		if (isset($user_info)) {
			$this->data['cell_phone'] = $user_info['cell_phone'];
		} else {
			$this->data['cell_phone'] = '';
		}

		if (isset($user_info)) {
			$this->data['email'] = $user_info['email'];
		} else {
			$this->data['email'] = '';
		}

		$this->load->model('user/user_group');

		if (isset($user_info)) {
			$this->data['user_group_id'] = $user_info['user_group_id'];
		} else {
			$this->data['user_group_id'] = $this->config->get('config_user_group_id');
		}

		if (isset($user_info)) {
			$this->data['status'] = $user_info['status'];
		} else {
			$this->data['status'] = 1;
		}

		if (isset($user_info)) {
			$this->data['address'] = $user_info['address'];
		} else {
			$this->data['address'] = '';
		}

		if (isset($user_info)) {
			$this->data['city'] = $user_info['city'];
		} else {
			$this->data['city'] = '';
		}

		if (isset($user_info)) {
			$this->data['state'] = $user_info['state'];
		} else {
			$this->data['state'] = '';
		}

		if (isset($user_info)) {
			$this->data['pcode'] = $user_info['pcode'];
		} else {
			$this->data['pcode'] = '';
		}

		if (isset($user_info)) {
			$this->data['country'] = $user_info['country'];
		} else {
			$this->data['country'] = '';
		}


		if (isset($user_info)) {
			$this->data['users_note'] = $user_info['users_note'];
		} else {
			$this->data['users_note'] = '';
		}

		if (isset($user_info)) {
			$this->data['post_secondary_education'] = $user_info['post_secondary_education'];
		} else {
			$this->data['post_secondary_education'] = '';
		}

		if (isset($user_info)) {
			$this->data['subjects_studied'] = $user_info['subjects_studied'];
		} else {
			$this->data['subjects_studied'] = '';
		}

		if (isset($user_info)) {
			$this->data['courses_available'] = $user_info['courses_available'];
		} else {
			$this->data['courses_available'] = '';
		}

		if (isset($user_info)) {
			$this->data['previous_experience'] = $user_info['previous_experience'];
		} else {
			$this->data['previous_experience'] = '';
		}

		if (isset($user_info)) {
			$this->data['cities'] = $user_info['cities'];
		} else {
			$this->data['cities'] = '';
		}

		if (isset($user_info)) {
			$this->data['references'] = $user_info['references'];
		} else {
			$this->data['references'] = '';
		}

		if (isset($user_info)) {
			$this->data['gender'] = $user_info['gender'];
		} else {
			$this->data['gender'] = '';
		}

		if (isset($user_info)) {
			$this->data['certified_teacher'] = $user_info['certified_teacher'];
		} else {
			$this->data['certified_teacher'] = '';
		}

		if (isset($user_info)) {
			$this->data['criminal_conviction'] = $user_info['criminal_conviction'];
		} else {
			$this->data['criminal_conviction'] = '';
		}

		if (isset($user_info)) {
			$this->data['background_check'] = $user_info['background_check'];
		} else {
			$this->data['background_check'] = '';
		}

		if (isset($user_info)) {
			$this->data['agreement'] = $user_info['agreement'];
		} else {
			$this->data['agreement'] = '';
		}

		$this->template = 'user/tutors_pdf_form.tpl';

		//$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		//echo $this->render(TRUE);

		$content = $this->render(TRUE);
		$html2pdf = new HTML2PDF('P','A4','fr');
		$html2pdf->WriteHTML($content);
		$html2pdf->Output($user_info['firstname'].'-'.$user_info['lastname'].'.pdf');
		exit;
	}

	public function delete() {
		$this->load->language('user/tutors');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('user/tutors');
			
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $user_id) {
				$this->model_user_tutors->deleteTutor($user_id);
			}
			log_activity("Tutor(s) Deleted", "Tutor(s) account deleted.");
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
				
			if (isset($this->request->get['filter_all'])) {
				$url .= '&filter_all=' . $this->request->get['filter_all'];
			}
				
			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . $this->request->get['filter_email'];
			}
				
			if (isset($this->request->get['filter_user_group_id'])) {
				$url .= '&filter_user_group_id=' . $this->request->get['filter_user_group_id'];
			}
				
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
				
			if (isset($this->request->get['filter_approved'])) {
				$url .= '&filter_approved=' . $this->request->get['filter_approved'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
				
			$this->redirect(HTTPS_SERVER . 'index.php?route=user/tutors&token=' . $this->session->data['token'] . $url);
		}

		$this->getList();
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
			$sort = 'date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = NULL;
		}

		if (isset($this->request->get['filter_all'])) {
			$filter_all = $this->request->get['filter_all'];
		} else {
			$filter_all = NULL;
		}

		if (isset($this->request->get['filter_email'])) {
			$filter_email = $this->request->get['filter_email'];
		} else {
			$filter_email = NULL;
		}

		if (isset($this->request->get['filter_user_group_id'])) {
			$filter_user_group_id = $this->request->get['filter_user_group_id'];
		} else {
			$filter_user_group_id = 2;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = NULL;
		}

		if (isset($this->request->get['filter_approved'])) {
			$filter_approved = $this->request->get['filter_approved'];
		} else {
			$filter_approved = NULL;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = NULL;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_all'])) {
			$url .= '&filter_all=' . $this->request->get['filter_all'];
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}

		if (isset($this->request->get['filter_user_group_id'])) {
			$url .= '&filter_user_group_id=' . $this->request->get['filter_user_group_id'];
		}
			
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
		}
			
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

		$this->document->breadcrumbs = array();

		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
		);

		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=user/tutors&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
      		);

      		$this->data['approve'] = HTTPS_SERVER . 'index.php?route=user/tutors/approve&token=' . $this->session->data['token'] . $url;
      		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=user/tutors/insert&token=' . $this->session->data['token'] . $url;
      		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=user/tutors/delete&token=' . $this->session->data['token'] . $url;

      		$this->data['users'] = array();

      		$data = array(
			'filter_name'              => $filter_name, 
			'filter_all'              => $filter_all, 
			'filter_email'             => $filter_email, 
			'filter_user_group_id' => $filter_user_group_id, 
			'filter_status'            => $filter_status, 
			'filter_approved'          => $filter_approved, 
			'filter_date_added'        => $filter_date_added,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
      		);

      		$user_total = $this->model_user_tutors->getTotalTutors($data);
			  var_dump(123);
			  
      		$results = $this->model_user_tutors->getTutors($data);
			  var_dump(456);
			   die();
      		foreach ($results as $result) {
      			$action = array();

      			$action[] = array(
				'text' => $this->language->get('text_view'),
				'href' => HTTPS_SERVER . 'index.php?route=user/tutors/view&token=' . $this->session->data['token'] . '&user_id=' . $result['user_id'] . $url
      			);
      				
      			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=user/tutors/update&token=' . $this->session->data['token'] . '&user_id=' . $result['user_id'] . $url
      			);
      				
      			$action[] = array(
				'text' => 'Contract',
				'href' => HTTPS_SERVER . 'index.php?route=user/tutors/pdf&token=' . $this->session->data['token'] . '&user_id=' . $result['user_id'] . $url
      			);
      				
      			$action[] = array(
				'text' => $this->language->get('text_view_work'),
				'href' => HTTPS_SERVER . 'index.php?route=user/tutors/work&token=' . $this->session->data['token'] . '&user_id=' . $result['user_id'] . $url
      			);
      				
      			$action[] = array(
				'text' => $this->language->get('text_view_paycheques'),
				'href' => HTTPS_SERVER . 'index.php?route=cms/paycheque&token=' . $this->session->data['token'] . '&user_id=' . $result['user_id'] .'&src=stud' .$url
      			);
      				
      			$this->data['users'][] = array(
				'user_id'    => $result['user_id'],
				'name'           => $result['name'],
				'email'          => $result['email'],
				'status'         => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'approved'       => ($result['approved'] ? $this->language->get('text_yes') : $this->language->get('text_no')),
				'date_added'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'       => isset($this->request->post['selected']) && in_array($result['user_id'], $this->request->post['selected']),
				'action'         => $action
      			);
      		}
      			
      		$this->data['heading_title'] = $this->language->get('heading_title');

      		$this->data['text_enabled'] = $this->language->get('text_enabled');
      		$this->data['text_disabled'] = $this->language->get('text_disabled');
      		$this->data['text_yes'] = $this->language->get('text_yes');
      		$this->data['text_no'] = $this->language->get('text_no');
      		$this->data['text_no_results'] = $this->language->get('text_no_results');

      		$this->data['column_user_id'] = $this->language->get('column_user_id');
      		$this->data['column_name'] = $this->language->get('column_name');
      		$this->data['column_email'] = $this->language->get('column_email');
      		$this->data['column_user_group'] = $this->language->get('column_user_group');
      		$this->data['column_status'] = $this->language->get('column_status');
      		$this->data['column_approved'] = $this->language->get('column_approved');
      		$this->data['column_date_added'] = $this->language->get('column_date_added');
      		$this->data['column_action'] = $this->language->get('column_action');

      		$this->data['button_approve'] = $this->language->get('button_approve');
      		$this->data['button_insert'] = $this->language->get('button_insert');
      		$this->data['button_delete'] = $this->language->get('button_delete');
      		$this->data['button_filter'] = $this->language->get('button_filter');

      		$this->data['token'] = $this->session->data['token'];

      		if (isset($this->session->data['error'])) {
      			$this->data['error_warning'] = $this->session->data['error'];
      				
      			unset($this->session->data['error']);
      		} elseif (isset($this->error['warning'])) {
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

      		if (isset($this->request->get['filter_name'])) {
      			$url .= '&filter_name=' . $this->request->get['filter_name'];
      		}

      		if (isset($this->request->get['filter_all'])) {
      			$url .= '&filter_all=' . $this->request->get['filter_all'];
      		}

      		if (isset($this->request->get['filter_email'])) {
      			$url .= '&filter_email=' . $this->request->get['filter_email'];
      		}

      		if (isset($this->request->get['filter_user_group_id'])) {
      			$url .= '&filter_user_group_id=' . $this->request->get['filter_user_group_id'];
      		}
      			
      		if (isset($this->request->get['filter_status'])) {
      			$url .= '&filter_status=' . $this->request->get['filter_status'];
      		}

      		if (isset($this->request->get['filter_approved'])) {
      			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
      		}

      		if (isset($this->request->get['filter_date_added'])) {
      			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
      		}
      			
      		if ($order == 'ASC') {
      			$url .= '&order=DESC';
      		} else {
      			$url .= '&order=ASC';
      		}

      		if (isset($this->request->get['page'])) {
      			$url .= '&page=' . $this->request->get['page'];
      		}

      		$this->data['sort_user_id'] = HTTPS_SERVER . 'index.php?route=user/tutors&token=' . $this->session->data['token'] . '&sort=c.user_id' . $url;
      		$this->data['sort_name'] = HTTPS_SERVER . 'index.php?route=user/tutors&token=' . $this->session->data['token'] . '&sort=name' . $url;
      		$this->data['sort_email'] = HTTPS_SERVER . 'index.php?route=user/tutors&token=' . $this->session->data['token'] . '&sort=c.email' . $url;
      		$this->data['sort_user_group'] = HTTPS_SERVER . 'index.php?route=user/tutors&token=' . $this->session->data['token'] . '&sort=user_group' . $url;
      		$this->data['sort_status'] = HTTPS_SERVER . 'index.php?route=user/tutors&token=' . $this->session->data['token'] . '&sort=c.status' . $url;
      		$this->data['sort_approved'] = HTTPS_SERVER . 'index.php?route=user/tutors&token=' . $this->session->data['token'] . '&sort=c.approved' . $url;
      		$this->data['sort_date_added'] = HTTPS_SERVER . 'index.php?route=user/tutors&token=' . $this->session->data['token'] . '&sort=c.date_added' . $url;

      		$url = '';

      		if (isset($this->request->get['filter_name'])) {
      			$url .= '&filter_name=' . $this->request->get['filter_name'];
      		}
      		if (isset($this->request->get['filter_all'])) {
      			$url .= '&filter_all=' . $this->request->get['filter_all'];
      		}

      		if (isset($this->request->get['filter_email'])) {
      			$url .= '&filter_email=' . $this->request->get['filter_email'];
      		}

      		if (isset($this->request->get['filter_user_group_id'])) {
      			$url .= '&filter_user_group_id=' . $this->request->get['filter_user_group_id'];
      		}

      		if (isset($this->request->get['filter_status'])) {
      			$url .= '&filter_status=' . $this->request->get['filter_status'];
      		}

      		if (isset($this->request->get['filter_approved'])) {
      			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
      		}

      		if (isset($this->request->get['filter_date_added'])) {
      			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
      		}
      			
      		if (isset($this->request->get['sort'])) {
      			$url .= '&sort=' . $this->request->get['sort'];
      		}

      		if (isset($this->request->get['order'])) {
      			$url .= '&order=' . $this->request->get['order'];
      		}

      		$pagination = new Pagination();
      		$pagination->total = $user_total;
      		$pagination->page = $page;
      		$pagination->limit = $this->config->get('config_admin_limit');
      		$pagination->text = $this->language->get('text_pagination');
      		$pagination->url = HTTPS_SERVER . 'index.php?route=user/tutors&token=' . $this->session->data['token'] . $url . '&page={page}';
      			
      		$this->data['pagination'] = $pagination->render();

      		$this->data['filter_name'] = $filter_name;
      		$this->data['filter_all'] = $filter_all;
      		$this->data['filter_email'] = $filter_email;
      		$this->data['filter_user_group_id'] = $filter_user_group_id;
      		$this->data['filter_status'] = $filter_status;
      		$this->data['filter_approved'] = $filter_approved;
      		$this->data['filter_date_added'] = $filter_date_added;

      		$this->load->model('user/user_group');

      		$this->data['sort'] = $sort;
      		$this->data['order'] = $order;

      		$this->template = 'user/tutors_list.tpl';
      		$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function getListRejected() {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = NULL;
		}

		if (isset($this->request->get['filter_all'])) {
			$filter_all = $this->request->get['filter_all'];
		} else {
			$filter_all = NULL;
		}

		if (isset($this->request->get['filter_email'])) {
			$filter_email = $this->request->get['filter_email'];
		} else {
			$filter_email = NULL;
		}

		if (isset($this->request->get['filter_user_group_id'])) {
			$filter_user_group_id = $this->request->get['filter_user_group_id'];
		} else {
			$filter_user_group_id = 2;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = NULL;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_all'])) {
			$url .= '&filter_all=' . $this->request->get['filter_all'];
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}
			
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

		$this->document->breadcrumbs = array();

		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
		);

		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=user/tutors/rejected&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title_rejected'),
      		'separator' => ' :: '
      		);

      		$this->data['approve'] = HTTPS_SERVER . 'index.php?route=user/tutors/approve&token=' . $this->session->data['token'] . $url;
      		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=user/tutors/delete&token=' . $this->session->data['token'] . $url;

      		$this->data['users'] = array();

      		$data = array(
			'filter_name'              => $filter_name, 
			'filter_all'              => $filter_all, 
			'filter_email'             => $filter_email, 
			'filter_user_group_id' => $filter_user_group_id, 
			'filter_date_added'        => $filter_date_added,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
      		);

      		$user_total = $this->model_user_tutors->getTotalTutorsRejected($data);

      		$results = $this->model_user_tutors->getTutorsRejected($data);

      		foreach ($results as $result) {
      			$action = array();

      			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=user/tutors/update&token=' . $this->session->data['token'] . '&user_id=' . $result['user_id'] . $url
      			);
      				
      			$this->data['users'][] = array(
				'user_id'    => $result['user_id'],
				'name'           => $result['name'],
				'email'          => $result['email'],
				'date_added'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'       => isset($this->request->post['selected']) && in_array($result['user_id'], $this->request->post['selected']),
				'action'         => $action
      			);
      		}
      			
      		$this->data['heading_title'] = $this->language->get('heading_title_rejected');

      		$this->data['text_enabled'] = $this->language->get('text_enabled');
      		$this->data['text_disabled'] = $this->language->get('text_disabled');
      		$this->data['text_yes'] = $this->language->get('text_yes');
      		$this->data['text_no'] = $this->language->get('text_no');
      		$this->data['text_no_results'] = $this->language->get('text_no_results');

      		$this->data['column_name'] = $this->language->get('column_name');
      		$this->data['column_email'] = $this->language->get('column_email');
      		$this->data['column_date_added'] = $this->language->get('column_date_added');
      		$this->data['column_action'] = $this->language->get('column_action');

      		$this->data['button_approve'] = $this->language->get('button_approve');
      		$this->data['button_delete'] = $this->language->get('button_delete');
      		$this->data['button_filter'] = $this->language->get('button_filter');

      		$this->data['token'] = $this->session->data['token'];

      		if (isset($this->session->data['error'])) {
      			$this->data['error_warning'] = $this->session->data['error'];
      				
      			unset($this->session->data['error']);
      		} elseif (isset($this->error['warning'])) {
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

      		if (isset($this->request->get['filter_name'])) {
      			$url .= '&filter_name=' . $this->request->get['filter_name'];
      		}
      		if (isset($this->request->get['filter_all'])) {
      			$url .= '&filter_all=' . $this->request->get['filter_all'];
      		}

      		if (isset($this->request->get['filter_email'])) {
      			$url .= '&filter_email=' . $this->request->get['filter_email'];
      		}

      		if (isset($this->request->get['filter_user_group_id'])) {
      			$url .= '&filter_user_group_id=' . $this->request->get['filter_user_group_id'];
      		}

      		if (isset($this->request->get['filter_date_added'])) {
      			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
      		}
      			
      		if ($order == 'ASC') {
      			$url .= '&order=DESC';
      		} else {
      			$url .= '&order=ASC';
      		}

      		if (isset($this->request->get['page'])) {
      			$url .= '&page=' . $this->request->get['page'];
      		}

      		$this->data['sort_name'] = HTTPS_SERVER . 'index.php?route=user/tutors/rejected&token=' . $this->session->data['token'] . '&sort=name' . $url;
      		$this->data['sort_email'] = HTTPS_SERVER . 'index.php?route=user/tutors/rejected&token=' . $this->session->data['token'] . '&sort=c.email' . $url;
      		$this->data['sort_date_added'] = HTTPS_SERVER . 'index.php?route=user/tutors/rejected&token=' . $this->session->data['token'] . '&sort=c.date_added' . $url;

      		$url = '';

      		if (isset($this->request->get['filter_name'])) {
      			$url .= '&filter_name=' . $this->request->get['filter_name'];
      		}

      		if (isset($this->request->get['filter_all'])) {
      			$url .= '&filter_all=' . $this->request->get['filter_all'];
      		}

      		if (isset($this->request->get['filter_email'])) {
      			$url .= '&filter_email=' . $this->request->get['filter_email'];
      		}

      		if (isset($this->request->get['filter_date_added'])) {
      			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
      		}
      			
      		if (isset($this->request->get['sort'])) {
      			$url .= '&sort=' . $this->request->get['sort'];
      		}

      		if (isset($this->request->get['order'])) {
      			$url .= '&order=' . $this->request->get['order'];
      		}

      		$pagination = new Pagination();
      		$pagination->total = $user_total;
      		$pagination->page = $page;
      		$pagination->limit = $this->config->get('config_admin_limit');
      		$pagination->text = $this->language->get('text_pagination');
      		$pagination->url = HTTPS_SERVER . 'index.php?route=user/tutors/rejected&token=' . $this->session->data['token'] . $url . '&page={page}';
      			
      		$this->data['pagination'] = $pagination->render();

      		$this->data['filter_name'] = $filter_name;
      		$this->data['filter_all'] = $filter_all;
      		$this->data['filter_email'] = $filter_email;
      		$this->data['filter_user_group_id'] = $filter_user_group_id;
      		$this->data['filter_date_added'] = $filter_date_added;

      		$this->load->model('user/user_group');

      		$this->data['sort'] = $sort;
      		$this->data['order'] = $order;

      		$this->template = 'user/tutors_list_rejected.tpl';
      		$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function getForm($heading_title) {
		$this->data['heading_title'] = $this->language->get($heading_title);

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_select'] = $this->language->get('text_select');
		 
		$this->data['entry_username'] = $this->language->get('entry_username');
		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_cellphone'] = $this->language->get('entry_cellphone');
		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['entry_confirm'] = $this->language->get('entry_confirm');
		$this->data['entry_user_group'] = $this->language->get('entry_user_group');
		$this->data['entry_approved'] = $this->language->get('entry_approved');
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

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add'] = $this->language->get('button_add');
		$this->data['button_remove'] = $this->language->get('button_remove');

		$this->data['tab_general'] = $this->language->get('tab_general');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['username'])) {
			$this->data['error_username'] = $this->error['username'];
		} else {
			$this->data['error_username'] = '';
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

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		if (isset($this->request->get['filter_all'])) {
			$url .= '&filter_all=' . $this->request->get['filter_all'];
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}

		if (isset($this->request->get['filter_user_group_id'])) {
			$url .= '&filter_user_group_id=' . $this->request->get['filter_user_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

		$this->document->breadcrumbs = array();

		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
		);

		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=user/tutors&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
      		);

      		if (!isset($this->request->get['user_id'])) {
      			$this->data['action'] = HTTPS_SERVER . 'index.php?route=user/tutors/insert&token=' . $this->session->data['token'] . $url;
      		} else {
      			$this->data['action'] = HTTPS_SERVER . 'index.php?route=user/tutors/update&token=' . $this->session->data['token'] . '&user_id=' . $this->request->get['user_id'] . $url;
      		}

      		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=user/tutors&token=' . $this->session->data['token'] . $url;

      		if (isset($this->request->get['user_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      			$user_info = $this->model_user_tutors->getTutor($this->request->get['user_id']);
      		}
      		//print_r($user_info);
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

      		$this->load->model('user/user_group');

      		if (isset($this->request->post['user_group_id'])) {
      			$this->data['user_group_id'] = $this->request->post['user_group_id'];
      		} elseif (isset($user_info)) {
      			$this->data['user_group_id'] = $user_info['user_group_id'];
      		} else {
      			$this->data['user_group_id'] = $this->config->get('config_user_group_id');
      		}
      		 
      		if (isset($this->request->post['approved'])) {
      			$this->data['approved'] = $this->request->post['approved'];
      		} elseif (isset($user_info)) {
      			$this->data['approved'] = $user_info['approved'];
      		} else {
      			$this->data['approved'] = 1;
      		}

      		if (isset($this->request->post['status'])) {
      			$this->data['status'] = $this->request->post['status'];
      		} elseif (isset($user_info)) {
      			$this->data['status'] = $user_info['status'];
      		} else {
      			$this->data['status'] = 1;
      		}

      		if (isset($this->request->post['username'])) {
      			$this->data['username'] = $this->request->post['username'];
      		} elseif (isset($user_info)) {
      			$this->data['username'] = $user_info['username'];
      		} else {
      			$this->data['username'] = '';
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

      		$this->template = 'user/tutors_form.tpl';
      		$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function getForm_view($heading_title) {
		$this->data['heading_title'] = $this->language->get($heading_title);

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_select'] = $this->language->get('text_select');
		 
		$this->data['entry_username'] = $this->language->get('entry_username');
		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_cellphone'] = $this->language->get('entry_cellphone');
		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['entry_confirm'] = $this->language->get('entry_confirm');
		$this->data['entry_user_group'] = $this->language->get('entry_user_group');
		$this->data['entry_approved'] = $this->language->get('entry_approved');
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

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add'] = $this->language->get('button_add');
		$this->data['button_remove'] = $this->language->get('button_remove');

		$this->data['tab_general'] = $this->language->get('tab_general');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['username'])) {
			$this->data['error_username'] = $this->error['username'];
		} else {
			$this->data['error_username'] = '';
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

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		if (isset($this->request->get['filter_all'])) {
			$url .= '&filter_all=' . $this->request->get['filter_all'];
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}

		if (isset($this->request->get['filter_user_group_id'])) {
			$url .= '&filter_user_group_id=' . $this->request->get['filter_user_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

		$this->document->breadcrumbs = array();

		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
		);

		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=user/tutors&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
      		);

      		if (!isset($this->request->get['user_id'])) {
      			$this->data['action'] = HTTPS_SERVER . 'index.php?route=user/tutors/insert&token=' . $this->session->data['token'] . $url;
      		} else {
      			$this->data['action'] = HTTPS_SERVER . 'index.php?route=user/tutors/update&token=' . $this->session->data['token'] . '&user_id=' . $this->request->get['user_id'] . $url;
      		}

      		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=user/tutors&token=' . $this->session->data['token'] . $url;

      		if (isset($this->request->get['user_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      			$user_info = $this->model_user_tutors->getTutor($this->request->get['user_id']);
      		}
      		//print_r($user_info);
      		if (isset($user_info)) {
      			$this->data['firstname'] = $user_info['firstname'];
      		} else {
      			$this->data['firstname'] = '';
      		}

      		if (isset($user_info)) {
      			$this->data['lastname'] = $user_info['lastname'];
      		} else {
      			$this->data['lastname'] = '';
      		}

      		if (isset($user_info)) {
      			$this->data['home_phone'] = $user_info['home_phone'];
      		} else {
      			$this->data['home_phone'] = '';
      		}

      		if (isset($user_info)) {
      			$this->data['cell_phone'] = $user_info['cell_phone'];
      		} else {
      			$this->data['cell_phone'] = '';
      		}

      		if (isset($user_info)) {
      			$this->data['email'] = $user_info['email'];
      		} else {
      			$this->data['email'] = '';
      		}

      		$this->load->model('user/user_group');

      		if (isset($user_info)) {
      			$this->data['user_group_id'] = $user_info['user_group_id'];
      		} else {
      			$this->data['user_group_id'] = $this->config->get('config_user_group_id');
      		}
      		 
      		if (isset($user_info)) {
      			$this->data['approved'] = $user_info['approved'];
      		} else {
      			$this->data['approved'] = 1;
      		}

      		if (isset($user_info)) {
      			$this->data['status'] = $user_info['status'];
      		} else {
      			$this->data['status'] = 1;
      		}

      		if (isset($user_info)) {
      			$this->data['username'] = $user_info['username'];
      		} else {
      			$this->data['username'] = '';
      		}

      		 

      		if (isset($user_info)) {
      			$this->data['address'] = $user_info['address'];
      		} else {
      			$this->data['address'] = '';
      		}

      		if (isset($user_info)) {
      			$this->data['city'] = $user_info['city'];
      		} else {
      			$this->data['city'] = '';
      		}

      		if (isset($user_info)) {
      			$this->data['state'] = $user_info['state'];
      		} else {
      			$this->data['state'] = '';
      		}

      		$all_states = $this->zone($this->data['state']);
      		$this->data['list_states'] = $all_states;

      		if (isset($user_info)) {
      			$this->data['pcode'] = $user_info['pcode'];
      		} else {
      			$this->data['pcode'] = '';
      		}

      		if (isset($user_info)) {
      			$this->data['country'] = $user_info['country'];
      		} else {
      			$this->data['country'] = '';
      		}
      		$all_countries = $this->country($this->data['country']);
      		$this->data['list_country'] = $all_countries;


      		if (isset($user_info)) {
      			$this->data['users_note'] = $user_info['users_note'];
      		} else {
      			$this->data['users_note'] = '';
      		}

      		if (isset($user_info)) {
      			$this->data['post_secondary_education'] = $user_info['post_secondary_education'];
      		} else {
      			$this->data['post_secondary_education'] = '';
      		}

      		if (isset($user_info)) {
      			$this->data['subjects_studied'] = $user_info['subjects_studied'];
      		} else {
      			$this->data['subjects_studied'] = '';
      		}

      		if (isset($user_info)) {
      			$this->data['courses_available'] = $user_info['courses_available'];
      		} else {
      			$this->data['courses_available'] = '';
      		}

      		if (isset($user_info)) {
      			$this->data['previous_experience'] = $user_info['previous_experience'];
      		} else {
      			$this->data['previous_experience'] = '';
      		}

      		if (isset($user_info)) {
      			$this->data['cities'] = $user_info['cities'];
      		} else {
      			$this->data['cities'] = '';
      		}

      		if (isset($user_info)) {
      			$this->data['references'] = $user_info['references'];
      		} else {
      			$this->data['references'] = '';
      		}

      		if (isset($user_info)) {
      			$this->data['gender'] = $user_info['gender'];
      		} else {
      			$this->data['gender'] = '';
      		}

      		if (isset($user_info)) {
      			$this->data['certified_teacher'] = $user_info['certified_teacher'];
      		} else {
      			$this->data['certified_teacher'] = '';
      		}

      		if (isset($user_info)) {
      			$this->data['criminal_conviction'] = $user_info['criminal_conviction'];
      		} else {
      			$this->data['criminal_conviction'] = '';
      		}

      		if (isset($user_info)) {
      			$this->data['background_check'] = $user_info['background_check'];
      		} else {
      			$this->data['background_check'] = '';
      		}

      		$this->template = 'user/tutors_view.tpl';
      		$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
		
	public function approve() {
		$this->load->language('user/tutors');
		$this->load->language('mail/user');
		 
		if (!$this->user->hasPermission('modify', 'user/tutors')) {
			$this->session->data['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->post['selected'])) {
				$this->load->model('user/tutors');
					
				foreach ($this->request->post['selected'] as $user_id) {
					$user_info = $this->model_user_tutors->getTutor($user_id);
						
					if ($user_info && !$user_info['approved']) {
						$this->model_user_tutors->approve($user_id);

						$this->load->model('setting/store');
							
						/*$store_info = $this->model_setting_store->getStore($user_info['store_id']);

						if ($store_info) {
						$store_name = $store_info['name'];
						$store_url = $store_info['url'] . 'index.php?route=account/login';
						} else {
						$store_name = $this->config->get('config_name');
						$store_url = $this->config->get('config_url') . 'index.php?route=account/login';
						}

						$message  = sprintf($this->language->get('text_welcome'), $store_name) . "\n\n";;
						$message .= $this->language->get('text_login') . "\n";
						$message .= $store_url . "\n\n";
						$message .= $this->language->get('text_services') . "\n\n";
						$message .= $this->language->get('text_thanks') . "\n";
						$message .= $store_name;

						$mail = new Mail();
						$mail->protocol = $this->config->get('config_mail_protocol');
						$mail->hostname = $this->config->get('config_smtp_host');
						$mail->username = $this->config->get('config_smtp_username');
						$mail->password = $this->config->get('config_smtp_password');
						$mail->parameter = $this->config->get('config_mail_parameter');
						$mail->port = $this->config->get('config_smtp_port');
						$mail->timeout = $this->config->get('config_smtp_timeout');
						$mail->setTo($user_info['email']);
						$mail->setFrom($this->config->get('config_email'));
						$mail->setSender($store_name);
						$mail->setSubject(sprintf($this->language->get('text_subject'), $store_name));
						$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
						$mail->send();*/
							
						$this->session->data['success'] = sprintf($this->language->get('text_approved'), $user_info['firstname'] . ' ' . $user_info['lastname']);
					}
				}
			}
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		if (isset($this->request->get['filter_all'])) {
			$url .= '&filter_all=' . $this->request->get['filter_all'];
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . $this->request->get['filter_email'];
		}

		if (isset($this->request->get['filter_user_group_id'])) {
			$url .= '&filter_user_group_id=' . $this->request->get['filter_user_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

		$this->redirect(HTTPS_SERVER . 'index.php?route=user/tutors&token=' . $this->session->data['token'] . $url);
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

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'user/tutors')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$user_id = isset($this->request->get['user_id'])?$this->request->get['user_id']:"";
		$this->request->post['certified_teacher'] = isset($this->request->post['certified_teacher'])?$this->request->post['certified_teacher']:"";
		$this->request->post['criminal_conviction'] = isset($this->request->post['criminal_conviction'])?$this->request->post['criminal_conviction']:"";
		$this->request->post['background_check'] = isset($this->request->post['background_check'])?$this->request->post['background_check']:"";

		if ((strlen(utf8_decode($this->request->post['username'])) < 1) || (strlen(utf8_decode($this->request->post['username'])) > 32)) {
			$this->error['username'] = $this->language->get('error_username');
		}

		if($this->model_user_tutors->validateUsername($this->request->post['username'], $user_id)){
			$this->error['username'] = $this->language->get('error_usernameexist');
		}

		if ((strlen(utf8_decode($this->request->post['firstname'])) < 1) || (strlen(utf8_decode($this->request->post['firstname'])) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}

		if ((strlen(utf8_decode($this->request->post['lastname'])) < 1) || (strlen(utf8_decode($this->request->post['lastname'])) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}
		 
		if ((strlen(utf8_decode($this->request->post['email'])) > 96) || (!preg_match(EMAIL_PATTERN, $this->request->post['email']))) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if($this->model_user_tutors->validateEmail($this->request->post['email'], $user_id)){
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

		if ((strlen(utf8_decode($this->request->post['users_note'])) < 3) || (strlen(utf8_decode($this->request->post['users_note'])) > 300)) {
			$this->error['users_note'] = $this->language->get('error_notes');
		}

		if ((strlen(utf8_decode($this->request->post['post_secondary_education'])) < 3) || (strlen(utf8_decode($this->request->post['post_secondary_education'])) > 300)) {
			$this->error['post_secondary_education'] = $this->language->get('error_post_secondary_education');
		}

		if ((strlen(utf8_decode($this->request->post['subjects_studied'])) < 3) || (strlen(utf8_decode($this->request->post['subjects_studied'])) > 300)) {
			$this->error['subjects_studied'] = $this->language->get('error_subjects_studied');
		}

		if ((strlen(utf8_decode($this->request->post['courses_available'])) < 3) || (strlen(utf8_decode($this->request->post['courses_available'])) > 300)) {
			$this->error['courses_available'] = $this->language->get('error_courses_available');
		}

		if ((strlen(utf8_decode($this->request->post['previous_experience'])) < 3) || (strlen(utf8_decode($this->request->post['previous_experience'])) > 300)) {
			$this->error['previous_experience'] = $this->language->get('error_previous_experience');
		}

		if ((strlen(utf8_decode($this->request->post['cities'])) < 3) || (strlen(utf8_decode($this->request->post['cities'])) > 300)) {
			$this->error['cities'] = $this->language->get('error_cities');
		}

		if ((strlen(utf8_decode($this->request->post['references'])) < 3) || (strlen(utf8_decode($this->request->post['references'])) > 300)) {
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

		if (($this->request->post['password']) || (!isset($this->request->get['user_id']))) {
			if ((strlen(utf8_decode($this->request->post['password'])) < 4) || (strlen(utf8_decode($this->request->post['password'])) > 20)) {
				$this->error['password'] = $this->language->get('error_password');
			}

			if ($this->request->post['password'] != $this->request->post['confirm']) {
				$this->error['confirm'] = $this->language->get('error_confirm');
			}
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'user/tutors')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
	  
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

}
?>
