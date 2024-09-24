<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GroupAuthority;
use App\Http\Requests\EditGroupAuthoritiesRequest;

class GroupController extends Controller
{
    // TODO: マジックワード
    const ALL_AUTHORITY = [
        'user_create',
        'employment_status_index',
        'employment_status_show',
        'employment_status_create',
        'employment_status_edit',
        'employment_status_destroy',
        'timestamp_show'
    ];

    const JP_AUTHORITIES = [
        'user_create' => '新規ユーザー登録',
        'employment_status_index' => '権限グループ一覧表示',
        'employment_status_show' => '権限グループ詳細表示',
        'employment_status_create' => '権限グループ作成',
        'employment_status_edit' => '権限グループ編集',
        'employment_status_destroy' => '権限グループ削除',
        'timestamp_show' => '個人勤怠表表示'
    ];

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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        echo "ダミーページ";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        $users = User::all();

        $all_authority = self::ALL_AUTHORITY;
        $jp_authorities = self::JP_AUTHORITIES;

        return view('groups.edit', compact('group', 'users', 'all_authority', 'jp_authorities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(EditGroupAuthoritiesRequest $request, Group $group)
    {   
        $group_newname = $request->newname;
        if(isset($group_newname) && $group_newname != $request->nowname)
        {
            $group->update(['name' => $group_newname]);
        }

        $authorityData = $request->authority_check;
        foreach ($authorityData as $targetGroupId => $authorities) {
            $groupAuthority = GroupAuthority::where([
                ['group_id', $group->id],
                ['target_group_id', $targetGroupId]
            ])->first();

            if ($groupAuthority) {
                $groupAuthority->authorities = json_encode($authorities);
        
                $groupAuthority->save();
            } else {

            }
        }
        
        $add_group_affiliations = $request->yet_affiliation_chek;
        if(isset($add_group_affiliations)){
            foreach($add_group_affiliations as $add_group_affiliation){
                $group->users()->attach($add_group_affiliation, ['created_at' => now(), 'updated_at' => now()]);
            }
        }
        
        $remove_group_affiliations = $request->affiliation_check;
        if(isset($remove_group_affiliations)){
            foreach($remove_group_affiliations as $remove_group_affiliation){
                $group->users()->detach($remove_group_affiliation);
            }
        }

        return redirect()->route('group.show', $group->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        //
    }
}
