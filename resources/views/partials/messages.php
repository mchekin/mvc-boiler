<?php if (isset($messages) && count($messages) > 0): ?>
<div class="alert alert-success">
    <ul>
        <?php foreach ($messages as $message): ?>
            <li><?php echo $message; ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>
