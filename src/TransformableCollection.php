<?php

namespace Spinen\Transformers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Input;
use Spinen\Transformers\Exceptions\TransformerNotFoundException;

/**
 * Class TransformableCollection
 *
 * @package App\Support\Transformation
 */
class TransformableCollection extends Collection
{
    /**
     * Fully Qualified Namespace to the Transformers
     *
     * @var string
     */
    protected $namespace = 'Spinen\Transformers';

    /**
     * Build out the namespace to the transformers
     *
     * @return string
     */
    public function getNamespace()
    {
        return trim($this->namespace, '\\');
    }

    /**
     * Make a transformer
     *
     * Allow the full path to the transformer class be specified or just the short name where it is concatenated to the
     * getNamespace method.
     *
     * @param string $class
     *
     * @return \Illuminate\Foundation\Application|mixed
     */
    public function getTransformer($class)
    {
        // If a full path to a class was passed in, use it, otherwise a
        $class = (class_exists($class)) ? $class : $this->getTransformerClass($class);

        return app($class);
    }

    public function getTransformerClass($class)
    {
        $class = $this->getNamespace() . '\\' . $class;

        if (class_exists($class)) {
            return $class;
        }

        throw new TransformerNotFoundException(sprintf('Could not locate Transformer [%s]', $class));
    }

    /**
     * Transform the data
     *
     * @param string $class
     *
     * @return array
     */
    protected function runTransformation($class)
    {
        return $this->getTransformer($class)
                    ->transformCollection($this)
                    ->toArray();
    }

    /**
     * Transform the collection
     *
     * You pass in a class name that is assume that the classes are located in app/Transformers/
     *
     * @param string $class
     *
     * @return array
     */
    public function transformTo($class)
    {
        return ['data' => $this->runTransformation($class)];
    }

    /**
     * Transform the collection to a paginated collection
     *
     * @param string $class
     *
     * @return LengthAwarePaginator
     */
    public function transformToWithPagination($class)
    {
        $page = Input::get('page', 1);
        $perPage = Input::get('limit', 5);
        $offset = ($page * $perPage) - $perPage;
        // TODO: Is there a way to slice before transforming, so that we don't waste time transforming unwanted data?
        $arraySlice = array_slice($this->runTransformation($class), $offset, $perPage, true);

        return new LengthAwarePaginator($arraySlice, $this->count(), $perPage);
    }
}
