<?php echo $header; ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/user.png');"><?php echo $heading_title; ?></h1>
	<div class="buttons"><a onclick="location = '<?php echo str_replace('&', '&amp;', $back); ?>'" class="button"><span><?php echo $button_back; ?></span></a></div>    
  </div>
  <div class="content">
    <table class="form">
      <tr>
        <td align="right"><?php echo $column_tutor_name; ?>:</td>
        <td><?php echo $tutor_info['student_name']; ?></td>
      </tr>
      <tr>
        <td align="right"><?php echo $entry_email; ?></td>
        <td><?php echo $tutor_info['email']; ?></td>
      </tr>
      <tr>
        <td align="right"><?php echo $entry_subjects; ?></td>
        <td><?php $p=0;foreach($arrsubids as $subject){if($p)echo ", ";echo stripslashes($subject['subjects_name']);$p++;} ?></td>
      </tr>
      <tr>
        <td align="right"><?php echo $entry_telephone; ?></td>
        <td><?php echo $tutor_info['home_phone']; ?></td>
      </tr>
      <tr>
        <td align="right"><?php echo $entry_cellphone; ?></td>
        <td><?php echo $tutor_info['cell_phone']; ?></td>
      </tr>
      <tr>
        <td align="right" valign="top"><?php echo $text_address; ?>:</td>
        <td><?php echo $tutor_info['faddress']; ?></td>
      </tr>
      <tr>
        <td align="right" valign="top"><?php echo $column_date_added; ?>:</td>
        <td><?php echo $date_added; ?></td>
      </tr>
      <tr>
        <td align="right" valign="top"><?php echo $column_status_by_student; ?>:</td>
        <td><?php echo $tutor_info['status_by_student']; ?></td>
      </tr>
    </table>
  </div>
</div>
<?php echo $footer; ?>