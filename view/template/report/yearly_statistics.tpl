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
	<form action="<?=$action?>" method="get" id="form">Filter by year 
	<select name="year" onChange="filter();">
	<option value="">All Years</option>
	<?php for($i=0; $i<30; $i++){ ?>
	<option value="<?=date('Y')-$i?>" <?php if($year == (date('Y')-$i))echo "selected";?>><?=date('Y')-$i?></option>
	<?php } ?>
	</select>
	</form>
	</div>
      <table class="list">
        <thead>
          <tr>
            <td class="right"><?php if ($sort == 'total_revenue') { ?>
              <a href="<?php echo $sort_total_revenue; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total_revenue; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_total_revenue; ?>"><?php echo $column_total_revenue; ?></a>
              <?php } ?></td>                                
            <td class="right"><?php if ($sort == 'hours_tutors') { ?>
              <a href="<?php echo $sort_hours_tutors; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_hours_tutors; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_hours_tutors; ?>"><?php echo $column_hours_tutors; ?></a>
              <?php } ?></td>
			<td class="right"><?php if ($sort == 'total_expenses') { ?>
              <a href="<?php echo $sort_total_expenses; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total_expenses; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_total_expenses; ?>"><?php echo $column_total_expenses; ?></a>
              <?php } ?></td>             
            <td class="right"><?php if ($sort == 'total_profit') { ?>
              <a href="<?php echo $sort_total_profit; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total_profit; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_total_profit; ?>"><?php echo $column_total_profit; ?></a>
              <?php } ?></td>
            <td class="right"><?php echo $column_no_of_students; ?></td>
            <td class="right"><?php echo $column_no_of_tutors; ?></td>
            <td class="center"><?php if ($sort == 'pay_month') { ?>
              <a href="<?php echo $sort_pay_month; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_pay_year; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_pay_month; ?>"><?php echo $column_pay_year; ?></a>
              <?php } ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($payments) { ?>
          <?php foreach ($payments as $payment) { ?>
          <tr>
            <td class="right"><?=$curr_symbol?><?php echo $payment['total_revenue']; ?></td>
			<td class="right"><?php echo $payment['hours_tutors']; ?></td>
			<td class="right"><?=$curr_symbol?><?php echo $payment['total_expenses']; ?></td>
			<td class="right"><?=$curr_symbol?><?php echo $payment['total_profit']; ?></td>
            <td class="right"><?php echo $payment['no_of_students']; ?></td>
			<td class="right"><?php echo $payment['no_of_tutors']; ?></td>
            <td class="center"><?php echo $payment['pay_month']; ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="6"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    <div class="pagination"><?php echo $pagination; ?></div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	var url = '<?php echo $action; ?>';
	
	var filter_year = $('select[name=\'year\']').attr('value');
	if (filter_year) {
		url += '&year=' + encodeURIComponent(filter_year);
	}
	location = url;
}
function exportdata() {
	var url = '<?php echo $action; ?>&type=export';
	var filter_year = $('select[name=\'year\']').attr('value');
	if (filter_year) {
		url += '&year=' + encodeURIComponent(filter_year);
	}
	location = url;
}
//--></script>
<?php echo $footer; ?>