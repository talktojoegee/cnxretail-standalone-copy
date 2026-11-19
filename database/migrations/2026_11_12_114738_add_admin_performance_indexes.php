<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addIndex('tenants', 'tenants_account_status_index', function (Blueprint $table) {
            $table->index('account_status', 'tenants_account_status_index');
        });
        $this->addIndex('tenants', 'tenants_end_date_index', function (Blueprint $table) {
            $table->index('end_date', 'tenants_end_date_index');
        });
        $this->addIndex('tenants', 'tenants_plan_id_index', function (Blueprint $table) {
            $table->index('plan_id', 'tenants_plan_id_index');
        });
        $this->addIndex('tenants', 'tenants_created_at_index', function (Blueprint $table) {
            $table->index('created_at', 'tenants_created_at_index');
        });
        $this->addIndex('tenants', 'tenants_company_name_index', function (Blueprint $table) {
            $table->index('company_name', 'tenants_company_name_index');
        });
        $this->addIndex('tenants', 'tenants_email_index', function (Blueprint $table) {
            $table->index('email', 'tenants_email_index');
        });

        if (! $this->indexExists('tenants', 'tenants_slug_prefix_index')) {
            DB::statement('ALTER TABLE `tenants` ADD INDEX `tenants_slug_prefix_index` (`slug`(191))');
        }

        $this->addIndex('items', 'items_tenant_id_item_type_index', function (Blueprint $table) {
            $table->index(['tenant_id', 'item_type'], 'items_tenant_id_item_type_index');
        });
        $this->addIndex('items', 'items_slug_index', function (Blueprint $table) {
            $table->index('slug', 'items_slug_index');
        });

        $this->addIndex('users', 'users_tenant_id_index', function (Blueprint $table) {
            $table->index('tenant_id', 'users_tenant_id_index');
        });
        $this->addIndex('users', 'users_account_status_index', function (Blueprint $table) {
            $table->index('account_status', 'users_account_status_index');
        });

        $this->addIndex('subscriptions', 'subscriptions_tenant_id_index', function (Blueprint $table) {
            $table->index('tenant_id', 'subscriptions_tenant_id_index');
        });
        $this->addIndex('subscriptions', 'subscriptions_plan_id_index', function (Blueprint $table) {
            $table->index('plan_id', 'subscriptions_plan_id_index');
        });
        $this->addIndex('subscriptions', 'subscriptions_created_at_index', function (Blueprint $table) {
            $table->index('created_at', 'subscriptions_created_at_index');
        });
        $this->addIndex('subscriptions', 'subscriptions_end_date_index', function (Blueprint $table) {
            $table->index('end_date', 'subscriptions_end_date_index');
        });

        $this->addIndex('buyer_requests', 'buyer_requests_tenant_id_index', function (Blueprint $table) {
            $table->index('tenant_id', 'buyer_requests_tenant_id_index');
        });
        $this->addIndex('buyer_requests', 'buyer_requests_product_id_index', function (Blueprint $table) {
            $table->index('product_id', 'buyer_requests_product_id_index');
        });
        $this->addIndex('buyer_requests', 'buyer_requests_created_at_index', function (Blueprint $table) {
            $table->index('created_at', 'buyer_requests_created_at_index');
        });
        $this->addIndex('buyer_requests', 'buyer_requests_email_index', function (Blueprint $table) {
            $table->index('email', 'buyer_requests_email_index');
        });

        $this->addIndex('contact_us', 'contact_us_created_at_index', function (Blueprint $table) {
            $table->index('created_at', 'contact_us_created_at_index');
        });
        $this->addIndex('contact_us', 'contact_us_email_index', function (Blueprint $table) {
            $table->index('email', 'contact_us_email_index');
        });

        $this->addIndex('admin_activity_logs', 'admin_activity_logs_created_at_index', function (Blueprint $table) {
            $table->index('created_at', 'admin_activity_logs_created_at_index');
        });
        $this->addIndex('admin_activity_logs', 'admin_activity_logs_subject_index', function (Blueprint $table) {
            $table->index('subject', 'admin_activity_logs_subject_index');
        });
    }

    public function down(): void
    {
        $this->dropIndexIfExists('tenants', 'tenants_account_status_index');
        $this->dropIndexIfExists('tenants', 'tenants_end_date_index');
        $this->dropIndexIfExists('tenants', 'tenants_plan_id_index');
        $this->dropIndexIfExists('tenants', 'tenants_created_at_index');
        $this->dropIndexIfExists('tenants', 'tenants_company_name_index');
        $this->dropIndexIfExists('tenants', 'tenants_email_index');
        $this->dropIndexIfExists('tenants', 'tenants_slug_prefix_index');

        $this->dropIndexIfExists('items', 'items_tenant_id_item_type_index');
        $this->dropIndexIfExists('items', 'items_slug_index');

        $this->dropIndexIfExists('users', 'users_tenant_id_index');
        $this->dropIndexIfExists('users', 'users_account_status_index');

        $this->dropIndexIfExists('subscriptions', 'subscriptions_tenant_id_index');
        $this->dropIndexIfExists('subscriptions', 'subscriptions_plan_id_index');
        $this->dropIndexIfExists('subscriptions', 'subscriptions_created_at_index');
        $this->dropIndexIfExists('subscriptions', 'subscriptions_end_date_index');

        $this->dropIndexIfExists('buyer_requests', 'buyer_requests_tenant_id_index');
        $this->dropIndexIfExists('buyer_requests', 'buyer_requests_product_id_index');
        $this->dropIndexIfExists('buyer_requests', 'buyer_requests_created_at_index');
        $this->dropIndexIfExists('buyer_requests', 'buyer_requests_email_index');

        $this->dropIndexIfExists('contact_us', 'contact_us_created_at_index');
        $this->dropIndexIfExists('contact_us', 'contact_us_email_index');

        $this->dropIndexIfExists('admin_activity_logs', 'admin_activity_logs_created_at_index');
        $this->dropIndexIfExists('admin_activity_logs', 'admin_activity_logs_subject_index');
    }

    private function addIndex(string $table, string $index, callable $callback): void
    {
        if ($this->indexExists($table, $index)) {
            return;
        }

        Schema::table($table, $callback);
    }

    private function dropIndexIfExists(string $table, string $index): void
    {
        if (! $this->indexExists($table, $index)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($index) {
            $blueprint->dropIndex($index);
        });
    }

    private function indexExists(string $table, string $index): bool
    {
        $database = DB::getDatabaseName();
        $count = DB::table('information_schema.statistics')
            ->where('table_schema', $database)
            ->where('table_name', $table)
            ->where('index_name', $index)
            ->count();

        return $count > 0;
    }
};
