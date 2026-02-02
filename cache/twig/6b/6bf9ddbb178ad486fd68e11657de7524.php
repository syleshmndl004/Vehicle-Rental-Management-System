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

/* index.twig */
class __TwigTemplate_c308f9fe2d3f2c8927db1d91b1cd3109 extends Template
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
        yield from $this->load("header.twig", 1)->unwrap()->yield($context);
        // line 2
        yield "
<h2>Vehicle Inventory</h2>

";
        // line 5
        if ((($tmp = ($context["message"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 6
            yield "    <div class=\"alert alert-success\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["message"] ?? null), "html", null, true);
            yield "</div>
";
        }
        // line 8
        if ((($tmp = ($context["error"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 9
            yield "    <div class=\"alert alert-danger\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["error"] ?? null), "html", null, true);
            yield "</div>
";
        }
        // line 11
        yield "
<div class=\"card\">
    <div class=\"card-body\">
        <table class=\"table table-striped table-bordered\">
            <thead class=\"table-dark\">
                <tr>
                    <th>Plate Number</th>
                    <th>Model</th>
                    <th>Type</th>
                    <th>Daily Rate</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                ";
        // line 26
        if ((($tmp =  !Twig\Extension\CoreExtension::testEmpty(($context["vehicles"] ?? null))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 27
            yield "                    ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["vehicles"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["vehicle"]) {
                // line 28
                yield "                        <tr>
                            <td>";
                // line 29
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["vehicle"], "plate_number", [], "any", false, false, false, 29), "html", null, true);
                yield "</td>
                            <td>";
                // line 30
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["vehicle"], "model", [], "any", false, false, false, 30), "html", null, true);
                yield "</td>
                            <td>";
                // line 31
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["vehicle"], "type", [], "any", false, false, false, 31), "html", null, true);
                yield "</td>
                            <td>\$";
                // line 32
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFilter('number_format')->getCallable()(CoreExtension::getAttribute($this->env, $this->source, $context["vehicle"], "daily_rate", [], "any", false, false, false, 32), 2), "html", null, true);
                yield "</td>
                            <td>
                                ";
                // line 34
                if ((CoreExtension::getAttribute($this->env, $this->source, $context["vehicle"], "current_status", [], "any", false, false, false, 34) == "Available")) {
                    // line 35
                    yield "                                    <span class=\"badge bg-success\">Available</span>
                                ";
                } else {
                    // line 37
                    yield "                                    <span class=\"badge bg-danger\">Rented</span>
                                ";
                }
                // line 39
                yield "                            </td>
                            <td class=\"actions\">
                                ";
                // line 41
                if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "user_id", [], "any", false, false, false, 41)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    // line 42
                    yield "                                    ";
                    if ((CoreExtension::getAttribute($this->env, $this->source, $context["vehicle"], "current_status", [], "any", false, false, false, 42) == "Available")) {
                        // line 43
                        yield "                                        <a href=\"book.php?id=";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["vehicle"], "id", [], "any", false, false, false, 43), "html", null, true);
                        yield "\" class=\"btn btn-sm btn-success\">Book Now</a>
                                    ";
                    }
                    // line 45
                    yield "                                    ";
                    if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "is_admin", [], "any", false, false, false, 45)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                        // line 46
                        yield "                                        <a href=\"edit.php?id=";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["vehicle"], "id", [], "any", false, false, false, 46), "html", null, true);
                        yield "\" class=\"btn btn-sm btn-warning\">Edit</a>
                                        <a href=\"delete.php?id=";
                        // line 47
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["vehicle"], "id", [], "any", false, false, false, 47), "html", null, true);
                        yield "\" class=\"btn btn-sm btn-danger\" onclick=\"return confirm('Delete this vehicle?')\">Delete</a>
                                    ";
                    }
                    // line 49
                    yield "                                ";
                }
                // line 50
                yield "                            </td>
                        </tr>
                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['vehicle'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 53
            yield "                ";
        } else {
            // line 54
            yield "                    <tr>
                        <td colspan=\"6\" class=\"text-center\">No vehicles found.</td>
                    </tr>
                ";
        }
        // line 58
        yield "            </tbody>
        </table>
    </div>
</div>

";
        // line 63
        yield from $this->load("footer.twig", 63)->unwrap()->yield($context);
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "index.twig";
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
        return array (  174 => 63,  167 => 58,  161 => 54,  158 => 53,  150 => 50,  147 => 49,  142 => 47,  137 => 46,  134 => 45,  128 => 43,  125 => 42,  123 => 41,  119 => 39,  115 => 37,  111 => 35,  109 => 34,  104 => 32,  100 => 31,  96 => 30,  92 => 29,  89 => 28,  84 => 27,  82 => 26,  65 => 11,  59 => 9,  57 => 8,  51 => 6,  49 => 5,  44 => 2,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "index.twig", "/Applications/XAMPP/xamppfiles/htdocs/VR.M/templates/index.twig");
    }
}
