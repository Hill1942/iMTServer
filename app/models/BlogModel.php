<?php
/**
 *
 * Author:  kaidi - ykdacd@outlook.com
 * Version: 
 * Date:    01/13, 2015
 */

namespace models;

use core\Model;
use daos\BlogDao;
use daos\UserDao;
use models\peas\BlogPea;


class BlogModel extends Model {

    /**
     * @param $content
     * @param $uid
     */
    public function postBlog($content, $uid) {

        $data = array(
            'uid'         => $uid,
            'content'     => $content,
            'share_num'   => 0,
            'comment_num' => 0,
            'like_num'    => 0
        );

        BlogDao::postBlog($data);
    }

    public function getBlog($pid) {
        $data = BlogDao::getBlog($pid);
        $blogItem = new BlogPea(
            $data[0]->id,
            $data[0]->username,
            $data[0]->avatar,
            $data[0]->postDate,
            $data[0]->content,
            $data[0]->shareNum == 0 ? ""   : $data[0]->shareNum,
            $data[0]->commentNum == 0 ? "" : $data[0]->commentNum,
            $data[0]->likeNum == 0 ? ""    : $data[0]->likeNum
        );
        return $blogItem;
    }

    /**
     * @param $num
     * @return array
     */
    public function getNewestBlog($num) {
        $data  = BlogDao::getNewestBlog($num);
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

    public function updateCommentNum($pid, $num) {
        BlogDao::updateCommentNum($pid, $num);
    }

    public function increaseCommentNum($pid) {
        $blogId = intval($pid);
        $data = BlogDao::getBlog($blogId);
        $oldNum = $data[0]->commentNum;
        $newNum = $oldNum + 1;
        BlogDao::updateCommentNum($pid, $newNum);
    }

}

