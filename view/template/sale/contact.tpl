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
      <h1 style="background-image: url('view/image/mail.png');"><?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_send; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table id="mail" border="0" class="form">
          <tr>
            <td><?php echo $entry_to; ?></td>
            <td><input type="hidden" name="store_id" value="0" /><select name="to">
                <?php if ($to == 'user_all') { ?>
                <option value="user_all" selected="selected"><?php echo $text_user_all; ?></option>
                <?php } else { ?>
                <option value="user_all"><?php echo $text_user_all; ?></option>
                <?php } ?>
                <?php if ($to == 'user_group') { ?>
                <option value="user_group" selected="selected"><?php echo $text_user_group; ?></option>
                <?php } else { ?>
                <option value="user_group"><?php echo $text_user_group; ?></option>
                <?php } ?>
                <?php if ($to == 'user') { ?>
                <option value="user" selected="selected"><?php echo $text_user; ?></option>
                <?php } else { ?>
                <option value="user"><?php echo $text_user; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tbody id="to-user-group" class="to">
            <tr>
              <td><?php echo $entry_user_group; ?></td>
              <td><select name="user_group_id">
                  <?php foreach ($user_groups as $user_group) { ?>
                  <?php if ($user_group['user_group_id'] == $user_group_id) { ?>
                  <option value="<?php echo $user_group['user_group_id']; ?>" selected="selected"><?php echo $user_group['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $user_group['user_group_id']; ?>"><?php echo $user_group['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select> <span id="show_active_tutors">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="active_tutors" value="Y" <?php if($active_tutors=="Y")echo 'checked="checked"';?> /> Send to Active Tutors Only</span></td>
            </tr>
          </tbody>
          <tbody id="to-user" class="to">
            <tr>
              <td><?php echo $entry_user; ?></td>
              <td><input type="text" name="users" value="" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><div class="scrollbox" id="user">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($users as $user) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="user<?php echo $user['user_id']; ?>" class="<?php echo $class; ?>"><?php echo $user['name']; ?>&nbsp;&nbsp;&nbsp;<img src="view/image/delete.png" align="absmiddle" />
                    <input type="hidden" name="user[]" value="<?php echo $user['user_id']; ?>" />
                  </div>
                  <?php } ?>
                </div></td>
            </tr>
          </tbody>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_subject; ?></td>
            <td><input type="text" name="subject" value="<?php echo $subject; ?>" />
              <?php if ($error_subject) { ?>
              <span class="error"><?php echo $error_subject; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_message; ?></td>
            <td><textarea name="message"><?php echo $message; ?></textarea>
              <?php if ($error_message) { ?>
              <span class="error"><?php echo $error_message; ?></span>
              <?php } ?></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
CKEDITOR.replace('message', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
//--></script> 
<script type="text/javascript"><!--	
$('select[name=\'to\']').bind('change', function() {
	$('#mail .to').hide();
	$('.scrollbox').next('span').hide();
	$('#mail #to-' + $(this).attr('value').replace('_', '-')).show();
});

$('select[name=\'user_group_id\']').bind('change', function() {
	if(this.value == '2')
		$('#show_active_tutors').show();
	else
		$('#show_active_tutors').hide();	
});

$('select[name=\'to\']').trigger('change');
$('select[name=\'user_group_id\']').trigger('change');
//--></script>
<script type="text/javascript"><!--
$.widget('custom.catcomplete', $.ui.autocomplete, {
	_renderMenu: function(ul, items) {
		var self = this, currentCategory = '';
		
		$.each(items, function(index, item) {
			if (item.category != currentCategory) {
				ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');
				
				currentCategory = item.category;
			}
			
			self._renderItem(ul, item);
		});
	}
});

$('input[name=\'users\']').catcomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=sale/user/autocomplete&token=<?php echo $token; ?>',
			type: 'POST',
			dataType: 'json',
			data: 'filter_name=' +  encodeURIComponent(request.term),
			success: function(data) {	
				response($.map(data, function(item) {
					return {
						category: item.user_group,
						label: item.name,
						value: item.user_id
					}
				}));
			}
		});
		
	}, 
	select: function(event, ui) {
		$('.scrollbox' + ui.item.value).remove();
		$('.scrollbox').append('<div id="user' + ui.item.value + '">' + ui.item.label + '&nbsp;&nbsp;&nbsp;<img src="view/image/delete.png" align="absmiddle" /><input type="hidden" name="user[]" value="' + ui.item.value + '" /></div>');

		$('.scrollbox div:odd').attr('class', 'odd');
		$('.scrollbox div:even').attr('class', 'even');
				
		return false;
	}
});

$('.scrollbox div img').live('click', function() {
	$(this).parent().remove();
	
	$('.scrollbox div:odd').attr('class', 'odd');
	$('.scrollbox div:even').attr('class', 'even');	
});
//--></script> 
<?php echo $footer; ?>