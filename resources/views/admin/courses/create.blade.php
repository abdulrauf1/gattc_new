@extends('layouts.admin')

@section('title', 'Add Course')

@section('content')

<div class="p-4 lg:p-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">
            Add Course
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Create a new GATTC course.
        </p>
    </div>


    <form method="POST"
          action="{{ route('admin.courses.store') }}">

        @csrf

        @include('admin.courses._form')

    </form>

</div>

@endsection