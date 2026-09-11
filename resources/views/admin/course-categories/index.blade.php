@extends('layouts.admin')

@section('title', 'Course Categories')

@section('content')

<div class="space-y-6">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

        <div class="flex items-start gap-4">

            <div class="w-12 h-12 rounded-2xl bg-blue-50
                        flex items-center justify-center shrink-0">

                <i data-lucide="layers-3"
                   class="w-6 h-6 text-blue-600"></i>

            </div>

            <div>

                <h1 class="text-2xl font-bold text-slate-800">
                    Course Categories
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Organize GATTC courses into technical and vocational categories.
                </p>

            </div>

        </div>


        {{-- VERY VISIBLE CREATE BUTTON --}}
        <a href="{{ route('admin.course-categories.create') }}"
                class="inline-flex items-center justify-center gap-2
                  px-5 py-3 rounded-xl
                  bg-emerald-600 text-white
                  font-semibold
                  shadow-lg shadow-emerald-600/20
                  hover:bg-emerald-700
                  hover:-translate-y-0.5
                  transition-all">

            <i data-lucide="plus-circle" class="w-5 h-5"></i>

            <span>Add New Category</span>

        </a>

    </div>


    {{-- =========================================================
         FLASH MESSAGES
    ========================================================== --}}
    @if(session('success'))

        <div class="flex items-start gap-3 p-4 rounded-xl
                    bg-emerald-50 border border-emerald-200">

            <div class="w-9 h-9 rounded-lg bg-emerald-100
                        flex items-center justify-center shrink-0">

                <i data-lucide="check-circle"
                   class="w-5 h-5 text-emerald-600"></i>

            </div>

            <div>
                <p class="font-semibold text-emerald-800">
                    Success
                </p>

                <p class="text-sm text-emerald-700 mt-0.5">
                    {{ session('success') }}
                </p>
            </div>

        </div>

    @endif


    @if(session('error'))

        <div class="flex items-start gap-3 p-4 rounded-xl
                    bg-red-50 border border-red-200">

            <div class="w-9 h-9 rounded-lg bg-red-100
                        flex items-center justify-center shrink-0">

                <i data-lucide="alert-circle"
                   class="w-5 h-5 text-red-600"></i>

            </div>

            <div>
                <p class="font-semibold text-red-800">
                    Unable to complete action
                </p>

                <p class="text-sm text-red-700 mt-0.5">
                    {{ session('error') }}
                </p>
            </div>

        </div>

    @endif


    {{-- =========================================================
         QUICK STATISTICS
    ========================================================== --}}
    @php
        $totalCategories = $categories->total();
        $activeCategories = $categories->getCollection()->where('status', true)->count();
        $totalCourses = $categories->getCollection()->sum(function ($category) {
            return $category->courses_count ?? 0;
        });
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        {{-- Total --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-slate-500">
                        Total Categories
                    </p>

                    <p class="text-2xl font-bold text-slate-800 mt-1">
                        {{ $totalCategories }}
                    </p>
                </div>

                <div class="w-11 h-11 rounded-xl bg-blue-50
                            flex items-center justify-center">

                    <i data-lucide="layers"
                       class="w-5 h-5 text-blue-600"></i>

                </div>

            </div>

        </div>


        {{-- Active --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-slate-500">
                        Active Categories
                    </p>

                    <p class="text-2xl font-bold text-emerald-600 mt-1">
                        {{ $activeCategories }}
                    </p>
                </div>

                <div class="w-11 h-11 rounded-xl bg-emerald-50
                            flex items-center justify-center">

                    <i data-lucide="circle-check"
                       class="w-5 h-5 text-emerald-600"></i>

                </div>

            </div>

        </div>


        {{-- Courses --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-slate-500">
                        Courses on Page
                    </p>

                    <p class="text-2xl font-bold text-violet-600 mt-1">
                        {{ $totalCourses }}
                    </p>
                </div>

                <div class="w-11 h-11 rounded-xl bg-violet-50
                            flex items-center justify-center">

                    <i data-lucide="book-open"
                       class="w-5 h-5 text-violet-600"></i>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         MAIN CARD
    ========================================================== --}}
    <div class="bg-white border border-slate-200 rounded-2xl
                shadow-sm overflow-hidden">

        {{-- Toolbar --}}
        <div class="p-5 border-b border-slate-200">

            <div class="flex flex-col sm:flex-row
                        sm:items-center sm:justify-between gap-4">

                <div>

                    <h2 class="text-lg font-bold text-slate-800">
                        All Categories
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Manage, edit or remove course categories.
                    </p>

                </div>


                {{-- Secondary Create Button --}}
                <a href="{{ route('admin.course-categories.create') }}"
                   class="inline-flex sm:hidden items-center justify-center gap-2
                          px-4 py-2.5 rounded-xl
                          bg-blue-600 text-white font-semibold">

                    <i data-lucide="plus" class="w-5 h-5"></i>

                    Add Category

                </a>

            </div>

        </div>


        {{-- =====================================================
             DESKTOP TABLE
        ====================================================== --}}
        <div class="hidden md:block overflow-x-auto">

            <table class="w-full">

                <thead>

                    <tr class="bg-slate-50 border-b border-slate-200">

                        <th class="px-6 py-4 text-left text-xs
                                   font-bold uppercase tracking-wider text-slate-500">
                            #
                        </th>

                        <th class="px-6 py-4 text-left text-xs
                                   font-bold uppercase tracking-wider text-slate-500">
                            Category
                        </th>

                        <th class="px-6 py-4 text-left text-xs
                                   font-bold uppercase tracking-wider text-slate-500">
                            Slug
                        </th>

                        <th class="px-6 py-4 text-center text-xs
                                   font-bold uppercase tracking-wider text-slate-500">
                            Courses
                        </th>

                        <th class="px-6 py-4 text-center text-xs
                                   font-bold uppercase tracking-wider text-slate-500">
                            Status
                        </th>

                        <th class="px-6 py-4 text-right text-xs
                                   font-bold uppercase tracking-wider text-slate-500">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($categories as $category)

                        <tr class="hover:bg-slate-50/80 transition">

                            <td class="px-6 py-4 text-sm text-slate-400">

                                {{ $categories->firstItem() + $loop->index }}

                            </td>


                            {{-- Category --}}
                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="w-10 h-10 rounded-xl
                                                bg-blue-50
                                                flex items-center justify-center">

                                        <i data-lucide="folder"
                                           class="w-5 h-5 text-blue-600"></i>

                                    </div>

                                    <div>

                                        <p class="font-semibold text-slate-800">
                                            {{ $category->name }}
                                        </p>

                                        @if($category->description)

                                            <p class="text-xs text-slate-500 mt-1 max-w-sm">
                                                {{ Str::limit($category->description, 70) }}
                                            </p>

                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- Slug --}}
                            <td class="px-6 py-4">

                                <span class="inline-flex px-2.5 py-1 rounded-lg
                                             bg-slate-100 text-slate-600
                                             font-mono text-xs">

                                    {{ $category->slug }}

                                </span>

                            </td>


                            {{-- Courses --}}
                            <td class="px-6 py-4 text-center">

                                <span class="inline-flex min-w-9 justify-center
                                             px-2.5 py-1.5 rounded-lg
                                             bg-blue-50 text-blue-700
                                             font-bold">

                                    {{ $category->courses_count ?? 0 }}

                                </span>

                            </td>


                            {{-- Status --}}
                            <td class="px-6 py-4 text-center">

                                @if($category->status)

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

                                <div class="flex items-center justify-end gap-2">

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.course-categories.edit', $category) }}"
                                       class="inline-flex items-center gap-2
                                              px-3.5 py-2 rounded-lg
                                              bg-blue-50 text-blue-700
                                              text-sm font-semibold
                                              hover:bg-blue-100 transition">

                                        <i data-lucide="pencil" class="w-4 h-4"></i>

                                        Edit

                                    </a>


                                    {{-- Delete --}}
                                    <form action="{{ route('admin.course-categories.destroy', $category) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this category? This action cannot be undone.');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="inline-flex items-center gap-2
                                                       px-3.5 py-2 rounded-lg
                                                       bg-red-50 text-red-700
                                                       text-sm font-semibold
                                                       hover:bg-red-100 transition">

                                            <i data-lucide="trash-2" class="w-4 h-4"></i>

                                            Delete

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="px-6 py-16">

                                <div class="text-center">

                                    <div class="mx-auto w-16 h-16 rounded-2xl
                                                bg-blue-50
                                                flex items-center justify-center">

                                        <i data-lucide="layers"
                                           class="w-8 h-8 text-blue-500"></i>

                                    </div>

                                    <h3 class="mt-5 text-lg font-bold text-slate-800">
                                        No course categories yet
                                    </h3>

                                    <p class="mt-2 text-sm text-slate-500">
                                        Start by creating your first course category.
                                    </p>

                                    <a href="{{ route('admin.course-categories.create') }}"
                                       class="inline-flex items-center gap-2
                                              mt-5 px-5 py-3 rounded-xl
                                              bg-blue-600 text-white
                                              font-semibold hover:bg-blue-700">

                                        <i data-lucide="plus-circle" class="w-5 h-5"></i>

                                        Create First Category

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =====================================================
             MOBILE CARDS
        ====================================================== --}}
        <div class="md:hidden divide-y divide-slate-100">

            @forelse($categories as $category)

                <div class="p-5">

                    <div class="flex items-start gap-3">

                        <div class="w-11 h-11 rounded-xl bg-blue-50
                                    flex items-center justify-center shrink-0">

                            <i data-lucide="folder"
                               class="w-5 h-5 text-blue-600"></i>

                        </div>


                        <div class="flex-1 min-w-0">

                            <div class="flex items-start justify-between gap-3">

                                <h3 class="font-bold text-slate-800">
                                    {{ $category->name }}
                                </h3>

                                @if($category->status)

                                    <span class="shrink-0 text-xs font-bold
                                                 px-2.5 py-1 rounded-full
                                                 bg-emerald-50 text-emerald-700">
                                        Active
                                    </span>

                                @else

                                    <span class="shrink-0 text-xs font-bold
                                                 px-2.5 py-1 rounded-full
                                                 bg-slate-100 text-slate-600">
                                        Inactive
                                    </span>

                                @endif

                            </div>

                            <p class="text-xs text-slate-500 mt-1 font-mono">
                                {{ $category->slug }}
                            </p>

                        </div>

                    </div>


                    <div class="mt-4 flex items-center justify-between">

                        <div class="text-sm text-slate-500">

                            Courses:

                            <span class="font-bold text-slate-700">
                                {{ $category->courses_count ?? 0 }}
                            </span>

                        </div>


                        <div class="flex gap-2">

                            <a href="{{ route('admin.course-categories.edit', $category) }}"
                               class="p-2.5 rounded-lg bg-blue-50 text-blue-700">

                                <i data-lucide="pencil" class="w-4 h-4"></i>

                            </a>


                            <form action="{{ route('admin.course-categories.destroy', $category) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this category?');">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="p-2.5 rounded-lg bg-red-50 text-red-700">

                                    <i data-lucide="trash-2" class="w-4 h-4"></i>

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            @empty

                <div class="p-10 text-center">

                    <p class="text-slate-500">
                        No categories available.
                    </p>

                    <a href="{{ route('admin.course-categories.create') }}"
                       class="inline-flex items-center gap-2 mt-4
                              px-4 py-2.5 rounded-xl
                              bg-blue-600 text-white font-semibold">

                        <i data-lucide="plus" class="w-5 h-5"></i>

                        Add Category

                    </a>

                </div>

            @endforelse

        </div>


        {{-- Pagination --}}
        @if($categories->hasPages())

            <div class="px-5 py-4 border-t border-slate-200">

                {{ $categories->withQueryString()->links() }}

            </div>

        @endif

    </div>

</div>

@endsection