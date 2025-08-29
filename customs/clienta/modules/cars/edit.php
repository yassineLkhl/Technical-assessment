<?php
$clientId = 'clienta';

// Chargement des données (voitures + garages)
$carsPath = __DIR__ . '/../../../../data/cars.json';
$garagesPath = __DIR__ . '/../../../../data/garages.json';

$cars = json_decode(@file_get_contents($carsPath), true);
$garages = json_decode(@file_get_contents($garagesPath), true);

// Vérification basique : arrêt si les fichiers ne sont pas valides
if (!is_array($cars) || !is_array($garages)) {
    echo "<p>Erreur : impossible de charger les données.</p>";
    return;
}

// Récupération de l'id de voiture passé en paramètre (GET)
$carId = $_GET['id'] ?? null;
$car = null;

// Recherche de la voiture correspondant à l'id, et appartenant bien au client A
foreach ($cars as $c) {
    if ((string)$c['id'] === (string)$carId && $c['customer'] === $clientId) {
        $car = $c;
        break;
    }
}

// Si aucune voiture trouvée → on arrête
if (!$car) {
    echo "<p>Voiture introuvable.</p>";
    return;
}

// Recherche du garage associé à la voiture
$garageTitle = null;
foreach ($garages as $g) {
    if ($g['id'] == $car['garageId']) {
        $garageTitle = $g['title'];
        break;
    }
}
?>

<h2>Détail voiture (Client A)</h2>
<ul>
  <li>ID : <?= htmlspecialchars($car['id']) ?></li>
  <li>Modèle : <?= htmlspecialchars($car['modelName']) ?></li>
  <li>Marque : <?= htmlspecialchars($car['brand']) ?></li>
  <li>Année : <?= date('Y', (int)$car['year']) ?></li>
  <li>Puissance : <?= htmlspecialchars($car['power']) ?> ch</li>
  <!-- Affichage d’un petit carré coloré en plus du code hex -->
  <li>Couleur : <span style="background:<?= htmlspecialchars($car['colorHex']) ?>;padding:0 10px"></span> <?= htmlspecialchars($car['colorHex']) ?></li>
  <li>Garage : <?= htmlspecialchars($garageTitle ?? 'Inconnu') ?></li>
</ul>

<!-- Lien pour revenir à la liste des voitures -->
<p><a href="#" class="back-to-list">← Retour à la liste</a></p>
