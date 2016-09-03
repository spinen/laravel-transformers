<?php

namespace Spinen\Transformers;

use Spinen\Transformers\TestCase;
use Spinen\Transformers\Transformable;
use Spinen\Transformers\TransformableCollection;

class TransformableTest extends TestCase
{
    /**
     * @test
     * @group unit
     */
    public function it_provides_the_expected_collection_type_when_calling_newCollection()
    {
        $model = new ModelStub();

        $this->assertInstanceOf(TransformableCollection::class, $model->newCollection());
    }
}

class ModelStub
{
    use Transformable;
}
