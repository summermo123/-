<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
if(isset($_GET["echostr"])){
	$wechatObj->valid();
}

$wechatObj->responseMsg();

class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		//$poststr =file_get_contents(" php://input")

      	//extract post data
		if (!empty($postStr)){
                
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
				$time = time();
				
				$msgtype = $postObj->MsgType;

				if($msgtype=='text'){
				
					$keyword = trim($postObj->Content);

					$textTpl = "<xml>
								<ToUserName><![CDATA[%s]]></ToUserName>
								<FromUserName><![CDATA[%s]]></FromUserName>
								<CreateTime>%s</CreateTime>
								<MsgType><![CDATA[%s]]></MsgType>
								<Content><![CDATA[%s]]></Content>
								<FuncFlag>0</FuncFlag>
								</xml>";   
								
					if(!empty( $keyword ))
					{
						$msgType = "text";
						//$contentStr = "Welcome to wechat world!";
						$contentStr = $keyword;

						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);

						echo $resultStr;
					}else{
						echo "Input something...";
					}
				
				}elseif($msgtype=='image'){

					$MediaId = $postObj->MediaId;

					$imgtpl = "<xml>
								<ToUserName><![CDATA[%s]]></ToUserName>
								<FromUserName><![CDATA[%s]]></FromUserName>
								<CreateTime>%s</CreateTime>
								<MsgType><![CDATA[image]]></MsgType>
								<Image>
								<MediaId><![CDATA[%s]]></MediaId>
								</Image>
								</xml>";

					$resultStr = sprintf($imgtpl, $fromUsername, $toUsername, $time,  $MediaId);

					echo $resultStr;

				}elseif($msgtype=='video'){

					$MediaId = $postObj->MediaId;
					$title = "aa";
					$desc  = "desc";

					$videotpl = "<xml>
								<ToUserName><![CDATA[%s]]></ToUserName>
								<FromUserName><![CDATA[%s]]></FromUserName>
								<CreateTime>%s</CreateTime>
								<MsgType><![CDATA[video]]></MsgType>
								<Video>
								<MediaId><![CDATA[%s]]></MediaId>
								<Title><![CDATA[%s]]></Title>
								<Description><![CDATA[%s]]></Description>
								</Video> 
								</xml>";

					$resultStr = sprintf($video, $fromUsername, $toUsername, $time,  $MediaId, $title, $desc);

					echo $resultStr;
				}
			

                

        }else {
        	echo "";
        	exit;
        }
    }
		
	private function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
		$token = TOKEN;

		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);

		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

?>