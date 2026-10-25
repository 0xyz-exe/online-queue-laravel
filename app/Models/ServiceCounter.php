<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ServiceCounter extends Model { protected $fillable=['service_location_id','name','code','active']; public function location(){return $this->belongsTo(ServiceLocation::class,'service_location_id');} }
