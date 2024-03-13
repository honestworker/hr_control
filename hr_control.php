<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: HR Control
Description: This module encompasses everything that goes into onboarding and paying your employees & provide a central database containing records for all employees past and presen & Recruitment Management & with timesheet mostly work with attendance, leave, holiday and shift
Version: 1.0.0
Requires at least: 2.3.*
Author: GreenTech Solutions
Author URI: https://codecanyon.net/user/greentech_solutions
*/

define('HR_CONTROL_MODULE_NAME', 'hr_control');
define('HR_CONTROL_MODULE_UPLOAD_FOLDER', module_dir_path(HR_CONTROL_MODULE_NAME, 'uploads'));
define('HR_CONTROL_ATTENDANCE_SAMPLE_UPLOAD_FOLDER', module_dir_path(HR_CONTROL_MODULE_NAME, 'uploads/attendance_sample_file/'));
define('HR_CONTROL_PAYSLIP_FOLDER', module_dir_path(HR_CONTROL_MODULE_NAME, 'uploads/payslip/'));

define('HR_CONTROL_CREATE_PAYSLIP_EXCEL', 'modules/hr_control/uploads/payslip_excel_file/');
define('HR_CONTROL_CREATE_ATTENDANCE_SAMPLE', 'modules/hr_control/uploads/attendance_sample_file/');
define('HR_CONTROL_CREATE_EMPLOYEES_SAMPLE', 'modules/hr_control/uploads/employees_sample_file/');
define('HR_CONTROL_CREATE_COMMISSIONS_SAMPLE', 'modules/hr_control/uploads/commissions_sample_file/');
define('HR_CONTROL_ERROR', 'modules/hr_control/uploads/file_error_response/');
define('HR_CONTROL_PAYSLIP_FILE', 'modules/hr_control/uploads/payslip/');
define('HR_CONTROL_EXPORT_EMPLOYEE_PAYSLIP', module_dir_path(HR_CONTROL_MODULE_NAME, 'uploads/export_employee_payslip/'));

define('HR_CONTROL_CONTRACT_ATTACHMENTS_UPLOAD_FOLDER', module_dir_path(HR_CONTROL_MODULE_NAME, 'uploads/contracts/'));
define('HR_CONTROL_JOB_POSIITON_ATTACHMENTS_UPLOAD_FOLDER', module_dir_path(HR_CONTROL_MODULE_NAME, 'uploads/job_position/'));
define('HR_CONTROL_Q_A_ATTACHMENTS_UPLOAD_FOLDER', module_dir_path(HR_CONTROL_MODULE_NAME, 'uploads/q_a/'));
define('HR_CONTROL_FILE_ATTACHMENTS_UPLOAD_FOLDER', module_dir_path(HR_CONTROL_MODULE_NAME, 'uploads/att_file/'));
define('HR_CONTROL_IMAGE_UPLOAD_FOLDER', 'uploads/staff_profile_images/');
define('HR_CONTROL_PATH', 'modules/hr_control/uploads/');
define('HR_CONTROL_CONTRACT_SIGN', 'modules/hr_control/uploads/contract_sign/');

define('HR_CONTROL_REVISION', 100);

register_merge_fields('hr_control/merge_fields/hr_contract_merge_fields');
hooks()->add_filter('other_merge_fields_available_for', 'hr_contract_register_other_merge_fields');

//prefix for contract
define('HR_CONTROL_PREFIX_PROBATIONARY', ' (CT1)');
define('HR_CONTROL_PREFIX_FORMAL', ' (CT2)');


hooks()->add_action('admin_init', 'hr_control_permissions');
hooks()->add_action('app_admin_head', 'hr_control_add_head_components');
hooks()->add_action('app_admin_footer', 'hr_control_load_js');
hooks()->add_action('app_search', 'hr_control_load_search');
hooks()->add_action('admin_init', 'hr_control_module_init_menu_items');
hooks()->add_action('admin_navbar_start', 'render_my_profile_icon');

//hr profile hook
hooks()->add_filter('hr_profile_tab_name', 'hr_control_add_tab_name', 10);
hooks()->add_filter('hr_profile_tab_content', 'hr_control_add_tab_content', 10);
hooks()->add_action('hr_profile_load_js_file', 'hr_control_load_js_file');

/**
* Register activation module hook
*/
register_activation_hook(HR_CONTROL_MODULE_NAME, 'hr_control_module_activation_hook');

/**
 * hr control module activation hook
 * @return [type] 
 */
function hr_control_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
* Register language files, must be registered if the module is using languages
*/
register_language_files(HR_CONTROL_MODULE_NAME, [HR_CONTROL_MODULE_NAME]);


$CI = & get_instance();
$CI->load->helper(HR_CONTROL_MODULE_NAME . '/hr_control');

/**
 * Init hr control module menu items in setup in admin_init hook
 * @return null
 */
