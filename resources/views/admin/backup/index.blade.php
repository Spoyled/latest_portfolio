@extends(backpack_view('blank'))

@section('content')
    <div class="mb-4">
        <form method="POST" action="{{ route('admin.backup.run') }}">
            @csrf
            <button class="btn btn-primary">Create a new backup</button>
        </form>
    </div>

    @if ($files->isEmpty())
        <div class="alert alert-info">There are no backups created</div>
    @else
        <ul class="list-group">
            @foreach ($files as $file)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $file }}
                    <a href="{{ route('admin.backup.download', ['file' => basename($file)]) }}" class="btn btn-sm btn-success">
                        Download
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
