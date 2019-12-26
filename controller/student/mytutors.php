<?php    
class ControllerStudentMytutors extends Controller { 
	private $error = array();
  
  	public function index() {
		$this->load->language('student/assignment');
		$this->document->title = $this->language->get('heading_title_mystudents');
		$this->load->model('tutor/assignment');
    	$this->getList();
  	}
	
  	public function viewdetails() {
		$this->load->language('student/assignment');
		$this->document->title = $this->language->get('heading_title_students_details');
		$this->load->model('tutor/assignment');
		$tutors_to_students_id = $this->request->get['tutors_to_students_id'];
    	$tutor_info = $this->model_tutor_assignment->getTutorDetails($tutors_to_students_id);
		
		$arrsubids = $this->model_tutor_assignment->getAssignedSubjects($tutors_to_students_id);
		
		$this->data['date_added'] = date($this->language->get('date_format_short'), strtotime($tutor_info['date_added']));
		
		$this->data['column_tutor_name'] = $this->language->get('column_tutor_name');
		$this->data['column_status_by_student'] = $this->language->get('column_status_by_student');
		$this->data['entry_subjects'] = $this->language->get('entry_subjects');
		$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_cellphone'] = $this->language->get('entry_cellphone');
		$this->data['entry_wage'] = $this->language->get('entry_wage');
    	$this->data['entry_invoice'] = $this->language->get('entry_invoice');
		$this->data['text_address'] = $this->language->get('text_address');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		
		
		$this->data['button_back'] = $this->language->get('button_back');
		$this->data['heading_title'] = $this->language->get('heading_title_students_details');	
		$this->data['tutor_info'] = $tutor_info;	
		$this->data['arrsubids'] = $arrsubids;	
		$this->data['back'] = HTTPS_SERVER . 'index.php?route=student/mytutors&token=' . $this->session->data['token'];
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/student/tutor_details.tpl')) {
			$this->template = $this->config->get('config_template') . '/student/tutor_details.tpl';
		} else {
			$this->template = 'student/tutor_details.tpl';
		}
		
		$this->children = array(
			'common/footer',
			'common/header'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));		
		
  	} 
	
  	public function nomoretutoring() {
		$this->load->language('student/assignment');
		$this->load->model('tutor/assignment');
		
    	if ($this->request->server['REQUEST_METHOD'] == 'POST') {
		
			$this->load->model('cms/notifications');
			$message = "Student changed his tutoring status to <b>No More Tutoring</b>";
			$notification = array(
				'notification_from'=>$this->session->data['user_id'],
				'notification_to'=>'1',
				'subject'=>'Tutoring status changed',
				'message'=>$message
			);
			$this->model_cms_notifications->addInformation($notification);
			
			$this->model_tutor_assignment->updateTutorStatusByStudent($this->request->post['selected'], "No More Tutoring");
			$this->session->data['success'] = $this->language->get('text_student_success');
	  
			$url = '';

			if (isset($this->request->get['filter_status_by_student'])) {
				$url .= '&filter_status_by_student=' . $this->request->get['filter_status_by_student'];
			}
			
			if (isset($this->request->get['filter_tutor_name'])) {
				$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
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
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=student/mytutors&token=' . $this->session->data['token'] . $url);
		}
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
			$sort = 'name'; 
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['filter_tutor_name'])) {
			$filter_tutor_name = $this->request->get['filter_tutor_name'];
		} else {
			$filter_tutor_name = NULL;
		}
		
		if (isset($this->request->get['filter_status_by_student'])) {
			$filter_status_by_student = $this->request->get['filter_status_by_student'];
		} else {
			$filter_status_by_student = NULL;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = NULL;
		}		
		
		$url = '';
		
		if (isset($this->request->get['filter_tutor_name'])) {
			$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
		}
		
		if (isset($this->request->get['filter_status_by_student'])) {
			$url .= '&filter_status_by_student=' . $this->request->get['filter_status_by_student'];
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
       		'href'      => HTTPS_SERVER . 'index.php?route=student/mystudents&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title_mystudents'),
      		'separator' => ' :: '
   		);
		
		$this->data['no_more_tutoring'] = HTTPS_SERVER . 'index.php?route=student/mytutors/nomoretutoring&token=' . $this->session->data['token'] . $url;

		$this->data['mystudents'] = array();

		$data = array(
			'filter_status_by_student'             => $filter_status_by_student, 
			'filter_tutor_name'             => $filter_tutor_name, 
			'filter_date_added'        => $filter_date_added,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                    => $this->config->get('config_admin_limit')
		);
		
		$students_total = $this->model_tutor_assignment->getTotalTutors($data);
		$results = $this->model_tutor_assignment->getTutors($data);
 
    	foreach ($results as $result) {
			$action = array();
		
			$action[] = array(
				'text' => $this->language->get('text_view_details'),
				'href' => HTTPS_SERVER . 'index.php?route=student/mytutors/viewdetails&token=' . $this->session->data['token'] . '&tutors_to_students_id=' . $result['tutors_to_students_id'] . $url
			);
			$subjects = $this->model_tutor_assignment->getAssignedSubjects($result['tutors_to_students_id']);
			$this->data['mystudents'][] = array(
				'tutors_to_students_id'    => $result['tutors_to_students_id'],
				'subjects'    => $subjects,
				'tutor_name'          => $result['tutor_name'],
				'status_by_student'          => $result['status_by_student'],
				'date_added'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'       => isset($this->request->post['selected']) && in_array($result['user_id'], $this->request->post['selected']),
				'action'         => $action
			);
		}	
					
		$this->data['heading_title'] = $this->language->get('heading_title_mystudents');	
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_status_by_student'] = $this->language->get('column_status_by_student');
		$this->data['column_tutor_name'] = $this->language->get('column_tutor_name');
		$this->data['column_subjects'] = $this->language->get('column_subjects');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_unassing'] = $this->language->get('button_unassing');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_done_tutoring'] = $this->language->get('button_done_tutoring');
		$this->data['button_no_more_tutoring'] = $this->language->get('button_no_more_tutoring');
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

		if (isset($this->request->get['filter_status_by_student'])) {
			$url .= '&filter_status_by_student=' . $this->request->get['filter_status_by_student'];
		}
		
		if (isset($this->request->get['filter_tutor_name'])) {
			$url .= '&filter_tutor_name=' . $this->request->get['filter_tutor_name'];
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
		$this->data['sort_status_by_student'] = HTTPS_SERVER . 'index.php?route=student/mystudents&token=' . $this->session->data['token'] . '&sort=status_by_student' . $url;
		$this->data['sort_tutor_name'] = HTTPS_SERVER . 'index.php?route=student/mystudents&token=' . $this->session->data['token'] . '&sort=tutor_name' . $url;
		$this->data['sort_date_added'] = HTTPS_SERVER . 'index.php?route=student/mystudents&token=' . $this->session->data['token'] . '&sort=date_added' . $url;


		$pagination = new Pagination();
		$pagination->total = $students_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=student/mystudents&token=' . $this->session->data['token'] . $url . '&page={page}';
			
		$this->data['pagination'] = $pagination->render();
		$this->data['filter_status_by_student'] = $filter_status_by_student;
		$this->data['filter_tutor_name'] = $filter_tutor_name;
		$this->data['filter_date_added'] = $filter_date_added;
		
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'student/mytutors.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
  	}

}
?>
