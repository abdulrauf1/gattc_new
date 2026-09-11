@extends('layouts.admin')

@section('title', 'Courses')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

        <div class="flex items-start gap-4">

            <div class="w-12 h-12 rounded-2xl bg-violet-50
                        flex items-center justify-center shrink-0">

                <i data-lucide="book-open"
                   class="w-6 h-6 text-violet-600"></i>

            </div>

            <div>

                <h1 class="text-2xl font-bold text-slate-800">
                    Courses
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Manage all GATTC training and technical courses.
                </p>

            </div>

        </div>


        {{-- CREATE --}}
        <a href="{{ route('admin.courses.create') }}"
           class="inline-flex items-center justify-center gap-2
                  px-5 py-3 rounded-xl
                  bg-emerald-600 text-white
                  font-semibold
                  shadow-lg shadow-violet-600/20
                  hover:bg-violet-700
                  hover:-translate-y-0.5
                  transition-all">

            <i data-lucide="plus-circle" class="w-5 h-5"></i>

            Add New Course

        </a>

    </div>


    {{-- FLASH --}}
    @if(session('success'))

        <div class="flex items-start gap-3 p-4 rounded-xl
                    bg-emerald-50 border border-emerald-200">

            <i data-lucide="check-circle"
               class="w-5 h-5 text-emerald-600 mt-0.5"></i>

            <div class="text-sm text-emerald-700">
                {{ session('success') }}
            </div>

        </div>

    @endif


    @if(session('error'))

        <div class="flex items-start gap-3 p-4 rounded-xl
                    bg-red-50 border border-red-200">

            <i data-lucide="alert-circle"
               class="w-5 h-5 text-red-600 mt-0.5"></i>

            <div class="text-sm text-red-700">
                {{ session('error') }}
            </div>

        </div>

    @endif


    {{-- STATS --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">

            <div class="flex justify-between items-center">

                <div>
                    <p class="text-sm text-slate-500">
                        Total Courses
                    </p>

                    <p class="text-2xl font-bold text-slate-800 mt-1">
                        {{ $courses->total() }}
                    </p>
                </div>

                <div class="w-11 h-11 rounded-xl bg-violet-50
                            flex items-center justify-center">

                    <i data-lucide="book-open"
                       class="w-5 h-5 text-violet-600"></i>

                </div>

            </div>

        </div>


        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-sm text-slate-500">
                        Courses Displayed
                    </p>

                    <p class="text-2xl font-bold text-blue-600 mt-1">
                        {{ $courses->count() }}
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

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-sm text-slate-500">
                        Quick Action
                    </p>

                    <a href="{{ route('admin.courses.create') }}"
                       class="inline-flex items-center gap-1.5
                              mt-2 text-sm font-bold text-violet-600
                              hover:text-violet-700">

                        <i data-lucide="plus" class="w-4 h-4"></i>

                        Create Course

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


    {{-- FILTER PANEL --}}
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
              action="{{ route('admin.courses.index') }}">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                {{-- Search --}}
                <div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Search Course
                    </label>

                    <div class="relative">

                        <i data-lucide="search"
                           class="absolute left-3 top-1/2 -translate-y-1/2
                                  w-4 h-4 text-slate-400"></i>

                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Course name or code"
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl
                                      border border-slate-300
                                      focus:ring-2 focus:ring-violet-500
                                      focus:border-violet-500">

                    </div>

                </div>


                {{-- Category --}}
                <div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Category
                    </label>

                    <select name="course_category_id"
                            class="w-full px-4 py-2.5 rounded-xl
                                   border border-slate-300
                                   focus:ring-2 focus:ring-violet-500">

                        <option value="">
                            All Categories
                        </option>

                        @foreach($categories ?? [] as $category)

                            <option value="{{ $category->id }}"
                                @selected(request('course_category_id') == $category->id)>

                                {{ $category->name }}

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
                                   focus:ring-2 focus:ring-violet-500">

                        <option value="">
                            All Status
                        </option>

                        <option value="1" @selected(request('status') === '1')>
                            Active
                        </option>

                        <option value="0" @selected(request('status') === '0')>
                            Inactive
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


                <a href="{{ route('admin.courses.index') }}"
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


    {{-- COURSES --}}
    <div class="bg-white border border-slate-200 rounded-2xl
                shadow-sm overflow-hidden">

        <div class="p-5 border-b border-slate-200
                    flex flex-col sm:flex-row
                    sm:items-center sm:justify-between gap-3">

            <div>

                <h2 class="text-lg font-bold text-slate-800">
                    Course List
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    {{ $courses->total() }} course(s) found
                </p>

            </div>


            <a href="{{ route('admin.courses.create') }}"
               class="inline-flex items-center justify-center gap-2
                      px-4 py-2.5 rounded-xl
                      bg-violet-600 text-white
                      font-semibold hover:bg-violet-700">

                <i data-lucide="plus" class="w-5 h-5"></i>

                Add Course

            </a>

        </div>


        {{-- DESKTOP --}}
        <div class="hidden md:block overflow-x-auto">

            <table class="w-full">

                <thead class="bg-slate-50 border-b border-slate-200">

                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                            Course
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                            Category
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                            Duration
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">
                            Batches
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

                    @forelse($courses as $course)

                        <tr class="hover:bg-slate-50 transition">

                            {{-- Course --}}
                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    @if($course->image)

                                        <img src="{{ asset('storage/' . $course->image) }}"
                                             class="w-12 h-12 rounded-xl object-cover"
                                             alt="{{ $course->title }}">

                                    @else

                                        <div class="w-12 h-12 rounded-xl bg-violet-50
                                                    flex items-center justify-center">

                                            <i data-lucide="book-open"
                                               class="w-5 h-5 text-violet-600"></i>

                                        </div>

                                    @endif


                                    <div>

                                        <p class="font-bold text-slate-800">
                                            {{ $course->title }}
                                        </p>

                                        @if($course->code)

                                            <p class="text-xs text-slate-500 mt-1">
                                                Code: {{ $course->code }}
                                            </p>

                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- Category --}}
                            <td class="px-6 py-4">

                                @if($course->category)

                                    <span class="px-3 py-1.5 rounded-lg
                                                 bg-slate-100 text-slate-700
                                                 text-xs font-semibold">

                                        {{ $course->category->name }}

                                    </span>

                                @else

                                    <span class="text-slate-400">
                                        No category
                                    </span>

                                @endif

                            </td>


                            {{-- Duration --}}
                            <td class="px-6 py-4 text-sm text-slate-600">

                                {{ $course->duration ?: 'Not specified' }}

                            </td>


                            {{-- Batches --}}
                            <td class="px-6 py-4 text-center">

                                <span class="inline-flex min-w-9 justify-center
                                             px-2.5 py-1.5 rounded-lg
                                             bg-blue-50 text-blue-700 font-bold">

                                    {{ $course->batches_count ?? 0 }}

                                </span>

                            </td>


                            {{-- Status --}}
                            <td class="px-6 py-4 text-center">

                                @if($course->status)

                                    <span class="inline-flex items-center gap-2
                                                 px-3 py-1.5 rounded-full
                                                 bg-emerald-50 text-emerald-700
                                                 text-xs font-bold">

                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        Active

                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-2
                                                 px-3 py-1.5 rounded-full
                                                 bg-slate-100 text-slate-600
                                                 text-xs font-bold">

                                        <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                        Inactive

                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td class="px-6 py-4">

                                <div class="flex justify-end gap-2">

                                    <a href="{{ route('admin.courses.edit', $course) }}"
                                       class="inline-flex items-center gap-2 px-3.5 py-2
                                              rounded-lg bg-blue-50 text-blue-700
                                              text-sm font-semibold hover:bg-blue-100">

                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                        Edit

                                    </a>


                                    <form action="{{ route('admin.courses.destroy', $course) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this course? This action cannot be undone.');">

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

                                <div class="mx-auto w-16 h-16 rounded-2xl bg-violet-50
                                            flex items-center justify-center">

                                    <i data-lucide="book-open"
                                       class="w-8 h-8 text-violet-500"></i>

                                </div>

                                <h3 class="mt-5 text-lg font-bold text-slate-800">
                                    No courses found
                                </h3>

                                <p class="mt-2 text-sm text-slate-500">
                                    Create your first GATTC course.
                                </p>

                                <a href="{{ route('admin.courses.create') }}"
                                   class="inline-flex items-center gap-2 mt-5
                                          px-5 py-3 rounded-xl
                                          bg-violet-600 text-white
                                          font-semibold hover:bg-violet-700">

                                    <i data-lucide="plus-circle" class="w-5 h-5"></i>

                                    Add First Course

                                </a>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- MOBILE --}}
        <div class="md:hidden divide-y divide-slate-100">

            @forelse($courses as $course)

                <div class="p-5">

                    <div class="flex gap-3">

                        @if($course->image)

                            <img src="{{ asset('storage/' . $course->image) }}"
                                 class="w-12 h-12 rounded-xl object-cover shrink-0"
                                 alt="{{ $course->title }}">

                        @else

                            <div class="w-12 h-12 rounded-xl bg-violet-50
                                        flex items-center justify-center shrink-0">

                                <i data-lucide="book-open"
                                   class="w-5 h-5 text-violet-600"></i>

                            </div>

                        @endif


                        <div class="flex-1 min-w-0">

                            <div class="flex justify-between gap-3">

                                <h3 class="font-bold text-slate-800">
                                    {{ $course->title }}
                                </h3>

                                @if($course->status)

                                    <span class="shrink-0 text-xs font-bold
                                                 px-2 py-1 rounded-full
                                                 bg-emerald-50 text-emerald-700">
                                        Active
                                    </span>

                                @else

                                    <span class="shrink-0 text-xs font-bold
                                                 px-2 py-1 rounded-full
                                                 bg-slate-100 text-slate-600">
                                        Inactive
                                    </span>

                                @endif

                            </div>

                            <p class="text-xs text-slate-500 mt-1">
                                {{ $course->category->name ?? 'No category' }}
                            </p>

                            @if($course->code)

                                <p class="text-xs text-slate-400 mt-1">
                                    {{ $course->code }}
                                </p>

                            @endif

                        </div>

                    </div>


                    <div class="grid grid-cols-2 gap-3 mt-4">

                        <div class="bg-slate-50 rounded-xl p-3">

                            <p class="text-xs text-slate-500">
                                Duration
                            </p>

                            <p class="font-semibold text-slate-700 mt-1">
                                {{ $course->duration ?: '—' }}
                            </p>

                        </div>


                        <div class="bg-slate-50 rounded-xl p-3">

                            <p class="text-xs text-slate-500">
                                Batches
                            </p>

                            <p class="font-semibold text-slate-700 mt-1">
                                {{ $course->batches_count ?? 0 }}
                            </p>

                        </div>

                    </div>


                    <div class="flex justify-end gap-2 mt-4">

                        <a href="{{ route('admin.courses.edit', $course) }}"
                           class="inline-flex items-center gap-2
                                  px-3 py-2 rounded-lg
                                  bg-blue-50 text-blue-700 text-sm font-semibold">

                            <i data-lucide="pencil" class="w-4 h-4"></i>
                            Edit

                        </a>


                        <form action="{{ route('admin.courses.destroy', $course) }}"
                              method="POST"
                              onsubmit="return confirm('Delete this course?');">

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

            @empty

                <div class="p-10 text-center text-slate-500">

                    No courses found.

                    <br>

                    <a href="{{ route('admin.courses.create') }}"
                       class="inline-flex items-center gap-2 mt-4
                              px-4 py-2.5 rounded-xl
                              bg-violet-600 text-white font-semibold">

                        <i data-lucide="plus" class="w-5 h-5"></i>

                        Add Course

                    </a>

                </div>

            @endforelse

        </div>


        @if($courses->hasPages())

            <div class="px-5 py-4 border-t border-slate-200">

                {{ $courses->withQueryString()->links() }}

            </div>

        @endif

    </div>

</div>

@endsection