<?php

use Phinx\Migration\AbstractMigration;

class DropPricingPlanForeignKeyFromSubscriptionPlan extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('business_subscription_plan');
        $table->dropForeignKey('subscription_pricing_plan_id')->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $table = $this->table('business_subscription_plan');
        $table->addForeignKey('subscription_pricing_plan_id', 'subscription_pricing_plan', 'id', [ 'delete' => 'CASCADE', 'update' => 'CASCADE' ])->save();            
    }
}
