<?php

use yii\db\Migration;

/**
 * Handles the creation for table `rbac_tables`.
 */
class m160415_133402_create_rbac_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->execute(file_get_contents(Yii::getAlias('@yii/rbac/migrations/schema-mysql.sql')));
		return true;
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $authManager = Yii::$app->getAuthManager();
        $this->db = $authManager->db;

        $this->dropTable($authManager->assignmentTable);
        $this->dropTable($authManager->itemChildTable);
        $this->dropTable($authManager->itemTable);
        $this->dropTable($authManager->ruleTable);

        return true;
    }
}
