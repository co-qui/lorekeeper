<?php

namespace App\Http\Controllers;

use App\Models\Handbook\HandbookCategory;
use App\Models\Handbook\HandbookPage;
use Auth;

class HandbookPageController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Handbook Page Controller
    |--------------------------------------------------------------------------
    |
    | Displays handbooks
    |
    */

    /**
     * Shows the handbook index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getIndex()
    {
        if (!Auth::check() || !Auth::user()->isStaff) {
            flash('You do not have the permission to access this page.')->error();
            return redirect('/');
        }

        if (Auth::user()->is_news_unread) {
            Auth::user()->update(['is_news_unread' => 0]);
        }

        return view('handbooks.handbook_index', [
            'categories'               => HandbookCategory::orderBy('sort', 'DESC')->get(),
            'handbooksWithoutCategory' => HandbookPage::where('category_id', '==', null)->orderBy('sort', 'DESC')->get(),
        ]);
    }

    /**
     * Shows a handbook.
     *
     * @param int         $id
     * @param string|null $slug
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getHandbook($id, $slug = null)
    {
        if (!Auth::check() || !Auth::user()->isStaff) {
            flash('You do not have the permission to access this page.')->error();
            return redirect('/');
        }

        $page = HandbookPage::where('id', $id)->where('is_visible', 1)->first();

        if (!$page) {
            abort(404);
        }

        return view('handbooks.handbook', ['page' => $page]);
    }

}
