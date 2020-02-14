<?php
class ModelStudentTutor extends Model {
	public function getStudentReport() {
			$sql = "SELECT u.user_id, CONCAT_WS(' ',firstname,lastname) as name , email , city
					FROM user u, user_info ui WHERE u.user_id = ui.user_id AND u.user_group_id =1 GROUP BY u.user_id";
			$query = $this->db->query($sql);
			$final_result = array();
			$result = $query->rows;
			foreach($results as $result)
			{
				$sql = "SELECT SUM( `total_hours` ) as th , SUM( `total_amount` ) as ta
						FROM `student_invoice` WHERE `student_id` = '".$result['user_id']."'";
				$query = $this->db->query($sql);
				$row = $query->row; 
				$total_hours =  (float)$row['th'] ;
				$total_amount = (float)$row['ta'] ;
				
				$sql = "SELECT SUM( `total_hours` ) as th ,  SUM( `total` ) as ta
						FROM `order` WHERE `customer_id` = '".$result['user_id']."'
						AND `package_id` <>0 AND `order_status_id` = '5'";
				$query = $this->db->query($sql);
				$row = $query->row; 
				$total_hours =  $total_hours+(float)$row['th'] ;
				$total_amount = $total_amount+(float)$row['ta'] ;
				
				$sql = "SELECT SUM( base_wage * session_duration ) as tr FROM tutors_to_students ts, sessions s 
						WHERE ts.`tutors_to_students_id` = s.`tutors_to_students_id` AND ts.`students_id` ='".$result['user_id']."'";
				$query = $this->db->query($sql);
				$row = $query->row;
				$total_revenues =  (float)$row['tr'];
				
				$final_result = array($result['user_id'],$result['name'],$result['email'],$result['city'],$total_hours,$total_amount,$total_amount-$total_revenues);
			}
			print_r($final_result);
			exit;
			return $final_result;
	}

}
