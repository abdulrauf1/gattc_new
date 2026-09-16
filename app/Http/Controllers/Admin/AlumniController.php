<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlumniController extends Controller
{
    public function index(Request $request)
    {
        $query =
            Alumni::query()
                ->latest('id');

        if ($request->filled('search')) {

            $search =
                trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'name',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'email',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'course',
                    'like',
                    "%{$search}%"
                );
            });
        }

        if ($request->filled('status')) {

            $query->where(
                'status',
                (bool) $request->status
            );
        }

        $alumni =
            $query
                ->paginate(15)
                ->withQueryString();

        return view(
            'admin.alumni.index',
            compact('alumni')
        );
    }

    public function create()
    {
        return view(
            'admin.alumni.create'
        );
    }

    public function store(Request $request)
    {
        $validated =
            $this->validateAlumni(
                $request
            );

        Alumni::create([

            'name' =>
                $validated['name'],

            'email' =>
                $validated['email'],

            'phone' =>
                $validated['phone'],

            'course' =>
                $validated['course'],

            'graduation_year' =>
                $validated['graduation_year'],

            'organization' =>
                $validated['organization']
                ?? null,

            'designation' =>
                $validated['designation']
                ?? null,

            'bio' =>
                $validated['bio']
                ?? null,

            'photo' =>
                $this->uploadPhoto(
                    $request
                ),

            /*
            | New alumni registrations wait for approval.
            */
            'status' =>
                false,
        ]);

        return redirect()
            ->route(
                'admin.alumni.index'
            )
            ->with(
                'success',
                'Alumni record added. It is awaiting approval.'
            );
    }

    public function edit(Alumni $alumnus)
    {
        return view(
            'admin.alumni.edit',
            compact('alumnus')
        );
    }

    public function update(
        Request $request,
        Alumni $alumnus
    ) {
        $validated =
            $this->validateAlumni(
                $request,
                $alumnus->id
            );

        $photo =
            $alumnus->photo;

        if ($request->hasFile('photo')) {

            if (
                $photo &&
                Storage::disk('public')
                    ->exists($photo)
            ) {

                Storage::disk('public')
                    ->delete($photo);
            }

            $photo =
                $this->uploadPhoto(
                    $request
                );
        }

        $alumnus->update([

            'name' =>
                $validated['name'],

            'email' =>
                $validated['email'],

            'phone' =>
                $validated['phone'],

            'course' =>
                $validated['course'],

            'graduation_year' =>
                $validated['graduation_year'],

            'organization' =>
                $validated['organization']
                ?? null,

            'designation' =>
                $validated['designation']
                ?? null,

            'bio' =>
                $validated['bio']
                ?? null,

            'photo' =>
                $photo,
        ]);

        return redirect()
            ->route(
                'admin.alumni.index'
            )
            ->with(
                'success',
                'Alumni record updated successfully.'
            );
    }

    public function approve(
        Alumni $alumnus
    ) {
        $alumnus->update([
            'status' => true,
        ]);

        return back()->with(
            'success',
            'Alumni profile approved.'
        );
    }

    public function reject(
        Alumni $alumnus
    ) {
        $alumnus->update([
            'status' => false,
        ]);

        return back()->with(
            'success',
            'Alumni profile rejected/unpublished.'
        );
    }

    public function destroy(
        Alumni $alumnus
    ) {
        if (
            $alumnus->photo &&
            Storage::disk('public')
                ->exists($alumnus->photo)
        ) {

            Storage::disk('public')
                ->delete($alumnus->photo);
        }

        $alumnus->delete();

        return redirect()
            ->route(
                'admin.alumni.index'
            )
            ->with(
                'success',
                'Alumni record deleted successfully.'
            );
    }

    private function validateAlumni(
        Request $request,
        ?int $ignoreId = null
    ): array {
        return $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:alumni,email,' .
                    ($ignoreId ?? 'NULL') .
                    ',id',
            ],

            'phone' => [
                'required',
                'string',
                'max:30',
            ],

            'course' => [
                'required',
                'string',
                'max:255',
            ],

            'graduation_year' => [
                'required',
                'integer',
                'min:1950',
                'max:' . (date('Y') + 2),
            ],

            'organization' => [
                'nullable',
                'string',
                'max:255',
            ],

            'designation' => [
                'nullable',
                'string',
                'max:255',
            ],

            'bio' => [
                'nullable',
                'string',
                'max:3000',
            ],

            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],
        ]);
    }

    private function uploadPhoto(
        Request $request
    ): ?string {
        if (!$request->hasFile('photo')) {
            return null;
        }

        return $request
            ->file('photo')
            ->store(
                'alumni',
                'public'
            );
    }
}