@include('layouts.header')

<section class="relative isolate overflow-hidden bg-slate-900">
    <img src="{{ asset('storage/images/A_horizontal_image_related_to_the_IT_industry,_fea.jpg') }}"
         alt="Hiring team collaborating"
         class="absolute inset-0 h-full w-full object-cover opacity-40">
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/80 to-slate-900/65"></div>

    <div class="relative mx-auto flex max-w-6xl flex-col gap-10 px-6 py-20 sm:py-24 lg:px-8">
        <div class="max-w-2xl">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-yellow-300">
                Hiring portal
            </p>
            <h1 class="mt-4 text-4xl font-semibold text-white sm:text-5xl">
                Sign in to manage your talent pipeline.
            </h1>
            <p class="mt-3 text-lg text-slate-200 sm:text-xl">
                Publish roles, review applications, and collaborate with your hiring team in one streamlined workspace.
            </p>
        </div>
    </div>
</section>

<main class="bg-slate-100">
    <div class="mx-auto max-w-6xl px-6 pb-20 pt-12 sm:px-8">
        <div class="grid gap-10 lg:grid-cols-[1fr,0.8fr]">
            <section class="rounded-3xl bg-white p-8 text-slate-900 shadow-sm ring-1 ring-slate-200">
                <h2 class="text-2xl font-semibold text-slate-900">Employer login</h2>
                <p class="mt-2 text-sm text-slate-900">
                    Access your job posts, monitor candidate progress, and move offers forward quickly.
                </p>

                <form method="POST"
                      action="{{ route('employer.login') }}"
                      class="mt-10 space-y-6">
                    @csrf

                    <x-validation-errors class="mb-4" />

                    @if (session('status'))
                        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    <label class="flex flex-col gap-2">
                        <span class="text-sm font-semibold text-slate-900">Work email</span>
                        <x-input id="email"
                                 class="block mt-1 w-full"
                                 type="email"
                                 name="email"
                                 :value="old('email')"
                                 required
                                 autofocus
                                 autocomplete="username" />
                    </label>

                    <label class="flex flex-col gap-2">
                        <span class="text-sm font-semibold text-slate-900">Password</span>
                        <x-input id="password"
                                 class="block mt-1 w-full"
                                 type="password"
                                 name="password"
                                 required
                                 autocomplete="current-password" />
                    </label>

                    <div class="flex items-center justify-between text-sm text-slate-900">
                        <label for="remember_me" class="flex cursor-pointer items-center gap-2 text-slate-900">
                            <x-checkbox id="remember_me" name="remember" />
                            <span>Keep me signed in</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="font-semibold text-blue-600 hover:text-blue-700"
                               href="{{ route('password.request') }}">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <div class="flex flex-col gap-4 text-slate-900">
                        <x-button class="w-full justify-center bg-slate-900 hover:bg-slate-800">
                            {{ __('Log in') }}
                        </x-button>
                        <p class="text-sm text-slate-900">
                            Need an employer account?
                            <a href="{{ route('employer.register') }}" class="font-semibold text-blue-600 hover:text-blue-700">
                                Create one now
                            </a>
                        </p>
                        <p class="text-sm text-slate-900">
                            Looking for job opportunities?
                            <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700">
                                Log in as a candidate
                            </a>
                        </p>
                    </div>
                </form>
            </section>

            <aside class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-8 text-slate-900 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">Why teams choose ProSnap</h3>
                    <ul class="mt-3 space-y-3 text-sm text-slate-900">
                        <li class="flex gap-3">
                            <span class="inline-flex h-2 w-2 rounded-full bg-emerald-600"></span>
                            Publish roles with clarity around success metrics and collaboration expectations.
                        </li>
                        <li class="flex gap-3">
                            <span class="inline-flex h-2 w-2 rounded-full bg-emerald-600"></span>
                            See candidate ATS scores and tailored summaries before scheduling interviews.
                        </li>
                        <li class="flex gap-3">
                            <span class="inline-flex h-2 w-2 rounded-full bg-emerald-600"></span>
                            Keep your hiring squad aligned with shared notes and status updates.
                        </li>
                    </ul>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-8 text-slate-900 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">Quick tip</h3>
                    <p class="mt-3 text-sm text-slate-900">
                        New roles perform best when you highlight impact, salary expectations, and team culture in the description.
                    </p>
                </div>
            </aside>
        </div>
    </div>
</main>

@include('layouts.footer')
