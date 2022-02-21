<div class="mx-2 mt-1 px-1 w-full">
    <div>
        <span class="text-white italic">{{ $name = $task->getPurpose() }}</span>
        <span class="px-1 text-gray-500">{{ str_repeat('.', $width - mb_strlen($name) - mb_strlen($status) - 8) }}</span>
        <span class="{{ $color }}">{{ $status }}</span>
    </div>

    <div>
        @if ($reason = $task->getStatusReason())
            <span class="text-white font-bold mr-2">⤷</span>
            <span class="italic {{ $color }}">{{ $reason }}</span>
        @endif
    </div>
</div>
