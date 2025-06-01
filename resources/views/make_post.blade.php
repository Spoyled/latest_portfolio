<!-- Header -->
@include('layouts.header')

<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <div class="text-gray-900 font-semibold">
                <!-- Logo or branding if you want -->
            </div>
        </x-slot>

        <!-- Display server-side validation errors (from Laravel) -->
        <x-validation-errors class="mb-4" />

        <!-- Give the form an ID so we can attach JS listeners -->
        <form 
            id="postForm" 
            method="POST" 
            action="{{ route('posts.store') }}" 
            enctype="multipart/form-data"
        >
            @csrf

            <!-- Title -->
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
                <!-- Example: Add a helper or error label here if you want -->
                <p id="titleError" class="text-red-600 text-sm hidden">Title must be between 1 and 255 characters.</p>
            </div>

            <!-- Description (Body) -->
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
                <p id="bodyError" class="text-red-600 text-sm hidden">Description is required (up to 2000 characters).</p>
            </div>

            <!-- Education -->
            <div class="mt-4">
                <x-label for="education" value="{{ __('Education') }}" />
                <x-input 
                    id="education" 
                    class="block mt-1 w-full border-2 border-gray-400 focus:border-blue-500"
                    type="text" 
                    name="education" 
                    :value="old('education')" 
                    maxlength="255"
                />
                <p id="educationError" class="text-red-600 text-sm hidden">Education must be up to 255 characters.</p>
            </div>

            <!-- Skills (Multiple Select) -->
            <div class="mt-4">
                <x-label for="skills" value="{{ __('Skills') }}" />
                <select 
                    id="skills"
                    class="block mt-1 w-full border-2 border-gray-400 focus:border-blue-500 form-multiselect"
                    name="skills[]" 
                    multiple 
                    required
                >
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
                <p id="skillsError" class="text-red-600 text-sm hidden">Please select at least one skill.</p>
            </div>

            <!-- Image File -->
            <div class="mt-4">
                <x-label for="image" value="{{ __('Image For The Post') }}" />
                <x-input 
                    id="image" 
                    class="block mt-1 w-full border-2 border-gray-400 focus:border-blue-500" 
                    type="file"
                    name="image" 
                    required 
                />
                <p id="imageError" class="text-red-600 text-sm hidden">Please select a valid image (jpg, jpeg, png, gif, webp) up to 2MB.</p>
            </div>

            <!-- Resume File -->
            <div class="mt-4">
                <x-label for="resume" value="{{ __('Resume') }}" />
                <x-input 
                    id="resume" 
                    class="block mt-1 w-full border-2 border-gray-400 focus:border-blue-500"
                    type="file" 
                    name="resume"
                />
                <p id="resumeError" class="text-red-600 text-sm hidden">Please select a valid resume (pdf, doc, docx) up to 2MB.</p>
            </div>

            <!-- Additional Links -->
            <div class="mt-4">
                <x-label for="additional_links" value="{{ __('Additional Links') }}" />
                <x-input 
                    id="additional_links" 
                    class="block mt-1 w-full border-2 border-gray-400 focus:border-blue-500"
                    type="url" 
                    name="additional_links"
                />
                <p id="additionalLinksError" class="text-red-600 text-sm hidden">Please enter a valid URL (optional).</p>
            </div>

            <!-- Submit -->
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

<!-- Include a small inline script to handle front-end validation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const postForm = document.getElementById('postForm');

    postForm.addEventListener('submit', function(e) {
        // We'll track if any field is invalid
        let formIsValid = true;

        // 1. Title Validation
        const titleField = document.getElementById('title');
        const titleError = document.getElementById('titleError');
        if (!titleField.value.trim() || titleField.value.length > 255) {
            titleError.classList.remove('hidden');
            formIsValid = false;
        } else {
            titleError.classList.add('hidden');
        }

        // 2. Body Validation
        const bodyField = document.getElementById('body');
        const bodyError = document.getElementById('bodyError');
        if (!bodyField.value.trim() || bodyField.value.length > 2000) {
            bodyError.classList.remove('hidden');
            formIsValid = false;
        } else {
            bodyError.classList.add('hidden');
        }

        // 3. Education (optional or required? If required, we check length)
        const educationField = document.getElementById('education');
        const educationError = document.getElementById('educationError');
        if (educationField.value.length > 255) {
            educationError.classList.remove('hidden');
            formIsValid = false;
        } else {
            educationError.classList.add('hidden');
        }

        // 4. Skills: must choose at least 1
        const skillsField = document.getElementById('skills');
        const skillsError = document.getElementById('skillsError');
        // For multiple select, check if any option is chosen
        if ([...skillsField.options].filter(option => option.selected).length === 0) {
            skillsError.classList.remove('hidden');
            formIsValid = false;
        } else {
            skillsError.classList.add('hidden');
        }

        // 5. Image File Validation
        const imageField = document.getElementById('image');
        const imageError = document.getElementById('imageError');
        if (imageField.files.length === 0) {
            // No file selected at all
            imageError.classList.remove('hidden');
            formIsValid = false;
        } else {
            // Check extension & size
            const allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            const imageFile = imageField.files[0];
            if (!allowedImageTypes.includes(imageFile.type) || imageFile.size > 2 * 1024 * 1024) {
                imageError.classList.remove('hidden');
                formIsValid = false;
            } else {
                imageError.classList.add('hidden');
            }
        }

        // 6. Resume File Validation (if resume is required for your flow)
        const resumeField = document.getElementById('resume');
        const resumeError = document.getElementById('resumeError');
        if (resumeField.files.length > 0) {
            // Check extension & size
            const allowedResumeTypes = [
                'application/pdf', 
                'application/msword', 
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ];
            const resumeFile = resumeField.files[0];
            if (!allowedResumeTypes.includes(resumeFile.type) || resumeFile.size > 2 * 1024 * 1024) {
                resumeError.classList.remove('hidden');
                formIsValid = false;
            } else {
                resumeError.classList.add('hidden');
            }
        } else {
            // If you want to enforce that normal users must upload a resume:
            // resumeError.classList.remove('hidden');
            // formIsValid = false;
            // Otherwise, we can allow it to be optional and do nothing if no file
            resumeError.classList.add('hidden');
        }

        // 7. Additional Links (optional but must be a valid URL if present)
        const additionalLinksField = document.getElementById('additional_links');
        const additionalLinksError = document.getElementById('additionalLinksError');
        if (additionalLinksField.value.trim() !== '') {
            // Basic check: see if it looks like a URL
            // If you want more robust validation, you could use a better regex or library
            const urlPattern = /^(https?:\/\/)?([\w-]+\.)+[\w-]+(\/[\w- ./?%&=]*)?$/i;
            if (!urlPattern.test(additionalLinksField.value.trim())) {
                additionalLinksError.classList.remove('hidden');
                formIsValid = false;
            } else {
                additionalLinksError.classList.add('hidden');
            }
        } else {
            // If it's empty, it's fine, just hide the error
            additionalLinksError.classList.add('hidden');
        }

        // If form is invalid, prevent submission
        if (!formIsValid) {
            e.preventDefault();
        }
    });
});
</script>

<!-- Footer -->
@include('layouts.footer')
