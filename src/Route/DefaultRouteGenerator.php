<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Teebb\SBAdmin2Bundle\Route;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Teebb\SBAdmin2Bundle\Admin\AdminInterface;

/**
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class DefaultRouteGenerator implements RouteGeneratorInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var RoutesCache
     */
    private $cache;

    /**
     * @var array
     */
    private $caches = [];

    /**
     * @var string[]
     */
    private $loaded = [];

    public function __construct(RouterInterface $router, RoutesCache $cache)
    {
        $this->router = $router;
        $this->cache = $cache;
    }

    public function generate($name, array $parameters = [], $absolute = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->router->generate($name, $parameters, $absolute);
    }

    public function generateUrl(
        AdminInterface $admin,
        $name,
        array $parameters = [],
        $absolute = UrlGeneratorInterface::ABSOLUTE_PATH
    )
    {
        $arrayRoute = $this->generateMenuUrl($admin, $name, $parameters, $absolute);

        return $this->router->generate($arrayRoute['route'], $arrayRoute['routeParameters'], $arrayRoute['routeAbsolute']);
    }

    public function generateMenuUrl(
        AdminInterface $admin,
        $name,
        array $parameters = [],
        $absolute = UrlGeneratorInterface::ABSOLUTE_PATH
    )
    {
        // if the admin is a child we automatically append the parent's id
        if ($admin->isChild() && $admin->getParent()->hasRequest()) {
            // twig template does not accept variable hash key ... so cannot use admin.idparameter ...
            // switch value

            if (isset($parameters['id'])) {
                $parameters[$admin->getIdParameter()] = $parameters['id'];
                unset($parameters['id']);
            }

            for ($parentAdmin = $admin->getParent(); null !== $parentAdmin; $parentAdmin = $parentAdmin->getParent()) {
                $parameters[$parentAdmin->getIdParameter()] = $admin->getParent()->getRequest()->attributes->get($parentAdmin->getIdParameter());
            }
    
        }

        $code = $this->getCode($admin, $name);

        if (!\array_key_exists($code, $this->caches)) {
            throw new \RuntimeException(sprintf('unable to find the route `%s`', $code));
        }

        return [
            'route' => $this->caches[$code],
            'routeParameters' => $parameters,
            'routeAbsolute' => $absolute,
        ];
    }

    public function hasAdminRoute(AdminInterface $admin, $name)
    {
        return \array_key_exists($this->getCode($admin, $name), $this->caches);
    }

    private function getCode(AdminInterface $admin, string $name): string
    {
        $this->loadCache($admin);

        // someone provide the fullname
        if (!$admin->isChild() && \array_key_exists($name, $this->caches)) {
            return $name;
        }

        $codePrefix = $admin->getBaseCodeRoute();

        // someone provide a code, so it is a child
        if (strpos($name, '.')) {
            return $codePrefix . '|' . $name;
        }

        return $codePrefix . '.' . $name;
    }

    private function loadCache(AdminInterface $admin): void
    {
        if ($admin->isChild()) {
            $this->loadCache($admin->getParent());
            return;
        }

        if (\in_array($admin->getAdminServiceId(), $this->loaded, true)) {
            return;
        }

        $this->caches = array_merge($this->cache->load($admin), $this->caches);

        $this->loaded[] = $admin->getAdminServiceId();
    }
}