function hr_control_module_init_menu_items()
{
    $CI = &get_instance();

    if (has_permission('hr_employee','','view') || has_permission('hr_attendance','','view') || has_permission('hr_commission','','view') || has_permission('hr_deduction','','view') || has_permission('hr_bonus_kpi','','view') || has_permission('hr_insurrance','','view') || has_permission('hr_payslip','','view') || has_permission('hr_payslip_template','','view') || has_permission('hr_income_tax','','view') || has_permission('hr_report','','view') || has_permission('hr_setting','','view') || has_permission('hr_employee','','view_own') || has_permission('hr_attendance','','view_own') || has_permission('hr_commission','','view_own') || has_permission('hr_deduction','','view_own') || has_permission('hr_bonus_kpi','','view_own') || has_permission('hr_insurrance','','view_own') || has_permission('hr_payslip','','view_own') || has_permission('hr_payslip_template','','view_own') || has_permission('hr_income_tax','','view_own')) {
        $CI->app_menu->add_sidebar_menu_item('hr_control', [
            'name'     => _l('hr_control'),
            'icon'     => 'fa fa-users',
            'position' => 30,
        ]);
    }
    if (has_permission('hr_employee','','view') || has_permission('hr_employee','','view_own')) {
        $CI->app_menu->add_sidebar_children_item('hr_control', [
            'slug'     => 'hr_manage_employees',
            'name'     => _l('hr_manage_employees'),
            'icon'     => 'fa fa-vcard',
            'href'     => admin_url('hr_control/manage_employees'),
            'position' => 1,
        ]);
    }
    if (has_permission('hr_attendance','','view') || has_permission('hr_attendance','','view_own')) {
        $CI->app_menu->add_sidebar_children_item('hr_control', [
            'slug'     => 'hr_manage_attendance',
            'name'     => _l('hr_manage_attendance'),
            'icon'     => 'fa fa-pencil-square menu-icon',
            'href'     => admin_url('hr_control/manage_attendance'),
            'position' => 2,
        ]);
    }
    if (has_permission('hr_commission','','view') || has_permission('hr_commission','','view_own')) {
        $CI->app_menu->add_sidebar_children_item('hr_control', [
            'slug'     => 'hr_manage_commissions',
            'name'     => _l('hr_commission_manage'),
            'icon'     => 'fa fa-american-sign-language-interpreting',
            'href'     => admin_url('hr_control/manage_commissions'),
            'position' => 3,
        ]);
    }
    if (has_permission('hr_deduction','','view') || has_permission('hr_deduction','','view_own')) {
        $CI->app_menu->add_sidebar_children_item('hr_control', [
            'slug'     => 'hr_manage_deductions',
            'name'     => _l('hr_deduction_manage'),
            'icon'     => 'fa fa-cut',
            'href'     => admin_url('hr_control/manage_deductions'),
            'position' => 4,
        ]);
    }
    if (has_permission('hr_bonus_kpi','','view') || has_permission('hr_bonus_kpi','','view_own')) {
        $CI->app_menu->add_sidebar_children_item('hr_control', [
            'slug'     => 'hr_bonus_kpi',
            'name'     => _l('hr_bonus_kpi'),
            'icon'     => 'fa fa-gift',
            'href'     => admin_url('hr_control/manage_bonus'),
            'position' => 5,
        ]);
    }
    if (has_permission('hr_insurrance','','view') || has_permission('hr_insurrance','','view_own')) {
        $CI->app_menu->add_sidebar_children_item('hr_control', [
            'slug'     => 'hr_insurrance',
            'name'     => _l('hr_insurrance'),
            'icon'     => 'fa fa-medkit',
            'href'     => admin_url('hr_control/manage_insurances'),
            'position' => 6,
        ]);
    }
    if (has_permission('hr_payslip','','view') || has_permission('hr_payslip','','view_own')) {
        $CI->app_menu->add_sidebar_children_item('hr_control', [
            'slug'     => 'hr_pay_slips',
            'name'     => _l('hr_pay_slips'),
            'icon'     => 'fa-solid fa-money-check',
            'href'     => admin_url('hr_control/payslip_manage'),
            'position' => 7,
        ]);
    }
    if (has_permission('hr_payslip_template','','view') || has_permission('hr_payslip_template','','view_own')) {
        $CI->app_menu->add_sidebar_children_item('hr_control', [
            'slug'     => 'hr_payslip_template',
            'name'     => _l('hr_pay_slip_templates'),
            'icon'     => 'fa fa-outdent',
            'href'     => admin_url('hr_control/payslip_templates_manage'),
            'position' => 8,
        ]);
    }
    if (has_permission('hr_income_tax','','view') || has_permission('hr_income_tax','','view_own')) {
        $CI->app_menu->add_sidebar_children_item('hr_control', [
            'slug'     => 'hr_income_tax',
            'name'     => _l('hr_income_tax'),
            'icon'     => 'fa fa-calendar-minus',
            'href'     => admin_url('hr_control/income_taxs_manage'),
            'position' => 9,
        ]);
    }
    if (has_permission('hr_report','','view')) {
        $CI->app_menu->add_sidebar_children_item('hr_control', [
            'slug'     => 'hr_control_reports',
            'name'     => _l('hr_reports'),
            'icon'     => 'fa fa-list-alt',
            'href'     => admin_url('hr_control/reports'),
            'position' => 10,
        ]);
    }
    if (has_permission('hr_setting','','view')) {
        $CI->app_menu->add_sidebar_children_item('hr_control', [
            'slug'     => 'hr_settings',
            'name'     => _l('settings'),
            'icon'     => 'fa fa-cog menu-icon',
            'href'     => admin_url('hr_control/setting?group=income_tax_rates'),
            'position' => 11,
        ]);
    }
    
	if (has_permission('hrm_dashboard','','view') || has_permission('staffmanage_orgchart','','view') || has_permission('hrm_reception_staff','','view') || has_permission('hrm_hr_records','','view') || has_permission('staffmanage_job_position','','view') || has_permission('staffmanage_training','','view') || has_permission('hr_manage_q_a','','view') || has_permission('hrm_contract','','view') || has_permission('hrm_dependent_person','','view') || has_permission('hrm_procedures_for_quitting_work','','view') || has_permission('hrm_report','','view') || has_permission('hrm_setting','','view')|| has_permission('staffmanage_orgchart','','view_own') || has_permission('staffmanage_job_position','','view_own') || has_permission('hrm_reception_staff','','view_own') || has_permission('hrm_hr_records','','view_own') || has_permission('staffmanage_training','','view_own')  || has_permission('hrm_contract','','view_own') || has_permission('hrm_dependent_person','','view_own') || has_permission('hrm_procedures_for_quitting_work','','view_own')) {
        $CI->app_menu->add_sidebar_menu_item('hr_control', [
            'name'     => _l('hr_control'),
            'icon'     => 'fa fa-users', 
            'position' => 5,
        ]);
    }
    if (has_permission('hrm_dashboard','','view')) {
        $CI->app_menu->add_sidebar_children_item('hr_control', [
           'slug'     => 'hr_control_dashboard',
           'name'     => _l('hr_dashboard'),
           'icon'     => 'fa fa-dashboard',
           'href'     => admin_url('hr_control/dashboard'),
           'position' => 1,
       ]);
    }
    if (has_permission('staffmanage_orgchart','','view') || has_permission('staffmanage_orgchart','','view_own')) {
        $CI->app_menu->add_sidebar_children_item('hr_control', [
           'slug'     => 'hr_control_organizational_chart',
           'name'     => _l('hr_organizational_chart'),
           'icon'     => 'fa fa-th-list',
           'href'     => admin_url('hr_control/organizational_chart'),
           'position' => 3,
       ]);
    }
    if (has_permission('hrm_reception_staff','','view') || has_permission('hrm_reception_staff','','view_own')) {
        $CI->app_menu->add_sidebar_children_item('hr_control', [
           'slug'     => 'hr_control_reception_of_staff',
           'name'     => _l('hr_receiving_staff_lable'),
           'icon'     => 'fa fa-edit',
           'href'     => admin_url('hr_control/reception_staff'),
           'position' => 3,
        ]);
    }
    if (has_permission('hrm_hr_records','','view') || has_permission('hrm_hr_records','','view_own')) {
        $CI->app_menu->add_sidebar_children_item('hr_control', [
           'slug'     => 'hr_control_hr_records',
           'name'     => _l('hr_hr_records'),
           'icon'     => 'fa fa-user',
           'href'     => admin_url('hr_control/staff_infor'),
           'position' => 4,
        ]);
    }
    if (has_permission('staffmanage_job_position','','view') || has_permission('staffmanage_job_position','','view_own')) {
        $CI->app_menu->add_sidebar_children_item('hr_control', [
           'slug'     => 'hr_control_job_position_manage',
           'name'     => _l('hr_job_descriptions'),
           'icon'     => 'fa fa-map-pin',
           'href'     => admin_url('hr_control/job_positions'),
           'position' => 2,
        ]);
    }
    if (has_permission('staffmanage_training','','view') || has_permission('staffmanage_training','','view_own')) {
        $CI->app_menu->add_sidebar_children_item('hr_control', [
           'slug'     => 'hr_control_training',
           'name'     => _l('hr_training'),
           'icon'     => 'fa fa-graduation-cap',
           'href'     => admin_url('hr_control/training?group=training_program'),
           'position' => 5,
        ]);
    }
    if (has_permission('hr_manage_q_a','','view')) {
        $CI->app_menu->add_sidebar_children_item('hr_control', [
           'slug'     => 'hr_control_q_a',
           'name'     => _l('hr_q_a'),
           'icon'     => 'fa fa-question-circle',
           'href'     => admin_url('hr_control/knowledge_base_q_a'),
           'position' => 9,
        ]);
    }
    if (has_permission('hrm_contract','','view') || has_permission('hrm_contract','','view_own')) {
        $CI->app_menu->add_sidebar_children_item('hr_control', [
           'slug'     => 'hr_control_contract',
           'name'     => _l('hr_hr_contracts'),
           'icon'     => 'fa fa-wpforms',
           'href'     => admin_url('hr_control/contracts'),
           'position' => 6,
        ]);
    }
    if (has_permission('hrm_dependent_person','','view') || has_permission('hrm_dependent_person','','view_own')) {
        $CI->app_menu->add_sidebar_children_item('hr_control', [
           'slug'     => 'hr_control_dependent_person',
           'name'     => _l('hr_dependent_persons'),
           'icon'     => 'fa fa-address-card-o',
           'href'     => admin_url('hr_control/dependent_persons'),
           'position' => 7,
        ]);
    }
    if (has_permission('hrm_procedures_for_quitting_work','','view') || has_permission('hrm_procedures_for_quitting_work','','view_own')) {
        $CI->app_menu->add_sidebar_children_item('hr_control', [
           'slug'     => 'hr_control_quitting_works',
           'name'     => _l('hr_resignation_procedures'),
           'icon'     => 'fa fa-user-times',
           'href'     => admin_url('hr_control/resignation_procedures'),
           'position' => 8,
        ]);
    }
    if (has_permission('hrm_report','','view')) {
        $CI->app_menu->add_sidebar_children_item('hr_control', [
           'slug'     => 'hr_control_reports',
           'name'     => _l('hr_reports'),
           'icon'     => 'fa fa-list-alt',
           'href'     => admin_url('hr_control/reports'),
           'position' => 10,
        ]);
    }
    if (has_permission('hrm_setting','','view')) {
        $CI->app_menu->add_sidebar_children_item('hr_control', [
           'slug'     => 'hr_control_setting',
           'name'     => _l('hr_settings'),
           'icon'     => 'fa fa-cogs',
           'href'     => admin_url('hr_control/setting?group=contract_type'),
           'position' => 14,
       ]);
    }
}

