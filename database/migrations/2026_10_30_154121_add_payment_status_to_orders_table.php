<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_status', 30)->default('unpaid')->after('status');
        });

        // Sinkronisasi data awal pesanan yang sudah ada:
        // 1. Pesanan non-COD yang statusnya processing/dikemas/shipped/dikirim/completed/selesai dianggap 'paid'
        DB::table('orders')
            ->where(function ($query) {
                $query->whereNull('payment_method')
                    ->orWhere(function ($q) {
                        $q->whereRaw('LOWER(payment_method) NOT LIKE ?', ['%cod%'])
                            ->whereRaw('LOWER(payment_method) NOT LIKE ?', ['%cash on delivery%'])
                            ->whereRaw('LOWER(payment_method) NOT LIKE ?', ['%bayar di tempat%']);
                    });
            })
            ->whereIn('status', ['processing', 'dikemas', 'shipped', 'dikirim', 'completed', 'selesai'])
            ->update(['payment_status' => 'paid']);

        // 2. Pesanan COD yang statusnya completed/selesai diubah menjadi 'paid'
        DB::table('orders')
            ->where(function ($query) {
                $query->whereRaw('LOWER(payment_method) LIKE ?', ['%cod%'])
                    ->orWhereRaw('LOWER(payment_method) LIKE ?', ['%cash on delivery%'])
                    ->orWhereRaw('LOWER(payment_method) LIKE ?', ['%bayar di tempat%']);
            })
            ->whereIn('status', ['completed', 'selesai'])
            ->update(['payment_status' => 'paid']);

        // 3. Pesanan yang dibatalkan menjadi 'cancelled'
        DB::table('orders')
            ->whereIn('status', ['cancelled', 'dibatalkan'])
            ->update(['payment_status' => 'cancelled']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_status');
        });
    }
};
