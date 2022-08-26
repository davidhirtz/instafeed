<?php

namespace app\migrations;

use app\models\InstagramToken;
use davidhirtz\yii2\skeleton\db\MigrationTrait;
use yii\db\Migration;

/**
 * Class M220825124126Instagram
 * @package app\migrations
 * @noinspection PhpUnused
 */
class M220825124126Instagram extends Migration
{
    use MigrationTrait;

    /**
     * @inheritDoc
     */
    public function safeUp()
    {
        $this->createTable(InstagramToken::tableName(), [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string()->notNull(),
            'description' => $this->string()->null(),
            'cache_duration' => $this->integer()->unsigned()->defaultValue(60 * 15),
            'verification_token' => $this->string()->null(),
            'username' => $this->string()->null(),
            'access_token' => $this->string()->null(),
            'refreshed_at' => $this->dateTime(),
            'expires_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'created_at' => $this->dateTime()->notNull(),
        ], $this->getTableOptions());
    }

    /**
     * @inheritDoc
     */
    public function safeDown()
    {
        $this->dropTable(InstagramToken::tableName());
    }
}