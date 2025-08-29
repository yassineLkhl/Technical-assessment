<?php
// Détermination du client actif via cookie (par défaut : clienta)
$clientId = $_COOKIE['client_id'] ?? 'clienta';

// Permet de changer de client via les liens
if (isset($_GET['setClient'])) {
    $newClient = $_GET['setClient'];
    if (in_array($newClient, ['clienta','clientb','clientc'])) {
        // Cookie valable 30 jours, HTTPOnly
        setcookie('client_id', $newClient, time()+60*60*24*30, '/', '', false, true);
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto e-auto</title>

    <!-- Bootstrap CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>
        /* Personnalisation Client A (vieilles / nouvelles voitures) */
        .car-old { background-color: #f8d7da !important; } /* rouge clair */
        .car-new { background-color: #d4edda !important; } /* vert clair */
    </style>

    <script>
        $(function () {
            const client = <?= json_encode($clientId) ?>;
            let currentModule = "cars";

            // Fonction générique pour charger un module (cars ou garages)
            function loadModule(module = "cars") {
                currentModule = module;

                // Module garages réservé à Client B
                if (module === "garages" && client !== "clientb") {
                    $(".dynamic-div").html('<div class="alert alert-warning">Le module "Garages" est disponible uniquement pour le Client B.</div>');
                    return;
                }

                $.get(`customs/${client}/modules/${module}/ajax.php`, function (html) {
                    $(".dynamic-div").html(html);
                }).fail(function () {
                    $(".dynamic-div").html('<div class="alert alert-danger">Erreur lors du chargement du module.</div>');
                });
            }

            // Chargement initial
            loadModule("cars");

            // Navigation modules
            $(document).on("click", ".switch-module", function (e) {
                e.preventDefault();
                loadModule($(this).data("module"));
            });

            // Vue détail voiture
            $(document).on("click", ".car-link", function (e) {
                e.preventDefault();
                const carId = $(this).data("id");
                $.get(`customs/${client}/modules/cars/edit.php`, { id: carId }, function (html) {
                    $(".dynamic-div").html(html);
                }).fail(()=> $(".dynamic-div").html('<div class="alert alert-danger">Voiture introuvable.</div>'));
            });

            // Retour liste voitures
            $(document).on("click", ".back-to-list", function (e) {
                e.preventDefault();
                loadModule(currentModule);
            });

            // Vue détail garage (Client B)
            $(document).on("click", ".garage-link", function (e) {
                e.preventDefault();
                const gid = $(this).data("id");
                if (client !== "clientb") {
                    $(".dynamic-div").html('<div class="alert alert-warning">Module Garages réservé au Client B.</div>');
                    return;
                }
                $.get(`customs/${client}/modules/garages/edit.php`, { id: gid }, function (html) {
                    $(".dynamic-div").html(html);
                }).fail(()=> $(".dynamic-div").html('<div class="alert alert-danger">Garage introuvable.</div>'));
            });

            // Retour liste garages
            $(document).on("click", ".back-to-garages", function (e) {
                e.preventDefault();
                loadModule("garages");
            });
        });
    </script>
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1 class="mb-4">Auto e-auto</h1>

        <!-- Sélecteur de client -->
        <nav class="mb-3">
            <div class="btn-group" role="group">
                <a href="?setClient=clienta" class="btn btn-outline-primary">Client A</a>
                <a href="?setClient=clientb" class="btn btn-outline-primary">Client B</a>
                <a href="?setClient=clientc" class="btn btn-outline-primary">Client C</a>
            </div>
        </nav>

        <p>
            Client actif : <strong class="text-primary"><?= htmlspecialchars($clientId) ?></strong>
        </p>

        <!-- Navigation modules -->
        <div class="mb-3">
            <a href="#" class="switch-module btn btn-secondary btn-sm" data-module="cars">Voitures</a>
            <a href="#" class="switch-module btn btn-secondary btn-sm" data-module="garages">Garages (B)</a>
        </div>

        <!-- Contenu dynamique -->
        <div class="dynamic-div card shadow-sm p-3 bg-white rounded"></div>
    </div>
</body>
</html>
