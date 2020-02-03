<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{

    public function sendResponse($result, $message)
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'message' => $error,
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }

    public function user(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'access_token' => 'required'
        ]);

        $user = User::select(['name', 'email', 'created_at'])
                    ->where('name', $request->name)
                    //->where('remember_token', $request->access_token)
                    ->get();

        if($user == null || $user == []) return $this->sendError('NO USER', ['error'=>'No data fetched']);
        $success['user'] = $user->toArray();
        return $this->sendResponse($success, 'Data fetched correct.');
    }
    public function register(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users',
            'name' => 'required',
            'password' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->pasword)
        ]);

        $success['name'] = $user->toArray();
        return $this->sendResponse($success, 'User login successfully.');
    }

    public function login(Request $request){
        $IONIC_APP_ID = 4;
        $IONIC_APP_KEY = 'QcXqwHxElbtz1chMlIJZwhaAmP87V8CSVAT9a7zG';
        $IONIC_GRANT_TYPE = 'password';

        if($request->grant_type == $IONIC_GRANT_TYPE && $request->client_id == $IONIC_APP_ID && $request->client_secret == $IONIC_APP_KEY)
            if(Auth::attempt(['email' => $request->username, 'password' => $request->pasword])){ 
                $user = Auth::user(); 
                $success['access_token'] =  $user->createToken('MyApp')->accessToken; 
                $success['name'] =  $user->name;
    
                $user->remember_token = $success['access_token'];

                return $this->sendResponse($success, 'User login successfully.');
            } 
            else{ 
                return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
            } 
        else
            return $this->sendError('Bad Request.', ['error'=>'Request Parameters are worng.']);
    }
}
