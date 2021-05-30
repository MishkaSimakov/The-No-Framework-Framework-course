<?php

namespace App\Providers;

use App\Rules\ExistsRule;
use App\Rules\Rule;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use ReflectionClass;
use Valitron\Validator;

class ValidationServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    /*
    * list of custom validation rules
    */
    protected array $rules = [
        'exists' => ExistsRule::class
    ];

    public function boot()
    {
        $container = $this->getContainer();

        foreach ($this->rules as $name => $rule) {
            if (!$this->isCustomValidationRule($rule)) {
                continue;
            }

            $rule = $container->get($rule);

            $this->addCustomRuleToValidator($name, $rule);
        }
    }

    public function register()
    {
        //
    }

    protected function isCustomValidationRule(string $rule): bool
    {
        $class = new ReflectionClass($rule);

        return $class->implementsInterface(Rule::class);
    }

    protected function addCustomRuleToValidator(string $name, Rule $rule)
    {
        Validator::addRule($name, function ($field, $value, $params, $fields) use ($rule) {
            return $rule->validate($field, $value, $params, $fields);
        }, $rule->message());
    }
}