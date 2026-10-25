<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ServiceLocation extends Model { protected $fillable=['name','slug','active']; public function categories(){return $this->hasMany(ServiceCategory::class);} public function counters(){return $this->hasMany(ServiceCounter::class);} }
