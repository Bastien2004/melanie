<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">    <title>Mes Livres</title>
    <link rel="stylesheet" href="{{ asset('css/livre.css') }}">
</head>
<body>
<!-- En-tête de la page -->
<div class="header">
    <h1>📚 Ma Bibliothèque</h1>
    <div class="nav-buttons">
        <a href="{{ route('accueil') }}" class="btn">← Retour</a>
        <a href="{{ route('logout') }}" class="btn">Se déconnecter</a>
    </div>
</div>

<div class="container">
    <!-- Barre de recherche et bouton ajouter -->
    <div class="actions-bar">
        {{-- ✅ Barre de recherche fonctionnelle (comme les DVDs) --}}
        <form action="{{ route('livres') }}" method="GET" class="search-box">
            <input type="text" name="search" placeholder="🔍 Rechercher un livre..." value="{{ request('search') }}">
            @if(request('search'))
                <a href="{{ route('livres') }}" class="btn-clear" title="Effacer la recherche">✕</a>
            @endif
        </form>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAjouter">+ Ajouter un livre</button>
    </div>

    <!-- Affichage des livres -->
    @if($livres->count() == 0)
        <!-- Message si aucun livre -->
        <div class="empty-state">
            <div class="empty-state-icon">📖</div>
            <h2>Aucun livre trouvé...</h2>
            <p>Essayez une autre recherche ou ajoutez un nouveau titre !</p>
            <a href="#" class="btn btn-primary" data-bs-toggle="modal"
               data-bs-target="#modalAjouter">Ajouter mon premier livre</a>
        </div>
    @else
        <!-- Liste des livres -->
        <div class="books-grid">
            @foreach($livres as $livre)
                <div class="book-card" data-bs-toggle="modal" data-bs-target="#modalVisualiser{{ $livre->id }}">
                    {{-- ✅ Affichage de l'image si disponible (comme les DVDs) --}}
                    @if($livre->image)
                        <img src="{{ asset('storage/' . $livre->image) }}" alt="{{ $livre->titre }}" style="width: 100%; height: 160px; object-fit: cover; border-radius: 8px; margin-bottom: 8px;">
                    @else
                        <div class="book-icon">📕</div>
                    @endif
                    <div class="book-title">{{ $livre->titre }}</div>
                    <div class="book-author">{{ $livre->auteur }}</div>
                    <div class="book-info">{{ $livre->genre }} - {{ $livre->annee }}</div>
                    <div class="rating-stars">
                        @for($i = 1; $i <= 5; $i++)
                            {{ $i <= $livre->note ? '★' : '☆' }}
                        @endfor
                    </div>
                    <div class="book-actions">
                        <button type="button" class="btn btn-small"
                                data-bs-toggle="modal"
                                data-bs-target="#modalModifier{{ $livre->id }}"
                                onclick="event.stopPropagation()">
                            Modifier
                        </button>

                        <a href="#" class="btn btn-small"
                           data-bs-toggle="modal"
                           data-bs-target="#suppressionModal{{ $livre->id }}"
                           onclick="event.stopPropagation()">Supprimer</a>
                    </div>
                </div>

                <!-- Modal modification-->
                <div class="modal fade" id="modalModifier{{ $livre->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 20px;">

                            <!-- Header -->
                            <div class="modal-header" style="border-bottom: 2px solid rgba(255,255,255,0.2); padding: 25px;">
                                <h5 class="modal-title" style="color: white; font-size: 24px; font-weight: 600;">
                                    📚 Modifier: {{ $livre->titre }}
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <!-- ✅ Formulaire avec enctype pour upload d'image -->
                            <form method="POST" action="{{ route('livres.update', $livre->id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body" style="padding: 30px; max-height: 70vh; overflow-y: auto;">

                                    <!-- Informations principales -->
                                    <div style="background: rgba(255,255,255,0.15); padding: 20px; border-radius: 15px; margin-bottom: 20px;">
                                        <h6 style="color: white; margin-bottom: 15px; font-weight: 600;">📖 Informations principales</h6>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" style="color: white; font-weight: 500;">Titre *</label>
                                                <input type="text" class="form-control" name="titre" value="{{ $livre->titre }}"
                                                       placeholder="Ex: Harry Potter" required
                                                       style="background: rgba(255,255,255,0.9); border: none; border-radius: 10px; padding: 12px;">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" style="color: white; font-weight: 500;">Auteur *</label>
                                                <input type="text" class="form-control" name="auteur" value="{{ $livre->auteur }}"
                                                       placeholder="Ex: J.K. Rowling" required
                                                       style="background: rgba(255,255,255,0.9); border: none; border-radius: 10px; padding: 12px;">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" style="color: white; font-weight: 500;">Genre</label>
                                                <input type="text" class="form-control" name="genre" value="{{ $livre->genre }}"
                                                       placeholder="Ex: Fantasy"
                                                       style="background: rgba(255,255,255,0.9); border: none; border-radius: 10px; padding: 12px;">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" style="color: white; font-weight: 500;">Année</label>
                                                <input type="number" class="form-control" name="annee" value="{{ $livre->annee }}"
                                                       placeholder="Ex: 1997"
                                                       style="background: rgba(255,255,255,0.9); border: none; border-radius: 10px; padding: 12px;">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Détails du livre -->
                                    <div style="background: rgba(255,255,255,0.15); padding: 20px; border-radius: 15px; margin-bottom: 20px;">
                                        <h6 style="color: white; margin-bottom: 15px; font-weight: 600;">📝 Détails du livre</h6>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" style="color: white; font-weight: 500;">Maison d'édition</label>
                                                <input type="text" class="form-control" name="maisonEdition" value="{{ $livre->maisonEdition }}"
                                                       placeholder="Ex: Gallimard"
                                                       style="background: rgba(255,255,255,0.9); border: none; border-radius: 10px; padding: 12px;">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" style="color: white; font-weight: 500;">Nombre de pages</label>
                                                <input type="number" class="form-control" name="nbPage" value="{{ $livre->nbPage }}"
                                                       placeholder="Ex: 320"
                                                       style="background: rgba(255,255,255,0.9); border: none; border-radius: 10px; padding: 12px;">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" style="color: white; font-weight: 500;">Format</label>
                                            <select class="form-control" name="format"
                                                    style="background: rgba(255,255,255,0.9); border: none; border-radius: 10px; padding: 12px;">
                                                <option value="">-- Choisir un format --</option>
                                                <option value="Broché" {{ $livre->format == 'Broché' ? 'selected' : '' }}>Broché</option>
                                                <option value="Poche" {{ $livre->format == 'Poche' ? 'selected' : '' }}>Poche</option>
                                                <option value="Relié" {{ $livre->format == 'Relié' ? 'selected' : '' }}>Relié</option>
                                            </select>
                                        </div>

                                        {{-- ✅ Import image par fichier (comme les DVDs) --}}
                                        <div class="mb-3">
                                            <label class="form-label" style="color: white; font-weight: 500;">Changer la couverture (Image)</label>
                                            @if($livre->image)
                                                <div class="mb-2">
                                                    <img src="{{ asset('storage/' . $livre->image) }}" style="height: 80px; border-radius: 6px;">
                                                </div>
                                            @endif
                                            <input type="file" name="image" class="form-control" accept="image/*"
                                                   style="background: rgba(255,255,255,0.9); border: none; border-radius: 10px; padding: 12px;">
                                        </div>
                                    </div>

                                    <!-- Avis personnel -->
                                    <div style="background: rgba(255,255,255,0.15); padding: 20px; border-radius: 15px;">
                                        <h6 style="color: white; margin-bottom: 15px; font-weight: 600;">⭐ Mon avis</h6>

                                        <div class="mb-3">
                                            <label class="form-label" style="color: white; font-weight: 500;">Ton avis</label>
                                            <textarea class="form-control" name="avis" rows="4"
                                                      placeholder="Écris ton avis sur ce livre..."
                                                      style="background: rgba(255,255,255,0.9); border: none; border-radius: 10px; padding: 12px;">{{ $livre->avis }}</textarea>
                                        </div>

                                        {{-- ✅ Menu déroulant pour la note (comme les DVDs) --}}
                                        <div class="mb-3">
                                            <label class="form-label" style="color: white; font-weight: 500;">Note sur 5</label>
                                            <select name="note" class="form-control"
                                                    style="background: rgba(255,255,255,0.9); border: none; border-radius: 10px; padding: 12px; width: 150px;">
                                                @for($i = 0; $i <= 5; $i++)
                                                    <option value="{{ $i }}" {{ $livre->note == $i ? 'selected' : '' }}>{{ $i }} Étoile{{ $i > 1 ? 's' : '' }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <!-- Footer -->
                                <div class="modal-footer" style="border-top: 2px solid rgba(255,255,255,0.2); padding: 20px;">
                                    <button type="button" class="btn" data-bs-dismiss="modal"
                                            style="background: rgba(255,255,255,0.2); color: white; border: 2px solid rgba(255,255,255,0.3); padding: 10px 24px; border-radius: 10px; font-weight: 500;">
                                        Annuler
                                    </button>
                                    <button type="submit" class="btn"
                                            style="background: rgba(255,255,255,0.3); color: white; border: 2px solid rgba(255,255,255,0.5); padding: 10px 24px; border-radius: 10px; font-weight: 600;">
                                        💾 Enregistrer
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                <!-- Modal visualisation-->
                <div class="modal fade" id="modalVisualiser{{ $livre->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 20px;">

                            <!-- Header -->
                            <div class="modal-header" style="border-bottom: 2px solid rgba(255,255,255,0.2); padding: 25px;">
                                <h5 class="modal-title" style="color: white; font-size: 24px; font-weight: 600;">
                                    📚 {{ $livre->titre }}
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body" style="padding: 30px; max-height: 70vh; overflow-y: auto;">

                                {{-- ✅ Affichage de l'image en mode visualisation --}}
                                @if($livre->image)
                                    <div class="text-center mb-3">
                                        <img src="{{ asset('storage/' . $livre->image) }}" style="max-height: 200px; border-radius: 10px;">
                                    </div>
                                @endif

                                <!-- Informations principales -->
                                <div style="background: rgba(255,255,255,0.15); padding: 20px; border-radius: 15px; margin-bottom: 20px;">
                                    <h6 style="color: white; margin-bottom: 15px; font-weight: 600;">📖 Informations principales</h6>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" style="color: white; font-weight: 500;">Titre</label>
                                            <input type="text" class="form-control" value="{{ $livre->titre }}" readonly
                                                   style="background: rgba(255,255,255,0.9); border: none; border-radius: 10px; padding: 12px;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" style="color: white; font-weight: 500;">Auteur</label>
                                            <input type="text" class="form-control" value="{{ $livre->auteur }}" readonly
                                                   style="background: rgba(255,255,255,0.9); border: none; border-radius: 10px; padding: 12px;">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" style="color: white; font-weight: 500;">Genre</label>
                                            <input type="text" class="form-control" value="{{ $livre->genre }}" readonly
                                                   style="background: rgba(255,255,255,0.9); border: none; border-radius: 10px; padding: 12px;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" style="color: white; font-weight: 500;">Année</label>
                                            <input type="number" class="form-control" value="{{ $livre->annee }}" readonly
                                                   style="background: rgba(255,255,255,0.9); border: none; border-radius: 10px; padding: 12px;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Détails du livre -->
                                <div style="background: rgba(255,255,255,0.15); padding: 20px; border-radius: 15px; margin-bottom: 20px;">
                                    <h6 style="color: white; margin-bottom: 15px; font-weight: 600;">📝 Détails du livre</h6>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" style="color: white; font-weight: 500;">Maison d'édition</label>
                                            <input type="text" class="form-control" value="{{ $livre->maisonEdition }}" readonly
                                                   style="background: rgba(255,255,255,0.9); border: none; border-radius: 10px; padding: 12px;">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" style="color: white; font-weight: 500;">Nombre de pages</label>
                                            <input type="number" class="form-control" value="{{ $livre->nbPage }}" readonly
                                                   style="background: rgba(255,255,255,0.9); border: none; border-radius: 10px; padding: 12px;">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" style="color: white; font-weight: 500;">Format</label>
                                        <input type="text" class="form-control" value="{{ $livre->format }}" readonly
                                               style="background: rgba(255,255,255,0.9); border: none; border-radius: 10px; padding: 12px;">
                                    </div>
                                </div>

                                <!-- Avis personnel -->
                                <div style="background: rgba(255,255,255,0.15); padding: 20px; border-radius: 15px;">
                                    <h6 style="color: white; margin-bottom: 15px; font-weight: 600;">⭐ Mon avis</h6>

                                    <div class="mb-3">
                                        <label class="form-label" style="color: white; font-weight: 500;">Avis</label>
                                        <textarea class="form-control" rows="4" readonly
                                                  style="background: rgba(255,255,255,0.9); border: none; border-radius: 10px; padding: 12px;">{{ $livre->avis }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" style="color: white; font-weight: 500;">Note /5</label>
                                        <div class="p-2 bg-secondary bg-opacity-25 rounded"
                                             style="min-height: 40px; display: flex; align-items: center;">
                                            @for($i = 1; $i <= ($livre->note ?? 0); $i++)
                                                <span class="text-warning fs-4 me-1">⭐</span>
                                            @endfor
                                            @for($i = ($livre->note ?? 0) + 1; $i <= 5; $i++)
                                                <span class="text-muted fs-4 me-1">☆</span>
                                            @endfor
                                            <small class="text-white-50 ms-2">({{ number_format($livre->note ?? 0) }}/5)</small>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Footer -->
                            <div class="modal-footer" style="border-top: 2px solid rgba(255,255,255,0.2); padding: 20px;">
                                <button type="button" class="btn" data-bs-dismiss="modal"
                                        style="background: rgba(255,255,255,0.2); color: white; border: 2px solid rgba(255,255,255,0.3); padding: 10px 24px; border-radius: 10px; font-weight: 500;">
                                    Fermer
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Modal de suppression -->
                <div class="modal fade" id="suppressionModal{{ $livre->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 20px;">

                            <!-- Header -->
                            <div class="modal-header" style="border-bottom: 2px solid rgba(255,255,255,0.2); padding: 25px;">
                                <h5 class="modal-title" style="color: white; font-size: 24px; font-weight: 600;">
                                    🗑️ Supprimer le livre
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <!-- Body -->
                            <div class="modal-body" style="padding: 30px;">
                                <div style="background: rgba(255,255,255,0.15); padding: 20px; border-radius: 15px; text-align: center;">
                                    <div style="font-size: 48px; margin-bottom: 15px;">⚠️</div>
                                    <h6 style="color: white; font-size: 18px; margin-bottom: 15px; font-weight: 600;">
                                        Êtes-vous sûr de vouloir supprimer ce livre ?
                                    </h6>
                                    <p style="color: rgba(255,255,255,0.9); margin-bottom: 10px; font-size: 16px;">
                                        <strong>{{ $livre->titre }}</strong>
                                    </p>
                                    <p style="color: rgba(255,255,255,0.8); font-size: 14px;">
                                        Cette action est irréversible.
                                    </p>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="modal-footer" style="border-top: 2px solid rgba(255,255,255,0.2); padding: 20px;">
                                <button type="button" class="btn" data-bs-dismiss="modal"
                                        style="background: rgba(255,255,255,0.2); color: white; border: 2px solid rgba(255,255,255,0.3); padding: 10px 24px; border-radius: 10px; font-weight: 500;">
                                    Annuler
                                </button>
                                <form method="POST" action="{{ route('livres.destroy', $livre->id) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn"
                                            style="background: rgba(220, 53, 69, 0.8); color: white; border: 2px solid rgba(220, 53, 69, 1); padding: 10px 24px; border-radius: 10px; font-weight: 600;">
                                        🗑️ Confirmer la suppression
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Modal Ajouter un livre -->
    <div class="modal fade" id="modalAjouter" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 20px;">

                <!-- Header -->
                <div class="modal-header" style="border-bottom: 2px solid rgba(255,255,255,0.2); padding: 25px;">
                    <h5 class="modal-title" style="color: white; font-size: 24px; font-weight: 600;">
                        ➕ Ajouter un livre
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                {{-- ✅ enctype pour upload d'image --}}
                <form method="POST" action="{{ route('livres.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body" style="padding: 30px; max-height: 70vh; overflow-y: auto;">

                        <!-- Informations principales -->
                        <div style="background: rgba(255,255,255,0.15); padding: 20px; border-radius: 15px; margin-bottom: 20px;">
                            <h6 style="color: white; margin-bottom: 15px; font-weight: 600;">📖 Informations principales</h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" style="color: white; font-weight: 500;">Titre *</label>
                                    <input type="text" class="form-control" name="titre" value="{{ old('titre') }}"
                                           placeholder="Ex: Harry Potter" required
                                           style="background: rgba(255,255,255,0.9); border: none; border-radius: 10px; padding: 12px;">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" style="color: white; font-weight: 500;">Auteur *</label>
                                    <input type="text" class="form-control" name="auteur" value="{{ old('auteur') }}"
                                           placeholder="Ex: J.K. Rowling" required
                                           style="background: rgba(255,255,255,0.9); border: none; border-radius: 10px; padding: 12px;">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" style="color: white; font-weight: 500;">Genre</label>
                                    <input type="text" class="form-control" name="genre" value="{{ old('genre') }}"
                                           placeholder="Ex: Fantasy"
                                           style="background: rgba(255,255,255,0.9); border: none; border-radius: 10px; padding: 12px;">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" style="color: white; font-weight: 500;">Année</label>
                                    <input type="number" class="form-control" name="annee" value="{{ old('annee') }}"
                                           placeholder="Ex: 1997"
                                           style="background: rgba(255,255,255,0.9); border: none; border-radius: 10px; padding: 12px;">
                                </div>
                            </div>
                        </div>

                        <!-- Détails du livre -->
                        <div style="background: rgba(255,255,255,0.15); padding: 20px; border-radius: 15px; margin-bottom: 20px;">
                            <h6 style="color: white; margin-bottom: 15px; font-weight: 600;">📝 Détails du livre</h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" style="color: white; font-weight: 500;">Maison d'édition</label>
                                    <input type="text" class="form-control" name="maisonEdition" value="{{ old('maisonEdition') }}"
                                           placeholder="Ex: Gallimard"
                                           style="background: rgba(255,255,255,0.9); border: none; border-radius: 10px; padding: 12px;">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" style="color: white; font-weight: 500;">Nombre de pages</label>
                                    <input type="number" class="form-control" name="nbPage" value="{{ old('nbPage') }}"
                                           placeholder="Ex: 320"
                                           style="background: rgba(255,255,255,0.9); border: none; border-radius: 10px; padding: 12px;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" style="color: white; font-weight: 500;">Format</label>
                                <select class="form-control" name="format"
                                        style="background: rgba(255,255,255,0.9); border: none; border-radius: 10px; padding: 12px;">
                                    <option value="">-- Choisir un format --</option>
                                    <option value="Broché">Broché</option>
                                    <option value="Poche">Poche</option>
                                    <option value="Relié">Relié</option>
                                </select>
                            </div>

                            {{-- ✅ Import image par fichier (comme les DVDs) --}}
                            <div class="mb-3">
                                <label class="form-label" style="color: white; font-weight: 500;">Couverture (Fichier Image)</label>
                                <input type="file" name="image" class="form-control" accept="image/*"
                                       style="background: rgba(255,255,255,0.9); border: none; border-radius: 10px; padding: 12px;">
                            </div>
                        </div>

                        <!-- Avis personnel -->
                        <div style="background: rgba(255,255,255,0.15); padding: 20px; border-radius: 15px;">
                            <h6 style="color: white; margin-bottom: 15px; font-weight: 600;">⭐ Mon avis</h6>

                            <div class="mb-3">
                                <label class="form-label" style="color: white; font-weight: 500;">Ton avis</label>
                                <textarea class="form-control" name="avis" rows="4"
                                          placeholder="Écris ton avis sur ce livre..."
                                          style="background: rgba(255,255,255,0.9); border: none; border-radius: 10px; padding: 12px;">{{ old('avis') }}</textarea>
                            </div>

                            {{-- ✅ Menu déroulant pour la note (comme les DVDs) --}}
                            <div class="mb-3">
                                <label class="form-label" style="color: white; font-weight: 500;">Note sur 5</label>
                                <select name="note" class="form-control"
                                        style="background: rgba(255,255,255,0.9); border: none; border-radius: 10px; padding: 12px; width: 150px;">
                                    @for($i = 0; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ old('note') == $i ? 'selected' : '' }}>{{ $i }} Étoile{{ $i > 1 ? 's' : '' }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="modal-footer" style="border-top: 2px solid rgba(255,255,255,0.2); padding: 20px;">
                        <button type="button" class="btn" data-bs-dismiss="modal"
                                style="background: rgba(255,255,255,0.2); color: white; border: 2px solid rgba(255,255,255,0.3); padding: 10px 24px; border-radius: 10px; font-weight: 500;">
                            Annuler
                        </button>
                        <button type="submit" class="btn"
                                style="background: rgba(102, 126, 234, 0.8); color: white; border: 2px solid rgba(102, 126, 234, 1); padding: 10px 24px; border-radius: 10px; font-weight: 600;">
                            ➕ Ajouter le livre
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
