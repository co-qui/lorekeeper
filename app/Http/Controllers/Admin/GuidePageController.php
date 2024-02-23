<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Auth;

use App\Models\Guide\GuidePage;
use App\Models\Guide\GuideCategory;
use App\Services\GuidePageService;

use App\Http\Controllers\Controller;

class GuidePageController extends Controller
{
    /**
     * Shows the page index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getIndex()
    {
        return view('admin.guide.guide_pages', [
            'pages' => GuidePage::orderBy('sort', 'DESC')->get()
        ]);
    }
    
    /**
     * Shows the create page page. 
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreatePage()
    {
        return view('admin.guide.create_edit_guide_page', [
            'page' => new GuidePage,
            'categories' => [ 0 => 'Select Category' ] + GuideCategory::orderBy('name')->pluck('name', 'id')->toArray()
        ]);
    }
    
    /**
     * Shows the edit page page.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditPage($id)
    {
        $page = GuidePage::find($id);
        if(!$page) abort(404);
        return view('admin.guide.create_edit_guide_page', [
            'page' => $page,
            'categories' => [ 0 => 'Select Category' ] + GuideCategory::orderBy('name')->pluck('name', 'id')->toArray()
        ]);
    }

    /**
     * Creates or edits a page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Services\GuidePageService  $service
     * @param  int|null                  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditPage(Request $request, GuidePageService $service, $id = null)
    {
        $id ? $request->validate(GuidePage::$updateRules) : $request->validate(GuidePage::$createRules);
        $data = $request->only([
            'title', 'text', 'is_visible', 'category_id'
        ]);
        if($id && $service->updatePage(GuidePage::find($id), $data, Auth::user())) {
            flash('Page updated successfully.')->success();
        }
        else if (!$id && $page = $service->createPage($data, Auth::user())) {
            flash('Page created successfully.')->success();
            return redirect()->to('admin/guides/edit/'.$page->id);
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }
    
    /**
     * Gets the page deletion modal.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeletePage($id)
    {
        $page = GuidePage::find($id);
        return view('admin.guide._delete_guide_page', [
            'page' => $page,
        ]);
    }

    /**
     * Deletes a page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Services\GuidePageService  $service
     * @param  int                       $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeletePage(Request $request, GuidePageService $service, $id)
    {
        if($id && $service->deletePage(GuidePage::find($id))) {
            flash('Page deleted successfully.')->success();
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->to('admin/guides');
    }

    /**
     * Sorts guides.
     *
     * @param  \Illuminate\Http\Request    $request
     * @param  App\Services\GuidePageService  $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSort(Request $request, GuidePageService $service)
    {
        if($service->sortGuide($request->get('sort'))) {
            flash('Guide order updated successfully.')->success();
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }


    /***********************************
     *   CATEGORIES
     ***********************************/

    /**
     * Shows the category index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCategoryIndex()
    {
        return view('admin.guide.guide_categories', [
            'categories' => GuideCategory::orderBy('sort', 'DESC')->get()
        ]);
    }
    
    /**
     * Shows the create category page. 
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateCategory()
    {
        return view('admin.guide.create_edit_guide_category', [
            'category' => new GuideCategory
        ]);
    }
    
    /**
     * Shows the edit category page.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditCategory($id)
    {
        $page = GuideCategory::find($id);
        if(!$page) abort(404);
        return view('admin.guide.create_edit_guide_category', [
            'category' => $page
        ]);
    }

    /**
     * Creates or edits a category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Services\GuidePageService  $service
     * @param  int|null                  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditCategory(Request $request, GuidePageService $service, $id = null)
    {
        $id ? $request->validate(GuideCategory::$updateRules) : $request->validate(GuideCategory::$createRules);
        $data = $request->only([
            'name', 'image', 'description', 'remove_image'
        ]);
        if($id && $service->updateCategory(GuideCategory::find($id), $data, Auth::user())) {
            flash('Category updated successfully.')->success();
        }
        else if (!$id && $page = $service->createCategory($data, Auth::user())) {
            flash('Category created successfully.')->success();
            return redirect()->to('admin/guides/categories/edit/'.$page->id);
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }
    
    /**
     * Gets the category deletion modal.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeleteCategory($id)
    {
        $page = GuideCategory::find($id);
        return view('admin.guide._delete_guide_category', [
            'category' => $page,
        ]);
    }

    /**
     * Deletes a category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Services\GuidePageService  $service
     * @param  int                       $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteCategory(Request $request, GuidePageService $service, $id)
    {
        if($id && $service->deleteCategory(GuideCategory::find($id))) {
            flash('Category deleted successfully.')->success();
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->to('admin/guides/categories');
    }

    /**
     * Sorts categories.
     *
     * @param  \Illuminate\Http\Request    $request
     * @param  App\Services\GuidePageService  $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSortCategory(Request $request, GuidePageService $service)
    {
        if($service->sortCategory($request->get('sort'))) {
            flash('Category order updated successfully.')->success();
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }
}
