<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;


class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProjectResource::collection(Project::orderBy('updated_at', 'desc')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "name" => ['required', 'unique:projects', 'max:255'],
                "description" => ["required"],
                "slug" => ['required', 'unique:projects', 'max:255'],
            ]
        );

        if($validator->fails()){
            return [
                "status" => false,
                "errors" => $validator->messages()
            ];
        }
        
        $project = Project::create([
            'name' => $request->name,
            "description" => $request->description,
            "slug" => $request->slug,
        ]);

        return [
            "status" => true,
            "project" => $project
        ];

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $project = ProjectResource::collection(Project::where('slug', $slug)->get());

        if(!$project){
            return response()->json([
                'status' => false,
                'messages' => 'Not found'
            ])->setStatusCode(404);
        }

        return $project;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        if($project->delete()){
            return [
                "status" => true,
                "project" => "delete"
            ];
        }else{
            return [
                "status" => false,
                "project" => "No delete"
            ];
        }
    }
}
