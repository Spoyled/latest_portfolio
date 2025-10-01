@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Applicants for: {{ $post->title }}</h1>

    @foreach ($post->applicants as $applicant)
        <div class="bg-white shadow rounded p-4 mb-4">
            <p><strong>Name:</strong> {{ $applicant->name }}</p>
            <p><strong>Email:</strong> {{ $applicant->email }}</p>
            <p>
                <strong>CV:</strong> 
                <a href="{{ asset('storage/cvs/' . $applicant->pivot->cv_path) }}" target="_blank" class="text-blue-500 underline">Download CV</a>
            </p>

            <p><strong>Status:</strong>
                @if ($applicant->pivot->recruited)
                    <span class="text-green-600 font-semibold">Recruited</span>
                @elseif ($applicant->pivot->declined)
                    <span class="text-red-600 font-semibold">Declined</span>
                @else
                    <div class="flex space-x-2">
                        <!-- Recruit Form -->
                        <form method="POST" action="{{ route('applicants.recruit', [$post->id, $applicant->id]) }}"
                            class="decision-form" data-action="recruit" data-post-id="{{ $post->id }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="bg-green-500 text-white px-4 py-1 rounded hover:bg-green-600">
                                Mark as Recruited
                            </button>
                        </form>


                        <!-- Decline Form -->
                        <form method="POST" action="{{ route('applicants.decline', [$post->id, $applicant->id]) }}"
                            class="decision-form" data-action="decline" data-post-id="{{ $post->id }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="bg-red-500 text-white px-4 py-1 rounded hover:bg-red-600">
                                Decline
                            </button>
                        </form>
                    </div>
                @endif
            </p>

        </div>
    @endforeach

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.decision-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const action = form.dataset.action;
            const postId = form.dataset.postId;
            const actionText = action === 'recruit' ? 'mark this applicant as recruited' : 'decline this applicant';

            Swal.fire({
                title: `Are you sure you want to ${actionText}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then(result => {
                if (!result.isConfirmed) return;

                Swal.fire({
                    title: 'Will you continue looking for more applicants?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                }).then(async result => {
                    if (!result.isConfirmed) {
                        try {
                            const res = await fetch(`/posts/${postId}/close`, {
                                method: 'POST',
                                credentials: 'same-origin',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({})
                            });

                            let responseData = {};
                            try {
                                responseData = await res.clone().json();
                            } catch (_) {
                                responseData = { status: res.status };
                            }

                            if (res.ok) {
                                Swal.fire('Closed!', 'The post has been closed.', 'success')
                                    .then(() => form.submit());
                            } else {
                                Swal.fire('Error!', responseData?.error || 'Failed to close the post.', 'error');
                            }

                        } catch (err) {
                            console.error('Fetch error:', err);
                            Swal.fire('Error!', 'Network error while closing the post.', 'error');
                        }

                    } else {
                        form.submit();
                    }
                });
            });
        });
    });
});
</script>
@endpush
