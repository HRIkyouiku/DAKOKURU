<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('ユーザーごと勤怠一覧') }}
        </h2>
    </x-slot>

    @include('partials.users_search_form', ['formRoutingUrl' => 'timestamp.users_daily_timestamps'])

    <div>
        <table>
            <thead>
                <tr>
                    <th>名前</th>
                    <th>部署</th>
                    @foreach ($targetDays as $date)
                        <th>{{ $date }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            <tr>
            @foreach ($dailyTimestampByUsers as $userId => $dailyTimestampByUser)
                <td>
                    <p>{{ $dailyTimestampByUser['name']->ln_jp . $dailyTimestampByUser['name']->fn_jp }}</p>
                </td>
                <td>
                    <p>{{ $dailyTimestampByUser['department_name']->name_jp }}</p>
                </td>
                @foreach ($dailyTimestampByUser['daily_timestamp'] as $date => $dailyTimestamp)
                    <td>
                    @if (count($dailyTimestamp) === 0)
                        <p>出：</p>
                        <p>退：</p>
                        @continue
                    @endif

                    @foreach ($dailyTimestamp as $type => $oneDayTimestamp)
                        @if ($type === 1)
                            <p>出：{{ $oneDayTimestamp['time'] }}</p>
                        @elseif ($type === 4)
                            <p>退：{{ $oneDayTimestamp['time'] }}</p>
                        @endif
                    @endforeach
                    </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
        </table>
    </div>
</x-app-layout>