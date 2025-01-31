<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Login - Telecom</title>
    <link rel="icon" href="https://www.colas.com/favicon-32x32.png?v=a3aaafc2f61dca56c11ff88452088fe0" type="image/png">
    <link rel="stylesheet" href="{{ asset('/assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/css/Nunito.css') }}">
    <style>
        /* Corps de la page */
        body {
            background: #0a4866; /* Fond bleu principal */
            font-family: 'Nunito', sans-serif;
            color: #495057;
            height: 100vh; /* Hauteur pleine page */
            margin: 0;
            display: flex;
            align-items: center; /* Centrage vertical */
            justify-content: center; /* Centrage horizontal */
        }

        /* Carte contenant le formulaire */
        .card {
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            background-color: #ffffff; /* Fond blanc */
            max-width: 400px;
            width: 100%; /* Responsive */
            padding: 20px; /* Ajout d'un padding pour plus de confort visuel */
        }

        /* Champs du formulaire */
        .form-control {
            border: 1px solid #ced4da;
            border-radius: 8px;
            padding: 0.9rem 1rem;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: #0a4866;
            box-shadow: none;
        }

        /* Bouton principal */
        .btn-primary {
            background-color: #0a4866;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            padding: 0.8rem 1rem;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #083b52;
        }

        /* Labels */
        .form-label {
            font-weight: 600;
            margin-bottom: 8px; /* Espacement entre le label et le champ */
            color: #495057;
        }

        /* Notice de sécurité */
        .security-notice {
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 6px;
            padding: 12px;
            font-size: 0.85rem;
            color: #495057;
            text-align: center;
            margin-top: 15px; /* Espacement avec les champs */
        }

        .security-notice svg {
            margin-right: 6px;
        }

        /* Titre et logo */
        .card h4 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #333333;
        }

        .card img {
            margin-bottom: 10px;
        }

        /* Espacement entre les éléments */
        .form-group {
            margin-bottom: 20px; /* Ajout d'un espacement pour un visuel plus aéré */
        }

        /* Affichage des erreurs */
        .alert-danger {
            font-size: 0.9rem;
            padding: 10px;
            border-radius: 6px;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="card-body">
            <!-- Logo et titre -->
            <div class="text-center mb-4">
                <img width="200" src="{{ asset('/assets/img/COLAS%20WE%20OPEN%20THE%20WAY.png') }}" alt="Logo">
            </div>
            <h4 class="text-center text-dark mb-4">Connectez-vous à Telecom</h4>

            <!-- Formulaire -->
            <form method="post" action="{{ url('/loginCheck') }}" class="user">
                @csrf

                <!-- Champ Identifiant -->
                <div class="form-group">
                    <input id="identifiant" name="identifiant" class="form-control" type="text" placeholder="Login ou Adresse email" required value="{{ old('identifiant') }}">
                </div>

                <!-- Champ Mot de passe -->
                <div class="form-group">
                    <input id="password" name="password" class="form-control" type="password" placeholder="Mot de passe" required>
                </div>

                <!-- Affichage des erreurs -->
                @if ($errors->any())
                    <div class="alert alert-danger text-center">
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- Notice de sécurité -->
                <div class="security-notice">
                    <strong>Remarque :</strong> Le système est sensible à la casse. Vous devez respecter les Majuscules et Minuscules.
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                </div>

                <!-- Bouton Se connecter -->
                <button class="btn btn-primary d-block w-100 mt-4" type="submit">
                    Se connecter
                </button>
            </form>
        </div>
    </div>

    <script src="{{ asset('/assets/bootstrap/js/bootstrap.min.js') }}"></script>
</body>

</html>
