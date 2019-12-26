<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background: url('view/image/information.png') 2px 9px no-repeat;"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div id="language">
        <table class="form">
          <tr>
            <td><span class="required">*</span> Filter by month</td>
            <td><select name="month" >
	<option value="">All Months</option>
	<?php for($i=1; $i<=12; $i++){ ?>
	<option value="<?=str_pad($i, 2, '0', STR_PAD_LEFT)?>" <?php if($month == str_pad($i, 2, '0', STR_PAD_LEFT))echo "selected";?>><?=date("F", strtotime('2011-'.str_pad($i, 2, '0', STR_PAD_LEFT).'-01'))?></option>
	<?php } ?>
	</select>&nbsp;&nbsp;
	<select name="year">
	<?php for($i=0; $i<30; $i++){ ?>
	<option value="<?=date('Y')-$i?>" <?php if($year == (date('Y')-$i))echo "selected";?>><?=date('Y')-$i?></option>
	<?php } ?>
	</select>
                <?php if ($error_payment_date) { ?>
                <span class="error"><?php echo $error_month_filter; ?></span>
                <?php } ?></td>
          </tr>
          
          <tr>
            <td></td>
            <td><input type="submit" name="export_masterlist" value="Export Envelopes">
            &nbsp;&nbsp; <input type="submit" name="export_paycheque" value="Export Cheques">
            </td>
          </tr>
          
      </table>
    </form>
  </div>
</div>
<?php echo $footer; ?>