<?php

/**
 * Created by PhpStorm.
 * User: Kaidi
 * Date: 2016/9/8
 * Time: 17:24
 */

namespace daos;

use core\Dao;

class PlayerDao extends Dao {

    public static function UpdatePlayer($data, $where) {

        return self::$_db->update("tbPlayers", $data, $where);

        /*ksort($data);

        $fieldNames = implode(',', array_keys($data));
        $fieldValues = ':'.implode(', :', array_keys($data));

        $sql = "INSERT INTO tbPlayers($fieldNames) VALUES($fieldValues)" .
            " ON DUPLICATE KEY UPDATE iScore=" . $score;

        $stmt = self::$_db->prepare($sql);

        foreach($data as $key => $value){
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();*/
    }

    public static function CheckUserRole($channel, $openid) {
        $data = self::$_db->select(
            "SELECT * FROM tbPlayers WHERE sOpenId = :openid and sChannel = :channel",
            array(
                ":openid" => $openid,
                ':channel' => $channel)
        );
        return $data;
    }



    public static function CreateUserRole($data) {
        self::$_db->insert("tbPlayers", $data);
    }

    public static function GetTopList($channel, $num) {

        $PREFIX = "tb";

        $data = self::$_db->select("
			SELECT
			    ".$PREFIX."Players.sOpenId as openid,
				".$PREFIX."Players.sAvatar as avatar,
				".$PREFIX."Players.sNickname as nickname,
				".$PREFIX."Players.iScore as score
			FROM
				".$PREFIX."Players
			WHERE
				".$PREFIX."Players.sChannel = :channel
			ORDER BY
				iScore DESC "."limit :num",
            array(
                ":channel" => $channel,
                ':num' => $num)
        );
        return $data;
    }

    public static function InsertTest($data) {


    }

}