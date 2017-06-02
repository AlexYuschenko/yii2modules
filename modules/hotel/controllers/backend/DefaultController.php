<?php

namespace app\modules\hotel\controllers\backend;

use Yii;
use app\modules\hotel\models\Photos;
use app\modules\hotel\models\backend\Hotel;
use app\modules\hotel\models\backend\HotelSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * DefaultController implements the CRUD actions for Hotel model.
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Hotel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HotelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Hotel model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Hotel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Hotel();
        $photos = new Photos();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            foreach (array_unique($model->photos) as $key => $photo) {
                $photo = new Photos();
                $photo->uri = $photo;
                $photo->hotel_id = $model->hid;
                $photo->save();
            }
            return $this->redirect(['view', 'id' => $model->hid]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'photos' => $photos,
            ]);
        }
    }

    /**
     * Updates an existing Hotel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $photos = new Photos();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->hid]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'photos' => $photos,
            ]);
        }
    }

    /**
     * Deletes an existing Hotel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @inheritdoc
     */
    public function actionPhotoUpload()
    {
        $photos = new Photos();

        if (Yii::$app->request->isAjax) {

            $photos->photos = UploadedFile::getInstances($photos, 'photos');
            if (count($photos->photos)) {
                $filenames = [];
                $preview = [];
                $initialPreviewConfig = [];
                foreach ($photos->photos as $key => $photo) {
                    $photos->photos[$key]->name = time() . '-' . $photo->name;
                }
                $photos->upload(Yii::$app->request->post('folderId'));
                foreach ($photos->photos as $key => $photo) {
                    $preview[] = Html::img('/uploads/' . Yii::$app->request->post('folderId') . '/' . $photo->name, [
                        'class' => 'file-preview-image',
                        'alt' => '',
                        'height' => '160px'
                    ]);
                    $url = Url::to(['/photo-delete', 'id' => 'hotels']);
                    $initialPreviewConfig[] = [
                        'caption' => $photo->name,
                        'url' => $url,
                        'key' => $key,
                    ];
                    $filenames[] = $photo->name;
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                // return [];
                return [
                    'initialPreview' => $preview,
                    'initialPreviewConfig' => $initialPreviewConfig,
                    'filenames' => $filenames,
                ];
            }
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['error' => 'Server error'];
    }

    /**
     * Finds the Hotel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Hotel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Hotel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
