<?php

namespace app\modules\hotel\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hotel\models\Attributes;

/**
 * AttributesSearch represents the model behind the search form about `app\modules\hotel\models\Attributes`.
 */
class AttributesSearch extends Attributes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['aid', 'type', 'updated_at'], 'integer'],
            [['name', 'created_at'], 'safe'],
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
        $query = Attributes::find();

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
        if ($this->created_at != '') {
            $query->andFilterWhere(['between', 'created_at', strtotime($this->created_at), strtotime($this->created_at . ' 23:59:59')]);
        }
        $query->andFilterWhere([
            'aid' => $this->aid,
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
