<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
  Schema::create('service_locations',function(Blueprint $t){$t->id();$t->string('name');$t->string('slug')->unique();$t->boolean('active')->default(true);$t->timestamps();});
  Schema::create('service_counters',function(Blueprint $t){$t->id();$t->foreignId('service_location_id')->constrained()->cascadeOnDelete();$t->string('name');$t->string('code',8);$t->boolean('active')->default(true);$t->timestamps();$t->unique(['service_location_id','code']);});
  Schema::create('service_categories',function(Blueprint $t){$t->id();$t->foreignId('service_location_id')->constrained()->cascadeOnDelete();$t->string('name');$t->string('prefix',4);$t->boolean('active')->default(true);$t->timestamps();$t->unique(['service_location_id','prefix']);});
  Schema::table('users',function(Blueprint $t){$t->string('role')->default('customer');});
  Schema::create('daily_queue_sequences',function(Blueprint $t){$t->id();$t->foreignId('service_location_id')->constrained()->cascadeOnDelete();$t->foreignId('service_category_id')->constrained()->cascadeOnDelete();$t->date('queue_date');$t->unsignedInteger('last_number')->default(0);$t->timestamps();$t->unique(['service_location_id','service_category_id','queue_date'],'queue_sequence_unique');});
  Schema::create('queue_tickets',function(Blueprint $t){$t->id();$t->foreignId('service_location_id')->constrained()->restrictOnDelete();$t->foreignId('service_category_id')->constrained()->restrictOnDelete();$t->foreignId('service_counter_id')->nullable()->constrained()->nullOnDelete();$t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();$t->date('queue_date');$t->unsignedInteger('daily_number');$t->string('display_number',16);$t->string('lookup_token',64)->unique();$t->string('customer_name',120)->nullable();$t->string('customer_phone',30)->nullable();$t->string('status')->default('waiting');$t->timestamp('called_at')->nullable();$t->timestamp('serving_at')->nullable();$t->timestamp('completed_at')->nullable();$t->timestamps();$t->unique(['service_location_id','service_category_id','queue_date','daily_number'],'queue_ticket_number_unique');$t->index(['service_location_id','queue_date','status']);});
  Schema::create('queue_audits',function(Blueprint $t){$t->id();$t->foreignId('queue_ticket_id')->constrained()->cascadeOnDelete();$t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();$t->string('action');$t->json('details')->nullable();$t->timestamps();});
 }
 public function down(): void {Schema::dropIfExists('queue_audits');Schema::dropIfExists('queue_tickets');Schema::dropIfExists('daily_queue_sequences');Schema::table('users',fn(Blueprint $t)=>$t->dropColumn('role'));Schema::dropIfExists('service_categories');Schema::dropIfExists('service_counters');Schema::dropIfExists('service_locations');}
};
