<?php

namespace App\Http\Controllers\Wenzhi;

use App\Http\Controllers\Common\Constants;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use QcloudApi;
use Illuminate\Http\Request;

/**
 * 腾讯文智情感分析接口
 * Class WenzhiController
 * Create by Steven Guo
 * @package App\Http\Controllers\Wenzhi
 */
class WenzhiController extends Controller {

    public function __construct(Request $request){
        parent::__construct($request);
    }

    /**
     * 功能:腾讯文智情感分析
     * 调用示例:http://local.api.qcloud.com/check?content=李亚鹏挺王菲：加油！孩儿他娘。
     * 返回结果示例：
     *  {
     *      code: 0,
     *      message: "",
     *      positive: 0.994810223579,
     *      negative: 0.00518980016932
     *  }
     *
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     * @internal param content $string
     */
    public function check(Request $request){
        if(!$request->has('content')){
            return $this->response(10005);
        }

        $config = array(
            'SecretId'       => Constants::QCloud_SecretId,
            'SecretKey'      => Constants::QCloud_SecretKey,
            'RequestMethod'  => 'POST',
            'DefaultRegion'  => 'gz'
        );

        $wenzhi = QcloudApi::load(QcloudApi::MODULE_WENZHI, $config);
        $package = array("content"=>$request->input('content'));
        $a = $wenzhi->TextSentiment($package);
        Log::info($a);

        if ($a === false) {
            $error = $wenzhi->getError();
            Log::error("Error code:" . $error->getCode() . ".\n");
            Log::error("message:" . $error->getMessage() . ".\n");
            Log::error("ext:" . var_export($error->getExt(), true) . ".\n");
        }

        $resp = json_decode($wenzhi->getLastResponse());
        Log::info($wenzhi->getLastResponse());
        Log::info($wenzhi->getLastRequest());
        return response()->json($resp);
    }
}
