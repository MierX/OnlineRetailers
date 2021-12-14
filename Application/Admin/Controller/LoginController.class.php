<?php

namespace Admin\Controller;

use Think\Controller;
use Think\Verify;

class LoginController extends Controller
{
    protected $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = D('Admin');
    }

    public function chkcode()
    {
        $Verify = new Verify([
            'fontSize' => 30,    // 验证码字体大小
            'length' => 2,     // 验证码位数
            'useNoise' => TRUE, // 关闭验证码杂点
        ]);
        $Verify->entry();
    }

    public function login()
    {
        if (IS_POST) {
            // 接收表单并且验证表单
            if ($this->model->validate($this->model->_login_validate)->create()) {
                if ($this->model->login()) {
                    $this->success('登录成功!', U('Index/index'));
                    exit;
                }
            }
            $this->error($this->model->getError());
        }
        $this->display();
    }

    public function logout()
    {
        $this->model->logout();
        redirect('login');
    }
}