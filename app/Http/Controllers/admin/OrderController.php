<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Seat;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller {
    public function index(Request $request){
        $orders = Order::with(['items', 'seat', 'items.product.product_images'])            
            ->latest('orders.created_at')
            ->get();

        // Order counts
        $totalOrders = Order::count();
        $dineinOrders = Order::where('order_type', 'Dinein')->latest()->paginate(10, ['*'], 'dinein_page');
        $takeawayOrders = Order::where('order_type', 'Takeaway')->latest()->paginate(10, ['*'], 'takeaway_page');
        $deliveryOrders = Order::where('order_type', 'Delivery')->latest()->paginate(10, ['*'], 'delivery_page');

        $data = [
            'orders' => $orders,
            'totalOrders' => $totalOrders,  
            'dineinOrders' => $dineinOrders,
            'takeawayOrders' => $takeawayOrders,
            'deliveryOrders' => $deliveryOrders,
        ];

        return view('admin.orders.list', $data);
    }

    

    public function detail($orderId){
        $order = Order::with('seat')->findOrFail($orderId);
        $orderItems = OrderItem::where('order_id',$orderId)->get();  
        $products = Product::latest('id');      

        return view('admin.orders.detail',[
            'order' => $order,
            'orderItems' => $orderItems,
            'products' => $products,
        ]);
    }


    public function changeOrderStatus(Request $request, $id){
        $order = Order::find($id);
        $order->status = $request->status;
        $order->shipped_date = $request->shipped_date;
        $order->save();

        $message = 'Order status updated successfully';

        session()->flash('success',$message);

        return response()->json([
            'status' => true,
            'message' => $message,
        ]);
    }

    public function sendInvoiceEmail(Request $request, $orderId){
        orderEmail($orderId, $request->userType);

        $message = 'Order email sent successfully';

        session()->flash('success',$message);

        return response()->json([
            'status' => true,
            'message' => $message,
        ]);
    }
}
