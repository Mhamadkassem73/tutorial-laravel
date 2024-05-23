<?php

namespace App\Http\Controllers\Zoom;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\SendEmailController;
use App\Models\ZoomModel;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Jubaer\Zoom\Facades\Zoom;

class ZoomController extends ApiController
{
    public function createNewZoomMeeting(Request $request)
    {
        if(!auth()->user()->user_withprof)
        {
            return ApiController::errorResponse("Please upgrade your account to register Zoom with prof", 400);  
        }
        //return  Carbon::now()->toDateTimeString();

        //$dateToCheck = null;

        // if ($dateToCheck->isSaturday() || $dateToCheck->isSunday()) {
        //     $nextMonday = $dateToCheck->next(Carbon::MONDAY);
        //     return  "Next Monday is: " . $nextMonday->toDateTimeString();
        // } else {
        //     return  "The provided date is neither Saturday nor Sunday.";
        // }

        //$zoom_date=null;

        // if($maxDate)
        // {
        //     if () {
        //         return "Time is not 16:00:00";
        //     } else {

        //         return "Time is not 16:00:00. Next one hour is: " . $dateToCheck->toDateTimeString();
        //     }
        // }

        $dateToCheck = Carbon::now()->format('Y-m-d H');
        $maxDate = ZoomModel::where(
            [
                ['zoom_date', '>', Carbon::now()]
            ]
        )->max('zoom_date');

        //return $maxDate;
        //  $maxDate=null;
        if (!$maxDate) {
            $dateToCheck = Carbon::now()->startOfDay()->setHour(8)->setMinute(0)->setSecond(0);
        }
        $tomorrow = Carbon::tomorrow();

        $maxDate_format = Carbon::parse($maxDate);
        //return $maxDate->format('H');
        if ((!$maxDate || ($maxDate && intval($maxDate_format->format('H')) >= 16)) && ($tomorrow->isSaturday() || $tomorrow->isSunday())) {
            $nextMonday = $maxDate->next(Carbon::MONDAY)->startOfDay()->setHour(8)->setMinute(0)->setSecond(0);
            $zoom_date = $nextMonday->toDateTimeString();
        } else if ($maxDate && intval($maxDate_format->format('H')) < 16) {
            $zoom_date = $maxDate_format->addHour()->toDateTimeString();
        } else if (!$maxDate) {
            $zoom_date = $tomorrow->startOfDay()->setHour(8)->setMinute(0)->setSecond(0)->toDateTimeString();
        } else {
            $zoom_date = $maxDate_format->addDay()->startOfDay()->setHour(8)->setMinute(0)->setSecond(0)->toDateTimeString();
        }

        $zoom_date_format = Carbon::parse($zoom_date);



        $date =  Carbon::create($zoom_date_format->format('Y'), $zoom_date_format->format('m'), $zoom_date_format->format('d'), $zoom_date_format->format('H'), 0, 0);


        $meetings = Zoom::createMeeting([
            "agenda" => 'your agenda',
            "topic" => 'your topic',
            "type" => 2, // 1 => instant, 2 => scheduled, 3 => recurring with no fixed time, 8 => recurring with fixed time
            "duration" => 60, // in minutes
            "timezone" => 'Asia/Beirut', // set your timezone
            "password" => 'abcd12345' . auth()->user()->id, //
            "start_time" => $date, // set your start time Carbon::create(2024, 4, 27, 15, 0, 0)
            "template_id" => 'Dv4YdINdTk+Z5RToadh5ug==', // set your template id  Ex: "Dv4YdINdTk+Z5RToadh5ug==" from https://marketplace.zoom.us/docs/api-reference/zoom-api/meetings/meetingtemplates
            "pre_schedule" => false,  // set true if you want to create a pre-scheduled meeting
            "schedule_for" => 'info@midad.online', // set your schedule for ,'mhamadkassem73@gmail.com'//ali.kassem221994@outlook.com
            "settings" => [
                'join_before_host' => false, // if you want to join before host set true otherwise set false
                'host_video' => false, // if you want to start video when host join set true otherwise set false
                'participant_video' => false, // if you want to start video when participants join set true otherwise set false
                'mute_upon_entry' => false, // if you want to mute participants when they join the meeting set true otherwise set false
                'waiting_room' => false, // if you want to use waiting room for participants set true otherwise set false
                'audio' => 'both', // values are 'both', 'telephony', 'voip'. default is both.
                'auto_recording' => 'none', // values are 'none', 'local', 'cloud'. default is none.
                'approval_type' => 2, // 0 => Automatically Approve, 1 => Manually Approve, 2 => No Registration Required
            ],

        ]);

        //return $meetings['data']['join_url'];
        $createZoom = ZoomModel::create([
            'zoom_date' => $zoom_date,
            'user_id' => auth()->user()->id,
            'zoom_url' => $meetings['data']['join_url'],
            'zoom_password' => $meetings['data']['password'],
            'zoom_meetingid' => $meetings['data']['pstn_password'],
        ]);

        $sendEmailController = new SendEmailController();
        $sendEmail=$sendEmailController->sendZoomMailFunc(auth()->user()->user_email, $meetings['data']['pstn_password'],$meetings['data']['password'],$meetings['data']['join_url'],$zoom_date);
        //return  $createZoom;
        return ApiController::successResponse($createZoom, 200);
    }

    public function  fetchZoomById(Request $request)
    {
        $zoom = ZoomModel::where(
            [
                ['zoom_date', '>', Carbon::now()->toDateTimeString()],
                 ['user_id', '=',  auth()->user()->id],
            ]
        )->get();
        return ApiController::successResponse($zoom, 200);
    }


    public function getAllMeetings(Request $request)
    {
        $meetings = Zoom::getAllMeeting();
        return ApiController::successResponse($meetings, 200);
    }
}
