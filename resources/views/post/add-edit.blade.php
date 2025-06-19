@extends('layout.app')

@section('content')
    <div class="card mt-4">
        <div class="card-header">
            <h4>{{ isset($post) ? 'Edit Post' : 'Add New Post' }}</h4>
        </div>

        <div class="card-body">
            <form action="{{route('post.storeOrUpdate')}}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- id hidden --}}
                @if(isset($post) && $post->id)
                    <input type="hidden" name="id" value="{{ $post->id}}">
                @endif


                {{-- Title --}}
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input
                        type="text"
                        name="title"
                        class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title', $post->title ?? '') }}"
                        required
                    >
                    @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea
                        name="description"
                        class="form-control @error('description') is-invalid @enderror"
                    >{{ old('description', $post->description ?? '') }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Image --}}
                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input
                        type="file"
                        name="image"
                        accept="image/*"
                        class="form-control @error('image') is-invalid @enderror"
                    >
                    @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    {{-- Display Image --}}
                    @if(isset($post) && $post->image)
                        <img src="{{ $post->image }}" width="100" class=" mt-2 rounded">
                    @endif
                </div>

                <div class="">
                    <button type="submit" class="btn btn-success">
                        {{ isset($post) ? 'Update' : 'Create' }} Post
                    </button>

                    <a href="{{ route('post.list') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>

            </form>

        </div>
    </div>


@endsection
