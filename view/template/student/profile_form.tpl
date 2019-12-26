<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
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
	<div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a></div>    
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_email; ?></td>
          <td><input type="text" name="email" value="<?php echo $email; ?>" />
          	  <input type="hidden" name="username" value="<?php echo $username; ?>" />
          	<?php if ($error_email) { ?>
            <span class="error"><?php echo $error_email; ?></span>
            <?php } ?></td>
        </tr>
        <!--
        <tr>
          <td><?php echo $entry_username; ?></td>
          <td><?php echo $username; ?>
          	<input type="hidden" name="username" value="<?php echo $username; ?>" />
            <?php if ($error_username) { ?>
            <span class="error"><?php echo $error_username; ?></span>
            <?php } ?></td>
        </tr>
        -->
        <tr>
          <td><?php echo $entry_password; ?></td>
          <td><input type="password" name="password" value="<?php echo $password; ?>">
            <?php if ($error_password) { ?>
            <span class="error"><?php echo $error_password; ?></span>
            <?php  } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_confirm; ?></td>
          <td><input type="password" name="confirm" value="<?php echo $confirm; ?>" />
            <?php if ($error_confirm) { ?>
            <span class="error"><?php echo $error_confirm; ?></span>
            <?php  } ?></td>
        </tr>
        <tr>
          <td valign="top"><?php echo $entry_student_status; ?></td>
          <td><?php echo $student_status; ?>
          <input type="hidden" name="student_status" value="<?php echo $student_status_id; ?>" /></td>
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
        <tr>
          <td valign="top"><span class="required">*</span> <?php echo $entry_grade_year; ?></td>
          <td><select name="grade_year" id="grade_year" onchange="$('.scrollbox').load('index.php?route=user/students/subjects&grade_id=' + this.value + '&filter_ids=<?php echo implode(",", $all_subject_ids); ?>')">
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
            <input type="hidden" name="grade_year_old" value="<?=$grade_year?>" />
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
            </div>
            <input type="hidden" name="old_subject_ids" value="<?=$old_subject_ids?>" />
            </td>
        </tr>
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
          <td><input type="text" name="cellphone" id="cellphone" value="<?php echo $cellphone; ?>" />
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
          	  <?php echo $list_states; ?>
          	  <?php /* ?>
              <option value=""><b>---Select A Province---</b></option>
              <option <?php if($state=="Alberta" || $state=='AB')echo "selected";?>>Alberta</option>
              <option <?php if($state=="British ColumbiaCana")echo "selected";?>>British ColumbiaCana</option>
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
              <option <?php if($state=="Wisconsin")echo "selected";?>>Wisconsin</option>
              <?php */ ?>
            </select>
            <?php if ($error_state) { ?>
            <span class="error"><?php echo $error_state; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_postcode; ?></td>
          <td><input type="text" name="postcode" id="postcode" value="<?php echo $postcode; ?>" />
            <?php if ($error_postcode) { ?>
            <span class="error"><?php echo $error_postcode; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_country; ?></td>
          <td><select name="country">
              <option value="" >-- Select Country --</option>          
          	  <?php echo $list_country; ?>
          	  <?php /* ?>
              <option <?php if($country=="Canada")echo "selected";?>>Canada</option>
              <option <?php if($country=="USA")echo "selected";?>>USA</option>
              <?php */ ?>
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
          <td colspan="2" align="center"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
function update_subjects() {
//$('.scrollbox').load('index.php?route=user/students/subjects&grade_id=' + this.value + '&filter_id=<?php echo $user_id; ?>');
}
//--></script>
<?php echo $footer; ?>