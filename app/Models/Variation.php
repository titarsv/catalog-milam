<?php

namespace App\Models;

class Variation extends Entity
{
    protected $fillable = [
        'external_id',
        'product_id',
        'brand_size',
        'price',
        'stock'
    ];

    protected $table = 'variations';
    public $timestamps = false;

    public function attribute_values()
    {
        return $this->belongsToMany('App\Models\AttributeValue', 'variation_attributes');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'id');
    }

    public function getSizeAttribute(){
        if(empty($this->attribute_values)){
            $attr = $this->attribute_values()
                ->join('attribute_values', 'variation_attributes.attribute_value_id', '=', 'attribute_values.id')
                ->where('attribute_values.attributes_id', 6)
                ->first();
            if(is_object($attr))
                return $attr->value;
        }else{
            foreach($this->attribute_values as $val){
                if($val->attribute_id == 6){
                    return $val;
                }
            }
        }

        return null;
    }

    public function setBrandSizeAttribute($value){
        if(!empty($value)){
            $this->attributes['brand_size'] = $value;
        }else{
            $product = Product::find($this->product_id);
            if($product->sizes_standard != 'EU'
                && !empty($this->size)
                && isset($product->sizes_standards[$product->sizes_type])
                && isset($product->sizes_standards[$product->sizes_type][$product->sizes_standard])
                && isset($product->sizes_standards[$product->sizes_type][$product->sizes_standard][$this->size->name])){
                $this->attributes['brand_size'] = $product->sizes_standards[$product->sizes_type][$product->sizes_standard][$this->size->name];
            }
        }
    }

    protected function dataMap(){
        return [
            'attributes' => [],
            'relations' => [
                'attribute_values' => [
                    'attributes' => [
                        'id' => '',
                        'attribute_id' => ''
                    ],
                    'relations' => [
                        'attribute' => [
                            'attributes' => [
                                'id' => ''
                            ],
                            'relations' => [
                                'localization' => [
                                    'attributes' => [
                                        'id' => '',
                                        'field' => '',
                                        'language' => '',
                                        'value' => ''
                                    ]
                                ]
                            ]
                        ],
                        'localization' => [
                            'attributes' => [
                                'id' => '',
                                'field' => '',
                                'language' => '',
                                'value' => ''
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    public function fieldsNames(){
        return [
            'attributes.brand_size' => [
                'name' => 'Размер производителя'
            ],
            'attributes.price' => [
                'name' => 'Цена'
            ],
            'attributes.stock' => [
                'name' => 'Наличие вариации'
            ],
            'relations.attribute_values' => [
                'name' => 'Атрибут',
                'multiple' => true,
                'fields' => [
                    'relations.localization' => [
                        'localization' => true,
                        'fields' => [
                            [
                                'name_from' => 'relations.attribute[].relations.localization[].attributes.value',
                                'name' => 'Название',
                                'field' => 'name'
                            ]
                        ]
                    ]
                ]
            ],
        ];
    }
}
