<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Repositories\PagesRepository;

class FrontController extends Controller
{
    public function getIndex(){
        $data['page_title'] = 'Landing Page | Ez Fit';
        $data['testimony'] = DB::table('testimony')->orderby('id','desc')->get();
        $data['slider_mobile'] = DB::table('slider_mobile')->orderby('id','desc')->get();
        $data['menu_example'] = DB::table('menu_example')->orderby('id','desc')->get();

        return view('frontend.pages.index',$data);
    }
    public function getSlider($type){
        $data = DB::table('testimony')
            ->orderby('id','desc');
        if (!empty($type) && $type != 'all'){
            $data = $data->where('type_testimony',$type);
        }
        $data = $data->get();

        return response()->json($data);
    }
    public function getPrivacy(){
    	$data['page_title'] = 'Privacy Policy | Ez Fit';
    	$data['content'] = PagesRepository::content('privacy_policy');

    	return view('frontend.pages.privacy',$data);
    }
    public function getAbout(){
        $data['page_title'] = 'About';
        $data['content'] = PagesRepository::content('about_us');

        return view('frontend.pages.page',$data);
    }

    public function getFaq(){
        $data['page_title'] = 'FAQ';
        $data['content'] = PagesRepository::content('faq');

        return view('frontend.pages.page',$data);
    }
    public function getContact(){
        $data['page_title'] = 'Contact Us';
        $data['content'] = PagesRepository::content('contact_us');

        return view('frontend.pages.page',$data);
    }
    public function getPrivacyPolicy(){
        $data['page_title'] = 'Privacy Policy';
        $data['content'] = PagesRepository::content('privacy_policy');

        return view('frontend.pages.page',$data);
    }
    public function getTac(){
        $data['page_title'] = 'Term And Condition';
        $data['content'] = PagesRepository::content('term_condition');

        return view('frontend.pages.page',$data);
    }

}
