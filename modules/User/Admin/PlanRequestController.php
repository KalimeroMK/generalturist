<?php
namespace Modules\User\Admin;

use Illuminate\Http\Request;
use Modules\AdminController;
use Modules\Gig\Models\GigCategory;
use Modules\Gig\Models\GigCategoryTranslation;
use Modules\User\Models\Plan;
use Modules\User\Models\PlanPayment;
use Modules\User\Models\PlanTranslation;
use Modules\User\Models\UserPlan;

class PlanRequestController extends AdminController
{
    protected $planClass;
    protected $userPlanClass;
    public function __construct()
    {
        $this->setActiveMenu(route('user.admin.plan.index'));
        $this->userPlanClass = UserPlan::class;
        $this->planClass = Plan::class;
    }


    public function index(){
        $query = PlanPayment::query();
        $query->where('object_model','plan')->with('plan')->orderBy('id','desc');
        if($user_id = request()->query('user_id'))
        {
            $query->where('object_id',$user_id);
        }

        $data = [
            'rows'=>$query->paginate(20),
            'page_title'=>__("Plan request management"),
            'breadcrumbs'=>[
                [
                    'url'=>route('user.admin.index'),
                    'name'=>__("Users"),
                ],
                [
                    'url'=>'#',
                    'name'=>__('Plan request management'),
                ],
            ]
        ];
        return view("User::admin.plan-request.index",$data);
    }

    public function bulkEdit(Request $request){
        $ids = $request->input('ids');
        $action = $request->input('action');
        if (empty($ids))
            return redirect()->back()->with('error', __('Select at lease 1 item!'));
        if (empty($action))
            return redirect()->back()->with('error', __('Select an Action!'));
        if ($action == 'delete') {
//            foreach ($ids as $id) {
//                if($id == Auth::id()) continue;
//                $query = User::where("id", $id)->first();
//                if(!empty($query)){
//                    $query->email.='_d';
//                    $query->save();
//                    $query->delete();
//                }
//            }
        } else {
            foreach ($ids as $id) {
                switch ($action){
                    case "completed":
                        $payment = PlanPayment::find($id);
                        if($payment->payment_gateway == 'offline_payment' and $payment->status == 'processing'){
                            $payment->markAsCompleted();
                        }
                    break;
                }
            }
        }
        return redirect()->back()->with('success', __('Updated successfully!'));
    }
}
