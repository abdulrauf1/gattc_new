@extends('layouts.admin')

@section('page-heading', 'Add Course Category')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="mb-4">
        <h1 class="text-xl font-bold">
            Add Course Category
        </h1>
        <p class="text-xs text-gray-500">
            Create a new course category.
        </p>
    </div>

    <form
        method="POST"
        action="{{ route('admin.course-categories.store') }}">

        @include(
            'admin.course-categories.form',
            ['courseCategory' => new \App\Models\CourseCategory()]
        )

    </form>

</div>

@endsection

@push('styles')
@include('admin.partials.form-styles')
@endpush