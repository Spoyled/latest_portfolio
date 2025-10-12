@include('layouts.header')

@php
    $selectedSkills = old('skills');
    if (!is_array($selectedSkills)) {
        $selectedSkills = array_filter(array_map('trim', explode(',', (string) ($post->skills ?? ''))));
    }

    $sections = $sections ?? \App\Support\ResumeBlueprint::parse($post->body ?? '');
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
                Refresh your spotlight
            </p>
            <h1 class="mt-4 text-4xl font-semibold text-white sm:text-5xl">
                Update your resume showcase.
            </h1>
            <p class="mt-3 text-lg text-slate-200 sm:text-xl">
                Fine-tune your pitch, highlight recent wins, and keep details current so hiring teams can connect faster.
            </p>
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
                <h2 class="text-2xl font-semibold text-slate-900">Edit post details</h2>
                <p class="mt-2 text-sm text-slate-500">
                    Adjust your narrative, upload refreshed assets, and keep your skillset aligned with the roles you want.
                </p>

                <form id="updatePostForm"
                      method="POST"
                      action="{{ route('posts.update', $post->id) }}"
                      enctype="multipart/form-data"
                      class="mt-8 space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-6 md:grid-cols-2">
                        <label class="flex flex-col gap-2">
                            <span class="text-sm font-semibold text-slate-700">Title<span class="text-rose-500">*</span></span>
                            <input id="title"
                                   name="title"
                                   type="text"
                                   value="{{ old('title', $post->title) }}"
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
                                   value="{{ old('education', $post->education) }}"
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
                                  class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('summary', $sections['summary']) }}</textarea>
                        <span id="summaryError" class="hidden text-xs font-semibold text-rose-600">Share a concise overview (up to 600 characters).</span>
                    </label>

                    <label class="flex flex-col gap-2">
                        <span class="text-sm font-semibold text-slate-700">Recent wins</span>
                        <textarea id="highlights"
                                  name="highlights"
                                  rows="4"
                                  maxlength="600"
                                  class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('highlights', $sections['highlights']) }}</textarea>
                        <span id="highlightsError" class="hidden text-xs font-semibold text-rose-600">Keep highlights within 600 characters.</span>
                        <span class="text-xs text-slate-400">Spotlight measurable outcomes or standout projects from the past year.</span>
                    </label>

                    <label class="flex flex-col gap-2">
                        <span class="text-sm font-semibold text-slate-700">Ideal next role<span class="text-rose-500">*</span></span>
                        <textarea id="ideal_role"
                                  name="ideal_role"
                                  rows="3"
                                  maxlength="400"
                                  required
                                  class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('ideal_role', $sections['ideal_role']) }}</textarea>
                        <span id="idealRoleError" class="hidden text-xs font-semibold text-rose-600">Describe the kind of role or challenge you are targeting (up to 400 characters).</span>
                    </label>

                    <label class="flex flex-col gap-2">
                        <span class="text-sm font-semibold text-slate-700">Collaboration style</span>
                        <textarea id="collaboration"
                                  name="collaboration"
                                  rows="3"
                                  maxlength="400"
                                  class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('collaboration', $sections['collaboration']) }}</textarea>
                        <span id="collaborationError" class="hidden text-xs font-semibold text-rose-600">Collaboration style should stay within 400 characters.</span>
                        <span class="text-xs text-slate-400">Share how you communicate, collaborate, and lead.</span>
                    </label>

                    <div class="grid gap-6 md:grid-cols-2">
                        <label class="flex flex-col gap-2">
                            <span class="text-sm font-semibold text-slate-700">Availability & logistics</span>
                            <textarea id="availability"
                                      name="availability"
                                      rows="3"
                                      maxlength="300"
                                      class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('availability', $sections['availability']) }}</textarea>
                            <span id="availabilityError" class="hidden text-xs font-semibold text-rose-600">Limit availability details to 300 characters.</span>
                            <span class="text-xs text-slate-400">Include availability, time zones, or location flexibility.</span>
                        </label>

                        <label class="flex flex-col gap-2">
                            <span class="text-sm font-semibold text-slate-700">Portfolio or case study link</span>
                            <input id="additional_links"
                                   name="additional_links"
                                   type="url"
                                   value="{{ old('additional_links', $post->additional_links) }}"
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
                            <span class="text-sm font-semibold text-slate-700">Update cover image</span>
                            <input id="image"
                                   name="image"
                                   type="file"
                                   accept=".jpg,.jpeg,.png,.gif,.webp"
                                   class="w-full rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-600 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <span class="text-xs text-slate-400">Leave blank to keep your current image (max 2 MB).</span>
                            <span id="imageError" class="hidden text-xs font-semibold text-rose-600">Please select a valid image (jpg, jpeg, png, gif, webp) up to 2MB.</span>
                        </label>

                        <label class="flex flex-col gap-2">
                            <span class="text-sm font-semibold text-slate-700">Update resume</span>
                            <input id="resume"
                                   name="resume"
                                   type="file"
                                   accept=".pdf,.doc,.docx"
                                   class="w-full rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-600 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <span class="text-xs text-slate-400">Optional: upload a refreshed CV (PDF, DOC, or DOCX up to 2 MB).</span>
                            <span id="resumeError" class="hidden text-xs font-semibold text-rose-600">Please select a valid resume (pdf, doc, docx) up to 2MB.</span>
                        </label>
                    </div>

                    <div class="flex flex-col gap-4 rounded-2xl bg-slate-50 p-6">
                        <p class="text-sm font-semibold text-slate-700">Update checklist</p>
                        <ul class="space-y-2 text-sm text-slate-500">
                            <li class="flex items-start gap-2">
                                <span class="mt-1 h-2 w-2 rounded-full bg-blue-500"></span>
                                Refresh your highlights with precise metrics or scope.
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-1 h-2 w-2 rounded-full bg-blue-500"></span>
                                Keep the “Ideal role” section specific about teams, industries, or problems you love solving.
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-1 h-2 w-2 rounded-full bg-blue-500"></span>
                                Confirm application-ready assets: image, resume, and contact links.
                            </li>
                        </ul>
                    </div>

                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <p class="text-xs text-slate-400">Changes go live immediately — make sure your details are accurate.</p>
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-yellow-400 px-6 py-3 text-sm font-semibold text-slate-900 shadow-sm transition hover:bg-yellow-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M5 13l4 4L19 7" />
                            </svg>
                            Save updates
                        </button>
                    </div>
                </form>
            </section>

            <aside class="space-y-6">
                <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">Why keep this fresh?</h3>
                    <ul class="mt-4 space-y-3 text-sm text-slate-600">
                        <li class="flex gap-3">
                            <span class="mt-1 inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                            Recruiters see your most recent updates first — highlight new skills and wins.
                        </li>
                        <li class="flex gap-3">
                            <span class="mt-1 inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                            A clear “Ideal next role” helps teams quickly assess fit and reach out.
                        </li>
                        <li class="flex gap-3">
                            <span class="mt-1 inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                            Specifying availability reduces back-and-forth and accelerates interviews.
                        </li>
                    </ul>
                </div>

                <div class="rounded-3xl bg-slate-900 p-8 text-white shadow-sm">
                    <h3 class="text-lg font-semibold">Share with confidence</h3>
                    <p class="mt-3 text-sm text-slate-200">
                        Send your ProSnap profile link alongside every application so hiring teams can access a polished, consistently branded overview.
                    </p>
                    <a href="{{ route('custom.profile.show') }}"
                       class="mt-6 inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-slate-900 transition hover:bg-yellow-200">
                        Preview my profile
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </aside>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const postForm = document.getElementById('updatePostForm');
    if (!postForm) return;

    postForm.addEventListener('submit', function (e) {
        let formIsValid = true;

        const titleField = document.getElementById('title');
        const titleError = document.getElementById('titleError');
        if (!titleField.value.trim() || titleField.value.length > 255) {
            titleError.classList.remove('hidden');
            formIsValid = false;
        } else {
            titleError.classList.add('hidden');
        }

        const summaryField = document.getElementById('summary');
        const summaryError = document.getElementById('summaryError');
        if (!summaryField.value.trim() || summaryField.value.length > 600) {
            summaryError.classList.remove('hidden');
            formIsValid = false;
        } else {
            summaryError.classList.add('hidden');
        }

        const highlightsField = document.getElementById('highlights');
        const highlightsError = document.getElementById('highlightsError');
        if (highlightsField.value.length > 600) {
            highlightsError.classList.remove('hidden');
            formIsValid = false;
        } else {
            highlightsError.classList.add('hidden');
        }

        const idealRoleField = document.getElementById('ideal_role');
        const idealRoleError = document.getElementById('idealRoleError');
        if (!idealRoleField.value.trim() || idealRoleField.value.length > 400) {
            idealRoleError.classList.remove('hidden');
            formIsValid = false;
        } else {
            idealRoleError.classList.add('hidden');
        }

        const collaborationField = document.getElementById('collaboration');
        const collaborationError = document.getElementById('collaborationError');
        if (collaborationField.value.length > 400) {
            collaborationError.classList.remove('hidden');
            formIsValid = false;
        } else {
            collaborationError.classList.add('hidden');
        }

        const availabilityField = document.getElementById('availability');
        const availabilityError = document.getElementById('availabilityError');
        if (availabilityField.value.length > 300) {
            availabilityError.classList.remove('hidden');
            formIsValid = false;
        } else {
            availabilityError.classList.add('hidden');
        }

        const educationField = document.getElementById('education');
        const educationError = document.getElementById('educationError');
        if (educationField.value.length > 255) {
            educationError.classList.remove('hidden');
            formIsValid = false;
        } else {
            educationError.classList.add('hidden');
        }

        const skillsField = document.getElementById('skills');
        const skillsError = document.getElementById('skillsError');
        if (!skillsField.selectedOptions || skillsField.selectedOptions.length === 0) {
            skillsError.classList.remove('hidden');
            formIsValid = false;
        } else {
            skillsError.classList.add('hidden');
        }

        const imageField = document.getElementById('image');
        const imageError = document.getElementById('imageError');
        if (imageField.files && imageField.files.length > 0) {
            const allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            const imageFile = imageField.files[0];
            if (!allowedImageTypes.includes(imageFile.type) || imageFile.size > 2 * 1024 * 1024) {
                imageError.classList.remove('hidden');
                formIsValid = false;
            } else {
                imageError.classList.add('hidden');
            }
        } else {
            imageError.classList.add('hidden');
        }

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

@include('layouts.footer')
