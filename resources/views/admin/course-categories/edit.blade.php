@extends('layouts.admin')

@section('page-heading', 'Edit Course Category')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="mb-4">
        <h1 class="text-xl font-bold">
            Edit Course Category
        </h1>
    </div>

    <form
        method="POST"
        action="{{ route(
            'admin.course-categories.update',
            $courseCategory
        ) }}">

        @include(
            'admin.course-categories.form',
            ['courseCategory' => $courseCategory]
        )

    </form>

</div>

@endsection

@push('styles')
@include('admin.partials.form-styles')
@endpush