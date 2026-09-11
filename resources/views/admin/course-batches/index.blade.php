@extends('layouts.admin')

@section('title', 'Course Batches')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

        <div class="flex items-start gap-4">

            <div class="w-12 h-12 rounded-2xl bg-emerald-50
                        flex items-center justify-center shrink-0">

                <i data-lucide="calendar-range"
                   class="w-6 h-6 text-emerald-600"></i>

            </div>

            <div>

                <h1 class="text-2xl font-bold text-slate-800">
                    Course Batches
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Manage training batches, schedules, capacity and status.
                </p>

            </div>

        </div>


        {{-- CREATE BUTTON --}}
        <a href="{{ route('admin.course-batches.create') }}"
           class="inline-flex items-center justify-center gap-2
                  px-5 py-3 rounded-xl
                  bg-emerald-600 text-white
                  font-semibold
                  shadow-lg shadow-emerald-600/20
                  hover:bg-emerald-700
                  hover:-translate-y-0.5
                  transition-all">

            <i data-lucide="plus-circle" class="w-5 h-5"></i>

            Add New Batch

        </a>

    </div>


    {{-- FLASH --}}
    @if(session('success'))

        <div class="flex items-start gap-3 p-4 rounded-xl
                    bg-emerald-50 border border-emerald-200">

            <i data-lucide="check-circle"
               class="w-5 h-5 text-emerald-600 mt-0.5"></i>

            <p class="text-sm text-emerald-700">
                {{ session('success') }}
            </p>

        </div>

    @endif


    @if(session('error'))

        <div class="flex items-start gap-3 p-4 rounded-xl
                    bg-red-50 border border-red-200">

            <i data-lucide="alert-circle"
               class="w-5 h-5 text-red-600 mt-0.5"></i>

            <p class="text-sm text-red-700">
                {{ session('error') }}
            </p>

        </div>

    @endif


    {{-- STATS --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-slate-500">
                        Total Batches
                    </p>

                    <p class="text-2xl font-bold text-slate-800 mt-1">
                        {{ $courseBatches->total() }}
                    </p>

                </div>

                <div class="w-11 h-11 rounded-xl bg-emerald-50
                            flex items-center justify-center">

                    <i data-lucide="calendar-range"
                       class="w-5 h-5 text-emerald-600"></i>

                </div>

            </div>

        </div>


        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-slate-500">
                        Current Page
                    </p>

                    <p class="text-2xl font-bold text-blue-600 mt-1">
                        {{ $courseBatches->count() }}
                    </p>

                </div>

                <div class="w-11 h-11 rounded-xl bg-blue-50
                            flex items-center justify-center">

                    <i data-lucide="list"
                       class="w-5 h-5 text-blue-600"></i>

                </div>

            </div>

        </div>


        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-slate-500">
                        Quick Action
                    </p>

                    <a href="{{ route('admin.course-batches.create') }}"
                       class="inline-flex items-center gap-1.5
                              mt-2 text-sm font-bold text-emerald-600">

                        <i data-lucide="plus" class="w-4 h-4"></i>

                        Create Batch

                    </a>

                </div>

                <div class="w-11 h-11 rounded-xl bg-emerald-50
                            flex items-center justify-center">

                    <i data-lucide="circle-plus"
                       class="w-5 h-5 text-emerald-600"></i>

                </div>

            </div>

        </div>

    </div>


    {{-- FILTER --}}
    <div class="bg-white border border-slate-200 rounded-2xl
                shadow-sm p-5">

        <div class="flex items-center gap-2 mb-4">

            <i data-lucide="sliders-horizontal"
               class="w-5 h-5 text-slate-500"></i>

            <h2 class="font-bold text-slate-800">
                Search & Filter
            </h2>

        </div>


        <form method="GET"
              action="{{ route('admin.course-batches.index') }}">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                {{-- Search --}}
                <div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Search Batch
                    </label>

                    <div class="relative">

                        <i data-lucide="search"
                           class="absolute left-3 top-1/2 -translate-y-1/2
                                  w-4 h-4 text-slate-400"></i>

                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Batch name..."
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl
                                      border border-slate-300
                                      focus:ring-2 focus:ring-emerald-500
                                      focus:border-emerald-500">

                    </div>

                </div>


                {{-- Course --}}
                <div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Course
                    </label>

                    <select name="course_id"
                            class="w-full px-4 py-2.5 rounded-xl
                                   border border-slate-300
                                   focus:ring-2 focus:ring-emerald-500">

                        <option value="">
                            All Courses
                        </option>

                        @foreach($courses ?? [] as $course)

                            <option value="{{ $course->id }}"
                                @selected(request('course_id') == $course->id)>

                                {{ $course->title }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Status --}}
                <div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Status
                    </label>

                    <select name="status"
                            class="w-full px-4 py-2.5 rounded-xl
                                   border border-slate-300
                                   focus:ring-2 focus:ring-emerald-500">

                        <option value="">
                            All Status
                        </option>

                        <option value="upcoming"
                            @selected(request('status') === 'upcoming')>
                            Upcoming
                        </option>

                        <option value="open"
                            @selected(request('status') === 'open')>
                            Open
                        </option>

                        <option value="ongoing"
                            @selected(request('status') === 'ongoing')>
                            Ongoing
                        </option>

                        <option value="completed"
                            @selected(request('status') === 'completed')>
                            Completed
                        </option>

                        <option value="cancelled"
                            @selected(request('status') === 'cancelled')>
                            Cancelled
                        </option>

                    </select>

                </div>

            </div>


            <div class="flex flex-col sm:flex-row gap-3 mt-4">

                <button type="submit"
                        class="inline-flex items-center justify-center gap-2
                               px-5 py-2.5 rounded-xl
                               bg-slate-800 text-white
                               font-semibold hover:bg-slate-700">

                    <i data-lucide="search" class="w-4 h-4"></i>

                    Apply Filters

                </button>


                <a href="{{ route('admin.course-batches.index') }}"
                   class="inline-flex items-center justify-center gap-2
                          px-5 py-2.5 rounded-xl
                          bg-slate-100 text-slate-700
                          font-semibold hover:bg-slate-200">

                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>

                    Reset

                </a>

            </div>

        </form>

    </div>


    {{-- BATCH LIST --}}
    <div class="bg-white border border-slate-200 rounded-2xl
                shadow-sm overflow-hidden">

        <div class="p-5 border-b border-slate-200
                    flex flex-col sm:flex-row
                    sm:items-center sm:justify-between gap-3">

            <div>

                <h2 class="text-lg font-bold text-slate-800">
                    Batch List
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    {{ $courseBatches->total() }} batch(es) found
                </p>

            </div>


            <a href="{{ route('admin.course-batches.create') }}"
               class="inline-flex items-center justify-center gap-2
                      px-4 py-2.5 rounded-xl
                      bg-emerald-600 text-white
                      font-semibold hover:bg-emerald-700">

                <i data-lucide="plus" class="w-5 h-5"></i>

                Add Batch

            </a>

        </div>


        {{-- DESKTOP --}}
        <div class="hidden md:block overflow-x-auto">

            <table class="w-full">

                <thead class="bg-slate-50 border-b border-slate-200">

                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                            Batch
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                            Course
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                            Schedule
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">
                            Capacity
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">
                            Status
                        </th>

                        <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($courseBatches as $batch)

                        <tr class="hover:bg-slate-50 transition">

                            {{-- Batch --}}
                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="w-11 h-11 rounded-xl bg-emerald-50
                                                flex items-center justify-center">

                                        <i data-lucide="calendar-days"
                                           class="w-5 h-5 text-emerald-600"></i>

                                    </div>

                                    <div>

                                        <p class="font-bold text-slate-800">
                                            {{ $batch->batch_name }}
                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- Course --}}
                            <td class="px-6 py-4">

                                @if($batch->course)

                                    <p class="font-semibold text-slate-700">
                                        {{ $batch->course->title }}
                                    </p>

                                    @if($batch->course->code)

                                        <p class="text-xs text-slate-500 mt-1">
                                            {{ $batch->course->code }}
                                        </p>

                                    @endif

                                @else

                                    <span class="text-red-500 text-sm">
                                        Course unavailable
                                    </span>

                                @endif

                            </td>


                            {{-- Schedule --}}
                            <td class="px-6 py-4">

                                <div class="space-y-1 text-sm">

                                    <div class="flex items-center gap-2 text-slate-600">

                                        <i data-lucide="calendar-plus"
                                           class="w-4 h-4 text-emerald-600"></i>

                                        {{ $batch->start_date
                                            ? $batch->start_date->format('d M Y')
                                            : 'Not set' }}

                                    </div>


                                    <div class="flex items-center gap-2 text-slate-500">

                                        <i data-lucide="calendar-minus"
                                           class="w-4 h-4"></i>

                                        {{ $batch->end_date
                                            ? $batch->end_date->format('d M Y')
                                            : 'Not set' }}

                                    </div>

                                </div>

                            </td>


                            {{-- Capacity --}}
                            <td class="px-6 py-4 text-center">

                                @if($batch->capacity)

                                    <span class="inline-flex items-center gap-1.5
                                                 px-3 py-1.5 rounded-lg
                                                 bg-blue-50 text-blue-700
                                                 font-bold">

                                        <i data-lucide="users"
                                           class="w-4 h-4"></i>

                                        {{ $batch->capacity }}

                                    </span>

                                @else

                                    <span class="text-sm text-slate-400">
                                        Unlimited
                                    </span>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td class="px-6 py-4 text-center">

                                @switch($batch->status)

                                    @case('open')

                                        <span class="inline-flex px-3 py-1.5 rounded-full
                                                     bg-emerald-50 text-emerald-700
                                                     text-xs font-bold">
                                            Open
                                        </span>

                                    @break

                                    @case('ongoing')

                                        <span class="inline-flex px-3 py-1.5 rounded-full
                                                     bg-blue-50 text-blue-700
                                                     text-xs font-bold">
                                            Ongoing
                                        </span>

                                    @break

                                    @case('completed')

                                        <span class="inline-flex px-3 py-1.5 rounded-full
                                                     bg-slate-100 text-slate-600
                                                     text-xs font-bold">
                                            Completed
                                        </span>

                                    @break

                                    @case('cancelled')

                                        <span class="inline-flex px-3 py-1.5 rounded-full
                                                     bg-red-50 text-red-700
                                                     text-xs font-bold">
                                            Cancelled
                                        </span>

                                    @break

                                    @default

                                        <span class="inline-flex px-3 py-1.5 rounded-full
                                                     bg-amber-50 text-amber-700
                                                     text-xs font-bold">
                                            Upcoming
                                        </span>

                                @endswitch

                            </td>


                            {{-- Actions --}}
                            <td class="px-6 py-4">

                                <div class="flex justify-end gap-2">

                                    <a href="{{ route('admin.course-batches.edit', $batch) }}"
                                       class="inline-flex items-center gap-2
                                              px-3.5 py-2 rounded-lg
                                              bg-blue-50 text-blue-700
                                              text-sm font-semibold
                                              hover:bg-blue-100">

                                        <i data-lucide="pencil" class="w-4 h-4"></i>

                                        Edit

                                    </a>


                                    <form action="{{ route('admin.course-batches.destroy', $batch) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this batch? This action cannot be undone.');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="inline-flex items-center gap-2
                                                       px-3.5 py-2 rounded-lg
                                                       bg-red-50 text-red-700
                                                       text-sm font-semibold
                                                       hover:bg-red-100">

                                            <i data-lucide="trash-2" class="w-4 h-4"></i>

                                            Delete

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="px-6 py-16 text-center">

                                <div class="mx-auto w-16 h-16 rounded-2xl bg-emerald-50
                                            flex items-center justify-center">

                                    <i data-lucide="calendar-range"
                                       class="w-8 h-8 text-emerald-500"></i>

                                </div>

                                <h3 class="mt-5 text-lg font-bold text-slate-800">
                                    No course batches yet
                                </h3>

                                <p class="mt-2 text-sm text-slate-500">
                                    Create a batch to start scheduling training.
                                </p>

                                <a href="{{ route('admin.course-batches.create') }}"
                                   class="inline-flex items-center gap-2 mt-5
                                          px-5 py-3 rounded-xl
                                          bg-emerald-600 text-white
                                          font-semibold hover:bg-emerald-700">

                                    <i data-lucide="plus-circle" class="w-5 h-5"></i>

                                    Create First Batch

                                </a>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- MOBILE --}}
        <div class="md:hidden divide-y divide-slate-100">

            @forelse($courseBatches as $batch)

                <div class="p-5">

                    <div class="flex items-start gap-3">

                        <div class="w-11 h-11 rounded-xl bg-emerald-50
                                    flex items-center justify-center shrink-0">

                            <i data-lucide="calendar-days"
                               class="w-5 h-5 text-emerald-600"></i>

                        </div>


                        <div class="flex-1">

                            <div class="flex justify-between gap-3">

                                <h3 class="font-bold text-slate-800">
                                    {{ $batch->batch_name }}
                                </h3>

                                @switch($batch->status)

                                    @case('open')
                                        <span class="shrink-0 text-xs font-bold px-2 py-1 rounded-full bg-emerald-50 text-emerald-700">
                                            Open
                                        </span>
                                    @break

                                    @case('ongoing')
                                        <span class="shrink-0 text-xs font-bold px-2 py-1 rounded-full bg-blue-50 text-blue-700">
                                            Ongoing
                                        </span>
                                    @break

                                    @case('completed')
                                        <span class="shrink-0 text-xs font-bold px-2 py-1 rounded-full bg-slate-100 text-slate-600">
                                            Completed
                                        </span>
                                    @break

                                    @case('cancelled')
                                        <span class="shrink-0 text-xs font-bold px-2 py-1 rounded-full bg-red-50 text-red-700">
                                            Cancelled
                                        </span>
                                    @break

                                    @default
                                        <span class="shrink-0 text-xs font-bold px-2 py-1 rounded-full bg-amber-50 text-amber-700">
                                            Upcoming
                                        </span>

                                @endswitch

                            </div>


                            <p class="text-sm text-slate-500 mt-1">
                                {{ $batch->course->title ?? 'Course unavailable' }}
                            </p>

                        </div>

                    </div>


                    <div class="grid grid-cols-2 gap-3 mt-4">

                        <div class="bg-slate-50 rounded-xl p-3">

                            <p class="text-xs text-slate-500">
                                Start Date
                            </p>

                            <p class="font-semibold text-sm text-slate-700 mt-1">

                                {{ $batch->start_date
                                    ? $batch->start_date->format('d M Y')
                                    : 'Not set' }}

                            </p>

                        </div>


                        <div class="bg-slate-50 rounded-xl p-3">

                            <p class="text-xs text-slate-500">
                                End Date
                            </p>

                            <p class="font-semibold text-sm text-slate-700 mt-1">

                                {{ $batch->end_date
                                    ? $batch->end_date->format('d M Y')
                                    : 'Not set' }}

                            </p>

                        </div>

                    </div>


                    <div class="mt-4 flex items-center justify-between">

                        <span class="text-sm text-slate-500">

                            Capacity:

                            <strong class="text-slate-700">
                                {{ $batch->capacity ?: 'Unlimited' }}
                            </strong>

                        </span>


                        <div class="flex gap-2">

                            <a href="{{ route('admin.course-batches.edit', $batch) }}"
                               class="inline-flex items-center gap-2
                                      px-3 py-2 rounded-lg
                                      bg-blue-50 text-blue-700
                                      text-sm font-semibold">

                                <i data-lucide="pencil" class="w-4 h-4"></i>

                                Edit

                            </a>


                            <form action="{{ route('admin.course-batches.destroy', $batch) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this batch?');">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="inline-flex items-center gap-2
                                               px-3 py-2 rounded-lg
                                               bg-red-50 text-red-700
                                               text-sm font-semibold">

                                    <i data-lucide="trash-2" class="w-4 h-4"></i>

                                    Delete

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            @empty

                <div class="p-10 text-center text-slate-500">

                    No batches found.

                    <br>

                    <a href="{{ route('admin.course-batches.create') }}"
                       class="inline-flex items-center gap-2 mt-4
                              px-4 py-2.5 rounded-xl
                              bg-emerald-600 text-white
                              font-semibold">

                        <i data-lucide="plus" class="w-5 h-5"></i>

                        Add Batch

                    </a>

                </div>

            @endforelse

        </div>


        {{-- PAGINATION --}}
        @if($courseBatches->hasPages())

            <div class="px-5 py-4 border-t border-slate-200">

                {{ $courseBatches->withQueryString()->links() }}

            </div>

        @endif

    </div>

</div>

@endsection