<?php

namespace App\Http\Controllers\Bugtracker;

use App\Http\Controllers\Controller;

use App\Repositories\PathRepository;
use Illuminate\Http\Request;

use App\Project;
use App\Board;
use App\Path;

class PathController extends Controller
{

    protected $path_repository;

    public function __construct(PathRepository $repository)
    {
        $this->path_repository = $repository;
    }

    public function savePath(Request $request, Project $project, Board $board)
    {
        $path = $board->paths()->create($request->all());

        if($request->ajax()) {
            $response = [ 'path_slug' => $path->path_slug ];
            return response(json_encode($response), 200);
        }

        return redirect()->back();
    }

    public function deletePath(Request $request, Project $project, Board $board)
    {
        $board->paths()->where(['path_slug'=>$request->path_slug])->first()->delete();

        if($request->ajax()) {
            return response("", 200);
        }

        return redirect()->back();

    }

}
