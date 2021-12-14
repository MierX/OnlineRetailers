<?php

namespace Admin\Model;

use Think\Model;
use Think\Verify;

class MemberModel extends Model
{
    protected $insertFields = ['username', 'password', 'cpassword', 'chkcode', 'must_click'];
    protected $updateFields = ['id', 'username', 'password', 'cpassword'];

    // 添加和修改会员时使用的表单验证规则
    protected $_validate = [
        ['must_click', 'require', '必须同意注册协议！', 1, 'regex', 3],
        ['username', 'require', '用户名不能为空！', 1, 'regex', 3],
        ['username', '1,30', '用户名的值最长不能超过 30 个字符！', 1, 'length', 3],
        ['password', 'require', '密码不能为空！', 1, 'regex', 1],
        ['password', '6,20', '密码的值最长不能超过 6-20 个字符！', 1, 'length', 3],
        ['cpassword', 'password', '两次密码输入不一致！', 1, 'confirm', 3],
        ['username', '', '用户名已经存在！', 1, 'unique', 3],
        ['chkcode', 'require', '验证码不能为空！', 1],
        ['chkcode', 'check_verify', '验证码不正确！', 1, 'callback'],
    ];

    // 为登录的表单定义一个验证规则 
    public $_login_validate = [
        ['username', 'require', '用户名不能为空！', 1],
        ['password', 'require', '密码不能为空！', 1],
        ['chkcode', 'require', '验证码不能为空！', 1],
        ['chkcode', 'check_verify', '验证码不正确！', 1, 'callback'],
    ];

    // 验证验证码是否正确
    function check_verify($code, $id = '')
    {
        $verify = new Verify();
        return $verify->check($code, $id);
    }

    public function login($needPassword = TRUE)
    {
        // 从模型中获取用户名和密码
        $username = $this->username;
        if ($needPassword) {
            $password = $this->password;
        }

        // 先查询这个用户名是否存在
        $user = $this
            ->field('id,username,password,jifen')
            ->where(['username' => ['eq', $username]])
            ->find();
        if ($user) {
            if ($needPassword) {
                if ($user['password'] == md5($password)) {
                    // 如果是QQ登录就绑定openid
                    if (isset($_SESSION['openid'])) {
                        $this->where(['id' => $user['id']])->setField('openid', $_SESSION['openid']);
                        unset($_SESSION['openid']);
                    }

                    // 登录成功存session
                    session('m_id', $user['id']);
                    session('m_username', $user['username']);
                    session('face', '/Public/Home/images/user1.gif');

                    // 计算当前会员级别ID并存SESSION
                    $mlModel = D('member_level');
                    $levelId = $mlModel
                        ->field('id')
                        ->where([
                            'jifen_bottom' => ['elt', $user['jifen']],
                            'jifen_top' => ['egt', $user['jifen']],
                        ])
                        ->find();
                    session('level_id', $levelId['id']);

                    // move CartData in cart to db
                    $cartModel = D('Home/Cart');
                    $cartModel->moveDataToDb();
                    return TRUE;
                } else {
                    $this->error = '密码不正确！';
                    return FALSE;
                }
            } else {
                // 登录成功存session
                session('m_id', $user['id']);
                session('m_username', $user['username']);
                session('face', '/Public/Home/images/user1.gif');

                // 计算当前会员级别ID并存SESSION
                $mlModel = D('member_level');
                $levelId = $mlModel
                    ->field('id')
                    ->where([
                        'jifen_bottom' => ['elt', $user['jifen']],
                        'jifen_top' => ['egt', $user['jifen']],
                    ])
                    ->find();
                session('level_id', $levelId['id']);

                // move CartData in cart to db
                $cartModel = D('Home/Cart');
                $cartModel->moveDataToDb();
                return TRUE;
            }
        } else {
            $this->error = '用户名不存在！';
            return FALSE;
        }
    }

    protected function _before_insert(&$data, $options)
    {
        $data['password'] = md5($data['password']);
        // 如果是QQ登录就绑定openid
        if (isset($_SESSION['openid'])) {
            $data['openid'] = $_SESSION['openid'];
            unset($_SESSION['openid']);
        }
    }

    public function logout()
    {
        session(null);
    }
    /************************************ 其他方法 ********************************************/
} 