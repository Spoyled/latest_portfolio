@include('layouts.header')

<section class="relative isolate overflow-hidden bg-slate-900">
    <img src="{{ asset('storage/images/A_horizontal_image_related_to_the_IT_industry,_fea.jpg') }}"
         alt="Hiring team planning"
         class="absolute inset-0 h-full w-full object-cover opacity-40">
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/80 to-slate-900/65"></div>

    <div class="relative mx-auto flex max-w-6xl flex-col gap-10 px-6 py-20 sm:py-24 lg:px-8">
        <div class="max-w-2xl">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-yellow-300">
                Build your hiring hub
            </p>
            <h1 class="mt-4 text-4xl font-semibold text-white sm:text-5xl">
                Create an employer account.
            </h1>
            <p class="mt-3 text-lg text-slate-200 sm:text-xl">
                Publish roles with clarity, review applications with context, and find the right talent faster.
            </p>
        </div>
    </div>
</section>

<main class="bg-slate-100">
    <div class="mx-auto max-w-6xl px-6 pb-20 pt-12 sm:px-8">
        <div class="grid gap-10 lg:grid-cols-[1fr,0.8fr]">
            <section class="rounded-3xl bg-white p-8 text-slate-900 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-2xl font-semibold text-slate-900">Create an employer account</h2>
                <p class="mt-2 text-sm text-slate-900">
                    Share actionable job posts, receive structured profiles, and keep your pipeline organised.
                </p>

                <form method="POST"
                      action="{{ route('employer.register') }}"
                      class="mt-10 space-y-6">
                    @csrf

                    <x-validation-errors class="mb-4" />

                    <label class="flex flex-col gap-2">
                        <span class="text-sm font-semibold text-slate-900">Company name</span>
                        <x-input id="name"
                                 class="block mt-1 w-full"
                                 type="text"
                                 name="name"
                                 :value="old('name')"
                                 required
                                 autofocus
                                 autocomplete="organization" />
                    </label>

                    <label class="flex flex-col gap-2">
                        <span class="text-sm font-semibold text-slate-900">Work email</span>
                        <x-input id="email"
                                 class="block mt-1 w-full"
                                 type="email"
                                 name="email"
                                 :value="old('email')"
                                 required
                                 autocomplete="username" />
                    </label>

                    <label class="flex flex-col gap-2">
                        <span class="text-sm font-semibold text-slate-900">Password</span>
                        <x-input id="password"
                                 class="block mt-1 w-full"
                                 type="password"
                                 name="password"
                                 required
                                 autocomplete="new-password" />
                        <span class="text-xs text-slate-800">Minimum 8 characters. Include numbers or symbols for added security.</span>
                    </label>

                    <label class="flex flex-col gap-2">
                        <span class="text-sm font-semibold text-slate-900">Confirm password</span>
                        <x-input id="password_confirmation"
                                 class="block mt-1 w-full"
                                 type="password"
                                 name="password_confirmation"
                                 required
                                 autocomplete="new-password" />
                    </label>

                    <div class="flex flex-col gap-4 text-slate-900">
                        <x-button class="w-full justify-center bg-slate-900 hover:bg-slate-800">
                            {{ __('Create employer account') }}
                        </x-button>
                        <p class="text-sm text-slate-900">
                            Already recruiting with ProSnap?
                            <a href="{{ route('employer.login') }}" class="font-semibold text-blue-600 hover:text-blue-700">
                                Log in
                            </a>
                        </p>
                        <p class="text-sm text-slate-900">
                            Looking for work?
                            <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-700">
                                Create a candidate profile
                            </a>
                        </p>
                    </div>
                </form>
            </section>

            <aside class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-8 text-slate-900 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">What you’ll get</h3>
                    <ul class="mt-3 space-y-3 text-sm text-slate-900">
                        <li class="flex gap-3">
                            <span class="inline-flex h-2 w-2 rounded-full bg-emerald-600"></span>
                            Publish polished job descriptions with collaboration, metrics, and culture cues.
                        </li>
                        <li class="flex gap-3">
                            <span class="inline-flex h-2 w-2 rounded-full bg-emerald-600"></span>
                            Review structured candidate profiles that highlight recent wins and availability.
                        </li>
                        <li class="flex gap-3">
                            <span class="inline-flex h-2 w-2 rounded-full bg-emerald-600"></span>
                            Track candidate status and share notes across your hiring squad seamlessly.
                        </li>
                    </ul>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-8 text-slate-900 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">Need assistance?</h3>
                    <p class="mt-3 text-sm text-slate-900">
                        Our team can help import existing roles or advise on how to tailor your job posts for ProSnap’s candidate audience.
                    </p>
                    <a href="mailto:support@prosnap.io"
                       class="mt-6 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                        Contact support
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M16 12H8m8 0l-4 4m4-4l-4-4" />
                        </svg>
                    </a>
                </div>
            </aside>
        </div>
    </div>
</main>

@include('layouts.footer')
