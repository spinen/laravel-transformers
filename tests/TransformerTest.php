<?php

namespace Spinen\Transformers;

use Illuminate\Database\Eloquent\Model;
use Mockery;
use Spinen\Transformers\TestCase;
use Spinen\Transformers\Transformer;

class TransformerTest extends TestCase
{
    /**
     * @test
     * @group unit
     */
    public function it_calls_transform_on_the_collection()
    {
        $model = Mockery::mock(MockedModel::class);

        $model->shouldReceive('doSomething')
              ->once()
              ->withNoArgs()
              ->andReturn('Ran');

        $collection = new TransformableCollection([$model]);

        $transformer = new TransformerStub();

        $this->assertEquals(['Ran'], $transformer->transformCollection($collection)
                                                 ->toArray());
    }
}

/**
 * Fake model so that we can mock it
 *
 * Class MockedModel
 *
 * @package App\Support\Transformation
 */
class MockedModel extends Model
{
}

/**
 * Fake transformer so that it can call an asserted method on the model
 *
 * Class TransformerStub
 *
 * @package App\Support\Transformation
 */
class TransformerStub extends Transformer
{
    /**
     * The method that actually converts the data using the map in the class
     *
     * @param Model $model
     *
     * @return mixed
     */
    public function transform(Model $model)
    {
        // Mocked out on the model so that we now that transform was called
        return $model->doSomething();
    }
}
