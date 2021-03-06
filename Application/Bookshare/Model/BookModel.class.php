<?php

namespace Bookshare\Model;
use Think\Model\RelationModel;


class BookModel extends RelationModel {

    /* 书籍模型自动验证 */
    protected $_validate = array(
        
        array('name', '2,30', -1, self::EXISTS_VALIDATE, 'length'), //书名长度不合法
        array('intro', '2,500', -2, self::EXISTS_VALIDATE, 'length'), //简介长度不合法
        array('cate', 'require', -3, self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('url', 'require', -4, self::MUST_VALIDATE, 'regex', self::MODEL_BOTH)

    );
    protected $_auto = array(
        array('uid', 'is_login', self::MODEL_INSERT, 'function'),
        array('create_time', NOW_TIME, self::MODEL_INSERT)
    );
    public function showRegError($code = 1){
        switch ($code) {
            case -1:  $error = '书名长度必须在2-30个字符之间！'; break;
            case -2:  $error = '简介内容长度必须在2-500个字符之间！'; break;
            case -3:  $error = '请选择分类！'; break;
            case -4:  $error = '请上传文件'; break;
            default:  $error = '未知错误';
        }
        return $error;
    } 
    
    /**
     * 上传一本书籍
     * @param  string $username 书名
     * @param  string $password 简介
     * @param  boolean $safety  分类
     * @return integer          注册成功-书籍id，注册失败-错误编号
     */
    public function register($name, $intro, $cate,$url){
        $data = array();
        if(isset($name)){   // 书名
            $data['name']   = $name;
        }
        if(isset($intro)){   // 简介
            $data['intro']   = $intro;
        }
        if(isset($cate)){   // 分类
            $data['cate']   = $cate;
        }
        if(isset($url)){   // 保存路径
            $data['url']   = $url;
        }
        
        /* 添加用户 */
        if($this->create($data)){
            $bid = $this->add();
            return $bid ? $bid : 0; //0-未知错误，大于0-注册成功
        } else {
            return $this->getError(); //错误详情见自动验证注释
        }
    }
    
}
