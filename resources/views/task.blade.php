<div class="mx-2 mt-1 px-1 w-full">
    <div class="space-x-1">
        <span class="text-white italic">{{ $name = $task->getPurpose() }}</span>
        <span class="text-blue-600">{{ str_repeat('.', $width - mb_strlen($name) - mb_strlen($status) - 8) }}</span>
        <span class="{{ $color }}">{{ $status }}</span>
    </div>

    <div class="space-x-2">
        @if ($reason = $task->getStatusReason())
            <span class="text-white font-bold">⤷</span>
            <span class="italic {{ $color }}">{{ $reason }}</span>
        @endif
    </div>
</div>
