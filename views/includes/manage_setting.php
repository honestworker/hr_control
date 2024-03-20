<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-3">
				<ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked">
					<?php
					$i = 0;
					foreach ($tab as $gr) {
						?>
						<li<?php if ($i == 0) {echo " class='active'"; } ?>>
						<a href="<?php echo admin_url('hr_control/setting?group='.$gr); ?>" data-group="<?php echo html_entity_decode($gr); ?>">
							<?php
								$icon['income_tax_rates'] = '<span class="fa fa-area-chart"></span>';
								$icon['income_tax_rebates'] = '<span class="fa-brands fa-shirtsinbulk"></span>';
								$icon['hr_records_earnings_list'] = '<span class="fa fa-dollar"></span>';
								$icon['earnings_list'] = '<span class="fa fa-dollar"></span>';
								$icon['salary_deductions_list'] = '<span class="fa fa-dedent"></span>';
								$icon['company_contributions_list'] = '<span class="fa fa-building-o"></span>';
								$icon['payroll_columns'] = '<span class="fa fa-database"></span>';
								$icon['data_integration'] = '<span class="fa fa-chain-broken"></span>';
								$icon['permissions'] = '<span class="fa fa-unlock-alt"></span>';
								$icon['insurance_list'] = '<span class="fa-brands fa-get-pocket"></span>';
								$icon['reset_data'] = '<span class="fa fa-window-close"></span>';

								if ($gr == 'hr_records_earnings_list') {
									echo html_entity_decode($icon[$gr] .' '. _l('earnings_list'));
								} elseif ($gr == 'income_tax_rates') {
									echo html_entity_decode($icon[$gr] .' '. _l($gr));
								} elseif ($gr == 'income_tax_rebates') {
									echo html_entity_decode($icon[$gr] .' '. _l($gr));
								} elseif ($gr == 'hr_records_earnings_list') {
									echo html_entity_decode($icon[$gr] .' '. _l($gr));
								} elseif ($gr == 'earnings_list') {
									echo html_entity_decode($icon[$gr] .' '. _l($gr));
								} elseif ($gr == 'salary_deductions_list') {
									echo html_entity_decode($icon[$gr] .' '. _l($gr));
								} elseif ($gr == 'insurance_list') {
									echo html_entity_decode($icon[$gr] .' '. _l($gr));
								} elseif ($gr == 'company_contributions_list') {
									echo html_entity_decode($icon[$gr] .' '. _l($gr));
								} elseif ($gr == 'payroll_columns') {
									echo html_entity_decode($icon[$gr] .' '. _l($gr));
								} elseif ($gr == 'data_integration') {
									echo html_entity_decode($icon[$gr] .' '. _l($gr));
								} elseif ($gr == 'permissions') {
									echo html_entity_decode($icon[$gr] .' '. _l($gr));
								} elseif ($gr == 'reset_data') {
									echo html_entity_decode($icon[$gr] .' '. _l($gr));
								} elseif($gr == 'workplace') {
								 	echo _l('hr_hr_workplace');
								} elseif ($gr == 'salary_type') {
								 	echo _l('hr_salary_type');
								} elseif ($gr == 'procedure_retire') {
								 	echo _l('hr_procedure_retire');
								} elseif ($gr == 'type_of_training') {
								 	echo _l('hr_type_of_training');
								} elseif ($gr == 'reception_staff') {
								 	echo _l('hr_reception_staff');
								} elseif ($gr == 'hr_profile_permissions') {
								 	echo _l('hr_hr_profile_permissions');
								} elseif ($gr == 'prefix_number') {
								 	echo _l('hr_prefix_number');
								} elseif ($gr == 'allowance_type') {
								 	echo _l('hr_allowance_type');
								} else {
								 	echo _l($group_item);
								}
							?>
						</a>
					</li>
					<?php $i++; } ?>
				</ul>
			</div>
			<div class="col-md-9">
				<div class="panel_s">
					<div class="panel-body">
						<?php $this->load->view($tabs['view']); ?>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		<?php echo form_close(); ?>
		<div class="btn-bottom-pusher"></div>
	</div>
</div>
<div id="new_version"></div>
<?php init_tail(); ?>

<?php 
$viewuri = $_SERVER['REQUEST_URI'];
?>

<?php if (!(strpos($viewuri,'admin/hr_control/setting?group=income_tax_rates') === false)) { 
	require 'modules/hr_control/assets/js/settings/income_tax_rates_js.php';
} elseif (!(strpos($viewuri,'admin/hr_control/setting?group=payroll_columns') === false)) {
	require 'modules/hr_control/assets/js/payroll_column/payroll_column_js.php';
} elseif (!(strpos($viewuri,'admin/hr_control/setting?group=income_tax_rebates') === false)) {
	require 'modules/hr_control/assets/js/settings/income_tax_rebates_js.php';
} elseif (!(strpos($viewuri,'admin/hr_control/setting?group=earnings_list') === false)) {
	require 'modules/hr_control/assets/js/settings/earnings_list_js.php';
} elseif (!(strpos($viewuri,'admin/hr_control/setting?group=salary_deductions_list') === false)) {
	require 'modules/hr_control/assets/js/settings/salary_deductions_list_js.php';
} elseif (!(strpos($viewuri,'admin/hr_control/setting?group=company_contributions_list') === false)) {
	require 'modules/hr_control/assets/js/settings/company_contributions_list_js.php';
} elseif (!(strpos($viewuri,'admin/hr_control/setting?group=data_integration') === false)) {
	require 'modules/hr_control/assets/js/settings/data_integration_js.php';
} elseif (!(strpos($viewuri,'admin/hr_control/setting?group=hr_records_earnings_list') === false)) {
	require 'modules/hr_control/assets/js/settings/hr_records_earnings_list_js.php';
} elseif (!(strpos($viewuri,'admin/hr_control/setting?group=permissions') === false)) {
	require 'modules/hr_control/assets/js/settings/permissions_js.php';
} elseif (!(strpos($viewuri,'admin/hr_control/setting?group=insurance_list') === false)) {
	require 'modules/hr_control/assets/js/settings/insurance_list_js.php';
} elseif ($group == 'reception_staff') {
	require('modules/hr_control/assets/js/settings/reception_staff_js.php');
} elseif (!(strpos($viewuri,'admin/hr_profile/setting?group=hr_profile_permissions') === false)) {
	require('modules/hr_control/assets/js/settings/hr_profile_permissions_js.php');
}
if (isset($icon[$gr])) {
	require('modules/hr_control/assets/js/settings/manage_setting_js.php');
}

 ?>
</body>
</html>
