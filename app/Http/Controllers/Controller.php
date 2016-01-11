<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $time;		//时间变量，存储时间datetime： 2015-07-01 12：12：12
    public $error;		//报错数组，存储通用的和常规报错参数
    protected $lang;	//获取接口语言

    public function __construct(Request $request){
        $this->time = date('Y-m-d H:i:s', time());
        $this->error = $this->getErrorList();

        $lang = '';
        if( $request->has('lang') ) {
            $lang = $request->input('lang');
            $lang = trim( $lang );
        }
        $this->lang = ( 'en'==$lang ) ? $lang : 'zh';
    }

    /**
     * 定义响应数据规范
     * 语言:zh[中文简体]、en[英文]
     * @param 	string	$code 	状态码
     * @param 	string	$msg 	状态码
     * @param 	array	$data 	状态码
     * @return	array
     */
    public function response( $code, $msg = null, $data = array() ) {
        $code = (int)$code;
        if( null == $msg ) {
            $errList = $this->getErrorList();
            if( !array_key_exists( $code, $errList ) ) {
                return 'key not exist in config';
            }
            $msg = $errList[ $code ][ $this->lang ];
        }

        $ret = array(
            'code' => $code,
            'msg' => "{$msg}",
        );

        if( null != $data ) {
            $ret[ 'data' ] = $data;
        }

        return $ret;
    }

    /*
	 * 定义通用报错列表
	*
	* @return	array
	*/
    public function getErrorList(){
        return array(
            0=>array("en"=>"Failed", "zh"=>"失败"),
            1=>array("en"=>"Success", "zh"=>"成功"),

            /*
				|--------------------------------------------------------------------------
				| 系统级错误
				|--------------------------------------------------------------------------
				|
				| 系统级错误
				|
				*/
            10001=>array("en"=>"System error", "zh"=>"系统错误"),
            10002=>array("en"=>"Service unavailable", "zh"=>"服务暂停"),
            10003=>array("en"=>"Remote service error", "zh"=>"远程服务错误"),
            10004=>array("en"=>"IP limit", "zh"=>"IP限制"),
            10005=>array("en"=>"Param error", "zh"=>"参数错误"),
            10006=>array("en"=>"Illegal request", "zh"=>"非法请求"),
            10007=>array("en"=>"Request api not found", "zh"=>"接口不存在"),
            10008=>array("en"=>"HTTP method error", "zh"=>"请求方式错误"),
            10009=>array("en"=>"Request body length over limit", "zh"=>"请求长度超过限制"),
            10010=>array("en"=>"Invalid user", "zh"=>"不合法的用户"),
            10011=>array("en"=>"User requests out of rate limit", "zh"=>"用户请求频次超过上限"),
            10012=>array("en"=>"Request timeout", "zh"=>"请求超时"),
            10013=>array("en"=>"User doesn't exists", "zh"=>"用户不存在"),
            10014=>array("en"=>"Username has registered", "zh"=>"用户名已注册"),
            10015=>array("en"=>"No phone number","zh"=>"无电话号码"),
            10016=>array("en"=>"User has login","zh"=>"用户已登录"),
            10017=>array("en"=>"exit login fail","zh"=>"退出登录失败"),
            10018=>array("en"=>"User has not login","zh"=>"用户未登录"),
            10019=>array("en"=>"create token Failed","zh"=>"令牌未生成"),
            10020=>array("en"=>"User has not token","zh"=>"令牌无效"),
            10021=>array("en"=>"User has not token","zh"=>"交易失败"),
        );
    }
}
