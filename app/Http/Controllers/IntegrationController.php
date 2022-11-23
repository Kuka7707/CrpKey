<?php

namespace App\Http\Controllers;

use App\Models\Integration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IntegrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Integration::orderBy('updated_at', 'desc')->get();
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
                'name' => ["required"],
                'project_id' => ["required"],
                'slug' => ['required', 'unique:projects', 'max:255'],
                // 'client_id' => ["required"],
                // 'client_secret' => ["required"],
                'auth_token' => ["required"]
            ]
        );

        if($validator->fails()){
            return [
                "status" => false,
                "errors" => $validator->messages()
            ];
        }
        
        $integration = Integration::create([
            "name" => $request->name,
            "project_id" => $request->project_id,
            "slug" => $request->slug,
            "profile" => $request->profile,
            "account" => $request->account,
            // 'client_id' =>  $request->client_id,
            // 'client_secret' =>  $request->client_secret,
            'auth_token' =>  $request->auth_token
        ]);

        return [
            "status" => true,
            "project" => $integration
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
        $integration = Integration::where('slug', $slug)->firstOrFail();

        if(!$integration){
            return response()->json([
                'status' => false,
                'messages' => 'Not found'
            ])->setStatusCode(404);
        }

        return $integration;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $integration)
    {   
        $integration = Integration::find($integration);

        $validator = Validator::make(
            $request->all(),
            [
                'name' => ["required"],
            ]
        );

        if($validator->fails()){
            return [
                "status" => false,
                "errors" => $validator->messages()
            ];
        }

        $integration->name = ($request->name);
        $integration->profile = ($request->profile);
        $integration->account = ($request->account);
        
        $integration->save();

        return  $integration;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Integration $integration)
    {   
        if($integration->delete()){
            return [
                "status" => true,
                "integr" => "delete"
            ];
        }else{
            return [
                "status" => false,
                "integr" => "No delete"
            ];
        }
            
    }
}
