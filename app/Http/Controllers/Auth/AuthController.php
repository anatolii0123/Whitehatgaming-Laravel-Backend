<?php
  
namespace App\Http\Controllers\Auth;
  
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailDemo;
use App\Mail\Gmail;
use App\Models\User;
use App\Models\School;
use App\Models\Classtable;
use Hash;
use App\Models\FileUpload;
  
class AuthController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */

    public function index()
    {
        if(!Auth::check()){
            return view('auth.login');
        }
        else if(Auth::user()->school_id == null && Auth::user()->role == 'Director'){
            return view('auth.login');
        }
        else{
            return view('home');
        }
        
    }  

    public function welcome()
    {
        if(Auth::check()){
            return view('home');
        }
        return view('auth.login');
    }  

    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            if(Auth::user()->role == 'Director' &&  Auth::user()->school_id == null){
                return redirect()->intended('login')->with('message', "You don't have any school!");
            }
            else{
                if(Auth::user()->impersonate != 'active'){
                $roledata = Auth::user()->role;
                $namedata = Auth::user()->name;
                $iddata = Auth::user()->id;
                $school = Auth::user()->school_id;
                // $token = Auth::user()->email;
                }else{
                    $roledata = 'master';
                    $namedata = 'super admin';
                    $iddata = 1;
                    $school = null;
                }
                session(['role' => $roledata, 'name' => $namedata,'id' => $iddata, 'school' => $school]);
                
                return redirect()->intended('home')->with('message', 'You have Successfully loggedin');
            }
            
        
        }
        return redirect()->intended('login')->with('message', 'Oppes! You have entered invalid credentials');
    }

        
    /**
     * Write code on Method
     *
     * @return response()
     */
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function home()
    {
        if(Auth::check()){
            if(Auth::user()->role != 'master'){
                return view('home');
            }else{
                $userdatas = User::where('role', 'Director')
                                ->get();
                return view('pages/support/users', compact('userdatas'));
            }
        }
        return redirect("login")->with('message','Opps! You do not have access');
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    
    
    public function codepage(){
        return view("codesend");
    }
    
    public function resetpage(){
        $token = Session::get('token');
        $errordata = null;
        if($token == null){
            return redirect('/codepage', compact('You are out of role!'));
        }else{
            return view("/auth/passwords/reset", compact('token', 'errordata'));
        }
        
    }

   

    /**
     * Write code on Method
     *
     * @return response()
     */
    
    public function emailSend(Request $request)
    {   $request-all();
        $data = User::where('email', '=', request()->get('email'))->exists(); 
        
        $email = $request->email;
        if($data == true){
            $random = strval(rand(0, 99999));
            $combine = $random.$email;
            User::where('email', $request->email)->update(['remember_token' => $combine]);
            $mailData = [
                'title' => 'Check email validiation',
                'content' => $combine
            ];
            Mail::to($email)->send(new EmailDemo($mailData));
            return response()->json(['code'=>200, 'message'=>'true'], 200);
        }else{
            return response()->json(['code'=>200, 'message'=>'No exist!'], 200);
        }
    }

    public function codeSend(Request $request)
    {  
        $data = User::where('remember_token', '=', $request->code)->exists(); 
        if($data == true){
            $token = $request->code;
            session(['token'=>$token]);
            return response()->json(['code'=>200, 'message'=>'Passed!'], 200);
        }else{
            return response()->json(['code'=>200, 'message'=>'Failed!'], 200);
        }
    }

   
    
    public function logout(Request $request) {
        
        $user = Auth::user();
        $user->name = Session::get('name');
        $user->role = Session::get('role');
        $user->school_id = Session::get('school');
        $user->impersonate = '';
        $user->save();
        
        Session::flush();
        Auth::logout();
  
        return Redirect('login');
    }
    
    

    public function passwordUpdate(Request $request){
        $email = $request->email;
        $passworddata =  Hash::make($request->password);
        $userdata = User::where('email', '=', $email);
        $isExist = User::where('email', '=', $email)->exists();
        if($userdata->get('remember_token') == null){
            return response()->json(['code'=>200, 'message'=>'Other email'], 200);
        }
        else if($isExist == true){
            User::where('email', $email)->update(['password' => $passworddata]);
            User::where('email', $email)->update(['remember_token' => null]);
            Session::flush();
            return response()->json(['code'=>200, 'message'=>'true'], 200);
        }else{
            return response()->json(['code'=>200, 'message'=>'Invalid email'], 200);
        }
    }
    
   
}