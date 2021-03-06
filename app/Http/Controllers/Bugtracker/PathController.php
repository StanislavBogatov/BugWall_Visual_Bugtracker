<?php

namespace App\Http\Controllers\Bugtracker;

use App\Http\Controllers\Controller;

use App\Http\Requests\DeletePathRequest;
use App\Path;
use Illuminate\Http\Request;

use App\Project;
use App\Board;

class PathController extends Controller
{

    public function all(Project $project, Board $board)
    {
        $board->paths->load('creator');
        return new \App\Http\Resources\Path\PathCollection($board->paths);
    }

    public function create(Request $request, Project $project, Board $board)
    {
        $path = $board->paths()->create($request->all());
        return new \App\Http\Resources\Path\PathResource($path);
    }

    public function destroy(DeletePathRequest $request, Project $project, Board $board, Path $path)
    {
        $board->paths()->where('path_slug', $request->path_slug)->first()->delete();

        if($request->ajax()) {
            return response("", 200);
        }

        return redirect()->back();
    }

}
