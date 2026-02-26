<?php

namespace App\Http\Controllers;

use App\Models\Livre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LivreController extends Controller
{
    // Affichage avec recherche
    public function livres(Request $request)
    {
        $search = $request->input('search');

        $livres = Livre::when($search, function ($query, $search) {
            return $query->where('titre', 'like', "%{$search}%")
                ->orWhere('auteur', 'like', "%{$search}%")
                ->orWhere('genre', 'like', "%{$search}%");
        })->orderBy('created_at', 'desc')->get();

        return view('livres', compact('livres'));
    }

    // Enregistrement d'un nouveau livre
    public function store(Request $request)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'auteur' => 'required|string|max:255',
            'genre' => 'nullable|string',
            'annee' => 'nullable|integer',
            'maisonEdition' => 'nullable|string',
            'nbPage' => 'nullable|integer',
            'format' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'avis' => 'nullable|string',
            'note' => 'nullable|integer|min:0|max:5',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('livres', 'public');
        }

        Livre::create($data);

        return redirect()->route('livres')->with('success', 'Livre ajouté avec succès !');
    }

    // Mise à jour d'un livre
    public function updateLivre(Request $request, $id)
    {
        $livre = Livre::findOrFail($id);

        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'auteur' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'note' => 'nullable|integer|min:0|max:5',
            'genre' => 'nullable',
            'annee' => 'nullable',
            'maisonEdition' => 'nullable',
            'nbPage' => 'nullable',
            'format' => 'nullable',
            'avis' => 'nullable',
        ]);

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($livre->image) {
                Storage::disk('public')->delete($livre->image);
            }
            $data['image'] = $request->file('image')->store('livres', 'public');
        }

        $livre->update($data);

        return redirect()->route('livres')->with('success', 'Livre modifié !');
    }

    // Suppression
    public function destroy($id)
    {
        $livre = Livre::findOrFail($id);

        if ($livre->image) {
            Storage::disk('public')->delete($livre->image);
        }

        $livre->delete();

        return redirect()->route('livres')->with('success', 'Livre supprimé');
    }
}
