<?php echo $header; ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/information.png');"><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="window.print();" class="button"><span><?php echo $button_print; ?></span></a></div>
  </div>
  <div class="content">
      <table class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $column_student_name; ?></td>              
            <td class="left"><?php echo $column_session_duration; ?></td> 
			<td class="left"><?php echo $column_session_date; ?></td> 
			<td class="left"><?php echo $column_session_amount; ?></td> 
          </tr>
        </thead>
        <tbody>
          <?php if ($all_sessions) { ?>
          <?php foreach ($all_sessions as $each_session) { ?>
          <tr>
            <td class="left"><?php echo $each_session['student_name']; ?></td>
            <td class="left"><?php echo $each_session['session_duration']; ?></td>
            <td class="left"><?php echo $each_session['session_date']; ?></td>
            <td class="left"><?php echo $each_session['session_amount']; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td class="right" colspan="3"><?php echo $text_session_total; ?></td>
            <td class="left"><?php $paycheque_info['session_amount']; ?></td>
          </tr>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td class="left" colspan="4"><h3><?php echo $text_essay_details; ?></h3></td> 
          </tr>
          <tr>
            <td class="left"><strong><?php echo $column_student_name; ?></strong></td>
			<td class="left"><strong><?php echo $column_essay_topic; ?></strong></td>                           
			<td class="left"><strong><?php echo $column_essay_date; ?></strong></td>  
			<td class="left"><strong><?php echo $column_essay_amount; ?></strong></td> 
          </tr>
          <?php if ($all_essays) { ?>
          <?php foreach ($all_essays as $each_essay) { ?>
          <tr>
            <td class="left"><?php echo $each_essay['student_name']; ?></td>
            <td class="left"><?php echo $each_essay['essay_topic']; ?></td>
            <td class="left"><?php echo $each_essay['essay_date']; ?></td>
            <td class="left"><?php echo $each_essay['essay_amount']; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td class="right" colspan="3"><?php echo $text_essay_total; ?></td>
            <td class="left"><?php echo $paycheque_info['essay_amount']; ?></td>
          </tr>      	  
      	  <?php } else { ?>
          <tr>
            <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td class="right" colspan="3"><?php echo $text_grand_total; ?></td>
            <td class="left"><?php echo $paycheque_info['total_paid']; ?></td>
          </tr>
       </tbody>
      </table>      
    </form>
  </div>
</div>
<?php echo $footer; ?>