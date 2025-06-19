@extends('layout.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Post List</h2>
            <a href="{{route('post.addEdit')}}" class="btn btn-success">+ Create New Post</a>
        </div>


        <table class="table table-bordered table-hover">
            <thead class="table-light">
            <tr>
                <th>Sr No.</th>
                <th>Title</th>
                <th>Description</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse($posts as $post)
                <tr>
                    <td>{{ $loop->iteration}}</td>

                    <td>{{ $post->title }}</td>
                    <td>{{ $post->description }}</td>
                    <td>
                        {{-- <img src="{{ asset($post->getImageAttribute()) }}" width="100" class="rounded mx-auto d-block">--}}
                        <img src="{{ $post->image}}" width="100" class="rounded mx-auto d-block">
                    </td>

                    <td>
                        <div class=" d-flex gap-1">
                            <a href="{{ route('post.addEdit', ['id' => Crypt::encrypt($post->id)]) }}"
                               class="btn btn-sm btn-primary">Edit</a>

                            <form action="{{ route('post.delete') }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $post->id}}">
                                <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure?')">Delete
                                </button>
                            </form>

                            {{-- We can also use AJAX for delete operation without using a form.--}}
                            <button onclick="onDeletePost({{ $post->id }})" class="btn btn-sm btn-warning">Delete
                            </button>
                        </div>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">No posts found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $posts->links('pagination::bootstrap-5') }}
        </div>
    </div>


@endsection

@section('scripts')
    {{--    We are using SweetAlert only here, so we include this script here.--}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function onDeletePost(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't Remove this! Using AJAX",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-outline-danger ml-1'
                },
                buttonsStyling: false
            }).then(function (result) {
                if (result.value) {
                    $.post("{{ route('post.onDelete') }}", {
                        _token: "{{ csrf_token() }}",
                        id: id
                    }, function (respo) {
                        Swal.fire({
                            icon: respo.success ? 'success' : 'error',
                            title: respo.success ? 'Deleted!' : 'Failed',
                            text: respo.msg,
                            customClass: {confirmButton: 'btn btn-success'}
                        });

                        if (respo.success) {
                            setTimeout(function () {
                                window.location.reload();
                            }, 700);
                        }
                    });
                }
            });
        }
    </script>
@endsection
