<?php
require_once 'config.php';

function isRegistered($userId){
	
	try {
		$result = false;
		$oConn = new PDO('mysql:host='. $GLOBALS['sHost'] .';dbname='. $GLOBALS['sDb'] .';charset=utf8', $GLOBALS['sUsername'], $GLOBALS['sPassword']);
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
function isPendingRegister($userId){
	
	try {
		$result = false;
		$oConn = new PDO('mysql:host='. $GLOBALS['sHost'] .';dbname='. $GLOBALS['sDb'] .';charset=utf8', $GLOBALS['sUsername'], $GLOBALS['sPassword']);
		$oConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$oStmt = $oConn->prepare('SELECT Requester FROM heroku_c567de8b5a4ca4f.query_table WHERE Requester ="' . $userId . '"');
		$oStmt->execute();
		$oResult = $oStmt->fetchAll();
		foreach ($oResult as $aRow) {
			$result=true;
		}
		$oConn=null;
		return $result;
		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
			$result=false;
		}
	
	return $result;
}
function replyToUser($reToken,$message,$ac_token){
	
	// Make a POST Request to Messaging API to reply to sender
	$url = 'https://api.line.me/v2/bot/message/reply';
	$data = [
		'replyToken' => $reToken,
		'messages' => [$message]
	];
	$post = json_encode($data);
	$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $ac_token);

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	//echo $result . "\r\n";
}
function pushToUser($userID,$message,$ac_token){
	
	// Make a POST Request to Messaging API to reply to sender
	$url = 'https://api.line.me/v2/bot/message/push';
	$data = [
		'to' => $userID,
		'messages' => [$message]
	];
	$post = json_encode($data);
	$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $ac_token);

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	//echo $result . "\r\n";
}