/**
 * hr control load js
 * @return library 
 */
function hr_control_load_js() {
    $CI = &get_instance();

    $viewuri = $_SERVER['REQUEST_URI'];

    if (!(strpos($viewuri,'admin/hr_control') === false)) {
        echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/js/deactivate_hotkey.js').'?v=' . HR_CONTROL_REVISION.'"></script>';
    }
    if (!(strpos($viewuri, '/admin/hr_control/setting') === false) || !(strpos($viewuri, '/admin/hr_control/manage_employees') === false) || !(strpos($viewuri, '/admin/hr_control/manage_attendance') === false) || !(strpos($viewuri, '/admin/hr_control/manage_deductions') === false) || !(strpos($viewuri, '/admin/hr_control/manage_commissions') === false) || !(strpos($viewuri, '/admin/hr_control/income_taxs_manage') === false) || !(strpos($viewuri, '/admin/hr_control/manage_insurances') === false) ) {   
        echo '<script src="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/handsontable/chosen.jquery.js') . '"></script>';
        echo '<script src="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/handsontable/handsontable-chosen-editor.js') . '"></script>';
    }
    if (!(strpos($viewuri,'admin/hr_control/view_payslip_templates_detail') === false)) {
        echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/luckysheet/js/luckysheet.umd_payslip.js').'?v=' . HR_CONTROL_REVISION.'"></script>';
    }
    if (!(strpos($viewuri,'admin/hr_control/view_payslip_detail') === false)) {
        echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/luckysheet/js/luckysheet.umd_payslip.js').'?v=' . HR_CONTROL_REVISION.'"></script>';
    }
    if (!(strpos($viewuri,'admin/hr_control/view_payslip_templates_detail') === false) || !(strpos($viewuri,'admin/hr_control/view_payslip') === false) ) {
        echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/luckysheet/js/spectrum.min.js').'?v=' . HR_CONTROL_REVISION.'"></script>';
        echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/luckysheet/js/plugin.js').'?v=' . HR_CONTROL_REVISION.'"></script>';
        echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/js/manage.js').'?v=' . HR_CONTROL_REVISION.'"></script>';
        echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/luckysheet/js/vue.js').'?v=' . HR_CONTROL_REVISION.'"></script>';
        echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/luckysheet/js/vuex.js').'?v=' . HR_CONTROL_REVISION.'"></script>';
        echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/luckysheet/js/vuexx.js').'?v=' . HR_CONTROL_REVISION.'"></script>';
        echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/luckysheet/js/index.js').'?v=' . HR_CONTROL_REVISION.'"></script>';
        echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/luckysheet/js/echarts.min.js').'?v=' . HR_CONTROL_REVISION.'"></script>';
        echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/luckysheet/js/chartmix.umd.min.js').'?v=' . HR_CONTROL_REVISION.'"></script>';
        echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/FileSaver.js').'?v=' . HR_CONTROL_REVISION.'"></script>';
        echo '<script  src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/excel.js').'?v=' . HR_CONTROL_REVISION.'"></script>';
        echo '<script  src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/js/exports.js').'?v=' . HR_CONTROL_REVISION.'"></script>';
        echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/js/upload_file.js').'?v=' . HR_CONTROL_REVISION.'"></script>';
        echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/luckysheet/js/luckyexcel.js').'?v=' . HR_CONTROL_REVISION.'"></script>';
        echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/luckysheet/js/store.js').'?v=' . HR_CONTROL_REVISION.'"></script>';
    }
    if (!(strpos($viewuri,'admin/hr_control/reports') === false)) {
        echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/highcharts/highcharts.js').'?v=' . HR_CONTROL_REVISION.'"></script>';
        echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/highcharts/exporting.js').'?v=' . HR_CONTROL_REVISION.'"></script>';
        echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/highcharts/series-label.js').'?v=' . HR_CONTROL_REVISION.'"></script>';
    }

	if (!(strpos($viewuri,'admin/hr_control/dashboard') === false)) {
		echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/highcharts/highcharts.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
		echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/highcharts/variable-pie.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
		echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/highcharts/export-data.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
		echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/highcharts/accessibility.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
		echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/highcharts/exporting.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
		echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/highcharts/highcharts-3d.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
	}
	if (!(strpos($viewuri,'admin/hr_control/reports') === false)) {
		echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/highcharts/highcharts.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
		echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/highcharts/exporting.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
		echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/highcharts/series-label.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
	}
	//settings
	if (!(strpos($viewuri,'admin/hr_control/setting?group=contract_type') === false)) {
		echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/js/setting/contract_type.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
	}
	if (!(strpos($viewuri,'admin/hr_control/setting?group=allowance_type') === false)) {
		echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/js/setting/allowance_type.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
	}
	if (!(strpos($viewuri,'admin/hr_control/setting?group=payroll') === false)) {
		echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/js/setting/payroll.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
	}
	if (!(strpos($viewuri,'admin/hr_control/setting?group=type_of_training') === false)) {
		echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/js/setting/type_of_training.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
	}
	if (!(strpos($viewuri,'admin/hr_control/setting?group=income_tax_individual') === false)) {
		echo '<script src="https://cdn.jsdelivr.net/npm/handsontable@7.2.2/dist/handsontable.full.min.js"></script>';
		echo '<link type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@7.2.2/dist/handsontable.full.min.css">';
	}
	if (!(strpos($viewuri,'admin/hr_control/setting?group=procedure_retire') === false)) {
		echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/js/setting/procedure_retire.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
	}
	if (!(strpos($viewuri,'admin/hr_control/setting?group=salary_type') === false)) {
		echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/js/setting/salary_type.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
	}
	if (!(strpos($viewuri,'admin/hr_control/setting?group=workplace') === false)) {
		echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/js/setting/workplace.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
	}
	if (!(strpos($viewuri,'admin/hr_control/training') === false)) {
		if (!(strpos($viewuri,'training_library') === false)) {
			echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/js/training/training_library.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
		}
	}
	if (!(strpos($viewuri,'admin/hr_control/job_position_manage') === false)) {
		echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/js/job_position/job/job.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
	}
	if (!(strpos($viewuri,'admin/hr_control/job_positions') === false)) {
		echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/js/job_position/position/position_manage.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
	}
	if (!(strpos($viewuri,'admin/hr_control/job_position_view_edit') === false)) {
		echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/js/job_position/job_position_view_edit.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
	}
	if (!(strpos($viewuri,'admin/hr_control/importxlsx') === false)) {
		echo '<script src="'.base_url('assets/plugins/jquery-validation/additional-methods.min.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
	}
	if (!(strpos($viewuri,'admin/hr_control/member') === false)) {
		if (!(strpos($viewuri,'insurrance') === false)) {
			echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/js/hr_record/includes/insurrance.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
		}
		if (!(strpos($viewuri,'income_tax') === false)) {
			echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/js/hr_record/includes/income_tax.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
		}
		if (!(strpos($viewuri,'profile') === false)) {
			echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/js/hr_record/includes/profile.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
		}
	   
		if (!(strpos($viewuri,'dependent_person') === false)) {
			echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/js/hr_record/includes/dependent_person.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
		}
		if (!(strpos($viewuri,'bonus_discipline') === false)) {
			echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/js/hr_record/includes/bonus_discipline.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
		}
		if (!(strpos($viewuri,'application_submitted') === false)) {
			echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/js/hr_record/includes/application_submitted.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
		}
		if (!(strpos($viewuri,'attach') === false)) {
			echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/js/hr_record/includes/attach.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
		}
		if (!(strpos($viewuri,'permission') === false)) {
			echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/js/hr_record/includes/permission.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
		}
	}
	if (!(strpos($viewuri,'admin/hr_control/contracts') === false) || !(strpos($viewuri,'admin/hr_control/staff_infor') === false)|| !(strpos($viewuri,'admin/hr_control/organizational_chart') === false)) {
		echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/ComboTree/comboTreePlugin.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
		echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/ComboTree/icontains.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
		echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/OrgChart-master/jquery.orgchart.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
	}
	if (!(strpos($viewuri,'admin/hr_control/contracts') === false) || !(strpos($viewuri,'admin/hr_control/staff_infor') === false) || !(strpos($viewuri,'admin/hr_control/organizational_chart') === false)) {
		echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/ComboTree/comboTreePlugin.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
		echo '<script src="'.module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/ComboTree/comboTreePlugin.js').'?v=' . VERSION_HR_PROFILE.'"></script>';
	}
	if (!(strpos($viewuri, '/admin/hr_control/contract') === false)) {
	   echo '<script src="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/handsontable/chosen.jquery.js') . '"></script>';
	   echo '<script src="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/handsontable/handsontable-chosen-editor.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/hr_control/contract_sign') === false)) {   
        echo '<script src="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/signature_pad.min.js') . '"></script>';
    }
}

