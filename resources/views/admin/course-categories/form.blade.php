@csrf

@if($courseCategory->exists)
    @method('PUT')
@endif

<div class="bg-white border rounded-xl p-5">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label class="form-label">
                Category Name *
            </label>

            <input
                type="text"
                name="name"
                required
                value="{{ old(
                    'name',
                    $courseCategory->name
                ) }}"
                class="form-input"
                placeholder="e.g. Information Technology">
        </div>


        <div>
            <label class="form-label">
                Status
            </label>

            <label class="flex items-center gap-2 h-10">

                <input
                    type="hidden"
                    name="status"
                    value="0">

                <input
                    type="checkbox"
                    name="status"
                    value="1"
                    class="rounded"
                    @checked(
                        old(
                            'status',
                            $courseCategory->exists
                                ? $courseCategory->status
                                : true
                        )
                    )>

                <span class="text-sm">
                    Active
                </span>

            </label>
        </div>


        <div class="md:col-span-2">

            <label class="form-label">
                Description
            </label>

            <textarea
                name="description"
                rows="4"
                class="form-input resize-none"
                placeholder="Category description...">{{ old(
                    'description',
                    $courseCategory->description
                ) }}</textarea>

        </div>

    </div>


    <div class="mt-5 flex justify-end gap-2">

        <a
            href="{{ route(
                'admin.course-categories.index'
            ) }}"
            class="btn-secondary">

            Cancel

        </a>

        <button
            class="btn-primary">

            {{ $courseCategory->exists
                ? 'Update Category'
                : 'Create Category'
            }}

        </button>

    </div>

</div>