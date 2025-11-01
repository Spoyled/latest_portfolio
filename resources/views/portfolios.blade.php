@include('layouts.header')

@php
    $totalPosts = $myPosts->count();
    $latestPost = $myPosts->sortByDesc(function ($post) {
        return $post->updated_at ?? $post->created_at;
    })->first();
    $latestDate = null;
    if ($latestPost) {
        $when = $latestPost->updated_at ?? $latestPost->created_at;
        if ($when instanceof \Carbon\Carbon) {
            $latestDate = $when->format('M d, Y');
        } elseif (!empty($when)) {
            $latestDate = \Carbon\Carbon::parse($when)->format('M d, Y');
        }
    }
@endphp

<section class="relative isolate overflow-hidden bg-slate-900">
    <img src="{{ asset('storage/images/A_horizontal_image_related_to_the_IT_industry,_fea.jpg') }}"
         alt="Creative collaboration"
         class="absolute inset-0 h-full w-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/75 to-slate-900/60"></div>

    <div class="relative mx-auto flex max-w-6xl flex-col gap-10 px-6 py-16 sm:py-20 lg:px-8">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-yellow-300">
                Portfolio overview
            </p>
            <h1 class="mt-4 text-4xl font-semibold text-white sm:text-5xl">
                Showcase the work you are proud of.
            </h1>
            <p class="mt-3 text-lg text-slate-200 sm:text-xl">
                Keep your strongest projects in one place so hiring teams can quickly understand the value you bring.
            </p>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="{{ route('make_post.index') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-yellow-400 px-6 py-3 text-base font-semibold text-slate-900 transition hover:bg-yellow-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-200">
                    Create a new highlight
                </a>
                <a href="{{ route('all_posts.index') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-white/30 px-6 py-3 text-base font-semibold text-white transition hover:border-yellow-300 hover:text-yellow-200">
                    Explore community posts
                </a>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-white/20 bg-white/10 p-5 backdrop-blur-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-200">Total entries</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ $totalPosts }}</p>
                <p class="mt-2 text-sm text-slate-200">A concise portfolio helps employers see your consistency and growth.</p>
            </div>
            <div class="rounded-2xl border border-white/20 bg-white/10 p-5 backdrop-blur-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-200">Latest update</p>
                <p class="mt-3 text-2xl font-semibold text-white">
                    {{ $latestDate ?? 'Add your first post' }}
                </p>
                <p class="mt-2 text-sm text-slate-200">
                    Refresh entries frequently to keep your skill story current.
                </p>
            </div>
            <div class="rounded-2xl border border-white/20 bg-white/10 p-5 backdrop-blur-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-200">Next action</p>
                <p class="mt-3 text-2xl font-semibold text-white">
                    {{ $totalPosts === 0 ? 'Get started' : 'Fine-tune details' }}
                </p>
                <p class="mt-2 text-sm text-slate-200">
                    Tailor descriptions so the impact and outcomes are easy to digest.
                </p>
            </div>
        </div>
    </div>
</section>

<main class="bg-slate-100">
    <div class="mx-auto max-w-6xl px-6 pb-20 pt-12 sm:px-8">
        <div class="mb-10 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-3xl font-semibold text-slate-900">My spotlight posts</h2>
                <p class="mt-2 text-sm text-slate-500">
                    Keep your most relevant accomplishments polished and ready for recruiters.
                </p>
            </div>
            <a href="{{ route('employer.make_post') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                </svg>
                New post
            </a>
        </div>

        @if ($myPosts->isEmpty())
            <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-12 text-center shadow-sm">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <h3 class="mt-6 text-xl font-semibold text-slate-900">No posts yet — let’s create your first one.</h3>
                <p class="mt-2 text-sm text-slate-500">
                    Share a project that highlights your strengths, metrics, and unique approach. It takes only a few minutes.
                </p>
                <a href="{{ route('make_post.index') }}"
                   class="mt-6 inline-flex items-center gap-2 rounded-xl bg-yellow-400 px-6 py-3 text-sm font-semibold text-slate-900 transition hover:bg-yellow-300">
                    Start building your portfolio
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        @else
            <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($myPosts as $post)
                    @php
                        $route = route('posts.show', $post->id);
                        $when = $post->updated_at ?? $post->created_at ?? null;
                        $whenStr = '';
                        if ($when instanceof \Carbon\Carbon) {
                            $whenStr = $when->format('M d, Y');
                        } elseif (is_string($when)) {
                            $whenStr = \Carbon\Carbon::parse($when)->format('M d, Y');
                        }
                        $imageUrl = !empty($post->image)
                            ? asset('storage/posts/' . $post->image)
                            : 'https://www.pngall.com/wp-content/uploads/12/Avatar-Profile-Vector.png';
                        $summarySource = $post->summary
                            ?? $post->short_description
                            ?? $post->description
                            ?? $post->body
                            ?? null;
                        $summary = is_string($summarySource)
                            ? \Illuminate\Support\Str::limit(strip_tags($summarySource), 120)
                            : 'Open the post to see skills, accomplishments, and supporting media.';
                        $skills = $post->skills ?? [];
                        if (is_string($skills)) {
                            $skills = array_filter(array_map('trim', explode(',', $skills)));
                        }
                    @endphp
                    <article class="group flex h-full flex-col overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="relative h-48 w-full overflow-hidden">
                            <img src="{{ $imageUrl }}" alt="{{ $post->title }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                            <span class="absolute left-4 top-4 inline-flex items-center rounded-full bg-blue-600 px-3 py-1 text-xs font-semibold text-white">
                                {{ $whenStr ?: 'Recent' }}
                            </span>
                        </div>
                        <div class="flex flex-1 flex-col gap-4 p-6">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 transition group-hover:text-blue-600">
                                    {{ $post->title }}
                                </h3>
                                @if (!empty($post->position) || !empty($post->location))
                                    <p class="mt-1 text-sm font-medium text-slate-500">
                                        @if (!empty($post->position))
                                            <span>{{ $post->position }}</span>
                                        @endif
                                        @if (!empty($post->position) && !empty($post->location))
                                            <span class="mx-2 text-slate-400">•</span>
                                        @endif
                                        @if (!empty($post->location))
                                            <span>{{ $post->location }}</span>
                                        @endif
                                    </p>
                                @endif
                            </div>
                            <p class="text-sm text-slate-600">{{ $summary }}</p>
                            @if (!empty($skills))
                                <div class="flex flex-wrap gap-2">
                                    @foreach (array_slice($skills, 0, 4) as $skill)
                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                            {{ $skill }}
                                        </span>
                                    @endforeach
                                    @if (count($skills) > 4)
                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">
                                            +{{ count($skills) - 4 }} more
                                        </span>
                                    @endif
                                </div>
                            @endif
                            <div class="mt-auto flex items-center justify-between">
                                <a href="{{ $route }}"
                                   class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 transition hover:text-blue-700">
                                    View details
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</main>

@include('layouts.footer')
