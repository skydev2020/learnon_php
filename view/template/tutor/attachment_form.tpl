<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background: url('view/image/information.png') 2px 9px no-repeat;"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a class="button submit_user_action"><span>Submit</span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div id="language">
        <table class="form">
          <tr>
            <td><?php echo $entry_student_name; ?></td>
            <td><?php echo $student_name; ?></td>
          </tr>
          <tr>
            <td>Attachment 1:</td>
            <td><input name="attachment_1" type="file" id="attachment_1" /></td>
          </tr>
          <tr>
            <td>Attachment 2:</td>
            <td><input name="attachment_2" type="file" id="attachment_2" /></td>
        </tr>
          <tr>
            <td>Attachment 3:</td>
            <td><input name="attachment_3" type="file" id="attachment_3" /></td>
        </tr>
        	  
      </table>
	  </div>
    </form>
  </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.calender').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>

<!-- User Alert -->
<script type="text/javascript" src="view/javascript/jquery/alertify.min.js"></script>
<link rel="stylesheet" type="text/css" href="view/stylesheet/alertify.core.css" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/alertify.default.css" />
<!--
<script src="http://softronikx.info/dev/tunes_and_ggs/design/common/js/alertify.min.js"></script>
<link rel="stylesheet" href="http://softronikx.info/dev/tunes_and_ggs/design/common/css/themes/alertify.core.css" />
<link rel="stylesheet" href="http://softronikx.info/dev/tunes_and_ggs/design/common/css/themes/alertify.default.css" id="toggleCSS" />-->
<script type="text/javascript" charset="utf-8">

	$(document).ready(function(){

		var default_message_for_dialog = 'Are you sure you want to upload the assignment? Please confirm the below points: <br><br><b> 1. Have you sourced all your info, quotes and data?<br>2. Have you included your bibliography and/or reference sheet?<br>3. Make sure there is NO PLAGIARISM, and NO copy/paste from the internet. </b><br><br> By submitting I agree this is my original work and that I have followed all instructions.';

		//clicking delete button - show dialog
		$('.submit_user_action').live('click', function(link) {

			link.preventDefault();
			var theREL = $(this).attr("rel");
			var theMESSAGE = (theREL == undefined || theREL == '') ? default_message_for_dialog : theREL;
			var theICON = '<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 0 0;"></span>';

			//set windows content (new) // confirm dialog
			alertify.set({ buttonReverse: true });
			alertify.set({ buttonFocus: "none"});    
			alertify.confirm(theICON + theMESSAGE, function (e) {
				if (e) {					
					$('#form').submit();					
				} else {
					// user clicked "cancel"
				}
			});
			
		});
		
	});

</script>
<!-- End of User Alert -->

<?php echo $footer; ?>