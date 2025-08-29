<?php
$clientId = 'clienta';

// Charger la liste des voitures depuis le fichier JSON
$jsonPath = __DIR__ . '/../../../../data/cars.json';
$cars = json_decode(@file_get_contents($jsonPath), true);

// Vérification, on arrête si les données ne sont pas valides
if (!is_array($cars)) {
    echo "<p>Erreur : impossible de charger les données.</p>";
    return;
}

// Filtrer les voitures pour ne garder que celles du client A
$cars = array_values(array_filter($cars, fn($c) => ($c['customer'] ?? null) === $clientId));
?>
<h2>Voitures (Client A)</h2>
<?php if (empty($cars)): ?>
  <!-- Cas où aucune voiture n'est trouvée -->
  <p>Aucune voiture trouvée pour ce client.</p>
<?php else: ?>
<ul class="cars-list">
  <?php foreach ($cars as $car): ?>
    <?php
      // Calcul de l'âge de la voiture à partir du timestamp
      $year = (int)$car['year'];
      $age  = (int)date('Y') - (int)date('Y', $year);

      // Attribution d'une classe CSS pour la coloration
      $class = ($age > 10) ? 'car-old' : (($age < 2) ? 'car-new' : '');
    ?>
    <li class="<?= $class ?>">
      <!-- Chaque voiture est cliquable et mène à la vue détail (edit.php) -->
      <a href="#" class="car-link" data-id="<?= htmlspecialchars($car['id']) ?>">
        <?= htmlspecialchars($car['modelName']) ?> —
        <?= htmlspecialchars($car['brand']) ?> —
        Année : <?= date('Y', $year) ?> —
        Puissance : <?= htmlspecialchars($car['power']) ?> ch
      </a>
    </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>