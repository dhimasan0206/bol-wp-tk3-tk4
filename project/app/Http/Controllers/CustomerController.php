<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->user()->hasPermissionTo('manage customer')) {
            abort(403);
        }

        $heads = [
            'ID',
            'Name',
            'Email',
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
        foreach (User::role('customer')->get() as $user) {
            $data[] = [
                $user->id,
                $user->name,
                $user->email,
                Auth::id() === $user->id ? "" : str_replace(":route", route("customers.edit", ['customer' => $user->id]), $btnEdit).
                str_replace(":route", route("customers.show", ['customer' => $user->id]), $btnDetails).
                str_replace(":route", route("customers.destroy", ['customer' => $user->id]), $btnDelete),
            ];
        }
        $config = [
            'data' => $data,
            // 'order' => [[1, 'asc']],
            // 'columns' => [null, null, null, ['orderable' => false]],
        ];
        return view('customers.index', compact('heads', 'config'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {   
        if (!$request->user()->hasPermissionTo('manage customer')) {
            abort(403);
        }

        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerRequest $request)
    {
        if (!$request->user()->hasPermissionTo('manage customer')) {
            abort(403);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        if (!$user->save()) {
            return redirect()->back()->withErrors('add user failed')->withInput();
        }

        $user->assignRole('customer');

        return to_route('customers.show', ['customer' => $user->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        if (!$request->user()->hasPermissionTo('manage customer')) {
            abort(403);
        }

        return view('customers.show', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, User $user)
    {
        if (!$request->user()->hasPermissionTo('manage customer')) {
            abort(403);
        }

        return view('customers.edit', [
            'customer' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerRequest $request, User $user)
    {
        if (!$request->user()->hasPermissionTo('manage customer')) {
            abort(403);
        }

        $user->name = $request->name;
        $user->password = Hash::make( $request->password);
        if (!$user->save()) {
            return redirect()->back()->withErrors('update '.$user->id.' failed')->withInput();
        }

        $user->assignRole('customer');

        return to_route('customers.show', ['customer' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        if (!$request->user()->hasPermissionTo('manage customer')) {
            abort(403);
        }

        if (!$user->delete()) {
            return redirect()->back()->withErrors('delete '.$user->id.' failed')->withInput();
        }

        return to_route('customers.index');
    }
}
