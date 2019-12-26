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
    <div class="buttons"><!-- <a onclick="location = '<?php echo $insert; ?>'" class="button"><span><?php echo $button_insert; ?></span></a>--><a onclick="$('form').submit();" class="button"><span><?php echo $button_delete; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
        <thead>
		<tr>
			<td colspan="3" align="center">Search mail to <input type="text" name="filter_mail_to" value="<?php echo $filter_mail_to; ?>" /> <a onclick="filter();" class="button"><span><?php echo $button_filter; ?></span></a></td>
		  	<td colspan="3" align="right" height="38">
  	<input type="checkbox" name="mail_to" value="yes" /> Mail To <input type="checkbox" name="subject" value="yes" /> Subject <input type="checkbox" name="message" value="yes" /> Message <input type="checkbox" name="date_send" value="yes" /> Date Send <a onclick="exportdata();" class="button"><span>Export</span></a>
			</td>
		  </tr>
          <tr>
             <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="left"><?php if ($sort == 'mail_to') { ?>
              <a href="<?php echo $sort_mail_to; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_mail_to; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_mail_to; ?>"><?php echo $column_mail_to; ?></a>
              <?php } ?></td>
            <td class="lect"><?php if ($sort == 'mail_from') { ?>
              <a href="<?php echo $sort_mail_from; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_mail_from; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_mail_from; ?>"><?php echo $column_mail_from; ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'subject') { ?>
              <a href="<?php echo $sort_subject; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_subject; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_subject; ?>"><?php echo $column_subject; ?></a>
              <?php } ?></td>
            <td class="center"><?php if ($sort == 'date_send') { ?>
              <a href="<?php echo $sort_date_send; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_send; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_date_send; ?>"><?php echo $column_date_send; ?></a>
              <?php } ?></td>
            <td class="center"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($informations) { ?>
          <?php foreach ($informations as $information) { ?>
          <tr>
             <td style="text-align: center;"><?php if ($information['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $information['information_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $information['information_id']; ?>" />
              <?php } ?></td> 
            <td class="left"><?php echo $information['mail_to']; ?></td>
            <td class="left"><?php echo $information['mail_from']; ?></td>
            <td class="left"><?php echo $information['subject']; ?></td>
            <td class="center"><?php echo $information['date_send']; ?></td>
            <td class="center"><?php foreach ($information['action'] as $action) { ?>
              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
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
	url = 'index.php?route=cms/logmail&token=<?php echo $token; ?>';
	
	var filter_mail_to = $('input[name=\'filter_mail_to\']').attr('value');
	if (filter_mail_to) {
		url += '&filter_mail_to=' + encodeURIComponent(filter_mail_to);
	}
	location = url;
}
function exportdata() {
	url = 'index.php?route=cms/logmail/export&token=<?php echo $token; ?>';
	
	var filter_mail_to = $('input[name=\'filter_mail_to\']').attr('value');
	if (filter_mail_to) {
		url += '&filter_mail_to=' + encodeURIComponent(filter_mail_to);
	}
	
	var mail_to = $('input[name=\'mail_to\']').attr('value');
	if ($('input[name=\'mail_to\']').attr('checked')) {
		url += '&mail_to=' + encodeURIComponent(mail_to);
	}
	
	var subject = $('input[name=\'subject\']').attr('value');
	if ($('input[name=\'subject\']').attr('checked')) {
		url += '&subject=' + encodeURIComponent(subject);
	}
	
	var message = $('input[name=\'message\']').attr('value');
	if ($('input[name=\'message\']').attr('checked')) {
		url += '&message=' + encodeURIComponent(message);
	}
	var date_send = $('input[name=\'date_send\']').attr('value');
	if ($('input[name=\'date_send\']').attr('checked')) {
		url += '&date_send=' + encodeURIComponent(date_send);
	}

	location = url;
}
//--></script>
<?php echo $footer; ?>