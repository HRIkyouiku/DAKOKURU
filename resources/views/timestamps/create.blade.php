<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('打刻トップ') }}
        </h2>
    </x-slot>
    <div>
        <form method="post" action="{{ route('timestamp.store') }}">
        @csrf
            <select name="work_place">
            @foreach ($places as $place)
                <option value="{{ $place->id }}">{{ $place->name }}</option>
            @endforeach
            </select>

            @if (!isset($timestampHistories[0]['type']))
                <input type="hidden" name="type" value="1">
                <button type="submit" style="background: #ddddff; font-size: 40px;" value="timestamp_store">出勤</button>
            @else
                @switch($timestampHistories[0]['type'])
                    @case(4)
                        <input type="hidden" name="type" value="1">
                        <button type="submit" style="background: #ddddff; font-size: 40px;" value="timestamp_store">出勤</button>
                    @break
                    @case(1)
                        <input type="hidden" name="type" value="2">
                        <button type="submit" style="background: #ddddff; font-size: 40px;" value="timestamp_store">休憩開始</button>
                    @break
                    @case(2)
                        <input type="hidden" name="type" value="3">
                        <button type="submit" style="background: #ddddff; font-size: 40px;" value="timestamp_store">休憩終了</button>
                    @break
                    @case(3)
                        <input type="hidden" name="type" value="4">
                        <button type="submit" style="background: #ddddff; font-size: 40px;" value="timestamp_store">退勤</button>
                    @break
                    @default エラー
                @endswitch
            @endif
        </form>
    </div>
    <br>
    <div>
    @if ($errors->has('type'))
        <span>{{$errors->first('type')}}</span>
    @endif
    @if ($errors->has('work_place'))
        <span>{{$errors->first('work_place')}}</span>
    @endif
    @if(session('success'))
        <span>{{ session('success') }}</span>
    @endif
    </div>
    <br>
    <div>
        <h2>打刻履歴</h2>
        <table>
        @foreach ($timestampHistories as $timestampHistory)
            <tr>
                <td>{{ $timestampHistory['date'] }}</td>
                <td>{{ $timestampHistory['time'] }}</td>
                <td>
                <!--  TODO:1~4で打刻種別を切り分けている、定数化したい -->
                @switch($timestampHistory['type'])
                    @case(1) 出勤 @break
                    @case(2) 休憩開始 @break
                    @case(3) 休憩終了 @break
                    @case(4) 退勤 @break
                    @default エラー
                @endswitch
                </td>
        @endforeach
        </table>
    </div>
</x-app-layout>