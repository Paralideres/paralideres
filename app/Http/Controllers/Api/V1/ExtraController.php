<?php



namespace App\Http\Controllers\Api\V1;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Tag;
use Illuminate\Support\Facades\Validator;



class ExtraController {

    public function create(Request $request)
    {
        // $request->user()
        $input = $request->input();
        $tag = new Tag();
        $tag->label = $input['tag'];
        $tag->slug = str_replace(' ', '_', $input['tag']);
        $tag->created_at = Carbon::now();
        $tag->save();
        $rv = array(
            "status" => 2000,
            "tag" => $tag->toArray()
        );
        return json_encode($rv, true);
    }
    public function signup(Request $request)
    {
        // $request->user()
        $input = $request->input();
        $validator = Validator::make($input, [
            'email' => 'required|email|unique:users',
            'password' => 'required|between:8,32',
            'password_confirmation' => 'required|between:8,32',
        ]);
        if($validator->fails()){
            $rv = array(
                "status" => 5000,
                "data" => $validator->messages()
            );
            return json_encode($rv, true);
        } else {

            if($input['password'] != $input['password_confirmation']){
                $rv = array(
                    "status" => 5000,
                    "data" => array(
                        "password" => ["The password and password confirmation field is mismatched."]
                    )
                );
                return json_encode($rv, true);
            } else {

                $email = $input['email'];
                $password = bcrypt($input['password_confirmation']);
                $username = strtok($request->email, '@');
                $is_active = 0;
                $activation_token = md5(uniqid());

                $userCreate = new User();
                $userCreate->username = $username;
                $userCreate->email = $email;
                $userCreate->password = $password;
                $userCreate->is_active = $is_active;
                $userCreate->activation_token = $activation_token;
                $userCreate->created_at = Carbon::now();
                if($userCreate->save()){

                    $to = $email;
                    $subject = "paralideres : Account Activation";

                    $message = '<html>
                                    <head>
                                    <title>Paralideres : Activation Token</title>
                                    </head>
                                    <body>
                                    <p>
                                    To activate your account please click on the below link or copy and paste the url to your 
                                    browser and activate your account
                                    </p>
                                    <br><br>
                                    <p><a href="'.env('APP_URL').'/account/activation/'.$activation_token.'"></a></p>
                                    
                                    </body>
                                    </html>';
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= 'From: no-reply.paralideres@gmail.com' . "\r\n";

                    $mail = mail($to,$subject,$message,$headers);

                    $rv = array(
                        "status" => 2000,
                        "data" => array(
                            "msg" => ["User registration has been done successfully"]
                        )
                    );
                    return json_encode($rv, true);

                } else {
                    $rv = array(
                        "status" => 6000,
                        "data" => array(
                            "error" => ["Fatal Error! Database not found."]
                        )
                    );
                    return json_encode($rv, true);
                }

            }



        }
        dd($validator->fails(), $validator->messages());
        $tag = new Tag();
        $tag->label = $input['tag'];
        $tag->slug = str_replace(' ', '_', $input['tag']);
        $tag->created_at = Carbon::now();
        $tag->save();
        $rv = array(
            "status" => 2000,
            "tag" => $tag->toArray()
        );
        return json_encode($rv, true);
    }
}