<?php

namespace Spinen\Transformers;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Transformer
 *
 * @package Spinen\Transformers\Transformation
 */
abstract class Transformer
{
    /**
     * Loop through the items in the collection & run the transform method
     *
     * @param TransformableCollection $model
     *
     * @return $this
     */
    public function transformCollection(TransformableCollection $model)
    {
        return $model->transform([$this, 'transform']);
    }

    /**
     * The method that actually converts the data using the map in the class
     *
     * @param Model $model
     *
     * @return mixed
     */
    abstract public function transform(Model $model);
}
