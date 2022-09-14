<?php

namespace app\models;

use app\controllers\AuthController;
use davidhirtz\yii2\datetime\DateTime;
use davidhirtz\yii2\datetime\DateTimeBehavior;
use davidhirtz\yii2\skeleton\behaviors\TimestampBehavior;
use davidhirtz\yii2\skeleton\behaviors\TrailBehavior;
use davidhirtz\yii2\skeleton\db\ActiveRecord;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Yii;
use yii\helpers\Inflector;

/**
 * InstagramToken model.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $username
 * @property string|null $user_id
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
                ['slug'],
                'unique',
            ],
            [
                ['name', 'slug', 'description'],
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
     * @return bool
     */
    public function beforeValidate()
    {
        $this->slug = Inflector::slug($this->slug ?: $this->name);
        return parent::beforeValidate();
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

    /**
     * @return $this
     */
    public function refreshAccessToken()
    {
        $response = (new Client())->get('https://graph.instagram.com/refresh_access_token', [
            'query' => [
                'grant_type' => 'ig_refresh_token',
                'access_token' => $this->access_token,
            ],
        ]);

        $this->setAttributesFromApiResponse($response);
        $this->refreshed_at = new DateTime();

        return $this;
    }

    /**
     * @return $this
     */
    public function resetInstagramAttributes()
    {
        foreach (['access_token', 'verification_token', 'username', 'user_id', 'refreshed_at', 'expires_at'] as $attribute) {
            $this->$attribute = null;
        }

        return $this;
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
    public function getCacheKey()
    {
        return "ig-cache-{$this->id}";
    }

    /**
     * @return string
     */
    public function getLoginUrl()
    {
        /** @see AuthController::actionLogin() */
        return Yii::$app->getUrlManager()->createAbsoluteUrl([
            'auth/login', 'state' => urlencode(base64_encode(serialize([
                $this->id, $this->verification_token
            ]))),
        ]);
    }

    /**
     * @return array
     */
    public function getRoute(): array
    {
        return ['/api/index', 'slug' => $this->slug];
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
            'name' => Yii::t('skeleton', 'Account'),
            'slug' => Yii::t('app', 'API Endpoint'),
            'username' => Yii::t('skeleton', 'User'),
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