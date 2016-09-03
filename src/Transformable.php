<?php

namespace Spinen\Transformers;

/**
 * Class Transformable
 *
 * @package App\Support\Transformation
 */
trait Transformable
{
    /**
     * Tell eloquent to make the collection as a TransformableCollection
     *
     * This allows us to mix in methods to the collection.
     *
     * @param array $models
     *
     * @return TransformableCollection
     */
    public function newCollection(array $models = [])
    {
        return new TransformableCollection($models);
    }
}
