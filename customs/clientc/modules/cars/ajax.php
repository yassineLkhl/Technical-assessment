<?php
$clientId = 'clientc';

$jsonPath = __DIR__ . '/../../../../data/cars.json';
$cars = json_decode(@file_get_contents($jsonPath), true);

if (!is_array($cars)) {
    echo "<p>Erreur : impossible de charger les données.</p>";
    return;
}

$cars = array_values(array_filter($cars, fn($c) => ($c['customer'] ?? null) === $clientId));
?>
<h2>Voitures (Client C)</h2>
<?php if (empty($cars)): ?>
  <p>Aucune voiture trouvée pour ce client.</p>
<?php else: ?>
  <ul>
    <?php foreach ($cars as $car): ?>
      <li>
        <a href="#" class="car-link" data-id="<?= htmlspecialchars($car['id']) ?>">
          <?= htmlspecialchars($car['modelName']) ?> —
          <?= htmlspecialchars($car['brand']) ?> —
          Couleur : <span style="display:inline-block;width:0.85em;height:0.85em;vertical-align:middle;border:1px solid #ccc;background:<?= htmlspecialchars($car['colorHex']) ?>"></span>
          <code><?= htmlspecialchars($car['colorHex']) ?></code>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>