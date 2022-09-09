<?php

namespace App\Http\Controllers;

use App\Http\Requests\Branch\StoreRequest;
use App\Http\Requests\Branch\UpdateRequest;
use App\Http\Services\BranchService;
use App\Http\Services\ProductService;
use App\Models\Branch;
use App\Models\Category;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new BranchService();
    }


    public function index()
    {
        $branches = cache()->remember('branches', 24*60*60*365, function (){
            return Branch::paginate();
        });

        return view('branches.index', compact('branches'));
    }

    public function create()
    {
        return view('branches.create');
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        Branch::create($data);
        return to_route('branch.index');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();

        return to_route('branch.index');
    }


    public function edit(Branch $branch)
    {
        return view('branches.edit', compact('branch'));
    }

    public function update(UpdateRequest $request,Branch $branch)
    {
        $data = $request->validated();
        $branch->update($data);

        return to_route('branch.index');
    }
}
