<?php

namespace App\Http\Controllers;

use App\Models\Peliculas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class PeliculasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $peliculas = Peliculas::all();
        return response()->json($peliculas);
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
    public function store(Request $request) {
        header('Access-Control-Allow-Headers: *');
          $image=$request->file('image');
       
          if($image){
            $image_path=$image->getClientOriginalName();
           \Storage::disk('films-videos')->put($image_path, \File::get($image));
           
    }
         $data=array(
         
            'image'=>$image, 
            'status'=>'success'
        );
        return response()->json($data,200);

        

        $image = $request->file('image');
        
        return response()->json($image->getClientOriginalName());
        return response()->json($image);
        return response()->json($request);

        $pelicula = new Peliculas();
        $pelicula->name = $request->name;
        $pelicula->price = $request->price;
        $pelicula->duration = $request->duration;
        $pelicula->video_url = $request->video_url;

        $img = $request->storeAs(
            'public/films-videos/',
            'my_film'
        );
        $url = Storage::url($img);
        if ($pelicula->save()) {
            return response()->json($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Peliculas  $peliculas
     * @return \Illuminate\Http\Response
     */
    public function show(Peliculas $peliculas, $id)
    {
        $pelicula = Peliculas::where('id', $id)->get();
        return response()->json($pelicula);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Peliculas  $peliculas
     * @return \Illuminate\Http\Response
     */
    public function edit(Peliculas $peliculas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Peliculas  $peliculas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pelicula = Peliculas::findOrFail($id);
        $pelicula->name = $request->name;
        $pelicula->price = $request->price;
        $pelicula->duration = $request->duration;
        $pelicula->save();

        return response()->json($pelicula);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Peliculas  $peliculas
     * @return \Illuminate\Http\Response
     */
    public function destroy(Peliculas $peliculas, $id)
    {
        $pelicula = Peliculas::findOrFail($id);
        $pelicula->delete();

        return response()->json($pelicula);
    }
}
