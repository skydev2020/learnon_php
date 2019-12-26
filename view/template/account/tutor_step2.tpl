<?php echo $header; ?>
<div id="content"><br />
  <div class="top">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center">
      <h1><?php echo $heading_title_tutor; ?></h1>
    </div>
  </div>
  <div class="middle">
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
<form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" id="create">
  <input name="username" value="<?php echo $username; ?>" type="hidden">
  <input name="firstname" value="<?php echo $firstname; ?>" type="hidden">
  <input name="lastname" value="<?php echo $lastname; ?>" type="hidden">
  <input name="email" value="<?php echo $email; ?>" type="hidden">
  <input name="home_phone" value="<?php echo $home_phone; ?>" type="hidden">
  <input name="cell_phone" value="<?php echo $cell_phone; ?>" type="hidden">
  <input name="address" value="<?php echo $address; ?>" type="hidden">
  <input name="city" value="<?php echo $city; ?>" type="hidden">
  <input name="state" value="<?php echo $state; ?>" type="hidden">
  <input name="pcode" value="<?php echo $pcode; ?>" type="hidden">
  <input name="country" value="<?php echo $country; ?>" type="hidden">
  <input name="password" value="<?php echo $password; ?>" type="hidden">
  <input name="confirm" value="<?php echo $confirm; ?>" type="hidden">
  <input name="users_note" value="<?php echo $users_note; ?>" type="hidden">
  <input name="post_secondary_education" value="<?php echo $post_secondary_education; ?>" type="hidden">
  <input name="subjects_studied" value="<?php echo $subjects_studied; ?>" type="hidden">
  <input name="courses_available" value="<?php echo $courses_available; ?>" type="hidden">
  <input name="previous_experience" value="<?php echo $previous_experience; ?>" type="hidden">
  <input name="cities" value="<?php echo $cities; ?>" type="hidden">
  <input name="references" value="<?php echo $references; ?>" type="hidden">
  <input name="gender" value="<?php echo $gender; ?>" type="hidden">
  <input name="certified_teacher" value="<?php echo $certified_teacher; ?>" type="hidden">
  <input name="criminal_conviction" value="<?php echo $criminal_conviction; ?>" type="hidden">
  <input name="background_check" value="<?php echo $background_check; ?>" type="hidden">
  <input type="hidden" name="step" value="2" />
  <?php if ($text_agree) { ?>
  <?php
  	$agreement_text = str_replace("value3",$name3,str_replace("value2",$name2,str_replace("value1",$name1,$tutor_agrement)));
	echo $agreement_text;
	$agreement_text2 = str_replace("value3","",str_replace("value2","",str_replace("value1","",$tutor_agrement)));
	?>
  <?php } ?>
  <p align="left">
  	<input type="hidden" name="agreement_text" value='<?=$agreement_text2?>' />
    <input name="logreg" class="submitbtn" value="Register" type="submit">
  </p>
</form>
  </div>
  <div class="bottom">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center"></div>
  </div>
</div>
<?php echo $footer; ?>