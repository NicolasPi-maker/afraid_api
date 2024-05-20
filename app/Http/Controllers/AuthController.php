<?php

namespace App\Http\Controllers;

use App\Helpers\AuthHelper;
use DateTime;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {}

    public function signUp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'username' => 'required|string',
        ]);
        $password = $request->get('password');
        $request->merge([
            'password' => Hash::make($password),
        ]);
        try {
            User::create($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur créé avec succès !',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $email = $request->get('email');
        $password = $request->get('password');
        $user = User::where('email',$email)->first();
        if ($user && Hash::check($password, $user->password)) {
            $token = AuthHelper::generateAccessToken($user->toArray());
            $refreshToken = AuthHelper::generateRefreshToken($user->toArray());
            return response()->json([
                'success' => true,
                'token' => $token,
                'refreshToken' => $refreshToken,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Email ou mot de passe incorrect !',
            ], 401);
        }
    }

    public function me(Request $request): JsonResponse
    {
	    $token = $request->bearerToken();
	    if ($token) {
		    try {
			    $decodedUser = AuthHelper::getUserFromToken($token);
			    if (isset($decodedUser->email)) {
					    $email = $decodedUser->email;
					    $user = User::where('email', $email)->with('role')->first();
					    return response()->json([
						    'success' => true,
						    'user' => $user,
					    ]);
				    }
		    } catch (Exception $e) {
			    return response()->json([
				    'success' => false,
				    'message' => 'Token invalide !',
			    ], 401);
		    }
	    }
	    return response()->json([
		    'success' => false,
		    'message' => 'Une erreur s\'est produite !',
		    'token' => $token,
	    ], 500);
    }

    public function refresh(Request $request): JsonResponse
    {
        $token = $request->bearerToken();
        if ($token) {
            try {
                $decoded = JWT::decode($token, new Key(AuthHelper::getKey(), 'HS256'));
                if (isset($decoded->user->email)) {
                      $email = $decoded->user->email;
                      $user = User::where('email', $email)->with('role')->first();
                      if ($user) {
                          $newToken = AuthHelper::generateAccessToken($user->toArray());
                          return response()->json([
                              'success' => true,
                              'token' => $newToken,
                          ]);
                      }
                }
            } catch (Exception $e) {
               return response()->json([
                  'success' => false,
									'message' => 'Token invalide !',
								], 401);
            }
        }
        return response()->json([
            'success' => false,
            'message' => 'Accès refusé !',
        ], 401);
    }
}
