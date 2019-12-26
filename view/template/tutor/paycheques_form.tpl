<?php echo $header; ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background: url('view/image/information.png') 2px 9px no-repeat;"><?php echo $heading_title; ?></h1>
  </div>
  <div class="content">
    <table class="form">
      <tr>
        <td align="right"><?php echo $entry_paycheque_num; ?></td>
        <td><?php echo $paycheque_info['paycheque_num']; ?></td>
      </tr>
      <tr>
        <td align="right"><?php echo $entry_paycheque_date; ?></td>
        <td><?php echo $paycheque_info['paycheque_date']; ?></td>
      </tr>
      <tr>
        <td align="right"><?php echo $entry_total_hours; ?></td>
        <td><?php echo $paycheque_info['total_hours']; ?></td>
      </tr>
      <tr>
        <td align="right"><?php echo $entry_total_amount; ?></td>
        <td><?php echo $paycheque_info['total_amount']; ?></td>
      </tr>
      <tr>
        <td align="right"><?php echo $entry_send_date; ?></td>
        <td><?php echo $paycheque_info['send_date']; ?></td>
      </tr>
      <tr>
        <td align="right"><?php echo $entry_num_of_sessions; ?></td>
        <td><?php echo $paycheque_info['num_of_sessions']; ?></td>
      </tr>
      <tr>
        <td align="right" valign="top"><?php echo $entry_paid_amount; ?></td>
        <td><?php echo $paycheque_info['paid_amount']; ?></td>
      </tr>
      <tr>
        <td align="right" valign="top"><?php echo $entry_balance_amount; ?></td>
        <td><?php echo $paycheque_info['balance_amount']; ?></td>
      </tr>
      <tr>
        <td align="right" valign="top"><?php echo $entry_paycheque_notes; ?></td>
        <td><?php echo $paycheque_info['paycheque_notes']; ?></td>
      </tr>
    </table>
  </div>
  <div class="buttons">
    <table>
      <tr>
        <td align="left"><a onclick="location = '<?php echo str_replace('&', '&amp;', $back); ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
      </tr>
    </table>
  </div>
</div>
</div>
<?php echo $footer; ?>