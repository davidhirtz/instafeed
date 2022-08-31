<?php

namespace app\migrations;

use app\models\InstagramToken;
use davidhirtz\yii2\skeleton\db\MigrationTrait;
use yii\db\Migration;

/**
* Class M220831131116User
* @package app\migrations
* @noinspection PhpUnused
*/
class M220831131116UserId extends Migration
{
    use MigrationTrait;

    /**
     * @inheritDoc
     */
    public function safeUp()
    {
        $this->alterColumn(InstagramToken::tableName(), 'user_id', $this->string()->null()->after('username'));
    }

    /**
     * @inheritDoc
     */
    public function safeDown()
    {
    }
}