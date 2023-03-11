<?php
class admission extends db2
{
	function admission_form_fields()
	{
		$head = '
		<script>
		jQuery(document).ready(function(){
		  jQuery.dobPicker({
			// Selectopr IDs
			daySelector: "#dobday",
			monthSelector: "#dobmonth",
			yearSelector: "#dobyear",
			// Minimum age
			minimumAge: 1,
			// Maximum age
			maximumAge: 25
		  });
		});
		function check_admission()
		{
		
			if(jQuery("#dobmonth").val() == "")
			{
				alert("Month of birth not selected");
				jQuery("#dobmonth").focus();
			}
			else if(jQuery("#dobyear").val() == "")
			{
				alert("Year of birth not selected");
				jQuery("#dobyear").focus();
			}
			else
			{
				var data = {
					student_name : jQuery("#student_name").val(),
					father_name : jQuery("#father_name").val(),
					date_of_birth : jQuery("#dobday").val() + "/" + jQuery("#dobmonth").val() + "/" + jQuery("#dobyear").val(),
					gender_id : jQuery("#gender_id").val(),
					religion : jQuery("#religion").val(),
					admission_class : jQuery("#admission_class").val(),
					last_current_class : jQuery("#last_current_class").val(),
					last_current_school : jQuery("#last_current_school").val(),
					father_contact_number : jQuery("#father_contact_number").val(),
					father_cnic : jQuery("#father_cnic").val(),
					mother_contact_number : jQuery("#mother_contact_number").val(),
					mother_cnic : jQuery("#mother_cnic").val(),
					email_address : jQuery("#email_address").val(),
					address : jQuery("#address").val(),
					siblings_in_gifs : jQuery("#siblings_in_gifs").val()
				};
				jQuery.ajax({
				  url: "admission.php?action=submit_application",
				  method: "POST",
				  data: data,
				  dataType: "json"
				}).done(function( msg ) {
				  if(msg.status == 1)
				  {
				  	//alert(msg.message);
					location.href = "admission.php?action=thanks";
				  }
				});
			}
			jQuery(".wpforms-submit").removeAttr("disabled");
			return false;
		}
		</script>
		<style>
		select#dobday {
			width: 60px;
			display: inline-block;
		}

