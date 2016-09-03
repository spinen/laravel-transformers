<?php

namespace Spinen\Transformers;

use Illuminate\Database\Eloquent\Model;
use Spinen\Transformers\TestCase;
use Spinen\Transformers\TransformableCollection;
use Spinen\Transformers\Transformer;

class TransformableCollectionTest extends TestCase
{
    /**
     * @var TransformableCollection
     */
    protected $collection;

    public function setUp()
    {
        parent::setUp();

        $this->collection = new TransformableCollection();
    }

    /**
     * @test
     * @group unit
     */
    public function it_builds_the_expected_path_the_the_transformer_for_short_name()
    {
        $this->assertEquals('Spinen\Transformers\Transformer', $this->collection->getTransformerClass('Transformer'));
    }

    /**
     * @test
     * @expectedException \Spinen\Transformers\Exceptions\TransformerNotFoundException
     * @group unit
     */
    public function it_raises_exception_when_the_transformer_does_not_exist()
    {
        $this->collection->transformToWithPagination('InvalidTransformer');
    }

    /**
     * @test
     * @group unit
     */
    public function it_allows_transformation()
    {
        $this->assertArrayHasKey('data', $this->collection->transformTo('TestUser'));
    }

    /**
     * @test
     * @group unit
     */
    public function it_allows_transformation_with_pagination()
    {
        $this->collection = $this->collection->transformToWithPagination('TestUser')
                                             ->toArray();
        $this->assertArrayHasKey('data', $this->collection);
        $this->assertArrayHasKey('total', $this->collection);
    }
}

function class_exists($class)
{
    if (("Spinen\\Transformers\\InvalidTransformer" == $class) || ("InvalidTransformer" == $class)) {
        return false;
    }

    return true;
}

/**
 * Fake transformer to test the changes
 *
 * @package App\Support\Transformation\Stubs
 */
class TestUser extends Transformer
{
    /**
     * @param Model $model
     *
     * @return array
     */
    public function transform(Model $model)
    {
        return [];
    }
}
