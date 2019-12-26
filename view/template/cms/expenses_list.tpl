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
    <h1 style="background-image: url('view/image/user.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="location = '<?php echo $insert; ?>'" class="button"><span><?php echo $button_insert; ?></span></a><a onclick="location = '<?php echo $insert_all; ?>'" class="button"><span><?php echo $button_insert_all; ?></span></a><a onclick="$('form').attr('action', '<?php echo $delete; ?>'); $('form').submit();" class="button"><span><?php echo $button_delete; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?=$action?>" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
        <thead>
		<tr>
			<td colspan="4" align="center">Start Date <input type="text" name="filter_start_date" value="<?php echo $filter_start_date; ?>" id="start_date" /> End Date <input type="text" name="filter_end_date" value="<?php echo $filter_end_date; ?>" id="end_date" /> <a onclick="filter();" class="button"><span><?php echo $button_filter; ?></span></a></td>
			<td align="center"><a onclick="exportdata();" class="button"><span>Export</span></a>
			</td>
		  </tr>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="left"><?php if ($sort == 'name') { ?>
              <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
              <?php } ?></td>  
            <td class="left"><?php if ($sort == 'amount') { ?>
              <a href="<?php echo $sort_amount; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_amount; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_amount; ?>"><?php echo $column_amount; ?></a>
              <?php } ?></td>                        
            <td class="left"><?php if ($sort == 'date') { ?>
              <a href="<?php echo $sort_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_date; ?>"><?php echo $column_date; ?></a>
              <?php } ?></td>
            <td class="center"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr class="filter">
            <td></td>
			<td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
            <td><input type="text" name="filter_amount" value="<?php echo $filter_amount; ?>" /></td>           
            <td><input type="text" name="filter_date" value="<?php echo $filter_date; ?>" size="12" id="date" /></td>
            <td align="center"><a onclick="filter();" class="button"><span><?php echo $button_filter; ?></span></a></td>
          </tr>
          <?php if ($expenses) { ?>
          <?php foreach ($expenses as $expense) { ?>
          <tr>
            <td style="text-align: center;"><input type="checkbox" name="selected[]" value="<?php echo $expense['proexpen_id']; ?>" /></td>
            <td class="left"><?php echo $expense['name']; ?></td>
            <td class="left"><?php echo $expense['amount']; ?></td>
            <td class="left"><?php echo $expense['date']; ?></td>
            <td class="center"><?php foreach ($expense['action'] as $action) { ?>
              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
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
	url = 'index.php?route=cms/expenses&token=<?php echo $token; ?>';
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	var filter_amount = $('input[name=\'filter_amount\']').attr('value');
	
	if (filter_amount) {
		url += '&filter_amount=' + encodeURIComponent(filter_amount);
	}
	var filter_date = $('input[name=\'filter_date\']').attr('value');
	
	if (filter_date) {
		url += '&filter_date=' + encodeURIComponent(filter_date);
	}
	var filter_start_date = $('input[name=\'filter_start_date\']').attr('value');
	
	if (filter_start_date) {
		url += '&filter_start_date=' + encodeURIComponent(filter_start_date);
	}
	var filter_end_date = $('input[name=\'filter_end_date\']').attr('value');
	
	if (filter_end_date) {
		url += '&filter_end_date=' + encodeURIComponent(filter_end_date);
	}
	
	location = url;
}

function exportdata() {
	url = 'index.php?route=cms/expenses/export&token=<?php echo $token; ?>';
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	var filter_amount = $('input[name=\'filter_amount\']').attr('value');
	
	if (filter_amount) {
		url += '&filter_amount=' + encodeURIComponent(filter_amount);
	}
	var filter_date = $('input[name=\'filter_date\']').attr('value');
	
	if (filter_date) {
		url += '&filter_date=' + encodeURIComponent(filter_date);
	}
	var filter_start_date = $('input[name=\'filter_start_date\']').attr('value');
	
	if (filter_start_date) {
		url += '&filter_start_date=' + encodeURIComponent(filter_start_date);
	}
	var filter_end_date = $('input[name=\'filter_end_date\']').attr('value');
	
	if (filter_end_date) {
		url += '&filter_end_date=' + encodeURIComponent(filter_end_date);
	}
	
	location = url;
}
//--></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date').datepicker({dateFormat: 'yy-mm-'});
	$('#start_date').datepicker({dateFormat: 'yy-mm-dd'});
	$('#end_date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<?php echo $footer; ?>