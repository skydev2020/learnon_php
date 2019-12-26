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
            <td><span class="required">*</span> <?php echo $entry_name; ?></td>
            <td><input name="name" id="name" value="<?php echo $name; ?>" size="50" />
              <?php if (isset($error_name)) { ?>
              <span class="error"><?php echo $error_name; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_hours; ?></td>
            <td><input name="hours" type="text" id="hours" value="<?php echo $hours; ?>" /></td>
          </tr>
          <tr>
            <td valign="top"><span class="required">*</span> <?php echo $entry_prepaid; ?></td>
            <td><input name="prepaid" type="radio" value="1" <?php if($prepaid == "1") echo "checked";?> />
              Yes
              <input name="prepaid" type="radio" value="0" <?php if($prepaid == "0") echo "checked";?> />
              No
              <?php if ($error_prepaid) { ?>
              <span class="error"><?php echo $error_prepaid; ?></span>
              <?php } ?>            </td>
          </tr>
		  <tr>
            <td><?php echo $entry_student_id; ?></td>
            <td><select name="student_id" id="student_id" onchange="$('#student_id').val($('#student_id option:selected').text())">
	                <option value="FALSE"><?php echo $text_select; ?></option>
	                <?php foreach ($students as $each_row) { ?>
	                <?php if ($each_row['student_id'] == $student_id) { ?>
	                <option value="<?php echo $each_row['student_id']; ?>" selected="selected"><?php echo $each_row['student_name']; ?></option>
	                <?php } else { ?>
	                <option value="<?php echo $each_row['student_id']; ?>"><?php echo $each_row['student_name']; ?></option>
	                <?php } ?>
	                <?php } ?>
                </select>
                <?php if ($error_student_id) { ?>
                <span class="error"><?php echo $error_student_id; ?></span>
                <?php } ?></td>
          </tr>          
        <tr>
          <td><?php echo $entry_grades; ?></td>
          <td><div class="scrollbox">
              <?php $class = 'odd'; ?>
              <?php foreach ($all_grades as $grade_id => $grade_name) { ?>
              <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
              <div class="<?php echo $class; ?>">
                <?php if (in_array($grade_id, $all_grade_ids)) { ?>
                <input type="checkbox" name="grades[]" value="<?php echo $grade_id; ?>" checked="checked" />
                <?php echo $grade_name; ?>
                <?php } else { ?>
                <input type="checkbox" name="grades[]" value="<?php echo $grade_id; ?>" />
                <?php echo $grade_name; ?>
                <?php } ?>
              </div>
              <?php } ?>
            </div></td>
          </tr>		  
          <tr>
            <td><span class="required">*</span> <?php echo $entry_description; ?></td>
            <td><textarea name="description" id="description"><?php echo $description; ?></textarea>
              <?php if (isset($error_description)) { ?>
              <span class="error"><?php echo $error_description; ?></span>
              <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_price_usa; ?></td>
          <td><input name="price_usa" type="text" id="price_usa" value="<?php echo $price_usa; ?>" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_price_alb; ?></td>
          <td><input name="price_alb" type="text" id="price_alb" value="<?php echo $price_alb; ?>" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_price_can; ?></td>
          <td><input name="price_can" type="text" id="price_can" value="<?php echo $price_can; ?>" /></td>
        </tr>
		<tr>
          <td valign="top"><span class="required">*</span> <?php echo $entry_status; ?></td>
		  <td><input name="status" type="radio" value="1" <?php if($status == "1") echo "checked";?> />
		    Enable
		    <input name="status" type="radio" value="0" <?php if($status == "0") echo "checked";?> />
		    Disable
		    </td>
		  </tr>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
CKEDITOR.replace('description', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
//--></script>
<?php echo $footer; ?>