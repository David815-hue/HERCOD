<?php

namespace Pelmered\FilamentMoneyField\Tests;

use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Field;
use Illuminate\Validation\ValidationException;
use Money\Exception\ParserException;
use Pelmered\FilamentMoneyField\Exceptions\UnsupportedCurrency;
use Pelmered\FilamentMoneyField\Forms\Components\MoneyInput;
use Pelmered\FilamentMoneyField\Forms\Rules\MaxValueRule;
use Pelmered\FilamentMoneyField\Forms\Rules\MinValueRule;
use Pelmered\FilamentMoneyField\Tests\Components\FormTestComponent;

class FormInputTest extends TestCase
{
    public function testFormInputMoneyFormat(): void
    {
        $component = ComponentContainer::make(FormTestComponent::make())
            ->statePath('data')
            ->components([MoneyInput::make('price')])
            ->fill(['price' => 123456]);

        $this->assertEquals('123456', $component->getState()['price']);
    }

    public function testNullState(): void
    {
        $component = ComponentContainer::make(FormTestComponent::make())
            ->statePath('data')
            ->components([MoneyInput::make('price')])
            ->fill(['price' => null]);

        $this->assertNull($component->getState()['price']);
    }

    public function testNonNumericState(): void
    {
        $this->expectException(ParserException::class);

        $component = ComponentContainer::make(FormTestComponent::make())
            ->statePath('data')
            ->components([MoneyInput::make('price')])
            ->fill(['price' => 'non_numeric']);

        $component->getState();
    }

    public function testCurrencySymbolPlacementAfterInGlobalConfig()
    {
        config(['filament-money-field.form_currency_symbol_placement' => 'after']);

        $component = ComponentContainer::make(FormTestComponent::make())
            ->statePath('data')
            ->components([MoneyInput::make('price')])
            ->fill(['price' => 20]);

        /** @var MoneyInput $field */
        $field = $component->getComponent('data.price');
        $this->assertEquals('$', $field->getSuffixLabel());
        $this->assertNull($field->getPrefixLabel());
    }

    public function testCurrencySymbolPlacementBeforeInGlobalConfig()
    {
        config(['filament-money-field.form_currency_symbol_placement' => 'before']);

        $component = ComponentContainer::make(FormTestComponent::make())
            ->statePath('data')
            ->components([MoneyInput::make('price')])
            ->fill(['price' => 20]);

        $field = $component->getComponent('data.price');
        $this->assertEquals('$', $field->getPrefixLabel());
        $this->assertNull($field->getSuffixLabel());
    }

    public function testCurrencySymbolPlacementHiddenInGlobalConfig()
    {
        config(['filament-money-field.form_currency_symbol_placement' => 'hidden']);

        $component = ComponentContainer::make(FormTestComponent::make())
            ->statePath('data')
            ->components([MoneyInput::make('price')])
            ->fill(['price' => 20]);

        $field = $component->getComponent('data.price');
        $this->assertNull($field->getPrefixLabel());
        $this->assertNull($field->getSuffixLabel());
    }

    public function testCurrencySymbolPlacementAfterOnField()
    {
        $component = ComponentContainer::make(FormTestComponent::make())
            ->statePath('data')
            ->components([MoneyInput::make('price')->symbolPlacement('after')])
            ->fill(['price' => 20]);

        $field = $component->getComponent('data.price');
        //dd($field);
        $this->assertEquals('$', $field->getSuffixLabel());
        $this->assertNull($field->getPrefixLabel());
    }

    public function testCurrencySymbolPlacementBeforeOnField()
    {
        $component = ComponentContainer::make(FormTestComponent::make())
            ->statePath('data')
            ->components([MoneyInput::make('price')->symbolPlacement('before')])
            ->fill(['price' => 20]);

        $field = $component->getComponent('data.price');
        $this->assertEquals('$', $field->getPrefixLabel());
        $this->assertNull($field->getSuffixLabel());
    }

    public function testCurrencySymbolPlacementHiddenOnField()
    {
        $component = ComponentContainer::make(FormTestComponent::make())
            ->statePath('data')
            ->components([MoneyInput::make('price')->symbolPlacement('hidden')])
            ->fill(['price' => 20]);

        $field = $component->getComponent('data.price');
        $this->assertNull($field->getPrefixLabel());
        $this->assertNull($field->getSuffixLabel());
    }

    public function testCurrencySymbolPlacementInvalidOnField()
    {
        $this->expectException(\InvalidArgumentException::class);

        ComponentContainer::make(FormTestComponent::make())
            ->statePath('data')
            ->components([MoneyInput::make('price')->symbolPlacement('invalid')])
            ->fill(['price' => 20]);
    }

