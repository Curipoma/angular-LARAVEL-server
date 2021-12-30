<?php

namespace App\Http\Controllers;

use App\Models\Films;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class FilmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $films = Films::paginate(4);
        return response()->json($films, 200);
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
    public function store(Request $req) {
        $filmVideo = $req->file('filmvideo');
        $filmCover = $req->file('filmcover');
        
        if($filmCover && $filmVideo){
            $filmVideoPath = pathinfo($filmVideo->getClientOriginalName());
            $filmCoverPath = pathinfo($filmCover->getClientOriginalName());

            $nameFile = intval(DB::table('films')->count()) + 1;

            $nameVideo = strval($nameFile).'.'. strval($filmVideoPath['extension']);
            $nameImage = strval($nameFile).'.'. strval($filmCoverPath['extension']);

            $urlFilmVideo = Storage::url(
                $filmVideo->storeAs("public/film-videos", $nameVideo)
            );
            $urlFilmCover = Storage::url(
                $filmCover->storeAs("public/film-covers", $nameImage)
            );
            
            $reqFilm = json_decode($req->film);
            $film = new Films();
            $film->name = $reqFilm->name;
            $film->price = $reqFilm->price;
            $film->duration = $reqFilm->duration;
            $film->video_url = $urlFilmVideo;
            $film->cover_page_url = $urlFilmCover;

            if ($film->save()) {
                return response()->json($film, 200);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Films  $films
     * @return \Illuminate\Http\Response
     */
    public function show(Request $req, $id) {
        $film = Films::where('id', $id)->get();
        return response()->json($film);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Films  $films
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $req)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Films $films
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id) {
        $reqFilm = json_decode($req->film);
        if ($reqFilm && $id) {
            $film = Films::findOrFail($id);
            $film->name = $reqFilm->name;
            $film->price = $reqFilm->price;
            $film->duration = $reqFilm->duration;
        }

        $filmvideo = $req->file('filmvideo');
        $filmcover = $req->file('filmcover');

        if ($filmvideo) {
            $filmvideoPath = pathinfo($filmvideo->getClientOriginalName());
            $nameVideo = $film->id .'.'. strval($filmvideoPath['extension']);
            unlink(public_path().$film->video_url);
            
            $urlFilmVideo = Storage::url(
                $filmvideo->storeAs("public/film-videos", $nameVideo)
            );
            $film->video_url = $urlFilmVideo;
        }
        
        if ($filmcover) {
            $filmcoverPath = pathinfo($filmcover->getClientOriginalName());
            $nameImage = $film->id .'.'. strval($filmcoverPath['extension']);
            unlink(public_path().$film->cover_page_url);
            
            $urlFilmCover = Storage::url($filmcover->storeAs("public/film-covers", $nameImage));
            $film->cover_page_url = $urlFilmCover;
        }

        if ($film->save()) {
            return response()->json($film, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Films  $films
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $film, $type, $id) {
        $film = film::findOrFail($id);
        if (
            unlink(public_path().$film->video_url) &&
            unlink(public_path().$film->cover_page_url) &&
            $film->delete()
        ) {
            return response()->json($film);
        }
        return response()->json(['message' => 'Data could not be deleted :/']);
    }

    public function download_image(Request $request, $id) {
        $film = film::findOrFail($id);
        $urlVideo = public_path($film->cover_page_url);
        $filmvideoPath = pathinfo($film->cover_page_url);
        $nameFile = $film->name .'.'. $filmvideoPath['extension'];
        return response()->download($urlVideo, $nameFile);
    }
    public function download_video(Request $request, $id) {
        $film = film::findOrFail($id);
        $urlVideo = public_path($film->video_url);
        $filmvideoPath = pathinfo($film->video_url);
        $nameFile = $film->name .'.'. $filmvideoPath['extension'];
        return response()->download($urlVideo, $nameFile);
    }

    // function getFile(Request $request) {
    //     $file = base64_decode(Storage::get('public/film-videos/butterfly.mp4'));
    //     return response($file)->header('Content-Type', 'video/mp4');
    // }
}
