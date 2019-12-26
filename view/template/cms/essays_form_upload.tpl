<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/user.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_upload; ?></span></a>
	<a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a>
	</div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $text_select_file; ?></td>
          <td><input type="file" name="upload_csv_file" id="upload_csv_file" /> </td>
		  <td><?php if ($error_upload_csv_file) { ?>
            <span class="error"><?php echo $error_upload_csv_file; ?></span>
          <?php } ?></td>
        </tr>
       
      </table>
    </form>
  </div>
</div>
<?php echo $footer; ?>