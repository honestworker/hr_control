<script>
	$(function() {
		'use strict';

		appValidateForm($('#import_form'),{file_csv:{required:true,extension: "xlsx"},source:'required',status:'required'});

		var language = $('input[name="language"]').val();
		if (language == 'vietnamese')
		{
			$( "#dowload_file_sample" ).append( '<a href="<?php echo admin_url('hr_control/staff_infor'); ?>" class=" mright5 btn btn-default" ><?php echo _l('hr__back') ?></a><hr>' );
		} else {
			$( "#dowload_file_sample" ).append( '<a href="<?php echo admin_url('hr_control/staff_infor'); ?>" class=" mright5 btn btn-default" ><?php echo _l('hr__back') ?></a><hr>' );
		}
	});
	
	function get_laguage() {
		'use strict';
		return $(".header-languages").find("li.active>").html();
	}

	function uploadfilecsv() {
		'use strict';
		if (($("#file_csv").val() != '') && ($("#file_csv").val().split('.').pop() == 'xlsx')) {
			var formData = new FormData();
			formData.append("file_csv", $('#file_csv')[0].files[0]);
			formData.append("csrf_token_name", $('input[name="csrf_token_name"]').val());
			formData.append("leads_import", $('input[name="leads_import"]').val());

			//show box loading
			var html = '';
			html += '<div class="Box">';
			html += '<span>';
			html += '<span></span>';
			html += '</span>';
			html += '</div>';
			$('#box-loading').html(html);
			$(event).attr( "disabled", "disabled" );

			$.ajax({ 
				url: admin_url + 'hr_control/import_profile_employees_excel', 
				method: 'post', 
				data: formData, 
				contentType: false, 
				processData: false
				
			}).done(function(response) {
				response = JSON.parse(response);

				//hide boxloading
				$('#box-loading').html('');
				$(event).removeAttr('disabled')

				$("#file_csv").val(null);
				$("#file_csv").change();
				$(".panel-body").find("#file_upload_response").html();
				if ($(".panel-body").find("#file_upload_response").html() != '') {
					$(".panel-body").find("#file_upload_response").empty();
				};
				if (response.total_rows) {
					$( "#file_upload_response" ).append( "<h4>Result</h4><h5><?php echo _l('import_line_number') ?> :"+response.total_rows+" </h5>" );
				}
				if (response.total_row_success) {
					$( "#file_upload_response" ).append( "<h5><?php echo _l('import_line_number_success') ?> :"+response.total_row_success+" </h5>" );
				}
				if (response.total_row_false) {
					$( "#file_upload_response" ).append( "<h5><?php echo _l('import_line_number_failed') ?> :"+response.total_row_false+" </h5>" );
				}
				if (response.total_row_false > 0)
				{
					$( "#file_upload_response" ).append( '<a href="'+response.site_url+response.filename+'" class="btn btn-warning"  ><?php echo _l('hr_download_file_error') ?></a>' );
					
				}
				if (response.total_rows < 1) {
					alert_float('warning', response.message);
				}
			});
			return false;
		}else if ($("#file_csv").val() != '') {
			alert_float('warning', "<?php echo _l('_please_select_a_file') ?>");
		}
	}


	function staff_export_item() {
		"use strict";
		var data = {};
		data.sample_file = 'true';

		$(event).addClass('disabled');

			setTimeout(function() {
				$.post(admin_url + 'hr_control/create_staff_sample_file', data).done(function(response) {
					response = JSON.parse(response);
					if (response.success == true) {
						alert_float('success', "<?php echo _l("create_sample_file_success") ?>");

						$('#dowload_items').removeClass('hide');
						$('.hr_export_staff').addClass('hide');

						$('#dowload_items').attr({target: '_blank', 
							href  : site_url +response.filename});

					} else {
						alert_float('success', "<?php echo _l("create_sample_file_fails") ?>");

					}

				}).fail(function(data) {


				});
			}, 200);
	}

</script>