<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\ProgramType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProgramTypeController extends Controller
{
    public function index()
    {
        $items = ProgramType::query()
            ->withCount('programs')
            ->orderByRaw('legacy_id is null')
            ->orderBy('legacy_id')
            ->orderBy('name')
            ->get();

        return view('admin.program-types.index', compact('items'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:program_types,name'],
        ]);

        ProgramType::create([
            'name' => trim($data['name']),
            'is_active' => true,
        ]);

        return back()->with('ok', 'Тип специальности создан');
    }

    public function update(Request $request, ProgramType $programType)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('program_types', 'name')->ignore($programType->id)],
        ]);

        $programType->update([
            'name' => trim($data['name']),
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('ok', 'Тип специальности обновлён');
    }

    public function destroy(ProgramType $programType)
    {
        $count = Program::where('program_type_id', $programType->id)->count();
        if ($count > 0) {
            return back()->withErrors([
                'type' => "Нельзя удалить тип: к нему привязано программ — {$count}. Сначала переназначьте их другому типу.",
            ]);
        }

        $programType->delete();
        return back()->with('ok', 'Тип специальности удалён');
    }
}
