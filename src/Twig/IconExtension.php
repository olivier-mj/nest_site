<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class IconExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('icon', [$this, 'svgIcon'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Génère le code HTML pour une icone SVG.
     */
    public function svgIcon(string $name, ?int $size = null, ?string $class = null, ?string $alt = null): string
    {
        $attrs = '';
        if ($size) {
            $attrs = " width=\"{$size}px\" height=\"{$size}px\"";
        }
        $className = '';
        if ($class) {
            $className = $class;
        }
        $altDescription ='';
        if ($alt) {
            $altDescription = "alt=\"$alt\"";
        }

        $title= '';
        if ($alt) {
            $title = "<title>{$alt}</title>";
        }
        return <<<HTML
        <svg class="icon icon-{$name} {$className}" {$attrs} {$altDescription}>
            {$title}
          <use xlink:href="/sprite.svg?logo#{$name}"></use>
        </svg>
        HTML;
    }

}