<?php
class ModelCmsNotifications extends Model {
	public function addInformation($data) {
		$this->db->query("INSERT INTO ".DB_PREFIX."notifications SET notification_from = '".$this->db->escape($data['notification_from'])."', notification_to = '".$this->db->escape($data['notification_to'])."', headers = '', subject = '".$this->db->escape($data['subject'])."', message = '".$this->db->escape($data['message'])."', date_send = now() ");
		$notification_id = $this->db->getLastId();	
		return $notification_id; 
	}
	
	public function getInformation($notification_id) {
		
		if($this->user->getUserGroupId() == '4')
			$query = $this->db->query("SELECT *, CONCAT(u.firstname,' ', u.lastname) as notification_from, g.name as group_name FROM " . DB_PREFIX . "notifications n LEFT JOIN ".DB_PREFIX." user u ON n.notification_from = u.user_id LEFT JOIN ".DB_PREFIX." user_group g ON u.user_group_id = g.user_group_id WHERE n.notification_id = '" . (int)$notification_id . "'");
		else
			$query = $this->db->query("SELECT *, CONCAT(u.firstname,' ', u.lastname) as notification_from, g.name as group_name FROM " . DB_PREFIX . "notifications n LEFT JOIN ".DB_PREFIX." user u ON n.notification_from = u.user_id LEFT JOIN ".DB_PREFIX." user_group g ON u.user_group_id = g.user_group_id WHERE n.notification_to = '".$this->session->data['user_id']."' AND n.notification_id = '" . (int)$notification_id . "'");
		
		return $query->row;		
	}
	
	public function deleteInformation($notification_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "notifications WHERE notification_id = '" . (int)$notification_id . "'");
	}
	
	/*Function added on 8th september, 2014 */
	public function getHomepageInformations() {

		$sql = "(SELECT CONCAT(u.firstname,' ', u.lastname) as name, g.name as group_name, n.subject as subject, n.date_send as date_considered, n.notification_id as id, n.message as message, 'notification' as type FROM notifications n LEFT JOIN user u ON n.notification_from = u.user_id LEFT JOIN user_group g ON u.user_group_id = g.user_group_id WHERE n.notification_to in (1,2) and u.user_group_id = 1 ORDER BY date_send DESC LIMIT 0,30) 
		
		UNION 
		
		(SELECT CONCAT(c.firstname, ' ', c.lastname) AS name, 'Student' AS group_name, ' ' as subject, c.date_added as date_considered, c.user_id as id, ' ' as message, 'registration' as type  FROM user c LEFT JOIN user_info ui ON (c.user_id = ui.user_id) WHERE c.user_group_id = 1 GROUP BY c.user_id ORDER BY c.date_added DESC LIMIT 0,30) 
		
		ORDER BY date_considered DESC LIMIT 0,30";	
		
		$query = $this->db->query($sql);
		
		return $query->rows;
		
	}
	
	
	
	
	public function getInformations($data = array()) {

		$sql = "SELECT *, CONCAT(u.firstname,' ', u.lastname) as notification_from, g.name as group_name FROM " . DB_PREFIX . "notifications n LEFT JOIN ".DB_PREFIX." user u ON n.notification_from = u.user_id LEFT JOIN ".DB_PREFIX." user_group g ON u.user_group_id = g.user_group_id ";
		
		$implode = array();
		
		if (isset($data['filter_notification_from']) && !is_null($data['filter_notification_from'])) {
			$implode[] = " notification_from like '%" . $this->db->escape($data['filter_notification_from']) . "%'";
		}
		
		if (isset($data['filter_notification_to']) && !is_null($data['filter_notification_to'])) {
			$implode[] = " notification_to like '%" . $this->db->escape($data['filter_notification_to']) . "%'";
		}
		
		if (isset($data['filter_subject']) && !is_null($data['filter_subject'])) {
			$implode[] = " subject like '%" . $this->db->escape($data['filter_subject']) . "%'";
		}
		
		if (isset($data['filter_date_send']) && !is_null($data['filter_date_send'])) {
			$implode[] = " date_send = '" . $data['filter_date_send'] . "'";
		}
		
		if (isset($data['filter_to_users']) && !is_null($data['filter_to_users'])) {
			$filter_to_users = implode(",", (array) $data['filter_to_users']);			
			$implode[] = " n.notification_to in (". $filter_to_users .") ";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
	
		$sort_data = array(
			'date_send',
			'notification_id'
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
		
		$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "notifications";
		
		$implode = array();
		
		if (isset($data['filter_notification_from']) && !is_null($data['filter_notification_from'])) {
			$implode[] = " notification_from like '%" . $this->db->escape($data['filter_notification_from']) . "%'";
		}
		
		if (isset($data['filter_notification_to']) && !is_null($data['filter_notification_to'])) {
			$implode[] = " notification_to like '%" . $this->db->escape($data['filter_notification_to']) . "%'";
		}
		
		if (isset($data['filter_subject']) && !is_null($data['filter_subject'])) {
			$implode[] = " subject like '%" . $this->db->escape($data['filter_subject']) . "%'";
		}
		
		if (isset($data['filter_date_send']) && !is_null($data['filter_date_send'])) {
			$implode[] = " date_send = '" . $data['filter_date_send'] . "'";
		}
		
		$sql .= " WHERE notification_to = '".$this->session->data['user_id']."' ";
		
		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}
			
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
}
?>