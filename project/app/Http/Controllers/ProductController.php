<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct() {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Gate::authorize('viewAny', Product::class);
        $heads = [
            'ID',
            'Image',
            'Name',
            'Description',
            'Type',
            'Stock',
            'Buying Price',
            'Selling Price',
            'Created At',
            'Updated At',
            'Actions'
        ];
        
        $btnAddToCart = '<a href=":route" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Add to Cart">
                        <i class="fa fa-lg fa-fw fa-cart-plus"></i>
                    </a>';
        $btnEdit = '<a href=":route" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                        <i class="fa fa-lg fa-fw fa-pen"></i>
                    </a>';
        $btnDelete = '<form action=":route" method="POST">
            '.csrf_field().'
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete">
                <i class="fa fa-lg fa-fw fa-trash"></i>
            </button>
        </form>';
        $btnDetails = '<a href=":route" class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details"><i class="fa fa-lg fa-fw fa-eye"></i></a>';
        $data = [];
        foreach (Product::all() as $product) {
            $data[] = [
                $product->id,
                '<img src="'.Storage::url($product->image_url).'" alt="'.Storage::url($product->image_url).'" width="100">',
                $product->name,
                $product->description,
                $product->type,
                $product->stock,
                $product->buying_price,
                $product->selling_price,
                $product->created_at,
                $product->updated_at,
                // str_replace(":route", "#", $btnAddToCart).
                str_replace(":route", route("products.edit", ["product" => $product->id]), $btnEdit).
                str_replace(":route", route("products.show", ["product" => $product->id]), $btnDetails).
                str_replace(":route", route("products.destroy", ["product" => $product->id]), $btnDelete),
            ];
        }
        $config = [
            'data' => $data,
            // 'order' => [[1, 'asc']],
            // 'columns' => [null, null, null, ['orderable' => false]],
        ];
        return view('products.index', compact('heads', 'config'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Gate::authorize('create', Product::class);
        
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        Gate::authorize('create', Product::class);

        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->type = $request->type;
        $product->stock = $request->stock;
        $product->buying_price = $request->buying_price;
        $product->selling_price = $request->selling_price;
        $product->image_url = $request->file('image')->store('images', 'public');
        if (!$product->save()) {
            return redirect()->back()->withErrors('add product failed')->withInput();
        }

        return to_route('products.show', ['product' => $product->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        Gate::authorize('view', $product);

        $product->image_url = Storage::url($product->image_url);

        return view('products.show', ['product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        Gate::authorize('update', $product);

        return view('products.edit', ['product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        Gate::authorize('update', $product);

        $product->name = $request->name;
        $product->description = $request->description;
        $product->type = $request->type;
        $product->stock = $request->stock;
        $product->buying_price = $request->buying_price;
        $product->selling_price = $request->selling_price;
        $old = $product->image_url;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $product->image_url = $request->file('image')->store('images', 'public');
        }
        if (!$product->save()) {
            return redirect()->back()->withErrors('update '.$product->id.' failed')->withInput();
        }
        if ($request->hasFile('image') && $request->file('image')->isValid() && $old != "") {
            Storage::disk('public')->delete($old);
        }

        return to_route('products.show', ['product' => $product]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        Gate::authorize('delete', $product);

        if (!$product->delete()) {
            return redirect()->back()->withErrors('delete '.$product->id.' failed')->withInput();
        }

        return to_route('products.index');
    }
}
