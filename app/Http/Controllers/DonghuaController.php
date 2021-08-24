<?php

namespace App\Http\Controllers;

use App\Models\Donghua;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class DonghuaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $donghuas = Donghua::with('user','genre')->orderBy('created_at', 'desc')->get();
        $response = [
            'messasge' => 'List donghua order by posted at',
            'data' => $donghuas
        ];

        return response()->json($response, Response::HTTP_OK);
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
            'title' => ['required', 'max:64', 'min:4', 'string', 'unique:donghuas'],
            'image' => ['required', 'image', 'max:4096', 'mimes:png,jpg,jpeg,gif,svg'],
            'synopsis' => ['required', 'max:300', 'min:8'],
            'status' => ['required', 'in:Ongoing,Completed'],
            'network' => ['required', 'string'],
            'studio' => ['required', 'string'],
            'release_date' => ['required', 'date'],
            'duration' => ['required', 'string'],
            'graphic' => ['required', 'string'],
            'country' => ['required', 'string'],
            'type' => ['required', 'in:Donghua,Anime'],
            'translated_by' => ['required', 'string'],
            'user_id' => ['required', 'integer'],
            'description' => ['required', 'max:300']
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['slug'] = Str::slug($request->title);
            $data['image'] = time() . '.' . $request->file('image')->getClientOriginalExtension();

            $move_image = $request->file('image')->move(public_path() . '/' . ('images'), $data['image']);
            $donghua = Donghua::create($data);
            $response = [
                'message' => 'Donghua created.',
                'data' => $donghua
            ];

            $donghua->genre()->attach($request->genre);

            DB::commit();

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            DB::rollback();
            @unlink($move_image);
            return response()->json([
                'message' => ["Failed", $e->errorInfo]
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $donghua = Donghua::with('user','genre')->findOrFail($id);

        $response = [
            'messasge' => 'Detail donghua.',
            'data' => $donghua
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $donghua_detail = Donghua::findOrFail($id);
        $validate = Validator::make($request->all(), [
            'title' => 'required|max:64|min:4|string|unique:donghuas,title,'.$donghua_detail->id,
            'image' => ['required', 'image', 'max:4096', 'mimes:png,jpg,jpeg,gif,svg'],
            'synopsis' => ['required', 'max:300', 'min:8'],
            'status' => ['required', 'in:Ongoing,Completed'],
            'network' => ['required', 'string'],
            'studio' => ['required', 'string'],
            'release_date' => ['required', 'date'],
            'duration' => ['required', 'string'],
            'graphic' => ['required', 'string'],
            'country' => ['required', 'string'],
            'type' => ['required', 'in:Donghua,Anime'],
            'translated_by' => ['required', 'string'],
            'user_id' => ['required', 'integer'],
            'description' => ['required', 'max:300']
        ]);

        if($validate->fails()){
            return response()->json($validate->errors(),
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();
        try {
            $data = $request->all();
            if($request->has('image'))
            {
                $data['slug'] = Str::slug($request->title);
                $data['image'] = time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(public_path() . '/' . ('images'), $data['image']);
                $donghua_detail->genre()->sync($request->genre);
                $donghua = $donghua_detail->update($data);
                $response = [
                    'message' => 'Donghua updated.',
                    'data' => $donghua
                ];

                DB::commit();

                return response()->json($response, Response::HTTP_OK);
            } else
            {
                $data['slug'] = Str::slug($request->title);
                $donghua = $donghua_detail->update($data);
                $response = [
                    'message' => 'Donghua updated.',
                    'data' => $donghua_detail
                ];

                DB::commit();

                return response()->json($response, Response::HTTP_OK);
            }

        } catch (QueryException $e) {
            DB::rollback();
            @unlink($request->file('image')->move(public_path() . '/' . ('images'), $data['image']));
            return response()->json([
                'message' => ["Failed", $e->errorInfo]
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $donghua_detail = Donghua::findOrFail($id);

        DB::beginTransaction();
        try {
            $old_image = public_path('/images/').$donghua_detail->image;
            if(file_exists($old_image)){
                @unlink($old_image);
            }
            $donghua_detail->delete();
            $response = [
                'message' => 'Donghua deleted.'
            ];

            DB::commit();

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            DB::rollback();
            return response()->json([
                'message' => ["Failed", $e->errorInfo]
            ]);
        }
    }
}
