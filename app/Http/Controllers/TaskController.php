<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * The TaskController class extends Laravel's base Controller class.
 *  It is responsible for managing tasks, including viewing, creating, modifying, and handling task registrations.
 */
class TaskController extends Controller
{
    /**
     * The getTasks method retrieves tasks for the authenticated user and renders a view to display them.
     */
    public function getTasks()
    {
        $user = Auth::user();
        $tasks = Task::getAllTasksForUser($user->id);
        return view('tasks.show_task', ['tasks' => $tasks]);
    }

    /**
     * The registerTask method handles task registration, updating participant counts, and returns JSON responses.
     */
    public static function registerTask(Request $request)
    {
        try {
            if ($request->id_task > 0) {
                $user = Auth::user();
                Task::register($request->id_task, $user->id);
                $people_count = Task::incrementPeopleCount($request->id_task);
                $MinimumAtteined = Task::isMinimumAtteined($request->id_task);
                return response()->json(["message" => "Inscription enregistrée",
                    "people_count" => $people_count,
                    "minimum_atteined" => $MinimumAtteined]);
            } else {
                return json_encode(["message" => "Erreur id incorrect"]);
            }
        } catch (\Throwable $th) {
            return response()->json(["message" => "Erreur dans l'inscription à la tâche"]);
        }
    }

    /**
     * The unregisterTask method handles task unregistration, updating participant counts, and returns JSON responses.
     */
    public static function unregisterTask(Request $request)
    {
        try {
            if ($request->id_task > 0) {
                $user = Auth::user();
                Task::unregister($request->id_task, $user->id);
                $people_count = Task::decrementPeopleCount($request->id_task);
                $MinimumAtteined = Task::isMinimumAtteined($request->id_task);
                return response()->json(["message" => "Désinscription enregistrée",
                    "people_count" => $people_count,
                    "minimum_atteined" => $MinimumAtteined]);
            } else {
                return json_encode(["message" => "Erreur id incorrect"]);
            }
        } catch (\Throwable $th) {
            return json_encode(["message" => "Erreur dans la désinscription à la tâche"]);
        }
    }

    /**
     * The sortTask method sorts tasks based on various criteria and returns a JSON response.
     */
    public static function sortTask(Request $request)
    {
        $sortBy = $request->input('sortBy');
        $user = Auth::user();
        $tasks = Task::getAllTasksForUser($user->id);

        switch ($sortBy) {
            case 'task_asc':
                $tasks->orderBy('name', 'asc');
                break;
            case 'task_desc':
                $tasks->orderBy('name', 'desc');
                break;
            case 'participants_asc':
                $tasks->orderBy('people_count', 'asc');
                break;
            case 'participants_desc':
                $tasks->orderBy('people_count', 'desc');
                break;
            case 'beginDate_asc':
                $tasks->orderBy('start_datetime', 'asc');
                break;
            case 'beginDate_desc':
                $tasks->orderBy('start_datetime', 'desc');
                break;
            case 'endDate_asc':
                $tasks->orderBy('end_datetime', 'asc');
                break;
            case 'endDate_desc':
                $tasks->orderBy('end_datetime', 'desc');
                break;
            case 'address_asc':
                $tasks->orderBy('address', 'asc');
                break;
            case 'address_desc':
                $tasks->orderBy('address', 'desc');
                break;
            case 'inscription_asc':
                $tasks->orderBy('StatusInscription', 'asc');
                break;
            case 'inscription_desc':
                $tasks->orderBy('StatusInscription', 'desc');
                break;
            default:
                $tasks->orderBy('tasks.id');
                break;
        }

        $sortedTasks = $tasks->get();
        return response()->json($sortedTasks);
    }

    /**
     * The createTask method renders the view for creating a new task.
     */
    public function createTask()
    {
        $old["id"] = 0;
        return view('tasks.create', ['old' => $old]);
    }

    /**
     * The storeTask method validates and stores a new task, rendering a view with validation confirmation.
     */
    public function storeTask(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'max:30'],
            'description' => ['required', 'max:255'],
            'start_datetime' => ['required', 'after:now'],
            'end_datetime' => ['required', 'after:start_datetime'],
            'address' => ['required', 'max:100'],
            'people_min' => ['required', 'numeric', 'gte:2'],
            'people_max' => ['required', 'numeric', 'gte:people_min'],

        ]);

        $corrected_start_datetime = $this->correctDatetimeFormat($request->start_datetime);
        $corrected_end_datetime = $this->correctDatetimeFormat($request->end_datetime);

        Task::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'start_datetime' => $corrected_start_datetime,
            'end_datetime' => $corrected_end_datetime,
            'address' => $validatedData['address'],
            'people_min' => $validatedData['people_min'],
            'people_max' => $validatedData['people_max'],

        ]);

        $old['id'] = 0;

        return view('tasks.create-validation', ['task_name' => $request->name, "old" => $old]);
    }

    /**
     *  It serves the purpose of formatting a datetime string by replacing the 'T' separator with a space
     */
    private function correctDatetimeFormat($datetime)
    {
        return str_replace('T', ' ', $datetime);
    }

    /**
     * The modifyFormTask method renders the view for modifying an existing task.
     */
    public function modifyFormTask($id)
    {
        $task = Task::find($id);
        return view('tasks.create', ['old' => $task]);
    }

    /**
     * The modifyConfirmTask method validates and modifies an existing task, redirecting with a success message.
     */
    public function modifyConfirmTask(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'max:30'],
            'description' => ['required', 'max:255'],
            'start_datetime' => ['required', 'after:now'],
            'end_datetime' => ['required', 'after:start_datetime'],
            'address' => ['required', 'max:100'],
            'people_min' => ['required', 'numeric'],
            'people_max' => ['required', 'numeric', 'gte:people_min'],
        ]);
        $corrected_start_datetime = $this->correctDatetimeFormat($request->start_datetime);
        $corrected_end_datetime = $this->correctDatetimeFormat($request->end_datetime);

        $task = Task::find($request->id);
        $task->name = $validatedData['name'];
        $task->description = $validatedData['description'];
        $task->start_datetime = $corrected_start_datetime;
        $task->end_datetime = $corrected_end_datetime;
        $task->address = $validatedData['address'];
        $task->people_min = $validatedData['people_min'];
        $task->people_max = $validatedData['people_max'];
        $task->save();
        $message = 'The task ' . $task->name . ' has successfully been modify';

        return redirect('/tasks')->with('success', $message);
    }

    /**
     * The increment method increments the participant count for a task.
     */
    public function increment(Request $request)
    {
        Task::incrementPeopleCount($request->id);

        return redirect()->back();

    }

    /**
     * Get the participation information of a task by its ID.
     *
     * @param int $idTask The ID of the task
     * @return void Outputs JSON response with task details and participation information
     */
    function getTasksById($idTask)
    {
        //$rep = Task::getDetailParticipation($idTask);

        // NOTE: this is an example of how EASY eloquent is
        // I LOVE ELOQUENT !!!!!!
        // also fixed a bug because... we don't use Participation anymore.
        $rep = Task::find($idTask)->users;
        return response()->json($rep);
    }
}

