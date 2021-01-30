<?php
namespace Fitbit\API;

use League\OAuth2\Client\Token\AccessToken as AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait as BearerAuthorizationTrait;
use League\OAuth2\Client\Provider\AbstractProvider as AbstractProvider;
use Psr\Http\Message\ResponseInterface;
use stdClass;

/**
 * Fitbit OAuth
 * The Fitbit implementation of the OAuth client
 *
 * @see: https://github.com/thephpleague/oauth2-client
 * @author James Van Hinsbergh
 */
class OAuth extends AbstractProvider
{
    use BearerAuthorizationTrait;

    public $scopes = ['write'];
    public $responseType = 'json';

    /**
     * @see AbstractProvider::urlAuthorize
     */
    public function urlAuthorize()
    {
        return 'https://www.fitbit.com/oauth2/authorize';
    }

    /**
     * @see AbstractProvider::urlAccessToken
     */
    public function urlAccessToken()
    {
        return 'https://api.fitbit.com/oauth2/token';
    }

    /**
     * @see AbstractProvider::urlUserDetails
     */
    public function urlUserDetails()
    {
        return 'https://api.fitbit.com/1/user/-/profile.json';
    }

    /**
     * @see AbstractProvider::userDetails
     */
    public function userDetails($response)
    {
        $user = new stdClass;

        $user->uid = $response->id;
        $user->name = implode(' ', [$response->firstname, $response->lastname]);
        $user->firstName = $response->firstname;
        $user->lastName = $response->lastname;
        $user->email = $response->email;
        $user->location = $response->country;
        $user->imageUrl = $response->profile;
        $user->gender = $response->sex;

        return $user;
    }

    /**
     * @see AbstractProvider::userUid
     */
    public function userUid($response)
    {
        return $response->id;
    }

    /**
     * @see AbstractProvider::userEmail
     */
    public function userEmail($response)
    {
        return isset($response->email) && $response->email ? $response->email : null;
    }

    /**
     * @see AbstractProvider::userScreenName
     */
    public function userScreenName($response)
    {
        return implode(' ', [$response->firstname, $response->lastname]);
    }

    /**
     * @see AbstractProvider::getBaseAuthorizationUrl
     */
    public function getBaseAuthorizationUrl()
    {
        return 'https://www.fitbit.com/oauth2/authorize';
    }

    /**
     * @see AbstractProvider::getBaseAccessTokenUrl
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return 'https://api.fitbit.com/oauth2/token';
    }

    /**
     * @see AbstractProvider::getResourceOwnerDetailsUrl
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return '';
    }

    /**
     * @see AbstractProvider::getDefaultScopes
     */
    protected function getDefaultScopes()
    {
        return ['write'];
    }

    /**
     * @see AbstractProvider::checkResponse
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
    }

    /**
     * @see AbstractProvider::createResourceOwner
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
    }
}
