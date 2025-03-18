<?php
require_once  '../../php/config_perso.inc.php';
require  '../../php/db_article.inc.php';
require  '../../php/utils.inc.php';
require_once  '../../php/utils_upload.inc.php';

use Blog\ArticleRepository;
use Blog\Article;

// Récupération de l'ID en GET
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$titre = nettoyage($_POST['titre'] ?? '');
$contenu = nettoyage($_POST['contenu'] ?? '');


$erreurs = [];
$messageErreur = $message  = '';

$articleRepository = new ArticleRepository();

// Chargement de l'article en mode modification
if ($id !== false && $id !== null) {
    $article = $articleRepository->getArticleById($id, $messageErreur);
    if ($article) {
        $titre = nettoyage($article->titre);
        $contenu = nettoyage($article->contenu);
    } else {
        $messageErreur = "Article introuvable.";
    }
}


//soumission du formulaire
if (isset($_POST['btn_article'])) {

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    // Validation du titre
    if (empty($titre)) {
        $erreurs[] = 'Le titre ne peut pas être vide';
    } else if (mb_strlen($titre) > 100) {
        $erreurs[] = 'Le titre ne peut pas être excéder 100 caractères.';
    }
    // Validation du contenu
    if (empty($contenu)) {
        $erreurs[] = 'Le contenu ne peut pas être vide';
    }
    //getion de l'insert et de la modif
    if (empty($erreurs)) {
        $article = new Article();
        $article->titre = $titre;
        $article->contenu = $contenu;


        if ($id !== false && $id !== null) {
            // Mode modification
            $article->id = $id;
            if ($articleRepository->updateArticle($article, $messageErreur)) {
                $message = "Article mis à jour avec succès.";
            } else {
                $erreurs[] = "Erreur technique lors de la mise à jour.";
                $erreurs[] = $messageErreur;
            }
        } else {
            // Mode ajout
            if ($articleRepository->insertArticle($article, $messageErreur)) {
                $message .= "Article correctement ajouté.";
                $titre = $contenu = '';
            } else {
                $erreurs[]  = "Erreur technique. Veuillez contacter l'administrateur.";
                $erreurs[] = $messageErreur;
            }
        }
    }
}
?>


<?php include   '../../inc/head.inc.php' ?>
<?php include   '../../inc/header.inc.php' ?>

<main class="centrage boxOmbre">

    <h1><?= $id ? 'Modifier' : 'Nouvel' ?> Article</h1>
    <ul class="containerFlex">
        <li><i class="fa fa-arrow-left"></i> <a href="<?= BASE_URL ?>"> vers la liste des articles</a></li>
    </ul>
    <form action="new.php" method="POST" class="formAdmin">
        <h2><?= $id ? 'Modifier' : 'Ajouter' ?> un article</h2>
        <?php
        afficherAlerte($message, 'success');
        afficherAlerte($erreurs, 'danger');
        ?>

        <input type="hidden" name="id" value="<?= $id ?>">

        <!-- Pour tester, les attributs required ont été enlevés et autres validations maxlength="100" -->
        <label for id="titre">Titre *<br><small>100 caractères max</small></label><input type="text" size="50" id="titre" name="titre" value="<?= $titre ?>">
        <label for id="contenu">Contenu *</label><textarea name="contenu" id="contenu"><?= $contenu ?></textarea>
        <input type="submit" class="btn btn-theme" name="btn_article" value="<?= $id ? 'Modifier' : 'Ajouter' ?>">

    </form>
</main>


<?php include  '../../inc/footer.inc.php' ?>