		select#dobmonth {
			width: 100px;
			display: inline-block;
		}

		select#dobyear {
			display: inline-block;
			width: 80px;
		}
		label#dobday-error, #dobmonth-error, #dobyear-error {position: absolute;top: -20px;}
		</style>
		';
		$temp = '
		<div class="vc_col-sm-6">
			<div  class="wpforms-field wpforms-field-name ">
				<label class="wpforms-field-label" for="student_name">Student\'s Name<span class="wpforms-required-label">*</span></label>
				<input type="text" id="student_name" name="student_name" required="">
			</div>
		 </div>
		<div class="vc_col-sm-6">
			<div  class="wpforms-field wpforms-field-name ">
				<label class="wpforms-field-label" for="father_name">Father\'s Name<span class="wpforms-required-label">*</span></label>
				<input type="text" id="father_name" name="father_name" required="">
			</div>
		 </div>
		<div class="vc_col-sm-6">
			<div  class="wpforms-field wpforms-field-name" style="position:relative">
				<label class="wpforms-field-label" for="date_of_birth">Date of Birth<span class="wpforms-required-label">*</span></label>
				<select id="dobday" required=""></select>
				<select id="dobmonth" required=""></select>
				<select id="dobyear" required=""></select>
			</div>
		 </div>
		<div class="vc_col-sm-6">
			<div  class="wpforms-field wpforms-field-name ">
				<label class="wpforms-field-label" for="gender_id">Gender<span class="wpforms-required-label">*</span></label>
				'.dropdown('gender').'
			</div>
		 </div>
		<div class="vc_col-sm-6">
			<div  class="wpforms-field wpforms-field-name ">
				<label class="wpforms-field-label" for="religion">Religion<span class="wpforms-required-label">*</span></label>
				<input type="text" id="religion" name="religion" required="">
			</div>
		 </div>
		<div class="vc_col-sm-6">
			<div  class="wpforms-field wpforms-field-name ">
				<label class="wpforms-field-label" for="admission_class">Admission for Class<span class="wpforms-required-label">*</span></label>
				'.class_dropdown('admission_class').'
			</div>
		 </div>
		<div class="vc_col-sm-6">
			<div  class="wpforms-field wpforms-field-name ">
				<label class="wpforms-field-label" for="last_current_class">Last / Current Class (if any)
				'.class_dropdown('last_class', '', '').'
			</div>
		 </div>
		<div class="vc_col-sm-6">
			<div  class="wpforms-field wpforms-field-name ">
				<label class="wpforms-field-label" for="last_current_school">Last / Current School (if any)</label>
				<input type="text" id="last_current_school" name="last_current_school">
			</div>
		 </div>
		<div class="vc_col-sm-6">
			<div  class="wpforms-field wpforms-field-name ">
				<label class="wpforms-field-label" for="father_contact_number">Father\'s Contact Number<span class="wpforms-required-label">*</span></label>
				<input type="text" id="father_contact_number" name="father_contact_number" required="">
			</div>
		 </div>
		<div class="vc_col-sm-6">
			<div  class="wpforms-field wpforms-field-name ">
				<label class="wpforms-field-label" for="father_cnic">Father\'s CNIC Number<span class="wpforms-required-label">*</span></label>
				<input type="text" id="father_cnic" name="father_cnic" required="">
			</div>
		 </div>
		<div class="vc_col-sm-6">
			<div  class="wpforms-field wpforms-field-name ">
				<label class="wpforms-field-label" for="mother_contact_number">Mother\'s Contact Number<span class="wpforms-required-label">*</span></label>
				<input type="text" id="mother_contact_number" name="mother_contact_number" required="">
			</div>
		 </div>
		<div class="vc_col-sm-6">
			<div  class="wpforms-field wpforms-field-name ">
				<label class="wpforms-field-label" for="mother_cnic">Mother\'s CNIC Number<span class="wpforms-required-label">*</span></label>
				<input type="text" id="mother_cnic" name="mother_cnic" required="">
			</div>
		 </div>
		<div class="vc_col-sm-6">
			<div  class="wpforms-field wpforms-field-name ">
				<label class="wpforms-field-label" for="email_address">Email Address<span class="wpforms-required-label">*</span></label>
				<input type="text" id="email_address" name="email_address" required="">
			</div>
		 </div>
		<div class="vc_col-sm-6">
			<div  class="wpforms-field wpforms-field-name ">
				<label class="wpforms-field-label" for="address">Residential Address<span class="wpforms-required-label">*</span></label>
				<input type="text" id="address" name="address" required="">
			</div>
		 </div>
		<div class="vc_col-sm-6">
			<div  class="wpforms-field wpforms-field-name ">
				<label class="wpforms-field-label" for="siblings_in_gifs">Siblings (if any in GIFS)<span class="wpforms-required-label">*</span></label>
				<small>Pattern: G.R.No. - Class - Name</small>
				<input type="text" id="siblings_in_gifs" name="siblings_in_gifs">
			</div>
		 </div>

		
		 <br>
		<div class="vc_col-sm-6">
			<button type="submit" name="submit" class="wpforms-submit subscribe-form-btn" id="submit" value="wpforms-submit">Submit</button>
		 </div>';
		
		$temp = '
				<form id="check_admission" class="wpforms-validate wpforms-form" method="post" action="?action=check_admission" onsubmit="return check_admission()">
					 <div class="vc_row wpb_row vc_row-fluid nopadding ">
						 '.$temp.'
					</div>
				</form>';
		return [$head, $temp];
	}
}
function class_dropdown($id='class', $name = '', $required = ' required=""')
{
	$db = new db2();
	if($name = '') $name = $id;
	$temp = '<select name="'.$name.'" id="'.$id.'" '.$required.'>';
	$temp .= '<option value="">Please select</option>';
	$result = $db->result("select * from class where status = 1 order by sort");
	foreach($result as $a)
	{
		$temp .= '<option value="'.$a["id"].'">'.$a["name"].'</option>';
	}
	$temp .= '</select>';
	return $temp;
}