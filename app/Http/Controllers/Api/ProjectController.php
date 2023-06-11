<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    //Create Project
    public function createProject(Request $request)
    {
        //validate
        $request->validate([
            "name" => "required",
            "description" => "required",
            "duration" => "required"
        ]);

        //student id + Create Data
        $student_id = auth()->user()->id;

        $project = new Project();

        $project->name = $request->name;
        $project->student_id = $student_id;
        $project->description = $request->description;
        $project->duration = $request->duration;

        $project->save();

        //response
        return response()->json([
            "status" => 1,
            "message" => "Project Created Successfully"
        ]);
    }

    //List Project
    public function listProject()
    {


        $student_id = auth()->user()->id;

        $projects = Project::where('student_id', $student_id)->get();

        return response()->json([
            "status" => 1,
            "message" => "Project",
            "data" => $projects
        ]);
    }

    //Single Project
    public function singleProject($id)
    {

        $student_id = auth()->user()->id;

        if (Project::where([
            "id" => $id,
            "student_id" => $student_id
        ])->exists()) {

            $details = Project::where([
                "id" => $id,
                "student_id" => $student_id
            ])->first();

            return response()->json([
                "status" => 1,
                "message" => "Project",
                "data" => $details
            ]);
        } else {
            return response()->json([
                "status" => 0,
                "message" => "Project not found"
            ]);
        }
    }

    //delete project
    public function deleteProject($id)
    {
        $student_id = auth()->user()->id;

        if (Project::where([
            "id" => $id,
            "student_id" => $student_id
        ])->exists()) {
            $project = Project::where([
                "id" => $id,
                "student_id" => $student_id
            ])->first();

             $project -> delete(); 

             return response()->json([
                "status" => 0,
                "message" => "Project deleted successfully"
             ]);
        } else {
            return response()->json([
                "status" => 0,
                "message" => "Project do not exist"

            ]);
        }
    }
}
