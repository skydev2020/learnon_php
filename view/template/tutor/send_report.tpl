<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/user.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_continue; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_student; ?></td>
          <td>
		    <select name="students_id" onchange="if(this.value!='')this.form.submit();">
		    <option value=""> Select </option>
			<?php foreach($students as $student){
				if($student['students_id']==$students_id){ ?>
			<option value="<?=$student['students_id']?>" selected="selected"><?=$student['student_name']?></option>
			<?php }else{ ?>
			<option value="<?=$student['students_id']?>"><?=$student['student_name']?></option>	 
			<?php }}?>
			</select>
            <?php if ($error_student) { ?>
            <span class="error"><?php echo $error_student; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_grade; ?></td>
          <td><input type="hidden" name="grade" value="<?php echo $grade; ?>" /><?php echo $grade; ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_subjects; ?></td>
          <td><input type="hidden" name="subjects" value="<?=$subjects?>" /><?=stripslashes($subjects)?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_student_prepared; ?></td>
          <td><input type="radio" name="student_prepared" value="0" <?php if($student_prepared != '1'){echo 'checked';}?> /> No <input type="radio" name="student_prepared" value="1" <?php if($student_prepared == '1'){echo 'checked';}?> /> Yes
		  </td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_questions_ready; ?></td>
          <td><input type="radio" name="questions_ready" value="0" <?php if($questions_ready != '1'){echo 'checked';}?> /> No <input type="radio" name="questions_ready" value="1" <?php if($questions_ready == '1'){echo 'checked';}?> /> Yes
		  </td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_pay_attention; ?></td>
          <td><input type="radio" name="pay_attention" value="0" <?php if($pay_attention != '1'){echo 'checked';}?> /> No <input type="radio" name="pay_attention" value="1" <?php if($pay_attention == '1'){echo 'checked';}?> /> Yes
		  </td>
        </tr>
        <tr>
          <td valign="top"><span class="required">*</span> <?php echo $entry_weaknesses; ?></td>
          <td><textarea name="weaknesses" cols="50" rows="5"><?php echo $weaknesses; ?></textarea>
            <?php if ($error_weaknesses) { ?>
            <span class="error"><?php echo $error_weaknesses; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_listen_to_suggestions; ?></td>
          <td><input type="radio" name="listen_to_suggestions" value="0" <?php if($listen_to_suggestions != '1'){echo 'checked';}?> /> No <input type="radio" name="listen_to_suggestions" value="1" <?php if($listen_to_suggestions == '1'){echo 'checked';}?> /> Yes
		  </td>
        </tr>
        <tr>
          <td valign="top"><?php echo $entry_improvements; ?></td>
          <td><textarea name="improvements" cols="50" rows="5"><?php echo $improvements; ?></textarea></td>
        </tr>
        <tr>
          <td valign="top"><?php echo $entry_other_comments; ?></td>
          <td><textarea name="other_comments" cols="50" rows="5"><?php echo $other_comments; ?></textarea></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php echo $footer; ?>