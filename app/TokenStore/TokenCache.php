<?php

namespace App\TokenStore;
use App\MsGraphToken;
use Auth;

class TokenCache {
  public function storeTokens($accessToken, $user) {
    // session([
    //   'accessToken' => $accessToken->getToken(),
    //   'refreshToken' => $accessToken->getRefreshToken(),
    //   'tokenExpires' => $accessToken->getExpires(),
    //   'userName' => $user->getDisplayName(),
    //   'userEmail' => null !== $user->getMail() ? $user->getMail() : $user->getUserPrincipalName()
    // ]);
            //cretate a new record or if the user id exists update record
        return MsGraphToken::updateOrCreate(['email' => $user->getMail()], [
            'user_id'       => $user->getId(),
            'access_token'  => $accessToken->getToken(),
            'email'  => $user->getMail(),
            'expires'       => $accessToken->getExpires(),
            'refresh_token' => $accessToken->getRefreshToken()
        ]);
  }

  public function clearTokens() {
    session()->forget('accessToken');
    session()->forget('refreshToken');
    session()->forget('tokenExpires');
    session()->forget('userName');
    session()->forget('userEmail');
  }

  public function getAccessToken() {
    
    $token = MsGraphToken::where('email', Auth::user()->email)->first();
    
    // // Check if tokens exist
    // if (empty(session('accessToken')) ||
    //     empty(session('refreshToken')) ||
    //     empty(session('tokenExpires'))) {
    //   return '';
    // }
    // Check if tokens exist
    if (empty($token->access_token) ||
        empty($token->refresh_token) ||
        empty($token->expires)) {
    return '';
}
    
    
    
    
    // Check if token is expired
    //Get current time + 5 minutes (to allow for time differences)
    $now = time() + 300;
    if ($token->expires <= $now) {
      // Token is expired (or very close to it)
      // so let's refresh
  
      // Initialize the OAuth client
      $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
        'clientId'                => env('OAUTH_APP_ID'),
        'clientSecret'            => env('OAUTH_APP_PASSWORD'),
        'redirectUri'             => env('OAUTH_REDIRECT_URI'),
        'urlAuthorize'            => env('OAUTH_AUTHORITY').env('OAUTH_AUTHORIZE_ENDPOINT'),
        'urlAccessToken'          => env('OAUTH_AUTHORITY').env('OAUTH_TOKEN_ENDPOINT'),
        'urlResourceOwnerDetails' => '',
        'scopes'                  => env('OAUTH_SCOPES')
      ]);
  
      try {
        $newToken = $oauthClient->getAccessToken('refresh_token', [
          'refresh_token' => $token->refresh_token
        ]);
        
        // Store the new values
        $this->updateTokens($newToken);
  
        return $newToken->getToken();
      }
      catch (League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        return '';
      }
    }
  
    // Token is still valid, just return it
    return $token->access_token;
  }

  public function updateTokens($newToken) {
    // session([
    //   'accessToken' => $accessToken->getToken(),
    //   'refreshToken' => $accessToken->getRefreshToken(),
    //   'tokenExpires' => $accessToken->getExpires()
    // ]);
                //cretate a new record or if the user id exists update record
                return MsGraphToken::updateOrCreate(['email' => Auth::user()->email], [
                  // 'user_id'       => $user->getId(),
                  'access_token'  => $newToken->getToken(),
                  // 'email'  => $user->getMail(),
                  'expires'       => $newToken->getExpires(),
                  'refresh_token' => $newToken->getRefreshToken()
              ]);
  }
}