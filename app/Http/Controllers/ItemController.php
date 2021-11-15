<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api,custom-jwt')->except(["index", "show", 'getComments']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Item::query();
        $orderBy = $request->order_by ?? "ratings_average";
        $order = $request->order ?? "DESC";

        if ($request->has("city_id")) {
            $query->where("city_id", $request->city_id);
        }
        if ($request->has("is_featured")) {
            $query->where("is_featured", true);
        }
        if ($request->has("s")) {
            $pattern = "%" . $request->s . "%";
            $query->where("name", "LIKE", $pattern);
        }

        if ($request->has("categories")) {
            $cats = array_map('intval', explode(",", $request->categories));
            $query->whereHas("categories", function ($query) use ($cats) {
                return $query->whereIn("categories.id", $cats);
            });
        }

        $query->leftJoin('comments', 'comments.item_id', '=', 'items.id')
            ->select(array(
                'items.*',
                DB::raw('AVG(rating) as ratings_average')
            ))
            ->groupBy('items.id')
            ->orderBy($orderBy, $order);

        return response()->json($query->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $request->validate([
            "name" => ['required'],
            "description" => ['required'],
            "address" => ['required'],
            "city_id" => ['required'],
            "image" => ['required', 'file'],
            'website' => [],
            'facebook' => [],
            "ig" => [],
            "phone" => [],
            "categories" => ['sometimes', 'array']
        ]);

        $data['user_id'] = auth()->user()->id;

        $item = Item::query()->create($data);
        uploadImage($request, $item);

        if (!empty($request->categories)) {

            $cats = array_map('intval', $request->categories);
            $item->categories()->sync($cats);
        }
        $item->refresh();

        return response()->json($item);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return response()->json($item->load(['user', 'categories', 'city']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {


        $item->update($request->all());
        uploadImage($request, $item);

        if (!empty($request->categories)) {
            $cats = array_map('intval', $request->categories);
            $item->categories()->sync($cats);
        }
        $item->refresh();

        return response()->json($item);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return response()->json($item);
    }

    public function getComments(Item $item)
    {

        return response()->json($item->comments()->get());
    }

    public function addComment(Request $request, Item $item)
    {
        $data = $request->validate([
            "rating" => "required",
            'comment' => [],
            "image" => ['sometimes', 'file']
        ]);

        $data['user_id'] = auth()->user()->id;
        $data['item_id'] = $item->id;
        $comment = Comment::query()->create($data);

        uploadImage($request, $comment);

        $item->comments()->save($comment);

        return response()->json($comment);
    }
    public function updateComment(Request $request, Item $item, $commentId)
    {
        $comment  = Comment::query()->findOrFail($commentId);
        $comment->update($request->all());
        uploadImage($request, $comment);
        return response()->json($comment);
    }
    public function deleteComment(Item $item, $commentId)
    {
        $comment  = Comment::query()->findOrFail($commentId);
        $comment->delete();
        return response()->json($comment);
    }
}
