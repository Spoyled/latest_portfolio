@include('layouts.header')

@php
    $skillPlaceholder = 'e.g. Product strategy, OKRs, SaaS growth, Jira, Confluence';
@endphp

<section class="relative isolate overflow-hidden bg-slate-900">
    <img src="{{ asset('storage/images/A_horizontal_image_related_to_the_IT_industry,_fea.jpg') }}"
         alt="Modern workplace"
         class="absolute inset-0 h-full w-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/75 to-slate-900/60"></div>

    <div class="relative mx-auto flex max-w-6xl flex-col gap-10 px-6 py-16 sm:py-20 lg:px-8">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-yellow-300">
                Fine-tune your opening
            </p>
            <h1 class="mt-4 text-4xl font-semibold text-white sm:text-5xl">
                Refresh this job post.
            </h1>
            <p class="mt-3 text-lg text-slate-200 sm:text-xl">
                Keep the role sharp, outline the impact, and share logistics so the right candidates step forward.
            </p>
        </div>
    </div>
</section>

<main class="bg-slate-100">
    <div class="mx-auto max-w-6xl px-6 pb-20 pt-12 sm:px-8">
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

        <div class="grid gap-10 lg:grid-cols-[1fr,0.65fr]">
            <section class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-2xl font-semibold text-slate-900">Role details</h2>
                <p class="mt-2 text-sm text-slate-500">
                    Clarify outcomes, success metrics, and collaboration expectations. Precision attracts relevant candidates faster.
                </p>

                <form method="POST"
                      action="{{ route('employer.posts.update', $post->id) }}"
                      enctype="multipart/form-data"
                      class="mt-8 space-y-6">
                    @csrf
                    @method('PUT')

                    <label class="flex flex-col gap-2">
                        <span class="text-sm font-semibold text-slate-700">Job title<span class="text-rose-500">*</span></span>
                        <input type="text"
                               name="title"
                               value="{{ old('title', $post->title) }}"
                               maxlength="255"
                               required
                               class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <span class="text-xs text-slate-400">Keep it clear and searchable (e.g., “Senior Product Designer, Growth”).</span>
                    </label>

                    <label class="flex flex-col gap-2">
                        <span class="text-sm font-semibold text-slate-700">What success looks like<span class="text-rose-500">*</span></span>
                        <textarea name="body"
                                  rows="5"
                                  maxlength="2000"
                                  required
                                  class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('body', $post->body) }}</textarea>
                        <span class="text-xs text-slate-400">Outline mission, responsibilities, and early milestones within 2000 characters.</span>
                    </label>

                    <div class="grid gap-6 md:grid-cols-2">
                        <label class="flex flex-col gap-2">
                            <span class="text-sm font-semibold text-slate-700">Location<span class="text-rose-500">*</span></span>
                            <input type="text"
                                   name="location"
                                   value="{{ old('location', $post->location) }}"
                                   maxlength="255"
                                   required
                                   class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <span class="text-xs text-slate-400">Include remote/setup expectations if relevant.</span>
                        </label>

                        <label class="flex flex-col gap-2">
                            <span class="text-sm font-semibold text-slate-700">Seniority / team<span class="text-rose-500">*</span></span>
                            <input type="text"
                                   name="position"
                                   value="{{ old('position', $post->position) }}"
                                   maxlength="255"
                                   required
                                   class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <span class="text-xs text-slate-400">e.g., “Lead role in Platform Engineering” or “Individual contributor · Design Systems”.</span>
                        </label>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <label class="flex flex-col gap-2">
                            <span class="text-sm font-semibold text-slate-700">Compensation (monthly, €)</span>
                            <input type="number"
                                   step="0.01"
                                   name="salary"
                                   value="{{ old('salary', $post->salary) }}"
                                   class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <span class="text-xs text-slate-400">Optional: sharing ranges boosts application quality.</span>
                        </label>

                        <label class="flex flex-col gap-2">
                            <span class="text-sm font-semibold text-slate-700">Skills & tools<span class="text-rose-500">*</span></span>
                            <input type="text"
                                   name="skills"
                                   value="{{ old('skills', $post->skills) }}"
                                   maxlength="255"
                                   required
                                   placeholder="{{ $skillPlaceholder }}"
                                   class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <span class="text-xs text-slate-400">Separate entries with commas.</span>
                        </label>
                    </div>

                    <label class="flex flex-col gap-2">
                        <span class="text-sm font-semibold text-slate-700">Supporting link</span>
                        <input type="url"
                               name="additional_links"
                               value="{{ old('additional_links', $post->additional_links) }}"
                               placeholder="https://yourcompany.com/careers"
                               class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </label>

                    <label class="flex flex-col gap-2">
                        <span class="text-sm font-semibold text-slate-700">Update hero image</span>
                        <input type="file"
                               name="image"
                               accept=".jpg,.jpeg,.png,.gif,.webp"
                               class="w-full rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-600 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <span class="text-xs text-slate-400">Square or 16:9 images work best (max 2 MB).</span>
                    </label>

                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <p class="text-xs text-slate-400">Updates are live immediately — double-check compensation, skills, and contact info.</p>
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-yellow-400 px-6 py-3 text-sm font-semibold text-slate-900 shadow-sm transition hover:bg-yellow-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M5 13l4 4L19 7" />
                            </svg>
                            Save changes
                        </button>
                    </div>
                </form>
            </section>

            <aside class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">Tips for standout roles</h3>
                    <ul class="mt-4 space-y-3 text-sm text-slate-600">
                        <li class="flex gap-3">
                            <span class="mt-1 inline-flex h-2 w-2 rounded-full bg-blue-500"></span>
                            Lead with impact — specify what the hire will own in the first 90 days.
                        </li>
                        <li class="flex gap-3">
                            <span class="mt-1 inline-flex h-2 w-2 rounded-full bg-blue-500"></span>
                            Clarify collaboration: who they report to, partner with, and influence.
                        </li>
                        <li class="flex gap-3">
                            <span class="mt-1 inline-flex h-2 w-2 rounded-full bg-blue-500"></span>
                            Share cultural signals: rituals, values, or ways your team supports growth.
                        </li>
                    </ul>
                </div>

                <div class="rounded-3xl bg-slate-900 p-8 text-white shadow-sm">
                    <h3 class="text-lg font-semibold">Need a new role instead?</h3>
                    <p class="mt-3 text-sm text-slate-200">
                        Use the “Make a Post” workflow when you’re ready to announce a fresh opening. We’ll highlight it across candidate dashboards immediately.
                    </p>
                    <a href="{{ route('employer.make_post') }}"
                       class="mt-6 inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-slate-900 transition hover:bg-yellow-200">
                        Create new job post
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M12 4v16m8-8H4" />
                        </svg>
                    </a>
                </div>
            </aside>
        </div>
    </div>
</main>

@include('layouts.footer')
