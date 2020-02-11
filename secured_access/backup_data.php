<?php

	define('DB_HOSTNAME', 'localhost');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', '');
	define('DB_DATABASE', 'learnonc_live');

	//$conn = mysql_connect(DB_HOSTNAME,DB_USERNAME, DB_PASSWORD)or die(mysql_error());
	//mysql_select_db(DB_DATABASE, $conn) or die(mysql_error());
	
	
	if(file_exists("/home1/learnonc/public_html/portal/secured_access/backups/".date("Y-m-d")."/code_backup.zip"))
	{
		echo "Today backup is already created. You can only create 1 backup per day!";
	}
	else
	{
		set_time_limit(0);
		
		//setup uniform timezone on the 2 servers
		date_default_timezone_set("America/New_York");
		
		//create todays directory
		mkdir("/home1/learnonc/public_html/portal/secured_access/backups/".date("Y-m-d"),0777, true);
		
		//database backup	
		exec('mysqldump --user=learnonc_openc --password=openc@11 --host=localhost learnonc_dbopenc > /home1/learnonc/public_html/portal/secured_access/backups/'.date("Y-m-d").'/backup.sql'); //path to save the backup file
		echo '<br> Database backup completed';
		
		
		//zip file of entire project code. It also contains db file now in backups folder, as it was created earlier
		$file_name = '/home1/learnonc/public_html/portal/secured_access/backups/'.date("Y-m-d").'/code_backup.zip';	
		
		
		//creating zip archive
		$zip_file = $file_name;
		require_once('pclzip.lib.php');
		$archive = new PclZip($zip_file);	
		
		$v_list = $archive->add("/home1/learnonc/public_html/portal",PCLZIP_CB_PRE_ADD, 'myPreAddCallBack'); //the archieve to add in backup
		
		if ($v_list == 0)
		{
			die("Error : ".$archive->errorInfo(true));
		}
		else
		{
			echo "<br> Code Backup Completed!";
		}	
		
	}
	
	function myPreAddCallBack($p_event, &$p_header)
	{
		$info = pathinfo($p_header['stored_filename']);
		// ----- backup files are skipped, except the database backup file
		if (
				strpos($info['dirname'],'/home1/learnonc/public_html/portal/secured_access/backups') === 0 and 
				(strpos($info['dirname'],'/home1/learnonc/public_html/portal/secured_access/backups/'.date("Y-m-d")) !== 0 or  
				$info['basename'] !== 'backup.sql')
			) 
		{
			return 0;
		}		
		// ----- all other files are simply added
		else 
		{
			return 1;
		}
	}
	
	
?>