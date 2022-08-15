<?php

use Illuminate\Pagination\Paginator;

Route::get('/', function () {
    $_SESSION['filter'] = $_SESSION['filter'] ?? 'all';
    $_SESSION['posts'] = $_SESSION['posts'] ?? [];
    $_SESSION['comments'] = $_SESSION['comments'] ?? [];
    
    $pages = Post::paginate(ITEMS_PER_PAGE)->lastPage();

    view('main', [
        'pages' => $pages
    ]);
});

//get amount of pages
Route::get('/posts/pages', function () {
    $pages = 0;

    switch ($_SESSION['filter']) {
        case 'negative':
            $pages = Post::where('rate', '<', '3')->where('rate', '>', '0')->paginate(ITEMS_PER_PAGE)->lastPage();
            break;   
        case 'positive':
            $pages = Post::where('rate', '>', '3')->paginate(ITEMS_PER_PAGE)->lastPage();
            break;    
        default:
            $pages = Post::paginate(ITEMS_PER_PAGE)->lastPage();
        break;
    }
    echo $pages;
});

// load some page with posts
Route::get('/posts/page/{page}', function ($page) {
    $posts = [];
    $_SESSION['filter'] = $_SESSION['filter'] ?? 'all';

    Paginator::currentPageResolver(function () use ($page) {
        return $page;
    });

    switch ($_SESSION['filter']) {
        case 'negative':
            $posts = Post::with('comments')->where('rate', '<', '3')->where('rate', '>', '0')->paginate(ITEMS_PER_PAGE)->all();
            break;   
        case 'positive':
            $posts = Post::with('comments')->where('rate', '>', '3')->paginate(ITEMS_PER_PAGE)->all();
            break;    
        default:
            $posts = Post::with('comments')->paginate(ITEMS_PER_PAGE)->all();
        break;
    }
    
    echo json_encode($posts);
});

//add rate to post
Route::post('/posts/rate', function () {
    // var_dump($_POST);
    $rate = $_POST['rate'];
    $post_id = $_POST['post_id'];
    $old_rate = $_SESSION['rated_posts'][$post_id] ?? 0;

    $post = Post::find($post_id);

    $sum = $post->rates_total * $post->rate;
    $rates_total = $post->rates_total;

    // if visitor rates post for the first time then we have to increase amount of rates
    if (!isset($_SESSION['rated_posts'][$post_id])) {
        $sum += $rate;
        $post->rates_total = ++$rates_total;
    } else {
        // if visitor does not then we have to substract old rate
        $sum = $sum - $old_rate + $rate;
    }    

    $post->rate = $sum / $rates_total;

    $_SESSION['rated_posts'][$post_id] = $rate;

    $post->save();

    echo json_encode(['rate' => $post->rate]);
});

// Store new post
Route::post('/posts/new/store', function () {
    $post= Post::store();
    $_SESSION['posts'][] = $post->id;
    echo $post->id;
});

// Store new comment
Route::post('/comments/new/store', function () {
    $comment = Comment::store();
    $_SESSION['comments'][] = $comment->id;
    echo $comment->id;
});

// set filter (negative, positive, all)
Route::post('/setfilter', function () {
    $_SESSION['filter'] = $_POST['filter'] ?? 'all';
});

Route::get('/test', function () {
    // $_SESSION = [];
    var_dump($_SESSION);
    echo '[' . join(', ', $_SESSION['comments']) . ']';
});