/**
 * hr control add head components
 * @return library 
 */
function hr_control_add_head_components() {
    $CI = &get_instance();
    $viewuri = $_SERVER['REQUEST_URI'];

    if (!(strpos($viewuri, '/admin/hr_control') === false)) { 
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/css/styles.css') . '"  rel="stylesheet" type="text/css" />';
    }
    if (!(strpos($viewuri, '/admin/hr_control/setting') === false) || !(strpos($viewuri, '/admin/hr_control/manage_employees') === false) || !(strpos($viewuri, '/admin/hr_control/manage_attendance') === false) || !(strpos($viewuri, '/admin/hr_control/manage_deductions') === false) || !(strpos($viewuri, '/admin/hr_control/manage_commissions') === false) || !(strpos($viewuri, '/admin/hr_control/income_taxs_manage') === false) || !(strpos($viewuri, '/admin/hr_control/manage_insurances') === false) ) { 
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.css') . '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/handsontable/chosen.css') . '"  rel="stylesheet" type="text/css" />';
        echo '<script src="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.js') . '"></script>';
    }
    if (!(strpos($viewuri,'admin/hr_control/view_payslip_templates_detail') === false) || !(strpos($viewuri,'admin/hr_control/view_payslip') === false)  ) {
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/css/manage.css') . '?v=' . HR_CONTROL_REVISION. '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/luckysheet/css/iconfont.css') . '?v=' . HR_CONTROL_REVISION. '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/luckysheet/css/luckysheet.css') . '?v=' . HR_CONTROL_REVISION. '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/luckysheet/css/plugins.css') . '?v=' . HR_CONTROL_REVISION. '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/luckysheet/css/pluginsCss.css') . '?v=' . HR_CONTROL_REVISION. '"  rel="stylesheet" type="text/css" />';

        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/luckysheet/css/iconCustom.css') . '?v=' . HR_CONTROL_REVISION. '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/luckysheet/css/luckysheet-cellFormat.css') . '?v=' . HR_CONTROL_REVISION. '"  rel="stylesheet" type="text/css" />';
        //not scroll
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/luckysheet/css/luckysheet-core.css') . '?v=' . HR_CONTROL_REVISION. '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/luckysheet/css/luckysheet-print.css') . '?v=' . HR_CONTROL_REVISION. '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/luckysheet/css/luckysheet-protection.css') . '?v=' . HR_CONTROL_REVISION. '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/luckysheet/css/luckysheet-zoom.css') . '?v=' . HR_CONTROL_REVISION. '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/luckysheet/css/chartmix.css') . '?v=' . HR_CONTROL_REVISION. '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/luckysheet/css/spectrum.min.css') . '?v=' . HR_CONTROL_REVISION. '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/luckysheet/css/chartmix.css') . '?v=' . HR_CONTROL_REVISION. '"  rel="stylesheet" type="text/css" />';
    }
    if (!(strpos($viewuri,'admin/hr_control/manage_bonus') === false) ) {
    }
    if (!(strpos($viewuri,'admin/hr_control/payslip_manage') === false) || !(strpos($viewuri,'admin/hr_control/payslip_templates_manage') === false) ) {
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/css/modal_dialog.css') . '?v=' . HR_CONTROL_REVISION. '"  rel="stylesheet" type="text/css" />';
    }
    if (!(strpos($viewuri, '/admin/hr_control/import_xlsx_attendance') === false) || !(strpos($viewuri, '/admin/hr_control/import_xlsx_employees') === false) || !(strpos($viewuri,'admin/hr_control/import_xlsx_commissions') === false) || !(strpos($viewuri,'admin/hr_control/view_payslip_detail') === false) || !(strpos($viewuri,'admin/hr_control/payslip_manage') === false) ) {
       echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/css/box_loading/box_loading.css')  .'?v=' . HR_CONTROL_REVISION. '"  rel="stylesheet" type="text/css" />'; 
    }
    if (!(strpos($viewuri,'admin/hr_control/view_payslip_detail') === false) || !(strpos($viewuri,'admin/hr_control/view_payslip_templates_detail') === false) ) {
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/css/luckysheet.css') . '?v=' . HR_CONTROL_REVISION. '"  rel="stylesheet" type="text/css" />';
    }
    
    if (hr_control_check_hide_menu()) {
        if (!(strpos($viewuri,'admin') === false)) {
            echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/css/hide_sidebar_menu.css') . '?v=' . VERSION_HR_PROFILE. '"  rel="stylesheet" type="text/css" />';
        }
    }
    if (!(strpos($viewuri,'admin/hr_control') === false)) {
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/css/style.css') . '?v=' . VERSION_HR_PROFILE. '"  rel="stylesheet" type="text/css" />';
    }
    if (!(strpos($viewuri,'admin/hr_control/organizational_chart') === false) || !(strpos($viewuri,'admin/hr_control/staff_infor') === false)) {
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/ComboTree/style.css') . '?v=' . VERSION_HR_PROFILE. '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/css/style.css') . '?v=' . VERSION_HR_PROFILE. '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, '/assets/plugins/OrgChart-master/jquery.orgchart.css') . '?v=' . VERSION_HR_PROFILE. '"  rel="stylesheet" type="text/css" />';
    }
    if (!(strpos($viewuri,'admin/hr_control/organizational_chart') === false)) {
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/css/organizational/organizational.css') . '?v=' . VERSION_HR_PROFILE. '"  rel="stylesheet" type="text/css" />';
        echo '<link href="https://fonts.googleapis.com/css?family=Gochi+Hand" rel="stylesheet">';
    }
    if (!(strpos($viewuri,'admin/hr_control/training') === false)) {
        if (!(strpos($viewuri,'insurrance') === false)) {
            echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/css/setting/insurrance.css') . '?v=' . VERSION_HR_PROFILE. '"  rel="stylesheet" type="text/css" />'; 
        }
    }
    if (!(strpos($viewuri,'admin/hr_control/job_position_view_edit') === false) || !(strpos($viewuri,'admin/hr_control/job_positions') === false)|| !(strpos($viewuri,'admin/hr_control/reception_staff') === false)|| !(strpos($viewuri,'admin/hr_control/training') === false)) {
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/css/job/job_position_view_edit.css') . '?v=' . VERSION_HR_PROFILE. '"  rel="stylesheet" type="text/css" />';
    }
    if (!(strpos($viewuri,'admin/hr_control/member') === false) || !(strpos($viewuri,'admin/hr_control/new_member') === false)|| !(strpos($viewuri,'admin/hr_control/staff_infor') === false)) {
        if (!(strpos($viewuri,'profile') === false)) {
            echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/css/hr_record/includes/profile.css') . '?v=' . VERSION_HR_PROFILE. '"  rel="stylesheet" type="text/css" />';
        }
    }
    if (!(strpos($viewuri,'admin/hr_control/import_job_p') === false) || !(strpos($viewuri,'admin/hr_control/import_xlsx_dependent_person') === false) || !(strpos($viewuri,'admin/hr_control/importxlsx') === false)) {
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/css/box_loading/box_loading.css') . '?v=' . VERSION_HR_PROFILE. '"  rel="stylesheet" type="text/css" />';
    }
    if (!(strpos($viewuri,'admin/hr_control/contracts') === false) || !(strpos($viewuri,'admin/hr_control/staff_infor') === false)) {
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME,'assets/plugins/ComboTree/style.css') .'?v=' . VERSION_HR_PROFILE. '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME,'assets/css/ribbons.css') .'?v=' . VERSION_HR_PROFILE. '"  rel="stylesheet" type="text/css" />';
    }
    if ( !(strpos($viewuri,'admin/hr_control/staff_infor') === false)) {
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME,'assets/css/hr_record/hr_record.css') .'?v=' . VERSION_HR_PROFILE. '"  rel="stylesheet" type="text/css" />';
    }
    if (!(strpos($viewuri, '/admin/hr_control/contract') === false)) {  
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.css') . '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/handsontable/chosen.css') . '"  rel="stylesheet" type="text/css" />';
        echo '<script src="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.js') . '"></script>';
    }
    if (!(strpos($viewuri, '/admin/hr_control/dashboard') === false)) { 
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/css/dashboard/dashboard.css') . '?v=' . VERSION_HR_PROFILE. '"  rel="stylesheet" type="text/css" />';
    }
    if (!(strpos($viewuri, '/admin/hr_control/setting') === false)) {
        echo '<link href="' . module_dir_url(HR_CONTROL_MODULE_NAME, 'assets/css/setting/contract_template.css') . '?v=' . VERSION_HR_PROFILE. '"  rel="stylesheet" type="text/css" />';
    }
}

