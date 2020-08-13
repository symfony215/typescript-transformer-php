<?php

namespace Spatie\TypescriptTransformer\Tests\Steps;

use PHPUnit\Framework\TestCase;
use Spatie\TypescriptTransformer\Steps\ReplaceMissingSymbolsStep;
use Spatie\TypescriptTransformer\Structures\TypesCollection;
use Spatie\TypescriptTransformer\Tests\Fakes\FakeTransformedType;

class ReplaceMissingSymbolsStepTest extends TestCase
{
    private ReplaceMissingSymbolsStep $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new ReplaceMissingSymbolsStep();
    }

    /** @test */
    public function it_can_replace_missing_symbols()
    {
        $collection = TypesCollection::create();

        $collection[] = FakeTransformedType::fake('Enum')->withNamespace('enums');
        $collection[] = FakeTransformedType::fake('Dto')
            ->withTransformed('{enum: {%enums\Enum%}, non-existing: {%non-existing%}}')
            ->withMissingSymbols([
                'enum' => 'enums\Enum',
                'non-existing' => 'non-existing',
            ]);

        $collection = $this->action->execute($collection);

        $this->assertEquals('{enum: enums.Enum, non-existing: any}', $collection['Dto']->transformed);
    }
}
