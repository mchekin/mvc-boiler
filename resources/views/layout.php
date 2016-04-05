<?php
$content = isset($content) ? $content : '';
$title = isset($title) ? $title : '';
?>
<!DOCTYPE html>
<html>
    <head><meta charset="utf-8">
        <title><?php echo $title ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    </head>
    <body>
        <div class="content col-md-8 col-md-offset-2">
            <?php include 'partials/messages.php' ?>
            <?php include 'partials/errors.php' ?>

            <?php echo $content ?>
        </div>

        <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script>
            $('.open-form').click(function(){
                $('.collapse.in').collapse('hide');
                $(this.dataset.target).collapse('show');
            });
        </script>
    </body>
</html>
