<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('ユーザーごと勤怠一覧') }}
        </h2>
    </x-slot>

    @include('partials.users_search_form', ['formRoutingUrl' => 'timestamp.users_daily_attendance_totalling'])

    <!-- TODO: 見づらすぎるので一時的にシンプルなCSSを用意 -->
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        td {
            border: 1px solid black;
            padding: 8px;
        }
    </style>

    <div>
        <table>
            <thead>
                <tr>
                    <th colspan="2"></th>
                    <th colspan="6">日数集計</th>
                    <th colspan="9">時間集計</th>
                </tr>
                <th>名前</th>
                <th>部署</th>
                <th>出勤</th>
                <th>欠勤</th>
                <th>遅刻</th>
                <th>早退</th>
                <th>所定休日<br>出勤</th>
                <th>法定休日<br>出勤</th>
                <th>総労働</th>
                <th>所定</th>
                <th>時間外</th>
                <th>深夜</th>
                <th>法定休日</th>
                <th>法定休日<br>深夜</th>
                <th>遅刻</th>
                <th>早退</th>
                <th>休憩</th>
            </thead>
            <tbody>
                @foreach ($usersAttendanceTotal as $userId => $attendanceTotal)
                <tr>
                    <td><p>{{ $attendanceTotal['name']->ln_jp . $attendanceTotal['name']->fn_jp }}</p></td>
                    <td><p>{{ $attendanceTotal['department_name']->name_jp }}</p></td>
                    <td><p>{{ $attendanceTotal['total']['出勤日数'] }}</p></td>
                    <td><p>{{ $attendanceTotal['total']['欠勤日数'] }}</p></td>
                    <td><p>{{ $attendanceTotal['total']['遅刻日数'] }}</p></td>
                    <td><p>{{ $attendanceTotal['total']['早退日数'] }}</p></td>
                    <td><p>{{ $attendanceTotal['total']['所定休日出勤日数'] }}</p></td>
                    <td><p>{{ $attendanceTotal['total']['法定休日出勤日数'] }}</p></td>
                    <td><p>{{ floor($attendanceTotal['total']['総労働時間'] * 10) / 10 }}</p></td>
                    <td><p>{{ floor($attendanceTotal['total']['所定労働時間'] * 10) / 10 }}</p></td>
                    <td><p>{{ floor($attendanceTotal['total']['時間外労働時間'] * 10) / 10 }}</p></td>
                    <td><p>{{ floor($attendanceTotal['total']['深夜労働時間'] * 10) / 10 }}</p></td>
                    <td><p>{{ floor($attendanceTotal['total']['法定休日労働時間'] * 10) / 10 }}</p></td>
                    <td><p>{{ floor($attendanceTotal['total']['法定休日深夜労働時間'] * 10) / 10 }}</p></td>
                    <td><p>{{ floor($attendanceTotal['total']['遅刻時間'] * 10) / 10 }}</p></td>
                    <td><p>{{ floor($attendanceTotal['total']['早退時間'] * 10) / 10 }}</p></td>
                    <td><p>{{ floor($attendanceTotal['total']['休憩時間'] * 10) / 10 }}</p></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>