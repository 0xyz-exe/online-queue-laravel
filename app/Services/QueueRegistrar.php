<?php
namespace App\Services;
use App\Models\QueueAudit;
use App\Models\QueueTicket;
use App\Models\ServiceCategory;
use App\Models\ServiceLocation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
class QueueRegistrar {
 public function register(ServiceLocation $location, ServiceCategory $category, ?User $user, ?string $name, ?string $phone): QueueTicket {
  return DB::transaction(function() use($location,$category,$user,$name,$phone){
   if(!$location->active||!$category->active||$category->service_location_id!==$location->id) throw ValidationException::withMessages(['category'=>'Service is unavailable.']);
   $date=now()->toDateString();
   DB::table('daily_queue_sequences')->insertOrIgnore(['service_location_id'=>$location->id,'service_category_id'=>$category->id,'queue_date'=>$date,'last_number'=>0,'created_at'=>now(),'updated_at'=>now()]);
   $seq=DB::table('daily_queue_sequences')->where(['service_location_id'=>$location->id,'service_category_id'=>$category->id,'queue_date'=>$date])->lockForUpdate()->first();
   $number=$seq->last_number+1;
   DB::table('daily_queue_sequences')->where('id',$seq->id)->update(['last_number'=>$number,'updated_at'=>now()]);
   $ticket=QueueTicket::create(['service_location_id'=>$location->id,'service_category_id'=>$category->id,'user_id'=>$user?->id,'queue_date'=>$date,'daily_number'=>$number,'display_number'=>$category->prefix.'-'.str_pad((string)$number,3,'0',STR_PAD_LEFT),'lookup_token'=>Str::random(48),'customer_name'=>$name,'customer_phone'=>$phone,'status'=>'waiting']);
   QueueAudit::create(['queue_ticket_id'=>$ticket->id,'user_id'=>$user?->id,'action'=>'registered','details'=>['status'=>'waiting']]);
   return $ticket;
  },5);
 }
}
