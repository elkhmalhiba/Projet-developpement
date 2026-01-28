<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class IncidentController extends Controller
{

    public function index()
    {
        $incidents = Incident::with(['user', 'resource'])->latest()->get();
        return view('incidents.index', compact('incidents'));
    }

    public function destroy(Incident $incident)
    {
        Notification::create([
            'user_id' => $incident->user_id,
            'title' => 'Incident résolu',
            'message' => "Votre signalement concernant la ressource '{$incident->resource->name}'.",
            'is_read' => false,
        ]);
        $incident->delete();
       return redirect()->route('tech.dashboard')->with('success', 'Le signalement a été supprimé par la modération.');
    }
    // app/Http/Controllers/IncidentController.php

    public function create($resource_id)
    {
        $resource = Resource::findOrFail($resource_id);
        return view('incidents.create', compact('resource'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'description' => 'required|string|min:10',
        ]);

        Incident::create([
            'resource_id' => $request->resource_id,
            'user_id' => Auth::id(),
            'description' => $request->description,
            'status' => 'ouvert',
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Incident signalé au Responsable Technique.');
    }


   public function show(Incident $incident)
{
    // Charger éventuellement les relations, par exemple l'utilisateur qui a signalé
    $incident->load('user');

    return view('incidents.show', compact('incident'));
}


    public function edit(Incident $incident)
    {
        return response()->json($incident);
    }

    public function update(Request $request, Incident $incident)
    {
        $request->validate([
            'description' => 'sometimes|required|string',
        ]);

        $incident->update($request->all());
        return response()->json($incident);
    }


}