    public function testInputMask()
    {
        config(['filament-money-field.use_input_mask' => true]);

        $component = ComponentContainer::make(FormTestComponent::make())
            ->statePath('data')
            ->components([MoneyInput::make('price')])
            ->fill(['price' => 20]);

        $this->assertStringContainsString('money($input', $component->getComponent('data.price')->getMask()->toHtml());
    }

    public function validationTester(Field $field, $value, ?callable $assertsCallback = null): true|array
    {
        try {
            ComponentContainer::make(FormTestComponent::make())
                ->statePath('data')
                ->components([$field])
                ->fill([$field->getName() => $value])
                ->validate();
        } catch (ValidationException $exception) {
            if ($assertsCallback) {
                $assertsCallback($exception, $field);
            }

            return [
                'errors' => $exception->validator->errors()->toArray()[$field->getStatePath()],
                'failed' => $exception->validator->failed()[$field->getStatePath()],
            ];
        }

        return true;
    }

    public function testMinAndMaxValues(): void
    {
        $this->assertTrue($this->validationTester(
            (new MoneyInput('totalAmount'))->required()->minValue(100)->maxValue(10000),
            20,
        ));

        $this->validationTester(
            (new MoneyInput('amount'))->required()->minValue(100)->maxValue(10000),
            20,
            function (ValidationException $exception, MoneyInput $field) {
                $this->assertArrayHasKey(MinValueRule::class, $exception->validator->failed()[$field->getStatePath()]);
                $this->assertEquals(
                    'The Amount must be less than or equal to 100.00.',
                    $exception->validator->errors()->toArray()[$field->getStatePath()][0]
                );
            }
        );

        $this->validationTester(
            (new MoneyInput('totalAmount'))->required()->minValue(100)->maxValue(10000),
            200,
            function (ValidationException $exception, MoneyInput $field) {
                $this->assertArrayHasKey(MaxValueRule::class, $exception->validator->failed()[$field->getStatePath()]);
                $this->assertEquals(
                    'The Total Amount must be less than or equal to 100.00.',
                    $exception->validator->errors()->toArray()[$field->getStatePath()][0]
                );
            }
        );

        $this->validationTester(
            (new MoneyInput('totalAmount'))->required()->minValue(100)->maxValue(10000),
            'random string',
            function (ValidationException $exception, MoneyInput $field) {
                $failed = $exception->validator->failed()[$field->getStatePath()];

                $this->assertArrayHasKey(MinValueRule::class, $failed);
                $this->assertArrayHasKey(MaxValueRule::class, $failed);
                $this->assertEquals(
                    'The Total Amount must be a valid numeric value.',
                    $exception->validator->errors()->toArray()[$field->getStatePath()][0]
                );
            }
        );
    }

    public function testUnsupportedCurrency(): void
    {
        $this->expectException(UnsupportedCurrency::class);
        $this->validationTester(
            (new MoneyInput('totalAmount'))->currency('SOMETHING'),
            20,
        );
    }

    public function testAllowLabelToBeOverrided(): void
    {
        $field = (new MoneyInput('price'))->label('Custom Label');

        $component = ComponentContainer::make(FormTestComponent::make())
            ->statePath('data')
            ->components([$field])
            ->fill([$field->getName() => 45345]);

        $field = $component->getComponent('data.price');
        $this->assertEquals('Custom Label', $field->getLabel());
    }

    public function testResolveLabelClosures(): void
    {
        $field = (new MoneyInput('price'))->label(function () {
            return 'Custom Label in Closure';
        });

        $component = ComponentContainer::make(FormTestComponent::make())
            ->statePath('data')
            ->components([$field])
            ->fill([$field->getName() => 45345]);

        $field = $component->getComponent('data.price');
        $this->assertEquals('Custom Label in Closure', $field->getLabel());
    }

    public function testSetDecimalsOnField(): void
    {
        $field     = (new MoneyInput('price'))->decimals(1);
        $component = ComponentContainer::make(FormTestComponent::make())
            ->statePath('data')
            ->components([$field])
            ->fill([$field->getName() => 2345345]);
        $this->assertEquals('2345345', $component->getState()['price']);

        $field     = (new MoneyInput('price'))->decimals(3);
        $component = ComponentContainer::make(FormTestComponent::make())
            ->statePath('data')
            ->components([$field])
            ->fill([$field->getName() => 2345345]);
        $this->assertEquals('2345345', $component->getState()['price']);

        $field     = (new MoneyInput('price'))->decimals(-2);
        $component = ComponentContainer::make(FormTestComponent::make())
            ->statePath('data')
            ->components([$field])
            ->fill([$field->getName() => 2345345]);
        $this->assertEquals('2345345', $component->getState()['price']);
    }
}
