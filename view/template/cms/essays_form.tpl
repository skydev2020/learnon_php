<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background: url('view/image/information.png') 2px 9px no-repeat;"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div id="language">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_assignment_num; ?> <span class="help">(e.g. A123)</span></td>
            <td><input name="assignment_num" id="assignment_num" value="<?php echo $assignment_num; ?>" size="10" />
              <?php if (isset($error_assignment_num)) { ?>
              <span class="error"><?php echo $error_assignment_num; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td valign="top"><?php echo $entry_tutor_name; ?></td>
            <td><select name="tutor_id" id="tutor_id">
            		<option value="FALSE"><?php echo $text_select; ?></option>
	                <?php foreach ($tutors as $each_row) { ?>
	                <?php if ($each_row['tutor_id'] == $tutor_id) { ?>
	                <option value="<?php echo $each_row['tutor_id']; ?>" selected="selected"><?php echo $each_row['tutor_name']; ?></option>
	                <?php } else { ?>
	                <option value="<?php echo $each_row['tutor_id']; ?>"><?php echo $each_row['tutor_name']; ?></option>
	                <?php } ?>
	                <?php } ?>
              </select>
                <?php if ($error_tutor_id) { ?>
                <span class="error"><?php echo $error_tutor_name; ?></span>
                <?php } ?>            </td>
          </tr>
          <tr>
            <td><?php echo $entry_student_name; ?></td>
            <td><input name="student_name" type="text" id="student_name" value="<?php echo $student_name; ?>" /> 
              OR 
                <select name="student_id" id="student_id" onchange="$('#student_name').val($('#student_id option:selected').text())">
	                <option value="FALSE"><?php echo $text_select; ?></option>
	                <?php foreach ($students as $each_row) { ?>
	                <?php if ($each_row['student_id'] == $student_id) { ?>
	                <option value="<?php echo $each_row['student_id']; ?>" selected="selected"><?php echo $each_row['student_name']; ?></option>
	                <?php } else { ?>
	                <option value="<?php echo $each_row['student_id']; ?>"><?php echo $each_row['student_name']; ?></option>
	                <?php } ?>
	                <?php } ?>
                </select>
                <?php if ($error_student_name) { ?>
                <span class="error"><?php echo $error_student_name; ?></span>
                <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_student_email; ?></td>
            <td><input name="student_email" id="student_email" value="<?php echo $student_email; ?>" size="50" />
              <?php if (isset($error_student_email)) { ?>
              <span class="error"><?php echo $error_student_email; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_topic; ?></td>
            <td><input name="topic" id="topic" value="<?php echo $topic; ?>" size="50" />
              <?php if (isset($error_topic)) { ?>
              <span class="error"><?php echo $error_topic; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_description; ?></td>
            <td><textarea name="description" cols="60" rows="4" id="description"><?php echo $description; ?></textarea>
              <?php if (isset($error_description)) { ?>
              <span class="error"><?php echo $error_description; ?></span>
              <?php } ?></td>
        </tr>
          <tr>
            <td valign="top"><?php echo $entry_format; ?></td>
            <td><select name="format" id="format">
                <?php if(!empty($format)) { ?>
                <option value="<?=$format?>" selected="selected">
                <?=$format?>
                </option>
                <option value="">Select One</option>
                <?php } else { ?>
                <option value="">Select One</option>
                <?php } ?>
                <option value="Online Submission">Online Submission</option>
                <option value=".docx">.docx</option>
                <option value=".doc">.doc</option>
                <option value=".txt">.txt</option>
                <option value=".xlsx">.xslx</option>
                <option value=".xls">.xls</option>
                <option value=".pdf">.pdf</option>            
              </select>
                <?php if ($error_format) { ?>
                <span class="error"><?php echo $error_format; ?></span>
                <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_total_price; ?></td>
            <td><input name="total_price" type="text" id="total_price" value="<?php echo $total_price; ?>" />
            <?php if ($error_total_price) { ?>
            <span class="error"><?php echo $error_total_price; ?></span>
            <?php } ?></td>
          </tr>
        <tr>
          <td><?php echo $entry_tutor_price; ?></td>
          <td><input name="tutor_price" type="text" id="tutor_price" value="<?php echo $tutor_price; ?>" />
          	<?php if ($error_tutor_price) { ?>
	        <span class="error"><?php echo $error_tutor_price; ?></span>
	        <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_date_assigned; ?></td>
          <td><input name="date_assigned" type="text" id="date_assigned" value="<?php echo $date_assigned; ?>" class="calender" />
              <?php if ($error_date_assigned) { ?>
              <span class="error"><?php echo $error_date_assigned; ?></span>
              <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_date_completed; ?></td>
          <td><input name="date_completed" type="text" id="date_completed" value="<?php echo $date_completed; ?>" class="calender" />
              <?php if ($error_date_completed) { ?>
              <span class="error"><?php echo $error_date_completed; ?></span>
              <?php } ?></td>
        </tr>
		<tr>
          <td><?php echo $entry_due_date; ?></td>
		  <td><input name="due_date" type="text" id="due_date" value="<?php echo $due_date; ?>" class="calender" />
		  	<?php if ($error_due_date) { ?>
	        <span class="error"><?php echo $error_due_date; ?></span>
	        <?php } ?></td>
		  </tr>
          <tr>
            <td valign="top"><?php echo $entry_status; ?></td>
            <td><select name="status" id="status">
            		<option value="FALSE"><?php echo $text_select; ?></option>
	                <?php foreach ($all_status as $each_row) { ?>
	                <?php if ($each_row['status_id'] == $status) { ?>
	                <option value="<?php echo $each_row['status_id']; ?>" selected="selected"><?php echo $each_row['status_name']; ?></option>
	                <?php } else { ?>
	                <option value="<?php echo $each_row['status_id']; ?>"><?php echo $each_row['status_name']; ?></option>
	                <?php } ?>
	                <?php } ?>
              </select>
                <?php if ($error_status) { ?>
                <span class="error"><?php echo $error_status; ?></span>
                <?php } ?>            </td>
          </tr>		  
      </table>
    </form>
  </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.calender').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<?php echo $footer; ?>