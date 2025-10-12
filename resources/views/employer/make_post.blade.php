@include('layouts.header')

@php
    $skillOptions = [
        'Product Management', 'Project Management', 'People Leadership', 'Business Operations',
        'Customer Success', 'Sales Strategy', 'Growth Marketing', 'Content Marketing',
        'UI/UX Design', 'Brand Design', 'Service Design', 'Design Systems',
        'Web Development', 'Mobile Development', 'Full-Stack Development', 'Backend Engineering',
        'Data Engineering', 'Data Science', 'AI & Machine Learning', 'Analytics Engineering',
        'Cybersecurity', 'Cloud Infrastructure', 'DevOps', 'QA Automation',
        'Finance & Accounting', 'Legal & Compliance', 'HR & Talent', 'Office Operations',
        'Support Engineering', 'Technical Writing', 'IT Service Management', 'Product Support',
    ];
    $selectedSkills = old('skills', []);
    if (!is_array($selectedSkills)) {
        $selectedSkills = array_filter(array_map('trim', explode(',', (string) $selectedSkills)));
    }
@endphp

<section class="relative isolate overflow-hidden bg-slate-950">
    <img src="{{ asset('storage/images/A_horizontal_image_related_to_the_IT_industry,_fea.jpg') }}"
         alt="Hiring workspace"
         class="absolute inset-0 h-full w-full object-cover opacity-40">
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/80 to-slate-900/65"></div>

    <div class="relative mx-auto flex max-w-6xl flex-col gap-10 px-6 py-20 sm:py-24 lg:px-8">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-yellow-300">
                Publish a role
            </p>
            <h1 class="mt-4 text-4xl font-semibold text-white sm:text-5xl">
                Market your opening clearly and attract the right candidates.
            </h1>
            <p class="mt-3 text-lg text-slate-200 sm:text-xl">
                Use structured prompts to capture mission, responsibilities, success metrics, and culture in minutes.
            </p>
        </div>
    </div>
</section>

