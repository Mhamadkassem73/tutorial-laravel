<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use App\Models\userAnswers;
use Faker\Core\Number;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\String_;

class QuestionController extends ApiController
{


    public function fetchFirstQuestion(Request $request){
        // $questionId= auth()->user_lastQuestionId;
         $questionId= auth()->user()->user_lastQuestionId;

        $question=$this->fetchNextQuestionByIdFynction($questionId);
        return ApiController::successResponse($question, 200);
    }
    public function fetchNextQuestionById(Request $request){
        $updateUser=User::where("id","=",auth()->user()->id)            
        ->update([
            'user_lastQuestionId' => $request->questionId
        ]);
        $this->saveAnswer($request->questionId,$request->answer,$request->isTrue,auth()->user()->id);
        $questionId=Question::where("question_id",'>',$request->questionId)
        ->orderBy("question_id", "asc")
        ->first()
        ->question_id;
        $question=$this->fetchNextQuestionByIdFynction($questionId);
        return ApiController::successResponse($question, 200);
    }
    public function fetchNextQuestionByIdFynction($questionId){
        $question=Question::where("question_id","=",$questionId)->first();
        $data['question']=$question;
        $data['image']=env('DB_HOST').$question->level_id."/".$question->lesson_id."/".$question->axis_id."/".$question->question_id."/".$question->question_image;
        $data['voice']=env('DB_HOST').$question->level_id."/".$question->lesson_id."/".$question->axis_id."/".$question->question_id."/".$question->question_voice;
        $data['answers']=Answer::where("question_id","=",$questionId)
        ->select(
            'answer_id',
            'question_id',
            'question_value as value',
            'question_isTrue as isTrue',
            'question_right as right',
            'question_left as left', 
            'question_count as count ',
            DB::raw('false as selected')
        )
        ->get();


        return $data;
    }
    public function saveAnswer(int $questionId,string $answer,bool $isTrue,int $userId)
    {
        $question=Question::where("question_id","=",$questionId)->first();
$date=now();
        $userAnswer = userAnswers::create([
            'question_id'  => $question->question_id,
            'lesson_id'  => $question->lesson_id,
            'level_id'  => $question->level_id, 
            'axis_id'  => $question->axis_id, 
            'userAnswer_question'  => $question->question_name,
            'userAnswer_answer'  => $answer,
            'userAnswer_isTrue'  => $isTrue,
            'userAnswer_date'  => $date,
            'user_id'=>$userId
        ]);
        return $userAnswer;
    }


    public function selectAnswersByuser(Request $request)
    {
        $answrs=userAnswers::where("user_id",'=',$request->userId)
        ->orderBy("userAnswer_date","DESC")
        ->get();
        $questionId= User::where("id",'=',$request->userId)->first()->user_lastQuestionId;
        
        
        //auth()->user()->user_lastQuestionId;


        $question=Question::where("question_id","=",$questionId)->first();
        $data=[
            "answers"=> $answrs,
            "lastQuestion"=> $question,
        ];
        return ApiController::successResponse($data, 200);
    }
}
