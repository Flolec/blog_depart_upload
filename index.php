<?php

require_once  'php/config_perso.inc.php';
include 'php/db_article.inc.php';
include 'php/utils.inc.php';

use Blog\ArticleRepository;

$message = '';
$articleRepository = new ArticleRepository();
$articles = $articleRepository->getAllArticles($message);
$message .= ($articles && count($articles) < 1) ? 'Pas d\' article pour le moment' : '';

?>

<?php include 'inc/head.inc.php' ?>
<?php include 'inc/header.inc.php' ?>

<main class="centrage">

    <h1 class="center">Nos articles</h1>

    <section class="info containeurFlex">
        <h2 class="basis100">Aperçus des articles</h2>
        <?php

        if (!empty($message)) {
            echo $message;
        } else {
            foreach ($articles as $article) { ?>
                <article class="item">
                    <p>#Article <?= nettoyage($article->id) ?></p>
                    <h3><?= nettoyage($article->titre) ?></h3>
                    <a href='public/article.php?id=<?= nettoyage($article->id) ?>'>&gt;&gt;Lire l'article</a>
                </article>
        <?php  }
        }

        ?>
    </section>

</main>


<?php include 'inc/footer.inc.php' ?>