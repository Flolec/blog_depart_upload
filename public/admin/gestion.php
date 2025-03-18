<?php
require_once  '../../php/config_perso.inc.php';
require  '../../php/db_article.inc.php';
require  '../../php/utils.inc.php';

use Blog\ArticleRepository;

$message = $messageErreur = '';
$articleRepository = new ArticleRepository();
$articles = $articleRepository->getAllArticles($messageErreur);

$afficherConfirmation = false;
$idToDelete = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

$action = nettoyage($_GET['action'] ?? '');
$titre = nettoyage($_GET['titre'] ?? '');
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);



//affichage de la confirmation
if ($id !== false && $id !== null && $action === "d") {
    $idToDelete =  $id;
    $afficherConfirmation = true;
}

// Suppression de l'article si confirmé
if (isset($_POST['confirm-delete']) && $idToDelete) {
    $afficherConfirmation = false;
    if ($articleRepository->deleteArticle($idToDelete, $messageErreur)) {
        $message = "L'article a bien été supprimé.";
        $articles = $articleRepository->getAllArticles($messageErreur);
    } else {
        $messageErreur .= "Erreur lors de la suppression de l'article.";
    }
}
?>

<?php include   '../../inc/head.inc.php' ?>
<?php include   '../../inc/header.inc.php' ?>
<main class="centrage ">

    <h1>Gestion des Articles</h1>
    <a href="new.php" class="btn color-theme">Ajouter un article</a>
    <?php
    afficherAlerte($message, 'success');
    afficherAlerte($messageErreur, 'danger');
    ?>
    <?php if ($afficherConfirmation) { ?>
        <div class="modal">
            <div class="modal-content">
                <h2>Confirmer la suppression</h2>
                <p>Voulez-vous vraiment supprimer l'article <?= $id; ?> ? Cette action est irréversible.</p>
                <form method="post" action='<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>'>
                    <input type="hidden" name="id" value="<?= $idToDelete; ?>">
                    <button type="submit" name="confirm-delete" class="btn color-danger">Oui, supprimer</button>
                    <a href="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="btn color-theme">Annuler</a>
                </form>
            </div>
        </div>
    <?php } ?>
    <table>
        <thead>
            <tr>
                <form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Actions</th>
                </form>
            </tr>

        </thead>
        <tbody>
            <?php foreach ($articles as $article) { ?>
                <tr>
                    <td><?= $article->id; ?></td>
                    <td><?= nettoyage($article->titre); ?></td>
                    <td>
                        <a href="new.php?id=<?= $article->id; ?>" class="btn color-edit">Modifier</a>
                        <a href="gestion.php?action=d&id=<?= $article->id; ?>" class="btn color-danger">Supprimer</a>
                        <a href="../article.php?&id=<?= $article->id; ?>" class="btn color-theme">Voir</a>

                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</main>


<?php include  '../../inc/footer.inc.php' ?>