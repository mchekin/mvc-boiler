<?php

namespace App\CommentsSystem\Controllers;

use App\CommentsSystem\Entity\CommentRepository;
use App\CommentsSystem\Filters\CommentIdFilter;
use App\CommentsSystem\Filters\EmailFilter;
use App\CommentsSystem\Filters\MessageFilter;
use App\CommentsSystem\Filters\NameFilter;
use Mchekin\MVCBoiler\Container;
use Mchekin\MVCBoiler\Controllers\Controller;
use Mchekin\MVCBoiler\Http\RequestInterface;
use Mchekin\MVCBoiler\Http\ResponseInterface;
use Mchekin\MVCBoiler\Views\View;

class CommentController extends Controller
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var CommentRepository
     */
    protected $commentRepo;

    /**
     * @var View
     */
    protected $view;

    /**
     * @var Container
     */
    protected $container;

    /**
     * CommentController constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->request = $container->request();
        $this->response = $container->response();
        $this->commentRepo = $container->commentRepo();
        $this->view = $container->view();
    }

    /**
     *
     */
    public function index()
    {
        // page title
        $title ='Comments';

        // get the main comments/posts
        $comments = $this->commentRepo->mainComments();

        // reading flash messages and errors
        $messages = $this->request->session('messages', []);
        $errors = $this->request->session('errors', []);

        // render the results using a view template
        $body = $this->view->render('comments.php', compact('title', 'comments', 'messages', 'errors'));

        // dispatching the response with the view
        $this->response->dispatch($body);
    }

    /**
     * @param $id
     */
    public function add($id)
    {
        $payload = array_merge($this->request->payload(), ['parent_id' => $id]);

        $errors = [];

        // get request payload
        $payload = $this->filter([
            'email' => new EmailFilter(),
            'name' => new NameFilter(),
            'message' => new MessageFilter(),
            'parent_id' => new CommentIdFilter(),
        ], $payload, $errors);

        // spam prevention
        $spamFields = array_intersect_key($payload, array_flip(['name', 'message']));
        if( $this->commentRepo->isSpam($spamFields) ) {
            $errors['spam'] = 'Repeating the same message considered spam';
        }

        // if no errors found save the comment to the database
        if (empty($errors)) {
            $this->commentRepo->saveComment($payload);
            $this->response->setSession('messages', ['new-comment' => 'New comment added successfully!']);
        }
        else {
            $this->response->setSession('errors', $errors);
        }

        $this->response->redirect('/');
    }
}
