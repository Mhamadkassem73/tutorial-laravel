<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\User;
use App\Models\userAnswers;
use Faker\Core\Number;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\String_;

class QuestionController extends ApiController
{


    public function fetchFirstQuestion(Request $request)
    {
        // $questionId= auth()->user_lastQuestionId;
        $questionId = auth()->user()->user_lastQuestionId;

        $question = $this->fetchNextQuestionByIdFynction($questionId);
        return ApiController::successResponse($question, 200);
    }
    public function fetchNextQuestionById(Request $request)
    {
        $updateUser = User::where("id", "=", auth()->user()->id)
            ->update([
                'user_lastQuestionId' => $request->questionId
            ]);
        if (isset($request->isTrue)) {
            $answer='';
            if(isset($request->answer))
            {
                $answer=$request->answer;
            }
       
            $this->saveAnswer($request->questionId, $request->answer, $request->isTrue, auth()->user()->id);
        }



        $questionIdLast = Question::where("question_id", '=', $request->questionId)
            ->orderBy("question_id", "asc")
            ->first()
            ->question_id;



        $questionId = Question::where("question_order", '>', $questionIdLast)
            ->orderBy("question_id", "asc")
            ->first()
            ->question_id;
        $question = $this->fetchNextQuestionByIdFynction($questionId);
        return ApiController::successResponse($question, 200);
    }
    public function fetchNextQuestionByIdFynction($questionId)
    {



        $question = Question::where("question_id", "=", $questionId)->first();
        $data['question'] = $question;
        $data['image'] = env('URL') . $question->level_id . "/" . $question->lesson_id . "/" . $question->axis_id . "/" . $question->question_id . "/" . $question->question_image;

        if ($question->question_voice!=null && $question->question_voice!=''& $question->question_voice!=' ')
            $data['voice'] = env('URL') . $question->level_id . "/" . $question->lesson_id . "/" . $question->axis_id . "/" . $question->question_id . "/" . $question->question_voice;
        else
            $data['voice'] = null;
        $data['answers'] = Answer::leftJoin("questions", "questions.question_id", "=", "answers.question_id")
            ->where("questions.question_id", "=", $questionId)
            ->select(
                'answers.answer_id',
                'answers.question_id',
                'answers.question_value as value',
                'answers.question_isTrue as isTrue',
                'answers.question_right as right',
                'answers.question_left as left',
                'answers.question_count as count',
                DB::raw("CONCAT('" . env('URL') . "', 
            questions.level_id, '/', 
            questions.lesson_id, '/', 
            questions.axis_id, '/', 
                            answers.question_id, '/', 
                            answers.question_image) AS question_image"),
                DB::raw("CONCAT('" . env('URL') . "', 
            questions.level_id, '/', 
            questions.lesson_id, '/', 
            questions.axis_id, '/', 
                            answers.question_id, '/', 
                            answers.question_voice) AS question_voice"),
                DB::raw('false as selected')
            )
            ->orderBy("answers.answer_id")
            ->get();


        return $data;
    }
    public function saveAnswer(int $questionId,  $answer, bool $isTrue, int $userId)
    {
        $question = Question::where("question_id", "=", $questionId)->first();
        $date = now();
        $userAnswer = userAnswers::create([
            'question_id'  => $question->question_id,
            'lesson_id'  => $question->lesson_id,
            'level_id'  => $question->level_id,
            'axis_id'  => $question->axis_id,
            'userAnswer_question'  => $question->question_name,
            'userAnswer_answer'  => $answer,
            'userAnswer_isTrue'  => $isTrue,
            'userAnswer_date'  => $date,
            'user_id' => $userId
        ]);
        return $userAnswer;
    }


    public function selectAnswersByuser(Request $request)
    {
        $answrs = userAnswers::where([
            ["user_id", '=', $request->userId],
            ["lesson_id", '=', $request->lesson],
        ])
            ->orderBy("userAnswer_date", "DESC")
            ->get();
        $questionId = User::where("id", '=', $request->userId)->first()->user_lastQuestionId;


        //auth()->user()->user_lastQuestionId;


        $question = Question::where("question_id", "=", $questionId)->first();
        $data = [
            "answers" => $answrs,
            "lastQuestion" => $question,
        ];
        return ApiController::successResponse($data, 200);
    }


    public function fetchLessons(Request $request)
    {
        // $questionId= auth()->user_lastQuestionId;
        $questionId = auth()->user()->user_lastQuestionId;
        $question = Question::where("question_id", "=", $questionId)->first();

        $lessons = Lesson::where([
            ['level_id', "<=", $question->level_id],
            ['axis_id', "<=", $question->axis_id],
        ])
            ->orderBy("axis_id")
            ->get();
        return ApiController::successResponse($lessons, 200);
    }
}
