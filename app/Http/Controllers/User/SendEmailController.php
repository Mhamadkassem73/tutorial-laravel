<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Mail\MyTestMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use App\Mail\SampleMail;
use App\Mail\SendAttachmentEmail;
use App\Mail\TestEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Hash;

class SendEmailController extends ApiController
{
    public function sendEmail(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'message' => 'required',
        //     'to' => 'required',
        //     'subject' => 'required',
        //     'username' => 'required',
        //     'password' => 'required',
        //     'encryption' => 'required',
        //     'uuid' => 'required'
        // ]);

        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 422);
        // }

        // if ($request->username && $request->password && $request->encryption) {
        //     // Replace the values with database values
        //     config([
        //         'mail.mailers.smtp.username' => $request->username,
        //         'mail.mailers.smtp.password' => $request->password,
        //         'mail.mailers.smtp.encryption' => $request->encryption
        //         // Add other configurations as needed
        //     ]);

        //     config(['mail.from.address' => $request->username]);
        // }


        //  $recipient = $request->to;
        //   $subject = $request->subject;
        //   $body = $request->message;

        // $attachmentPath = null;
        // if ($request->file) {
        //     $path = public_path('/attachment/');
        //     if (!file_exists($path)) {
        //        // File::makeDirectory($path, $mode = 0777, true, true);
        //     }

        //     $file = $request->file;
        //     $extension = $file->getClientOriginalExtension();
        //     $document = 'attachment' . time() . '.' . $extension;
        //     $file->move($path, $document);
        //     $attachmentPath = public_path('attachment') . '/' . $document; // Path to the file you want to attach
        // }

        //  try {
        //    $mail = Mail::to($recipient);

        //   if ($attachmentPath && file_exists($attachmentPath)) {
        //       $mail->send(new SendAttachmentEmail($subject, $body, $attachmentPath));
        //   } else {
        //      $mail->send(new SendAttachmentEmail($subject, $body));
        //    }

        //   return Response()->json(["message" => "Email has been sent successfully!"], 200);
        // } catch (\Throwable $th) {
        //     return Response()->json(["message" => "Failed to authenticate !"], 500);
        // }
    }

    public function sendEmailFunc($recipient, $subject, $body)
    {
        $mail = Mail::to($recipient);
        $mail->send(new SendAttachmentEmail($subject, $body));
        return Response()->json(["message" => "Email has been sent successfully!"], 200);
    }

    public function forgotPassEmail(Request $request)
    {
        $user = User::where("user_email", "=", $request->email)->first();

        if (!$user) {
            return ApiController::errorResponse('Email does not found! Are you sure you are already a member?', 400);
        }

        $randomNumber = rand(1000, 9999);
        $timeNowPlus10Minutes = Carbon::now()->addMinutes(2);
        $updateUser = User::where("user_email", "=", $request->email)->update(
            [
                'user_forgotedate' => $timeNowPlus10Minutes,
                'user_forgotetoken' => $randomNumber
            ]
        );

        $body = "This is the code to forgot the password " . $randomNumber;
        $this->sendEmailFunc($request->email, "Forgot Password", $body);

        return ApiController::successResponse('Password reset sent! You\'ll receive an email if you are registered on our system.', 200);
    }


    public function forgotPass(Request $request)
    {
        $user = User::where([
            ['user_forgotedate', ">", Carbon::now()],
            ['user_forgotetoken', "=", $request->key],
        ])
            ->first();

            if($user && $request->password==$request->passwordConfirm)
            {

                $user = User::where([
                    ['id', "=", $user ->id],
                ])
                    ->update([
                        'password' =>Hash::make($request->password) 
                    ]);
                    return ApiController::successResponse('done', 200);
            }

            else{
                return ApiController::errorResponse('error', 400);
            }
    }


    public function sendZoomMailFunc($email,$zoom_id,$zoom_pass,$zoom_urL,$zoom_date)
    {




        $body ="the zoom meeting Date:  ".$zoom_date
        ."<br> Zoom Id :" .$zoom_id
        ."<br> password :".$zoom_pass 
        ."<br> Or URL  :".$zoom_urL ;
        $this->sendEmailFunc($email, "Zoom Meeting", $body);

        return true;
    }
}