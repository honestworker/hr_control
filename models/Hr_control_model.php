<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * hr control model
 */
class Hr_control_model extends App_Model {
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * check format date Y-m-d
	 * @param  [type] $date 
	 * @return boolean       
	 */
	public function check_format_date($date)
	{
		if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * get income tax rate
	 * @param  boolean $id 
	 * @return [type]      
	 */
	public function get_income_tax_rate($id = false)
	{
        if (is_numeric($id)) {
            return $this->db->get_where(db_prefix() . 'hr_income_tax_rates', ['id' => $id])->row();
        }
    	return $this->db->get(db_prefix(). 'hr_income_tax_rates')->result_array();
    }

	/**
	 * update income tax rates
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function update_income_tax_rates($data)
	{
		$affectedRows = 0;

		if (isset($data['incometax_rate_hs'])) {
			$incometax_rate_hs = $data['incometax_rate_hs'];
			unset($data['incometax_rate_hs']);
		}

		if (isset($incometax_rate_hs)) {
			$incometax_rate_detail = json_decode($incometax_rate_hs);

			$es_detail = [];
			$row = [];
			$rq_val = [];
			$header = [];

			$header[] = 'tax_bracket_value_from';
			$header[] = 'tax_bracket_value_to';
			$header[] = 'tax_rate';
			$header[] = 'equivalent_value';
			$header[] = 'effective_rate';
			$header[] = 'id';

			foreach ($incometax_rate_detail as $key => $value) {
				if ($value[2] != '') {
					$es_detail[] = array_combine($header, $value);
				}
			}
		}

		$row = [];
		$row['update'] = []; 
		$row['insert'] = []; 
		$row['delete'] = [];
		$total = [];

		foreach ($es_detail as $key => $value) {
			if ($value['id'] != '') {
				$row['delete'][] = $value['id'];
				$row['update'][] = $value;
			} else {
				unset($value['id']);
				$row['insert'][] = $value;
			}
		}

		if (empty($row['delete'])) {
			$row['delete'] = ['0'];
		}
		$row['delete'] = implode(",",$row['delete']);
		$this->db->where('id NOT IN ('.$row['delete'] .') ');
		$this->db->delete(db_prefix().'hr_income_tax_rates');
		if ($this->db->affected_rows() > 0) {
			$affectedRows++;
		}

		if (count($row['insert']) != 0) {
			$affected_rows = $this->db->insert_batch(db_prefix().'hr_income_tax_rates', $row['insert']);
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}
		if (count($row['update']) != 0) {
			$affected_rows = $this->db->update_batch(db_prefix().'hr_income_tax_rates', $row['update'], 'id');
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}

		if ($affectedRows > 0) {
			return true;
		}

		return false;
	}

	/**
	 * get income tax rebates
	 * @param  boolean $id 
	 * @return [type]      
	 */
	public function get_income_tax_rebates($id = false)
	{
        if (is_numeric($id)) {
            return $this->db->get_where(db_prefix() . 'hr_income_tax_rebates', ['id' => $id])->row();
        }
		return $this->db->get(db_prefix(). 'hr_income_tax_rebates')->result_array();
    }

	/**
	 * update income tax rebates
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function update_income_tax_rebates($data)
	{
		$affectedRows = 0;

		if (isset($data['incometax_rebates_hs'])) {
			$incometax_rebates_hs = $data['incometax_rebates_hs'];
			unset($data['incometax_rebates_hs']);
		}

		if (isset($incometax_rebates_hs)) {
			$incometax_rate_detail = json_decode($incometax_rebates_hs);

			$es_detail = [];
			$row = [];
			$rq_val = [];
			$header = [];

			$header[] = 'code';
			$header[] = 'description';
			$header[] = 'value';
			$header[] = 'total';
			$header[] = 'id';

			foreach ($incometax_rate_detail as $key => $value) {
				//only get row "value" != 0
				if ($value[2] != '') {
					$es_detail[] = array_combine($header, $value);
				}
			}
		}
		$row = [];
		$row['update'] = []; 
		$row['insert'] = []; 
		$row['delete'] = [];
		$total = [];

		foreach ($es_detail as $key => $value) {
			if ($value['id'] != '') {
				$row['delete'][] = $value['id'];
				$row['update'][] = $value;
			} else {
				unset($value['id']);
				$row['insert'][] = $value;
			}
		}

		if (empty($row['delete'])) {
			$row['delete'] = ['0'];
		}
		$row['delete'] = implode(",",$row['delete']);
		$this->db->where('id NOT IN ('.$row['delete'] .') ');
		$this->db->delete(db_prefix().'hr_income_tax_rebates');
		if ($this->db->affected_rows() > 0) {
			$affectedRows++;
		}

		if (count($row['insert']) != 0) {
			$affected_rows = $this->db->insert_batch(db_prefix().'hr_income_tax_rebates', $row['insert']);
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}
		if (count($row['update']) != 0) {
			$affected_rows = $this->db->update_batch(db_prefix().'hr_income_tax_rebates', $row['update'], 'id');
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}

		if ($affectedRows > 0) {
			return true;
		}

		return false;
	}

	/**
	 * get earnings list
	 * @param  boolean $id 
	 * @return [type]      
	 */
	public function get_earnings_list($id = false)
	{
        if (is_numeric($id)) {
            return $this->db->get_where(db_prefix() . 'hr_earnings_list', ['id' => $id])->row();
        }
        return $this->db->get(db_prefix() . 'hr_earnings_list')->result_array();
    }

    /**
     * update earnings list
     * @param  [type] $data 
     * @return [type]       
     */
    public function update_earnings_list($data)
	{
		$affectedRows = 0;

		if (isset($data['earnings_list_hs'])) {
			$earnings_list_hs = $data['earnings_list_hs'];
			unset($data['earnings_list_hs']);
		}

		if (isset($earnings_list_hs)) {
			$incometax_rate_detail = json_decode($earnings_list_hs);

			$es_detail = [];
			$row = [];
			$rq_val = [];
			$header = [];

			$header[] = 'code';
			$header[] = 'description';
			$header[] = 'short_name';
			$header[] = 'taxable';
			$header[] = 'basis_type';
			$header[] = 'id';

			foreach ($incometax_rate_detail as $key => $value) {
				//only get row "value" != 0
				if ($value[0] != '' ) {
					$es_detail[] = array_combine($header, $value);
				}
			}
		}
		$row = [];
		$row['update'] = []; 
		$row['insert'] = []; 
		$row['delete'] = [];
		$total = [];

		foreach ($es_detail as $key => $value) {
			if ($value['id'] != '') {
				$row['delete'][] = $value['id'];
				$row['update'][] = $value;
			} else {
				unset($value['id']);
				$row['insert'][] = $value;
			}
		}

		if (empty($row['delete'])) {
			$row['delete'] = ['0'];
		}
		$row['delete'] = implode(",",$row['delete']);
		$this->db->where('id NOT IN ('.$row['delete'] .') ');
		$this->db->delete(db_prefix().'hr_earnings_list');
		if ($this->db->affected_rows() > 0) {
			$affectedRows++;
		}

		if (count($row['insert']) != 0) {
			$affected_rows = $this->db->insert_batch(db_prefix().'hr_earnings_list', $row['insert']);
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}
		if (count($row['update']) != 0) {
			$affected_rows = $this->db->update_batch(db_prefix().'hr_earnings_list', $row['update'], 'id');
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}

		if ($affectedRows > 0) {
			return true;
		}

		return false;
	}

	/**
	 * get salary deductions list
	 * @param  boolean $id 
	 * @return [type]      
	 */
	public function get_salary_deductions_list($id = false)
	{
        if (is_numeric($id)) {
            return $this->db->get_where(db_prefix() . 'hr_salary_deductions_list', ['id' => $id])->row();
        }
		return $this->db->get(db_prefix() . 'hr_salary_deductions_list')->result_array();
    }

    /**
     * update earnings list
     * @param  [type] $data 
     * @return [type]       
     */
    public function update_salary_deductions_list($data)
	{
		$affectedRows = 0;

		if (isset($data['salary_deductions_list_hs'])) {
			$salary_deductions_list_hs = $data['salary_deductions_list_hs'];
			unset($data['salary_deductions_list_hs']);
		}

		if (isset($salary_deductions_list_hs)) {
			$incometax_rate_detail = json_decode($salary_deductions_list_hs);

			$es_detail = [];
			$row = [];
			$rq_val = [];
			$header = [];

			$header[] = 'code';
			$header[] = 'description';
			$header[] = 'rate';
			$header[] = 'basis';
			$header[] = 'id';

			foreach ($incometax_rate_detail as $key => $value) {
				//only get row "value" != 0
				if ($value[0] != ''  && $value[3] != '') {
					$es_detail[] = array_combine($header, $value);
				}
			}
		}
		$row = [];
		$row['update'] = []; 
		$row['insert'] = []; 
		$row['delete'] = [];
		$total = [];

		foreach ($es_detail as $key => $value) {
			if ($value['id'] != '') {
				$row['delete'][] = $value['id'];
				$row['update'][] = $value;
			} else {
				unset($value['id']);
				$row['insert'][] = $value;
			}
		}


		if (empty($row['delete'])) {
			$row['delete'] = ['0'];
		}
		$row['delete'] = implode(",",$row['delete']);
		$this->db->where('id NOT IN ('.$row['delete'] .') ');
		$this->db->delete(db_prefix().'hr_salary_deductions_list');
		if ($this->db->affected_rows() > 0) {
			$affectedRows++;
		}

		if (count($row['insert']) != 0) {
			$affected_rows = $this->db->insert_batch(db_prefix().'hr_salary_deductions_list', $row['insert']);
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}
		if (count($row['update']) != 0) {
			$affected_rows = $this->db->update_batch(db_prefix().'hr_salary_deductions_list', $row['update'], 'id');
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}

		if ($affectedRows > 0) {
			return true;
		}

		return false;
	}

	/**
	 * get insurance list
	 * @param  boolean $id 
	 * @return [type]      
	 */
	public function get_insurance_list($id = false)
	{
        if (is_numeric($id)) {
            return $this->db->get_where(db_prefix() . 'hr_insurance_list', ['id' => $id])->row();
        }
        return $this->db->get(db_prefix() . 'hr_insurance_list')->result_array();
    }

    /**
     * update insurance list
     * @param  [type] $data 
     * @return [type]       
     */
    public function update_insurance_list($data)
	{
		$affectedRows = 0;

		if (isset($data['insurance_list_hs'])) {
			$insurance_list_hs = $data['insurance_list_hs'];
			unset($data['insurance_list_hs']);
		}

		if (isset($insurance_list_hs)) {
			$incometax_rate_detail = json_decode($insurance_list_hs);

			$es_detail = [];
			$row = [];
			$rq_val = [];
			$header = [];

			$header[] = 'code';
			$header[] = 'description';
			$header[] = 'rate';
			$header[] = 'basis';
	
			$header[] = 'id';

			foreach ($incometax_rate_detail as $key => $value) {
				//only get row "value" != 0
				if ($value[0] != ''  && $value[3] != '') {
					$es_detail[] = array_combine($header, $value);
				}
			}
		}
		$row = [];
		$row['update'] = []; 
		$row['insert'] = []; 
		$row['delete'] = [];
		$total = [];

		foreach ($es_detail as $key => $value) {
			if ($value['id'] != '') {
				$row['delete'][] = $value['id'];
				$row['update'][] = $value;
			} else {
				unset($value['id']);
				$row['insert'][] = $value;
			}
		}

		if (empty($row['delete'])) {
			$row['delete'] = ['0'];
		}
		$row['delete'] = implode(",",$row['delete']);
		$this->db->where('id NOT IN ('.$row['delete'] .') ');
		$this->db->delete(db_prefix().'hr_insurance_list');
		if ($this->db->affected_rows() > 0) {
			$affectedRows++;
		}

		if (count($row['insert']) != 0) {
			$affected_rows = $this->db->insert_batch(db_prefix().'hr_insurance_list', $row['insert']);
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}
		if (count($row['update']) != 0) {
			$affected_rows = $this->db->update_batch(db_prefix().'hr_insurance_list', $row['update'], 'id');
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}

		if ($affectedRows > 0) {
			return true;
		}

		return false;
	}

	/**
	 * get company contributions list
	 * @param  boolean $id 
	 * @return [type]      
	 */
	public function get_company_contributions_list($id = false)
	{
        if (is_numeric($id)) {
            return $this->db->get_where(db_prefix() . 'hr_company_contributions_list', ['id' => $id])->row();
        }
        return $this->db->get(db_prefix() . 'hr_company_contributions_list')->result_array();
    }

    /**
     * update company contributions list
     * @param  [type] $data 
     * @return [type]       
     */
    public function update_company_contributions_list($data)
	{
		$affectedRows = 0;

		if (isset($data['company_contributions_list_hs'])) {
			$company_contributions_list_hs = $data['company_contributions_list_hs'];
			unset($data['company_contributions_list_hs']);
		}

		if (isset($company_contributions_list_hs)) {
			$incometax_rate_detail = json_decode($company_contributions_list_hs);

			$es_detail = [];
			$row = [];
			$rq_val = [];
			$header = [];

			$header[] = 'code';
			$header[] = 'description';
			$header[] = 'rate';
			$header[] = 'basis';
			$header[] = 'earn_inclusion';
			$header[] = 'earn_exclusion';
			$header[] = 'earnings_max';
			$header[] = 'id';

			foreach ($incometax_rate_detail as $key => $value) {
				//only get row "value" != 0
				if ($value[0] != ''  && $value[4] != '') {
					$es_detail[] = array_combine($header, $value);
				}
			}
		}
		$row = [];
		$row['update'] = []; 
		$row['insert'] = []; 
		$row['delete'] = [];
		$total = [];

		foreach ($es_detail as $key => $value) {
			if ($value['id'] != '') {
				$row['delete'][] = $value['id'];
				$row['update'][] = $value;
			} else {
				unset($value['id']);
				$row['insert'][] = $value;
			}
		}

		if (empty($row['delete'])) {
			$row['delete'] = ['0'];
		}
		$row['delete'] = implode(",",$row['delete']);
		$this->db->where('id NOT IN ('.$row['delete'] .') ');
		$this->db->delete(db_prefix().'hr_company_contributions_list');
		if ($this->db->affected_rows() > 0) {
			$affectedRows++;
		}

		if (count($row['insert']) != 0) {
			$affected_rows = $this->db->insert_batch(db_prefix().'hr_company_contributions_list', $row['insert']);
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}
		if (count($row['update']) != 0) {
			$affected_rows = $this->db->update_batch(db_prefix().'hr_company_contributions_list', $row['update'], 'id');
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}

		if ($affectedRows > 0) {
			return true;
		}

		return false;
	}

	/**
	 * update data integration
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function update_data_integration($data)
	{
		$affected_rows = 0;
		
		$data_integration = array(
			'option_val' => 0
		);
		$this->db->where('option_name', 'integrated_hrprofile');
		$this->db->or_where('option_name', 'integrated_timesheets');
		$this->db->or_where('option_name', 'integrated_commissions');
		$this->db->update(db_prefix().'hr_control_option', $data_integration); 
		if ($this->db->affected_rows() > 0) {
			$affected_rows++;
		}

		if (count($data) > 0) {
			foreach ($data as $key => $value) {					
				switch ($key) {
					case 'integration_actual_workday':
						$this->db->where('option_name', $key);
						$this->db->update(db_prefix().'hr_control_option', ['option_val' => implode(',', $value)]); 
						break;

					case 'integration_paid_leave':
						$this->db->where('option_name', $key);
						$this->db->update(db_prefix().'hr_control_option', ['option_val' => implode(',', $value)]); 
						break;

					case 'integration_unpaid_leave':
						$this->db->where('option_name', $key);
						$this->db->update(db_prefix().'hr_control_option', ['option_val' => implode(',', $value)]); 
						break;

					case 'standard_working_time':
						$this->db->where('option_name', $key);
						$this->db->update(db_prefix().'hr_control_option', ['option_val' => $value]); 
						break;
					
					default:
					$this->db->where('option_name', $value);
					$this->db->update(db_prefix().'hr_control_option', ['option_val' => 1]); 
						break;
				}

			    if ($this->db->affected_rows() > 0) {
			    	$affected_rows++;
			    }
			}
		}

		if ($affected_rows > 0) {
			return true;
		}
		return false;
	}

	/**
	 * delete hr control permission
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_hr_control_permission($id)
	{
		$str_permissions ='';
		foreach (list_hr_control_permisstion() as $per_key =>  $per_value) {
			if (strlen($str_permissions ?? '') > 0) {
				$str_permissions .= ",'".$per_value."'";
			} else {
				$str_permissions .= "'".$per_value."'";
			}
		}

		$sql_where = " feature IN (".$str_permissions.") ";

		$this->db->where('staff_id', $id);
		$this->db->where($sql_where);
		$this->db->delete(db_prefix() . 'staff_permissions');

		if ($this->db->affected_rows() > 0) {
			return true;
		}

		return false;
	}

	/**
	 * setting get attendance data
	 * @return [type] 
	 */
	public function setting_get_attendance_type()
	{
	    $actual_type=[];
	    $paid_leave_type=[];
	    $unpaid_leave_type=[];

	    $attendance_types = hr_attendance_type();
	    $actual_workday   = explode(',', get_hr_control_option('integration_actual_workday'));
	    $paid_leave       = explode(',', get_hr_control_option('integration_paid_leave'));
	    $unpaid_leave     = explode(',', get_hr_control_option('integration_unpaid_leave'));

	    foreach ($attendance_types as $key => $value) {
	    	//actual_workday
	        if (!in_array($key, $paid_leave) && !in_array($key, $unpaid_leave)) {
	        	$actual_type[$key] = $value;
	        }

	        //paid_leave
	        if (!in_array($key, $actual_workday) && !in_array($key, $unpaid_leave)) {
	        	$paid_leave_type[$key] = $value;
	        }

	        //unpaid_leave
	        if (!in_array($key, $actual_workday) && !in_array($key, $paid_leave)) {
	        	$unpaid_leave_type[$key] = $value;
	        }
	    }

	    $results=[];
	    $results['actual_workday'] 	= $actual_type;
	    $results['paid_leave'] 		= $paid_leave_type;
	    $results['unpaid_leave'] 	= $unpaid_leave_type;

	    return $results;
	}

	/**
	 * get timesheet type for setting
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function get_timesheet_type_for_setting($data)
	{
		$actual_type ='';
	    $paid_leave_type ='';
	    $unpaid_leave_type ='';

	    $attendance_types = hr_attendance_type();
	    $actual_workday   = isset($data['actual_workday']) ? $data['actual_workday'] : [];
	    $paid_leave       = isset($data['paid_leave']) ? $data['paid_leave'] : [];
	    $unpaid_leave     = isset($data['unpaid_leave']) ? $data['unpaid_leave'] : [];

	    foreach ($attendance_types as $key => $value) {
	    	//actual_workday
	        if (!in_array($key, $paid_leave) && !in_array($key, $unpaid_leave)) {
				$select ='';
				if (in_array($key, $actual_workday)) {
					$select = ' selected';
				}	        	
				$actual_type .= '<option value="' . $key . '" '.$select.'>' . $value . '</option>';
	        }

	        //paid_leave
	        if (!in_array($key, $actual_workday) && !in_array($key, $unpaid_leave)) {
	        	$select ='';
				if (in_array($key, $paid_leave)) {
					$select = ' selected';
				}	        	
				$paid_leave_type .= '<option value="' . $key . '" '.$select.'>' . $value . '</option>';
	        }

	        //unpaid_leave
	        if (!in_array($key, $actual_workday) && !in_array($key, $paid_leave)) {
	        	$select ='';
				if (in_array($key, $unpaid_leave)) {
					$select = ' selected';
				}	        	
				$unpaid_leave_type .= '<option value="' . $key . '" '.$select.'>' . $value . '</option>';
	        }

	    }

	    $results=[];
	    $results['actual_workday'] 	= $actual_type;
	    $results['paid_leave'] 		= $paid_leave_type;
	    $results['unpaid_leave'] 	= $unpaid_leave_type;
	    return $results;
	}

	/**
	 * hr records get earnings list
	 * @param  boolean $id 
	 * @return [type]
	 * get data: salary type, allowance type from HR records module when use feature "data integration" in settings menu.      
	 */
	public function hr_records_get_earnings_list($id = false) {
        if (is_numeric($id)) {
            return $this->db->get_where(db_prefix() . 'hr_earnings_list_hr_records', ['id' => $id])->row();
        }
        return $this->db->get(db_prefix() . 'hr_earnings_list_hr_records')->result_array();
    }

    /**
     * hr records update earnings list
     * @param  [type] $data 
     * @return [type]       
     */
    public function earnings_list_synchronization($data)
	{
		$affectedRows = 0;
		$hr_control_alphabeticala = hr_control_alphabeticala();
		$array_salary_allowance=[];
		if (hr_control_get_status_modules('hr_profile') && get_hr_control_option('integrated_hrprofile') == 1) {
			$this->load->model('hr_profile/hr_profile_model');
			//get salary type
			$salary_types = $this->hr_profile_model->get_salary_form();
			//get allowance type
			$allowance_types = $this->hr_profile_model->get_allowance_type();

			foreach ($salary_types as $key=>  $value) {
				$code = str_replace("-", "", strtoupper($value['form_name']));
				$code = str_replace(" ", "_", strtoupper($value['form_name']));

			    $array_salary_allowance['salary_'.$value['form_id']] = [
			    	'code' 			=> $code,
			    	'description' 	=> $value['form_name'],
			    	'short_name' 	=> $value['form_name'],
			    	'taxable' 		=> 0,
			    	'basis_type' 	=> 'monthly',
			    	'rel_type' 		=> 'salary',
			    	'rel_id' 		=> $value['form_id']
			    ];
			}

			foreach ($allowance_types as $key=>  $value) {
				$code = str_replace("-", "", strtoupper($value['type_name']));
				$code = str_replace(" ", "_", strtoupper($value['type_name']));
			    $array_salary_allowance['allowance_'.$value['type_id']] = [
			    	'code' 			=> $code,
			    	'description' 	=> $value['type_name'],
			    	'short_name' 	=> $value['type_name'],
			    	'taxable' 		=> 0,
			    	'basis_type' 	=> 'monthly',
			    	'rel_type' 		=> 'allowance',
			    	'rel_id' 		=> $value['type_id']
			    ];
			}
		}

		if (isset($data['earnings_list_hr_records_hs'])) {
			$earnings_list_hr_records_hs = $data['earnings_list_hr_records_hs'];
			unset($data['earnings_list_hr_records_hs']);
		}

		if (isset($earnings_list_hr_records_hs)) {
			$incometax_rate_detail = json_decode($earnings_list_hr_records_hs);

			$es_detail = [];
			$row = [];
			$rq_val = [];
			$header = [];

			$header[] = 'code';
			$header[] = 'description';
			$header[] = 'short_name';
			$header[] = 'taxable';
			$header[] = 'basis_type';
			$header[] = 'id';
			$header[] = 'rel_type';
			$header[] = 'rel_id';

			foreach ($incometax_rate_detail as $key => $value) {
				//only get row "value" != 0
				if ($value[0] != '' ) {
					$es_detail[] = array_combine($header, $value);
				}
			}
		}

		$row = [];
		$row['update'] = []; 
		$row['insert'] = []; 
		$row['delete'] = [];
		$total = [];
		foreach ($es_detail as $key => $value) {
			if ($value['id'] != '') {
				$row['delete'][] = $value['id'];

				if (isset($array_salary_allowance[$value['rel_type'].'_'.$value['rel_id']])) {
					$value['description'] = $array_salary_allowance[$value['rel_type'].'_'.$value['rel_id']]['description'];
					$row['update'][] = $value;

					unset($array_salary_allowance[$value['rel_type'].'_'.$value['rel_id']]);

					if (isset($hr_control_alphabeticala[$value['code']])) {
						unset($hr_control_alphabeticala[$value['code']]);
					}
				}
			}
		}
		foreach ($array_salary_allowance as $value) {
			$value['code'] = reset($hr_control_alphabeticala);
		    $row['insert'][] =  $value;

		    unset($hr_control_alphabeticala[$value['code']]);
		}

		if (empty($row['delete'])) {
			$row['delete'] = ['0'];
		}
		$row['delete'] = implode(",",$row['delete']);
		$this->db->where('id NOT IN ('.$row['delete'] .') ');
		$this->db->delete(db_prefix().'hr_earnings_list_hr_records');
		if ($this->db->affected_rows() > 0) {
			$affectedRows++;
		}

		if (count($row['insert']) != 0) {
			$affected_rows = $this->db->insert_batch(db_prefix().'hr_earnings_list_hr_records', $row['insert']);
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}
		if (count($row['update']) != 0) {
			$affected_rows = $this->db->update_batch(db_prefix().'hr_earnings_list_hr_records', $row['update'], 'id');
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}

		if ($affectedRows > 0) {
			return true;
		}

		return false;
	}

	/**
	 * get hrp employees header
	 * @param  [type] $rel_type 
	 * @return [type]           
	 */
	public function get_hr_employees_header($rel_type)
	{
		$this->db->where('rel_type', $rel_type);
		$this->db->order_by('header_oder', 'asc');
		return $this->db->get(db_prefix() . 'hr_employees_header')->result_array();
	}

	/**
	 * get hrp employees value
	 * @param  [type] $rel_type 
	 * @return [type]           
	 */
	public function get_hr_employees_value($rel_type)
	{
		$this->db->where('rel_type', $rel_type);
		$this->db->order_by('header_id', 'asc');
		return $this->db->get(db_prefix() . 'hr_employees_value')->result_array();
	}

	/**
	 * get employees data
	 * @return [type] 
	 */
	public function get_employees_data1()
	{
		              
		if (hr_control_get_status_modules('hr_profile') && get_hr_control_option('integrated_hrprofile') == 1) {
			$rel_type = 'hr_records';
		} else {
			$rel_type = 'none';
		}
		$header_id_code=[];
		$header_code=[];
		$header_column=[];
		$header_value=[];
		$body_value=[];
		//get header
		$employees_header = $this->get_hr_employees_header($rel_type);
		foreach ($employees_header as $value) {
			array_push($header_column, [
				'data' => $value['header_code'],
				'type' => 'text'
			]);
			$header_code[] = $value['header_code'];
			$header_id_code[$value['header_code']] = $value['id'];

			$header_value[]=_l($value['header_value']);
		}

		array_push($header_column, [
			'data' => 'staff_id',
			'type' => 'text'
		]);

		$header_code[] = 'staff_id';

		$header_value[]='staff_id';

		//get body
		$sql_query = "SELECT  staff_id, header_code, value, v.rel_type, v.header_id  FROM ".db_prefix()."hr_employees_value as v left join " . db_prefix() . "hr_employees_header as h on h.id = v.header_id where v.rel_type = '" . $rel_type . "' order by staff_id, header_oder";
		$employees_value = $this->db->query($sql_query)->result_array();
		$body_temp=[];
		foreach ($employees_value as $key => $value) {
			if ($key+1 < count($employees_value)) {
				if ($value['staff_id'] != $employees_value[$key+1]['staff_id']) {
					$body_temp[$value['header_code']] = $value['value'] ;
					$body_temp['staff_id'] = $value['staff_id'] ;
					array_push($body_value, $body_temp);

					$body_temp=[];
				} else {
					$body_temp[$value['header_code']] = $value['value'] ;
				}
			} else {
				$body_temp[$value['header_code']] = $value['value'] ;
				$body_temp['staff_id'] = $value['staff_id'] ;
				array_push($body_value, $body_temp);
			}
		}

		$employees_data=[];
		$employees_data['header_id_code'] 	= $header_id_code;
		$employees_data['header_column'] 	= $header_column;
		$employees_data['header_code'] 		= $header_code;
		$employees_data['header_value'] 	= $header_value;
		$employees_data['body_value'] 		= $body_value;

		return $employees_data;
	}

	/**
	 * get employees data
	 * @param  [type] $month    
	 * @param  [type] $rel_type 
	 * @return [type]           
	 */
	public function get_employees_data($month, $rel_type ='', $where='')
	{
		if ($rel_type == '') {
			$rel_type = hr_get_profile_status();
		}

		if ($where != '') {
			$this->db->where($where);
		}
		$this->db->where('rel_type', $rel_type);
		$this->db->where("date_format(month, '%Y-%m-%d') = '".$month."'");
		$this->db->order_by('staff_id', 'asc');
		$employees = $this->db->get(db_prefix() . 'hr_employees_value')->result_array();

		$employees_decode = $this->employees_data_json_encode_decode('decode', $employees, '');

		return $employees_decode;
	}

	/**
	 * employees data json encode decode
	 * @param  [type] $type   
	 * @param  [type] $data   
	 * @param  [type] $header 
	 * @return [type]         
	 */
	public function employees_data_json_encode_decode($type, $data, $header ='')
	{
		if ($type == 'decode') {
			// json decode
			foreach ($data as $key => $value) {
			    $probationary_contracts = json_decode($value['probationary_contracts']);
			    $primary_contracts = json_decode($value['primary_contracts']);

			    unset($data[$key]['probationary_contracts']);
			    unset($data[$key]['primary_contracts']);

			    foreach ($probationary_contracts as $probationary_key => $probationary_value) {
			    	foreach (get_object_vars($probationary_value) as $column_key => $column_value) {
			    		$data[$key][$column_key] = $column_value;

			    		$data[$key]['contract_value'][$column_key] = $column_value;
			    	}
			    }

			    foreach ($primary_contracts as $primary_key => $primary_value) {
			        foreach (get_object_vars($primary_value) as $column_key => $column_value) {
			    		$data[$key][$column_key] = $column_value;

			    		$data[$key]['contract_value'][$column_key] = $column_value;
			    	}
			    }			    
			}
		} else {
			// json encode
			$data_detail=[];
			$data_detail = $data;

			$data=[];
			$rel_type = hr_get_profile_status();


			foreach ($data_detail as $data_detail_value) {
				$temp = [];
				$probationary_contracts = [];
				$primary_contracts = [];

				foreach ($data_detail_value as $key => $value) {
					if ($rel_type == 'hr_records') {
						//integration Hr records module
						if (preg_match('/^st1_/', $key) || preg_match('/^al1_/', $key)) {
							array_push($probationary_contracts, [
								$key => $value
							]);
						} else if (preg_match('/^st2_/', $key) || preg_match('/^al2_/', $key)) {
							array_push($primary_contracts, [
								$key => $value
							]);
						} else if ($key != 'department_name' && $key != 'employee_name' && $key != 'employee_number'  ) {
							if (($key == 'probationary_effective' || $key == 'probationary_expiration' || $key == 'primary_effective' || $key == 'primary_expiration')  ) {
								if ($value != '') {
									$temp[$key] = $value;
								} else {
									$temp[$key] = null;
								}
							} else {
								$temp[$key] = $value;
							}
						}
					} else {
						//none integration Hr records module
						//earning1_ (of propational contract)
						//earning2_ (of formal contract)
						if (preg_match('/^earning1_/', $key) ) {
							array_push($probationary_contracts, [
								$key => $value
							]);
						} else if (preg_match('/^earning2_/', $key) || preg_match('/^al2_/', $key)) {
							array_push($primary_contracts, [
								$key => $value
							]);
						} else if ($key != 'department_name' && $key != 'employee_name' && $key != 'employee_number') {
							if (($key == 'probationary_effective' || $key == 'probationary_expiration' || $key == 'primary_effective' || $key == 'primary_expiration')  ) {
								if ($value != '') {
									$temp[$key] = $value;
								} else {
									$temp[$key] = null;
								}
							} else {
								$temp[$key] = $value;
							}
						}
					}
				}

				$temp['probationary_contracts'] = json_encode($probationary_contracts);
				$temp['primary_contracts'] = json_encode($primary_contracts);

				$data[] = $temp;
			}

		}
		return $data;
	}

	/**
	 * get format employees data
	 * @param  [type] $rel_type 
	 * @return [type]   
	 * Description: Each staff will have a maximun 2 Contract: probationary, formal
	 */
	public function get_format_employees_data($rel_type)
	{	
		$staff_information=[];
		$staff_information[] = 'id';
		$staff_information[] = 'staff_id';
		$staff_information[] = 'rel_type';
		$staff_information[] = 'employee_number';
		$staff_information[] = 'employee_name';
		$staff_information[] = 'job_title';
		$staff_information[] = 'department_name';
		$staff_information[] = 'income_tax_number';
		$staff_information[] = 'residential_address';
		$staff_information[] = 'income_rebate_code';
		$staff_information[] = 'income_tax_rate';
		$staff_information[] = 'bank_name';
		$staff_information[] = 'account_number';

		//get column header name, column format
		$column_format=[];
		$staff_information_header=[];

		foreach ($staff_information as $value) {
			if ($value == 'staff_id') {
				$staff_information_header[] = 'staff_id';
			} else if ($value == 'id') {
				$staff_information_header[] = 'id';
			} else {
				$staff_information_header[] = _l($value);
			}
		    array_push($column_format, [
		    	'data' => $value,
		    	'type' => 'text'
		    ]);
		}		

		//get value for probationary contract, formal contract
		$prefix_probationary = HR_CONTROL_PREFIX_PROBATIONARY;
		$prefix_formal = HR_CONTROL_PREFIX_FORMAL;
		$array_earnings_list_probationary = [];
		$array_earnings_list_formal = [];

		$array_earnings_list_probationary_header = [];
		$array_earnings_list_formal_header = [];

	    if ($rel_type == 'hr_records') {
	    	//get earning list from setting
			$hr_records_earnings_list = $this->hr_records_get_earnings_list();
			foreach ($hr_records_earnings_list as $key => $value) {
				$name ='';
				
				switch ($value['rel_type']) {
					case 'salary':
						$probationary_code = 'st1_'.$value['rel_id'];
						$formal_code = 'st2_'.$value['rel_id'];
						break;

					case 'allowance':
						$probationary_code = 'al1_'.$value['rel_id'];
						$formal_code  = 'al2_'.$value['rel_id'];
						break;
					
					default:
						# code...
						break;
				}

				if ($value['short_name'] != '') {
					$name .= $value['short_name'];
				} else if ($value['description'] != '') {
					$name .= $value['description'];
				} else if ($value['code'] != '') {
					$name .= $value['code'];
				} else if ($value['id'] != '') {
					$name .= $value['id'];
				}

				$array_earnings_list_probationary[$probationary_code] = 0;
				$array_earnings_list_formal[$formal_code] = 0;

				$array_earnings_list_probationary_header[] = $name.$prefix_probationary;
				$array_earnings_list_formal_header[] = $name.$prefix_formal;
			}
	    } else {
	    	$earnings_list = $this->get_earnings_list();

			foreach ($earnings_list as $key => $value) {
				$name ='';

				$array_earnings_list_probationary['earning1_'.$value['id']] = 0;
				$array_earnings_list_formal['earning2_'.$value['id']] = 0;

				$array_earnings_list_probationary_header[] = $value['short_name'].$prefix_probationary;
				$array_earnings_list_formal_header[] = $value['short_name'].$prefix_formal;
			}
	    }

		//probationary_effective
		//probationary_expiration
		//primary_effective
		//primary_expiration
		$probationary_date=[];
		$primary_date=[];

		$probationary_key =[];
		$primary_key =[];

		$probationary_date[] = _l('probationary_effective');
		$probationary_date[] = _l('probationary_expiration');
		$primary_date[] = _l('primary_effective');
		$primary_date[] = _l('primary_expiration');

		$probationary_key[] = 'probationary_effective';
		$probationary_key[] = 'probationary_expiration';
		$primary_key[] = 'primary_effective';
		$primary_key[] = 'primary_expiration';

	    //get column format for probationary, formal
	    foreach ($array_earnings_list_probationary as $key => $value) {
	    	array_push($column_format, [
	    		'data' => $key,
	    		'type'=> 'numeric',
	    		'numericFormat'=> [
	    			'pattern' => '0,00',
	    		]
	    	]);
	    }

	    array_push($column_format, [
	    	'data' => 'probationary_effective',
	    	'type'=> 'date',
	    	'correctFormat'=> 'true',
	    	'dateFormat'=> 'YYYY-MM-DD'
	    ]);
	    array_push($column_format, [
	    	'data' => 'probationary_expiration',
	    	'type'=> 'date',
	    	'correctFormat'=> 'true',
	    	'dateFormat'=> 'YYYY-MM-DD'
	    ]);	    

	    foreach ($array_earnings_list_formal as $key => $value) {
	    	array_push($column_format, [
	    		'data' => $key,
	    		'type'=> 'numeric',
	    		'numericFormat'=> [
	    			'pattern' => '0,00',
	    		]
	    	]);
	    }

	    array_push($column_format, [
	    	'data' => 'primary_effective',
	    	'type'=> 'date',
	    	'correctFormat'=> 'true',
	    	'dateFormat'=> 'YYYY-MM-DD',
	    ]);
	    array_push($column_format, [
	    	'data' => 'primary_expiration',
	    	'type'=> 'date',
	    	'correctFormat'=> 'true',
	    	'dateFormat'=> 'YYYY-MM-DD',
	    ]);
	    

	    $results=[];
	    $results['probationary']=$array_earnings_list_probationary;
	    $results['formal']		=$array_earnings_list_formal;
	    $results['staff_information']		= $staff_information;
	    $results['probationary_key']		= $probationary_key;
	    $results['primary_key']				= $primary_key;

	    $results['header']		= array_merge($staff_information_header, $array_earnings_list_probationary_header, $probationary_date, $array_earnings_list_formal_header, $primary_date);
	    $results['column_format']		= $column_format;
	    return $results;
	}

	/**
	 * FunctionNam
	 * @param [type]  $prefix_str          
	 * @param [type]  $number              
	 * @param integer $number_of_characters
	 */
	public function hr_format_code($prefix_str, $number, $number_of_characters = 5)
	{
		$str_result = $prefix_str.str_pad($number,$number_of_characters,'0',STR_PAD_LEFT);
		return $str_result;
	}

	/**
	 * get list staff contract
	 * @return [type]
	 * get staff contract, detail by staff all, Each employee will take the last 2 contracts from the search month (if the employee has 2 contracts in 1 month) and status = valid  
	 */
	public function get_list_staff_contract($month)
	{
		$month = date("Y-m", strtotime($month ?? ''));
		$sql_temp = "select * FROM ".db_prefix()."hr_staff_contract left join ".db_prefix()."hr_staff_contract_detail on ".db_prefix()."hr_staff_contract_detail.staff_contract_id = ".db_prefix()."hr_staff_contract.id_contract where ".db_prefix()."hr_staff_contract.id_contract IN (SELECT id_contract FROM ".db_prefix()."hr_staff_contract where ".db_prefix()."hr_staff_contract.contract_status = 'valid' AND date_format(start_valid, '%Y-%m-%d') >= '".$month."')";

		$sql = "SELECT *  FROM ".db_prefix()."hr_staff_contract as ct left join ".db_prefix()."hr_staff_contract_detail on ".db_prefix()."hr_staff_contract_detail.staff_contract_id = ct.id_contract
			where (select count(*) from ".db_prefix()."hr_staff_contract as f where f.staff = ct.staff and f.start_valid >= ct.start_valid) <= 2
			AND date_format(ct.start_valid, '%Y-%m') <= '".$month."' AND if (ct.end_valid is NULL, 1=1, date_format(ct.end_valid, '%Y-%m') >= '".$month."')
			AND ct.contract_status ='valid' order by staff,start_valid  desc";

		$staff_contracts = $this->db->query($sql)->result_array();

		$contracts=[];

		$check_contract_detail=[];
		$contract_detail=[];
		foreach ($staff_contracts as $key => $value) {
			if (count($check_contract_detail) == 0) {
				$check_contract_detail['id_contract']=$value['id_contract'];
				$check_contract_detail['staff_id']=$value['staff'];
			}

			$contract_detail[$value['rel_type']] = $value['rel_value'];
			$contract_detail['start_valid'] = $value['start_valid'];
			$contract_detail['end_valid'] = $value['end_valid'];

			if (count($staff_contracts) != $key+1) {
				if ($check_contract_detail['id_contract'] != $staff_contracts[$key+1]['id_contract'] || $check_contract_detail['staff_id'] != $staff_contracts[$key+1]['staff'] ) {
					//formal
					if (!isset($contracts[$check_contract_detail['staff_id']]['formal'])) {
						$contract_detail_temp=[];

						foreach ($contract_detail as $contract_detail_key => $contract_detail_value) {
							if ($contract_detail_key == 'start_valid') {
							    $contract_detail_temp['primary_effective'] = $contract_detail_value;
							} else if ($contract_detail_key == 'end_valid') {
							    $contract_detail_temp['primary_expiration'] = $contract_detail_value;
							} else {
							    $contract_detail_key = str_replace('_', '2_', $contract_detail_key);
							    $contract_detail_temp[$contract_detail_key] = $contract_detail_value;
							}
						}

						$contract_detail_temp['hourly_or_month'] = $value['hourly_or_month'];
						$contracts[$check_contract_detail['staff_id']]['formal'] = $contract_detail_temp;
					} else if (!isset($contracts[$check_contract_detail['staff_id']]['probationary'])) {
						//probationary	
						$contract_detail_temp=[];
						foreach ($contract_detail as $contract_detail_key => $contract_detail_value) {
							if ($contract_detail_key == 'start_valid') {
							    $contract_detail_temp['probationary_effective'] = $contract_detail_value;
							} else if ($contract_detail_key == 'end_valid') {
							    $contract_detail_temp['probationary_expiration'] = $contract_detail_value;
							} else {
							    $contract_detail_key = str_replace('_', '1_', $contract_detail_key);
							    $contract_detail_temp[$contract_detail_key] = $contract_detail_value;
							}
						}
						$contract_detail_temp['hourly_or_month'] = $value['hourly_or_month'];
						$contracts[$check_contract_detail['staff_id']]['probationary'] = $contract_detail_temp;
					}
					
					$contract_detail=[];
					$check_contract_detail=[];
				}
			} else {
				if (!isset($contracts[$check_contract_detail['staff_id']]['formal'])) {
				// formal	
					$contract_detail_temp=[];
					foreach ($contract_detail as $contract_detail_key => $contract_detail_value) {
						if ($contract_detail_key == 'start_valid') {
							    $contract_detail_temp['primary_effective'] = $contract_detail_value;
							} else if ($contract_detail_key == 'end_valid') {
							    $contract_detail_temp['primary_expiration'] = $contract_detail_value;
							} else {
								$contract_detail_key = str_replace('_', '2_', $contract_detail_key);
								$contract_detail_temp[$contract_detail_key] = $contract_detail_value;
							}
					}
					$contract_detail_temp['hourly_or_month'] = $value['hourly_or_month'];
					$contracts[$check_contract_detail['staff_id']]['formal'] = $contract_detail_temp;
				} else if (!isset($contracts[$check_contract_detail['staff_id']]['probationary'])) {
				// probationary	
					$contract_detail_temp=[];
					foreach ($contract_detail as $contract_detail_key => $contract_detail_value) {
						if ($contract_detail_key == 'start_valid') {
							    $contract_detail_temp['probationary_effective'] = $contract_detail_value;
							} else if ($contract_detail_key == 'end_valid') {
							    $contract_detail_temp['probationary_expiration'] = $contract_detail_value;
							} else {
							$contract_detail_key = str_replace('_', '1_', $contract_detail_key);
							$contract_detail_temp[$contract_detail_key] = $contract_detail_value;
						}
					}
					$contract_detail_temp['hourly_or_month'] = $value['hourly_or_month'];
					$contracts[$check_contract_detail['staff_id']]['probationary'] = $contract_detail_temp;
				}
			}
		}

		return $contracts;
	}

	/* Function to synchronize data from HR records module (information related to the salary of employees in the contract) */
	/* add columns, delete columns when settings change,
	TH1: Synchronize for the first time when there is no data
	TH2: Synchronize after having data: maybe some master data is changed (add, delete, edit)
	*/
	/**
	 * employees synchronization
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function employees_synchronization($data)
	{
		$affectedRows = 0;

		$rel_type = hr_get_profile_status();
		$format_employees_data = $this->get_format_employees_data($rel_type);
		$staff_information_key = $format_employees_data['staff_information'];
		$staff_probationary_key = array_keys($format_employees_data['probationary']);
		$probationary_key = $format_employees_data['probationary_key'];
		$primary_key = $format_employees_data['primary_key'];

		$staff_formal_key = array_keys($format_employees_data['formal']);

		$employees_month = date('Y-m-d',strtotime($data['employees_fill_month'].'-01' ?? ''));
		$staff_contract = $this->get_list_staff_contract($employees_month);
		if (isset($data['hr_employees_value'])) {
			$hr_employees_value = $data['hr_employees_value'];
			unset($data['hr_employees_value']);
		}

		/*update save note*/
		if (isset($hr_employees_value)) {
			$hr_employees_detail = json_decode($hr_employees_value);

			$es_detail = [];
			$row = [];
			$header = array_merge($staff_information_key, $staff_probationary_key, $probationary_key, $staff_formal_key, $primary_key);

			foreach ($hr_employees_detail as $key => $value) {				
				$temp = [];
				$probationary_contracts = [];
				$primary_contracts = [];
				$combine_data = [];
				$combine_data = array_combine($header, $value);

				//st1: is Salary type (of propational contract)
				//al1: is Allowance  type (of propational contract)
				//ts2: is Salary type (of formal contract)
				//al2: is Allowance  type (of formal contract)
				foreach ($combine_data as $combine_key => $combine_value) {
					if ($rel_type == 'hr_records') {
						//integration Hr records module
						if (preg_match('/^st1_/', $combine_key) || preg_match('/^al1_/', $combine_key)) {

							//get value from staff contract if exist
							if (isset($staff_contract[$combine_data['staff_id']]['probationary'][$combine_key])) {
								$combine_value = $staff_contract[$combine_data['staff_id']]['probationary'][$combine_key];
							}

							array_push($probationary_contracts, [
								$combine_key => $combine_value
							]);
						} else if (preg_match('/^st2_/', $combine_key) || preg_match('/^al2_/', $combine_key)) {
							//get value from staff contract if exist
							if (isset($staff_contract[$combine_data['staff_id']]['formal'][$combine_key])) {
								$combine_value = $staff_contract[$combine_data['staff_id']]['formal'][$combine_key];
							}

							array_push($primary_contracts, [
								$combine_key => $combine_value
							]);
						} else if ($combine_key == 'probationary_effective' ||$combine_key == 'probationary_expiration') {
							if (isset($staff_contract[$combine_data['staff_id']]['probationary'][$combine_key])) {
								$combine_value = $staff_contract[$combine_data['staff_id']]['probationary'][$combine_key];
							}

							$temp[$combine_key] = $combine_value;
						} else if ($combine_key == 'primary_effective' ||$combine_key == 'primary_expiration' ) {
							if (isset($staff_contract[$combine_data['staff_id']]['formal'][$combine_key])) {
								$combine_value = $staff_contract[$combine_data['staff_id']]['formal'][$combine_key];
							}

							$temp[$combine_key] = $combine_value;
						} else if ($combine_key != 'department_name' && $combine_key != 'employee_name' && $combine_key != 'employee_number'  ) {
							$temp[$combine_key] = $combine_value;
						}
					}
				}

				$temp['probationary_contracts'] = json_encode($probationary_contracts);
				$temp['primary_contracts'] = json_encode($primary_contracts);
				$temp['month'] = $employees_month;
				$es_detail[] = $temp;
			}
		}

		$row = [];
		$row['update'] = []; 
		$row['insert'] = []; 
		$row['delete'] = [];
		$total = [];

		$total['total_amount'] = 0;

		foreach ($es_detail as $key => $value) {
			if ($value['id'] != 0) {
				$row['delete'][] = $value['id'];
				$row['update'][] = $value;
			} else {
				unset($value['id']);
				$row['insert'][] = $value;
			}

		}

		if (empty($row['delete'])) {
			$row['delete'] = ['0'];
		}

		if ($data['department_employees_filter'] == '' && $data['staff_employees_filter'] == '' && $data['role_employees_filter'] == '') {
			$row['delete'] = implode(",",$row['delete']);
			$this->db->where('id NOT IN ('.$row['delete'] .') and rel_type = "'.$rel_type.'" AND date_format(month,"%Y-%m-%d") = "'.$employees_month.'"');
			$this->db->delete(db_prefix().'hr_employees_value');
			if ($this->db->affected_rows() > 0) {
				$affectedRows++;
			}
		}

		if (count($row['insert']) != 0) {
			$affected_rows = $this->db->insert_batch(db_prefix().'hr_employees_value', $row['insert']);
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}
		if (count($row['update']) != 0) {
			$affected_rows = $this->db->update_batch(db_prefix().'hr_employees_value', $row['update'], 'id');
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}

		if ($affectedRows > 0) {
			return true;
		}
		return false;
	}

	/*TO DO: XỬ LÝ CẬP NHẬT, cập nhật thông tin nhân viên sau khi người dùng thực hiện thay đổi trên handsome table, dùng cho 2 trường hợp: có tích hợp HR records module, không tích hợp Hr records module.  đang xử lý!!!*/
	/**
	 * 
	 * employees update
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function employees_update($data)
	{
		$affectedRows = 0;

		$rel_type = hr_get_profile_status();
		$format_employees_data = $this->get_format_employees_data($rel_type);
		$staff_information_key = $format_employees_data['staff_information'];
		$probationary_key = $format_employees_data['probationary_key'];
		$primary_key = $format_employees_data['primary_key'];

		$staff_probationary_key = array_keys($format_employees_data['probationary']);
		$staff_formal_key = array_keys($format_employees_data['formal']);

		$employees_month = date('Y-m-d',strtotime($data['employees_fill_month'].'-01' ?? ''));

		if (isset($data['hr_employees_value'])) {
			$hr_employees_value = $data['hr_employees_value'];
			unset($data['hr_employees_value']);
		}

		/*update save note*/

		if (isset($hr_employees_value)) {
			$hr_employees_detail = json_decode($hr_employees_value);

			$es_detail = [];
			$row = [];
			$header = array_merge($staff_information_key, $staff_probationary_key, $probationary_key, $staff_formal_key, $primary_key);
			foreach ($hr_employees_detail as $key => $value) {				
				$temp = [];
				$probationary_contracts = [];
				$primary_contracts = [];
				$combine_data = [];
				
				$combine_data = array_combine($header, $value);

				//st1: is Salary type (of propational contract)
				//al1: is Allowance  type (of propational contract)
				//ts2: is Salary type (of formal contract)
				//al2: is Allowance  type (of formal contract)
				foreach ($combine_data as $combine_key => $combine_value) {

					if ($rel_type == 'hr_records') {
						//integration Hr records module
						if (preg_match('/^st1_/', $combine_key) || preg_match('/^al1_/', $combine_key)) {
							array_push($probationary_contracts, [
								$combine_key => $combine_value
							]);
						} else if (preg_match('/^st2_/', $combine_key) || preg_match('/^al2_/', $combine_key)) {
							array_push($primary_contracts, [
								$combine_key => $combine_value
							]);
						} else if ($combine_key != 'department_name' && $combine_key != 'employee_name' && $combine_key != 'employee_number'  ) {
							if (($combine_key == 'probationary_effective' || $combine_key == 'probationary_expiration' || $combine_key == 'primary_effective' || $combine_key == 'primary_expiration')  ) {
								if ($combine_value != '') {
									$temp[$combine_key] = $combine_value;
								} else {
									$temp[$combine_key] = null;
								}
							} else {
								$temp[$combine_key] = $combine_value;
							}
						}
					} else {
						//none integration Hr records module
						//earning1_ (of propational contract)
						//earning2_ (of formal contract)
						if (preg_match('/^earning1_/', $combine_key) ) {
							array_push($probationary_contracts, [
								$combine_key => $combine_value
							]);
						} else if (preg_match('/^earning2_/', $combine_key) || preg_match('/^al2_/', $combine_key)) {
							array_push($primary_contracts, [
								$combine_key => $combine_value
							]);
						} else if ($combine_key != 'department_name' && $combine_key != 'employee_name' && $combine_key != 'employee_number') {
							if (($combine_key == 'probationary_effective' || $combine_key == 'probationary_expiration' || $combine_key == 'primary_effective' || $combine_key == 'primary_expiration')  ) {
								if ($combine_value != '') {
									$temp[$combine_key] = $combine_value;
								} else {
									$temp[$combine_key] = null;
								}
							} else {
								$temp[$combine_key] = $combine_value;
							}
						}
					}					
				}

				$temp['probationary_contracts'] = json_encode($probationary_contracts);
				$temp['primary_contracts'] = json_encode($primary_contracts);
				$temp['month'] = $employees_month;

				$es_detail[] = $temp;
			}
		}

		$row = [];
		$row['update'] = []; 
		$row['insert'] = []; 
		$row['delete'] = [];
		$total = [];

		$total['total_amount'] = 0;
		foreach ($es_detail as $key => $value) {
			if ($value['id'] != 0) {
				$row['delete'][] = $value['id'];
				$row['update'][] = $value;
			} else {
				unset($value['id']);
				$row['insert'][] = $value;
			}

		}

		if (empty($row['delete'])) {
			$row['delete'] = ['0'];
		}
		if ($data['department_employees_filter'] == '' && $data['staff_employees_filter'] == '' && $data['role_employees_filter'] == '') {
			$row['delete'] = implode(",",$row['delete']);
			$this->db->where('id NOT IN ('.$row['delete'] .') and rel_type = "'.$rel_type.'" AND date_format(month,"%Y-%m-%d") = "'.$employees_month.'"');
			$this->db->delete(db_prefix().'hr_employees_value');
			if ($this->db->affected_rows() > 0) {
				$affectedRows++;
			}
		}

		if (count($row['insert']) != 0) {
			$affected_rows = $this->db->insert_batch(db_prefix().'hr_employees_value', $row['insert']);
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}
		if (count($row['update']) != 0) {
			$affected_rows = $this->db->update_batch(db_prefix().'hr_employees_value', $row['update'], 'id');
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}

		if ($affectedRows > 0) {
			return true;
		}
		return false;
	}

	/**
	 * get hrp attendance
	 * @param  [type] $rel_type 
	 * @return [type]           
	 */
	public function get_hr_attendance($month, $where='')
	{
		$rel_type = hr_get_timesheets_status();

		if ($where != '') {
			$this->db->where($where);
		}
		$this->db->where('rel_type', $rel_type);
		$this->db->where("date_format(month, '%Y-%m-%d') = '".$month."'");
		$this->db->order_by('staff_id', 'asc');
		$employees_timesheets = $this->db->get(db_prefix() . 'hr_employees_timesheets')->result_array();

		return $employees_timesheets;
	}

	/**
	 * get day in month
	 * @param  [type] $month 
	 * @return [type]        
	 */
	public function get_day_header_in_month($month, $rel_type='')
	{
		$_month = (int)date('m',strtotime($month ?? ''));
		$_year = (int)date('Y',strtotime($month ?? ''));

		$staff_key=[];
		$attendance_key=[];
		$days_key=[];
		$days_header=[];
		$days_header_name=[];
		$columns_type=[];
		$days_header_type=[];

		$staff_key[] = 'staff_id';
		$staff_key[] = 'id';
		$staff_key[] = 'rel_type';
		$staff_key[] = 'month';
		$staff_key[] = 'hr_code';
		$staff_key[] = 'staff_name';
		$staff_key[] = 'staff_departments';

		$attendance_key[] = 'actual_workday_probation';
		$attendance_key[] = 'actual_workday';
		$attendance_key[] = 'paid_leave';
		$attendance_key[] = 'unpaid_leave';
		$attendance_key[] = 'standard_workday';

		$total_day_in_month = cal_days_in_month(CAL_GREGORIAN,$_month,$_year);
		for ($d = 1; $d <= $total_day_in_month; $d++) {
			$days_key[] = 'day_'.$d;

			$jd=cal_to_jd(CAL_GREGORIAN,$_month,$d,$_year);
            $day=jddayofweek($jd,0);
                switch($day) {
                    case 0:
                       	$days_header['day_'.$d] = 0;
                       	$days_header_name[] = _l('sunday').' '. $d;
                        break;
                    case 1:
                       	$days_header['day_'.$d] = 0;
                       	$days_header_name[] = _l('monday').' '. $d;

                        break;
                    case 2:
                       	$days_header['day_'.$d] = 0;
                       	$days_header_name[] = _l('tuesday').' '. $d;

                        break;
                    case 3:
                       	$days_header['day_'.$d] = 0;
                       	$days_header_name[] = _l('wednesday').' '. $d;

                        break;
                    case 4:
                       	$days_header['day_'.$d] = 0;
                       	$days_header_name[] = _l('thursday').' '. $d;

                        break;
                    case 5:
                       	$days_header['day_'.$d] = 0;
                       	$days_header_name[] = _l('friday').' '. $d;

                        break;
                    case 6:
                       	$days_header['day_'.$d] = 0;
                       	$days_header_name[] = _l('saturday').' '. $d;
                        break;                        
                }
                array_push($days_header_type, [
                	'data' => 'day_'.$d,
                	'type'=> 'numeric',
                	'numericFormat'=> [
                		'pattern' => '0,00',
                	]
                ]);
		}

		$headers=[];
		foreach ($staff_key as $value) {
			if ($value == 'staff_id') {
				$headers[] = 'staff_id';
			} else {
				$headers[] = _l($value);
			}
		    
		    array_push($columns_type, [
		    	'data' => $value,
		    	'type' => 'text'
		    ]);
		}

		$headers = array_merge($headers, $days_header_name);
		$columns_type = array_merge($columns_type, array_values($days_header_type));

		foreach ($attendance_key as $value) {
		    $headers[] = _l($value);

		    array_push($columns_type, [
		    	'data' => $value,
		    	'type'=> 'numeric',
		    	'numericFormat'=> [
		    		'pattern' => '0,00',
		    	]
		    ]);
		}

		$results=[];
		$results['headers'] = $headers;
		$results['staff_key'] = $staff_key;
		$results['attendance_key'] = $attendance_key;
		$results['days_key'] = $days_key;
		$results['days_header'] = $days_header;
		$results['columns_type'] = $columns_type;

		return $results;
	}

	/**
	 * add update attendance
	 * @param [type] $data 
	 */
	public function add_update_attendance($data)
	{	

		$affectedRows = 0;
		$rel_type = hr_get_timesheets_status();

		$attendance_month = date('Y-m-d',strtotime($data['attendance_fill_month'].'-01' ?? ''));

		$days_header_in_month = $this->hr_control_model->get_day_header_in_month($attendance_month);
		$header_key = array_merge($days_header_in_month['staff_key'], $days_header_in_month['days_key'], $days_header_in_month['attendance_key']);
		
		if (isset($data['hr_attendance_value'])) {
			$hr_attendance_value = $data['hr_attendance_value'];
			unset($data['hr_attendance_value']);
		}

		/*update save note*/

		if (isset($hr_attendance_value)) {
			$hr_attendance_detail = json_decode($hr_attendance_value);

			$es_detail = [];
			$row = [];

			foreach ($hr_attendance_detail as $key => $value) {				
				$es_detail[] = array_combine($header_key, $value);
			}
		}

		$row = [];
		$row['update'] = []; 
		$row['insert'] = []; 
		$row['delete'] = [];
		$total = [];

		$total['total_amount'] = 0;
		foreach ($es_detail as $key => $value) {
			if (isset($value['staff_departments'])) {
				unset($value['staff_departments']);
			}
			if (isset($value['hr_code'])) {
				unset($value['hr_code']);
			}
			if (isset($value['staff_name'])) {
				unset($value['staff_name']);
			}
			
			if ($value['id'] != 0) {
				$row['delete'][] = $value['id'];
				$row['update'][] = $value;
			} else {
				unset($value['id']);
				$row['insert'][] = $value;
			}
		}

		if (empty($row['delete'])) {
			$row['delete'] = ['0'];
		}

		if ($data['department_attendance_filter'] == '' && $data['staff_attendance_filter'] == '' && $data['role_attendance_filter'] == '') {
			$row['delete'] = implode(",",$row['delete']);
			$this->db->where('id NOT IN ('.$row['delete'] .') and rel_type = "'.$rel_type.'" AND date_format(month,"%Y-%m-%d") = "'.$attendance_month.'"');
			$this->db->delete(db_prefix().'hr_employees_timesheets');
			if ($this->db->affected_rows() > 0) {
				$affectedRows++;
			}
		}

		if (count($row['insert']) != 0) {
			$affected_rows = $this->db->insert_batch(db_prefix().'hr_employees_timesheets', $row['insert']);
			if ($affected_rows > 0) {
				$affectedRows++;
			}

		}
		if (count($row['update']) != 0) {
			$affected_rows = $this->db->update_batch(db_prefix().'hr_employees_timesheets', $row['update'], 'id');
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}

		if ($affectedRows > 0) {
			return true;
		}
		return false;
	}

	/**
	 * synchronization attendance
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function synchronization_attendance($data)
	{
		$affectedRows = 0;
		$rel_type = hr_get_timesheets_status();

		$attendance_month = date('Y-m-d',strtotime($data['attendance_fill_month'].'-01' ?? ''));
		$timesheets_data = $this->hr_get_timesheets_data($attendance_month, $rel_type);
		//get day header in month
		$days_header_in_month = $this->get_day_header_in_month($attendance_month, $rel_type);
		$header_key = array_merge($days_header_in_month['staff_key'], $days_header_in_month['days_key'], $days_header_in_month['attendance_key']);
		
		if (isset($data['hr_attendance_value'])) {
			$hr_attendance_value = $data['hr_attendance_value'];
			unset($data['hr_attendance_value']);
		}

		/*update save note*/
		if (isset($hr_attendance_value)) {
			$hr_attendance_detail = json_decode($hr_attendance_value);

			$es_detail = [];
			$row = [];
			foreach ($hr_attendance_detail as $key => $value) {
				$attendance_temp = [];
			
				$combine_temp = array_combine($header_key, $value);
				$combine_temp = array_merge($combine_temp, $days_header_in_month['days_header']);

				if (isset($timesheets_data['staff_timesheets'][$combine_temp['staff_id']])) {
					$combine_temp = array_merge($combine_temp, $timesheets_data['staff_timesheets'][$combine_temp['staff_id']]);
				}

				if (isset($timesheets_data['staff_timesheet_details'][$combine_temp['staff_id']])) {
					$combine_temp = array_merge($combine_temp, $timesheets_data['staff_timesheet_details'][$combine_temp['staff_id']]);
				}

				$es_detail[] = $combine_temp;				
			}
		}

		$row = [];
		$row['update'] = []; 
		$row['insert'] = []; 
		$row['delete'] = [];
		$total = [];

		$total['total_amount'] = 0;

		foreach ($es_detail as $key => $value) {
			if (isset($value['hr_code'])) {
				unset($value['hr_code']);
			}
			if (isset($value['staff_name'])) {
				unset($value['staff_name']);
			}
			if (isset($value['staff_departments'])) {
				unset($value['staff_departments']);
			}
			if ($value['id'] != 0) {
				$row['delete'][] = $value['id'];
				$row['update'][] = $value;
			} else {
				unset($value['id']);
				$row['insert'][] = $value;
			}
		}

		if (empty($row['delete'])) {
			$row['delete'] = ['0'];
		}

		if ($data['department_attendance_filter'] == '' && $data['staff_attendance_filter'] == '' && $data['role_attendance_filter'] == '') {
			$row['delete'] = implode(",",$row['delete']);
			$this->db->where('id NOT IN ('.$row['delete'] .') and rel_type = "'.$rel_type.'" AND date_format(month,"%Y-%m-%d") = "'.$attendance_month.'"');
			$this->db->delete(db_prefix().'hr_employees_timesheets');
			if ($this->db->affected_rows() > 0) {
				$affectedRows++;
			}
		}

		if (count($row['insert']) != 0) {
			$affected_rows = $this->db->insert_batch(db_prefix().'hr_employees_timesheets', $row['insert']);
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}
		if (count($row['update']) != 0) {
			$affected_rows = $this->db->update_batch(db_prefix().'hr_employees_timesheets', $row['update'], 'id');
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}

		if ($affectedRows > 0) {
			return true;
		}
		return false;
	}

	/**
	 * hrp get timesheets data
	 * @param  [type] $month 
	 * @return [type]        
	 */
	public function hr_get_timesheets_data($month, $rel_type)
	{
		$timesheet_get_shifts = $this->timesheet_get_shifts($month);
		$get_employees_data = $this->get_employees_data($month);
		$employees_data = [];
		foreach ($get_employees_data as $employee_key => $employee_value) {
		    $employees_data[$employee_value['staff_id']] = $employee_value;
		}

		$y_month = date('Y-m',strtotime($month ?? ''));
		//get option for timesheets type
		$actual_workday   = explode(',', get_hr_control_option('integration_actual_workday'));
		$paid_leave       = explode(',', get_hr_control_option('integration_paid_leave'));
		$unpaid_leave     = explode(',', get_hr_control_option('integration_unpaid_leave'));

		$date_to_column_name = date_to_column_name();

	    //need close attendance before synchronization
		$sql_where_1 = "SELECT staff_id, type, sum(value) as total_time FROM ".db_prefix()."timesheets_timesheet where date_format(date_work,'%Y-%m') = '".$y_month."' group by staff_id, type;";

		$sql_where = "SELECT *, date_work as date_work_before, date_format(date_work, '%d') as date_work FROM ".db_prefix()."timesheets_timesheet where date_format(date_work,'%Y-%m') = '".$y_month."';";
		
		$staff_timesheets=[];
		$staff_timesheet_details=[];
		$timesheets = $this->db->query($sql_where)->result_array();

		foreach ($timesheets as $timesheet) {
			$timesheet_rel_type ='';

			if (in_array($timesheet['type'], $actual_workday)) {
				if (isset($employees_data[$timesheet['staff_id']])) {
					//check timesheet in formal contract or probationary contract.
					$payslip_month = date("m", strtotime($month ?? ''));
					$probationary_expiration_month = date("m", strtotime($employees_data[$timesheet['staff_id']]['probationary_expiration'] ?? ''));

					//if probationary_expiration month == payslip month
					if ($payslip_month == $probationary_expiration_month ) {
						//if probationary_expiration day <= timesheet day
						if (  strtotime($timesheet['date_work_before'] ?? '') <= strtotime($employees_data[$timesheet['staff_id']]['probationary_expiration'] ?? '')) {
							$timesheet_rel_type .= 'actual_workday_probation';
						} else {
						//if probationary_expiration day > timesheet day
							$timesheet_rel_type .= 'actual_workday';
						}
					} else {
						$timesheet_rel_type .= 'actual_workday';
					}
				} else {
					$timesheet_rel_type .= 'actual_workday';
				}
			} else if (in_array($timesheet['type'], $paid_leave)) {
				$timesheet_rel_type .= 'paid_leave';
			} else if (in_array($timesheet['type'], $unpaid_leave)) {
				$timesheet_rel_type .= 'unpaid_leave';
			}

			if ($timesheet_rel_type != '') {
				if (isset($staff_timesheets[$timesheet['staff_id']])) {
					$staff_timesheets[$timesheet['staff_id']][$timesheet_rel_type] += (float)$timesheet['value'];
				} else {
					$staff_timesheets[$timesheet['staff_id']] =  [
						'staff_id' 			=> $timesheet['staff_id'],
						'month' 			=> $month,
						'actual_workday' 	=> 0,
						'actual_workday_probation' 	=> 0,
						'paid_leave' 		=> 0,
						'unpaid_leave' 		=> 0,
						'rel_type' 			=> $rel_type,
						'standard_workday' 			=> isset($timesheet_get_shifts[$timesheet['staff_id']]) ? $timesheet_get_shifts[$timesheet['staff_id']] : 0,
					];

					$staff_timesheets[$timesheet['staff_id']][$timesheet_rel_type] += (float)$timesheet['value'];
				}
			}

			if ($timesheet_rel_type == 'actual_workday') {
				$column_name = $date_to_column_name[$timesheet['date_work']];

				if (isset($staff_timesheet_details[$timesheet['staff_id']][$column_name])) {
					$staff_timesheet_details[$timesheet['staff_id']][$column_name] += (float)$timesheet['value'];
				} else {
					$staff_timesheet_details[$timesheet['staff_id']][$column_name] =  (float)$timesheet['value'];

				}
			}
		}
			
		$results = [];
		$results['staff_timesheets'] = $staff_timesheets;
		$results['staff_timesheet_details'] = $staff_timesheet_details;

		return $results;
	}

	/**
	 * timesheet get shifts
	 * @param  [type] $month 
	 * @return [type]        
	 */
	public function timesheet_get_shifts($month)
	{
		// payslip_template_get_staffid	
		$month_format = date("Y-m", strtotime($month ?? ''));
		$this->db->where(' date_format(from_date, "%Y-%m") <= "'.$month_format.'" AND date_format(to_date, "%Y-%m") >= "'.$month_format.'"');
		$work_shifts = $this->db->get(db_prefix() . 'work_shift')->result_array();

		$staff_shift = [];
		foreach ($work_shifts as $value) {
			if ($value['type_shiftwork'] == 'by_absolute_time') {

				$sql_query = "SELECT ws.id, ws.department, ws.position, ws.staff, ws.from_date, ws.to_date, wsd.work_shift_id, wsd.shift_id, st.id as st_id, wsd.staff_id, wsd.date, IFNULL(time_end_work, 0) - IFNULL(time_start_work, 0) - ( IFNULL(end_lunch_break_time, 0) - IFNULL(start_lunch_break_time, 0)) as shifts_time 
					FROM ".db_prefix()."work_shift as ws
					left join ".db_prefix()."work_shift_detail as wsd on ws.id = wsd.work_shift_id
					left join ".db_prefix()."shift_type as st on wsd.shift_id = st.id
					where ws.id = ".$value['id']."";

				$shift_details = $this->db->query($sql_query)->result_array();

				if ($value['staff'] != 0 || $value['staff'] != '') {
					//staff != 0 OR != '' : assign shifts directly to employees
					foreach ($shift_details as $shift_detail) {
						// if assigned 1 shift twice in the same day, only 1 time
						if (!isset($staff_shift[$shift_detail['staff_id'].'_'.$shift_detail['date'].'_'.$shift_detail['shift_id']])) {
							$staff_shift[$shift_detail['staff_id'].'_'.$shift_detail['date'].'_'.$shift_detail['shift_id']] = $shift_detail['shifts_time'];
						}
					}
				} else {
					//staff == 0 OR == '' : assign shifts directly to department or role
					$arr_staff_ids = $this->payslip_template_get_staffid($value['department'], $value['position'], '');

					if ($arr_staff_ids != false) {
						$arr_staff_ids = explode(',', $arr_staff_ids );
						foreach ($arr_staff_ids as $staff_id) {
							foreach ($shift_details as $shift_detail) {
							// if assigned 1 shift twice in the same day, only 1 time
								if (!isset($staff_shift[$staff_id.'_'.$shift_detail['date'].'_'.$shift_detail['shift_id']])) {

									$staff_shift[$staff_id.'_'.$shift_detail['date'].'_'.$shift_detail['shift_id']] = $shift_detail['shifts_time'];
								}
							}
						}
					}
				}
			} else if ($value['type_shiftwork'] == 'repeat_periodically') {
				$sql_query = "SELECT ws.id, ws.department, ws.position, ws.staff, ws.from_date, ws.to_date, wsd.work_shift_id, wsd.shift_id, st.id as st_id, wsd.staff_id, wsd.number, IFNULL(time_end_work, 0) - IFNULL(time_start_work, 0) - ( IFNULL(end_lunch_break_time, 0) - IFNULL(start_lunch_break_time, 0)) as shifts_time 
					FROM ".db_prefix()."work_shift as ws
					left join ".db_prefix()."work_shift_detail_number_day as wsd on ws.id = wsd.work_shift_id
					left join ".db_prefix()."shift_type as st on wsd.shift_id = st.id
					where ws.id = ".$value['id']."";
				$shift_details = $this->db->query($sql_query)->result_array();

				$shift_details_value=[];
				foreach ($shift_details as $shift_detail) {
					$shift_details_value[$shift_detail['number']] = ['work_shift_id' => $shift_detail['work_shift_id'], 'shifts_time' => $shift_detail['shifts_time']];
				}

				//TO DO
				$shift_detail_from_month = date("m", strtotime($value['from_date'] ?? ''));
				$shift_detail_to_month = date("m", strtotime($value['to_date'] ?? ''));
				$attendance_month_format = date("m", strtotime($month_format ?? ''));

				if ((float)$attendance_month_format == (float)$shift_detail_to_month && (float)$attendance_month_format == (float)$shift_detail_from_month ) {
					$from_day = date_format(date_create($value['from_date']),"j");
					$to_day = date_format(date_create($value['to_date']),"j");
				} else if ((float)$attendance_month_format == (float)$shift_detail_from_month ) {					
					$from_day = date_format(date_create($value['from_date']),"j");
					$to_day = cal_days_in_month(CAL_GREGORIAN,date("m", strtotime($month_format ?? '')),date("Y", strtotime($month_format ?? '')));
				} else if ((float)$attendance_month_format == (float)$shift_detail_to_month) {
					$from_day = 1;
					$to_day = date_format(date_create($value['to_date']),"j");
				} else {
					$from_day = 1;
					$to_day = cal_days_in_month(CAL_GREGORIAN,date("m", strtotime($month_format ?? '')),date("Y", strtotime($month_format ?? '')));
				}

				if ($value['staff'] != 0 || $value['staff'] != '') {
					//staff != 0 OR != '' : assign shifts directly to employees
					foreach ($shift_details as $shift_detail) {
						for ($day = $from_day; $day <= $to_day; $day++) { 
							if (strlen($day ?? '') == 1) {
								$day = '0'.$day;
							}

							$shifts_date = date('Y-m-d', strtotime($month_format.'-'.$day ?? ''));
							$shifts_number = date('N', strtotime($month_format.'-'.$day ?? ''));

							if (date('N', strtotime($month_format.'-'.$day ?? '')) == $shift_detail['number']) {
								if (!isset($staff_shift[$shift_detail['staff_id'].'_'.$shifts_date.'_'.$shift_detail['number']])) {
									$staff_shift[$shift_detail['staff_id'].'_'.$shifts_date.'_'.$shift_detail['number']] = $shift_detail['shifts_time'];
								}
							}
						}
					}
				} else {
					//staff == 0 OR == '' : assign shifts directly to department or role
					$arr_staff_ids = $this->payslip_template_get_staffid($value['department'], $value['position'], '');

					if ($arr_staff_ids != false) {
						$arr_staff_ids = explode(',', $arr_staff_ids );
						foreach ($arr_staff_ids as $staff_id) {
							for ($day = $from_day; $day <= $to_day; $day++) { 
								if (strlen($day ?? '') == 1) {
									$day = '0'.$day;
								}

								$shifts_date = date('Y-m-d', strtotime($month_format.'-'.$day ?? ''));
								$shifts_number = date('N', strtotime($month_format.'-'.$day ?? ''));
								if (isset($shift_details_value[$shifts_number])) {
									$work_shift_id = $shift_details_value[$shifts_number]['work_shift_id']; 
									$shifts_time = $shift_details_value[$shifts_number]['shifts_time']; 

									if (!isset($staff_shift[$staff_id.'_'.$shifts_date.'_'.$work_shift_id])) {
										$staff_shift[$staff_id.'_'.$shifts_date.'_'.$work_shift_id] = $shifts_time;
									}
								}
							}							
						}
					}
				}
			}
		}

		$shift_by_staff=[];
		foreach ($staff_shift as $key => $staff_shift) {
			$staff_id = explode('_', $key)[0];

			if (isset($shift_by_staff[$staff_id])) {
				$shift_by_staff[$staff_id] += (float)$staff_shift;
			} else {
				$shift_by_staff[$staff_id] = (float)$staff_shift;
			}
		}

		return $shift_by_staff;
	}

	/**
	 * import attendance data
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function import_attendance_data($es_detail)
	{
		$affectedRows=0;
		
		$row = [];
		$row['update'] = []; 
		$row['insert'] = []; 
		$row['delete'] = [];
		$total = [];

		$total['total_amount'] = 0;

		foreach ($es_detail as $key => $value) {
			if ($value['id'] != 0) {
				$row['delete'][] = $value['id'];
				$row['update'][] = $value;
			} else {
				unset($value['id']);
				$row['insert'][] = $value;
			}
		}

		if (count($row['insert']) != 0) {
			$affected_rows = $this->db->insert_batch(db_prefix().'hr_employees_timesheets', $row['insert']);
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}
		if (count($row['update']) != 0) {
			$affected_rows = $this->db->update_batch(db_prefix().'hr_employees_timesheets', $row['update'], 'id');
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}

		if ($affectedRows > 0) {
			return true;
		}
		return false;
	}

	/**
	 * attendance calculation
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function attendance_calculation($data)
	{
		$month = date('Y-m-d',strtotime($data['month'].'-01' ?? ''));

		//get employee data for caculation attendance
		$employees = $this->get_employees_data($month);
		$employees_data = [];
		foreach ($employees as $employee_key => $employee_value) {
			$employees_data[$employee_value['staff_id']] = $employee_value;
		}

		$rel_type = hr_get_timesheets_status();
		$date_to_column_name = date_to_column_name();

		$str_select_day = '*, ';
		$str_select_day .= '('.implode("+", $date_to_column_name).') as actual_workday_temp';
		
		$this->db->select($str_select_day);
		$this->db->where('rel_type', $rel_type);
		$this->db->where("date_format(month, '%Y-%m-%d') = '".$month."'");
		$this->db->order_by('staff_id', 'asc');
		$employees_timesheets = $this->db->get(db_prefix() . 'hr_employees_timesheets')->result_array();

		foreach ($employees_timesheets as $em_key => $timesheet) {
			$employees_timesheets[$em_key]['actual_workday'] = 0;
			$employees_timesheets[$em_key]['actual_workday_probation'] = 0;

			if (isset($employees_data[$timesheet['staff_id']])) {
				//check timesheet in formal contract or probationary contract.
				$payslip_month = date("m", strtotime($month ?? ''));
				$probationary_expiration_month = date("m", strtotime($employees_data[$timesheet['staff_id']]['probationary_expiration'] ?? ''));
				$probationary_expiration_day = date("d", strtotime($employees_data[$timesheet['staff_id']]['probationary_expiration'] ?? ''));

					//if probationary_expiration month == payslip month
				foreach ($timesheet as $timesheet_key => $timesheet_value) {
					if ((float)$payslip_month == (float)$probationary_expiration_month ) {

						if (preg_match('/^day_/', $timesheet_key)) {
							$day = str_replace('day_', '', $timesheet_key);
								//if probationary_expiration day <= timesheet day
							if ( (float)$day <= (float)$probationary_expiration_day) {					
								$employees_timesheets[$em_key]['actual_workday_probation'] += $timesheet_value;
							} else {
								//if probationary_expiration day > timesheet day
								$employees_timesheets[$em_key]['actual_workday'] += $timesheet_value;
							}
						}
					} else {
						if (preg_match('/^day_/', $timesheet_key)) {
							$employees_timesheets[$em_key]['actual_workday'] += $timesheet_value;
						}
					}
				}
			} else {
				$employees_timesheets[$em_key]['actual_workday'] = $timesheet['actual_workday_temp'];
			}

			unset($employees_timesheets[$em_key]['actual_workday_temp']);
		}

		if (count($employees_timesheets) > 0) {
			$this->db->update_batch(db_prefix().'hr_employees_timesheets', $employees_timesheets, 'id');
		}
		return true;
	}

	/**
	 * import employees data
	 * @param  [type] $es_detail 
	 * @return [type]            
	 */
	public function import_employees_data($es_detail)
	{
		$es_detail = $this->employees_data_json_encode_decode('json_encode', $es_detail);
		$affectedRows=0;
		
		$row = [];
		$row['update'] = []; 
		$row['insert'] = []; 
		$row['delete'] = [];
		$total = [];

		$total['total_amount'] = 0;
		foreach ($es_detail as $key => $value) {
			if ($value['id'] != 0) {
				$row['delete'][] = $value['id'];
				$row['update'][] = $value;
			} else {
				unset($value['id']);
				$row['insert'][] = $value;
			}
		}

		if (count($row['insert']) != 0) {
			$affected_rows =  $this->db->insert_batch(db_prefix().'hr_employees_value', $row['insert']);
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}
		if (count($row['update']) != 0) {
			$affected_rows = $this->db->update_batch(db_prefix().'hr_employees_value', $row['update'], 'id');
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}
		
		if ($affectedRows > 0) {
			return true;
		}
		return false;
	}

	/**
	 * get format deduction data
	 * @return [type] 
	 */
	public function get_format_deduction_data()
	{	
		$staff_information=[];
		$staff_information[] = 'id';
		$staff_information[] = 'staff_id';
		$staff_information[] = 'month';
		$staff_information[] = 'employee_number';
		$staff_information[] = 'employee_name';
		$staff_information[] = 'department_name';


		//get column header name, column format
		$column_format=[];
		$staff_information_header=[];

		foreach ($staff_information as $value) {
			if ($value == 'staff_id') {
				$staff_information_header[] = 'staff_id';
			} else if ($value == 'id') {
				$staff_information_header[] = 'id';
			} else {
				$staff_information_header[] = _l($value);
			}
			array_push($column_format, [
				'data' => $value,
				'type' => 'text'
			]);
		}		

		//get value for deduction
		$array_deduction = [];
		$array_deduction_header = [];

	    //get salary deductions list from setting
		$salary_deductions = $this->get_salary_deductions_list();

		foreach ($salary_deductions as $key => $value) {
			$name ='';

			if ($value['description'] != '') {
				$name .= $value['description'];
			} else if ($value['code'] != '') {
				$name .= $value['code'];
			} else if ($value['id'] != '') {
				$name .= $value['id'];
			}

			$array_deduction['deduction_'.$value['id']] = $value['rate'];

			if ($value['basis'] == 'gross' || $value['basis'] == 'fixed_amount' ) {
				$array_deduction_header[] = $name.' ('.$value['basis'].')';
			} else {
				$array_deduction_header[] = $name;
			}

			array_push($column_format, [
				'data' => 'deduction_'.$value['id'],
				'type'=> 'numeric',
				'numericFormat'=> [
					'pattern' => '0,00',
				]
			]);
		}

		$results=[];
		$results['staff_information']		= $staff_information;
		$results['array_deduction']			= $array_deduction;
		$results['staff_information_header']			= $staff_information_header;
		$results['array_deduction_header']			= $array_deduction_header;

		$results['column_format']		= $column_format;
		$results['header']		= array_merge($staff_information_header, $array_deduction_header);

		return $results;
	}

	/**
	 * get deductions data
	 * @param  [type] $month    
	 * @param  [type] $rel_type 
	 * @return [type]           
	 */
	public function get_deductions_data($month, $where ='')
	{	
		if ($where != '') {
			$this->db->where($where);
		}
		$this->db->where("date_format(month, '%Y-%m-%d') = '".$month."'");
		$this->db->order_by('staff_id', 'asc');
		$deductions = $this->db->get(db_prefix() . 'hr_salary_deductions')->result_array();

		$deductions_decode = $this->deductions_data_json_encode_decode('decode', $deductions, '');
		return $deductions_decode;
	}


	/**
	 * deductions data json encode decode
	 * @param  [type] $type   
	 * @param  [type] $data   
	 * @param  string $header 
	 * @return [type]         
	 */
	public function deductions_data_json_encode_decode($type, $data, $header ='')
	{
		if ($type == 'decode') {
			// json decode
			foreach ($data as $key => $value) {
				$deduction_list = json_decode($value['deduction_list']);

				unset($data[$key]['deduction_list']);

				foreach ($deduction_list as $deduction_key => $deduction_value) {
					foreach (get_object_vars($deduction_value) as $column_key => $column_value) {
						$data[$key][$column_key] = $column_value;

						$data[$key]['deduction_value'][$column_key] = $column_value;
					}
				}
			}

		} else {
			// json encode
			$data_detail=[];
			$data_detail = $data;

			$data=[];

			foreach ($data_detail as $data_detail_value) {
				$temp = [];
				$deduction_list = [];

				foreach ($data_detail_value as $key => $value) {
					//integration Hr records module
					if (preg_match('/^deduction_/', $key) ) {
						array_push($deduction_list, [
							$key => $value
						]);
					} else if ($key != 'department_name' && $key != 'employee_name' && $key != 'employee_number'  ) {
						$temp[$key] = $value;
					}
				}

				$temp['deduction_list'] = json_encode($deduction_list);
				$data[] = $temp;
			}
		}
		return $data;
	}

	/**
	 * deductions update
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function deductions_update($data)
	{
		$affectedRows = 0;
		
		$format_deductions_data = $this->get_format_deduction_data();
		$staff_information_key = $format_deductions_data['staff_information'];

		$array_deduction_key = array_keys($format_deductions_data['array_deduction']);

		$deductions_month = date('Y-m-d',strtotime($data['deductions_fill_month'].'-01' ?? ''));

		if (isset($data['hr_deductions_value'])) {
			$hr_deductions_value = $data['hr_deductions_value'];
			unset($data['hr_deductions_value']);
		}

		/*update save note*/

		if (isset($hr_deductions_value)) {
			$hr_deductions_detail = json_decode($hr_deductions_value);

			$es_detail = [];
			$row = [];
			$header = array_merge($staff_information_key, $array_deduction_key);			
			foreach ($hr_deductions_detail as $key => $value) {				
				$temp = [];
				$probationary_contracts = [];
				$primary_contracts = [];
				$combine_data = [];
				
				$combine_data = array_combine($header, $value);

				$es_detail[] = $combine_data;
			}
		}

		$es_detail = $this->deductions_data_json_encode_decode('encode', $es_detail);

		$row = [];
		$row['update'] = []; 
		$row['insert'] = []; 
		$row['delete'] = [];
		$total = [];

		$total['total_amount'] = 0;
		foreach ($es_detail as $key => $value) {
			if ($value['id'] != 0) {
				$row['delete'][] = $value['id'];
				$row['update'][] = $value;
			} else {
				unset($value['id']);
				$row['insert'][] = $value;
			}

		}

		if (empty($row['delete'])) {
			$row['delete'] = ['0'];
		}
		if ($data['department_deductions_filter'] == '' && $data['staff_deductions_filter'] == '' && $data['role_deductions_filter'] == '') {
			$row['delete'] = implode(",",$row['delete']);
			$this->db->where('id NOT IN ('.$row['delete'] .')  AND date_format(month,"%Y-%m-%d") = "'.$deductions_month.'"');
			$this->db->delete(db_prefix().'hr_salary_deductions');
			if ($this->db->affected_rows() > 0) {
				$affectedRows++;
			}
		}

		if (count($row['insert']) != 0) {
			$affected_rows = $this->db->insert_batch(db_prefix().'hr_salary_deductions', $row['insert']);
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}
		if (count($row['update']) != 0) {
			$affected_rows = $this->db->update_batch(db_prefix().'hr_salary_deductions', $row['update'], 'id');
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}

		if ($affectedRows > 0) {
			return true;
		}
		return false;
	}

	/**
	 * get format commission data
	 * @return [type]
	 */
	public function get_format_commission_data()
	{	
		$staff_information=[];
		$staff_information[] = 'id';
		$staff_information[] = 'staff_id';
		$staff_information[] = 'rel_type';
		$staff_information[] = 'month';
		$staff_information[] = 'employee_number';
		$staff_information[] = 'employee_name';
		$staff_information[] = 'department_name';
		$staff_information[] = 'commission_amount';


		//get column header name, column format
		$column_format=[];
		$staff_information_header=[];

		foreach ($staff_information as $value) {
			if ($value == 'staff_id') {
				$staff_information_header[] = 'staff_id';
			} else if ($value == 'id') {
				$staff_information_header[] = 'id';
			} else {
				$staff_information_header[] = _l($value);
			}

			if ($value == 'commission_amount') {
				array_push($column_format, [
					'data' => 'commission_amount',
					'type'=> 'numeric',
					'numericFormat'=> [
						'pattern' => '0,00',
					]
				]);
			} else {
				array_push($column_format, [
					'data' => $value,
					'type' => 'text'
				]);
			}
		}
		
		//get value for commission
		$results=[];
		$results['staff_information']		= $staff_information;
		$results['staff_information_header']			= $staff_information_header;

		$results['column_format']		= $column_format;
		$results['headers']		= $staff_information_header;

		return $results;
	}

	/**
	 * get commissions data
	 * @param  [type]
	 * @return [type]
	 */
	public function get_commissions_data($month, $where ='')
	{
		$rel_type = hr_get_commission_status();

		if ($where != '') {
			$this->db->where($where);
		}
		$this->db->where('rel_type', $rel_type);
		$this->db->where("date_format(month, '%Y-%m-%d') = '".$month."'");
		$this->db->order_by('staff_id', 'asc');
		$commissions = $this->db->get(db_prefix() . 'hr_commissions')->result_array();

		return $commissions;
	}

	/**
	 * commissions update
	 * @param  [type]
	 * @return [type]
	 */
	public function commissions_update($data)
	{
		$affectedRows = 0;
		$rel_type = hr_get_commission_status();

		$format_commissions_data = $this->get_format_commission_data();
		$staff_information_key = $format_commissions_data['staff_information'];

		$commissions_month = date('Y-m-d',strtotime($data['commissions_fill_month'].'-01' ?? ''));

		if (isset($data['hr_commissions_value'])) {
			$hr_commissions_value = $data['hr_commissions_value'];
			unset($data['hr_commissions_value']);
		}

		/*update save note*/
		if (isset($hr_commissions_value)) {
			$hr_commissions_detail = json_decode($hr_commissions_value);

			$es_detail = [];
			$row = [];
			$header = $staff_information_key;			
			foreach ($hr_commissions_detail as $key => $value) {				
				$temp = [];
				$combine_data = [];
				$combine_data = array_combine($header, $value);

				$es_detail[] = $combine_data;
			}
		}

		$row = [];
		$row['update'] = []; 
		$row['insert'] = []; 
		$row['delete'] = [];
		$total = [];

		$total['total_amount'] = 0;
		foreach ($es_detail as $key => $value) {
			if (isset($value['employee_number'])) {
				unset($value['employee_number']);
			}
			if (isset($value['employee_name'])) {
				unset($value['employee_name']);
			}
			if (isset($value['department_name'])) {
				unset($value['department_name']);
			}
			
			if ($value['id'] != 0) {
				$row['delete'][] = $value['id'];
				$row['update'][] = $value;
			} else {
				unset($value['id']);
				$row['insert'][] = $value;
			}
		}

		if (empty($row['delete'])) {
			$row['delete'] = ['0'];
		}
		if ($data['department_commissions_filter'] == '' && $data['staff_commissions_filter'] == '' && $data['role_commissions_filter'] == '') {
			$row['delete'] = implode(",",$row['delete']);
			$this->db->where('id NOT IN ('.$row['delete'] .')  AND date_format(month,"%Y-%m-%d") = "'.$commissions_month.'" AND rel_type = "'.$rel_type.'"');
			$this->db->delete(db_prefix().'hr_commissions');
			if ($this->db->affected_rows() > 0) {
				$affectedRows++;
			}
		}

		if (count($row['insert']) != 0) {
			$affected_rows = $this->db->insert_batch(db_prefix().'hr_commissions', $row['insert']);
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}
		if (count($row['update']) != 0) {
			$affected_rows = $this->db->update_batch(db_prefix().'hr_commissions', $row['update'], 'id');
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}

		if ($affectedRows > 0) {
			return true;
		}
		return false;
	}

	/**
	 * import commissions data
	 * @param  [type] $es_detail 
	 * @return [type]            
	 */
	public function import_commissions_data($es_detail)
	{
		$affectedRows=0;
		
		$row = [];
		$row['update'] = []; 
		$row['insert'] = []; 
		$row['delete'] = [];
		$total = [];

		$total['total_amount'] = 0;

		foreach ($es_detail as $key => $value) {
			if ($value['id'] != 0) {
				$row['delete'][] = $value['id'];
				$row['update'][] = $value;
			} else {
				unset($value['id']);
				$row['insert'][] = $value;
			}
		}

		if (count($row['insert']) != 0) {
			$affected_rows = $this->db->insert_batch(db_prefix().'hr_commissions', $row['insert']);
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}
		if (count($row['update']) != 0) {
			$affected_rows = $this->db->update_batch(db_prefix().'hr_commissions', $row['update'], 'id');
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}

		if ($affectedRows > 0) {
			return true;
		}
		return false;
	}

	/**
	 * commissions synchronization
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function commissions_synchronization($data)
	{
		$affectedRows = 0;
		$rel_type = hr_get_commission_status();

		$format_commissions_data = $this->get_format_commission_data();
		$staff_information_key = $format_commissions_data['staff_information'];
		$commissions_month = date('Y-m-d',strtotime($data['commissions_fill_month'].'-01' ?? ''));

		$staff_commissions = $this->get_list_staff_commissions($commissions_month);

		if (isset($data['hr_commissions_value'])) {
			$hr_commissions_value = $data['hr_commissions_value'];
			unset($data['hr_commissions_value']);
		}

		/*update save note*/
		if (isset($hr_commissions_value)) {
			$hr_commissions_detail = json_decode($hr_commissions_value);

			$es_detail = [];
			$row = [];
			$header = $staff_information_key;			
			foreach ($hr_commissions_detail as $key => $value) {
				
				$temp = [];
				$combine_data = [];
				$combine_data = array_combine($header, $value);

				$es_detail[] = $combine_data;
			}
		}

		$row = [];
		$row['update'] = []; 
		$row['insert'] = []; 
		$row['delete'] = [];
		$total = [];

		$total['total_amount'] = 0;
		foreach ($es_detail as $key => $value) {
			if (isset($staff_commissions[$value['staff_id']])) {
				$value['commission_amount'] = $staff_commissions[$value['staff_id']]['commission_amount'];
			}

			if (isset($value['employee_number'])) {
				unset($value['employee_number']);
			}
			if (isset($value['employee_name'])) {
				unset($value['employee_name']);
			}
			if (isset($value['department_name'])) {
				unset($value['department_name']);
			}
			
			if ($value['id'] != 0) {
				$row['delete'][] = $value['id'];
				$row['update'][] = $value;
			} else {
				unset($value['id']);
				$row['insert'][] = $value;
			}

		}

		if (empty($row['delete'])) {
			$row['delete'] = ['0'];
		}
		if ($data['department_commissions_filter'] == '' && $data['staff_commissions_filter'] == '' && $data['role_commissions_filter'] == '') {
			$row['delete'] = implode(",",$row['delete']);
			$this->db->where('id NOT IN ('.$row['delete'] .')  AND date_format(month,"%Y-%m-%d") = "'.$deductions_month.'" AND rel_type = "'.$rel_type.'"');
			$this->db->delete(db_prefix().'hr_commissions');
			if ($this->db->affected_rows() > 0) {
				$affectedRows++;
			}
		}

		if (count($row['insert']) != 0) {
			$affected_rows = $this->db->insert_batch(db_prefix().'hr_commissions', $row['insert']);
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}
		if (count($row['update']) != 0) {
			$affected_rows = $this->db->update_batch(db_prefix().'hr_commissions', $row['update'], 'id');
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}

		if ($affectedRows > 0) {
			return true;
		}
		return false;
	}

	/**
	 * get list staff commissions
	 * @param  [type] $month 
	 * @return [type]        
	 */
	public function get_list_staff_commissions($month)
	{
		$month = date('Y-m', strtotime($month ?? ''));
		$sql_where ="SELECT c.staffid, sum(crd.amount_paid) as commission_amount FROM ".db_prefix()."commission_receipt as cr
		left join ".db_prefix()."commission_receipt_detail as crd on cr.id = crd.receipt_id
		left join ".db_prefix()."commission as c on crd.commission_id = c.id
		where date_format(cr.date, '%Y-%m') = '".$month."' AND c.is_client = '0'
		group by c.staffid";

		$commissions = $this->db->query($sql_where)->result_array();

		$staff_commissions=[];
		foreach ($commissions as $value) {
		    $staff_commissions[$value['staffid']] = $value;
		}

		return $staff_commissions;
	}

	/**
	 * get income tax data
	 * @param  [type] $month 
	 * @return [type]        
	 */
	public function get_income_tax_data($month)
	{
		$this->db->where("date_format(month, '%Y-%m-%d') = '".$month."'");
		$this->db->order_by('staff_id', 'asc');
		$income_taxs = $this->db->get(db_prefix() . 'hr_income_taxs')->result_array();
		return $income_taxs;
	}

	/**
	 * get total income tax in year
	 * @param  [type] $month 
	 * @return [type]        
	 */
	public function get_total_income_tax_in_year($month)
	{
		$month = date('Y', strtotime($month ?? ''));

		$this->db->select('staff_id, sum(income_tax) as tax_for_year');
		$this->db->where("date_format(month, '%Y') = '".$month."'");
		$this->db->group_by('staff_id');
		$income_taxs = $this->db->get(db_prefix() . 'hr_income_taxs')->result_array();
		return $income_taxs;
	}

	/**
	 * get format income tax data
	 * @return [type] 
	 */
	public function get_format_income_tax_data()
	{	
		$staff_information=[];
		$staff_information[] = 'id';
		$staff_information[] = 'staff_id';
		$staff_information[] = 'month';
		$staff_information[] = 'employee_number';
		$staff_information[] = 'employee_name';
		$staff_information[] = 'department_name';
		$staff_information[] = 'income_tax';
		$staff_information[] = 'tax_for_year';

		//get column header name, column format
		$column_format=[];
		$staff_information_header=[];

		foreach ($staff_information as $value) {
			if ($value == 'staff_id') {
				$staff_information_header[] = 'staff_id';
			} else if ($value == 'id') {
				$staff_information_header[] = 'id';
			} else {
				$staff_information_header[] = _l($value);
			}

			if ($value == 'income_tax' || $value == 'tax_for_year') {
				array_push($column_format, [
					'data' => 'income_tax',
					'type'=> 'numeric',
					'numericFormat'=> [
						'pattern' => '0,00',
					]
				]);
			} else {
				array_push($column_format, [
					'data' => $value,
					'type' => 'text'
				]);
			}
		}		

		//get value for commission

		$results=[];
		$results['staff_information']		= $staff_information;
		$results['staff_information_header']			= $staff_information_header;

		$results['column_format']		= $column_format;
		$results['headers']		= $staff_information_header;

		return $results;
	}

	/**
	 * get format insurances data
	 * @return [type] 
	 */
	public function get_format_insurance_data()
	{	
		$staff_information=[];
		$staff_information[] = 'id';
		$staff_information[] = 'staff_id';
		$staff_information[] = 'month';
		$staff_information[] = 'employee_number';
		$staff_information[] = 'employee_name';
		$staff_information[] = 'department_name';

		//get column header name, column format
		$column_format=[];
		$staff_information_header=[];

		foreach ($staff_information as $value) {
			if ($value == 'staff_id') {
				$staff_information_header[] = 'staff_id';
			} else if ($value == 'id') {
				$staff_information_header[] = 'id';
			} else {
				$staff_information_header[] = _l($value);
			}
			array_push($column_format, [
				'data' => $value,
				'type' => 'text'
			]);
		}
		
		//get value for insurance
		$array_insurance = [];
		$array_insurance_header = [];

	    //get salary insurance list from setting
		$salary_insurances = $this->get_insurance_list();

		foreach ($salary_insurances as $key => $value) {
			$name ='';

			if ($value['description'] != '') {
				$name .= $value['description'];
			} else if ($value['code'] != '') {
				$name .= $value['code'];
			} else if ($value['id'] != '') {
				$name .= $value['id'];
			}

			$array_insurance['st_insurance_'.$value['id']] = $value['rate'];
			$array_insurance_header[] = $name.' ('._l($value['basis']).')';

			array_push($column_format, [
				'data' => 'st_insurance_'.$value['id'],
				'type'=> 'numeric',
				'numericFormat'=> [
					'pattern' => '0,00',
				]
			]);
		}

		$results=[];
		$results['staff_information']		= $staff_information;
		$results['array_insurance']			= $array_insurance;
		$results['staff_information_header']			= $staff_information_header;
		$results['array_insurance_header']			= $array_insurance_header;

		$results['column_format']		= $column_format;
		$results['header']		= array_merge($staff_information_header, $array_insurance_header);

		return $results;
	}

	/**
	 * get insurances data
	 * @param  [type] $month 
	 * @return [type]        
	 */
	public function get_insurances_data($month, $where ='')
	{	
		if ($where != '') {
			$this->db->where($where);
		}
		$this->db->where("date_format(month, '%Y-%m-%d') = '".$month."'");
		$this->db->order_by('staff_id', 'asc');
		$insurances = $this->db->get(db_prefix() . 'hr_staff_insurances')->result_array();

		$insurances_decode = $this->insurances_data_json_encode_decode('decode', $insurances, '');
		return $insurances_decode;
	}

	/**
	 * insurances data json encode decode
	 * @param  [type] $type   
	 * @param  [type] $data   
	 * @param  string $header 
	 * @return [type]         
	 */
	public function insurances_data_json_encode_decode($type, $data, $header ='')
	{
		if ($type == 'decode') {
			// json decode
			foreach ($data as $key => $value) {
				$insurance_list = json_decode($value['insurance_list']);
				unset($data[$key]['insurance_list']);

				foreach ($insurance_list as $insurance_key => $insurance_value) {
					foreach (get_object_vars($insurance_value) as $column_key => $column_value) {
						$data[$key][$column_key] = $column_value;
						$data[$key]['insurance_value'][$column_key] = $column_value;
					}
				}
			}
		} else {
			// json encode
			$data_detail=[];
			$data_detail = $data;

			$data=[];

			foreach ($data_detail as $data_detail_value) {
				$temp = [];
				$insurance_list = [];

				foreach ($data_detail_value as $key => $value) {
					//integration Hr records module
					if (preg_match('/^st_insurance_/', $key) ) {
						array_push($insurance_list, [
							$key => $value
						]);
					} else if ($key != 'department_name' && $key != 'employee_name' && $key != 'employee_number'  ) {
						$temp[$key] = $value;
					}					
				}

				$temp['insurance_list'] = json_encode($insurance_list);
				$data[] = $temp;
			}
		}
		return $data;
	}

	/**
	 * insurances update
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function insurances_update($data)
	{
		$affectedRows = 0;
		
		$format_insurances_data = $this->get_format_insurance_data();
		$staff_information_key = $format_insurances_data['staff_information'];

		$array_insurance_key = array_keys($format_insurances_data['array_insurance']);

		$insurances_month = date('Y-m-d',strtotime($data['insurances_fill_month'].'-01' ?? ''));

		if (isset($data['hr_insurances_value'])) {
			$hr_insurances_value = $data['hr_insurances_value'];
			unset($data['hr_insurances_value']);
		}

		/*update save note*/
		if (isset($hr_insurances_value)) {
			$hr_insurances_detail = json_decode($hr_insurances_value);

			$es_detail = [];
			$row = [];
			$header = array_merge($staff_information_key, $array_insurance_key);			
			foreach ($hr_insurances_detail as $key => $value) {				
				$temp = [];
				$probationary_contracts = [];
				$primary_contracts = [];
				$combine_data = [];
				
				$combine_data = array_combine($header, $value);

				$es_detail[] = $combine_data;
			}
		}

		$es_detail = $this->insurances_data_json_encode_decode('encode', $es_detail);

		$row = [];
		$row['update'] = []; 
		$row['insert'] = []; 
		$row['delete'] = [];
		$total = [];

		$total['total_amount'] = 0;
		foreach ($es_detail as $key => $value) {
			if ($value['id'] != 0) {
				$row['delete'][] = $value['id'];
				$row['update'][] = $value;
			} else {
				unset($value['id']);
				$row['insert'][] = $value;
			}
		}

		if (empty($row['delete'])) {
			$row['delete'] = ['0'];
		}
		if ($data['department_insurances_filter'] == '' && $data['staff_insurances_filter'] == '' && $data['role_insurances_filter'] == '') {
			$row['delete'] = implode(",",$row['delete']);
			$this->db->where('id NOT IN ('.$row['delete'] .')  AND date_format(month,"%Y-%m-%d") = "'.$insurances_month.'"');
			$this->db->delete(db_prefix().'hr_staff_insurances');
			if ($this->db->affected_rows() > 0) {
				$affectedRows++;
			}
		}

		if (count($row['insert']) != 0) {
			$affected_rows = $this->db->insert_batch(db_prefix().'hr_staff_insurances', $row['insert']);
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}
		if (count($row['update']) != 0) {
			$affected_rows = $this->db->update_batch(db_prefix().'hr_staff_insurances', $row['update'], 'id');
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}

		if ($affectedRows > 0) {
			return true;
		}
		return false;
	}

	/**
	 * get hrp payroll columns
	 * @param  boolean $id 
	 * @return [type]      
	 */
	public function get_hr_control_columns($id = false) {
		if (is_numeric($id)) {
			$this->db->where('id', $id);
			return $this->db->get(db_prefix() . 'hr_payroll_columns')->row();
		}
		if ($id == false) {
			return $this->db->order_by('order_display', 'asc')->get(db_prefix(). 'hr_payroll_columns')->result_array();
		}
	}

	/**
	 * get list payroll column method
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function get_list_payroll_column_method($data)
	{
		$method_option = '';

		if (isset($data['taking_method']) && $data['taking_method'] != '') {
			$list_column_method=[];
			$list_column_method[] = ['name' => 'system', 'lable' => _l('system_method')];
			$list_column_method[] = ['name' => 'caculator', 'lable' => _l('caculator_method')];
			$list_column_method[] = ['name' => 'constant', 'lable' => _l('constant_method')];

			$method_option .= '<option value=""></option>';
			foreach ($list_column_method as $method) {
				$select='';
				if ($method['name'] == $data['taking_method']) {           
					$select .= 'selected';
				}
				$method_option .= '<option value="' . $method['name'] . '" '.$select.'>' . $method['lable'] . '</option>';
			}
		} else {
			/*get payroll column method for case create new*/

			$method_option .= '<option value=""></option>';

			$method_option .= '<option value="system">' . _l('system_method'). '</option>';
			$method_option .= '<option value="caculator">' . _l('caculator_method'). '</option>';
			$method_option .= '<option value="constant">' . _l('constant_method'). '</option>';
		}
	   
		$data_return =[];
		$data_return['method_option'] = $method_option;

		return $data_return;
	}

	/**
	 * get list payroll column function name
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function get_list_payroll_column_function_name($data)
	{
		$prefix_probationary = HR_CONTROL_PREFIX_PROBATIONARY;
		$prefix_formal = HR_CONTROL_PREFIX_FORMAL;
		$payroll_system_columns = payroll_system_columns();
		$payroll_columns = $this->get_hr_control_columns();

		$hr_payroll_columns=[];
		foreach ($payroll_columns as $key => $value) {
			$hr_payroll_columns[] = $value['function_name'];
		}

		$method_option = '';

		//define function for get data payroll column: only get from system
		$list_column_method=[];
		foreach ($payroll_system_columns as $column_value) {
		    $list_column_method[] = ['function_name' => $column_value,        'lable' => ($column_value)];
		}

		//staff contract: salary, allowance
		$integrate_hr_profile = hr_get_profile_status();

		if ($integrate_hr_profile == 'hr_records') {
			//hr_records
			$hr_records_earnings_list = $this->hr_records_get_earnings_list();
			foreach ($hr_records_earnings_list as $key => $value) {				
				switch ($value['rel_type']) {
					case 'salary':
						$code1 = 'st1_'.$value['rel_id'];
						$code2 = 'st2_'.$value['rel_id'];
						break;

					case 'allowance':
						$code1 = 'al1_'.$value['rel_id'];
						$code2 = 'al2_'.$value['rel_id'];
						break;
					
					default:
						# code...
						break;
				}

				if ($value['short_name'] != '') {
					$list_column_method[] = ['function_name' => $code1,          'lable' => $value['short_name'].$prefix_probationary];
					$list_column_method[] = ['function_name' => $code2,          'lable' => $value['short_name'].$prefix_formal];
				} else if ($value['description'] != '') {
					$list_column_method[] = ['function_name' => $code1,          'lable' => $value['description'].$prefix_probationary];
					$list_column_method[] = ['function_name' => $code2,          'lable' => $value['description'].$prefix_formal];
				} else if ($value['code'] != '') {
					$list_column_method[] = ['function_name' => $code1,          'lable' => $value['code'].$prefix_probationary];
					$list_column_method[] = ['function_name' => $code2,          'lable' => $value['code'].$prefix_formal];
				} else if ($value['id'] != '') {
					$list_column_method[] = ['function_name' => $code1,          'lable' => $value['id'].$prefix_probationary];
					$list_column_method[] = ['function_name' => $code2,          'lable' => $value['id'].$prefix_formal];
				}
			}
		} else {
			//none
			$earnings_list = $this->get_earnings_list();
			foreach ($earnings_list as $key => $value) {
				$list_column_method[] = ['function_name' => 'earning1_'.$value['id'],          'lable' => $value['short_name'].$prefix_probationary];
				$list_column_method[] = ['function_name' => 'earning2_'.$value['id'],          'lable' => $value['short_name'].$prefix_formal];
			}
		}

		//get salary deductions list from setting
		$salary_deductions = $this->get_salary_deductions_list();
		foreach ($salary_deductions as $key => $value) {
			$name ='';

			if ($value['description'] != '') {
				$name .= $value['description'];
			} else if ($value['code'] != '') {
				$name .= $value['code'];
			} else if ($value['id'] != '') {
				$name .= $value['id'];
			}

			if ($value['basis'] == 'gross' || $value['basis'] == 'fixed_amount' ) {
				$list_column_method[] = ['function_name' => 'deduction_'.$value['id'],        'lable' => $name.' ('.$value['basis'].')'];
			} else {
				$list_column_method[] = ['function_name' => 'deduction_'.$value['id'],        'lable' => $name];
			}
		}

		//get insurance list from setting
		$salary_insurances = $this->get_insurance_list();
		foreach ($salary_insurances as $key => $value) {
			$name ='';

			if ($value['description'] != '') {
				$name .= $value['description'];
			} else if ($value['code'] != '') {
				$name .= $value['code'];
			} else if ($value['id'] != '') {
				$name .= $value['id'];
			}

			$list_column_method[] = ['function_name' => 'st_insurance_'.$value['id'],        'lable' => $name.' ('._l($value['basis']).')'];
		}
					
		if (isset($data['function_name']) && $data['function_name'] != '') {
			$method_option .= '<option value=""></option>';
			foreach ($list_column_method as $method) {
				if (!in_array($method['function_name'], $payroll_system_columns) && !in_array($method['function_name'], $hr_payroll_columns) || $method['function_name'] == $data['function_name']) {
					$select='';
					if ($method['function_name'] == $data['function_name']) {           
						$select .= 'selected';
					}
					$method_option .= '<option value="' . $method['function_name'] . '" '.$select.'>' . $method['lable'] . '</option>';
				}
			}
		} else {
			/*get payroll column method for case create new*/
			$method_option .= '<option value=""></option>';
			foreach ($list_column_method as $method) {
				if (!in_array($method['function_name'], $payroll_system_columns) && !in_array($method['function_name'], $hr_payroll_columns)) {
					$method_option .= '<option value="' . $method['function_name'] . '" >' . $method['lable'] . '</option>';
				}
			}
		}
	   
		$data_return =[];
		$data_return['method_option'] = $method_option;

		return $data_return;
	}

	/**
	 * add payroll column
	 * @param [type] $data 
	 */
	public function add_payroll_column($data) {
		if (isset($data['display_with_staff'])) {
			$data['display_with_staff'] = 'true';
		} else {
			$data['display_with_staff'] = 'false';
		}  

		$data['staff_id_created'] = get_staff_user_id();
		$data['date_created'] = date('Y-m-d H:i:s');

		$this->db->insert(db_prefix() . 'hr_payroll_columns', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	/**
	 * update insurance type
	 * @param  array $data 
	 * @param  integer $id   
	 * @return boolean       
	 */
	public function update_payroll_column($data, $id) {
		$hr_payroll_column = $this->get_hr_control_columns();
		if ($hr_payroll_column) {
			if ($hr_payroll_column->is_edit == 'no') {
				if (isset($data['taking_method'])) {
					unset($data['taking_method']);
				}
				if (isset($data['function_name'])) {
					unset($data['function_name']);
				}
			}
		}

		if (isset($data['display_with_staff'])) {
			$data['display_with_staff'] = 'true';
		} else {
			$data['display_with_staff'] = 'false';
		}
		
		$data['staff_id_created'] = get_staff_user_id();
		$this->db->where('id',$id);
		$this->db->update(db_prefix().'hr_payroll_columns', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * delete insurance type
	 * @param  integer $id 
	 * @return boolean     
	 */
	public function delete_payroll_column($id) {
		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'hr_payroll_columns');
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * count payroll column
	 * @return [type] 
	 */
	public function count_control_column()
	{
		$payroll_columns = count($this->get_hr_control_columns());

		return (float)$payroll_columns + 1;
	}

	/**
	 * get hrp payslip templates
	 * @param  boolean $id 
	 * @return [type]      
	 */
	public function get_hr_payslip_templates($id = false) {
		if (is_numeric($id)) {
			$this->db->where('id', $id);
			return $this->db->get(db_prefix() . 'hr_payslip_templates')->row();
		}
		if ($id == false) {
			return $this->db->query('select * from ' . db_prefix() . 'hr_payslip_templates order by id desc')->result_array();
		}
	}

	/**
	 * get hrp payslip
	 * @param  boolean $id 
	 * @return [type]      
	 */
	public function get_hr_payslip($id = false) {
		if (is_numeric($id)) {
			$this->db->where('id', $id);
			return $this->db->get(db_prefix() . 'hr_payslips')->row();
		}
		if ($id == false) {
			return $this->db->query('select * from ' . db_prefix() . 'hr_payslips order by id desc')->result_array();
		}
	}

	/**
	 * get payslip template selected html
	 * @param  [type] $payslip_template_id 
	 * @return [type]                      
	 */
	public function get_payslip_template_selected_html($payslip_template_id)
	{
		$payslip_templates = $this->get_hr_payslip_templates();
		$template_options = '';

		if (isset($payslip_template_id) && $payslip_template_id != '') {
			$template_options .= '<option value=""></option>';
			foreach ($payslip_templates as $template) {
				$select='';
				if ($template['id'] == $payslip_template_id) {           
					$select .= 'selected';
				}
				$template_options .= '<option value="' . $template['id'] . '" '.$select.'>' . $template['templates_name'] . '</option>';
			}
		} else {
			/*get payslip template for case create new*/

			$template_options .= '<option value=""></option>';
			foreach ($payslip_templates as $template) {
				$template_options .= '<option value="' . $template['id'] . '" >' . $template['templates_name'] . '</option>';
			}
		}

		return $template_options;
	}

	/**
	 * get payslip column html
	 * @param  [type] $payslip_columns 
	 * @return [type]                  
	 */
	public function get_payslip_column_html($payslip_columns)
	{		
		$payroll_columns = $this->get_hr_control_columns();
		$payroll_column_options = '';

		if (isset($payslip_columns) && $payslip_columns != '') {
			$array_payslip_column = explode(",", $payslip_columns);

			foreach ($payroll_columns as $column_id) {
				$select='';
				if (in_array($column_id['id'], $array_payslip_column)) {
					$select .= 'selected';
				}
				
				$payroll_column_options .= '<option value="' . $column_id['id'] . '" '.$select.'>' . $column_id['column_key'] . '</option>';
			}
		} else {
			/*get payslip template for case create new*/
			foreach ($payroll_columns as $column_id) {
				$payroll_column_options .= '<option value="' . $column_id['id'] . '" >' . $column_id['column_key'] . '</option>';
			}
		}

		return $payroll_column_options;
	}

	/**
	 * add payslip template
	 * @param [type] $data 
	 */
	public function add_payslip_template($data) {
		if (isset($data['department_id'])) {
			$data['department_id'] = implode(',', $data['department_id']);
		}
		if (isset($data['role_employees'])) {
			$data['role_employees'] = implode(',', $data['role_employees']);
		}
		if (isset($data['staff_employees'])) {
			$data['staff_employees'] = implode(',', $data['staff_employees']);
		}
		if (isset($data['except_staff'])) {
			$data['except_staff'] = implode(',', $data['except_staff']);
		}
		
		if (isset($data['edit_payslip_column'])) {
			unset($data['edit_payslip_column']);
		}

		//add staff_id default to payslip template
		if (!in_array('1', $data['payslip_columns'])) {
			array_unshift($data['payslip_columns'], '1');
		}

		$data['payslip_columns'] =  implode(',', $data['payslip_columns']);
		$data['staff_id_created'] = get_staff_user_id();
		$data['date_created'] = date('Y-m-d H:i:s');

		$this->db->insert(db_prefix() . 'hr_payslip_templates', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	/**
	 * update payslip template
	 * @param  [type] $data 
	 * @param  [type] $id   
	 * @return [type]       
	 */
	public function update_payslip_template($data, $id)
	{
		if (isset($data['department_id'])) {
			$data['department_id'] = implode(',', $data['department_id']);
		} else {
			$data['department_id'] = '';
		}
		if (isset($data['role_employees'])) {
			$data['role_employees'] = implode(',', $data['role_employees']);
		} else {
			$data['role_employees'] = '';
		}
		if (isset($data['staff_employees'])) {
			$data['staff_employees'] = implode(',', $data['staff_employees']);
		} else {
			$data['staff_employees'] = '';
		}

		if (isset($data['except_staff'])) {
			$data['except_staff'] = implode(',', $data['except_staff']);
		} else {
			$data['except_staff'] = '';
		}

		$data['payslip_columns'] =  implode(',', $data['payslip_columns']);
		$data['staff_id_created'] = get_staff_user_id();

		$this->db->where('id',$id);
		$this->db->update(db_prefix().'hr_payslip_templates', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * delete payslip template
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_payslip_template($id) {
		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'hr_payslip_templates');
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * delete payslip
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_payslip($id)
	{
        hooks()->do_action('before_payslip_deleted', $id);
		
		$affected_rows =0;

		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'hr_payslips');
		if ($this->db->affected_rows() > 0) {
			$affected_rows++;
		}

		$delete_payslip_detail = $this->delete_payslip_detail($id);
		if ($delete_payslip_detail == true) {
			$affected_rows++;
		}

		//delete income tax
		$this->db->where('payslip_id', $id);
		$this->db->delete(db_prefix() . 'hr_income_taxs');
		if ($this->db->affected_rows() > 0) {
			$affected_rows++;
		}

		if ($affected_rows > 0) {
			return true;
		}
		return false;
	}

	/**
	 * delete_payslip_detail
	 * @param  [type] $payslip_id 
	 * @return [type]             
	 */
	public function delete_payslip_detail($payslip_id)
	{
		$this->db->where('payslip_id', $payslip_id);
		$this->db->delete(db_prefix() . 'hr_payslip_details');
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		
		return false;
	}

	/**
	 * update payslip templates detail
	 * @param  [type] $data 
	 * @param  [type] $id   
	 * @return [type]       
	 */
	public function update_payslip_templates_detail($data, $id) {  
		if (isset($data['image_flag'])) {
			if ($data['image_flag'] == "true") {
				$data['payslip_template_data'] = str_replace('[removed]', 'data:image/png;base64,', $data['payslip_template_data']); 
				$data['payslip_template_data'] = str_replace('imga$imga', '"', $data['payslip_template_data']); 
				$data['payslip_template_data'] = str_replace('""', '"', $data['payslip_template_data']); 
			}
		}

		$payslip_template_data_decode = json_decode($data['payslip_template_data']);
		if (isset($payslip_template_data_decode[0])) {
			if (isset($payslip_template_data_decode[0]->celldata)) {
				$data['cell_data'] = hr_payslip_replace_string(json_encode($payslip_template_data_decode[0]->celldata));
			}
		}
		$data['payslip_template_data'] = hr_payslip_replace_string($data['payslip_template_data']);
		$data['templates_name'] = $data['name']; 
		unset($data['name']);
		unset($data['image_flag']);

		$this->db->where('id', $id);
		$this->db->update(db_prefix().'hr_payslip_templates', $data);

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * add payslip templates detail first
	 * @param [type] $id 
	 */
	public function add_payslip_templates_detail_first($id, $update= false, $old_column_formular=[])
	{
		$payslip_templates = $this->get_hr_payslip_templates($id);

		$payslip_template_data = [];

		if ($payslip_templates) {
			$payslip_columns = explode(",", $payslip_templates->payslip_columns);

			$sql_where = "SELECT id, column_key, function_name, taking_method FROM ".db_prefix()."hr_payroll_columns where find_in_set(id, '".$payslip_templates->payslip_columns."') order by order_display";
			$payroll_columns = $this->db->query($sql_where)->result_array();

			$payslip_template_data['name']      = $payslip_templates->templates_name;

			$data = [];
			$data_row_null = [];
			$data_row_name = [];

			$cell_data = [];

			//column A to Z
			$min_index_column = 25 < count($payslip_columns) ? count($payslip_columns) : 25;

			$payslip_template_data['column']    = $min_index_column;

			//render data null of row
			for ($x = 0; $x <= $min_index_column; $x++) {
				$data_row_null[] = null;
			}

			$columnlen=[];
			$rowlen=[];
			$rowlen[3] = 46;
			$rowlen[4] = 46;
			$rowlen[5] = 46;
			$calcChain =[];

			//add data value for cell			
			foreach ($payslip_columns as $value) {
				$columnlen[] = 183;

				$neededObject = array_filter(
					$payroll_columns,
					function ($e) use ($value) {
						return $e['id'] == $value ;
					}
				);

				foreach ($neededObject as $object_key => $object_value) {
					$data_row_name[]    = $this->general_template_cell_data($object_value['column_key']);
					$cell_data[]        = $this->general_cell_data(3, $object_key, $object_value['column_key'],'','',true , false);
					$cell_data[]        = $this->general_cell_data(4, $object_key, $object_value['function_name'],'','',false , true);

					$f='';
					if ($update == true) {
						//case update
						if ($object_value['taking_method'] == 'caculator' || $object_value['taking_method'] == 'constant') {

							if ($object_value['taking_method'] == 'caculator') {
								$object_value['taking_method'] = 'formula';
							}
							if (isset($old_column_formular[$object_value['function_name']])) {
								$f = $old_column_formular[$object_value['function_name']];
							}

			    			//calcChain: Formula chain, used when the cell linked by the formula is changed, all formulas referencing this cell will be refreshed.
							array_push($calcChain, [
								"r" => 5,
								"c" => $object_key,
								"index" => 0,
								"color" => "b",
								"parent" => null,
								"chidren" => new stdClass(),
								"times" => 1
							]);
						}
					} else {
					//case add new
						if ($object_value['taking_method'] == 'caculator') {
							$object_value['taking_method'] = 'formula';
						}
					}

					$cell_data[]        = $this->general_cell_data(5, $object_key, $object_value['taking_method'],'',$f,false , true);
				}
			}

			//concat payslip template data with data fixed
			$payslip_template_data = array_merge($payslip_template_data, $this->payslip_template_data_fixed([], [], $columnlen, $rowlen));

			//add data null for cell
			if (count($payslip_columns) < $min_index_column) {
				for ($x = 0; $x <= $min_index_column - count($payslip_columns); $x++) {
					$data_row_name[] =  null;
				}
			}

			$data[] = $data_row_null;
			$data[] = $data_row_null;
			$data[] = $data_row_null;
			$data[] = $data_row_name;

			$payslip_template_data['celldata']    = $cell_data;
			$payslip_template_data['calcChain']    = $calcChain;

			for ($x = 0; $x <= 31; $x++) {
				$data[] = $data_row_null;
			}

			$payslip_template_data['data'] = $data;

			$payslip_template_data_update = [];
			$payslip_template_data_update_temp = json_decode(hr_payslip_replace_string(json_encode($payslip_template_data)));
			$payslip_template_data_update[] = $payslip_template_data_update_temp;
		

			$this->db->where('id', $id);
			$this->db->update(db_prefix().'hr_payslip_templates', [
				'payslip_template_data' => json_encode($payslip_template_data_update),
				'cell_data' => json_encode($cell_data)
			]);

			if ($this->db->affected_rows() > 0) {
				return true;
			} else {
				return false;
			}
		}

		return false;
	}

	/**
	 * [general_cell_data description]
	 * @param  [type] $cell_name [description]
	 * @return [type]            [description]
	 *      Cell data format
			
			*  {
			*       "m":"Hr_code",
			*       "ct":{"fa":"General","t":"g"},
			*       "v":"Hr_code"
			*   }
			* 
			*
	 */
	public function general_template_cell_data($cell_name)
	{
		$ct_data = [];
		$ct_data = [
			"fa"    => "General",
			"t"     => "g",
		];

		$cell_data = [];
		$cell_data = [
			"m"     => $cell_name,
			"ct"    => $ct_data,
			"v"     => $cell_name,
			"bg" 	=> '#fff000',
			"bl" 	=> 1,
			"fs" 	=> 12,
			"ht" 	=> 0,
			"vt" 	=> 0,
		];

		return $cell_data;
	}

	/**
	 * general cell data
	 * @param  [type] $row   
	 * @param  [type] $col   
	 * @param  [type] $value 
	 * @return [type]
	 * {"r":2,"c":0,"v":{"m":"Hr_code","ct":{"fa":"General","t":"g"},"v":"Hr_code"}}        
	 */
	public function general_cell_data($row, $col, $value, $t, $f, $luckysheet_header_format, $luckysheet_row_format, $luckysheet_company_format='false', $number_format ='')
	{	
		$cell_format=[];

		if ($t != '') {
			$t = 'g';
		}

		$ct_data = [];

		if ($number_format == 11) {
			$ht = 2;

			$ct_data = [
				"fa"    => '#,##0.00',
				"t"     => 'n',
			];
		} else {
			$ht = 1;

			$ct_data = [
				"fa"    => "General",
				"t"     => $t,
			];
		}

		$v_data = [];

		if ($f != '') {
			if ($luckysheet_row_format == true) {
				$v_data = [
					"m"     => $value,
					"ct"    => $ct_data,
					"v"     => $value,
					"f"		=> $f,
					"bl" 	=> 0,
					"fs" 	=> 11,
					"vt" 	=> 0,
					"ht"	=> $ht,
				];
			} else {
				$v_data = [
					"m"     => $value,
					"ct"    => $ct_data,
					"v"     => $value,
					"f"		=> $f
				];
			}
		} else {
			if ($luckysheet_header_format == true) {
				$v_data = [
					"m"     => $value,
					"ct"    => $ct_data,
					"v"     => $value,
					"bg" 	=> '#fff000',
					"bl" 	=> 1,
					"fs" 	=> 12,
					"ht" 	=> 0,
					"vt" 	=> 0,
					"tb"	=> 2,
				];
			} else if ($luckysheet_row_format == true) {
				$v_data = [
					"m"     => $value,
					"ct"    => $ct_data,
					"v"     => $value,
					"bl" 	=> 0,
					"fs" 	=> 11,
					"vt" 	=> 0,
					"ht"	=> $ht,

				];
			} else if ($luckysheet_company_format == true) {
				$v_data = [
					"m"     => $value,
					"ct"    => $ct_data,
					"v"     => $value,
					"fs" 	=> 17,
					"tb" 	=> 1,
					"bl" 	=> 1,

				];
			} else {
				$v_data = [
					"m"     => $value,
					"ct"    => $ct_data,
					"v"     => $value,
				];
			}			
		}

		$cell_data = [];
		$cell_data = [
			"r" => $row,
			"c" => $col,
			"v" => $v_data,
		];
		return $cell_data;
	}

	/**
	 * payslip template data fixed
	 * @param  string $value 
	 * @return [type]        
	 */
	public function payslip_template_data_fixed($visible_row =[], $visible_column = [], $columnlen =[], $rowlen=[])
	{   
		$payslip_template_data = [];

		$payslip_template_data['status']    = '1';
		$payslip_template_data['order']     = '0';
		$payslip_template_data['row']       = 36;
		$payslip_template_data['config']    = new stdClass();
		$payslip_template_data['config']->columnlen = $columnlen;
		$payslip_template_data['config']->rowlen = $rowlen;
		$payslip_template_data['index']    = 0;
		$payslip_template_data['load']    = '1';

		$visibledatarow = [];

		if (count($visible_row) > 0) {
			$visibledatarow = $visible_row;
		} else {
			$visibledatarow = [20,40,60,80,100,120,140,160,180,200,220,240,260,280,300,320,340,360,380,400,420,440,460,480,500,520,540,560,580,600,620,640,660,680,700,720];
		}

		$payslip_template_data['visibledatarow']    = $visibledatarow;
		$visibledatacolumn = [];

		if (count($visible_column) > 0) {
			$visibledatacolumn = $visible_column;
		} else {
			$visibledatacolumn = [74,148,222,296,370,444,518,592,666,740,814,888,962,1036,1110,1184,1258,1332,1406,1480,1554,1628,1702,1776,1850,1924];
		}
		$payslip_template_data['visibledatacolumn']    = $visibledatacolumn;
		$payslip_template_data['ch_width']    = 3009;
		$payslip_template_data['rh_height']    = 822;

		$luckysheet_select_save = [
			"left"          => 74,
			"width"         => 73,
			"top"           => 40,
			"height"        => 19,
			"left_move"     => 74,
			"width_move"    => 73,
			"top_move"      => 40,
			"height_move"   => 19,
			"row"           => array(0 => 3, 1 => 3),
			"column"        => array(0 => 1, 1 => 1),
			"row_focus"     => 2,
			"column_focus"  => 1,
		];
		$payslip_template_data['luckysheet_select_save']    = array(0 => $luckysheet_select_save);

		$luckysheet_selection_range =array();
		$payslip_template_data['luckysheet_selection_range']    = $luckysheet_selection_range;
		$payslip_template_data['zoomRatio']    = 1;

		return $payslip_template_data;
	}

	/**
	 * update payslip templates detail first
	 * @param  [type] $id 
	 * @return [type]  
	 *
	 * Update payslip template data when update column on Main management, ex: delete column
	 */
	public function update_payslip_templates_detail_first($old_column_formular, $id)
	{
		$result = $this->add_payslip_templates_detail_first($id, true, $old_column_formular);
		return $result;
	}

	/**
	 * check update payslip template detail
	 * @param  [type] $data 
	 * @param  [type] $id   
	 * @return [type]       
	 */
	public function check_update_payslip_template_detail($data, $id)
	{
		$payslip_template = $this->get_hr_payslip_templates($id);

		//get old column formula
		$old_cell_data = json_decode($payslip_template->cell_data);
		$array_cell_data = $this->array_cell_data($payslip_template);
		$old_column_formular = array_combine($array_cell_data['payroll_column_key'], $array_cell_data['payroll_formular']);

		if ($payslip_template) {
			$old_payslip_columns = explode(",", $payslip_template->payslip_columns);

			$diff = array_diff($old_payslip_columns, $data['payslip_columns']);
			$diff1 = array_diff($data['payslip_columns'], $old_payslip_columns);

			if (count($diff) > 0 || count($diff1) > 0) {
				return ['status' => true, 'old_column_formular' => $old_column_formular];
			}
			return ['status' => false];
		}

		return ['status' => false];
	}

	/**
	 * get staff info
	 * @param  [type] $staffid 
	 * @return [type]          
	 */
	public function get_staff_info($staffid) {
		$this->db->where('staffid', $staffid);
		$results = $this->db->get(db_prefix().'staff')->row();
		return $results;        
	}

	/**
	 * get staff departments
	 * @param  boolean $userid  
	 * @param  boolean $onlyids 
	 * @return [type]           
	 */
	public function get_staff_departments($userid = false, $onlyids = false)
	{
		if ($userid == false) {
			$userid = get_staff_user_id();
		}
		if ($onlyids == false) {
			$this->db->select();
		} else {
			$this->db->select(db_prefix() . 'staff_departments.departmentid');
		}
		$this->db->from(db_prefix() . 'staff_departments');
		$this->db->join(db_prefix() . 'departments', db_prefix() . 'staff_departments.departmentid = ' . db_prefix() . 'departments.departmentid', 'left');
		$this->db->where('staffid', $userid);
		$departments = $this->db->get()->result_array();
		if ($onlyids == true) {
			$departmentsid = [];
			foreach ($departments as $department) {
				array_push($departmentsid, $department['departmentid']);
			}

			return $departmentsid;
		}
		return $departments;
	}

	/**
	 * get all staff departments
	 * @return [type] 
	 */
	public function get_all_staff_departments()
	{
		$sql = "SELECT sdp.staffid, dp.name FROM ".db_prefix()."staff_departments as sdp
		left join ".db_prefix()."departments as dp on sdp.departmentid = dp.departmentid 
		left join ".db_prefix()."staff as s on sdp.staffid = s.staffid
		where s.active = 1
		order by sdp.staffid";

		$staff_departments = $this->db->query($sql)->result_array();

		$staff=[];
		foreach ($staff_departments as $value) {
			if (isset($staff[$value['staffid']])) {
				$staff[$value['staffid']] = $staff[$value['staffid']].', '.$value['name'];
			} else {
				$staff[$value['staffid']] = $value['name'];
			}
		}

		return $staff;
	}
	
	/**
	 * get bonus
	 * @param  [integer] $staffid 
	 * @param  [] $month   
	 * @return object        
	 */
	public function get_bonus_by_month($staffid, $month)
	{
		$this->db->where('staffid', $staffid);
		$this->db->where('month_bonus_kpi', $month);

	   	return $this->db->get(db_prefix() . 'hr_bonus_kpi')->row();
	}

	/**
	 * get bonus kpi
	 * @param  [type] $month 
	 * @return [type]        
	 */
	public function get_bonus_kpi($month, $where='')
	{
		$month = date('Y-m', strtotime($month ?? ''));
		if ($where != '') {
			$this->db->where($where);
		}
		$this->db->select('*, staffid as staff_id');
		$this->db->where('month_bonus_kpi', $month);

		$bonus_kpi = $this->db->get(db_prefix() . 'hr_bonus_kpi')->result_array();
		return $bonus_kpi;
	}

	/**
	 * get staff timekeeping applicable object
	 * @return [type] 
	 */
	public function get_staff_timekeeping_applicable_object($where = [])
	{
		$rel_type = hr_get_profile_status();

		$this->db->select('*,CONCAT(firstname," ",lastname) as full_name');
    	if ($rel_type == 'hr_records') {
    		$this->db->join(db_prefix() . 'hr_job_position', db_prefix() . 'staff.job_position = ' . db_prefix() . 'hr_job_position.position_id', 'left');
    	}
    	$this->db->where('active', 1);
    	$this->db->where($where);
    	$this->db->order_by('firstname', 'desc');
		$staffs = $this->db->get(db_prefix().'staff')->result_array();
		return $staffs; 
	}

	/**
	 * add bonus kpi
	 * @param [type] $data array
	 */
	public function add_bonus_kpi($data)
	{
		$data_bonus_kpi = str_replace(', ','|/\|',$data['bonus_kpi_value']);

		$data_data_bonus_kpi = explode( ',', $data_bonus_kpi);
		$results = 0;
		$results_update = '';
		$flag_empty = 0;

		$month_add_update = str_replace('/', '-', $data['allowance_commodity_fill_month']);
		
		foreach ($data_data_bonus_kpi as  $data_bonus_key => $data_bonus_value) {
			if ($data_bonus_value == '') {
				$data_bonus_value = 0;
			}
			if (($data_bonus_key+1)%6 == 0) {
				$arr_temp['bonus_kpi'] = hr_control_reformat_currency($data_bonus_value);

				//check add or update data
				$this->db->where('staffid', $arr_temp['staffid']);
				$this->db->where('month_bonus_kpi', $month_add_update);

				$staff_point_in_month = $this->db->get(db_prefix() . 'hr_bonus_kpi')->row();

				if ($staff_point_in_month) {
					//update
					$this->db->where('id', $staff_point_in_month->id);
					$this->db->update(db_prefix() . 'hr_bonus_kpi', $arr_temp);
					if ($this->db->affected_rows() > 0) {
						$results_update = true;
					}
				} else {
					//insert
					$this->db->insert(db_prefix().'hr_bonus_kpi', $arr_temp);
					$insert_id = $this->db->insert_id();
					if ($insert_id) {
						$results++;
					}
				}
				
				$arr_temp = [];
			} else {
				switch (($data_bonus_key+1)%6) {
					case 1:
					 	$arr_temp['staffid'] = str_replace('|/\|',', ',$data_bonus_value);
						break;										 
				}

				$arr_temp['month_bonus_kpi'] = $month_add_update;
			}
		}
		
		if ($results > 0 || $results_update == true) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * getStaff
	 * @param  string $id    
	 * @param  array  $where 
	 * @return [type]        
	 */
	public function getStaff($id = '', $where = [])
	{
		$select_str = '*,CONCAT(firstname," ",lastname) as full_name';

		// Used to prevent multiple queries on logged in staff to check the total unread notifications in core/AdminController.php
		if (is_staff_logged_in() && $id != '' && $id == get_staff_user_id()) {
			$select_str .= ',(SELECT COUNT(*) FROM ' . db_prefix() . 'notifications WHERE touserid=' . get_staff_user_id() . ' and isread=0) as total_unread_notifications, (SELECT COUNT(*) FROM ' . db_prefix() . 'todos WHERE finished=0 AND staffid=' . get_staff_user_id() . ') as total_unfinished_todos';
		}

		$this->db->select($select_str);
		$this->db->where($where);

		if (is_numeric($id)) {
			$this->db->where('staffid', $id);
			$staff = $this->db->get(db_prefix() . 'staff')->row();

			if ($staff) {
				$this->load->model('staff/staff_model');
				$staff->permissions = $this->staff_model->get_staff_permissions($id);
			}

			return $staff;
		}
		
		$this->db->order_by('firstname', 'desc');

		return $this->db->get(db_prefix() . 'staff')->result_array();
	}

	/**
	 * payslip template get staffid
	 * @param  [type] $departemnt_ids 
	 * @param  [type] $role_ids       
	 * @param  [type] $staff_ids      
	 * @return [type]                
	 */
	public function payslip_template_get_staffid($department_ids, $role_ids, $staff_ids, $except_staff='')
	{
		if (strlen($staff_ids ?? '') > 0) {
			if ( strlen($except_staff ?? '') > 0) {
				$array_except_staff = explode(",", $except_staff);
				$array_staff_ids = explode(",", $except_staff);

				$new_staff_ids=[];
				foreach ($array_staff_ids as $value) {
				    if (!in_array($value, $array_except_staff)) {
				    	$new_staff_ids[] = $value;
				    }
				}

				if (count($new_staff_ids) > 0) {
					return implode(",", $new_staff_ids);
				}
				return '';
			} else {
				return $staff_ids;
			}
		}	
		
		$department_querystring='';
		$role_querystring='';
		$except_staff_querystring='';

		if (strlen($department_ids ?? '') > 0) {
			$arrdepartment = $this->staff_model->get('', 'staffid in (select '.db_prefix().'staff_departments.staffid from '.db_prefix().'staff_departments where departmentid IN( '.$department_ids.'))');
			$temp = '';
			foreach ($arrdepartment as $value) {
				$temp = $temp.$value['staffid'].',';
			}
			$temp = rtrim($temp,",");
			$department_querystring = 'FIND_IN_SET(staffid, "'.$temp.'")';
		}

		if ( strlen($role_ids ?? '') > 0) {
			$role_querystring = 'FIND_IN_SET(role, "'.$role_ids.'")';
		}

		if ( strlen($except_staff ?? '') > 0) {
			$except_staff_querystring = 'staffid NOT IN ('.$except_staff .')' ;
		}

		$arrQuery = array($department_querystring, $role_querystring, $except_staff_querystring);

		$newquerystring = '';
		foreach ($arrQuery as $string) {
			if ($string != '') {
				$newquerystring = $newquerystring.$string.' AND ';
			}            
		}  

		$newquerystring=rtrim($newquerystring,"AND ");
		if ($newquerystring == '') {
			$newquerystring = [];
		}
		$staffs = $this->get_staff_timekeeping_applicable_object($newquerystring);
		$staff_ids=[];
		foreach ($staffs as $key => $value) {
		    $staff_ids[] = $value['staffid'];
		}

		if (count($staff_ids) > 0) {
			return implode(',', $staff_ids);
		}
		return false;
	}

	/**
	 * add payslip
	 * @param [type] $data 
	 */
	public function add_payslip($data)
	{   	
		$staff_departments = $this->get_all_staff_departments();
		$render_income_tax_formular = $this->render_income_tax_formular('AX');
		$number_to_anphabe = hr_payslip_number_to_anphabe();
		$payroll_templates = $this->get_hr_payslip_templates($data['payslip_template_id']);
		$staffids = $this->payslip_template_get_staffid($payroll_templates->department_id, $payroll_templates->role_employees, $payroll_templates->staff_employees, $payroll_templates->except_staff);

		$str_sql1 = 'staffid  IN (0)';
		$str_sql = 'staff_id  IN (0)';
		if ($staffids != false) {
			$str_sql1 = 'staffid  IN ('.$staffids .')';
			$str_sql = 'staff_id  IN ('.$staffids .')';
		}

		$hr_profile_status = hr_get_profile_status();

		//get_staff based on payslip template ( staff_id)
		
		//staff information
		$staffs=[];
		$attendances=[];
		$employees_data=[];

		$payslip_month = date('Y-m-d',strtotime($data['payslip_month'].'-01' ?? ''));

		if ($str_sql1 == '') {
			$staffs = [];
		} else {
			$staffs = $this->get_staff_timekeeping_applicable_object($str_sql1);
		}

		$staffs_id=[];
		foreach ($staffs as $staff_key => $staff_value) {
			$staff_value['employee_name'] = $staff_value['firstname'].' '.$staff_value['lastname'];
			$staff_value['payment_run_date'] = _d(date('Y-m-d'));

			if (isset($staff_departments[$staff_value['staffid']])) {
				$staff_value['dept_name'] = $staff_departments[$staff_value['staffid']];
			} else {
				$staff_value['dept_name'] = '';
			}

			if ($hr_profile_status == 'hr_records') {
				$staff_value['employee_number'] = $staff_value['staff_identifi'];
				$staff_value['job_title'] = $staff_value['position_name'];
				$staff_value['income_tax_number'] = $staff_value['Personal_tax_code'];
				$staff_value['residential_address'] = $staff_value['resident'];
			} else {
				$staff_value['employee_number'] = $this->hr_format_code('EXS', $staff_value['staffid'], 5);
			}

			$staff_value['pay_slip_number'] = $this->hr_format_code('PS_'.date('Y-m', strtotime($data['payslip_month'] ?? '')).'_', $staff_value['staffid'], 3);

			$staff_value['staff_id'] = $staff_value['staffid'];
		    $staffs_id[$staff_value['staffid']] = $staff_value;
		}

		//get attendance by month
		$hr_attendance = $this->get_hr_attendance($payslip_month, $str_sql);
		foreach ($hr_attendance as $attendance_key => $attendance_value) {
		    $attendances[$attendance_value['staff_id']] = $attendance_value;

		    if (isset($staffs_id[$attendance_value['staff_id']])) {
		    	$staffs_id[$attendance_value['staff_id']] = array_merge($staffs_id[$attendance_value['staff_id']], $attendance_value);
		    } else {
		    	$staffs_id[$attendance_value['staff_id']] = $attendance_value;
		    }
		}

		//get imcome tax rebate from setting
		$income_tax_rebates = $this->get_income_tax_rebates();
		$ic_rebates =[];
		foreach ($income_tax_rebates as $rebates_key => $rebates_value) {
		    $ic_rebates[$rebates_value['code']] = $rebates_value['total'];
		}

		//get employees data
		$get_employees_data = $this->get_employees_data($payslip_month,'', $str_sql);
		foreach ($get_employees_data as $employee_key => $employee_value) {
			$employee_value['it_rebate_code'] = $employee_value['income_rebate_code'];
			$employee_value['income_tax_code'] = $employee_value['income_tax_rate'];
			$employee_value['bank_name'] = $employee_value['bank_name'];
			$employee_value['account_number'] = $employee_value['account_number'];

			if (isset($ic_rebates[$employee_value['income_rebate_code']])) {
				$employee_value['it_rebate_value'] = $ic_rebates[$employee_value['income_rebate_code']];
			}

		    $employees_data[$employee_value['staff_id']] = $employee_value;

		    if (isset($staffs_id[$employee_value['staff_id']])) {
		    	$staffs_id[$employee_value['staff_id']] = array_merge($staffs_id[$employee_value['staff_id']], $employee_value);
		    } else {
		    	$staffs_id[$employee_value['staff_id']] = $employee_value;
		    }
		}

		//get salary deduction
    	$deductions_data = $this->get_deductions_data($payslip_month, $str_sql);
    	foreach ($deductions_data as $deduction_key => $deduction_value) {
    		$deductions_value[$deduction_value['staff_id']] = $deduction_value;

    		if (isset($staffs_id[$deduction_value['staff_id']])) {
		    	$staffs_id[$deduction_value['staff_id']] = array_merge($staffs_id[$deduction_value['staff_id']], $deduction_value);
		    } else {
		    	$staffs_id[$deduction_value['staff_id']] = $deduction_value;
		    }
    	}

    	//get commission
    	$commissions_data = $this->get_commissions_data($payslip_month, $str_sql);
    	foreach ($commissions_data as $commission_key => $commission_value) {
    	    $commissions_value[$commission_value['staff_id']] = $commission_value;

    		if (isset($staffs_id[$commission_value['staff_id']])) {
		    	$staffs_id[$commission_value['staff_id']] = array_merge($staffs_id[$commission_value['staff_id']], $commission_value);
		    } else {
		    	$staffs_id[$commission_value['staff_id']] = $commission_value;
		    }
    	}

    	//get bonus kpi
    	$bonus_kpi_data = $this->get_bonus_kpi($payslip_month, $str_sql1);
    	foreach ($bonus_kpi_data as $bonus_kpi_key => $bonus_kpi_value) {
    	    $bonus_kpis_value[$bonus_kpi_value['staff_id']] = $bonus_kpi_value;

    		if (isset($staffs_id[$bonus_kpi_value['staff_id']])) {
		    	$staffs_id[$bonus_kpi_value['staff_id']] = array_merge($staffs_id[$bonus_kpi_value['staff_id']], $bonus_kpi_value);
		    } else {
		    	$staffs_id[$bonus_kpi_value['staff_id']] = $bonus_kpi_value;
		    }
    	}

    	//get insurance data
    	$insurances_data = $this->get_insurances_data($payslip_month, $str_sql);
    	foreach ($insurances_data as $insurance_key => $insurance_value) {
    		$insurances_value[$insurance_value['staff_id']] = $insurance_value;

    		if (isset($staffs_id[$insurance_value['staff_id']])) {
		    	$staffs_id[$insurance_value['staff_id']] = array_merge($staffs_id[$insurance_value['staff_id']], $insurance_value);
		    } else {
		    	$staffs_id[$insurance_value['staff_id']] = $insurance_value;
		    }
    	}

    	//get salary deduction from setting
    	$get_salary_deductions_list_setting = $this->get_salary_deductions_list();

    	$salary_deductions_list_setting=[];
    	foreach ($get_salary_deductions_list_setting as $sl_key =>  $sl_value) {
    	    $salary_deductions_list_setting['deduction_'.$sl_value['id']] = $sl_value['basis'];
    	}

    	//get insurance data from setting
    	$get_insurance_list_setting = $this->get_insurance_list();

    	$insurance_list_setting=[];
    	foreach ($get_insurance_list_setting as $sl_key =>  $sl_value) {
    	    $insurance_list_setting['st_insurance_'.$sl_value['id']] = $sl_value['basis'];
    	}

    	//get salary by task
    	$salary_by_tasks = $this->get_tasks_timer_by_month($payslip_month, $str_sql, $str_sql1, $hr_profile_status);
    	foreach ($salary_by_tasks as $staff_id_key => $task_value) {
    		if (isset($staffs_id[$staff_id_key])) {
		    	$staffs_id[$staff_id_key] = array_merge($staffs_id[$staff_id_key], $task_value);
		    } else {
		    	$staffs_id[$staff_id_key] = $task_value;
		    }
    	}

		$array_cell_data = $this->array_cell_data($payroll_templates);
		//array payroll column name, array payroll column key, array payroll formular
		$payroll_column_name = $array_cell_data['payroll_column_name'];
		$payroll_column_key =  $array_cell_data['payroll_column_key'];
		$payroll_formular = $array_cell_data['payroll_formular'];


		//get formular with related key
		$payroll_formular = array_slice($payroll_formular, 0, count($payroll_column_key));
		$payroll_column_name = array_slice($payroll_column_name, 0, count($payroll_column_key));

		$payroll_key_formular = array_combine($payroll_column_key, $payroll_formular);
		$payroll_column_key_name = array_combine($payroll_column_key, $payroll_column_name);

		$payroll_system_columns = payroll_system_columns();
		$payroll_system_columns_dont_format = payroll_system_columns_dont_format();

		//get header, row format
		$luckysheet_header_format = luckysheet_header_format();
		$luckysheet_row_format = luckysheet_row_format();

		$payslip_cell_data =[];
		$staff_row = 5;

		$row_value_temp = 160;
		$column_value_temp = 191;
		$visibledatarow = [40,80,120,160];
		$visibledatacolumn = [];
		$columnlen=[];
		$rowlen=[];
		$calcChain=[];

		// set company logo
		$payslip_cell_data[] = $this->general_cell_data(1, 4, get_option('companyname') , $t='g', $f ='', false, false, true);
		$payslip_cell_data[] = $this->general_cell_data(2, 4, _l('payroll_in_month').$data['payslip_month'] , $t='g', $f ='', false, true, false);

		if (count($staffs_id) > 0) {
			foreach ($staffs_id as $staff_id => $staff_value ) {
				$col = 0;
				foreach ($payroll_key_formular as $payroll_key  => $payroll_formular) {
					//get gross pay key 
					if ($payroll_key == 'gross_pay') {
						$gross_pay_index = $col;
					}

					//get taxable salary
					if ($payroll_key == 'taxable_salary') {
						$taxable_salary_index = $col;
					}

					// write header
					if ($staff_row == 5) {
						$payslip_cell_data[] = $this->general_cell_data($staff_row-2, $col, $payroll_column_key_name[$payroll_key], $t='g', $f ='', true, false);
						$payslip_cell_data[] = $this->general_cell_data($staff_row-1, $col, $payroll_key, $t='g', $f ='', false, true);

						$column_value_temp = $column_value_temp + 191;
						$visibledatacolumn[] = $column_value_temp;
						$columnlen[] = 183;
						$rowlen[$staff_row-2] = 46;
					}

					// check if key in system column, st1: salary type of (CT1: like Probationary contracts) , al1: allowance type (CT1: like Probationary contracts), st2: salary type of (CT2: like formal contracts) , al2: allowance type (CT2: like formal contracts), 
					// earning1_: salary or allowance type of (CT1: like Probationary contracts)
					// earning2_: salary or allowance type of (CT2: like Probationary contracts)
					// deduction_: salary deduction
					if (in_array($payroll_key, $payroll_system_columns) || preg_match('/^st1_/', $payroll_key) || preg_match('/^al1_/', $payroll_key) ||preg_match('/^st2_/', $payroll_key) || preg_match('/^al2_/', $payroll_key) || preg_match('/^earning1_/', $payroll_key) || preg_match('/^earning2_/', $payroll_key) || preg_match('/^deduction_/', $payroll_key) || preg_match('/^st_insurance_/', $payroll_key)  ) {
						if (preg_match('/^deduction_/', $payroll_key)) {
							$value= isset($staff_value[$payroll_key]) ? $staff_value[$payroll_key] : 0 ;

							if ($salary_deductions_list_setting[$payroll_key] == "gross") {
								if (isset($gross_pay_index)) {
			    					//6 is formular is row 6
									$payroll_formular = "=".$number_to_anphabe[$gross_pay_index]."6*".$value."/100";
								}
							} else if (preg_match('/^st_/', $salary_deductions_list_setting[$payroll_key]) || preg_match('/^al_/', $salary_deductions_list_setting[$payroll_key]) || preg_match('/^earning_/', $salary_deductions_list_setting[$payroll_key])) {

								$salary_deductions_list_setting[$payroll_key];
								$deduction_explode = explode("_", $salary_deductions_list_setting[$payroll_key]);
								$deduction_prefix = $deduction_explode[0];
								$deduction_salary_id = $deduction_explode[1];

								$probationary_value = 0; 
								$formal_value = 0;
								$average_number = 0;

								if (isset($staff_value[$deduction_prefix.'1_'.$deduction_salary_id])) {
									$probationary_value = $staff_value[$deduction_prefix.'1_'.$deduction_salary_id]; 

									if ((float)$staff_value[$deduction_prefix.'1_'.$deduction_salary_id] > 0) {
										$average_number ++;
									}
								}

								if (isset($staff_value[$deduction_prefix.'2_'.$deduction_salary_id])) {
									$formal_value = $staff_value[$deduction_prefix.'2_'.$deduction_salary_id]; 

									if ((float)$staff_value[$deduction_prefix.'2_'.$deduction_salary_id] > 0) {
										$average_number ++;
									}
								}

								if ($average_number > 0) {
									$payroll_formular = "=".((float)$probationary_value + (float)$formal_value)/$average_number*($value/100);
								} else {
									$payroll_formular = "=0";
								}
							}
						} else if (preg_match('/^st_insurance_/', $payroll_key)) {
							$value= isset($staff_value[$payroll_key]) ? $staff_value[$payroll_key] : 0 ;

							if ($insurance_list_setting[$payroll_key] == "gross") {
								if (isset($gross_pay_index)) {
			    					//6 is formular is row 6
									$payroll_formular = "=".$number_to_anphabe[$gross_pay_index]."6*".$value."/100";
								}
							}
						} else if ($payroll_key == 'income_tax_paye') {
							if (isset($taxable_salary_index)) {
								if (isset($staff_value['income_tax_code']) && $staff_value['income_tax_code'] == 'A') {
									$taxable_salary_formular = str_replace('AX', $number_to_anphabe[$taxable_salary_index]."6", $render_income_tax_formular);
		    						//6 is formular is row 6
		    						if ($taxable_salary_formular != '') {
		    							$payroll_formular = "=".$taxable_salary_formular;
		    						} else {
		    							$payroll_formular =0;
		    							$value=0;
		    						}
								} else {
									$payroll_formular =0;
									$value=0;
								}
							}
						} else {
							$value= isset($staff_value[$payroll_key]) ? $staff_value[$payroll_key] : 0 ;
						}

						$t='g';
					} else {
						$value='';
						$t='n';
					}

					if ($payroll_formular != '0') {
						$f = str_replace('6', $staff_row+1, $payroll_formular);

			    		//calcChain: Formula chain, used when the cell linked by the formula is changed, all formulas referencing this cell will be refreshed.
						array_push($calcChain, [
							"r" => $staff_row,
							"c" => $col,
							"index" => 0,
							"color" => "b",
							"parent" => null,
							"chidren" => new stdClass(),
							"times" => 1
						]);
					} else {
						$f = '';
					}

			    	// start row 5
					if (!in_array($payroll_key, $payroll_system_columns_dont_format)) {
						$payslip_cell_data[] = $this->general_cell_data($staff_row, $col, $value, $t, $f, false , true,'', true);
					} else {
						$payslip_cell_data[] = $this->general_cell_data($staff_row, $col, $value, $t, $f, false , true,'', false);
					}

					$col++;
				}

				$row_value_temp = $row_value_temp + 40;
				$visibledatarow[] = $row_value_temp;
				$rowlen[$staff_row] = 25;

				$staff_row++;
			}
		} else {
			$payslip_cell_data[] = $this->general_cell_data(5, 4, _l('no_eligible_employee_was_found_for_this_payslip_template'), $t='g', $f ='', false, false, true);
		}

		$payslip_template_data['name']      = $data['payslip_name'];
		//concat payslip template data with data fixed
		$payslip_template_data = array_merge($payslip_template_data, $this->payslip_template_data_fixed($visibledatarow, $visibledatacolumn, $columnlen, $rowlen));
		//column A to Z
		$min_index_column = 25 < count($payroll_key_formular) ? count($payroll_key_formular) : 25;
		$payslip_template_data['column']    = $min_index_column;

		$payslip_template_data['celldata']    = $payslip_cell_data;
		$payslip_template_data['data']    = [];
		
		$payslip_template_data['calcChain']    = $calcChain;
		$payslip_template_data['defaultRowHeight']    = 19;
		$payslip_template_data['defaultColWidth']    = 73;

		$payslip_data[] = $payslip_template_data;

		$this->db->insert(db_prefix().'hr_payslips', [
			'payslip_name' => $data['payslip_name'],
			'payslip_month' => $payslip_month,
			'payslip_template_id' => $data['payslip_template_id'],
			'staff_id_created' => get_staff_user_id(),
			'date_created' => date('Y-m-d H:i:s'),			
		]);

		$insert_id = $this->db->insert_id();

		if ($insert_id) {
			//insert payslip file
			$this->add_payslip_file($insert_id, json_encode($payslip_data), $data['payslip_name']);
			return $insert_id;
		}
		return false;
	}

	/**
	 * add payslip file
	 * @param [type] $data 
	 */
	public function add_payslip_file($insert_id, $data, $payslip_name)
	{
		$path = HR_CONTROL_PAYSLIP_FOLDER . $insert_id . '-'.$payslip_name.'.txt';
		$realpath_data = $insert_id . '-'.$payslip_name.'.txt';
		hr_file_force_contents($path, $data);

		$this->db->where('id', $insert_id);
		$this->db->update(db_prefix() . 'hr_payslips', ['file_name' => $realpath_data]);

		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * update payslip
	 * @param  [type] $data 
	 * @param  [type] $id   
	 * @return [type]       
	 */
	public function update_payslip($data, $id)
	{ 
		$affected_rows = 0;
		$payslip = $this->hr_control_model->get_hr_payslip($id);
		unlink(HR_CONTROL_PAYSLIP_FILE.$payslip->file_name);
		
		if (isset($data['image_flag'])) {
			if ($data['image_flag'] == "true") {
				$data['payslip_data'] = str_replace('[removed]', 'data:image/png;base64,', $data['payslip_data']); 
				$data['payslip_data'] = str_replace('imga$imga', '"', $data['payslip_data']); 
				$data['payslip_data'] = str_replace('""', '"', $data['payslip_data']); 
			}
		}

		$data['payslip_data'] = hr_payslip_replace_string($data['payslip_data']);
		$payslip_data = $data['payslip_data'];
		$payslip_name = $data['name'];

		$data['payslip_name'] = $data['name']; 
		unset($data['name']);
		unset($data['image_flag']);
		unset($data['payslip_data']);

		$this->db->where('id', $id);
		$this->db->update(db_prefix().'hr_payslips', $data);
		
		$add_payslip_file = $this->add_payslip_file($id, $payslip_data, $payslip_name);

		return true;
	}

	/**
	 * payslip_close
	 * @param  [type] $data  
	 * @param  [type] $month 
	 * @return [type]        
	 */
	public function payslip_close($data)
	{	
		$start = microtime(true);

		$affectedRows = 0;
		$payroll_column_key=[];
		$payroll_column_value=[];

		$payroll_system_columns = payroll_system_columns();
		$hr_payslip = $this->get_hr_payslip($data['id']);
		if ($hr_payslip) {
			$month = $hr_payslip->payslip_month;
		} else {
			$month = date('Y-m-d');
		}

		$payslip_data_decode = json_decode($data['payslip_data']);
		if (isset($payslip_data_decode[0])) {
			if (isset($payslip_data_decode[0]->celldata)) {
				$payslip_data = $payslip_data_decode[0]->celldata;
			}
		}

		if (isset($payslip_data)) {
			foreach ($payslip_data as $key => $value) {
				//column key from row 4
				if ($value->r == 4) {
					$payroll_column_key[] = isset($value->v->m) ? $value->v->m : '';
				}

				//column value
				if ($value->r > 4) {
					$payroll_column_value[$value->r][] = isset($value->v->m) ? hr_reformat_currency($value->v->m) : 0;
				}
			}
		}

		$payslip_detail=[];
		$income_taxs=[];
		$staff_ids=[];
		//add key: payslip_id, month to payroll column key
		array_unshift($payroll_column_key, "payslip_id", "month"); 
		if (count($payroll_column_value) > 0) {
			foreach ($payroll_column_value as $key => $value) {
			    array_unshift($value, $data['id'], $month);

			    if (count($payroll_column_key) != count($value)) {
			    	return false;
			    }
			    $check_array_combine = array_combine($payroll_column_key, $value);

			    $payslip_detail[] = array_combine($payroll_column_key, $value);
			}
		}
		foreach ($payslip_detail as $key => $value) {
			$payslip_json_data=[];
			foreach ($value as $payroll_key => $payroll_value) {
				if ($payroll_key == 'payment_run_date') {
					$payslip_detail[$key][$payroll_key] = to_sql_date($payroll_value);
				}

			    if (!in_array($payroll_key, $payroll_system_columns) && $payroll_key != 'payslip_id' && $payroll_key != 'month') {
			    	$payslip_json_data[$payroll_key] = $payroll_value;
			    	unset($payslip_detail[$key][$payroll_key]);
			    }
			}
			if (isset($payslip_detail[$key]['bank_name'])) {
				unset($payslip_detail[$key]['bank_name']);
			}
			if (isset($payslip_detail[$key]['account_number'])) {
				unset($payslip_detail[$key]['account_number']);
			}
			
			$payslip_detail[$key]['json_data'] =  json_encode($payslip_json_data);

			$income_taxs[$key]['staff_id'] = isset($value['staff_id']) ? $value['staff_id'] : 0;
			$income_taxs[$key]['month'] = isset($value['month']) ? $value['month'] : null;
			$income_taxs[$key]['income_tax'] = isset($value['income_tax_paye']) ? $value['income_tax_paye'] : 0;
			$income_taxs[$key]['payslip_id'] = $data['id'];
			$staff_ids[] = $value['staff_id'];
		}
					
		if (count($payslip_detail) != 0) {
			//udpate payslip status
			$update_result = $this->update_payslip_status($data['id'], 'payslip_closing');
			if ($update_result == true) {
				$affectedRows++;
			}

			//delete mass paylip detail before update
			$this->db->where('payslip_id', $data['id']);
			$this->db->where('month', $month);
			$this->db->delete(db_prefix().'hr_payslip_details');

			//delete mass hr_income_taxs before update
			$this->db->where('staff_id IN ('.implode(",",$staff_ids) .') ');
			$this->db->where('month', $month);
			$this->db->delete(db_prefix().'hr_income_taxs');

			$affected_rows = $this->db->insert_batch(db_prefix().'hr_payslip_details', $payslip_detail);
			if ($affected_rows > 0) {
				$affectedRows++;
			}

			$affected_rows = $this->db->insert_batch(db_prefix().'hr_income_taxs', $income_taxs);
			if ($affected_rows > 0) {
				$affectedRows++;
			}
		}
		if ($affectedRows > 0) {
			return true;
		}

		return false;
	}

	/**
	 * update payslip status
	 * @param  [type] $id     
	 * @param  [type] $status 
	 * @return [type]         
	 */
	public function update_payslip_status($id, $status)
	{	
	    $this->db->where('id', $id);
	    $this->db->update(db_prefix().'hr_payslips', ['payslip_status' => $status]);
	    if ($this->db->affected_rows() > 0) {
	    	return true;
	    }
	    return false;
	}

	/**
	 * render personal income tax
	 * @param  [type] $PARAMETERS 
	 * @return [type]             
	 */
	public function render_personal_income_tax($PARAMETERS)
	{
	}

	/**
	 * get payslip detail
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function get_payslip_detail($id = false)
	{
	    if (is_numeric($id)) {
            return $this->db->get_where(db_prefix() . 'hr_payslip_details', ['id' => $id])->row();
        }
        return $this->db->get(db_prefix() . 'hr_payslip_details')->result_array();
	}

	/**
	 * get income summary report
	 * @param  [type] $sql_where 
	 * @return [type]            
	 */
	public function get_income_summary_report($sql_where='')
	{
		$this->db->select(db_prefix().'hr_payslip_details.staff_id, pay_slip_number, employee_number, employee_name,  month, net_pay, ' . db_prefix() . 'hr_payslips.payslip_status,'.db_prefix().'hr_payslip_details.employee_number');
		$this->db->join(db_prefix() . 'hr_payslips', db_prefix() . 'hr_payslip_details.payslip_id = ' . db_prefix() . 'hr_payslips.id', 'left');
		if ($sql_where != '') {
			$this->db->where($sql_where);
		}
		$this->db->order_by('staff_id', 'desc');
		$payslip_details = $this->db->get(db_prefix() . 'hr_payslip_details')->result_array(); 

		$staff_income=[];
		foreach ($payslip_details as $key => $value) {
			$get_month = date("m", strtotime($value['month'] ?? ''));

			$staff_income[$value['staff_id']]['pay_slip_number'] = $value['pay_slip_number'];
			$staff_income[$value['staff_id']]['employee_name'] = $value['employee_name'];
			$staff_income[$value['staff_id']][$get_month] = $value['net_pay'];
			if (isset($staff_income[$value['staff_id']]['average_income'])) {
				$staff_income[$value['staff_id']]['average_income'] += (float)$value['net_pay'];
			} else {
				$staff_income[$value['staff_id']]['average_income'] = (float)$value['net_pay'];
			}
		}

		return $staff_income;
	}

	/**
	 * get insurance summary report
	 * @param  string $sql_where 
	 * @return [type]            
	 */
	public function get_insurance_summary_report($sql_where='')
	{
		$this->db->select(db_prefix().'hr_payslip_details.staff_id, sum(total_insurance) as total_insurance');
		$this->db->join(db_prefix() . 'hr_payslips', db_prefix() . 'hr_payslip_details.payslip_id = ' . db_prefix() . 'hr_payslips.id', 'left');
		if ($sql_where != '') {
			$this->db->where($sql_where);
		}
		$this->db->group_by(db_prefix().'hr_payslip_details.staff_id');
		$this->db->order_by(db_prefix().'hr_payslip_details.staff_id', 'desc');
		$payslip_details = $this->db->get(db_prefix() . 'hr_payslip_details')->result_array(); 

		$staff_insurance=[];
		foreach ($payslip_details as $key => $value) {
			$staff_insurance[$value['staff_id']] = $value['total_insurance'];
		}

		return $staff_insurance;
	}

	/**
	 * get staff in deparment
	 * @param  [type] $department_id 
	 * @return [type]                
	 */
	public function get_staff_in_deparment($department_id)
	{
		$data = [];
		$sql = 'select 
		departmentid 
		from    (select * from '.db_prefix().'departments
		order by '.db_prefix().'departments.parent_id, '.db_prefix().'departments.departmentid) departments_sorted,
		(select @pv := '.$department_id.') initialisation
		where   find_in_set(parent_id, @pv)
		and     length(@pv := concat(@pv, ",", departmentid)) OR departmentid = '.$department_id.'';
		$result_arr = $this->db->query($sql)->result_array();
		foreach ($result_arr as $key => $value) {
			$data[$key] = $value['departmentid'];
		}

		if (count($data) > 0) {
			$sql_where = db_prefix().'staff.staffid IN (SELECT staffid FROM '.db_prefix().'staff_departments WHERE departmentid IN (' . implode(', ', $data) . '))';
			$staffs = $this->get_staff_timekeeping_applicable_object($sql_where);

			$staff_id=[];
			foreach ($staffs as $key => $value) {
				$staff_id[] = $value['staffid'];
			}

			return $staff_id;
		}
		return [];
	}

	/**
	 * payslip chart
	 * @return [type] 
	 */
	public function payslip_chart($filter_by_year = '', $staff_id='')
	{
		$months_report = $this->input->post('months_report');
		$custom_date_select = '';

		if ($filter_by_year != '') {
			$filter_by_year = $filter_by_year;
		} else {
			$filter_by_year = date('Y');
		}

		if ($staff_id != '') {
			$staff_id = $staff_id;
		} else {
			$staff_id = get_staff_user_id();
		}
		

		$this->db->select(db_prefix().'hr_payslip_details.staff_id, pay_slip_number, employee_number, employee_name,  month, net_pay, ' . db_prefix() . 'hr_payslips.payslip_status, total_insurance, income_tax_paye, total_deductions');
		$this->db->join(db_prefix() . 'hr_payslips', db_prefix() . 'hr_payslip_details.payslip_id = ' . db_prefix() . 'hr_payslips.id', 'left');
		$this->db->where($filter_by_year.' AND '.db_prefix().'hr_payslip_details.staff_id = '.$staff_id.' AND payslip_status = "payslip_closing"');
		$this->db->order_by('staff_id', 'desc');
		$payslip_details = $this->db->get(db_prefix() . 'hr_payslip_details')->result_array(); 

		$staff_income=[];
		foreach ($payslip_details as $key => $value) {
			$get_month = date("m", strtotime($value['month'] ?? ''));

			$staff_income[$value['staff_id']]['pay_slip_number'] = $value['pay_slip_number'];
			$staff_income[$value['staff_id']]['employee_name'] = $value['employee_name'];			

			if (isset($staff_income[$value['staff_id']]['total_insurance'])) {
				$staff_income[$value['staff_id']][$get_month]['total_insurance'] += (float)$value['total_insurance'];
			} else {
				$staff_income[$value['staff_id']][$get_month]['total_insurance'] = (float)$value['total_insurance'];
			}

			if (isset($staff_income[$value['staff_id']]['income_tax_paye'])) {
				$staff_income[$value['staff_id']][$get_month]['income_tax_paye'] += (float)$value['income_tax_paye'];
			} else {
				$staff_income[$value['staff_id']][$get_month]['income_tax_paye'] = (float)$value['income_tax_paye'];
			}

			if (isset($staff_income[$value['staff_id']]['total_deductions'])) {
				$staff_income[$value['staff_id']][$get_month]['total_deductions'] += (float)$value['total_deductions'];
			} else {
				$staff_income[$value['staff_id']][$get_month]['total_deductions'] = (float)$value['total_deductions'];
			}

			if (isset($staff_income[$value['staff_id']]['net_pay'])) {
				$staff_income[$value['staff_id']][$get_month]['net_pay'] += (float)$value['net_pay'];
			} else {
				$staff_income[$value['staff_id']][$get_month]['net_pay'] = (float)$value['net_pay'];
			}
		}

		for($_month = 1 ; $_month <= 12; $_month++) {
			$month_t = date('m',mktime(0, 0, 0, $_month, 04, 2016));

			if ($_month == 5) {
				$chart['categories'][] = _l('month_05');
			} else {
				$chart['categories'][] = _l('month_'.$_month);
			}

			if (isset($staff_income[$staff_id][$month_t])) {
				$chart['hr_staff_insurance'][] = isset($staff_income[$staff_id][$month_t]['total_insurance']) ? $staff_income[$staff_id][$month_t]['total_insurance'] : 0;
				$chart['hr_staff_income_tax'][] = isset($staff_income[$staff_id][$month_t]['income_tax_paye']) ? $staff_income[$staff_id][$month_t]['income_tax_paye'] : 0;
				$chart['hr_staff_deduction'][] = isset($staff_income[$staff_id][$month_t]['total_deductions']) ? $staff_income[$staff_id][$month_t]['total_deductions'] : 0;
				$chart['hr_staff_net_pay'][] = isset($staff_income[$staff_id][$month_t]['net_pay']) ? $staff_income[$staff_id][$month_t]['net_pay'] : 0;

			} else {
				$chart['hr_staff_insurance'][] = 0;
				$chart['hr_staff_income_tax'][] = 0;
				$chart['hr_staff_deduction'][] = 0;
				$chart['hr_staff_net_pay'][] = 0;
			}
		}

		return $chart;
	}	

	/**
	 * render income tax formular
	 * @param  [type] $taxable_salary 
	 * @return [type]                 
	 */
	public function render_income_tax_formular($taxable_salary)
	{	
		$formular='';
		$income_tax_formular='';
		$formular_close='';
	    //get icome tax rate
		$income_tax_rate = $this->get_income_tax_rate();

		foreach ($income_tax_rate as $key => $value) {
			$formular_close .=')';

			if (strlen($income_tax_formular ?? '') == 0) {
				$income_tax_formular .='if ('.$taxable_salary.'<='.$value['tax_bracket_value_to'].',(('.$taxable_salary.'-'.$value['tax_bracket_value_from'].')*'.$value['tax_rate'].'/100)';
			} else if ($key+1 != count($income_tax_rate)) {
				$income_tax_formular .=',if ('.$taxable_salary.'<='.$value['tax_bracket_value_to'].',(('.$value['tax_bracket_value_to'].'-'.$value['tax_bracket_value_from'].')*'.$value['tax_rate'].'/100)+'.$formular.'';
			} else {
				$income_tax_formular .=',if ('.$taxable_salary.'>='.$value['tax_bracket_value_from'].',(('.$taxable_salary.'-'.$value['tax_bracket_value_from'].')*'.$value['tax_rate'].'/100)+'.$formular.' , '.$formular.$formular_close;
			}

			if ($value['tax_bracket_value_to'] != 0 ) {
				if (strlen($formular ?? '') == 0) {
					$formular .= '(('.$value['tax_bracket_value_to'].'-'.$value['tax_bracket_value_from'].')*'.$value['tax_rate'].'/100)';
				} else {
					$formular .= '+'.'(('.$value['tax_bracket_value_to'].'-'.$value['tax_bracket_value_from'].')*'.$value['tax_rate'].'/100)';
				}
			}
		}

		return $income_tax_formular;		
	}

	/**
	 * get department payslip chart
	 * @param  string $month 
	 * @return [type]        
	 */
	public function get_department_payslip_chart($from_date, $to_date)
	{	
		$this->db->select();
		$this->db->from(db_prefix() . 'staff_departments');
		$this->db->join(db_prefix() . 'departments', db_prefix() . 'staff_departments.departmentid = ' . db_prefix() . 'departments.departmentid', 'left');
		$staff_departments = $this->db->get()->result_array();

	    //select payslip detail by month
		$this->db->where('month >= ', $from_date);
		$this->db->where('month <= ', $to_date);
		$this->db->order_by('staff_id', 'asc');
		$payslip_details = $this->db->get(db_prefix().'hr_payslip_details')->result_array();

		$staff_payslip=[];
		$data_result=[];
		foreach ($payslip_details as $key => $payslip) {
			if (isset($staff_payslip[$payslip['staff_id']])) {
				$staff_payslip[$payslip['staff_id']]['gross_pay'] 			+= $payslip['gross_pay'];
				$staff_payslip[$payslip['staff_id']]['total_insurance'] 	+= $payslip['total_insurance'];
				$staff_payslip[$payslip['staff_id']]['income_tax_paye'] 	+= $payslip['income_tax_paye'];
				$staff_payslip[$payslip['staff_id']]['total_deductions'] 	+= $payslip['total_deductions'];
				$staff_payslip[$payslip['staff_id']]['commission_amount'] 	+= $payslip['commission_amount'];
				$staff_payslip[$payslip['staff_id']]['bonus_kpi'] 			+= $payslip['bonus_kpi'];
				$staff_payslip[$payslip['staff_id']]['net_pay'] 			+= $payslip['net_pay'];
				$staff_payslip[$payslip['staff_id']]['total_cost'] 			+= $payslip['total_cost'];
			} else {
				$staff_payslip[$payslip['staff_id']]['gross_pay'] 			= $payslip['gross_pay'];
				$staff_payslip[$payslip['staff_id']]['total_insurance'] 	= $payslip['total_insurance'];
				$staff_payslip[$payslip['staff_id']]['income_tax_paye'] 	= $payslip['income_tax_paye'];
				$staff_payslip[$payslip['staff_id']]['total_deductions'] 	= $payslip['total_deductions'];
				$staff_payslip[$payslip['staff_id']]['commission_amount'] 	= $payslip['commission_amount'];
				$staff_payslip[$payslip['staff_id']]['bonus_kpi'] 			= $payslip['bonus_kpi'];
				$staff_payslip[$payslip['staff_id']]['net_pay'] 			= $payslip['net_pay'];
				$staff_payslip[$payslip['staff_id']]['total_cost'] 			= $payslip['total_cost'];
			}
		}

		$department_name=[];

		foreach ($staff_departments as $key => $staff_department) {
			if (isset($staff_payslip[$staff_department['staffid']])) {
				if (isset($data_result[$staff_department['departmentid']])) {
					$data_result[$staff_department['departmentid']]['gross_pay'] += $staff_payslip[$staff_department['staffid']]['gross_pay'];
					$data_result[$staff_department['departmentid']]['total_insurance'] += $staff_payslip[$staff_department['staffid']]['total_insurance'];
					$data_result[$staff_department['departmentid']]['income_tax_paye'] += $staff_payslip[$staff_department['staffid']]['income_tax_paye'];
					$data_result[$staff_department['departmentid']]['total_deductions'] += $staff_payslip[$staff_department['staffid']]['total_deductions'];
					$data_result[$staff_department['departmentid']]['commission_amount'] += $staff_payslip[$staff_department['staffid']]['commission_amount'];
					$data_result[$staff_department['departmentid']]['bonus_kpi'] += $staff_payslip[$staff_department['staffid']]['bonus_kpi'];
					$data_result[$staff_department['departmentid']]['net_pay'] += $staff_payslip[$staff_department['staffid']]['net_pay'];
					$data_result[$staff_department['departmentid']]['total_cost'] += $staff_payslip[$staff_department['staffid']]['total_cost'];
				} else {
					if (!in_array($staff_department['name'], $department_name)) {
						$department_name[] =  $staff_department['name'];
					}
					$data_result[$staff_department['departmentid']]['gross_pay'] = $staff_payslip[$staff_department['staffid']]['gross_pay'];
					$data_result[$staff_department['departmentid']]['total_insurance'] = $staff_payslip[$staff_department['staffid']]['total_insurance'];
					$data_result[$staff_department['departmentid']]['income_tax_paye'] = $staff_payslip[$staff_department['staffid']]['income_tax_paye'];
					$data_result[$staff_department['departmentid']]['total_deductions'] = $staff_payslip[$staff_department['staffid']]['total_deductions'];
					$data_result[$staff_department['departmentid']]['commission_amount'] = $staff_payslip[$staff_department['staffid']]['commission_amount'];
					$data_result[$staff_department['departmentid']]['bonus_kpi'] = $staff_payslip[$staff_department['staffid']]['bonus_kpi'];
					$data_result[$staff_department['departmentid']]['net_pay'] = $staff_payslip[$staff_department['staffid']]['net_pay'];
					$data_result[$staff_department['departmentid']]['total_cost'] = $staff_payslip[$staff_department['staffid']]['total_cost'];
				}
			} else {
				if (!in_array($staff_department['name'], $department_name)) {
					$department_name[] =  $staff_department['name'];
				}

				$data_result[$staff_department['departmentid']]['gross_pay'] = 0;
				$data_result[$staff_department['departmentid']]['total_insurance'] = 0;
				$data_result[$staff_department['departmentid']]['income_tax_paye'] = 0;
				$data_result[$staff_department['departmentid']]['total_deductions'] = 0;
				$data_result[$staff_department['departmentid']]['commission_amount'] = 0;
				$data_result[$staff_department['departmentid']]['bonus_kpi'] = 0;
				$data_result[$staff_department['departmentid']]['net_pay'] = 0;
				$data_result[$staff_department['departmentid']]['total_cost'] = 0;
			}
		}

		$payslip_columns=[];
		$payslip_columns[] = 'gross_pay';
		$payslip_columns[] = 'total_insurance';
		$payslip_columns[] = 'income_tax_paye';
		$payslip_columns[] = 'total_deductions';
		$payslip_columns[] = 'commission_amount';
		$payslip_columns[] = 'bonus_kpi';
		$payslip_columns[] = 'net_pay';
		$payslip_columns[] = 'total_cost';
		$list_result=[];

		foreach ($payslip_columns as $payslip_column) {
			$list_data_count = [];
			foreach ($data_result as $department) {
				$count = 0;
				if (isset($department[$payslip_column])) {
					$count = round((float)$department[$payslip_column], 2);
				}
				$list_data_count[] = $count;
			}

			switch ($payslip_column) {
				case 'gross_pay':
					$payslip_column_name = _l('ps_gross_pay');
					break;
				case 'total_insurance':
					$payslip_column_name = _l('ps_total_insurance');
					break;
				case 'income_tax_paye':
					$payslip_column_name = _l('ps_income_tax_paye');
					break;
				case 'total_deductions':
					$payslip_column_name = _l('ps_total_deductions');
					break;
				case 'commission_amount':
					$payslip_column_name = _l('ps_commission_amount');
					break;
				case 'bonus_kpi':
					$payslip_column_name = _l('ps_bonus_kpi');
					break;
				case 'net_pay':
					$payslip_column_name = _l('ps_net_pay');
					break;
				case 'total_cost':
					$payslip_column_name = _l('ps_total_cost');
					break;
			}
			array_push($list_result,array('stack' => $payslip_column_name,'data' => $list_data_count));
		}

		$data=[];
		$data['list_result'] = $list_result;
		$data['department_name'] = $department_name;
		
		return $data;
	}

	/**
	 * array cell data
	 * @param  [type] $payroll_templates 
	 * @return [type]                    
	 */
	public function array_cell_data($payroll_templates)
	{
		$payroll_column_name =[];
		$payroll_column_key =[];
		$payroll_formular =[];

		if ($payroll_templates) {
			$payroll_cell_data = json_decode($payroll_templates->cell_data);

			foreach ($payroll_cell_data as $key => $value) {

				//column Name from row 3
				if ($value->r == 3) {
					$payroll_column_name[] = $value->v->m;
				}

				//column key from row 4
				if ($value->r == 4) {
					$payroll_column_key[] = $value->v->m;
				}

				//column formular from row 5
				if ($value->r == 5) {
					if (isset($value->v->f) ) {
						//if column is formula
						$payroll_formular[] = $value->v->f;
					} else {
						$payroll_formular[] = 0;
					}
				}
				
			}
		}  

		$results=[];
		$results['payroll_column_name']	=	$payroll_column_name;
		$results['payroll_column_key']	=	$payroll_column_key;
		$results['payroll_formular']	=	$payroll_formular;

		return $results;
	}

	/**
	 * payslip template checked
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function payslip_template_checked($data)
	{
		//staff has payslip tempalte
		$staff_has_template=[];
		$str_staff_has_template='';

		if (isset($data['department_ids'])) {
			$department_id = implode(',', $data['department_ids']);
		} else {
			$department_id ='';
		}

		if (isset($data['role_ids'])) {
			$role_employees = implode(',', $data['role_ids']);
		} else {
			$role_employees = '';
		}

		if (isset($data['staff_ids'])) {
			$staff_employees = implode(',', $data['staff_ids']);
		} else {
			$staff_employees = '';
		}

		if (isset($data['expect_staff_ids'])) {
			$except_staff = $data['expect_staff_ids'];
		} else {
			$except_staff = [];
		}

		$staff_ids = $this->payslip_template_get_staffid($department_id, $role_employees, $staff_employees);
	
		if ($staff_ids != false) {
			$array_staff_ids = explode(",", $staff_ids);

			foreach ($array_staff_ids as $key => $value) {
				if (in_array($value, $except_staff)) {
					unset($array_staff_ids[$key]);
				}
			}

			if (count($array_staff_ids) > 0) {
				if (isset($data['id']) && is_numeric($data['id'])) {
					//update payslip template
					
					$this->db->where('id != ', $data['id']);
					$payslip_templates = $this->db->get(db_prefix() . 'hr_payslip_templates')->result_array();
				} else {
					//add payslip template
					$payslip_templates = $this->get_hr_payslip_templates();
				}

				for ($i=0; $i < count($payslip_templates); $i++) { 
					if ($payslip_templates[$i]['staff_employees'] != '' || $payslip_templates[$i]['staff_employees'] != null) {
						$array_staffs = explode(",", $payslip_templates[$i]['staff_employees']);
					} else {
						$get_staffid_by_payslip_template = $this->payslip_template_get_staffid($payslip_templates[$i]['department_id'], $payslip_templates[$i]['role_employees'], $payslip_templates[$i]['staff_employees'], $payslip_templates[$i]['except_staff']);

						$array_staffs=[];
						if ($get_staffid_by_payslip_template != false) {
							$array_staffs = explode(",", $get_staffid_by_payslip_template);
						}
					}

					foreach ($array_staffs as $staff_key => $staff_value) {
						if (in_array($staff_value, $array_staff_ids) && !in_array($staff_value, $except_staff) && !in_array($staff_value, $staff_has_template)) {
							$staff_has_template[] = $staff_value;
						}
					}
				}

				//TODO
				if (count($staff_has_template) > 0) {
					$staff_str_query = ' staffid IN ('.implode(",",$staff_has_template) .') ';

					$array_staff_has_template = $this->get_staff_timekeeping_applicable_object($staff_str_query);

					foreach ($array_staff_has_template as $key => $value) {
						if (strlen($str_staff_has_template ?? '') > 0) {
							$str_staff_has_template .= ', '. $value['firstname'].' '.$value['lastname'];
						} else {
							$str_staff_has_template .=  $value['firstname'].' '.$value['lastname'];
						}
					}					

					$str_staff_has_template .= _l('falls_within_other_the_payslip_template');
					return $str_staff_has_template;
				}
				return true;
			}
			return true;
		}
		return true;
	}

	/**
	 * payslip checked
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function payslip_checked($payslip_month, $payslip_template_id, $closing= false)
	{	
		if ($closing == false) {
			$payslip_month = date('Y-m-d', strtotime($payslip_month.'-01' ?? ''));
		}
		$this->db->where('payslip_month', $payslip_month);
		$this->db->where('payslip_template_id', $payslip_template_id);
		$this->db->where('payslip_status', 'payslip_closing');
		$payslip_closing = $this->db->get(db_prefix().'hr_payslips')->result_array();

		if (count($payslip_closing) > 0) {
			return false;
		}
		return true;
	}

	/**
	 * payslip download
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function payslip_download($data)
	{	

		$affectedRows = 0;
		$payroll_header=[];
		$payroll_column_key=[];
		$payroll_column_value=[];

		$payroll_system_columns = payroll_system_columns();
		$hr_payslip = $this->get_hr_payslip($data['id']);
		if ($hr_payslip) {
			$month = $hr_payslip->payslip_month;
		} else {
			$month = date('Y-m-d');
		}

		$payslip_data_decode = json_decode($data['payslip_data']);
		if (isset($payslip_data_decode[0])) {
			if (isset($payslip_data_decode[0]->celldata)) {
				$payslip_data = $payslip_data_decode[0]->celldata;
			}
		}

		if (isset($payslip_data)) {
			foreach ($payslip_data as $key => $value) {
				//column key from row 4
				if ($value->r == 3) {
					$payroll_header[] = isset($value->v->m) ? $value->v->m : '';
				}

				//column key from row 4
				if ($value->r == 4) {
					$payroll_column_key[] = isset($value->v->m) ? $value->v->m : '';
				}

				//column value
				if ($value->r > 4) {
					$payroll_column_value[$value->r][] = isset($value->v->m) ? hr_reformat_currency($value->v->m) : 0;
				}
			}
		}

		$payslip_detail=[];
		//add key: payslip_id, month to payroll column key
		if (count($payroll_column_value) > 0) {
			foreach ($payroll_column_value as $key => $value) {

			    if (count($payroll_column_key) != count($value)) {
			    	return false;
			    }
			    $check_array_combine = array_combine($payroll_column_key, $value);

			    $payslip_detail[] = array_combine($payroll_column_key, $value);
			}
		}

		$result=[];
		$result['payroll_header'] = $payroll_header;
		$result['payroll_column_key'] = $payroll_column_key;
		$result['payslip_detail'] = $payslip_detail;
		$result['month'] = $month;
		return $result;
	}

	/**
	 * employees copy
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function employees_copy($data)
	{
		$message='';
		$affectedRows=0;
		$month = date('Y-m-d', strtotime("-1 months",strtotime($data['month'].'-01' ?? '') ?? ''));

		$rel_type = hr_get_profile_status();
		$this->db->where('rel_type', $rel_type);
		$this->db->where("date_format(month, '%Y-%m-%d') = '".$month."'");
		$this->db->order_by('staff_id', 'asc');
		$employees = $this->db->get(db_prefix() . 'hr_employees_value')->result_array();

		if (count($employees) > 0) {
			//delete old data
			$this->db->where('rel_type', $rel_type);
			$this->db->where("date_format(month, '%Y-%m-%d') = '".date('Y-m-d', strtotime($data['month'].'-01' ?? ''))."'");
			$affected_rows = $this->db->delete(db_prefix().'hr_employees_value');
			if ($affected_rows > 0) {
				$affectedRows++;
			}

			//insert new data
			foreach ($employees as $key => $employee) {
			    if (isset($employee['id'])) {
			    	unset($employees[$key]['id']);
			    }
			    $employees[$key]['month'] = date('Y-m-d', strtotime($data['month'].'-01' ?? ''));
			}

			$affected_rows = $this->db->insert_batch(db_prefix().'hr_employees_value', $employees);
			if ($affected_rows > 0) {
				$affectedRows++;
			}

			$message = ($affectedRows > 0) ? _l('hr_added_successfully') : _l('hr_add_failed');
			$status  = ($affectedRows > 0) ? 'success' : 'warning';
		} else {  
			$message = _l('No_data_for_the_previous_month');
			$status = 'warning';
		}

		return ['message' => $message, 'status' => $status];
	}

	/**
	 * get tasks timer by_month
	 * @param  [type] $month 
	 * @return [type]        
	 */
	public function get_tasks_timer_by_month($month, $staff_id, $str_sql1, $hr_profile_status)
	{
		$month_temp = $month;
		//TODO get hourly rate by contract
		$salary_task_timers=[];

		$month = (int)date('m', strtotime($month ?? ''));

		//get_staff
		$this->db->where($str_sql1);
		$this->db->order_by('firstname', 'desc');
		$staffs = $this->db->get(db_prefix().'staff')->result_array();

		$staff_data=[];
		foreach ($staffs as $key => $staff) {
			$staff_data[$staff['staffid']] = $staff;
		}

		if ($hr_profile_status == 'hr_records') {
			//get_contract by staff
			$staff_contracts = $this->get_list_staff_contract($month_temp);
		}
		
		$sql_where="SELECT ".db_prefix()."tasks.hourly_rate, ".db_prefix()."taskstimers.staff_id, from_unixtime(start_time, '%Y-%m-%d %H:%i:%s') as start_time, from_unixtime(end_time, '%Y-%m-%d %H:%i:%s') as end_time, date_format(from_unixtime(end_time, '%Y-%m-%d'), '%c') as months, TIMESTAMPDIFF(MINUTE, from_unixtime(start_time, '%Y-%m-%d %H:%i:%s'), from_unixtime(end_time, '%Y-%m-%d %H:%i:%s')) as total_time FROM ".db_prefix()."taskstimers 
		LEFT join ".db_prefix()."tasks on ".db_prefix()."taskstimers.task_id = ".db_prefix()."tasks.id
		where date_format(from_unixtime(end_time, '%Y-%m-%d'), '%c') = ".$month." AND ".$staff_id."
		order by staff_id desc";

		$task_timers = $this->db->query($sql_where)->result_array();

		foreach ($task_timers as $key => $task_timer) {
			if ($task_timer['hourly_rate'] != 0) {
				if (isset($salary_task_timers[$task_timer['staff_id']])) {
					$salary_task_timers[$task_timer['staff_id']]['salary_from_tasks'] += (float)$task_timer['hourly_rate']*((float)$task_timer['total_time']/60);
					$salary_task_timers[$task_timer['staff_id']]['total_hours_by_tasks'] += (float)$task_timer['total_time']/60;
				} else {
					$salary_task_timers[$task_timer['staff_id']]['salary_from_tasks'] = (float)$task_timer['hourly_rate']*((float)$task_timer['total_time']/60);
					$salary_task_timers[$task_timer['staff_id']]['total_hours_by_tasks'] = (float)$task_timer['total_time']/60;
				}
			} else {
				if ($hr_profile_status == 'hr_records') {
					//get by contract
					if (isset($staff_contracts[$task_timer['staff_id']])) {
						$hourly_rate=0;
						$flag_contract = false;

						//formal contract
						if (isset($staff_contracts[$task_timer['staff_id']]['formal'])) {
							if ($staff_contracts[$task_timer['staff_id']]['formal']['hourly_or_month'] == 'hourly_rate') {
								if ($staff_contracts[$task_timer['staff_id']]['formal']['primary_expiration'] == null) {
									if (strtotime($staff_contracts[$task_timer['staff_id']]['formal']['primary_effective'] ?? '') <= strtotime($task_timer['start_time'] ?? '') ) {
										foreach ($staff_contracts[$task_timer['staff_id']]['formal'] as $formal_key => $formal_value) {
											if (preg_match('/^st2_/', $formal_key) || preg_match('/^al2_/', $formal_key)) {
											//get value from staff contract if exist
												$hourly_rate += (float)$formal_value;											
											}
										}
										$flag_contract = true;
									}
								} else {
									if (strtotime($staff_contracts[$task_timer['staff_id']]['formal']['primary_effective'] ?? '') <= strtotime($task_timer['start_time'] ?? '') &&  strtotime($task_timer['start_time'] ?? '') <= strtotime($staff_contracts[$task_timer['staff_id']]['formal']['primary_expiration'] ?? '')) {
										foreach ($staff_contracts[$task_timer['staff_id']]['formal'] as $formal_key => $formal_value) {
											if (preg_match('/^st2_/', $formal_key) || preg_match('/^al2_/', $formal_key)) {
												//get value from staff contract if exist
												$hourly_rate += (float)$formal_value;											
											}
										}
										$flag_contract = true;
									}
								}
							}
						}

						//probationary contract
						if ($flag_contract == false && isset($staff_contracts[$task_timer['staff_id']]['probationary'])) {
							if ($staff_contracts[$task_timer['staff_id']]['probationary']['hourly_or_month'] == 'hourly_rate') {
								if ($staff_contracts[$task_timer['staff_id']]['probationary']['probationary_expiration'] == null) {
									if (strtotime($staff_contracts[$task_timer['staff_id']]['probationary']['probationary_effective'] ?? '') <= strtotime($task_timer['start_time'] ?? '') ) {
										foreach ($staff_contracts[$task_timer['staff_id']]['probationary'] as $probationary_key => $probationary_value) {
											if (preg_match('/^st1_/', $probationary_key) || preg_match('/^al1_/', $probationary_key)) {
												//get value from staff contract if exist
												$hourly_rate += (float)$probationary_value;											
											}
										}
										$flag_contract = true;
									}
								} else {
									if (strtotime($staff_contracts[$task_timer['staff_id']]['probationary']['probationary_effective'] ?? '') <= strtotime($task_timer['start_time'] ?? '') &&  strtotime($task_timer['start_time'] ?? '') <= strtotime($staff_contracts[$task_timer['staff_id']]['probationary']['probationary_expiration'] ?? '')) {
										foreach ($staff_contracts[$task_timer['staff_id']]['probationary'] as $probationary_key => $probationary_value) {
											if (preg_match('/^st1_/', $probationary_key) || preg_match('/^al1_/', $probationary_key)) {
											//get value from staff contract if exist
												$hourly_rate += (float)$probationary_value;											
											}
										}
										$flag_contract = true;
									}
								}
							}
						}

						if ($flag_contract == false) {
							if (isset($staff_data[$task_timer['staff_id']])) {
								$hourly_rate = $staff_data[$task_timer['staff_id']]['hourly_rate'];
							}
						}

						if (isset($salary_task_timers[$task_timer['staff_id']])) {
							$salary_task_timers[$task_timer['staff_id']]['salary_from_tasks'] += (float)$hourly_rate*((float)$task_timer['total_time']/60);
							$salary_task_timers[$task_timer['staff_id']]['total_hours_by_tasks'] += (float)$task_timer['total_time']/60;
						} else {
							$salary_task_timers[$task_timer['staff_id']]['salary_from_tasks'] = (float)$hourly_rate*((float)$task_timer['total_time']/60);
							$salary_task_timers[$task_timer['staff_id']]['total_hours_by_tasks'] = (float)$task_timer['total_time']/60;
						}
					} else {
						//get hourly rate in staff
						$hourly_rate=0;
						if (isset($staff_data[$task_timer['staff_id']])) {
							$hourly_rate = $staff_data[$task_timer['staff_id']]['hourly_rate'];							
						}

						if (isset($salary_task_timers[$task_timer['staff_id']])) {
							$salary_task_timers[$task_timer['staff_id']]['salary_from_tasks'] += (float)$hourly_rate*((float)$task_timer['total_time']/60);
							$salary_task_timers[$task_timer['staff_id']]['total_hours_by_tasks'] += (float)$task_timer['total_time']/60;
						} else {
							$salary_task_timers[$task_timer['staff_id']]['salary_from_tasks'] = (float)$hourly_rate*((float)$task_timer['total_time']/60);
							$salary_task_timers[$task_timer['staff_id']]['total_hours_by_tasks'] = (float)$task_timer['total_time']/60;
						}
					}
				} else {
					//get hourly rate in staff
					$hourly_rate=0;
					if (isset($staff_data[$task_timer['staff_id']])) {

						$hourly_rate = $staff_data[$task_timer['staff_id']]['hourly_rate'];
					}

					if (isset($salary_task_timers[$task_timer['staff_id']])) {
						$salary_task_timers[$task_timer['staff_id']]['salary_from_tasks'] += (float)$hourly_rate*((float)$task_timer['total_time']/60);
						$salary_task_timers[$task_timer['staff_id']]['total_hours_by_tasks'] += (float)$task_timer['total_time']/60;
					} else {
						$salary_task_timers[$task_timer['staff_id']]['salary_from_tasks'] = (float)$hourly_rate*((float)$task_timer['total_time']/60);
						$salary_task_timers[$task_timer['staff_id']]['total_hours_by_tasks'] = (float)$task_timer['total_time']/60;
					}
				}
			}
		}

		return $salary_task_timers;		
	}

	/**
	 * payslip of staff
	 * @param  [type] $payslip_id 
	 * @return [type]             
	 */
	public function payslip_of_staff($payslip_id)
	{
		$staff_ids=[];

		$this->db->where('payslip_id', $payslip_id);
		$payslip_details = $this->db->get(db_prefix().'hr_payslip_details')->result_array();

		foreach ($payslip_details as $payslip_detail) {
			$staff_ids[] = $payslip_detail['staff_id'];
		}

		return $staff_ids;
	}

	/**
	 * remove employees not under management on payslip
	 * @param  [type] $payslip_data 
	 * @return [type]               
	 */
	public function remove_employees_not_under_management_on_payslip($payslip_data)
	{
		$payslip_data_decode = json_decode($payslip_data);
		if (is_array($payslip_data_decode)) {
			if (isset($payslip_data_decode[0]->celldata)) {
				$array_staffid_by_permission = get_array_staffid_by_permission();
				$row_remove=[];
				$staff_id_col;

				//get col of staff_id
				foreach ($payslip_data_decode[0]->celldata as $celldata) {
					if (isset($celldata->v->m) && $celldata->v->m =='staff_id' ) {
						$staff_id_col = $celldata->c;
					}

					if (isset($staff_id_col) && strlen($staff_id_col ?? '') > 0) {
						break;
					}
				}

				//get row remove on payslip
				if (isset($staff_id_col)) {
					foreach ($payslip_data_decode[0]->celldata as $celldata) {
						if (isset($celldata->r) && $celldata->r > 4 && isset($celldata->c) && $celldata->c == $staff_id_col && isset($celldata->v->m) && !in_array($celldata->v->m, $array_staffid_by_permission)  ) {
							$row_remove[] = $celldata->r;
						}
					}
				}

				//remove row on payslip
				foreach ($payslip_data_decode[0]->celldata as $key => $celldata) {
					if (in_array($celldata->r, $row_remove)) {
						$payslip_data_decode[0]->celldata[$key]->v->m = '####';
						$payslip_data_decode[0]->celldata[$key]->v->v = '####';
					}
				}

				return json_encode($payslip_data_decode);

			}
			return $payslip_data;
		}
		return $payslip_data;
	}

	/**
	 * employee export pdf
	 * @param  [type] $export_employee 
	 * @return [type]                  
	 */
	public function employee_export_pdf($export_employee)
	{
		return app_pdf('export_employee', module_dir_path(HR_CONTROL_MODULE_NAME, 'libraries/pdf/Export_employee_pdf.php'), $export_employee);
	}

	/**
	 * get payslip detail by payslip_id
	 * @param  [type] $payslip_id 
	 * @return [type]             
	 */
	public function get_payslip_detail_by_payslip_id($payslip_id)
	{
		$this->db->where('payslip_id', $payslip_id);
		return $this->db->get(db_prefix() . 'hr_payslip_details')->result_array();
	}
	
	/*
		HR Profile
	*/
	/**
	 * get hr profile dashboard data
	 * @return array 
	 */
	public function get_hr_control_dashboard_data(){
		$data_hrm = [];
		$staff = $this->staff_model->get();
		$total_staff = count($staff);
		$new_staff_in_month = $this->db->query('SELECT * FROM '.db_prefix().'staff WHERE MONTH(datecreated) = '.date('m').' AND YEAR(datecreated) = '.date('Y'))->result_array();
		$staff_working = $this->db->query('SELECT * FROM '.db_prefix().'staff WHERE status_work = "working"')->result_array();
		$staff_birthday = $this->db->query('SELECT * FROM '.db_prefix().'staff WHERE status_work = "working" AND MONTH(birthday) = '.date('m').' ORDER BY birthday ASC')->result_array();
		$staff_inactivity = $this->db->query('SELECT * FROM '.db_prefix().'staff WHERE status_work = "inactivity" AND staffid in (SELECT staffid FROM '.db_prefix().'hr_list_staff_quitting_work where dateoff >= \''.date('Y-m-01').' 00:00:00'.'\' and dateoff <= \''.date('Y-m-t').' 23:59:59'.'\')')->result_array();
		$overdue_contract = $this->db->query('SELECT * FROM '.db_prefix().'hr_staff_contract WHERE end_valid < "'.date('Y-m-d').'" AND contract_status = "valid"')->result_array();
		$expire_contract = $this->db->query('SELECT * FROM '.db_prefix().'hr_staff_contract WHERE end_valid <= "'.date('Y-m-d',strtotime('+7 day',strtotime(date('Y-m-d')))).'" AND end_valid >= "'.date('Y-m-d').'" AND contract_status = "valid"')->result_array();

		$data_hrm['staff_birthday'] = $staff_birthday;
		$data_hrm['total_staff'] = $total_staff;
		$data_hrm['new_staff_in_month'] = count($new_staff_in_month);
		$data_hrm['staff_working'] = count($staff_working);
		$data_hrm['staff_inactivity'] = count($staff_inactivity);
		$data_hrm['overdue_contract'] = count($overdue_contract);
		$data_hrm['expire_contract'] = count($expire_contract);
		$data_hrm['overdue_contract_data'] = $overdue_contract;
		$data_hrm['expire_contract_data'] = $expire_contract;
		return $data_hrm;
	}

	/**
	 * staff chart by age
	 * @return array 
	 */
	public function staff_chart_by_age()
	{
		$staffs = $this->staff_model->get();
		$chart = [];
		$status_1 = ['name' => _l('18_24_age'), 'color' => '#777', 'y' => 0, 'z' => 100];
		$status_2 = ['name' => _l('25_29_age'), 'color' => '#fc2d42', 'y' => 0, 'z' => 100];
		$status_3 = ['name' => _l('30_39_age'), 'color' => '#03a9f4', 'y' => 0, 'z' => 100];
		$status_4 = ['name' => _l('40_60_age'), 'color' => '#ff6f00', 'y' => 0, 'z' => 100];
		foreach ($staffs as $staff) {
			$diff = date_diff(date_create(), date_create($staff['birthday']));
			$age = $diff->format('%Y');

			if($age >= 18 && $age <= 24)
			{
				$status_1['y'] += 1;
			}elseif ($age >= 25 && $age <= 29) {
				$status_2['y'] += 1;
			}elseif ($age >= 30 && $age <= 39) {
				$status_3['y'] += 1;
			}elseif ($age >= 40 && $age <= 60) {
				$status_4['y'] += 1;
			}
		}
		if($status_1['y'] > 0){
			array_push($chart, $status_1);
		}
		if($status_2['y'] > 0){
			array_push($chart, $status_2);
		}
		if($status_3['y'] > 0){
			array_push($chart, $status_3);
		}
		if($status_4['y'] > 0){
			array_push($chart, $status_4);
		}
		return $chart;
	}

	/**
	 * contract type chart
	 * @return  array
	 */
	public function contract_type_chart()
	{
		$contracts = $this->db->query('SELECT * FROM tblhr_staff_contract')->result_array();
		$statuses = $this->get_contracttype();
		$color_data = ['#00FF7F', '#0cffe95c','#80da22','#f37b15','#da1818','#176cea','#5be4f0', '#57c4d8', '#a4d17a', '#225b8', '#be608b', '#96b00c', '#088baf',
		'#63b598', '#ce7d78', '#ea9e70', '#a48a9e', '#c6e1e8', '#648177' ,'#0d5ac1' ,
		'#d2737d' ,'#c0a43c' ,'#f2510e' ,'#651be6' ,'#79806e' ,'#61da5e' ,'#cd2f00' ];

		$_data                         = [];
		$total_value =0;
		$has_permission = has_permission('pw_mana_projects', '', 'view');
		$sql            = '';
		foreach ($statuses as $status) {
			$sql .= ' SELECT COUNT(*) as total';
			$sql .= ' FROM ' . db_prefix() . 'hr_staff_contract';
			$sql .= ' WHERE name_contract=' . $status['id_contracttype'];
			$sql .= ' UNION ALL ';
			$sql = trim($sql);
		}

		$result = [];
		if ($sql != '') {
			$sql    = substr($sql, 0, -10);
			$result = $this->db->query($sql)->result();
		}
		foreach ($statuses as $key => $status) {
			$total_value+=(int)$result[$key]->total;
		}
		foreach ($statuses as $key => $status) {
			if($total_value > 0){
				array_push($_data,
					[ 
						'name' => $status['name_contracttype'],
						'y'    => (int)$result[$key]->total,
						'z'    => (number_format(((int)$result[$key]->total/$total_value), 4, '.',""))*100,
						'color'=>$color_data[$key]
					]);
			}else{
				array_push($_data,
					[ 
						'name' => $status['name_contracttype'],
						'y'    => (int)$result[$key]->total,
						'z'    => (number_format(((int)$result[$key]->total/1), 4, '.',""))*100,
						'color'=>$color_data[$key]
					]);
			}
		}
		return $_data;
	}

	/**
	 * staff chart by departments
	 * @return [type] 
	 */
	public function staff_chart_by_departments()
	{
		$chart = [];
		$color_data = ['#a48a9e', '#c6e1e8', '#648177' ,'#0d5ac1','#00FF7F', '#0cffe95c','#80da22','#f37b15','#da1818','#176cea','#5be4f0', '#57c4d8', '#a4d17a', '#225b8', '#be608b', '#96b00c', '#088baf',
		'#63b598', '#ce7d78', '#ea9e70' ,
		'#d2737d' ,'#c0a43c' ,'#f2510e' ,'#651be6' ,'#79806e' ,'#61da5e' ,'#cd2f00' ];

		$this->db->select(db_prefix().'staff_departments.departmentid, count(staffdepartmentid) as total_staff,'.db_prefix().'departments.name as department_name');
		$this->db->join(db_prefix() . 'departments', db_prefix() . 'departments.departmentid = ' . db_prefix() . 'staff_departments.departmentid', 'left');
		$this->db->group_by('departmentid');
		$staff_departments = $this->db->get(db_prefix().'staff_departments')->result_array();

		$color_index=0;
		foreach ($staff_departments as $key => $value) {
			if(isset($color_data[$color_index])){
				array_push($chart, [
					'name' 		=> $value['department_name'],
					'color' 	=> $color_data[$color_index],
					'y' 		=>	(int)$value['total_staff'],
					'z' 		=> 100
				]);
			}else{
				$color_index = 0;
				array_push($chart, [
					'name' 		=> $value['department_name'],
					'color' 	=> $color_data[$color_index],
					'y' 		=> (int)$value['total_staff'],
					'z' 		=> 100
				]);
			}
			$color_index++;
		}

		return $chart;
	}

	/**
	 * staff chart by job positions
	 * @return [type] 
	 */
	public function staff_chart_by_job_positions()
	{
		$chart = [];
		$color_data = ['#d2737d' ,'#c0a43c' ,'#f2510e' ,'#651be6' ,'#79806e' ,'#61da5e' ,'#cd2f00','#00FF7F', '#0cffe95c','#80da22','#f37b15','#da1818','#176cea','#5be4f0', '#57c4d8', '#a4d17a', '#225b8', '#be608b', '#96b00c', '#088baf',
		'#63b598', '#ce7d78', '#ea9e70', '#a48a9e', '#c6e1e8', '#648177' ,'#0d5ac1' ];

		$this->db->select(db_prefix().'hr_job_position.position_name, count(staffid) as total_staff, job_position');
		$this->db->join(db_prefix() . 'hr_job_position', db_prefix() . 'hr_job_position.position_id = ' . db_prefix() . 'staff.job_position', 'left');
		$this->db->group_by('job_position');
		$staff_departments = $this->db->get(db_prefix().'staff')->result_array();

		$color_index=0;
		foreach ($staff_departments as $key => $value) {
			if(isset($color_data[$color_index])){
				array_push($chart, [
					'name' 		=> $value['position_name'],
					'color' 	=> $color_data[$color_index],
					'y' 		=>	(int)$value['total_staff'],
					'z' 		=> 100
				]);
			}else{
				$color_index = 0;
				array_push($chart, [
					'name' 		=> $value['position_name'],
					'color' 	=> $color_data[$color_index],
					'y' 		=> (int)$value['total_staff'],
					'z' 		=> 100
				]);
			}
			$color_index++;
		}

		return $chart;
	}

	/**
	 * report by staffs
	 * @return [type] 
	 */
	public function report_by_staffs()
	{
		$months_report = $this->input->post('months_report');
		$custom_date_select = '';

		$current_year = date('Y');
		for($_month = 1 ; $_month <= 12; $_month++){
			$month_t = date('m',mktime(0, 0, 0, $_month, 04, 2016));

			if($_month == 5){
				$chart['categories'][] = _l('month_05');
			} else {
				$chart['categories'][] = _l('month_'.$_month);
			}

			$month = $current_year.'-'.$month_t;

			$chart['hr_new_staff'][] = $this->new_staff_by_month($month);
			$chart['hr_staff_are_working'][] = $this->staff_working_by_month($month);
			$chart['hr_staff_quit'][] = $this->staff_quit_work_by_month($month);
		}

		return $chart;
	}

	/**
	 * new staff by month
	 * @param  [type] $from 
	 * @param  [type] $to   
	 * @return [type]       
	 */
	public function new_staff_by_month($month)
	{
		$this->db->select('count(staffid) as total_staff');
		$sql_where = "date_format(datecreated, '%Y-%m') = '".$month."'";
		$this->db->where($sql_where);
		$result = $this->db->get(db_prefix().'staff')->row();

		if($result){
			return (int)$result->total_staff;
		}
		return 0;
	}

	/**
	 * staff working by_month
	 * @param  [type] $from 
	 * @param  [type] $to   
	 * @return [type]       
	 */
	public function staff_working_by_month($month)
	{
		$this->db->select('count(staffid) as total_staff');
		$sql_where = "status_work = 'working' AND date_format(datecreated, '%Y-%m') < '".$month."'";
		$this->db->where($sql_where);
		$result = $this->db->get(db_prefix().'staff')->row();

		if($result){
			return (int)$result->total_staff;
		}
		return 0;
	}

	/**
	 * staff quit work by month
	 * @param  [type] $month 
	 * @return [type]        
	 */
	public function staff_quit_work_by_month($month)
	{	
		$this->db->select('count(staffid) as total_staff');
		$sql_where = 'staffid in (SELECT staffid FROM '.db_prefix().'hr_list_staff_quitting_work where date_format(dateoff, "%Y-%m") <= '.$month.') OR (status_work = "inactivity" AND date_format(date_update, "%Y-%m") = "'.$month.'")';
		$this->db->where($sql_where);
		$result = $this->db->get(db_prefix().'staff')->row();

		if($result){
			return (int)$result->total_staff;
		}
		return 0;
	}
	
	/**
	 * get contracttype
	 * @param  boolean $id 
	 * @return [type]      
	 */
	public function get_contracttype($id = false) {
		if (is_numeric($id)) {
			$this->db->where('id_contracttype', $id);

			return $this->db->get(db_prefix() . 'hr_staff_contract_type')->row();
		}

		if ($id == false) {
			return $this->db->query('select * from '.db_prefix().'hr_staff_contract_type order by id_contracttype desc')->result_array();
		}
	}

	/**
	 * get data departmentchart
	 * @return array 
	 */
	public function get_data_departmentchart(){        
		$department =  $this->db->query('select  departmentid as id, parent_id as pid, name, manager_id
			from '.db_prefix().'departments as d order by d.parent_id, d.departmentid')->result_array();

		$dep_tree = [];
		foreach ($department as $dep) {
			if($dep['pid']==0){
				$job_pst = hr_control_job_position_by_staff($dep['manager_id']);

				array_push($dep_tree, 
					[
						'id' => $dep['id'],
						'name' =>$dep['name'],
						'title'    =>get_staff_full_name($dep['manager_id']),
						'image' => staff_profile_image($dep['manager_id'], [
							'staff-profile-image-small staff-chart-padding',
						]),
						'children'=>$this->get_child_node_chart($dep['id'], $department),
						'reality_now' => _l('hr_current_personnel').': '.$this->count_reality_now($dep['id']),
						'dp_user_icon' => '"fa fa-user-o"',
						'job_position' => $job_pst,
					]
				);
			} else {
				break;
			}            
		}  
		return $dep_tree;
	}
	/**
	 * get child node chart
	 * @param  integer $id      
	 * @param  integer $arr_dep 
	 * @return array          
	*/
	private function get_child_node_chart($id, $arr_dep){
		$dep_tree = array();
		foreach ($arr_dep as $dep) {
			if($dep['pid']==$id){   
				$node = array();  
				$node['id'] = $dep['id'];           
				$node['name'] = $dep['name'];
				$node['title'] = get_staff_full_name($dep['manager_id']);
				$node['image'] = staff_profile_image($dep['manager_id'], [
					'staff-profile-image-small staff-chart-padding',
				]);
				$node['dp_user_icon'] = '"fa fa-user-o"';
				$node['job_position'] = hr_control_job_position_by_staff($dep['manager_id']);
				

				$node['children'] = $this->get_child_node_chart($dep['id'], $arr_dep);
				$node['reality_now'] = _l('hr_current_personnel').': '.$this->count_reality_now($dep['id']);
				if(count($node['children'])==0){
					unset($node['children']);
				}
				$dep_tree[] = $node;
			} 
		} 
		return $dep_tree;
	}

	/**
	 * get data departmentchart v2
	 * @return [type] 
	 */
	public function get_data_departmentchart_v2(){ 
	$manager_id = get_staff_user_id();

		$department =  $this->db->query('select  departmentid as id, parent_id as pid, name, manager_id
			from '.db_prefix().'departments as d order by d.parent_id, d.departmentid')->result_array();

		$dep_tree = [];
		foreach ($department as $dep) {
			if($dep['pid']==0 && $dep['manager_id'] == get_staff_user_id()){
				$job_pst = hr_control_job_position_by_staff($dep['manager_id']);

				array_push($dep_tree, 
					[
						'id' => $dep['id'],
						'name' =>$dep['name'],
						'title'    =>get_staff_full_name($dep['manager_id']),
						'image' => staff_profile_image($dep['manager_id'], [
							'staff-profile-image-small staff-chart-padding',
						]),
						'children'=>$this->get_child_node_chart($dep['id'], $department),
						'reality_now' => _l('hr_current_personnel').': '.$this->count_reality_now($dep['id']),
						'dp_user_icon' => '"fa fa-user-o"',
						'job_position' => $job_pst,
					]
				);
			} elseif($dep['pid'] ==0 && $dep['manager_id'] != get_staff_user_id()){

				$job_pst = hr_control_job_position_by_staff($dep['manager_id']);
				$child_node = $this->get_child_node_chart_v2($dep['id'], $department);
				$check_is_manager = $this->check_is_manager($child_node, $manager_id);

				if(preg_match('/true/', json_encode($check_is_manager))){

					array_push($dep_tree, 
						[
							'id' => $dep['id'],
							'name' =>$dep['name'],
							'title'    =>get_staff_full_name($dep['manager_id']),
							'image' => staff_profile_image($dep['manager_id'], [
								'staff-profile-image-small staff-chart-padding',
							]),
							'children'=>$this->get_child_node_chart_v2($dep['id'], $department),
							'reality_now' => _l('hr_current_personnel').': '.$this->count_reality_now($dep['id']),
							'dp_user_icon' => '"fa fa-user-o"',
							'job_position' => $job_pst,
						]
					);
				}

			}            
		} 
		return $dep_tree;
	}


	/**
	 * get child node chart v2
	 * @param  [type] $id      
	 * @param  [type] $arr_dep 
	 * @return [type]          
	 */
	private function get_child_node_chart_v2($id, $arr_dep){
		$dep_tree = array();
		foreach ($arr_dep as $dep) {
			if($dep['pid']==$id){

				$node = array();  
				$node['id'] = $dep['id'];           
				$node['name'] = $dep['name'];
				$node['manager_id'] = $dep['manager_id'];
				$node['title'] = get_staff_full_name($dep['manager_id']);
				$node['image'] = staff_profile_image($dep['manager_id'], [
					'staff-profile-image-small staff-chart-padding',
				]);
				$node['dp_user_icon'] = '"fa fa-user-o"';
				$node['job_position'] = hr_control_job_position_by_staff($dep['manager_id']);
				

				$node['children'] = $this->get_child_node_chart_v2($dep['id'], $arr_dep);
				$node['reality_now'] = _l('hr_current_personnel').': '.$this->count_reality_now($dep['id']);
				if(count($node['children'])==0){
					unset($node['children']);
				}
				$dep_tree[] = $node;
			} 
		} 
		return $dep_tree;
	}

	/**
	 * check is manager
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function check_is_manager($data, $manager_id)
	{	
		$check_array = array();
		foreach ($data as $key => $value) {
			if($value['manager_id'] == $manager_id){
				$check_array[] = true;
			}elseif(isset($value['children'])){
				$check_array[] = $this->check_is_manager($value['children'], $manager_id);
			}
		}
		return $check_array;
	}

	/**
	 * count reality now
	 * @param  integer $department 
	 * @return integer             
	 */
	public function count_reality_now($department){
		$staff_dpm = $this->db->query('select '.db_prefix().'staff_departments.staffid from '.db_prefix().'staff_departments Left join '.db_prefix().'staff ON '.db_prefix().'staff.staffid = '.db_prefix().'staff_departments.staffid where '.db_prefix().'staff_departments.departmentid = '.$department.' and '.db_prefix().'staff.status_work != "inactivity"')->result_array();

		return count($staff_dpm);
	}
	/**
	 * get data chart
	 * @return array 
	 */
	public function get_data_chart()
	{
		$department =  $this->db->query('select s.staffid as id, s.team_manage as pid, CONCAT(s.firstname," ",s.lastname) as name,  r.name as rname, j.position_name as job_position_name
			from tblstaff as s left join tblroles as r on s.role = r.roleid left join '.db_prefix().'hr_job_position as j on j.position_id = s.job_position where s.status_work != "inactivity"       
			order by s.team_manage, s.staffid')->result_array();
		$dep_tree = array();
		foreach ($department as $dep) {
			if($dep['pid'] == 0){
				$dpm = $this->getdepartment_name($dep['id']);
				$node = array();
				$node['name'] = $dep['name'];
				$node['job_position_name'] = '';

				if($dep['job_position_name'] != null && $dep['job_position_name'] != 'undefined'){
					$node['dp_user_icon'] = '"fa fa-map-pin menu-icon"';

					$node['job_position_name'] = $dep['job_position_name'];
				}

				if($dep['rname'] != null){
					$node['title'] = $dep['rname'];
					$node['dp_user_icon'] = '"fa fa-map-pin menu-icon"';
				}else{
					$node['title'] = '';
				}
				if($dpm->name != null){
					$node['departmentname'] = $dpm->name;
					$node['dp_icon'] = '"fa fa-sitemap"';
				}else{
					$node['departmentname'] = '';
				}
				$node['image'] = staff_profile_image($dep['id'], [
					'staff-profile-image-small staff-chart-padding',
				]);
				$node['children'] = $this->get_child_node_staff_chart($dep['id'], $department);
				$dep_tree[] = $node;
			} else {
				break;
			}            
		}   
		return $dep_tree;
	}

	/**
	 * get data chart v2
	 * @return [type] 
	 */
	public function get_data_chart_v2()
	{
		$team_manage = get_staff_user_id();
		$staffs =  $this->db->query('select s.staffid as id, s.team_manage as pid, CONCAT(s.firstname," ",s.lastname) as name,  r.name as rname, j.position_name as job_position_name
			from tblstaff as s left join tblroles as r on s.role = r.roleid left join '.db_prefix().'hr_job_position as j on j.position_id = s.job_position where s.status_work != "inactivity"       
			order by s.team_manage, s.staffid')->result_array();
		$dep_tree = array();
		foreach ($staffs as $dep) {
			if($dep['pid'] == 0 && $dep['id'] == $team_manage){
				$dpm = $this->getdepartment_name($dep['id']);
				$node = array();
				$node['name'] = $dep['name'];
				$node['job_position_name'] = '';

				if($dep['job_position_name'] != null && $dep['job_position_name'] != 'undefined'){
					$node['dp_user_icon'] = '"fa fa-map-pin menu-icon"';

					$node['job_position_name'] = $dep['job_position_name'];
				}

				if($dep['rname'] != null){
					$node['title'] = $dep['rname'];
					$node['dp_user_icon'] = '"fa fa-map-pin menu-icon"';
				}else{
					$node['title'] = '';
				}
				if($dpm->name != null){
					$node['departmentname'] = $dpm->name;
					$node['dp_icon'] = '"fa fa-sitemap"';
				}else{
					$node['departmentname'] = '';
				}
				$node['image'] = staff_profile_image($dep['id'], [
					'staff-profile-image-small staff-chart-padding',
				]);
				$node['children'] = $this->get_child_node_staff_chart($dep['id'], $staffs);
				$dep_tree[] = $node;

			} elseif($dep['pid'] ==0 && $dep['id'] != $team_manage){
				
				$child_node = $this->get_child_node_staff_chart($dep['id'], $staffs);
				$check_is_team_manage = $this->check_is_team_manage($child_node, $team_manage);

				if(preg_match('/true/', json_encode($check_is_team_manage))){

					$dpm = $this->getdepartment_name($dep['id']);
					$node = array();
					$node['name'] = $dep['name'];
					$node['job_position_name'] = '';

					if($dep['job_position_name'] != null && $dep['job_position_name'] != 'undefined'){
						$node['dp_user_icon'] = '"fa fa-map-pin menu-icon"';

						$node['job_position_name'] = $dep['job_position_name'];
					}

					if($dep['rname'] != null){
						$node['title'] = $dep['rname'];
						$node['dp_user_icon'] = '"fa fa-map-pin menu-icon"';
					}else{
						$node['title'] = '';
					}

					if($dpm->name != null){
						$node['departmentname'] = $dpm->name;
						$node['dp_icon'] = '"fa fa-sitemap"';
					}else{
						$node['departmentname'] = '';
					}
					$node['image'] = staff_profile_image($dep['id'], [
						'staff-profile-image-small staff-chart-padding',
					]);
					$node['children'] = $this->get_child_node_staff_chart($dep['id'], $staffs);

					$dep_tree[] = $node;
				}

			}            
		}   
		return $dep_tree;
	}

	/**
	 * check is team manage
	 * @param  [type] $data       
	 * @param  [type] $manager_id 
	 * @return [type]             
	 */
	public function check_is_team_manage($data, $manager_id)
	{	
		$check_array = array();
		foreach ($data as $key => $value) {
			if($value['team_manage'] == $manager_id){
				$check_array[] = true;
			}elseif(isset($value['children'])){
				$check_array[] = $this->check_is_team_manage($value['children'], $manager_id);
			}
		}
		return $check_array;
	}

	/**
	 * get department tree
	 * @return array 
	 */
	public function get_department_tree(){
		$department =  $this->db->query('select  departmentid as id, parent_id as pid, name from '.db_prefix().'departments as d order by d.parent_id, d.departmentid')->result_array();

		$dep_tree = array();

		$node = array();
        $node['id'] = 0;
        $node['title'] = _l('dropdown_non_selected_tex');
        $node['subs'] = array();
        $dep_tree[] = $node;

		foreach ($department as $dep) {
			if($dep['pid']==0){
				$node = array();
				$node['id'] = $dep['id'];
				$node['title'] = $dep['name'];
				$node['subs'] = $this->get_child_node($dep['id'], $department);
				$dep_tree[] = $node;
			} else {
				break;
			}            
		}     
		return $dep_tree;
	}


	 /**
	 * Get child node of department tree
	 * @param  $id      current department id
	 * @param  $arr_dep department array
	 * @return current department tree
	 */
	 private function get_child_node($id, $arr_dep){
	 	$dep_tree = array();
	 	foreach ($arr_dep as $dep) {
	 		if($dep['pid']==$id){   
	 			$node = array();             
	 			$node['id'] = $dep['id'];
	 			$node['title'] = $dep['name'];
	 			$node['subs'] = $this->get_child_node($dep['id'], $arr_dep);
	 			if(count($node['subs'])==0){
	 				unset($node['subs']);
	 			}
	 			$dep_tree[] = $node;
	 		} 
	 	} 
	 	return $dep_tree;
	 }


	/**
	 * get department name
	 * @param  integer $departmentid 
	 * @return object               
	 */
	public function hr_control_get_department_name($departmentid){
		return $this->db->query('select '.db_prefix().'departments.name from tbldepartments where departmentid = '.$departmentid)->row();
	}
	/**
	 * get all staff not in record
	 * @return array object
	 */
	public function get_all_staff_not_in_record(){
		return $this->db->query('select * from '.db_prefix().'staff where active = 1 AND staffid not in (select staffid from '.db_prefix().'hr_rec_transfer_records)')->result_array();
	}
	/**
	 * get setting transfer records
	 * @return array 
	 */
	public function get_setting_transfer_records(){
		return $this->db->get(db_prefix().'setting_transfer_records')->result_array();
	}
	/**
	 * get_staff_tree
	 * @return array 
	 */
	public function get_staff_tree(){
		$department =  $this->db->query('select s.staffid as id, s.team_manage as pid, CONCAT(s.firstname," ",s.lastname) as name
			from tblstaff as s         
			order by s.team_manage, s.staffid')->result_array();
		$dep_tree = array();
		foreach ($department as $dep) {
			if($dep['pid'] == 0){
				$node = array();
				$node['id'] = $dep['id'];
				$node['title'] = $dep['name'];

				$node['subs'] = $this->get_child_node_staff($dep['id'], $department);
				$dep_tree[] = $node;
			} else {
				break;
			}            
		}     
		return $dep_tree;
	}
		/**
	 * Get child node of department tree
	 * @param  $id      current department id
	 * @param  $arr_dep department array
	 * @return current department tree
	 */
		private function get_child_node_staff($id, $arr_dep){
			$dep_tree = array();
			foreach ($arr_dep as $dep) {
				if($dep['pid']==$id){   
					$node = array();             
					$node['id'] = $dep['id'];
					$node['title'] = $dep['name'];
					$node['subs'] = $this->get_child_node_staff($dep['id'], $arr_dep);
					if(count($node['subs']) == 0){
						unset($node['subs']);
					}
					$dep_tree[] = $node;
				} 
			} 
			return $dep_tree;
		}
	/**
	 * get all jp interview training
	 * @return object 
	 */
	public function get_all_jp_interview_training(){
		return $this->db->query('select * from '.db_prefix().'hr_jp_interview_training')->row();
	}
	/**
	 * get setting asset allocation
	 * @return array 
	 */
	public function get_setting_asset_allocation(){
		return $this->db->get(db_prefix().'setting_asset_allocation')->result_array();
	}

	/**
	 * get list record meta
	 * @return array 
	 */
	public function get_list_record_meta(){
		return $this->db->get(db_prefix().'records_meta')->result_array();
	}
	/**
	 * add setting transfer records
	 */
	public function add_setting_transfer_records($data_transfer_meta){
		$this->db->empty_table(db_prefix() . 'setting_transfer_records');
		$list_meta = $this->get_list_record_meta();
		foreach ($data_transfer_meta['meta'] as $key => $value) {
			if($value != ''){
				$name='';
				foreach ($list_meta as $list_item) {

					if($list_item['meta']==$value){
						$name=$list_item['name'];
					}
				}
				$this->db->insert(db_prefix().'setting_transfer_records', [
					'name' => $name,
					'meta' => $value
				]);
			}
		}  
	}
	/**
	 * add setting asset allocation
	 * @param array $data_asset_name 
	 */
	public function add_setting_asset_allocation($data_asset_name){
		$this->db->empty_table(db_prefix() . 'setting_asset_allocation');       
		foreach ($data_asset_name['name'] as $key => $value) {  
			if($value != ''){
				$this->db->insert(db_prefix().'setting_asset_allocation', [
					'name' => $value,
					'meta' => ''
				]);
			}              
		}
	} 


	/**
	 * add rec transfer records
	 * @param array $data_asset_name 
	 */
	public function add_rec_transfer_records($data)
	{     
		$this->db->insert(db_prefix().'hr_rec_transfer_records', [
			'staffid' => $data['staffid'],
			'creator' => get_staff_user_id(),
			'firstname' => $data['firstname'],
			'birthday' => $data['birthday'],
			'staff_identifi' => $data['staffidentifi']
		]);

		$insert_id = $this->db->insert_id();

		if ($insert_id) {
			return $insert_id;
		}
		return false;

	}


	/**
	 * group checklist
	 * @return array 
	 */
	public function group_checklist(){
		return $this->db->get(db_prefix().'group_checklist')->result_array();
	}
	/**
	 * get setting training 
	 * @return object
	 */
	public function get_setting_training(){
		return $this->db->get(db_prefix().'setting_training')->row();
	}


	/**
	 * get job position
	 * @param  integer $id 
	 * @return array or object     
	 */
	public function get_job_position($id = false)
	{
		if (is_numeric($id)) {
			$this->db->where('position_id', $id);
			return $this->db->get(db_prefix() . 'hr_job_position')->row();
		}
		if ($id == false) {
			return $this->db->query('select * from '.db_prefix().'hr_job_position')->result_array();
		}
	}




	/**
	 * get allowance type
	 * @param  integer $id 
	 * @return array or object      
	 */
	public function get_allowance_type($id = false){
		if (is_numeric($id)) {
			$this->db->where('type_id', $id);

			return $this->db->get(db_prefix() . 'hr_allowance_type')->row();
		}

		if ($id == false) {
			return  $this->db->get(db_prefix() . 'hr_allowance_type')->result_array();
		}

	}


	/**
	 * get salary form
	 * @param  integer $id 
	 * @return array or object
	 */
	public function get_salary_form($id = false){
		if (is_numeric($id)) {
			$this->db->where('form_id', $id);

			return $this->db->get(db_prefix() . 'hr_salary_form')->row();
		}

		if ($id == false) {
			return $this->db->query('select * from '.db_prefix().'hr_salary_form order by form_id desc')->result_array();
		}

	}



	/**
	 * get procedure retire
	 * @param  integer $id 
	 * @return array     
	 */
	public function get_procedure_retire($id = ''){
		if($id == ''){
			return $this->db->get(db_prefix().'hr_procedure_retire')->result_array();
		}else{
			$this->db->where('procedure_retire_id', $id);
			return $this->db->get(db_prefix().'hr_procedure_retire')->result_array();
		}
	}


	/**
	 * get allowance type tax
	 * @param  integer $id 
	 */
	public function get_allowance_type_tax($id = false){
		$this->db->where('taxable', "1");
		return  $this->db->get(db_prefix() . 'hr_allowance_type')->result_array();
	}



	/**
	 * add contract type
	 * @param array $data 
	 */
	public function add_contract_type($data){
		$this->db->insert(db_prefix() . 'hr_staff_contract_type', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}


	/**
	 * delete contract type
	 * @param  integer $id 
	 */
	public function delete_contract_type($id){
		$this->db->where('id_contracttype', $id);
		$this->db->delete(db_prefix() . 'hr_staff_contract_type');
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * add allowance type
	 * @param array $data 
	 */
	public function add_allowance_type($data){
		$data['allowance_val'] = hr_control_reformat_currency($data['allowance_val']);

		$this->db->insert(db_prefix() . 'hr_allowance_type', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}


	/**
	 * update allowance type
	 * @param  array $data 
	 * @param  integer $id   
	 * @return boolean       
	 */
	public function update_allowance_type($data, $id)
	{   
		$data['allowance_val'] = hr_control_reformat_currency($data['allowance_val']);
		
		$this->db->where('type_id', $id);
		$this->db->update(db_prefix() . 'hr_allowance_type', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}


	/**
	 * update contract type
	 * @param  array $data 
	 * @param  integer $id   
	 * @return boolean       
	 */
	public function update_contract_type($data, $id)
	{   
		$this->db->where('id_contracttype', $id);
		$this->db->update(db_prefix() . 'hr_staff_contract_type', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * delete allowance type
	 * @param  integer $id 
	 * @return boolean     
	 */
	public function delete_allowance_type($id){
		$this->db->where('type_id', $id);
		$this->db->delete(db_prefix() . 'hr_allowance_type');
		if ($this->db->affected_rows() > 0) {
			return true;
		}

		return false;
	}



	/**
	 * add salary form
	 * @param array $data 
	 */
	public function add_salary_form($data){
		$data['salary_val'] = hr_control_reformat_currency($data['salary_val']);

		$this->db->insert(db_prefix() . 'hr_salary_form', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}


	/**
	 * update salary form
	 * @param  array $data 
	 * @param  integer $id   
	 * @return boolean       
	 */
	public function update_salary_form($data, $id)
	{   
		$data['salary_val'] = hr_control_reformat_currency($data['salary_val']);

		$this->db->where('form_id', $id);
		$this->db->update(db_prefix() . 'hr_salary_form', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}


	/**
	 * delete salary form
	 * @param  integer $id 
	 * @return boolean     
	 */
	public function delete_salary_form($id){
		$this->db->where('form_id', $id);
		$this->db->delete(db_prefix() . 'hr_salary_form');
		if ($this->db->affected_rows() > 0) {
			return true;
		}

		return false;
	}



	/**
	 * add procedure form manage
	 * @param array $data 
	 */
	public function add_procedure_form_manage($data)
	{

		if(isset($data['departmentid'])){
			$data['department'] = json_encode($data['departmentid']);
			unset($data['departmentid']);
		}
		$data['datecreator'] = date('Y-m-d H:i:s');

		$this->db->insert(db_prefix().'hr_procedure_retire_manage',$data);
		$insert_id = $this->db->insert_id();

		if($insert_id){
			return $insert_id;
		}
		return false;
	}


	/**
	 * update procedure form manage
	 * @param  array $data 
	 * @param  integer $id   
	 * @return boolean       
	 */
	public function update_procedure_form_manage($data,$id)
	{
		if(isset($data['departmentid'])){
			$data['department'] = json_encode($data['departmentid']);
			unset($data['departmentid']);
		}
		if(isset($data['name_procedure_retire_edit'])){
			$data['name_procedure_retire'] = $data['name_procedure_retire_edit'];
			unset($data['name_procedure_retire_edit']);
		}

		$data['datecreator'] = date('Y-m-d H:i:s');

		$this->db->where('id',$id);
		$this->db->update(db_prefix().'hr_procedure_retire_manage',$data);
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;

	}


	/**
	 * get procedure form manage
	 * @param  integer $id 
	 * @return array or object     
	 */
	public function get_procedure_form_manage($id = '')
	{
		if ($id != '') {
			$this->db->where('id', $id);
			return $this->db->get(db_prefix() . 'hr_procedure_retire_manage')->row();
		}
		if ($id == '') {
			return $this->db->query('select * from '.db_prefix().'hr_procedure_retire_manage order by id desc')->result_array();
		}
	}


	/**
	 * delete procedure form manage
	 * @param  integer $id 
	 * @return boolean     
	 */
	public function delete_procedure_form_manage($id){
		$affected_rows = 0;
		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'hr_procedure_retire_manage');
		if ($this->db->affected_rows() > 0) {
			$affected_rows++;
		}

		$this->db->where('procedure_retire_id', $id);
		$this->db->delete(db_prefix() . 'hr_procedure_retire');
		if ($this->db->affected_rows() > 0) {
			$affected_rows++;
		}
		
		if ($affected_rows > 0) {
			return true;
		}
		return false;
	}


	/**
	 * check department on procedure
	 * @param  integer $departmentid 
	 * @return array               
	 */
	public function check_department_on_procedure($departmentid)
	{
		$data = $this->get_procedure_form_manage();

		$data_val = '';
		foreach ($data as $key => $value) {
			$departments = json_decode($value['department'], true);
			if(in_array((int)$departmentid,$departments)){
				$data_val = $value['id'];
				return $data_val;
			}
		}
		return $data_val;

	}


	/**
	 * add procedure retire
	 * @param array $data 
	 */
	public function add_procedure_retire($data){

		$data['option_name'] = json_encode($data['option_name'][1]);
		$data['rel_name'] = implode($data['rel_name']);
		$this->db->insert(db_prefix().'hr_procedure_retire', $data);

		$insert_id = $this->db->insert_id();

		return $insert_id;

	}

	/**
	 * delete procedure retire
	 * @param  integer $id 
	 * @return boolean     
	 */
	public function delete_procedure_retire($id){
		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'hr_procedure_retire');
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * get edit procedure retire
	 * @param  integer $id 
	 * @return object     
	 */
	public function get_edit_procedure_retire($id){
		$this->db->where('id', $id);
		return $this->db->get(db_prefix() . 'hr_procedure_retire')->row();
	}


	/**
	 * edit procedure retire
	 * @param  array $data 
	 * @param  integer $id   
	 * @return boolean       
	 */
	public function edit_procedure_retire($data, $id){
		$data['option_name'] = json_encode($data['option_name'][1]);
		$data['rel_name'] = implode($data['rel_name']);
		$this->db->where('id', $id);
		$this->db->update(db_prefix() . 'hr_procedure_retire', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}
	
	

	/**
	 * get job position training process
	 * @param  integer $id 
	 * @return array      
	 */
	public function get_job_position_training_process($id = false){
		if (is_numeric($id)) {
			$this->db->where('job_position_id', $id);
			return  $this->db->get(db_prefix() . 'hr_jp_interview_training')->result_array();
		}

		if ($id == false) {
		   return  $this->db->get(db_prefix() . 'hr_jp_interview_training')->result_array();
	   } 
   }
 /**
  * get job position interview process
  * @param  integer $id 
  * @return array or object      
  */
 public function get_job_position_interview_process($id = false){
	if(is_numeric($id)){
		$this->db->where('interview_process_id', $id);
		return  $this->db->get(db_prefix() . 'hr_jp_interview_training')->row();
	}

	if($id == false){
		return  $this->db->get(db_prefix() . 'hr_jp_interview_training')->result_array();
	}
}
	
	/**
	 * add position training
	 * @param [type] $data 
	 */
	public function add_position_training($data)
	{
		if (isset($data['disabled'])) {
			$data['active'] = 0;
			unset($data['disabled']);
		} else {
			$data['active'] = 1;
		}
		if (isset($data['iprestrict'])) {
			$data['iprestrict'] = 1;
		} else {
			$data['iprestrict'] = 0;
		}
		if (isset($data['onlyforloggedin'])) {
			$data['onlyforloggedin'] = 1;
		} else {
			$data['onlyforloggedin'] = 0;
		}
		$datecreated = date('Y-m-d H:i:s');
		$this->db->insert(db_prefix().'hr_position_training', [
			'subject'         => $data['subject'],
			'training_type'   => $data['training_type'],
			'slug'            => slug_it($data['subject']),
			'description'     => $data['description'],
			'viewdescription' => $data['viewdescription'],
			'datecreated'     => $datecreated,
			'active'          => $data['active'],
			'onlyforloggedin' => $data['onlyforloggedin'],
			'iprestrict'      => $data['iprestrict'],
			'hash'            => md5($datecreated),
			'fromname'        => $data['fromname'],
		]);
		$trainingid = $this->db->insert_id();
		if (!$trainingid) {
			// return false;
		}
		log_activity('New Training Type Added [ID: ' . $trainingid . ', Subject: ' . $data['subject'] . ']');

		return $trainingid;
	}


	/**
	 * update position training
	 * @param  [type] $data        
	 * @param  [type] $training_id 
	 * @return [type]              
	 */
	public function update_position_training($data, $training_id)
	{
		if (isset($data['disabled'])) {
			$data['active'] = 0;
			unset($data['disabled']);
		} else {
			$data['active'] = 1;
		}
		if (isset($data['onlyforloggedin'])) {
			$data['onlyforloggedin'] = 1;
		} else {
			$data['onlyforloggedin'] = 0;
		}
		if (isset($data['iprestrict'])) {
			$data['iprestrict'] = 1;
		} else {
			$data['iprestrict'] = 0;
		}
		$this->db->where('training_id', $training_id);
		$this->db->update(db_prefix().'hr_position_training', [
			'subject'         => $data['subject'],
			'training_type'   => $data['training_type'],
			'slug'            => slug_it($data['subject']),
			'description'     => $data['description'],
			'viewdescription' => $data['viewdescription'],
			'iprestrict'      => $data['iprestrict'],
			'active'          => $data['active'],
			'onlyforloggedin' => $data['onlyforloggedin'],
			'fromname'        => $data['fromname'],
		]);
		if ($this->db->affected_rows() > 0) {
			log_activity('Training Updated [ID: ' . $training_id . ', Subject: ' . $data['subject'] . ']');

			return true;
		}

		return false;
	}


	/**
	 * get position training
	 * @param  integer $id 
	 * @return array     
	 */
	public function get_position_training($id = '')
	{
		$this->db->where('training_id', $id);
		$position_training = $this->db->get(db_prefix().'hr_position_training')->row();
		if (!$position_training) {
			return false;
		}
		$this->db->where('rel_id', $position_training->training_id);
		$this->db->where('rel_type', 'position_training');
		$this->db->order_by('question_order', 'asc');
		$questions = $this->db->get(db_prefix().'hr_position_training_question_form')->result_array();
		$i         = 0;
		foreach ($questions as $question) {
			$this->db->where('questionid', $question['questionid']);
			$box                      = $this->db->get(db_prefix().'hr_p_t_form_question_box')->row();
			$questions[$i]['boxid']   = $box->boxid;
			$questions[$i]['boxtype'] = $box->boxtype;
			if ($box->boxtype == 'checkbox' || $box->boxtype == 'radio') {
				$this->db->order_by('questionboxdescriptionid', 'asc');
				$this->db->where('boxid', $box->boxid);
				$boxes_description = $this->db->get(db_prefix().'hr_p_t_form_question_box_description')->result_array();
				if (count($boxes_description) > 0) {
					$questions[$i]['box_descriptions'] = [];
					foreach ($boxes_description as $box_description) {
						$questions[$i]['box_descriptions'][] = $box_description;
					}
				}
			}
			$i++;
		}
		$position_training->questions = $questions;

		return $position_training;
	}


	/**
	 * add training question
	 * @param [type] $data 
	 */
	public function add_training_question($data)
	{
		$questionid = $this->insert_training_question($data['training_id']);
		if ($questionid) {
			$boxid    = $this->insert_question_type($data['type'], $questionid);
			$response = [
				'questionid' => $questionid,
				'boxid'      => $boxid,
			];
			if ($data['type'] == 'checkbox' or $data['type'] == 'radio') {
				$questionboxdescriptionid = $this->add_box_description($questionid, $boxid);
				array_push($response, [
					'questionboxdescriptionid' => $questionboxdescriptionid,
				]);
			}

			return $response;
		}

		return false;
	}


	/**
	 * insert training question
	 * @param  [type] $training_id 
	 * @param  string $question    
	 * @return [type]              
	 */
	private function insert_training_question($training_id, $question = '')
	{
		$this->db->insert(db_prefix().'hr_position_training_question_form', [
			'rel_id'   => $training_id,
			'rel_type' => 'position_training',
			'question' => $question,
		]);
		$insert_id = $this->db->insert_id();
		if ($insert_id) {
			log_activity('New Training Question Added [TrainingID: ' . $training_id . ']');
		}

		return $insert_id;
	}


	/**
	 * Add new question type
	 * @param  string $type       checkbox/textarea/radio/input
	 * @param  mixed $questionid question id
	 * @return mixed
	 */
	private function insert_question_type($type, $questionid)
	{
		$this->db->insert(db_prefix().'hr_p_t_form_question_box', [
			'boxtype'    => $type,
			'questionid' => $questionid,
		]);

		return $this->db->insert_id();
	}


	/**
	 * update question
	 * @param  array $data 
	 * @return boolean        
	 */
	public function update_question($data)
	{
		$_required = 1;
		if ($data['question']['required'] == 'false') {
			$_required = 0;
		}
		$affectedRows = 0;
		$this->db->where('questionid', $data['questionid']);
		$this->db->update(db_prefix().'hr_position_training_question_form', [
			'question' => $data['question']['value'],
			'required' => $_required,
			'point' => $data['question']['point'],
		]);
		if ($this->db->affected_rows() > 0) {
			$affectedRows++;
		}
		if (isset($data['boxes_description'])) {
			foreach ($data['boxes_description'] as $box_description) {
				$this->db->where('questionboxdescriptionid', $box_description[0]);
				$this->db->update(db_prefix().'hr_p_t_form_question_box_description', [
					'description' => $box_description[1],
				]);
				if ($this->db->affected_rows() > 0) {
					$affectedRows++;
				}
			}
		}
		if ($affectedRows > 0) {
			log_activity('Training Question Updated [QuestionID: ' . $data['questionid'] . ']');

			return true;
		}

		return false;
	}


	/**
	 * update survey questions orders
	 * @param  array $data 
	 */
	public function update_survey_questions_orders($data)
	{
		foreach ($data['data'] as $question) {
			$this->db->where('questionid', $question[0]);
			$this->db->update(db_prefix().'hr_position_training_question_form', [
				'question_order' => $question[1],
			]);
		}
	}


	/**
	 * remove question
	 * @param  integer $questionid 
	 * @return boolean             
	 */
	public function remove_question($questionid)
	{
		$affectedRows = 0;
		$this->db->where('questionid', $questionid);
		$this->db->delete(db_prefix().'hr_p_t_form_question_box_description');
		if ($this->db->affected_rows() > 0) {
			$affectedRows++;
		}
		$this->db->where('questionid', $questionid);
		$this->db->delete(db_prefix().'hr_p_t_form_question_box');
		if ($this->db->affected_rows() > 0) {
			$affectedRows++;
		}
		$this->db->where('questionid', $questionid);
		$this->db->delete(db_prefix().'hr_position_training_question_form');
		if ($this->db->affected_rows() > 0) {
			$affectedRows++;
		}
		if ($affectedRows > 0) {
			log_activity('Training Question Deleted [' . $questionid . ']');

			return true;
		}

		return false;
	}


	/**
	 * remove box description
	 * @param  integer $questionbod 
	 * @return boolean                           
	 */
	public function remove_box_description($questionboxdescriptionid)
	{
		$this->db->where('questionboxdescriptionid', $questionboxdescriptionid);
		$this->db->delete(db_prefix().'hr_p_t_form_question_box_description');
		if ($this->db->affected_rows() > 0) {
			return true;
		}

		return false;
	}


	/**
	 * add box description
	 * @param integer $questionid  
	 * @param integer $boxid       
	 * @param string $description
	 * @return  integer
	 */
	public function add_box_description($questionid, $boxid, $description = '')
	{
		$this->db->insert(db_prefix().'hr_p_t_form_question_box_description', [
			'questionid'  => $questionid,
			'boxid'       => $boxid,
			'description' => $description,
		]);

		return $this->db->insert_id();
	}
	

	/**
	 * add training result
	 * @param integer $id     
	 * @param array $result 
	 */
	public function add_training_result($id, $result)
	{
		$this->db->insert(db_prefix().'hr_p_t_surveyresultsets', [
			'date'      => date('Y-m-d H:i:s'),
			'trainingid'  => $id,
			'ip'        => $this->input->ip_address(),
			'useragent' => substr($this->input->user_agent(), 0, 149),
			'staff_id'  => get_staff_user_id(),
		]);
		$resultsetid = $this->db->insert_id();
		if ($resultsetid) {
			if (isset($result['selectable']) && sizeof($result['selectable']) > 0) {
				foreach ($result['selectable'] as $boxid => $question_answers) {
					foreach ($question_answers as $questionid => $answer) {
						$count = count($answer);
						for ($i = 0; $i < $count; $i++) {
							$this->db->insert(db_prefix().'hr_p_t_form_results', [
								'boxid'            => $boxid,
								'boxdescriptionid' => $answer[$i],
								'rel_id'           => $id,
								'rel_type'         => 'position_training',
								'questionid'       => $questionid,
								'answer'      	   => $answer[$i],
								'resultsetid'      => $resultsetid,
							]);
						}
					}
				}
			}
			unset($result['selectable']);
			if (isset($result['question'])) {
				foreach ($result['question'] as $questionid => $val) {
					$boxid = $this->get_training_question_box_id($questionid);
					$this->db->insert(db_prefix().'hr_p_t_form_results', [
						'boxid'       => $boxid,
						'rel_id'      => $id,
						'rel_type'    => 'position_training',
						'questionid'  => $questionid,
						'answer'      => $val[0],
						'resultsetid' => $resultsetid,
					]);
				}
			}

			return true;
		}

		return false;
	}



	/**
	 * get training question box id
	 * @param  integer $questionid 
	 * @return integer             
	 */
	private function get_training_question_box_id($questionid)
	{
		$this->db->select('boxid');
		$this->db->from(db_prefix().'hr_p_t_form_question_box');
		$this->db->where('questionid', $questionid);
		$box = $this->db->get()->row();

		return $box->boxid;
	}



	/**
	 * update answer question
	 * @param  array $data 
	 * @return array       
	 */
	public function update_answer_question($data)
	{
		$this->db->where('questionboxdescriptionid', $data['questionboxdescriptionid']);
		$this->db->update(db_prefix().'hr_p_t_form_question_box_description', [
			'correct' => $data['correct'],
		]);
		if ($this->db->affected_rows() > 0) {
			log_activity('Training Question Updated [QuestionID: questionboxdescriptionid ' . $data['questionboxdescriptionid'] . ']');
			return true;
		}
		return false;
	}


	/**
	 * get child training type
	 * @param  integer $id 
	 * @return array     
	 */
	public function get_child_training_type($id){
		$this->db->where('training_type',$id);
		$this->db->order_by('datecreated', 'desc');
		$rs = $this->db->get(db_prefix().'hr_position_training')->result_array();
		return  $rs;
	}


	/**
	 * add job position training process
	 * @param array $data 
	 */
	public function add_job_position_training_process($data){
		if(isset($data['department_id'])){
			unset($data['department_id']);
		}

		if(isset($data['additional_training'])){
			$data_staff_id = $data['staff_id'];
			if(isset($data['staff_id'])){
				$data['staff_id'] = implode(',', $data['staff_id']);
			}

			$data['time_to_start'] = to_sql_date($data['time_to_start']);
			$data['time_to_end'] = to_sql_date($data['time_to_end']);
		}

		$data['date_add'] = date('Y-m-d H:i:s');
		$data['position_training_id'] = implode(',',$data['position_training_id']);

		if(isset($data['job_position_id'])){
			$data['job_position_id'] = implode(',',$data['job_position_id']);
		}

		$this->db->insert(db_prefix().'hr_jp_interview_training',$data);
		$insert_id = $this->db->insert_id();

		if ($insert_id) {
			if(isset($data['additional_training'])){
				if(isset($data_staff_id) && count($data_staff_id) > 0){

					$training_description = '<div> '._l('a_new_training_program_is_assigned_to_you').'</div><br>';

					foreach ($data_staff_id as $staff_id) {
					//send notification
						$notified = add_notification([
							'description' => $training_description,
							'touserid' => $staff_id,
							'link' => 'hr_control/member/' . $staff_id.'/'.'training',
							'additional_data' => serialize([
								$training_description,
							]),
						]);

						if ($notified) {
							pusher_trigger_notification([$staff_id]);
						}
					}
				}
			}

			return $insert_id;
		}
		return false;
	}


	/**
	 * update job position training process
	 * @param  array $data 
	 * @param  integer $id   
	 * @return integer or boolean       
	 */
	public function update_job_position_training_process($data, $id){
		if(isset($data['department_id'])){
			unset($data['department_id']);
		}

		if(isset($data['additional_training'])){
			if(isset($data['staff_id'])){
				$data_staff_id = $data['staff_id'];
				$data['staff_id'] = implode(',', $data['staff_id']);
			}else{
				$data['staff_id'] = '';
			}

			$data['time_to_start'] = to_sql_date($data['time_to_start']);
			$data['time_to_end'] = to_sql_date($data['time_to_end']);

			$data['job_position_id'] = null;

		}else{
			$data['staff_id'] = '';
			$data['time_to_start'] = null;
			$data['time_to_end'] = null;
			$data['additional_training'] = '';

			if(isset($data['job_position_id'])){
				$data['job_position_id'] = implode(',',$data['job_position_id']);
			}else{
				$data['job_position_id'] = null;
			}

			$data['staff_id'] = null;
			$data['time_to_start'] = null;
			$data['time_to_end'] = null;
		}

		$data['date_add'] = date('Y-m-d H:i:s');
		$data['position_training_id'] = implode(',',$data['position_training_id']);


		$this->db->where('training_process_id', $id);
		$this->db->update(db_prefix().'hr_jp_interview_training',$data);
		if ($this->db->affected_rows() > 0) {

			if(isset($data['additional_training'])){
				if(isset($data_staff_id) && count($data_staff_id) > 0){

					$training_description = '<div> '._l('a_new_training_program_is_assigned_to_you').'</div><br>';

					foreach ($data_staff_id as $staff_id) {
					//send notification
						$notified = add_notification([
							'description' => $training_description,
							'touserid' => $staff_id,
							'link' => 'hr_control/member/' . $staff_id.'/'.'training',
							'additional_data' => serialize([
								$training_description,
							]),
						]);

						if ($notified) {
							pusher_trigger_notification([$staff_id]);
						}
					}
				}
			}

			return true;
		}
		return false;
	}


	/**
	 * get jobposition by department
	 * @param integer $department_id 
	 * @param  integer $status        
	 * @return string                
	 */
	// public function get_jobposition_by_department($department_id = '', $status)
	public function get_jobposition_by_department($status, $department_id = '')
	{
		$arr_staff_id=[];
		$index_dep = 0;
		if(is_array($department_id)){
			/*get staff in deaprtment start*/
			foreach ($department_id as $key => $value) {
				/*get staff in department*/
				$this->db->select('staffid');
				$this->db->where('departmentid', $value);

				$arr_staff = $this->db->get(db_prefix().'staff_departments')->result_array();
				if(count($arr_staff) > 0){
					foreach ($arr_staff as $value) {
						if(!in_array($value['staffid'], $arr_staff_id)){
							$arr_staff_id[$index_dep] = $value['staffid'];
							$index_dep++;
						}
					}
				}
			}
			/*get staff in deaprtment end*/
			$options = '';
			if(count($arr_staff_id) == 0){
				return $options;
			}
			/*get position start*/
			$arr_staff_id = implode(",", $arr_staff_id);
			$sql_where = 'SELECT '.db_prefix().'hr_job_position.position_id, position_name FROM '.db_prefix().'staff left join '.db_prefix().'hr_job_position on '.db_prefix().'staff.job_position = '.db_prefix().'hr_job_position.position_id WHERE '.db_prefix().'staff.job_position != "0" AND '.db_prefix().'staff.staffid IN ('.$arr_staff_id.')';
			
			
			$arr_job_position = $this->db->query($sql_where)->result_array();
			$arr_check_exist=[];
			foreach ($arr_job_position as $k => $note) {
				if(!in_array($note['position_id'], $arr_check_exist)){
					$select = ' selected';
					$options .= '<option value="' . $note['position_id'] . '" '.$select.'>' . $note['position_name'] . '</option>';
					$arr_check_exist[$k] = $note['position_id'];
				}
			}
			/*get position end*/
			return $options;
		}else{
			$arr_job_position = $this->get_job_position();
			$options = '';
			foreach ($arr_job_position as $note) {
				$options .= '<option value="' . $note['position_id'] . '">' . $note['position_name'] . '</option>';
			}
		  return $options;
		}
	}


  /**
   * get job position
   * @param  integer $id 
   * @return object or array      
   */
	public function get_job_p($id = false)
	{
		if (is_numeric($id)) {
			$this->db->where('job_id', $id);

			return $this->db->get(db_prefix() . 'hr_job_p')->row();
		}

		if ($id == false) {
			return $this->db->query('select * from tblhr_job_p')->result_array();
		}
	}


	/**
	 * add job position
	 * @param array $data 
	 */
	public function add_job_p($data)
	{
		$option = 'off';

		if(isset($data['create_job_position'])){
		   $option = $data['create_job_position'];
		   unset($data['create_job_position']);
		}

		$this->db->insert(db_prefix() . 'hr_job_p', $data);
		$insert_id = $this->db->insert_id();

		if($insert_id){
			if($option == 'on'){
				$data_position['position_name'] = $data['job_name'];
				$data_position['job_position_description'] = $data['description'];
				$data_position['job_p_id'] = $insert_id;
				$this->add_job_position($data_position);
			}
		}

		return $insert_id;
	}


	/**
	 * update job position
	 * @param  array $data 
	 * @param  integer $id   
	 * @return boolean       
	 */
	public function update_job_p($data, $id)
	{ 
		$this->db->where('job_id', $id);
		$this->db->update(db_prefix() . 'hr_job_p', $data);

		if ($this->db->affected_rows() > 0) {
			return true;
		}

		return true;
	}


	/**
	 * delete job position
	 * @param  integer $id 
	 * @return boolean     
	 */
	public function delete_job_p($id)
	{

		$this->db->where('job_id', $id);
		$this->db->delete(db_prefix() . 'hr_job_p');

		if ($this->db->affected_rows() > 0) {
			return true;
		}

		return false;
	}


	/**
	 * add job position
	 * @param aray $data 
	 */
	public function add_job_position($data)
	{
		if(isset($data['file'])){
			$files = $data['file'];
			unset($data['file']);
		}
		if(isset($data['tags'])){
			$tags = $data['tags'];
			unset($data['tags']);
		}

		if(isset($data['order'])){
			$orders = $data['order'];
			unset($data['order']);
		}

		if(isset($data['interview_name'])){
			$interview_names = $data['interview_name'];
			unset($data['interview_name']);
		}
		if(isset($data['rec_evaluation_form_id'])){
			$rec_evaluation_form_ids = $data['rec_evaluation_form_id'];
			unset($data['rec_evaluation_form_id']);
		}
		if(isset($data['rec_evaluation_form_id'])){
			$rec_evaluation_form_ids = $data['rec_evaluation_form_id'];
			unset($data['rec_evaluation_form_id']);
		}
		if(isset($data['head_unit'])){
			$head_units = $data['head_unit'];
			unset($data['head_unit']);
		}
		if(isset($data['specific_people'])){
			$specific_peoples = $data['specific_people'];
			unset($data['specific_people']);
		}
		if(isset($data['description'])){
			$descriptions = $data['description'];
			unset($data['description']);
		}

		if(isset($data['training_process_order'])){
			$descriptions = $data['training_process_order'];
			unset($data['training_process_order']);
		}

		if(isset($data['training_process_id'])){
			$descriptions = $data['training_process_id'];
			unset($data['training_process_id']);
		}
		if(isset($data['department_id'])){
			$data['department_id'] = implode(',', $data['department_id']);
		}

		$this->db->insert(db_prefix() . 'hr_job_position', $data);
		$insert_id = $this->db->insert_id();

		if ($insert_id) {
			if(isset($tags)){
				handle_tags_save($tags, $insert_id, 'job_position');
			}

			/*update next number setting*/
			$this->update_prefix_number(['job_position_number' =>  get_hr_control_option('job_position_number')+1]);
		}

		return $insert_id;
	}


	 /**
	 * update job position
	 * @param aray $data 
	 */
	 public function update_job_position($data, $id)
	 {   
		$affected_rows = 0;

		if(isset($data['file'])){
			$files = $data['file'];
			unset($data['file']);
		}


		if(strlen($data['tags']) > 0){

			$this->db->where('rel_id', $id);
			$this->db->where('rel_type', 'job_position');
			$arr_tag = $this->db->get(db_prefix() . 'taggables')->result_array();

			if(count($arr_tag) > 0){
	        	//update
				$arr_tag_insert =  explode(',', $data['tags']);
				/*get order last*/
				$total_tag = count($arr_tag);
				$tag_order_last = $arr_tag[$total_tag-1]['tag_order']+1;

				foreach ($arr_tag_insert as $value) {
					/*insert tbl tags*/  
					$this->db->insert(db_prefix() . 'tags', ['name' => $value]);
					$insert_tag_id = $this->db->insert_id();

					/*insert tbl taggables*/
					if($insert_tag_id){
						$this->db->insert(db_prefix() . 'taggables', ['rel_id' => $id, 'rel_type'=>'job_position', 'tag_id' => $insert_tag_id, 'tag_order' => $tag_order_last]);
						$this->db->insert_id();

						$tag_order_last++;

						$affected_rows++;
					}

				}

			}else{
	        	//insert
				handle_tags_save($data['tags'], $id, 'job_position');
				$affected_rows++;

			}
		}

		if (isset($data['tags'])) {
			unset($data['tags']);
		}


		if(isset($data['department_id'])){
			$data['department_id'] = implode(',', $data['department_id']);
		}else{
			$data['department_id'] = null;
		}

		$this->db->where('position_id', $id);
		$this->db->update(db_prefix() . 'hr_job_position', $data);
		if ($this->db->affected_rows() > 0) {
			$affected_rows++;
		}

		if($affected_rows > 0){
			return true;
		}
		return false;
	}


	/**
 * delete job position
 * @param aray $data 
 */
	public function delete_job_position($id){
		

			//delete atachement file
			$job_position_file = $this->get_hr_control_file($id, 'job_position');
			foreach ($job_position_file as $file_key => $file_value) {
				$this->delete_hr_job_position_attachment_file($file_value['id']);
			}

			//delete tags
			$this->db->where('rel_id', $id);
			$this->db->where('rel_type', 'job_position');
			$arr_tag = $this->db->get(db_prefix() . 'taggables')->result_array();
			foreach ($arr_tag as $tag_key => $tag_value) {
				//delete tag item
				$this->db->where('id', $tag_value['tag_id']);
				$this->db->delete(db_prefix() . 'tags');
			}

			$this->db->where('rel_id', $id);
			$this->db->where('rel_type', 'job_position');
			$this->db->delete(db_prefix() . 'taggables');

			//delete salary scale
			$this->db->where('job_position_id', $id);
			$this->db->delete(db_prefix() . 'hr_jp_salary_scale');
			//delete table job position
			$this->db->where('position_id', $id);
			$this->db->delete(db_prefix() . 'hr_job_position');
			if ($this->db->affected_rows() > 0) {
				return true;
			}
	}


	/**
	 * get list job position tags file
	 * @param  [type] $job_position_id 
	 * @return [type]                  
	 */
	public function get_list_job_position_tags_file($job_position_id)
	{
		$data=[];
		$arr_file = $this->get_hrm_attachments_file($job_position_id, 'job_position');

		/* get list tinymce start*/
		$this->db->from(db_prefix() . 'taggables');
		$this->db->join(db_prefix() . 'tags', db_prefix() . 'tags.id = ' . db_prefix() . 'taggables.tag_id', 'left');

		$this->db->where(db_prefix() . 'taggables.rel_id', $job_position_id);
		$this->db->where(db_prefix() . 'taggables.rel_type', 'job_position');
		$this->db->order_by('tag_order', 'ASC');

		$job_position_tags = $this->db->get()->result_array();

		$html_tags='';
		foreach ($job_position_tags as $tag_value) {
			$html_tags .='<li class="tagit-choice ui-widget-content ui-state-default ui-corner-all tagit-choice-editable tag-id-'.$tag_value['id'].' true" value="'.$tag_value['id'].'">
			<span class="tagit-label">'.$tag_value['name'].'</span>
			<a class="tagit-close">
			<span class="text-icon">×</span>
			<span class="ui-icon ui-icon-close"></span>
			</a>
			</li>';
		}

		$htmlfile='';
		//get file attachment html
		if(isset($arr_file)){
		   $htmlfile = '<div class="row col-md-12" id="attachment_file">';
		   foreach($arr_file as $attachment) {
			  $href_url = site_url('modules/hrm/uploads/job_position/'.$attachment['rel_id'].'/'.$attachment['file_name']).'" download';
			  if(!empty($attachment['external'])){
					$href_url = $attachment['external_link'];
				}

				$htmlfile .= '<div class="display-block contract-attachment-wrapper">';
				$htmlfile .= '<div class="col-md-10">';
				$htmlfile .= '<div class="col-md-1 mr-5">';
				$htmlfile .= '<a name="preview-btn" onclick="preview_file_job_position(this); return false;" rel_id = "'.$attachment['rel_id'].'" id = "'.$attachment['id'].'" href="Javascript:void(0);" class="mbot10 btn btn-success pull-left" data-toggle="tooltip" title data-original-title="'._l("preview_file").'">';
				$htmlfile .= '<i class="fa fa-eye"></i>'; 
				$htmlfile .= '</a>';
				$htmlfile .= '</div>';
				$htmlfile .= '<div class=col-md-9>';
				$htmlfile .= '<div class="pull-left"><i class="'.get_mime_class($attachment['filetype']).'"></i></div>';
				$htmlfile .= '<a href="'.$href_url.'>'.$attachment['file_name'].'</a>';
				$htmlfile .= '<p class="text-muted">'.$attachment["filetype"].'</p>';
				$htmlfile .= '</div>';
				$htmlfile .= '</div>';
				$htmlfile .= '<div class="col-md-2 text-right">';
				if(has_permission('staffmanage_job_position', '', 'delete')){
				   $htmlfile .= '<a href="#" class="text-danger" onclick="delete_job_position_attachment(this,'.$attachment['id'].'); return false;"><i class="fa fa fa-times"></i></a>';
				}

				$htmlfile .= '</div>';
				$htmlfile .= '<div class="clearfix"></div><hr/>';
				$htmlfile .= '</div>';
			}

			$htmlfile .= '</div>';
		}

		$data['htmltag']    = $html_tags;  
		$data['htmlfile']   = $htmlfile;  

		return $data;
	}


	/**
	 * get hrm attachments file
	 * @param  [type] $rel_id   
	 * @param  [type] $rel_type 
	 * @return [type]           
	 */
	public function get_hrm_attachments_file($rel_id, $rel_type){
		//contract : //rel_id = $id_contract, rel_type = 'hrm_contract'
		
		$this->db->order_by('dateadded', 'desc');
		$this->db->where('rel_id', $rel_id);
		$this->db->where('rel_type', $rel_type);

		return $this->db->get(db_prefix() . 'files')->result_array();

	}

	/**
	 * get department from job p
	 * @param  integer $job_p_id 
	 * @return array           
	 */
	public function get_department_from_job_p($job_p_id)
	{   
		$data=[];
		$index=0;

		$this->db->where('job_p_id', $job_p_id);
		$job_position =  $this->db->get(db_prefix().'hr_job_position')->result_array();
		if(count($job_position) > 0){
			foreach ($job_position as $job_value) {
				if($job_value['department_id'] != null && $job_value['department_id'] != ''){

				 $arr = explode(',', $job_value['department_id']);
				 foreach ($arr as $arr_value) {
					 if(!in_array($arr_value, $data)){
						$data[$index] = $arr_value;
						$index ++;
					}
				}
			}
		}
	}
	return $data;
}


	/**
	 * check child in job position
	 * @param  integer $id 
	 * @return boolean     
	 */
	public function check_child_in_job_p($id)
	{
		$this->db->where('job_p_id', $id);
		$arr_job_chil = $this->db->get(db_prefix() . 'hr_job_position')->result_array();

		foreach ($arr_job_chil as $key => $value) {
			if (is_reference_in_table('job_position', db_prefix() . 'staff', $value['position_id'])) {
			   return true;;
			}
		}
		return false;
	}


/**
 * get array job position
 * @param  integer $id 
 * @return boolean      
 */
public function get_array_job_position($id = false)
{
	if (is_numeric($id)) {
		$this->db->where('job_p_id', $id);
		return $this->db->get(db_prefix() . 'hr_job_position')->result_array();
	}
	return false;
}
/**
 * get job position tag
 * @param  integer $id 
 */
public function get_job_position_tag($id=''){
	/* get list tinymce start*/
	$this->db->from(db_prefix() . 'taggables');
	$this->db->join(db_prefix() . 'tags', db_prefix() . 'tags.id = ' . db_prefix() . 'taggables.tag_id', 'left');
	$this->db->where(db_prefix() . 'taggables.rel_id', $id);
	$this->db->where(db_prefix() . 'taggables.rel_type', 'job_position');
	$this->db->order_by('tag_order', 'ASC');
	$job_position_tags = $this->db->get()->result_array();
	return $job_position_tags;
}
	/**
	* get array interview process by position id
	* @param  integer $id
	* @return  array
	*/
	public function get_interview_process_byposition($id = false){
		if (is_numeric($id)) {
			$sql_where ='find_in_set("'.$id.'", job_position_id)';
			$this->db->where($sql_where);
			$this->db->order_by('interview_process_id', 'desc');
			return  $this->db->get(db_prefix() . 'jp_interview_process')->result_array();
		}

	}
	/**
	* get array training process by position id
	* @param  integer $id
	* @return  array
	*/
	public function get_traing_process_byposition($id = false){
		if (is_numeric($id)) {
			$sql_where ='find_in_set("'.$id.'", job_position_id)';
			$this->db->where($sql_where);
			$this->db->order_by('training_process_id', 'desc');
			return  $this->db->get(db_prefix() . 'hr_jp_interview_training')->result_array();
		}
	}
	/**
	 * get job position salary scale
	 * @param  integer $job_position_id 
	 * @return array                  
	 */
	public function get_job_position_salary_scale($job_position_id){
		$data=[];
		$salary_insurance = 0;
		$salary_form = [];        
		$salary_allowance = [];   

		$this->db->where('job_position_id', $job_position_id);
		$arr_salary_sacale = $this->db->get(db_prefix() . 'hr_jp_salary_scale')->result_array();

		foreach ($arr_salary_sacale as $key => $value) {
			switch ($value['rel_type']) {
				case 'insurance':
					# code...
				$salary_insurance = $value['value'];
				break;
				
				case 'salary':
					# code...
				array_push($salary_form, $arr_salary_sacale[$key]);
				break;
				
				case 'allowance':
					# code...
				array_push($salary_allowance, $arr_salary_sacale[$key]);
				break;
			}

		}
		$data['insurance'] = $salary_insurance;
		$data['salary'] = $salary_form;
		$data['allowance'] = $salary_allowance;

		return $data;
	}
	/**
	 * get hr profile attachments file
	 * @param  integer $rel_id   
	 * @param  integer $rel_type 
	 * @return array           
	 */
	public function get_hr_control_attachments_file($rel_id, $rel_type){        
		$this->db->order_by('dateadded', 'desc');
		$this->db->where('rel_id', $rel_id);
		$this->db->where('rel_type', $rel_type);
		return $this->db->get(db_prefix() . 'files')->result_array();
	}
	/**
	 * get department from position department
	 * @param  array $arr_value 
	 * @param  integer $position  
	 * @return string            
	 */
	public function get_department_from_position_department($arr_value, $position)
	{
		$job_p_id='';

		$job_p=[];
		$index_dep = 0;

		if($position == false){

		 foreach ($arr_value as $key => $value) {
			$sql_where = 'find_in_set('.$value.', department_id)';
			$this->db->where($sql_where);
			$arr_job_position = $this->db->get(db_prefix().'hr_job_position')->result_array();

			if(count($arr_job_position) > 0){
				foreach ($arr_job_position as $value) {
					if(!in_array($value['job_p_id'], $job_p)){

						$job_p[$index_dep] = $value['job_p_id'];
						$index_dep++;

					}


				}
			}

		}

		if(count($job_p) > 0){
			$job_p_id .= implode(',', $job_p);
		}

	}else{
		foreach ($arr_value as $key => $value) {

			$this->db->where('position_id', $value);
			$arr_job_position = $this->db->get(db_prefix().'hr_job_position')->result_array();

			if(count($arr_job_position) > 0){
				foreach ($arr_job_position as $value) {
					if(!in_array($value['job_p_id'], $job_p)){

						$job_p[$index_dep] = $value['job_p_id'];
						$index_dep++;

					}


				}
			}

		}
		if(count($job_p) > 0){
			$job_p_id .= implode(',', $job_p);
		}

	}
	return $job_p_id;
}
/**
 * get position by department
 * @param integer $department_id 
 * @param  integer $status        
 * @return string                
 */
public function get_position_by_department($department_id, $status)
{

	$job_position=[];
	$index_dep = 0;
	$options = '';

	if(is_array($department_id))
	{
		/*get staff in deaprtment start*/
		foreach ($department_id as $key => $value) {
			$sql_where = 'find_in_set('.$value.', department_id)';
			$this->db->where($sql_where);
			$arr_job_position = $this->db->get(db_prefix().'hr_job_position')->result_array();

			if(count($arr_job_position) > 0){
				foreach ($arr_job_position as $value) {
					if(!in_array($value['position_id'], $job_position)){
						$options .= '<option value="' . $value['position_id'] . '">' . $value['position_name'] . '</option>';

						$job_position[$index_dep] = $value['position_id'];
						$index_dep++;
					}
				}
			}
		}
		return $options;
	}else{

		$arr_job_position = $this->get_job_position();
		$options = '';
		foreach ($arr_job_position as $note) {

		  $options .= '<option value="' . $note['position_id'] . '">' . $note['position_name'] . '</option>';
	  }
	  return $options;
  }
}


	/**
	 * job position add update salary scale
	 * @param  array $data 
	 * @return boolean       
	 */
	public function job_position_add_update_salary_scale($data){
		if(isset($data['job_position_id'])){
			$job_position_id = $data['job_position_id'];
			unset($data['job_position_id']);
		}
		$this->db->where('job_position_id', $job_position_id);
		$this->db->delete(db_prefix().'hr_jp_salary_scale');

		$this->db->insert(db_prefix().'hr_jp_salary_scale',[
			'job_position_id' => $job_position_id,
			'rel_type' => 'insurance',
			'value' => hr_control_reformat_currency($data['premium_rates']),
		]);
		foreach($data['salary_form'] as $salary_key => $salary_value){

			$this->db->insert(db_prefix().'hr_jp_salary_scale', [
				'job_position_id' => $job_position_id,
				'rel_type' => 'salary',
				'rel_id' => $salary_value,
				'value' =>  hr_control_reformat_currency($data['contract_expense'][$salary_key]),
			]);
		}
		foreach($data['allowance_type'] as $allowance_key => $allowance_value){

			$this->db->insert(db_prefix().'hr_jp_salary_scale', [
				'job_position_id' => $job_position_id,
				'rel_type' => 'allowance',
				'rel_id' => $allowance_value,
				'value' =>  hr_control_reformat_currency($data['allowance_expense'][$allowance_key]),
			]);
		}
		return true;
	}


	/**
	 * get staff
	 * @param  integer $id    
	 * @param  array  $where 
	 * @return array        
	 */
	public function get_staff($id = '', $where = [])
	{
		$select_str = '*,CONCAT(firstname," ",lastname) as full_name';
		if (is_staff_logged_in() && $id != '' && $id == get_staff_user_id()) {
			$select_str .= ',(SELECT COUNT(*) FROM ' . db_prefix() . 'notifications WHERE touserid=' . get_staff_user_id() . ' and isread=0) as total_unread_notifications, (SELECT COUNT(*) FROM ' . db_prefix() . 'todos WHERE finished=0 AND staffid=' . get_staff_user_id() . ') as total_unfinished_todos';
		}

		$this->db->select($select_str);
		$this->db->where($where);

		if (is_numeric($id)) {
			$this->db->where('staffid', $id);
			$staff = $this->db->get(db_prefix() . 'staff')->row();

			if ($staff) {
				$staff->permissions = $this->get_staff_permissions($id);
			}

			return $staff;
		}
		$this->db->order_by('firstname', 'desc');

		return $this->db->get(db_prefix() . 'staff')->result_array();
	}


	/**
	 * add manage info reception
	 * @param array $data 
	 */
	public function add_manage_info_reception($data)
	{
		$this->db->empty_table(db_prefix() . 'group_checklist');       
		$this->db->empty_table(db_prefix() . 'checklist');       
		foreach ($data['title_name'] as $key => $menu) {
			if($menu != ''){
				$data_s['group_name'] = $menu;
				$this->db->insert(db_prefix() . 'group_checklist', $data_s);
				$insert_id = $this->db->insert_id();

				if(isset($data['sub_title_name'][$key])){
					foreach ($data['sub_title_name'][$key] as $sub_menu) {
						if($sub_menu != ''){
							$data_ss['name'] = $sub_menu;
							$data_ss['group_id'] = $insert_id;
							$this->db->insert(db_prefix() . 'checklist', $data_ss);
						}                      
					}
				}

			}         
		}
	}


	/**
	 * add setting training
	 */
	public function add_setting_training($data)
	{
		if(isset($data['training_type'])){
			$this->db->empty_table(db_prefix() . 'setting_training');  
			$this->db->insert(db_prefix() . 'setting_training', $data);  
		}   
	}


	/**
	 * checklist by group
	 * @param  integer $group_id 
	 * @return array           
	 */
	public function checklist_by_group($group_id = ''){
		$this->db->where('group_id', $group_id);
		return $this->db->get(db_prefix().'checklist')->result_array();
	}


	/**
	 * count max checklist
	 * @return [type] 
	 */
	public function count_max_checklist()
	{
		$sql_where = "SELECT count(id) as total_sub_item  FROM ".db_prefix()."checklist
						group by group_id
						order by total_sub_item desc limit 1";
		$max_sub_item = $this->db->query($sql_where)->row();

		if($max_sub_item){
			return (float)$max_sub_item->total_sub_item;
		}

		return 1;
	}


	/**
	 * get staff info id
	 * @param  [type] $staffid 
	 * @return [type]          
	 */
	public function get_staff_info_id($staffid){
		$this->db->where('staffid', $staffid);
		return $this->db->get(db_prefix().'staff')->row();
	}

	/**
	 * add_manage_info_reception_for_staff
	 * @param integer $id_staff 
	 * @param integer $data     
	 */
	public function add_manage_info_reception_for_staff($id_staff, $data)
	{
		if(isset($data['sub_title_name'])&&isset($data['title_name'])){
			foreach ($data['title_name'] as $key => $menu) {
				if($menu != ''){
					$data_s['group_name'] = $menu;
					$data_s['staffid'] = $id_staff;
					$this->db->insert(db_prefix() . 'hr_group_checklist_allocation', $data_s);
					$insert_id = $this->db->insert_id();
					if(isset($data['sub_title_name'][$key])){
						foreach ($data['sub_title_name'][$key] as $sub_menu) {
							if($sub_menu != ''){
								$data_ss['name'] = $sub_menu;
								$data_ss['group_id'] = $insert_id;
								$this->db->insert(db_prefix() . 'hr_checklist_allocation', $data_ss);
							}                      
						}
					}

				}         
			}
		}            
	} 


	/**
	 * add asset staff
	 * @param integer $id   
	 * @param array $data 
	 */
	public function add_asset_staff($id,$data){  
		foreach ($data as $key => $value) {
			$this->db->insert(db_prefix() . 'hr_allocation_asset', [
				'staff_id'      => $id,
				'asset_name' => $value['name'],
				'assets_amount' => '1']);
		}
	}


	/**
	 * get jp interview training
	 * @param  integer $position_id   
	 * @param  integer $training_type 
	 * @return object                
	 */
	public function get_jp_interview_training($position_id, $training_type = ''){
		if($training_type==''){
			$type_training = $this->getTraining_Setting();    
			if($type_training){
				return $this->db->query('select * from '.db_prefix().'hr_jp_interview_training where find_in_set('.$position_id.',job_position_id) and training_type = \''.$type_training->training_type.'\' ORDER BY date_add desc limit 1')->row();
			}
			else{
				return $this->db->query('select * from '.db_prefix().'hr_jp_interview_training where find_in_set('.$position_id.',job_position_id) ORDER BY date_add desc limit 1')->row();
			}
		}
		else{
			return $this->db->query('select * from '.db_prefix().'hr_jp_interview_training where find_in_set('.$position_id.',job_position_id) and training_type = \''.$training_type.'\' ORDER BY date_add desc limit 1')->row();
		}
	}


	/**
	 * add training staff
	 * @param integer $data_training 
	 * @param integer $id_staff      
	 */
	public function add_training_staff($data_training,$id_staff){
		$data['staffid'] = $id_staff;
		$explode = explode(',', $data_training->position_training_id);
		$data['training_process_id'] = implode(',',array_unique($explode));
		$data['training_type'] = $data_training->training_type;
		$data['training_name'] = $data_training->training_name;
		$data['jp_interview_training_id'] = $data_training->training_process_id;

		$this->db->insert(db_prefix() . 'hr_training_allocation', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}


/**
 * add transfer records reception
 * @param array $data    
 * @param integer $staffid 
 */
public function add_transfer_records_reception($data,$staffid){
 $list_meta = $this->get_list_record_meta();
 foreach ($data as $key => $value) {
	$name='';
	foreach ($list_meta as $list_item) {
		if($list_item['meta']==$value){
			$name=$list_item['name'];
		}
	}
	$this->db->insert(db_prefix().'hr_transfer_records_reception', [
		'name' => $name,
		'meta' => $value,
		'staffid' => $staffid
	]);
}  
}
/**
 * getPercent
 * @param  integer $total  
 * @param  integer $effect 
 * @return foat         
 */
public function getPercent($total,$effect){
	if($total == 0){
		return 0;
	}
	return number_format(($effect * 100 / $total), 0);
}


	/**
	 * get group checklist allocation by staff id
	 * @param  integer $staffid 
	 * @return integer          
	 */
	public function get_group_checklist_allocation_by_staff_id($staffid){
		$this->db->where('staffid', $staffid);
		return $this->db->get(db_prefix().'hr_group_checklist_allocation')->result_array();
	}


	/**
	 * get checklist allocation by group id
	 * @param  integer $id_group 
	 * @return array           
	 */
	public function get_checklist_allocation_by_group_id($id_group){
		$this->db->where('group_id', $id_group);
		return $this->db->get(db_prefix().'hr_checklist_allocation')->result_array();
	}


	/**
	 * get resultset training
	 * @param  integer $id 
	 * @return integer     
	 */
	public function get_resultset_training($id, $training_process_id){
	   return $this->db->query('select * from '.db_prefix().'hr_p_t_surveyresultsets where staff_id = \''.$id.'\' AND trainingid IN ('.$training_process_id.') order by date desc')->result_array();
	}


	/**
	 * get allocation asset
	 * @param  integer $staff_id 
	 * @return array           
	 */
	public function get_allocation_asset($staff_id){
		$this->db->where('staff_id',$staff_id);
		return $this->db->get(db_prefix().'hr_allocation_asset')->result_array();
	}


/**
 * get result training staff
 * @param  integer $list_resultsetid 
 * @return array                   
 */
public function get_result_training_staff($list_resultsetid){
  return $this->db->query('select * from '.db_prefix().'hr_p_t_form_results where resultsetid in ('.$list_resultsetid.')')->result_array();
}

	/**
	 * get id result correct
	 * @param  integer $id_question 
	 * @return object              
	 */
	public function get_id_result_correct($question_id){
		$boxdescriptionids =[];
		$this->db->where('questionid', $question_id);
		$this->db->where('correct', 0);
		$result = $this->db->get(db_prefix().'hr_p_t_form_question_box_description')->result_array();

		foreach ($result as $value) {
		    array_push($boxdescriptionids, $value['questionboxdescriptionid']);
		}
		return $boxdescriptionids;
	}


	/**
	 * get point training question form
	 * @param  [type] $id_question 
	 * @return [type]              
	 */
	public function get_point_training_question_form($id_question){
        $this->db->where('questionid',$id_question);
        return $this->db->get(db_prefix().'hr_position_training_question_form')->row();
    }


	/**
	 * delete manage info reception
	 * @param  integer $id 
	 */
	public function delete_manage_info_reception($id){
		$this->db->where('staffid', $id);
		$list = $this->db->get(db_prefix().'hr_group_checklist_allocation')->result_array();
		$this->db->where('staffid', $id);
		$this->db->delete(db_prefix().'hr_group_checklist_allocation');
		foreach ($list as $sub_menu) {
			$this->db->where('group_id', $sub_menu['id']);
			$this->db->delete(db_prefix().'hr_checklist_allocation');
		}                         
	}


	/**
	 * delete setting training
	 * @param  integer $id 
	 */
	public function delete_setting_training($id){
		$this->db->where('staffid', $id);
		$this->db->delete(db_prefix().'hr_training_allocation');

		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}


	/**
	 * delete setting asset allocation
	 * @param  integer $id 
	 * @return integer     
	 */
	public function delete_setting_asset_allocation($id){
		$this->db->where('staff_id', $id);
		$this->db->delete(db_prefix().'hr_allocation_asset');

		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}


	/**
	 * delete reception
	 * @param  integer $id 
	 * @return boolean     
	 */
	public function delete_reception($id){
		$this->db->where('staffid', $id);
		$this->db->delete(db_prefix() . 'hr_rec_transfer_records');
		if ($this->db->affected_rows() > 0) {
			$this->db->where('staffid', $id);
			$this->db->delete(db_prefix() . 'hr_training_allocation');

			$this->db->where('staff_id', $id);
			$this->db->delete(db_prefix() . 'hr_allocation_asset');


			$this->db->where('staffid', $id);
			$data_checklist = $this->db->get(db_prefix().'hr_group_checklist_allocation')->result_array();
			if(isset($data_checklist)){
				if($data_checklist){
					$this->db->where('staffid', $id);
					$this->db->delete(db_prefix() . 'hr_group_checklist_allocation');
					foreach ($data_checklist as $key => $checklist) {
						$this->db->where('group_id', $checklist['id']);
						$this->db->delete(db_prefix() . 'hr_checklist_allocation');                                         
					}                    
				}
			}
			return true;
		}
		return false;
	}


	/**
	 * get department by staffid
	 * @param  integer $id_staff 
	 * @return object           
	 */
	public function get_department_by_staffid($id_staff){
		$this->db->where('staffid',$id_staff);
		$departments = $this->db->get(db_prefix().'staff_departments')->result_array();
		$w = '0';
		if(isset($departments[0]['departmentid'])){
			$w = $departments[0]['departmentid'];
		}
		return $this->db->query('select * from '.db_prefix().'departments where departmentid = '.$w)->row();
	}


/**
 * get transfer records reception staff
 * @param  integer $id 
 * @return integer     
 */
public function get_transfer_records_reception_staff($id){
	$this->db->where('staffid',$id);
	return $this->db->get(db_prefix().'hr_transfer_records_reception')->result_array();
}
/**
 * update checklist
 * @param  array $data 
 * @return boolean       
 */
public function update_checklist($data){ 
	$this->db->where('id', $data['checklist_id']);
	$this->db->update(db_prefix() . 'hr_checklist_allocation', ['status' => $data['status_checklist']]);
	if ($this->db->affected_rows() > 0) {
		return true;
	}
	return false;
}
/**
 * delete tag item
 * @param  array $data 
 * @return boolean       
 */
public function delete_tag_item($tag_id){
	$count_af = 0;
	$this->db->where(db_prefix() . 'taggables.tag_id', $tag_id);
	$this->db->delete(db_prefix() . 'taggables');
	if ($this->db->affected_rows() > 0) {
	   $count_af++;
   }
   $this->db->where(db_prefix() . 'tags.id', $tag_id);
   $this->db->delete(db_prefix() . 'tags');
   if ($this->db->affected_rows() > 0) {
	   $count_af++;
   }
   return $count_af > 0 ?  true :  false;
}


	/**
	 * add new asset staff
	 * @param integer $id   
	 * @param array $data 
	 */
	public function add_new_asset_staff($id,$data)
	{  
		foreach ($data as $key => $value) {
			if($value != ''){
			  $this->db->insert(db_prefix() . 'hr_allocation_asset', [
				'staff_id'      => $id,
				'asset_name' => $value,
				'assets_amount' => '1',
				]);
			}
		}

	}


	/**
	 * update asset staff
	 * @param  array $data 
	 * @return boolean       
	 */
	public function update_asset_staff($data){ 
		$this->db->where('allocation_id', $data['allocation_id']);
		$this->db->update(db_prefix() . 'hr_allocation_asset', ['status_allocation' => $data['status_allocation']]);
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}


	/**
	 * delete allocation asset
	 * @param  integer $allocation_id 
	 * @return boolean                
	 */
	public function delete_allocation_asset($allocation_id){
		$this->db->where('allocation_id',$allocation_id);
		$this->db->delete(db_prefix() . 'hr_allocation_asset');
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}


	/**
	 * get training allocation staff
	 * @param  integer $id 
	 * @return object     
	 */
	public function get_training_allocation_staff($id){
		$this->db->where('staffid',$id);
		return $this->db->get(db_prefix().'hr_training_allocation')->row();
	}



 /**
	 * @param  integer ID (option)
	 * @param  boolean (optional)
	 * @return mixed
	 * Get departments where staff belongs
	 * If $onlyids passed return only departmentsID (simple array) if not returns array of all departments
	 */
 public function get_staff_departments($userid = false, $onlyids = false)
 {
	if ($userid == false) {
		$userid = get_staff_user_id();
	}
	if ($onlyids == false) {
		$this->db->select();
	} else {
		$this->db->select(db_prefix() . 'staff_departments.departmentid');
	}
	$this->db->from(db_prefix() . 'staff_departments');
	$this->db->join(db_prefix() . 'departments', db_prefix() . 'staff_departments.departmentid = ' . db_prefix() . 'departments.departmentid', 'left');
	$this->db->where('staffid', $userid);
	$departments = $this->db->get()->result_array();
	if ($onlyids == true) {
		$departmentsid = [];
		foreach ($departments as $department) {
			array_push($departmentsid, $department['departmentid']);
		}
		return $departmentsid;
	}
	return $departments;
}
  /**
	 * Get staff permissions
	 * @param  mixed $id staff id
	 * @return array
	 */
  public function get_staff_permissions($id)
  {
		// Fix for version 2.3.1 tables upgrade
	if (defined('DOING_DATABASE_UPGRADE')) {
		return [];
	}

	$permissions = $this->app_object_cache->get('staff-' . $id . '-permissions');

	if (!$permissions && !is_array($permissions)) {
		$this->db->where('staff_id', $id);
		$permissions = $this->db->get('staff_permissions')->result_array();

		$this->app_object_cache->add('staff-' . $id . '-permissions', $permissions);
	}

	return $permissions;
}
public function get_job_position_arrayid()
{
	$position = $this->db->query('select * from '.db_prefix().'hr_job_position')->result_array();
	$position_arrray = [];
	foreach ($position as $value) {
		array_push($position_arrray, $value['position_id']);
	}
	return $position_arrray;
}

	
	/**
	 * get workplace array id
	 * @return [type] 
	 */
	public function get_workplace_array_id()
	{
		$workplace = $this->db->query('select * from tblhr_workplace')->result_array();
		$workpalce_array =[];
		foreach ($workplace as $value) {
			array_push($workpalce_array, $value['id']);
		}
		return $workpalce_array;
	}

	
	/**
	 * get workplace
	 * @param  boolean $id 
	 * @return [type]      
	 */
	public function get_workplace($id = false)
	{
		if (is_numeric($id)) {
			$this->db->where('id', $id);
			return $this->db->get(db_prefix() . 'hr_workplace')->row();
		}
		if ($id == false) {
		   return  $this->db->get(db_prefix() . 'hr_workplace')->result_array();
		}

	}


	/**
	 * add workplace
	 * @param [type] $data 
	 */
	public function add_workplace($data){
		$this->db->insert(db_prefix() . 'hr_workplace', $data);
		$insert_id = $this->db->insert_id();

		if ($insert_id) {
			return $insert_id;
		}
		return false;
	}


	/**
	 * update workplace
	 * @param  [type] $data 
	 * @param  [type] $id   
	 * @return [type]       
	 */
	public function update_workplace($data, $id)
	{   
		$this->db->where('id', $id);
		$this->db->update(db_prefix() . 'hr_workplace', $data);

		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * delete workplace
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_workplace($id){
		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'hr_workplace');

		if ($this->db->affected_rows() > 0) {
			return true;
		}

		return false;
	}


 /**
	 * format date
	 * @param  date $date     
	 * @return date           
	 */
 public function format_date($date){
	if(!$this->check_format_date_ymd($date)){
		$date = to_sql_date($date);
	}
	return $date;
}            

	/**
	 * format date time
	 * @param  date $date     
	 * @return date           
	 */
	public function format_date_time($date){
		if(!$this->check_format_date($date)){
			$date = to_sql_date($date, true);
		}
		return $date;
	}
	 /**
	 * check format date ymd
	 * @param  date $date 
	 * @return boolean       
	 */
	 public function check_format_date_ymd($date) {
		if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * check format date
	 * @param  date $date 
	 * @return boolean 
	 */
	public function check_format_date($date){
		if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])\s(0|[0-1][0-9]|2[0-4]):?((0|[0-5][0-9]):?(0|[0-5][0-9])|6000|60:00)$/",$date)) {
			return true;
		} else {
			return false;
		}
	}


	 /**
	 * @param  integer (optional)
	 * @return object
	 * Get single goal
	 */
	public function add_staff($data)
	{
		if (isset($data['fakeusernameremembered'])) {
			unset($data['fakeusernameremembered']);
		}
		if (isset($data['fakepasswordremembered'])) {
			unset($data['fakepasswordremembered']);
		}
		// First check for all cases if the email exists.
		$this->db->where('email', $data['email']);
		$email = $this->db->get(db_prefix() . 'staff')->row();
		if ($email) {
			die('Email already exists');
		}
		$data['admin'] = 0;
		if (is_admin()) {
			if (isset($data['administrator'])) {
				$data['admin'] = 1;
				unset($data['administrator']);
			}
		}

		$send_welcome_email = true;
		$original_password  = $data['password'];
		if (!isset($data['send_welcome_email'])) {
			$send_welcome_email = false;
		} else {
			unset($data['send_welcome_email']);
		}

		$data['password']        = app_hash_password($data['password']);
		$data['datecreated']     = date('Y-m-d H:i:s');
		if (isset($data['departments'])) {
			$departments = $data['departments'];
			unset($data['departments']);
		}

		if(isset($data['role_v'])){
			$data['role'] = $data['role_v'];
			unset($data['role_v']);
		}

		$permissions = [];
        if (isset($data['permissions'])) {
            $permissions = $data['permissions'];
            unset($data['permissions']);
        }

		if (isset($data['custom_fields'])) {
			$custom_fields = $data['custom_fields'];
			unset($data['custom_fields']);
		}

		if ($data['admin'] == 1) {
			$data['is_not_staff'] = 0;
		}

		if (isset($data['birthday'])) {
			$data['birthday'] = to_sql_date($data['birthday']);
		}else{
			$data['birthday'] = null;
		}

		if (isset($data['days_for_identity'])) {
			$data['days_for_identity'] = to_sql_date($data['days_for_identity']);
		}else{
			$data['days_for_identity'] = null;
		}

		$this->db->insert(db_prefix() . 'staff', $data);
		$staffid = $this->db->insert_id();
		if ($staffid) {
			/*update next number setting*/
			$this->update_prefix_number(['staff_code_number' =>  get_hr_control_option('staff_code_number')+1]);
			
			$slug = $data['firstname'] . ' ' . $data['lastname'];

			if ($slug == ' ') {
				$slug = 'unknown-' . $staffid;
			}

			if ($send_welcome_email == true) {
				send_mail_template('staff_created', $data['email'], $staffid, $original_password);
			}

			$this->db->where('staffid', $staffid);
			$this->db->update(db_prefix() . 'staff', [
				'media_path_slug' => slug_it($slug),
			]);

			if (isset($custom_fields)) {
				handle_custom_fields_post($staffid, $custom_fields);
			}
			if (isset($departments)) {
				foreach ($departments as $department) {
					$this->db->insert(db_prefix() . 'staff_departments', [
						'staffid'      => $staffid,
						'departmentid' => $department,
					]);
				}
			}

			// Delete all staff permission if is admin we dont need permissions stored in database (in case admin check some permissions)
            $this->update_permissions($data['admin'] == 1 ? [] : $permissions, $staffid);

			log_activity('New Staff Member Added [ID: ' . $staffid . ', ' . $data['firstname'] . ' ' . $data['lastname'] . ']');

			// Get all announcements and set it to read.
			$this->db->select('announcementid');
			$this->db->from(db_prefix() . 'announcements');
			$this->db->where('showtostaff', 1);
			$announcements = $this->db->get()->result_array();
			foreach ($announcements as $announcement) {
				$this->db->insert(db_prefix() . 'dismissed_announcements', [
					'announcementid' => $announcement['announcementid'],
					'staff'          => 1,
					'userid'         => $staffid,
				]);
			}
			hooks()->do_action('staff_member_created', $staffid);

			return $staffid;
		}

		return false;
	}


	/**
	 * update staff
	 * @param  [type] $data 
	 * @param  [type] $id   
	 * @return [type]       
	 */
	public function update_staff($data, $id)
	{
		if (isset($data['fakeusernameremembered'])) {
			unset($data['fakeusernameremembered']);
		}
		if (isset($data['fakepasswordremembered'])) {
			unset($data['fakepasswordremembered']);
		}

		$data = hooks()->apply_filters('before_update_staff_member', $data, $id);
		if($this->get_staff($id)->admin == '1') {
			$data['administrator'] = 1;
		}
				
		if (is_admin()) {
			if (isset($data['administrator'])) {
				$data['admin'] = 1;
				unset($data['administrator']);
			} else {
				if ($id != get_staff_user_id()) {
					if ($id == 1) {
						return [
							'cant_remove_main_admin' => true,
						];
					}
				} else {
					return [
						'cant_remove_yourself_from_admin' => true,
					];
				}
				$data['admin'] = 0;
			}
		}

		if(isset($data['administrator'])){
			unset($data['administrator']);
		}

		$affectedRows = 0;
		if (isset($data['departments'])) {
			$departments = $data['departments'];
			unset($data['departments']);
		}

		if(isset($data['role_v'])){
			$data['role'] = $data['role_v'];
			unset($data['role_v']);
		}

		$permissions = [];
        if (isset($data['permissions'])) {
            $permissions = $data['permissions'];
            unset($data['permissions']);
        }

		if (isset($data['custom_fields'])) {
			$custom_fields = $data['custom_fields'];
			if (handle_custom_fields_post($id, $custom_fields)) {
				$affectedRows++;
			}
			unset($data['custom_fields']);
		}
		if (empty($data['password'])) {
			unset($data['password']);
		} else {
			$data['password']             = app_hash_password($data['password']);
			$data['last_password_change'] = date('Y-m-d H:i:s');
		}


		if (isset($data['two_factor_auth_enabled'])) {
			$data['two_factor_auth_enabled'] = 1;
		} else {
			$data['two_factor_auth_enabled'] = 0;
		}

		if (isset($data['is_not_staff'])) {
			$data['is_not_staff'] = 1;
		} else {
			$data['is_not_staff'] = 0;
		}

		if (isset($data['admin']) && $data['admin'] == 1) {
			$data['is_not_staff'] = 0;
		}

		if (isset($data['birthday'])) {
			$data['birthday'] = to_sql_date($data['birthday']);
		}else{
			$data['birthday'] = null;
		}

		if (isset($data['days_for_identity'])) {
			$data['days_for_identity'] = to_sql_date($data['days_for_identity']);
		}else{
			$data['days_for_identity'] = null;
		}

		$data['date_update'] = date('Y-m-d');

		$data['email_signature'] = nl2br_save_html($data['email_signature']);

		$this->load->model('departments_model');
		$staff_departments = $this->departments_model->get_staff_departments($id);
		if (sizeof($staff_departments) > 0) {
			if (!isset($data['departments'])) {
				$this->db->where('staffid', $id);
				$this->db->delete(db_prefix() . 'staff_departments');
			} else {
				foreach ($staff_departments as $staff_department) {
					if (isset($departments)) {
						if (!in_array($staff_department['departmentid'], $departments)) {
							$this->db->where('staffid', $id);
							$this->db->where('departmentid', $staff_department['departmentid']);
							$this->db->delete(db_prefix() . 'staff_departments');
							if ($this->db->affected_rows() > 0) {
								$affectedRows++;
							}
						}
					}
				}
			}
			if (isset($departments)) {
				foreach ($departments as $department) {
					$this->db->where('staffid', $id);
					$this->db->where('departmentid', $department);
					$_exists = $this->db->get(db_prefix() . 'staff_departments')->row();
					if (!$_exists) {
						$this->db->insert(db_prefix() . 'staff_departments', [
							'staffid'      => $id,
							'departmentid' => $department,
						]);
						if ($this->db->affected_rows() > 0) {
							$affectedRows++;
						}
					}
				}
			}
		} else {
			if (isset($departments)) {
				foreach ($departments as $department) {
					$this->db->insert(db_prefix() . 'staff_departments', [
						'staffid'      => $id,
						'departmentid' => $department,
					]);
					if ($this->db->affected_rows() > 0) {
						$affectedRows++;
					}
				}
			}
		}


		$this->db->where('staffid', $id);
		$this->db->update(db_prefix() . 'staff', $data);

		if ($this->db->affected_rows() > 0) {
			$affectedRows++;
		}
		
		if ($this->update_permissions((isset($data['admin']) && $data['admin'] == 1 ? [] : $permissions), $id)) {
            $affectedRows++;
        }

		if ($affectedRows > 0) {
			hooks()->do_action('staff_member_updated', $id);
			log_activity('Staff Member Updated [ID: ' . $id . ', ' . $data['firstname'] . ' ' . $data['lastname'] . ']');

			return true;
		}

		return false;
	}


	/**
	 * get department name
	 * @param  integer $staffid 
	 */
	public function getdepartment_name($staffid){
		return $this->db->query('select s.staffid, d.departmentid ,d.name
			from tblstaff as s 
			left join tblstaff_departments as sd on sd.staffid = s.staffid
			left join tbldepartments d on d.departmentid = sd.departmentid 
			where s.staffid in ('.$staffid.')
			order by d.departmentid,s.staffid')->row();
	}
	/**
	 * get child node staff chart
	 * @param  integer $id      
	 * @param  integer $arr_dep 
	 * @return array          
	 */
	private function get_child_node_staff_chart($id, $arr_dep){
		$dep_tree = array();
		foreach ($arr_dep as $dep) {
			if($dep['pid']==$id){ 
				$dpm = $this->getdepartment_name($dep['id']);  
				$node = array();             
				$node['name'] = $dep['name'];
				$node['team_manage'] = $dep['pid'];
				$node['job_position_name'] = '';
				
				if($dep['job_position_name'] != null && $dep['job_position_name'] != 'undefined'){
					$node['dp_user_icon'] = '"fa fa-map-pin menu-icon"';

					$node['job_position_name'] = $dep['job_position_name'];
				}
				if($dep['rname'] != null){
					$node['title'] = $dep['rname'];
					$node['dp_user_icon'] = '"fa fa-map-pin menu-icon"';
				}else{
					$node['title'] = '';
				}
				if($dpm->name != null){
					$node['departmentname'] = $dpm->name;
					$node['dp_icon'] = '"fa fa-sitemap"';
				}else{
					$node['departmentname'] = ' ';
				}
				$node['image'] = staff_profile_image($dep['id'], [
					'staff-profile-image-small staff-chart-padding',
				]);
				
				$node['children'] = $this->get_child_node_staff_chart($dep['id'], $arr_dep);
				if(count($node['children']) == 0){
					unset($node['children']);
				}
				$dep_tree[] = $node;
			} 
		} 
		return $dep_tree;
	}

	/**
	 * delete staff
	 * @param  [type] $id               
	 * @param  [type] $transfer_data_to 
	 * @return [type]                   
	 */
	public function delete_staff($id, $transfer_data_to)
	{
		if (!is_numeric($transfer_data_to)) {
			return false;
		}

		if ($id == $transfer_data_to) {
			return false;
		}

		hooks()->do_action('before_delete_staff_member', [
			'id'               => $id,
			'transfer_data_to' => $transfer_data_to,
		]);

		$name           = get_staff_full_name($id);
		$transferred_to = get_staff_full_name($transfer_data_to);

		$this->db->where('addedfrom', $id);
		$this->db->update(db_prefix() . 'estimates', [
			'addedfrom' => $transfer_data_to,
		]);

		$this->db->where('sale_agent', $id);
		$this->db->update(db_prefix() . 'estimates', [
			'sale_agent' => $transfer_data_to,
		]);

		$this->db->where('addedfrom', $id);
		$this->db->update(db_prefix() . 'invoices', [
			'addedfrom' => $transfer_data_to,
		]);

		$this->db->where('sale_agent', $id);
		$this->db->update(db_prefix() . 'invoices', [
			'sale_agent' => $transfer_data_to,
		]);

		$this->db->where('addedfrom', $id);
		$this->db->update(db_prefix() . 'expenses', [
			'addedfrom' => $transfer_data_to,
		]);

		$this->db->where('addedfrom', $id);
		$this->db->update(db_prefix() . 'notes', [
			'addedfrom' => $transfer_data_to,
		]);

		$this->db->where('userid', $id);
		$this->db->update(db_prefix() . 'newsfeed_post_comments', [
			'userid' => $transfer_data_to,
		]);

		$this->db->where('creator', $id);
		$this->db->update(db_prefix() . 'newsfeed_posts', [
			'creator' => $transfer_data_to,
		]);

		$this->db->where('staff_id', $id);
		$this->db->update(db_prefix() . 'projectdiscussions', [
			'staff_id' => $transfer_data_to,
		]);

		$this->db->where('addedfrom', $id);
		$this->db->update(db_prefix() . 'projects', [
			'addedfrom' => $transfer_data_to,
		]);

		$this->db->where('addedfrom', $id);
		$this->db->update(db_prefix() . 'creditnotes', [
			'addedfrom' => $transfer_data_to,
		]);

		$this->db->where('staff_id', $id);
		$this->db->update(db_prefix() . 'credits', [
			'staff_id' => $transfer_data_to,
		]);

		$this->db->where('staffid', $id);
		$this->db->update(db_prefix() . 'project_files', [
			'staffid' => $transfer_data_to,
		]);

		$this->db->where('staffid', $id);
		$this->db->update(db_prefix() . 'proposal_comments', [
			'staffid' => $transfer_data_to,
		]);

		$this->db->where('addedfrom', $id);
		$this->db->update(db_prefix() . 'proposals', [
			'addedfrom' => $transfer_data_to,
		]);

		$this->db->where('staffid', $id);
		$this->db->update(db_prefix() . 'task_comments', [
			'staffid' => $transfer_data_to,
		]);

		$this->db->where('addedfrom', $id);
		$this->db->where('is_added_from_contact', 0);
		$this->db->update(db_prefix() . 'tasks', [
			'addedfrom' => $transfer_data_to,
		]);

		$this->db->where('staffid', $id);
		$this->db->update(db_prefix() . 'files', [
			'staffid' => $transfer_data_to,
		]);

		$this->db->where('renewed_by_staff_id', $id);
		$this->db->update(db_prefix() . 'contract_renewals', [
			'renewed_by_staff_id' => $transfer_data_to,
		]);

		$this->db->where('addedfrom', $id);
		$this->db->update(db_prefix() . 'task_checklist_items', [
			'addedfrom' => $transfer_data_to,
		]);

		$this->db->where('finished_from', $id);
		$this->db->update(db_prefix() . 'task_checklist_items', [
			'finished_from' => $transfer_data_to,
		]);

		$this->db->where('admin', $id);
		$this->db->update(db_prefix() . 'ticket_replies', [
			'admin' => $transfer_data_to,
		]);

		$this->db->where('admin', $id);
		$this->db->update(db_prefix() . 'tickets', [
			'admin' => $transfer_data_to,
		]);

		$this->db->where('addedfrom', $id);
		$this->db->update(db_prefix() . 'leads', [
			'addedfrom' => $transfer_data_to,
		]);

		$this->db->where('assigned', $id);
		$this->db->update(db_prefix() . 'leads', [
			'assigned' => $transfer_data_to,
		]);

		$this->db->where('staff_id', $id);
		$this->db->update(db_prefix() . 'taskstimers', [
			'staff_id' => $transfer_data_to,
		]);

		$this->db->where('addedfrom', $id);
		$this->db->update(db_prefix() . 'contracts', [
			'addedfrom' => $transfer_data_to,
		]);

		$this->db->where('assigned_from', $id);
		$this->db->where('is_assigned_from_contact', 0);
		$this->db->update(db_prefix() . 'task_assigned', [
			'assigned_from' => $transfer_data_to,
		]);

		$this->db->where('responsible', $id);
		$this->db->update(db_prefix() . 'leads_email_integration', [
			'responsible' => $transfer_data_to,
		]);

		$this->db->where('responsible', $id);
		$this->db->update(db_prefix() . 'web_to_lead', [
			'responsible' => $transfer_data_to,
		]);

		$this->db->where('created_from', $id);
		$this->db->update(db_prefix() . 'subscriptions', [
			'created_from' => $transfer_data_to,
		]);

		$this->db->where('notify_type', 'specific_staff');
		$web_to_lead = $this->db->get(db_prefix() . 'web_to_lead')->result_array();

		foreach ($web_to_lead as $form) {
			if (!empty($form['notify_ids'])) {
				$staff = unserialize($form['notify_ids']);
				if (is_array($staff)) {
					if (in_array($id, $staff)) {
						if (($key = array_search($id, $staff)) !== false) {
							unset($staff[$key]);
							$staff = serialize(array_values($staff));
							$this->db->where('id', $form['id']);
							$this->db->update(db_prefix() . 'web_to_lead', [
								'notify_ids' => $staff,
							]);
						}
					}
				}
			}
		}

		$this->db->where('id', 1);
		$leads_email_integration = $this->db->get(db_prefix() . 'leads_email_integration')->row();

		if ($leads_email_integration->notify_type == 'specific_staff') {
			if (!empty($leads_email_integration->notify_ids)) {
				$staff = unserialize($leads_email_integration->notify_ids);
				if (is_array($staff)) {
					if (in_array($id, $staff)) {
						if (($key = array_search($id, $staff)) !== false) {
							unset($staff[$key]);
							$staff = serialize(array_values($staff));
							$this->db->where('id', 1);
							$this->db->update(db_prefix() . 'leads_email_integration', [
								'notify_ids' => $staff,
							]);
						}
					}
				}
			}
		}

		$this->db->where('assigned', $id);
		$this->db->update(db_prefix() . 'tickets', [
			'assigned' => 0,
		]);

		$this->db->where('staff', 1);
		$this->db->where('userid', $id);
		$this->db->delete(db_prefix() . 'dismissed_announcements');

		$this->db->where('userid', $id);
		$this->db->delete(db_prefix() . 'newsfeed_comment_likes');

		$this->db->where('userid', $id);
		$this->db->delete(db_prefix() . 'newsfeed_post_likes');

		$this->db->where('staff_id', $id);
		$this->db->delete(db_prefix() . 'customer_admins');

		$this->db->where('fieldto', 'staff');
		$this->db->where('relid', $id);
		$this->db->delete(db_prefix() . 'customfieldsvalues');

		$this->db->where('userid', $id);
		$this->db->delete(db_prefix() . 'events');

		$this->db->where('touserid', $id);
		$this->db->delete(db_prefix() . 'notifications');

		$this->db->where('staff_id', $id);
		$this->db->delete(db_prefix() . 'user_meta');

		$this->db->where('staff_id', $id);
		$this->db->delete(db_prefix() . 'project_members');

		$this->db->where('staff_id', $id);
		$this->db->delete(db_prefix() . 'project_notes');

		$this->db->where('creator', $id);
		$this->db->or_where('staff', $id);
		$this->db->delete(db_prefix() . 'reminders');

		$this->db->where('staffid', $id);
		$this->db->delete(db_prefix() . 'staff_departments');

		$this->db->where('staffid', $id);
		$this->db->delete(db_prefix() . 'todos');

		$this->db->where('staff', 1);
		$this->db->where('user_id', $id);
		$this->db->delete(db_prefix() . 'user_auto_login');

		$this->db->where('staff_id', $id);
		$this->db->delete(db_prefix() . 'staff_permissions');

		$this->db->where('staffid', $id);
		$this->db->delete(db_prefix() . 'task_assigned');

		$this->db->where('staffid', $id);
		$this->db->delete(db_prefix() . 'task_followers');

		$this->db->where('staff_id', $id);
		$this->db->delete(db_prefix() . 'pinned_projects');

		$this->db->where('staffid', $id);
		$this->db->delete(db_prefix() . 'staff');
		log_activity('Staff Member Deleted [Name: ' . $name . ', Data Transferred To: ' . $transferred_to . ']');
		$this->db->where('staffid', $id);
		$this->db->delete(db_prefix() . 'hr_rec_transfer_records');
		hooks()->do_action('staff_member_deleted', [
			'id'               => $id,
			'transfer_data_to' => $transfer_data_to,
		]);      
		return true;
	}
	

	/**
	 * get hr profile attachments
	 * @param  integer $staffid 
	 * @return array          
	 */
	public function get_hr_control_attachments($staffid){
		$this->db->order_by('dateadded', 'desc');
		$this->db->where('rel_id', $staffid);
		$this->db->where('rel_type', 'hr_staff_file');

		return $this->db->get(db_prefix() . 'files')->result_array();

	}
	
	/**
	 * get records received
	 * @param  integer $id
	 * @return object     
	*/
	public function get_records_received($id)
	{
		return $this->db->query('select tblstaff.records_received from tblstaff where staffid = '.$id)->row();
	}




	/**
	 * get hr profile profile file
	 * @param  integer $staffid 
	 * @return array          
	 */
	public function get_hr_control_profile_file($staffid){

		$this->db->order_by('dateadded', 'desc');
		$this->db->where('rel_id', $staffid);
		$this->db->where('rel_type', 'staff_profile_images');

		return $this->db->get(db_prefix() . 'files')->result_array();

	}


	/**
	 * get duration
	 * @return array 
	 */
	public function get_duration(){
		return $this->db->query('SELECT duration, unit FROM tblhr_staff_contract_type group by duration, unit')->result_array();
	}


	/**
	 * add education
	 * @param array $data 
	 */
	public function add_education($data){
		$data['date_create'] = date('y-m-d');
		$insert_id = $this->db->insert(db_prefix() . 'hr_education', $data);
		if ($insert_id) {
			return $insert_id;
		}
		return false;

	}


	/**
	 * update education
	 * @param array $data 
	 */
	public function update_education($data)
	{   
		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix() . 'hr_education', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}


	/**
	 * delete education
	 * @param integer $id 
	 */
	public function delete_education($id){
		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'hr_education');
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}


/**
 * member get evaluate form
 * @param  integer $staffid 
 * @return array          
 */
public function member_get_evaluate_form($staffid){
	$arr_evaluate_form = $this->get_evaluate_form_status();
	$sql = "SELECT staffid, staff_identifi, firstname FROM ".db_prefix().'staff WHERE staffid ='.$staffid;
	$arr_staff = $this->db->query($sql)->result_array();
	$data_object =[];

	foreach ($arr_evaluate_form as $evaluate_value) {
		$data =[];
		if(strlen(json_encode($arr_staff)) != 2){
			$evalute_staff = $this->get_dataobject_result_evaluate($evaluate_value['id'], $arr_staff);
			if(count($evalute_staff[0]) != 0){
				$data['id'] = $evaluate_value['id'];
				$data['hr_code'] = $arr_staff[0]['staff_identifi'];
				$data['eval_form_name'] = $this->get_evaluation_form($evaluate_value['evaluate_form'])->eval_form_name;
				$start_month = $this->get_evaluation_form($evaluate_value['evaluate_form'])->start_month;
				$end_month = $this->get_evaluation_form($evaluate_value['evaluate_form'])->end_month;
				$data['period_eval'] =  date("m/Y", strtotime($evaluate_value['start_month'])).' - '. date("m/Y", strtotime($evaluate_value['end_month']));
				$data['total_kpi'] =  array_reverse($evalute_staff[0])[0];

			}
		}
		if(count($data) != 0){
			array_push($data_object, $data);
		}
	}
	return $data_object;
}
/**
 * get evaluate form status
 * @return array 
 */
public function get_evaluate_form_status(){
	$this->db->where('status', '1');
	return  $this->db->get(db_prefix() . 'evaluate_form')->result_array();

}
/**
 * get dataobject result evaluate
 * @param  integer  $id       
 * @param  boolean $arrstaff 
 * @return integer            
 */
public function get_dataobject_result_evaluate($id, $arrstaff = false){
	$evaluation_form = $this->get_evaluate_form($id);
	$emp_marks = json_decode($evaluation_form->emp_marks);
	if(isset($evaluation_form->percent)){
		$percent = json_decode($evaluation_form->percent);
	}else{
		$percent = (float)0;
	}

	$evaluation_form_detail = $this->get_evaluation_form_detail($evaluation_form->evaluate_form);
	$evaluate_result = $this->get_assessor_from($id);

	if($arrstaff != false){
		$arr_staff = $arrstaff;
	}else{
		$sql = "SELECT staffid, staff_identifi, firstname FROM ".db_prefix().'staff WHERE 1 = 1';           

		if(isset($evaluation_form->department_id) && $evaluation_form->department_id != 'null' && $evaluation_form->department_id != '0'&&$evaluation_form->apply_for=='department'){
			$searchVal = array('[', ']', '"');
			$replaceVal = array('(', ')', '');
			$department_array = str_replace($searchVal, $replaceVal, $evaluation_form->department_id);
			$sql .= ' AND staffid in ( select staffid from tblstaff_departments where departmentid in '.$department_array.' )';
		}
		if(isset($evaluation_form->role_id) && $evaluation_form->role_id != 'null' && $evaluation_form->role_id != '0'&&$evaluation_form->apply_for=='role'){
		 $searchVal = array('[', ']', '"');
		 $replaceVal = array('(', ')', '');
		 $role_array = str_replace($searchVal, $replaceVal, $evaluation_form->roles_id);
		 $sql .= ' AND role in '.$role_array.'';
	 } 
	 if(isset($evaluation_form->staff_id) && $evaluation_form->staff_id != 'null' && $evaluation_form->staff_id != '0'&&$evaluation_form->apply_for=='staff'){
		 $searchVal = array('[', ']', '"');
		 $replaceVal = array('(', ')', '');
		 $staff_array = str_replace($searchVal, $replaceVal, $evaluation_form->staff_id);
		 $sql .= ' AND staffid in '.$staff_array.'';
	 } 
	 $arr_staff = $this->db->query($sql)->result_array();
 }

 $arr_object =[];
 $flag_member_evaluate = 0;
 foreach ($arr_staff as $staff) {
	$kpi_staff = 0;
	$staff_info =[];
	$staff_info[] = $staff['staff_identifi'];
	$staff_info[] = $staff['firstname'];
	foreach ($evaluation_form_detail as $eval_det_key => $eval_det_value) {
		$arr_income = json_decode($eval_det_value['income']);
		$arr_kpi_percent = json_decode($eval_det_value['kpi_percent']);
		$arr_kpi_formula = json_decode($eval_det_value['kpi_formula']);

		$kpi_temp = 0;
		foreach (json_decode($eval_det_value['kpi_key']) as $kpi_key => $kpi_value) {
			$staff_info[] = $arr_income[$kpi_key] ;
			foreach ($emp_marks as $emp_marks_key =>  $staff_id) {
				$kpi_formula1 = '';
				$kpi_formula2 = '';
				foreach ($evaluate_result as $evaluate_result_value) {
					if($evaluate_result_value['assessor_id'] == $staff_id){
						$arr_result = json_decode($evaluate_result_value['result']);
						foreach ($arr_result as $arr_result_value) {
							if($arr_result_value->staff_id == $staff['staff_identifi']){

								$staff_info[] = $arr_result_value->$kpi_value ;
								$formula = $arr_kpi_formula[$kpi_key];
								if($arr_result_value->$kpi_value != ''){
									$result_value = $arr_result_value->$kpi_value;
								}else{
									$result_value = 0;
								}


								$formula = str_replace($kpi_value,$result_value,$formula);
								$formula = eval('return '.$formula.';');

								$kpi_formula2 .= (($formula*$percent[$emp_marks_key]/100)/$arr_income[$kpi_key])*$arr_kpi_percent[$kpi_key]/100;
								$kpi_temp += (float)eval('return '.$kpi_formula2.';');
							}

						}
						if($arrstaff != false){
							if(count($staff_info) == 3){
								$flag_member_evaluate = 1;
							}
						}

					}
				}

			}
			$staff_info[] = number_format($kpi_temp, 3);
			$kpi_staff += $kpi_temp;
		}
	}
	if($arrstaff != false && $flag_member_evaluate == 1){
		$member_evaluate = [];
		array_push($arr_object, $member_evaluate);
	}else{
		$staff_info[] = number_format($kpi_staff, 3);
		array_push($arr_object, $staff_info);
	}

}
return $arr_object;
}
/**
 * add attachment to database
 * @param integer  $rel_id     
 * @param string  $rel_type   
 * @param string  $attachment 
 * @param integer $insert_id
 */
public function add_attachment_to_database($rel_id, $rel_type, $attachment, $external = false)
{
	$data['dateadded'] = date('Y-m-d H:i:s');
	$data['rel_id']    = $rel_id;
	if (!isset($attachment[0]['staffid'])) {
		$data['staffid'] = get_staff_user_id();
	} else {
		$data['staffid'] = $attachment[0]['staffid'];
	}

	if (isset($attachment[0]['task_comment_id'])) {
		$data['task_comment_id'] = $attachment[0]['task_comment_id'];
	}
	$data['rel_type'] = $rel_type;

	if (isset($attachment[0]['contact_id'])) {
		$data['contact_id']          = $attachment[0]['contact_id'];
		$data['visible_to_customer'] = 1;
		if (isset($data['staffid'])) {
			unset($data['staffid']);
		}
	}

	$data['attachment_key'] = app_generate_hash();

	if ($external == false) {
		$data['file_name'] = $attachment[0]['file_name'];
		$data['filetype']  = $attachment[0]['filetype'];
	} else {
		$path_parts            = pathinfo($attachment[0]['name']);
		$data['file_name']     = $attachment[0]['name'];
		$data['external_link'] = $attachment[0]['link'];
		$data['filetype']      = !isset($attachment[0]['mime']) ? get_mime_by_extension('.' . $path_parts['extension']) : $attachment[0]['mime'];
		$data['external']      = $external;
		if (isset($attachment[0]['thumbnailLink'])) {
			$data['thumbnail_link'] = $attachment[0]['thumbnailLink'];
		}
	}
	$this->db->insert(db_prefix() . 'files', $data);
	$insert_id = $this->db->insert_id();
	return $insert_id;
}

	/**
	 * function get file for hrm staff
	 * @param  integer  $id     
	 * @param  boolean $rel_id 
	 * @return object          
	 */
	public function get_file($id, $rel_id = false)
	{
		if (is_client_logged_in()) {
			$this->db->where('visible_to_customer', 1);
		}
		$this->db->where('id', $id);
		$file = $this->db->get('tblfiles')->row();
		if ($file && $rel_id) {
			if ($file->rel_id != $rel_id) {
				return false;
			}
		}
		return $file;
	}

	/**
	 * delete staff attchement
	 * @param  integer $attachment_id 
	 * @return integer                
	 */
	public function delete_hr_control_staff_attachment($attachment_id)
	{
		$deleted    = false;
		$attachment = $this->get_hr_control_attachments_delete($attachment_id);
		if ($attachment) {
			if (empty($attachment->external)) {
				unlink(HR_PROFILE_FILE_ATTACHMENTS_UPLOAD_FOLDER.'/' .$attachment->rel_id.'/'.$attachment->file_name);
			}
			$this->db->where('id', $attachment->id);
			$this->db->delete(db_prefix() . 'files');
			if ($this->db->affected_rows() > 0) {
				$deleted = true;
				log_activity('Contract Attachment Deleted [ContractID: ' . $attachment->rel_id . ']');
			}

			if (is_dir(HR_PROFILE_FILE_ATTACHMENTS_UPLOAD_FOLDER.'/' .$attachment->rel_id)) {
				$other_attachments = list_files(HR_PROFILE_FILE_ATTACHMENTS_UPLOAD_FOLDER.'/' .$attachment->rel_id);
				if (count($other_attachments) == 0) {
					delete_dir(HR_PROFILE_FILE_ATTACHMENTS_UPLOAD_FOLDER.'/' .$attachment->rel_id);
				}
			}
		}
		return $deleted;
	}



	/**
	 * get hr profile attachments delete
	 * @param  integer $id 
	 * @return object     
	 */
	public function get_hr_control_attachments_delete($id){
		if (is_numeric($id)) {
			$this->db->where('id', $id);
			return $this->db->get(db_prefix() . 'files')->row();
		}
	}


	/**
	 * update staff permissions
	 * @param  array $data 
	 * @param  integer $id   
	 * @return boolean       
	 */
	public function update_staff_permissions($data){
		if($this->update_permissions((isset($data['admin']) && $data['admin'] == 1 ? [] : $data['permissions']), $data['id'])) {
			$affectedRows++;
		}
		if ($affectedRows > 0) {
			hooks()->do_action('staff_member_updated', $data['id']);
			log_activity('Staff Member Updated [ID: ' . $data['id'] . ', ' . $data['firstname'] . ' ' . $data['lastname'] . ']');
			return true;
		}
		return false;
	}

	/**
	 * update permissions
	 * @param  array $permissions 
	 * @param  integer $id          
	 * @return boolean              
	 */
	public function update_permissions($permissions, $id)
	{
		$this->db->where('staff_id', $id);
		$this->db->delete('staff_permissions');
		$is_staff_member = is_staff_member($id);
		foreach ($permissions as $feature => $capabilities) {
			foreach ($capabilities as $capability) {
				if ($feature == 'leads' && !$is_staff_member) {
					continue;
				}
				$this->db->insert('staff_permissions', ['staff_id' => $id, 'feature' => $feature, 'capability' => $capability]);
			}
		}
		return true;
	}


	/**
	 * get file info
	 * @param  integer $id       
	 * @param  string $rel_type 
	 * @return object           
	 */
	public function get_file_info($id,$rel_type){
		$this->db->where('rel_id', $id);
		$this->db->where('rel_type', $rel_type);
		return $this->db->get(db_prefix().'files')->row();
	}
   /**
	* update staff profile
	* @param  array $data 
	* @return boolean       
	*/
   public function update_staff_profile($data){
	$id = $data['id'];
	unset($data['id']);
	$data['date_update']          = date('Y-m-d');
	$data['birthday']             = to_sql_date($data['birthday']);
	$data['days_for_identity']    = to_sql_date($data['days_for_identity']);
	if (isset($data['fakeusernameremembered'])) {
		unset($data['fakeusernameremembered']);
	}
	if (isset($data['fakepasswordremembered'])) {
		unset($data['fakepasswordremembered']);
	}
	if (isset($data['nationality'])) {
		unset($data['nationality']);
	}
	$data = hooks()->apply_filters('before_update_staff_member', $data, $id);
	if (is_admin()) {
		if (isset($data['administrator'])) {
			$data['admin'] = 1;
			unset($data['administrator']);
		} else {
			if ($id != get_staff_user_id()) {
				if ($id == 1) {
					return [
						'cant_remove_main_admin' => true,
					];
				}
			} else {
				return [
					'cant_remove_yourself_from_admin' => true,
				];
			}
			$data['admin'] = 0;
		}
	}

	$affectedRows = 0;
	if (isset($data['departments'])) {
		$departments = $data['departments'];
		unset($data['departments']);
	}

	$permissions = [];
	if (isset($data['permissions'])) {
		$permissions = $data['permissions'];
		unset($data['permissions']);
	}

	if (isset($data['custom_fields'])) {
		$custom_fields = $data['custom_fields'];
		if (handle_custom_fields_post($id, $custom_fields)) {
			$affectedRows++;
		}
		unset($data['custom_fields']);
	}
	if (!isset($data['password'])) {
		unset($data['password']);
	} else {
		$data['password']             = app_hash_password($data['password']);
		$data['last_password_change'] = date('Y-m-d H:i:s');
	}


	if (isset($data['two_factor_auth_enabled'])) {
		$data['two_factor_auth_enabled'] = 1;
	} else {
		$data['two_factor_auth_enabled'] = 0;
	}

	if (isset($data['is_not_staff'])) {
		$data['is_not_staff'] = 1;
	} else {
		$data['is_not_staff'] = 0;
	}

	if (isset($data['admin']) && $data['admin'] == 1) {
		$data['is_not_staff'] = 0;
	}

	if(isset($data['year_requisition'])){
		unset($data['year_requisition']);
	}


   // First check for all cases if the email exists.
   
		$this->db->where('email', $data['email']);
		$email = $this->db->get(db_prefix() . 'staff')->row();
		if ($email) {
			// sdie('Email already exists');
		}

		$data['admin'] = 0;
		if (is_admin()) {
			if (isset($data['administrator'])) {
				$data['admin'] = 1;
				unset($data['administrator']);
			}
		}

		$send_welcome_email = true;
		$original_password  = $data['password'];
		if (!isset($data['send_welcome_email'])) {
			$send_welcome_email = false;
		} else {
			unset($data['send_welcome_email']);
		}
		if ($data['admin'] == 1) {
			$data['is_not_staff'] = 0;
		}


	$data['email_signature'] = nl2br_save_html($data['email_signature']);

	$this->load->model('departments_model');
	$staff_departments = $this->departments_model->get_staff_departments($id);
	if (sizeof($staff_departments) > 0) {
		if (!isset($data['departments'])) {
			$this->db->where('staffid', $id);
			$this->db->delete(db_prefix() . 'staff_departments');
		} else {
			foreach ($staff_departments as $staff_department) {
				if (isset($departments)) {
					if (!in_array($staff_department['departmentid'], $departments)) {
						$this->db->where('staffid', $id);
						$this->db->where('departmentid', $staff_department['departmentid']);
						$this->db->delete(db_prefix() . 'staff_departments');
						if ($this->db->affected_rows() > 0) {
							$affectedRows++;
						}
					}
				}
			}
		}
		if (isset($departments)) {
			foreach ($departments as $department) {
				$this->db->where('staffid', $id);
				$this->db->where('departmentid', $department);
				$_exists = $this->db->get(db_prefix() . 'staff_departments')->row();
				if (!$_exists) {
					$this->db->insert(db_prefix() . 'staff_departments', [
						'staffid'      => $id,
						'departmentid' => $department,
					]);
					if ($this->db->affected_rows() > 0) {
						$affectedRows++;
					}
				}
			}
		}
	} else {
		if (isset($departments)) {
			foreach ($departments as $department) {
				$this->db->insert(db_prefix() . 'staff_departments', [
					'staffid'      => $id,
					'departmentid' => $department,
				]);
				if ($this->db->affected_rows() > 0) {
					$affectedRows++;
				}
			}
		}
	}
	$this->db->where('staffid', $id);
	$this->db->update(db_prefix() . 'staff', $data);
	if ($this->db->affected_rows() > 0) {
		$affectedRows++;
	}
	/*update avatar end*/
	if ($this->update_permissions((isset($data['admin']) && $data['admin'] == 1 ? [] : $permissions), $id)) {
		$affectedRows++;
	}
	if ($affectedRows > 0) {
		hooks()->do_action('staff_member_updated', $id);
		log_activity('Staff Member Updated [ID: ' . $id . ', ' . $data['firstname'] . ' ' . $data['lastname'] . ']');
		return true;
	}
	return false;
}
   /**
	* get staff in deparment
	* @param  integer $department_id 
	* @return integer                
	*/
   public function get_staff_in_deparment($department_id)
   {
		$data = [];
		$sql = 'select 
		departmentid 
		from    (select * from '.db_prefix().'departments
		order by '.db_prefix().'departments.parent_id, '.db_prefix().'departments.departmentid) departments_sorted,
		(select @pv := '.$department_id.') initialisation
		where   find_in_set(parent_id, @pv)
		and     length(@pv := concat(@pv, ",", departmentid)) OR departmentid = '.$department_id.'';
			$result_arr = $this->db->query($sql)->result_array();
			foreach ($result_arr as $key => $value) {
				$data[$key] = $value['departmentid'];
			}
		  return $data;
	}

	/**
	 * get staff role
	 * @param  [type] $staff_id 
	 * @return [type]           
	 */
	public function get_staff_role($staff_id){

		return $this->db->query('select r.name
			from '.db_prefix().'staff as s 
				left join '.db_prefix().'roles as r on r.roleid = s.role
			where s.staffid ='.$staff_id)->row();
	}


	/**
	 * delete hr profile permission
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_hr_control_permission($id)
	{
		$str_permissions ='';
		foreach (list_hr_control_permisstion() as $per_key =>  $per_value) {
			if(strlen($str_permissions) > 0){
				$str_permissions .= ",'".$per_value."'";
			}else{
				$str_permissions .= "'".$per_value."'";
			}
		}

		$sql_where = " feature IN (".$str_permissions.") ";

		$this->db->where('staff_id', $id);
		$this->db->where($sql_where);
		$this->db->delete(db_prefix() . 'staff_permissions');

		if ($this->db->affected_rows() > 0) {
			return true;
		}

		return false;
	}


	/**
	 * get data dpm chart
	 * @param  [type] $dpm 
	 * @return [type]      
	 */
	public function get_data_dpm_chart($dpm)
	{
		
		 $department =  $this->db->query('select s.staffid as id,s.job_position, s.phonenumber, s.staff_identifi, s.email as staff_email, s.team_manage as pid, s.firstname as name
		from tblstaff as s 
		 left join tblstaff_departments as sd on sd.staffid = s.staffid
				left join tbldepartments d on d.departmentid = sd.departmentid where d.departmentid = "'.$dpm.'" and s.status_work != "inactivity"
		order by s.team_manage, s.staffid')->result_array();

		$dep_tree = array(); 

		$list_id = [];
		foreach ($department as $ds ) {
			$list_id[] = $ds['id'];
		}

		foreach ($department as $dep) {

			if($dep['pid'] == 0 ||  !in_array($dep['pid'], $list_id) ){
				$dpm = $this->getdepartment_name($dep['id']);
				$node = array();
				$node['name'] = $dep['name'];
				
				$node['staff_identifi'] = $dep['staff_identifi'];
				$node['identifi_icon'] = '"fa fa-qrcode"';
				$node['staff_email'] = $dep['staff_email'];
				$node['mail_icon'] = '"fa fa-envelope"';
				$node['dp_phonenumber'] = '"fa fa-phone"';
				$node['dp_user_icon'] = '"fa fa-user-o"';


				if($dep['job_position'] != null && $dep['job_position'] != 0){
					$node['job_position'] = $this->get_job_position($dep['job_position']);
					$node['job_position_url'] = admin_url('hrm/job_position_view_edit/'.$dep['job_position']);
				}else{
					$node['job_position'] = '';
					$node['job_position_url'] = '';
				}

				if($dep['phonenumber'] != null){
					$node['phonenumber'] = $dep['phonenumber'];
					
				}else{
					$node['phonenumber'] = '';
				}

				if($dpm->name != null){
					$node['departmentname'] = $dpm->name;
					$node['dp_icon'] = '"fa fa-sitemap"';
				}else{
					$node['departmentname'] = '';
				}

				$node['image'] = staff_profile_image($dep['id'], [
				'staff-profile-image-small staff-chart-padding',
				]);
				$node['children'] = $this->get_child_node_staff_dpm_chart($dep['id'], $department);
				
				$dep_tree[] = $node;
			}        
		}   
		return $dep_tree;

	}


	/**
	 * list job department
	 * @param  [type] $department 
	 * @return [type]             
	 */
	public function list_job_department($department){
		$this->db->select('staffid');
		$this->db->where('departmentid', $department);
		$arr_staff_id = [];
		$arr_staff = $this->db->get(db_prefix().'staff_departments')->result_array();
		$index_dep = 0;
		if(count($arr_staff) > 0){
			foreach ($arr_staff as $value) {
				if(!in_array($value['staffid'], $arr_staff_id)){
					$arr_staff_id[$index_dep] = $value['staffid'];
					$index_dep++;
				}                
			}
		}

		$rs = [];
		if(count($arr_staff_id) > 0){

		
			$arr_staff_id = implode(",", $arr_staff_id);
			$sql_where = 'SELECT '.db_prefix().'hr_job_position.position_id, position_name FROM '.db_prefix().'staff left join '.db_prefix().'hr_job_position on '.db_prefix().'staff.job_position = '.db_prefix().'hr_job_position.position_id WHERE '.db_prefix().'staff.job_position != "0" AND '.db_prefix().'staff.staffid IN ('.$arr_staff_id.')';

			$arr_job_position = $this->db->query($sql_where)->result_array();

			
			$arr_check_exist=[];
			foreach ($arr_job_position as $k => $note) {
				if(!in_array($note['position_id'], $arr_check_exist)){
					$rs[] = $note['position_id'];
					$arr_check_exist[$k] = $note['position_id'];
			   }


			}
		}

		return $rs;
	}


	/**
	 * delete hr job position attachment file
	 * @param  [type] $attachment_id 
	 * @return [type]                
	 */
	public function delete_hr_job_position_attachment_file($attachment_id)
	{
		$deleted    = false;
		$attachment = $this->get_hr_control_attachments_delete($attachment_id);
		if ($attachment) {
			if (empty($attachment->external)) {
				unlink(get_hr_control_upload_path_by_type('job_position') .$attachment->rel_id.'/'.$attachment->file_name);
			}
			$this->db->where('id', $attachment->id);
			$this->db->delete(db_prefix() . 'files');
			if ($this->db->affected_rows() > 0) {
				$deleted = true;
				log_activity('job_position Attachment Deleted [job_positionID: ' . $attachment->rel_id . ']');
			}

			if (is_dir(get_hr_control_upload_path_by_type('job_position') .$attachment->rel_id)) {
			// if (is_dir(get_upload_path_by_type('job_position') . $attachment->rel_id)) {
				// Check if no attachments left, so we can delete the folder also
				$other_attachments = list_files(get_hr_control_upload_path_by_type('job_position') .$attachment->rel_id);
				if (count($other_attachments) == 0) {
					// okey only index.html so we can delete the folder also
					delete_dir(get_hr_control_upload_path_by_type('job_position') .$attachment->rel_id);
				}
			}
		}

		return $deleted;
	}


	/**
	 * get hrm profile file
	 * @param  [type] $rel_id   
	 * @param  [type] $rel_type 
	 * @return [type]           
	 */
	public function get_hr_control_file($rel_id, $rel_type){
		$this->db->order_by('dateadded', 'desc');
		$this->db->where('rel_id', $rel_id);
		$this->db->where('rel_type', $rel_type);

		return $this->db->get(db_prefix() . 'files')->result_array();
	}


	/**
	 * get job position training de
	 * @param  boolean $id 
	 * @return [type]      
	 */
	public function get_job_position_training_de($id = false){
		$this->db->where('training_process_id', $id);
		return  $this->db->get(db_prefix() . 'hr_jp_interview_training')->row();
	}


	/**
	 * delete job position training process
	 * @param  [type] $trainingid 
	 * @return [type]             
	 */
	public function delete_job_position_training_process($trainingid){
		//delete general info
		$this->db->where('training_process_id', $trainingid);
		$this->db->delete(db_prefix().'hr_jp_interview_training');
		if ($this->db->affected_rows() > 0) {
			 return true;
		}
		return false;

	}

	/**
	 * delete position training
	 * @param  [type] $trainingid 
	 * @return [type]             
	 */
	public function delete_position_training($trainingid)
	{
		$affectedRows = 0;
		$this->db->where('training_id', $trainingid);
		$this->db->delete(db_prefix().'hr_position_training');
		if ($this->db->affected_rows() > 0) {
			$affectedRows++;
			// get all questions from the survey
			$this->db->where('rel_id', $trainingid);
			$this->db->where('rel_type', 'position_training');
			$questions = $this->db->get(db_prefix().'hr_position_training_question_form')->result_array();
			// Delete the question boxes
			foreach ($questions as $question) {
				$this->db->where('questionid', $question['questionid']);
				$this->db->delete(db_prefix().'hr_p_t_form_question_box');
				$this->db->where('questionid', $question['questionid']);
				$this->db->delete(db_prefix().'hr_p_t_form_question_box_description');
			}
			$this->db->where('rel_id', $trainingid);
			$this->db->where('rel_type', 'position_training');
			$this->db->delete(db_prefix().'hr_position_training_question_form');

			$this->db->where('rel_id', $trainingid);
			$this->db->where('rel_type', 'position_training');
			$this->db->delete(db_prefix().'hr_p_t_form_results');

			$this->db->where('trainingid', $trainingid);
			$this->db->delete(db_prefix().'hr_p_t_surveyresultsets');
		}
		if ($affectedRows > 0) {
			log_activity('Training Deleted [ID: ' . $trainingid . ']');

			return true;
		}

		return false;
	}

	/**
	 * get list position training by id training
	 * @param  array $training_id_aray 
	 * @return array                   
	 */
	public function get_list_position_training_by_id_training($training_id_aray){
		return $this->db->query('select * from '.db_prefix().'hr_position_training where training_id in ('.$training_id_aray.')')->result_array();
	}


	/**
	 * get contract
	 * @param  integer $id 
	 * @return array     
	 */
	public function get_contract($id){
		if (is_numeric($id)) {
			$this->db->where('id_contract', $id);
			return $this->db->get(db_prefix() . 'hr_staff_contract')->row();
		}

		if ($id == false) {
		   return  $this->db->get(db_prefix() . 'hr_staff_contract')->result_array();
		}

	}

	/**
	 * get contract detail
	 * @param  integer $id 
	 * @return array     
	 */
	public function get_contract_detail($id){
		$staff_contract_detail = $this->db->query('select * from '.db_prefix().'hr_staff_contract_detail where staff_contract_id = '.$id)->result_array();
		return $staff_contract_detail;
	}


	/**
	 * add contract
	 * @param array $data 
	 */
	public function add_contract($data){


		$data['start_valid']    = to_sql_date($data['start_valid']);
		$data['end_valid']      = to_sql_date($data['end_valid']);
		$data['sign_day']       = to_sql_date($data['sign_day']);



		if(isset($data['job_position'])){
			$job_position = $data['job_position'];
			unset($data['job_position']);
		}

		if (isset($data['staff_contract_hs'])) {
			$staff_contract_hs = $data['staff_contract_hs'];
			unset($data['staff_contract_hs']);
		}
        
        $data['content'] = $this->hr_get_contract_template_by_staff($data['staff']);
        $data['hash'] = app_generate_hash();

		$this->db->insert(db_prefix() . 'hr_staff_contract', $data);
		$insert_id = $this->db->insert_id();

		if(isset($staff_contract_hs)){
			$staff_contract_detail = json_decode($staff_contract_hs);

			$es_detail = [];
			$row = [];
			$rq_val = [];
			$header = [];

			$header[] = 'type';
			$header[] = 'rel_type';
			$header[] = 'rel_value';
			$header[] = 'since_date';
			$header[] = 'contract_note';

			foreach ($staff_contract_detail as $key => $value) {

				if($value[0] != ''){
					$es_detail[] = array_combine($header, $value);
				}
			}
		}

		if (isset($insert_id)) {

			/*insert detail*/
			foreach($es_detail as $key => $rqd){
				$es_detail[$key]['staff_contract_id'] = $insert_id;
			}

			if(count($es_detail) != 0){
				$this->db->insert_batch(db_prefix().'hr_staff_contract_detail',$es_detail);
			}
			/*update next number setting*/
			$this->update_prefix_number(['contract_code_number' =>  get_hr_control_option('contract_code_number')+1]);

		}


		return $insert_id;
	}


	/**
	 * update contract
	 * @param  array $data 
	 * @param  integer $id   
	 * @return boolean       
	 */
	public function update_contract($data, $id)
	{   
		$affectedRows = 0;

		$data['start_valid']    = to_sql_date($data['start_valid']);
		$data['end_valid']      = to_sql_date($data['end_valid']);
		$data['sign_day']       = to_sql_date($data['sign_day']);

		if(isset($data['job_position'])){
			$job_position = $data['job_position'];
			unset($data['job_position']);
		}

		if (isset($data['staff_contract_hs'])) {
			$staff_contract_hs = $data['staff_contract_hs'];
			unset($data['staff_contract_hs']);
		}

		$this->db->where('id_contract', $id);
		$this->db->update(db_prefix() . 'hr_staff_contract', $data);


		if ($this->db->affected_rows() > 0) {
			$affectedRows++;
		}

		if(isset($staff_contract_hs)){
			$staff_contract_detail = json_decode($staff_contract_hs);

			$es_detail = [];
			$row = [];
			$rq_val = [];
			$header = [];


			$header[] = 'type';
			$header[] = 'rel_type';
			$header[] = 'rel_value';
			$header[] = 'since_date';
			$header[] = 'contract_note';
			$header[] = 'contract_detail_id';
			$header[] = 'staff_contract_id';

			foreach ($staff_contract_detail as $key => $value) {
				if($value[0] != ''){
					$es_detail[] = array_combine($header, $value);
				}
			}
		}

		$row = [];
		$row['update'] = []; 
		$row['insert'] = []; 
		$row['delete'] = [];
		$total = [];

		$total['total_amount'] = 0;

		foreach ($es_detail as $key => $value) {
			if($value['contract_detail_id'] != ''){
				$row['delete'][] = $value['contract_detail_id'];
				$row['update'][] = $value;
			}else{
				unset($value['contract_detail_id']);
				$value['staff_contract_id'] = $id;
				$row['insert'][] = $value;
			}

		}

		if(empty($row['delete'])){
			$row['delete'] = ['0'];
		}
		$row['delete'] = implode(",",$row['delete']);
		$this->db->where('contract_detail_id NOT IN ('.$row['delete'] .') and staff_contract_id ='.$id);
		$this->db->delete(db_prefix().'hr_staff_contract_detail');
		if($this->db->affected_rows() > 0){
			$affectedRows++;
		}

		if(count($row['insert']) != 0){
			$this->db->insert_batch(db_prefix().'hr_staff_contract_detail', $row['insert']);
			if($this->db->affected_rows() > 0){
				$affectedRows++;
			}
		}
		if(count($row['update']) != 0){
			$this->db->update_batch(db_prefix().'hr_staff_contract_detail', $row['update'], 'contract_detail_id');
			if($this->db->affected_rows() > 0){
				$affectedRows++;
			}
		}

		if ($affectedRows > 0) {
			return true;
		}
		return false;
	}


	/**
	 * delete contract
	 * @param  integer $id 
	 * @return boolean     
	 */
	public function delete_contract($id){
		$affectedRows = 0;

		$staff_name='';
		$staff_id='';
		$staff_contract_id=$id;


		$staff_contract = $this->get_contract($id);

		if($staff_contract){

			$staff_name .=  get_staff_full_name($staff_contract->staff);
			$staff_id .= $staff_contract->staff;
		}

		$this->db->where('staff_contract_id', $id);
		$this->db->delete(db_prefix() . 'hr_staff_contract_detail');
		if($this->db->affected_rows() > 0){
			$affectedRows++;
		}
		
		$this->db->where('id_contract', $id);
		$this->db->delete(db_prefix() . 'hr_staff_contract');
		if($this->db->affected_rows() > 0){
			$affectedRows++;
		}

		//delete atachement file
		$hr_contract_file = $this->get_hr_control_file($id, 'hr_contract');
		foreach ($hr_contract_file as $file_key => $file_value) {
			$this->delete_hr_contract_attachment_file($file_value['id']);
		}

		/*write log delete contract*/
		log_activity('Staff Contract Deleted [ID Contract: ' . $staff_contract_id . ', ' . $staff_name . ' - ' . $staff_id . ' Deleted by '. get_staff_full_name(get_staff_user_id()). ' - '.get_staff_user_id().' ]', date('Y-m-d H:i:s'), get_staff_full_name(get_staff_user_id()));

		if ($affectedRows > 0) {
			return true;
		}

		return false;
	}

	/**
	 * get staff active
	 * @return array 
	 */
	public function get_staff_active()
	{
		$staff = $this->db->query('select * from '.db_prefix().'staff as s where s.active = "1"  order by s.staffid')->result_array();
		return $staff;
	}

	/**
	 * get staff active has contract
	 * @return array 
	 */
	public function get_staff_active_has_contract()
	{
		$where = '(select count(*) from '.db_prefix().'hr_staff_contract where staff = '.db_prefix().'staff.staffid and start_valid <="'.date('Y-m-d').'" and IF(end_valid != null, end_valid >="'.date('Y-m-d').'",1=1)) > 0 and (status_work="working" OR status_work="maternity_leave") and active=1';

		$this->db->where($where);
		return $this->db->get(db_prefix().'staff')->result_array();
	}


	/**
	 *  update prefix number
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function update_prefix_number($data)
	{
		$affected_rows=0;

		$hr_control_hide_menu = 0;
		if(isset($data['hr_control_hide_menu'])){
			$hr_control_hide_menu = $data['hr_control_hide_menu'];
			unset($data['hr_control_hide_menu']);
		}
		$update_option_re =  update_option('hr_control_hide_menu', $hr_control_hide_menu);
		if($update_option_re){
			$affected_rows++;
		}

		foreach ($data as $key => $value) {

			$this->db->where('option_name',$key);
			$this->db->update(db_prefix() . 'hr_control_option', [
				'option_val' => $value,
			]);

			if ($this->db->affected_rows() > 0) {
				$affected_rows++;
			}
			
		}

		if($affected_rows > 0){
			return true;
		}else{
			return false;
		}
	}


	/**
	 * create code
	 * @param  [type] $rel_type 
	 * @return [type]           
	 */
	public function create_code($rel_type) {
		//rel_type: position_code, staff_contract, ...
		$str_result ='';

		$prefix_str ='';
		switch ($rel_type) {
			case 'position_code':
				$prefix_str .= get_hr_control_option('job_position_prefix');
				$next_number = (int) get_hr_control_option('job_position_number');
				$str_result .= $prefix_str.str_pad($next_number,5,'0',STR_PAD_LEFT);
				break;
			case 'staff_contract_code':
				$prefix_str .= get_hr_control_option('contract_code_prefix');
				$next_number = (int) get_hr_control_option('contract_code_number');
				$str_result .= $prefix_str.str_pad($next_number,5,'0',STR_PAD_LEFT).'-'.date('M-Y');
				break;
			case 'staff_code':
				$prefix_str .= get_hr_control_option('staff_code_prefix');
				$next_number = (int) get_hr_control_option('staff_code_number');
				$str_result .= $prefix_str.str_pad($next_number,5,'0',STR_PAD_LEFT);
				break;
			
			default:
				# code...
				break;
		}

		return $str_result;

	}


	/**
	 * check department format
	 * @param  [type] $department 
	 * @return [type]             
	 */
	public function check_department_format($departments)
	{
		$str_error = '';
		$department = [];

		$arr_department = explode(',', $departments);
		for ($i = 0; $i < count($arr_department); $i++) {

			$this->db->like(db_prefix() . 'departments.departmentid', $arr_department[$i]);
			$department_value = $this->db->get(db_prefix() . 'departments')->row();

			if($department_value){
				$department[$i] = $department_value->departmentid;
			}else{

				$str_error .= $arr_department[$i].', ';
				return ['status' => false, 'result' => $str_error];
			}
		}

		return ['status' => true, 'result' => $department];
	}


	/**
	 * get dependent person
	 * @param  boolean $id 
	 * @return [type]      
	 */
	public function get_dependent_person($id = false)
	{
		if (is_numeric($id)) {
			$this->db->where('id', $id);

			return $this->db->get(db_prefix() . 'hr_dependent_person')->row();
		}

		if ($id == false) {
		   return  $this->db->get(db_prefix() . 'hr_dependent_person')->result_array();
		}

	}    


	/**
	 * get dependent person bytstaff
	 * @param  [type] $staffid 
	 * @return [type]          
	 */
	public function get_dependent_person_bytstaff($staffid)
	{
		$this->db->where('staffid', $staffid);
		return $this->db->get(db_prefix() . 'hr_dependent_person')->result_array();
	}


	/**
	 * add dependent person
	 * @param [type] $data 
	 */
	public function add_dependent_person($data)
	{
		if(!isset($data['staffid'])){
			$data['staffid'] = get_staff_user_id();
		}

		$data['dependent_bir'] = to_sql_date($data['dependent_bir']);

		if(isset($data['start_month'])){
			$data['start_month'] = to_sql_date($data['start_month']);
		}

		if(isset($data['end_month'])){
			$data['end_month'] = to_sql_date($data['end_month']);
		}
		
		$this->db->insert(db_prefix().'hr_dependent_person', $data);
		$insert_id = $this->db->insert_id();
		if($insert_id){
			return $insert_id;
		}
		return false;
	}


	/**
	 * update dependent person
	 * @param  [type] $data 
	 * @param  [type] $id   
	 * @return [type]       
	 */
	public function update_dependent_person($data, $id)
	{   
		if(isset($data['start_month'])){
			$data['start_month'] = to_sql_date($data['start_month']);
		}
		
		if(isset($data['end_month'])){
			$data['end_month'] = to_sql_date($data['end_month']);
		}

		$this->db->where('id', $id);
		$data['dependent_bir'] = to_sql_date($data['dependent_bir']);
		$this->db->update(db_prefix() . 'hr_dependent_person', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}


	/**
	 * delete dependent person
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_dependent_person($id)
	{
		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'hr_dependent_person');
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}


	/**
	 * update approval status
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function update_approval_dependent_person($data)
	{
		$data_obj['start_month'] = to_sql_date($data['start_month']);
		$data_obj['end_month'] = to_sql_date($data['end_month']);
		$data_obj['status_comment'] = $data['reason'];
		$data_obj['status'] = $data['status'];

		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix() . 'hr_dependent_person',$data_obj);
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}


	/**
	 * update approval status
	 * @param  [type] $data 
	 * @return [type]       
	 */
	public function update_approval_status($data){
		$data_obj['start_month'] = to_sql_date($data['start_month']);
		$data_obj['end_month'] = to_sql_date($data['end_month']);
		$data_obj['status_comment'] = $data['reason'];
		$data_obj['status'] = $data['status'];

		$this->db->where('id', $data['id']);
		$this->db->update(db_prefix() . 'hr_dependent_person',$data_obj);
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}


	/**
	 * add resignation procedure
	 * @param [type] $data 
	 */
	public function add_resignation_procedure($data)
	{
		$data['dateoff'] = to_sql_date($data['dateoff'], true);
		$data['staff_name'] = get_staff_full_name($data['staffid']);
		$staffid = $data['staffid'];

		$insert_id = $this->db->insert(db_prefix().'hr_list_staff_quitting_work', $data);

		if($insert_id){
			$asset = $this->get_data_asset($staffid);

			if(count($asset) > 0){
				$rel_id_asset = $this->add_data_of_staff_quit_work_by_id( _l('asset'));
				foreach ($asset as $key => $name) {
					if($rel_id_asset){
						$option_name_by_id = $this->add_data_of_staff_quit_work($rel_id_asset, $name['asset_name'], $staffid);
					}
				}
			}

			$department_staff = $this->departments_model->get_staff_departments($staffid);

			if(count($department_staff) > 0){
				foreach ($department_staff as $deparment) {
					$check = $this->check_department_on_procedure($deparment['departmentid']);
					if(strlen($check) > 0){
						break;
					}
				}
					
			}else{
				$check = '';
			}
			if($check != ''){

				$result = $this->get_procedure_retire($check);

				if(count($result) > 0){
					foreach ($result as $key => $name) {
						if($name['rel_name']){
							$rel_id = $this->add_data_of_staff_quit_work_by_id($name['rel_name'], $name['people_handle_id']);
							if($rel_id){
								$name['option_name'] = json_decode($name['option_name']);
								foreach ($name['option_name'] as $option) {
									$option_name_by_id = $this->add_data_of_staff_quit_work($rel_id, $option, $staffid);
								}
							}

							$people_handle_id = $name['people_handle_id'];
							$staffid_user = get_staff_user_id();
							$subject = get_staff_full_name($staffid);
							$link = 'hr_control/resignation_procedures?detail='.$staffid;

							if($people_handle_id != ''){
								if ($staffid_user != $people_handle_id) {
									$notification_data = [
										'description' => _l('hr_resignation_procedures_are_waiting_for_your_confirmation') .$subject,
										'touserid'    => $people_handle_id,
										'link'        => $link,
									];

									$notification_data['additional_data'] = serialize([
										$subject,
									]);

									if (add_notification($notification_data)) {
										pusher_trigger_notification([$people_handle_id]);
									}

								}
							}
						}
					}
				}

			}

			return $insert_id;
		}

		return false;        
	}

	/**
	 * get data asset
	 * @param  [type] $staffid 
	 * @return [type]          
	 */
	public function get_data_asset($staffid)
	{
		$this->db->where('staff_id', $staffid);
		return $this->db->get(db_prefix().'hr_allocation_asset')->result_array();
	}


	/**
	 * add data of staff quit work by id
	 * @param [type] $rel_name         
	 * @param string $people_handle_id 
	 */
	public function add_data_of_staff_quit_work_by_id($rel_name, $people_handle_id = '')
	{

		if($people_handle_id == ''){
			$people_handle_id = get_staff_user_id();
		}
		$this->db->insert(db_prefix().'hr_procedure_retire_of_staff_by_id',[
			'rel_name' => $rel_name,
			'people_handle_id' => $people_handle_id
		]);
		$insert_id = $this->db->insert_id();

		if ($insert_id) {
			return $insert_id;
		}
		return false;        

	}


	/**
	 * add data of staff quit work
	 * @param [type] $rel_id      
	 * @param [type] $option_name 
	 * @param [type] $staffid     
	 */
	public function add_data_of_staff_quit_work($rel_id, $option_name, $staffid)
	{
		$insert_id = $this->db->insert(db_prefix().'hr_procedure_retire_of_staff',[
			'rel_id' => $rel_id,
			'option_name' => $option_name,
			'status' => 0,
			'staffid' => $staffid
		]);

		if ($insert_id) {
			return $insert_id;
		}
		return false;        

	}


	/**
	 * get resignation procedure by staff
	 * @param  [type] $staff_id 
	 * @return [type]           
	 */
	public function get_resignation_procedure_by_staff($staff_id)
	{
		$this->db->where('staffid', $staff_id);
		$resignation_procedure = $this->db->get(db_prefix() . 'hr_list_staff_quitting_work')->row();

		return $resignation_procedure;
	}


	/**
	 * delete procedures for quitting work
	 * @param  [type] $staffid 
	 * @return [type]          
	 */
	public function delete_procedures_for_quitting_work($staffid)
	{
		$affectedRows = 0;
		$this->db->where('staffid', $staffid);
		$this->db->delete(db_prefix() . 'hr_list_staff_quitting_work');

		if ($this->db->affected_rows() > 0) {
			$affectedRows++;
		}

		$this->db->where('staffid', $staffid);
		$this->db->delete(db_prefix() . 'hr_procedure_retire_of_staff');
		
		if ($this->db->affected_rows() > 0) {
			$affectedRows++;
		}

		if ($affectedRows > 0) {
			return true;
		}
		return false;
	}


	/**
	 * get data procedure retire of staff
	 * @param  [type] $staffid 
	 * @return [type]          
	 */
	public function get_data_procedure_retire_of_staff($staffid)
	{
		$this->db->select('a.id, a.staffid, a.rel_id, a.option_name, a.status, b.rel_name, b.people_handle_id');
		$this->db->from(db_prefix().'hr_procedure_retire_of_staff as a');
		$this->db->join(db_prefix().'hr_procedure_retire_of_staff_by_id as b','b.id = a.rel_id');
		$this->db->where('staffid', $staffid);
		return $this->db->get()->result_array();
	}


	/**
	 * update status quit work
	 * @param  [type] $staffid 
	 * @return [type]          
	 */
	public function update_status_quit_work($staffid, $id)
	{
		$affectedRows = 0;
		$this->db->where('id', $id);
		$this->db->update(db_prefix().'hr_list_staff_quitting_work', [
			'approval' => 'approved'
		]);

		if ($affectedRows > 0) {
			return true;
		}

		if($staffid){
			$this->db->where('staffid',$staffid);
			$this->db->update(db_prefix().'staff', [
				'active' => 0,
				'status_work' => 'inactivity'
			]);
			if ($affectedRows > 0) {
				return true;
			}

		}

		if ($affectedRows > 0) {
			return true;
		}
		return false;

	}


	/**
	 * update status procedure retire of staff
	 * @param  array  $where 
	 * @return [type]        
	 */
	public function update_status_procedure_retire_of_staff($where =[])
	{
		$this->db->where($where);
		$this->db->update(db_prefix().'hr_procedure_retire_of_staff', [
			'status' => 1
		]);

		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}


	/**
	 * delete hr q a attachment file
	 * @param  [type] $attachment_id 
	 * @return [type]                
	 */
	public function delete_hr_q_a_attachment_file($attachment_id)
	{
		$deleted    = false;
		$attachment = $this->get_hr_control_attachments_delete($attachment_id);
		if ($attachment) {
			if (empty($attachment->external)) {
				unlink(get_hr_control_upload_path_by_type('kb_article_files') .$attachment->rel_id.'/'.$attachment->file_name);
			}
			$this->db->where('id', $attachment->id);
			$this->db->delete(db_prefix() . 'files');
			if ($this->db->affected_rows() > 0) {
				$deleted = true;
				log_activity('kb article files Attachment Deleted [job_positionID: ' . $attachment->rel_id . ']');
			}

			if (is_dir(get_hr_control_upload_path_by_type('kb_article_files') .$attachment->rel_id)) {
			// if (is_dir(get_upload_path_by_type('kb_article_files') . $attachment->rel_id)) {
				// Check if no attachments left, so we can delete the folder also
				$other_attachments = list_files(get_hr_control_upload_path_by_type('kb_article_files') .$attachment->rel_id);
				if (count($other_attachments) == 0) {
					// okey only index.html so we can delete the folder also
					delete_dir(get_hr_control_upload_path_by_type('kb_article_files') .$attachment->rel_id);
				}
			}
		}

		return $deleted;
	}


	/**
	 * get salary allowance handsontable
	 * @return [type] 
	 */
	public function get_salary_allowance_handsontable()
	{

		$salary_type        = _l('hr_salary_type');
		$allowance_type     = _l('hr_allowance_type');
		$salary_symbol      = 'st';
		$allowance_symbol   = 'al';

		$salary_types = $this->db->query('select CONCAT("'.$salary_symbol.'","_",form_id) as id, CONCAT("'.$salary_type.'",": ",form_name) as label from ' . db_prefix() . 'hr_salary_form ')->result_array();

		$allowance_types = $this->db->query('select CONCAT("'.$allowance_symbol.'","_",type_id) as id, CONCAT("'.$allowance_type.'",": ",type_name) as label from ' . db_prefix() . 'hr_allowance_type ')->result_array();

		return array_merge($salary_types, $allowance_types);

	}


	/**
	 * delete hr contract attachment file
	 * @param  [type] $attachment_id 
	 * @return [type]               
	 */
	public function delete_hr_contract_attachment_file($attachment_id)
	{
		$deleted    = false;
		$attachment = $this->get_hr_control_attachments_delete($attachment_id);
		if ($attachment) {
			if (empty($attachment->external)) {
				unlink(get_hr_control_upload_path_by_type('staff_contract') .$attachment->rel_id.'/'.$attachment->file_name);
			}
			$this->db->where('id', $attachment->id);
			$this->db->delete(db_prefix() . 'files');
			if ($this->db->affected_rows() > 0) {
				$deleted = true;
				log_activity('staff_contract Attachment Deleted [staff_contractID: ' . $attachment->rel_id . ']');
			}

			if (is_dir(get_hr_control_upload_path_by_type('staff_contract') .$attachment->rel_id)) {
				// Check if no attachments left, so we can delete the folder also
				$other_attachments = list_files(get_hr_control_upload_path_by_type('staff_contract') .$attachment->rel_id);
				if (count($other_attachments) == 0) {
					// okey only index.html so we can delete the folder also
					delete_dir(get_hr_control_upload_path_by_type('staff_contract') .$attachment->rel_id);
				}
			}
		}

		return $deleted;
	}


	/**
	 * get salary allowance for table
	 * @param  [type] $contract_id 
	 * @return [type]              
	 */
	public function get_salary_allowance_for_table($contract_id)
	{   
		$salary_allowance = '';
		$contract_details = $this->get_contract_detail($contract_id);

		if(count($contract_details) > 0){
			foreach ($contract_details as $key => $value) {
				$type_name ='';
				if(preg_match('/^st_/', $value['rel_type'])){
					$rel_value = str_replace('st_', '', $value['rel_type']);
					$salary_type = $this->get_salary_form($rel_value);

					$type = 'salary';
					if($salary_type){
						$type_name = $salary_type->form_name;
					}

				}elseif(preg_match('/^al_/', $value['rel_type'])){
					$rel_value = str_replace('al_', '', $value['rel_type']);
					$allowance_type = $this->get_allowance_type($rel_value);

					$type = 'allowance';
					if($allowance_type){
						$type_name = $allowance_type->type_name;
					}
				}
				$salary_allowance .= $type_name.': '. app_format_money($value['rel_value'],'').'('._l('hr_start_month').':'._d($value['since_date']).')'.'<br>';

			}
		}

		return $salary_allowance;
	}


	/**
	 * send mail training
	 * @param  [type] $email       
	 * @param  [type] $sender_name 
	 * @param  [type] $subject     
	 * @param  [type] $body        
	 * @return [type]              
	 */
	public function send_mail_training($email,$sender_name,$subject,$body){
        $staff_id = get_staff_user_id();
        $inbox = array();
        $inbox['to'] = $email;
        $inbox['sender_name'] = get_option('companyname');
        $inbox['subject'] = _strip_tags($subject);
        $inbox['body'] = _strip_tags($body);        
        $inbox['body'] = nl2br_save_html($inbox['body']);
        $inbox['date_received']      = date('Y-m-d H:i:s');
        
        if(strlen(get_option('smtp_host')) > 0 && strlen(get_option('smtp_password')) > 0 && strlen(get_option('smtp_username')) > 0){
 		$ci = &get_instance();
            $ci->email->initialize();
            $ci->load->library('email');    
            $ci->email->clear(true);
            $ci->email->from(get_option('smtp_email'), $inbox['sender_name']);
            $ci->email->to($inbox['to']);
            
            $ci->email->subject($inbox['subject']);
            $ci->email->message($inbox['body']);
          
            $ci->email->send(true);
        }
        return true;
    }

    /**
     * get board mark form
     * @param  [type] $rel_id 
     * @return [type]         
     */
    public function get_board_mark_form($rel_id){
         $this->db->where('training_id',$rel_id);
        return $this->db->get(db_prefix().'hr_position_training')->row();
    }


    public function report_by_leave_statistics()
    {
    	$months_report = $this->input->post('months_report');
    	$custom_date_select = '';
    	if ($months_report != '') {

    		if (is_numeric($months_report)) {
                // Last month
    			if ($months_report == '1') {
    				$beginMonth = date('Y-m-01', strtotime('first day of last month'));
    				$endMonth   = date('Y-m-t', strtotime('last day of last month'));
    			} else {
    				$months_report = (int) $months_report;
    				$months_report--;
    				$beginMonth = date('Y-m-01', strtotime("-$months_report MONTH"));
    				$endMonth   = date('Y-m-t');
    			}

    			$custom_date_select = '(hrl.start_time BETWEEN "' . $beginMonth . '" AND "' . $endMonth . '")';
    		} elseif ($months_report == 'this_month') {
    			$custom_date_select = '(hrl.start_time BETWEEN "' . date('Y-m-01') . '" AND "' . date('Y-m-t') . '")';
    		} elseif ($months_report == 'this_year') {
    			$custom_date_select = '(hrl.start_time BETWEEN "' .
    			date('Y-m-d', strtotime(date('Y-01-01'))) .
    			'" AND "' .
    			date('Y-m-d', strtotime(date('Y-12-31'))) . '")';
    		} elseif ($months_report == 'last_year') {
    			$custom_date_select = '(hrl.start_time BETWEEN "' .
    			date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-01-01'))) .
    			'" AND "' .
    			date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-12-31'))) . '")';
    		} elseif ($months_report == 'custom') {
    			$from_date = to_sql_date($this->input->post('report_from'));
    			$to_date   = to_sql_date($this->input->post('report_to'));
    			if ($from_date == $to_date) {
    				$custom_date_select =  'hrl.start_time ="' . $from_date . '"';
    			} else {
    				$custom_date_select = '(hrl.start_time BETWEEN "' . $from_date . '" AND "' . $to_date . '")';
    			}
    		}

    	}

    	$chart = [];
    	$dpm = $this->departments_model->get();
    	foreach($dpm as $d){
    		$chart['categories'][] = $d['name'];

    		$chart['sick_leave'][] = $this->count_type_leave($d['departmentid'],1,$custom_date_select);
    		$chart['maternity_leave'][] = $this->count_type_leave($d['departmentid'],2,$custom_date_select);
    		$chart['private_work_with_pay'][] = $this->count_type_leave($d['departmentid'],3,$custom_date_select);
    		$chart['private_work_without_pay'][] = $this->count_type_leave($d['departmentid'],4,$custom_date_select);
    		$chart['child_sick'][] = $this->count_type_leave($d['departmentid'],5,$custom_date_select);
    		$chart['power_outage'][] = $this->count_type_leave($d['departmentid'],6,$custom_date_select);
    		$chart['meeting_or_studying'][] = $this->count_type_leave($d['departmentid'],7,$custom_date_select);
    	}

    	return $chart;
    }


    /**
     * get list quiting work
     * @param  [type] $staffid 
     * @return [type]          
     */
    public function get_list_quiting_work($staffid){
        if($staffid != ''){
            $this->db->where('staffid', $staffid);
            return $this->db->get(db_prefix().'hr_list_staff_quitting_work')->row();
        }else{
            return $this->db->get(db_prefix().'hr_list_staff_quitting_work')->result_array();
        }
    }

    /**
     * get staff by _month
     * @param  [type] $from_month 
     * @param  [type] $to_month   
     * @return [type]             
     */
    public function get_staff_by_month($from_month, $to_month)
    {
        return $this->db->query('select * from '.db_prefix().'staff where datecreated between \''.$from_month.'\' and \''.$to_month.'\'')->result_array();
    }


    /**
     * get dstafflist by year
     * @param  [type] $year  
     * @param  [type] $month 
     * @return [type]        
     */
    public function get_dstafflist_by_year($year,$month)
    {
    	return $this->db->query('select * from '.db_prefix().'staff where year(datecreated) = \''.$year.'\' and month(datecreated) >= \''.$month.'\' and staffid not in (select staffid from '.db_prefix().'hr_list_staff_quitting_work)')->result_array();
    }


    /**
     * get staff by department id and time
     * @param  [type] $id_department 
     * @param  [type] $from_time     
     * @param  [type] $to_time       
     * @return [type]                
     */
    public function get_staff_by_department_id_and_time($id_department, $from_time, $to_time)
    {
    	$format_from_date = preg_replace('/\//','-', $from_time); 
    	$format_to_date = preg_replace('/\//','-', $to_time);
    	$start_date = strtotime(date_format(date_create($format_from_date),"Y/m/d"));
    	$end_date = strtotime(date_format(date_create($format_to_date),"Y/m/d"));
    	$list_staff = $this->db->query('select * from '.db_prefix().'staff where staffid in (SELECT staffid FROM '.db_prefix().'staff_departments where departmentid = '.$id_department.')')->result_array();

    	$list_id_staff = [];
    	$list_id=[];
    	foreach ($list_staff as $key => $value) {
    		$list_staff_contract = $this->db->query('select * from '.db_prefix().'hr_staff_contract where staff = '.$value['staffid'].'')->result_array();
    		$min = 9999999999;
    		$max = 0;
    		foreach ($list_staff_contract as $key => $item_contract) {
    			$format_date1 = preg_replace('/\//','-', $item_contract['start_valid']); 
    			$date = date_format(date_create($format_date1),"Y/m/d");                                                                 
    			$start_date = strtotime($date);
    			if($start_date < $min){
    				$min = $start_date;
    			}

    			$format_date2 = preg_replace('/\//','-', $item_contract['end_valid']); 
    			$date = date_format(date_create($format_date2),"Y/m/d");                     
    			$to_date = strtotime($date);
    			if($to_date > $max){
    				$max = $to_date;
    			}
    		}
    		if(($min >= $start_date)&&($min <= $end_date)){
    			$list_id[] = $value['staffid'];
    		}
    		else{
    			if(($max>=$end_date)&&($max<=$end_date)){
    				$list_id[] = $value['staffid'];
    			}
    		}
    	}
    	$implode = '0';
    	if(isset($list_id)){
    		if(count($list_id)>0){
    			$implode = implode(',', $list_id);
    		}
    	}
    	return $this->db->query('SELECT * FROM '.db_prefix().'staff where staffid in ('.$implode.')')->result_array();
    }


    /**
     * get department by list id
     * @param  string $list_id 
     * @return [type]          
     */
    public function get_department_by_list_id($list_id = '')
    {
    	if($list_id==''){
    		return $this->db->query('select * from '.db_prefix().'departments')->result_array();
    	}
    	else{
    		return $this->db->query('select * from '.db_prefix().'departments where departmentid in ('.$list_id.')')->result_array();
    	}
    }


    /**
     * get list contract detail staff
     * @param  [type] $staffid 
     * @return [type]          
     */
    public function get_list_contract_detail_staff($staffid)
    {

    	$this->db->where('staff', $staffid);
		$this->db->order_by('start_valid', 'desc');
		$this->db->limit(2);
		$staff_contracts = $this->db->get(db_prefix() . 'hr_staff_contract')->result_array();

		if(count($staff_contracts) == 2){

			$new_salary=0;
			$old_salary=0;
			$staff_contract_ids = [];
			foreach ($staff_contracts as $key => $staff_contract) {
			    if($key == 0){
			    	$date_effect = $staff_contract['start_valid'];
			    }
			    array_push($staff_contract_ids, $staff_contract['id_contract']);
			}

			$this->db->select('sum(rel_value) as rel_value, staff_contract_id');
			$sql_where = 'staff_contract_id IN ("'.implode('", "', $staff_contract_ids).'")';
			$this->db->where($sql_where);
			$this->db->group_by('staff_contract_id');
			$staff_contract_details = $this->db->get(db_prefix().'hr_staff_contract_detail')->result_array();

			$contract_detail=[];
			foreach ($staff_contract_details as $d_key => $staff_contract_detail) {
			    $contract_detail[$staff_contract_detail['staff_contract_id']] = $staff_contract_detail['rel_value'];
			}

			foreach ($staff_contract_ids as $key => $value) {
			    if($key == 0){
			    	//new
			    	if(isset($contract_detail[$value])){
			    		$new_salary = $contract_detail[$value];
			    	}
			    }else{
			    	//old
			    	if(isset($contract_detail[$value])){
			    		$old_salary = $contract_detail[$value];
			    	}
			    }
			}

			$result_array=[];
			$result_array['new_salary']=$new_salary;
			$result_array['old_salary']=$old_salary;
			$result_array['date_effect']=$date_effect;
			$result_array;
			return $result_array;

		}else{
			return false;
		}

    }


    /**
     * get list staff by year
     * @param  [type] $year 
     * @return [type]       
     */
    public function get_list_staff_by_year($year)
    {
    	return $this->db->query('select * from '.db_prefix().'staff where year(datecreated) = \''.$year.'\' and staffid not in (select staffid from '.db_prefix().'hr_list_staff_quitting_work)')->result_array();
    }


    /**
     * count staff by department literacy
     * @param  string $department_ids 
     * @return [type]                 
     */
    public function count_staff_by_department_literacy($department_ids='')
    {
    	$result =[];

    	$this->db->select('count(staffdepartmentid) as total_staff, departmentid, literacy');
    	if($department_ids != ''){
    		$sql_where = db_prefix().'staff_departments.departmentid in ('.$department_ids.')';
    		$this->db->where($sql_where);
    	}
    	$this->db->join(db_prefix() . 'staff', db_prefix() . 'staff.staffid = ' . db_prefix() . 'staff_departments.staffid', 'left');
		$this->db->group_by('departmentid, literacy');
		$this->db->order_by('departmentid', 'asc');
		$staff_departments = $this->db->get(db_prefix().'staff_departments')->result_array();

		$department_id= 0;
		$temp=[];
		foreach ($staff_departments as $key => $value) {
			if($value['literacy'] != ''){
				$temp[$value['literacy']] = $value['total_staff'];

				if(count($staff_departments) != $key+1){
					if($value['departmentid'] != $staff_departments[$key+1]['departmentid']){
						$result[$value['departmentid']] = $temp;
						$temp=[];
					}
				}else{
					$result[$value['departmentid']] = $temp;

				}
			}

		}
		return $result;
    }


    /**
     * report by staffs month
     * @param  [type] $from_date 
     * @param  [type] $to_date   
     * @return [type]            
     */
    public function report_by_staffs_month($from_date, $to_date)
	{
	
		$new_staff_by_month = $this->report_new_staff_by_month($from_date, $to_date);
		$staff_working_by_month = $this->report_staff_working_by_month($from_date, $to_date);
		$staff_quit_work_by_month = $this->report_staff_quit_work_by_month($from_date, $to_date);

		for($_month = 1 ; $_month <= 12; $_month++){
			$month_t = date('m',mktime(0, 0, 0, $_month, 04, 2016));

			if($_month == 5){
				$chart['categories'][] = _l('month_05');
			}else{
				$chart['categories'][] = _l('month_'.$_month);
			}


			$chart['hr_new_staff'][] = isset($new_staff_by_month[$month_t]) ? $new_staff_by_month[$month_t] : 0;
			$chart['hr_staff_are_working'][] = isset($staff_working_by_month[$month_t]) ? $staff_working_by_month[$month_t] : 0;
			$chart['hr_staff_quit'][] = isset($staff_quit_work_by_month[$month_t]) ? $staff_quit_work_by_month[$month_t] : 0;
		}

		return $chart;
	}


	/**
	 * [report_new_staff_by_month
	 * @param  [type] $month 
	 * @return [type]        
	 */
	public function report_new_staff_by_month($from_date ,$to_date)
	{
		$result =[];
		$this->db->select('count(staffid) as total_staff, date_format(datecreated, "%m") as datecreated');
		$sql_where = "date_format(datecreated, '%Y-%m-%d') >= '".$from_date."' AND date_format(datecreated, '%Y-%m-%d') <= '".$to_date."'";
		$this->db->where($sql_where);
		$this->db->group_by("date_format(datecreated, '%m')");
		$staffs = $this->db->get(db_prefix().'staff')->result_array();

		foreach ($staffs as $key => $value) {
		    $result[$value['datecreated']] = (int)$value['total_staff'];
		}
		return $result;
		
	}


	/**
	 * report staff working by month
	 * @param  [type] $month 
	 * @return [type]        
	 */
	public function report_staff_working_by_month($from_date ,$to_date)
	{
		$result =[];
		$this->db->select('count(staffid) as total_staff, date_format(datecreated, "%m") as datecreated');

		$sql_where = "date_format(datecreated, '%Y-%m-%d') >= '".$from_date."' AND date_format(datecreated, '%Y-%m-%d') <= '".$to_date."' AND status_work = 'working'";
		$this->db->where($sql_where);
		$this->db->group_by("date_format(datecreated, '%m')");

		$staffs = $this->db->get(db_prefix().'staff')->result_array();

		foreach ($staffs as $key => $value) {
		    $result[$value['datecreated']] = (int)$value['total_staff'];

		}
		return $result;

	}


	/**
	 * report staff quit work by month
	 * @param  [type] $month 
	 * @return [type]        
	 */
	public function report_staff_quit_work_by_month($from_date ,$to_date)
	{	
		$result =[];

		$this->db->select('count(id) as total_staff, date_format(dateoff, "%m") as datecreated');
		$sql_where = " date_format(dateoff, '%Y-%m') <= '".$to_date."'";
		$this->db->where($sql_where);
		$this->db->group_by("date_format(dateoff, '%m')");
		$quitting_works = $this->db->get(db_prefix().'hr_list_staff_quitting_work')->result_array();


		//
		$this->db->select('count(staffid) as total_staff, date_format(date_update, "%m") as datecreated');
		$sql_where1 = " status_work = 'inactivity' AND date_format(date_update, '%Y-%m') <= '".$to_date."' ";
		$this->db->where($sql_where1);
		$this->db->group_by("date_format(date_update, '%m')");
		$staffs = $this->db->get(db_prefix().'staff')->result_array();

		$arr_result =[];
		foreach ($quitting_works as $value) {
		    if(isset($arr_result[$value['datecreated']])){
		    	$arr_result[$value['datecreated']] += (int)$value['total_staff'];
		    }else{
		    	$arr_result[$value['datecreated']] = $value['total_staff'];
		    }
		}

		foreach ($staffs as $value) {
		    if(isset($arr_result[$value['datecreated']])){
		    	$arr_result[$value['datecreated']] += (int)$value['total_staff'];
		    }else{
		    	$arr_result[$value['datecreated']] = $value['total_staff'];
		    }
		}
		
		return $arr_result;

	}


	/**
	 * hr get training question form by relid
	 * @param  [type] $relid 
	 * @return [type]        
	 */
	public function hr_get_training_question_form_by_relid($rel_id)
	{

		$this->db->where('rel_id', $rel_id);
		$training_question_forms = $this->db->get(db_prefix().'hr_position_training_question_form')->result_array();
		return $training_question_forms;
	}	


	/**
	 * hr get form results by resultsetid
	 * @param  [type] $resultsetid 
	 * @return [type]              
	 */
	public function hr_get_form_results_by_resultsetid($resultsetid, $questionid)
	{

		$boxdescriptionids =[];
		$this->db->where('resultsetid', $resultsetid);
		$this->db->where('questionid', $questionid);
		$form_results = $this->db->get(db_prefix().'hr_p_t_form_results')->result_array();

		foreach ($form_results as $value) {
		    array_push($boxdescriptionids, $value['boxdescriptionid']);
		}
		return $boxdescriptionids;
	}


	/**
	 * delete hr article attachment file
	 * @param  [type] $attachment_id 
	 * @return [type]                
	 */
	public function delete_hr_article_attachment_file($attachment_id)
    {
        $deleted    = false;
        $attachment = $this->get_hr_control_attachments_delete($attachment_id);
        if ($attachment) {
            if (empty($attachment->external)) {
                unlink(get_hr_control_upload_path_by_type('kb_article_files') .$attachment->rel_id.'/'.$attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete(db_prefix() . 'files');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
                log_activity('kb_article_files Attachment Deleted [kb_article_filesID: ' . $attachment->rel_id . ']');
            }

            if (is_dir(get_hr_control_upload_path_by_type('kb_article_files') .$attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(get_hr_control_upload_path_by_type('kb_article_files') .$attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(get_hr_control_upload_path_by_type('kb_article_files') .$attachment->rel_id);
                }
            }
        }

        return $deleted;
    }

    /**
     * get type of training
     * @param  boolean $id 
     * @return [type]      
     */
    public function get_type_of_training($id = false){
    	if (is_numeric($id)) {
    		$this->db->where('id', $id);

    		return $this->db->get(db_prefix() . 'hr_type_of_trainings')->row();
    	}

    	if ($id == false) {
    		return $this->db->query('select * from '.db_prefix().'hr_type_of_trainings order by id desc')->result_array();
    	}

    }

    /**
     * add type of training
     * @param [type] $data 
     */
    public function add_type_of_training($data)
    {

    	$this->db->insert(db_prefix() . 'hr_type_of_trainings', $data);
    	$insert_id = $this->db->insert_id();
    	return $insert_id;
    }

    /**
     * update type of training
     * @param  [type] $data 
     * @param  [type] $id   
     * @return [type]       
     */
	public function update_type_of_training($data, $id)
	{   
		$this->db->where('id', $id);
		$this->db->update(db_prefix() . 'hr_type_of_trainings', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}


	/**
	 * delete type of training
	 * @param  [type] $id 
	 * @return [type]     
	 */
	public function delete_type_of_training($id)
	{
		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'hr_type_of_trainings');
		if ($this->db->affected_rows() > 0) {
			return true;
		}

		return false;
	}
	

	/**
	 * get list training program
	 * @param  [type] $position_id   
	 * @param  [type] $training_type 
	 * @return [type]                
	 */
	public function get_list_training_program($position_id, $training_type)
	{
		$options='';
		if($training_type != 0){

			$training_programs = $this->db->query('select * from '.db_prefix().'hr_jp_interview_training where find_in_set('.$position_id.',job_position_id) and training_type = \''.$training_type.'\' ORDER BY date_add desc')->result_array();
		}else{
			$training_programs = $this->db->query('select * from '.db_prefix().'hr_jp_interview_training where find_in_set('.$position_id.',job_position_id)  ORDER BY date_add desc')->result_array();

		}

	    foreach ($training_programs as $training_program) {
	    	$options .= '<option value="' . $training_program['training_process_id'] . '">' . $training_program['training_name'] . '</option>';
	    }

	    return $options;
	}


	/**
	 * delete tranining result by staffid
	 * @param  [type] $staff_id 
	 * @return [type]           
	 */
	public function delete_tranining_result_by_staffid($staff_id)
	{	
		$affected_rows =0;
		$resultset_training = $this->get_resultset_training($staff_id);
		if($resultset_training){
			$this->db->where('resultsetid', $resultset_training->resultsetid);
			$this->db->delete(db_prefix().'hr_p_t_form_results');
			if ($this->db->affected_rows() > 0) {
				$affected_rows++;
			}
		}

		$this->db->where('staff_id', $staff_id);
		$this->db->delete(db_prefix().'hr_p_t_surveyresultsets');

		if ($this->db->affected_rows() > 0) {
			$affected_rows++;
		}

		if($affected_rows > 0){
			return true;
		}
		return false;
	}

	/**
	 * get additional training
	 * @param  [type] $staff_id 
	 * @return [type]           
	 */
	public function get_additional_training($staff_id)
	{
		$sql_where ='find_in_set("'.$staff_id.'", staff_id)';
		$this->db->where($sql_where);
		$this->db->order_by('training_process_id', 'desc');
		$interview_trainings = $this->db->get(db_prefix() . 'hr_jp_interview_training')->result_array();

		return $interview_trainings;
	}


	/**
	 * get mark staff from resultsetid
	 * @param  [type] $resultsetid 
	 * @return [type]              
	 */
	public function get_mark_staff_from_resultsetid($resultsetid, $id, $staff_id)
	{

		$result_data=[];
		$array_training_point=[];
		$training_program_point=0;

		//Get the latest employee's training result.
	   $trainig_resultset = $this->db->query('select * from '.db_prefix().'hr_p_t_surveyresultsets where resultsetid = \''.$resultsetid.'\'')->result_array();

		$array_training_resultset = [];
		$array_resultsetid = [];
		$list_resultset_id='';

		foreach ($trainig_resultset as $item) {
			if(count($array_training_resultset)==0){
				array_push($array_training_resultset, $item['trainingid']);
				array_push($array_resultsetid, $item['resultsetid']);

				$list_resultset_id.=''.$item['resultsetid'].',';
			}
			if(!in_array($item['trainingid'], $array_training_resultset)){
				array_push($array_training_resultset, $item['trainingid']);
				array_push($array_resultsetid, $item['resultsetid']);

				$list_resultset_id.=''.$item['resultsetid'].',';
			}
		}

		$list_resultset_id = rtrim($list_resultset_id,",");
		$count_out = 0;
		if($list_resultset_id==""){
			$list_resultset_id = '0';
		}else{
			$count_out = count($array_training_resultset);
		}

		$array_result = [];
		foreach ($array_training_resultset as $key => $training_id) {
			$total_question = 0;
			$total_question_point = 0;

		    $total_point = 0;
		    $training_library_name = '';
		    $training_question_forms = $this->hr_control_model->hr_get_training_question_form_by_relid($training_id);
		    $hr_position_training = $this->hr_control_model->get_board_mark_form($training_id);
		    $total_question = count($training_question_forms);
		    if($hr_position_training){
		    	$training_library_name .= $hr_position_training->subject;
		    }
		    foreach ($training_question_forms as $question) {
				$flag_check_correct = true;

				$get_id_correct = $this->hr_control_model->get_id_result_correct($question['questionid']);
		    	$form_results = $this->hr_control_model->hr_get_form_results_by_resultsetid($array_resultsetid[$key], $question['questionid']);

		    	$result_data[$question['questionid']] = [
		    		'array_id_correct' => $get_id_correct,
		    		'form_results' => $form_results
		    	];


		    	if(count($get_id_correct) == count($form_results)){
		    		foreach ($get_id_correct as $correct_key => $correct_value) {
		    		    if(!in_array($correct_value, $form_results)){
							$flag_check_correct = false;
		    		    }
		    		}
		    	}else{
					$flag_check_correct = false;
		    	}

		    	$result_point = $question['point'];
		    	$total_question_point += $result_point;

		    	if($flag_check_correct == true){
		    		$total_point += $result_point;
		    		$training_program_point += $result_point;
		    	}
		        
		    }

		    array_push($array_training_point, [
		    	'training_name' => $training_library_name,
		    	'total_point'	=> $total_point,
		    	'training_id'	=> $training_id,
		    	'total_question'	=> $total_question,
		    	'total_question_point'	=> $total_question_point,
		    ]);
		}

		$response = [];
		$response['training_program_point'] = $training_program_point;
		$response['staff_training_result'] = $array_training_point;
		$response['result_data'] = $result_data;
		$response['staff_name'] = get_staff_full_name($staff_id);
		return $response;
	}


	/**
	 * get training library
	 * @return [type] 
	 */
	public function get_training_library()
	{
		$this->db->order_by('datecreated', 'desc');
		$rs = $this->db->get(db_prefix().'hr_position_training')->result_array();
		return  $rs;
	}

	/**
	 * get training result by training program
	 * @param  [type] $training_program_id 
	 * @return [type]                      
	 */
	public function get_training_result_by_training_program($training_program_id)
	{
		$data=[];
		$training_results=[];

	    $training_program = $this->get_job_position_training_de($training_program_id);

	    if($training_program){
	    	$training_library = $training_program->position_training_id;

	    	if($training_program->additional_training == 'additional_training'){
	    		$staff_ids = $training_program->staff_id;
	    	}else{
	    		//get list staff by job position
	    		
	    		$this->db->where('job_position IN ('. $training_program->job_position_id.') ');
	    		$this->db->select('*');
	    		$staffs = $this->db->get(db_prefix().'staff')->result_array();

	    		$arr_staff_id =[];
	    		$staff_ids = '';
	    		foreach ($staffs as $value) {
	    		    $arr_staff_id[] = $value['staffid'];
	    		}

	    		if(count($arr_staff_id) > 0){
	    			$staff_ids = implode(',', $arr_staff_id);
	    		}
	    	}

	    	if(strlen($staff_ids) > 0){
	    		//get training result by staff and training library
	    		$sql_where="SELECT * FROM ".db_prefix()."hr_p_t_surveyresultsets
	    		where  trainingid IN (". $training_library.") AND staff_id IN (". $staff_ids.")
	    		order by date asc
	    		";
	    		$results = $this->db->query($sql_where)->result_array();

	    		foreach ($results as $value) {
	    		    $training_results[$value['staff_id'].$value['trainingid']] = $value;
	    		}
	    		
	    	}

	    	foreach ($training_results as $training_result) {

	    		$training_temp=[];

					//Get the latest employee's training result.
	    		$get_mark_staff=$this->get_mark_staff_v2($training_result['trainingid'], $training_result['resultsetid']);

	    		if(count($get_mark_staff['staff_training_result']) > 0){
	    			$get_mark_staff['staff_id'] = $training_result['staff_id'];

	    			$get_mark_staff['staff_training_result'][0]['staff_id'] = $training_result['staff_id'];
	    			$get_mark_staff['staff_training_result'][0]['resultsetid'] = $training_result['resultsetid'];
	    			$get_mark_staff['staff_training_result'][0]['hash'] = hr_get_training_hash($training_result['trainingid']);
	    			$get_mark_staff['staff_training_result'][0]['date'] = $training_result['date'];

	    			if(isset($data[$get_mark_staff['staff_training_result'][0]['staff_id']])){
	    				$data[$training_result['staff_id']]['staff_training_result'][] = $get_mark_staff['staff_training_result'][0];
	    				$data[$training_result['staff_id']]['training_program_point'] += (float)$get_mark_staff['training_program_point'];
	    			}else{
	    				$data[$training_result['staff_id']] = $get_mark_staff;
	    			}

	    		}

	    	}
	    }

	    return $data;
	}

	/**
	 * get mark staff v2
	 * @param  [type] $id_staff            
	 * @param  [type] $training_process_id 
	 * @return [type]                      
	 */
	public function get_mark_staff_v2($trainingid, $resultsetid){
		$array_training_point=[];
		$training_program_point=0;


		$array_training_resultset = [];
		$array_resultsetid = [];
		$list_resultset_id='';

			if(count($array_training_resultset)==0){
				array_push($array_training_resultset, $trainingid);
				array_push($array_resultsetid, $resultsetid);

				$list_resultset_id.=''.$resultsetid.',';
			}
			if(!in_array($trainingid, $array_training_resultset)){
				array_push($array_training_resultset, $trainingid);
				array_push($array_resultsetid, $resultsetid);

				$list_resultset_id.=''.$resultsetid.',';
			}

		$list_resultset_id = rtrim($list_resultset_id,",");
		$count_out = 0;
		if($list_resultset_id==""){
			$list_resultset_id = '0';
		}else{
			$count_out = count($array_training_resultset);
		}


		$array_result = [];
		foreach ($array_training_resultset as $key => $training_id) {
			$total_question = 0;
			$total_question_point = 0;

		    $total_point = 0;
		    $training_library_name = '';
		    $training_question_forms = $this->hr_control_model->hr_get_training_question_form_by_relid($training_id);
		    $hr_position_training = $this->hr_control_model->get_board_mark_form($training_id);
		    $total_question = count($training_question_forms);
		    if($hr_position_training){
		    	$training_library_name .= $hr_position_training->subject;
		    }

		    foreach ($training_question_forms as $question) {
				$flag_check_correct = true;

				$get_id_correct = $this->hr_control_model->get_id_result_correct($question['questionid']);
		    	$form_results = $this->hr_control_model->hr_get_form_results_by_resultsetid($array_resultsetid[$key], $question['questionid']);

		    	if(count($get_id_correct) == count($form_results)){
		    		foreach ($get_id_correct as $correct_key => $correct_value) {
		    		    if(!in_array($correct_value, $form_results)){
							$flag_check_correct = false;
		    		    }
		    		}
		    	}else{
					$flag_check_correct = false;
		    	}

		    	$result_point = $this->hr_control_model->get_point_training_question_form($question['questionid']);
		    	$total_question_point += $result_point->point;

		    	if($flag_check_correct == true){
		    		$total_point += $result_point->point;
		    		$training_program_point += $result_point->point;
		    	}
		        
		    }

		    array_push($array_training_point, [
		    	'training_name' => $training_library_name,
		    	'total_point'	=> $total_point,
		    	'training_id'	=> $training_id,
		    	'total_question'	=> $total_question,
		    	'total_question_point'	=> $total_question_point,
		    ]);
		}

		$response = [];
		$response['training_program_point'] = $training_program_point;
		$response['staff_training_result'] = $array_training_point;

		return $response;
	}

	/**
	 * get staff from training program
	 * @param  [type] $training_programs 
	 * @return [type]                    
	 */
	public function get_staff_from_training_program($training_programs)
	{

		$sql_where = 'training_process_id IN ("'.implode(",", $training_programs).'")';
		$this->db->where($sql_where);
		$training_programs = $this->db->get(db_prefix().'hr_jp_interview_training')->result_array();

		$arr_staff_id=[];
		foreach ($training_programs as $training_program) {
		    if($training_program['additional_training'] == 'additional_training'){
				$training_program_staff=explode(',', $training_program['staff_id']);

				foreach ($training_program_staff as $training_staff_id) {
				    if(!in_array($training_staff_id, $arr_staff_id)){

						$arr_staff_id[] = $training_staff_id;
				    }
				}

			}else{
	    		//get list staff by job position

				$this->db->where('job_position in ('. $training_program['job_position_id'].') ');
				$this->db->select('*');
				$staffs = $this->db->get(db_prefix().'staff')->result_array();

				foreach ($staffs as $value) {
					if(!in_array($value['staffid'], $arr_staff_id)){

						$arr_staff_id[] = $value['staffid'];
					}
				}
			}
		}

		if(count($arr_staff_id) > 0){
			return implode(',', $arr_staff_id);
		}else{
			return '';
		}

	}

	/**
	 * get department by manager
	 * @return [type] 
	 */
	public function get_department_by_manager()
	{
		$department_ids=[];

	    $this->db->where('manager_id', get_staff_user_id());
	    $departments = $this->db->get(db_prefix().'departments')->result_array();
	    foreach ($departments as $department) {
	    	$department_id =  $this->get_staff_in_deparment($department['departmentid']);
	    	$department_ids = array_merge($department_ids, $department_id);
	    }

	    $department_ids = array_unique($department_ids);

	    return $department_ids;
	}

	/**
	 * get staff by manager
	 * @return [type] 
	 */
	public function get_staff_by_manager()
	{
		$staff_id=[];

		//get staff by deparment
	    $department_id = $this->get_department_by_manager();
	    if(count($department_id) > 0){
	    	$this->db->where('departmentid IN ('.implode(",", $department_id) .') ');
	    	$staff_departments = $this->db->get(db_prefix().'staff_departments')->result_array();
	    	foreach ($staff_departments as $staff_department) {
	    	    $staff_id[] = $staff_department['staffid'];
	    	}
	    }

	    //get staff by manager with children

	    $this->db->where('team_manage', get_staff_user_id());
	    $this->db->or_where('staffid', get_staff_user_id());
	    $staffs = $this->db->get(db_prefix().'staff')->result_array();
	    foreach ($staffs as $staff) {
	    	$staff_by_manager =  $this->get_staff_in_teammanage($staff['staffid']);
	    	$staff_id = array_merge($staff_id, $staff_by_manager);
	    }
	    //remove same staffid
	    $staff_id = array_unique($staff_id);

	    return $staff_id;
	}

	/**
	 * get staff in teammanage
	 * @param  [type] $teammanage 
	 * @return [type]             
	 */
	public function get_staff_in_teammanage($teammanage)
    {

        $data =[];
        $sql = 'select 
        staffid 
        from    (select * from '.db_prefix().'staff
        order by '.db_prefix().'staff.team_manage, '.db_prefix().'staff.staffid) teammanage_sorted,
        (select @pv := '.$teammanage.') initialisation
        where   find_in_set(team_manage, @pv)
        and     length(@pv := concat(@pv, ",", staffid)) OR staffid = '.$teammanage.'';
        
        $result_arr = $this->db->query($sql)->result_array();
        foreach ($result_arr as $key => $value) {
            $data[$key] = $value['staffid'];
        }

       return $data;
    }

    /**
     * get staff by job position
     * @param  [type] $job_position_id 
     * @return [type]                  
     */
    public function get_staff_by_job_position($job_position_id)
    {
    	$staff_id=[];

    	$this->db->where('job_position IN ('.$job_position_id .') ');
    	$staffs = $this->db->get(db_prefix().'staff')->result_array();
    	foreach ($staffs as $staff) {
    		$staff_id[] = $staff['staffid'];
    	}

    	return $staff_id;   
    }

    /**
     * contract clear signature
     * @param  [type] $id 
     * @return [type]     
     */
	public function contract_clear_signature($id)
	{
		$this->db->select('signature');
		$this->db->where('id_contract', $id);
		$contract = $this->db->get(db_prefix() . 'hr_staff_contract')->row();

		if ($contract) {

			$this->db->where('id_contract', $id);
			$this->db->update(db_prefix() . 'hr_staff_contract', ['signature' => null]);

			if (!empty($contract->signature)) {
				unlink(HR_PROFILE_CONTRACT_SIGN .$contract->id_contract.'/'.$contract->signature);
			}

			return true;
		}

		return false;
	}

	public function hr_get_staff_contract_pdf($id = '', $where = [], $for_editor = false)
	{
		$this->db->select('*,' );
		$this->db->where($where);
		$this->db->join(db_prefix() . 'hr_staff_contract_type', '' . db_prefix() . 'hr_staff_contract_type.id_contracttype = ' . db_prefix() . 'hr_staff_contract.name_contract', 'left');
		$this->db->join(db_prefix() . 'staff', '' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'hr_staff_contract.staff');

		if (is_numeric($id)) {
			$this->db->where(db_prefix() . 'hr_staff_contract.id_contract', $id);
			$contract = $this->db->get(db_prefix() . 'hr_staff_contract')->row();
			if ($contract) {

				if ($for_editor == false) {
					$this->load->library('merge_fields/hr_contract_merge_fields');
					$this->load->library('merge_fields/other_merge_fields');

					$merge_fields = [];
					$merge_fields = array_merge($merge_fields, $this->hr_contract_merge_fields->format($id));
					$merge_fields = array_merge($merge_fields, $this->other_merge_fields->format());

					$logo_url = '';

					foreach ($merge_fields as $key => $val) {
						if($key == '{logo_url}'){
							$logo_url .= $val;
							
						}

						if($key == '{logo_image_with_url}'){
							$val ='';
							
							$val .= '<a href="'.$logo_url.'" class="logo hr-img-responsive" style=" width: 300px; height: auto;">';
							$val .= '<img src="'.$logo_url.'" class="hr-img-responsive" style=" width: 300px; height: auto;" alt="GTSS Solution Viet Nam">';
							$val .= '</a>';
							
						}

						if (stripos($contract->content, $key) !== false) {
							$contract->content = str_ireplace($key, $val, $contract->content);
						} else {
							$contract->content = str_ireplace($key, '', $contract->content);
						}
					}

					//staff
					$contract->content .= '<div class="col-md-6  text-left">';
							$contract->content .= '<p class="bold ">'. _l('staff_signature');

							$contract->content .= '<div class="bold">';
								
								if(is_numeric($contract->staff)){
									$contracts_staff_signer = get_staff_full_name($contract->staff);
								}else {
									$contracts_staff_signer = ' ';
								}

								
								$contract->content .= '<p class="no-mbot">'. _l('contract_signed_by') . ': '.$contracts_staff_signer.'</p>';
								$contract->content .= '<p class="no-mbot">'. _l('contract_signed_date') . ': ' . _d($contract->staff_sign_day) .'</p>';
							$contract->content .= '</div>';
							$contract->content .= '<p class="bold">'. _l('hr_signature_text');
							
						$contract->content .= '</p>';
						$contract->content .= '<div class="pull-left">';
							if(strlen($contract->staff_signature) > 0){

								$contract->content .= '<img src="'.site_url('download/preview_image?path='.protected_file_url_by_path(HR_PROFILE_CONTRACT_SIGN.$contract->id_contract.'/'.$contract->staff_signature)).'" class="img-responsive" alt="">';
							}else{
								$contract->content .= '<img src="" class="img-responsive" alt="">';

							}

						$contract->content .= '</div>';
					$contract->content .= '</div>';

					//company
					$contract->content .= '<div class="col-md-6  text-right">';
							$contract->content .= '<p class="bold">'. _l('company_signature');

							$contract->content .= '<div class="bold">';
								
								if(is_numeric($contract->signer)){
									$contracts_signer = get_staff_full_name($contract->signer);
								}else {
									$contracts_signer = ' ';
								}

								
								$contract->content .= '<p class="no-mbot">'. _l('contract_signed_by') . ': '.$contracts_signer.'</p>';
								$contract->content .= '<p class="no-mbot">'. _l('contract_signed_date') . ': ' . _d($contract->sign_day) .'</p>';
							$contract->content .= '</div>';
							$contract->content .= '<p class="bold">'. _l('hr_signature_text');
							
						$contract->content .= '</p>';
						$contract->content .= '<div class="pull-right">';
							if(strlen($contract->signature) > 0){

								$contract->content .= '<img src="'.site_url('download/preview_image?path='.protected_file_url_by_path(HR_PROFILE_CONTRACT_SIGN.$contract->id_contract.'/'.$contract->signature)).'" class="img-responsive" alt="">';
							}else{

								$contract->content .= '<img src="" class="img-responsive" alt="">';
							}

						$contract->content .= '</div>';
					$contract->content .= '</div>';


				}
			}

			return $contract;
		}
		$contracts = $this->db->get(db_prefix() . 'hr_staff_contract')->result_array();

		return $contracts;
	}

	/**
	 * hr_get_staff_contract_pdf_only_for_pdf
	 * @param  string  $id         
	 * @param  array   $where      
	 * @param  boolean $for_editor 
	 * @return [type]              
	 */
	public function hr_get_staff_contract_pdf_only_for_pdf($id = '', $where = [], $for_editor = false)
	{
		$this->db->select('*,' );
		$this->db->where($where);
		$this->db->join(db_prefix() . 'hr_staff_contract_type', '' . db_prefix() . 'hr_staff_contract_type.id_contracttype = ' . db_prefix() . 'hr_staff_contract.name_contract', 'left');
		$this->db->join(db_prefix() . 'staff', '' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'hr_staff_contract.staff');

		if (is_numeric($id)) {
			$this->db->where(db_prefix() . 'hr_staff_contract.id_contract', $id);
			$contract = $this->db->get(db_prefix() . 'hr_staff_contract')->row();
			if ($contract) {

				if ($for_editor == false) {
					$this->load->library('merge_fields/hr_contract_merge_fields');
					$this->load->library('merge_fields/other_merge_fields');

					$merge_fields = [];
					$merge_fields = array_merge($merge_fields, $this->hr_contract_merge_fields->format($id));
					$merge_fields = array_merge($merge_fields, $this->other_merge_fields->format());

					$logo_url = '';

					foreach ($merge_fields as $key => $val) {
						if($key == '{logo_url}'){
							$logo_url .= $val;
							
						}

						if($key == '{logo_image_with_url}'){
							$val ='';
							
							$val .= '<a href="'.$logo_url.'" class="logo hr-img-responsive" style=" width: 300px; height: auto;">';
							$val .= '<img src="'.$logo_url.'" class="hr-img-responsive" style=" width: 300px; height: auto;" alt="GTSS Solution Viet Nam">';
							$val .= '</a>';
							
						}

						if (stripos($contract->content, $key) !== false) {
							$contract->content = str_ireplace($key, $val, $contract->content);
						} else {
							$contract->content = str_ireplace($key, '', $contract->content);
						}
					}


					if(is_numeric($contract->staff)){
						$contracts_staff_signer = get_staff_full_name($contract->staff);
					}else {
						$contracts_staff_signer = ' ';
					}

					if(is_numeric($contract->signer)){
						$contracts_signer = get_staff_full_name($contract->signer);
					}else {
						$contracts_signer = ' ';
					}


					$contract->content .= '<table class="table">
					<tbody>

					<tr>
					<td  width="50%" class="text-left"><b>'. _l('staff_signature').'</b></td>
					<td width="50%" class="text_right"><b>'. _l('company_signature').'</b></td>
					</tr>

					<tr>
					<td  width="50%" class="text-left"><b>'. _l('contract_signed_by') . '</b>: '.$contracts_staff_signer.'</td>
					<td  width="50%" class="text_right"><b>'. _l('contract_signed_by') . '</b>: '.$contracts_signer.'</td>
					</tr>

					<tr>
					<td  width="50%" class="text-left"><b>'.  _l('contract_signed_date') . '</b>: ' . _d($contract->staff_sign_day) .'</td>
					<td  width="50%" class="text_right"><b>'. _l('contract_signed_date') . '</b>: ' . _d($contract->sign_day).'</td>
					</tr>

					<tr>';
					if(strlen($contract->staff_signature) > 0){

						$contract->content .= '<td  width="50%" class="text-left"><img src="'.site_url('download/preview_image?path='.protected_file_url_by_path(HR_PROFILE_CONTRACT_SIGN.$contract->id_contract.'/'.$contract->staff_signature)).'" class="img-responsive" alt=""></td>';
					}else{
						$contract->content .= '<td  width="50%" class="text-left"><img src="" class="img-responsive" alt=""></td>';
					}

					if(strlen($contract->signature) > 0){
						$contract->content .='<td  width="50%" class="text_right"><img src="'.site_url('download/preview_image?path='.protected_file_url_by_path(HR_PROFILE_CONTRACT_SIGN.$contract->id_contract.'/'.$contract->signature)).'" class="img-responsive" alt=""></td>
					</tr>';
					}else{
						$contract->content .='<td  width="50%" class="text_right"><img src="" class="img-responsive" alt=""></td>
					</tr>';

					}
				

					$contract->content .='</tbody>
					</table>';

					$contract->content  .= '<link href="' . module_dir_url(HR_PROFILE_MODULE_NAME, 'assets/css/pdf_style.css') . '"  rel="stylesheet" type="text/css" />';


				}
			}

			return $contract;
		}
		$contracts = $this->db->get(db_prefix() . 'hr_staff_contract')->result_array();

		return $contracts;
	}

	/**
	 * get contract template
	 * @param  boolean $id 
	 * @return [type]      
	 */
	public function get_contract_template($id = false)
	{
		if (is_numeric($id)) {
			$this->db->where('id', $id);
			return $this->db->get(db_prefix() . 'hr_contract_template')->row();
		}
		if ($id == false) {
		   return  $this->db->get(db_prefix() . 'hr_contract_template')->result_array();
		}

	}

	/**
	 * add contract template
	 * @param [type] $data 
	 */
	public function add_contract_template($data){
		$data['content'] = $data['content'];
		$data['job_position'] = implode(',', $data['job_position']);

		$this->db->insert(db_prefix() . 'hr_contract_template', $data);
		$insert_id = $this->db->insert_id();

		if ($insert_id) {
			return $insert_id;
		}
		return false;
	}

	/**
	 * update contract template
	 * @param  [type] $data 
	 * @param  [type] $id   
	 * @return [type]       
	 */
	public function update_contract_template($data, $id)
	{   
		$data['content'] = $data['content'];
		$data['job_position'] = implode(',', $data['job_position']);

		$this->db->where('id', $id);
		$this->db->update(db_prefix() . 'hr_contract_template', $data);

		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}

	/**
	 * delete contract template 
	 * @param  [type] $id [
	 * @return [type]     [
	 */
	public function delete_contract_template($id){
		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'hr_contract_template');

		if ($this->db->affected_rows() > 0) {
			return true;
		}

		return false;
	}

	/**
	 * hr get contract template by staff
	 * @param  [type] $staffid 
	 * @return [type]          
	 */
	public function hr_get_contract_template_by_staff($staffid)
	{	
		$content ='';
		$staff = $this->get_staff($staffid);
		if($staff){
			if( is_numeric($staff->job_position) && $staff->job_position != 0 && $staff->job_position != '' ){

				$sql_where ='find_in_set("'.$staff->job_position.'", job_position)';
				$this->db->where($sql_where);
				$this->db->order_by('id', 'desc');
				$contract_template = $this->db->get(db_prefix() . 'hr_contract_template')->row();

				if($contract_template){
					$content = $contract_template->content;
				}
			}
		}

		return $content;
	}

	function update_hr_staff_contract_content($id, $staffid)
	{
		$content = $this->hr_get_contract_template_by_staff($staffid);

		$this->db->where('id_contract', $id);
		$this->db->update(db_prefix() . 'hr_staff_contract', ['content' => $content]);

		if ($this->db->affected_rows() > 0) {
			return true;
		}
		return false;
	}
}