/**
 * hr control permissions
 * @return capabilities 
 */
function hr_control_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];
    $dashboard['capabilities'] = [
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',        
    ];
    $capabilities_3['capabilities'] = [
        'view_own'   => _l('permission_view_own'),
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];
    $capabilities_4['capabilities'] = [
        'view_own'   => _l('permission_view_own'),
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
    ];

    register_staff_capabilities('hr_employee', $capabilities_3, _l('hr_control_employee'));
    register_staff_capabilities('hr_attendance', $capabilities_3, _l('hr_control_attendance'));
    register_staff_capabilities('hr_commission', $capabilities_3, _l('hr_control_commission'));
    register_staff_capabilities('hr_deduction', $capabilities_3, _l('hr_control_deduction'));
    register_staff_capabilities('hr_bonus_kpi', $capabilities_3, _l('hr_control_bonus_kpi'));
    register_staff_capabilities('hr_insurrance', $capabilities_3, _l('hr_control_insurrance'));
    register_staff_capabilities('hr_payslip', $capabilities_3, _l('hr_control_payslip'));
    register_staff_capabilities('hr_payslip_template', $capabilities_3, _l('hr_control_payslip_template'));
    register_staff_capabilities('hr_income_tax', $capabilities_4, _l('hr_control_income_tax'));
    register_staff_capabilities('hr_report', $dashboard, _l('hr_control_report'));
    register_staff_capabilities('hr_setting', $capabilities, _l('hr_control_setting'));
    
    //Dashboard
    register_staff_capabilities('hrm_dashboard', $dashboard, _l('HR_dashboard'));
    //Orgranization
    register_staff_capabilities('staffmanage_orgchart', $capabilities_4, _l('HR_organizational_chart'));
    //Onboarding Process
    register_staff_capabilities('hrm_reception_staff', $capabilities_4, _l('HR_reception_staff'));
    //Hr Profile
    register_staff_capabilities('hrm_hr_records', $capabilities_4, _l('hr_hr_records'));
    //Job Description
    register_staff_capabilities('staffmanage_job_position', $capabilities_4, _l('HR_job_escription'));
    //Training
    register_staff_capabilities('staffmanage_training', $capabilities_4, _l('HR_training'));
    //Q&A
    register_staff_capabilities('hr_manage_q_a', $capabilities_3, _l('HR_q&a'));
    //Contracts
    register_staff_capabilities('hrm_contract', $capabilities_4, _l('HR_contract'));
    //Dependent Persons
    register_staff_capabilities('hrm_dependent_person', $capabilities_4, _l('HR_dependent_persons'));
    //Resignation procedures
    register_staff_capabilities('hrm_procedures_for_quitting_work', $capabilities_4, _l('HR_resignation_procedures'));
    //Reports
    register_staff_capabilities('hrm_report', $dashboard, _l('HR_report'));
    //Settings
    register_staff_capabilities('hrm_setting', $capabilities, _l('HR_setting'));
}

