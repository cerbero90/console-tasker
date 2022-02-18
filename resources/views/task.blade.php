<div class="mx-2 my-1 w-full">
    <em class="pl-1 text-white underline">{{ $name = $task->getPurpose() }}</em>
    <span class="px-1 text-gray-500">{{ str_repeat('.', $width - mb_strlen($name) - mb_strlen($status) - 8) }}</span>
    <span class="pr-1 {{ $color }}">{{ $status }}</span>
</div>
