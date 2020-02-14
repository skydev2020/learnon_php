<?php
class ModelCmsSettings extends Model {
	public function addSetting($data) {
		$this->db->query("INSERT INTO ".DB_PREFIX."default_wages SET wage_usa = '".$this->db->escape($data['wage_usa'])."', wage_canada = '".$this->db->escape($data['wage_canada'])."', wage_alberta = '".$this->db->escape($data['wage_alberta'])."', invoice_usa = '".$this->db->escape($data['invoice_usa'])."', invoice_canada = '".$this->db->escape($data['invoice_canada'])."', invoice_alberta = '".$this->db->escape($data['invoice_alberta'])."' ");
		$wageid = $this->db->getLastId();	
		return $wageid; 
	}
	
	public function editSetting($wageid, $data) {
		$this->db->query("UPDATE ".DB_PREFIX."default_wages SET wage_usa = '".$this->db->escape($data['wage_usa'])."', wage_canada = '".$this->db->escape($data['wage_canada'])."', wage_alberta = '".$this->db->escape($data['wage_alberta'])."', invoice_usa = '".$this->db->escape($data['invoice_usa'])."', invoice_canada = '".$this->db->escape($data['invoice_canada'])."', invoice_alberta = '".$this->db->escape($data['invoice_alberta'])."' WHERE wageid = '".(int)$wageid."'");
	}
	
	public function getSetting() {
		$arrsetting = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "default_wages ");
		if(count($query->rows)>0){
			$arrsetting = $query->rows[0];
			return $arrsetting;
		}else{
		    return 0;
		}
		
	}
}
?>