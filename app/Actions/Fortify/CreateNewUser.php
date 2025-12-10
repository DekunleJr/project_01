<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:15'],
            'nin' => ['required', 'string', 'max:11'],
            'bvn' => ['required', 'string', 'max:11'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        return User::create([
            'firstname' => $input['firstname'],
            'lastname' => $input['lastname'],
            'number' => $input['number'],
            'nin' => $input['nin'],
            'bvn' => $input['bvn'],
            'email' => $input['email'],
            'password' => $input['password'],
        ]);
    }
}
