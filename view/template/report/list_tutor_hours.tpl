<?php echo $header; ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/user.png');"><?php echo $heading_title; ?></h1>
	<div style="float:right;padding-top:8px; text-align:right;"><a onclick="exportdata();" class="button"><span>Export</span></a></div>
  </div>
  <div class="content">
    <div style="float:right; margin:0px 0px 10px 10px;">
	<form action="<?=$action?>" method="get" id="form">Filter by month 
	<select name="month" onChange="filter();">
	<?php for($i=1; $i<=12; $i++){ ?>
	<option value="<?=str_pad($i, 2, '0', STR_PAD_LEFT)?>" <?php if($month == str_pad($i, 2, '0', STR_PAD_LEFT))echo "selected";?>><?=date("F", strtotime('2011-'.str_pad($i, 2, '0', STR_PAD_LEFT).'-01'))?></option>
	<?php } ?>
	</select>&nbsp;&nbsp;
	<select name="year" onChange="filter();">
	<?php for($i=0; $i<30; $i++){ ?>
	<option value="<?=date('Y')-$i?>" <?php if($year == (date('Y')-$i))echo "selected";?>><?=date('Y')-$i?></option>
	<?php } ?>
	</select>
	</form>
	</div>
      <table class="list">
        <thead>
          <tr>
            <td class="right"><?php if ($sort == 'total_hours') { ?>
              <a href="<?php echo $sort_total_hours; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total_hours; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_total_hours; ?>"><?php echo $column_total_hours; ?></a>
              <?php } ?></td>                                
            <td class="right"><?php if ($sort == 'total_pay') { ?>
              <a href="<?php echo $sort_total_pay; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total_pay; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_total_pay; ?>"><?php echo $column_total_pay; ?></a>
              <?php } ?></td>
            <td class="center"><?php if ($sort == 'pay_month') { ?>
              <a href="<?php echo $sort_pay_month; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_pay_month; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_pay_month; ?>"><?php echo $column_pay_month; ?></a>
              <?php } ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($payments) { ?>
          <?php foreach ($payments as $payment) { ?>
          <tr>
            <td class="right"><?php echo $payment['total_hours']; ?></td>
			<td class="right"><?php echo $payment['total_pay']; ?></td>
            <td class="center"><?php echo $payment['pay_month']; ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="3"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    <div class="pagination"><?php echo $pagination; ?></div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = '<?php echo $action; ?>';
	
	var filter_month = $('select[name=\'month\']').attr('value');
	if (filter_month) {
		url += '&month=' + encodeURIComponent(filter_month);
	}
	
	var filter_year = $('select[name=\'year\']').attr('value');
	if (filter_year) {
		url += '&year=' + encodeURIComponent(filter_year);
	}
	location = url;
}
function exportdata() {
	var url = '<?php echo $action; ?>&type=export';
	var filter_month = $('select[name=\'month\']').attr('value');
	if (filter_month) {
		url += '&month=' + encodeURIComponent(filter_month);
	}
	
	var filter_year = $('select[name=\'year\']').attr('value');
	if (filter_year) {
		url += '&year=' + encodeURIComponent(filter_year);
	}
	location = url;
}
//--></script>
<?php echo $footer; ?>