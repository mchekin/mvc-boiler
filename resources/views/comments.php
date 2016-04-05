<?php
$title = isset($title) ? $title : '';
$comments = isset($comments) ? $comments : [];

?>
<?php ob_start() ?>
<h1><?php echo $title ?></h1>
<?php if(count($comments) > 0 ): ?>
    <ul class="list-group">
        <?php foreach ($comments as $comment):?>
            <li class="list-group-item">
                <?php include 'comment.php' ?>
            </li>
        <?php endforeach ?>
    </ul>
<?php else: ?>
    <p>No Comments/Posts.</p>
<?php endif; ?>
<?php $content = ob_get_clean() ?>

<?php include 'layout.php' ?>
