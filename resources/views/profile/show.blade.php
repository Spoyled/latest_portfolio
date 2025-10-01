<x-app-layout>
    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('profile.update-profile-information-form')

                <x-section-border />
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>

                <x-section-border />
            @endif

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                

                <div class="mt-10 sm:mt-0">
                    @livewire('profile.delete-user-form')
                </div>
            @endif

            <x-form-section submit="saveProfile">
                <x-slot name="title">
                    {{ __('Profile Information') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('Update your account\'s profile information and email address.') }}
                </x-slot>

                <x-slot name="form">
                    <div class="col-span-6 sm:col-span-4">
                        <x-label for="name" value="{{ __('Name') }}" />
                        <x-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="state.name" autocomplete="name" />
                        <x-input-error for="name" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <x-label for="email" value="{{ __('Email') }}" />
                        <x-input id="email" type="email" class="mt-1 block w-full" wire:model.defer="state.email" autocomplete="email" />
                        <x-input-error for="email" class="mt-2" />
                    </div>

                    @if (Laravel\Jetstream\Jetstream::hasProfilePhotoFeature())
                        <div class="col-span-6 sm:col-span-4">
                            <x-label for="photo" value="{{ __('Photo') }}" />
                            <x-profile-photo-input id="photo" class="mt-1 block w-full" wire:model="photo" />

                            <x-input-error for="photo" class="mt-2" />
                        </div>
                    @endif
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

            <x-section-border />

            <div class="mt-10 sm:mt-0">
                <x-action-section>
                    <x-slot name="title">
                        {{ __('Generate CV') }}
                    </x-slot>

                    <x-slot name="description">
                        {{ __('Generate a professional CV from your profile information.') }}
                    </x-slot>

                    <x-slot name="content">
                        <div class="max-w-xl text-sm text-gray-600">
                            <p>
                                {{ __('Click the button to generate a DOCX version of your CV. Make sure your profile information is up-to-date.') }}
                            </p>
                        </div>

                        <div class="mt-5">
                            <form action="{{ route('profile.generate-cv') }}" method="POST">
                                @csrf
                                <x-button type="submit">
                                    {{ __('Generate Professional CV') }}
                                </x-button>
                            </form>
                        </div>
                    </x-slot>
                </x-action-section>
            </div>
        </div>
    </div>
</x-app-layout>
