<?php

namespace app\migrations;

use app\models\InstagramToken;
use davidhirtz\yii2\skeleton\db\MigrationTrait;
use yii\db\Migration;

/**
 * Class M220826115832Slug
 * @package app\migrations
 * @noinspection PhpUnused
 */
class M220826115832Slug extends Migration
{
    use MigrationTrait;

    /**
     * @inheritDoc
     */
    public function safeUp()
    {
        $this->addColumn(InstagramToken::tableName(), 'slug', $this->string()->notNull()->unique());
    }

    /**
     * @inheritDoc
     */
    public function safeDown()
    {
        $this->dropColumn(InstagramToken::tableName(), 'slug');
    }
}