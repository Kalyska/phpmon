<?php
session_start();
session_destroy();


$json = file_get_contents("pokemons.json");
$pokemons = json_decode($json, true);

$random = array_rand($pokemons, 1);

include("templates/header.php");
?>
<div class="selection">
    <h2>Sélectionnez votre Pokémon</h2>

    <div class="pkm-display">
        <?php
        foreach ($pokemons as $pkm) {
        ?>
            <figure>
                <a href="duel.php?id=<?= $pkm["id"] ?>&vs=<?= $pokemons[$random]["id"] ?>"><img class="pkm-img" src="img/pokemons/<?= $pkm["id"] ?>.jpg" alt=""></a>
                <figcaption><img src="img/type/<?= $pkm["type"] ?>.png" alt="type du pokémon"><?= $pkm["puissance"] ?></figcaption>
            </figure>
        <?php
        }
        ?>
    </div>
</div>
<?php
include("templates/footer.php");
