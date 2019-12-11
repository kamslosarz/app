<?php


namespace unit\Form\Login;

use App\Form\Login\LoginForm;
use ReflectionException;
use ReflectionProperty;
use Tests\FormExpectation;
use Tests\TestCase\FormTestCase;
use Validator\Constraint\RegexConstraint;

class LoginFormTest extends FormTestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldConstructLoginForm()
    {
        $formExpectation = new FormExpectation();
        $formExpectation
            ->expectFieldAddition('addInput', [['name' => 'login']])
            ->expectFieldAddition('addInput', [['name' => 'password']])
            ->expectFieldAddition('addButton', [
                ['name' => 'submit', 'value' => 'save', 'id' => 'login-button'], ['label' => 'save']
            ])
            ->expectConstraintAddition('login', RegexConstraint::class, ['regex' => '/[a-zA-Z0-9-.]{4,16}/']);

        $loginForm = $this->getForm(LoginForm::class, $formExpectation);

        $attributesProperty = new ReflectionProperty($loginForm, 'attributes');
        $attributesProperty->setAccessible(true);
        $attributes = $attributesProperty->getValue($loginForm);

        $this->assertInstanceOf(LoginForm::class, $loginForm);
        $this->assertEquals([
            'name' => 'login-form',
            'method' => 'post'
        ], $attributes->__toArray());
    }
}