<?php 

namespace App\Repositories;

use App\User;

class UserRepository {

	public function findByUsernameOrCreate($userData, $service)
	{
		$user = new User();
		if(!empty($userData->email))
		{
			$user = User::updateOrCreate(['email' => $userData->email], 
										 ['password' => bcrypt('newleaf'),
									  	  'avatar_path' => $userData->avatar_original,
										  'name' => $userData->name, 
										  'email' => $userData->email, 
										  'social_service' => $service,
										  'social_id' => $userData->id
										 ]
										);	


		}
		else
		{
			
			$user = User::updateOrCreate(['social_service' => $service, 'social_id' => $userData->id], 
										 ['password' => bcrypt('newleaf'),
									  	  'avatar_path' => $userData->avatar_original,
										  'name' => $userData->name, 
										  'email' => $userData->id . '@email.com', 
										  'social_service' => $service,
										  'social_id' => $userData->id
										 ]
										);

		}

		return $user;
	}

}