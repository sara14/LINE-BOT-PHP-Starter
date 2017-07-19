<?php
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

			// Build message to reply back
			//$messages = [
			//	'type' => 'text',
			//	'text' => $userId . ":" . $text
			//];
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
			}else if($text == "ทดสอบ"){
				$messages = [
					'type' => 'text',
					'text' => $userId . ":" . $text
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
			

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
		}else{
			// Get text sent
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

			

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
		}
	}
}
echo "OK";
