<?php
/**
 * Created by PhpStorm.
 * User: kaidi
 * Date: 15-6-11
 * Time: 下午10:21
 */

namespace models\peas;


class CommentPea {
    private $id;

    private $uid;

    private $bid;

    private $postDate;

    private $content;

    private $likeNum;

    function __construct($id, $uid, $bid, $postDate, $content, $likeNum)
    {
        $this->id = $id;
        $this->uid = $uid;
        $this->bid = $bid;
        $this->postDate = $postDate;
        $this->content = $content;
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
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param mixed $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return mixed
     */
    public function getBid()
    {
        return $this->bid;
    }

    /**
     * @param mixed $bid
     */
    public function setBid($bid)
    {
        $this->bid = $bid;
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