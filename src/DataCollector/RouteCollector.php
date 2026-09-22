<?php

declare(strict_types=1);

namespace Ikoss\Mezzio\DebugBar\DataCollector;

use DebugBar\DataCollector\AssetProvider;
use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\Renderable;
use Laminas\Diactoros\ServerRequestFactory;
use Mezzio\Router\RouterInterface;

use function is_string;

class RouteCollector extends DataCollector implements Renderable, AssetProvider
{
    protected string $name;

    protected array $config;

    protected RouterInterface $router;

    public function __construct(RouterInterface $router, array $config)
    {
        $this->router = $router;
        $this->config = $config;
        $this->name   = 'Route';
    }

    public function collect(): array
    {
        $data = $this->getRouteInformation();
        foreach ($data as $k => $v) {
            if ($this->isHtmlVarDumperUsed()) {
                $v = $this->getVarDumper()->renderVar($v);
            } elseif (! is_string($v)) {
                $v = $this->getDataFormatter()->formatVar($v);
            }
            $data[$k] = $v;
        }
        return $data;
    }

    /**
     * @return string[]
     */
    protected function getRouteInformation(): array
    {
        $request = ServerRequestFactory::fromGlobals(
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        );

        $match = $this->router->match($request);

        return $this->config[ 'routes' ][ $match->getMatchedRouteName() ] ?? ['no data'];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getAssets()
    {
        return $this->isHtmlVarDumperUsed() ? $this->getVarDumper()->getAssets() : [];
    }

    /**
     * @return array
     */
    public function getWidgets()
    {
        $name   = $this->getName();
        $widget = $this->isHtmlVarDumperUsed()
            ? "PhpDebugBar.Widgets.HtmlVariableListWidget"
            : "PhpDebugBar.Widgets.VariableListWidget";
        return [
            "$name" => [
                "icon"    => "gear",
                "widget"  => $widget,
                "map"     => "$name",
                "default" => "{}",
            ],
        ];
    }
}
