<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\DislikedShops;
use App\Shop;
use App\User;
use Auth;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function __construct()
    {
        // authentication middleware
        $this->middleware('jwt.auth');
    }

    /**
     * @api {get} /shops/nearbyShops
     * @apiName getNearbyShops
     * @apiDescription  get all nearby shops
     * @apiParam  {longitude, latitude} coordinates optional
     *
     */
    public function getNearbyShops(Request $request)
    {
        $user = Auth::user();
        if ($request->has('longitude') && $request->has('latitude')) {
            $nearbyShops = Shop::whereDoesntHave('beenDisliked')
                ->where('location', 'near', [
                    '$geometry' => [
                        'type' => 'Point',
                        'coordinates' => [
                            (double) $request->get('longitude'),
                            (double) $request->get('latitude'),
                        ],
                    ],
                ])->where('users_liked_ids', '<>', $user->_id)->get();

            return response()->json([
                'message' => 'All nearby shops',
                'nearbyShops' => $nearbyShops,
            ], 200);
        } else {
            $nearbyShops = Shop::whereDoesntHave('beenDisliked')
                ->where('users_liked_ids', '<>', $user->_id)->get();
            return response()->json([
                'message' => 'All nearby shops',
                'nearbyShops' => $nearbyShops,
            ], 200);
        }
    }

    /**
     * @api {get} /shops/preferredShops
     * 
     * @apiName getPreferredShops
     * 
     * @apiDescription get all preferred shops
     * 
     * 
     */
    public function getPreferredShops()
    {
        $user = Auth::user();
        $preferredShops = $user->preferredShops()->get();

        return response()->json([
            'message' => 'All preferred shops!',
            'preferredShops' => $preferredShops,
        ], 200);
    }

    /**
     * @api {post} /shops/likeShop
     * 
     * @apiName attachShop
     * 
     * @apiDescription  add shop to the user's preferred shops
     * @apiParam  {String} shop_id
     *
     */
    public function attachShop(Request $request)
    {
        $user = Auth::user();
        $shop = Shop::findOrFail($request->get('_id'));

        $user->preferredShops()->save($shop);
        return response()->json([
            'message' => 'shop attached successfully',
        ], 201);
    }

    /**
     * @api {post} /shops/separateShop
     * 
     * @apiName detachShop
     * @apiDescription  remove shop from the user's favorite shops
     * @apiParam  {String} shop_id
     * 
     *
     */
    public function detachShop(Request $request)
    {
        $user = Auth::user();

        $shop = Shop::findOrFail($request->get('_id'));

        $user->preferredShops()->detach($shop);

        return response()->json([
            'message' => 'shop ' . $shop->name . ' has been successfully detached',
        ], 200);
    }

    /**
     * @api {post} /shops/dislikeShop
     * 
     * @apiName dislikeShop
     * 
     * @apiDescription dislike shop for 2 hours
     * 
     * @apiParam  {String} shop_id
     */
    public function dislikeShop(Request $request)
    {
        $user = Auth::user();
        $shop = Shop::findOrFail($request->get('_id'));
        $dislikedShop = new DislikedShops(['shop_id' => $shop->_id]);
        $dislikedShop->save();
        $user->dislikedShops()->save($dislikedShop);
        return response()->json([
          'message' => 'shop ' . $shop->name . ' has been successfully disliked :p',
      ], 200);
    }
}
