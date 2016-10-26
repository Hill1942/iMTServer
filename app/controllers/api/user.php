<?php

/**
 *
 * Author:  kaidi - ykdacd@outlook.com
 * Version: 0.1
 * Date:    2016/09/05
 */

namespace controllers\api;


use daos\PlayerDao;
use core\Controller;

define('MAIL_TYPE_SEND_ITEM', 1);
define('MAIL_TYPE_MESSAGE', 2);

class User extends Controller{

    private $sKey = "f1539aeb081ebbdb98547fb7d331bc11";
    private static $redis;

    public function initRole() {
        $openid    = $_REQUEST['openid'];
        $channel   = $_REQUEST['channel'];
        $avatar    = $_REQUEST['avatar'];
        $nickname  = $_REQUEST['nickname'];

        $roleOut = PlayerDao::CheckUserRole($channel, $openid);
        if (count($roleOut) > 0) {
            $tmp = explode(" ", $roleOut[0]->dtLastLogin);
            $oldDt = $tmp[0];
            if ($oldDt != date("Y-m-d") && $roleOut[0]->iHeart < 5) {
                //echo "new date \n";
                $data = array(
                    "iHeart" => 5,
                    "dtLastLogin" => date("Y-m-d H:m:s")
                );
            } else {
                $data = array(
                    "dtLastLogin" => date("Y-m-d H:m:s")
                );
            }

            if (PlayerDao::UpdatePlayer($data, array("sOpenId" => $openid, "sChannel" => $channel))) {

                $this->OutputJson(0, "old user", array(
                    "sOpenId"   => $roleOut[0]->sOpenId,
                    "sChannel"  => $roleOut[0]->sChannel,
                    "iScore"    => $roleOut[0]->iScore,
                    "iScore2"   => $roleOut[0]->iScore2,
                    "iHeart"    => $roleOut[0]->iHeart,
                    "iCandy"    => $roleOut[0]->iCandy,
                ));
            } else {
                $this->OutputJson(-1, "something wrong");
            }
        } else {

            $data = array(
                "sOpenId"   => $openid,
                "sChannel"  => $channel,
                "sAvatar"   => $avatar,
                "sNickname" => $nickname,
                "iScore"    => 0,
                "iHeart"    => 5,
                "iCandy"    => 20,
                "dtLastLogin" => date("Y-m-d H:m:s"),
            );
            PlayerDao::CreateUserRole($data);

            $this->OutputJson(1, "a new user");
        }
    }

    public function login() {

        echo "kk";

        $idTokenString = $_REQUEST['token'];

        echo $idTokenString;

        $oauth_credentials = __DIR__ . '/../../../oauth-credentials.json';

        echo $oauth_credentials;

        if (!file_exists($oauth_credentials)) {
            //echo "<br>no file";
            exit(0);
        }

        /************************************************
         * NOTICE:
         * The redirect URI is to the current page, e.g:
         * http://localhost:8080/idtoken.php
         ************************************************/
        $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        $client = new \Google_Client();
        //echo "<br>989";

        $client->setAuthConfig($oauth_credentials);

       // echo "<br>14989";

        $client->setRedirectUri($redirect_uri);
        $client->setScopes('email');

        //echo "<br>63fdg";

        $client->setAccessToken($idTokenString);

        //echo "<br>9hgo";

        //echo "<br>ooo: " . json_encode($client->getAccessToken());


        $token_data = $client->verifyIdToken();

        if ($token_data) {
            $outArr = array(
                       "guid"=> "",
                       "guidToken"=> "2b9b8ab9b6852b0e41002f0263135d03",
                       "guidTokenExpire"=> $token_data["exp"],
                       "guidUserBirthday"=> "",
                       "guidUserNick"=> $token_data["name"],
                       "guidUserPortrait"=> $token_data["picture"],
                       "guidUserSex"=> 1,
                       "openId"=> md5(date("Y-m-d H:i:s") . $token_data["name"]),
                       "retCode"=> 1,
                       "retExtraJson"=> "{}",
                       "retMsg"=> "SUCCESS",
                   );

            echo json_encode($outArr);
        }

        //$this->OutputJson(0, "success", $token_data);

        if ($client->getAccessToken()) {
            //echo "<br>9hgo" . $
            //$token_data = $client->verifyIdToken();

            //$this->OutputJson(0, "success", $token_data);
        }


        //$verifier = new Google_AccessToken_Verify();

        //$idToken = $verifier->verifyIdToken($idTokenString);

        /*$outArr = array(
            "guid"=> $idToken->,
            "guidToken"=> "2b9b8ab9b6852b0e41002f0263135d03",
            "guidTokenExpire"=> 1482495622,
            "guidUserBirthday"=> "",
            "guidUserNick"=> "Yang Kaidi",
            "guidUserPortrait"=> "https=>//fbcdn-profile-a.akamaihd.net/hprofile-ak-xft1/v/t1.0-1/p50x50/13001266_853461958113804_818103792221861027_n.jpg?oh=059b00090df95a981d9cf9f0596620f0&oe=58AD6395&__gda__=1487469897_912cb2ed16e3d7f2052b4d4c44641dce",
            "guidUserSex"=> 1,
            "openId"=> "67108864000900",
            "imsdkRetCode"=> 1,
            "imsdkRetMsg"=> "",
            "retCode"=> 1,
            "retExtraJson"=> "{}",
            "retMsg"=> "SUCCESS",
        );*/







    }

     //获取sValidKey
    private function getSValidKey($params) {
        $sParams = '';
        if (ksort($params)) {
            foreach($params as $k => $v) {
                $sParams .= $v;
            }
            $sParams .= $this->sKey;
            return md5($sParams);
        }
        return false;
    }

    private function generatePassword($length = 8) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $password = "";
        for ($i = 0; $i < $length; $i++) {
            // 这里提供两种字符获取方式
            // 第一种是使用 substr 截取$chars中的任意一位字符；
            // 第二种是取字符数组 $chars 的任意元素
            // $password .= substr($chars, mt_rand(0, strlen($chars) – 1), 1);
            $password .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $password;
    }

    private function getRedis() {
        if (User::$redis) {
            return User::$redis;
        } else {
            User::$redis = new \Redis();
            $conn = User::$redis->connect("127.0.0.1", 6379, 3);
            if (!$conn) {
                exit(1);
            }
            return User::$redis;
        }
    }

    private function OutputJson($retCode, $retMsg, $retData = array()) {
        echo json_encode(array(
            "ret"  => $retCode,
            "msg"  => $retMsg,
            "data" => $retData
        ));
        exit(0);
    }
}