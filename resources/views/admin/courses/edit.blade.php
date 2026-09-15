@extends('layouts.admin')

@section('title', 'Edit Course')

@section('content')

<div class="p-4 lg:p-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">
            Edit Course
        </h1>
    </div>

    <form method="POST"
          action="{{ route('admin.courses.update', $course) }}">

        @csrf
        @method('PUT')

        @include('admin.courses._form')

    </form>

</div>

@endsection