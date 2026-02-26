<?php

namespace App\Http\Controllers;

use App\Models\Dvd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DvdController extends Controller
{
    public function index(Request $request)
    {
        // On récupère le mot-clé tapé par l'utilisateur
        $search = $request->input('search');

        // On crée la requête de base
        $query = Dvd::query();

        // Si le champ search n'est pas vide
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                    ->orWhere('realisateur', 'like', "%{$search}%")
                    ->orWhere('genre', 'like', "%{$search}%");
            });
        }

        // On récupère les résultats (triés par titre pour faire propre)
        $dvds = $query->orderBy('titre', 'asc')->get();

        return view('dvds', compact('dvds'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre' => 'required',
            'realisateur' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Validation
            'note' => 'nullable|integer',
            'genre' => 'nullable|string',
            'annee' => 'nullable|integer',
            'duree' => 'nullable|integer',
        ]);

        // LOGIQUE DE STOCKAGE DE L'IMAGE
        if ($request->hasFile('image')) {
            // Enregistre dans storage/app/public/dvds
            $path = $request->file('image')->store('dvds', 'public');
            $data['image_url'] = $path;
        }

        Dvd::create($data);

        return redirect()->route('dvds.index')->with('success', 'Film ajouté !');
    }

    public function update(Request $request, $id)
    {
        $dvd = Dvd::findOrFail($id);

        $data = $request->validate([
            'titre' => 'required',
            'realisateur' => 'required',
            'image' => 'nullable|image|max:2048',
            'note' => 'nullable|integer',
            'genre' => 'nullable',
            'annee' => 'nullable',
            'duree' => 'nullable',
        ]);

        if ($request->hasFile('image')) {
            // On supprime l'ancienne image si elle existe pour ne pas saturer le disque
            if ($dvd->image_url) {
                Storage::disk('public')->delete($dvd->image_url);
            }
            $path = $request->file('image')->store('dvds', 'public');
            $data['image_url'] = $path;
        }

        $dvd->update($data);

        return redirect()->route('dvds.index')->with('success', 'Film mis à jour !');
    }

    public function destroy($id)
    {
        $dvd = Dvd::findOrFail($id);

        if ($dvd->image_url) {
            Storage::disk('public')->delete($dvd->image_url);
        }

        $dvd->delete();
        return redirect()->back();
    }
}
