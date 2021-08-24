<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class EpisodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $episodes = Episode::with('donghua')->orderBy('created_at', 'desc')->get();
        $response = [
            'messasge' => 'List episode order by posted at',
            'data' => $episodes
        ];

        return response()->json($response, Response::HTTP_OK);
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
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'min:4', 'max:64', 'string', 'unique:episodes'],
            'sub_title' => ['required', 'min:4', 'max:32', 'string', 'unique:episodes'],
            'resolutions' => ['required', 'string', 'min:3', 'max:24'],
            'links' => ['required', 'string', 'min:4', 'max:255'],
            'streaming' => ['required', 'string', 'min:4', 'max:255'],
            'donghua_id' => ['required', 'integer']
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $episode = Episode::create($request->all());
            $response = [
                'message' => 'Episode created.',
                'data' => $episode
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'message' => ["Failed", $e->errorInfo]
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Episode  $episode
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $episode = Episode::with('donghua')->findOrFail($id);
        $response = [
            'message' => 'Detail episode.',
            'data' => $episode
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Episode  $episode
     * @return \Illuminate\Http\Response
     */
    public function edit(Episode $episode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Episode  $episode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Episode $episode)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'min:4', 'max:64', 'string', 'unique:episodes'],
            'sub_title' => ['required', 'min:4', 'max:32', 'string', 'unique:episodes'],
            'resolutions' => ['required', 'string', 'min:3', 'max:24'],
            'links' => ['required', 'string', 'min:4', 'max:255'],
            'streaming' => ['required', 'string', 'min:4', 'max:255'],
            'donghua_id' => ['required', 'integer']
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $data = $episode->update($request->all());
            $response = [
                'message' => 'Episode updated.',
                'data' => $data
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'message' => ["Failed", $e->errorInfo]
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Episode  $episode
     * @return \Illuminate\Http\Response
     */
    public function destroy(Episode $episode)
    {
        try {
            $episode->delete();
            $response = [
                'message' => 'Episode deleted.',
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'message' => ["Failed", $e->errorInfo]
            ]);
        }
    }
}
