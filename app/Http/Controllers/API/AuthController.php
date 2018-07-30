<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class AuthController extends Controller
{
    /**
     * @api {post} /user/signIn
     * @apiName  autheticate users
     *
     *
     * @apiParam  {String} email
     * @apiParam  {String} password
     *
     *
     * @apiSuccess (200) token
     *
     */
    public function authenticate(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        // grab credentials from the request
        $credentials = $request->only(['email', 'password']);
        $validator = Validator::make($credentials, $rules);
        if ($validator->fails()) {
            $errors = $validator->messages()->toJson();
            return response()->json(['error' => $errors], 400);
        }
        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return response([
                    'status' => 'error',
                    'error' => 'Invalid Credentials.',
                ], 400);
            }
        } catch (JWTException $e) {
            // something went wrong while attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        // all good so return the token
        return response()->json(['token' => $token], 200);

    }
    /**
     * @api {post} /user/signUp
     * @apiName register users
     *
     * @apiParam  {String} email
     * @apiParam  {String} password
     *
     *
     * @apiSuccess (201) user created
     */
    public function register(Request $request)
    {
        // validate sign up form
        $rules = [
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ];
        $input = $request->only(['email', 'password']);
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $errors = $validator->messages();
            return response()->json(['error' => $errors], 400);
        }
        // save the new user
        $user = new User([
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);
        $user->save();
        // generate token for auth
        $token = JWTAuth::attempt($input);
        return response()->json([
            'message' => 'user was created successfully!',
            'token' => $token,
        ], 201);
    }
    /**
     * @api {post} /user/logout
     *
     * @apiName logout user
     */
    public function logout()
    {
        // kill token
        JWTAuth::invalidate();
        return response([
            'status' => 'success',
            'message' => 'Logged out Successfully.',
        ], 200);
    }

    /**
     * @api {post} /user/auth/refreshToken
     *
     * @apiName refresh token
     * @apiDescription refresh token after expiration
     *
     *
     */
    public function refreshedToken()
    {
        $newToken = JWTAuth::parseToken()->refresh();
        return response([
            'status' => 'success',
            'token' => $newToken,
        ], 200);
    }

}
