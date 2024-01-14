<?php
namespace App\Http\Controllers;

use App\Actions\Position\DeletePositionAction;
use App\Exceptions\EntityDeleteException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Position;
use Illuminate\Support\Facades\Auth;

class PositionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $positions = Position::orderBy('id', 'desc')->get();
        return view('positions', compact('positions'));
    }

    public function create()
    {
        $position = new Position();
        return view('positions.create', compact('position'));
    }

    public function store(Request $request)
    {
        // Валидация данных
        $request->validate([
            'name' => 'required|string|min:2|max:256',
        ]);

        // Создание новой должности
        Position::create([
            'name' => $request->input('name'),
        ]);

        return redirect()->route('positions.index')->with('success', 'Position created successfully');
    }

    public function edit(Position $position)
    {
        return view('positions.edit', compact('position'));
    }

    public function update(Request $request, Position $position)
    {
        $request->validate([
            'name' => 'required|string|min:2|max:256',
        ]);

        $position->update(['name' => $request->input('name'),
            'admin_updated_id' => Auth::id(),
            ]);

        return redirect()->route('positions.index')->with('success', 'Название должности успешно обновлено');
    }

    public function destroy(Position $position, DeletePositionAction $deletePositionAction): RedirectResponse
    {
        // TODO: rewrite this code with use validation request
        // TODO: resolve this code when another functionality will be implemented
        $response = redirect()->route('positions.index');

        try {
            $deletePositionAction($position);
        } catch (EntityDeleteException $e) {
            return $response->with('error', $e->getMessage());
        }

        return $response->with('success', 'Должность успешно удалена');
    }
}
