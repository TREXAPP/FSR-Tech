<?php

namespace FSR\Http\Controllers\Admin;

use FSR;
use FSR\File;
use FSR\Listing;
use FSR\Product;
use FSR\FoodType;
use FSR\Location;
use FSR\Volunteer;
use FSR\ListingOffer;
use FSR\Organization;
use FSR\Custom\Methods;
use FSR\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:master_admin,admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filters = [
        'food_type' => '0'
      ];
        $food_types = FoodType::where('status', 'active')->get();
        $products = Product::where('status', 'active')->get();
        return $this->return_view($food_types, $products, $filters);
    }
    /**
     * return view
     *
     * @param FoodType
     * @param Product
     * @param Integer
     * @return \Illuminate\Http\Response
     */
    protected function return_view($food_types, $products, $filters)
    {
        return view('admin.products')->with([
        'filters' => $filters,
        'products' => $products,
        'food_types' => $food_types,
      ]);
    }

    public function handle_post(Request $request)
    {
        $data = $request->all();
        if (!empty($data['post-type'])) {
            switch ($data['post-type']) {
          case 'filter':
            if ($data['food-types-filter-select']) {
                return $this->handle_filter($data);
            } else {
                return $this->index();
            }

          break;
          case 'delete':
            return $this->handle_delete($request->all());
            break;
          default:
            return $this->index();
          break;
        }
        }
    }

    /**
     * handle post from filter
     *
     * @param Array $data
     * @return \Illuminate\Http\Response
     */
    protected function handle_filter(array $data)
    {
        $filters = [
        'food_type' => $data['food-types-filter-select']
      ];
        $food_types = FoodType::where('status', 'active')->get();
        $products = Product::where('food_type_id', $data['food-types-filter-select'])
                           ->where('status', 'active')->get();
        return $this->return_view($food_types, $products, $filters);
    }


    /**
     * Handle offer listing "delete". (it is actually update)
     *
     * @param  Array $data
     * @return \Illuminate\Http\Response
     */
    public function handle_delete(array $data)
    {
        $product = $this->delete($data);
        return back()->with('status', "Производот е успешно избришан!");
    }

    /**
     * Mark the selected location as cancelled
     *
     * @param  array  $data
     * @return \FSR\Product
     */
    protected function delete(array $data)
    {
        $product = Product::find($data['product_id']);
        $product->status = 'deleted';
        $product->save();
        return $product;
    }
}
