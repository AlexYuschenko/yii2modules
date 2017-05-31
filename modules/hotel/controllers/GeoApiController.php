<?php

namespace app\modules\hotel\controllers;

use lembadm\geodb\models\City;
use lembadm\geodb\models\CityName;
use lembadm\geodb\models\Country;
use lembadm\geodb\models\Region;
use yii\db\Query;
use yii\rest\Controller;

/**
 * GeoApi controller for the `hotel` module
 */
class GeoApiController extends Controller
{
    /**
     * @param string|null $q
     * @param int|null    $id
     *
     * @return array
     */
    public function actionCountries($q = null, $id = null)
    {
        if ($id) {
            return [
                'results' => [
                    'id'   => $id,
                    'text' => Country::findOne(['iso' => $id])->name
                ]
            ];
        }
        $data = (new Query)
            ->select('iso AS id, name AS text')
            ->from(Country::tableName())
            ->orderBy(['name' => SORT_DESC])
            ->limit(50);
        if ($q) {
            $data->where(['like', 'name', $q . '%', false]);
        }
        return [
            'results' => array_values($data->createCommand()->queryAll())
        ];
    }

    /**
     * @param string|null $q
     * @param int|null    $id
     * @param int|null    $country_id
     *
     * @return array
     */
    public function actionRegions($q = null, $id = null, $country_id = null)
    {
        if ($id) {
            return [
                'results' => [
                    'id'   => (int)$id,
                    'text' => Region::findOne((int)$id)->name
                ]
            ];
        }
        $data = (new Query)
            ->select('r.id, r.name AS text')
            ->from(Region::tableName() . ' r')
            ->limit(50);
        if ($q) {
            $data->where([
                'or',
                ['like', 'r.name', $q . '%', false],
                ['like', 'r.name_ascii', $q . '%', false],
            ]);
        }
        if ($country_id) {
            $data->leftJoin(Country::tableName() . ' c', 'c.id = r.country_id');
            $data->andWhere(['c.iso' => $country_id]);
        }
        return [
            'results' => array_values($data->createCommand()->queryAll())
        ];
    }
    /**
     * @param string|null $q
     * @param int|null    $id
     * @param int|null    $country_id
     * @param int|null    $region_id
     *
     * @return array
     */
    public function actionCities($q = null, $id = null, $country_id = null, $region_id = null)
    {
        if ($id) {
            return [
                'results' => [
                    'id'   => (int)$id,
                    'text' => City::findOne((int)$id)->name
                ]
            ];
        }
        if ($q) {
            $data = (new Query)
                ->select('c.id AS id, c.name AS text')
                ->from(CityName::tableName() . ' cn')
                ->innerJoin(City::tableName() . ' c', 'c.id = cn.city_id')
                ->where(['like', 'cn.name', $q . '%', false])
                ->groupBy('cn.city_id')
                ->limit(50);
            if ($country_id) {
                $data->innerJoin(Country::tableName() . ' cc', 'cc.id = cn.country_id');
                $data->andWhere(['cc.iso' => $country_id]);
            }
            if ($region_id) {
                $data->andWhere(['cn.region_id' => $region_id]);
            }
        } else {
            $data = (new Query)
                ->select('c.id, c.name AS text')
                ->from(City::tableName() . ' c')
                ->limit(50);
            if ($country_id) {
                $data->innerJoin(Country::tableName() . ' cc', 'cc.id = c.country_id');
                $data->andWhere(['cc.iso' => $country_id]);
            }
            if ($region_id) {
                $data->andWhere(['region_id' => $region_id]);
            }
        }
        return [
            'results' => array_values($data->createCommand()->queryAll())
        ];
    }
}
