<?php declare(strict_types=1);

/**
 * CP Element Counter plugin
 *
 * @package   CraftCPElementCounter
 * @author    IWF Web Solutions <web-solutions@iwf.ch>
 * @copyright Copyright (c) 2024-2026 IWF Web Solutions <web-solutions@iwf.ch>
 * @license   https://github.com/iwf-web/craft-cp-element-counter/blob/main/LICENSE.txt MIT License
 * @link      https://github.com/iwf-web/craft-cp-element-counter
 */

namespace cpelementcounter\services;

use CraftCms\Cms\Asset\Elements\Asset;
use CraftCms\Cms\Cp\RequestedSite;
use CraftCms\Cms\Edition;
use CraftCms\Cms\Element\Contracts\ElementInterface;
use CraftCms\Cms\Element\ElementIndexes;
use CraftCms\Cms\Element\ElementSources;
use CraftCms\Cms\Entry\Elements\Entry;
use CraftCms\Cms\Support\Facades\Sites;
use CraftCms\Cms\Support\Str;
use CraftCms\Cms\Support\Url;
use CraftCms\Cms\User\Elements\User;
use Illuminate\Support\Facades\Gate;

use function CraftCms\Cms\currentUser;

/**
 * Counts the elements of every source the CP navigation lists.
 *
 * @author    Stefan Friedrich
 *
 * @since     6.0.0
 */
class CountService
{
    /**
     * Element types with sources in the CP nav, and the index path their
     * sources fall back to when they have no path of their own.
     *
     * @var array<class-string<ElementInterface>, string>
     */
    private const ELEMENT_TYPES = [
        Entry::class => 'content/entries',
        Asset::class => 'assets',
        User::class => 'users',
    ];

    /**
     * @var null|array<string, int>
     */
    private ?array $counts = null;

    public function __construct(
        private readonly ElementSources $elementSources,
        private readonly ElementIndexes $elementIndexes,
        private readonly RequestedSite $requestedSite,
    ) {}

    /**
     * Returns the element counts keyed by the CP URL the nav links each source by.
     *
     * @return array<string, int>
     */
    public function getCountsByCpUrl(): array
    {
        if ($this->counts !== null) {
            return $this->counts;
        }

        // Shared with every Inertia page, the login screen included.
        if (currentUser() === null) {
            return $this->counts = [];
        }

        $siteId = $this->siteId();
        $counts = [];

        foreach (self::ELEMENT_TYPES as $elementType => $indexPath) {
            if (!$this->isInNav($elementType)) {
                continue;
            }

            foreach ($this->pages($elementType) as $page) {
                $pagePath = $page !== null ? 'content/'.Str::slug($page) : $indexPath;

                $sources = $this->elementSources->getSources(
                    $elementType,
                    context: ElementSources::CONTEXT_NAVIGATION,
                    page: $page,
                    siteId: $siteId,
                );

                foreach ($sources as $source) {
                    if (($source['type'] ?? null) === ElementSources::TYPE_HEADING) {
                        continue;
                    }

                    $key = $source['key'] ?? null;

                    if (!\is_string($key) || $key === '') {
                        continue;
                    }

                    $url = Url::cpUrl($this->sourcePath($elementType, $pagePath, $key, $source, $page));
                    $counts[$url] = $this->count($elementType, $source, $siteId);
                }
            }
        }

        return $this->counts = $counts;
    }

    /**
     * Mirrors the checks the core navigation does before listing an element
     * type's sources. Entry and asset sources are already limited to the
     * sections and volumes the user may access.
     *
     * @param class-string<ElementInterface> $elementType
     */
    private function isInNav(string $elementType): bool
    {
        if ($elementType === User::class) {
            return Edition::get() !== Edition::Solo && Gate::check('viewUsers');
        }

        return true;
    }

    /**
     * Entry sources can be split into several nav pages; every other element
     * type has a single index.
     *
     * @param class-string<ElementInterface> $elementType
     *
     * @return list<null|string>
     */
    private function pages(string $elementType): array
    {
        if ($elementType !== Entry::class) {
            return [null];
        }

        $pages = $this->elementSources->getPages(Entry::class, ElementSources::CONTEXT_NAVIGATION);

        return $pages->isNotEmpty() ? array_values($pages->all()) : [null];
    }

    /**
     * Mirrors how the core navigation links a source.
     *
     * @param class-string<ElementInterface> $elementType
     * @param array<string, mixed>           $source
     */
    private function sourcePath(string $elementType, string $indexPath, string $key, array $source, ?string $page): string
    {
        return $elementType::sourceCpUri($source, $page)
            ?? ($key === '*' ? $indexPath : $indexPath.'?source='.rawurlencode($key));
    }

    /**
     * Counts the elements of a source the way its index lists them, regardless of status.
     *
     * @param class-string<ElementInterface> $elementType
     * @param array<string, mixed>           $source
     */
    private function count(string $elementType, array $source, ?int $siteId): int
    {
        $query = $this->elementIndexes->buildQueryState($elementType, $source)['query']
            ->status(null)
        ;

        if ($siteId !== null) {
            $query->siteId($siteId);
        }

        return (int) $query->count();
    }

    /**
     * The site the CP is working with, or `null` on a single-site install.
     */
    private function siteId(): ?int
    {
        if (!Sites::isMultiSite()) {
            return null;
        }

        return $this->requestedSite->get()?->id;
    }
}
