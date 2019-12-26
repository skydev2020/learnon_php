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
          <td><?php echo $entry_subjects; ?></td>
          <td><div class="scrollbox">
              <?php $class = 'odd'; ?>
              <?php foreach ($all_subjects as $subjects_id => $subjects_name) { ?>
              <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
              <div class="<?php echo $class; ?>">
                <?php if (in_array($subjects_id, $all_subjects_ids)) { ?>
                <input type="checkbox" name="subjects[]" value="<?php echo $subjects_id; ?>" checked="checked" />
                <?php echo $subjects_name; ?>
                <?php } else { ?>
                <input type="checkbox" name="subjects[]" value="<?php echo $subjects_id; ?>" />
                <?php echo $subjects_name; ?>
                <?php } ?>
              </div>
              <?php } ?>
            </div></td>
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
		<!--<tr>
          <td valign="top"><span class="required">*</span> <?php echo $entry_status; ?></td>
		  <td><input name="status" type="radio" value="1" <?php if($status == "1") echo "checked";?> />
		    Enable
		    <input name="status" type="radio" value="0" <?php if($status == "0") echo "checked";?> />
		    Disable
		    </td>
		  </tr> -->
		  <input name="status" type="hidden" value="1" />
      </table>
    </form>
  </div>
</div>
<?php echo $footer; ?>