<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/user.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_edit; ?></span></a>
	<a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a>
	</div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $text_wage_usa; ?></td>
          <td><input type="text" name="wage_usa" id="wage_usa" value="<?php echo $wage_usa; ?>" />	
            <?php if ($error_wage_usa) { ?>
            <span class="error"><?php echo $error_wage_usa; ?></span>
            <?php } ?></td>
        </tr>
       <tr>
          <td><span class="required">*</span> <?php echo $text_wage_canada; ?></td>
          <td><input type="text" name="wage_canada" id="wage_canada" value="<?php echo $wage_canada; ?>" />	
            <?php if ($error_wage_canada) { ?>
            <span class="error"><?php echo $error_wage_canada; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $text_wage_alberta; ?></td>
          <td><input type="text" name="wage_alberta" id="wage_alberta" value="<?php echo $wage_alberta; ?>" />	
            <?php if ($error_wage_alberta) { ?>
            <span class="error"><?php echo $error_wage_alberta; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $text_invoice_usa; ?></td>
          <td><input type="text" name="invoice_usa" id="invoice_usa" value="<?php echo $invoice_usa; ?>" />	
            <?php if ($error_invoice_usa) { ?>
            <span class="error"><?php echo $error_invoice_usa; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $text_invoice_canada; ?></td>
          <td><input type="text" name="invoice_canada" id="invoice_canada" value="<?php echo $invoice_canada; ?>" />	
            <?php if ($error_invoice_canada) { ?>
            <span class="error"><?php echo $error_invoice_canada; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $text_invoice_alberta; ?></td>
          <td><input type="text" name="invoice_alberta" id="invoice_alberta" value="<?php echo $invoice_alberta; ?>" />	
            <?php if ($error_invoice_alberta) { ?>
            <span class="error"><?php echo $error_invoice_alberta; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $text_upload_new_rates; ?></td>
          <td><input type="file" name="income_file" id="income_file" /> </td>
		  <td><?php if ($income_file) { ?>
            <span class="error"><?php echo $error_income_file; ?></span>
          <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $text_download_old_rates; ?></td>
          <td><div class="buttons"><a onclick="exportdata()" class="button"><span><?php echo $button_export; ?></span></a>
	</div></td>
		  <td>&nbsp;</td>
        </tr>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
function exportdata() {
	url = 'index.php?route=cms/expenses/exportBaseRates&token=<?php echo $token; ?>';
	
	location = url;
}
//--></script>
<?php echo $footer; ?>