<?php

namespace unit\Form\Contact;

use App\Form\Contact\ContactForm;
use ReflectionException;
use ReflectionProperty;
use Tests\FormExpectation;
use Tests\TestCase\FormTestCase;

class ContactFormTest extends FormTestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldConstructContactForm()
    {
        $formExpectation = new FormExpectation();
        $formExpectation->expectFieldAddition('addInput', [['name' => 'title']])
            ->expectFieldAddition('addTextarea', [['name' => 'message']])
            ->expectFieldAddition('addButton', [['name' => 'submit'], ['label' => 'save']]);

        $contactForm = $this->getForm(ContactForm::class, $formExpectation);

        $attributesProperty = new ReflectionProperty($contactForm, 'attributes');
        $attributesProperty->setAccessible(true);
        $attributes = $attributesProperty->getValue($contactForm);

        $this->assertInstanceOf(ContactForm::class, $contactForm);
        $this->assertEquals([
            'name' => 'contact-form',
            'method' => 'post'
        ], $attributes->__toArray());
    }
}