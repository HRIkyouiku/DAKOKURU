<?php

namespace App\Http\Controllers;

use App\Models\Timestamp;
use App\Models\WorkPlace;
use App\Models\Name;
use App\Models\Department;
use App\Models\Group;
use App\Models\DepartmentAffiliation;
use App\Models\GroupAffiliation;
use App\Models\EmploymentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Datetime;

class TimestampController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // HACK: WorkPlaceモデルでSoftDeletesしてればdeleted_atでの絞り込み不要かもしれない
        $places = WorkPlace::query()->where('deleted_at', null)->get();
        $timestampHistories = $this->getTimestampHistories();

        return view('timestamps.create', compact('places', 'timestampHistories'));
    }

    /**
     * @param int $userId
     * @return array
     */
    private function getTimestampHistories(int $userId = null)
    {
        $timestamps = Timestamp::query()
            ->where('user_id', $userId ? : Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(12) // TODO: 12件取得しておけば最低でも2日前まではすべて見れる、マジックナンバー
            ->get()
        ;

        $timestampHistories = [];
        $limitTime = new DateTime('05:00'); // TODO:現在勤怠上の日付変更はAM5時で固定だが、将来的にはユーザーによって変わる

        foreach ($timestamps as $stamp) {
            $stampTime = new DateTime($stamp->time);
            if ($stampTime < $limitTime) {
                $stampHour = $stampTime->format('H') + 24;
                $stampMinute = $stampTime->format('i');
                $formatedTime = $stampHour . ':' . $stampMinute;
            } else {
                $formatedTime = $stampTime->format('H:i');
            }

            $timestampHistories[] = [
                'date' => $stamp->date,
                'time' => $formatedTime,
                'type' => $stamp->type
            ];
        }

        return $timestampHistories;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedRequest = $request->validate([
            'type' => 'required|integer',
            'work_place' => 'required|integer'
        ]);

        $errors = [];

        if (!WorkPlace::find($validatedRequest['work_place'])->exists()) {
            $errors['work_place'] = '存在しない勤務場所を指定しています。';
        }

        if (!empty($errors)) {
            return back()->withInput()->withErrors($errors);
        }

        $currentDateTime = new DateTime();
        $currentTime = new DateTime($currentDateTime->format('H:i'));

        $limitTime = new DateTime('05:00'); // TODO:現在勤怠上の日付変更はAM5時で固定だが、将来的にはユーザーによって変わる
        if ($currentTime < $limitTime) {
            $currentDateTime->modify('-1 day');
        }
        $fixedDate = new DateTime($currentDateTime->format('Y-m-d'));

        $timestamp = new Timestamp();
        $timestamp->user_id = Auth::id();
        $timestamp->date = $fixedDate->format('Y-m-d');
        $timestamp->time = $currentTime->format('H:i:s');
        $timestamp->type = $request->input('type');
        $timestamp->work_place_id = $request->input('work_place');
        $timestamp->remark = null; // TODO:2024年8月の第一弾リリースでは備考欄は使わない
        $timestamp->approved = 0; // TODO:打刻時は0（未承認）で固定、将来的にマジックナンバーを避ける

        $timestamp->save();

        return redirect()->route('timestamp.create')->with('success', '打刻が登録されました。');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getDailyTimestampByUsers(Request $request)
    {
        // TODO:エラー赤セージやRequestの処理が整理されたらここも直す
        $validatedRequest = $this->validateRequest($request);

        if (!isset($request->user_search) && !isset($request->to_last_month) && !isset($request->to_next_month) && !isset($request->month_serach)) {
            // NOTE: 負荷軽減のため、フォームでの絞り込みをかけない初期の状態では打刻を取得しない
            $Timestamp = Timestamp::query()->where('id', null);
        } else {
            $Timestamp = $this->getTimestampQuery($request);
        }

        $targetMonth = ($request->target_month) ? new DateTime($request->target_month) : new DateTime($request->period);

        $Timestamps = $this->createPeriodSpecifiedTimestampQuery($request, $Timestamp, $targetMonth);
        $timestamps = $Timestamps->get();

        // NOTE: 対象付きの日付のみの作成
        $targetDays = [];
        for ($i = (int)$targetMonth->modify('first day of')->format('d'); $i < $targetMonth->modify('last day of')->format('d'); $i++) {
            $targetDays[] = (new DateTime($targetMonth->format('Y-m-') . $i))->format('Y-m-d');
        }

        $dailyTimestampByUsers = $this->createDailyTimestampByUsers($timestamps, $targetDays);

        $request->merge(['period' => $targetMonth->format('Y-m')]);

        return view('timestamps.users_daily_timestamps', [
            'dailyTimestampByUsers' => $dailyTimestampByUsers,
            'targetDays' => $targetDays,
            'departments' => Department::get(),
            'groups' => Group::get(),
            'period' => $request->period,
            'oldNameKeywords' => $request->name_keywords,
            'oldDepartmentId' => $request->department_id,
            'oldGroupId' => $request->group_id,
            'currentPage' => 'users_daily_timestamps'
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getDailyAttendanceTotallingByUsers(Request $request)
    {
        // TODO:エラー赤セージやRequestの処理が整理されたらここも直す
        $validatedRequest = $this->validateRequest($request);

        if (!isset($request->user_search) && !isset($request->to_last_month) && !isset($request->to_next_month) && !isset($request->month_serach)) {
            // NOTE: 負荷軽減のため、フォームでの絞り込みをかけない初期の状態では打刻を取得しない
            $Timestamp = Timestamp::query()->where('id', null);
        } else {
            $Timestamp = $this->getTimestampQuery($request);
        }

        $targetMonth = ($request->target_month) ? new DateTime($request->target_month) : new DateTime($request->period);

        $Timestamps = $this->createPeriodSpecifiedTimestampQuery($request, $Timestamp, $targetMonth);
        $timestamps = $Timestamps->get();

        // NOTE: 対象付きの日付のみの作成
        $targetDays = [];
        for ($i = (int)$targetMonth->modify('first day of')->format('d'); $i < $targetMonth->modify('last day of')->format('d'); $i++) {
            $targetDays[] = (new DateTime($targetMonth->format('Y-m-') . $i))->format('Y-m-d');
        }

        $dailyTimestampByUsers = $this->createDailyTimestampByUsers($timestamps, $targetDays);

        $total = [];

        // TODO: 現在の前提
        //     ・勤怠締め日は月末
        //     ・出勤・退勤が0〜24時の間にそれぞれ1つずつだけ確実にある前提(FIXME: 日付を超えた処理を考慮していない)
        //     ・フレックス無し
        //     ・FIXME: 休日は誰しも週に2日間、つまりlegal_holidayとnon_legal_holidayそれぞれjson型だが、数字が"1つだけ"入っているとする
        foreach ($dailyTimestampByUsers as $userId => $dailyTimestampByUser) {
            $employmentStatus = EmploymentStatus::query()->where('user_id', $userId)->first();

            if (!isset($total[$userId])) {
                $total[$userId]['出勤日数'] = 0;
                $total[$userId]['欠勤日数'] = 0;
                $total[$userId]['遅刻日数'] = 0;
                $total[$userId]['早退日数'] = 0;
                $total[$userId]['所定休日出勤日数'] = 0;
                $total[$userId]['法定休日出勤日数'] = 0;
                $total[$userId]['総労働時間'] = 0;
                $total[$userId]['所定労働時間'] = 0;
                $total[$userId]['時間外労働時間'] = 0;
                $total[$userId]['深夜労働時間'] = 0;
                $total[$userId]['法定休日労働時間'] = 0;
                $total[$userId]['法定休日深夜労働時間'] = 0;
                $total[$userId]['遅刻時間'] = 0;
                $total[$userId]['早退時間'] = 0;
                $total[$userId]['休憩時間'] = 0;
            }

            // NOTE: 可読性を優先し、再利用できるコードをあえてまとめていない
            foreach ($dailyTimestampByUser['daily_timestamp'] as $date => $timestampByTypeByDay) {

                $dayOfWeekday = (new DateTime($date))->format('w');
                $legalHolidayWorkFlg = 0;

                if ($timestampByTypeByDay === []) {
                    // 【欠勤】
                    if ($dayOfWeekday !== $employmentStatus->non_legal_holiday[1] && $dayOfWeekday !== $employmentStatus->legal_holiday[1]) {
                        ++$total[$userId]['欠勤日数'];
                    }
                    continue;
                }

                // 【所定休日出勤日数/法定休日出勤日数】
                if ($dayOfWeekday === $employmentStatus->non_legal_holiday[1]) {
                    ++$total[$userId]['所定休日出勤日数'];
                }

                if ($dayOfWeekday === $employmentStatus->legal_holiday[1]) {
                    ++$total[$userId]['法定休日出勤日数'];
                    $legalHolidayWorkFlg = 1;
                }

                if (!isset($timestampByTypeByDay[1]) || !isset($timestampByTypeByDay[4])) {
                    // TODO: 打刻修正が必要な日となるので仕様を固めて実装
                    continue;
                }

                // 【出勤日数】
                ++$total[$userId]['出勤日数'];

                // 【遅刻日数/遅刻時間】
                if ($timestampByTypeByDay[1]['time'] > $employmentStatus->business_start_time) {
                    ++$total[$userId]['遅刻日数'];
                    $total[$userId]['遅刻時間'] += round((strtotime($timestampByTypeByDay[1]['time']) - strtotime($employmentStatus->business_start_time)) / 3600, 3);
                }

                // 【早退日数/早退時間】
                if ($timestampByTypeByDay[4]['time'] < $employmentStatus->business_end_time) {
                    ++$total[$userId]['早退日数'];
                    $total[$userId]['早退時間'] += round((strtotime($employmentStatus->business_start_time) - strtotime($timestampByTypeByDay[4]['time'])) / 3600, 3);
                }

                // 【総労働時間/所定労働時間/時間外労働/深夜残業時間】
                $totalWorkTime = round((strtotime($timestampByTypeByDay[4]['time']) - strtotime($timestampByTypeByDay[1]['time'])) / 3600, 3);

                // NOTE: 「早出残業」にも対応している
                $earlyOvertime = round((strtotime($employmentStatus->business_start_time) - strtotime($timestampByTypeByDay[1]['time'])) / 3600, 3);
                $lateOvertime = round((strtotime($timestampByTypeByDay[4]['time']) - strtotime($employmentStatus->business_end_time)) / 3600, 3);
                $overtime = (($earlyOvertime > 0) ? $earlyOvertime : 0) + (($lateOvertime > 0) ? $lateOvertime : 0);

                // NOTE: 深夜3時などに開始した場合の「早出深夜残業」にも対応している
                $earlyNightOvertime = round((strtotime($employmentStatus->night_shift_end_time) - strtotime($timestampByTypeByDay[1]['time'])) / 3600, 3);
                $lateNightOvertime = round((strtotime($timestampByTypeByDay[4]['time']) - strtotime($employmentStatus->night_shift_start_time)) / 3600, 3);
                $nightOvertime = (($earlyNightOvertime > 0) ? $earlyNightOvertime : 0) + (($lateNightOvertime > 0) ? $lateNightOvertime : 0);

                $total[$userId]['総労働時間'] += $totalWorkTime;
                $total[$userId]['深夜労働時間'] += $nightOvertime;
                $total[$userId]['時間外労働時間'] += $overtime;
                $total[$userId]['所定労働時間'] += $totalWorkTime - $nightOvertime - $overtime;

                // 【法定休日労働時間/法定休日深夜労働時間】
                if ($legalHolidayWorkFlg === 1) {
                    $total[$userId]['法定休日労働時間'] += $totalWorkTime;
                    $total[$userId]['法定休日深夜労働時間'] += $nightOvertime;
                }

                // 【休憩時間】
                if (isset($timestampByTypeByDay[2]) && isset($timestampByTypeByDay[3])) {
                    $total[$userId]['休憩時間'] += round((strtotime($timestampByTypeByDay[3]['time']) - strtotime($timestampByTypeByDay[2]['time'])) / 3600, 3);
                }
            }

            $dailyTimestampByUsers[$userId]['total'] = $total[$userId];
        }

        $request->merge(['period' => $targetMonth->format('Y-m')]);

        return view('timestamps.users_daily_attendance_totalling', [
            'usersAttendanceTotal' => $dailyTimestampByUsers,
            'departments' => Department::get(),
            'groups' => Group::get(),
            'period' => $request->period,
            'oldNameKeywords' => $request->name_keywords,
            'oldDepartmentId' => $request->department_id,
            'oldGroupId' => $request->group_id,
            'currentPage' => 'users_daily_attendance_totalling'
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    private function validateRequest($request)
    {
        // NOTE: periodのみGETパラメータなので個別でエラー判定をする
        $ym = explode('-', $request->period);
        if (count($ym) !== 2 || checkdate((int)$ym[1], 1, (int)$ym[0]) === false) {
            // TODO: エラーメッセージベタ書き
            $errors['period'] = '「2024-10」のようにハイフンを使い年月を指定してください。）';
            return redirect('timestamp/users_daily_timestamps/2000-01')->withErrors($errors)->with([
                'dailyTimestampByUsers' => [],
                'targetDays' => [],
                'departments' => Department::get(),
                'groups' => Group::get(),
                'period' => '2000-01',
                'oldNameKeywords' => $request->name_keywords,
                'oldDepartmentId' => $request->department_id,
                'oldGroupId' => $request->group_id,
                'currentFilename' => 'users_daily_timestamps'
            ]);
        }

        // TODO: マジックナンバー
        $validatedRequest = $request->validate([
            'name_keywords' => 'string|max:120',
            'department_id' => 'string|max:10',
            'group_id' => 'string|max:10'
        ]);

        if (!empty($errors)) {
            return back()->withInput()->withErrors($errors);
        }

        return $validatedRequest;
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getTimestampQuery(Request $request)
    {
        $Timestamp = Timestamp::query();

        if ($request->name_keywords !== null) {
            $query = Name::with('[user]');
            $words = explode(' ', str_replace('　', ' ', $request->name_keywords));
            foreach ($words as $word) {
                $query = $query
                    ->where('fn_jp', 'like', '%' . $word . '%')
                    ->orWhere('ln_jp', 'like', '%' . $word . '%');
            }
            $searchedUserIds = $query->pluck('user_id');
            $Timestamp->whereIn('user_id', $searchedUserIds);
        }

        if ($request->department_id !== '0') {
            $searchedUserIds = DepartmentAffiliation::with(['user'])
                ->where('id', (int)$request->department_id)
                ->pluck('user_id');
            $Timestamp->whereIn('user_id', $searchedUserIds);
        }

        if ($request->group_id !== '0') {
            $searchedUserIds = GroupAffiliation::with(['user'])
                ->where('group_id', (int)$request->group_id)
                ->pluck('user_id');
            $Timestamp->whereIn('user_id', $searchedUserIds);
        }

        return $Timestamp;
    }

   /**
     * @param Request $request
     * @param Timestamp $timestamp
     * @return array
     */
    private function createPeriodSpecifiedTimestampQuery($request, $Timestamp, $targetMonth)
    {
        if (isset($request->to_last_month)) {
            $targetMonth = $targetMonth->modify('-1 month');
        } elseif (isset($request->to_next_month)) {
            $targetMonth = $targetMonth->modify('+1 month');
        }

        $firstDayOfMonth = $targetMonth->format('Y-m-d');
        $lastDayOfMonth = $targetMonth->modify('last day of')->format('Y-m-d');

        return $Timestamp->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth]);
    }

    /**
     * @param array $timestamp
     * @param array $targetDays
     * @return array
     */
    private function createDailyTimestampByUsers($timestamps, $targetDays)
    {
        $dailyTimestampByUsers = [];

        // HACK: 配列と配列の掛け合わせ、リファクタの余地あり
        foreach ($timestamps as $timestamp) {
            foreach ($targetDays as $date) {
                if ($timestamp->date === $date) { // NOTE: 表で使うことを想定し、その日の打刻データが存在したらそれを入れ、そうでなかった場合も日付をキーに空の配列を入れる
                    // NOTE: 1日にそれぞれの打刻種別が1回ずつである前提でこの実装にしている、例えば1日に出勤が2度以上あると「$timestamp->type」をキーにできない
                    $dailyTimestampByUsers[$timestamp->user_id]['daily_timestamp'][$date][$timestamp->type] = [
                        'time' => $timestamp->time,
                        'work_place_id' => $timestamp->work_place_id,
                        'work_place_name' => (WorkPlace::find($timestamp->work_place_id) ? WorkPlace::find($timestamp->work_place_id)->name : '削除済みの勤務場所') // TODO: メッセージ管理場所を見直し
                    ];
                } else {
                    if (!isset( $dailyTimestampByUsers[$timestamp->user_id]['daily_timestamp'][$date])) {
                        $dailyTimestampByUsers[$timestamp->user_id]['daily_timestamp'][$date] = [];
                    }
                }
            }

            if (!isset($dailyTimestampByUsers[$timestamp->user_id]['name'])) {
                $dailyTimestampByUsers[$timestamp->user_id]['name'] = Name::query()
                    ->where('user_id', $timestamp->user_id)
                    ->first(['fn_jp', 'ln_jp'])
                ;

                // HACK: 1つのクエリにまとめられないか？また上記のユーザー名ともどもコレクションと配列が混合しているのを解消できないか？
                $departmentId = DepartmentAffiliation::query()
                    ->where('user_id', $timestamp->user_id)
                    ->pluck('department_id')
                ;
                $dailyTimestampByUsers[$timestamp->user_id]['department_name'] = Department::query()
                    ->where('id', $departmentId)
                    ->first(['name_jp'])
                ;
            }
        }

        return $dailyTimestampByUsers;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Timestamp  $timestamp
     * @return \Illuminate\Http\Response
     */
    public function show(Timestamp $timestamp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Timestamp  $timestamp
     * @return \Illuminate\Http\Response
     */
    public function edit(Timestamp $timestamp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Timestamp  $timestamp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Timestamp $timestamp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Timestamp  $timestamp
     * @return \Illuminate\Http\Response
     */
    public function destroy(Timestamp $timestamp)
    {
        //
    }
}
