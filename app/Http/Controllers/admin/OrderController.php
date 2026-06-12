<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Configuration;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Seat;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller {
    public function index(Request $request){
        $orders = Order::with(['items', 'seat', 'items.product.product_images'])->latest('orders.created_at')->get();

        // Order counts
        $totalOrders = Order::count();
        $dineinOrders = Order::where('order_type', 'Dinein')->latest()->paginate(10, ['*'], 'dinein_page');
        $takeawayOrders = Order::where('order_type', 'Takeaway')->latest()->paginate(10, ['*'], 'takeaway_page');
        $deliveryOrders = Order::where('order_type', 'Delivery')->latest()->paginate(10, ['*'], 'delivery_page');
        $config = Configuration::first();
        
        $statuses = Order::pluck('status');
        $running = Order::where('status', 'running')->get();
        $pending = Order::where('status', 'pending')->get();
        $placed = Order::where('status', 'placed')->get();
        $shipped = Order::where('status', 'shipped')->get();
        $delivered = Order::where('status', 'delivered')->get();

        $data = [
            'orders' => $orders,
            'config' => $config,
            'statuses' => $statuses,
            'running' => $running,
            'pending' => $pending,
            'placed' => $placed,
            'shipped' => $shipped,
            'delivered' => $delivered,
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
        $config = Configuration::first();          
      
        return view('admin.orders.detail',[
            'order' => $order,
            'orderItems' => $orderItems,
            'products' => $products,
            'config' => $config,
        ]);
    }


    public function changeOrderStatus(Request $request, $id){
        $order = Order::find($id);
        $order->status = $request->status;
        $order->shipped_date = $request->shipped_date;
        $order->save();        

        try {
            Seat::where('id', $id)->update([
                'status' => $request->status
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Status Updated'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ], 500);
        }

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

    public function invoicePdf($id){
        $order = Order::with(['items.product'])->findOrFail($id);
        $config = Configuration::first();
        $pdf = Pdf::loadView('admin.orders.invoice', compact('order','config'))->setPaper([0, 0, 275, 500], 'portrait');

        return $pdf->download(
            'invoice_'.$order->id.
            '_table_'.$order->seat?->table.
            '_'.$order->seat?->area?->area_name.            
            '.pdf'
        );
    }
}