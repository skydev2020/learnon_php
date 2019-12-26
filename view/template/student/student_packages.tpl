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
  </div>
  <div class="content">
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
        <thead>
          <tr>
            <td class="left"><?php if ($sort == 'name') { ?>
              <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_student_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_name; ?>"><?php echo $column_student_name; ?></a>
              <?php } ?></td>              
            <td class="right"><?php if ($sort == 'package_name') { ?>
              <a href="<?php echo $sort_package_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_package_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_package_name; ?>"><?php echo $column_package_name; ?></a>
              <?php } ?></td>
			<td class="right"><?php if ($sort == 'total_hours') { ?>
              <a href="<?php echo $sort_total_hours; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total_hours; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_total_hours; ?>"><?php echo $column_total_hours; ?></a>
              <?php } ?></td>    
			<td class="right"><?php if ($sort == 'left_hours') { ?>
              <a href="<?php echo $sort_left_hours; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_left_hours; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_left_hours; ?>"><?php echo $column_left_hours; ?></a>
              <?php } ?></td>  
			<td class="right"><?php if ($sort == 'date_added') { ?>
              <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
              <?php } ?></td>  			  
            <td class="right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($packages) { ?>
          <?php foreach ($packages as $information) { ?>
          <tr>
		  
            <td class="left"><?php echo $information['student_name']; ?></td>
            <td class="right"><?php echo $information['package_name']; ?></td>
            <td class="right"><?php echo $information['total_hours']; ?></td>			
			<td class="left"><?php echo $information['left_hours']; ?></td>
            <td class="right"><?php echo $information['date_added']; ?></td>
            <td class="right"><?php foreach ($information['action'] as $action) { ?>
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
<?php echo $footer; ?>