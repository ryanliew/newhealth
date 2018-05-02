<?php 

namespace App\Repositories;

use App\User;

class UserRepository {

	public function findByUsernameOrCreate($userData, $service)
	{
		if(!empty($userData->email))
		{
			$user = User::updateOrCreate(['social_service' => $service, 'social_id' => $userData->id], 
										 ['password' => bcrypt('123456'),
									  	  'avatar_path' => $userData->avatar_original,
										  'name' => $userData->name, 
										  'email' => $userData->email ? $userData->email : $userData->id . '@email.com', 
										  'social_service' => $service,
										  'social_id' => $userData->id
										 ]
										);
			/*$user = User::where('email', $userData->email)->first();
				
			if(!$user)
			{
				$user = User::create([
					'email' => $userData->email,
					'password' => bcrypt('123456'),
					'avatar_path' => $userData->avatar_original,
					'name' => $userData->name
				]);
			}	*/	

			return $user;
		}

		return false;
	}

}