<?php
session_start();

require("./Pokemon.php");
$json = file_get_contents("pokemons.json");
$pokemons = json_decode($json, true);

// Création des Pokémons

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $pkmActif;

    foreach ($pokemons as $pkm) {
        if ($pkm["id"] == $_GET["id"]) {
            $actifPkm = new Pokemon($pkm["id"], $pkm["name"], $pkm["puissance"], $pkm["type"], $pkm["weak"]);
            foreach ($pkm["attacks"] as $atk) {
                $actifPkm->addAttack($atk["name"], $atk["damage"], $atk["type"]);
            }
        }
        if ($pkm["id"] == $_GET["vs"]) {
            $vsPkm = new Pokemon($pkm["id"], $pkm["name"], $pkm["puissance"], $pkm["type"], $pkm["weak"]);
            foreach ($pkm["attacks"] as $atk) {
                $vsPkm->addAttack($atk["name"], $atk["damage"], $atk["type"]);
            }
        }
    }

    // Enregistrement des objets dans la session

    $_SESSION["actifPkm"] = serialize($actifPkm);
    $_SESSION["vsPkm"] = serialize($vsPkm);
}

// Récupération des objets

$actifPkm = unserialize($_SESSION["actifPkm"]);
$vsPkm = unserialize($_SESSION["vsPkm"]);

// Récupération des attaques 

$attacks = $actifPkm->getAttacks();
$counterattacks = $vsPkm->getAttacks();
$random = array_rand($counterattacks, 1);



// Calcul des dégâts et rédaction du message

if (isset($_GET["a"])) {
    $a = $_GET["a"];

    foreach ($attacks as $attack) {
        if ($a === ($attack->name)) {
            $damage = ceil(($attack->damage) * ($actifPkm->puissance) / ($vsPkm->puissance));
            if ($attack->type === $vsPkm->weak) {
                $damage *= 2;
            }
            $vsPkm->life = ($vsPkm->life) - $damage;
            if ($vsPkm->life < 0) {
                $vsPkm->life = 0;
            }
            $player = "{$actifPkm->name} lance l'attaque {$attack->name} et inflige {$damage} points de dégâts à l'adversaire.";
            $_SESSION['vsPkm'] = serialize($vsPkm);
        }
    }

    if ($vsPkm->life > 0) {
        $damage = ceil(($counterattacks[$random]->damage) * ($vsPkm->puissance) / ($actifPkm->puissance));
        if ($counterattacks[$random]->type === $actifPkm->weak) {
            $damage *= 2;
        }
        $actifPkm->life = ($actifPkm->life) - $damage;
        if ($actifPkm->life < 0) {
            $actifPkm->life = 0;
        }
        $opponent = "{$vsPkm->name} adverse lance l'attaque {$counterattacks[$random]->name} et inflige {$damage} points de dégâts à {$actifPkm->name}.";
        $_SESSION['actifPkm'] = serialize($actifPkm);
    }

    // Fin du combat si 1 des 2 adversaires est KO

    if ($actifPkm->life === 0 || $vsPkm->life === 0) {
        if ($actifPkm->life > 0) {
            $message = "Bravo ! Vous avez gagné !";
        } else {
            $message = "Dommage ! Vous avez perdu.";
        }
        session_destroy();
    }
}

// Affichage

include("templates/header.php");
?>
<div>
    <h2>C'est l'heure du du-du-du-duel !</h2>
    <div class="duel-display">
        <div class="player">
            <div class="life">
                <div class="life-container">
                    <div class="life-color red width-<?= $actifPkm->life ?>"></div>
                </div>
                <span><?= $actifPkm->life ?> PV</span>
            </div>

            <figure>
                <img class="pkm-img" src="./img/pokemons/<?= $actifPkm->id ?>.jpg" alt="votre Pokémon">
                <figcaption><img src="img/type/<?= $actifPkm->type ?>.png" alt="type du pokémon"><?= $actifPkm->puissance ?></figcaption>
            </figure>

            <div class="attacks">
                <?php
                foreach ($attacks as $attack) {
                ?>
                    <a class="btn" href="duel.php?a=<?= $attack->name ?>">
                        <?= $attack->name ?>
                    </a>
                <?php
                }
                ?>
            </div>
        </div>

        <div class="duel-details">
            <?php if (isset($player)) {
            ?>
                <p><?= $player ?></p>
            <?php
            }
            if (isset($opponent)) {
            ?>
                <p><?= $opponent ?></p>
            <?php
            }
            if (isset($message)) {
            ?>
                <p><?= $message ?></p>
                <a class="btn" href="index.php">Rejouer</a>
            <?php
            } ?>
        </div>

        <div class="opponent">
            <div class="life">
                <div class="life-container">
                    <div class="life-color width-<?= $vsPkm->life ?>"></div>
                </div>
                <span><?= $vsPkm->life ?> PV</span>
            </div>

            <figure>
                <img class="pkm-img" src="./img/pokemons/<?= $vsPkm->id ?>.jpg" alt="votre Pokémon">
                <figcaption><img src="img/type/<?= $vsPkm->type ?>.png" alt="type du pokémon"><?= $vsPkm->puissance ?></figcaption>
            </figure>
        </div>
    </div>
</div>
<?php
include("templates/footer.php");
