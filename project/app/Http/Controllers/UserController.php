<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Gate::authorize('viewAny', User::class);

        $heads = [
            'ID',
            'Name',
            'Email',
            'Role',
            'Actions'
        ];
        
        $btnEdit = '<a href=":route" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                        <i class="fa fa-lg fa-fw fa-pen"></i>
                    </a>';
        $btnDelete = '<form action=":route" method="POST">
            '.csrf_field().'
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete">
                <i class="fa fa-lg fa-fw fa-trash"></i>
            </button>
        </form>';
        $btnDetails = '<a href=":route" class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details"><i class="fa fa-lg fa-fw fa-eye"></i></a>';
        $data = [];
        foreach (User::all() as $user) {
            $data[] = [
                $user->id,
                $user->name,
                $user->email,
                $user->getRoleNames()->join(','),
                Auth::id() === $user->id ? "" : str_replace(":route", route("users.edit", ["user" => $user->id]), $btnEdit).
                str_replace(":route", route("users.show", ["user" => $user->id]), $btnDetails).
                str_replace(":route", route("users.destroy", ["user" => $user->id]), $btnDelete),
            ];
        }
        $config = [
            'data' => $data,
            // 'order' => [[1, 'asc']],
            // 'columns' => [null, null, null, ['orderable' => false]],
        ];
        return view('users.index', compact('heads', 'config'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Gate::authorize('create', User::class);
        
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        Gate::authorize('create', User::class);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        if (!$user->save()) {
            return redirect()->back()->withErrors('add user failed')->withInput();
        }

        return to_route('users.show', ['user' => $user->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        Gate::authorize('view', $user);

        return view('users.show', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        Gate::authorize('update', $user);

        return view('users.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        Gate::authorize('update', $user);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make( $request->password);
        if (!$user->save()) {
            return redirect()->back()->withErrors('update '.$user->id.' failed')->withInput();
        }

        return to_route('users.show', ['user' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);

        if (!$user->delete()) {
            return redirect()->back()->withErrors('delete '.$user->id.' failed')->withInput();
        }

        return to_route('users.index');
    }
}
