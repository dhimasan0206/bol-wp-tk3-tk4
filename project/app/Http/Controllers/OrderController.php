<?php

namespace App\Http\Controllers;

use App\Http\Requests\CancelOrderRequest;
use App\Http\Requests\FulfillOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $heads = [
            'ID',
            'Date',
            'Status',
            'Total Items',
            'Total Price',
            'Customer',
            'Actions',
        ];

        $btnFulfill = '<form action=":route" method="POST">'.csrf_field().'<button>Fulfill</button></form>';

        $btnCancel = '<form action=":route" method="POST">'.csrf_field().'<button>Cancel</button></form>';
        
        $data = [];
        $orders = [];
        if ($request->user()->hasRole('customer')) {
            $orders = Order::where('user_id', '=', Auth::id())->get();
        } elseif ($request->user()->hasPermissionTo('manage order')) {
            $orders = Order::all();
        }
        foreach ($orders as $order) {
            $actions = '';
            if ($order->fulfillable() && $request->user()->hasPermissionTo('manage order')) {
                $actions .= str_replace(":route", route('orders.fulfill', ['order' => $order->id]), $btnFulfill);
            }
            if ($order->cancellable() && $order->user_id == Auth::id()) {
                $actions .= str_replace(":route", route('orders.cancel', ['order' => $order->id]), $btnCancel);
            }
            $data[] = [
                $order->id,
                $order->order_date,
                $order->status,
                $order->totalItems(),
                $order->totalPrice(),
                $order->customer->name,
                $actions,
            ];
        }

        $config = [
            'data' => $data,
            // 'order' => [[1, 'asc']],
            // 'columns' => [null, null, null, ['orderable' => false]],
        ];
        return view('orders.index', compact('heads', 'config'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $heads = [
            'ID',
            'Image',
            'Name',
            'Description',
            'Type',
            'Stock',
            'Quantity',
            'Price',
            'Subtotal',
            'Available',
            'Actions'
        ];
        
        $btnAdd = '<form action=":route" method="POST">
                        '.csrf_field().'
                        <input type="hidden" name="product_id" value=":product_id">
                        <input type="hidden" name="delta" value="1">
                        <button type="submit" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Add to Cart">
                            <i class="fa fa-lg fa-fw fa-plus"></i>
                        </button>
                    </form>';
        $btnSubstract = '<form action=":route" method="POST">
                    '.csrf_field().'
                    <input type="hidden" name="product_id" value=":product_id">
                    <input type="hidden" name="delta" value="-1">
                    <button type="submit" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Add to Cart">
                        <i class="fa fa-lg fa-fw fa-minus"></i>
                    </button>
                </form>';
        $btnDelete = '<form action=":route" method="POST">
            '.csrf_field()
            .method_field('DELETE').'
            <button type="submit" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Add to Cart">
                <i class="fa fa-lg fa-fw fa-trash"></i>
            </button>
        </form>';
        $data = [];
        $totalQuantity = 0;
        $totalPrice = 0;
        $unavailableItems = [];
        foreach (User::find(Auth::id())->cartItems as $cartItem) {
            $product = $cartItem->product;
            
            $subtotal = $cartItem->quantity * $product->selling_price;
            $totalQuantity += $cartItem->quantity;
            $totalPrice += $subtotal;

            $available = $product->stock >= $cartItem->quantity;
            if (!$available) {
                array_push($unavailableItems, $product);
            }

            $data[] = [
                $product->id,
                '<img src="'.Storage::url($product->image_url).'" alt="'.Storage::url($product->image_url).'" width="100">',
                $product->name,
                $product->description,
                $product->type,
                $product->stock,
                $cartItem->quantity,
                $product->selling_price,
                $subtotal,
                $available ? "true" : "false: reduce quantity or remove item",
                $available ? 
                    str_replace(":product_id", $product->id, str_replace(":route", route("carts.store"), $btnSubstract)).
                    str_replace(":product_id", $product->id, str_replace(":route", route("carts.store"), $btnAdd)).
                    str_replace(":route", route("carts.destroy", ['cart' => $cartItem->id]), $btnDelete)
                    :
                    str_replace(":product_id", $product->id, str_replace(":route", route("carts.store"), $btnSubstract)).
                    str_replace(":route", route("carts.destroy", ['cart' => $cartItem->id]), $btnDelete)
                ,
            ];
        }
        $config = [
            'data' => $data,
        ];
        return view('orders.create', compact('heads', 'config', 'totalQuantity', 'totalPrice', 'unavailableItems'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request)
    {
        DB::beginTransaction();

        $order = new Order();
        $order->user_id = Auth::id();
        $order->order_date = now();
        $order->status = 'PENDING';
        if (!$order->save()) {
            DB::rollBack();
            return redirect()->back()->withErrors('Checkout failed on create order');
        }

        foreach (User::find(Auth::id())->cartItems as $cartItem) {
            $product = $cartItem->product;
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $product->id;
            $orderItem->image_url = $product->image_url;
            $orderItem->name = $product->name;
            $orderItem->description = $product->description;
            $orderItem->type = $product->type;
            $orderItem->price = $product->selling_price;
            $orderItem->quantity = $cartItem->quantity;
            if (!$orderItem->save()) {
                DB::rollBack();
                return redirect()->back()->withErrors('Checkout failed on add order item');
            }
        }

        $deleted = DB::delete('delete from carts where user_id = ?', [Auth::id()]);
        if (!$deleted) {
            DB::rollBack();
            return redirect()->back()->withErrors('Checkout failed on removing cart items');
        }

        DB::commit();

        return to_route('orders.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrderRequest  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    /**
     * Cancel the specified resource in storage.
     *
     * @param  \App\Http\Requests\CancelOrderRequest  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function cancel(CancelOrderRequest $request, Order $order) {
        if (!$order->cancellable() || $order->user_id != Auth::id()) {
            abort(403);
        }
        $order->status = 'CANCELLED';
        $order->save();
        return redirect()->back();
    }

    /**
     * Fulfill the specified resource in storage.
     *
     * @param  \App\Http\Requests\FulfillOrderRequest  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function fulfill(FulfillOrderRequest $request, Order $order) {
        if (!$order->fulfillable()) {
            abort(403);
        }
        DB::beginTransaction();
        $order->status = 'FULFILLED';
        $order->fulfilled_at = now();
        if (!$order->save()) {
            DB::rollBack();
            return redirect()->back()->withErrors('Fulfill order failed on update order');
        }
        foreach ($order->items as $item) {
            $item->product->stock -= $item->quantity;
            if (!$item->product->save()) {
                return redirect()->back()->withErrors('Fulfill order failed on reduce product stock');
            }
        }
        DB::commit();
        return redirect()->back();
    }

    public function dashboard()
    {
        $all_status = [];
        $all_count = [];
        foreach (DB::table('orders')->select(DB::raw('count(*) as order_count, status'))->groupBy('status')->get() as $order) {
            array_push($all_status, $order->status);
            array_push($all_count, $order->order_count);
        }

        $daily_status = [];
        $daily_count = [];
        foreach (DB::table('orders')->select(DB::raw('count(*) as order_count, status'))->whereRaw('DATE(order_date) = CURRENT_DATE()')->groupBy('status')->get() as $order) {
            array_push($daily_status, $order->status);
            array_push($daily_count, $order->order_count);
        }

        $weekly_status = [];
        $weekly_count = [];
        foreach (DB::table('orders')->select(DB::raw('count(*) as order_count, status'))->whereRaw('DATE(order_date) <= CURRENT_DATE() AND YEAR(order_date) = YEAR(CURRENT_DATE()) AND WEEK(order_date) = WEEK(CURRENT_DATE())')->groupBy('status')->get() as $order) {
            array_push($weekly_status, $order->status);
            array_push($weekly_count, $order->order_count);
        }

        $monthly_status = [];
        $monthly_count = [];
        foreach (DB::table('orders')->select(DB::raw('count(*) as order_count, status'))->whereRaw('DATE(order_date) <= CURRENT_DATE() AND YEAR(order_date) = YEAR(CURRENT_DATE()) AND MONTH(order_date) = MONTH(CURRENT_DATE())')->groupBy('status')->get() as $order) {
            array_push($monthly_status, $order->status);
            array_push($monthly_count, $order->order_count);
        }

        $quarterly_status = [];
        $quarterly_count = [];
        foreach (DB::table('orders')->select(DB::raw('count(*) as order_count, status'))->whereRaw('DATE(order_date) <= CURRENT_DATE() AND YEAR(order_date) = YEAR(CURRENT_DATE()) AND QUARTER(order_date) = QUARTER(CURRENT_DATE())')->groupBy('status')->get() as $order) {
            array_push($quarterly_status, $order->status);
            array_push($quarterly_count, $order->order_count);
        }

        $yearly_status = [];
        $yearly_count = [];
        foreach (DB::table('orders')->select(DB::raw('count(*) as order_count, status'))->whereRaw('DATE(order_date) <= CURRENT_DATE() AND YEAR(order_date) = YEAR(CURRENT_DATE())')->groupBy('status')->get() as $order) {
            array_push($yearly_status, $order->status);
            array_push($yearly_count, $order->order_count);
        }

        return view('orders.dashboard', compact(
            'all_status',
            'all_count',
            'daily_status',
            'daily_count',
            'weekly_status',
            'weekly_count',
            'monthly_status',
            'monthly_count',
            'quarterly_status',
            'quarterly_count',
            'yearly_status',
            'yearly_count',
        ));
    }
}
