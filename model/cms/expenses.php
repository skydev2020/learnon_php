<?php
class ModelCmsExpenses extends Model {

	public function addExpenses($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "profits` SET " .
				" date = '" . $this->db->escape($data['date']) . 
				"', name = '" . $this->db->escape($data['name']) . 
				"', amount = '" . (float) $data['amount'] . 
				"', detail = '" . $this->db->escape($data['detail']) . 
				"', date_added = NOW()");
			
		$tutors_to_students_id = $this->db->getLastId();
	}

	public function addIncome($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "other_income` SET " .
				" date = '" . $this->db->escape($data['date']) . 
				"', name = '" . $this->db->escape($data['name']) . 
				"', amount = '" . (float) $data['amount'] . 
				"', notes = '" . $this->db->escape($data['notes']) . 
				"', timestamp = NOW()");
			
		$income_id = $this->db->getLastId();

		return $income_id;
	}

	public function addPastIncome($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "monthly_income` SET " .
				" date = '" . $this->db->escape($data['date']) . 
				"', tutoring_revenue = '" . $this->db->escape($data['tutoring_revenue']) . 
				"', homework_revenue = '" . (float) $data['homework_revenue'] . 
				"', other_revenue = '" . $this->db->escape($data['other_revenue']) . 
				"', date_added = NOW()");
			
		$income_id = $this->db->getLastId();

		return $income_id;
	}

	public function editExpenses($proexpen_id, $data) {

		$this->db->query("UPDATE `" . DB_PREFIX . "profits` SET " .
				" date = '" . $this->db->escape($data['date']) . 
				"', name = '" . $this->db->escape($data['name']) . 
				"', amount = '" . (float) $data['amount'] . 
				"', detail = '" . $this->db->escape($data['detail']) . 
				"', date_modified = NOW() " .
				" WHERE proexpen_id = '" . (int)$proexpen_id . "'");
	}

	public function deleteExpenses($proexpen_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "profits WHERE proexpen_id = '" . (int)$proexpen_id . "'");
	}

	public function getExpensesList() {
		return array('Google Adwords','Facebook Advertising','Yahoo Advertising','Print Advertising','Flyer Delivery','Other Advertising','Tutor Recruitment Cost','Printed Materials Expense','Website Expense','Website Maintenance','Website SEO monthly fee','Computer Expense','Telephone Expense','1800 number Expense','Postage Expense','Office Supplies','Interest Expense','Bank Fees','Accountant Expense','Collections Expense','Legal Expense','Tax Expense','Auto: Depreciation','Auto: Parking and Tolls','Auto: Gasoline','Auto: Service and Reg','Auto: Public Transport','Auto: Misc','Gift Expense','Charity','Entertainment','Medical Expense','Travel Expense','Miscelanous Expense');
	}

	public function getExpenses($proexpen_id) {

		$sql = "SELECT * FROM " . DB_PREFIX . "profits WHERE proexpen_id = '". (int)$proexpen_id ."'";

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getMonthlyExpense($month=0, $year=0) {
		if(!empty($month) && !empty($year)) {

			//not like conditon by Softronikx Technologies
			$sql = "SELECT sum(amount) as expense FROM " . DB_PREFIX . "profits WHERE month(date) = '". $month ."' AND year(date) = '". $year ."' and name NOT LIKE  '%Tutor Payments%' group by month(date) ";

			$query = $this->db->query($sql);

			if(count($query->row) > 0)
			return $query->row['expense'];
			else
			return 0;

		} else {
			return 0;
		}
	}

	public function getYearlyExpense($year=0) {
		if(!empty($year)) {
			$sql = "SELECT sum(amount) as expense FROM " . DB_PREFIX . "profits WHERE year(date) = '". $year ."' group by year(date) ";

			$query = $this->db->query($sql);

			if(count($query->row) > 0)
			return $query->row['expense'];
			else
			return 0;

		} else {
			return 0;
		}
	}

	public function getAllExpenses($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "profits ";

		$implode = array();

		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$implode[] = " name like '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (isset($data['filter_amount']) && !is_null($data['filter_amount'])) {
			$implode[] = " amount = '" . (float)$data['filter_amount'] . "'";
		}

		if (isset($data['filter_date']) && !is_null($data['filter_date'])) {
			$implode[] = " date LIKE '" . $this->db->escape($data['filter_date']) . "%'";
		}

		if (isset($data['filter_start_date']) && !is_null($data['filter_start_date']) && isset($data['filter_end_date']) && !is_null($data['filter_end_date'])) {
			$implode[] = " date BETWEEN '" . $this->db->escape($data['filter_start_date']) . "' AND '" . $this->db->escape($data['filter_end_date']) . "' ";
		} else if (is_null($data['filter_start_date']) && isset($data['filter_end_date']) && !is_null($data['filter_end_date'])) {
			$month =  date("m",strtotime($data['filter_end_date']));
			$year = date("Y",strtotime($data['filter_end_date']));
			$implode[] = " (month(date) = '". $month ."' AND year(date) = '". $year ."' )";
		} else if (isset($data['filter_start_date']) && !is_null($data['filter_start_date']) && is_null($data['filter_end_date'])) {
			$month =  date("m",strtotime($data['filter_start_date']));
			$year = date("Y",strtotime($data['filter_start_date']));
			$implode[] = " (month(date) = '". $month ."' AND year(date) = '". $year ."' )";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'name',
			'amount',
			'date'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY date";
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

	public function getTotalExpenses($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "profits ";

		$implode = array();

		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$implode[] = " name like '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (isset($data['filter_amount']) && !is_null($data['filter_amount'])) {
			$implode[] = " amount = '" . (float)$data['filter_amount'] . "'";
		}

		if (isset($data['filter_date']) && !is_null($data['filter_date'])) {
			$implode[] = " date LIKE '" . $this->db->escape($data['filter_date']) . "%'";
		}

		if (isset($data['filter_start_date']) && !is_null($data['filter_start_date']) && isset($data['filter_end_date']) && !is_null($data['filter_end_date'])) {
			$implode[] = " date BETWEEN '" . $this->db->escape($data['filter_start_date']) . "' AND '" . $this->db->escape($data['filter_end_date']) . "' ";
		} else if (is_null($data['filter_start_date']) && isset($data['filter_end_date']) && !is_null($data['filter_end_date'])) {
			$month =  date("m",strtotime($data['filter_end_date']));
			$year = date("Y",strtotime($data['filter_end_date']));
			$implode[] = " (month(date) = '". $month ."' AND year(date) = '". $year ."' )";
		} else if (isset($data['filter_start_date']) && !is_null($data['filter_start_date']) && is_null($data['filter_end_date'])) {
			$month =  date("m",strtotime($data['filter_start_date']));
			$year = date("Y",strtotime($data['filter_start_date']));
			$implode[] = " (month(date) = '". $month ."' AND year(date) = '". $year ."' )";
		}


		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function validateExpenses($email, $user_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM ".DB_PREFIX."user WHERE email = '".$email."' AND user_id<>'".$user_id."'");
		return $query->row['total'];
	}

	public function getDefaultBaseRates() {

		$sql = "SELECT * FROM " . DB_PREFIX . "default_wages";

		$query = $this->db->query($sql);
		return $query->row;
	}

	public function editBaseRates($data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "default_wages` SET " .
				" wage_usa = '" . $this->db->escape($data['wage_usa']) . 
				"', wage_canada = '" . $this->db->escape($data['wage_canada']) . 
				"', wage_alberta = '" . $this->db->escape($data['wage_alberta']) . 
				"', invoice_usa = '" . $this->db->escape($data['invoice_usa']) . 
				"', invoice_canada = '" . $this->db->escape($data['invoice_canada']) . 
				"', invoice_alberta = '" . $this->db->escape($data['invoice_alberta']) . 
				"' WHERE wageid = 1");
	}

public function getTutorBaseRates($data) {
		$sql = "SELECT tutors_to_students_id,tu.username as tutor_uname, su.username as student_uname,base_wage,base_invoice,ts.date_added FROM " . DB_PREFIX . "tutors_to_students ts, user as tu,user as su WHERE ts.tutors_id=tu.user_id AND ts.students_id=su.user_id";

		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function updateTutorBaseRates($data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "tutors_to_students` SET " .
				" base_wage = '" . $this->db->escape($data['base_wage']) . 
				"', base_invoice  = '" . $this->db->escape($data['base_invoice']) . 
				"' WHERE tutors_to_students_id  = '" . $this->db->escape($data['tutors_to_students_id'])."'");
	}
}