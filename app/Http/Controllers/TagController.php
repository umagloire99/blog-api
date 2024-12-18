<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Traits\ApiResponse;
use App\Http\Requests\TagRequest;

/**
 * @group Tags
 *
 * APIs for Tag.
 */
class TagController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the tags.
     *
     * @queryParam page integer The page number for pagination. Example: 2
     * @queryParam per_page integer Number of results per page. Defaults to 10. Example: 5
     * @queryParam search string Search term to filter blog posts. Example: Laravel
     */
    public function index(Request $request)
    {
        $tags = Tag::search($request->search)->paginate();

        return $this->success(
            'Tags successully retrieved.',
            $tags
        );
    }

    /**
     * Store a newly created tag in storage.
     *
     * @authenticated
     */
    public function store(TagRequest $request)
    {
        $tag = Tag::create(['name' => $request->name]);

        return $this->success(
            'Tag has been successfully created.',
            $tag
        );
    }

    /**
     * Update the specified tag in storage.
     * @authenticated
     */
    public function update(TagRequest $request, Tag $tag)
    {
        $tag->update(['name' => $request->name]);

        return $this->success(
            'Tag has been successfully updated.',
            $tag
        );
    }

    /**
     * Remove the specified tag from storage.
     * @authenticated
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return $this->success(
            'Tag has been successfully deleted.'
        );
    }
}
