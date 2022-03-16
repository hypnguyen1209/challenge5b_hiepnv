<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exercise;
use App\Models\Account;
use App\Models\SubmitExercise;

class ExerciseController extends Controller
{
    public function get() {
        if(auth()->user()->type === 'student') {
            return $this::getStudent();
        }
        return $this::getSubmitAll();
    }

    protected static function getStudent() {
        $selfId = auth()->user()->id;
        $exercises = Exercise::all();
        $mySubmits = SubmitExercise::where('userId', $selfId)->get();
        //print_r($mySubmits);
        $result = [];
        foreach($exercises as $v) {
            $tmp = [
                'id' => $v->id,
                'authorId' => $v->authorId,
                'title' => $v->title,
                'description' => $v->description,
                'url' => $v->url,
                'created_at' => $v->created_at,
                'updated_at' => $v->updated_at,
                'submitted' => []
            ];
            foreach($mySubmits as $submit) {
                if($submit->exerciseId == $tmp['id']) {
                    array_push($tmp['submitted'], [
                        'id' => $submit->id,
                        'exerciseId' => $submit->exerciseId,
                        'userId' => $submit->userId,
                        'url' => $submit->url,
                        'created_at' => $submit->created_at,
                        'updated_at' => $submit->updated_at
                    ]);
                }
            }
            array_push($result, $tmp);
        }
        return response()->json([
            'status' => true,
            'data' => $result
        ]);
    }

    public function create(Request $request) {
        $request->validate([
            'file' => 'required|mimes:png,jpg,jpeg,txt,docx,pdf|max:20480',
            'title' => 'required|max:255|min:1',
            'description' => 'required|min:1'
        ]);

        $authorId = auth()->user()->id;
        $fileName = rand(100, 999).'_'.$request->file->getClientOriginalName();  
        $request->file->move(public_path('uploads/exercise'), $fileName);
        Exercise::create([
            'authorId' => $authorId,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'url' => '/uploads/exercise/'.$fileName
        ]);
        return response()->json([
            'status' => true
        ]);
    }

    public function delete(Request $request, $id) {
        $exer = Exercise::where('id', $id)->first();
        if(\File::exists(public_path(ltrim($exer->url, '/')))) {
            \File::delete(public_path(ltrim($exer->url, '/')));
            $exer->delete();
        }else{
            return response()->json([
                'status' => false,
                'msg' => 'File does not exist'
            ]);
        }
        return response()->json([
            'status' => true
        ]);
    }

    public function submit(Request $request, $id) {
        $request->validate([
            'file' => 'required|mimes:png,jpg,jpeg,txt,docx,pdf|max:20480'
        ]);
        if(!Exercise::where('id', $id)->exists()) {
            return response()->json([
                'status' => false,
                'msg' => 'invalid exerciseId'
            ], 400);
        }
        $selfId = auth()->user()->id;
        $fileName = rand(100, 999).'_'.$request->file->getClientOriginalName();  
        $request->file->move(public_path('uploads/submit'), $fileName);
        if(SubmitExercise::where('userId', $selfId)->where('exerciseId', $id)->exists()) {
            SubmitExercise::where('userId', $selfId)->where('exerciseId', $id)->update([
                'url' => '/uploads/submit/'.$fileName
            ]);
            return response()->json([
                'status' => true,
                'msg' => 'Submition has been updated'
            ]);
        }
        SubmitExercise::create([
            'userId' => $selfId,
            'exerciseId' => $id,
            'url' => '/uploads/submit/'.$fileName
        ]);
        return response()->json([
            'status' => true,
            'msg' => 'Submition has been updated'
        ]);
    }

    public function getSubmit(Request $request, $id) {
        $submited = SubmitExercise::where('exerciseId', $id)->get();
        return response()->json([
            'status' => true,
            'data' => $submited
        ]);
    }

    public function submitted(Request $request, $id) {
        $selfId = auth()->user()->id;
        $submition = SubmitExercise::where('userId', $selfId)->where('exerciseId', $id)->first();
        return response()->json([
            'status' => true,
            'data' => $submition
        ]);
    }
    protected static function getSubmitAll() {
        $submition = SubmitExercise::all();
        $exers = Exercise::all();
        $result = [];
        foreach($exers as $v) {
            $tmp = [
                'id' => $v->id,
                'authorId' => $v->authorId,
                'title' => $v->title,
                'description' => $v->description,
                'url' => $v->url,
                'created_at' => $v->created_at,
                'updated_at' => $v->updated_at,
                'submitted' => []
            ];
            foreach($submition as $submit) {
                if($submit->exerciseId == $tmp['id']) {
		    $username = Account::where('id', $submit->userId)->first()->username;
                    array_push($tmp['submitted'], [
                        'id' => $submit->id,
                        'exerciseId' => $submit->exerciseId,
                        'userId' => $submit->userId,
			'username' => $username,
                        'url' => $submit->url,
                        'created_at' => $submit->created_at,
                        'updated_at' => $submit->updated_at
                    ]);
                } 
            }
            //print_r($tmp);
            array_push($result, $tmp);
        }
        return response()->json([
            'status' => true,
            'data' => $result
        ]);
    }
}
