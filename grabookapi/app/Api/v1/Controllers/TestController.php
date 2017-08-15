<?php

namespace App\Api\v1\Controllers;
use Request;
use JWTAuth;
use App\Models\Auth AS User;
use Response;
use Exception;
/**
 * Class 
 * @package App\Http\Controllers\Api
 */
class TestController extends Controller{
  /**
   * calls parent constructor and call jwt.auth middleware
   */
	public function __construct()
    {
        
    }
  /**
   * Return a JWT ( JSON Web Token )
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function test(Request $request)
  { 

 //    try{
    
 //    $user = User::find(55)->toArray();
    
 //    return $this->response->array($user);
 //    }
 //    catch(Exception $e) {
        
 //        return $this->response->array($e->getMessage());
 //    }
 //    die;
 //    $data['data']=$user;
 //    //$data = fractal($user, new UserTransformer());
	// return response()->json($data);

    $user = JWTAuth::parseToken()->authenticate();
    dd($user);

    try{
		  return $token =JWTAuth::parseToken()->authenticate();
		}
		catch(\Tymon\JWTAuth\Exceptions\JWTException $e){//general JWT exception
        $token = 'book';
        dd($e->getMessage());
        }
			$token = JWTAuth::getToken();
		
		dd($token);
    }
}