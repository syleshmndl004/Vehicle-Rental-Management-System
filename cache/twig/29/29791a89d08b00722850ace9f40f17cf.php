<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* header.twig */
class __TwigTemplate_3418c632f4c7648fedaeb10b2928b5f8 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield "<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>";
        // line 6
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((array_key_exists("page_title", $context)) ? (Twig\Extension\CoreExtension::default(($context["page_title"] ?? null), "Vehicle Rental System")) : ("Vehicle Rental System")), "html", null, true);
        yield "</title>
    <link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css\" rel=\"stylesheet\">
    <link rel=\"stylesheet\" href=\"../assets/css/style.css\">
</head>
<body class=\"bg-light\">

<nav class=\"navbar navbar-expand-lg navbar-dark bg-dark mb-4\">
    <div class=\"container\">
        <a class=\"navbar-brand\" href=\"";
        // line 14
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["base_path"] ?? null), "html", null, true);
        yield "index.php\">🚗 ";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["app_name"] ?? null), "html", null, true);
        yield "</a>
        <button class=\"navbar-toggler\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#navbarNav\">
            <span class=\"navbar-toggler-icon\"></span>
        </button>
        <div class=\"collapse navbar-collapse\" id=\"navbarNav\">
            <ul class=\"navbar-nav me-auto mb-2 mb-lg-0\">
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"";
        // line 21
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["base_path"] ?? null), "html", null, true);
        yield "index.php\">Home</a>
                </li>
                <li class=\"nav-item\">
                    <a class=\"nav-link\" href=\"";
        // line 24
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["base_path"] ?? null), "html", null, true);
        yield "search.php\">🔍 Search</a>
                </li>
                ";
        // line 26
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "user_id", [], "any", false, false, false, 26)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 27
            yield "                    ";
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "is_admin", [], "any", false, false, false, 27)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 28
                yield "                        <li class=\"nav-item\">
                            <a class=\"nav-link\" href=\"";
                // line 29
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["base_path"] ?? null), "html", null, true);
                yield "add.php\">➕ Add Vehicle</a>
                        </li>
                    ";
            }
            // line 32
            yield "                    <li class=\"nav-item\">
                        <a class=\"nav-link\" href=\"";
            // line 33
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["base_path"] ?? null), "html", null, true);
            yield "bookings.php\">
                            📋 ";
            // line 34
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "is_admin", [], "any", false, false, false, 34)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                yield "Bookings";
            } else {
                yield "My Bookings";
            }
            // line 35
            yield "                        </a>
                    </li>
                ";
        }
        // line 38
        yield "            </ul>
            <div class=\"d-flex\">
                ";
        // line 40
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "user_id", [], "any", false, false, false, 40)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 41
            yield "                    <span class=\"navbar-text me-3\">
                        Welcome, <strong>";
            // line 42
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "username", [], "any", false, false, false, 42), "html", null, true);
            yield "</strong>
                    </span>
                    <a href=\"";
            // line 44
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["base_path"] ?? null), "html", null, true);
            yield "logout.php\" class=\"btn btn-outline-light\">Logout</a>
                ";
        } else {
            // line 46
            yield "                    <a href=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["base_path"] ?? null), "html", null, true);
            yield "login.php\" class=\"btn btn-outline-light me-2\">Login</a>
                    <a href=\"";
            // line 47
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["base_path"] ?? null), "html", null, true);
            yield "register.php\" class=\"btn btn-primary\">Sign Up</a>
                ";
        }
        // line 49
        yield "            </div>
        </div>
    </div>
</nav>

<div class=\"container\">
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "header.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  144 => 49,  139 => 47,  134 => 46,  129 => 44,  124 => 42,  121 => 41,  119 => 40,  115 => 38,  110 => 35,  104 => 34,  100 => 33,  97 => 32,  91 => 29,  88 => 28,  85 => 27,  83 => 26,  78 => 24,  72 => 21,  60 => 14,  49 => 6,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "header.twig", "/Applications/XAMPP/xamppfiles/htdocs/VR.M/templates/header.twig");
    }
}
