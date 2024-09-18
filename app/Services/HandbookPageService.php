<?php

namespace App\Services;

use App\Models\Handbook\HandbookCategory;
use App\Models\Handbook\HandbookPage;
use DB;

class HandbookPageService extends Service {
    /*
    |--------------------------------------------------------------------------
    | Handbook Page Service
    |--------------------------------------------------------------------------
    |
    | Handles the creation and editing of handbook pages.
    |
    */

    /**
     * Creates a site page.
     *
     * @param array                 $data
     * @param \App\Models\User\User $user
     *
     * @return \App\Models\HandbookPage|bool
     */
    public function createPage($data, $user) {
        DB::beginTransaction();

        try {
            if (isset($data['text']) && $data['text']) {
                $data['parsed_text'] = parse($data['text']);
            }
            $data['user_id'] = $user->id;
            if (!isset($data['is_visible'])) {
                $data['is_visible'] = 0;
            }

            $page = HandbookPage::create($data);

            return $this->commitReturn($page);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Updates a site page.
     *
     * @param array                 $data
     * @param \App\Models\User\User $user
     * @param mixed                 $page
     *
     * @return \App\Models\HandbookPage|bool
     */
    public function updatePage($page, $data, $user) {
        DB::beginTransaction();

        try {
            if (isset($data['text']) && $data['text']) {
                $data['parsed_text'] = parse($data['text']);
            }
            $data['user_id'] = $user->id;
            if (!isset($data['is_visible'])) {
                $data['is_visible'] = 0;
            }

            $page->update($data);

            return $this->commitReturn($page);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Deletes a site page.
     *
     * @param mixed $page
     *
     * @return bool
     */
    public function deletePage($page) {
        DB::beginTransaction();

        try {
            $page->delete();

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Sorts handbook order.
     *
     * @param array $data
     *
     * @return bool
     */
    public function sortHandbook($data) {
        DB::beginTransaction();

        try {
            // explode the sort array and reverse it since the order is inverted
            $sort = array_reverse(explode(',', $data));

            foreach ($sort as $key => $s) {
                HandbookPage::where('id', $s)->update(['sort' => $key]);
            }

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /*************************************
     *   CATEGORIES
     *************************************/

    /**
     * Creates a new category.
     *
     * @param array $data
     */
    public function createCategory($data) {
        DB::beginTransaction();

        try {
            $data = $this->populateData($data);

            $image = null;
            if (isset($data['image']) && $data['image']) {
                $image = $data['image'];
                $data['image_name'] = uniqid(true).'.'.$image->getClientOriginalExtension();
                unset($data['image']);
            } else {
                $data['image_name'] = null;
            }

            $category = HandbookCategory::create($data);

            if ($image) {
                $this->handleImage($image, $category->categoryImagePath, $category->categoryImageFileName);
            }

            return $this->commitReturn($category);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Updates a category.
     *
     * @param \App\Models\Category $category
     * @param array                $data
     * @param mixed                $user
     */
    public function updateCategory($category, $data, $user) {
        DB::beginTransaction();

        try {
            // More specific validation
            if (HandbookCategory::where('name', $data['name'])->where('id', '!=', $category->id)->exists()) {
                throw new \Exception('The name has already been taken.');
            }

            $data = $this->populateData($data, $category);

            $image = null;
            if (isset($data['image']) && $data['image']) {
                $image = $data['image'];
                $data['image_name'] = uniqid(true).'.'.$image->getClientOriginalExtension();
                unset($data['image']);
            }

            $category->update($data);

            if ($category) {
                $this->handleImage($image, $category->categoryImagePath, $category->categoryImageFileName);
            }

            return $this->commitReturn($category);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Deletes a category.
     *
     * @param mixed $category
     *
     * @return bool
     */
    public function deleteCategory($category) {
        DB::beginTransaction();

        try {
            // Check first if handbooks with this category exist
            if (HandbookPage::where('category_id', $category->id)->exists()) {
                throw new \Exception('A handbook uses this category. Please change its category first.');
            }

            if ($category->image_name) {
                $this->deleteImage($category->categoryImagePath, $category->categoryImageFileName);
            }
            $category->delete();

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Sorts category order.
     *
     * @param array $data
     *
     * @return bool
     */
    public function sortCategory($data) {
        DB::beginTransaction();

        try {
            // explode the sort array and reverse it since the order is inverted
            $sort = array_reverse(explode(',', $data));

            foreach ($sort as $key => $s) {
                HandbookCategory::where('id', $s)->update(['sort' => $key]);
            }

            return $this->commitReturn(true);
        } catch (\Exception $e) {
            $this->setError('error', $e->getMessage());
        }

        return $this->rollbackReturn(false);
    }

    /**
     * Processes user input for creating/updating a category.
     *
     * @param array      $data
     * @param mixed|null $category
     *
     * @return array
     */
    private function populateData($data, $category = null) {
        if (isset($data['description']) && $data['description']) {
            $data['parsed_description'] = parse($data['description']);
        }

        if (isset($data['remove_image'])) {
            if ($category && $category->image_name && $data['remove_image']) {
                $data['image_name'] = null;
                $this->deleteImage($category->categoryImagePath, $category->categoryImageFileName);
            }
            unset($data['remove_image']);
        }

        return $data;
    }
}