<main class="bg-slate-100">
    <div class="mx-auto max-w-6xl px-6 pb-20 pt-12 sm:px-8">

        @if ($errors->any())
            <div class="mb-8 rounded-2xl border border-rose-300 bg-rose-50 p-6">
                <h2 class="text-sm font-semibold text-rose-700">Please address the following:</h2>
                <ul class="mt-3 list-disc space-y-1 pl-5 text-sm text-rose-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid gap-10 lg:grid-cols-[1fr,0.65fr]">
            <section class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-2xl font-semibold text-slate-900">Role details</h2>
                <p class="mt-2 text-sm text-slate-500">
                    Bring clarity to the role by outlining where this hire will have impact and how they’ll collaborate.
                </p>

                <form id="employerPostForm"
                      method="POST"
                      action="{{ route('employer.posts.store') }}"
                      enctype="multipart/form-data"
                      class="mt-10 space-y-6">
                    @csrf

                    <label class="flex flex-col gap-2">
                        <span class="text-sm font-semibold text-slate-700">Job title<span class="text-rose-500">*</span></span>
                        <input id="title"
                               name="title"
                               type="text"
                               value="{{ old('title') }}"
                               maxlength="255"
                               required
                               class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <span id="titleError" class="hidden text-xs font-semibold text-rose-600">Job title is required (max 255 characters).</span>
                    </label>

                    <div class="grid gap-6 md:grid-cols-2">
                        <label class="flex flex-col gap-2">
                            <span class="text-sm font-semibold text-slate-700">Location<span class="text-rose-500">*</span></span>
                            <input id="location"
                                   name="location"
                                   type="text"
                                   value="{{ old('location') }}"
                                   maxlength="255"
                                   placeholder="e.g. Berlin · Hybrid"
                                   required
                                   class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <span id="locationError" class="hidden text-xs font-semibold text-rose-600">Location is required (letters, spaces, dashes only).</span>
                        </label>

                        <label class="flex flex-col gap-2">
                            <span class="text-sm font-semibold text-slate-700">Team / level<span class="text-rose-500">*</span></span>
                            <input id="position"
                                   name="position"
                                   type="text"
                                   value="{{ old('position') }}"
                                   maxlength="255"
                                   placeholder="e.g. Senior · Product Platform"
                                   required
                                   class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <span id="positionError" class="hidden text-xs font-semibold text-rose-600">Please describe the level or team.</span>
                        </label>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <label class="flex flex-col gap-2">
                            <span class="text-sm font-semibold text-slate-700">Compensation (monthly, €)</span>
                            <input id="salary"
                                   name="salary"
                                   type="number"
                                   step="0.01"
                                   value="{{ old('salary') }}"
                                   placeholder="Optional: share a range"
                                   class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900.shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <span id="salaryError" class="hidden text-xs font-semibold text-rose-600">Salary must be a valid number if provided.</span>
                        </label>

                        <label class="flex flex-col gap-2">
                            <span class="text-sm font-semibold text-slate-700">Required skills<span class="text-rose-500">*</span></span>
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
                            <span id="skillsError" class="hidden text-xs font-semibold text-rose-600">Select at least one relevant skill or capability.</span>
                            <span class="text-xs text-slate-400">Hold Ctrl (Windows) or Command (macOS) to select multiple entries.</span>
                        </label>
                    </div>

                    <label class="flex flex-col gap-2">
                        <span class="text-sm font-semibold text-slate-700">External link</span>
                        <input id="additional_links"
                               name="additional_links"
                               type="url"
                               value="{{ old('additional_links') }}"
                               placeholder="Optional: company deck, benefits page, or process overview"
                               class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <span id="additionalLinksError" class="hidden text-xs font-semibold text-rose-600">Please enter a valid URL or leave blank.</span>
                    </label>

                    <div class="grid gap-6 md:grid-cols-2">
                        <label class="flex flex-col gap-2">
                            <span class="text-sm font-semibold text-slate-700">Hero image<span class="text-rose-500">*</span></span>
                            <input id="image"
                                   name="image"
                                   type="file"
                                   accept=".jpg,.jpeg,.png,.gif,.webp"
                                   required
                                   class="w-full rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-600 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <span id="imageError" class="hidden text-xs.font-semibold text-rose-600">Upload a JPG, PNG, GIF, or WEBP image (max 2 MB).</span>
                        </label>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                            <p class="font-semibold text-slate-800">Tips</p>
                            <ul class="mt-2 list-disc space-y-1 pl-4">
                                <li>Showcase your office, team, or brand identity.</li>
                                <li>Use 1200×800 or 4:3 images for crisp display.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="space-y-4 rounded-3xl border border-slate-200 bg-slate-50 p-6">
                        <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Role narrative</h3>
                        <p class="text-xs text-slate-500">
                            These sections will be combined into the published job description. Keep each concise (≤400 characters).
                        </p>

                        <label class="flex flex-col gap-2">
                            <span class="text-sm font-semibold text-slate-700">Role overview<span class="text-rose-500">*</span></span>
                            <textarea id="role_overview"
                                      rows="4"
                                      maxlength="400"
                                      required
                                      class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900.shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('role_overview') }}</textarea>
                            <span id="overviewError" class="hidden text-xs font-semibold text-rose-600">Share a brief summary of the role (max 400 characters).</span>
                        </label>

                        <label class="flex flex-col gap-2">
                            <span class="text-sm font-semibold text-slate-700">Key responsibilities</span>
                            <textarea id="responsibilities"
                                      rows="4"
                                      maxlength="400"
                                      class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900.shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('responsibilities') }}</textarea>
                        </label>

                        <label class="flex flex-col gap-2">
                            <span class="text-sm font-semibold text-slate-700">Success metrics</span>
                            <textarea id="success_metrics"
                                      rows="3"
                                      maxlength="300"
                                      class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900.shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('success_metrics') }}</textarea>
                        </label>

                        <label class="flex flex-col gap-2">
                            <span class="text-sm font-semibold text-slate-700">Team & culture</span>
                            <textarea id="team_culture"
                                      rows="3"
                                      maxlength="300"
                                      class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900.shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('team_culture') }}</textarea>
                        </label>
                    </div>

                    <input type="hidden" id="body" name="body" value="{{ old('body') }}">

                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <p class="text-xs text-slate-400">Publishing creates a live listing instantly. Review details first to avoid edits later.</p>
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M5 13l4 4L19 7" />
                            </svg>
                            Publish role
                        </button>
                    </div>
                </form>
            </section>

            <aside class="space-y-6">
                <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">What great postings include</h3>
                    <ul class="mt-3 space-y-3 text-sm text-slate-600">
                        <li class="flex gap-3">
                            <span class="inline-flex h-2 w-2 rounded-full bg-emerald-600"></span>
                            A clear mission and why the role matters for your product or customers.
                        </li>
                        <li class="flex gap-3">
                            <span class="inline-flex h-2 w-2 rounded-full bg-emerald-600"></span>
                            Success metrics that help candidates understand impact in the first 90 days.
                        </li>
                        <li class="flex gap-3">
                            <span class="inline-flex h-2 w-2 rounded-full bg-emerald-600"></span>
                            Collaboration cues: who they’ll partner with, and how your team ships work.
                        </li>
                    </ul>
                </div>

                <div class="rounded-3xl bg-slate-900 p-8 text-white shadow-sm">
                    <h3 class="text-lg font-semibold">Need inspiration?</h3>
                    <p class="mt-3 text-sm text-slate-200">
                        Browse recently posted roles to see how other hiring teams position scope, metrics, and culture to stand out.
                    </p>
                    <a href="{{ route('employer.all_posts') }}"
                       class="mt-6 inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-slate-900 transition hover:bg-yellow-200">
                        View recent postings
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

