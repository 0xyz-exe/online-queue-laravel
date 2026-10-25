<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class QueueAudit extends Model { protected $fillable=['queue_ticket_id','user_id','action','details']; protected function casts(): array{return ['details'=>'array'];} public function user(){return $this->belongsTo(User::class);} }
