<?php

namespace App\Actions\Fortify;

use App\Models\Employer;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateEmployerProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given employer's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update($employer, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('employers')->ignore($employer->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $employer->updateProfilePhoto($input['photo']);
        }

        if ($input['email'] !== $employer->email &&
            $employer instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($employer, $input);
        } else {
            $employer->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
            ])->save();
        }
    }

    /**
     * Update the verified employer's profile information.
     */
    protected function updateVerifiedUser(Employer $employer, array $input): void
    {
        $employer->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $employer->sendEmailVerificationNotification();
    }
}