@include('layouts.footer')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('employerPostForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        let formIsValid = true;

        const titleField = document.getElementById('title');
        const titleError = document.getElementById('titleError');
        if (!titleField.value.trim() || titleField.value.length > 255) {
            titleError.classList.remove('hidden');
            formIsValid = false;
        } else {
            titleError.classList.add('hidden');
        }

        const locationField = document.getElementById('location');
        const locationError = document.getElementById('locationError');
        const locationRegex = /^[\p{L}\s\-.,()]+$/u;
        if (!locationField.value.trim() || !locationRegex.test(locationField.value) || locationField.value.length > 255) {
            locationError.classList.remove('hidden');
            formIsValid = false;
        } else {
            locationError.classList.add('hidden');
        }

        const positionField = document.getElementById('position');
        const positionError = document.getElementById('positionError');
        if (!positionField.value.trim() || positionField.value.length > 255) {
            positionError.classList.remove('hidden');
            formIsValid = false;
        } else {
            positionError.classList.add('hidden');
        }

        const salaryField = document.getElementById('salary');
        const salaryError = document.getElementById('salaryError');
        if (salaryField.value.trim() !== '') {
            const salaryValue = Number(salaryField.value);
            if (Number.isNaN(salaryValue)) {
                salaryError.classList.remove('hidden');
                formIsValid = false;
            } else {
                salaryError.classList.add('hidden');
            }
        } else {
            salaryError.classList.add('hidden');
        }

        const skillsField = document.getElementById('skills');
        const skillsError = document.getElementById('skillsError');
        if (!skillsField.selectedOptions || skillsField.selectedOptions.length === 0) {
            skillsError.classList.remove('hidden');
            formIsValid = false;
        } else {
            skillsError.classList.add('hidden');
        }

        const additionalLinksField = document.getElementById('additional_links');
        const additionalLinksError = document.getElementById('additionalLinksError');
        if (additionalLinksField.value.trim() !== '') {
            let isValidUrl = true;
            try {
                new URL(additionalLinksField.value);
            } catch (error) {
                isValidUrl = false;
            }
            if (!isValidUrl) {
                additionalLinksError.classList.remove('hidden');
                formIsValid = false;
            } else {
                additionalLinksError.classList.add('hidden');
            }
        } else {
            additionalLinksError.classList.add('hidden');
        }

        const imageField = document.getElementById('image');
        const imageError = document.getElementById('imageError');
        if (!imageField.files || imageField.files.length === 0) {
            imageError.textContent = 'Please upload an image to support your post.';
            imageError.classList.remove('hidden');
            formIsValid = false;
        } else {
            const allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            const imageFile = imageField.files[0];
            if (!allowedImageTypes.includes(imageFile.type) || imageFile.size > 2 * 1024 * 1024) {
                imageError.textContent = 'Please upload a JPG, PNG, GIF, or WEBP image up to 2MB.';
                imageError.classList.remove('hidden');
                formIsValid = false;
            } else {
                imageError.classList.add('hidden');
            }
        }

        const overviewField = document.getElementById('role_overview');
        const overviewError = document.getElementById('overviewError');
        if (!overviewField.value.trim() || overviewField.value.length > 400) {
            overviewError.classList.remove('hidden');
            formIsValid = false;
        } else {
            overviewError.classList.add('hidden');
        }

        const responsibilitiesField = document.getElementById('responsibilities');
        const successField = document.getElementById('success_metrics');
        const teamField = document.getElementById('team_culture');

        const sections = [
            { heading: 'Role Overview', value: overviewField.value.trim() },
            { heading: 'Key Responsibilities', value: responsibilitiesField.value.trim() },
            { heading: 'Success Metrics', value: successField.value.trim() },
            { heading: 'Team & Culture', value: teamField.value.trim() },
        ];

        let compiledBody = sections
            .filter(section => section.value !== '')
            .map(section => `### ${section.heading}\n${section.value}`)
            .join("\n\n");

        const bodyInput = document.getElementById('body');
        bodyInput.value = compiledBody.trim();

        if (!bodyInput.value) {
            overviewError.classList.remove('hidden');
            formIsValid = false;
        }

        if (!formIsValid) {
            e.preventDefault();
            const firstError = form.querySelector('.text-rose-600:not(.hidden)');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
});
</script>
