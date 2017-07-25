<?php
    require_once 'config.php';
	try {
		$oConn = new PDO('mysql:host='.$sHost.';dbname='.$sDb.';charset=utf8', $sUsername, $sPassword);
		$oConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$oStmt = $oConn->prepare('SELECT iquery FROM heroku_c567de8b5a4ca4f.query_table');
		$oStmt->execute();
		$oResult = $oStmt->fetchAll();
		foreach ($oResult as $aRow) {
			print_r($aRow['iquery']);
		}
		$oConn=null;
		echo "Res:" . isRegistered('U043c1dbc5506079b0b11d3f402aea555');
	} catch(PDOException $e) {
		echo 'ERROR: ' . $e->getMessage();
	}
	function isRegistered($userId){
	try {
		$result = false;
		$oConn = new PDO('mysql:host='.$sHost.';dbname='.$sDb.';charset=utf8', $sUsername, $sPassword);
		$oConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$oStmt = $oConn->prepare('SELECT userid FROM heroku_c567de8b5a4ca4f.user_list WHERE userid="' . $userId . '"');
		$oStmt->execute();
		$oResult = $oStmt->fetchAll();
		foreach ($oResult as $aRow) {
			$result=true;
			echo $aRow['userid'];
		}
		$oConn=null;
		return $result;
	} catch(PDOException $e) {
		echo 'ERROR: ' . $e->getMessage();
		$result=false;
	}
	
	return $result;
}
?> 