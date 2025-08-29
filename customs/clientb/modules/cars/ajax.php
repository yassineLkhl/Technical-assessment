<?php
$clientId = 'clientb';

// Charger les données voitures et garages depuis les fichiers JSON
$carsPath = __DIR__ . '/../../../../data/cars.json';
$garagesPath = __DIR__ . '/../../../../data/garages.json';

$cars = json_decode(@file_get_contents($carsPath), true);
$garages = json_decode(@file_get_contents($garagesPath), true);

// Vérification basique : arrêt si données invalides
if (!is_array($cars) || !is_array($garages)) {
    echo "<p>Erreur : impossible de charger les données.</p>";
    return;
}

// Création d'un tableau indexé par id pour retrouver rapidement un garage
$garagesById = [];
foreach ($garages as $g) {
    $garagesById[$g['id']] = $g;
}

// Filtrer les voitures pour ne garder que celles appartenant au client B
$cars = array_values(array_filter($cars, fn($c) => ($c['customer'] ?? null) === $clientId));
?>
<h2>Voitures (Client B)</h2>
<?php if (empty($cars)): ?>
  <!-- Cas où le client B n'a pas de voitures -->
  <p>Aucune voiture trouvée pour ce client.</p>
<?php else: ?>
  <ul>
    <?php foreach ($cars as $car): ?>
      <li>
        <!-- Chaque voiture est cliquable pour afficher le détail (edit.php) -->
        <a href="#" class="car-link" data-id="<?= htmlspecialchars($car['id']) ?>">
          <!-- Nom du modèle en minuscule + marque + garage associé -->
          <?= strtolower(htmlspecialchars($car['modelName'])) ?> —
          <?= htmlspecialchars($car['brand']) ?> —
          Garage : <?= htmlspecialchars($garagesById[$car['garageId']]['title'] ?? 'Inconnu') ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>