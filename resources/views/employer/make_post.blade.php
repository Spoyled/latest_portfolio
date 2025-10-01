<!-- Header -->
@include('layouts.header')

<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <div class="text-gray-900 font-semibold">
                {{ $isEmployer ? 'Create Job Offer' : 'Create Resume Post' }}
            </div>
        </x-slot>

        <!-- Server-side validation errors -->
        <x-validation-errors class="mb-4" />

        <!-- Add an ID to your form so we can hook into it with JS.
             Also add a data attribute to know if we're dealing with employer or user -->
        <form 
            id="postForm" 
            method="POST" 
            action="{{ $isEmployer ? route('employer.posts.store') : route('posts.store') }}"
            enctype="multipart/form-data" 
            data-is-employer="{{ $isEmployer ? 'true' : 'false' }}"
        >
            @csrf

            {{-- Title / Job Position --}}
            <div>
                <x-label for="title" value="{{ __('Title') }}" />
                <x-input 
                    id="title" 
                    class="block mt-1 w-full border-2 border-gray-400 focus:border-blue-500" 
                    type="text"
                    name="title" 
                    :value="old('title')" 
                    required 
                    autofocus
                    maxlength="255"
                />
                <!-- Example error message -->
                <p id="titleError" class="text-red-600 text-sm hidden">
                    {{ $isEmployer ? 'Job Position' : 'Title' }} is required (max 255 characters).
                </p>
            </div>

            {{-- Description --}}
            <div class="mt-4">
                <x-label for="body" value="{{ __('Description') }}" />
                <textarea 
                    id="body" 
                    class="block mt-1 w-full border-2 border-gray-400 focus:border-blue-500" 
                    name="body"
                    rows="3" 
                    required
                    maxlength="2000"
                >{{ old('body') }}</textarea>
                <p id="bodyError" class="text-red-600 text-sm hidden">
                    Description is required (max 2000 characters).
                </p>
            </div>

            {{-- Employer-Specific Fields --}}
            @if($isEmployer)
                <div class="mt-4">
                    <x-label for="salary" value="{{ __('Salary') }}" />
                    <x-input 
                        id="salary" 
                        class="block mt-1 w-full border-2 border-gray-400 focus:border-blue-500"
                        type="number" 
                        name="salary" 
                        placeholder="Enter salary in USD" 
                        required 
                    />
                    <p id="salaryError" class="text-red-600 text-sm hidden">
                        Salary is required and must be a valid number.
                    </p>
                </div>

                <div class="mt-4">
                    <x-label for="location" value="{{ __('Job Location') }}" />
                    <x-input 
                        id="location" 
                        class="block mt-1 w-full border-2 border-gray-400 focus:border-blue-500"
                        type="text" 
                        name="location" 
                        placeholder="Enter the job location" 
                        :value="old('location')" 
                        required 
                    />
                    <p id="locationError" class="text-red-600 text-sm hidden">
                        Job location is required (max 255 characters).
                    </p>
                </div>

                <div class="mt-4">
                    <x-label for="position" value="{{ __('Job Position') }}" />
                    <x-input 
                        id="position" 
                        class="block mt-1 w-full border-2 border-gray-400 focus:border-blue-500"
                        type="text" 
                        name="position" 
                        placeholder="Enter the job position" 
                        :value="old('position')" 
                        required 
                    />
                    <p id="positionError" class="text-red-600 text-sm hidden">
                        Job position is required (max 255 characters).
                    </p>
                </div>

                <div class="mt-4">
                    <x-label for="skills" value="{{ __('Required Skills') }}" />
                    <select 
                        id="skills"
                        class="block mt-1 w-full border-2 border-gray-400 focus:border-blue-500 form-multiselect"
                        name="skills[]" 
                        multiple 
                        required
                    >
                        <option value="Web Development">Web Development</option>
                        <option value="Cybersecurity">Cybersecurity</option>
                        <option value="Data Analysis">Data Analysis</option>
                        <option value="AI and Machine Learning">AI and Machine Learning</option>
                        <option value="Project Management">Project Management</option>
                    </select>
                    <p id="skillsError" class="text-red-600 text-sm hidden">
                        Please select at least one required skill.
                    </p>
                </div>
            @else
                {{-- User-Specific Fields --}}
                <div class="mt-4">
                    <x-label for="education" value="{{ __('Education') }}" />
                    <x-input 
                        id="education" 
                        class="block mt-1 w-full border-2 border-gray-400 focus:border-blue-500"
                        type="text" 
                        name="education" 
                        :value="old('education')" 
                        required
                        maxlength="255"
                    />
                    <p id="educationError" class="text-red-600 text-sm hidden">
                        Education is required (max 255 characters).
                    </p>
                </div>

                <div class="mt-4">
                    <x-label for="resume" value="{{ __('Upload Resume') }}" />
                    <x-input 
                        id="resume" 
                        class="block mt-1 w-full border-2 border-gray-400 focus:border-blue-500"
                        type="file" 
                        name="resume" 
                        required 
                    />
                    <p id="resumeError" class="text-red-600 text-sm hidden">
                        Please upload a valid resume (PDF, DOC, DOCX) up to 2MB.
                    </p>
                </div>
            @endif

            {{-- Additional Links --}}
            <div class="mt-4">
                <x-label for="additional_links" value="{{ __('Additional Links') }}" />
                <x-input 
                    id="additional_links" 
                    class="block mt-1 w-full border-2 border-gray-400 focus:border-blue-500"
                    type="url" 
                    name="additional_links" 
                />
                <p id="additionalLinksError" class="text-red-600 text-sm hidden">
                    Please enter a valid URL (optional).
                </p>
            </div>

            {{-- Image Upload --}}
            <div class="mt-4">
                <x-label for="image" value="{{ __('Image For The Post') }}" />
                <input 
                    id="image" 
                    class="block mt-1 w-full border-2 border-gray-400 focus:border-blue-500" 
                    type="file"
                    name="image" 
                    required 
                />
                <p id="imageError" class="text-red-600 text-sm hidden">
                    Please upload a valid image (jpg, jpeg, png, gif, webp) up to 2MB.
                </p>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button
                    class="ms-4 bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded text-3xl"
                >
                    {{ __('Create Post') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>

<!-- Footer -->
@include('layouts.footer')

{{-- Front-End Validation Script --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const postForm = document.getElementById('postForm');
    const isEmployer = postForm.dataset.isEmployer === 'true';

    postForm.addEventListener('submit', (e) => {
        let formIsValid = true;

        // Title Check
        const titleField = document.getElementById('title');
        const titleError = document.getElementById('titleError');
        if (!titleField.value.trim() || titleField.value.trim().length > 255) {
            titleError.classList.remove('hidden');
            formIsValid = false;
        } else {
            titleError.classList.add('hidden');
        }

        // Description Check
        const bodyField = document.getElementById('body');
        const bodyError = document.getElementById('bodyError');
        if (!bodyField.value.trim() || bodyField.value.trim().length > 2000) {
            bodyError.classList.remove('hidden');
            formIsValid = false;
        } else {
            bodyError.classList.add('hidden');
        }

        // Employer fields
        if (isEmployer) {
            // Salary
            const salaryField = document.getElementById('salary');
            const salaryError = document.getElementById('salaryError');
            if (!salaryField.value || isNaN(salaryField.value)) {
                salaryError.classList.remove('hidden');
                formIsValid = false;
            } else {
                salaryError.classList.add('hidden');
            }

            // Skills
            const skillsField = document.getElementById('skills');
            const skillsError = document.getElementById('skillsError');
            const selectedSkills = [...skillsField.options].filter(opt => opt.selected).length;
            if (selectedSkills === 0) {
                skillsError.classList.remove('hidden');
                formIsValid = false;
            } else {
                skillsError.classList.add('hidden');
            }

        } else {
            // User fields
            // Education
            const educationField = document.getElementById('education');
            const educationError = document.getElementById('educationError');
            if (!educationField.value.trim() || educationField.value.trim().length > 255) {
                educationError.classList.remove('hidden');
                formIsValid = false;
            } else {
                educationError.classList.add('hidden');
            }

            // Resume
            const resumeField = document.getElementById('resume');
            const resumeError = document.getElementById('resumeError');
            if (resumeField.files.length === 0) {
                resumeError.classList.remove('hidden');
                formIsValid = false;
            } else {
                const allowedResumeTypes = [
                    'application/pdf',
                    'application/msword', 
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                ];
                const resumeFile = resumeField.files[0];
                if (!allowedResumeTypes.includes(resumeFile.type) || resumeFile.size > 2*1024*1024) {
                    resumeError.classList.remove('hidden');
                    formIsValid = false;
                } else {
                    resumeError.classList.add('hidden');
                }
            }
        }

        // Additional Links
        const additionalLinksField = document.getElementById('additional_links');
        const additionalLinksError = document.getElementById('additionalLinksError');
        if (additionalLinksField.value.trim() !== '') {
            // Basic URL check
            const urlPattern = /^(https?:\/\/)?([\w-]+\.)+[\w-]+(\/[\w- ./?%&=]*)?$/i;
            if (!urlPattern.test(additionalLinksField.value.trim())) {
                additionalLinksError.classList.remove('hidden');
                formIsValid = false;
            } else {
                additionalLinksError.classList.add('hidden');
            }
        } else {
            additionalLinksError.classList.add('hidden');
        }

        // Image
        const imageField = document.getElementById('image');
        const imageError = document.getElementById('imageError');
        if (imageField.files.length === 0) {
            imageError.classList.remove('hidden');
            formIsValid = false;
        } else {
            const allowedImageTypes = [
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/webp'
            ];
            const imageFile = imageField.files[0];
            if (!allowedImageTypes.includes(imageFile.type) || imageFile.size > 2*1024*1024) {
                imageError.classList.remove('hidden');
                formIsValid = false;
            } else {
                imageError.classList.add('hidden');
            }
        }

        if (!formIsValid) {
            e.preventDefault(); // Stop form submission
        }
    });
});
</script>
