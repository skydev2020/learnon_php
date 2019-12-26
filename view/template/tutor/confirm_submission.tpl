<?php echo $header; ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/user.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_confirm; ?></span></a> <a onclick="location = '<?php echo $cancel; ?>'" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?=$action?>" method="post" id="form">
      <table class="list">
        <thead>
          <tr>
            <td class="left"><?php if ($sort == 'student_name') { ?>
              <a href="<?php echo $sort_student_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_student_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_student_name; ?>"><?php echo $column_student_name; ?></a>
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
            <td class="left"><?php if ($sort == 'session_notes') { ?>
              <a href="<?php echo $sort_session_notes; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_session_notes; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_session_notes; ?>"><?php echo $column_session_notes; ?></a>
              <?php } ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($sessions) { ?>
          <?php foreach ($sessions as $session) { ?>
          <tr>
            <td class="left"><?php echo $session['student_name']; ?><input type="hidden" name="selected[]" value="<?php echo $session['session_id']; ?>" /></td>
            <td class="left"><?php echo $session['session_date']; ?></td>
            <td class="left"><?php echo $session['session_duration']; ?></td>
            <td class="left"><?php echo $session['session_notes']; ?></td>
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
  </div>
</div>
<?php echo $footer; ?>