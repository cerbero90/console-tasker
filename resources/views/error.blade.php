@if ($exception)
    <div class="my-2 px-1 space-y-1 bg-red-500 text-white">
        <div class="w-full font-bold pt-1"><span class="pr-1">⚠️</span> An error occurred:</div>
        <div class="w-full italic pb-1">{{ $exception->getMessage() }}</div>
    </div>
@endif
