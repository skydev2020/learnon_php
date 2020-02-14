<?php
class ModelCmsLogactivity extends Model {

	public function getInformation($activity_id) {
		$query = $this->db->query("SELECT a.*, CONCAT(u.firstname, ' ', u.lastname) AS user_name, g.name as group_name FROM " . DB_PREFIX . "activity_log AS a  LEFT JOIN " . DB_PREFIX . "user AS u ON a.user_id = u.user_id  LEFT JOIN " . DB_PREFIX . "user_group AS g ON a.user_group_id = g.user_group_id WHERE activity_id = '" . (int)$activity_id . "'");
		return $query->row;		
	}
	
	public function getInformations($data = array()) {

		$sql = "SELECT a.*, CONCAT(u.firstname, ' ', u.lastname) AS user_name, g.name as group_name FROM " . DB_PREFIX . "activity_log AS a  LEFT JOIN " . DB_PREFIX . "user AS u ON a.user_id = u.user_id  LEFT JOIN " . DB_PREFIX . "user_group AS g ON a.user_group_id = g.user_group_id ";
	
		$sort_data = array(
			'date_added',
			'user_name',
			'group_name',
			'activity',
			'a.activity_id'
		);		
	
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY a.date_added";	
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
		$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "activity_log ";
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
}
?>