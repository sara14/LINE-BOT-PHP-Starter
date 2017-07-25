<?php
	
	function isRegstered($usid){
		require_once 'config.php';
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
	function isRegist($userId){
	require_once 'config.php';
	try {
		$result = false;
		$oConn = new PDO('mysql:host='.$sHost.';dbname='.$sDb.';charset=utf8', $sUsername, $sPassword);
		$oConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$oStmt = $oConn->prepare('SELECT userid FROM heroku_c567de8b5a4ca4f.user_list WHERE userid="' . $userId . '"');
		$oStmt->execute();
		$oResult = $oStmt->fetchAll();
		foreach ($oResult as $aRow) {
			if($userId == $aRow['userid']){
				$result=true;
			}
		}
		$oConn=null;
		return $result;
	} catch(PDOException $e) {
		echo 'ERROR: ' . $e->getMessage();
		$result=false;
	}
	
	return $result;
}
	echo "" . isRegist('U043c1dbc5506079b0b11d3f402aea555');
	
?> 