$access_token = 'Mq2pK5XVmM83DUMUh/55lt5oFCV2PzEpGV3qoG7tr/2B6MXhmOtjVwPXyfhgH27GrC8nysA0Po3KH+b+ImCfK9fg+xwPFTuLCauttOjLE47vlGSxItWqNJXLUS0xjkUnXXjblop8wg1wOocI6ezgQAdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
		
		// Loop through each event
		foreach ($events['events'] as $event) {
			// Reply only when message sent is in 'text' format
			if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
				// Get text sent
				$text = $event['message']['text'];
				// Get replyToken
				$replyToken = $event['replyToken'];
				// Get userId
				$userId = $event['source']['userId'];
				//$rrs = json_encode($event['source']);
				$rrs = $content;
				if(isRegistered($userId)==1){
				
					if($text == "Confirm"){
						$messages =[
						  'type' => 'template',
						  'altText' => 'this is a confirm template',
						  'template' => [
							  'type' => 'confirm',
							  'text' => 'Are you sure?',
							  'actions' => [
								  [
									'type' => 'message',
									'label' => 'Yes',
									'text' => 'Chose yes'
								  ],
								  [
									'type' => 'message',
									'label' => 'No',
									'text' => 'Chose no'
								  ]
							  ]
						  ]
						];
						
					}else if($text == "Test"){
						$messages = [
							'type' => 'text',
							'text' => $userId . ":" . $rrs
						];
						
					}else if($text == "สวัสดี"){
						$messages = [
							'type' => 'text',
							'text' => 'สวัสดีครับ  ^^'
						];
					}else if($text == "ทดสอบ"){
						$messages = [
									  'type' => 'imagemap',
									  'baseUrl' => 'https://github.com/line/line-bot-sdk-go/tree/master/examples/kitchensink/static/rich',
									  'altText' => 'this is an imagemap',
									  'baseSize' => [
										  'height' => 700,
										  'width' => 700
									  ],
									  'actions' => [
										  [
											  'type' => 'uri',
											  'linkUri' => 'https://example.com/',
											  'area' => [
												  'x' => 0,
												  'y' => 0,
												  'width' => 700,
												  'height' => 700
											  ]
										  ],
										  [
											  'type' => 'message',
											  'text' => 'Confirm',
											  'area' => [
												  'x' => 350,
												  'y' => 0,
												  'width' => 700,
												  'height' => 700
											  ]
										  ]
									  ]
									];
									
					}else if($text == "Profile"){
						$messages = [
									  'type' => 'template',
									  'altText' => 'this is a buttons template',
									  'template' => [
										  'type' => 'buttons',
										  'thumbnailImageUrl' => 'https://blog.prepscholar.com/hs-fs/hubfs/main_register.jpg',
										  'title' => 'ทดสอบเนื้อหา',
										  'text' => 'Please select',
										  'actions' => [
											  [
												'type' => 'postback',
												'label'=> 'Buy',
												'data' => 'action=buy&itemid=123'
											  ],
											  [
												'type' => 'postback',
												'label' => 'Add to cart',
												'data' => 'action=add&itemid=123'
											  ],
											  [
												'type' => 'uri',
												'label' => 'View detail',
												'uri' => 'http://php.fnlist.com/php/json_decode'
											  ]
										  ]
									  ]
									];
						
					}else{
						$messages = [
							'type' => 'text',
							'text' => 'ขออภัยครับผมไม่สามารถตอบคำถามนี้ได้ในตอนนี้ อย่างไรก็ดีคำถามของคุณจะถูกส่งไปให้ HR Admin และเมื่อมีการตอบกลับ ผมจะแจ้งให้ทราบทันทีครับ'
						];
					}
					
					replyToUser($replyToken,$messages,$access_token);
				}else{
					if (strpos($text, 'ลงทะเบียน-') !== false) {
						
						try {
							if(isPendingRegister($userId)==1){
							
							}else{
								$oConn = new PDO('mysql:host='.$sHost.';dbname='.$sDb.';charset=utf8', $sUsername, $sPassword);
								$oConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
								$oStmt = $oConn->prepare('INSERT INTO heroku_c567de8b5a4ca4f.query_table VALUES("' . $replyToken . '","' . $text . '","' . $userId . '",Now())');
								$oStmt->execute();
								$oConn=null;
								$messages = [
											'type' => 'text',
											'text' => 'คำร้องขอลงทะเบียนของคุณถูกส่งไปที่ผู้ดูแลระบบแล้ว ซึ่งอาจจะใช้เวลาสักระยะเพื่อรอการอนุมัติ ทันทีที่คำขอของคุณได้รับการอนุมัติผมจะแจ้งให้ทราบทันทีครับ'
										];
										
								$adminMessages = [
									  'type' => 'template',
									  'altText' => 'this is a buttons template',
									  'template' => [
										  'type' => 'buttons',
										  'thumbnailImageUrl' => 'https://blog.prepscholar.com/hs-fs/hubfs/main_register.jpg',
										  'title' => 'คำร้องขอลงทะเบียน',
										  'text' => 'มีคำขอ' . $text . 'ต้องการอนุมัติหรือไม่',
										  'actions' => [
											  [
												'type' => 'postback',
												'label'=> 'อนุมัติ',
												'data' => 'action=addmember&itemid=' . $userId . '&reqid=' . $replyToken
											  ],
											  [
												'type' => 'postback',
												'label' => 'ไม่อนุมัติ',
												'data' => 'action=rejectmember&itemid=' . $userId . '&reqid=' . $replyToken
											  ]
										  ]
									  ]
									];
							}
							replyToUser($replyToken,$messages,$access_token);
							pushToUser('U043c1dbc5506079b0b11d3f402aea555',$adminMessages,$access_token);
							
						} catch(PDOException $e) {
							$err = $e->getMessage();
							$messages = [
										'type' => 'text',
										'text' => 'error' . $err
									];
							replyToUser($replyToken,$messages,$access_token);
						}
						
					}else{
						if(isPendingRegister($userId)==1){
							$messages = [
											'type' => 'text',
											'text' => 'คุณได้ส่งคำร้องลงทะเบียนไปแล้ว แต่ยังไม่ได้รับการอนุมัติ อาจใช้เวลาสักครู่เพื่อรอการตรวจสอบครับ'
										];
						}else{
							$messages = [
											'type' => 'text',
											'text' => 'คุณยังไม่ได้ลงทะเบียน จึงยังไม่สามารถสอบถามข้อมูลได้ เพื่อการตอบคำถามที่ถูกต้องกรุณาลงทะเบียนก่อน โดยพิมพ์ข้อความ "ลงทะเบียน-รหัสพนักงาน" ส่งมาที่ผมเพื่อลงทะเบียนครับ ตัวอย่างเช่น ลงทะเบียน-7100000'
										];
						}
						replyToUser($replyToken,$messages,$access_token);
					}
					
				}
			}else if ($event['type'] == 'postback') {
				$text = $event['type'] . $event['postback']['data'];
				$replyToken = $event['replyToken'];
				$userId = $event['source']['userId'];
				if (strpos($text, 'action=addmember') !== false) {
					$postback = explode("&", $text);
					$targetUserID = explode("=",$postback[1])[1];
					$reqID = explode("=",$postback[2])[1];
					if(isPendingRegister($targetUserID)==1){
						try{
						
							$oConn = new PDO('mysql:host='.$sHost.';dbname='.$sDb.';charset=utf8', $sUsername, $sPassword);
							$oConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							$oStmt = $oConn->prepare('INSERT INTO heroku_c567de8b5a4ca4f.user_list VALUES("' . $targetUserID . '",Now())');
							$oStmt->execute();
							$oConn=null;
							
							$oConn = new PDO('mysql:host='.$sHost.';dbname='.$sDb.';charset=utf8', $sUsername, $sPassword);
							$oConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							$oDel = $oConn->prepare('DELETE FROM heroku_c567de8b5a4ca4f.query_table WHERE ReqID = "' . $reqID . '"');
							$oDel->execute();
							$oConn=null;
							$messages = [
								'type' => 'text',
								'text' => "ทำการเพิ่มผู้ใช้ใหม่เรียบร้อยแล้ว"
							];
							$replymessages = [
								'type' => 'text',
								'text' => "คำร้องขอลงทะเบียนได้รับการอนุมัติจาก HR Admin แล้วครับ มาเริ่มคุยกันดีกว่า"
							];
							pushToUser($targetUserID,$replymessages,$access_token);
						} catch(PDOException $e) {
								$err = $e->getMessage();
								$messages = [
											'type' => 'text',
											'text' => 'error' . $err
										];
								//replyToUser($replyToken,$messages,$access_token);
						}
					}else{
						$messages = [
							'type' => 'text',
							'text' => "คำร้องนี้ถูกจัดการไปแล้ว"
						];
					}
					replyToUser($replyToken,$messages,$access_token);
				}else if (strpos($text, 'action=rejectmember') !== false) {
					$postback = explode("&", $text);
					$targetUserID = explode("=",$postback[1])[1];
					$reqID = explode("=",$postback[2])[1];
					if(isPendingRegister($targetUserID)==1){
						try{
							$oConn = new PDO('mysql:host='.$sHost.';dbname='.$sDb.';charset=utf8', $sUsername, $sPassword);
							$oConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							$oStmt = $oConn->prepare('DELETE FROM heroku_c567de8b5a4ca4f.query_table WHERE ReqID = "' . $reqID . '"');
							$oStmt->execute();
							$oConn=null;
							$messages = [
								'type' => 'text',
								'text' => "ทำการปฎิเสธการลงทะเบียนของผู้ใช้เรียบร้อยแล้ว" . $targetUserID
							];
							$replymessages = [
								'type' => 'text',
								'text' => "คำร้องขอลงทะเบียนของคุณถูกปฎิเสธ กรุณาตรวจสอบข้อมูลของคุณ และลงทะเบียนอีกครั้งครับ"
							];
							pushToUser($targetUserID,$replymessages,$access_token);
						} catch(PDOException $e) {
							$err = $e->getMessage();
							$messages = [
										'type' => 'text',
										'text' => 'error' . $err
									];
							//replyToUser($replyToken,$messages,$access_token);
						}
						
					}else{
						$messages = [
						'type' => 'text',
						'text' => "คำร้องนี้ถูกจัดการไปแล้ว"
					];
					}
					
					replyToUser($replyToken,$messages,$access_token);
				}
				
				
			}else{
				// Get type sent
				$text = $event['type'] . $event['postback']['data'];
				// Get replyToken
				$replyToken = $event['replyToken'];
				// Get userId
				$userId = $event['source']['userId'];

				// Build message to reply back
					$messages = [
						'type' => 'text',
						//'text' => "Respond :" . $text
						'text' => "Respond :" . $content
					];
				
				replyToUser($replyToken,$messages,$access_token);
				
			}
		}
	
}
echo "OK1";
