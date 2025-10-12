@include('layouts.header')

<section class="relative isolate overflow-hidden bg-slate-900">
    <img src="{{ asset('storage/images/A_horizontal_image_related_to_the_IT_industry,_fea.jpg') }}"
         alt="Creative workspace"
         class="absolute inset-0 h-full w-full object-cover opacity-40">
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/80 to-slate-900/65"></div>

    <div class="relative mx-auto flex max-w-6xl flex-col gap-10 px-6 py-20 sm:py-24 lg:px-8">
        <div class="max-w-2xl">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-yellow-300">
                Join the network
            </p>
            <h1 class="mt-4 text-4xl font-semibold text-white sm:text-5xl">
                Build a profile recruiters remember.
            </h1>
            <p class="mt-3 text-lg text-slate-200 sm:text-xl">
                Share your impact, surface roles that fit, and stay ready for introductions from teams that value your craft.
            </p>
        </div>
    </div>
</section>

<main class="bg-slate-100">
    <div class="mx-auto max-w-6xl px-6 pb-20 pt-12 sm:px-8">
        <div class="grid gap-10 lg:grid-cols-[1fr,0.8fr]">
            <section class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-2xl font-semibold text-slate-900">Create a candidate account</h2>
                <p class="mt-2 text-sm text-slate-500">
                    Publish a structured resume profile, track ATS feedback, and connect with hiring teams in minutes.
                </p>

                <form method="POST"
                      action="{{ route('register') }}"
                      class="mt-10 space-y-6">
                    @csrf

                    <x-validation-errors class="mb-4" />

                    <label class="flex flex-col gap-2">
                        <span class="text-sm font-semibold text-slate-700">Full name</span>
                        <x-input id="name"
                                 class="block mt-1 w-full"
                                 type="text"
                                 name="name"
                                 :value="old('name')"
                                 required
                                 autofocus
                                 autocomplete="name" />
                    </label>

                    <label class="flex flex-col gap-2">
                        <span class="text-sm font-semibold text-slate-700">Email</span>
                        <x-input id="email"
                                 class="block mt-1 w-full"
                                 type="email"
                                 name="email"
                                 :value="old('email')"
                                 required
                                 autocomplete="username" />
                    </label>

                    <label class="flex flex-col gap-2">
                        <span class="text-sm font-semibold text-slate-700">Password</span>
                        <x-input id="password"
                                 class="block mt-1 w-full"
                                 type="password"
                                 name="password"
                                 required
                                 autocomplete="new-password" />
                        <span class="text-xs text-slate-400">Minimum 8 characters. Mix letters, numbers, and symbols for best security.</span>
                    </label>

                    <label class="flex flex-col gap-2">
                        <span class="text-sm font-semibold text-slate-700">Confirm password</span>
                        <x-input id="password_confirmation"
                                 class="block mt-1 w-full"
                                 type="password"
                                 name="password_confirmation"
                                 required
                                 autocomplete="new-password" />
                    </label>

                    <div class="flex flex-col gap-4">
                        <x-button class="w-full justify-center bg-blue-600 hover:bg-blue-700">
                            {{ __('Create account') }}
                        </x-button>
                        <p class="text-sm text-slate-500">
                            Already have an account?
                            <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700">
                                Log in
                            </a>
                        </p>
                        <p class="text-sm text-slate-500">
                            Hiring talent?
                            <a href="{{ route('employer.register') }}" class="font-semibold text-blue-600 hover:text-blue-700">
                                Create an employer account
                            </a>
                        </p>
                    </div>
                </form>
            </section>

            <aside class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">Why candidates love ProSnap</h3>
                    <ul class="mt-3 space-y-3 text-sm text-slate-600">
                        <li class="flex gap-3">
                            <span class="inline-flex h-2 w-2 rounded-full bg-blue-600"></span>
                            Structured storytelling that highlights wins, collaboration style, and availability.
                        </li>
                        <li class="flex gap-3">
                            <span class="inline-flex h-2 w-2 rounded-full bg-blue-600"></span>
                            On-demand ATS feedback so you can iterate before pressing send.
                        </li>
                        <li class="flex gap-3">
                            <span class="inline-flex h-2 w-2 rounded-full bg-blue-600"></span>
                            Share a single link with recruiters to showcase your latest updates.
                        </li>
                    </ul>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">Need an employer account?</h3>
                    <p class="mt-3 text-sm text-slate-600">
                        Publish roles, manage applicants, and collaborate with your hiring squad from a single workspace.
                    </p>
                    <a href="{{ route('employer.register') }}"
                       class="mt-6 inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                        Employer sign up
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
