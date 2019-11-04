<?php


namespace Form\FormView;

use Collection\Collection;
use Form\Field\FormField;
use Form\Form;

class FormView
{
    use AttributesAsString;

    private $htmlElements;

    /**
     * FormView constructor.
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->htmlElements = new Collection();
        $this->attributes = $form->getAttributes();
        $this->initialize($form);
    }

    /**
     * @param Form $form
     */
    private function initialize(Form $form)
    {
        /** @var FormField $field */
        foreach($form->getFields() as $field)
        {
            $this->htmlElements->append(new HtmlElement($this->getResource($field), $this->adjustFieldName($field->getAttributes()), $field->getOptions()));
        }
    }

    /**
     * @param FormField $field
     * @return string
     */
    protected function getResource(FormField $field): string
    {
        return sprintf('%s.phtml', str_replace('\\', '/', strtolower(get_class($field))));
    }

    /**
     * @return Collection
     */
    public function getElements(): Collection
    {
        return $this->htmlElements;
    }

    private function adjustFieldName(Collection $attributes): Collection
    {
        if(!$this->attributes->get('name'))
        {
            return $attributes;
        }

        $name = [];
        preg_match("/(^[^\[^\]]{1,})(.{0,})|(^\[+[^\[^\]]{0,}+])(.{0,})/", $attributes->get('name', ''), $name);
        list(, $fieldName, $suffix) = $name;
        $attributes->set('name', sprintf('%s[%s]%s', $this->attributes->get('name'), $fieldName, $suffix));

        return $attributes;
    }
}