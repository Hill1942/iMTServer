<?php
/**
 * Author:  kaidi - ykdacd@outlook.com
 * Version: 
 * Date:    02/13, 2015
 */

namespace models\peas;


use core\Pea;

class BlogPea extends Pea{
    private $id;

    private $username;

    private $avatar;

    private $postDate;

    private $content;

    private $shareNum;

    private $commentNum;

    private $likeNum;

    function __construct($id, $username, $avatar, $postDate, $content, $shareNum, $commentNum, $likeNum)
    {
        $this->id       = $id;
        $this->username = $username;
        $this->avatar = $avatar;
        $this->postDate = $postDate;
        $this->content = $content;
        $this->shareNum = $shareNum;
        $this->commentNum = $commentNum;
        $this->likeNum = $likeNum;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
    public function getPostDate()
    {
        return $this->postDate;
    }

    /**
     * @param mixed $postDate
     */
    public function setPostDate($postDate)
    {
        $this->postDate = $postDate;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getShareNum()
    {
        return $this->shareNum;
    }

    /**
     * @param mixed $shareNum
     */
    public function setShareNum($shareNum)
    {
        $this->shareNum = $shareNum;
    }

    /**
     * @return mixed
     */
    public function getCommentNum()
    {
        return $this->commentNum;
    }

    /**
     * @param mixed $commentNum
     */
    public function setCommentNum($commentNum)
    {
        $this->commentNum = $commentNum;
    }

    /**
     * @return mixed
     */
    public function getLikeNum()
    {
        return $this->likeNum;
    }

    /**
     * @param mixed $likeNum
     */
    public function setLikeNum($likeNum)
    {
        $this->likeNum = $likeNum;
    }




}