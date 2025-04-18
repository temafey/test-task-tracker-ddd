<?php

declare(strict_types=1);

namespace Micro;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    protected const SERVICE_NAME = 'tracker';

    protected const DOMAINS = ['task'];

    public function getCacheDir(): string
    {
        if ($this->environment === 'prod') {
            //return '/dev/shm/symfony-app/cache/' . $this->environment;
        }

        return CACHE_PATH . $this->environment;
    }

    public function getLogDir(): string
    {
        return LOG_PATH;
    }

    public function registerBundles(): iterable
    {
        $contents = require CONFIG_PATH . 'bundles.php';

        if (file_exists(CONFIG_PATH . 'bundles_' . $this->environment . '.php')) {
            $specContents = require CONFIG_PATH . 'bundles_' . $this->environment . '.php';
            $contents = array_merge_recursive($contents, $specContents);
        }

        foreach ($contents as $class => $envs) {
            if ((isset($envs['all']) || isset($envs[$this->environment])) && class_exists($class)) {
                yield new $class();
            }
        }
    }

    /**
     * @SuppressWarnings(PHPMD)
     */
    private function configureContainer(
        ContainerConfigurator $container,
        LoaderInterface $loader,
        ContainerBuilder $builder
    ): void {
        $builder->setParameter('container.autowiring.strict_mode', true);
        $builder->setParameter('container.dumper.inline_class_loader', true);
        $loader->load(CONFIG_PATH . '{packages}/*' . self::CONFIG_EXTS, 'glob');

        if (is_dir(CONFIG_PATH . 'packages/' . $this->environment)) {
            $loader->load(CONFIG_PATH . '{packages}/' . $this->environment . '/*' . self::CONFIG_EXTS, 'glob');
        }
        $loader->load(CONFIG_PATH . 'parameters' . self::CONFIG_EXTS, 'glob');
        $loader->load(CONFIG_PATH . '{services}' . self::CONFIG_EXTS, 'glob');
        $loader->load(CONFIG_PATH . '{services}/*' . self::CONFIG_EXTS, 'glob');
        $loader->load(CONFIG_PATH . '{services}_' . $this->environment . self::CONFIG_EXTS, 'glob');

        if (is_dir(CONFIG_PATH . 'services/' . $this->environment)) {
            $loader->load(CONFIG_PATH . '{services}/' . $this->environment . '/*' . self::CONFIG_EXTS, 'glob');
        }
        $loader->load(CONFIG_PATH . '{domains}/*/*/{parameters}' . self::CONFIG_EXTS, 'glob');
        $loader->load(CONFIG_PATH . '{domains}/*/*/{services}' . self::CONFIG_EXTS, 'glob');
        $loader->load(CONFIG_PATH . '{domains}/*/*/{services}/*' . self::CONFIG_EXTS, 'glob');

        foreach (self::DOMAINS as $domainName) {
            $servicesEnvDirName = sprintf(
                '%sdomains/%s/%s/services/%s',
                CONFIG_PATH,
                self::SERVICE_NAME,
                $domainName,
                $this->environment
            );
            $servicesEnvFileName = sprintf(
                '%sdomains/%s/%s/services_%s',
                CONFIG_PATH,
                self::SERVICE_NAME,
                $domainName,
                $this->environment
            );
            if (is_dir($servicesEnvDirName)) {
                $loader->load(sprintf('%s/*%s', $servicesEnvDirName, self::CONFIG_EXTS), 'glob');
            }
            if (file_exists($servicesEnvFileName)) {
                $loader->load(sprintf('%s%s', $servicesEnvFileName, self::CONFIG_EXTS), 'glob');
            }
        }
    }

    /**
     * @SuppressWarnings(PHPMD)
     */
    private function configureRoutes(RoutingConfigurator $routes): void
    {
        if (is_dir(CONFIG_PATH . '/routes/')) {
            $routes->import(CONFIG_PATH . '/routes/*' . self::CONFIG_EXTS, 'glob');
        }

        if (is_dir(CONFIG_PATH . '/routes/' . $this->environment)) {
            $routes->import(CONFIG_PATH . '/routes/' . $this->environment . '/**/*' . self::CONFIG_EXTS, 'glob');
        }

        $routes->import(CONFIG_PATH . '/routes' . self::CONFIG_EXTS, 'glob');
    }
}
