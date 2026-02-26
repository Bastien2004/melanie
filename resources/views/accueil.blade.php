<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            color: white;
        }

        .header {
            max-width: 1200px;
            margin: 0 auto 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.15);
            padding: 20px 30px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }

        .header h1 {
            font-size: 28px;
            font-weight: 600;
        }

        .logout-btn {
            color: white;
            background: rgba(255, 255, 255, 0.2);
            padding: 12px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 50px;
            font-size: 18px;
            opacity: 0.95;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .category-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 40px 30px;
            border-radius: 20px;
            text-align: center;
            text-decoration: none;
            color: white;
            transition: all 0.3s;
            border: 2px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }

        .category-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .category-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .category-card:hover::before {
            opacity: 1;
        }

        .category-icon {
            font-size: 48px;
            margin-bottom: 15px;
            display: block;
        }

        .category-title {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .category-subtitle {
            font-size: 14px;
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .header h1 {
                font-size: 22px;
            }

            .categories-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="header">
    <h1>Bienvenue !</h1>
    <a href="{{ route('logout') }}" class="logout-btn">Se déconnecter</a>
</div>

<div class="container">
    <p class="welcome-text">Accéder et gérer ses collections</p>

    <div class="categories-grid">
        <a href="{{ route('livres') }}" class="category-card">
            <span class="category-icon">📚</span>
            <div class="category-title">Livres</div>
            <div class="category-subtitle">Ta bibliothèque</div>
        </a>

        <a href="{{ route('dvds.index') }}" class="category-card">
            <span class="category-icon">📀</span>
            <div class="category-title">DVD</div>
            <div class="category-subtitle">Ta collection de films</div>
        </a>

        <a href="" class="category-card">
            <span class="category-icon">⭐</span>
            <div class="category-title">Wishlist</div>
            <div class="category-subtitle">Tes envies</div>
        </a>

        <a href="" class="category-card">
            <span class="category-icon">🎮</span>
            <div class="category-title">Jeux</div>
            <div class="category-subtitle">Ta gaming room</div>
        </a>

        <a href="" class="category-card">
            <span class="category-icon">🕹️</span>
            <div class="category-title">Consoles</div>
            <div class="category-subtitle">Ton matériel</div>
        </a>

        <a href="" class="category-card">
            <span class="category-icon">🔌</span>
            <div class="category-title">Câbles</div>
            <div class="category-subtitle">Tes accessoires</div>
        </a>
    </div>
</div>
</body>
</html>
