<?php
	require_once 'config.php';
	echo isRegstered('U043c1dbc5506079b0b11d3f402aea555');
	function isRegstered($usid){
		try {
			$oConn = new PDO('mysql:host='.$sHost.';dbname='.$sDb.';charset=utf8', $sUsername, $sPassword);
			$oConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$oStmt = $oConn->prepare('SELECT userid FROM heroku_c567de8b5a4ca4f.user_list');
			$oStmt->execute();
			$oResult = $oStmt->fetchAll();
			foreach ($oResult as $aRow) {
				print_r($aRow['userid']);
			}
			$oConn=null;
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
	}
?> 