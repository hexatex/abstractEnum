<?php

namespace Hexatex\AbstractEnum;

use ReflectionClass;
use Exception;
use InvalidArgumentException;

abstract class AbstractEnum /* Examples at the bottom */
{
    private $className;
    private $enumName; /* Utensil */
    private $enumOption; /* fork, steakKnife */
    private $key; /* 1 */

    public function __construct(string $enumOption)
    {
        $reflectionClass = new ReflectionClass(get_called_class());
        $this->className = $reflectionClass->getName(); /* Acme\Utensil */
        $this->enumName = $reflectionClass->getShortName();
        $this->enumOption = $enumOption;

        if (defined("{$this->className}::{$this->enumOption}")) {
            $this->key = constant("{$this->className}::{$this->enumOption}");
        }
    }

    /*
     * Accessors
     */
    public function getValue() /* : mixed */
    {
        if ($this->key) {
            return $this->key;
        } else {
            return "{$this->className}::{$this->enumOption}";
        }
    }

    /*
     * Static Methods
     */
    static public function exists(string $enumOption)
    {
        $abstractEnum = new static($enumOption);

        return $abstractEnum;
    }

    static public function existsOrFail(string $enumOption): abstractEnum
    {
        $abstractEnum = self::exists($enumOption);

        if (!$abstractEnum->key) {
            throw new InvalidArgumentException("The enum option code {$abstractEnum->enumName}::{$abstractEnum->enumOption}() is not a defined constant in the {$abstractEnum->className} class.");
        }

        return $abstractEnum;
    }

    /*
     * Magic Methods
     */
    static public function __callStatic($enumOption, $arguments): abstractEnum
    {
        $abstractEnum = self::existsOrFail($enumOption);
        return $abstractEnum;
    }

    public function __toString(): string
    {
        return (string)$this->getValue();
    }
}

/*
class Utensil extends abstractEnum
{
    const spoon = 2;
    const steakKnife = 3;
    const stringCheese = 'lol string cheese isnt a Utensil. What>??!!';
}

// Utensil::existsOrFail('asdBbb');

echo "Utensil::spoon()";
print_r(Utensil::spoon());

echo "echo Utensil::spoon()\n";
echo Utensil::spoon() . "\n";

if (Utensil::spoon() == Utensil::steakKnife())
    echo "Utensil::spoon() == Utensil::steakKnife()\n";
if (Utensil::steakKnife() == Utensil::steakKnife())
    echo "Utensil::steakKnife() == Utensil::steakKnife()\n";
if (Utensil::spoon() == Utensil::spoon())
    echo "Utensil::spoon() == Utensil::spoon()\n";

print_r(get_class(Utensil::steakKnife()));
echo "\n";
echo Utensil::stringCheese();
*/