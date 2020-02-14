<?php
class ModelCmsLogmail extends Model {
	public function addInformation($data) {
		$this->db->query("INSERT INTO ".DB_PREFIX."mail_log SET " .
				" mail_from = '".$this->db->escape($data['mail_from']).
				"', mail_to = '".$this->db->escape($data['mail_to']).
				"', headers = '".$this->db->escape($data['headers']).
				"', subject = '".$this->db->escape($data['subject']).
				"', message = '".$this->db->escape($data['message']).
				"'");
		$logid = $this->db->getLastId();	
		return $logid; 
	}
	
	public function getInformation($logid) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "mail_log WHERE log_id = '" . (int)$logid . "'");
		
		return $query->row;		
	}
	
	public function getInformations($data = array(), $select = " * ") {

		$sql = "SELECT $select FROM " . DB_PREFIX . "mail_log";
		
		$implode = array();
		
		if (isset($data['filter_mail_from']) && !is_null($data['filter_mail_from'])) {
			$implode[] = " mail_from like '%" . $this->db->escape($data['filter_mail_from']) . "%'";
		}
		
		if (isset($data['filter_mail_to']) && !is_null($data['filter_mail_to'])) {
			$implode[] = " mail_to like '%" . $this->db->escape($data['filter_mail_to']) . "%'";
		}
		
		if (isset($data['filter_subject']) && !is_null($data['filter_subject'])) {
			$implode[] = " subject like '%" . $this->db->escape($data['filter_subject']) . "%'";
		}
		
		if (isset($data['filter_date_send']) && !is_null($data['filter_date_send'])) {
			$implode[] = " date_send = '" . $data['filter_date_send'] . "'";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
	
		$sort_data = array(
			'date_send',
			'log_id'
		);		
	
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY date_send";	
		}
		
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
	
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}		

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
		
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}	

		$query = $this->db->query($sql);
		
		return $query->rows;
		
	}
	
	public function getTotalInformations($data = array()) {
		
		$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "mail_log";
		
		$implode = array();
		
		if (isset($data['filter_mail_from']) && !is_null($data['filter_mail_from'])) {
			$implode[] = " mail_from like '%" . $this->db->escape($data['filter_mail_from']) . "%'";
		}
		
		if (isset($data['filter_mail_to']) && !is_null($data['filter_mail_to'])) {
			$implode[] = " mail_to like '%" . $this->db->escape($data['filter_mail_to']) . "%'";
		}
		
		if (isset($data['filter_subject']) && !is_null($data['filter_subject'])) {
			$implode[] = " subject like '%" . $this->db->escape($data['filter_subject']) . "%'";
		}
		
		if (isset($data['filter_date_send']) && !is_null($data['filter_date_send'])) {
			$implode[] = " date_send = '" . $data['filter_date_send'] . "'";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
			
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
}
?>