<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Handbook\HandbookCategory;
use App\Models\Handbook\HandbookPage;
use App\Services\HandbookPageService;
use Auth;
use Illuminate\Http\Request;

class HandbookPageController extends Controller {
    /**
     * Shows the page index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getIndex() {
        return view('admin.handbook.handbook_pages', [
            'pages' => HandbookPage::orderBy('sort', 'DESC')->get(),
        ]);
    }

    /**
     * Shows the create page. 
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreatePage() {
        return view('admin.handbook.create_edit_handbook_page', [
            'page'       => new HandbookPage,
            'categories' => [0 => 'Select Category'] + HandbookCategory::orderBy('name')->pluck('name', 'id')->toArray(),
        ]);
    }

    /**
     * Shows the edit page page.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditPage($id) {
        $page = HandbookPage::find($id);
        if (!$page) {
            abort(404);
        }

        return view('admin.handbook.create_edit_handbook_page', [
            'page'       => $page,
            'categories' => [0 => 'Select Category'] + HandbookCategory::orderBy('name')->pluck('name', 'id')->toArray(),
        ]);
    }

    /**
     * Creates or edits a page.
     *
     * @param App\Services\HandbookPageService $service
     * @param int|null                         $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditPage(Request $request, HandbookPageService $service, $id = null) {
        $id ? $request->validate(HandbookPage::$updateRules) : $request->validate(HandbookPage::$createRules);
        $data = $request->only([
            'title', 'text', 'is_visible', 'category_id',
        ]);
        if ($id && $service->updatePage(HandbookPage::find($id), $data, Auth::user())) {
            flash('Page updated successfully.')->success();
        } elseif (!$id && $page = $service->createPage($data, Auth::user())) {
            flash('Page created successfully.')->success();

            return redirect()->to('admin/handbooks/edit/'.$page->id);
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }

    /**
     * Gets the page deletion modal.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeletePage($id) {
        $page = HandbookPage::find($id);

        return view('admin.handbook._delete_handbook_page', [
            'page' => $page,
        ]);
    }

    /**
     * Deletes a page.
     *
     * @param App\Services\HandbookPageService $service
     * @param int                              $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeletePage(Request $request, HandbookPageService $service, $id) {
        if ($id && $service->deletePage(HandbookPage::find($id))) {
            flash('Page deleted successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->to('admin/handbooks');
    }

    /**
     * Sorts handbooks.
     *
     * @param App\Services\HandbookPageService $service
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSort(Request $request, HandbookPageService $service) {
        if ($service->sortHandbook($request->get('sort'))) {
            flash('Handbook order updated successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
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
    public function getCategoryIndex() {
        return view('admin.handbook.handbook_categories', [
            'categories' => HandbookCategory::orderBy('sort', 'DESC')->get(),
        ]);
    }

    /**
     * Shows the create category page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateCategory() {
        return view('admin.handbook.create_edit_handbook_category', [
            'category' => new HandbookCategory,
        ]);
    }

    /**
     * Shows the edit category page.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditCategory($id) {
        $page = HandbookCategory::find($id);
        if (!$page) {
            abort(404);
        }

        return view('admin.handbook.create_edit_handbook_category', [
            'category' => $page,
        ]);
    }

    /**
     * Creates or edits a category.
     *
     * @param App\Services\HandbookPageService $service
     * @param int|null                         $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditCategory(Request $request, HandbookPageService $service, $id = null) {
        $id ? $request->validate(HandbookCategory::$updateRules) : $request->validate(HandbookCategory::$createRules);
        $data = $request->only([
            'name', 'image', 'description', 'remove_image',
        ]);
        if ($id && $service->updateCategory(HandbookCategory::find($id), $data, Auth::user())) {
            flash('Category updated successfully.')->success();
        } elseif (!$id && $page = $service->createCategory($data, Auth::user())) {
            flash('Category created successfully.')->success();

            return redirect()->to('admin/handbooks/categories/edit/'.$page->id);
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }

    /**
     * Gets the category deletion modal.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeleteCategory($id) {
        $page = HandbookCategory::find($id);

        return view('admin.handbook._delete_handbook_category', [
            'category' => $page,
        ]);
    }

    /**
     * Deletes a category.
     *
     * @param App\Services\HandbookPageService $service
     * @param int                              $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteCategory(Request $request, HandbookPageService $service, $id) {
        if ($id && $service->deleteCategory(HandbookCategory::find($id))) {
            flash('Category deleted successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->to('admin/handbooks/categories');
    }

    /**
     * Sorts categories.
     *
     * @param App\Services\HandbookPageService $service
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSortCategory(Request $request, HandbookPageService $service) {
        if ($service->sortCategory($request->get('sort'))) {
            flash('Category order updated successfully.')->success();
        } else {
            foreach ($service->errors()->getMessages()['error'] as $error) {
                flash($error)->error();
            }
        }

        return redirect()->back();
    }
}
