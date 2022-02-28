@if ($files)
    @php([$created, $updated] = collect($files)->partition(fn ($file) => $file->wasCreated()))

    <div class="mx-2 mt-2 px-1 space-y-2">
        @if ($created->isNotEmpty())
            <div class="space-y-1">
                <h2 class="text-center font-bold text-green-500">✨ Created files</h2>
                <ul>
                    @foreach ($created as $file)
                        <li class="text-green-500 font-bold">
                            <span class="text-white underline font-normal">{{ $file->getRelativePath() }}</span>
                        </li>

                        <li class="list-none pl-2 space-x-2">
                            @if ($reason = $file->getManualUpdateReason())
                                <span class="text-white font-bold">⤷</span>
                                <span class="italic text-blue-600">{{ $reason }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if ($updated->isNotEmpty())
            <div class="space-y-1">
                <h2 class="text-center font-bold text-blue-500">✨ Updated files</h2>
                <ul>
                    @foreach ($updated as $file)
                        <li class="text-blue-500 font-bold">
                            <span class="text-white underline font-normal">{{ $file->getRelativePath() }}</span>
                        </li>

                        <li class="list-none pl-2 space-x-2">
                            @if ($reason = $file->getManualUpdateReason())
                                <span class="text-white font-bold">⤷</span>
                                <span class="italic text-blue-600">{{ $reason }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endif
