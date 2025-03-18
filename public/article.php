<?php

require_once  '../php/config_perso.inc.php';
require_once  '../php/db_article.inc.php';
require_once  '../php/utils.inc.php';


use Blog\ArticleRepository;

$messageErreur = "";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id !== false && $id !== null) {
    $articleRepository = new ArticleRepository();
    $article = $articleRepository->getArticleById($id, $messageErreur);

    if (!$article) {
        $messageErreur .=   "Erreur : L'article demandé n'existe pas.";
    }
} else {
    $messageErreur = "Erreur : L'identifiant de l'article est invalide.";
}

?>

<?php include  '../inc/head.inc.php' ?>
<?php include  '../inc/header.inc.php' ?>

<main class="centrage boxOmbre">
    <ul class="containerFlex">
        <li><i class="fa fa-arrow-left"></i> <a href="<?= BASE_URL ?>"> vers la liste des articles</a></li>
    </ul>
    <?php if (isset($article) && $article) { ?>
        <h1><?= nettoyage($article->titre) ?></h1>
        <div class="containeurFlex">
            <div class="basis50">
                <?php if (empty($article->nom_img)) { ?>
                    <img src="<?= BASE_URL ?>/img/no_image.png" alt="Pas d'image pour cet article">
                <?php } else {
                ?>
                    <img src="<?= BASE_URL . '/' . UPLOAD_DOSSIER ?><?= nettoyage($article->nom_img) ?>" alt="<?= nettoyage($article->nom_img) ?>">
                <?php

                }  ?>



            </div>
            <p class="basis50"><?= nl2br(nettoyage($article->contenu)) ?></p>
        </div>

    <?php } else {
        afficherAlerte($messageErreur, 'danger');
    } ?>


</main>


<?php include '../inc/footer.inc.php' ?>