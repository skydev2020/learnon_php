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
    <h1 style="background-image: url('view/image/information.png');"><?php echo $heading_title; ?></h1>
  </div>
  <div class="content">
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="list" align="left">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="left"><?php if ($sort == 'invoice_num') { ?>
              <a href="<?php echo $sort_invoice_num; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_invoice_num; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_invoice_num; ?>"><?php echo $column_invoice_num; ?></a>
              <?php } ?></td>              
            <td class="left"><?php if ($sort == 'invoice_date') { ?>
              <a href="<?php echo $sort_invoice_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_invoice_date; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_invoice_date; ?>"><?php echo $column_invoice_date; ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'total_hours') { ?>
              <a href="<?php echo $sort_total_hours; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total_hours; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_total_hours; ?>"><?php echo $column_total_hours; ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'total_amount') { ?>
              <a href="<?php echo $sort_total_amount; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total_amount; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_total_amount; ?>"><?php echo $column_total_amount; ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'invoice_status') { ?>
              <a href="<?php echo $sort_invoice_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_invoice_status; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_invoice_status; ?>"><?php echo $column_invoice_status; ?></a>
              <?php } ?></td>
            <td class="right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <!-- <tr class="filter">
            <td></td>
            <td><input type="text" name="filter_invoice_num" value="<?php echo $filter_invoice_num; ?>" /></td>
            <td><input type="text" name="filter_invoice_date" value="<?php echo $filter_invoice_date; ?>" size="12" id="date" /></td>
            <td><input type="text" name="filter_total_hours" value="<?php echo $filter_total_hours; ?>" /></td>     
            <td><input type="text" name="filter_total_amount" value="<?php echo $filter_total_amount; ?>" /></td>
			<td><input type="text" name="filter_send_date" value="<?php echo $filter_send_date; ?>" size="12" id="date2" /></td>
            <td align="center"><a onclick="filter();" class="button"><span><?php echo $button_filter; ?></span></a></td>
          </tr> -->
          <?php if ($invoices) { ?>
          <?php foreach ($invoices as $invoice) { ?>
          <tr>
            <td style="text-align: center;"><?php if ($invoice['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $invoice['invoice_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $invoice['invoice_id']; ?>" />
              <?php } ?></td>
            <td class="left"><?php echo $invoice['invoice_num']; ?></td>
            <td class="left"><?php echo $invoice['invoice_date']; ?></td>
            <td class="left"><?php echo $invoice['total_hours']; ?></td>
            <td class="left"><?php echo $invoice['total_amount']; ?></td>
            <td class="left"><?php echo $invoice['invoice_status']; ?></td>
            <td class="right"><?php foreach ($invoice['action'] as $action) { ?>
              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="7"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </form>
    <div class="pagination"><?php echo $pagination; ?></div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	var url = 'index.php?route=student/invoice&token=<?php echo $token; ?>';
	
	var filter_invoice_num = $('input[name=\'filter_invoice_num\']').attr('value');
	if (filter_invoice_num) {
		url += '&filter_invoice_num=' + encodeURIComponent(filter_invoice_num);
	}
	var filter_invoice_date = $('input[name=\'filter_invoice_date\']').attr('value');
	if (filter_invoice_date) {
		url += '&filter_invoice_date=' + encodeURIComponent(filter_invoice_date);
	}
	var filter_total_hours = $('input[name=\'filter_total_hours\']').attr('value');
	if (filter_total_hours) {
		url += '&filter_total_hours=' + encodeURIComponent(filter_total_hours);
	}
	var filter_total_amount = $('input[name=\'filter_total_amount\']').attr('value');
	if (filter_total_amount) {
		url += '&filter_total_amount=' + encodeURIComponent(filter_total_amount);
	}
	var filter_send_date = $('input[name=\'filter_send_date\']').attr('value');
	if (filter_send_date) {
		url += '&filter_send_date=' + encodeURIComponent(filter_send_date);
	}
	location = url;
}
//--></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date').datepicker({dateFormat: 'yy-mm-dd'});
	$('#date2').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<?php echo $footer; ?>