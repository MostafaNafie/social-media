<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Resources\UserResource;
use App\Jobs\SendLoggedUserEmail;
use App\Mail\UserLoggedMail;
use Illuminate\Support\Facades\Mail;
use Psr\Http\Message\ServerRequestInterface;

class LoginController extends AuthController
{
    public function login(ServerRequestInterface $request)
    {
        //validation
        $rules = [
            'grant_type' => 'required',
            'client_id' => 'required|exists:oauth_clients,id',
            'client_secret' => 'required|exists:oauth_clients,secret',
            'username' => 'required',
            'password' => 'required',
        ];
        $data = $this->isValid($request, $rules);

        // get token
        $response = $this->accessTokenService->issueToken($request);
        $tokenData = json_decode($response->getContent(), true);
        $tokenData['user'] = new UserResource(
            \App\Models\User::query()
                ->where('email', $data['username'])
                ->firstOrFail()
        );

        // Send email to user
//        Mail::to($data['username'])->send(new UserLoggedMail());
        dispatch(new SendLoggedUserEmail($data['username']));
        return $this->ok($tokenData);
    }
}
