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

    public function uploadSurvivalScore() {

        $openid    = $_REQUEST['openid'];
        $channel   = $_REQUEST['channel'];
        $score     = $_REQUEST['score'];

        $data = array(
            "iScore"    => $score
        );

        $where = array(
            "sOpenId" => $openid,
            "sChannel" => $channel
        );

        if (PlayerDao::UpdatePlayer($data, $where)) {
            $this->OutputJson(0, "succeed in uploading survival score ");
        } else {
            $this->OutputJson(-1, "fail to upload survival score");
        }

        /*
        $redis = $this->getRedis();
        $tmp = $redis->get($userKey);
        if ($tmp) {
            $userInfo = json_decode($tmp, true);
            $surScore = $userInfo["sur_score"];
            $chaScore = $userInfo["cha_score"];

            if ($score > $surScore) {
                $redis->set($userKey, json_encode(array(
                    "sur_score" => $score,
                    "cha_score" => $chaScore,
                )));
            }

            echo "ok1";
        } else {
            $redis->set($userKey, json_encode(array(
                "sur_score" => $score,
                "cha_score" => 0,
            )));
            echo "ok2";
        }*/
    }

    /**
     *
     */
    public function uploadChallengeScore() {

        $openid    = $_REQUEST['openid'];
        $channel   = $_REQUEST['channel'];
        $score     = $_REQUEST['score'];

        $data = array(
            "iScore2"    => $score
        );

        $where = array(
            "sOpenId" => $openid,
            "sChannel" => $channel
        );

        if (PlayerDao::UpdatePlayer($data, $where)) {
            $this->OutputJson(0, "succeed in uploading challenge score ");
        } else {
            $this->OutputJson(-1, "fail to upload challenge score");
        }
    }

    public function getTopList() {
        //$openid    = $_REQUEST['openid'];
        $channel   = $_REQUEST['channel'];

        $topList = PlayerDao::GetTopList($channel, 10);

        $this->OutputJson(0, "succeed in loading list", $topList);
    }

    public function getFriendRankList() {

        /*$url = "http://srvapi-beta.itop.qq.com/v1.0/friends/lists?
        iGameId=1130&
        sInnerToken=0b76bac45b9639bf9d109f33855bf153&
        iOpenid=67113494512746&
        iPlatform=2&
        sValidKey=4750196c674ab8197495eb0ff2a01135";*/

        $channel    = $_REQUEST["channel"];
        $gameId     = $_REQUEST["gameId"];
        $innerToken = $_REQUEST["innerToken"];
        $openid     = $_REQUEST["openid"];
        $platform   = $_REQUEST["platform"];
        $validKey   = $this->getSValidKey(array(
            "iGameId"     => $gameId,
            "sInnerToken" => $innerToken,
            "iOpenid"     => $openid,
            "iPlatform"   => $platform
        ));
        //echo $validKey;
        //echo "<br>";

        $url = "http://srvapi-beta.itop.qq.com/v1.0/friends/lists?";
        $url .= "iGameId=" . $gameId . "&sInnerToken=" . $innerToken . "&iOpenid="
             . $openid . "&iPlatform=" . $platform . "&sValidKey=" . $validKey;

        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_POST, 0);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 5);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        $retInfo = curl_exec($curlHandle);

        //echo $retInfo;
        //echo "<br><br>";

        $retData = json_decode($retInfo, true);
        //echo gettype($retData);
        //echo "<br>";
        //echo $retData["code"];
        if ($retData["code"] == 1) {
            $rankList = array();

            $friendList = $retData["data"];  //friend info (openid) from imsdk

            //get friend info (score, name) from db
            $redis = $this->getRedis();
            for ($i = 0; $i < count($friendList); $i++) {
                $currOpenid = $friendList[$i]["iOpenid"];
                $tmp = PlayerDao::CheckUserRole($channel, $currOpenid);
                if (count($tmp) > 0) {
                    if ($redis->get("SendRedHeart_" . $openid . "_" . $currOpenid)) {
                        $sendHeart = 1;
                    } else {
                        $sendHeart = 0;
                    }
                    array_push($rankList, array(
                        "openid" => "" . $currOpenid,
                        "avatar" => $tmp[0]->sAvatar,
                        "nickname" => $tmp[0]->sNickname,
                        "score" => $tmp[0]->iScore,
                        "sendHeart" => $sendHeart
                    ));
                }
            }

            //add self info
            $self = PlayerDao::CheckUserRole($channel, $openid);
            array_push($rankList, array(
                "openid" => $openid,
                "avatar" => $self[0]->sAvatar,
                "nickname" => $self[0]->sNickname,
                "score" => $self[0]->iScore,
                "sendHeart" => -1
            ));

            foreach ($rankList as $key => $row) {
                $score_rank[$key] = $row ["score"];
            }

            array_multisort($score_rank, SORT_DESC, $rankList);

            $this->OutputJson(0, "succeed in loading friend list", $rankList);
        } else {
            $this->OutputJson(-1, "fail to get friend list");
        }
    }

    public function sendRedHeart() {
        $channel    = $_REQUEST["channel"];
        $openid     = $_REQUEST["openid"];
        $target     = $_REQUEST["target"];

        $redis = $this->getRedis();
        if ($redis->get("SendRedHeart_" . $openid . "_" . $target)) {
            $this->OutputJson(-200, "already send");
        }

        $tmp = PlayerDao::CheckUserRole($channel, $openid);

        if (count($tmp) > 0) {
            $nickname = $tmp[0]->sNickname;
            $msg = $nickname . " has sent you a red heart !";

            $end = strtotime(date("Y-m-d") . " 23:59:59");
            $now = time();
            $expireTime = $end - $now;
            $redis->setEx("SendRedHeart_" . $openid . "_" . $target, $expireTime, 1);
            $this->sendMail(MAIL_TYPE_SEND_ITEM, $target, $channel, $msg, array("iHeart" => 1));
        } else {
            $this->OutputJson(-102, "fail to send red heart");
        }
    }

    public function sendMessage() {
        $channel    = $_REQUEST["channel"];
        //$openid     = $_REQUEST["openid"];
        $target     = $_REQUEST["openid"];
        $msg        = $_REQUEST["msg"];

        $this->sendMail(MAIL_TYPE_MESSAGE, $target, $channel, $msg);
    }

    private function sendMail($mailType, $target, $channel, $msg, $items = array()) {
        $redis    = $this->getRedis();
        $mailId   = "MID-" . $channel . "-" . date("YmdHms") . "-" . $this->generatePassword(6) . "-" . $mailType;
        $mailItem = array(
            "type" => $mailType,
            "msg" => $msg
        );

        switch ($mailType) {
            case MAIL_TYPE_SEND_ITEM:
                $mailItem["items"] = $items;
                break;
            case MAIL_TYPE_MESSAGE:
                break;
            default:
                break;
        }

        if ($redis->set($mailId, json_encode($mailItem))) {
            $out = $redis->sAdd("mails_" . $channel . "_" . $target, $mailId);
            if ($out <= 0) {
                $this->OutputJson(-100, "fail to send mail");
            }
        } else {
            $this->OutputJson(-100, "fail to send mail");
        }

        $this->OutputJson(0, "succeed to send mail", array("mailId" => $mailId));
    }

    public function receiveMail() {
        $channel    = $_REQUEST["channel"];
        $openid     = $_REQUEST["openid"];
        $mailId     = $_REQUEST["mailId"];

        $redis = $this->getRedis();
        $tmp = $redis->get($mailId);
        if ($tmp) {
            $mailInfo = json_decode($tmp, true);
            switch ($mailInfo["type"]) {
                case MAIL_TYPE_SEND_ITEM:
                    $userOldData = PlayerDao::CheckUserRole($channel, $openid);
                    if (count($userOldData) > 0) {
                        $items = $mailInfo["items"];
                        $data = array();

                        foreach ($items as $key => $value) {
                            $newValue = intval($userOldData[0]->$key) + intval($value);
                            if ($key == "iHeart") {
                                $data[$key] = $newValue > 20 ? 20 : $newValue;
                            } else {
                                $data[$key] = $newValue;
                            }
                        }
                        $where = array(
                            "sOpenId" => $openid,
                            "sChannel" => $channel
                        );

                        if (PlayerDao::UpdatePlayer($data, $where)) {
                            $redis->del($mailId);
                            $redis->sRem("mails_" . $channel . "_" . $openid, $mailId);
                            $this->OutputJson(0, "succeed in update user's items", array(
                                "type" => 1,
                                "items" => $data
                            ));
                        } else {
                            $this->OutputJson(-1, "fail to update user's items");
                        }
                    } else {
                        $this->OutputJson(-102, "fail to load user info");
                    }

                    break;
                case MAIL_TYPE_MESSAGE:
                    $redis->del($mailId);
                    $redis->sRem("mails_" . $channel . "_" . $openid, $mailId);
                    $this->OutputJson(0, "succeed to receive mail", array(
                        "type" => MAIL_TYPE_MESSAGE
                    ));
                    break;
                default:
                    break;
            }
        } else {
            $this->OutputJson(-103, "no such mail");
        }

    }

    public function getAllMails() {
        $channel    = $_REQUEST["channel"];
        $openid     = $_REQUEST["openid"];

        $redis = $this->getRedis();
        $mailIds = $redis->sMembers("mails_" . $channel . "_" . $openid);
        $mails = array();
        for ($i = 0; $i < count($mailIds); $i++) {
            $mailItem = $redis->get($mailIds[$i]);
            if ($mailItem) {
                $tmp = json_decode($mailItem, true);
                array_push($mails, array(
                    "mailId" => $mailIds[$i],
                    "msg" => $tmp["msg"]
                ));
            } else {
                $redis->sRem("mails_" . $channel . "_" . $openid, $mailIds[$i]);
            }
        }

        $this->OutputJson(0, "success, get all mails", $mails);
    }

    public function test() {


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