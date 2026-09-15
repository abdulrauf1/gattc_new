<div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

        <div class="md:col-span-2">

            <label class="mb-1 block text-sm font-medium text-slate-700">
                Course Title
            </label>

            <input type="text"
                   name="title"
                   value="{{ old('title', $course->title ?? '') }}"
                   required
                   class="w-full rounded-lg border-slate-300">

        </div>


        <div>

            <label class="mb-1 block text-sm font-medium text-slate-700">
                Category
            </label>

            <select name="course_category_id"
                    required
                    class="w-full rounded-lg border-slate-300">

                <option value="">
                    Select Category
                </option>

                @foreach($categories as $category)

                    <option value="{{ $category->id }}"
                        @selected(old('course_category_id', $course->course_category_id ?? '') == $category->id)>

                        {{ $category->name }}

                    </option>

                @endforeach

            </select>

        </div>


        <div>

            <label class="mb-1 block text-sm font-medium text-slate-700">
                Course Type
            </label>

            <select name="course_type"
                    required
                    class="w-full rounded-lg border-slate-300">

                <option value="regular"
                    @selected(old('course_type', $course->course_type ?? '') === 'regular')}>
                    Regular
                </option>

                <option value="dit"
                    @selected(old('course_type', $course->course_type ?? '') === 'dit')}>
                    DIT / 2nd Shift
                </option>

                <option value="private"
                    @selected(old('course_type', $course->course_type ?? '') === 'private')}>
                    Private / IMC
                </option>

            </select>

        </div>


        <div>

            <label class="mb-1 block text-sm font-medium text-slate-700">
                Fee Amount
            </label>

            <input type="number"
                   name="fee_amount"
                   min="0"
                   step="0.01"
                   value="{{ old('fee_amount', $course->fee_amount ?? '') }}"
                   required
                   class="w-full rounded-lg border-slate-300">

        </div>


        <div>

            <label class="mb-1 block text-sm font-medium text-slate-700">
                Bank Account
            </label>

            <select name="bank_account_id"
                    required
                    class="w-full rounded-lg border-slate-300">

                <option value="">
                    Select Bank Account
                </option>

                @foreach($bankAccounts as $bank)

                    <option value="{{ $bank->id }}"
                        @selected(old('bank_account_id', $course->bank_account_id ?? '') == $bank->id)>

                        {{ $bank->account_title }}
                        —
                        {{ $bank->account_number }}

                    </option>

                @endforeach

            </select>

        </div>


        <div>

            <label class="mb-1 block text-sm font-medium text-slate-700">
                Duration
            </label>

            <input type="text"
                   name="duration"
                   value="{{ old('duration', $course->duration ?? '') }}"
                   class="w-full rounded-lg border-slate-300">

        </div>


        <div>

            <label class="mb-1 block text-sm font-medium text-slate-700">
                Eligibility
            </label>

            <input type="text"
                   name="eligibility"
                   value="{{ old('eligibility', $course->eligibility ?? '') }}"
                   class="w-full rounded-lg border-slate-300">

        </div>


        <div class="md:col-span-2">

            <label class="mb-1 block text-sm font-medium text-slate-700">
                Description
            </label>

            <textarea name="description"
                      rows="5"
                      class="w-full rounded-lg border-slate-300">{{ old('description', $course->description ?? '') }}</textarea>

        </div>


        <div>

            <label class="mb-1 block text-sm font-medium text-slate-700">
                Sort Order
            </label>

            <input type="number"
                   name="sort_order"
                   value="{{ old('sort_order', $course->sort_order ?? 0) }}"
                   class="w-full rounded-lg border-slate-300">

        </div>


        <div class="flex items-center gap-3 pt-7">

            <input type="checkbox"
                   name="status"
                   value="1"
                   @checked(old('status', $course->status ?? true))
                   class="rounded border-slate-300">

            <label class="text-sm font-medium text-slate-700">
                Active Course
            </label>

        </div>

    </div>


    <div class="mt-6 flex justify-end gap-3">

        <a href="{{ route('admin.courses.index') }}"
           class="rounded-lg bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-700">
            Cancel
        </a>

        <button type="submit"
                class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white">
            Save Course
        </button>

    </div>

</div>