@php
    $depositRows = $depositDetails ?? collect();

    $depositTotal = $depositRows->sum(
        fn ($row) => (float) $row->amount
    );
@endphp


<div class="deposit-details">

    <div class="mb-2 text-center font-bold">
        Details of Deposit
    </div>


    @foreach ($depositRows as $index => $detail)

        <div class="flex items-center border-b border-slate-300 py-1 text-[10px]">

            <div class="w-6">
                {{ $index + 1 }}
            </div>

            <div class="flex-1">
                {{ $detail->fee_name }}
            </div>

            <div class="w-24 text-right">
                Rs.
                {{ number_format((float) $detail->amount, 2) }}
            </div>

        </div>

    @endforeach


    <div class="mt-2 flex items-center font-bold text-[11px]">

        <div class="flex-1">
            TOTAL FEE:
        </div>

        <div class="w-24 text-right">
            Rs.
            {{ number_format($depositTotal, 2) }}
        </div>

    </div>

</div>