<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Hash;

class UsersController extends Controller
{
    //注册
    public function getRegister () {
        $message="欢迎注册！";
        return view ('users.register', compact('message'));
    }

    //注册表单提交
    public function postCreate (Request $request) {
        //表单验证
        /*
         *  required:必填的，不能为空
            alpha:字母
            email:邮件格式
            unique:users:唯一，参考users表的设置
            alpha_num:字母或数字
            between:长度位于哪两个数字之间
            confirmed:需要确认的
         * */
        $this -> validate ($request, [
            'username' => 'required|alpha_num|between:3,20|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|between:6,20|confirmed',
            'password_confirmation' => 'required|between:6,20'
        ]);
        $user = new User;
        $user->username = $request['username'];
        $user->email = $request['email'];
        $user->password = Hash::make($request['password']);
        $user->save();
        //$message = "注册成功，请重新登录";
        //登录获取session
        Auth :: attempt ([
            'username'=>$request['username'],
            'password'=>$request['password']
        ]);
        return view ('users/moreinfo');

    }


    public function getLogin () {
        $message = "";
        return view ('users.login', compact('message'));
    }

    public function postSignin (Request $request) {
        if (Auth :: attempt ([
            'username'=>$request['name_mail'],
            'password'=>$request['password']
        ]) ||
            Auth :: attempt ([
                'email'=>$request['name_mail'],
                'password'=>$request['password']
            ])
        ) {
            //重定向到个人中心，未实现
            return redirect ('users/success');
        }
        else {
            $message = "用户名或密码错误";
            return view ('users/login', compact('message'));
        }

    }

    //登陆成功
    public function getSuccess () {
        return view ('users.success');
    }


    //填写更多个人信息
    public function getMoreinfo () {
        return view ('users.moreinfo');
    }

    public function postUpdate (Request $request) {
        $this -> validate ($request, [
            'sex' => 'required',
            'age' => 'required',
            'birthday' => 'required|date',
            'place' => 'required'
        ]);


        $user = Auth::user();
        $user -> sex = $request['sex'];
        $user -> age = $request['age'];
        $user -> birthday = $request['birthday'];
        $user -> place = $request ['place'];
        $user -> save();
        return redirect ('/users/profile');
    }

    public function getLogout () {
        if (Auth::check()) {
            Auth::logout();
        }
        return redirect ('users/login');
    }

    public function getProfile () {
        if (Auth::check()) {
            $user = Auth::user();
            return view ('users.profile', compact ('user'));
        }
    }
}
