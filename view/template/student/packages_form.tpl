<?php echo $header; ?> 
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background: url('view/image/information.png') 2px 9px no-repeat;"><?php echo $heading_title; ?></h1>
    <div class="buttons"><?php if(! $confirm_order) { ?><a onclick="$('#form').submit();" class="button"><span><?php echo $button_continue; ?></span></a><?php } ?><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
   
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div id="language">
        <table class="form">
          <tr>
            <td><?php echo $entry_name; ?></td>
            <td><?php echo $name; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_description; ?></td>
            <td><?php echo $description; ?></td>
        </tr>
        <tr>
            <td><?php echo $entry_price; ?></td>
            <td><?php echo $price; ?></td>
        </tr>        
        <?php if($confirm_order) {?>
        <tr>
          <td>&nbsp;</td>
          <td><table style="float: right; display: inline-block;">
          <?php foreach ($totals as $total) { ?>
          <tr>
            <td align="right"><?php echo $total['title']; ?></td>
            <td align="right"><?php echo $currency_symbol.$total['value']; ?></td>
          </tr>
          <?php } ?>
        </table></td>
        </tr>
        <?php } ?>
        <?php if(!$confirm_order) {?>
		<tr>
		<td><?php echo $text_payment_method; ?></td>
		<td>
	      <?php if ($payment_methods) { ?>
	        <table width="100%" cellpadding="3">
	          <?php foreach ($payment_methods as $payment_method) { ?>
	          <tr>
	            <td width="1">
	              <?php if ($payment_method['id'] == $payment || !$payment) { ?>
				  <?php $payment = $payment_method['id']; ?>
	              <input type="radio" name="payment_method" value="<?php echo $payment_method['id']; ?>" id="<?php echo $payment_method['id']; ?>" checked="checked" style="margin: 0px;" />
	              <?php } else { ?>
	              <input type="radio" name="payment_method" value="<?php echo $payment_method['id']; ?>" id="<?php echo $payment_method['id']; ?>" style="margin: 0px;" />
	              <?php } ?></td>
	            <td><label for="<?php echo $payment_method['id']; ?>" style="cursor: pointer;"><?php echo $payment_method['title']; ?></label></td>
	          </tr>
	          <?php } ?>
	        </table>
	      <?php } ?>
		</td>
		</tr>
        <tr>
          <td><?php echo $entry_coupon; ?></td>
          <td>
          	<input type="text" name="coupon" value="<?php echo $coupon; ?>" />
		  </td>
        </tr>
		<?php } ?>
		<?php if($confirm_order) {?>		
        <tr>
          <td colspan="2"><div id="payment">
          <div class="buttons">
  <table>
    <tr>
      <td align="left"><a onclick="location='<?php echo $back; ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
      <td align="right"><a onclick="confirmSubmit();" class="button"><span><?php echo $button_confirm; ?> Confirm</span></a></td>
    </tr>
  </table>
</div>

<script type="text/javascript">
var click_once = 1;
<!--
function confirmSubmit() {
	$('#checkout').submit();
}
//--></script>
          <?php //echo $payment; ?></div></td>
        </tr>
        <?php } ?>
      </table>
    </form>
    <?php //echo 'a'; print_r($paypal); ?>
     <form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="checkout">
          	<input name="cmd" value="_cart" type="hidden">
      <input name="upload" value="1" type="hidden">
      <input name="item_number_1" value="<?php echo $paypal[products][0][model];?>" type="hidden">
      <input name="item_name_1" value="<?php echo  $paypal[products][0][name];?>" type="hidden">
      <input name="amount_1" value="<?php echo  $paypal[products][0][total];?>" type="hidden">
      <input name="discount_amount_cart" value="0.00" type="hidden">
      <input name="shipping_1" value="0.00" type="hidden">
      <input name="tax_cart" value="0.00" type="hidden">
      <input name="handling_cart" value="<?php echo  round($paypal[totals][1][value],2);?>" type="hidden">
      <input name="business" value="billing@learnon.ca" type="hidden">
      <input name="currency_code" value="CAD" type="hidden">
      <input name="email" value="<?php echo  $paypal[email];?>" type="hidden">
      <input name="invoice" value="<?php echo  $paypal[invoice_pk];?> - <?php echo  $paypal[firstname].' '. $paypal[lastname];?>" type="hidden">
      <input name="lc" value="en" type="hidden">
      <input name="rm" value="2" type="hidden">
      <input name="charset" value="utf-8" type="hidden">
      <input name="paymentaction" value="sale" type="hidden">
      <input name="return" value="http://www.learnon.ca/portal/index.php?route=payment_student/pp_standard/pdt" type="hidden">
      <input name="notify_url" value="http://www.learnon.ca/portal/index.php?route=payment_student/pp_standard/callback" type="hidden">
      <input name="cancel_return" value="http://www.learnon.ca/portal/index.php?route=student/invoice/cancel&token=<?php echo $token; ?>" type="hidden">
      <input name="custom" value="a2No" type="hidden">
  </form>
  </div>
</div>
<?php echo $footer; ?>