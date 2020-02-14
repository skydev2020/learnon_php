<?php
class ModelCmsPaycheque extends Model {
	public function getMailFormat($format_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "broadcasts WHERE broadcasts_id = '" . (int)$format_id . "'");

		return $query->row;
	}

	public function editPayCheque($paycheque_id, $data) {

		$this->db->query("UPDATE " . DB_PREFIX . "tutor_paycheque SET
		paycheque_num = '" . $this->db->escape($data['paycheque_num']) ."', 
		paycheque_date = '" . $this->db->escape($data['paycheque_date']) ."', 
		total_hours = '" . $this->db->escape($data['total_hours']) ."', 
		total_amount = '" . $this->db->escape($data['total_amount']) ."', 
		balance_amount = '" . $this->db->escape($data['balance_amount']) ."', 
		paid_amount = '" . $this->db->escape($data['paid_amount']) ."', paycheque_notes = '" . $this->db->escape($data['paycheque_notes']) ."', paycheque_status = '" . $this->db->escape($data['paycheque_status']) ."',  send_date = '" . $this->db->escape($data['pay_date']) ."',  pay_date = '" . $this->db->escape($data['pay_date']) ."', num_of_sessions = '" . (int)$data['num_of_sessions'] ."' WHERE paycheque_id = '" . (int)$paycheque_id."'");
	}

	//softronikx Technologies function
	public function markPaychequeAsPaid($data) {

		if(isset($data['paycheque_id']) and !empty($data['paycheque_id']) and is_numeric($data['paycheque_id']))
		{
			$this->db->query("UPDATE " . DB_PREFIX . "tutor_paycheque SET
			balance_amount = '" . $this->db->escape($data['balance_amount']) ."', 	
			paycheque_status = '" . $this->db->escape($data['paycheque_status']) ."', 			
			pay_date = '" . $this->db->escape($data['pay_date']) ."',
			paid_amount = total_amount WHERE paycheque_id = '" . (int)$data['paycheque_id']."' LIMIT 1");		
		}

	}



	public function getPayCheque($paycheque_id) {
		$sql = "SELECT p.*, concat(ut.firstname,' ',ut.lastname) as tutor_name, ut.username as tutors_email FROM " . DB_PREFIX . "tutor_paycheque p LEFT JOIN user ut ON (p.tutor_id = ut.user_id) WHERE paycheque_id = '" . (int)$paycheque_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function lockPaycheque($paycheque_id) {
		$locked_status = 1;
		$this->db->query("UPDATE `" . DB_PREFIX . "tutor_paycheque` SET is_locked = '" . $locked_status . "' WHERE paycheque_id = '" . (int)$paycheque_id . "' ");
	}

	public function unlockPaycheque($paycheque_id) {
		$locked_status = 0;
		$this->db->query("UPDATE `" . DB_PREFIX . "tutor_paycheque` SET is_locked = '" . $locked_status . "' WHERE paycheque_id = '" . (int)$paycheque_id . "' ");
	}

	public function deletePaycheque($paycheque_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "tutor_paycheque WHERE paycheque_id = '" . (int)$paycheque_id . "'");
	}

	public function getPayCheques($data = array(), $select = "") {

		if(!empty($select)) {
			$sql = "SELECT $select FROM " . DB_PREFIX . "tutor_paycheque p LEFT JOIN user ut ON (p.tutor_id = ut.user_id) ";
		} else {
			$sql = "SELECT p.*, concat(ut.firstname,' ',ut.lastname, ' (', user_id,')') as tutor_name FROM " . DB_PREFIX . "tutor_paycheque p LEFT JOIN user ut ON (p.tutor_id = ut.user_id) ";
		}

			
		$implode = array();

		/*Softronikx Technologies */
		if (isset($data['filter_user_id']) && !is_null($data['filter_user_id'])) {
			$implode[] = "p.tutor_id = '" . $this->db->escape($data['filter_user_id']) . "' ";
		}
		/* End of code by Softronikx Technologies */

		if (isset($data['filter_tutor_name']) && !is_null($data['filter_tutor_name'])) {
			$implode[] = " concat(ut.firstname,' ',ut.lastname) like '%" . $this->db->escape($data['filter_tutor_name']) . "%' ";
		}

		if (isset($data['filter_paycheque_status']) && !is_null($data['filter_paycheque_status'])) {
			$implode[] = "p.paycheque_status = '" . $this->db->escape($data['filter_paycheque_status']) . "' ";
		}

		if (isset($data['filter_total_amount']) && !is_null($data['filter_total_amount'])) {
			$implode[] = "p.total_amount = '" . $this->db->escape($data['filter_total_amount']) . "' ";
		}

		if (isset($data['filter_total_hours']) && !is_null($data['filter_total_hours'])) {
			$implode[] = "p.total_hours = '" . $this->db->escape($data['filter_total_hours']) . "' ";
		}

		if (isset($data['filter_paycheque_date']) && !is_null($data['filter_paycheque_date'])) {

			$implode[] = "p.paycheque_date like '" . $this->db->escape($data['filter_paycheque_date']) . "%'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'tutor_name',
			'total_hours',
			'total_amount',
			'paycheque_status',
			'total_hours'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				if($data['sort'] == "tutor_name")
				$sql .= " ORDER BY lower(concat(ut.firstname,' ',ut.lastname)) ";
				else
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY total_hours";
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

			//echo $sql;

			$query = $this->db->query($sql);
			return $query->rows;
	}

	public function getMonthlyExpense($month=0, $year=0) {
		if(!empty($month) && !empty($year)) {

			//Softronikx Technologies - commented original sql and replaced with the new one
			$sql = "SELECT sum(total_amount) as expense FROM " . DB_PREFIX . "tutor_paycheque WHERE month(paycheque_date) = '". $month ."' AND year(paycheque_date) = '". $year ."' AND paycheque_status = 'Paid' group by month(paycheque_date) ";

			//$sql = "SELECT sum(paid_amount) as expense FROM " . DB_PREFIX . "tutor_paycheque WHERE month(pay_date) = '". $month ."' AND year(pay_date) = '". $year ."' group by month(pay_date) ";

			$query = $this->db->query($sql);

			//Softronikx Technologies
			$sql = "SELECT amount as expense FROM " . DB_PREFIX . "profits WHERE month(date) = '". $month ."' AND year(date) = '". $year ."' and name LIKE '%Tutor Payments%' group by month(date) ";

			$query2 = $this->db->query($sql);

			if(count($query->row) > 0 or count($query2->row) > 0)
			return $query->row['expense'] + $query2->row['expense'];
			else
			return 0;

		} else {
			return 0;
		}
	}

	public function getYearlyExpense($year=0) {
		if(!empty($year)) {
			$sql = "SELECT sum(paid_amount) as expense FROM " . DB_PREFIX . "tutor_paycheque WHERE year(pay_date) = '". $year ."' group by year(pay_date) ";

			$query = $this->db->query($sql);

			if(count($query->row) > 0)
			return $query->row['expense'];
			else
			return 0;

		} else {
			return 0;
		}
	}

	public function getTotalPayCheques($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tutor_paycheque p LEFT JOIN user ut ON (p.tutor_id = ut.user_id) ";
		$implode = array();

		/*Softronikx Technologies */
		if (isset($data['filter_user_id']) && !is_null($data['filter_user_id'])) {
			$implode[] = "p.tutor_id = '" . $this->db->escape($data['filter_user_id']) . "' ";
		}
		/* End of code by Softronikx Technologies */


		if (isset($data['filter_tutor_name']) && !is_null($data['filter_tutor_name'])) {
			$implode[] = " concat(ut.firstname,' ',ut.lastname) like '%" . $this->db->escape($data['filter_tutor_name']) . "%' ";
		}

		if (isset($data['filter_paycheque_status']) && !is_null($data['filter_paycheque_status'])) {
			$implode[] = "p.paycheque_status = '" . $this->db->escape($data['filter_paycheque_status']) . "' ";
		}

		if (isset($data['filter_total_amount']) && !is_null($data['filter_total_amount'])) {
			$implode[] = "p.total_amount = '" . $this->db->escape($data['filter_total_amount']) . "' ";
		}

		if (isset($data['filter_total_hours']) && !is_null($data['filter_total_hours'])) {
			$implode[] = "p.total_hours = '" . $this->db->escape($data['filter_total_hours']) . "' ";
		}

		if (isset($data['filter_paycheque_date']) && !is_null($data['filter_paycheque_date'])) {
			$implode[] = "p.paycheque_date like '" . $this->db->escape($data['filter_paycheque_date']) . "%'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	

	function getMonthlyMasterExportList($month=0,$year=0)
	{
		if(!empty($month) && !empty($year)) {
			$sql = "SELECT concat(ut.firstname,' ',ut.lastname) as tutor_name,ui.address,ui.city,ui.state,ui.pcode,SUM(p.total_amount) as total_amount,ui.country FROM tutor_paycheque p,user ut,user_info ui WHERE p.tutor_id = ut.user_id AND ut.user_id=ui.user_id AND p.paycheque_status <> 'PAID' AND  MONTH(paycheque_date)='". $month ."' AND YEAR(paycheque_date)='". $year ."' GROUP BY ut.user_id";
//			$sql = "SELECT concat(ut.firstname,' ',ut.lastname) as tutor_name,ui.address,ui.city,ui.state,ui.pcode,SUM(p.total_amount) as total_amount,ui.country FROM tutor_paycheque p,user ut,user_info ui WHERE p.tutor_id = ut.user_id AND ut.user_id=ui.user_id AND p.paycheque_status='PAID' AND MONTH(paycheque_date)='". $month ."' AND YEAR(paycheque_date)='". $year ."' GROUP BY ut.user_id";
			//echo $sql;
			//exit;
			$query = $this->db->query($sql);
			return $query->rows;
		}else{
			return 0;}
	}
}
?>