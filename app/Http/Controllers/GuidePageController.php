<?php

namespace App\Http\Controllers;

use Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Guide\GuidePage;
use App\Models\Guide\GuideCategory;

class GuidePageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Guide Page Controller
    |--------------------------------------------------------------------------
    |
    | Displays guides
    |
    */

    /**
     * Shows the guide index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getIndex()
    {
        if(Auth::check() && Auth::user()->is_news_unread) Auth::user()->update(['is_news_unread' => 0]);
        return view('guides.guide_index', [
            'categories' => GuideCategory::orderBy('sort', 'DESC')->get(),
            'guidesWithoutCategory' => GuidePage::where('category_id', '==', null)->orderBy('sort', 'DESC')->get()
        ]);
    }
    
    /**
     * Shows a guide.
     *
     * @param  int          $id
     * @param  string|null  $slug
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getGuide($id, $slug = null)
    {
        $page = GuidePage::where('id', $id)->where('is_visible', 1)->first();
        if(!$page) abort(404);
        return view('guides.guide', ['page' => $page]);
    }
}
