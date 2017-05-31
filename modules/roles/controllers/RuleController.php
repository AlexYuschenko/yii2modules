<?php

namespace app\modules\roles\controllers;

use Yii;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\rbac\Role;
use yii\rbac\Permission;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\validators\RegularExpressionValidator;
use app\modules\roles\models\AuthRole;
use app\modules\roles\models\AuthPermission;
use app\modules\roles\models\Rule;
use app\modules\roles\models\RuleSearch;
use yii\filters\VerbFilter;

class RuleController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actionIndex()
    {
        $searchModel = new RuleSearch(null);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('rule', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Rule model.
     * @return mixed
     */
    public function actionAddRule()
    {
        $request = Yii::$app->request;
        $model = new Rule(null);

        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('roles', 'Rule has been created'));
            return $this->redirect(Url::toRoute(['rule/index']));
        } else {
            return $this->render('addRule', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Rule model.
     * @param string $name
     * @return mixed
     */
    public function actionUpdateRule($name)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($name);

        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('roles', 'Rule have been updated'));
            return $this->redirect(Url::toRoute(['rule/index']));
        } else {
            return $this->render('updateRule', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Delete an existing Rule model.
     * @param string $name
     * @return mixed
     */
    public function actionDeleteRule($name)
    {
        $request = Yii::$app->request;
        $this->findModel($name)->delete();

        Yii::$app->getSession()->setFlash('success', Yii::t('roles', 'Rule have been deleted'));
        return $this->redirect(Url::toRoute(['rule/index']));
    }

    /**
     * Finds the Rule model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $name
     * @return Rule the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($name)
    {
        if (($model = Rule::find($name)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('roles', 'The requested page does not exist.'));
        }
    }
}
