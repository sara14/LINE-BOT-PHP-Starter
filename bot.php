<?php

function isRegistered($userId){
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
						
					}else if($text == "test"){
						$messages = [
							'type' => 'text',
							'text' => $userId . ":" . $text
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
						
					}
					
					replyToUser($replyToken,$messages,$access_token);
				}else{
					if (strpos($text, 'ลงทะเบียน-') !== false) {
						require_once 'config.php';
						try {
							$oConn = new PDO('mysql:host='.$sHost.';dbname='.$sDb.';charset=utf8', $sUsername, $sPassword);
							$oConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							$oStmt = $oConn->prepare('INSERT INTO heroku_c567de8b5a4ca4f.query_table VALUES("' . $replyToken . '","' . $text . '","' . $userId . '",Now())');
							$oStmt->execute();
							$oConn=null;
							$messages = [
										'type' => 'text',
										'text' => 'คำร้องขอลงทะเบียนของคุณถูกส่งไปที่ผู้ดูแลระบบแล้ว ซึ่งอาจจะใช้เวลาสักพักเพื่อรอการอนุมัติ ทันทีที่คำขอของคุณได้รับการอนุมัติผมจะแจ้งให้ทราบทันทีครับ'
									];
							replyToUser($replyToken,$messages,$access_token);
							
						} catch(PDOException $e) {
							echo 'ERROR: ' . $e->getMessage();
							$messages = [
										'type' => 'text',
										'text' => 'error'
									];
							replyToUser($replyToken,$messages,$access_token);
						}
						
					}else{
						$messages = [
										'type' => 'text',
										'text' => 'คุณยังไม่ได้ลงทะเบียน จึงยังไม่สามารถสอบถามข้อมูลได้ เพื่อการตอบคำถามที่ถูกต้องกรุณาลงทะเบียนก่อน โดยพิมพ์ข้อความ "ลงทะเบียน-รหัสพนักงาน" ส่งมาที่ผมเพื่อลงทะเบียนครับ ตัวอย่างเช่น ลงทะเบียน-7100000'
									];
						replyToUser($replyToken,$messages,$access_token);
					}
					
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
						'text' => "Respond :" . $text
					];
				
				replyToUser($replyToken,$messages,$access_token);
				
			}
		}
	
}
echo "OK";
