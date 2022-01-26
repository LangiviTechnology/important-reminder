<?php 

namespace Langivi\ImportantReminder\Services;


class TokenService
{

	public function __construct(
	) {
	}

    public function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

	public function jwtGenerate(object $payload, string $key, int $expiration = 900): string 
	{
		//build the headers
		$headers = ['alg'=>'HS256', 'typ'=>'JWT'];
		$headers_encoded = $this->base64url_encode(json_encode($headers));

		//build the payload
		$payload->expiration = time() + $expiration;
		$payload_encoded = $this->base64url_encode(json_encode($payload));
		
		//build the signature
		$signature = hash_hmac('SHA256',"$headers_encoded.$payload_encoded",$key,true);
		$signature_encoded = $this->base64url_encode($signature);

		//build and return the token
		$token = "$headers_encoded.$payload_encoded.$signature_encoded";
		return $token;		
	}

	public function jwtVerify(string $jwt, string $secret): bool
	{
		// split the jwt
		$tokenParts = explode('.', $jwt);
		$header = base64_decode($tokenParts[0]);
		$payload = base64_decode($tokenParts[1]);
		$signature_provided = $tokenParts[2];
	
		// check the expiration time - note this will cause an error if there is no 'exp' claim in the jwt
		$expiration = json_decode($payload)->expiration;
		$is_token_expired = ($expiration - time()) < 0;
	
		// build a signature based on the header and payload using the secret
		$base64_url_header = $this->base64url_encode($header);
		$base64_url_payload = $this->base64url_encode($payload);
		$signature = hash_hmac('SHA256', $base64_url_header . "." . $base64_url_payload, $secret, true);
		$base64_url_signature = $this->base64url_encode($signature);
	
		// verify it matches the signature provided in the jwt
		$is_signature_valid = ($base64_url_signature === $signature_provided);
		
		if ($is_token_expired || !$is_signature_valid) {
			return false;
		} else {
			return true;
		}
	}


    public function generateTokens($payload): object
    {
		// realize it
		// $accessSecret = $this->containerBuilder->getParameter('JWT_ACCESS_SECRET');
		// $refreshSecret = $this->containerBuilder->getParameter('JWT_REFRESH_SECRET');
		// var_dump('------------', $accessSecret, $refreshSecret,'-=--===========');
		
		$accessSecret = 'Sb2xlIjoiQWRtaW4iL';
		$refreshSecret = 'CJVc2VybmFtZSI6Ikph';
		
		$accessToken = $this->jwtGenerate($payload, $accessSecret, 60 * 30 ); // 30 min
		$refreshToken =$this->jwtGenerate($payload, $refreshSecret, 60 * 24 * 30 ); // 30 days
		
		return (object) [
			'accessToken' => $accessToken,
			'refreshToken' => $refreshToken,
		];
    }
	

	public function validationAccessToken(string $token) :bool
    {
		return true;
    }

	public function validationRefreshToken(string $token) :bool
    {
		return true;
    }

	public function findToken(string $refreshToken): string | bool
    {
		return false;
    }
	public function removeToken(string $email): string
    {
        return '';
    }
	public function saveToken(string $userId,string $refreshToken): string
    {
        return '';
    }

}
