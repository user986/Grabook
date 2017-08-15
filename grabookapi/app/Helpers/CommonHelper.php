<?php

namespace App\Helpers;

use Carbon\Carbon;
use Exception;
trait CommonHelper {

    function currentDateTime() {
        return Carbon::now()->toDateTimeString();
    }

    /**
     * This function convert a date string into application's date format
     *
     * @param  type string $date 
     * @return string
     * @author Icreon Tech -Dev2.
     */
    function outputDateFormat($date, $format = null) {
        if(empty($date)){
            return '';
        }
        if(!$format){
            $format = 'm/d/Y';
        }
        if ($date == config('custom.null_date') || $date == '0000-00-00') {
            return '';
        }
        if(!($date instanceof Carbon)) {
            if(is_numeric($date)) {
                 // Assume Timestamp
                 $date = Carbon::createFromTimestamp($date);
            } else {
                $date = Carbon::parse($date);
            }
            return Carbon::parse($date)->format($format);
        }
        return $date->setTimezone('US/Eastern')->format($format);
    }


    /**
     * This function convert a amount in money format
     *
     * @param  type string $date 
     * @return string
     * @author Icreon Tech -Dev2.
     */
    function moneyFormat($amount) {
        try {
          return number_format($amount, 2, '.', ',');
        } catch (Exception $e) {
          return $amount;
        }
    }

    /**
     * This function convert a amount in money format
     *
     * @param  type string $date 
     * @return string
     * @author Icreon Tech -Dev2.
     */
    function numberFormat($amount) {
        try {
          return number_format($amount);
        } catch (Exception $e) {
          return $amount;
        }
    }

    function daysDiff($fromDate, $toDate = null){
        if(empty($fromDate)){
            return 0;
        }
        if(!empty($toDate)){
            $now = strtotime($toDate);
        }else{
            $now = strtotime(date('Y-m-d'));
        }
        $your_date = strtotime($fromDate);
        $datediff = $your_date -  $now;
        return (floor($datediff/(60*60*24))>0)? floor($datediff /(60*60*24)):0;
    }



      public function makeResponse($data,$apiStatusCode = NULL)
      {

            if (!empty($apiStatusCode) && $apiStatusCode != 200) {
                switch($apiStatusCode):
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
                            'message' => config('message.'.$data),
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
