<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/8
 * Time: 16:31
 */

namespace App\Http\Form;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Traits\Macroable;

class Field
{
    use Macroable;

    /**
     * Column name.
     *
     * @var string
     */
    protected $column = '';

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * The validation rules for creation.
     *
     * @var array|\Closure
     */
    public $creationRules = [];

    /**
     * The validation rules for updates.
     *
     * @var array|\Closure
     */
    public $updateRules = [];

    /**
     * Validation messages.
     *
     * @var array
     */
    protected $validationMessages = [];

    /**
     * Parent form.
     *
     * @var Form
     */
    protected $form = null;


    /**
     * Field constructor.
     * @param string $column
     */
    public function __construct($column = '')
    {
        $this->column = $column;
    }

    /**
     * Get column of the field.
     *
     * @return string
     */
    public function column()
    {
        return $this->column;
    }

    /**
     * Prepare for a field value before update or insert.
     *
     * @param $value
     *
     * @return mixed
     */
    public function prepare($value)
    {
        return $value;
    }

    /**
     * @param Form $form
     *
     * @return $this
     */
    public function setForm(Form $form = null)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Set the validation rules for the field.
     *
     * @param array|string $rules
     * @param array                 $messages
     *
     * @return $this
     */
    public function rules($rules = null, $messages = [])
    {
        $this->rules = $this->mergeRules($rules, $this->rules);

        $this->setValidationMessages('default', $messages);

        return $this;
    }

    /**
     * @param string|array         $input
     * @param string|array         $original
     *
     * @return array|Closure
     */
    protected function mergeRules($input, $original)
    {
        if (!empty($original)) {
            $original = $this->formatRules($original);
        }

        $rules = array_merge($original, $this->formatRules($input));

        return $rules;
    }

    /**
     * Format validation rules.
     *
     * @param array|string $rules
     *
     * @return array
     */
    protected function formatRules($rules)
    {
        if (is_string($rules)) {
            $rules = array_filter(explode('|', $rules));
        }

        return array_filter((array) $rules);
    }

    /**
     * Set validation messages for column.
     *
     * @param string $key
     * @param array  $messages
     *
     * @return $this
     */
    public function setValidationMessages($key, array $messages)
    {
        $this->validationMessages[$key] = $messages;

        return $this;
    }

    /**
     * Get validator for this field.
     *
     * @param array $input
     *
     * @return bool|\Illuminate\Contracts\Validation\Validator|mixed
     */
    public function getValidator(array $input)
    {
        $rules = [];

        if (!$fieldRules = $this->getRules()) {
            return false;
        }

        if (!Arr::has($input, $this->column)) {
            return false;
        }

        $rules[$this->column] = $fieldRules;

        return validator($input, $rules, $this->getValidationMessages());
    }

    /**
     * Get validation messages for the field.
     *
     * @return array|mixed
     */
    public function getValidationMessages()
    {
        // Default validation message.
        $messages = $this->validationMessages['default'] ?? [];

        if (request()->isMethod('POST')) {
            $messages = $this->validationMessages['creation'] ?? $messages;
        } elseif (request()->isMethod('PUT')) {
            $messages = $this->validationMessages['update'] ?? $messages;
        }

        return $messages;
    }

    /**
     * Get field validation rules.
     *
     * @return string
     */
    protected function getRules()
    {
        if (request()->isMethod('POST')) {
            $rules = $this->creationRules ?: $this->rules;
        } elseif (request()->isMethod('PUT')) {
            $rules = $this->updateRules ?: $this->rules;
        } else {
            $rules = $this->rules;
        }

        if (is_string($rules)) {
            $rules = array_filter(explode('|', $rules));
        }

        return $rules;
    }

}
