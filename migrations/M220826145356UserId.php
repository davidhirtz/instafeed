<?php

namespace app\migrations;

use app\models\InstagramToken;
use davidhirtz\yii2\skeleton\db\MigrationTrait;
use yii\db\Migration;

/**
 * Class M220826145356UserId
 * @package app\migrations
 * @noinspection PhpUnused
 */
class M220826145356UserId extends Migration
{
    use MigrationTrait;

    /**
     * @inheritDoc
     */
    public function safeUp()
    {
        $this->addColumn(InstagramToken::tableName(), 'user_id', $this->string()->notNull()->after('username'));
    }

    /**
     * @inheritDoc
     */
    public function safeDown()
    {
        $this->dropColumn(InstagramToken::tableName(), 'user_id');
    }
}