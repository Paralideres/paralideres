<?php

/**
 * CommonService
 * @author : Tarek Monjur
 * @email : tarekmonjur@gmail.com
 */

namespace App\Service;


trait CommonService
{


    /**
     * @description set the ajax response message
     * @param array $message
     * @return \Illuminate\Http\JsonResponse
     * @author Tarek Monjur
     */
    protected function setResponse($data,$status,$type,$code,$title,$message){
        $sendData['data'] = $data;
        $sendData['status'] = $status;
        $sendData['statusType'] = $type;
        $sendData['code'] = $code;
        $sendData['title'] = $title;
        $sendData['message'] = $message;
        return response()->json($sendData,$code);
    }


}
