<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Services\ProductService;
use App\Http\Services\UserService;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new UserService();
    }


    public function index(Request $request)
    {
        $search = $request->search;

        $users = User::query();
        if ($search)
            $users = $users->where('phone_number', 'like', "%$search%")
                ->orWhere('name', 'like', "%$search%")
                ->orWhere('first_name', 'like', "%$search%")
                ->orWhere('username', 'like', "%$search%")
                ->orWhere('last_name', 'like', "%$search%");
        $users = $users
            ->orderBy('phone_number', 'desc')
            ->paginate();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        User::create($data);
        return to_route('user.index');
    }

    public function destroy(User $user)
    {
        $user->delete();
        Alert::success('Success', 'Deleted');

        return to_route('user.index');
    }


    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(UpdateRequest $request, User $user)
    {
        $data = $request->validated();
        $user->update($data);
        return to_route('user.index');
    }
}
