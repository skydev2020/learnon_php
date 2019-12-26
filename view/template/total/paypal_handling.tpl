<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/total.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><?php echo $entry_fee; ?></td>
          <td><input type="text" name="paypal_handling_fee" value="<?php echo $paypal_handling_fee; ?>" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_fee_type; ?></td>
          <td><select name="fee_type">
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
          <td><?php echo $entry_status; ?></td>
          <td><select name="paypal_handling_status">
              <?php if ($paypal_handling_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input type="text" name="paypal_handling_sort_order" value="<?php echo $paypal_handling_sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php echo $footer; ?>