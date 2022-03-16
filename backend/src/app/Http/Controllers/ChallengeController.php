<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChallengeController extends Controller
{
    protected static function allChallengeFiles() {
        $path = public_path('uploads/challenge');
        $files = [];
        foreach(scandir($path) as $file) {
            if(!in_array($file, ['.', '..'])) {
                array_push($files, $file);
            }
        }
        return $files;
    }

    protected static function getContentFile($id) {
        foreach(self::allChallengeFiles() as $file) {
            if(preg_match('/^challenge__'.$id.'\.(.*)\.txt$/', $file)) {
                return \File::get(public_path('uploads/challenge').'/'.$file);
            }
        }
        return null;
    }

    protected static function getAnswer($id) {
        foreach(self::allChallengeFiles() as $file) {
            if(preg_match('/^challenge__'.$id.'\.(.*)\.[\w ]+\.txt$/', $file, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    protected static function checkAnswser($id, $answer) {
        return $answer === self::getAnswer($id);
    }

    protected static function getFileNameId($id) {
        foreach(self::allChallengeFiles() as $file) {
            if(preg_match('/^challenge__'.$id.'\.(.*)\.txt$/', $file, $matches)) {
                return $file;
            }
        }
        return null;
    }

    protected static function setId() {
        $max = 0;
        foreach(self::allChallengeFiles() as $file) {
            if(preg_match('/^challenge__(\d+).[\S ]+\.txt$/', $file, $matches)) {
                $maxId = intval($matches[1]);
                if($maxId > $max) $max = $maxId;
            }
        }
        return strval(++$max);

    }

    public function create(Request $request) {
        $request->validate([
            'file' => 'required|mimes:txt|max:5120',
	    'title' => 'required|regex:/^[\w ]+$/',
	    'description' => 'required|regex:/^[\w ]+$/'
        ]);
        $id = $this::setId();
        $fileName = 'challenge__'.$id.'.'.$request->input('title').'.'.$request->input('description').'.txt';
        $request->file->move(public_path('uploads/challenge'), $fileName);
        return response()->json([
            'status' => true
        ]);
    }

    public function get() {
        $result = [];
        foreach($this::allChallengeFiles() as $file) {
            preg_match_all('/^challenge__(\d+)\.([\w ]+)\.([\w ]+)\.txt$/m', $file, $matches, PREG_SET_ORDER, 0);
            //print_r($matches);
	    if(auth()->user()->type !== 'student') {
		array_push($result, [
		    'id' => $matches[0][1],
		    'title' => $matches[0][2],
		    'description' => $matches[0][3]
		]);
	    } else {
		array_push($result, [
		    'id' => $matches[0][1],
		    'description' => $matches[0][3]
		]);
	    }
        }
        return response()->json([
            'status' => true,
            'data' => $result
        ]);
    }

    public function delete(Request $request, $id) {
        $fileName = $this::getFileNameId($id);
        if($fileName) {
            \File::delete(public_path('uploads/challenge/'.$fileName));
            return response()->json([
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'invalid ID'
            ], 422);
        }
    }

    public function submit(Request $request, $id) {
        $answer = $request->input('answer');
        return $this::checkAnswser($id, $answer) ? response()->json([
            'status' => true,
            'content' => $this::getContentFile($id)
        ]) : response()->json([
            'status' => false,
            'message' => 'Answer incorrect'
        ]); 
    }

    public function test(Request $request) {
        return $this::getAnswer(3);
    } 

}
