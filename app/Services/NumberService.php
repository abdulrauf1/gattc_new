<?php

namespace App\Services;

use App\Models\NumberSequence;
use Illuminate\Support\Facades\DB;

class NumberService
{
    public function generate(string $name, string $prefix): string
    {
        return DB::transaction(function () use ($name, $prefix) {

            $year = now()->year;

            $sequence = NumberSequence::where('name', $name)
                ->where('year', $year)
                ->lockForUpdate()
                ->first();

            if (!$sequence) {
                $sequence = NumberSequence::create([
                    'name' => $name,
                    'year' => $year,
                    'current_number' => 0,
                ]);

                // Lock the newly created row.
                $sequence = NumberSequence::where('id', $sequence->id)
                    ->lockForUpdate()
                    ->first();
            }

            $sequence->increment('current_number');

            $number = str_pad(
                $sequence->current_number,
                6,
                '0',
                STR_PAD_LEFT
            );

            return "{$prefix}-{$year}-{$number}";
        });
    }
}