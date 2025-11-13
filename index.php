<?php

require './Quiver.php';
require './Weapon.php';
require './Shield.php';
require './Human.php';
require './Combat.php';

const SEED = 33;
$seed = SEED ?: (int)(microtime(true) * 1000000) ^ hexdec(bin2hex(random_bytes(4)));
mt_srand($seed);

$sword = new Weapon(name: 'Épée en bois', damage: mt_rand(10, 40), range: mt_rand(15, 60) / 10);
$axe = new Weapon(name: 'Hache en pierre', damage: mt_rand(12, 48), range: mt_rand(12, 48) / 10);
$bow = new Weapon(
  name: 'Arc en bois',
  damage: mt_rand(8, 32),
  range: mt_rand(50, 200) / 10,
  quiver: new Quiver(arrows: mt_rand(0, 10)),
  isMelee: false
);
$shield = new Shield(durability: mt_rand(65, 260), tier: mt_rand(3, 12));

$steve = new Human(
  name: 'Steve',
  health: mt_rand(135, 540),
  weapon: $sword,
  secondaryWeapon: $bow,
  shield: $shield,
  position: 0
);

$alex = new Human(
  name: 'Alex',
  health: mt_rand(135, 540),
  weapon: $axe,
  position: 4
);

$combat = new Combat([$steve, $alex], $seed);


function printGameState(array $weapons, array $shields, array $humans, int $seed): void {
  echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

  foreach ($weapons as $weapon) {
    $emoji = $weapon->isMelee() ? ($weapon->getName() === 'Hache en pierre' ? "🪓" : "⚔️") : "🏹";
    echo "{$emoji}  {$weapon->getName()}\n";
    echo "    └ Dégâts: [{$weapon->getDamage()}]\n";
    echo "    └ Portée: [{$weapon->getRange()}]\n";

    if (!$weapon->isMelee() && $weapon->getQuiver() !== null) {
      $arrows = $weapon->getQuiver()->getArrows();
      echo "    └ Flèches: [" . ($arrows === 0 ? "∞" : $arrows) . "]\n";
    }
  }

  foreach ($shields as $shield) {
    echo "🛡️  Bouclier\n";
    echo "    └ Durabilité: [{$shield->getDurability()}]\n";
    echo "    └ Tier: [{$shield->getTier()}]\n";
  }
  
  echo "\n❤️  Combattants\n";
  foreach ($humans as $human) {
    echo "    └ {$human->getName()}: [{$human->getHealth()} PV]\n";
  }

  echo "\n🎲  Graine aléatoire: [{$seed}]\n";
  echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
}

printGameState([$sword, $axe, $bow], [$shield], [$steve, $alex], $seed);
$combat->start();