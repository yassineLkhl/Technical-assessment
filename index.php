<?php

// Détermination du client actif via un cookie, par défaut clienta
$clientId = $_COOKIE['client_id'] ?? 'clienta';

// Permet de changer de client via les liens de navigation
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
    <style>
        /* Styles pour la liste des voitures (Etape 5) */
        .dynamic-div .cars-list { list-style: none; padding: 0; margin: 0; }
        .dynamic-div .cars-list li { padding: .5rem .75rem; border-bottom: 1px solid #eee; }
        .dynamic-div .car-old { background-color: #ffe6e6; }  /* > 10 ans */
        .dynamic-div .car-new { background-color: #e6ffea; }  /* < 2 ans */
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tool4cars</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(function () {
            const client = <?= json_encode($clientId) ?>;
            let currentModule = "cars";

            // Charge dynamiquement un module (cars ou garages) via Ajax
            function loadModule(module = "cars") {
                currentModule = module;

                // Limite l'accès au module garages (réservé au client B)
                if (module === "garages" && client !== "clientb") {
                    $(".dynamic-div").html('<p>Le module "Garages" est disponible uniquement pour le Client B.</p>');
                    return;
                }

                $.get(`customs/${client}/modules/${module}/ajax.php`, function (html) {
                    $(".dynamic-div").html(html);
                }).fail(function () {
                    $(".dynamic-div").html("<p>Erreur lors du chargement du module.</p>");
                });
            }

            // Chargement initial du module voitures
            loadModule("cars");

            // Gestion navigation modules
            $(document).on("click", ".switch-module", function (e) {
                e.preventDefault();
                loadModule($(this).data("module"));
            });

            // Gestion des voitures 
            // Ouverture de la fiche détail au clic sur une voiture
            $(document).on("click", ".car-link", function (e) {
                e.preventDefault();
                const carId = $(this).data("id");
                $.get(`customs/${client}/modules/cars/edit.php`, { id: carId }, function (html) {
                    $(".dynamic-div").html(html);
                }).fail(()=> $(".dynamic-div").html("<p>Voiture introuvable.</p>"));
            });

            // Retour à la liste des voitures
            $(document).on("click", ".back-to-list", function (e) {
                e.preventDefault();
                loadModule(currentModule);
            });

            // Gestion des garages (Client B uniquement)
            // Ouverture de la fiche détail au clic sur un garage
            $(document).on("click", ".garage-link", function (e) {
                e.preventDefault();
                const gid = $(this).data("id");
                if (client !== "clientb") {
                    $(".dynamic-div").html('<p>Module Garages réservé au Client B.</p>');
                    return;
                }
                $.get(`customs/${client}/modules/garages/edit.php`, { id: gid }, function (html) {
                    $(".dynamic-div").html(html);
                }).fail(()=> $(".dynamic-div").html("<p>Garage introuvable.</p>"));
            });

            // Retour à la liste des garages
            $(document).on("click", ".back-to-garages", function (e) {
                e.preventDefault();
                loadModule("garages");
            });
        });
    </script>
</head>
<body>
    <h1>Auto e-auto</h1>
    <nav>
        <!-- Lien de sélection du client -->
        <a href="?setClient=clienta"> Client A </a>
        <a href="?setClient=clientb"> Client B </a>
        <a href="?setClient=clientc"> Client C </a>
    </nav>
    <p> Client actif : <strong><?= htmlspecialchars($clientId) ?></strong></p>

    <p>
        Modules :
        <a href="#" class="switch-module" data-module="cars">Voitures</a> |
        <a href="#" class="switch-module" data-module="garages">Garages (B)</a>
    </p>

    <!-- Contenu chargé dynamiquement (liste/détail voitures ou garages) -->
    <div class="dynamic-div"></div>
</body>
</html>