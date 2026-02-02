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

/* bookings.twig */
class __TwigTemplate_89dc287e671137d324efac3488dbbbda extends Template
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
<h2>";
        // line 3
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "is_admin", [], "any", false, false, false, 3)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            yield "All Bookings";
        } else {
            yield "My Bookings";
        }
        yield "</h2>

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
        <table class=\"table table-striped\">
            <thead>
                <tr>
                    ";
        // line 17
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "is_admin", [], "any", false, false, false, 17)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 18
            yield "                        <th>User</th>
                    ";
        }
        // line 20
        yield "                    <th>Vehicle</th>
                    <th>Plate</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Cost</th>
                    <th>Status</th>
                    <th>Booked On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                ";
        // line 31
        if ((($tmp =  !Twig\Extension\CoreExtension::testEmpty(($context["bookings"] ?? null))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 32
            yield "                    ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["bookings"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["booking"]) {
                // line 33
                yield "                        <tr>
                            ";
                // line 34
                if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "is_admin", [], "any", false, false, false, 34)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    // line 35
                    yield "                                <td><strong>";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["booking"], "username", [], "any", false, false, false, 35), "html", null, true);
                    yield "</strong></td>
                            ";
                }
                // line 37
                yield "                            <td>";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["booking"], "model", [], "any", false, false, false, 37), "html", null, true);
                yield "</td>
                            <td>";
                // line 38
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["booking"], "plate_number", [], "any", false, false, false, 38), "html", null, true);
                yield "</td>
                            <td>";
                // line 39
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, $context["booking"], "start_date", [], "any", false, false, false, 39), "M d, Y"), "html", null, true);
                yield "</td>
                            <td>";
                // line 40
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, $context["booking"], "end_date", [], "any", false, false, false, 40), "M d, Y"), "html", null, true);
                yield "</td>
                            <td>\$";
                // line 41
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->env->getFilter('number_format')->getCallable()(CoreExtension::getAttribute($this->env, $this->source, $context["booking"], "total_cost", [], "any", false, false, false, 41), 2), "html", null, true);
                yield "</td>
                            <td><span class=\"badge bg-info\">";
                // line 42
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["booking"], "booking_status", [], "any", false, false, false, 42), "html", null, true);
                yield "</span></td>
                            <td>";
                // line 43
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, $context["booking"], "created_at", [], "any", false, false, false, 43), "M d, Y"), "html", null, true);
                yield "</td>
                            <td>
                                <a href=\"edit_booking.php?id=";
                // line 45
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["booking"], "booking_id", [], "any", false, false, false, 45), "html", null, true);
                yield "\" class=\"btn btn-sm btn-warning\">Edit</a>
                                <form action=\"bookings.php\" method=\"POST\" style=\"display:inline;\" onsubmit=\"return confirm('Delete this booking?');\">
                                    <input type=\"hidden\" name=\"action\" value=\"delete\">
                                    <input type=\"hidden\" name=\"booking_id\" value=\"";
                // line 48
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["booking"], "booking_id", [], "any", false, false, false, 48), "html", null, true);
                yield "\">
                                    <input type=\"hidden\" name=\"csrf_token\" value=\"";
                // line 49
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["csrf_token"] ?? null), "html", null, true);
                yield "\">
                                    <button type=\"submit\" class=\"btn btn-sm btn-danger\">Delete</button>
                                </form>
                            </td>
                        </tr>
                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['booking'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 55
            yield "                ";
        } else {
            // line 56
            yield "                    <tr><td colspan=\"";
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "is_admin", [], "any", false, false, false, 56)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                yield "9";
            } else {
                yield "8";
            }
            yield "\" class=\"text-center\">No bookings yet.</td></tr>
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
        return "bookings.twig";
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
        return array (  189 => 63,  182 => 58,  172 => 56,  169 => 55,  157 => 49,  153 => 48,  147 => 45,  142 => 43,  138 => 42,  134 => 41,  130 => 40,  126 => 39,  122 => 38,  117 => 37,  111 => 35,  109 => 34,  106 => 33,  101 => 32,  99 => 31,  86 => 20,  82 => 18,  80 => 17,  72 => 11,  66 => 9,  64 => 8,  58 => 6,  56 => 5,  47 => 3,  44 => 2,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "bookings.twig", "/Applications/XAMPP/xamppfiles/htdocs/VR.M/templates/bookings.twig");
    }
}
