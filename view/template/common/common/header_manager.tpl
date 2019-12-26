<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" xml:lang="<?php echo $lang; ?>">
<head>
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" />
<link rel="stylesheet" type="text/css" href="view/javascript/jquery/ui/themes/ui-lightness/ui.all.css" />
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script type="text/javascript" src="view/javascript/jquery/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-1.8.9.custom.min.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.core.js"></script>
<script type="text/javascript" src="view/javascript/jquery/superfish/js/superfish.js"></script>
<script type="text/javascript" src="view/javascript/jquery/tab.js"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<script type="text/javascript">
//-----------------------------------------
// Confirm Actions (delete, uninstall)
//-----------------------------------------
$(document).ready(function(){
	
    // Confirm Delete
    $('#form').submit(function(){
        if ($(this).attr('action').indexOf('delete',1) != -1) {
            if (!confirm ('<?php echo $text_confirm; ?>')) {
                return false;
            }
        }
    });
    	
    // Confirm Uninstall
    $('a').click(function(){
        if ($(this).attr('href') != null && $(this).attr('href').indexOf('uninstall',1) != -1) {
            if (!confirm ('<?php echo $text_confirm; ?>')) {
                return false;
            }
        }
    });
});
</script>
<script type="text/javascript">
$(document).ready(function(){
    $(".scrollbox").each(function(i) {
    	$(this).attr('id', 'scrollbox_' + i);
		sbox = '#' + $(this).attr('id');
    	$(this).after('<span><a onclick="$(\'' + sbox + ' :checkbox\').attr(\'checked\', \'checked\');"><u><?php echo $text_select_all; ?></u></a> / <a onclick="$(\'' + sbox + ' :checkbox\').attr(\'checked\', \'\');"><u><?php echo $text_unselect_all; ?></u></a></span>');
	});
});
</script>
</head>
<body>
<div id="container">
<div id="header">
  <div class="div1"><img src="view/image/logo.png" title="<?php echo $heading_title; ?>" onclick="location = '<?php echo $home; ?>'" /></div>
  <?php if ($logged) { ?>
  <div class="div2"><img src="view/image/lock.png" alt="" style="position: relative; top: 3px;" />&nbsp;<?php echo $logged; ?></div>
  <?php } ?>
</div>
<?php if ($logged) { ?>
<div id="menu">
  <ul class="nav left" style="display: none;">
    <li id="dashboard"><a href="<?php echo $home; ?>" class="top"><?php echo $text_dashboard; ?></a></li>
    <li id="package"><a href="<?php echo $packages; ?>" class="top"><?php echo $text_package; ?></a></li>
    <li id="essay"><a href="<?php echo $essays; ?>" class="top"><?php echo $text_essay; ?></a></li>
    <li id="student"><a href="<?php echo $students; ?>" class="top"><?php echo $text_student; ?></a>
      <ul>
        <li><a href="<?php echo $student_assignment; ?>"><?php echo $text_student_assignment; ?></a></li>
      </ul>
    </li>
    <li id="tutor"><a href="<?php echo $tutors; ?>" class="top"><?php echo $text_tutor; ?></a>
      <ul>
        <li><a href="<?php echo $tutor_assignment; ?>"><?php echo $text_tutor_assignment; ?></a></li>
      </ul>
    </li>
    <li id="tutor"><a href="<?php echo $sessions; ?>" class="top"><?php echo $text_session; ?></a></li>
    <li id="tutor"><a href="<?php echo $cms; ?>" class="top"><?php echo $text_cms; ?></a>
      <ul>
    		<li><a href="<?php echo $information; ?>"><?php echo $text_information; ?></a></li>
    		<li><a href="<?php echo $coupon; ?>"><?php echo $text_coupon; ?></a></li>
    		<li><a href="<?php echo $email_templates; ?>"><?php echo $text_email_templates; ?></a></li>
    		<li><a href="<?php echo $logmail; ?>"><?php echo $text_logmail; ?></a></li>
			<li><a href="<?php echo $logactivity; ?>"><?php echo $text_logactivity; ?></a></li>
    		<li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
        	<li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>    
      </ul>
    </li>
    <li id="reports"><a href="<?php echo $cms; ?>" class="top"><?php echo $text_reports; ?></a>
      <ul>
        <li><a href="<?php echo $reports; ?>"><?php echo $text_report; ?></a></li>
		<li><a href="<?php echo $view_monthly_data; ?>"><?php echo $text_view_monthly_data; ?></a></li>
		<li><a href="<?php echo $report_sale; ?>"><?php echo $text_report_sale; ?></a></li>
      </ul>
    </li>
	<li id="help"><a href="<?php echo $resources; ?>" class="top" target="_blank"><?php echo $text_resources; ?></a></li>
    <li id="help"><a href="<?php echo $helps; ?>" class="top"><?php echo $text_help; ?></a></li>
  </ul>
  <ul class="nav right">
    <li id="store"><a class="top" href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
  </ul>
  <script type="text/javascript"><!--
$(document).ready(function() {
	$('.nav').superfish({
		hoverClass	 : 'sfHover',
		pathClass	 : 'overideThisToUse',
		delay		 : 0,
		animation	 : {height: 'show'},
		speed		 : 'normal',
		autoArrows   : false,
		dropShadows  : false, 
		disableHI	 : false, /* set to true to disable hoverIntent detection */
		onInit		 : function(){},
		onBeforeShow : function(){},
		onShow		 : function(){},
		onHide		 : function(){}
	});
	
	$('.nav').css('display', 'block');
});
//--></script>
  <script type="text/javascript"><!-- 
function getURLVar(urlVarName) {
	var urlHalves = String(document.location).toLowerCase().split('?');
	var urlVarValue = '';
	
	if (urlHalves[1]) {
		var urlVars = urlHalves[1].split('&');

		for (var i = 0; i <= (urlVars.length); i++) {
			if (urlVars[i]) {
				var urlVarPair = urlVars[i].split('=');
				
				if (urlVarPair[0] && urlVarPair[0] == urlVarName.toLowerCase()) {
					urlVarValue = urlVarPair[1];
				}
			}
		}
	}
	
	return urlVarValue;
} 

$(document).ready(function() {
	route = getURLVar('route');
	
	if (!route) {
		$('#dashboard').addClass('selected');
	} else {
		part = route.split('/');
		
		url = part[0];
		
		if (part[1]) {
			url += '/' + part[1];
		}
		
		$('a[href*=\'' + url + '\']').parents('li[id]').addClass('selected');
	}
});
//--></script>
</div>
<?php } ?>
<div id="content">
<?php if ($breadcrumbs) { ?>
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div>
<?php } ?>
