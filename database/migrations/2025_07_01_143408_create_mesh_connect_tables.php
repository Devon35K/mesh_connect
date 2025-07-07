<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Users Table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->index();
            $table->string('password');
            $table->string('profile_image')->nullable();
            $table->string('recovery_token')->nullable(); // For staff-mediated password recovery
            $table->enum('role', ['admin', 'staff', 'guest'])->default('guest')->index();
            $table->timestamps();

            // Indexes for faster analytics queries
            $table->index('created_at');
        });

        // Coupons Table
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('coupon_code')->unique()->index();
            $table->integer('total_minutes')->default(0);
            $table->integer('used_minutes')->default(0); // Track consumed time
            $table->unsignedBigInteger('used_by')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->dateTime('expires_at')->nullable(); // Support time-limited coupons
            $table->timestamps();

            $table->foreign('used_by')->references('id')->on('users')->onDelete('set null');
        });

        // Sessions Table
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('coupon_id')->index();
            $table->dateTime('start_time')->index();
            $table->dateTime('end_time')->nullable();
            $table->enum('status', ['active', 'paused', 'completed', 'expired'])->default('active')->index();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade');
        });

        // Transactions Table
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id')->index();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('PHP'); // Support multiple currencies
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending')->index();
            $table->dateTime('paid_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
            $table->timestamps();

            $table->foreign('session_id')->references('id')->on('sessions')->onDelete('cascade');
        });

        // Audit Logs Table (New)
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('action'); // e.g., 'create_coupon', 'start_session', 'reset_password'
            $table->text('details')->nullable(); // JSON or text for additional info
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        // Stored Procedure for Safe Coupon Validation
        DB::unprepared("
            CREATE PROCEDURE ValidateCoupon(IN in_coupon_code VARCHAR(50), IN in_user_id INT)
            BEGIN
                DECLARE remaining_time INT;
                DECLARE coupon_expires_at DATETIME;

                SELECT total_minutes, expires_at INTO remaining_time, coupon_expires_at
                FROM coupons
                WHERE coupon_code = in_coupon_code
                  AND is_active = TRUE
                  AND (used_by IS NULL OR used_by = in_user_id)
                  AND (expires_at IS NULL OR expires_at > NOW());

                IF remaining_time IS NOT NULL THEN
                    UPDATE coupons
                    SET used_by = in_user_id
                    WHERE coupon_code = in_coupon_code AND used_by IS NULL;
                    SELECT 'VALID' AS status, remaining_time;
                ELSE
                    SELECT 'INVALID' AS status, 0;
                END IF;
            END
        ");
    }

    public function down(): void
    {
        // Drop Tables in Reverse Order
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('users');

        // Drop Stored Procedure
        DB::unprepared('DROP PROCEDURE IF EXISTS ValidateCoupon');
    }
};
