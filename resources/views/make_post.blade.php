@include('layouts.header')

@php
    $selectedSkills = old('skills', []);
    if (!is_array($selectedSkills)) {
        $selectedSkills = array_filter(array_map('trim', explode(',', (string) $selectedSkills)));
    }
    $skillOptions = [
        'Photography', 'Editing', 'Graphic Design', 'Social Media', 'Videography',
        'Web Development', 'Mobile Application Development', 'Database Management', 'Cloud Computing',
        'Cybersecurity', 'AI and Machine Learning', 'Data Analysis', 'Networking', 'Software Engineering',
        'Technical Support', 'Project Management', 'UI/UX Design', 'Systems Administration', 'DevOps',
        'Quality Assurance', 'IT Consulting', 'Blockchain', 'Internet of Things (IoT)', 'Big Data',
        'IT Sales and Marketing', 'Virtualization', 'Information Security', 'Software Testing',
        'Scripting and Automation', 'Technical Writing', 'Business Analysis', 'Ethical Hacking',
        'Compliance and Governance',
    ];
@endphp

<section class="relative isolate overflow-hidden bg-slate-900">
    <img src="{{ asset('storage/images/A_horizontal_image_related_to_the_IT_industry,_fea.jpg') }}"
         alt="Creative workspace"
         class="absolute inset-0 h-full w-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/75 to-slate-900/60"></div>

    <div class="relative mx-auto flex max-w-6xl flex-col gap-10 px-6 py-16 sm:py-20 lg:px-8">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-yellow-300">
                Craft your next job post
            </p>
            <h1 class="mt-4 text-4xl font-semibold text-white sm:text-5xl">
                Publish a polished post that tells your story.
            </h1>
            <p class="mt-3 text-lg text-slate-200 sm:text-xl">
                Use this form to share your achievements, skills, and supporting material.
                A clear narrative helps hiring teams recognise your impact faster.
            </p>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-white/20 bg-white/10 p-5 backdrop-blur-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-200">Step 1</p>
                <p class="mt-3 text-2xl font-semibold text-white">Define the headline</p>
                <p class="mt-2 text-sm text-slate-200">Write a clear, searchable title that reflects the opportunity or expertise.</p>
            </div>
            <div class="rounded-2xl border border-white/20 bg-white/10 p-5 backdrop-blur-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-200">Step 2</p>
                <p class="mt-3 text-2xl font-semibold text-white">Add rich context</p>
                <p class="mt-2 text-sm text-slate-200">Share measurable outcomes, tools, and responsibilities that set you apart.</p>
            </div>
            <div class="rounded-2xl border border-white/20 bg-white/10 p-5 backdrop-blur-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-200">Step 3</p>
                <p class="mt-3 text-2xl font-semibold text-white">Upload proof points</p>
                <p class="mt-2 text-sm text-slate-200">Include visuals and documents that back up your narrative.</p>
            </div>
        </div>
    </div>
</section>

