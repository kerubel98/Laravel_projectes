<div class="space-y-4">
    <!-- Header Section -->
    <div class="border-b pb-4 dark:border-gray-700">
        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full dark:bg-blue-800/20 dark:text-blue-500">
            @php
                $fromAddress = html_entity_decode($record->sender);
                echo 'From'. ' '. $fromAddress ?? 'Unknown' ."\n";
            @endphp
        </span>

        <!-- Priority Badge -->
        <span @class([
            'px-2 py-1 text-xs rounded-full',
            'bg-red-100 text-red-800 dark:bg-red-800/20 dark:text-red-500' => $record->priority == 1,
            'bg-yellow-100 text-yellow-800 dark:bg-yellow-800/20 dark:text-yellow-500' => $record->priority == 2,
            'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' => $record->priority >= 3,
                ])>
            @switch($record->priority)
                @case(1) High Priority @break
                @case(2) Medium Priority @break
                @default Normal Priority
            @endswitch
        </span>

        <!-- Flag Badge -->
        @if($record->flag_id && $record->flag_id !== 'flag_not_set')
            <span @class([
                'px-2 py-1 text-xs rounded-full',
                'bg-blue-100 text-blue-800 dark:bg-blue-800/20 dark:text-blue-500' => $record->flag_id === 'info',
                'bg-purple-100 text-purple-800 dark:bg-purple-800/20 dark:text-purple-500' => $record->flag_id === 'important',
        ])>
            {{ strtoupper($record->flag_id) }}
        </span>
        @endif

        <!-- Date and other info -->
        <div class="text-sm text-gray-500 dark:text-gray-400">
            Received: {{ $record->received_at->format('M d, Y h:i A') }}
        </div>
    </div>

    <!-- Email Content -->
    <div class="prose dark:prose-invert max-w-none">
        {!! nl2br(e($record->content)) !!}
    </div>
</div>
