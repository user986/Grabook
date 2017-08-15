<?php

namespace App\Api\v1\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Dingo\Api\Routing\Helpers;
use Carbon\Carbon;
use App\Helpers\CommonHelper;

class Controller extends BaseController {

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests,
        Helpers,
        CommonHelper;

    protected $responseArray = array();

    /**
     * make response
     *
     * @param $data
     * @return mixed
     */
    public function currentTime() {
        return Carbon::now();
    }

    /**
     * make response
     *
     * @param $data
     * @return mixed
     */
    public function makeResponse($data, $apiStatusCode = NULL) {

        if (!empty($apiStatusCode) && $apiStatusCode != 200) {
            switch ($apiStatusCode):
                case 404:
                    $response = array(
                        'statusCode' => 404,
                        'error' => TRUE,
                        'result' => array(
                            'messageCode' => 'page_not_found',
                            'message' => 'Page Not Found',
                        )
                    );
                    break;
                case 500:
                    $response = array(
                        'statusCode' => 500,
                        'error' => TRUE,
                        'result' => array(
                            'messageCode' => 'internal_server_error',
                            'message' => 'Internal Server Error',
                        )
                    );
                    break;
                default:

                    $response = array(
                        'statusCode' => $apiStatusCode,
                        'error' => TRUE,
                        'result' => array(
                            'messageCode' => $data,
                            'message' => config('message.' . $data),
                        )
                    );
                    break;
            endswitch;
        } else {
            $response = array(
                'statusCode' => 200,
                'error' => FALSE,
                'result' => $data
            );
        }
        return $this->response->array($response);
    }

}