<main class="bg-slate-100">
    <div class="mx-auto max-w-6xl px-6 pb-20 pt-12 sm:px-8">
        @if (session('status'))
            <div class="mb-6 rounded-2xl border border-emerald-300 bg-emerald-50 p-4 text-sm font-semibold text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-rose-300 bg-rose-50 p-6">
                <h2 class="text-sm font-semibold text-rose-700">Please address the following:</h2>
                <ul class="mt-3 list-disc space-y-1 pl-5 text-sm text-rose-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid gap-10 lg:grid-cols-[1fr,0.6fr]">
            <section class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-2xl font-semibold text-slate-900">Post details</h2>
                <p class="mt-2 text-sm text-slate-500">
                    Provide essential information about your experience, role, and supporting materials.
                </p>

                <form id="postForm"
                      method="POST"
                      action="{{ route('posts.store') }}"
                      enctype="multipart/form-data"
                      class="mt-8 space-y-6">
                    @csrf

                    <div class="grid gap-6 md:grid-cols-2">
                        <label class="flex flex-col gap-2">
                            <span class="text-sm font-semibold text-slate-700">Title<span class="text-rose-500">*</span></span>
                            <input id="title"
                                   name="title"
                                   type="text"
                                   value="{{ old('title') }}"
                                   maxlength="255"
                                   required
                                   class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <span id="titleError" class="hidden text-xs font-semibold text-rose-600">Title must be between 1 and 255 characters.</span>
                        </label>

                        <label class="flex flex-col gap-2">
                            <span class="text-sm font-semibold text-slate-700">Education</span>
                            <input id="education"
                                   name="education"
                                   type="text"
                                   value="{{ old('education') }}"
                                   maxlength="255"
                                   class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <span id="educationError" class="hidden text-xs font-semibold text-rose-600">Education must be up to 255 characters.</span>
                        </label>
                    </div>

                    <label class="flex flex-col gap-2">
                        <span class="text-sm font-semibold text-slate-700">Professional snapshot<span class="text-rose-500">*</span></span>
                        <textarea id="summary"
                                  name="summary"
                                  rows="4"
                                  maxlength="600"
                                  required
                                  class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('summary') }}</textarea>
                        <span id="summaryError" class="hidden text-xs font-semibold text-rose-600">Share a concise overview (up to 600 characters).</span>
                    </label>

                    <label class="flex flex-col gap-2">
                        <span class="text-sm font-semibold text-slate-700">Key achievements</span>
                        <textarea id="highlights"
                                  name="highlights"
                                  rows="4"
                                  maxlength="600"
                                  class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('highlights') }}</textarea>
                        <span id="highlightsError" class="hidden text-xs font-semibold text-rose-600">Keep highlights within 600 characters.</span>
                        <span class="text-xs text-slate-400">Highlight measurable impact or a favourite project from the last 12 months.</span>
                    </label>

                    <label class="flex flex-col gap-2">
                        <span class="text-sm font-semibold text-slate-700">Ideal next role<span class="text-rose-500">*</span></span>
                        <textarea id="ideal_role"
                                  name="ideal_role"
                                  rows="3"
                                  maxlength="400"
                                  required
                                  class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('ideal_role') }}</textarea>
                        <span id="idealRoleError" class="hidden text-xs font-semibold text-rose-600">Describe the kind of role or challenge you are targeting (up to 400 characters).</span>
                    </label>

                    <label class="flex flex-col gap-2">
                        <span class="text-sm font-semibold text-slate-700">Collaboration style</span>
                        <textarea id="collaboration"
                                  name="collaboration"
                                  rows="3"
                                  maxlength="400"
                                  class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('collaboration') }}</textarea>
                        <span id="collaborationError" class="hidden text-xs font-semibold text-rose-600">Collaboration style should stay within 400 characters.</span>
                        <span class="text-xs text-slate-400">Explain how you communicate, lead, and collaborate best.</span>
                    </label>

                    <div class="grid gap-6 md:grid-cols-2">
                        <label class="flex flex-col gap-2">
                            <span class="text-sm font-semibold text-slate-700">Availability & logistics</span>
                            <textarea id="availability"
                                      name="availability"
                                      rows="3"
                                      maxlength="300"
                                      class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('availability') }}</textarea>
                            <span id="availabilityError" class="hidden text-xs font-semibold text-rose-600">Limit availability details to 300 characters.</span>
                            <span class="text-xs text-slate-400">Mention start date, location preferences, time zones, or visa status.</span>
                        </label>

                        <label class="flex flex-col gap-2">
                            <span class="text-sm font-semibold text-slate-700">Portfolio or case study link</span>
                            <input id="additional_links"
                                   name="additional_links"
                                   type="url"
                                   value="{{ old('additional_links') }}"
                                   placeholder="https://portfolio.example.com"
                                   class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <span id="additionalLinksError" class="hidden text-xs font-semibold text-rose-600">Please enter a valid URL (optional).</span>
                        </label>
                    </div>

                    <label class="flex flex-col gap-2">
                        <span class="text-sm font-semibold text-slate-700">Skills & toolset<span class="text-rose-500">*</span></span>
                        <select id="skills"
                                name="skills[]"
                                multiple
                                required
                                class="h-48 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            @foreach ($skillOptions as $skill)
                                <option value="{{ $skill }}" {{ in_array($skill, $selectedSkills, true) ? 'selected' : '' }}>
                                    {{ $skill }}
                                </option>
                            @endforeach
                        </select>
                        <span id="skillsError" class="hidden text-xs font-semibold text-rose-600">Please select at least one skill.</span>
                        <p class="text-xs text-slate-400">Hold Ctrl (Windows) or Command (macOS) to select multiple skills.</p>
                    </label>

                    <div class="grid gap-6 md:grid-cols-2">
                        <label class="flex flex-col gap-2">
                            <span class="text-sm font-semibold text-slate-700">Cover image<span class="text-rose-500">*</span></span>
                            <input id="image"
                                   name="image"
                                   type="file"
                                   accept=".jpg,.jpeg,.png,.gif,.webp"
                                   required
                                   class="w-full rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-600 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <span class="text-xs text-slate-400">Upload a supporting visual (max 2 MB).</span>
                            <span id="imageError" class="hidden text-xs font-semibold text-rose-600">Please select a valid image (jpg, jpeg, png, gif, webp) up to 2MB.</span>
                        </label>

                        <label class="flex flex-col gap-2">
                            <span class="text-sm font-semibold text-slate-700">Supporting resume</span>
                            <input id="resume"
                                   name="resume"
                                   type="file"
                                   accept=".pdf,.doc,.docx"
                                   class="w-full rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-600 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <span class="text-xs text-slate-400">Optional but recommended – PDF, DOC, or DOCX up to 2 MB.</span>
                            <span id="resumeError" class="hidden text-xs font-semibold text-rose-600">Please select a valid resume (pdf, doc, docx) up to 2MB.</span>
                        </label>
                    </div>

                    <div class="flex flex-col gap-4 rounded-2xl bg-slate-50 p-6">
                        <p class="text-sm font-semibold text-slate-700">Before publishing</p>
                        <ul class="space-y-2 text-sm text-slate-500">
                            <li class="flex items-start gap-2">
                                <span class="mt-1 h-2 w-2 rounded-full bg-blue-500"></span>
                                Double-check spelling, links, and data points to maintain credibility.
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-1 h-2 w-2 rounded-full bg-blue-500"></span>
                                Highlight measurable achievements, tools, or team impact.
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-1 h-2 w-2 rounded-full bg-blue-500"></span>
                                Select skills that align with the work you want to be known for.
                            </li>
                        </ul>
                    </div>

                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <p class="text-xs text-slate-400">By publishing, you confirm you have rights to share these assets.</p>
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-yellow-400 px-6 py-3 text-sm font-semibold text-slate-900 shadow-sm transition hover:bg-yellow-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            Publish post
                        </button>
                    </div>
                </form>
            </section>

            <aside class="space-y-6">
                <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">Tips for a standout post</h3>
                    <ul class="mt-4 space-y-3 text-sm text-slate-600">
                        <li class="flex gap-3">
                            <span class="mt-1 inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                            Lead with results—mention outcomes, metrics, or recognition you earned.
                        </li>
                        <li class="flex gap-3">
                            <span class="mt-1 inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                            Reference the context—describe the challenge, your role, and collaborators.
                        </li>
                        <li class="flex gap-3">
                            <span class="mt-1 inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                            Keep visuals high resolution (1200px wide or larger) for a polished appearance.
                        </li>
                        <li class="flex gap-3">
                            <span class="mt-1 inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                            Link to live demos, Git repositories, videos, or press mentions whenever possible.
                        </li>
                    </ul>
                </div>

                <div class="rounded-3xl bg-slate-900 p-8 text-white shadow-sm">
                    <h3 class="text-lg font-semibold">Need feedback before posting?</h3>
                    <p class="mt-3 text-sm text-slate-200">
                        Share a draft link with a mentor or colleague. Fresh eyes can help you highlight what matters most.
                    </p>
                    <a href="{{ route('custom.profile.show') }}"
                       class="mt-6 inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-slate-900 transition hover:bg-yellow-200">
                        Review my profile
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </aside>
        </div>
    </div>
