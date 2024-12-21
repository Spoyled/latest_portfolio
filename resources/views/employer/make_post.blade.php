<!-- Header -->
@include('layouts.header')

<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <div class="text-gray-900 font-semibold">
                {{ $isEmployer ? 'Create Job Offer' : 'Create Resume Post' }}
            </div>
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ $isEmployer ? route('employer.posts.store') : route('posts.store') }}"
            enctype="multipart/form-data">
            @csrf

            {{-- Title or Job Position --}}
            <div>
                <x-label for="title" value="{{ $isEmployer ? __('Job Position') : __('Title') }}" />
                <x-input id="title" class="block mt-1 w-full border-2 border-gray-400 focus:border-blue-500" type="text"
                    name="title" :value="old('title')" required autofocus />
            </div>

            {{-- Description --}}
            <div class="mt-4">
                <x-label for="body" value="{{ __('Description') }}" />
                <textarea id="body" class="block mt-1 w-full border-2 border-gray-400 focus:border-blue-500" name="body"
                    rows="3" required>{{ old('body') }}</textarea>
            </div>

            {{-- Employer-Specific Fields --}}
            @if($isEmployer)
                <div class="mt-4">
                    <x-label for="salary" value="{{ __('Salary') }}" />
                    <x-input id="salary" class="block mt-1 w-full border-2 border-gray-400 focus:border-blue-500"
                        type="number" name="salary" placeholder="Enter salary in USD" required />
                </div>

                <div>
                    <x-label for="skills" value="{{ __('Required Skills') }}" />
                    <select id="skills"
                        class="block mt-1 w-full border-2 border-gray-400 focus:border-blue-500 form-multiselect"
                        name="skills[]" multiple required>
                        <option value="Web Development">Web Development</option>
                        <option value="Cybersecurity">Cybersecurity</option>
                        <option value="Data Analysis">Data Analysis</option>
                        <option value="AI and Machine Learning">AI and Machine Learning</option>
                        <option value="Project Management">Project Management</option>
                    </select>
                </div>
            @else
                {{-- User-Specific Fields --}}
                <div>
                    <x-label for="education" value="{{ __('Education') }}" />
                    <x-input id="education" class="block mt-1 w-full border-2 border-gray-400 focus:border-blue-500"
                        type="text" name="education" :value="old('education')" required />
                </div>

                <div class="mt-4">
                    <x-label for="resume" value="{{ __('Upload Resume') }}" />
                    <x-input id="resume" class="block mt-1 w-full border-2 border-gray-400 focus:border-blue-500"
                        type="file" name="resume" required />
                </div>
            @endif

            {{-- Additional Links --}}
            <div class="mt-4">
                <x-label for="additional_links" value="{{ __('Additional Links') }}" />
                <x-input id="additional_links" class="block mt-1 w-full border-2 border-gray-400 focus:border-blue-500"
                    type="url" name="additional_links" />
            </div>

            {{-- Image Upload --}}
            <div class="mt-4">
                <x-label for="image" value="{{ __('Image For The Post') }}" />
                <x-input id="image" class="block mt-1 w-full border-2 border-gray-400 focus:border-blue-500" type="file"
                    name="image" required />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button
                    class="ms-4 bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded text-3xl">
                    {{ __('Create Post') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>

<!-- Footer -->
@include('layouts.footer')
