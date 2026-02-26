<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dvd.css') }}">
    <title>Ma Vidéothèque</title>

</head>
<body>

<div class="header">
    <h1>🎬 Ma Vidéothèque</h1>
    <div class="nav-buttons">
        <a href="{{ route('accueil') }}" class="btn">← Accueil</a>
        <a href="{{ route('logout') }}" class="btn">Déconnexion</a>
    </div>
</div>

<div class="container">
    <div class="actions-bar">
        <form action="{{ route('dvds.index') }}" method="GET" class="search-box">
            <input type="text" name="search" placeholder="🔍 Rechercher un film..." value="{{ request('search') }}">
            @if(request('search'))
                <a href="{{ route('dvds.index') }}" class="btn-clear" title="Effacer la recherche">✕</a>
            @endif
        </form>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAjouter">+ Ajouter un DVD</button>
    </div>

    @if($dvds->count() == 0)
        <div class="empty-state text-center p-5">
            <div class="display-1">💿</div>
            <h2>Aucun film trouvé...</h2>
            <p>Essayez une autre recherche ou ajoutez un nouveau titre !</p>
        </div>
    @else
        <div class="books-grid">
            @foreach($dvds as $dvd)
                <div class="book-card text-center">
                    <div style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalVoir{{ $dvd->id }}">
                        @if($dvd->image_url)
                            <img src="{{ asset('storage/' . $dvd->image_url) }}" class="dvd-poster" alt="{{ $dvd->titre }}">
                        @else
                            <div class="dvd-poster d-flex align-items-center justify-content-center">
                                <span style="font-size: 80px;">💿</span>
                            </div>
                        @endif

                        <div class="book-title text-truncate">{{ $dvd->titre }}</div>
                        <div class="book-author text-truncate">{{ $dvd->realisateur }}</div>

                        <div class="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                {{ $i <= $dvd->note ? '★' : '☆' }}
                            @endfor
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button class="btn btn-sm flex-fill" data-bs-toggle="modal" data-bs-target="#modalModifier{{ $dvd->id }}">Modifier</button>
                        <button class="btn btn-sm btn-danger flex-fill" data-bs-toggle="modal" data-bs-target="#modalSuppr{{ $dvd->id }}">Supprimer</button>
                    </div>
                </div>

                <div class="modal fade" id="modalVoir{{ $dvd->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title">Détails du film</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center p-4">
                                @if($dvd->image_url)
                                    <img src="{{ asset('storage/' . $dvd->image_url) }}" class="modal-poster-view mb-3">
                                @endif
                                <h2 class="mb-1">{{ $dvd->titre }}</h2>
                                <p class="text-danger fw-bold fs-5 mb-2">{{ $dvd->realisateur }}</p>

                                <div class="rating-stars fs-3 mb-4">
                                    @for($i = 1; $i <= 5; $i++)
                                        {{ $i <= $dvd->note ? '★' : '☆' }}
                                    @endfor
                                </div>

                                <div class="row g-3">
                                    <div class="col-4">
                                        <span class="info-label">Genre</span>
                                        <span class="info-value">{{ $dvd->genre ?? 'N/A' }}</span>
                                    </div>
                                    <div class="col-4">
                                        <span class="info-label">Année</span>
                                        <span class="info-value">{{ $dvd->annee ?? 'N/A' }}</span>
                                    </div>
                                    <div class="col-4">
                                        <span class="info-label">Durée</span>
                                        <span class="info-value">{{ $dvd->duree ?? '0' }} min</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalModifier{{ $dvd->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title">Modifier le film</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('dvds.update', $dvd->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Changer l'affiche (Image)</label>
                                        <input type="file" name="image" class="form-control" accept="image/*">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Note sur 5</label>
                                        <select name="note" class="form-select">
                                            @for($i = 0; $i <= 5; $i++)
                                                <option value="{{ $i }}" {{ $dvd->note == $i ? 'selected' : '' }}>{{ $i }} Étoile{{ $i > 1 ? 's' : '' }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Titre</label>
                                        <input type="text" name="titre" class="form-control" value="{{ $dvd->titre }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Réalisateur</label>
                                        <input type="text" name="realisateur" class="form-control" value="{{ $dvd->realisateur }}" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 mb-3">
                                            <label class="form-label">Année</label>
                                            <input type="number" name="annee" class="form-control" value="{{ $dvd->annee }}">
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label class="form-label">Durée (min)</label>
                                            <input type="number" name="duree" class="form-control" value="{{ $dvd->duree }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-0">
                                    <button type="submit" class="btn btn-primary w-100">Enregistrer les changements</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalSuppr{{ $dvd->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-danger">
                            <div class="modal-body text-center p-4">
                                <h3>Confirmer la suppression ?</h3>
                                <p>Voulez-vous retirer <strong>{{ $dvd->titre }}</strong> de la vidéothèque ?</p>
                                <div class="d-flex gap-3 mt-4">
                                    <button class="btn flex-fill" data-bs-dismiss="modal">Annuler</button>
                                    <form action="{{ route('dvds.destroy', $dvd->id) }}" method="POST" class="flex-fill">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<div class="modal fade" id="modalAjouter" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">📽️ Ajouter un DVD</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('dvds.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Affiche (Fichier Image)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Note sur 5</label>
                        <select name="note" class="form-select">
                            @for($i = 0; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ $i == 5 ? 'selected' : '' }}>{{ $i }} Étoile{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Titre du film</label>
                        <input type="text" name="titre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Réalisateur</label>
                        <input type="text" name="realisateur" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Genre</label>
                        <input type="text" name="genre" class="form-control" placeholder="Ex: Science-Fiction">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Année</label>
                            <input type="number" name="annee" class="form-control">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Durée (min)</label>
                            <input type="number" name="duree" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary w-100">Ajouter à la collection</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
