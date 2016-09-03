<?php

namespace Spinen\Commands;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Spinen\Transformers\TestCase;

class TransformerMakeCommandTest extends TestCase
{
    /**
     * @test
     * @group unit
     */
    public function it_requires_a_name_for_the_transformer()
    {
        $input = new ArrayInput([
            'command' => 'make:transformer',
        ]);
        $output = new BufferedOutput();

        app(Kernel::class)->handle($input, $output);

        $this->assertContains('missing: "name"', $output->fetch());
    }

    /**
     * @test
     * @group unit
     */
    public function it_builds_correct_transformer_file()
    {
        $stub = app_path('Console/Commands/stubs/transformer.stub');
        $path = app_path('Transformers/TestTransformer.php');
        $content = <<<EOT
<?php

namespace App\Transformers;

use App\Support\Transformation\Transformer;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TestTransformer
 *
 * @package App\Transformers
 */
class TestTransformer extends Transformer
{
    /**
     * @param Model \$model
     *
     * @return array
     */
    public function transform(Model \$model)
    {
        return [
            // TODO: Layout transformation here
        ];
    }
}

EOT;
//        var_export('**********');
//        var_export($content, false);
//        var_export('**********');

        File::shouldReceive('get')
            ->once()
            ->with($stub)
            ->andReturn(file_get_contents($stub));

        File::shouldReceive('put')
            ->once()
            ->withArgs([
                $path,
                $content,
            ])
            ->andReturnNull();

        File::shouldIgnoreMissing();

        $input = new ArrayInput([
            'command' => 'make:transformer',
            'name'    => 'TestTransformer',
        ]);
        $output = new BufferedOutput();

        app(Kernel::class)->handle($input, $output);

        $this->assertContains('successfully', $output->fetch());
    }
}
