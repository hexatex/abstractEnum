<?php

namespace Hexatex\AbstractEnum;

use ReflectionClass;
use Exception;
use InvalidArgumentException;

abstract class AbstractEnum /* Examples at the bottom */
{
    private $className; /* Acme\Utensil */
    private $enumName; /* Utensil */
    private $enumOption; /* fork, steakKnife */
    private $key; /* 1 */

    public function __construct(string $enumOption)
    {
        $reflectionClass = new ReflectionClass(get_called_class());
        $this->className = $reflectionClass->getName();
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


class UtensilType extends abstractEnum
{
    const spoon = 2;
    const steakKnife = 3;
    // Or namespace them like this:
    const fork = 'UtensilType@fork';
    // ...Although if you're storing them in a database consider using numbers.
}

interface IUtensil
{
    public function getType(): UtensilType;
}

abstract class Utensil implements IUtensil
{
    //...
}

class Spoon extends Utensil
{
    public function getType(): UtensilType
    {
        return UtensilType::spoon();
    }
}

class Drawer
{
    // ...
    public function addUtensil(Utensil $utensil) {
        switch ($utensil->getType()) {
            case UtensilType::fork():
                // polish prongs...
                // place in fork slot
                break;
            case UtensilType::spoon():
                // Shine spoon on shirt
                // Place in spoon slot
                echo "Oooooo spoon so shiny";
                break;
        }
    }
}

$drawer = new Drawer;
$spoon = new Spoon;
$drawer->addUtensil($spoon);

// $drawer->grabUtensil(UtensilType::spoon());

// // UtensilType::existsOrFail('salidKnife'); // throws exception
// if (UtensilType::exists('salidKnife') == false) {
//     echo "Salad knives don't exist. \n";
// }

// print_r(UtensilType::spoon());

// echo "UtensilType::spoon()\n";

// if (UtensilType::spoon() == UtensilType::steakKnife()) // False
//     echo "UtensilType::spoon() == UtensilType::steakKnife()\n";
// if (UtensilType::steakKnife() == UtensilType::steakKnife()) // True
//     echo "UtensilType::steakKnife() == UtensilType::steakKnife()\n";

// print_r(get_class(UtensilType::steakKnife()));