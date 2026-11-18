<?php

namespace App\Http\Controllers;

use App\Models\Technician;
use Illuminate\Http\Request;

class TechnicianController extends Controller
{
    public function index(Request $request)
    {
        $technicians = Technician::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('technician_name', 'ilike', "%{$search}%")
                        ->orWhere('phone_number', 'ilike', "%{$search}%")
                        ->orWhere('telegram_username', 'ilike', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.technicians.index', compact('technicians'));
    }

    public function create()
    {
        return view('pages.technicians.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'technician_name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:30',
            'telegram_username' => 'nullable|string|max:255',
            'role_telegram' => 'nullable|string|max:100',
            'active_technician' => 'required|boolean',
        ]);

        Technician::create($validated);

        return redirect()
            ->route('technicians.index')
            ->with('success', 'Technician created successfully.');
    }

    public function edit(Technician $technician)
    {
        return view('pages.technicians.edit', compact('technician'));
    }

    public function update(Request $request, Technician $technician)
    {
        $validated = $request->validate([
            'technician_name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:30',
            'telegram_username' => 'nullable|string|max:255',
            'role_telegram' => 'nullable|string|max:100',
            'active_technician' => 'required|boolean',
        ]);

        $technician->update($validated);

        return redirect()
            ->route('technicians.index')
            ->with('success', 'Technician updated successfully.');
    }

    public function destroy(Technician $technician)
    {
        $technician->delete();

        return redirect()
            ->route('technicians.index')
            ->with('success', 'Technician deleted successfully.');
    }

    public function show(Technician $technician)
    {
        return view('pages.technicians.show', compact('technician'));
    }
}
