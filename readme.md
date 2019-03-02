# AbstractEnum
This abstract class allows you to simulate the enum type found in many other languages.

## Examples
```php
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
```