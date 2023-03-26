<?php
namespace lbs\auth\services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException ;
use Firebase\JWT\BeforeValidException;

class AuthService{
    
    private $secret = "ptIYUZhKW%KFzFGBfIMzr1ptfr%Izvq8";

    public function signin($connection ,$users){
        try {
            $users=sscanf($users, "Basic %s")[0];
            $users=base64_decode($users);
            $users=explode(":",$users);
            
            $username=$users[0];
            $password=$users[1];

            $user = $connection->users->authLbs->findOne(['usermail' => $username]);
            if ($user == null)
                throw new \Exception("User not found", 404);

            if (!password_verify($password, $user->userpswd)) 
                throw new \Exception("Wrong password", 401);


            $payload = [ 
                'iss'=>'http://auth.myapp.net',
                'aud'=>'http://api.myapp.net',
                'iat'=>time(), 'exp'=>time()+3600,
                'uid' => $user['usermail'],
                'lvl' => $user['userlevel'],
                'username' => $user['username']
            ];
            $token = JWT::encode( $payload, $this->secret, 'HS512' );

            return [
                "token"=>$token,
                "refresh"=>$user["refresh_token"]
            ];


        } catch (Exception $e) {
              return $e->getMessage();
        }
    }

    public function verifyToken($tokenstring){

        try {

            $token = JWT::decode($tokenstring, new Key($this->secret,'HS512')) ;

            return [
                "usermail" => $token->uid,
                "username" => $token->username,
                "userlevel" => $token->lvl,
            ];

        } catch (ExpiredException | SignatureInvalidException | BeforeValidException | \UnexpectedValueException | Exception $e) { 

            return $e->getMessage();

        }
    }
}