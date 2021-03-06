<?php
/**
 * Created by PhpStorm.
 * User: Prophet
 * Date: 2016/10/18
 * Time: 22:33
 */

namespace Home\Controller;
use Think\Controller;
import('Org.Im.rongcloud');

class RongController extends Controller
{
    private static $APPkey = "kj7swf8o7whu2";
    private static $APPSecret = "KlcwUbhosHi34";
    //Im类库引用

    private function imToken($status)
    {
        if($status['code'] == '200')
        {
            //获取用户
            $db = M('user');
            $id = session('userid');
            $user = $db->WHERE('id = ' . $id)->find();
            $name = $user['name'];
            $pic = $user['pic'];

            $rongCloud = new \RongCloud($this::$APPkey, $this::$APPSecret);
            $token = $rongCloud->user()->getToken($id, $name, $pic);
            $data['id'] = $id;
            $data['name'] = $name;
            $data['pic'] = $pic;
            $data['token'] = json_decode($token,true)['token'];
            $this->ajaxReturn($data);
        }
        else
        {
            $this -> ajaxReturn(array('error' => '06'));
        }
    }

    public function check()
    {
        $id = session('userid');
        if (!session('?userid'))
        {
            $this -> ajaxReturn(array('error' => '06'));
        }
        $rongcloud = new \RongCloud($this::$APPkey,$this::$APPSecret);
        $result = $rongcloud -> user() -> checkOnline($id);
        $result = json_decode($result,true);
        $this ->imToken($result);
    }
}