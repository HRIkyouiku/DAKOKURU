<div>
    <form method="get" action="{{ route($formRoutingUrl, ['period' => $period]) }}">
        @csrf
        <input type="text" name="name_keywords" placeholder="検索したいユーザー名" value="{{ old('name_keywords', $oldNameKeywords) }}">

        <select name="department_id">
            <option value="0">- 全選択 -</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}" {{ old('department_id', $oldDepartmentId) == $department->id ? 'selected' : '' }}>{{ $department->name_jp }}</option>
            @endforeach
        </select>

        <select name="group_id">
            <option value="0">- 全選択 -</option>
            @foreach ($groups as $group)
                <option value="{{ $group->id }}" {{ old('group_id', $oldGroupId) == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
            @endforeach
        </select>

        <input type="hidden" name="searching" value=true>
        <input type="submit" name="user_search" value="絞り込み">

        <br><br>
        <input type="month" name="target_month" value="{{ old('period', $period) }}">
        <input type="submit" name="month_serach" value="期間変更">
        <span>｜</span>
        <input type="submit" name="to_last_month" value="先月へ">
        <span>｜</span>
        <input type="submit" name="to_next_month" value="翌月へ">

        <br>
        @if ($currentPage === 'users_daily_timestamps')
            <div>
                <span>打刻一覧</span>
                <a href="{{ route('timestamp.users_daily_attendance_totalling', ['period' => $period]) }}">勤務項目別集計</a>
            </div>
        @elseif ($currentPage === 'users_daily_attendance_totalling')
            <div>
                <a href="{{ route('timestamp.users_daily_timestamps', ['period' => $period]) }}">打刻一覧</a>
                <span>勤務項目別集計</span>
            </div>
        @endif
    </form>

    <div>
    @if ($errors->has('period'))
        <span>{{$errors->first('period')}}</span>
    @endif
    </div>
</div>