<!-- File Modified by Softronikx Technologies -->
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
	
	</div>
      <table class="list">
        <thead>
          <tr>
		  
			 <td class="right">              
			  <?php echo $column_user_id; ?>
             </td> 
			  <td class="right">
			   <?php echo $column_name; ?>
			  </td> 
			  
			  <td class="right">
			   <?php echo $column_email; ?>
			  </td> 
			  
			  <td class="right">
			   <?php echo $column_students_tutored; ?>
			  </td> 
			  
			  <td class="right">
			   <?php echo $column_total_hours; ?>
			  </td> 
			  
			  <td class="right">
			   <?php echo $column_total_avg_hours; ?>
			  </td> 
			  
			  <td class="right">
			   <?php echo $column_total_avg_duration; ?>
			  </td> 		  
			  
          </tr>
        </thead>
        <tbody>
          <?php if ($results) { ?>
          <?php foreach ($results as $result) { ?>
          <tr>
            <td class="right"><?php echo $result['Id']; ?></td>
			<td class="right"><?php echo $result['Tutor Name']; ?></td>
			<td class="right"><?php echo $result['Email']; ?></td>
			<td class="right"><?php echo $result['Students Tutored']; ?></td>
            <td class="right"><?php echo $result['Hours Tutored']; ?></td>
			<td class="right"><?php echo $result['Avg Hours Per Student']; ?></td>
            <td class="center"><?php echo $result['Average Duration Per Student']; ?></td>
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
function exportdata() {
	var url = '<?php echo $action; ?>&type=export';
	location = url;
}
//--></script>
<?php echo $footer; ?>