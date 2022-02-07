<?php 

namespace Langivi\ImportantReminder\Services;


class TokenService
{
	private string $accessSecret; 
	private string $refreshSecret;

	public function __construct(
	) {
	}

	public function setAccessToken(string $accessSecret): self
	{	
		$this->accessSecret = $accessSecret;
		return $this;
	}

	public function setRefreshToken(string $refreshSecret): self
	{	
		$this->refreshSecret = $refreshSecret;
		return $this;
	}

    public function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

	public function jwtGenerate(object $payload, string $secret, int $expiration = 900): string 
	{
		//build the headers
		$headers = ['alg'=>'HS256', 'typ'=>'JWT'];
		$headers_encoded = $this->base64url_encode(json_encode($headers));

		//build the payload
		$payload->expiration = time() + $expiration;
		$payload_encoded = $this->base64url_encode(json_encode($payload));
		
		//build the signature
		$signature = hash_hmac('SHA256',"$headers_encoded.$payload_encoded",$secret,true);
		$signature_encoded = $this->base64url_encode($signature);

		//build and return the token
		$token = "$headers_encoded.$payload_encoded.$signature_encoded";
		return $token;		
	}

	public function jwtVerify(string $jwt, string $secret): object|null
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
			return null;
		} else {
			return json_decode($payload);
		}
	}


    public function generateTokens(object $payload): object
    {
		$accessToken = $this->jwtGenerate($payload, $this->accessSecret, 60 * 15 ); // 30 min
		$refreshToken =$this->jwtGenerate($payload, $this->refreshSecret, 60 * 60 * 24 * 15 ); // 30 days
		
		return (object) [
			'accessToken' => $accessToken,
			'refreshToken' => $refreshToken,
		];
    }

	public function validationAccessToken(string $token): object|null
    {
		return $this->jwtVerify($token, $this->accessSecret);
    }

	public function validationRefreshToken(string $token): object|null
    {
		return $this->jwtVerify($token, $this->refreshSecret);
    }

	public function findOne(string $refreshToken): string | bool
    {
		//TODO add search in db
		return $refreshToken;
    }
	public function removeToken(string $refreshToken): string
    {
		//TODO add  db
        return $refreshToken;
    }
	public function saveToken(string $userId,string $refreshToken): string
    {
		//TODO add  db
        return $refreshToken;
    }

}