</main>

@include('layouts.footer')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const postForm = document.getElementById('postForm');
    if (!postForm) return;

    postForm.addEventListener('submit', function (e) {
        let formIsValid = true;

        // Title validation
        const titleField = document.getElementById('title');
        const titleError = document.getElementById('titleError');
        if (!titleField.value.trim() || titleField.value.length > 255) {
            titleError.classList.remove('hidden');
            formIsValid = false;
        } else {
            titleError.classList.add('hidden');
        }

        // Summary validation
        const summaryField = document.getElementById('summary');
        const summaryError = document.getElementById('summaryError');
        if (!summaryField.value.trim() || summaryField.value.length > 600) {
            summaryError.classList.remove('hidden');
            formIsValid = false;
        } else {
            summaryError.classList.add('hidden');
        }

        // Highlights validation (optional)
        const highlightsField = document.getElementById('highlights');
        const highlightsError = document.getElementById('highlightsError');
        if (highlightsField.value.length > 600) {
            highlightsError.classList.remove('hidden');
            formIsValid = false;
        } else {
            highlightsError.classList.add('hidden');
        }

        // Ideal role validation
        const idealRoleField = document.getElementById('ideal_role');
        const idealRoleError = document.getElementById('idealRoleError');
        if (!idealRoleField.value.trim() || idealRoleField.value.length > 400) {
            idealRoleError.classList.remove('hidden');
            formIsValid = false;
        } else {
            idealRoleError.classList.add('hidden');
        }

        // Collaboration validation (optional)
        const collaborationField = document.getElementById('collaboration');
        const collaborationError = document.getElementById('collaborationError');
        if (collaborationField.value.length > 400) {
            collaborationError.classList.remove('hidden');
            formIsValid = false;
        } else {
            collaborationError.classList.add('hidden');
        }

        // Availability validation (optional)
        const availabilityField = document.getElementById('availability');
        const availabilityError = document.getElementById('availabilityError');
        if (availabilityField.value.length > 300) {
            availabilityError.classList.remove('hidden');
            formIsValid = false;
        } else {
            availabilityError.classList.add('hidden');
        }

        // Education validation
        const educationField = document.getElementById('education');
        const educationError = document.getElementById('educationError');
        if (educationField.value.length > 255) {
            educationError.classList.remove('hidden');
            formIsValid = false;
        } else {
            educationError.classList.add('hidden');
        }

        // Skills validation
        const skillsField = document.getElementById('skills');
        const skillsError = document.getElementById('skillsError');
        if (!skillsField.selectedOptions || skillsField.selectedOptions.length === 0) {
            skillsError.classList.remove('hidden');
            formIsValid = false;
        } else {
            skillsError.classList.add('hidden');
        }

        // Image validation
        const imageField = document.getElementById('image');
        const imageError = document.getElementById('imageError');
        if (!imageField.files || imageField.files.length === 0) {
            imageError.textContent = 'Please select an image to support your post.';
            imageError.classList.remove('hidden');
            formIsValid = false;
        } else {
            const allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            const imageFile = imageField.files[0];
            if (!allowedImageTypes.includes(imageFile.type) || imageFile.size > 2 * 1024 * 1024) {
                imageError.textContent = 'Please select a valid image (jpg, jpeg, png, gif, webp) up to 2MB.';
                imageError.classList.remove('hidden');
                formIsValid = false;
            } else {
                imageError.classList.add('hidden');
            }
        }

        // Resume validation (optional)
        const resumeField = document.getElementById('resume');
        const resumeError = document.getElementById('resumeError');
        if (resumeField.files && resumeField.files.length > 0) {
            const allowedResumeTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            const resumeFile = resumeField.files[0];
            if (!allowedResumeTypes.includes(resumeFile.type) || resumeFile.size > 2 * 1024 * 1024) {
                resumeError.classList.remove('hidden');
                formIsValid = false;
            } else {
                resumeError.classList.add('hidden');
            }
        } else {
            resumeError.classList.add('hidden');
        }

        // Additional links validation
        const additionalLinksField = document.getElementById('additional_links');
        const additionalLinksError = document.getElementById('additionalLinksError');
        if (additionalLinksField.value.trim() !== '') {
            let urlValid = true;
            try {
                new URL(additionalLinksField.value);
            } catch (err) {
                urlValid = false;
            }
            if (!urlValid) {
                additionalLinksError.classList.remove('hidden');
                formIsValid = false;
            } else {
                additionalLinksError.classList.add('hidden');
            }
        } else {
            additionalLinksError.classList.add('hidden');
        }

        if (!formIsValid) {
            e.preventDefault();
            const firstError = this.querySelector('.text-rose-600:not(.hidden)');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
});
</script>
