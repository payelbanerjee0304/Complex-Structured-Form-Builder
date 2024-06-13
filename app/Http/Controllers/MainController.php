<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use MongoDB\BSON\ObjectId;

class MainController extends Controller
{
    Public function insert():View
    {
        return view('form');
    }
    public function create(Request $request)
    {
        return view('create');  
    }
    public function store(Request $request)
    {
        $taskpoints = $request->input('taskpoints');
        $questions = $request->input('questions');
        $totalSubtasks = count($questions);
        $subtaskPointsProvided = array_filter(array_column($questions, 'subtaskpoint'));

        // Distribute task points equally among subtasks if no subtask points are provided
        if (empty($subtaskPointsProvided)) {
            $equalSubtaskPoint = $taskpoints / $totalSubtasks;
            foreach ($questions as &$question) {
                $question['subtaskpoint'] = $equalSubtaskPoint;
            }
        }
        foreach ($questions as &$questionGroup) {
            foreach ($questionGroup as &$question) {
                
                // Rename keys if they exist
        if (isset($question['question'])) {
            $title = $question['question'];
            unset($question['question']);
        }
        if (isset($question['type'])) {
            $selecttype = $question['type'];
            unset($question['type']);
        }
        // Convert isMandetory value
        if (isset($question['isMandetory'])) {
            $isMandetory = $question['isMandetory'] === 'on';
        }
        
                // Transform options into the desired structure
                if (isset($question['options']) && is_array($question['options'])) {
                    $transformedOptions = [];
                    foreach ($question['options'] as $option) {
                        $parttasks = [];
                        if (isset($option['parttask']) && is_array($option['parttask'])) {
                            foreach ($option['parttask'] as $parttask) {
                                $parttasks[] = [
                                    'question' => $parttask['question'],
                                    'type' => $parttask['type'],
                                    'options' => isset($parttask['options']) ? $parttask['options'] : []
                                ];
                            }
                        }
    
                        $transformedOptions[] = [
                            'text' => $option['text'],
                            'parttask' => $parttasks
                        ];
                    }
                    $question = [
                        '_id' => new ObjectId(),
                        'title' => $title,
                        'selecttype' => $selecttype,
                        'options' => $transformedOptions,
                        'isMandetory' => $isMandetory
                    ];
                }
            }
        }

        // Prepare the data for insertion
        $data = [
            'taskpoints' => $taskpoints,
            'subtask' => $questions
        ];

        // Insert data into the MongoDB collection
        DB::table('testtask')->insert($data);

        return redirect()->back()->with('success', 'Data has been successfully saved.');
    }
    public function store3(Request $request)
    {
        $taskpoints = $request->input('taskpoints');
        $questions = $request->input('questions');
        $totalSubtasks = count($questions);
        $subtaskPointsProvided = array_filter(array_column($questions, 'subtaskpoint'));

        // Distribute task points equally among subtasks if no subtask points are provided
        if (empty($subtaskPointsProvided)) {
            $equalSubtaskPoint = $taskpoints / $totalSubtasks;
            foreach ($questions as &$question) {
                $question['subtaskpoint'] = $equalSubtaskPoint;
            }
        }
        foreach ($questions as &$questionGroup) {
            foreach ($questionGroup as &$question) {
                
                // Rename keys if they exist
        if (isset($question['question'])) {
            $title = $question['question'];
            unset($question['question']);
        }
        if (isset($question['type'])) {
            $selecttype = $question['type'];
            unset($question['type']);
        }
        // Convert isMandetory value
        if (isset($question['isMandetory'])) {
            $isMandetory = $question['isMandetory'] === 'on';
        }
        
                // Transform options into the desired structure
                if (isset($question['options']) && is_array($question['options'])) {
                    $transformedOptions = [];
                    foreach ($question['options'] as $option) {
                        $transformedOptions[] = [
                            'text' => $option,
                            'parttask' => [
                                'typeId' => '',
                                'values' => [] // Empty array for values
                            ]
                        ];
                    }
                    $question = [
                        '_id' => new ObjectId(),
                        'title' => $title,
                        'selecttype' => $selecttype,
                        'options' => $transformedOptions,
                        'isMandetory' => $isMandetory
                    ];
                }
            }
        }

        // Prepare the data for insertion
        $data = [
            'taskpoints' => $taskpoints,
            'subtask' => $questions
        ];

        // Insert data into the MongoDB collection
        DB::table('testtask')->insert($data);

        return redirect()->back()->with('success', 'Data has been successfully saved.');
    }
    public function store2(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'questions' => 'required|array', // Questions array must be present and an array
            'questions.*.question' => 'required|string', // Each question must have a non-empty string as its question
            'questions.*.type' => 'required|string', // Each question must have a non-empty string as its type
            'questions.*.options' => 'nullable|array', // Question options, if present, must be an array
            // 'questions.*.toggleTwo' => 'nullable|boolean', // Commented out, as it's not currently used
        ]);

        $questions = []; // Initialize an empty array to store formatted question data
        $totalSubtaskPoints = 0;
        
        foreach ($request->questions as $index => $questionData) {
            // Extract values from each question array
            $title = $questionData['question']; // Extract question title
            $selectType = $questionData['type']; // Extract question type
            $options = $questionData['options'] ?? [null]; // Extract question options or set to [null] if not provided
            $toggleTwo = !empty($questionData['toggleTwo']) ? true : false; // Extract toggle value or set to false if not provided

            // Push formatted question data into the questions array
            $questions[] = [
                'title' => $title,
                'selecttype' => $selectType,
                'options' => $options,
                'toggletwo' => $toggleTwo,
            ];
        }

        // Prepare data for insertion into the database
        $questionData = [
            'name' => json_encode($questions), // Convert the questions array to JSON and store it in the 'name' column
            'email' => 'email',
            'password' => 'password',
            'phone' => 1,
            'gender' => 1,
            'language' => 'language',
            'image' => 'hi',
            'user' => 'user',
            'auth' => 1
        ];

        // Insert the formatted question data into the database table 'pracone'
        DB::table('pracone')->insert($questionData);

        // Redirect back to the create page with a success message
        return redirect('/create')->with('success', 'Questions created successfully!');
    }

    
    public function store1(Request $request)
    {
        echo "<pre>";
        print_r($request->all());die;
        // Validate the incoming request data
        $request->validate([
            'questions' => 'required|array', // Questions array must be present and an array
            'questions.*.question' => 'required|string', // Each question must have a non-empty string as its question
            'questions.*.type' => 'required|string', // Each question must have a non-empty string as its type
            'questions.*.options' => 'nullable|array', // Question options, if present, must be an array
            // 'questions.*.toggleTwo' => 'nullable|boolean', // Commented out, as it's not currently used
        ]);
        $questions = []; // Initialize an empty array to store formatted question data
        $totalSubtaskPoints = 0;
        foreach ($request->questions as $index => $questionData) {
            // Extract values from each question array
            $title = $questionData['question']; // Extract question title
            $selectType = $questionData['type']; // Extract question type
            // Extract question options or set to an empty array if not provided
        $options = isset($questionData['options']) && is_array($questionData['options']) ? array_map(function($option) {
            return [
                'text' => $option,
                'parttask' => [
                    'typeId' => '',
                    'values' => []
                ]
            ];
        }, $questionData['options']) : [];
            $toggleTwo = !empty($questionData['toggleTwo']); // Extract toggle value or set to false if not provided
            $subtaskPoint = $questionData['subtaskpoint'];
            $totalSubtaskPoints += $subtaskPoint;
            // Create an array to represent the current question
            $questionArray = [
                'title' => $title,
                'subtaskPoint'=>$subtaskPoint,
                'selecttype' => $selectType,
                'options' => $options,
                'isMandetory' => $toggleTwo,
            ];
            // Add the current question array to the questions array
            $questions[] = $questionArray;
        }
          // Calculate the total task points
        $taskPoints = $request->taskpoints;
        // Calculate the total number of questions
        $totalQuestions = count($questions);
        // Check if any subtask points are specified
        $specifiedSubtaskPoints = $totalSubtaskPoints > 0;
        // Check if no subtask points are provided for any question
        $noSubtaskPoints = $totalSubtaskPoints === 0;
        // If no subtask points are provided for any question, divide the total task points equally among all questions
        if ($noSubtaskPoints) {
            // Ensure there are questions to divide points among
            if ($totalQuestions > 0) {
                $pointsPerQuestion = $taskPoints / $totalQuestions;
                // Update subtask points for each question
                foreach ($questions as &$question) {
                    $question['subtaskPoint'] = $pointsPerQuestion;
                }
                unset($question); // Unset the reference to the last element
                // Update the total subtask points
                $totalSubtaskPoints = $taskPoints;
            } else {
                // Redirect with an error if there are no questions
                return redirect('/createtest')->with('success', 'No questions found.');
            }
        }
        // Prepare data for insertion into the database
        $taskData = [
            'subTasks' => $questions, // Store the array of questions in the 'subTasks' field
        ];
        $taskpoints=$request->taskpoints;
        $data=[
            'taskPoints'=>$request->taskpoints,
            'totalSubtaskPoints' => $totalSubtaskPoints,
        ];
        // Insert the formatted question data into the database table 'testtask'
        $combinedData = array_merge($data, $taskData);
        if($taskpoints==$totalSubtaskPoints)
        {
            DB::table('testtask')->insert([$combinedData]);
            // Redirect back to the create page with a success message
            return redirect('/createtest')->with('success', 'Questions created successfully!');
        }
        else
        {
            return redirect('/createtest')->with('success', 'both are not equal');
        }
    }
    

    

    public function alldata():View
    {
        $user='client';
        $data=DB::table('pracone')->where('user','=',$user)->get();
        return view('displayall')->with(['allinfo'=>$data]);

    }
    public function displayclient($dp)
    {
        $userid=$dp;
        $user=DB::table('pracone')->where('userid','=',$userid)->get();
        return view('displayclient')->with(['clientinfo'=>$user[0]]);
    }
    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/login')->with('message','Logout Successfully');
    }
    public function delete($del)
    {
        $userid=$del;
        DB::table('pracone')->where('user_id','=',$userid)->delete();
        return redirect('/alldata')->with('message','User deleted');
    }
    public function edit($ep)
    {
        $userid=$ep;
        $data=DB::table('pracone')->where('user_id','=',$userid)->get();
        return view('edit')->with(['userinfo'=>$data[0]]);
    }
    public function editaction(Request $request)
    {
        $userid=$request->input('uid');
        $name=$request->input('name');
        $email=$request->input('email');
        $phone=$request->input('phone');
        $gender=$request->input('gender');
        $language=implode(', ', $request->input('language'));
        if($request->file('file'))
        $file=$request->file('file');
        $fileName=time()."_".$file->getClientOriginalName();
        $uploadlocation='upload./';
        $file->move($uploadlocation, $fileName);
        $data=[
            'name'=>$name,
            'email'=>$email,
            'phone'=>$phone,
            'gender'=>$gender,
            'language'=>$language,
            'image'=>$uploadlocation.'/'.$fileName
        ];
        DB::table('pracone')->where('user_id','=',$userid)->update($data);
        return redirect('/alldata')->with('message','Updated Data Successfully');
    }
    public function login():View
    {
        return view('login');
    }
    public function loginaction(Request $request)
    {
        $email=$request->input('email');
        $password=md5($request->input('password'));
        $user= DB::table('pracone')->where('email','=',$email)->get();
        $auth=$user[0]->auth;
        if($auth!=0)
        {
            return redirect('/login')->with('message','You are blocked by admin');
        }
        else
        {
        if(empty($user[0]))
        {
            return redirect('/login')->with('message','Data not found');
        }
        else
        {
            $password_db=$user[0]->password;
            $user1=$user[0]->user;
            if($password_db==$password)
            {
                if($user1=='client')
                {
                    
                    return view('displayclient')->with(['clientinfo'=>$user]);
                }
                else
                {
                    return redirect('/alldata');
                }
            }
            else
            {
                return redirect('/login')->with('message','password doesnot match');
            }
        }
    }
    }
    
}