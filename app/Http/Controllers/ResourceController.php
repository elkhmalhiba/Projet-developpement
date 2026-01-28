<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\ResourceCategory;
use Illuminate\Http\Request;

class ResourceController extends Controller{
    public function index()
    {
        $categories = ResourceCategory::with('resources')->get();
        $resources = Resource::all();

        return view('resources.index', compact('categories','resources'));
    }
    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $categories = ResourceCategory::all();
        return view('resources.create', compact('categories'));
    }

    /**
     * Stocker une nouvelle ressource
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'resource_category_id' => 'required|exists:resource_categories,id',
            'cpu' => 'nullable|string|max:100',
            'ram' => 'nullable|string|max:100',
            'bandwidth' => 'nullable|string|max:100',
            'capacity' => 'nullable|string|max:100',
            'os' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:100',
            'status' => 'required|in:available,occupied,maintenance',
            'notes' => 'nullable|string',
        ]);

        // Création de la ressource
        $resource = Resource::create([
            'name' => $request->name,
            'resource_category_id' => $request->resource_category_id,
            'cpu' => $request->cpu,
            'ram' => $request->ram,
            'bandwidth' => $request->bandwidth,
            'capacity' => $request->capacity,
            'os' => $request->os,
            'location' => $request->location,
            'status' => $request->status,
        ]);

        // Redirection avec message de succès
        return redirect()->route('admin.dashboard')
            ->with('success', 'Ressource créée avec succès !');
    }

    /**
     * Afficher une ressource spécifique
     */
    public function show(Resource $resource)
    {
        return view('admin.resources.show', compact('resource'));
    }

    /**Afficher le formulaire d'édition*/
    public function edit(Resource $resource)
    {
        $categories = ResourceCategory::all();
        return view('resources.edit', compact('resource', 'categories'));
    }

    /**
     * Mettre à jour une ressource
     */
    public function update(Request $request, Resource $resource)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'resource_category_id' => 'required|exists:resource_categories,id',
            'cpu' => 'nullable|string|max:100',
            'ram' => 'nullable|string|max:100',
            'bandwidth' => 'nullable|string|max:100',
            'capacity' => 'nullable|string|max:100',
            'os' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:100',
            'status' => 'required|in:available,occupied,maintenance',
        ]);

        $resource->update($request->all());

        return redirect()->route('admin.dashboard')
            ->with('success', 'Ressource mise à jour avec succès !');
    }

    /**
     * Supprimer une ressource
     */
    public function destroy(Resource $resource)
    {
        // Vérifier s'il y a des réservations ou incidents associés
        if ($resource->reservations()->exists()) {
            return back()->with('error', 'Impossible de supprimer cette ressource car elle est associée à des réservations ou incidents.');
        }
        $resource->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Ressource supprimée avec succès !');
    }
}