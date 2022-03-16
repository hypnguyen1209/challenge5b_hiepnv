<?php

namespace App\Http\Controllers;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Avatar;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{

    public function login(Request $request) {
        $input = $request->only('username', 'password');
        $token = null;
        if (!$token = JWTAuth::attempt($input)) {
            return response()->json([
                'status' => false,
                'msg' => 'Invalid username or password',
            ], 401);
        }
        return response()->json([
            'status' => true,
            'token' => $token,
        ]);
    }

    public function me() {
        return response()->json(auth()->user());
    }

    public function logout(Request $request) {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);
            return response()->json([
                'status' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'status' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }

    public function getMessage() {
        return response()->json([
            'status' => true,
            'message' => auth()->user()->message
        ]);

    }

    public function sendMessage(Request $request, $id) {
	$request->validate([
	    'message' => 'required'
	]);
        $message = $request->input('message');
        $user = Account::where('id', $id)->first();
        $user->message = $message;
        $user->save();
        return response()->json([
            'status' => true
        ]);
    }

    public function getAll() {
        $all = Account::all();
        return response()->json([
            'status' => true,
            'data' => $all
        ]);
    }
    public function changePassword(Request $request) {
        $request->validate([
	    'old_password' => 'required|min:8',
	    'new_password' => 'required|min:8'
	]);
        $oldPassword = $request->input('old_password');
        $self = auth()->user();
        if(JWTAuth::attempt(['username' => $self->username, 'password' => $oldPassword])) {
            $newPassword = $request->input('new_password');
            $newHashed = Hash::make($newPassword);
            $user = Account::where('id', $self->id)->first();
            $user->password = $newHashed;
            $user->save();
            return response()->json([
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'msg' => 'Password incorrect!'
            ]);
        }
    }

    public function getAvatar() {
        $self = auth()->user();
        $avatar = Avatar::where('id', $self->id)->first();
        return response()->json([
            'status' => true,
            'url' => $avatar->url
        ]);
    }

    public function changeAvt(Request $request) {
        $request->validate([
            'file' => 'required|image|mimes:png,jpg,jpeg|max:5120',
        ]);
        $self = auth()->user();
	$avt = Avatar::where('id', $self->id)->first();
	\File::delete(public_path(ltrim($avt->url, '/')));
        $fileName = $self->id.'.'.$request->file->extension();
        $request->file->move(public_path('uploads'), $fileName);
        Avatar::where('id', $self->id)->update([
            'url' => '/uploads/'.$fileName
        ]);
        return response()->json([
            'status' => true,
            'url' => '/uploads/'.$fileName
        ]);
    }

    public function getStudents(Request $request) {
	$students = Account::where('type', 'student')->get();
	return response()->json([
	    'status' => true,
	    'data' => $students
	]);
    }
    public function changeInfo(Request $request) {
        $self = auth()->user();
        $request->validate([
            'username' => 'required|min:6',
            'fullname' => 'required|min:6',
            'phone' => 'required|min:10',
            'email' => 'required|email'
        ]);
        $user = Account::where('id', $self->id)->first();
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        if($self->type !== 'student') {
            $user->username = $request->input('username');
            $user->fullname = $request->input('fullname');
        }
        $user->save();
        return response()->json([
            'status' => true,
            'msg' => 'Infomation has been changed!'
        ]);
    }

    public function changeInfoStudent(Request $request, $id) {
        $request->validate([
	    'username' => 'required|min:6',
	    'fullname' => 'required|min:6',
	    'phone' => 'required|min:10',
	    'email' => 'required|email'
	]);
        $user = Account::where('type', 'student')->where('id', $id)->first();
        $user->update([
            'username' => $request->input('username'),
            'fullname' => $request->input('fullname'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone')
        ]);
        return response()->json([
            'status' => true
        ]);
    }
    public function changeInfoAll(Request $request, $id) {
        $request->validate([
            'username' => 'required|min:6',
            'fullname' => 'required|min:6',
            'phone' => 'required|min:10',
            'email' => 'required|email'
        ]);
        $user = Account::where('id', $id)->first();
        $user->update([
            'username' => $request->input('username'),
            'fullname' => $request->input('fullname'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone')
        ]);
        return response()->json([
            'status' => true
        ]);
    }
    public function changeInfoUser(Request $request, $id) {
	$typeUser = auth()->user()->type;
	if($typeUser === 'teacher') {
	    return $this->changeInfoStudent($request, $id);
	} else if($typeUser === 'admin') {
	    return $this->changeInfoAll($request, $id);
	}
    }
}
