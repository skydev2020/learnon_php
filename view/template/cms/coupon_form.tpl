<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
    <table class="form">
      <tr>
        <td><span class="required">*</span> <?php echo $entry_name; ?></td>
        <td><input name="name" value="<?php echo $name; ?>" />
          <?php if (isset($error_name)) { ?>
          <span class="error"><?php echo $error_name; ?></span>
          <?php } ?></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_description; ?></td>
        <td><textarea name="description" cols="40" rows="5"><?php echo $description; ?></textarea>
          <?php if (isset($error_description)) { ?>
          <span class="error"><?php echo $error_description; ?></span>
          <?php } ?></td>
      </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_code; ?></td>
          <td><input type="text" name="code" value="<?php echo $code; ?>" />
            <?php if ($error_code) { ?>
            <span class="error"><?php echo $error_code; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_type; ?></td>
          <td><select name="type">
              <?php if ($type == 'P') { ?>
              <option value="P" selected="selected"><?php echo $text_percent; ?></option>
              <?php } else { ?>
              <option value="P"><?php echo $text_percent; ?></option>
              <?php } ?>
              <?php if ($type == 'F') { ?>
              <option value="F" selected="selected"><?php echo $text_amount; ?></option>
              <?php } else { ?>
              <option value="F"><?php echo $text_amount; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_discount; ?></td>
          <td><input type="text" name="discount" value="<?php echo $discount; ?>" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_date_start; ?></td>
          <td><input type="text" name="date_start" value="<?php echo $date_start; ?>" size="12" id="date_start" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_date_end; ?></td>
          <td><input type="text" name="date_end" value="<?php echo $date_end; ?>" size="12" id="date_end" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_uses_total; ?></td>
          <td><input type="text" name="uses_total" value="<?php echo $uses_total; ?>" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_uses_customer; ?></td>
          <td><input type="text" name="uses_customer" value="<?php echo $uses_customer; ?>" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="status">
              <?php if ($status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date_start').datepicker({dateFormat: 'yy-mm-dd'});
	
	$('#date_end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<?php echo $footer; ?>