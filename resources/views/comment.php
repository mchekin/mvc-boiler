<?php
/** @var Comment $comment */

use App\CommentsSystem\Entity\Comment;
use Mchekin\MVCBoiler\Container;
?>

<?php if (isset($comment) && is_a($comment, Comment::class)): ?>
    <div class="clearfix">
        <p>
            <?php echo $comment->name() ?> ( <i><span class="glyphicon glyphicon-envelope"></span> <?php echo $comment->email() ?></i> )
        </p>
        <p>
            <?php echo $comment->message() ?>
        </p>
        <button class="btn btn-default open-form pull-right" data-target="#collapse<?php echo $comment->getId() ?>">Reply
            <span class="glyphicon glyphicon-comment"></span>
        </button>
    </div>
    <hr>
    <div id="collapse<?php echo $comment->getId() ?>" class="collapse clearfix table table-bordered" style="padding: 10px">
        <form role="form" class="" method="POST" action="/comment/<?php echo $comment->getId() ?>">
            <div class="form-group">
                <div>
                    <label for="name" class="sr-only">Name</label>
                    <input type="text" name="name" value="" class="form-control" placeholder="Name" required="" autofocus="">
                </div>
            </div>

            <div class="form-group">
                <div>
                    <label for="email" class="sr-only">Email address</label>
                    <input type="email" name="email" value="" class="form-control" placeholder="Email address" required="" autofocus="">
                </div>
            </div>

            <div class="form-group">
                <div>
                    <label for="message" class="sr-only">Comment</label>
                    <textarea class="form-control" rows="5" name="message" id="message" placeholder="Comment here..." required=""></textarea>
                </div>
            </div>

            <div>
                <button type="submit" class="btn btn-success pull-right">Submit <span class="glyphicon glyphicon-pencil"></span></button>
            </div>
        </form>
    </div>
    <?php $childComments = Container::getGlobal()->commentRepo()->commentsByParentCommentId($comment->getId()); ?>
    <?php if(count($childComments) > 0 ): ?>
        <ul class="list-group">
            <?php foreach ($childComments as $comment):?>
                <li class="list-group-item">
                    <?php include 'comment.php' ?>
                </li>
            <?php endforeach ?>
        </ul>
    <?php endif; ?>
<?php endif; ?>
