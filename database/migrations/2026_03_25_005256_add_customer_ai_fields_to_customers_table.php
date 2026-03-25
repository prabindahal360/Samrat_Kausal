<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->decimal('total_spend', 12, 2)->default(0)->after('country');
            $table->string('customer_category')->nullable()->after('total_spend');
            $table->integer('discount_percentage')->default(0)->after('customer_category');
            $table->text('recommended_products')->nullable()->after('discount_percentage');
            $table->timestamp('last_recommendation_sent_at')->nullable()->after('recommended_products');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'total_spend',
                'customer_category',
                'discount_percentage',
                'recommended_products',
                'last_recommendation_sent_at',
            ]);
        });
    }
};