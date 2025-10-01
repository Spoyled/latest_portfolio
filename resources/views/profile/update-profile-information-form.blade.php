<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" id="photo" class="hidden"
                            wire:model.live="photo"
                            x-ref="photo"
                            x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <x-label for="photo" value="{{ __('Photo') }}" />

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('storage/images/profile1.png') }}" class="rounded-full h-20 w-20 object-cover" alt="Profile Picture">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <x-secondary-button class="mt-2 me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Select A New Photo') }}
                </x-secondary-button>

                @if ($this->user->profile_photo_path)
                    <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Remove Photo') }}
                    </x-secondary-button>
                @endif

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('Name') }}" />
            <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" required autocomplete="name" />
            <x-input-error for="name" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Email') }}" />
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" required autocomplete="username" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <p class="text-sm mt-2">
                    {{ __('Your email address is unverified.') }}

                    <button type="button" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" wire:click.prevent="sendEmailVerification">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p class="mt-2 font-medium text-sm text-green-600">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            @endif
        </div>

        <!-- Location -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="location" value="{{ __('Location') }}" />
            <x-input id="location" type="text" class="mt-1 block w-full" wire:model="state.location" autocomplete="location" />
            <x-input-error for="location" class="mt-2" />
        </div>

        <!-- Birth Date -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="birth_date" value="{{ __('Birth Date') }}" />
            <x-input id="birth_date" type="date" class="mt-1 block w-full" wire:model="state.birth_date" />
            <x-input-error for="birth_date" class="mt-2" />
        </div>

        <!-- Summary -->
        <div class="col-span-6">
            <x-label for="summary" value="{{ __('Summary/Objective') }}" />
            <textarea id="summary" class="form-input rounded-md shadow-sm mt-1 block w-full" wire:model="state.summary"></textarea>
            <x-input-error for="summary" class="mt-2" />
        </div>

        <!-- Skills -->
        <div class="col-span-6">
            <x-label for="skills" value="{{ __('Skills (comma-separated)') }}" />
            <x-input id="skills" type="text" class="mt-1 block w-full" wire:model="state.skills_string" />
            <x-input-error for="skills" class="mt-2" />
        </div>

        <!-- Work Experience -->
        <div class="col-span-6">
            <x-label value="{{ __('Work Experience') }}" />
            <div class="space-y-2">
                @if(isset($state['work_experience']) && is_array($state['work_experience']))
                    @foreach($state['work_experience'] as $index => $job)
                        <div class="border p-2 rounded">
                            <x-input type="text" placeholder="Job Title" class="mt-1 w-full" wire:model="state.work_experience.{{$index}}.title" />
                            <x-input type="text" placeholder="Company" class="mt-1 w-full" wire:model="state.work_experience.{{$index}}.company" />
                            <x-input type="text" placeholder="Years (e.g., 2020-2022)" class="mt-1 w-full" wire:model="state.work_experience.{{$index}}.years" />
                            <textarea placeholder="Description" class="form-input rounded-md shadow-sm mt-1 w-full" wire:model="state.work_experience.{{$index}}.description"></textarea>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Education -->
        <div class="col-span-6">
            <x-label value="{{ __('Education') }}" />
            <div class="space-y-2">
                @if(isset($state['education']) && is_array($state['education']))
                    @foreach($state['education'] as $index => $edu)
                        <div class="border p-2 rounded">
                            <x-input type="text" placeholder="Degree" class="mt-1 w-full" wire:model="state.education.{{$index}}.degree" />
                            <x-input type="text" placeholder="Institution" class="mt-1 w-full" wire:model="state.education.{{$index}}.institution" />
                            <x-input type="text" placeholder="Years (e.g., 2016-2020)" class="mt-1 w-full" wire:model="state.education.{{$index}}.years" />
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>
