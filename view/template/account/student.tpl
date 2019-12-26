<?php echo $header; ?><br />
<?php if ($error_warning) { ?>
     
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>

<script type="text/javascript">
    $(function() {
        $("#telephone").mask("999 999 9999");
        $("#cellphone").mask("999 999 9999");
		$("#postcode").mask("*** ***");
		
		$("#postcode").focusout(function() {
			$("#postcode").val($("#postcode").val("").toUpperCase());
		});
    });
	
</script>

<div class="box">      
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/user.png');"><?php echo $heading_title; ?></h1>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
      	<tr>
      		<td colspan="2">
      			<h3 style="width:95%;color:#668CBF" ><?php echo $entry_header; ?></h3>
      		</td>
      	</tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_username; ?></td>
          <td><input type="text" name="username" value="<?php echo $username; ?>" />
            <?php if ($error_username) { ?>
            <span class="error"><?php echo $error_username; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_password; ?></td>
          <td><input type="password" name="password" value="<?php echo $password; ?>" style='float:left'>
          	<div style='float:left;width:600px;margin-left:30px;color:#668CBF;font-size:12px'><?php echo $entry_info; ?></div>
            <?php if ($error_password) { ?>
            <span style="clear:both;" class="error"><?php echo $error_password; ?></span>
            <?php  } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_confirm; ?></td>
          <td><input type="password" name="confirm" value="<?php echo $confirm; ?>" />
            <?php if ($error_confirm) { ?>
            <span class="error"><?php echo $error_confirm; ?></span>
            <?php  } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
          <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" />
            <?php if ($error_firstname) { ?>
            <span class="error"><?php echo $error_firstname; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
          <td><input type="text" name="lastname" value="<?php echo $lastname; ?>" />
            <?php if ($error_lastname) { ?>
            <span class="error"><?php echo $error_lastname; ?></span>
            <?php } ?></td>
        </tr>
        <!--<tr>
            <td valign="top"><span class="required">*</span> <?php echo $entry_parent; ?></td>
            <td><input name="parent" type="radio" value="Y" <?php if($parent == "Y") echo "checked";?> />
              Yes
              <input name="parent" type="radio" value="N" <?php if($parent == "N") echo "checked";?> />
              No
              <?php if ($error_parent) { ?>
              <span class="error"><?php echo $error_parent; ?></span>
              <?php } ?>
            </td>
        </tr> -->
        <tr>
          <td valign="top"><span class="required">*</span> <?php echo $entry_grade_year; ?> <div id="loader" style="float:right;display:none;"><img src="view/image/loading.gif" /></div></td>
          <td><select name="grade_year" id="grade_year" onchange="getSubjects(this.value);">
                <?php foreach ($grade_years as $value => $text ) { ?>
                <?php if ($value == $grade_year) { ?>
                <option value="<?php echo $value; ?>" selected="selected"><?php echo $text; ?></option>
                <?php } else { ?>
                <option value="<?php echo $value; ?>"><?php echo $text; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            <?php if ($error_grade_year) { ?>
            <span class="error"><?php echo $error_grade_year; ?></span>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <td><?php echo $entry_subjects; ?></td>
          <td><div class="scrollbox">
              <?php $class = 'odd'; ?>
              <?php foreach ($all_subjects as $subject_id => $subject_name) { ?>
              <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
              <div class="<?php echo $class; ?>">
                <?php if (in_array($subject_id, $all_subject_ids)) { ?>
                <input type="checkbox" name="subjects[]" value="<?php echo $subject_id; ?>" checked="checked" />
                <?php echo $subject_name; ?>
                <?php } else { ?>
                <input type="checkbox" name="subjects[]" value="<?php echo $subject_id; ?>" />
                <?php echo $subject_name; ?>
                <?php } ?>
              </div>
              <?php } ?>
            </div></td>
        </tr>
    	<!--<tr>
          <td><span class="required">*</span> <?php echo $entry_email; ?></td>
          <td><input type="text" name="email" value="<?php echo $email; ?>" />
          	<?php if ($error_email) { ?>
            <span class="error"><?php echo $error_email; ?></span>
            <?php } ?></td>
        </tr> -->        
        <tr>
          <td><span class="required">*</span> <?php echo $entry_parent_firstname; ?></td>
          <td><input type="text" name="parent_firstname" value="<?php echo $parent_firstname; ?>" />
            <?php if ($error_parent_firstname) { ?>
            <span class="error"><?php echo $error_parent_firstname; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_parent_lastname; ?></td>
          <td><input type="text" name="parent_lastname" value="<?php echo $parent_lastname; ?>" />
            <?php if ($error_parent_lastname) { ?>
            <span class="error"><?php echo $error_parent_lastname; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
          <td><input type="text" name="telephone" id="telephone" value="<?php echo $telephone; ?>" />
            <?php if ($error_telephone) { ?>
            <span class="error"><?php echo $error_telephone; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_cellphone; ?></td>
          <td><input type="text" name="cellphone"  id="cellphone" value="<?php echo $cellphone; ?>" />
            <?php if ($error_cellphone) { ?>
            <span class="error"><?php echo $error_cellphone; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_address; ?></td>
          <td><input type="text" name="address" size="50" value="<?php echo $address; ?>" />
            <?php if ($error_address) { ?>
            <span class="error"><?php echo $error_address; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_city; ?></td>
          <td><input type="text" name="city" value="<?php echo $city; ?>" />
            <?php if ($error_city) { ?>
            <span class="error"><?php echo $error_city; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_state; ?></td>
          <td><select name="state">
              <!-- <option value=""><b>---Select A Province---</b></option>
              <option <?php if($state=="Alberta" || $state=='AB')echo "selected";?>>Alberta</option>
              <option <?php if($state=="British Columbia")echo "selected";?>>British Columbia</option>
              <option <?php if($state=="Manitoba")echo "selected";?>>Manitoba</option>
              <option <?php if($state=="New Brunswick")echo "selected";?>>New Brunswick</option>
              <option <?php if($state=="Ontario")echo "selected";?>>Ontario</option>
              <option <?php if($state=="Quebec")echo "selected";?>>Quebec</option>
              <option <?php if($state=="Saskatchewan")echo "selected";?>>Saskatchewan</option>
              <option value=""><b>---Select A State---</b></option>
              <option <?php if($state=="Illinois")echo "selected";?>>Illinois</option>
              <option <?php if($state=="Indiana")echo "selected";?>>Indiana</option>
              <option <?php if($state=="Massachusetts")echo "selected";?>>Massachusetts</option>
              <option <?php if($state=="Michigan")echo "selected";?>>Michigan</option>
              <option <?php if($state=="New Jersey")echo "selected";?>>New Jersey</option>
              <option <?php if($state=="New York")echo "selected";?>>New York</option>
              <option <?php if($state=="North Carolina")echo "selected";?>>North Carolina</option>
              <option <?php if($state=="Ohio")echo "selected";?>>Ohio</option>
              <option <?php if($state=="Pennsylvania")echo "selected";?>>Pennsylvania</option>
              <option <?php if($state=="Virginia")echo "selected";?>>Virginia</option>
              <option <?php if($state=="Washington")echo "selected";?>>Washington</option>
              <option <?php if($state=="Wisconsin")echo "selected";?>>Wisconsin</option> -->
			  <?php echo $list_states; ?>
            </select>
            <?php if ($error_state) { ?>
            <span class="error"><?php echo $error_state; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_postcode; ?></td>
          <td><input type="text" id="postcode" name="postcode" value="<?php echo $postcode; ?>" />
            <?php if ($error_postcode) { ?>
            <span class="error"><?php echo $error_postcode; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_country; ?></td>
          <td><select name="country">
              <option value="" >-- Select Country --</option>
              <!-- <option <?php if($country=="Canada")echo "selected";?>>Canada</option>
              <option <?php if($country=="USA")echo "selected";?>>USA</option> -->
			  <?php echo $list_country;?>
            </select>
            <?php if ($error_country) { ?>
            <span class="error"><?php echo $error_country; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td valign="top"><?php echo $entry_student_note; ?></td>
          <td><textarea name="student_note" rows="4" cols="60"><?php echo $student_note; ?></textarea>
            <?php if ($error_student_note) { ?>
            <span class="error"><?php echo $error_student_note; ?></span>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_major_intersection; ?></td>
          <td><input name="major_intersection" type="text" id="major_intersection" value="<?php echo $major_intersection; ?>" />
            <?php if ($error_major_intersection) { ?>
            <span class="error"><?php echo $error_major_intersection; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_school_name; ?></td>
          <td><input name="school_name" type="text" id="school_name" value="<?php echo $school_name; ?>" />
            <?php if ($error_school_name) { ?>
            <span class="error"><?php echo $error_school_name; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td valign="top"><span class="required">*</span> <?php echo $entry_heard_aboutus; ?></td>
          <td><select name="heard_aboutus">
          	  <?php if(!empty($heard_aboutus)) { ?>
          	  <option value="<?=$heard_aboutus?>" selected="selected"><?=$heard_aboutus?></option>
          	  <option value="">Select One</option>
          	  <?php } else { ?>
          	  <option value="">Select One</option>
              <?php } ?>
              <option value="Google">Google</option>
              <option value="Yahoo">Yahoo</option>
              <option value="Other Search Engine">Other Search Engine</option>
              <option value="Facebook">Facebook</option>
              <option value="Street Sign">Street Sign</option>
              <option value="Your School">Your School</option>
              <option value="Flyer">Flyer</option>
              <option value="Friends">Friends</option>
              <option value="Other">Other</option>
            </select>
            <?php if ($error_heard_aboutus) { ?>
            <span class="error"><?php echo $error_heard_aboutus; ?></span>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <td><?php echo $entry_heard_aboutus_other; ?></td>
          <td><input name="heard_aboutus_other" type="text" id="heard_aboutus_other" value="<?php echo $heard_aboutus_other; ?>" />
            <?php if ($error_heard_aboutus_other) { ?>
            <span class="error"><?php echo $error_heard_aboutus_other; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td colspan="2"><div class="student_agrement" style="height:200px;overflow:auto;">
			<?=$student_agrement?>
          </div></td>
        </tr>
        <tr>
          <td></td>
          <td><input type="checkbox" name="agree" value="1" /> <?php echo $text_agree; ?></td>
        </tr>
        <tr>
          <td></td>
          <td><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></td>
        </tr>
      </table>
    </form>
  </div>  
</div>
<script type="text/javascript"><!--
function getSubjects(grade_id) {
	$('#loader').show();
	$('.scrollbox').load('index.php?route=user/students/subjects&grade_id=' + grade_id + '&filter_ids=<?php echo implode(",", $all_subject_ids); ?>', function() {
  		$('#loader').hide();
	});
}
//--></script>
<?php echo $footer; ?>