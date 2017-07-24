<?php
    require_once 'config.php';
	try {
		$oConn = new PDO('mysql:host='.$sHost.';dbname='.$sDb, $sUsername, $sPassword);
		$oConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$oStmt = $oConn->prepare('SELECT iquery FROM heroku_c567de8b5a4ca4f.query_table');
		$oStmt->execute();
		$oResult = $oStmt->fetchAll();
		foreach ($oResult as $aRow) {
			print_r($aRow['iquery']);
		}
	} catch(PDOException $e) {
		echo 'ERROR: ' . $e->getMessage();
	}
?> 