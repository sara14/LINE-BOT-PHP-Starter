<?php
    require_once 'config.php';
	try {
		//$oConn = new PDO('mysql:host='.$sHost.';dbname='.$sDb, $sUsername, $sPassword);
		//$oConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//$oStmt = $oConn->prepare('SELECT iquery FROM heroku_c567de8b5a4ca4f.query_table');
		//$oStmt->execute();
		//$oResult = $oStmt->fetchAll();
		//foreach ($oResult as $aRow) {
		//	print_r($aRow['iquery']);
		//}
		//$oConn->close
		
		$objConnect = mysql_connect($sHost,$sUsername,$sPassword) or die("Error Connect to Database");
		$objDB = mysql_select_db($sDb);
		mysql_query("SET character_set_results=tis620");
		mysql_query("SET character_set_client=tis620");
		mysql_query("SET character_set_connection=tis620");
		$strSQL = 'INSERT INTO query_table VALUES("X","ทดสอบ","666",Now())';
		mysql_query($strSQL);
		mysql_close($objConnect);
	//} catch(PDOException $e) {
	//	echo 'ERROR: ' . $e->getMessage();
	//}
	echo 'complete';
	
	} catch(Exception $e) {
		echo 'ERROR: ';
	}
?> 