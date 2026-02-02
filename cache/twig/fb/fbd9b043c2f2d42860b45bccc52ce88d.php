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

/* search.twig */
class __TwigTemplate_017aff7b4c8d73ea56266616a1782345 extends Template
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
<h2>Search Vehicles</h2>

<div class=\"card mb-4\">
    <div class=\"card-body\" style=\"padding: 2.5rem;\">
        <form action=\"search.php\" method=\"GET\" class=\"row g-3 align-items-end\">
            <div class=\"col-md-3\">
                <label for=\"keyword\" class=\"form-label\">Keyword</label>
                <input type=\"text\" class=\"form-control form-control-lg\" id=\"keyword\" name=\"keyword\" value=\"";
        // line 10
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["keyword"] ?? null), "html", null, true);
        yield "\" autocomplete=\"off\">
            </div>
            <div class=\"col-md-2\">
                <label for=\"type\" class=\"form-label\">Type</label>
                <select id=\"type\" name=\"type\" class=\"form-select form-select-lg\">
                    <option value=\"\">All</option>
                    <option value=\"Car\" ";
        // line 16
        if ((($context["type"] ?? null) == "Car")) {
            yield "selected";
        }
        yield ">Car</option>
                    <option value=\"Bike\" ";
        // line 17
        if ((($context["type"] ?? null) == "Bike")) {
            yield "selected";
        }
        yield ">Bike</option>
                    <option value=\"Scooter\" ";
        // line 18
        if ((($context["type"] ?? null) == "Scooter")) {
            yield "selected";
        }
        yield ">Scooter</option>
                </select>
            </div>
            <div class=\"col-md-4\">
                <label class=\"form-label\">Price Range (\$)</label>
                <div class=\"input-group input-group-lg\">
                    <span class=\"input-group-text\">Min</span>
                    <input type=\"number\" class=\"form-control\" name=\"min_rate\" placeholder=\"0\" value=\"";
        // line 25
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["min_rate"] ?? null), "html", null, true);
        yield "\">
                    <span class=\"input-group-text\">Max</span>
                    <input type=\"number\" class=\"form-control\" name=\"max_rate\" placeholder=\"100\" value=\"";
        // line 27
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["max_rate"] ?? null), "html", null, true);
        yield "\">
                </div>
            </div>
            <div class=\"col-md-3\">
                <button type=\"submit\" class=\"btn btn-primary btn-lg w-100\" style=\"height: 48px;\">Search</button>
            </div>
        </form>
    </div>
</div>

<h3>Results (";
        // line 37
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["results_count"] ?? null), "html", null, true);
        yield " found)</h3>
<table class=\"table table-striped table-bordered\">
    <thead class=\"table-dark\">
        <tr>
            <th>Plate</th>
            <th>Model</th>
            <th>Type</th>
            <th>Daily Rate</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        ";
        // line 50
        if ((($tmp =  !Twig\Extension\CoreExtension::testEmpty(($context["vehicles"] ?? null))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 51
            yield "            ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["vehicles"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["vehicle"]) {
                // line 52
                yield "                <tr>
                    <td>";
                // line 53
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["vehicle"], "plate_number", [], "any", false, false, false, 53), "html", null, true);
                yield "</td>
                    <td>";
                // line 54
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["vehicle"], "model", [], "any", false, false, false, 54), "html", null, true);
                yield "</td>
                    <td>";
                // line 55
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["vehicle"], "type", [], "any", false, false, false, 55), "html", null, true);
                yield "</td>
                    <td>\$";
                // line 56
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFilter('number_format')->getCallable()(CoreExtension::getAttribute($this->env, $this->source, $context["vehicle"], "daily_rate", [], "any", false, false, false, 56), 2), "html", null, true);
                yield "</td>
                    <td>
                        ";
                // line 58
                if ((CoreExtension::getAttribute($this->env, $this->source, $context["vehicle"], "current_status", [], "any", false, false, false, 58) == "Available")) {
                    // line 59
                    yield "                            <span class=\"badge bg-success\">Available</span>
                        ";
                } else {
                    // line 61
                    yield "                            <span class=\"badge bg-danger\">Rented</span>
                        ";
                }
                // line 63
                yield "                    </td>
                    <td>
                        ";
                // line 65
                if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "user_id", [], "any", false, false, false, 65)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    // line 66
                    yield "                            ";
                    if ((CoreExtension::getAttribute($this->env, $this->source, $context["vehicle"], "current_status", [], "any", false, false, false, 66) == "Available")) {
                        // line 67
                        yield "                                <a href=\"book.php?id=";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["vehicle"], "id", [], "any", false, false, false, 67), "html", null, true);
                        yield "\" class=\"btn btn-sm btn-success\">Book Now</a>
                            ";
                    } else {
                        // line 69
                        yield "                                <span class=\"text-muted\">Not Available</span>
                            ";
                    }
                    // line 71
                    yield "                        ";
                } else {
                    // line 72
                    yield "                            <a href=\"login.php\" class=\"btn btn-sm btn-outline-primary\">Login to Book</a>
                        ";
                }
                // line 74
                yield "                    </td>
                </tr>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['vehicle'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 77
            yield "        ";
        } else {
            // line 78
            yield "            <tr>
                <td colspan=\"6\" class=\"text-center\">No vehicles match your search criteria.</td>
            </tr>
        ";
        }
        // line 82
        yield "    </tbody>
</table>

<script src=\"../assets/js/search-autocomplete.js\"></script>

";
        // line 87
        yield from $this->load("footer.twig", 87)->unwrap()->yield($context);
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "search.twig";
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
        return array (  208 => 87,  201 => 82,  195 => 78,  192 => 77,  184 => 74,  180 => 72,  177 => 71,  173 => 69,  167 => 67,  164 => 66,  162 => 65,  158 => 63,  154 => 61,  150 => 59,  148 => 58,  143 => 56,  139 => 55,  135 => 54,  131 => 53,  128 => 52,  123 => 51,  121 => 50,  105 => 37,  92 => 27,  87 => 25,  75 => 18,  69 => 17,  63 => 16,  54 => 10,  44 => 2,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "search.twig", "/Applications/XAMPP/xamppfiles/htdocs/VR.M/templates/search.twig");
    }
}
