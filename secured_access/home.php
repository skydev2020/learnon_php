<?php

session_start();
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'learnonc_openc');
define('DB_PASSWORD', 'openc@11');
define('DB_DATABASE', 'learnonc_dbopenc');

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<body>

<div id="main">

	

	<!-- Columns -->
	<div id="cols" class="box">

		
		<hr class="noscreen" />

		<!-- Content (Right Column) -->
		<div id="content" class="box">
		
			<h3 class="tit">Data Backup</h3>
			
			<!-- Form -->
			<form method="post" >
						
			<fieldset>
				<legend> Download Backups </legend>
				
				<table class="nostyle">
						<tr>			
						<td>							
								<?php
									//get list of zip files									
									$path = "/home1/learnonc/public_html/portal/secured_access/backups";

									// Open the folder
									$dir_handle = @opendir($path) or die("Unable to open $path");

									// Loop through the files
									while ($file = readdir($dir_handle)) 
									{

										if(strlen($file)==10 and substr_count($file,'-') == 2 and (substr_count($file,'2012-') == 1 || substr_count($file,'2013-') == 1 || substr_count($file,'2014-') == 1 || substr_count($file,'2015-') == 1 ) and $file != '2012-12-25')	
											echo "<a href='http://learnon.ca/secured_access/backups/$file/code_backup.zip'>$file</a><br>";
											
											//echo "<option value='$file'>$file</option>";

									}
									// Close
									closedir($dir_handle); 
								?>						
												
						</td>						
					</tr>
					
				</table>
			</fieldset>	

			<fieldset>
				<legend> Create Backup </legend>
				
				<table class="nostyle">
						<tr>			
						<td>		
							<a href="http://learnon.ca/portal/secured_access/backup_data.php">Create Todays Compressed Backup</a>		
												
						</td>						
					</tr>
					
				</table>
			</fieldset>	
				
				</form>
		</div> <!-- /content -->

	</div> <!-- /cols -->

	<hr class="noscreen" />

	<!-- Footer -->
	<?php //include(ABSPATH_CMN_INC.'footer.php');?>
	 <!-- /footer -->

</div> <!-- /main -->
</body>
</html>