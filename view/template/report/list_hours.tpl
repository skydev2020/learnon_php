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
            <td class="left"><?php if ($sort == 'tutor_name') { ?>
              <a href="<?php echo $sort_tutor_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_tutor_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_tutor_name; ?>"><?php echo $column_tutor_name; ?></a>
              <?php } ?></td>                                
            <td class="left"><?php if ($sort == 'session_date') { ?>
              <a href="<?php echo $sort_session_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_session_date; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_session_date; ?>"><?php echo $column_session_date; ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'session_duration') { ?>
              <a href="<?php echo $sort_session_duration; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_session_duration; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_session_duration; ?>"><?php echo $column_session_duration; ?></a>
              <?php } ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($sessions) { ?>
          <?php foreach ($sessions as $session) { ?>
          <tr>
			<td class="left"><?php echo $session['tutor_name']; ?></td>
            <td class="left"><?php echo $session['session_date']; ?></td>
            <td class="left"><?php switch($session['session_duration']){case "0.5":echo "30 Minutes";break;case "1.0":echo "1 Hour";break;case "1.5":echo "1 Hour + 30 Minutes";break;case "2.0":echo "2 Hours";break;case "2.5":echo "2 Hours + 30 Minutes";break;case "3.0":echo "3 Hours";break;case "3.5":echo "3 Hours + 30 Minutes";break;case "4.0":echo "4 Hours";break;case "4.5":echo "4 Hours + 30 Minutes";break;case "5.0":echo "5 Hours";break;}; ?></td>
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