<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get(
    '/',
    function () {
        ob_start();
        echo(get_rss_title('http://sacramento.craigslist.org/search/cta?catAbb=cto&query=ford%20escape%20hybrid&sort=priceasc&srchType=T&format=rss'));
        echo(get_rss_title('http://sfbay.craigslist.org/search/sss?catAbb=cto&query=ford%20escape%20hybrid&srchType=A&format=rss'));
        echo(get_rss_title('http://losangeles.craigslist.org/search/sss?catAbb=cto&query=ford%20escape%20hybrid&srchType=A&format=rss'));
        return ob_get_clean();
    }
);

function get_rss_title($feed_url)
{
    ob_start();
    $p = new SimplePie();
    $p->set_feed_url($feed_url);
    $p->set_cache_location('/tmp');
    $p->init();

    foreach ($p->get_items() as $item) {
//        var_dump(enforce_model_in_title('escape', $item->get_title()));
        if (enforce_model_in_title('escape', $item->get_title())) {
            var_dump($item->get_title());

            var_dump(get_year($item->get_title()));
            var_dump(get_price($item->get_title()));
            echo '<hr>';
        }
    }

    return ob_get_clean();
}

function get_year($string)
{
    preg_match('/20\d{2}/', $string, $matches);
    if (isset($matches[0])) {
        return ($matches[0]);
    } else {

        preg_match("/'[0-1][0-9]/", $string, $matches_abbr);
        if (isset($matches_abbr[0])) {
            return '20' . trim($matches_abbr[0], "'");
        } else {
            return 'No year found';
        }
    }
}

function get_price($string)
{
    preg_match('/\$\d{2,6}/', $string, $matches);
    if (isset($matches[0])) {
        return $matches[0];
    } else {
        return 'No price found.';
    }
}

function enforce_model_in_title($model, $title)
{
    $title = strtolower($title);
    if (strpos($title, $model) === false) {
        return false;
    } else {
        return true;
    }
}