/**
 * hr control add tab name
 * @param  [type] $row  
 * @param  [type] $aRow 
 * @return [type]       
 */
function hr_control_add_tab_name($tab_names)
{
    $tab_names[] = 'hr_payslip';
    return $tab_names;
}

/**
 * hr control add tab content
 * @param  [type] $tab_content_link 
 * @return [type]                   
 */
function hr_control_add_tab_content($tab_content_link)
{
    if (!(strpos($tab_content_link, 'hr_record/includes/hr_payslip') === false)) {
        $tab_content_link = 'hr_control/employee_payslip/staff_payslip_tab_content';
    }

    return $tab_content_link;
}

/**
 * hr control load js file
 * @param  [type] $group_name 
 * @return [type]             
 */
function hr_control_load_js_file($group_name)
{
    echo  require 'modules/hr_control/assets/js/employee_payslip/payslip_js.php';
}

/**
 * hr contract register other merge fields
 * @param  [type] $for 
 * @return [type]      
 */
function hr_contract_register_other_merge_fields($for)
{
    $for[] = 'hr_contract';

    return $for;
}

/**
 * render my profile icon
 * @return [type] 
 */
function render_my_profile_icon() {
    $CI = &get_instance();
    if (!hr_control_check_hide_menu()) {
        echo '<li class="dropdown">
        <a href="' . admin_url('hr_control/member/' . get_staff_user_id()) . '" class="check_in_out_timesheet" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="'._l('hr_my_profile').'"><i class="fa fa-address-card"></i>
        </a>' ;
        echo '</li>';
    }
}