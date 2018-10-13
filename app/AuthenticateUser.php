<?php 

namespace App;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthenticateUser {

	private $users;

	function __construct(UserRepository $users)
	{
		$this->users = $users;
	}

	public function execute($service, $hasCode, $listener)
	{

		if( ! $hasCode) return $this->getAuthorizationFirst($service);

		$user = $this->users->findByUsernameOrCreate($this->getSocialUser($service), $service);

		if($user)
		{
			Auth::login($user, true);

			return $listener->userHasLoggedIn($user, request()->referrerId);
		}
		return $listener->invalidUserInformation();
	}

	public function getAuthorizationFirst($service)
	{
		session(['referrerId' => request()->referrer]);
		return Socialite::driver($service)->redirect();
	}

	public function getSocialUser($service)
	{
		return Socialite::driver($service)->user();
	}

}