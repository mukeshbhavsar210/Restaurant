<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Configuration;
use App\Models\Menu;
use App\Models\Payment;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;
use App\Models\OrderItem;
use App\Models\TableType;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
//use Intervention\Image\ImageManagerStatic as Image;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InvoiceController extends Controller implements HasMiddleware {
    public static function middleware(): array {
        return [
                
            ];
        }


    public function index(Request $request){
        $areas = Area::orderBy('area_name','ASC')->get();
        //$seats = Seat::where('area_id',NULL)->with('seat')->orderBy('table_order', 'ASC')->get();
        $tableRunning = OrderItem::with('seat')->get();

        $totalTable = DB::table('seats')
                    ->select(DB::raw('count(*) as total_tables'))
                    ->get()[0]->total_tables;

        $outletCounts = DB::table('areas')
                    ->select(DB::raw('count(*) as total_tables'))
                    ->get()[0]->total_tables;        

        $tableIndividual = DB::table('seats')
                    //->join('areas','seatings.area_id','=','areas.id')
                    ->select(DB::raw('count(*) as number'), 'area_id')
                    ->groupBy('area_id')
                    ->get()[0]->number;                
                               
        $outlets = Area::withCount('seat as total_seats')
                    ->with([
                        'seats' => function ($query) {
                            $query->orderBy('table_order', 'ASC');
                        }
                    ])
                    ->orderByRaw("CASE WHEN area_name = 'Default' THEN 0 ELSE 1 END")
                    ->get();
        
        $selectedOutletId = $outlets->where('view', 1)->first()?->id;
        $activeArea = Area::where('view', 1)->first();
        $tableTypes = TableType::with(['seats' => function ($query) use ($activeArea) {
            $query->where('area_id', $activeArea?->id);
        }])->get();

        $payments = Payment::get();                            

        $data = [
            'outlets'          => $outlets,
            'activeArea'       => $activeArea, 
            'tableTypes'       => $tableTypes,
            'payments'         => $payments,            
            'areas'            => $areas,
            // 'seats'         => $seats,
            'tableIndividual'  => $tableIndividual,
            'totalTable'       => $totalTable,
            'tableRunning'     => $tableRunning,
            'outletCounts'     => $outletCounts,
            'selectedOutletId' => $selectedOutletId,
        ];        

        $seatingCapacities = [1, 2, 4, 6, 8, 10];
        $businessTypes = [
            ['id' => 'Dinein', 'name' => 'Dinein'],
            ['id' => 'Takeaway', 'name' => 'Takeaway'],
            ['id' => 'Delivery', 'name' => 'Delivery'],
        ];
      
        $data['branchForm'] = [
            'title' => 'Create Outlet',
            'modal_id' => 'createBranchModal',            

            'formConfig' => [
                'action' => route('branch.store'),
                'method' => 'POST',
                'button' => 'Create Outlet',
                                
                'fields' => [
                    [
                        'type' => 'text',
                        'name' => 'area_name',
                        'label' => 'Outlet Name',
                        'required' => true,
                        'placeholder' => 'Outlet Name',
                        'class' => 'slug-source',
                        'data'  => [
                            'target' => '#area_slug'
                        ], 
                        'col' => 'col-md-12'
                    ],
                    [
                        'type' => 'text',
                        'name' => 'area_slug',
                        'label' => 'Slug',
                        'required' => true,
                        'id'    => 'area_slug',
                        'col' => 'd-none'
                    ],
                    [
                        'type' => 'text',
                        'name' => 'manager_name',
                        'label' => 'Manager Name',
                        'required' => true,       
                        'placeholder' => 'Manager Name',                 
                        'col' => 'col-md-12'
                    ],
                    [
                        'type' => 'text',
                        'name' => 'phone',
                        'label' => 'Phone',
                        'required' => true,       
                        'placeholder' => 'Phone',
                        'col' => 'col-md-12'
                    ],
                    [
                        'type' => 'text',
                        'name' => 'mobile',
                        'label' => 'Mobile',
                        'required' => true,       
                        'placeholder' => 'Mobile',
                        'col' => 'col-md-12'
                    ],
                    [
                        'type' => 'textarea',
                        'name' => 'address',
                        'label' => 'Address',
                        'required' => true,       
                        'placeholder' => 'Address',
                        'col' => 'col-md-12'
                    ],
                ]
            ]
        ];      
        
        $tableOrders = [];

        for ($i = 1; $i <= 10; $i++) {
            $tableOrders[] = [
                'id' => $i,
                'table_order' => $i,
            ];
        }
        
        $data['tableForm'] = [
            'title' => 'Add Table',
            'modal_id' => 'createTableModal',            

            'formConfig' => [
                'action' => route('table.store'),
                'method' => 'POST',
                'button' => 'Add Table',                
                
                'fields' => [
                    [
                        'type' => 'text',
                        'name' => 'table',
                        'label' => 'Table',
                        'required' => true,
                        'placeholder' => 'e.g. 1',
                        'col' => 'col-md-12'
                    ],
                    [
                        'type' => 'select',
                        'name' => 'area_id',
                        'label' => 'Outlet',
                        'required' => true,
                        'options' => $outlets,
                        'option_value' => 'id',
                        'option_text' => 'area_name',
                        'option_label' => 'area_name',
                        'col' => 'col-md-12'
                    ],
                    [
                        'type' => 'select',
                        'name' => 'type_id',
                        'label' => 'Type',
                        'required' => true,
                        'options' => $tableTypes,
                        'option_value' => 'id',
                        'option_text' => 'name',
                        'option_label' => 'name',
                        'col' => 'col-md-12'
                    ],
                    [
                        'type' => 'radio',
                        'name' => 'capacity',
                        'label' => 'Seat',
                        'required' => true,
                        'options' => $seatingCapacities,
                        'col' => 'col-md-12'
                    ],
                    [
                        'type' => 'select',
                        'name' => 'table_order',
                        'label' => 'Order',
                        'required' => true,
                        'options' => $tableOrders,
                        'option_value' => 'id',
                        'option_text' => 'table_order',
                        'col' => 'col-md-12'
                    ],
                ]
            ]
        ];        

        return view('admin.invoice.index', $data);        
    }


    public function pos_order(Seat $seat) {
        $categories = Category::with('products')->get();
        $config = Configuration::first();
        $seats = collect();
        if (session('branch_id')) {
            $seats = Seat::where('branch_id', session('branch_id'))->orderBy('table')->get();
        }

        $seats = Seat::where('area_id', session('area_id'))->orderBy('table_order', 'ASC')->get();

        if (!session()->has('area_id')) {
            $defaultArea = Area::where('area_name', 'Default')->first();
            if ($defaultArea) {
                session([
                    'area_id' => $defaultArea->id,
                    'area_name' => $defaultArea->area_name,
                ]);
            }
        }
        $seats = Seat::where('area_id', session('area_id'))->orderBy('table_order')->get();

        //dd(session('cart'));
        dd(session()->all());

        return view('admin.invoice.pos_order', compact(
            'seat', 'categories', 'config', 'seats'
        ));
    }


    public function branch_store(Request $request){
        $area = new Area();
        $area->manager_name = $request->manager_name;
        $area->area_name = $request->area_name;
        $area->area_slug = $request->area_slug;
        $area->phone = $request->phone;
        $area->mobile = $request->mobile;
        $area->address = $request->address;
        $area->save();

        return redirect()->route('configurations.index')->with('success','Branch added successfully.'); 
    }   

    
    
    public function branch_edit($areaId, Request $request){
        $area = Area::find($areaId);

        if (empty($area)) {
            return redirect()->route('configurations.index');
        }

        return view('admin.areas.edit', compact('area'));
    }

    public function branch_update($areaId, Request $request){
        $area = Area::find($areaId);
        if (empty($area)) {
            $request->session()->flash('error', 'area not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'area not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',            
        ]);

        if ($validator->passes()) {
            $area->name = $request->name;            
            $area->save();

            $request->session()->flash('success', 'Branch updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Branch updated successfully'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function branch_delete($id){
        $area = Area::find($id);
        $area->delete();

        return redirect()->route('configurations.index')->with('success','Branch deleted successfully.');
    }

    public function table_store(Request $request){
        //QR CODE
        $number = mt_rand(1000000000, 9999999999);        
        $request['product_code'] = $number;

         $validator = Validator::make($request->all(), [
            'area_id'    => 'required',
            'table'      => 'required',
            'capacity'   => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $table = new Seat();
        $table->area_id = $request->area_id;
        $table->type_id = $request->type_id;
        $table->table = $request->table;
        $table->capacity = $request->capacity;
        $table->table_order = $request->table_order;
        $table->save();

        return redirect()->back()->with('success', 'Table added successfully');       
    } 

    public function table_delete($id) {
        $table = Seat::find($id);

        if (!$table) {
            return redirect()->back()
                ->with('error', 'Seat not found');
        }

        $table->delete();

        return redirect()->route('configurations.index')
            ->with('success', 'Seat deleted successfully.');
    }
    
    public function table_destroy($id, Request $request){
        $subCategory = Menu::find($id);

        if(empty($subCategory)){
            $request->session()->flash('error','Record not found');
            return response([
                'status' => false,
                'notFound' => true,
            ]);
        }

        $subCategory->delete();

        $request->session()->flash('success', 'Sub Category deleted successfully');

        return response([
            'status' => true,
            'message' => 'Sub Category deleted successfully',
        ]);
    }

    public function dineinOrderStatus(Request $request, $id) {
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
    }   

    public function tablePdf($id) {
        $seat = Seat::with('area')->findOrFail($id);
        $config = Configuration::first();

        // Table URL
        $tableUrl = url('/table/'.$seat->area->area_slug.'/table-'.$seat->table);

        // Generate QR Code
        $qrCode = base64_encode(QrCode::format('png')->size(250)->margin(1)->generate($tableUrl));

        $pdf = Pdf::loadView('admin.configurations.table', compact('seat', 'config', 'qrCode', 'tableUrl'))
                ->setPaper([0, 0, 250, 350], 'portrait');

        return $pdf->download('Table-'.$seat->table_name.'.pdf');
    }


    public function branch_view(Request $request) {
        Area::query()->update(['view' => 0]);

        Area::where('id', $request->outlet_id)
            ->update(['view' => 1]);

        return back()->with('success', 'Branch View set successfully.');

    }
    
}