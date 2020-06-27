<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use App\TokenStore\TokenCache;
use Auth;
use App\User;
use Hash;

class MsGraphLoginController extends Controller
{
  public function signin()
  {
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

    $authUrl = $oauthClient->getAuthorizationUrl();

    // Save client state so we can validate in callback
    session(['oauthState' => $oauthClient->getState()]);

    // Redirect to AAD signin page
    return redirect()->away($authUrl);
  }

  public function callback(Request $request)
  {
    // Validate state
    $expectedState = session('oauthState');
    $request->session()->forget('oauthState');
    $providedState = $request->query('state');

    if (!isset($expectedState) || !isset($providedState) || $expectedState != $providedState) {
      return redirect('/')
        ->with('error', 'Invalid auth state')
        ->with('errorDetail', 'The provided auth state did not match the expected value');
    }

    // Authorization code should be in the "code" query param
    $authCode = $request->query('code');
    if (isset($authCode)) {
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
        // Make the token request
        $accessToken = $oauthClient->getAccessToken('authorization_code', [
          'code' => $authCode
        ]);

        $graph = new Graph();
        $graph->setAccessToken($accessToken->getToken());

        $userGraph = $graph->createRequest('GET', '/me')
          ->setReturnType(Model\User::class)
          ->execute();

        // $id = $user->getId();

        $tokenCache = new TokenCache();
        $tokenCache->storeTokens($accessToken, $userGraph);
        $userData = $this->userFindorCreate($userGraph);

        Auth::login($userData, true);
        
        return redirect('/');
      }
      catch (League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        return redirect('/')
          ->with('error', 'Error requesting access token')
          ->with('errorDetail', $e->getMessage());
      }
    }

    return redirect('/')
      ->with('error', $request->query('error'))
      ->with('errorDetail', $request->query('error_description'));
  }

  public function userFindorCreate($userGraph){
    $user = User::where('email', $userGraph->getMail())->first();
    if(!$user){
        $user = new User;
    $user->name = $userGraph->getDisplayName();
    $user->email = $userGraph->getMail();
    $user->password= Hash::make('the-password-of-choice');
    // $user->name= $username;
    // $user->email= $username."@ksau-hs.edu.sa";
    // $user->password= Hash::make('the-password-of-choice');
    $user->save();
    $user->assignRole('enduser');
    }
    return $user;
}

  public function signout()
  {
    $user = Auth::user();
    $tokenCache = new TokenCache();
    $tokenCache->clearTokens($user);
    return redirect('/');
  }

  public function usersList(Request $request)
  {
        // Get the access token from the cache
        $tokenCache = new TokenCache();
        $accessToken = $tokenCache->getAccessToken();
    
        // Create a Graph client
        $graph = new Graph();
        $graph->setAccessToken($accessToken);

        $userParams = $request->q;
        $queryParams = array(
          '$filter' => "startswith(displayName,'". $userParams ."')  or startswith(mail,'". $userParams ."')",
          // '$orderby' => 'createdDateTime DESC'
        );
    
        // Append query parameters to the '/me/events' url
        $getEventsUrl = '/users?'.http_build_query($queryParams);
    
        $events = $graph->createRequest('GET', $getEventsUrl)
          ->setReturnType(Model\Event::class)
          ->execute();
          return response()->json($events);

  }
}