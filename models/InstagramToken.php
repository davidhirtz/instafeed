<?php

namespace app\models;

use app\controllers\SiteController;
use davidhirtz\yii2\datetime\DateTime;
use davidhirtz\yii2\datetime\DateTimeBehavior;
use davidhirtz\yii2\skeleton\behaviors\TimestampBehavior;
use davidhirtz\yii2\skeleton\behaviors\TrailBehavior;
use davidhirtz\yii2\skeleton\db\ActiveRecord;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Yii;

/**
 * InstagramToken model.
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $username
 * @property int|null $cache_duration
 * @property string $verification_token
 * @property string|null $access_token
 * @property DateTime|null $refreshed_at
 * @property DateTime|null $expires_at
 * @property DateTime|null $updated_at
 * @property DateTime $created_at
 *
 * @method static InstagramToken findOne($condition)
 */
class InstagramToken extends ActiveRecord
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'DateTimeBehavior' => DateTimeBehavior::class,
            'TrailBehavior' => TrailBehavior::class,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [
                ['name'],
                'required',
            ],
            [
                ['name'],
                'unique',
            ],
            [
                ['description'],
                'string',
                'max' => 255,
            ],
            [
                ['cache_duration'],
                'number',
                'integerOnly' => true,
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function beforeSave($insert)
    {
        $this->attachBehaviors([
            'TimestampBehavior' => TimestampBehavior::class
        ]);

        if (!$this->verification_token) {
            $this->verification_token = Yii::$app->getSecurity()->generateRandomString(12);
        }

        return parent::beforeSave($insert);
    }

    public function refreshAccessToken()
    {
        $response = (new Client())->get('https://graph.instagram.com/refresh_access_token', [
            'query' => [
                'grant_type' => 'ig_exchange_token',
                'ig_exchange_token' => $this->access_token,
            ],
        ]);

        $this->setAttributesFromApiResponse($response);
    }

    /**
     * @param ResponseInterface $response
     * @return void
     */
    public function setAttributesFromApiResponse($response)
    {
        $data = json_decode($response->getBody()->getContents(), true);

        $this->access_token = $data['access_token'] ?? null;
        $this->expires_at = (new DateTime())->setTimestamp($data['expires_in'] + time());
    }

    /**
     * @return string
     */
    public function getLoginUrl()
    {
        /** @see SiteController::actionLogin() */
        return Yii::$app->getUrlManager()->createAbsoluteUrl([
            'site/login', 'state' => urlencode(base64_encode(serialize([
                $this->id, $this->verification_token
            ]))),
        ]);
    }

    /**
     * @return array
     * @noinspection PhpUnused
     */
    public function getTrailAttributes(): array
    {
        return array_diff($this->attributes(), [
            'access_token',
            'verification_token',
            'updated_at',
            'created_at',
        ]);
    }

    /**
     * @return string
     * @noinspection PhpUnused
     */
    public function getTrailModelName()
    {
        return Yii::t('app', 'Instagram Account');
    }

    /**
     * @return array|false
     * @noinspection PhpUnused
     */
    public function getTrailModelAdminRoute()
    {
        return $this->id ? ['/admin/instagram-token/update', 'id' => $this->id] : false;
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'name' => Yii::t('skeleton', 'Name'),
            'description' => Yii::t('skeleton', 'Description'),
            'cache_duration' => Yii::t('app', 'Request caching'),
            'refreshed_at' => Yii::t('app', 'Last refreshed'),
            'expires_at' => Yii::t('app', 'Valid until'),
        ];
    }

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%instagram_token}}';
    }
}