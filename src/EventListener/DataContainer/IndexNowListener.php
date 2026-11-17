<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024-2026, Bianka Kriege
 * @package: company-data
 */

namespace BiankaKriege\ContaoCompanyData\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\Routing\ContentUrlGenerator;
use Contao\DataContainer;
use Contao\Model;
use Contao\PageModel;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCallback(table: 'tl_page', target: 'config.onsubmit')]
#[AsCallback(table: 'tl_news', target: 'config.onsubmit')]
#[AsCallback(table: 'tl_calendar_events', target: 'config.onsubmit')]
final readonly class IndexNowListener
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private ContentUrlGenerator $urlGenerator,
        private LoggerInterface $logger,
        #[Autowire('%env(default::INDEXNOW_KEY)%')]
        private string|null $key = null,
    ) {
    }

    public function __invoke(DataContainer $dc): void
    {
        if (!$this->key || !$dc->id) {
            return;
        }

        $modelClass = Model::getClassFromTable($dc->table);
        $model = class_exists($modelClass) ? $modelClass::findById($dc->id) : null;

        if (null === $model || !$model->published || str_contains((string) $model->robots, 'noindex')) {
            return;
        }

        if ($model instanceof PageModel && 'regular' !== $model->type) {
            return;
        }

        try {
            $url = $this->urlGenerator->generate($model, [], UrlGeneratorInterface::ABSOLUTE_URL);

            $response = $this->httpClient->request('GET', 'https://api.indexnow.org/indexnow', [
                'query' => ['url' => $url, 'key' => $this->key],
                'timeout' => 2,
            ]);

            if ($response->getStatusCode() >= 300) {
                $this->logger->warning(\sprintf('IndexNow rejected "%s" with status %d.', $url, $response->getStatusCode()));
            }
        } catch (\Throwable $e) {
            $this->logger->warning('IndexNow request failed: '.$e->getMessage());
        }
    }
}