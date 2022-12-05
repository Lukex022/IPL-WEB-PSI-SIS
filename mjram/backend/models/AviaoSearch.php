<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Aviao;

/**
 * AviaoSearch represents the model behind the search form of `common\models\Aviao`.
 */
class AviaoSearch extends Aviao
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'combustivelatual', 'combustivelmaximo', 'id_companhia'], 'integer'],
            [['marca', 'modelo', 'data_registo', 'estado'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Aviao::find();

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
            'id' => $this->id,
            'combustivelatual' => $this->combustivelatual,
            'combustivelmaximo' => $this->combustivelmaximo,
            'data_registo' => $this->data_registo,
            'id_companhia' => $this->id_companhia,
        ]);

        $query->andFilterWhere(['like', 'marca', $this->marca])
            ->andFilterWhere(['like', 'modelo', $this->modelo])
            ->andFilterWhere(['like', 'estado', $this->estado]);

        return $dataProvider;
    }
}
