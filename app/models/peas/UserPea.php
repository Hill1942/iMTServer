<?php
/**
 *
 * Author:  kaidi - ykdacd@outlook.com
 * Version: 
 * Date:    01/13, 2015
 */

namespace models\peas;

use core\Pea;

class UserPea extends Pea {

    private $email;

    private $username;

    private $avatar;

    private $password;

    private $intro;

    private $postNum;

    private $followers;

    private $follows;

    function __construct($email, $username, $avatar, $password, $intro, $postNum, $followers, $follows)
    {
        $this->email = $email;
        $this->username = $username;
        $this->avatar = $avatar;
        $this->password = $password;
        $this->intro = $intro;
        $this->postNum = $postNum;
        $this->followers = $followers;
        $this->follows = $follows;
    }


    /**
     * @return array
     */
    public function getData() {
        return array(
            'username'  => $this->username,
            'email'     => $this->email,
            'password'  => $this->password,
            'avatar'    => $this->avatar,
            'intro'     => $this->intro,
            'postNum'   => $this->postNum,
            'followers' => $this->followers,
            'follows'   => $this->follows,
        );
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getIntro()
    {
        return $this->intro;
    }

    /**
     * @param mixed $intro
     */
    public function setIntro($intro)
    {
        $this->intro = $intro;
    }

    /**
     * @return mixed
     */
    public function getPostNum()
    {
        return $this->postNum;
    }

    /**
     * @param mixed $postNum
     */
    public function setPostNum($postNum)
    {
        $this->postNum = $postNum;
    }

    /**
     * @return mixed
     */
    public function getFollowers()
    {
        return $this->followers;
    }

    /**
     * @param mixed $followers
     */
    public function setFollowers($followers)
    {
        $this->followers = $followers;
    }

    /**
     * @return mixed
     */
    public function getFollows()
    {
        return $this->follows;
    }

    /**
     * @param mixed $follows
     */
    public function setFollows($follows)
    {
        $this->follows = $follows;
    }


}