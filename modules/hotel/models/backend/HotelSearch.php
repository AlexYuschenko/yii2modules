<?php

namespace app\modules\hotel\models\backend;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hotel\models\backend\Hotel;

/**
 * HotelSearch represents the model behind the search form about `app\modules\hotel\models\backend\Hotel`.
 */
class HotelSearch extends Hotel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hid', 'user_id', 'stars', 'country', 'city', 'created_at', 'updated_at'], 'integer'],
            [['name', 'description', 'address', 'hotel_type', 'check_in', 'check_out'], 'safe'],
            [['map_lat', 'map_lng'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Hotel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'hid' => $this->hid,
            'user_id' => $this->user_id,
            'stars' => $this->stars,
            'country' => $this->country,
            'city' => $this->city,
            'map_lat' => $this->map_lat,
            'map_lng' => $this->map_lng,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'hotel_type', $this->hotel_type])
            ->andFilterWhere(['like', 'check_in', $this->check_in])
            ->andFilterWhere(['like', 'check_out', $this->check_out]);

        return $dataProvider;
    }
}
