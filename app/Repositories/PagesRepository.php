<?php
namespace App\Repositories;

use DB;
use App\Models\Pages;

class PagesRepository extends Pages
{
    // TODO : Make you own query methods

	public static function content($slug){
		$page = Pages::findBySlug($slug);

		$result = $page->getContent();
		return $result;
	}
    public static function page($slug){
        $page = Pages::findBySlug($slug);

        $result = $page->getContent();
        return $result;
    }
}