<?php
/**
 *
 * Author:  kaidi - ykdacd@outlook.com
 * Version:
 * Date:    06/11, 2015
 */

namespace models;

use core\Model;
use daos\BlogDao;
use daos\CommentDao;
use daos\UserDao;
use models\peas\BlogPea;
use models\peas\CommentPea;


class CommentModel extends Model {

    /**
     * @param $content
     * @param $uid
     */
    public function postBlogComment($uid, $bid, $content) {

        $data = array(
            'uid'         => $uid,
            'bid'         => $bid,
            'content'     => $content,
            'like_num'    => 0
        );

        CommentDao::postBlogComment($data);
    }

    /**
     * @param $num
     * @return array
     */
    public function getNewestComment($num, $bid) {
        $blogId = intval($bid);
        $data  = CommentDao::getNewestComment($num, $blogId);
        $comments = array();

        foreach($data as $row){
            $commentItem = new CommentPea(
                $row->id,
                $row->uid,
                $row->bid,
                $row->postDate,
                $row->content,
                $row->likeNum == 0 ? "" : $row->likeNum
            );
            array_push($comments, $commentItem);
        }

        return $comments;
    }

    /**
     * @param $num
     * @param $uid
     * @return array
     */
    public function getNewestBlogByUser($num, $uid) {
        $data = BlogDao::getNewestBlogByUser($num, $uid);
        $blogs = array();

        foreach($data as $row){
            $blogItem = new BlogPea(
                $row->id,
                "",
                "",
                $row->postDate,
                $row->content,
                $row->shareNum == 0 ? "" : $row->shareNum,
                $row->commentNum == 0 ? "" : $row->commentNum,
                $row->likeNum == 0 ? "" : $row->likeNum
            );
            array_push($blogs, $blogItem);
        }

        return $blogs;
    }


    /**
     * @param $index_str
     * @param $num
     * @return mixed
     */
    public function getNextBlog($index_str, $num) {
        $index = intval($index_str);
        $data  = BlogDao::getNextBlog($index, $num);
        $blogs = array();

        foreach($data as $row){
            $blogItem = new BlogPea(
                $row->id,
                $row->username,
                $row->avatar,
                $row->postDate,
                $row->content,
                $row->shareNum == 0 ? "" : $row->shareNum,
                $row->commentNum == 0 ? "" : $row->commentNum,
                $row->likeNum == 0 ? "" : $row->likeNum
            );
            array_push($blogs, $blogItem);
        }

        return $blogs;
    }

    /**
     * @param $index_str
     * @param $num
     * @param $uid
     * @return array
     */
    public function getNextBlogByUser($index_str, $num, $uid) {
        $index = intval($index_str);
        $data  = BlogDao::getNextBlogByUser($index, $num, $uid);
        $blogs = array();

        foreach($data as $row){
            $blogItem = new BlogPea(
                $row->id,
                "",
                "",
                $row->postDate,
                $row->content,
                $row->shareNum == 0 ? "" : $row->shareNum,
                $row->commentNum == 0 ? "" : $row->commentNum,
                $row->likeNum == 0 ? "" : $row->likeNum
            );
            array_push($blogs, $blogItem);
        }

        return $blogs;
    }

}

