<?php
$clientId = 'clientb';

$carsPath = __DIR__ . '/../../../../data/cars.json';
$garagesPath = __DIR__ . '/../../../../data/garages.json';

$cars = json_decode(@file_get_contents($carsPath), true);
$garages = json_decode(@file_get_contents($garagesPath), true);

if (!is_array($cars) || !is_array($garages)) {
    echo "<p>Erreur : impossible de charger les données.</p>";
    return;
}

// Indexer les garages par id
$garagesById = [];
foreach ($garages as $g) {
    $garagesById[$g['id']] = $g;
}

$cars = array_values(array_filter($cars, fn($c) => ($c['customer'] ?? null) === $clientId));
?>
<h2>Voitures (Client B)</h2>
<?php if (empty($cars)): ?>
  <p>Aucune voiture trouvée pour ce client.</p>
<?php else: ?>
  <ul>
    <?php foreach ($cars as $car): ?>
      <li>
        <a href="#" class="car-link" data-id="<?= htmlspecialchars($car['id']) ?>">
          <?= strtolower(htmlspecialchars($car['modelName'])) ?> —
          <?= htmlspecialchars($car['brand']) ?> —
          Garage : <?= htmlspecialchars($garagesById[$car['garageId']]['title'] ?? 'Inconnu') ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>