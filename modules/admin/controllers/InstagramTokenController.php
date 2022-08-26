<?php


namespace app\modules\admin\controllers;

use app\models\InstagramToken;
use davidhirtz\yii2\skeleton\models\User;
use davidhirtz\yii2\skeleton\web\Controller;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * InstagramTokenController implements the CRUD actions for Instagram model.
 */
class InstagramTokenController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'delete', 'index', 'refresh', 'reset', 'update'],
                        'roles' => [User::AUTH_ROLE_ADMIN],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                    'refresh' => ['post'],
                    'reset' => ['post'],
                ],
            ],
        ]);
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $provider = new ActiveDataProvider([
            'query' => InstagramToken::find(),
        ]);

        return $this->render('index', [
            'provider' => $provider,
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionCreate()
    {
        $instagram = new InstagramToken();
        $instagram->loadDefaultValues();

        if ($instagram->load(Yii::$app->getRequest()->post()) && $instagram->insert()) {
            $this->success(Yii::t('app', 'Instagram account was added.'));
            return $this->redirect(['update', 'id' => $instagram->id]);
        }

        return $this->render('create', [
            'instagram' => $instagram,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     */
    public function actionUpdate($id)
    {
        $instagram = $this->findInstagramToken($id);

        if ($instagram->load(Yii::$app->getRequest()->post()) && $instagram->update()) {
            $this->success('Instagram account was updated.');
            return $this->redirect(['update', 'id' => $instagram->id]);
        }

        return $this->render('update', [
            'instagram' => $instagram,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     */
    public function actionRefresh($id)
    {
        $instagram = $this->findInstagramToken($id);

        try {
            $instagram->refreshAccessToken();
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
            return $this->redirect(['update', 'id' => $instagram->id]);
        }

        if ($instagram->update()) {
            $this->success('Instagram token was refreshed.');
            return $this->redirect(['update', 'id' => $instagram->id]);
        }

        return $this->render('update', [
            'instagram' => $instagram,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     */
    public function actionReset($id)
    {
        $instagram = $this->findInstagramToken($id);
        $instagram->resetInstagramAttributes();

        if ($instagram->update()) {
            $this->success('Instagram account was cleared.');
            return $this->redirect(['update', 'id' => $instagram->id]);
        }

        return $this->render('update', [
            'instagram' => $instagram,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     */
    public function actionDelete($id)
    {
        $instagram = $this->findInstagramToken($id);

        if ($instagram->delete()) {
            $this->success('Instagram account was removed.');
        }

        return $this->redirect(['index']);
    }

    /**
     * @param int $id
     * @return InstagramToken
     */
    private function findInstagramToken($id)
    {
        if (!$instagram = InstagramToken::findOne((int)$id)) {
            throw new NotFoundHttpException();
        }

        return $instagram;
    }
}