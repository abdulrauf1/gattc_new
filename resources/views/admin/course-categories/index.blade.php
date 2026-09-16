@extends('layouts.admin')

@section('page-heading', 'Course Categories')

@section('content')

<div class="space-y-4">

    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-xl font-bold text-gray-900">
                Course Categories
            </h1>

            <p class="text-xs text-gray-500 mt-1">
                Organize GATTC courses by technical category.
            </p>
        </div>

        <a
            href="{{ route('admin.course-categories.create') }}"
            class="h-9 px-4 rounded-lg bg-emerald-500
                   hover:bg-emerald-600 text-white
                   text-sm font-semibold
                   inline-flex items-center gap-1.5">

            <i data-lucide="plus" class="w-4 h-4"></i>
            Add Category

        </a>

    </div>


    <div class="bg-white border rounded-xl p-3">

        <form method="GET">

            <div class="flex items-center gap-2">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search category..."
                    class="flex-1 h-9 rounded-lg border
                           border-gray-300 px-3 text-sm">

                <select
                    name="status"
                    class="w-32 h-9 rounded-lg border
                           border-gray-300 px-3 text-sm">

                    <option value="">
                        All Status
                    </option>

                    <option value="1"
                        @selected(request('status') === '1')}>
                        Active
                    </option>

                    <option value="0"
                        @selected(request('status') === '0')}>
                        Inactive
                    </option>

                </select>

                <button
                    class="h-9 px-4 rounded-lg bg-emerald-500
                           text-white text-sm font-semibold">

                    Filter

                </button>

                <a
                    href="{{ route('admin.course-categories.index') }}"
                    class="w-9 h-9 rounded-lg bg-gray-100
                           flex items-center justify-center">

                    <i data-lucide="rotate-ccw"
                       class="w-4 h-4"></i>

                </a>

            </div>

        </form>

    </div>


    <div class="bg-white border rounded-xl overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full min-w-[750px] text-sm">

                <thead class="bg-gray-50 border-b">

                    <tr>
                        <th class="px-4 py-3 text-left">Category</th>
                        <th class="px-4 py-3 text-left">Description</th>
                        <th class="px-4 py-3 text-left">Courses</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>

                </thead>

                <tbody class="divide-y">

                @forelse($categories as $category)

                    <tr class="hover:bg-gray-50">

                        <td class="px-4 py-3">

                            <p class="font-semibold">
                                {{ $category->name }}
                            </p>

                            <p class="text-[11px] text-gray-400">
                                {{ $category->slug }}
                            </p>

                        </td>

                        <td class="px-4 py-3 text-gray-600">
                            {{ \Illuminate\Support\Str::limit(
                                $category->description,
                                80
                            ) ?: '—' }}
                        </td>

                        <td class="px-4 py-3 font-semibold">
                            {{ $category->courses_count }}
                        </td>

                        <td class="px-4 py-3">

                            @if($category->status)
                                <span class="badge-green">
                                    Active
                                </span>
                            @else
                                <span class="badge-gray">
                                    Inactive
                                </span>
                            @endif

                        </td>

                        <td class="px-4 py-3 text-right">

                            <div class="inline-flex gap-1">

                                <a
                                    href="{{ route(
                                        'admin.course-categories.show',
                                        $category
                                    ) }}"
                                    class="icon-btn"
                                    title="View">

                                    <i data-lucide="eye"
                                       class="w-4 h-4"></i>

                                </a>

                                <a
                                    href="{{ route(
                                        'admin.course-categories.edit',
                                        $category
                                    ) }}"
                                    class="icon-btn"
                                    title="Edit">

                                    <i data-lucide="pencil"
                                       class="w-4 h-4"></i>

                                </a>

                                <form
                                    method="POST"
                                    action="{{ route(
                                        'admin.course-categories.destroy',
                                        $category
                                    ) }}"
                                    onsubmit="return confirm(
                                        'Delete this category?'
                                    )">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="icon-btn danger"
                                        title="Delete">

                                        <i data-lucide="trash-2"
                                           class="w-4 h-4"></i>

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5"
                            class="px-4 py-10 text-center text-gray-400">
                            No categories found.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        @if($categories->hasPages())
            <div class="border-t px-4 py-3">
                {{ $categories->withQueryString()->links() }}
            </div>
        @endif

    </div>

</div>

@endsection

@push('styles')
<style>
    .badge-green,
    .badge-gray {
        display:inline-flex;
        padding:.25rem .625rem;
        border-radius:9999px;
        font-size:.7rem;
        font-weight:600;
    }

    .badge-green {
        background:#ecfdf5;
        color:#047857;
    }

    .badge-gray {
        background:#f3f4f6;
        color:#4b5563;
    }

    .icon-btn {
        width:2rem;
        height:2rem;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        border-radius:.5rem;
        background:#f3f4f6;
        color:#4b5563;
    }

    .icon-btn:hover {
        background:#e5e7eb;
    }

    .icon-btn.danger {
        color:#dc2626;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) lucide.createIcons();
});
</script>
@endpush