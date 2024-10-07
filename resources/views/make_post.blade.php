 <!-- Header -->
@include('layouts.header')

<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <div class="text-gray-800 font-semibold">
                <span class="text-black text-xl">Pro</span><span class="text-yellow-500 text-xl">Snap</span>
            </div>
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
            @csrf

            <div>
                <x-label for="title" value="{{ __('Title') }}" />
                <x-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
            </div>

            <div class="mt-4">
                <x-label for="body" value="{{ __('Description') }}" />
                <textarea id="body" class="block mt-1 w-full form-textarea" name="body" rows="3" required>{{ old('body') }}</textarea>
            </div>

            <div>
                <x-label for="education" value="{{ __('Education') }}" />
                <x-input id="education" class="block mt-1 w-full" type="text" name="education" :value="old('education')" required autofocus />
            </div>

            <div>
                <x-label for="skills" value="{{ __('Skills') }}" />
                <select id="skills" class="block mt-1 w-full form-multiselect" name="skills[]" multiple required>
                    <option value="Photography">Photography</option>
                    <option value="Editing">Editing</option>
                    <option value="Graphic Design">Graphic Design</option>
                    <option value="Social Media">Social Media</option>
                    <option value="Videography">Videography</option>
                    <option value="Web Development">Web Development</option>
                    <option value="Mobile Application Development">Mobile Application Development</option>
                    <option value="Database Management">Database Management</option>
                    <option value="Cloud Computing">Cloud Computing</option>
                    <option value="Cybersecurity">Cybersecurity</option>
                    <option value="AI and Machine Learning">AI and Machine Learning</option>
                    <option value="Data Analysis">Data Analysis</option>
                    <option value="Networking">Networking</option>
                    <option value="Software Engineering">Software Engineering</option>
                    <option value="Technical Support">Technical Support</option>
                    <option value="Project Management">Project Management</option>
                    <option value="UI/UX Design">UI/UX Design</option>
                    <option value="Systems Administration">Systems Administration</option>
                    <option value="DevOps">DevOps</option>
                    <option value="Quality Assurance">Quality Assurance</option>
                    <option value="IT Consulting">IT Consulting</option>
                    <option value="Blockchain">Blockchain</option>
                    <option value="Internet of Things (IoT)">Internet of Things (IoT)</option>
                    <option value="Big Data">Big Data</option>
                    <option value="IT Sales and Marketing">IT Sales and Marketing</option>
                    <option value="Virtualization">Virtualization</option>
                    <option value="Information Security">Information Security</option>
                    <option value="Software Testing">Software Testing</option>
                    <option value="Scripting and Automation">Scripting and Automation</option>
                    <option value="Technical Writing">Technical Writing</option>
                    <option value="Business Analysis">Business Analysis</option>
                    <option value="Ethical Hacking">Ethical Hacking</option>
                    <option value="Compliance and Governance">Compliance and Governance</option>
                </select>
            </div>

            <div class="mt-4">
                <x-label for="image" value="{{ __('Image For The Post') }}" />
                <x-input id="image" class="block mt-1 w-full" type="file" name="image" required />
            </div>

            <div class="mt-4">
                <x-label for="resume" value="{{ __('Resume') }}" />
                <x-input id="resume" class="block mt-1 w-full" type="file" name="resume" required />
            </div>

            <div class="mt-4">
                <x-label for="additional_links" value="{{ __('Additional Links') }}" />
                <x-input id="additional_links" class="block mt-1 w-full" type="url" name="additional_links" required />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="ms-4">
                    {{ __('Create Post') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>

<!-- Footer -->
@include('layouts.footer')