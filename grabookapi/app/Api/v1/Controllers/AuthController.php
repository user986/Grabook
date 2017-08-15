<?php

namespace App\Api\v1\Controllers;
use JWTAuth;
//use Request;
use App\Models\User;
use App\Models\Recruiter;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
/**
 * Class 
 * @package App\Http\Controllers\Api
 */
class AuthController extends Controller{
  /**
   * calls parent constructor and call jwt.auth middleware
   */
	public function __construct()
    {
       $this->currentDateTime = $this->currentDateTime(); 

    }
  /**
   * Return a JWT ( JSON Web Token )
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function authenticate(Request $request)
  {

    $credentials = request()->only('email', 'password');
    // /dd($credentials);
    try{
        if(! $token = JWTAuth::attempt($credentials)){
            return $this->makeResponse('invalid_credentials',401);
        }else{
          $user =  User::select(['email','RecruiterID','UserID'])->where('email','=',$credentials['email'])->first();

          $user->DayViewStartTime = $this->currentDateTime;
          $user->save();

          $userDetail = $user->toArray();
          $userDetail['person']  = $user->recruiter->person;

          $user =  User::select(['email','RecruiterID','UserID'])->where('email','=',$credentials['email'])->with('recruiter.person')->first()->toArray();
          $userDetails = array();
          $userDetails['userDetails'] = $userDetail;
          $userDetails['userDetails']['token'] = $token;
          return $this->makeResponse($userDetails);
        }
    }
    catch(JWTException $e){
        return \Response::json(['error' => 'could_not_create_token'], 500);
    }
    return $this->makeResponse($token);
  }

  public function changePassword(Request $request)
  {
      $credentials =  request()->only('old_password','new_password');
      $user = User::select('email','RecruiterID')->where('UserID',$request->attributes->get('user')['UserID'])->first();
      if (!JWTAuth::attempt(array('email'=>$user['email'],'password'=>$credentials['old_password']))) {
          return $this->makeResponse('incorrect_current_password',422);
      }else{

          if((isset($credentials['old_password']) && !empty($credentials['old_password']))){
              $bcrypt = bcrypt($credentials['old_password']);
              $user->password = $bcrypt;
              $user->save();

              /* Insert into Recruiter Table */
              $recruiter = Recruiter::find($user->RecruiterID);
              $recruiter->ModifiedByID = $request->attributes->get('user')['UserID'];
              $recruiter->ModifiedDate = $this->currentDateTime;
              $recruiter->EchoSignPassword = $bcrypt;  
              $recruiter->save();
          }
          return $this->makeResponse('Password Updated Successfully');
      }
  }
}