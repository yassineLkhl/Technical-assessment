<?php
$clientId = 'clientb';

// Charger les données (voitures + garages) depuis les fichiers JSON
$carsPath = __DIR__ . '/../../../../data/cars.json';
$garagesPath = __DIR__ . '/../../../../data/garages.json';

$cars = json_decode(@file_get_contents($carsPath), true);
$garages = json_decode(@file_get_contents($garagesPath), true);

// Vérification basique : on arrête si les données ne sont pas valides
if (!is_array($cars) || !is_array($garages)) {
    echo "<p>Erreur : impossible de charger les données.</p>";
    return;
}

// Récupération de l'id de voiture passé en GET
$carId = $_GET['id'] ?? null;
$car = null;

// Recherche de la voiture correspondant à l'id,
// en s'assurant qu'elle appartient bien au client B
foreach ($cars as $c) {
    if ((string)$c['id'] === (string)$carId && $c['customer'] === $clientId) {
        $car = $c;
        break;
    }
}

// Si aucune voiture trouvée, on affiche un message d'erreur
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

<h2>Détail voiture (Client B)</h2>
<ul>
  <li>ID : <?= htmlspecialchars($car['id']) ?></li>
  <!-- Particularité Client B : le modèle est affiché en minuscule -->
  <li>Modèle : <?= strtolower(htmlspecialchars($car['modelName'])) ?></li>
  <li>Marque : <?= htmlspecialchars($car['brand']) ?></li>
  <li>Année : <?= date('Y', (int)$car['year']) ?></li>
  <li>Puissance : <?= htmlspecialchars($car['power']) ?> ch</li>
  <!-- Affichage de la couleur (code + petit carré coloré) -->
  <li>Couleur : <span style="background:<?= htmlspecialchars($car['colorHex']) ?>;padding:0 10px"></span> <?= htmlspecialchars($car['colorHex']) ?></li>
  <li>Garage : <?= htmlspecialchars($garageTitle ?? 'Inconnu') ?></li>
</ul>

<!-- Bouton retour à la liste -->
<p><a href="#" class="back-to-list">← Retour à la liste</a></p>