<?php
class ControllerStudentReport extends Controller {
	public function studentreport() {
		$this->load->language('report/monthlydata');
		$this->document->title = $this->language->get('heading_title_statistics');
		$this->data['heading_title'] = $this->language->get('heading_title_statistics');

		$this->document->breadcrumbs = array();
		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
		);
		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=report/monthlydata&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
      		);
      		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=report/monthlydata/monthlystatiscs&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title_statistics'),
      		'separator' => ' :: '
      		);


      		$this->load->model('report/studenttutorreport');
      		$results = $this->model_student_tutor->getStudentReport();


      		$url = '';



      		$this->template = 'report/studentreport.tpl';
      		$this->children = array(
			'common/header',	
			'common/footer'	
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	function export()
	{
	}
}
